# DELIVERY.md — ZLM.ID

## Cara Run Project

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
copy .env.example .env
# Edit .env: DB_DATABASE=zlm_id, DB_USERNAME=root, DB_PASSWORD=

# 3. Generate key & migrate
php artisan key:generate
php artisan migrate --seed

# 4. Link storage
php artisan storage:link

# 5. Run dev server
composer dev
# atau manual:
# php artisan serve (terminal 1)
# npm run dev    (terminal 2)
```

## Daftar Endpoint / Halaman

### Landing Pages (Public)
| URL | Nama Route | Deskripsi |
|-----|-----------|-----------|
| `/` | `landing.home` | Homepage — Hero, Featured Products, Categories, Testimonials, Blog, Newsletter |
| `/search` | `landing.search` | Katalog dengan filter (kategori, brand, harga) + sort (latest, price asc/desc) |
| `/laptop/{id}` | `landing.detail` | Detail produk — gambar, spesifikasi, kelebihan/kekurangan, review, produk similar |
| `/compare` | `landing.compare` | Perbandingan produk (max 3) |
| `/articles` | `landing.articles` | Artikel & panduan (statis) |

### Cart & Checkout (Auth required)
| URL | Nama Route | Deskripsi |
|-----|-----------|-----------|
| `/cart` | `cart.index` | Keranjang belanja |
| `/checkout` | `landing.checkout` | Checkout dengan form alamat |
| `/orders` | `orders.history` | Riwayat pesanan |
| `/orders/{order}` | `orders.confirmation` | Konfirmasi pesanan |

### Auth (Breeze)
| URL | Nama Route | Deskripsi |
|-----|-----------|-----------|
| `/login` | `login` | Login |
| `/register` | `register` | Register |
| `/profile` | `profile.edit` | Edit profile |
| `/wishlist` | `wishlist.index` | Wishlist user |

### Admin (Role: admin required)
| URL | Nama Route | Deskripsi |
|-----|-----------|-----------|
| `/admin` | `admin.dashboard` | Dashboard — stats produk, users, orders |
| `/admin/laptops` | `admin.laptops.index` | CRUD Laptop |
| `/admin/laptops/create` | `admin.laptops.create` | Create Laptop (Trix Editor) |
| `/admin/laptops/{id}/edit` | `admin.laptops.edit` | Edit Laptop |
| `/admin/laptops/{id}` | `admin.laptops.show` | Detail Laptop (spesifikasi, varian, kelebihan/kekurangan) |
| `/admin/laptops/{id}/variants` | `admin.laptops.variants.index` | Manage varian laptop |
| `/admin/categories` | `admin.categories.index` | CRUD Kategori |
| `/admin/orders` | `admin.orders.index` | Daftar order + update status |
| `/admin/customers` | `admin.customers.index` | Daftar customer |
| `/admin/customers/{id}` | `admin.customers.show` | Detail customer |

## API Endpoints (JSON)
| Method | URL | Deskripsi |
|--------|-----|-----------|
| POST | `/compare/add` | Tambah produk ke perbandingan |
| DELETE | `/compare/remove/{id}` | Hapus dari perbandingan |
| DELETE | `/compare/clear` | Kosongkan perbandingan |
| GET | `/compare/ids` | Ambil ID produk yang dibandingkan |
| GET | `/compare/products?search=` | Cari produk untuk modal compare |
| POST | `/wishlist/toggle` | Toggle wishlist (auth) |
| POST | `/laptop/{id}/reviews` | Submit review (auth) |

## Fitur yang Baru Saja Ditambahkan
| Fitur | Status |
|-------|--------|
| ✅ Rupiah currency di semua halaman | Selesai |
| ✅ Reviews + form di detail produk | Selesai |
| ✅ Stock deduction saat order | Selesai |
| ✅ Checkout form alamat (Indonesia) | Selesai |
| ✅ Sort dropdown fungsional | Selesai |
| ✅ Mobile menu off-canvas | Selesai |
| ✅ Compare via session (konsisten) | Selesai |
| ✅ Admin order status management | Selesai |
| ✅ Admin sidebar mobile | Selesai |
| ✅ Footer 3 kolom | Selesai |
| ✅ Fix 404 create produk (route order) | Selesai |

## Yang Perlu Dikonfigurasi User
1. **Database**: Buat database `zlm_id` MySQL, atur `.env`
2. **Role**: Jalankan `php artisan db:seed --class=RoleSeeder` untuk role admin
3. **Storage**: `php artisan storage:link` untuk file upload
4. **Email**: Atur `MAIL_*` di `.env` untuk notifikasi OTP & order
5. **Logo**: Pastikan file `public/assets/logo.png` ada
6. **Images**: Produk menggunakan URL eksternal (Unsplash/placeholder)
