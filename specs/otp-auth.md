# Spesifikasi Teknis: Modul E — OTP Authentication

## Deskripsi
Modul OTP Authentication menggantikan verifikasi email tradisional (Laravel Breeze `RegisteredUserController` + `VerifyEmailController`) dengan sistem OTP 6 digit via email. Juga mengubah flow forgot password dari link reset email menjadi verifikasi OTP.

## Tujuan
1. Setelah register, user **tidak langsung login** — harus verifikasi OTP via email
2. Forgot password menggunakan OTP verifikasi dulu, baru masuk ke form reset password
3. OTP 6 digit, berlaku 5 menit, maksimal 3 percobaan
4. Resend OTP dengan cooldown 60 detik

## File yang Dibuat

### Migration & Model
- `database/migrations/YYYY_MM_DD_HHMMSS_add_deleted_at_to_users_table.php` — BARU (SoftDeletes untuk User)
- `database/migrations/YYYY_MM_DD_HHMMSS_create_user_otps_table.php` — BARU
- `app/Models/Otp.php` — BARU (wajib SoftDeletes)
- `app/Notifications/OtpNotification.php` — BARU

## Kebijakan SoftDelete
- Otp model WAJIB menggunakan `SoftDeletes` trait
- User model perlu migration tambahan `deleted_at` karena belum SoftDeletes

### Controller
- `app/Http/Controllers/Auth/OtpController.php` — BARU

### Views
- `resources/views/auth/otp-request.blade.php` — BARU
- `resources/views/auth/otp-verify.blade.php` — BARU
- `resources/views/emails/otp.blade.php` — BARU

## File yang Dimodifikasi
| File | Perubahan |
|------|-----------|
| `app/Models/User.php` | Tambah relasi `otps(): HasMany` |
| `app/Http/Controllers/Auth/RegisteredUserController.php` | Ganti login flow → redirect ke OTP verify |
| `app/Http/Controllers/Auth/PasswordResetLinkController.php` | Ganti send reset link → redirect ke OTP verify |
| `routes/auth.php` | Tambah route OTP group |

## Struktur Database

### Tabel `user_otps`
| Kolom | Tipe | Nullable | Default | Keterangan |
|-------|------|----------|---------|------------|
| id | `uuid()` | No | — | Primary key (HasUuids) |
| user_id | `foreignUuid('user_id')->constrained()->cascadeOnDelete()` | No | — | Foreign key ke users |
| otp | `string(6)` | No | — | 6 digit angka |
| type | `string(20)` | No | — | `register`, `forgot`, `login` |
| expires_at | `timestamp` | No | — | `now() + 5 minutes` |
| used_at | `timestamp` | Yes | null | Diisi saat diverifikasi |
| created_at | `timestamp` | Yes | null | Default Laravel |
| updated_at | `timestamp` | Yes | null | Default Laravel |

**Indexes**:
- `user_id + type + expires_at` — composite index untuk lookup cepat

## Model: `App\Models\Otp`
```php
class Otp extends Model
{
    use HasUuids;

    protected $fillable = ['user_id', 'otp', 'type', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    public function isValid(): bool
    {
        return !$this->isExpired() && !$this->isUsed();
    }
}
```

## Controller: `OtpController`

### `showRequestForm()`
- GET `/otp/request`
- Return view `auth.otp-request`
- Bawa `type` dari session/query (auto-fill hidden input)

### `requestOtp(Request)`
- POST `/otp/request`
- Validasi: `email` required|exists:users, `type` required|in:register,forgot,login
- Generate: 6 digit random
- Hapus OTP lama untuk user+type yang belum used
- Simpan OTP baru
- Kirim `OtpNotification`
- Redirect ke `route('otp.verify')` dengan session `['otp_email' => $email, 'otp_type' => $type]`

### `showVerifyForm()`
- GET `/otp/verify`
- Cek session `otp_email` dan `otp_type`
- Jika tidak ada, redirect ke `otp.request`
- Return view `auth.otp-verify` dengan `$email` dan `$type`

### `verifyOtp(Request)`
- POST `/otp/verify`
- Validasi: `email` required, `otp` required|digits:6, `type` required
- Cari OTP: `where(['user_id' => $user->id, 'otp' => $request->otp, 'type' => $request->type, 'used_at' => null])->where('expires_at', '>', now())->first()`
- Jika tidak valid → back with error "Kode OTP tidak valid atau telah kedaluwarsa."
- Set `used_at = now()`, save
- Switch by type:
  - `register`: redirect ke login → `with('status', 'Akun berhasil diverifikasi. Silakan login.')`
  - `forgot`: redirect ke `password.reset` dengan token di session
  - `login`: `Auth::login($user)`, redirect ke dashboard

### `resendOtp(Request)`
- POST `/otp/resend`
- Validasi: `email` required, `type` required
- Cek cooldown: OTP terakhir untuk user+type harus dibuat > 60 detik lalu
- Jika cooldown → return error "Silakan tunggu 60 detik"
- Hapus OTP lama, generate baru, kirim ulang

## UI Design

### Halaman Request OTP (auth.otp-request)
```
┌──────────────────────────────────┐
│  [LOGO]                          │
│                                  │
│  Verifikasi Akun                 │
│  Masukkan email Anda untuk       │
│  menerima kode OTP               │
│                                  │
│  ┌────────────────────────────┐  │
│  │ 📧 email@example.com      │  │
│  └────────────────────────────┘  │
│                                  │
│  ┌────────────────────────────┐  │
│  │   Kirim Kode OTP           │  │
│  └────────────────────────────┘  │
│                                  │
│  Sudah punya kode?               │
│  [Masukkan kode]                 │
└──────────────────────────────────┘
```

### Halaman Verify OTP (auth.otp-verify)
```
┌──────────────────────────────────┐
│  [LOGO]                          │
│                                  │
│  Masukkan Kode OTP               │
│  Kami telah mengirim kode ke     │
│  email@example.com               │
│                                  │
│  ┌────────────────────────────┐  │
│  │ [_][_][_][_][_][_]        │  │
│  │ 6 digit, auto-next         │  │
│  └────────────────────────────┘  │
│                                  │
│  ┌────────────────────────────┐  │
│  │   Verifikasi               │  │
│  └────────────────────────────┘  │
│                                  │
│  Tidak menerima kode?            │
│  [Kirim Ulang] 00:45             │
│                                  │
│  [← Gunakan email lain]          │
└──────────────────────────────────┘
```

## Detail Form Input

### Request OTP
| Field | Tipe Input | Required | Validasi | Notes |
|-------|-----------|----------|----------|-------|
| email | `<input type="email">` | Yes | `required|email|exists:users` | |
| type | `<input type="hidden">` | Yes | `required|in:register,forgot,login` | Dikirim dari form |

### Verify OTP
| Field | Tipe Input | Required | Validasi | Notes |
|-------|-----------|----------|----------|-------|
| otp[] | 6x `<input maxlength="1" inputmode="numeric">` | Yes | `required|digits:6` | Auto-focus, paste support |
| email | `<input type="hidden">` | Yes | — | Dari session |
| type | `<input type="hidden">` | Yes | — | Dari session |

## Data Tampilan

### Email Template (emails.otp)
```
+====================================+
|  ZLM.ID                            |
|  Kode Verifikasi OTP               |
+====================================+
|                                    |
|  Halo, {name}!                     |
|                                    |
|  Gunakan kode berikut untuk        |
|  memverifikasi akun Anda:          |
|                                    |
|  ╔══════════════════════════╗      |
|  ║     4 8 2 9 1 7          ║      |
|  ╚══════════════════════════╝      |
|                                    |
|  Kode berlaku 5 menit.             |
|  Abaikan jika bukan Anda.          |
|                                    |
+====================================+
```

### Pesan Status
| Kondisi | Tipe | Pesan |
|---------|------|-------|
| OTP terkirim | success | "Kode OTP telah dikirim ke email Anda." |
| Verifikasi berhasil | success | "Verifikasi berhasil. Silakan {login/masuk}" |
| OTP expired | error | "Kode OTP telah kedaluwarsa. Silakan minta kode baru." |
| OTP salah | error | "Kode OTP tidak valid. {sisa} percobaan tersisa." |
| Percobaan habis | error | "Terlalu banyak percobaan. Silakan minta kode baru." |
| Resend cooldown | error | "Silakan tunggu {detik} detik sebelum mengirim ulang." |
| Email tidak ditemukan | error | "Email tidak terdaftar." |

## Integrasi

### Register Flow (Baru)
```
Register form → POST /register → User::create()
  → Hapus event(new Registered)
  → Hapus Auth::login
  → Simpan OTP di DB
  → Kirim email OTP
  → Redirect ke /otp/verify (type: register)
  → User input 6 digit → POST /otp/verify
  → Valid → redirect ke login
  → User login manual dengan email+password
```

### Forgot Password Flow (Baru)
```
Forgot form → POST /forgot-password
  → Validasi email exists
  → Simpan OTP di DB
  → Kirim email OTP
  → Redirect ke /otp/verify (type: forgot)
  → User input 6 digit → POST /otp/verify
  → Valid → redirect ke /reset-password/{token}
  → User input password baru
```

## Daftar Lengkap File

### Baru (6 file)
1. `database/migrations/..._create_user_otps_table.php`
2. `app/Models/Otp.php`
3. `app/Notifications/OtpNotification.php`
4. `app/Http/Controllers/Auth/OtpController.php`
5. `resources/views/auth/otp-request.blade.php`
6. `resources/views/auth/otp-verify.blade.php`
7. `resources/views/emails/otp.blade.php`

### Dimodifikasi (4 file)
1. `app/Models/User.php` — tambah relasi
2. `app/Http/Controllers/Auth/RegisteredUserController.php` — ganti flow
3. `app/Http/Controllers/Auth/PasswordResetLinkController.php` — ganti flow
4. `routes/auth.php` — tambah route

## Urutan Pengerjaan
1. Migration `user_otps`
2. Model `Otp`
3. Update User model
4. `OtpNotification` + email template
5. `OtpController` (all methods)
6. Views (request + verify)
7. Routes
8. Update RegisteredUserController
9. Update PasswordResetLinkController
10. Test all flows

## Definisi Selesai
- ✅ Register → OTP terkirim ke email → verify → redirect ke login (bukan auto-login)
- ✅ Forgot password → OTP terkirim → verify → reset password form
- ✅ OTP valid 5 menit, 1x pakai
- ✅ Maksimal 3 percobaan gagal, setelah itu OTP invalid
- ✅ Resend OTP dengan cooldown 60 detik
- ✅ Auto-focus antar input 6 digit OTP
- ✅ Paste support untuk kode OTP
- ✅ Email template responsive, menampilkan kode dengan jelas
