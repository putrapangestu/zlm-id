# TRX-5: Xendit Webhook Handler

## Tujuan
Menerima dan memproses notifikasi webhook dari Xendit untuk update status pembayaran otomatis.

## File Baru
- `app/Http/Controllers/XenditWebhookController.php`

## File Diubah
- `routes/web.php` — exclude CSRF untuk route webhook
- `app/Http/Middleware/VerifyCsrfToken.php` atau `bootstrap/app.php` (Laravel 11) — tambah pengecualian

## Route
```php
Route::post('/webhooks/xendit', [XenditWebhookController::class, 'handle'])
    ->name('webhooks.xendit')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
```

## Detail Implementasi

### 1. XenditWebhookController
```php
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    public function handle(Request $request, XenditService $xendit)
    {
        // Verify callback token
        $token = $request->header('x-callback-token');
        $expectedToken = config('xendit.webhook_verification_token');
        
        if ($expectedToken && $token !== $expectedToken) {
            Log::warning('Xendit webhook: invalid callback token', [
                'received' => $token,
                'expected' => $expectedToken,
            ]);
            return response()->json(['error' => 'Invalid token'], 401);
        }
        
        $payload = $request->all();
        $event = $payload['event'] ?? $payload['status'] ?? null;
        $externalId = $payload['external_id'] ?? ($payload['data']['external_id'] ?? null);
        
        Log::info('Xendit webhook received', [
            'event' => $event,
            'external_id' => $externalId,
        ]);
        
        if (!$externalId) {
            return response()->json(['error' => 'Missing external_id'], 400);
        }
        
        // Parse order ID dari external_id (format: ORDER-{id}-{timestamp})
        $parts = explode('-', $externalId);
        $orderId = $parts[1] ?? null;
        
        if (!$orderId) {
            return response()->json(['error' => 'Invalid external_id format'], 400);
        }
        
        $order = Order::find($orderId);
        
        if (!$order) {
            Log::warning('Xendit webhook: order not found', ['external_id' => $externalId]);
            return response()->json(['error' => 'Order not found'], 404);
        }
        
        // Process based on event/status
        $status = $payload['status'] ?? null;
        
        switch ($status) {
            case 'PAID':
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'paid_at' => now(),
                ]);
                Log::info("Order {$order->order_number} marked as PAID via webhook");
                break;
                
            case 'EXPIRED':
                $order->update([
                    'payment_status' => 'expired',
                ]);
                Log::info("Order {$order->order_number} marked as EXPIRED via webhook");
                break;
                
            case 'FAILED':
                $order->update([
                    'payment_status' => 'failed',
                ]);
                Log::info("Order {$order->order_number} marked as FAILED via webhook");
                break;
                
            default:
                Log::info("Xendit webhook: unhandled status", ['status' => $status]);
        }
        
        return response()->json(['success' => true]);
    }
}
```

### 2. CSRF Exception (Laravel 11 — `bootstrap/app.php`)
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->validateCsrfTokens(except: [
        '/webhooks/xendit',
    ]);
})
```

### Definisi Selesai
- [ ] Route webhook bisa diakses POST tanpa CSRF token
- [ ] Callback token diverifikasi (jika dikonfigurasi)
- [ ] Event PAID mengupdate payment_status = paid dan status = processing
- [ ] Event EXPIRED mengupdate payment_status = expired
- [ ] Event FAILED mengupdate payment_status = failed
- [ ] Logging untuk semua event webhook
- [ ] Return response JSON 200 untuk sukses
