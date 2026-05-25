# Spesifikasi Teknis: Modul F — Compare Feature Enhancement

## Deskripsi
Migrasi fitur compare dari query string + localStorage ke session-based storage dengan AJAX. Menambahkan CompareController, endpoint RESTful, dan dynamic comparison grid.

## Masalah Saat Ini
- Compare menggunakan `localStorage` di client-side (`laptopsToCompare` key)
- Tidak ada server-side persistence — data hilang jika ganti device/browser
- Halaman `/compare` hanya render hardcoded 2 produk statis
- Tidak ada validasi server (max 3 items, duplicate check)

## Solusi
- Session-based storage (`session('compare', [])`) — **tidak ada tabel database**
- CompareController dengan method index/add/remove/clear
- AJAX endpoints untuk interaksi tanpa reload
- Dynamic grid di halaman compare (mendukung 2-3 produk)

## File yang Dibuat

### Controller
- `app/Http/Controllers/CompareController.php` — BARU

### Views
- `resources/views/components/floating-compare.blade.php` — BARU

### Assets
- `public/js/compare.js` — BARU

## File yang Dimodifikasi

| File | Perubahan |
|------|-----------|
| `routes/web.php` | Ganti route compare, tambah AJAX routes |
| `app/Http/Controllers/LaptopController.php` | Hapus method `compare()` |
| `resources/views/landing/compare.blade.php` | Rewrite: dynamic grid, baca dari session |
| `resources/views/landing/home.blade.php` | Update tombol compare → AJAX, hapus localStorage logic |
| `resources/views/landing/search.blade.php` | Update tombol compare → AJAX |
| `resources/views/layouts/landing.blade.php` | Include `compare.js` |

## Controller: `CompareController`

### Method Detail

#### `index()`
```
GET /compare
→ Baca session('compare', []) → array of laptop UUIDs
→ Laptop::whereIn('id', $ids)->with('categories')->get()
→ Return view('landing.compare', ['laptops' => $laptops])
```

#### `add(Request)`
```
POST /compare/add
Input: { "laptop_id": "uuid" }
→ Cari laptop di DB (findOrFail)
→ Ambil session('compare', [])
→ Jika count >= 3: return 422 { success: false, message: "Maksimal 3 produk" }
→ Jika ID sudah ada: return 409 { success: false, message: "Produk sudah ada" }
→ Push ID ke array
→ Simpan ke session
→ Return 200 { success: true, message: "Ditambahkan ke perbandingan", count: n }
```

#### `remove($laptop)`
```
DELETE /compare/remove/{laptop}
→ Ambil session('compare', [])
→ Filter: hapus ID yang cocok
→ Simpan ke session
→ Return 200 { success: true, message: "Dihapus dari perbandingan", count: n }
```

#### `clear()`
```
DELETE /compare/clear
→ Session::forget('compare')
→ Return 200 { success: true, message: "Daftar perbandingan dikosongkan" }
```

## Routes

### Di `routes/web.php` (Public routes — tanpa middleware auth)
```php
// Ganti route lama
Route::get('/compare', [CompareController::class, 'index'])->name('landing.compare');
Route::post('/compare/add', [CompareController::class, 'add'])->name('compare.add');
Route::delete('/compare/remove/{laptop}', [CompareController::class, 'remove'])->name('compare.remove');
Route::delete('/compare/clear', [CompareController::class, 'clear'])->name('compare.clear');
```

### Yang Dihapus
```php
// Hapus baris ini dari LaptopController
Route::get('/compare', [LaptopController::class, 'compare'])->name('landing.compare'); // DELETE
// Hapus method compare() dari LaptopController
```

## UI Design

### Halaman Compare (Dynamic Grid)
```
┌────────────────────────────────────────────────────────────┐
│ [Navbar]                                                    │
├────────────────────────────────────────────────────────────┤
│ Breadcrumb: Home > Products > Compare                       │
│                                                              │
│ ★ Perbandingan Produk               [Hapus Semua]            │
│                                                              │
│ ┌────────────┬────────────┬────────────────────────────┐    │
│ │            │            │                            │    │
│ │ Produk 1   │ Produk 2   │ Produk 3 (atau [+Tambah]) │    │
│ │            │            │                            │    │
│ │ Gambar     │ Gambar     │ Gambar placeholder         │    │
│ │ Brand      │ Brand      │ atau "Tambah produk"       │    │
│ │ Nama       │ Nama       │                            │    │
│ │ Prosesor   │ Prosesor   │                            │    │
│ │ RAM        │ RAM        │                            │    │
│ │ Storage    │ Storage    │                            │    │
│ │ Harga      │ Harga      │                            │    │
│ │ Layar      │ Layar      │                            │    │
│ │ GPU        │ GPU        │                            │    │
│ │ Berat      │ Berat      │                            │    │
│ │ Baterai    │ Baterai    │                            │    │
│ │ [Hapus]    │ [Hapus]    │                            │    │
│ └────────────┴────────────┴────────────────────────────┘    │
│                                                              │
│ [← Kembali ke Katalog]                                       │
└──────────────────────────────────────────────────────────────┘
```

**Layout Rules**:
- 2 produk: grid 2 kolom
- 3 produk: grid 3 kolom
- Jika < 3 produk, kolom kosong menampilkan "Tambah produk untuk dibandingkan" dengan link ke katalog
- Setiap kolom memiliki tombol "Hapus" untuk mengeluarkan dari daftar

### Spec Comparison Table
| Kategori | Baris | Ikon |
|----------|-------|------|
| Performance | Processor | `solar:cpu-linear` |
| | RAM | `solar:ram-linear` |
| | Storage | `solar:database-linear` |
| | Graphics | `solar:graph-new-linear` |
| Display | Screen Size | `solar:monitor-linear` |
| | Resolution | `solar:monitor-linear` |
| Physical | Weight | `solar:case-minimalistic-linear` |
| | Battery | `solar:battery-charge-linear` |

## Data Tampilan

### Product Card di Compare Page
| Elemen | Sumber Data | Format |
|--------|------------|--------|
| Image | `$laptop->image_url ?? placehold.co` | 200x200, object-contain |
| Brand | `$laptop->brand` | Text, uppercase badge |
| Name | `$laptop->name` | Text, link ke detail |
| Processor | `$laptop->processor` | Text with icon |
| RAM | `$laptop->ram` | Text with icon |
| Storage | `$laptop->storage` | Text with icon |
| Graphics | `$laptop->graphics` | Text |
| Display | `$laptop->display` | Text |
| Weight | `$laptop->weight` | `X kg` |
| Battery | `$laptop->battery_life` | Text |
| Price | `$laptop->price` | `Rp X.XXX.XXX` |
| Remove btn | — | `DELETE /compare/remove/{id}` |

### JSON Response Format
```json
// Success
{ "success": true, "message": "Ditambahkan ke perbandingan", "count": 2 }

// Error max items
{ "success": false, "message": "Maksimal 3 produk dapat dibandingkan" }

// Error duplicate
{ "success": false, "message": "Produk sudah ada dalam daftar perbandingan" }

// On remove
{ "success": true, "message": "Dihapus dari perbandingan", "count": 1 }

// On clear
{ "success": true, "message": "Daftar perbandingan dikosongkan" }
```

## Dependency Graph
```
Modul F (Compare Enhancement)
   ├── CompareController (F.1)
   │   ├── index() → reads session ➔ view
   │   ├── add() → writes session ➔ JSON
   │   ├── remove() → writes session ➔ JSON
   │   └── clear() → clears session ➔ JSON
   ├── Routes (F.2) → web.php
   ├── LaptopController cleanup (F.3)
   ├── compare.blade.php update (F.4)
   └── compare.js (F.5) → AJAX handlers
```

**Dependencies**:
- Tidak bergantung pada modul Fase 2 lain
- Bergantung pada: Laravel Session, Laptop model, existing web.php routes
- **Digunakan oleh**: Modul H (Landing Widget)

## Urutan Pengerjaan
1. Buat `CompareController` (4 methods)
2. Update `routes/web.php`
3. Hapus `compare()` dari `LaptopController`
4. Rewrite `resources/views/landing/compare.blade.php`
5. Buat `public/js/compare.js`
6. Test: add 3 items, hapus, clear, halaman compare

## Definisi Selesai
- ✅ CompareController dengan 4 method (index, add, remove, clear)
- ✅ Session-based storage (bukan localStorage)
- ✅ Max 3 produk, validasi duplicate
- ✅ AJAX endpoints return JSON
- ✅ Halaman compare render grid dinamis (2-3 kolom)
- ✅ Spec table menampilkan semua field laptop
- ✅ Tombol hapus di setiap kolom
- ✅ Clear all button
- ✅ Tidak ada lagi `compare()` di LaptopController
