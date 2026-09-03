<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Category;
use App\Models\Laptop;
use App\Models\LaptopVariant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductItem;
use App\Models\Setting;
use App\Models\StockMovement;
use App\Models\User;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PosController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    public function index(): View
    {
        return view('pos.index');
    }

    public function bootstrap(): JsonResponse
    {
        // Only products that have passed QC and have sellable stock
        $products = Laptop::with(['categories', 'variants'])
            ->where('stock', '>', 0)
            ->get()
            ->map(function ($laptop) {
                return [
                    'id' => $laptop->id,
                    'name' => $laptop->name,
                    'brand' => $laptop->brand,
                    'price' => $laptop->price,
                    'final_price' => $laptop->final_price,
                    'has_discount' => $laptop->has_discount,
                    'discount_value' => $laptop->discount_value,
                    'discount_type' => $laptop->discount_type,
                    'stock' => $laptop->stock,
                    'processor' => $laptop->processor,
                    'ram' => $laptop->ram,
                    'storage' => $laptop->storage,
                    'image' => $laptop->image_url_full,
                    'category_ids' => $laptop->categories->pluck('id')->toArray(),
                    'variants' => $laptop->variants->map(function ($v) use ($laptop) {
                        return [
                            'id' => $v->id,
                            'name' => $v->name,
                            'ram' => $v->ram ?? $laptop->ram,
                            'storage' => $v->storage ?? $laptop->storage,
                            'price' => $v->final_price,
                            'stock' => $v->stock,
                        ];
                    }),
                ];
            });

        // Also fetch QC Passed items with SKUs for instant barcode scanning
        $qcUnits = ProductItem::with('laptop')
            ->where('qc_status', 'passed')
            ->whereNotNull('sku')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'sku' => $item->sku,
                    'serial_number' => $item->serial_number,
                    'laptop_id' => $item->laptop_id,
                    'variant_id' => $item->laptop_variant_id,
                    'name' => $item->laptop->name,
                    'price' => $item->variant ? $item->variant->final_price : $item->laptop->final_price,
                ];
            });

        $categories = Category::where('is_active', true)->get(['id', 'name', 'slug']);

        $members = User::role('customer')
            ->get(['id', 'name', 'email', 'phone_number', 'member_number', 'member_tier', 'member_points'])
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'name' => $m->name,
                    'phone' => $m->phone_number,
                    'member_number' => $m->member_number,
                    'tier' => $m->member_tier,
                    'points' => $m->member_points,
                    'discount_percentage' => $m->tier_discount_percentage,
                ];
            });

        $settings = [
            'store_name' => Setting::getValue('store_name', 'ZLM.ID Laptop Store'),
            'store_address' => Setting::getValue('store_address', 'Jl. Soekarno Hatta No. 45, Malang'),
            'store_phone' => Setting::getValue('store_phone', '0812-3456-7890'),
            'tax_rate' => (float) Setting::getValue('tax_rate', '11'),
            'currency_symbol' => 'Rp',
        ];

        return response()->json([
            'status' => 'success',
            'version' => time(),
            'data' => [
                'products' => $products,
                'qc_units' => $qcUnits,
                'categories' => $categories,
                'members' => $members,
                'settings' => $settings,
            ],
        ]);
    }

    public function sync(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'orders' => 'required|array|min:1',
            'orders.*.client_order_uuid' => 'required|uuid',
            'orders.*.items' => 'required|array|min:1',
            'orders.*.payment_method' => 'required|string',
            'orders.*.subtotal' => 'required|numeric',
            'orders.*.discount' => 'nullable|numeric',
            'orders.*.tax' => 'nullable|numeric',
            'orders.*.total' => 'required|numeric',
            'orders.*.cash_tendered' => 'nullable|numeric',
            'orders.*.change_due' => 'nullable|numeric',
            'orders.*.member_id' => 'nullable|exists:users,id',
            'orders.*.notes' => 'nullable|string',
            'orders.*.created_at' => 'nullable|date',
        ]);

        $results = [];

        foreach ($validated['orders'] as $orderData) {
            $clientUuid = $orderData['client_order_uuid'];

            // 1. Idempotency Check: Don't duplicate if already synced
            $existing = Order::where('client_order_uuid', $clientUuid)->first();
            if ($existing) {
                $results[] = [
                    'client_order_uuid' => $clientUuid,
                    'server_order_id' => $existing->id,
                    'order_number' => $existing->order_number,
                    'status' => 'already_synced',
                ];
                continue;
            }

            // 2. Process Transaction Atomically
            DB::beginTransaction();
            try {
                $member = !empty($orderData['member_id']) ? User::find($orderData['member_id']) : null;
                $pointsEarned = (int) floor($orderData['total'] / 100000); // 1 point per 100k

                $orderNumber = 'POS-' . date('ymd') . '-' . strtoupper(Str::random(4));

                $order = Order::create([
                    'client_order_uuid' => $clientUuid,
                    'user_id' => $member?->id ?? auth()->id(),
                    'order_number' => $orderNumber,
                    'source' => 'pos',
                    'cashier_id' => auth()->id(),
                    'status' => 'completed',
                    'payment_status' => 'paid',
                    'payment_method' => $orderData['payment_method'],
                    'subtotal' => $orderData['subtotal'],
                    'discount_amount' => $orderData['discount'] ?? 0,
                    'member_discount_amount' => $orderData['member_discount_amount'] ?? 0,
                    'tax' => $orderData['tax'] ?? 0,
                    'shipping_cost' => 0,
                    'total' => $orderData['total'],
                    'cash_tendered' => $orderData['cash_tendered'] ?? $orderData['total'],
                    'change_due' => $orderData['change_due'] ?? 0,
                    'points_earned' => $pointsEarned,
                    'notes' => $orderData['notes'] ?? 'Transaksi Kasir POS Offline Sync',
                    'created_at' => $orderData['created_at'] ?? now(),
                ]);

                foreach ($orderData['items'] as $item) {
                    $laptop = Laptop::findOrFail($item['laptop_id']);
                    $variant = !empty($item['variant_id']) ? LaptopVariant::find($item['variant_id']) : null;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'laptop_id' => $laptop->id,
                        'laptop_variant_id' => $variant?->id,
                        'product_item_id' => $item['product_item_id'] ?? null,
                        'product_name' => $laptop->name,
                        'variant_name' => $variant?->name ?? 'Standard',
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $item['quantity'] * $item['unit_price'],
                    ]);

                    // Deduct stock via InventoryService
                    $this->inventoryService->reduceStockForSale(
                        $laptop,
                        $variant,
                        $item['quantity'],
                        $order,
                        auth()->user()
                    );
                }

                // Give loyalty points to member if attached
                if ($member && $pointsEarned > 0) {
                    $member->increment('member_points', $pointsEarned);
                }

                // Log audit
                AuditLog::create([
                    'user_id' => auth()->id(),
                    'action' => 'pos_order_synced',
                    'entity_type' => Order::class,
                    'entity_id' => $order->id,
                    'payload' => [
                        'order_number' => $order->order_number,
                        'total' => $order->total,
                        'client_uuid' => $clientUuid,
                    ],
                ]);

                DB::commit();

                $results[] = [
                    'client_order_uuid' => $clientUuid,
                    'server_order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => 'synced',
                ];
            } catch (\Exception $e) {
                DB::rollBack();
                $results[] = [
                    'client_order_uuid' => $clientUuid,
                    'status' => 'failed',
                    'error' => $e->getMessage(),
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'synced_count' => count(array_filter($results, fn($r) => in_array($r['status'], ['synced', 'already_synced']))),
            'results' => $results,
        ]);
    }
}
