# TRX-2: Xendit Service Layer

## Tujuan
Membuat service class untuk berkomunikasi dengan Xendit API v2 (Invoices).

## File Baru
- `app/Services/XenditService.php`
- `config/xendit.php`

## File Diubah
- `.env` — tambah konfigurasi Xendit

## Detail Implementasi

### 1. Config: `config/xendit.php`
```php
<?php
return [
    'secret_key' => env('XENDIT_SECRET_KEY'),
    'public_key' => env('XENDIT_PUBLIC_KEY'),
    'webhook_verification_token' => env('XENDIT_WEBHOOK_VERIFICATION_TOKEN'),
    'is_production' => env('XENDIT_PRODUCTION', false),
];
```

### 2. .env additions
```
XENDIT_SECRET_KEY=xnd_development_CxiNE4h1Reku1rPgllpU0FXAwxf6cPDya2MMpZ4hrzqHAIz4jGtbjgNkL5jg8
XENDIT_PUBLIC_KEY=xnd_public_development_lnxajFaXd3mzpAgetAnXUH9zWA2_9ZwEdMMmJieT1hHEZM_WfuICCZu0sEoZOLL
XENDIT_PRODUCTION=false
XENDIT_WEBHOOK_VERIFICATION_TOKEN=
```

### 3. XenditService Methods

#### `createInvoice(Order $order): array`
Panggil Xendit API untuk membuat invoice.

**Endpoint**: `POST https://api.xendit.co/v2/invoices`

**Request Headers**:
```
Authorization: Basic base64(secret_key + :)
Content-Type: application/json
```

**Request Body**:
```json
{
    "external_id": "ORDER-{order.id}-{timestamp}",
    "amount": {order.total},
    "description": "Pembayaran ZLM.ID - {order.order_number}",
    "customer": {
        "given_names": "{order.user.name}",
        "email": "{order.user.email}"
    },
    "customer_notification_preference": {
        "invoice_paid": ["email"],
        "invoice_expired": ["email"]
    },
    "success_redirect_url": "{route('orders.xendit.callback', order)}?status=success",
    "failure_redirect_url": "{route('orders.xendit.callback', order)}?status=failed",
    "currency": "IDR"
}
```

**Response Handling**:
- Success (200): return `['id', 'invoice_url', 'expiry_date', 'status']`
- Failure: throw exception dengan pesan error

#### `getInvoiceStatus(string $invoiceId): array`
Panggil Xendit API untuk cek status invoice.

**Endpoint**: `GET https://api.xendit.co/v2/invoices/{invoiceId}`

**Response**: status invoice (`PAID`, `EXPIRED`, `PENDING`, etc.)

#### `handleWebhook(array $payload): void`
Proses callback webhook dari Xendit.

**Logic**:
1. Verifikasi token (dari header `x-callback-token` atau dari parameter)
2. Cari order berdasarkan `external_id`
3. Update status berdasarkan event:
   - `invoice.paid` → `payment_status = paid`, `status = processing`, `paid_at = now()`
   - `invoice.expired` → `payment_status = expired`
   - `invoice.failed` → `payment_status = failed`

### Error Handling
- Wrapping dengan try-catch di controller
- Log error menggunakan `Log::error()`
- Return response JSON dengan status code sesuai

### Definisi Selesai
- [ ] `config/xendit.php` bisa diakses via `config('xendit.secret_key')`
- [ ] `XenditService::createInvoice()` mengembalikan data invoice
- [ ] `XenditService::getInvoiceStatus()` mengembalikan status
- [ ] `XenditService::handleWebhook()` memproses webhook dengan benar
- [ ] Error handling untuk API failure (timeout, invalid response)
