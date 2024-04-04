package main

import (
	"context"
	"encoding/json"
	"fmt"
	"github.com/skip2/go-qrcode"
	"log"
	"net/http"
	"net/url"
	"os"
	"path/filepath"
	"payment-api/internal/facilitator"
	"strconv"
)

const (
	addr           = ":8000"
	baseURL        = "http://localhost" + addr
	consumerKey    = ""
	consumerSecret = ""
)

type generateQRCodeReq struct {
	Amount      uint32 `json:"amount,omitempty"`
	Description string `json:"description,omitempty"`
	HouseID     uint   `json:"house_id,omitempty"`
	PhoneNumber uint64 `json:"phone_number,omitempty"`
}

type generateQRCodeRes struct {
	QrCode string `json:"qr_code,omitempty"`
}

func sendResponse(w http.ResponseWriter, status int, payload []byte) {
	if status == http.StatusInternalServerError {
		payload = []byte(http.StatusText(status))
	}

	w.WriteHeader(status)
	_, _ = w.Write(payload)
}

func paymentHandler(w http.ResponseWriter, r *http.Request) {
	w.Header().Set("Content-Type", "application/json")

	switch r.Method {
	case http.MethodPost:
		var req *generateQRCodeReq
		if err := json.NewDecoder(r.Body).Decode(&req); err != nil {
			log.Printf("decode request: %v\n", err)

			sendResponse(w, http.StatusInternalServerError, nil)
			return
		}

		if req.Description == "" {
			req.Description = "Rent payment"
		}

		var (
			amountStr      = strconv.Itoa(int(req.Amount))
			houseIDStr     = strconv.Itoa(int(req.HouseID))
			phoneNumberStr = strconv.Itoa(int(req.PhoneNumber))
		)

		params := url.Values{}
		params.Add("amount", amountStr)
		params.Add("description", req.Description)
		params.Add("house", houseIDStr)
		params.Add("phone-number", phoneNumberStr)

		paymentURL := baseURL + "?" + params.Encode()
		log.Println(paymentURL)

		wd, err := os.Getwd()
		if err != nil {
			log.Printf("get wd: %v\n", err)

			w.WriteHeader(http.StatusInternalServerError)
			return
		}

		filename := fmt.Sprintf("%s-%s-%s.png", houseIDStr, amountStr, phoneNumberStr)
		filename = filepath.Join(wd, "storage", "qrcodes", filename)

		err = qrcode.WriteFile(paymentURL, qrcode.High, 256, filename)
		if err != nil {
			log.Printf("generate qr: %v\n", err)

			sendResponse(w, http.StatusInternalServerError, nil)
			return
		}

		resp := &generateQRCodeRes{QrCode: filename}
		payload, err := json.Marshal(resp)
		if err != nil {
			log.Printf("marshal response: %v\n", err)

			sendResponse(w, http.StatusInternalServerError, nil)
			return
		}

		sendResponse(w, http.StatusOK, payload)
	case http.MethodGet:
		if r.URL.Path != "/" {
			return
		}

		query := r.URL.Query()

		amount, err := strconv.Atoi(query.Get("amount"))
		if err != nil {
			log.Printf("convert amount: %v\n", err)
			sendResponse(w, http.StatusInternalServerError, nil)
			return
		}

		phoneNumber, err := strconv.Atoi(query.Get("phone-number"))
		if err != nil {
			log.Printf("convert phone number: %v\n", err)
			sendResponse(w, http.StatusInternalServerError, nil)
			return
		}

		var (
			client        = &http.Client{}
			mpesaClient   = facilitator.NewMpesaClient(client, consumerKey, consumerSecret)
			ctx           = context.Background()
			reqPaymentReq = &facilitator.RequestPaymentReq{
				Amount:      uint32(amount),
				Description: query.Get("description"),
				From:        uint(phoneNumber),
				ID:          query.Get("house"),
			}
		)

		resp, err := mpesaClient.RequestPayment(ctx, reqPaymentReq)
		if err != nil {
			log.Printf("request payment: %v\n", err)
			sendResponse(w, http.StatusInternalServerError, nil)
			return
		}

		payload, _ := json.Marshal(resp)
		sendResponse(w, http.StatusOK, payload)
	default:
		w.WriteHeader(http.StatusMethodNotAllowed)
	}

}

func main() {
	mux := http.NewServeMux()
	mux.HandleFunc("/", paymentHandler)

	log.Printf("server running on port: %v", addr)

	if err := http.ListenAndServe(addr, mux); err != nil {
		log.Fatalf("start server: %v", err)
	}
}
