# DELIVERY — Payment & Transaction System + Xendit + RajaOngkir

**Project:** ZLM.ID (Laravel 13)
**Status:** ✅ Selesai — 50 tests passed (116 assertions)
**Tanggal:** 2026-06-04

---

## Cara Run Project

```bash
cd C:\kerjaan\gitlab\zlm-id

# 1. Install dependencies (jika fresh clone)
composer install
npm install && npm run build

# 2. Environment
copy .env.example .env   # copy dulu, lalu isi credentials
php artisan key:generate

# 3. Database
php artisan migrate
php artisan db:seed

# 4. Storage link (untuk upload proof of transfer)
php artisan storage:link

# 5. Jalankan
php artisan serve
```

## Environment Variables (wajib diisi)

```env
# Xendit
XENDIT_SECRET_KEY=xnd_development_...
XENDIT_PUBLIC_KEY=xnd_public_development_...
XENDIT_PRODUCTION=false
XENDIT_WEBHOOK_VERIFICATION_TOKEN=

# RajaOngkir
RAJAONGKIR_API_KEY=...
RAJAONGKIR_BASE_URL=https://api.rajaongkir.com/starter
RAJAONGKIR_ORIGIN_CITY_ID=152   # Kota asal pengiriman (default: Jakarta)
```

## Daftar Routes

### 🔵 User (auth required)
| Method | URL | Fungsi |
|--------|-----|--------|
| GET | `/checkout` | Halaman checkout dengan RajaOngkir shipping calculator |
| POST | `/orders` | Place order → create invoice Xendit |
| GET | `/orders/{order}` | Halaman konfirmasi/detail order |
| GET | `/orders` | Riwayat order user |
| POST | `/orders/{order}/proof` | Upload bukti transfer (manual) |
| GET | `/orders/{order}/xendit/callback` | Callback setelah bayar Xendit |

### 🟢 Shipping API (auth required, AJAX)
| Method | URL | Fungsi |
|--------|-----|--------|
| GET | `/shipping/provinces` | Daftar provinsi |
| GET | `/shipping/cities?province_id=` | Daftar kota per provinsi |
| POST | `/shipping/cost` | Hitung ongkir (body: destination, weight) |

### 🟣 Xendit Webhook (public, no CSRF)
| Method | URL | Fungsi |
|--------|-----|--------|
| POST | `/webhooks/xendit` | Menerima callback dari Xendit (PAID/EXPIRED/FAILED) |

### 🟠 Admin (role: admin)
| Method | URL | Fungsi |
|--------|-----|--------|
| GET | `/admin/dashboard` | Dashboard dengan stats real (orders, revenue, dll) |
| GET | `/admin/transactions` | List semua transaksi |
| GET | `/admin/transactions/create` | Form create transaksi |
| POST | `/admin/transactions` | Simpan transaksi baru |
| GET | `/admin/transactions/{order}` | Detail transaksi |
| POST | `/admin/transactions/{order}/confirm-payment` | Konfirmasi pembayaran manual |
| GET | `/admin/settings` | Halaman settings (tax rate) |
| POST | `/admin/settings` | Simpan settings |

### ⚪ Static
| Method | URL | Fungsi |
|--------|-----|--------|
| GET | `/` | Home / landing page |
| GET | `/search` | Pencarian laptop |
| GET | `/laptops/{laptop}` | Detail laptop |
| GET | `/compare` | Perbandingan laptop |

## Flow Transaksi

### User Checkout (Xendit - forced)
```
Checkout → Pilih Provinsi → Pilih Kota → Pilih Kurir → Place Order
    → XenditInvoice → Redirect ke Xendit → Bayar → Webhook → Status PAID
```

### User Checkout (Manual Transfer - admin only)
```
Admin Create Transaction → pilih manual_transfer
    → Customer lihat no rekening → Upload bukti
    → Admin Confirm Payment → Status PAID
```

## Hal yang Perlu Dikonfigurasi Admin

### 1. Tax Rate
- Buka `/admin/settings`
- Ubah `Tax Rate` (default 11%)
- Berlaku untuk semua transaksi baru

### 2. Xendit Webhook
- Di dashboard Xendit, set webhook URL ke: `https://domain-anda.com/webhooks/xendit`
- Untuk development: gunakan Xendit API key development (sudah di .env)

### 3. RajaOngkir Origin City
- Ubah `RAJAONGKIR_ORIGIN_CITY_ID` di .env (default 152 = Jakarta Pusat)
- Starter tier hanya support 1 origin city

### 4. Storage
- `php artisan storage:link` — diperlukan agar file upload proof muncul

## Struktur File — File Baru/Diubah

### File Baru (15)
```
config/xendit.php
config/rajaongkir.php
app/Services/XenditService.php
app/Services/RajaOngkirService.php
app/Http/Controllers/ShippingController.php
app/Http/Controllers/ProofUploadController.php
app/Http/Controllers/XenditWebhookController.php
app/Http/Controllers/Admin/SettingController.php
app/Models/Setting.php
app/helpers.php
database/migrations/2026_06_04_100001_add_payment_and_shipping_fields_to_orders_table.php
database/migrations/2026_06_04_100002_create_settings_table.php
database/seeders/SettingsSeeder.php
resources/views/admin/transactions/show.blade.php
resources/views/admin/transactions/create.blade.php
resources/views/admin/settings/index.blade.php
```

### File Diubah (13)
```
app/Models/Order.php
app/Http/Controllers/OrderController.php
app/Http/Controllers/Admin/TransactionController.php
app/Http/Controllers/Admin/DashboardController.php
app/Providers/AppServiceProvider.php
app/Exceptions/Handler.php (via bootstrap/app.php)
routes/web.php
bootstrap/app.php
composer.json
resources/views/orders/checkout.blade.php
resources/views/orders/confirmation.blade.php
resources/views/orders/history.blade.php
resources/views/admin/dashboard.blade.php
resources/views/layouts/admin.blade.php
resources/views/admin/transactions/index.blade.php
tests/Feature/OrderTest.php
```

## Test Coverage

```
Tests: 50 passed (116 assertions)
├── Unit ExampleTest       ✓
├── AdminTest              ✓ (6 tests)
├── Auth                   ✓ (13 tests)
├── CartTest               ✓ (6 tests)
├── LandingPagesTest       ✓ (5 tests)
├── OrderTest              ✓ (6 tests — includes Xendit mock)
├── ProfileTest            ✓ (5 tests)
└── ReviewWishlistTest     ✓ (5 tests)
```
