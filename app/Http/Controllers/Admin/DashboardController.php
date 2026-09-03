<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laptop;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductItem;
use App\Models\ProductReturn;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $todayStart = now()->startOfDay();
        $monthStart = now()->startOfMonth();

        // 1. Single consolidated query for TODAY's orders & revenue (Index-friendly on created_at)
        $todayStats = Order::where('created_at', '>=', $todayStart)
            ->selectRaw("
                COUNT(*) as total_orders,
                COALESCE(SUM(CASE WHEN payment_status = 'paid' THEN total ELSE 0 END), 0) as revenue,
                COALESCE(SUM(CASE WHEN payment_status = 'paid' AND (source = 'pos' OR source = 'offline') THEN total ELSE 0 END), 0) as revenue_pos,
                COALESCE(SUM(CASE WHEN payment_status = 'paid' AND (source != 'pos' AND source != 'offline') THEN total ELSE 0 END), 0) as revenue_web,
                COALESCE(SUM(CASE WHEN payment_status = 'paid' AND (source = 'pos' OR source = 'offline') THEN 1 ELSE 0 END), 0) as count_pos,
                COALESCE(SUM(CASE WHEN payment_status = 'paid' AND (source != 'pos' AND source != 'offline') THEN 1 ELSE 0 END), 0) as count_web
            ")
            ->first();

        // 2. Single consolidated query for MONTH's orders & revenue
        $monthStats = Order::where('created_at', '>=', $monthStart)
            ->selectRaw("
                COUNT(*) as total_orders,
                COALESCE(SUM(CASE WHEN payment_status = 'paid' THEN total ELSE 0 END), 0) as revenue
            ")
            ->first();

        // 3. Operational Action Alerts (Perlu Tindakan Segera)
        $actionAlerts = [
            'pending_verifications' => Order::where('payment_status', 'pending_verification')->count(),
            'need_shipping' => Order::where('payment_status', 'paid')
                ->whereIn('status', ['pending', 'processing'])
                ->count(),
            'pending_qc' => ProductItem::where('qc_status', 'pending')->count(),
            'critical_stock_count' => Laptop::where('is_active', true)->where('stock', '<=', 2)->count(),
            'pending_returns' => ProductReturn::where('status', 'pending')->count(),
        ];

        // 4. Five (5) urgent orders requiring action (Verifikasi Bukti Bayar & Butuh Dikirim)
        $ordersToProcess = Order::with(['user:id,name,email', 'items:id,order_id,product_name,quantity'])
            ->where(function ($q) {
                $q->where('payment_status', 'pending_verification')
                  ->orWhere(function ($sub) {
                      $sub->where('payment_status', 'paid')
                          ->whereIn('status', ['pending', 'processing']);
                  });
            })
            ->latest('created_at')
            ->take(5)
            ->get();

        // 5. Top 5 selling laptops this month (cached 10 min to keep DB ultra-fast)
        $topSelling = Cache::remember('dashboard_top_selling_month', 600, function () use ($monthStart) {
            return OrderItem::where('created_at', '>=', $monthStart)
                ->selectRaw('laptop_id, SUM(quantity) as total_sold, SUM(subtotal) as total_revenue')
                ->with(['laptop:id,name,brand,slug,image_url,stock'])
                ->groupBy('laptop_id')
                ->orderByDesc('total_sold')
                ->take(5)
                ->get()
                ->map(function ($item) {
                    return (object) [
                        'laptop_id' => $item->laptop_id,
                        'total_sold' => (int) $item->total_sold,
                        'total_revenue' => (float) $item->total_revenue,
                        'laptop' => (object) [
                            'name' => $item->laptop?->name ?? 'Laptop #' . $item->laptop_id,
                            'brand' => $item->laptop?->brand ?? '',
                            'slug' => $item->laptop?->slug ?? '',
                            'stock' => $item->laptop?->stock ?? 0,
                        ],
                    ];
                })
                ->all();
        });

        // 6. Five (5) laptops with critically low or empty stock (<= 2 units)
        $criticalStocks = Laptop::where('is_active', true)
            ->where('stock', '<=', 2)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get(['id', 'name', 'brand', 'slug', 'stock', 'price']);

        // 7. Inventory health breakdown
        $stockSummary = [
            'total_models' => Laptop::where('is_active', true)->count(),
            'ready_units' => (int) Laptop::where('is_active', true)->sum('stock'),
            'in_qc' => ProductItem::where('qc_status', 'pending')->count(),
            'failed_qc' => ProductItem::where('qc_status', 'failed')->count(),
        ];

        return view('admin.dashboard', compact(
            'todayStats',
            'monthStats',
            'actionAlerts',
            'ordersToProcess',
            'topSelling',
            'criticalStocks',
            'stockSummary'
        ));
    }
}
