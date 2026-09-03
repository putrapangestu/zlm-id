<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductItem;
use App\Models\ProductReturn;
use App\Models\Restock;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReturnController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    public function index(Request $request): View
    {
        $type = $request->get('type', 'all'); // 'all', 'customer', 'supplier'
        $status = $request->get('status');

        $query = ProductReturn::with(['user', 'order', 'orderItem.laptop', 'restock', 'productItem.laptop', 'processor']);

        if ($type === 'customer') {
            $query->where('return_type', 'customer');
        } elseif ($type === 'supplier') {
            $query->where('return_type', 'supplier');
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('return_number', 'like', "%{$search}%")
                  ->orWhere('supplier_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('order', function ($oq) use ($search) {
                      $oq->where('order_number', 'like', "%{$search}%");
                  });
            });
        }

        $returns = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total_customer' => ProductReturn::where('return_type', 'customer')->count(),
            'total_supplier' => ProductReturn::where('return_type', 'supplier')->count(),
            'total_pending' => ProductReturn::where('status', 'pending')->count(),
            'total_completed' => ProductReturn::where('status', 'completed')->count(),
        ];

        return view('admin.returns.index', compact('returns', 'stats', 'type', 'status'));
    }

    public function createSupplierReturn(Request $request): View
    {
        $restocks = Restock::with(['items.laptop'])->latest()->take(30)->get();
        $defectiveItems = ProductItem::with(['laptop', 'restock'])->where('qc_status', 'failed')->latest()->get();

        return view('admin.returns.create-supplier', compact('restocks', 'defectiveItems'));
    }

    public function storeSupplierReturn(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'supplier_phone' => 'nullable|string|max:50',
            'restock_id' => 'nullable|exists:restocks,id',
            'product_item_id' => 'nullable|exists:product_items,id',
            'reason' => 'required|in:defective_item,wrong_item,not_as_described,other',
            'resolution_type' => 'required|in:refund,replacement,repair',
            'refund_amount' => 'nullable|numeric|min:0',
            'notes' => 'required|string|max:1000',
            'proof_images' => 'nullable|array',
            'proof_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $uploadedImages = [];
        if ($request->hasFile('proof_images')) {
            foreach ($request->file('proof_images') as $img) {
                $uploadedImages[] = $img->store('returns', 'public');
            }
        }
        $validated['proof_images'] = $uploadedImages;

        $return = $this->inventoryService->createSupplierReturn($validated, auth()->user());

        return redirect()->route('admin.returns.show', $return)
            ->with('success', "Retur ke supplier {$return->supplier_name} berhasil dibuat (No. Retur: {$return->return_number}).");
    }

    public function show(ProductReturn $return): View
    {
        $return->load(['user', 'order', 'orderItem.laptop', 'restock.items.laptop', 'processor', 'productItem.laptop']);
        return view('admin.returns.show', compact('return'));
    }

    public function process(Request $request, ProductReturn $return): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,item_received,completed,cancelled',
            'resolution_type' => 'required|in:refund,replacement,repair',
            'stock_action' => 'required|in:return_to_quarantine_qc,return_to_stock,scrap_defective,no_stock_change',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $this->inventoryService->processReturn(
            $return,
            $validated['status'],
            $validated['resolution_type'],
            $validated['stock_action'],
            $validated['admin_notes'] ?? null,
            auth()->user()
        );

        return redirect()->route('admin.returns.show', $return)
            ->with('success', "Permohonan retur {$return->return_number} berhasil diperbarui (Status: " . strtoupper($validated['status']) . ").");
    }

    public function storeCustomerReturn(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'reason' => 'required|in:defective_item,wrong_item,not_as_described,change_of_mind,other',
            'customer_notes' => 'required|string|max:1000',
            'proof_images' => 'nullable|array',
            'proof_images.*' => 'image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        $orderItem = OrderItem::where('order_id', $order->id)->where('id', $validated['order_item_id'])->firstOrFail();

        $uploadedImages = [];
        if ($request->hasFile('proof_images')) {
            foreach ($request->file('proof_images') as $img) {
                $uploadedImages[] = $img->store('returns', 'public');
            }
        }

        $return = ProductReturn::create([
            'return_type' => 'customer',
            'order_id' => $order->id,
            'order_item_id' => $orderItem->id,
            'user_id' => auth()->id(),
            'product_item_id' => $orderItem->product_item_id,
            'reason' => $validated['reason'],
            'customer_notes' => $validated['customer_notes'],
            'proof_images' => $uploadedImages,
            'status' => 'pending',
            'resolution_type' => 'replacement',
            'refund_amount' => $orderItem->subtotal,
            'stock_action' => 'return_to_quarantine_qc',
        ]);

        return redirect()->back()
            ->with('success', "Permohonan retur {$return->return_number} telah dikirim dan akan diverifikasi oleh tim teknisi ZLM.");
    }
}
