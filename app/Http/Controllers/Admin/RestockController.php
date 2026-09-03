<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Laptop;
use App\Models\Restock;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RestockController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    public function index(Request $request): View
    {
        $query = Restock::with(['creator', 'items.laptop'])
            ->withCount('productItems');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('restock_number', 'like', "%{$search}%")
                  ->orWhere('supplier_name', 'like', "%{$search}%")
                  ->orWhere('invoice_number', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
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
        return view('admin.restocks.create', compact('laptops', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'supplier_phone' => 'nullable|string|max:50',
            'invoice_number' => 'nullable|string|max:100',
            'purchase_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'entry_mode' => 'required|in:new_product,existing_product',
            // If existing product
            'items' => 'nullable|array',
            'items.*.laptop_id' => 'required_if:entry_mode,existing_product|exists:laptops,id',
            'items.*.quantity' => 'required_if:entry_mode,existing_product|integer|min:1',
            'items.*.purchase_price' => 'required_if:entry_mode,existing_product|numeric|min:0',
            'items.*.notes' => 'nullable|string|max:255',
            // If new product created directly from restock
            'new_laptop.name' => 'required_if:entry_mode,new_product|string|max:255',
            'new_laptop.brand' => 'required_if:entry_mode,new_product|string|max:255',
            'new_laptop.price' => 'nullable|numeric|min:0',
            'new_laptop.processor' => 'required_if:entry_mode,new_product|string|max:255',
            'new_laptop.ram' => 'required_if:entry_mode,new_product|string|max:255',
            'new_laptop.storage' => 'required_if:entry_mode,new_product|string|max:255',
            'new_laptop.graphics' => 'nullable|string|max:255',
            'new_laptop.display' => 'nullable|string|max:255',
            'new_laptop.weight' => 'nullable|numeric|min:0',
            'new_laptop.battery_life' => 'nullable|string|max:255',
            'new_laptop.description' => 'nullable|string',
            'new_laptop.kelebihan' => 'nullable|string',
            'new_laptop.kekurangan' => 'nullable|string',
            'new_laptop.categories' => 'nullable|array|exists:categories,id',
            'new_quantity' => 'required_if:entry_mode,new_product|integer|min:1',
            'new_purchase_price' => 'required_if:entry_mode,new_product|numeric|min:0',
        ]);

        $restockData = [
            'supplier_name' => $validated['supplier_name'],
            'supplier_phone' => $validated['supplier_phone'] ?? null,
            'invoice_number' => $validated['invoice_number'] ?? null,
            'purchase_date' => $validated['purchase_date'],
            'notes' => $validated['notes'] ?? null,
            'items' => [],
        ];

        if ($validated['entry_mode'] === 'new_product') {
            $restockData['items'][] = [
                'new_laptop' => $validated['new_laptop'],
                'quantity' => (int) $validated['new_quantity'],
                'purchase_price' => (float) $validated['new_purchase_price'],
                'notes' => 'Unit baru dari batch restock ' . $validated['supplier_name'],
            ];
        } else {
            $restockData['items'] = $validated['items'];
        }

        $restock = $this->inventoryService->createRestock($restockData, auth()->user());

        return redirect()->route('admin.restocks.show', $restock)
            ->with('success', "Batch restock {$restock->restock_number} berhasil dicatat. Seluruh unit barang masuk sebagai 'Pending QC' (status produk Nonaktif) sampai diinspeksi dan lolos QC.");
    }

    public function show(Restock $restock): View
    {
        $restock->load(['creator', 'items.laptop', 'productItems.laptop', 'productItems.inspector']);
        return view('admin.restocks.show', compact('restock'));
    }

    public function printDotMatrix(Restock $restock): View
    {
        $restock->load(['creator', 'items.laptop', 'productItems']);
        return view('admin.restocks.print-dotmatrix', compact('restock'));
    }
}
