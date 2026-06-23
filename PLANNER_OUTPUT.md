# PLANNER OUTPUT — ZLM.ID Fase 2 Implementation Plan

## Ringkasan
- **Total modul**: 9 (11 sub-modul)
- **Fase**: A (5 modul paralel) → B (2 modul sequential) → C (1 modul / 3 sub-laporan) → D (1 modul)
- **Builder Agents**: 4 Builder (Builder-1 sd Builder-4) untuk Fase A, 2 Builder untuk Fase B/C, 1 Builder untuk Fase D
- **Total file baru**: ~25 file
- **Total file diubah**: ~20 file
- **Migration baru**: 3 migrations

---

## Dependency Graph

```
FASE A (Foundation — Paralel):
  AUTH-1   ─── independent
  SEARCH-1 ─── independent
  TESTI-1  ─── independent
  PROFIL-1 ─── depends on existing Setting model
  USER-1   ─── independent

FASE B (Order Enhancement — Sequential):
  TRACK-1  ─── depends on existing Order model
  NOTIF-1  ─── depends on TRACK-1 (trigger saat shipped/delivered) + OrderController

FASE C (Reports):
  LAPORAN  ─── depends on existing Order, Laptop, OrderItem models

FASE D (Polish):
  LANDING-1 ─── depends on PROFIL-1 (settings data)
```

---

## Detail Per Modul

---

### FASE A-1: AUTH-1 — Login dengan Google

**File baru (3):**
| # | Path | Keterangan |
|---|------|------------|
| 1 | `app/Http/Controllers/Auth/GoogleController.php` | Controller handle redirect & callback Google OAuth |
| 2 | `database/migrations/xxxx_add_google_id_to_users_table.php` | Migration tambah kolom google_id + avatar |

**File diubah (4):**
| # | Path | Perubahan |
|---|------|-----------|
| 1 | `routes/auth.php` | Tambah 2 route: `auth/google` dan `auth/google/callback` |
| 2 | `config/services.php` | Tambah konfigurasi Google OAuth (client_id, secret, redirect) |
| 3 | `resources/views/auth/login.blade.php` | Tambah tombol "Login with Google" sebelum divider |
| 4 | `resources/views/auth/register.blade.php` | Tambah tombol "Daftar with Google" sebelum divider |

**Migration: YES**
- `google_id` (string, nullable, unique)
- `avatar` (string, nullable)

**Logic:**
- `redirect()` → return Socialite::driver('google')->redirect()
- `callback()` →:
  1. Dapatkan user dari Google via Socialite
  2. Cari user where `google_id = $googleUser->id` ATAU `email = $googleUser->email`
  3. Jika ditemukan → login (update google_id & avatar jika null)
  4. Jika tidak → create User baru, assign role 'user', login
  5. Redirect ke intended URL atau dashboard

**Dependencies:**
- composer require laravel/socialite
- Harus ada Spatie role 'user' sudah ter-seed

**Builder Assignment:** Builder-1
**Estimasi:** 30 menit

---

### FASE A-2: SEARCH-1 — Smart Search (Budget + Spesifikasi)

**File baru (2):**
| # | Path | Keterangan |
|---|------|------------|
| 1 | `app/Http/Controllers/SmartSearchController.php` | Controller dengan index() + search() |
| 2 | `resources/views/landing/smart-search.blade.php` | View form + hasil pencarian |

**File diubah (3):**
| # | Path | Perubahan |
|---|------|-----------|
| 1 | `routes/web.php` | Tambah 2 route GET/POST `/smart-search` |
| 2 | `resources/views/components/landing-nav.blade.php` | Tambah link "Smart Search" di desktop & mobile menu |
| 3 | `resources/views/landing/home.blade.php` | Tambah tombol "Smart Search" di hero section |

**Migration: NO** (menggunakan data existing dari laptops table)

**Logic — Scoring Algorithm:**
```
Input: budget_max, priority, usage, brand (optional)

1. Query semua laptop with price <= budget_max
2. Untuk setiap laptop, hitung:
   - budget_score = (price / budget_max) * 100   (semakin dekat ke budget, semakin tinggi)
   - cpu_score = berdasarkan mapping processor class (i3=40, i5=60, i7=80, i9=100, Ryzen3=40, Ryzen5=60, Ryzen7=80, Ryzen9=100)
   - ram_score = berdasarkan mapping: 8GB=60, 16GB=80, 32GB=100
   - storage_score = 256GB=60, 512GB=80, 1TB=100
   - gpu_score = berdasarkan usage: Integrated=40, Entry=60, Mid=80, High=100
   
3. total_score = (budget_score * 0.35) + (cpu_score * 0.25) + (ram_score * 0.15) + (storage_score * 0.10) + (gpu_score * 0.15)

4. Filter: jika priority tertentu, bobot kriteria itu dinaikkan +5%, lainnya dikurangi
5. Sort by total_score DESC
6. Return collection dengan score badge
```

**View Layout:**
- Form: budget input, priority radio, usage dropdown, brand dropdown
- Results: cards dengan skor badge (persentase), nama, harga, spesifikasi, link detail
- Tips jika hasil kosong

**Builder Assignment:** Builder-1
**Estimasi:** 45 menit

---

### FASE A-3: TESTI-1 — Testimoni CRUD

**File baru (6):**
| # | Path | Keterangan |
|---|------|------------|
| 1 | `database/migrations/xxxx_create_testimonials_table.php` | Migration testimonials table |
| 2 | `app/Models/Testimonial.php` | Model Testimonial |
| 3 | `app/Http/Controllers/Admin/TestimonialController.php` | Admin CRUD controller |
| 4 | `resources/views/admin/testimonials/index.blade.php` | Admin list testimoni |
| 5 | `resources/views/admin/testimonials/create.blade.php` | Admin form create |
| 6 | `resources/views/admin/testimonials/edit.blade.php` | Admin form edit |
| 7 | `resources/views/landing/testimonials.blade.php` | Halaman publik testimoni |

**File diubah (4):**
| # | Path | Perubahan |
|---|------|-----------|
| 1 | `routes/web.php` | Tambah route public `/testimonials` + admin resource route |
| 2 | `resources/views/landing/home.blade.php` | Ganti testimonial static dengan loop $testimonials dari DB |
| 3 | `app/Http/Controllers/LaptopController.php` | Tambah $testimonials ke index() (query top 3 active) |
| 4 | `resources/views/components/landing-nav.blade.php` | Tambah link "Testimoni" |

**File diubah (admin sidebar):**
| # | Path | Perubahan |
|---|------|-----------|
| 1 | `resources/views/layouts/admin.blade.php` | Tambah link "Testimonials" ke sidebar |

**Migration: YES**
```
Schema::create('testimonials', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('name');
    $table->string('position')->nullable();
    $table->text('content');
    $table->unsignedTinyInteger('rating')->default(5);
    $table->string('photo')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

**Logic:**
- Admin CRUD dengan upload foto, star rating 1-5, toggle active/inactive
- Public: menampilkan semua testimoni active
- Landing: query `Testimonial::where('is_active', true)->latest()->take(3)->get()`
- Validasi: name required, content required, rating 1-5, foto max 2MB (jpg/png/webp)

**Builder Assignment:** Builder-2
**Estimasi:** 60 menit

---

### FASE A-4: PROFIL-1 — Store Info Settings

**File baru (0):** Tidak ada file baru (menggunakan existing Setting model + controller)

**File diubah (3):**
| # | Path | Perubahan |
|---|------|-----------|
| 1 | `app/Http/Controllers/Admin/SettingController.php` | Rewrite index() dengan tabs logic, update() dengan validasi multi-field |
| 2 | `resources/views/admin/settings/index.blade.php` | Full rewrite dengan 3 tabs (General, Social Media, Location) |
| 3 | `database/seeders/SettingsSeeder.php` | Tambah default settings baru |

**Migration: NO** (menggunakan existing settings table dengan key-value)

**Logic:**
- `index()`: Tampilkan view dengan tab active (default 'general'). Semua data dari config('settings.xxx')
- `update(Request)`: Validasi berdasarkan tab yang disubmit:
  - **Tab General**: store_name, store_description, store_email, store_phone, store_opening_hours, store_logo (upload)
  - **Tab Sosial Media**: social_instagram, social_facebook, social_tiktok, social_youtube, store_whatsapp (validasi URL)
  - **Tab Lokasi**: store_address, store_google_maps (validasi embed URL)
- Setiap field di-save via `Setting::setValue(key, value)`
- Refresh config setelah update

**Seed data baru di SettingsSeeder:**
```php
'store_description' => 'Premium laptop store...',
'store_address' => 'Jl. Raya Malang No. 123, Malang, Jawa Timur',
'store_phone' => '+62 123 4567 8910',
'store_email' => 'support@zlm.id',
'store_google_maps' => '',
'store_whatsapp' => '6212345678910',
'social_instagram' => 'https://instagram.com/zlm.id',
'social_facebook' => 'https://facebook.com/zlm.id',
'social_tiktok' => 'https://tiktok.com/@zlm.id',
'social_youtube' => 'https://youtube.com/@zlm.id',
'store_logo' => '',
'store_opening_hours' => 'Sen - Sab: 09:00 - 18:00',
```

**View Layout Tabs:**
```
[General] [Sosial Media] [Lokasi]
Tab General: Nama, Deskripsi, Email, Telepon, Jam Operasi, Logo (upload)
Tab Sosial Media: Instagram, Facebook, TikTok, YouTube, WhatsApp
Tab Lokasi: Alamat, Google Maps embed
```

**Builder Assignment:** Builder-2
**Estimasi:** 45 menit

---

### FASE A-5: USER-1 — Admin User Management

**File baru (3):**
| # | Path | Keterangan |
|---|------|------------|
| 1 | `resources/views/admin/users/create.blade.php` | Form create user |
| 2 | `resources/views/admin/users/edit.blade.php` | Form edit user |
| 3 | `resources/views/admin/users/show.blade.php` | Detail user + riwayat order |

**File diubah (2):**
| # | Path | Perubahan |
|---|------|-----------|
| 1 | `app/Http/Controllers/Admin/UserController.php` | Tambah method create, store, show, edit, update, destroy |
| 2 | `resources/views/admin/users/index.blade.php` | Ganti dummy data dengan real data dari DB, tambah search & filter |

**Migration: NO** (menggunakan existing users table + Spatie roles)

**Routes (existing, perlu ditambah):**
```php
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index'); // existing
    Route::get('/create', [UserController::class, 'create'])->name('create'); // new
    Route::post('/', [UserController::class, 'store'])->name('store'); // new
    Route::get('/{user}', [UserController::class, 'show'])->name('show'); // new
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit'); // new
    Route::put('/{user}', [UserController::class, 'update'])->name('update'); // new
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy'); // new
});
```

**Logic:**
- `index()`: Query users with search (name/email) + filter role + pagination
- `create()`: Return view dengan roles dari Spatie
- `store(Request)`: Validasi name, email (unique), password (min:8, confirmed), role. Create user, assign role
- `show(User)`: Load user + orders (dengan items) + total belanja
- `edit(User)`: Return view dengan data user
- `update(Request, User)`: Validasi name, email (unique except current), password (nullable|min:8), role. Update user, sync role
- `destroy(User)`: Soft delete user, redirect dengan success message

**Validasi:**
- Email unique (kecuali user yang sama saat edit)
- Password required saat create / optional saat edit
- Role wajib dipilih dari Spatie roles

**Builder Assignment:** Builder-3
**Estimasi:** 60 menit

---

### FASE B-1: TRACK-1 — User Tracking

**File baru (4):**
| # | Path | Keterangan |
|---|------|------------|
| 1 | `database/migrations/xxxx_add_tracking_fields_to_orders_table.php` | Migration tracking fields |
| 2 | `app/Http/Controllers/TrackingController.php` | Controller tracking (index, show, trackByNumber) |
| 3 | `resources/views/landing/tracking.blade.php` | Halaman tracking publik dengan form + timeline |
| 4 | `resources/views/admin/orders/tracking.blade.php` | Admin panel update tracking (modal/halaman) |

**File diubah (5):**
| # | Path | Perubahan |
|---|------|-----------|
| 1 | `app/Models/Order.php` | Tambah $casts untuk tracking_history, helper methods |
| 2 | `routes/web.php` | Tambah route tracking (public + admin) |
| 3 | `resources/views/orders/history.blade.php` | Tambah tombol "Lacak" jika order punya tracking_number |
| 4 | `resources/views/admin/orders/index.blade.php` | Tambah kolom/tombol tracking di admin |
| 5 | `app/Http/Controllers/Admin/OrderStatusController.php` | Update untuk handle tracking fields saat status change |

**Migration: YES**
```php
Schema::table('orders', function (Blueprint $table) {
    $table->string('tracking_number', 100)->nullable()->after('shipping_phone');
    $table->json('tracking_history')->nullable()->after('tracking_number');
    $table->timestamp('shipped_at')->nullable()->after('tracking_history');
    $table->date('estimated_delivery')->nullable()->after('shipped_at');
});
```

**Logic Order Model:**
```php
// Tambah casts
protected $casts = [
    // ... existing casts
    'tracking_history' => 'array',
    'shipped_at' => 'datetime',
    'estimated_delivery' => 'date',
];

// Helper methods
public function addTrackingEvent(string $status, string $description, ?string $location = null): void
{
    $history = $this->tracking_history ?? [];
    $history[] = [
        'status' => $status,
        'description' => $description,
        'location' => $location,
        'timestamp' => now()->toIso8601String(),
    ];
    $this->update(['tracking_history' => $history]);
}

public function getLatestTracking(): ?array
{
    $history = $this->tracking_history ?? [];
    return !empty($history) ? end($history) : null;
}

public function isShipped(): bool { return $this->status === 'shipped'; }
public function isDelivered(): bool { return $this->status === 'delivered'; }
```

**Logic TrackingController:**
- `index()`: Form input nomor pesanan/resi
- `trackByNumber(Request)`: Cari order by order_number ATAU tracking_number
- `show(Order)`: Tampilkan tracking detail (auth + verifikasi kepemilikan)

**Admin Update Tracking:**
- Form input nomor resi (tracking_number)
- Tombol update status dengan konfirmasi
- Tambah catatan tracking event
- Trigger email notifikasi saat shipped/delivered

**Builder Assignment:** Builder-3
**Estimasi:** 60 menit

---

### FASE B-2: NOTIF-1 — Email Notifications

**File baru (6):**
| # | Path | Keterangan |
|---|------|------------|
| 1 | `app/Mail/OrderConfirmationMail.php` | Mailable order confirmation |
| 2 | `app/Mail/OrderShippedMail.php` | Mailable order shipped |
| 3 | `app/Mail/OrderDeliveredMail.php` | Mailable order delivered |
| 4 | `resources/views/emails/order-confirmation.blade.php` | Template email confirmation |
| 5 | `resources/views/emails/order-shipped.blade.php` | Template email shipped |
| 6 | `resources/views/emails/order-delivered.blade.php` | Template email delivered |

**File diubah (2):**
| # | Path | Perubahan |
|---|------|-----------|
| 1 | `app/Http/Controllers/OrderController.php` | Tambah trigger email di placeOrder() |
| 2 | `app/Http/Controllers/Admin/OrderStatusController.php` | Tambah trigger email saat shipped & delivered |

**Migration: NO** (menggunakan existing queue/jobs table)

**Logic — Mailing Classes:**

**OrderConfirmationMail:**
- Dikirim saat: `OrderController@placeOrder` setelah order berhasil dibuat
- Data: order number, items list, total, shipping address
- Payment link jika unpaid
- Gunakan `Mail::to($order->user->email)->queue(new OrderConfirmationMail($order))`

**OrderShippedMail:**
- Dikirim saat: `OrderStatusController@update` ketika status berubah jadi 'shipped'
- Data: order number, tracking number, courier, tracking link
- Gunakan queue

**OrderDeliveredMail:**
- Dikirim saat: `OrderStatusController@update` ketika status berubah jadi 'delivered'
- Data: order number, review link
- Gunakan queue

**Template Design — ZLM.ID Style:**
- Header: Logo ZLM.ID + nama toko
- Warna: #DF5E1D (primary orange), #363230 (text)
- Font: Inter
- Layout: Card style dengan border-radius
- Footer: Contact info, alamat, sosial media

**Trigger Integration:**
```php
// Di OrderController@placeOrder — setelah order sukses dibuat
Mail::to($order->user->email)->queue(new OrderConfirmationMail($order));

// Di OrderStatusController@update — saat status berubah
if ($data['status'] === 'shipped') {
    Mail::to($order->user->email)->queue(new OrderShippedMail($order));
}
if ($data['status'] === 'delivered') {
    Mail::to($order->user->email)->queue(new OrderDeliveredMail($order));
}
```

**Builder Assignment:** Builder-4
**Estimasi:** 45 menit

---

### FASE C: LAPORAN — Reports (Purchases + Profit-Loss + Product Stats)

**File baru (4):**
| # | Path | Keterangan |
|---|------|------------|
| 1 | `app/Http/Controllers/Admin/ReportController.php` | Controller dengan 3 method: purchases, profitLoss, productStats |
| 2 | `resources/views/admin/reports/purchases.blade.php` | Laporan pembelian |
| 3 | `resources/views/admin/reports/profit-loss.blade.php` | Laporan laba rugi |
| 4 | `resources/views/admin/reports/product-stats.blade.php` | Laporan statistik barang |

**File diubah (2):**
| # | Path | Perubahan |
|---|------|-----------|
| 1 | `routes/web.php` | Tambah route group `/admin/reports` dengan 3 routes |
| 2 | `resources/views/layouts/admin.blade.php` | Tambah submenu "Laporan" di sidebar dengan dropdown |

**Migration: NO** (menggunakan data existing dari orders, laptops, order_items)

**Logic — ReportController Methods:**

**1. `purchases(Request)` — Laporan Pembelian:**
- Filter: date range (start_date, end_date), status pesanan, payment status
- Query: Order::with('user', 'items')->whereBetween('created_at', [$start, $end])
- Data table: No, Tanggal, Order#, Customer, Items Count, Total, Payment Status, Order Status
- Summary: total orders, total revenue, average order value
- Sorting: latest first

**2. `profitLoss(Request)` — Laporan Laba Rugi:**
- Filter: periode (bulanan / custom date range)
- Query: paid orders in range
- Perhitungan:
  - Pendapatan = sum(total) dari paid orders
  - HPP = sum(unit_price * quantity) dari order_items (menggunakan harga jual sebagai estimasi modal)
  - Biaya Operasional = sum(shipping_cost) dari paid orders
  - Laba Kotor = Pendapatan - HPP
  - Laba Bersih = Laba Kotor - Biaya Operasional
- Tampilan: format laporan akuntansi dengan sections

**3. `productStats(Request)` — Laporan Statistik Barang:**
- Filter: kategori, date range
- Sections:
  - **Ringkasan Stok**: total produk, tersedia (>0), habis (=0), minimum (<=5)
  - **Top Selling**: join order_items + laptops, group by laptop_id, sum quantity, order by total_quantity DESC
  - **Top Rated**: laptops with reviews, avg rating, order by rating DESC
- Tampilan: cards + tables

**Routes:**
```php
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/purchases', [ReportController::class, 'purchases'])->name('purchases');
    Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('profit-loss');
    Route::get('/product-stats', [ReportController::class, 'productStats'])->name('product-stats');
});
```

**Builder Assignment:** Builder-4
**Estimasi:** 75 menit

---

### FASE D: LANDING-1 — Footer Social Media & Location

**File baru (0):** Tidak ada file baru

**File diubah (1):**
| # | Path | Perubahan |
|---|------|-----------|
| 1 | `resources/views/components/landing-footer.blade.php` | Full rewrite: tambah social icons, maps, address, hours |

**Migration: NO** (menggunakan data dari settings table via config)

**Logic:**
- Semua data dari `config('settings.xxx')` yang sudah di-load di AppServiceProvider
- Jika setting kosong, tampilkan placeholder atau hide section

**Footer New Layout:**
```
4 columns (desktop) / stacked (mobile):
1. ZLM.ID Brand: deskripsi toko dari settings
2. Links: Katalog, Artikel, Smart Search, Bandingkan, Testimoni
3. Contact: email, telepon, WhatsApp dari settings
4. Social Media: icon IG, FB, TT, YT, WA (Iconify Solar)

Bottom section:
- Alamat toko + Google Maps embed (iframe)
- Jam operasional
- Copyright
```

**Social Media Icons & Hover Colors:**
| Platform | Icon | Hover Color |
|----------|------|-------------|
| Instagram | `solar:instagram-linear` | #E4405F |
| Facebook | `solar:facebook-linear` | #1877F2 |
| TikTok | `solar:tiktok-linear` | #000000 |
| YouTube | `solar:youtube-linear` | #FF0000 |
| WhatsApp | `solar:phone-calling-linear` | #25D366 |

**Google Maps:**
- Jika `store_google_maps` terisi, tampilkan iframe embed
- Jika tidak, tampilkan alamat sebagai text saja
- Fallback: link Google Maps dengan alamat

**Nav Link Tambahan:**
- Tambah "Testimoni" di Links section
- Tambah "Smart Search" di Links section

**Builder Assignment:** Builder-1 (cleanup)
**Estimasi:** 30 menit

---

## Builder Agent Assignment

| Builder | Modul | File Baru | File Diubah | Estimasi |
|---------|-------|-----------|-------------|----------|
| **Builder-1** | AUTH-1 + SEARCH-1 | 5 | 7 | 75 menit |
| **Builder-2** | TESTI-1 + PROFIL-1 | 7 | 6 | 105 menit |
| **Builder-3** | USER-1 + TRACK-1 | 7 | 7 | 120 menit |
| **Builder-4** | NOTIF-1 + LAPORAN | 10 | 4 | 120 menit |
| **Builder-1 (lagi)** | LANDING-1 (Fase D) | 0 | 1 | 30 menit |

## Urutan Eksekusi

```
Round 1 (Paralel):
  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐
  │  Builder-1  │  │  Builder-2  │  │  Builder-3  │  │  Builder-4  │
  │  AUTH-1     │  │  TESTI-1    │  │  USER-1     │  │  NOTIF-1    │
  │  SEARCH-1   │  │  PROFIL-1   │  │  TRACK-1    │  │  LAPORAN    │
  └─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘

Round 2 (Sequential — after Round 1 complete):
  ┌─────────────┐
  │  Builder-1  │
  │  LANDING-1  │
  └─────────────┘
```

## Risiko & Mitigasi

| Risiko | Dampak | Mitigasi |
|--------|--------|----------|
| Socialite conflict dengan existing auth | Medium | Test di local dulu, pastikan route tidak bentrok |
| Scoring algorithm tidak akurat | Medium | Tuning bobot setelah testing dengan data real |
| Email queue tidak jalan | High | Pastikan queue worker terdaftar di supervisor / Task Scheduler |
| Tracking migration conflict dengan existing orders | Low | Default value nullable untuk semua kolom baru |
| Data setting tidak muncul di footer | Low | Fallback value di view jika config('settings.xxx') null |

## File Summary

### File Baru Total: ~25

| Path | Modul |
|------|-------|
| `app/Http/Controllers/Auth/GoogleController.php` | AUTH-1 |
| `app/Http/Controllers/SmartSearchController.php` | SEARCH-1 |
| `app/Models/Testimonial.php` | TESTI-1 |
| `app/Http/Controllers/Admin/TestimonialController.php` | TESTI-1 |
| `app/Http/Controllers/TrackingController.php` | TRACK-1 |
| `app/Http/Controllers/Admin/ReportController.php` | LAPORAN |
| `app/Mail/OrderConfirmationMail.php` | NOTIF-1 |
| `app/Mail/OrderShippedMail.php` | NOTIF-1 |
| `app/Mail/OrderDeliveredMail.php` | NOTIF-1 |
| `database/migrations/xxxx_add_google_id_to_users_table.php` | AUTH-1 |
| `database/migrations/xxxx_create_testimonials_table.php` | TESTI-1 |
| `database/migrations/xxxx_add_tracking_fields_to_orders_table.php` | TRACK-1 |
| `resources/views/landing/smart-search.blade.php` | SEARCH-1 |
| `resources/views/landing/testimonials.blade.php` | TESTI-1 |
| `resources/views/landing/tracking.blade.php` | TRACK-1 |
| `resources/views/admin/testimonials/index.blade.php` | TESTI-1 |
| `resources/views/admin/testimonials/create.blade.php` | TESTI-1 |
| `resources/views/admin/testimonials/edit.blade.php` | TESTI-1 |
| `resources/views/admin/users/create.blade.php` | USER-1 |
| `resources/views/admin/users/edit.blade.php` | USER-1 |
| `resources/views/admin/users/show.blade.php` | USER-1 |
| `resources/views/admin/orders/tracking.blade.php` | TRACK-1 |
| `resources/views/admin/reports/purchases.blade.php` | LAPORAN |
| `resources/views/admin/reports/profit-loss.blade.php` | LAPORAN |
| `resources/views/admin/reports/product-stats.blade.php` | LAPORAN |
| `resources/views/emails/order-confirmation.blade.php` | NOTIF-1 |
| `resources/views/emails/order-shipped.blade.php` | NOTIF-1 |
| `resources/views/emails/order-delivered.blade.php` | NOTIF-1 |

### File Diubah Total: ~18

| Path | Modul |
|------|-------|
| `routes/auth.php` | AUTH-1 |
| `routes/web.php` | SEARCH-1, TESTI-1, TRACK-1, LAPORAN |
| `config/services.php` | AUTH-1 |
| `app/Models/Order.php` | TRACK-1 |
| `app/Models/Laptop.php` | (tidak diubah) |
| `app/Http/Controllers/Admin/SettingController.php` | PROFIL-1 |
| `app/Http/Controllers/Admin/UserController.php` | USER-1 |
| `app/Http/Controllers/Admin/OrderStatusController.php` | TRACK-1, NOTIF-1 |
| `app/Http/Controllers/OrderController.php` | NOTIF-1 |
| `app/Http/Controllers/LaptopController.php` | TESTI-1 (tambah $testimonials) |
| `database/seeders/SettingsSeeder.php` | PROFIL-1 |
| `resources/views/auth/login.blade.php` | AUTH-1 |
| `resources/views/auth/register.blade.php` | AUTH-1 |
| `resources/views/landing/home.blade.php` | SEARCH-1, TESTI-1 |
| `resources/views/components/landing-footer.blade.php` | LANDING-1 |
| `resources/views/components/landing-nav.blade.php` | SEARCH-1, TESTI-1 |
| `resources/views/orders/history.blade.php` | TRACK-1 |
| `resources/views/admin/settings/index.blade.php` | PROFIL-1 |
| `resources/views/admin/users/index.blade.php` | USER-1 |
| `resources/views/admin/orders/index.blade.php` | TRACK-1 |
| `resources/views/layouts/admin.blade.php` | TESTI-1, LAPORAN |

## Checklist Verifikasi Final

- [ ] Semua route sudah di-test (public + admin + auth)
- [ ] Google login flow berfungsi (redirect + callback + create/login)
- [ ] Smart Search menampilkan hasil dengan skor kecocokan
- [ ] Testimoni CRUD admin berfungsi penuh
- [ ] Testimoni landing page dinamis dari database
- [ ] Settings page dengan 3 tabs menyimpan semua data
- [ ] User CRUD (create, read, update, soft delete) berfungsi
- [ ] Tracking migration berjalan, model helper methods bekerja
- [ ] Tracking timeline tampil di frontend
- [ ] Admin bisa update tracking number + status
- [ ] Email terkirim saat: order dibuat, shipped, delivered
- [ ] Laporan pembelian dengan filter date range
- [ ] Laporan laba rugi dengan perhitungan otomatis
- [ ] Laporan statistik barang (stok, top selling, rating)
- [ ] Sidebar admin: menu Testimoni, Reports, Users
- [ ] Footer: social media icons, maps, alamat, jam operasional
- [ ] Semua view menggunakan design system (Orange primary, Inter font, Iconify Solar)
