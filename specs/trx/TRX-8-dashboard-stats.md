# TRX-8: Admin Dashboard Stats Update

## Tujuan
Mengupdate dashboard admin untuk menampilkan data transaksi real dari database.

## File Diubah
- `app/Http/Controllers/Admin/DashboardController.php`
- `resources/views/admin/dashboard.blade.php`

## Detail Implementasi

### 1. DashboardController
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laptop;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_laptops' => Laptop::count(),
            'total_users' => User::count(),
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
        ];
        
        return view('admin.dashboard', compact('stats'));
    }
}
```

### 2. Dashboard View
Update 4 card stats:
- Products: total products (existing)
- Users: total users (existing)
- Orders: total orders (real count) — ganti dari 0 ke `$stats['total_orders']`
- Revenue: total revenue paid — ganti dari Rp 0 ke `$stats['total_revenue']`

Opsional: tambah 2 row tambahan untuk:
- Pending Payments (jumlah unpaid + pending_verification)
- Monthly Revenue (pendapatan bulan ini)

### Definisi Selesai
- [ ] Dashboard menampilkan jumlah order real
- [ ] Dashboard menampilkan total revenue real
- [ ] Dashboard bisa nampilin pending payments count
- [ ] Tidak ada error query
