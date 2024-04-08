FROM golang:1.21.9-alpine3.19 AS builder

WORKDIR /app

RUN adduser -S appuser

COPY go.mod go.sum  ./

RUN --mount=type=cache,target=/go/pkg/mod \
    --mount=type=cache,target=/root/.cache/go-build \
    go mod download

COPY .. .

RUN go build -mod=mod -o server cmd/api/*

FROM alpine:latest

COPY --from=builder /etc/passwd /etc/passwd
USER appuser

LABEL version="1.0.0"
LABEL author="jwambugu"

WORKDIR /app

COPY --from=builder /app/server .

EXPOSE 8000

CMD ["/app/server"]