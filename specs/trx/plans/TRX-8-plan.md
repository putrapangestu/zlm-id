# TRX-8 Implementation Plan: Admin Dashboard Stats Update

## Effort: Small

## File Structure
| File | Tipe | Keterangan |
|------|------|------------|
| `app/Http/Controllers/Admin/DashboardController.php` | DIUBAH | Tambah query stats orders & revenue |
| `resources/views/admin/dashboard.blade.php` | DIUBAH | Ganti hardcoded 0/Rp 0 dengan data real |

## Implementation Order

### Step 1: Update DashboardController@index
Tambah ke array `$stats` yang sudah ada:
```php
'total_orders' => Order::count(),
'total_revenue' => Order::where('payment_status', 'paid')->sum('total'),
'pending_orders' => Order::where('payment_status', 'unpaid')->count(),
'pending_verification' => Order::where('payment_status', 'pending_verification')->count(),
'monthly_revenue' => Order::where('payment_status', 'paid')
    ->whereMonth('created_at', now()->month)
    ->whereYear('created_at', now()->year)
    ->sum('total'),
'monthly_orders' => Order::whereMonth('created_at', now()->month)
    ->whereYear('created_at', now()->year)
    ->count(),
```
Import: `use App\Models\Order;`

### Step 2: Update Dashboard View
- **Orders card**: Ganti `0` → `{{ $stats['total_orders'] }}`
- **Revenue card**: Ganti `Rp 0` → `Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}`
- Opsional: tambah 2 card tambahan:
  - Pending Payments: `$stats['pending_orders'] + $stats['pending_verification']`
  - Monthly Revenue: `Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}`
- Sesuaikan layout grid jika perlu (dari 4 ke 6 kolom)

## Dependencies Internal
- TRX-1 (migration) — orders table harus sudah ada kolom payment_status

## Data Flow
```
DashboardController@index
    ↓
Query: Order::count(), Order::where('payment_status','paid')->sum('total'), etc.
    ↓
View menampilkan data real (bukan 0)
```

## Test Plan
- Unit test: stats menghitung dengan benar untuk berbagai skenario
- Unit test: tidak ada order → total = 0 (bukan error)
- Integration test: buat order → dashboard menampilkan count +1
- Integration test: buat order paid → revenue bertambah
