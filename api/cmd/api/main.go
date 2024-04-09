package main

import (
	"context"
	"encoding/json"
	"errors"
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
	addr    = ":8000"
	baseURL = "http://localhost" + addr
	//baseURL        = "https://b95b-196-202-217-217.ngrok-free.app"
	consumerKey    = "bRDPwQlV8jmi9YAhYFjWywVslIMWK6AfDnGQu1Kv12NiQdVe"
	consumerSecret = "tvXhhA6WCw8pPARhpUyBjEFIs7rA8wt7GVG0Yls1Zsb7lgGeGC0cqcRuWNaY7LWM"
)

var uploadPath = "storage"

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

		filename := fmt.Sprintf("%s-%s-%s.png", houseIDStr, amountStr, phoneNumberStr)
		path := filepath.Join(uploadPath, filename)

		err := qrcode.WriteFile(paymentURL, qrcode.High, 256, path)
		if err != nil {
			log.Printf("generate qr: %v\n", err)

			sendResponse(w, http.StatusInternalServerError, nil)
			return
		}

		var (
			fileURL = baseURL + "/static/" + filename
			resp    = &generateQRCodeRes{QrCode: fileURL}
		)

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
	if _, err := os.Stat(uploadPath); err != nil {
		if errors.Is(err, os.ErrNotExist) {
			if err = os.MkdirAll(uploadPath, os.ModePerm); err != nil {
				log.Panicf("mkdir: %v\n", err)
			}
		}
	}

	var (
		mux = http.NewServeMux()
		fs  = http.FileServer(http.Dir(uploadPath))
	)

	mux.Handle("/static/", http.StripPrefix("/static", fs))
	mux.HandleFunc("/", paymentHandler)

	log.Printf("server running on port: %v", addr)

	if err := http.ListenAndServe(addr, mux); err != nil {
		log.Fatalf("start server: %v", err)
	}
}
