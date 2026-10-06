<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Laptop;
use App\Models\ProductItem;
use App\Models\Restock;
use App\Models\Supplier;
use App\Services\InventoryService;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RestockController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    public function index(Request $request): View
    {
        $query = Restock::with(['creator', 'supplier', 'items.laptop'])
            ->withCount('productItems');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('restock_number', 'like', "%{$search}%")
                  ->orWhere('supplier_name', 'like', "%{$search}%")
                  ->orWhere('invoice_number', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($shippingStatus = $request->get('shipping_status')) {
            $query->where('shipping_status', $shippingStatus);
        }

        if ($startDate = $request->get('start_date')) {
            $query->whereDate('purchase_date', '>=', $startDate);
        }
        if ($endDate = $request->get('end_date')) {
            $query->whereDate('purchase_date', '<=', $endDate);
        }

        $restocks = $query->latest('purchase_date')->paginate(15)->withQueryString();

        $stats = [
            'total_batches' => Restock::count(),
            'total_invested' => Restock::sum('total_amount'),
            'total_units' => \App\Models\RestockItem::sum('quantity'),
        ];

        return view('admin.restocks.index', compact('restocks', 'stats'));
    }

    public function create(): View
    {
        $laptops = Laptop::orderBy('name')->get();
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::active()->sorted()->get();
        $suppliers = Supplier::active()->orderBy('name')->get();

        return view('admin.restocks.create', compact('laptops', 'categories', 'brands', 'suppliers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'supplier_name' => 'nullable|string|max:255',
            'supplier_phone' => 'nullable|string|max:50',
            'shipping_status' => 'nullable|in:pending,in_transit,received,completed',
            'shipping_courier' => 'nullable|string|max:100',
            'tracking_number' => 'nullable|string|max:100',
            'invoice_number' => 'nullable|string|max:100',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'entry_mode' => 'required|in:new_product,existing_product',
            
            // Multiple existing items
            'items' => 'nullable|array',
            'items.*.laptop_id' => 'nullable|exists:laptops,id',
            'items.*.quantity' => 'nullable|integer|min:1',
            'items.*.purchase_price' => 'nullable|numeric|min:0',
            'items.*.notes' => 'nullable|string|max:255',

            // New product inputs
            'new_laptop.name' => 'nullable|string|max:255',
            'new_laptop.brand' => 'nullable|string|max:255',
            'new_laptop.brand_id' => 'nullable|exists:brands,id',
            'new_laptop.price' => 'nullable|numeric|min:0',
            'new_laptop.processor' => 'nullable|string|max:255',
            'new_laptop.ram' => 'nullable|string|max:255',
            'new_laptop.storage' => 'nullable|string|max:255',
            'new_laptop.graphics' => 'nullable|string|max:255',
            'new_laptop.display' => 'nullable|string|max:255',
            'new_laptop.ports' => 'nullable|string',
            'new_laptop.camera' => 'nullable|string|max:255',
            'new_laptop.audio' => 'nullable|string|max:255',
            'new_laptop.connectivity' => 'nullable|string|max:255',
            'new_laptop.color' => 'nullable|string|max:255',
            'new_laptop.warranty' => 'nullable|string|max:255',
            'new_laptop.weight' => 'nullable|numeric|min:0',
            'new_laptop.battery_life' => 'nullable|string|max:255',
            'new_laptop.description' => 'nullable|string',
            'new_laptop.kelebihan' => 'nullable|string',
            'new_laptop.kekurangan' => 'nullable|string',
            'new_laptop.categories' => 'nullable|array|exists:categories,id',
            'new_quantity' => 'nullable|integer|min:1',
            'new_purchase_price' => 'nullable|numeric|min:0',
            'new_laptops' => 'nullable|array',
            'new_laptops.*.name' => 'nullable|string|max:255',
            'new_laptops.*.brand' => 'nullable|string|max:255',
            'new_laptops.*.brand_id' => 'nullable|exists:brands,id',
            'new_laptops.*.price' => 'nullable|numeric|min:0',
            'new_laptops.*.processor' => 'nullable|string|max:255',
            'new_laptops.*.ram' => 'nullable|string|max:255',
            'new_laptops.*.storage' => 'nullable|string|max:255',
            'new_laptops.*.graphics' => 'nullable|string|max:255',
            'new_laptops.*.display' => 'nullable|string|max:255',
            'new_laptops.*.ports' => 'nullable|string',
            'new_laptops.*.camera' => 'nullable|string|max:255',
            'new_laptops.*.audio' => 'nullable|string|max:255',
            'new_laptops.*.connectivity' => 'nullable|string|max:255',
            'new_laptops.*.color' => 'nullable|string|max:255',
            'new_laptops.*.warranty' => 'nullable|string|max:255',
            'new_laptops.*.weight' => 'nullable|numeric|min:0',
            'new_laptops.*.battery_life' => 'nullable|string|max:255',
            'new_laptops.*.description' => 'nullable|string',
            'new_laptops.*.kelebihan' => 'nullable|string',
            'new_laptops.*.kekurangan' => 'nullable|string',
            'new_laptops.*.categories' => 'nullable|array|exists:categories,id',
            'new_laptops.*.quantity' => 'nullable|integer|min:1',
            'new_laptops.*.purchase_price' => 'nullable|numeric|min:0',
        ]);

        $supplierId = $validated['supplier_id'] ?? null;
        $supplierName = $validated['supplier_name'] ?? null;
        $supplierPhone = $validated['supplier_phone'] ?? null;

        if ($supplierId) {
            $supplierObj = Supplier::find($supplierId);
            if ($supplierObj) {
                $supplierName = $supplierName ?: $supplierObj->name;
                $supplierPhone = $supplierPhone ?: $supplierObj->phone;
            }
        }

        if (empty($supplierName)) {
            return back()->withInput()->with('error', 'Silakan pilih atau masukkan nama supplier.');
        }

        $restockData = [
            'supplier_id' => $supplierId,
            'supplier_name' => $supplierName,
            'supplier_phone' => $supplierPhone,
            'shipping_status' => $validated['shipping_status'] ?? 'received',
            'shipping_courier' => $validated['shipping_courier'] ?? null,
            'tracking_number' => $validated['tracking_number'] ?? null,
            'invoice_number' => $validated['invoice_number'] ?? null,
            'purchase_date' => $validated['purchase_date'],
            'notes' => $validated['notes'] ?? null,
            'items' => [],
        ];

        if ($validated['entry_mode'] === 'new_product') {
            $newProducts = [];
            if (!empty($validated['new_laptop']['name']) || !empty($validated['new_laptop']['processor'])) {
                $newProducts[] = [
                    'laptop' => $validated['new_laptop'],
                    'quantity' => (int) ($validated['new_quantity'] ?? 1),
                    'purchase_price' => (float) ($validated['new_purchase_price'] ?? 0),
                ];
            }
            foreach ($validated['new_laptops'] ?? [] as $newLaptop) {
                if (empty($newLaptop['name']) && empty($newLaptop['processor'])) {
                    continue;
                }
                $newProducts[] = [
                    'laptop' => $newLaptop,
                    'quantity' => (int) ($newLaptop['quantity'] ?? 1),
                    'purchase_price' => (float) ($newLaptop['purchase_price'] ?? 0),
                ];
            }

            if (empty($newProducts)) {
                return back()->withInput()->with('error', 'Masukkan minimal 1 model laptop baru.');
            }

            foreach ($newProducts as $newProduct) {
                if (empty($newProduct['laptop']['name']) || empty($newProduct['laptop']['processor'])) {
                    return back()->withInput()->with('error', 'Nama laptop dan processor wajib diisi untuk setiap model baru.');
                }

                $restockData['items'][] = [
                    'new_laptop' => $newProduct['laptop'],
                    'quantity' => $newProduct['quantity'],
                    'purchase_price' => $newProduct['purchase_price'],
                    'notes' => 'Unit baru dari batch restock ' . $supplierName,
                ];
            }
        } else {
            $validItems = [];
            if (!empty($validated['items'])) {
                foreach ($validated['items'] as $item) {
                    if (!empty($item['laptop_id']) && !empty($item['quantity']) && (int)$item['quantity'] > 0) {
                        $validItems[] = [
                            'laptop_id' => $item['laptop_id'],
                            'quantity' => (int) $item['quantity'],
                            'purchase_price' => (float) ($item['purchase_price'] ?? 0),
                            'notes' => $item['notes'] ?? null,
                        ];
                    }
                }
            }

            if (empty($validItems)) {
                return back()->withInput()->with('error', 'Minimal pilih 1 laptop yang di-restock.');
            }

            $restockData['items'] = $validItems;
        }

        $restock = $this->inventoryService->createRestock($restockData, auth()->user());

        return redirect()->route('admin.restocks.show', $restock)
            ->with('success', "Batch restock {$restock->restock_number} ({$restock->items->count()} model laptop) berhasil dicatat. Seluruh unit barang masuk sebagai 'Pending QC' (status produk Nonaktif) sampai diinspeksi dan lolos QC.");
    }

    public function show(Restock $restock): View
    {
        $restock->load(['creator', 'supplier', 'items.laptop', 'productItems.laptop', 'productItems.inspector']);
        return view('admin.restocks.show', compact('restock'));
    }

    public function updateShippingStatus(Request $request, Restock $restock): RedirectResponse
    {
        $validated = $request->validate([
            'shipping_status' => 'required|in:pending,in_transit,received,completed',
            'shipping_courier' => 'nullable|string|max:100',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $updates = [
            'shipping_status' => $validated['shipping_status'],
        ];

        if ($request->filled('shipping_courier')) {
            $updates['shipping_courier'] = $validated['shipping_courier'];
        }
        if ($request->filled('tracking_number')) {
            $updates['tracking_number'] = $validated['tracking_number'];
        }

        if ($validated['shipping_status'] === 'in_transit' && !$restock->shipped_at) {
            $updates['shipped_at'] = now();
        } elseif (in_array($validated['shipping_status'], ['received', 'completed']) && !$restock->received_at) {
            $updates['received_at'] = now();
        }

        $restock->update($updates);

        return redirect()->back()->with('success', 'Status pengiriman restock berhasil diperbarui.');
    }

    public function printDotMatrix(Restock $restock): View
    {
        $restock->load(['creator', 'supplier', 'items.laptop', 'productItems']);
        return view('admin.restocks.print-dotmatrix', compact('restock'));
    }

    public function exportQcPdf(Request $request, Restock $restock)
    {
        $validated = $request->validate([
            'product_item_ids' => 'required|array|min:1',
            'product_item_ids.*' => 'required|uuid|distinct|exists:product_items,id',
        ]);

        $items = ProductItem::with(['laptop', 'variant', 'inspector'])
            ->where('restock_id', $restock->id)
            ->where('qc_status', 'passed')
            ->whereIn('id', $validated['product_item_ids'])
            ->orderBy('created_at')
            ->get();

        if ($items->count() !== count($validated['product_item_ids'])) {
            abort(422, 'Pilih hanya unit yang sudah lolos QC pada batch restock ini.');
        }

        $items->each(function (ProductItem $item): void {
            $platformText = strtolower($item->laptop->brand . ' ' . $item->laptop->name);
            $item->setAttribute('inspection_platform', Str::contains($platformText, ['apple', 'macbook']) ? 'mac' : 'windows');

            $checklist = $item->qc_checklist ?? [];
            $statuses = collect($checklist)->filter(fn ($value) => in_array($value, ['ok', 'minor', 'defect', 'match', 'mismatch'], true));
            $item->setAttribute(
                'inspection_grade',
                $statuses->contains(fn ($value) => in_array($value, ['defect', 'mismatch'], true))
                    ? 'C'
                    : ($statuses->contains('minor') ? 'B' : 'A')
            );
        });

        $html = view('admin.restocks.qc-report-pdf', compact('restock', 'items'))->render();
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', false);

        $pdf = new Dompdf($options);
        $pdf->loadHtml($html, 'UTF-8');
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="inspection-' . Str::slug($restock->restock_number) . '.pdf"',
        ]);
    }
}
