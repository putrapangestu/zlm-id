# LAPORAN: Reports Module (Purchases, Profit-Loss, Product Stats)

## Tujuan
Admin bisa meng-generate 3 jenis laporan: Pembelian, Laba Rugi, dan Statistik Barang.

## Implementasi

### 1. Controller: `app/Http/Controllers/Admin/ReportController.php`

### 2. Routes
```php
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/purchases', [ReportController::class, 'purchases'])->name('purchases');
    Route::get('/profit-loss', [ReportController::class, 'profitLoss'])->name('profit-loss');
    Route::get('/product-stats', [ReportController::class, 'productStats'])->name('product-stats');
    Route::post('/export', [ReportController::class, 'export'])->name('export');
});
```

### 3. Laporan Pembelian

**Filter:**
- Date range (start_date, end_date)
- Status pesanan
- Payment status

**Data:**
| No | Tanggal | Order # | Customer | Items | Total | Status Pembayaran | Status Pesanan |
|----|---------|---------|----------|-------|-------|-------------------|----------------|
| 1  | 19/06/26| ORD-XXX | John Doe | 2 item | Rp 15jt | Paid | Delivered |

**Summary:**
- Total orders
- Total revenue
- Rata-rata order value

### 4. Laporan Laba Rugi

**Filter:**
- Periode (bulanan / custom date range)

**Perhitungan:**
- **Pendapatan**: Total dari paid orders
- **HPP (Modal)**: Estimasi dari harga beli (gunakan `price * qty` atau field `cost_price` jika ada)
- **Biaya Operasional**: Total shipping_cost
- **Pajak**: Total tax
- **Laba Kotor** = Pendapatan - HPP
- **Laba Bersih** = Laba Kotor - Biaya Operasional - Pajak

**Tampilan:**
```
LAPORAN LABA RUGI
Periode: Juni 2026

PENDAPATAN
  Total Penjualan          Rp 150.000.000
  -----------------------------------------
  Total Pendapatan         Rp 150.000.000

BEBAN POKOK PENDAPATAN
  Modal Barang             Rp 110.000.000
  -----------------------------------------
  Total BPP                Rp 110.000.000

LABA KOTOR                 Rp  40.000.000

BIAYA OPERASIONAL
  Biaya Pengiriman         Rp   5.000.000
  -----------------------------------------
  Total Biaya              Rp   5.000.000

LABA BERSIH                Rp  35.000.000
```

### 5. Laporan Statistik Barang

**Filter:**
- Kategori
- Date range (untuk penjualan)

**Sections:**

#### A. Ringkasan Stok
- Total produk
- Produk tersedia (>0 stock)
- Produk habis (0 stock)
- Produk minimum (<=5 stock)

#### B. Top Selling Products
| # | Produk | Kategori | Terjual | Total Revenue | Stok |
|---|--------|----------|---------|---------------|------|
| 1 | Lenovo X1 | Business | 15 | Rp 180jt | 8 |

#### C. Top Rated Products
| # | Produk | Rating | Jumlah Review |
|---|--------|--------|---------------|
| 1 | Dell XPS | 4.8 | 24 |

### 6. Export (opsional, stretch goal)
- Export ke PDF
- Export ke Excel (gunakan library seperti maatwebsite/laravel-excel)

### 7. Admin Sidebar
Tambah menu "Laporan" dengan submenu:
- Laporan Pembelian
- Laporan Laba Rugi
- Statistik Barang

## Views

### `admin/reports/purchases.blade.php`
- Filter form di atas
- Table hasil
- Summary cards

### `admin/reports/profit-loss.blade.php`
- Filter periode
- Laporan style akuntansi
- Export button

### `admin/reports/product-stats.blade.php`
- Tab: Stok, Best Seller, Rating
- Cards + Tables

## Definisi Selesai
- [x] Laporan Pembelian dengan filter date range + status
- [x] Laporan Laba Rugi dengan perhitungan otomatis
- [x] Laporan Statistik Barang (stok, top selling, rating)
- [x] Sidebar menu laporan
- [x] Export (stretch goal)
