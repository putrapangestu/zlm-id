# PLAN — Perbaikan Fitur ZLM.ID (Juni 2026)

## Ringkasan Issue dari User

| # | Issue | Prioritas |
|---|-------|-----------|
| 1 | Barang per variant — tidak ada tampilan gambar/spec per variant | 🔴 High |
| 2 | Gambar produk cuma 1, gabisa banyak | 🔴 High |
| 3 | Compare jelek: `removeFromCompare()` gak ada, sync localStorage kacau, tombol di detail gak perlu | 🔴 High |
| 4 | Tombol salin (copy link) dan bagikan (share) gak jalan | 🟡 Medium |
| 5 | Tombol add product untuk compare di modal gak jalan | 🔴 High |

## Tech Stack

- **Backend**: Laravel 11 (PHP 8.x)
- **Frontend**: Tailwind CSS (CDN), Iconify, Vanilla JS
- **Database**: MySQL (via Laravel migrations)
- **Storage**: Laravel Storage (public disk)

---

## Modul & Urutan Pengerjaan

### Modul A — Multi-Image Gallery untuk Laptop
**Dependency**: None  
**Agent**: Backend Builder  

**Problem**: Laptop cuma punya 1 `image_url`, detail page pake 3 gambar Unsplash hardcoded.  
**Solusi**: Migration baru `laptop_images`, update Admin LaptopController untuk upload banyak gambar, update detail view jadi gallery dinamis.

Yang harus dibuat/diubah:
- Migration: `create_laptop_images_table` (id uuid, laptop_id FK, image_url, sort_order, caption nullable)
- Model: `App\Models\LaptopImage`
- Laptop model: tambah `hasMany LaptopImage`, hapus `$appends = ['image_url_full']` -> pindah ke accessor biasa
- Admin LaptopController: tambah upload multiple images (dropzone)
- Admin create/edit view: tambah multi-image upload component
- Detail view: ganti 3 Unsplash hardcoded dengan dynamic images dari laptop_images
- Tambah image deletion di admin

**Definition of Done**:
- Migration bisa dijalankan
- Admin bisa upload multiple images per laptop
- Detail page menampilkan gambar-gambar tersebut sebagai gallery
- Gambar bisa dihapus dari admin

---

### Modul B — Admin Variant Management + Variant Switching di Detail Page
**Dependency**: Modul A (untuk image variant)  
**Agent**: Fullstack Builder  

**Problem Ganda**:
1. **Admin**: Variant section di halaman show laptop hanya muncul kalau sudah ada variant. User bingung "variant-nya ada dimana?" karena ketika laptop belum punya variant, tidak ada tombol/tau untuk add variant.
2. **Frontend**: Pilih variant cuma update price, gambar dan spec table tidak berubah.

#### Bagian 1 — Admin: Variant Section Selalu Muncul

Di `resources/views/admin/laptops/show.blade.php`:
- Hapus kondisi `@if ($laptop->variants->count() > 0)` — section Variants harus **selalu tampil**
- Ketika variants > 0: tampilkan table variant + link "Manage Variants"
- Ketika variants == 0: tampilkan empty state + tombol "Add Variant" yang menuju `route('admin.laptops.variants.create', $laptop)`

#### Bagian 2 — Frontend: Update Variant Switching

Di `resources/views/landing/detail.blade.php`:
- Tambah data-variant attributes pada tiap variant option (image, ram, storage, graphics, display, weight, battery, stock)
- Tambah JS handler yang update:
  - Main image (src)
  - Spec table values
  - Stock badge
  - Price
- Kalau variant tanpa override field tertentu, pakai data laptop default

**Definition of Done**:
- Admin: halaman show laptop selalu menampilkan variant section
- Admin: ada tombol "Add Variant" ketika belum ada variant
- Admin: ada link "Manage Variants" ketika sudah punya variant
- Frontend: pilih variant → gambar utama berganti
- Frontend: pilih variant → spec table menyesuaikan
- Frontend: pilih variant → stock badge & price berubah

---

### Modul C — Perbaikan Compare Feature
**Dependency**: None (independent)  
**Agent**: Fullstack Builder  

**Problem**:
1. `removeFromCompare()` tidak didefinisikan di compare.blade.js → tombol X rusak
2. Dual storage (session + localStorage) bisa out-of-sync → hapus localStorage, pakai session aja
3. Tombol Compare di detail page tidak diperlukan (per user)
4. Floating compare widget perlu sync dengan session, bukan localStorage

Yang harus diubah:
- `compare.blade.php`:
  - Tambah fungsi `removeFromCompare(laptopId)` — panggil `DELETE /compare/remove/{id}`
  - Hapus semua referensi ke localStorage (`laptopsToCompare`)
  - `loadCompareProducts()`: baca existing IDs dari server (`/compare/ids`) bukan localStorage
  - `addCompareFromModal()`: tambah ke session saja, reload page setelah sukses
  - Hapus kode localStorage di line 198, 238-240
- `detail.blade.php`:
  - Hapus tombol Compare dari action buttons
  - Tapi pastikan fungsi `addToCompare` masih ada di global (untuk floating widget)
- `search.blade.php` & `home.blade.php`:
  - Tidak diubah (compare buttons tetap ada di sini)
- `floating-compare.blade.php`:
  - Tidak diubah (masih pakai session)

**Definition of Done**:
- Tombol X (remove) di compare page berfungsi
- Tidak ada lagi referensi localStorage untuk compare
- Tombol Compare tidak muncul di detail page
- Floating compare widget sync dengan benar

---

### Modul D — Share & Copy Link Buttons
**Dependency**: None  
**Agent**: Frontend Builder  

**Problem**: Tombol share dan copy di detail page tidak punya event handler.  
**Solusi**: Tambah JS handler untuk Web Share API + Clipboard API.

Yang harus diubah:
- `detail.blade.php`:
  - Tambah `onclick` pada tombol share: `shareProduct()`
  - Tambah `onclick` pada tombol copy: `copyProductLink()`
  - Implementasi:
    - `shareProduct()`: Web Share API (`navigator.share`) dengan fallback copy
    - `copyProductLink()`: Clipboard API (`navigator.clipboard.writeText`) dengan fallback

**Definition of Done**:
- Klik tombol share → muncul native share dialog (atau fallback copy)
- Klik tombol copy → link tercopy ke clipboard + toast notifikasi
- Handle error case (browser tidak support)

---

### Modul E — Fix Compare Modal "Add to Compare"
**Dependency**: Modul C  
**Agent**: Fullstack Builder  

**Problem**: Tombol add product di modal compare page mungkin tidak jalan karena localStorage sync issue.  
**Analisis**: `addCompareFromModal()` di compare.blade.php line 229-249 seharusnya jalan, tapi:
1. `loadCompareProducts()` baca `compareIds` dari localStorage → kalau localStorage kosong/salah, produk yang sudah ditambahkan tetap muncul
2. Setelah Modul C (hapus localStorage), fungsi ini harus pakai data dari session saja

Yang harus diubah:
- `compare.blade.php` `loadCompareProducts()`:
  - Ganti `localStorage.getItem('laptopsToCompare')` dengan fetch ke `/compare/ids`
  - Filter produk yang sudah ada di session
- `addCompareFromModal()`:
  - Hapus manipulasi localStorage
  - Cukup panggil API, kalau sukses reload page

**Definition of Done**:
- Modal menampilkan produk yang belum ditambahkan saja
- Klik produk di modal → berhasil ditambahkan
- Tidak ada error di console

---

## Dependency Graph

```
Modul A (Multi-Image) ──┐
                        ├──> Modul B (Variant Switch)
Modul C (Compare Fix) ──┤
                        ├──> Modul E (Compare Modal Fix)
Modul D (Share/Copy) ───┘
```

Modul C dan D bisa dikerjakan paralel setelah Modul A.
Modul B tergantung Modul A (karena image variant).
Modul E tergantung Modul C.

---

## Assignment Agent

| Modul | Agent | Estimasi |
|-------|-------|----------|
| A — Multi-Image Gallery | Backend Builder | 45 menit |
| B — Variant Switching | Frontend Builder | 30 menit |
| C — Compare Fix | Fullstack Builder | 30 menit |
| D — Share/Copy | Frontend Builder | 15 menit |
| E — Compare Modal Fix | Fullstack Builder | 15 menit |

---

## Definisi Selesai (Global)

- Semua perubahan sudah di-commit dengan pesan `fix:` atau `feat:`
- STATUS.md diupdate
- Tidak ada error JavaScript di console browser
- Admin panel: multi-upload berfungsi
- Storefront: semua tombol berfungsi
