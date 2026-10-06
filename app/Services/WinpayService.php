<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use JsonException;
use RuntimeException;

class WinpayService
{
    public function createQrisPayment(Order $order): array
    {
        $payload = [
            'partnerReferenceNo' => $order->order_number,
            'amount' => [
                'value' => (string) $order->total,
                'currency' => 'IDR',
            ],
            'validityPeriod' => now()->addMinutes($this->expiryMinutes())->toIso8601String(),
            'additionalInfo' => [
                'isStatic' => false,
            ],
        ];

        if ($terminalId = config('winpay.terminal_id')) {
            $payload['terminalId'] = $terminalId;
        }

        $data = $this->send('/v1.0/qr/qr-mpm-generate', $payload, '2004700');
        $qrUrl = $data['qrUrl'] ?? null;
        $qrContent = $data['qrContent'] ?? null;

        if (
            (! is_string($qrUrl) || $qrUrl === '')
            && (! is_string($qrContent) || $qrContent === '')
        ) {
            throw new RuntimeException('Respons Winpay QRIS tidak berisi QR URL atau konten QR.');
        }

        return [
            'reference' => $data['partnerReferenceNo'] ?? $order->order_number,
            'contract_id' => $data['additionalInfo']['contractId'] ?? null,
            'qr_url' => $qrUrl,
            'qr_content' => $qrContent,
            'virtual_account_no' => null,
            'channel' => 'QRIS',
            'expiry' => $data['additionalInfo']['expiredAt'] ?? $payload['validityPeriod'],
        ];
    }

    public function createVirtualAccount(Order $order, string $channel): array
    {
        $payload = [
            'virtualAccountName' => mb_substr('ZLM '.$order->order_number, 0, 24),
            'trxId' => $order->order_number,
            'totalAmount' => [
                'value' => (string) $order->total,
                'currency' => 'IDR',
            ],
            'virtualAccountTrxType' => 'c',
            'expiredDate' => now()->addMinutes($this->expiryMinutes())->toIso8601String(),
            'additionalInfo' => [
                'channel' => $channel,
            ],
        ];

        $data = $this->send('/v1.0/transfer-va/create-va', $payload, '2002700');
        $virtualAccount = $data['virtualAccountData'] ?? null;
        $number = is_array($virtualAccount) ? ($virtualAccount['virtualAccountNo'] ?? null) : null;

        if (! is_string($number) || trim($number) === '') {
            throw new RuntimeException('Respons Winpay VA tidak berisi nomor Virtual Account.');
        }

        return [
            'reference' => $virtualAccount['trxId'] ?? $order->order_number,
            'contract_id' => $virtualAccount['additionalInfo']['contractId'] ?? null,
            'qr_url' => null,
            'qr_content' => null,
            'virtual_account_no' => trim($number),
            'channel' => $channel,
            'expiry' => $virtualAccount['expiredDate'] ?? $payload['expiredDate'],
        ];
    }

    public function verifyCallback(array $payload, array $headers, string $path): bool
    {
        $partnerId = (string) config('winpay.partner_id', '');
        $receivedPartnerId = $headers['partner_id'] ?? '';
        $timestamp = $headers['timestamp'] ?? '';
        $signature = $headers['signature'] ?? '';

        if (
            $partnerId === ''
            || ! is_string($receivedPartnerId)
            || ! hash_equals($partnerId, $receivedPartnerId)
            || ! is_string($timestamp)
            || $timestamp === ''
            || ! is_string($signature)
            || $signature === ''
        ) {
            return false;
        }

        $publicKeyPath = config('winpay.public_key_path');
        if (! is_string($publicKeyPath) || $publicKeyPath === '' || ! is_file($publicKeyPath)) {
            throw new RuntimeException('Winpay public key belum dikonfigurasi atau file tidak ditemukan.');
        }

        $publicKeyContents = file_get_contents($publicKeyPath);
        if ($publicKeyContents === false) {
            throw new RuntimeException('Winpay public key tidak dapat dibaca.');
        }

        $publicKey = openssl_pkey_get_public($publicKeyContents);
        if ($publicKey === false) {
            throw new RuntimeException('Format Winpay public key tidak valid.');
        }

        $body = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        $stringToSign = $this->stringToSign('POST', $path, $body, $timestamp);
        $decodedSignature = base64_decode($signature, true);

        return $decodedSignature !== false
            && openssl_verify($stringToSign, $decodedSignature, $publicKey, OPENSSL_ALGO_SHA256) === 1;
    }

    private function send(string $path, array $payload, string $expectedResponseCode): array
    {
        $partnerId = (string) config('winpay.partner_id', '');
        $privateKeyPath = config('winpay.private_key_path');
        $baseUrl = rtrim((string) config('winpay.base_url', ''), '/');

        if ($partnerId === '' || ! is_string($privateKeyPath) || $privateKeyPath === '' || ! is_file($privateKeyPath)) {
            throw new RuntimeException('Konfigurasi Winpay belum lengkap (partner ID atau private key).');
        }

        if ($baseUrl === '') {
            throw new RuntimeException('Winpay base URL belum dikonfigurasi.');
        }

        $privateKeyContents = file_get_contents($privateKeyPath);
        if ($privateKeyContents === false) {
            throw new RuntimeException('Winpay private key tidak dapat dibaca.');
        }

        $privateKey = openssl_pkey_get_private($privateKeyContents);
        if ($privateKey === false) {
            throw new RuntimeException('Format Winpay private key tidak valid.');
        }

        try {
            $body = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException('Payload Winpay tidak dapat dienkode.', previous: $exception);
        }

        $timestamp = now()->toIso8601String();
        $externalId = Str::uuid()->toString();
        $stringToSign = $this->stringToSign('POST', $path, $body, $timestamp);

        if (! openssl_sign($stringToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            throw new RuntimeException('Gagal membuat signature request Winpay.');
        }

        $response = Http::timeout((int) config('winpay.timeout', 15))
            ->withHeaders([
                'X-TIMESTAMP' => $timestamp,
                'X-SIGNATURE' => base64_encode($signature),
                'X-PARTNER-ID' => $partnerId,
                'X-EXTERNAL-ID' => $externalId,
                'CHANNEL-ID' => (string) config('winpay.channel_id', 'WEB'),
            ])
            ->withBody($body, 'application/json')
            ->post($baseUrl.$path);

        return $this->parseResponse($response, $path, $expectedResponseCode);
    }

    private function parseResponse(Response $response, string $path, string $expectedResponseCode): array
    {
        $data = $response->json();
        if (
            $response->failed()
            || ! is_array($data)
            || ($data['responseCode'] ?? null) !== $expectedResponseCode
        ) {
            Log::error('Winpay API request failed', [
                'path' => $path,
                'http_status' => $response->status(),
                'response' => $response->json() ?? $response->body(),
            ]);

            throw new RuntimeException('Winpay gagal memproses pembayaran: '.($data['responseMessage'] ?? $response->reason()));
        }

        return $data;
    }

    private function stringToSign(string $method, string $path, string $body, string $timestamp): string
    {
        $bodyHash = strtolower(hash('sha256', $body));

        return implode(':', [$method, $path, $bodyHash, $timestamp]);
    }

    private function expiryMinutes(): int
    {
        return max(2, min(129600, (int) config('winpay.expiry_minutes', 60)));
    }
}
