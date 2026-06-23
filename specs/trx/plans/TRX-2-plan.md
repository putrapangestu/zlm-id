# TRX-2 Implementation Plan: Xendit Service Layer

## Effort: Medium

## File Structure
| File | Tipe | Keterangan |
|------|------|------------|
| `config/xendit.php` | BARU | Konfigurasi API key Xendit |
| `app/Services/XenditService.php` | BARU | Service class untuk komunikasi Xendit API |
| `.env` | DIUBAH | Tambah XENDIT_* environment variables |

## Implementation Order

### Step 1: Buat `.env` additions
Tambah ke `.env`:
```
XENDIT_SECRET_KEY=xnd_development_CxiNE4h1Reku1rPgllpU0FXAwxf6cPDya2MMpZ4hrzqHAIz4jGtbjgNkL5jg8
XENDIT_PUBLIC_KEY=xnd_public_development_lnxajFaXd3mzpAgetAnXUH9zWA2_9ZwEdMMmJieT1hHEZM_WfuICCZu0sEoZOLL
XENDIT_PRODUCTION=false
XENDIT_WEBHOOK_VERIFICATION_TOKEN=
```

### Step 2: Buat `config/xendit.php`
- File konfigurasi standalone
- Keys: `secret_key`, `public_key`, `webhook_verification_token`, `is_production`
- Semua ambil dari env

### Step 3: Buat `app/Services/XenditService.php`
- Namespace: `App\Services`
- Methods:
  - `createInvoice(Order $order): array` — POST ke `/v2/invoices`
    - Basic Auth dengan base64(secret_key . ":")
    - Body: external_id, amount, description, customer, redirect URLs
    - Return: `['id', 'invoice_url', 'expiry_date', 'status']`
  - `getInvoiceStatus(string $invoiceId): array` — GET `/v2/invoices/{invoiceId}`
    - Return full response dengan status invoice
  - `handleWebhook(array $payload): void` — proses callback
    - Parse external_id → cari Order → update status
- Error handling: Log::error, throw Exception

### Step 4: Register Service (opsional)
- Tidak perlu binding di AppServiceProvider — cukup `app(XenditService::class)` via auto-resolution

## Dependencies Internal
- Config file harus dibuat sebelum service (service baca config)
- Order model harus sudah punya kolom xendit_* (TRX-1)

## API / Interface
```php
namespace App\Services;

class XenditService {
    public function createInvoice(Order $order): array
    public function getInvoiceStatus(string $invoiceId): array
    public function handleWebhook(array $payload): void
}
```

## Data Flow
```
OrderController/TransactionController
    ↓ createInvoice($order)
XenditService → HTTP POST → Xendit API
    ↓ response {id, invoice_url, expiry_date}
Order update: xendit_invoice_id, xendit_invoice_url, xendit_expiry
    ↓ redirect user ke invoice_url
User bayar di Xendit
    ↓ callback/webhook
XenditService::handleWebhook() → Update payment_status
```

## Test Plan
- Test config: `config('xendit.secret_key')` mengembalikan value dari .env
- Test createInvoice: mock HTTP call → return array dengan key yang diharapkan
- Test getInvoiceStatus: mock HTTP call → return status
- Test error handling: mock failed HTTP → exception terthrow
- Integration: pastikan bisa create invoice (butuh Xendit API key real/staging)
