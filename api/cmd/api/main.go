package main

import (
	"encoding/json"
	"log"
	"net/http"
)

type generateQRCodeReq struct {
	PhoneNumber uint   `json:"phone_number,omitempty"`
	Amount      uint32 `json:"amount,omitempty"`
	Description string `json:"description,omitempty"`
}

func generateQRCode(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodPost {
		w.WriteHeader(http.StatusMethodNotAllowed)
		return
	}

	var req *generateQRCodeReq
	if err := json.NewDecoder(r.Body).Decode(&req); err != nil {
		w.WriteHeader(http.StatusInternalServerError)
		return
	}

	//TODO: generate QR code here
}

func main() {
	mux := http.NewServeMux()
	mux.HandleFunc("/", generateQRCode)

	err := http.ListenAndServe(":8000", mux)
	if err != nil {
		log.Fatalf("start server: %v", err)
	}
}
