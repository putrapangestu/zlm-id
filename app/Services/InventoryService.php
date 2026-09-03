<?php

namespace App\Services;

use App\Models\Laptop;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductItem;
use App\Models\ProductReturn;
use App\Models\Restock;
use App\Models\RestockItem;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InventoryService
{
    public function __construct(
        protected WhatsAppService $whatsAppService
    ) {}

    public function createRestock(array $data, User $creator): Restock
    {
        return DB::transaction(function () use ($data, $creator) {
            $restock = Restock::create([
                'supplier_name' => $data['supplier_name'],
                'supplier_phone' => $data['supplier_phone'] ?? null,
                'invoice_number' => $data['invoice_number'] ?? null,
                'purchase_date' => $data['purchase_date'] ?? now()->format('Y-m-d'),
                'total_amount' => 0,
                'notes' => $data['notes'] ?? null,
                'status' => 'received',
                'created_by' => $creator->id,
            ]);

            $totalAmount = 0;

            foreach ($data['items'] as $itemData) {
                $qty = (int) $itemData['quantity'];
                $price = (float) $itemData['purchase_price'];
                $subtotal = $qty * $price;
                $totalAmount += $subtotal;

                // 1. Dapatkan atau Buat Laptop Baru dari Restock
                if (!empty($itemData['laptop_id'])) {
                    $laptop = Laptop::findOrFail($itemData['laptop_id']);
                } elseif (!empty($itemData['new_laptop'])) {
                    $newLaptopData = $itemData['new_laptop'];
                    $brandId = $newLaptopData['brand_id'] ?? null;
                    $brandName = $newLaptopData['brand'] ?? 'Tanpa Brand';
                    if ($brandId) {
                        $brandObj = \App\Models\Brand::find($brandId);
                        if ($brandObj) $brandName = $brandObj->name;
                    } elseif (!empty($brandName)) {
                        $brandObj = \App\Models\Brand::firstOrCreate(
                            ['name' => trim($brandName)],
                            ['slug' => \Illuminate\Support\Str::slug(trim($brandName)), 'is_active' => true]
                        );
                        $brandId = $brandObj->id;
                        $brandName = $brandObj->name;
                    }

                    $laptop = Laptop::create([
                        'name' => $newLaptopData['name'],
                        'brand' => $brandName,
                        'brand_id' => $brandId,
                        'description' => $newLaptopData['description'] ?? 'Unit restock baru ZLM.ID',
                        'price' => $newLaptopData['price'] ?? ($price * 1.25), // Default markup 25% jika belum diset
                        'processor' => $newLaptopData['processor'],
                        'ram' => $newLaptopData['ram'],
                        'storage' => $newLaptopData['storage'],
                        'graphics' => $newLaptopData['graphics'] ?? null,
                        'display' => $newLaptopData['display'] ?? null,
                        'ports' => $newLaptopData['ports'] ?? null,
                        'camera' => $newLaptopData['camera'] ?? null,
                        'audio' => $newLaptopData['audio'] ?? null,
                        'connectivity' => $newLaptopData['connectivity'] ?? null,
                        'color' => $newLaptopData['color'] ?? null,
                        'warranty' => $newLaptopData['warranty'] ?? 'Garansi Toko 1 Bulan',
                        'weight' => $newLaptopData['weight'] ?? 1.4,
                        'battery_life' => $newLaptopData['battery_life'] ?? null,
                        'image_url' => $newLaptopData['image_url'] ?? null,
                        'kelebihan' => $newLaptopData['kelebihan'] ?? null,
                        'kekurangan' => $newLaptopData['kekurangan'] ?? null,
                        'stock' => 0,
                        'uninspected_stock' => 0,
                        'qc_passed_stock' => 0,
                        'is_active' => false, // Nonaktif / QC Off di awal sampai lolos inspeksi QC
                        'is_featured' => false,
                    ]);

                    if (!empty($newLaptopData['categories'])) {
                        $laptop->categories()->sync($newLaptopData['categories']);
                    }
                } else {
                    throw new \InvalidArgumentException("Laptop ID atau data produk baru wajib disertakan dalam restock item.");
                }

                RestockItem::create([
                    'restock_id' => $restock->id,
                    'laptop_id' => $laptop->id,
                    'quantity' => $qty,
                    'purchase_price' => $price,
                    'subtotal' => $subtotal,
                    'notes' => $itemData['notes'] ?? null,
                ]);

                // 2. Generasikan unit barang fisik (Pending QC, SKU belum diterbitkan)
                for ($i = 0; $i < $qty; $i++) {
                    ProductItem::create([
                        'restock_id' => $restock->id,
                        'laptop_id' => $laptop->id,
                        'sku' => null, // SKU terbit HANYA setelah lolos QC
                        'serial_number' => null,
                        'qc_status' => 'pending',
                        'is_sold' => false,
                        'qc_checklist' => null,
                        'qc_notes' => null,
                    ]);
                }

                // 3. Tambahkan uninspected_stock (stok jual belum bertambah sampai QC Lolos)
                $laptop->increment('uninspected_stock', $qty);

                // 4. Log Mutasi Stok
                StockMovement::create([
                    'laptop_id' => $laptop->id,
                    'type' => 'PURCHASE',
                    'quantity' => $qty,
                    'stock_before' => $laptop->stock,
                    'stock_after' => $laptop->stock,
                    'reference_type' => Restock::class,
                    'reference_id' => $restock->id,
                    'notes' => "Restock {$qty} unit dari {$restock->supplier_name} (Pending QC)",
                    'user_id' => $creator->id,
                ]);
            }

            $restock->update(['total_amount' => $totalAmount]);

            // Kirim notifikasi WhatsApp ke Admin jika aktif
            $this->whatsAppService->sendRestockAlert($restock);

            return $restock;
        });
    }

    public function passQc(ProductItem $item, string $sku, ?string $serialNumber, array $checklist, ?string $notes, User $inspector): ProductItem
    {
        return DB::transaction(function () use ($item, $sku, $serialNumber, $checklist, $notes, $inspector) {
            $wasPending = $item->qc_status === 'pending';

            $item->update([
                'sku' => $sku,
                'serial_number' => $serialNumber,
                'qc_status' => 'passed',
                'qc_checklist' => $checklist,
                'qc_notes' => $notes,
                'qc_by' => $inspector->id,
                'qc_at' => now(),
            ]);

            $laptop = $item->laptop;

            if ($wasPending) {
                $laptop->decrement('uninspected_stock', 1);
            }

            // Tambahkan stok yang siap dijual (Sellable Stock)
            $stockBefore = $laptop->stock;
            $laptop->increment('stock', 1);
            $laptop->increment('qc_passed_stock', 1);

            // AKTIFKAN PRODUK OTOMATIS JIKA SEBELUMNYA NONAKTIF KARENA PENDING QC
            if (!$laptop->is_active) {
                $laptop->update(['is_active' => true]);
            }

            // Update status restock jika semua item sudah dicek
            if ($item->restock_id) {
                $this->updateRestockStatus($item->restock_id);
            }

            // Log Mutasi Stok
            StockMovement::create([
                'laptop_id' => $laptop->id,
                'product_item_id' => $item->id,
                'type' => 'QC_PASSED',
                'quantity' => 1,
                'stock_before' => $stockBefore,
                'stock_after' => $stockBefore + 1,
                'reference_type' => ProductItem::class,
                'reference_id' => $item->id,
                'notes' => "Unit lolos QC dengan SKU: {$sku} (Produk Aktif)",
                'user_id' => $inspector->id,
            ]);

            return $item;
        });
    }

    public function failQc(ProductItem $item, array $checklist, ?string $notes, User $inspector): ProductItem
    {
        return DB::transaction(function () use ($item, $checklist, $notes, $inspector) {
            $wasPending = $item->qc_status === 'pending';

            $item->update([
                'sku' => null, // Tidak lolos QC tidak mendapat SKU jual
                'qc_status' => 'failed',
                'qc_checklist' => $checklist,
                'qc_notes' => $notes,
                'qc_by' => $inspector->id,
                'qc_at' => now(),
            ]);

            $laptop = $item->laptop;

            if ($wasPending) {
                $laptop->decrement('uninspected_stock', 1);
            }

            if ($item->restock_id) {
                $this->updateRestockStatus($item->restock_id);
            }

            StockMovement::create([
                'laptop_id' => $laptop->id,
                'product_item_id' => $item->id,
                'type' => 'QC_FAILED',
                'quantity' => 0,
                'stock_before' => $laptop->stock,
                'stock_after' => $laptop->stock,
                'reference_type' => ProductItem::class,
                'reference_id' => $item->id,
                'notes' => "Unit GAGAL QC: " . ($notes ?? 'Cacat fisik/fungsi'),
                'user_id' => $inspector->id,
            ]);

            return $item;
        });
    }

    public function createSupplierReturn(array $data, User $creator): ProductReturn
    {
        return DB::transaction(function () use ($data, $creator) {
            $productItem = !empty($data['product_item_id']) ? ProductItem::find($data['product_item_id']) : null;
            $restock = !empty($data['restock_id']) ? Restock::find($data['restock_id']) : $productItem?->restock;
            $restockItem = !empty($data['restock_item_id']) ? RestockItem::find($data['restock_item_id']) : null;
            $laptop = $productItem?->laptop ?? ($restockItem ? $restockItem->laptop : null);

            $return = ProductReturn::create([
                'return_type' => 'supplier',
                'restock_id' => $restock?->id,
                'restock_item_id' => $restockItem?->id,
                'product_item_id' => $productItem?->id,
                'supplier_name' => $data['supplier_name'] ?? $restock?->supplier_name ?? 'Distributor Supplier',
                'supplier_phone' => $data['supplier_phone'] ?? $restock?->supplier_phone,
                'user_id' => $creator->id,
                'reason' => $data['reason'] ?? 'defective_item',
                'customer_notes' => $data['notes'] ?? 'Retur barang rusak / reject QC ke Supplier Restock',
                'proof_images' => $data['proof_images'] ?? null,
                'status' => 'approved',
                'resolution_type' => $data['resolution_type'] ?? 'replacement',
                'refund_amount' => $data['refund_amount'] ?? ($restockItem?->purchase_price ?? 0),
                'stock_action' => 'scrap_defective',
                'processed_by' => $creator->id,
                'admin_notes' => $data['admin_notes'] ?? 'Retur ke supplier diproses',
                'processed_at' => now(),
            ]);

            if ($productItem) {
                $productItem->update(['qc_status' => 'returned']);
            }

            if ($laptop) {
                // Log stock movement
                StockMovement::create([
                    'laptop_id' => $laptop->id,
                    'product_item_id' => $productItem?->id,
                    'type' => 'SUPPLIER_RETURN',
                    'quantity' => 1,
                    'stock_before' => $laptop->stock,
                    'stock_after' => $laptop->stock,
                    'reference_type' => ProductReturn::class,
                    'reference_id' => $return->id,
                    'notes' => "Unit diretur ke Supplier ({$return->supplier_name}) - No: {$return->return_number}",
                    'user_id' => $creator->id,
                ]);
            }

            return $return;
        });
    }

    public function reduceStockForSale(Laptop $laptop, ?object $variant, int $qty, Order $order, User $actor): void
    {
        $stockBefore = $laptop->stock;
        $laptop->decrement('stock', $qty);
        $laptop->decrement('qc_passed_stock', $qty);

        // Cari dan tandai unit ProductItem yang terjual
        $soldItems = ProductItem::where('laptop_id', $laptop->id)
            ->where('qc_status', 'passed')
            ->where('is_sold', false)
            ->take($qty)
            ->get();

        foreach ($soldItems as $unit) {
            $unit->update(['is_sold' => true]);
        }

        StockMovement::create([
            'laptop_id' => $laptop->id,
            'type' => $order->source === 'pos' ? 'POS_SALE' : 'SALE',
            'quantity' => $qty,
            'stock_before' => $stockBefore,
            'stock_after' => max(0, $stockBefore - $qty),
            'reference_type' => Order::class,
            'reference_id' => $order->id,
            'notes' => "Penjualan {$qty} unit pada Order #{$order->order_number} ({$order->source})",
            'user_id' => $actor->id,
        ]);
    }

    protected function updateRestockStatus(string $restockId): void
    {
        $restock = Restock::find($restockId);
        if (!$restock) return;

        $totalItems = $restock->productItems()->count();
        $pendingItems = $restock->productItems()->where('qc_status', 'pending')->count();

        if ($pendingItems === 0) {
            $restock->update(['status' => 'completed']);
        } elseif ($pendingItems < $totalItems) {
            $restock->update(['status' => 'partially_checked']);
        }
    }

    public function processReturn(ProductReturn $return, string $status, string $resolution, string $stockAction, ?string $adminNotes, User $processor): ProductReturn
    {
        return DB::transaction(function () use ($return, $status, $resolution, $stockAction, $adminNotes, $processor) {
            $return->update([
                'status' => $status,
                'resolution_type' => $resolution,
                'stock_action' => $stockAction,
                'admin_notes' => $adminNotes,
                'processed_by' => $processor->id,
                'processed_at' => now(),
            ]);

            $orderItem = $return->orderItem;
            $laptop = $orderItem?->laptop;

            if ($laptop && ($status === 'completed' || $status === 'item_received')) {
                if ($stockAction === 'return_to_stock') {
                    $stockBefore = $laptop->stock;
                    $laptop->increment('stock', 1);
                    $laptop->increment('qc_passed_stock', 1);

                    StockMovement::create([
                        'laptop_id' => $laptop->id,
                        'type' => 'RETURN_IN',
                        'quantity' => 1,
                        'stock_before' => $stockBefore,
                        'stock_after' => $stockBefore + 1,
                        'reference_type' => ProductReturn::class,
                        'reference_id' => $return->id,
                        'notes' => "Unit retur {$return->return_number} dikembalikan ke stok jual",
                        'user_id' => $processor->id,
                    ]);
                } elseif ($stockAction === 'return_to_quarantine_qc') {
                    $laptop->increment('uninspected_stock', 1);

                    if ($return->product_item_id) {
                        ProductItem::where('id', $return->product_item_id)->update(['qc_status' => 'pending', 'is_sold' => false]);
                    }

                    StockMovement::create([
                        'laptop_id' => $laptop->id,
                        'type' => 'RETURN_IN',
                        'quantity' => 0,
                        'stock_before' => $laptop->stock,
                        'stock_after' => $laptop->stock,
                        'reference_type' => ProductReturn::class,
                        'reference_id' => $return->id,
                        'notes' => "Unit retur {$return->return_number} masuk karantina QC ulang",
                        'user_id' => $processor->id,
                    ]);
                }
            }

            $this->whatsAppService->sendReturnStatus($return);

            return $return;
        });
    }
}
