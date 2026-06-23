# TRACK-1: Tracking Barang untuk User

## Tujuan
User bisa melacak status pengiriman barang pesanan mereka secara real-time.

## Implementasi

### 1. Migration: Add tracking fields ke orders
`database/migrations/xxxx_add_tracking_fields_to_orders_table.php`

| Kolom | Tipe | Nullable | Keterangan |
|-------|------|----------|------------|
| `tracking_number` | string(100) | Yes | No resi pengiriman |
| `tracking_history` | json | Yes | Riwayat tracking events |
| `shipped_at` | timestamp | Yes | Waktu pengiriman |
| `estimated_delivery` | date | Yes | Estimasi tiba |

### 2. Model Update (`app/Models/Order.php`)
- Tambah `$casts` untuk `tracking_history` → array
- Helper methods:
  - `addTrackingEvent(string $status, string $description, ?string $location)`
  - `getLatestTracking()`
  - `isShipped()`
  - `isDelivered()`

### 3. Controller: `app/Http/Controllers/TrackingController.php`
Methods:
- `index()` — Form input no tracking / pilih pesanan
- `show(Order $order)` — Tampilkan tracking detail (auth, verifikasi kepemilikan)
- `trackByNumber(Request $request)` — Tracking via nomor resi (public)

### 4. Routes
```php
Route::get('/tracking', [TrackingController::class, 'index'])->name('tracking.index');
Route::get('/tracking/{order}', [TrackingController::class, 'show'])->name('tracking.show');
Route::post('/tracking/number', [TrackingController::class, 'trackByNumber'])->name('tracking.by-number');
```

### 5. View: `resources/views/landing/tracking.blade.php`
Timeline tracking visual:
```
┌──────────────────────────────────────────────┐
│ Lacak Pesanan Anda                           │
│                                              │
│ [Masukkan No. Pesanan atau Resi] [🔍 Lacak] │
├──────────────────────────────────────────────┤
│                                              │
│ Order #ORD-ABC12345                          │
│ Status: ● SHIPPING (Dalam Perjalanan)        │
│ Kurir: JNE REG | Resi: JP0123456789          │
│ Estimasi: 22 Jun 2026                        │
│                                              │
│ Timeline:                                    │
│                                              │
│ ✅ Jun 19, 14:30 — Pesanan Diterima Kurir    │
│    📍 Malang                                 │
│                                              │
│ ✅ Jun 19, 10:00 — Paket Diproses            │
│    📍 Gudang ZLM.ID                          │
│                                              │
│ ✅ Jun 18, 15:20 — Pembayaran Dikonfirmasi   │
│                                              │
│ ✅ Jun 18, 09:00 — Pesanan Dibuat            │
└──────────────────────────────────────────────┘
```

### 6. Admin Update Tracking
Di halaman admin tracking (`admin/orders/{order}/tracking`):
- Input nomor resi
- Tombol update status (PROCESSING → SHIPPING → DELIVERED)
- Tambah catatan tracking
- Kirim email notifikasi saat status berubah

### 7. Tombol Tracking di Order History
Setiap order di history user punya tombol "Lacak" jika sudah memiliki tracking_number.

## Definisi Selesai
- [x] Migration tracking fields berfungsi
- [x] User bisa tracking via order atau nomor resi
- [x] Timeline visual tracking
- [x] Admin bisa update tracking status
- [x] Email notifikasi saat tracking status berubah
