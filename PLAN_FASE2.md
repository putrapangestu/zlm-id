# PLAN FASE 2 — ZLM-ID: OTP Auth, Compare Enhancement, Admin Customers, Landing Widget

## Ringkasan
Fase 2 menambahkan 4 modul baru di atas fondasi Fase 1.

## Kebijakan Arsitektur
### SoftDelete Wajib
- **Semua model** WAJIB menggunakan `SoftDeletes` trait — tidak boleh ada hard delete
- `User.php` saat ini belum SoftDeletes → perlu migration `add_deleted_at_to_users_table`
- Model baru (`Otp`) juga wajib SoftDeletes
- Saat migration `down()`, jangan gunakan `Schema::dropIfExists()` langsung — gunakan `Schema::table()->dropSoftDeletes()` atau handle dengan benar
- Relasi `foreignId()->constrained()->cascadeOnDelete()` tetap boleh di migration, karena delete secara soft tidak menghapus baris FK

### Compare Storage
- Compare **tidak disimpan di database** — cukup session-based: `session('compare', [])`
- Karena berbasis session, data compare hanya bertahan selama session browser

| Modul | Kode | Prioritas | Estimasi |
|-------|------|-----------|----------|
| OTP Authentication | E | Tinggi | 3 hari |
| Compare Feature Enhancement | F | Sedang | 2 hari |
| Admin Customers Enhancement | G | Sedang | 2 hari |
| Compare Landing Integration | H | Rendah | 1 hari |

---

## Desain Sistem

### Design System (sama dengan Fase 1)
- **Primary**: `#DF5E1D` (orange)
- **Text**: `#363230` (dark charcoal)
- **Font**: Inter (Google Fonts)
- **Border Radius**: `xl` (12px), `2xl` (16px)
- **Shadow**: `shadow-sm`, `shadow-md`, `shadow-lg`

---

## Modul E: OTP Authentication (BARU)

### E.1 Migration — Tabel `user_otps`
**File**: `database/migrations/YYYY_MM_DD_HHMMSS_create_user_otps_table.php`

| Kolom | Tipe | Nullable | Default | Notes |
|-------|------|----------|---------|-------|
| id | `uuid` | No | — | Primary key |
| user_id | `foreignId('users')->cascadeOnDelete()` | No | — | Relasi ke users |
| otp | `string(6)` | No | — | 6 digit angka |
| type | `string(20)` | No | — | `register`, `forgot`, `login` |
| expires_at | `timestamp` | No | — | 5 menit setelah dibuat |
| used_at | `timestamp` | Yes | null | Diisi saat berhasil diverifikasi |
| created_at | `timestamp` | No | — | Default Laravel |
| updated_at | `timestamp` | No | — | Default Laravel |

Index: `['user_id', 'type', 'expires_at']`

### E.2 Model — `App\Models\Otp`
- `HasUuids` trait
- `$fillable`: `['user_id', 'otp', 'type', 'expires_at']`
- `$casts`: `['expires_at' => 'datetime', 'used_at' => 'datetime']`
- Relasi: `belongsTo(User::class)`
- Method helper: `isExpired(): bool`, `isUsed(): bool`, `isValid(): bool`

### E.3 Model Update — `App\Models\User`
- Tambah relasi: `otps(): HasMany`

### E.4 Controller — `App\Http\Controllers\Auth\OtpController`
**Method**:

| Method | Route | Fungsi |
|--------|-------|--------|
| `requestOtp(Request)` | POST `/otp/request` | Generate OTP 6 digit, simpan ke DB, kirim email |
| `verifyOtp(Request)` | POST `/otp/verify` | Cek OTP dari DB, validasi masa berlaku & penggunaan, tandai used_at |
| `resendOtp(Request)` | POST `/otp/resend` | Hapus OTP lama, generate baru, kirim ulang (cooldown 60s) |

**Logika requestOtp**:
1. Validasi input: `email` (required, exists:users), `type` (required, in:register/forgot/login)
2. Cari user berdasarkan email
3. Hapus OTP sebelumnya untuk user+type yang belum digunakan (`used_at IS NULL`)
4. Generate 6 digit random (`str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT)`)
5. Simpan ke `user_otps`
6. Kirim email via `App\Notifications\OtpNotification`
7. Return redirect ke halaman verify dengan `email` dan `type` di session

**Logika verifyOtp**:
1. Validasi input: `email`, `otp` (6 digit, numeric), `type`
2. Cari OTP record dengan kondisi: `user_id` (dari email), `type`, `otp`, `used_at IS NULL`, `expires_at > now()`
3. Jika tidak ditemukan → return error "Kode OTP tidak valid atau telah kedaluwarsa."
4. Set `used_at = now()`
5. Berdasarkan type:
   - `register`: redirect ke login dengan status success
   - `forgot`: redirect ke reset password form dengan token di session
   - `login`: login user langsung, redirect ke dashboard

### E.5 Mail Notification — `App\Notifications\OtpNotification`
**File**: `app/Notifications/OtpNotification.php`
- `via()`: `['mail']`
- `toMail()`: build email dengan template Blade `emails.otp`
- Menampilkan: logo, kode OTP 6 digit, masa berlaku 5 menit

### E.6 Views

#### Halaman Request OTP (`auth.otp-request`)
```
┌─────────────────────────────────────┐
│         ZLM-ID                      │
│   Masukkan Email                    │
├─────────────────────────────────────┤
│                                     │
│   [________________________]        │
│   (input email)                     │
│                                     │
│   <input type="hidden" name="type"> │
│                                     │
│  ┌─────────────────────────┐        │
│  │   Kirim Kode OTP        │        │
│  └─────────────────────────┘        │
│                                     │
│   Sudah punya kode? [Verifikasi]    │
└─────────────────────────────────────┘
```

#### Halaman Verify OTP (`auth.otp-verify`)
```
┌─────────────────────────────────────┐
│         ZLM-ID                      │
│   Verifikasi OTP                    │
│   Kode telah dikirim ke              │
│   email@example.com                 │
├─────────────────────────────────────┤
│                                     │
│  [ _ ][ _ ][ _ ][ _ ][ _ ][ _ ]    │
│  6 kotak input — auto-focus next    │
│                                     │
│  ┌─────────────────────────┐        │
│  │   Verifikasi             │        │
│  └─────────────────────────┘        │
│                                     │
│  [Kirim Ulang] — cooldown 60s       │
│  Timer: 0:45                        │
│                                     │
│  [← Kembali]                        │
└─────────────────────────────────────┘
```

### E.7 Routes (di `routes/auth.php`)

```php
Route::middleware('guest')->group(function () {
    Route::get('otp/request', [OtpController::class, 'showRequestForm'])->name('otp.request');
    Route::post('otp/request', [OtpController::class, 'requestOtp'])->name('otp.request.store');
    Route::get('otp/verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify');
    Route::post('otp/verify', [OtpController::class, 'verifyOtp'])->name('otp.verify.store');
    Route::post('otp/resend', [OtpController::class, 'resendOtp'])->name('otp.resend');
});
```

### E.8 Integrasi dengan Register
**File**: `app/Http/Controllers/Auth/RegisteredUserController.php`
- Di method `store()`, setelah `$user = User::create(...)`:
  - Hapus `event(new Registered($user))`
  - Hapus `Auth::login($user)`
  - Ganti: panggil `OtpController@requestOtp` atau langsung simpan OTP + redirect ke form verify
- User dibiarkan **belum login** sampai OTP diverifikasi

### E.9 Integrasi dengan Forgot Password
**File**: `app/Http/Controllers/Auth/PasswordResetLinkController.php`
- Di method `store()`, ganti logika:
  - Verifikasi email ada di DB
  - Alihkan ke OTP verify flow (type: `forgot`) alih-alih kirim reset link email
  - Setelah OTP terverifikasi, redirect ke halaman reset password

### UI: Form Input — Request OTP
| Field | Tipe Input | Required | Notes |
|-------|-----------|----------|-------|
| email | `<input type="email">` | Yes | Format email, must exist in users |
| type | `<input type="hidden">` | Yes | `register` / `forgot` / `login` |

### UI: Form Input — Verify OTP
| Field | Tipe Input | Required | Notes |
|-------|-----------|----------|-------|
| otp[] | 6x `<input maxlength="1" inputmode="numeric">` | Yes | Auto-focus ke next input |
| email | `<input type="hidden">` | Yes | Dari session |
| type | `<input type="hidden">` | Yes | Dari session |

### Data Tampilan — Pesan Status
| Kondisi | Pesan |
|---------|-------|
| OTP terkirim | "Kode OTP telah dikirim ke email Anda." |
| OTP valid | "Verifikasi berhasil." |
| OTP expired | "Kode OTP telah kedaluwarsa. Silakan minta kode baru." |
| OTP salah | "Kode OTP tidak valid." |
| Resend cooldown | "Silakan tunggu {detik} detik sebelum mengirim ulang." |
| Email tidak ditemukan | "Email tidak terdaftar." |

---

## Modul F: Compare Feature Enhancement

### F.1 Controller Baru — `App\Http\Controllers\CompareController`

| Method | HTTP | Route | Fungsi |
|--------|------|-------|--------|
| `index()` | GET | `/compare` | Tampilkan halaman compare, baca dari session |
| `add(Request)` | POST | `/compare/add` | Tambah laptop ID ke session (max 3), return JSON |
| `remove($id)` | DELETE | `/compare/remove/{laptop}` | Hapus laptop ID dari session, return JSON |
| `clear()` | DELETE | `/compare/clear` | Kosongkan session compare, return JSON |

**Logika add**:
1. Validasi: laptop ID exists di DB
2. Ambil array dari `session('compare', [])`
3. Jika `count >= 3` dan ID belum ada → return `{success: false, message: "Maksimal 3 produk"}` 
4. Jika ID sudah ada → return `{success: false, message: "Sudah dalam daftar"}`
5. Tambah ID ke array, simpan ke session
6. Return `{success: true, message: "Ditambahkan", count: n}`

**Logika remove**:
1. Ambil array dari `session('compare', [])`
2. Filter ID yang tidak sama
3. Simpan ke session
4. Return `{success: true, message: "Dihapus", count: n}`

### F.2 Routes (di `routes/web.php`)
```php
// Ganti route lama
Route::get('/compare', [CompareController::class, 'index'])->name('landing.compare');
Route::post('/compare/add', [CompareController::class, 'add'])->name('compare.add');
Route::delete('/compare/remove/{laptop}', [CompareController::class, 'remove'])->name('compare.remove');
Route::delete('/compare/clear', [CompareController::class, 'clear'])->name('compare.clear');

// Hapus dari LaptopController
// Route::get('/compare', [LaptopController::class, 'compare'])->name('landing.compare'); // DELETE
```

### F.3 Update View — `landing.compare.blade.php`
**Perubahan**:
- Baca data dari session (`session('compare', [])`)
- Query laptop: `Laptop::whereIn('id', $compareIds)->get()`
- Tampilkan grid perbandingan dinamis (bukan hardcoded 2 produk)
- Layout: 2 kolom untuk 2 produk, 3 kolom untuk 3 produk
- Kolom kosong: "Tambah produk untuk dibandingkan"

### F.4 Hapus Method Lama
**File**: `app/Http/Controllers/LaptopController.php`
- Hapus method `compare()` (baris 57-67)

### F.5 CSS/JS
**File**: `public/js/compare.js`
Fungsi:
- `addToCompare(laptopId)` → POST `/compare/add`
- `removeFromCompare(laptopId)` → DELETE `/compare/remove/{id}`
- `clearCompare()` → DELETE `/compare/clear`
- Update floating widget badge
- Toast notification feedback

---

## Modul G: Admin Customers Enhancement

### G.0 SoftDeletes untuk User (PRASYARAT)
**Migration**: `add_deleted_at_to_users_table` (+ kolom `deleted_at` nullable timestamp)
**Model**: Tambah `use SoftDeletes` trait ke `User.php`

### G.1 Controller Baru — `App\Http\Controllers\Admin\CustomerController`

| Method | HTTP | Route | Fungsi |
|--------|------|-------|--------|
| `index(Request)` | GET | `/admin/customers` | List customers dengan search & filter |
| `show(User)` | GET | `/admin/customers/{user}` | Detail customer (orders, spending, reviews) |

**Method index()**:
- Query: `User::query()` (SoftDeletes otomatis filter `deleted_at IS NULL`)
- Filter:
  - `search`: cari by name/email (`LIKE`)
  - `status`: `all` / `active` (email_verified_at NOT NULL) / `inactive` (email_verified_at IS NULL)
- `withCount('orders')` — jumlah order tiap user
- Pagination: 20 per page
- Data ke view: `$users`, `$search`, `$statusFilter`

**Method show()**:
- Ambil user dengan eager load: `orders.items`, `reviews.laptop`
- Hitung: `totalSpending` (sum of order totals), `orderCount`, `reviewCount`, `lastOrderDate`
- Data ke view: `$customer`, `$orders`, `$reviews`, `$totalSpending`

### G.2 Routes (di `routes/web.php`)
```php
// Ganti inline closure dengan controller
Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::get('/customers/{user}', [CustomerController::class, 'show'])->name('customers.show');

// Hapus closure lama:
// Route::get('/customers', function () { ... })->name('customers.index'); // DELETE
```

### G.3 Views

#### Index — `admin.customers.index` (UPDATE)
```
┌───────────────────────────────────────────────────────────────┐
│ Customers                                     [Total: 120]    │
├───────────────────────────────────────────────────────────────┤
│ ┌───────────────────────────────────────────────────────────┐│
│ │ [Search...]          [Status ▼]                           ││
│ └───────────────────────────────────────────────────────────┘│
│ ┌────────┬────────────┬──────────┬──────────┬────────┬──────┐│
│ │ Name   │ Email      │ Status   │ Since    │ Orders │ Act. ││
│ ├────────┼────────────┼──────────┼──────────┼────────┼──────┤│
│ │ Budi   │ budi@m.    │ Active   │ Jan 25   │ 5      │ View ││
│ │ Sari   │ sari@m.    │ Inactive │ Mar 25   │ 2      │ View ││
│ └────────┴────────────┴──────────┴──────────┴────────┴──────┘│
│ [Pagination 20]                                              │
└───────────────────────────────────────────────────────────────┘
```

**Data Card — Customer Index**:
| Elemen | Sumber Data | Tipe |
|--------|------------|------|
| Name | `$user->name` | Text, link ke show |
| Email | `$user->email` | Text |
| Status | `$user->email_verified_at ? 'Active' : 'Inactive'` | Badge (Active: emerald, Inactive: gray) |
| Since | `$user->created_at->format('M Y')` | Date |
| Orders | `$user->orders_count` | Number (withCount) |
| Actions | Link ke `show` | Icon/button |

#### Show — `admin.customers.show` (BARU)
```
┌───────────────────────────────────────────────────────────────┐
│ Customer Detail: Budi Santoso          [← Back to Customers]  │
├───────────────────────────────────────────────────────────────┤
│ ┌───────────────────────────────────────────────────────────┐│
│ │  PROFILE                          STATUS                  ││
│ │  Name: Budi Santoso               Email: verified ✓      ││
│ │  Email: budi@email.com            Member since: Jan 2025  ││
│ │  Role: buyer                      Last active: 2 days ago ││
│ └───────────────────────────────────────────────────────────┘│
│                                                               │
│ ┌───────┬──────────┬──────────┐                               │
│ │Orders │ Spending │ Reviews  │                               │
│ │  5    │ Rp 45jt  │  3       │                               │
│ └───────┴──────────┴──────────┘                               │
│                                                               │
│ ★ Recent Orders (5)                                           │
│ ┌──────┬──────────┬──────────┬──────────┬────────┐          ││
│ │Order │ Date     │ Items    │ Total    │ Status │          ││
│ ├──────┼──────────┼──────────┼──────────┼────────┤          ││
│ │#INV-1│ 12 Jan   │ 2 items  │ Rp 25jt  │completed│          ││
│ └──────┴──────────┴──────────┴──────────┴────────┘          ││
│                                                               │
│ ★ Recent Reviews (3)                                          │
│ ┌──────────┬──────────┬───────┬──────────────────────┐      ││
│ │ Product  │ Rating   │ Date  │ Review               │      ││
│ ├──────────┼──────────┼───────┼──────────────────────┤      ││
│ │ ThinkPad │ ★★★★★    │ Jan   │ "Great laptop..."   │      ││
│ └──────────┴──────────┴───────┴──────────────────────┘      ││
└───────────────────────────────────────────────────────────────┘
```

### G.4 Form Input — Search & Filter
| Field | Tipe Input | Required | Notes |
|-------|-----------|----------|-------|
| search | `<input type="text" placeholder="Cari nama/email...">` | No | LIKE query |
| status | `<select>` | No | `all`, `active`, `inactive` (active = email_verified_at not null) |

---

## Modul H: Compare Landing Integration

### H.1 Floating Compare Widget
**File baru**: `resources/views/components/floating-compare.blade.php`

Tetap menggunakan session-based storage (`/compare/add` AJAX).

**UI**:
```
                    ┌───────────────────────┐
                    │ ⚖ Compare (2/3)       │  ← fixed bottom-right z-50
                    │ [View]  [Clear]        │     bg-white shadow-xl rounded-2xl
                    └───────────────────────┘
                    Badge counter di icon navbar juga
```

**Kondisi**:
- Hanya tampil jika `count > 0`
- Jika `count === 0`, widget sembunyi
- Badge: bulat merah putih di pojok icon

### H.2 Product Card — Tombol Compare
**File**: `resources/views/landing/home.blade.php` dan `resources/views/landing/search.blade.php`

Update tombol compare yang sudah ada:
```html
<button onclick="addToCompare('{{ $laptop->id }}')"
        data-compare-btn
        data-laptop-id="{{ $laptop->id }}"
        class="compare-btn ...">
    <iconify-icon icon="solar:scale-linear"></iconify-icon>
</button>
```

### H.3 JavaScript — `public/js/compare.js`
**Event handlers**:
1. `click .compare-btn` → `POST /compare/add` → update widget + toast
2. `click .compare-remove` → `DELETE /compare/remove/{id}` → update widget + toast
3. `click .compare-clear` → `DELETE /compare/clear` → sembunyikan widget

**Fungsi AJAX**:
```js
function addToCompare(laptopId) {
    fetch('/compare/add', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken},
        body: JSON.stringify({laptop_id: laptopId})
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            updateFloatingCompare(data.count);
        } else {
            showToast(data.message, 'error');
        }
    });
}
```

### H.4 Toast Notification
**Reuse** dari existing `showToast()` di home.blade.php, pisahkan ke file sendiri.

### H.5 Include di Layout
- `layouts/landing.blade.php`: include `compare.js` di `@push('scripts')`
- `landing/home.blade.php` & `landing/search.blade.php`: include `floating-compare` component

---

## Dependency Graph
```
Modul E (OTP Auth)
   ├── E.1 Migration user_otps_table
   ├── E.2 Model Otp
   ├── E.3 Update User model (relasi otps)
   ├── E.4 Controller OtpController
   ├── E.5 Notification OtpNotification + email template
   ├── E.6 Views (otp-request, otp-verify)
   ├── E.7 Routes (auth.php)
   ├── E.8 Update RegisteredUserController
   └── E.9 Update PasswordResetLinkController

Modul F (Compare Enhancement)
   ├── F.1 CompareController (index/add/remove/clear)
   ├── F.2 Routes update (web.php)
   ├── F.3 Update compare.blade.php (dynamic grid)
   ├── F.4 Hapus compare() dari LaptopController
   └── F.5 compare.js + CSS

Modul G (Admin Customers)
   ├── G.0 Migration: add_deleted_at_to_users (SoftDeletes)
   ├── G.0 Update User model (+ SoftDeletes trait + relasi otps)
   ├── G.1 CustomerController (index/show)
   ├── G.2 Routes update (web.php)
   ├── G.3 Update index.blade.php (search/filter, status, orders count)
   └── G.4 Show.blade.php (baru — profile, stats, orders, reviews)

Modul H (Landing Widget)
   ├── H.1 Floating compare component
   ├── H.2 Update product cards (home + search)
   ├── H.3 compare.js (AJAX integration)
   ├── H.4 Toast notification
   └── H.5 Include in layouts

Cross-module dependencies:
   H (Landing Widget) ── depends on ──→ F (CompareController + session)
   E.8 (Register OTP) ── depends on ──→ E.1, E.2, E.4, E.5
   E.9 (Forgot OTP) ── depends on ──→ E.1, E.2, E.4, E.5
   
   Tidak ada dependency silang antara E, F, G.
   H tergantung pada F (session + controller).
```

## Urutan Pengerjaan

### Tahap 1: OTP Authentication (Modul E)
1. **E.1** — Buat migration `create_user_otps_table`
2. **E.2** — Buat model `Otp`
3. **E.3** — Update `User.php`: tambah relasi `otps()`
4. **E.5** — Buat `OtpNotification` + email template Blade
5. **E.4** — Buat `OtpController` dengan method `showRequestForm`, `requestOtp`, `showVerifyForm`, `verifyOtp`, `resendOtp`
6. **E.6** — Buat view `auth.otp-request.blade.php` dan `auth.otp-verify.blade.php`
7. **E.7** — Tambah route OTP di `routes/auth.php`
8. **E.8** — Update `RegisteredUserController@store`: setelah create user → redirect ke OTP verify
9. **E.9** — Update `PasswordResetLinkController@store`: arahkan ke OTP flow

### Tahap 2: Compare Enhancement (Modul F)
10. **F.1** — Buat `CompareController`
11. **F.2** — Update `routes/web.php`: ganti route compare, tambah AJAX routes
12. **F.4** — Hapus method `compare()` dari `LaptopController`
13. **F.3** — Update `landing/compare.blade.php`: baca dari session, grid dinamis
14. **F.5** — Buat `public/js/compare.js`

### Tahap 3: Admin Customers (Modul G)
15. **G.0a** — Migration `add_deleted_at_to_users_table` (+ SoftDeletes trait ke User)
16. **G.1** — Buat `Admin\CustomerController`
17. **G.2** — Update `routes/web.php`: ganti closure
18. **G.3** — Update `admin/customers/index.blade.php`: search bar, filter status, kolom Status + Orders count
19. **G.4** — Buat `admin/customers/show.blade.php`

### Tahap 4: Landing Widget (Modul H)
19. **H.1** — Buat `components/floating-compare.blade.php`
20. **H.2** — Update `landing/home.blade.php` dan `landing/search.blade.php`: tombol compare via AJAX + widget include
21. **H.3** — Update `compare.js`: integrasi AJAX dengan floating widget
22. **H.4** — Pisahkan `showToast()` ke file global, update existing usages
23. **H.5** — Include `compare.js` di layout

### Tahap 5: Review & Polish
24. Test all OTP flows (register → OTP → verify, forgot → OTP → reset)
25. Test compare (add/remove/clear via AJAX, floating widget, halaman compare)
26. Test admin customers (search, filter, show detail)
27. Test responsive: floating widget di mobile
28. Test edge cases: OTP expired, compare max 3, customer tanpa order

## Definisi Selesai
- ✅ User bisa register dengan verifikasi OTP via email (bukan langsung login)
- ✅ User bisa reset password via OTP (bukan link email tradisional)
- ✅ OTP memiliki masa berlaku 5 menit, maks 3 percobaan
- ✅ Tombol resend OTP dengan cooldown 60 detik
- ✅ Compare menggunakan session storage (bukan query string/localStorage)
- ✅ Compare AJAX: add/remove/clear tanpa reload halaman
- ✅ Floating compare widget muncul otomatis saat ada item
- ✅ Halaman compare menampilkan grid dinamis (2-3 produk)
- ✅ Admin customers: search by name/email, filter by active/inactive status
- ✅ Admin customer index: Name, Email, Status badge (Active/Inactive), Since, Orders count, Actions
- ✅ Admin customer detail: profile, orders, spending, reviews
- ✅ Badge counter compare di navbar (jika ada)
- ✅ Toast notification untuk setiap aksi compare
- ✅ Tidak ada broken route/error di semua halaman
