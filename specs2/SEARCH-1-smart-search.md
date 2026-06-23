# SEARCH-1: Budget & Spec-based Smart Search

## Tujuan
User bisa mencari laptop berdasarkan budget maksimal dan preferensi spesifikasi. Hasil tidak harus sama persis, tetapi menampilkan laptop dengan spesifikasi paling mendekati.

## Implementasi

### 1. Controller Baru: `app/Http/Controllers/SmartSearchController.php`

Methods:
- `index()` — Tampilkan form pencarian
- `search(Request $request)` — Proses pencarian + hitung skor

### 2. Route (`routes/web.php`)
```php
Route::get('/smart-search', [SmartSearchController::class, 'index'])->name('landing.smart-search');
Route::post('/smart-search', [SmartSearchController::class, 'search'])->name('landing.smart-search.post');
```

### 3. Smart Search Logic

#### Input Form:
- **Budget**: input number (budget maksimal)
- **Prioritas** (pilih prioritas utama):
  - Processor (Intel i5/i7/i9 / AMD Ryzen 5/7/9)
  - RAM (8GB / 16GB / 32GB)
  - Storage (256GB / 512GB / 1TB)
  - GPU (Integrated / Entry / Mid / High)
  - Brand (opsional)
- **Penggunaan** (opsional):
  - Office / Programming / Design / Gaming / All

#### Scoring Algorithm:
Setiap laptop dalam range budget mendapat skor berdasarkan kecocokan:

| Kriteria | Bobot | Cara Hitung |
|----------|-------|-------------|
| Budget fit | 35% | Semakin dekat ke budget max, semakin tinggi skor |
| Processor | 25% | Berdasarkan generasi/kelas prosesor |
| RAM | 15% | 8GB=60, 16GB=80, 32GB=100 |
| Storage | 10% | 256GB=60, 512GB=80, 1TB=100 |
| GPU | 15% | Sesuai kebutuhan penggunaan |

Rumus: `total_score = (budget_score * 0.35) + (cpu_score * 0.25) + (ram_score * 0.15) + (storage_score * 0.10) + (gpu_score * 0.15)`

### 4. View: `resources/views/landing/smart-search.blade.php`

Layout:
```
┌─────────────────────────────────────────────────────┐
│ Smart Search — Cari Laptop Idealmu                  │
├─────────────────────────────────────────────────────┤
│ Budget Maksimal: [Rp ___________]                   │
│                                                      │
│ Prioritas Spesifikasi:                               │
│ ○ Processor  ○ RAM  ○ Storage  ○ GPU  ○ Semua       │
│                                                      │
│ Kategori Penggunaan:                                 │
│ [▼ Pilih...]                                         │
│                                                      │
│ Brand (opsional):                                    │
│ [▼ Semua Brand]                                      │
│                                                      │
│ [🔍 Cari Laptop Ideal]                               │
├─────────────────────────────────────────────────────┤
│ Hasil Pencarian (diurutkan by skor kecocokan)       │
│                                                      │
│ ┌──────┬──────────────────────────┬────────┬──────┐ │
│ │ Skor │ Laptop                   │ Harga  │ Detail│ │
│ ├──────┼──────────────────────────┼────────┼──────┤ │
│ │ 92%  │ Lenovo ThinkPad X1 Gen  │ Rp 12jt│ [→]  │ │
│ │ 85%  │ Dell XPS 15             │ Rp 14jt│ [→]  │ │
│ │ 78%  │ MacBook Pro 14          │ Rp 11jt│ [→]  │ │
│ └──────┴──────────────────────────┴────────┴──────┘ │
│                                                      │
│ Tips: Jika hasil kurang, coba naikkan budget atau    │
│ turunkan ekspektasi spesifikasi.                     │
└─────────────────────────────────────────────────────┘
```

### 5. Link di Landing Page
Tambahkan tombol/link "Smart Search" di hero section dan navbar.

## Definisi Selesai
- [x] Form smart search dengan input budget + preferensi
- [x] Algoritma scoring berdasarkan bobot kriteria
- [x] Hasil pencarian diurutkan by skor kecocokan
- [x] Tampilan card hasil dengan badge persentase skor
- [x] Link ke smart search dari landing page
