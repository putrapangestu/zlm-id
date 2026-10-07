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
        $defaultStart = $period === 'yearly' ? now()->startOfYear() : now()->startOfMonth();
        $startDate = $request->filled('start_date') ? $request->start_date : $defaultStart->format('Y-m-d');
        $endDate = $request->filled('end_date') ? $request->end_date : now()->format('Y-m-d');

        $paidOrders = Order::with(['items.productItem.parts', 'items.laptop', 'user'])
            ->where('payment_status', 'paid')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();

        $totalRevenue = (float) $paidOrders->sum('total');
        $onlineRevenue = (float) $paidOrders->where('source', 'web')->sum('total');
        $posRevenue = (float) $paidOrders->where('source', 'pos')->sum('total');

        $shippingCost = (float) $paidOrders->sum('shipping_cost');
        $taxTotal = (float) $paidOrders->sum('tax');
        $memberDiscounts = (float) $paidOrders->sum('member_discount_amount');

        // Calculate real HPP from sold items (including QC spareparts)
        $baseCostSold = 0;
        $qcPartsCostSold = 0;
        $totalHppSold = 0;

        foreach ($paidOrders as $order) {
            foreach ($order->items as $item) {
                $qty = max(1, (int) $item->quantity);
                if ($item->productItem) {
                    $itemBase = (float) $item->productItem->base_cost;
                    $itemParts = (float) $item->productItem->additional_cost;
                    $itemFinal = (float) $item->productItem->final_cost;

                    if ($itemFinal <= 0) {
                        $itemFinal = (float) ($item->unit_price ?: ($item->price ?: 0)) * 0.7;
                        $itemBase = $itemFinal;
                    }
                    $baseCostSold += ($itemBase * $qty);
                    $qcPartsCostSold += ($itemParts * $qty);
                    $totalHppSold += ($itemFinal * $qty);
                } else {
                    // Fallback to cost estimation
                    $unitP = (float) ($item->unit_price ?: ($item->price ?: 0));
                    $estHpp = ($unitP > 0 ? $unitP * 0.7 : 0) * $qty;
                    $baseCostSold += $estHpp;
                    $totalHppSold += $estHpp;
                }
            }
        }

        // Total supplier restocks in this period
        $restockPurchasesTotal = (float) Restock::whereBetween('purchase_date', [$startDate, $endDate])->sum('total_amount');

        $grossProfit = $totalRevenue - $totalHppSold;
        $grossProfit -= $taxTotal;
        $netProfit = $grossProfit - $shippingCost - $memberDiscounts;
        $revenueExcludingTax = max(0, $totalRevenue - $taxTotal);
        $grossMarginPercent = $revenueExcludingTax > 0 ? round(($grossProfit / $revenueExcludingTax) * 100, 1) : 0;
        $netMarginPercent = $revenueExcludingTax > 0 ? round(($netProfit / $revenueExcludingTax) * 100, 1) : 0;
        $ordersCount = $paidOrders->count();

        $groupByMonth = $period === 'yearly'
            || ($period === 'custom' && \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) > 90);
        $chartStart = \Carbon\Carbon::parse($startDate);
        $chartEnd = \Carbon\Carbon::parse($endDate);
        $bucket = $groupByMonth ? $chartStart->copy()->startOfMonth() : $chartStart->copy()->startOfDay();
        $transactionChartLabels = [];
        $transactionChartCounts = [];
        $transactionChartRevenue = [];

        while ($bucket->lte($chartEnd)) {
            $key = $bucket->format($groupByMonth ? 'Y-m' : 'Y-m-d');
            $matchingOrders = $paidOrders->filter(fn (Order $order) => $order->created_at?->format($groupByMonth ? 'Y-m' : 'Y-m-d') === $key);
            $transactionChartLabels[] = $groupByMonth ? $bucket->translatedFormat('M Y') : $bucket->translatedFormat('d M');
            $transactionChartCounts[] = $matchingOrders->count();
            $transactionChartRevenue[] = (float) $matchingOrders->sum('total');
            $groupByMonth ? $bucket->addMonth() : $bucket->addDay();
        }

        return view('admin.reports.profit-loss', [
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalRevenue' => $totalRevenue,
            'revenue' => $totalRevenue,
            'onlineRevenue' => $onlineRevenue,
            'posRevenue' => $posRevenue,
            'shippingCost' => $shippingCost,
            'taxTotal' => $taxTotal,
            'revenueExcludingTax' => $revenueExcludingTax,
            'memberDiscounts' => $memberDiscounts,
            'baseCostSold' => $baseCostSold,
            'qcPartsCostSold' => $qcPartsCostSold,
            'hpp' => $totalHppSold,
            'totalHppSold' => $totalHppSold,
            'restockPurchasesTotal' => $restockPurchasesTotal,
            'grossProfit' => $grossProfit,
            'netProfit' => $netProfit,
            'grossMarginPercent' => $grossMarginPercent,
            'netMarginPercent' => $netMarginPercent,
            'ordersCount' => $ordersCount,
            'transactionChartLabels' => $transactionChartLabels,
            'transactionChartCounts' => $transactionChartCounts,
            'transactionChartRevenue' => $transactionChartRevenue,
            'transactionChartUnit' => $groupByMonth ? 'per bulan' : 'per hari',
        ]);
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

        // Brand distribution for chart
        $brandDistribution = Laptop::selectRaw('brand, SUM(stock) as total_stock')
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->groupBy('brand')
            ->orderByDesc('total_stock')
            ->take(8)
            ->get();

        // QC Distribution for chart
        $qcDistribution = [
            'passed' => ProductItem::where('qc_status', 'passed')->count(),
            'pending' => ProductItem::where('qc_status', 'pending')->count(),
            'failed' => ProductItem::where('qc_status', 'failed')->count(),
        ];

        return view('admin.reports.product-stats', compact('stockSummary', 'topSelling', 'topRated', 'brandDistribution', 'qcDistribution'));
    }
}
