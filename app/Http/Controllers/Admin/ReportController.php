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

            $summaryQuery = clone $query;
            $summary = [
                'total_batches' => (clone $summaryQuery)->count(),
                'total_purchases' => (clone $summaryQuery)->sum('total_amount'),
                'total_units' => RestockItem::whereIn('restock_id', (clone $summaryQuery)->pluck('id'))->sum('quantity'),
            ];

            $records = $query->latest('purchase_date')->paginate(20)->withQueryString();

            return view('admin.reports.purchases', compact('records', 'summary', 'type'));
        }

        // Customer Sales Orders
        $query = Order::with('user', 'items');

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        $summaryQuery = clone $query;
        $summary = [
            'total_orders' => (clone $summaryQuery)->count(),
            'total_revenue' => (clone $summaryQuery)->where('payment_status', 'paid')->sum('total'),
            'avg_order' => (clone $summaryQuery)->where('payment_status', 'paid')->avg('total') ?? 0,
        ];

        $records = $query->latest()->paginate(20)->withQueryString();

        return view('admin.reports.purchases', compact('records', 'summary', 'type'));
    }

    public function profitLoss(Request $request): View
    {
        $startDate = $request->filled('start_date') ? $request->start_date : now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->filled('end_date') ? $request->end_date : now()->format('Y-m-d');

        $paidOrders = Order::where('payment_status', 'paid')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $totalRevenue = (float) (clone $paidOrders)->sum('total');
        $onlineRevenue = (float) (clone $paidOrders)->where('source', 'web')->sum('total');
        $posRevenue = (float) (clone $paidOrders)->where('source', 'pos')->sum('total');

        $shippingCost = (float) (clone $paidOrders)->sum('shipping_cost');
        $taxTotal = (float) (clone $paidOrders)->sum('tax');
        $memberDiscounts = (float) (clone $paidOrders)->sum('member_discount_amount');

        // Total HPP from restocks in this period or sold items
        $orderIds = (clone $paidOrders)->pluck('id');
        $itemHpp = (float) RestockItem::whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->sum('subtotal');

        if ($itemHpp == 0) {
            // Fallback estimation (65% of revenue if restock data is new)
            $itemHpp = $totalRevenue * 0.7;
        }

        $grossProfit = $totalRevenue - $itemHpp;
        $netProfit = $grossProfit - $shippingCost - $memberDiscounts;
        $ordersCount = (clone $paidOrders)->count();

        return view('admin.reports.profit-loss', compact(
            'totalRevenue', 'onlineRevenue', 'posRevenue',
            'shippingCost', 'taxTotal', 'memberDiscounts', 'itemHpp',
            'grossProfit', 'netProfit', 'ordersCount',
            'startDate', 'endDate'
        ));
    }

    public function productStats(Request $request): View
    {
        // Stock and QC Summary
        $stockSummary = [
            'total_models' => Laptop::count(),
            'qc_passed_stock' => Laptop::sum('stock'),
            'uninspected_stock' => ProductItem::where('qc_status', 'pending')->count(),
            'failed_qc_stock' => ProductItem::where('qc_status', 'failed')->count(),
            'low_stock' => Laptop::where('stock', '>', 0)->where('stock', '<=', 3)->count(),
            'out_of_stock' => Laptop::where('stock', '<=', 0)->count(),
        ];

        // Top Selling
        $topSelling = OrderItem::selectRaw('laptop_id, SUM(quantity) as total_qty, SUM(subtotal) as total_revenue')
            ->with('laptop')
            ->groupBy('laptop_id')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        // Recent Stock Movements
        $recentMovements = StockMovement::with(['laptop', 'user'])
            ->latest()
            ->take(15)
            ->get();

        return view('admin.reports.product-stats', compact('stockSummary', 'topSelling', 'recentMovements'));
    }
}
