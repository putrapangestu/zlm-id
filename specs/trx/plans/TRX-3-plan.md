# TRX-3 Implementation Plan: User Checkout — Xendit + RajaOngkir

## Effort: Large

## File Structure
| File | Tipe | Keterangan |
|------|------|------------|
| `app/Http/Controllers/OrderController.php` | DIUBAH | Major: placeOrder + xenditCallback methods |
| `resources/views/orders/checkout.blade.php` | DIUBAH | Major: RajaOngkir integration + Xendit forced |
| `resources/views/orders/confirmation.blade.php` | DIUBAH | Minor: tampilkan info shipping |
| `routes/web.php` | DIUBAH | Tambah route xendit callback |

## Implementation Order

### Step 1: Update `routes/web.php`
Di dalam grup `middleware ['auth', 'verified']`, tambah:
```php
Route::get('/orders/{order}/xendit/callback', [OrderController::class, 'xenditCallback'])
    ->name('orders.xendit.callback');
```
Route shipping sudah ditambah di TRX-2B.

### Step 2: Update `OrderController@placeOrder` — Validasi RajaOngkir
- Tambah validasi baru untuk shipping: `shipping_cost`, `shipping_courier`, `shipping_service`, `shipping_etd`, `shipping_city_id`, `shipping_city_name`, `shipping_province_name`
- Ganti validasi `shipping_city` → `shipping_city_name`, `shipping_province` → `shipping_province_name`
- Hitung `$total = $subtotal + $tax + $shippingCost`
- Set `payment_method` jadi `'xendit'` (forced)

### Step 3: Update `OrderController@placeOrder` — Integrasi Xendit
Setelah create order + items + delete cart:
- Wrap with try-catch
- Panggil `XenditService::createInvoice($order)`
- Update order dengan `xendit_invoice_id`, `xendit_invoice_url`, `xendit_expiry`
- Redirect ke `$invoice['invoice_url']` (external redirect)
- Jika gagal: redirect ke confirmation dengan warning

### Step 4: Tambah `xenditCallback` Method
Method baru di `OrderController`:
- Validasi: order milik user yang login (`$order->user_id !== auth()->id()` → 403)
- Baca `$request->query('status')`
- Jika `success`/`paid`: verifikasi ke Xendit API → update payment_status = paid
- Jika failed/cancel: redirect confirmation dengan error

### Step 5: Update Checkout View (`checkout.blade.php`)
- **Payment Method Section**: HILANG — tidak ada pilihan, forced Xendit
- **Shipping Section**: Ganti dengan RajaOngkir interactive flow:
  - Province dropdown (dari API) → City dropdown → Shipping cost options
  - Alpine.js component `shippingCalculator()`:
    - `init()` → load provinces dari `/shipping/provinces`
    - `loadCities()` → load cities berdasarkan province
    - `loadShippingCost()` → POST ke `/shipping/cost` dengan weight dari cart
    - `updateTotal()` → dispatch event untuk update sidebar
  - Hidden inputs: `shipping_city_id`, `shipping_city_name`, `shipping_province_name`, `shipping_cost`, `shipping_courier`, `shipping_service`, `shipping_etd`
- **Total Sidebar**: Tambah Alpine.js x-data untuk dynamic shipping cost:
  - `shippingCost`, `subtotal`, `tax`
  - Dengarkan event `@shipping-selected.window` untuk update shipping cost
  - Tampilkan breakdown: Subtotal + Tax + Shipping = Grand Total
  - Ganti tombol "Place Order" → "Bayar dengan Xendit →"
- Tax display: gunakan `config('settings.tax_rate', 11)` — menunggu TRX-10 (atau sementara hardcode 11)

### Step 6: Update Confirmation View (`confirmation.blade.php`)
- Tambah section shipping info: courier, service, ongkir, estimasi
- Jika Xendit unpaid: tampilkan link/info pembayaran Xendit
- Break down total: subtotal, tax, shipping, grand total

## Dependencies Internal
- TRX-1 (migration) — order table harus punya kolom baru
- TRX-2 (XenditService) — untuk create invoice
- TRX-2B (ShippingController + RajaOngkirService) — untuk AJAX shipping
- TRX-10 (Tax Settings) — untuk tax rate configurable (bisa sementara hardcode 11%)

## Dependencies Antar File
1. routes/web.php (tambah route)
2. OrderController.php (modify placeOrder, add xenditCallback)
3. checkout.blade.php (RajaOngkir + Alpine.js)
4. confirmation.blade.php (shipping info + payment info)

## Data Flow
```
Checkout Page
    ↓ User pilih province → city → shipping option
    ↓ Submit form + validated shipping data
OrderController::placeOrder()
    ↓ Create order + items
    ↓ Update stock, delete cart
XenditService::createInvoice($order)
    ↓ Redirect ke Xendit invoice URL
User bayar
    ↓ Redirect back ke /orders/{order}/xendit/callback?status=success
OrderController::xenditCallback()
    ↓ Verify status via Xendit API
Order update → paid
    ↓ Redirect ke confirmation
```

## Test Plan
- Unit test: validasi form shipping (required fields)
- Unit test: placeOrder dengan stock tidak cukup
- Unit test: xenditCallback dengan order milik user lain → 403
- Integration test: full checkout flow (mock Xendit API)
- Browser test: Alpine.js province → city → cost flow
- Edge case: shipping cost = 0, weight = 0
- Edge case: Xendit API timeout → fallback ke confirmation page
