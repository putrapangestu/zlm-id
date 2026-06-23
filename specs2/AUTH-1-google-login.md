# AUTH-1: Login dengan Google

## Tujuan
User bisa login/register menggunakan akun Google mereka.

## Implementasi

### 1. Install Socialite
```bash
composer require laravel/socialite
```

### 2. Config (`config/services.php`)
```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URL'),
],
```

### 3. Migration — Tambah kolom ke users
`database/migrations/xxxx_add_google_id_to_users_table.php`
- `google_id` (string, nullable, unique)
- `avatar` (string, nullable)

### 4. Controller Baru: `app/Http/Controllers/Auth/GoogleController.php`
Methods:
- `redirect()` — redirect ke Google OAuth
- `callback()` — handle callback dari Google

### 5. Routes (`routes/auth.php`)
```php
Route::get('auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'callback']);
```

### 6. View — Tambah tombol Google di login & register
`resources/views/auth/login.blade.php`
`resources/views/auth/register.blade.php`

### 7. .env — Tambah key
```
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URL=/auth/google/callback
```

## Flow
1. User klik "Login with Google"
2. Redirect ke Google OAuth consent screen
3. User approve → callback ke aplikasi
4. Cari user by google_id ATAU email
5. Jika ada → login
6. Jika belum ada → create user baru, assign role 'user', login

## Definisi Selesai
- [x] Socialite terinstall
- [x] Google login button di login page
- [x] Google login button di register page
- [x] Flow login berfungsi (new user + existing user)
- [x] Avatar tersimpan dari Google
