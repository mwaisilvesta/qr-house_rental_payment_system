// Package facilitator facilitates making payment request using different providers.
// This code is used for demo purposes and should not be used in production as is.
package facilitator

import (
	"context"
	"fmt"
	"github.com/jwambugu/mpesa-golang-sdk"
)

type RequestPaymentReq struct {
	Amount      uint32 `json:"amount,omitempty"`
	Description string `json:"description,omitempty"`
	From        uint   `json:"from,omitempty"`
	ID          string `json:"id,omitempty"`
}

type RequestPaymentResp struct {
	ExternalID string `json:"external_id,omitempty"`
}

type TransactionStatus uint

const (
	SuccessTransactionStatus TransactionStatus = iota + 1
	FailedTransactionStatus
)

type TransactionStatusResp struct {
	Status TransactionStatus `json:"status,omitempty"`
}

type Facilitate interface {
	RequestPayment(ctx context.Context, req *RequestPaymentReq) (*RequestPaymentResp, error)
	TransactionStatus(ctx context.Context, id string) (*TransactionStatusResp, error)
}

type mpesaClient struct {
	mpesaApp *mpesa.Mpesa

	passkey   string
	shortcode uint
}

func (m *mpesaClient) RequestPayment(ctx context.Context, req *RequestPaymentReq) (*RequestPaymentResp, error) {
	stkReq := mpesa.STKPushRequest{
		BusinessShortCode: m.shortcode,
		TransactionType:   mpesa.CustomerPayBillOnlineTransactionType,
		Amount:            uint(req.Amount),
		PartyA:            req.From,
		PartyB:            m.shortcode,
		PhoneNumber:       uint64(req.From),
		CallBackURL:       "https://webhook.site/c1181493-1dd2-42de-a2e5-e4fc74d8a529",
		AccountReference:  req.ID,
		TransactionDesc:   req.Description,
	}

	resp, err := m.mpesaApp.STKPush(ctx, m.passkey, stkReq)
	if err != nil {
		return nil, fmt.Errorf("request payment: %v", err)
	}

	return &RequestPaymentResp{
		ExternalID: resp.CheckoutRequestID,
	}, nil
}

func (m *mpesaClient) TransactionStatus(ctx context.Context, id string) (*TransactionStatusResp, error) {
	stkQueryReq := mpesa.STKQueryRequest{
		BusinessShortCode: m.shortcode,
		CheckoutRequestID: id,
	}

	resp, err := m.mpesaApp.STKQuery(ctx, m.passkey, stkQueryReq)
	if err != nil {
		return nil, fmt.Errorf("transaction status: %v", err)
	}

	transactionStatus := SuccessTransactionStatus
	if resp.ResultCode != "0" {
		transactionStatus = FailedTransactionStatus
	}

	return &TransactionStatusResp{Status: transactionStatus}, nil

}

func NewMpesaClient(httpClient mpesa.HttpClient, key string, secret string) Facilitate {
	mpesaApp := mpesa.NewApp(httpClient, key, secret, mpesa.EnvironmentSandbox)
	return &mpesaClient{
		mpesaApp:  mpesaApp,
		passkey:   "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919",
		shortcode: 174379,
	}
}
