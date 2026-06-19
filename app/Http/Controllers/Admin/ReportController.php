<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laptop;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function purchases(Request $request)
    {
        $query = Order::with('user', 'items');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Hitung summary dulu sebelum paginate
        $summaryQuery = clone $query;
        $summary = [
            'total_orders' => (clone $summaryQuery)->count(),
            'total_revenue' => (clone $summaryQuery)->where('payment_status', 'paid')->sum('total'),
            'avg_order' => (clone $summaryQuery)->where('payment_status', 'paid')->avg('total') ?? 0,
        ];

        $orders = $query->latest()->paginate(20)->withQueryString();

        return view('admin.reports.purchases', compact('orders', 'summary'));
    }

    public function profitLoss(Request $request)
    {
        $period = $request->input('period', 'monthly');

        // Default: bulan ini
        $startDate = $request->filled('start_date') ? $request->start_date : now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->filled('end_date') ? $request->end_date : now()->format('Y-m-d');

        $paidOrders = Order::where('payment_status', 'paid')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $revenue = (float) $paidOrders->sum('total');
        $shippingCost = (float) $paidOrders->sum('shipping_cost');
        $taxTotal = (float) $paidOrders->sum('tax');

        // HPP (estimasi dari unit_price * quantity di order_items)
        $orderIds = (clone $paidOrders)->pluck('id');
        $hpp = (float) OrderItem::whereIn('order_id', $orderIds)
            ->selectRaw('SUM(unit_price * quantity) as total')
            ->value('total') ?? 0;

        $grossProfit = $revenue - $hpp;
        $netProfit = $grossProfit - $shippingCost;

        $ordersCount = (clone $paidOrders)->count();

        return view('admin.reports.profit-loss', compact(
            'revenue', 'shippingCost', 'taxTotal', 'hpp',
            'grossProfit', 'netProfit', 'ordersCount',
            'startDate', 'endDate', 'period'
        ));
    }

    public function productStats(Request $request)
    {
        // Ringkasan Stok
        $totalProducts = Laptop::count();
        $availableStock = Laptop::where('stock', '>', 0)->count();
        $outOfStock = Laptop::where('stock', '<=', 0)->count();
        $lowStock = Laptop::where('stock', '>', 0)->where('stock', '<=', 5)->count();

        // Top Selling
        $topSelling = OrderItem::selectRaw('laptop_id, SUM(quantity) as total_qty, SUM(quantity * unit_price) as total_revenue')
            ->with('laptop')
            ->groupBy('laptop_id')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        // Top Rated
        $topRated = Laptop::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->having('reviews_avg_rating', '>', 0)
            ->orderByDesc('reviews_avg_rating')
            ->take(10)
            ->get();

        $stockSummary = compact('totalProducts', 'availableStock', 'outOfStock', 'lowStock');

        return view('admin.reports.product-stats', compact('stockSummary', 'topSelling', 'topRated'));
    }
}
