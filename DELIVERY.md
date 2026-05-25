# DELIVERY — ZLM-ID Laravel 13.x

## Cara Run Project

```bash
# 1. Serve aplikasi
php artisan serve

# 2. Link storage (jika belum)
php artisan storage:link

# 3. Seed database (jika perlu reset)
php artisan db:seed

# 4. Run tests
php artisan test
```

## Daftar Endpoint / Halaman

### Landing (Public)
| Halaman | Route |
|---------|-------|
| Home | `/` |
| Search / Catalog | `/search?q=&category=...` |
| Detail Laptop | `/laptops/{laptop:slug}` |
| Compare | `/compare?ids=...` |
| Cart | `/cart` |
| Checkout | `/checkout` |
| Wishlist | `/wishlist` |
| Orders | `/orders` |
| Order Detail | `/orders/{order}` |
| Login | `/login` |
| Register | `/register` |

### Admin
| Halaman | Route |
|---------|-------|
| Dashboard | `/admin` |
| Laptops CRUD | `/admin/laptops` |
| Laptop Detail | `/admin/laptops/{laptop}` |
| Laptop Variants | `/admin/laptops/{laptop}/variants` |
| Variant Edit (shallow) | `/admin/variants/{variant}/edit` |
| Categories CRUD | `/admin/categories` |
| Orders | `/admin/orders` |

## Hal yang Perlu Dikonfigurasi User

### 1. Storage Link
```bash
php artisan storage:link
```
Pastikan folder berikut ada di `storage/app/public/`:
- `laptops/`
- `variants/`
- `categories/`

### 2. Environment
File `.env` harus berisi:
- Database MySQL credentials
- `APP_URL=http://localhost:8000` (untuk storage path resolution)

### 3. Role & Permission
Seeder akan membuat role: `admin`, `buyer`
```bash
php artisan db:seed
```

### 4. Rekomendasi Produksi
- Ubah storage disk dari `public` ke `s3` jika di production
- Konfigurasi queue untuk order processing
- Set `APP_DEBUG=false` di production

## Fitur Baru di Release Ini

### ✨ Drag & Drop Image Upload
- Admin Laptop, Variant, dan Category: ganti dari input URL teks → dropzone drag & drop
- Vanilla JS (zero dependency), reusable partial `_image_upload.blade.php`
- Validasi: image|mimes:jpg,jpeg,png,webp|max:2048
- Auto-delete file lama saat update/delete
- Tombol "Remove" didukung penuh (hapus image dari DB + storage)

### ✨ Kelebihan & Kekurangan
- Data sample untuk 12 laptop (Trix HTML format)
- Ditampilkan di halaman detail produk
- Admin bisa edit via form laptop

### ✨ image_url_full Accessor
- Semua model (Laptop, LaptopVariant, Category) punya `$appends = ['image_url_full']`
- Backward compatible: support path storage maupun URL eksternal
- Semua view landing sudah menggunakan `image_url_full`

### ✨ Route & Bug Fixes
- Route order: `show` setelah `resource()` untuk cegah 404
- Shallow routing di variant: `admin.variants.*` untuk edit/update/destroy
- 50/50 test passing (116 assertions)
