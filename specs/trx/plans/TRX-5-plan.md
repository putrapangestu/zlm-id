# TRX-5 Implementation Plan: Xendit Webhook Handler

## Effort: Small

## File Structure
| File | Tipe | Keterangan |
|------|------|------------|
| `app/Http/Controllers/XenditWebhookController.php` | BARU | Controller untuk menerima webhook Xendit |
| `routes/web.php` | DIUBAH | Tambah route webhook (tanpa CSRF) |
| `bootstrap/app.php` | DIUBAH | Tambah CSRF exception |

## Implementation Order

### Step 1: Buat `XenditWebhookController`
- Method: `handle(Request $request, XenditService $xendit)`
- **Verifikasi Token**:
  - Ambil header `x-callback-token`
  - Bandingkan dengan `config('xendit.webhook_verification_token')`
  - Jika mismatch → Log warning + return 401
- **Parse Payload**:
  - Ambil `external_id` dari payload (format: `ORDER-{id}-{timestamp}`)
  - Parse order ID (explode `-`, ambil index 1)
  - Cari Order via `Order::find($orderId)`
  - Jika not found → Log warning + return 404
- **Proses Status**:
  - `PAID` → `payment_status = paid`, `status = processing`, `paid_at = now()`
  - `EXPIRED` → `payment_status = expired`
  - `FAILED` → `payment_status = failed`
  - Default → Log unhandled status
- Return JSON 200 `{'success': true}`

### Step 2: Update `routes/web.php`
```php
Route::post('/webhooks/xendit', [XenditWebhookController::class, 'handle'])
    ->name('webhooks.xendit')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
```
Letakkan di **luar** grup middleware auth — webhook bisa diakses publik.

### Step 3: Update `bootstrap/app.php`
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->validateCsrfTokens(except: [
        '/webhooks/xendit',
    ]);
    
    // existing alias definitions...
})
```

## Dependencies Internal
- TRX-2 (XenditService) — untuk constant/reference (opsional, handleWebhook di spec ada di service)
- Catatan: Spek TRX-2 menyebut `handleWebhook()` di XenditService, tapi spek TRX-5 implementasi langsung di controller. Pilih implementasi di controller saja untuk simplicity, atau delegasikan ke service. **Keputusan**: logika update status tulis langsung di controller (lebih sederhana), XenditService.handleWebhook tidak perlu dibuat.

## API / Interface
```php
namespace App\Http\Controllers;

class XenditWebhookController {
    public function handle(Request $request, XenditService $xendit): JsonResponse
}
```

**Endpoint**: `POST /webhooks/xendit`
- No CSRF protection
- No Auth
- Header: `x-callback-token` (optional, if configured)
- Body: Xendit callback payload

## Data Flow
```
Xendit Server
    ↓ POST /webhooks/xendit (PAID/EXPIRED/FAILED)
XenditWebhookController::handle()
    ↓ Verify callback token
    ↓ Parse external_id → cari Order
    ↓ Update payment_status + status
Return JSON 200
```

## Test Plan
- Unit test: webhook dengan token salah → 401
- Unit test: webhook dengan external_id invalid → 400
- Unit test: webhook dengan order tidak ditemukan → 404
- Unit test: event PAID → order payment_status = paid, status = processing
- Unit test: event EXPIRED → payment_status = expired
- Unit test: event FAILED → payment_status = failed
- Integration test: POST /webhooks/xendit tanpa CSRF token → sukses (200)
- Integration test: POST dengan CSRF token biasa → tetap sukses (tanpa middleware)
