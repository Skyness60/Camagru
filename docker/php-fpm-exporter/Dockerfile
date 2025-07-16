FROM golang:1.20-alpine AS builder

RUN apk add --no-cache git ca-certificates

RUN git clone https://github.com/hipages/php-fpm_exporter.git /src

WORKDIR /src

RUN go build -o php-fpm-exporter .
