<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laptop;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductItem;
use App\Models\Restock;
use App\Models\RestockItem;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function purchases(Request $request): View
    {
        $type = $request->get('type', 'supplier'); // 'supplier' (Restock) or 'customer' (Sales)

        if ($type === 'supplier') {
            $query = Restock::with(['creator', 'items.laptop']);

            if ($request->filled('start_date')) {
                $query->whereDate('purchase_date', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $query->whereDate('purchase_date', '<=', $request->end_date);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $summaryQuery = clone $query;
            $summary = [
                'total_orders' => (clone $summaryQuery)->count(),
                'total_batches' => (clone $summaryQuery)->count(),
                'total_revenue' => (clone $summaryQuery)->sum('total_amount'),
                'total_purchases' => (clone $summaryQuery)->sum('total_amount'),
                'total_units' => (int) RestockItem::whereIn('restock_id', (clone $summaryQuery)->pluck('id'))->sum('quantity'),
                'avg_order' => (clone $summaryQuery)->avg('total_amount') ?? 0,
            ];

            $records = $query->latest('purchase_date')->paginate(20)->withQueryString();
            $orders = $records;

            return view('admin.reports.purchases', compact('records', 'orders', 'summary', 'type'));
        }

        // Customer Sales Orders
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
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        $summaryQuery = clone $query;
        $summary = [
            'total_orders' => (clone $summaryQuery)->count(),
            'total_batches' => (clone $summaryQuery)->count(),
            'total_revenue' => (clone $summaryQuery)->where('payment_status', 'paid')->sum('total'),
            'total_purchases' => (clone $summaryQuery)->where('payment_status', 'paid')->sum('total'),
            'total_units' => (int) OrderItem::whereIn('order_id', (clone $summaryQuery)->pluck('id'))->sum('quantity'),
            'avg_order' => (clone $summaryQuery)->where('payment_status', 'paid')->avg('total') ?? 0,
        ];

        $records = $query->latest()->paginate(20)->withQueryString();
        $orders = $records;

        return view('admin.reports.purchases', compact('records', 'orders', 'summary', 'type'));
    }

    public function profitLoss(Request $request): View
    {
        $period = $request->get('period', 'monthly');
        $startDate = $request->filled('start_date') ? $request->start_date : now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->filled('end_date') ? $request->end_date : now()->format('Y-m-d');

        $paidOrders = Order::where('payment_status', 'paid')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $totalRevenue = (float) (clone $paidOrders)->sum('total');
        $revenue = $totalRevenue;
        $onlineRevenue = (float) (clone $paidOrders)->where('source', 'web')->sum('total');
        $posRevenue = (float) (clone $paidOrders)->where('source', 'pos')->sum('total');

        $shippingCost = (float) (clone $paidOrders)->sum('shipping_cost');
        $taxTotal = (float) (clone $paidOrders)->sum('tax');
        $memberDiscounts = (float) (clone $paidOrders)->sum('member_discount_amount');

        // Total HPP from restocks in this period
        $itemHpp = (float) RestockItem::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->sum('subtotal');

        if ($itemHpp == 0 && $totalRevenue > 0) {
            // Fallback estimation (65% of revenue if restock data is historical)
            $itemHpp = $totalRevenue * 0.7;
        }
        $hpp = $itemHpp;

        $grossProfit = $totalRevenue - $itemHpp;
        $netProfit = $grossProfit - $shippingCost - $memberDiscounts;
        $ordersCount = (clone $paidOrders)->count();

        return view('admin.reports.profit-loss', compact(
            'period',
            'revenue', 'totalRevenue', 'onlineRevenue', 'posRevenue',
            'shippingCost', 'taxTotal', 'memberDiscounts', 'hpp', 'itemHpp',
            'grossProfit', 'netProfit', 'ordersCount',
            'startDate', 'endDate'
        ));
    }

    public function productStats(Request $request): View
    {
        // Stock and QC Summary
        $stockSummary = [
            'totalProducts' => Laptop::count(),
            'total_models' => Laptop::count(),
            'availableStock' => (int) Laptop::sum('stock'),
            'qc_passed_stock' => (int) Laptop::sum('stock'),
            'uninspected_stock' => ProductItem::where('qc_status', 'pending')->count(),
            'failed_qc_stock' => ProductItem::where('qc_status', 'failed')->count(),
            'lowStock' => Laptop::where('stock', '>', 0)->where('stock', '<=', 3)->count(),
            'low_stock' => Laptop::where('stock', '>', 0)->where('stock', '<=', 3)->count(),
            'outOfStock' => Laptop::where('stock', '<=', 0)->count(),
            'out_of_stock' => Laptop::where('stock', '<=', 0)->count(),
        ];

        // Top Selling
        $topSelling = OrderItem::selectRaw('laptop_id, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->with('laptop')
            ->groupBy('laptop_id')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        // Top Rated
        $topRated = Laptop::withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->having('reviews_count', '>', 0)
            ->orderByDesc('reviews_avg_rating')
            ->take(10)
            ->get();

        // Recent Stock Movements
        $recentMovements = StockMovement::with(['laptop', 'user'])
            ->latest()
            ->take(15)
            ->get();

        return view('admin.reports.product-stats', compact('stockSummary', 'topSelling', 'topRated', 'recentMovements'));
    }
}
