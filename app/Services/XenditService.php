<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XenditService
{
    protected string $baseUrl = 'https://api.xendit.co';
    protected string $secretKey;

    public function __construct()
    {
        $this->secretKey = config('xendit.secret_key');
    }

    protected function authHeader(): array
    {
        $token = base64_encode($this->secretKey . ':');
        return ['Authorization' => 'Basic ' . $token];
    }

    public function createInvoice(Order $order): array
    {
        $response = Http::withHeaders($this->authHeader())->post($this->baseUrl . '/v2/invoices', [
            'external_id' => (string) $order->id,
            'amount' => (float) $order->total,
            'description' => 'Pembayaran Order #' . $order->order_number,
            'customer' => [
                'given_names' => $order->user->name ?? 'Customer',
                'email' => $order->user->email ?? '',
            ],
            'customer_notification_preference' => [
                'invoice_paid' => ['email', 'whatsapp'],
            ],
            'success_redirect_url' => route('orders.confirmation', $order),
            'failure_redirect_url' => route('orders.checkout'),
        ]);

        if ($response->failed()) {
            Log::error('Xendit createInvoice failed', [
                'order_id' => $order->id,
                'response' => $response->body(),
            ]);
            throw new \Exception('Gagal membuat invoice Xendit: ' . $response->body());
        }

        $data = $response->json();

        return [
            'id' => $data['id'],
            'invoice_url' => $data['invoice_url'],
            'expiry_date' => $data['expiry_date'],
            'status' => $data['status'],
        ];
    }

    public function getInvoiceStatus(string $invoiceId): array
    {
        $response = Http::withHeaders($this->authHeader())->get($this->baseUrl . '/v2/invoices/' . $invoiceId);

        if ($response->failed()) {
            Log::error('Xendit getInvoiceStatus failed', [
                'invoice_id' => $invoiceId,
                'response' => $response->body(),
            ]);
            throw new \Exception('Gagal mengecek status invoice Xendit');
        }

        return $response->json();
    }

    public function handleWebhook(array $payload): void
    {
        $externalId = $payload['external_id'] ?? null;
        if (! $externalId) {
            return;
        }

        $order = Order::find($externalId);
        if (! $order) {
            Log::warning('Xendit webhook: order not found', ['external_id' => $externalId]);
            return;
        }

        $status = $payload['status'] ?? '';
        if ($status === 'PAID') {
            $order->update([
                'payment_status' => 'paid',
                'paid_at' => now(),
                'xendit_invoice_id' => $payload['id'] ?? $order->xendit_invoice_id,
            ]);
        } elseif (in_array($status, ['EXPIRED', 'FAILED'])) {
            $order->update(['payment_status' => 'failed']);
        }

        Log::info('Xendit webhook processed', [
            'order_id' => $order->id,
            'status' => $status,
        ]);
    }
}
