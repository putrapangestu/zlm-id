# PLAN FASE 2 — Missing Features ZLM.ID

## Project Context
Laravel 13.x e-commerce laptop store with MySQL, Tailwind CSS, Alpine.js, Spatie Roles, Iconify Icons (Solar).

### Tech Stack
- **Backend**: Laravel 13.x, PHP 8.3
- **Database**: MySQL
- **Frontend**: Blade + Tailwind CSS + Alpine.js
- **Icons**: Iconify (Solar)
- **Design System**: Primary `#DF5E1D` (orange), Text `#363230` (dark charcoal), Font Inter
- **Payment**: Xendit (already integrated)
- **Shipping**: RajaOngkir (already integrated)
- **Auth**: Laravel Breeze (already integrated)

### Missing Features (11 items)
Dari audit lengkap, berikut fitur yang BELUM ada:

| # | Fitur | Kategori | Prioritas |
|---|-------|----------|-----------|
| 1 | Login dengan Google | Auth | 🔴 High |
| 2 | Budget & Spec-based Search | Landing | 🔴 High |
| 3 | Tracking Barang (User) | Order | 🔴 High |
| 4 | Email Notifikasi Pesanan | Order | 🔴 High |
| 5 | Halaman Testimoni + CRUD | Landing | 🟡 Medium |
| 6 | Admin Profil ZLM (Store Info) | Admin | 🟡 Medium |
| 7 | Admin Manajemen User (CRUD) | Admin | 🟡 Medium |
| 8 | Laporan Pembelian | Report | 🟢 Normal |
| 9 | Laporan Laba Rugi | Report | 🟢 Normal |
| 10 | Laporan Statistik Barang | Report | 🟢 Normal |
| 11 | Sosial Media & Lokasi Footer | Landing | 🟢 Normal |

---

## Module Breakdown

### Modul AUTH-1: Login dengan Google
**File baru**: `app/Http/Controllers/Auth/GoogleController.php`, `config/services.php` (update)
**File diubah**: `routes/auth.php`, `composer.json` (add socialite)
**View diubah**: `resources/views/auth/login.blade.php`

Menggunakan Laravel Socialite untuk Google OAuth.

**Definisi Selesai**: User bisa login dengan Google, data user tersimpan di database, redirect setelah login berfungsi.

---

### Modul SEARCH-1: Budget & Spec-based Search
**File baru**: `app/Http/Controllers/SmartSearchController.php`
**File diubah**: `routes/web.php`
**View baru**: `resources/views/landing/smart-search.blade.php`

Fitur pencarian lanjutan:
1. User input **budget maksimal**
2. User pilih **prioritas spesifikasi** (processor, RAM, storage, GPU, brand)
3. Sistem mencari laptop dengan spesifikasi **paling mendekati** budget
4. Hasil diurutkan berdasarkan **skor kecocokan** (matching score)
5. Menampilkan rekomendasi "best value" dalam budget

**Definisi Selesai**: User bisa mencari laptop dengan budget + preferensi spesifikasi, hasil ditampilkan dengan skor kecocokan.

---

### Modul TRACK-1: Tracking Barang untuk User
**File baru**: `app/Http/Controllers/TrackingController.php`
**File diubah**: `routes/web.php`, `app/Models/Order.php` (add tracking fields)
**View baru**: `resources/views/landing/tracking.blade.php`, `resources/views/orders/tracking.blade.php`
**Migration baru**: Tambah kolom `tracking_number`, `tracking_notes`, `tracking_history` (JSON) ke orders

Tracking dengan status:
- `PENDING` → `PROCESSING` → `SHIPPING` → `DELIVERED`
- Admin update tracking via admin panel
- User bisa lihat tracking real-time

**Definisi Selesai**: User bisa tracking barang, admin bisa update status tracking.

---

### Modul NOTIF-1: Email Notifikasi Pesanan
**File baru**: `app/Mail/OrderConfirmationMail.php`, `app/Mail/OrderShippedMail.php`, `app/Mail/OrderDeliveredMail.php`
**View baru**: `resources/views/emails/order-confirmation.blade.php`, `resources/views/emails/order-shipped.blade.php`

Email dikirim saat:
1. Pesanan berhasil dibuat (Order Confirmation)
2. Pesanan dikirim (Tracking number + kurir)
3. Pesanan diterima (Delivered)

**Definisi Selesai**: Email otomatis terkirim untuk setiap event order.

---

### Modul TESTI-1: Halaman Testimoni + CRUD
**File baru**: `app/Models/Testimonial.php`, `app/Http/Controllers/Admin\TestimonialController.php`, `database/migrations/xxxx_create_testimonials_table.php`
**File diubah**: `routes/web.php` (admin + public routes)
**View baru**: `resources/views/landing/testimonials.blade.php`, `resources/views/admin/testimonials/index.blade.php`, `create.blade.php`, `edit.blade.php`
**View diubah**: `resources/views/landing/home.blade.php` (ganti testimoni static → dynamic)

Fitur:
- Admin CRUD testimonial (nama, pekerjaan, konten, rating, foto)
- Halaman publik `/testimonials`
- Testimoni di landing page jadi dinamis dari database

**Definisi Selesai**: Testimoni bisa dikelola admin dan tampil dinamis di landing + halaman khusus.

---

### Modul PROFIL-1: Admin Profil ZLM (Store Info)
**File diubah**: `app/Http/Controllers/Admin/SettingController.php`
**View diubah**: `resources/views/admin/settings/index.blade.php`

Tambahkan setting baru:
| Key | Deskripsi |
|-----|-----------|
| `store_name` | Nama toko |
| `store_description` | Deskripsi toko |
| `store_address` | Alamat lengkap |
| `store_phone` | No telepon |
| `store_email` | Email toko |
| `store_google_maps` | Embed Google Maps URL |
| `social_instagram` | URL Instagram |
| `social_facebook` | URL Facebook |
| `social_tiktok` | URL TikTok |
| `social_youtube` | URL YouTube |
| `store_logo` | Path logo toko |

Settings page jadi komprehensif dengan tab: General, Sosial Media, Lokasi.

**Definisi Selesai**: Admin bisa mengatur semua informasi toko termasuk sosial media dan lokasi.

---

### Modul USER-1: Admin Manajemen User (CRUD)
**File diubah**: `app/Http/Controllers/Admin/UserController.php`
**View baru**: `resources/views/admin/users/create.blade.php`, `edit.blade.php`, `show.blade.php`
**View diubah**: `resources/views/admin/users/index.blade.php`

Fitur:
- List users (dengan search, filter role)
- Create user (manual, dengan assign role)
- Edit user (name, email, password, role)
- Delete user (soft delete)
- Lihat detail user (riwayat pesanan user)

**Definisi Selesai**: Admin bisa create, read, update, delete user dengan role management.

---

### Modul LAPORAN-1: Laporan Pembelian
**File baru**: `app/Http/Controllers/Admin/ReportController.php`
**View baru**: `resources/views/admin/reports/purchases.blade.php`
**File diubah**: `routes/web.php` (admin routes)

Fitur:
- Filter by date range
- Tabel: No, Tanggal, Order#, Customer, Items, Total, Status
- Export ke PDF/Excel
- Total summary

**Definisi Selesai**: Admin bisa generate laporan pembelian dengan filter dan export.

---

### Modul LAPORAN-2: Laporan Laba Rugi
**File baru**: bagian dari `ReportController.php`
**View baru**: `resources/views/admin/reports/profit-loss.blade.php`

Fitur:
- Filter by date range (bulanan, custom)
- Total Pendapatan (revenue dari paid orders)
- Total Modal/HPP (dari harga beli laptop)
- Biaya Operasional (shipping cost)
- Laba Kotor & Laba Bersih
- Export

**Definisi Selesai**: Admin bisa lihat laporan laba rugi dengan perhitungan otomatis.

---

### Modul LAPORAN-3: Laporan Statistik Barang
**File baru**: bagian dari `ReportController.php`
**View baru**: `resources/views/admin/reports/product-stats.blade.php`

Fitur:
- Stok barang (tersedia, habis, minimum)
- Barang terlaris (top selling)
- Barang dengan rating tertinggi
- Filter by kategori & date range
- Export

**Definisi Selesai**: Admin bisa lihat statistik barang lengkap.

---

### Modul LANDING-1: Sosial Media & Lokasi di Footer
**File diubah**: `resources/views/components/landing-footer.blade.php`

Menambahkan:
- Social media icons (Instagram, Facebook, TikTok, YouTube, WhatsApp) dari settings
- Google Maps embed lokasi toko
- Alamat toko dari settings
- Jam operasional

**Definisi Selesai**: Footer menampilkan sosial media dan lokasi toko dari database settings.

---

## Dependency Graph

```
AUTH-1 (Google Login)       ─── independent
SEARCH-1 (Smart Search)     ─── independent
TRACK-1 (Tracking)          ─── depends on: existing Order model
NOTIF-1 (Email Notification) ── depends on: TRACK-1 (for shipped email)
TESTI-1 (Testimoni)         ─── independent
PROFIL-1 (Store Info)       ─── depends on: existing Setting model
USER-1 (User CRUD)          ─── independent
LAPORAN-1/2/3 (Reports)    ─── depends on: existing Order, Laptop models
LANDING-1 (Footer)          ─── depends on: PROFIL-1 (settings data)
```

## Urutan Pengerjaan

### Fase A — Foundation (paralel)
1. **AUTH-1** Google Login
2. **SEARCH-1** Smart Search
3. **TESTI-1** Testimoni CRUD + Page
4. **PROFIL-1** Store Info Settings
5. **USER-1** Admin User CRUD

### Fase B — Order Enhancement (berurutan)
6. **TRACK-1** User Tracking
7. **NOTIF-1** Email Notifications

### Fase C — Reports (paralel, setelah Fase B)
8. **LAPORAN-1** Laporan Pembelian
9. **LAPORAN-2** Laporan Laba Rugi
10. **LAPORAN-3** Laporan Statistik Barang

### Fase D — Polish
11. **LANDING-1** Sosial Media & Lokasi Footer

## Definisi Selesai (Keseluruhan)
- ✅ Login dengan Google berfungsi penuh
- ✅ Pencarian laptop berdasarkan budget + spesifikasi dengan skor kecocokan
- ✅ User bisa tracking status pengiriman barang
- ✅ Email notifikasi otomatis (order, shipped, delivered)
- ✅ Testimoni dinamis dengan CRUD admin
- ✅ Admin bisa mengatur profil toko, sosial media, dan lokasi
- ✅ Admin bisa CRUD user
- ✅ Laporan Pembelian, Laba Rugi, dan Statistik Barang bisa di-generate
- ✅ Footer landing page menampilkan sosial media dan lokasi toko
- ✅ Semua route aman (auth + role:admin)
