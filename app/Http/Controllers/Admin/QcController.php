<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductItem;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QcController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    public function index(Request $request): View
    {
        $status = $request->get('status', 'pending');
        $query = ProductItem::with(['laptop', 'variant', 'restock', 'inspector']);

        if ($status !== 'all') {
            $query->where('qc_status', $status);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%")
                  ->orWhereHas('laptop', function ($lq) use ($search) {
                      $lq->where('name', 'like', "%{$search}%")
                         ->orWhere('brand', 'like', "%{$search}%");
                  });
            });
        }

        $items = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total_pending' => ProductItem::where('qc_status', 'pending')->count(),
            'total_passed' => ProductItem::where('qc_status', 'passed')->count(),
            'total_failed' => ProductItem::where('qc_status', 'failed')->count(),
            'pass_rate' => ProductItem::whereIn('qc_status', ['passed', 'failed'])->count() > 0
                ? round((ProductItem::where('qc_status', 'passed')->count() / ProductItem::whereIn('qc_status', ['passed', 'failed'])->count()) * 100, 1)
                : 100,
        ];

        return view('admin.qc.index', compact('items', 'stats', 'status'));
    }

    public function inspect(ProductItem $item): View
    {
        $item->load(['laptop', 'restock']);
        return view('admin.qc.inspect', compact('item'));
    }

    public function approve(Request $request, ProductItem $item): RedirectResponse
    {
        $validated = $request->validate([
            'sku' => 'required|string|max:100|unique:product_items,sku,' . $item->id,
            'serial_number' => 'nullable|string|max:100',
            'checklist' => 'required|array',
            'checklist.screen' => 'required|in:ok,minor,defect',
            'checklist.keyboard' => 'required|in:ok,minor,defect',
            'checklist.battery' => 'required|in:ok,minor,defect',
            'checklist.body' => 'required|in:ok,minor,defect',
            'checklist.ports' => 'required|in:ok,minor,defect',
            'checklist.webcam' => 'required|in:ok,minor,defect',
            'checklist.specs' => 'required|in:match,mismatch',
            'notes' => 'nullable|string|max:1000',
        ]);

        $this->inventoryService->passQc(
            $item,
            $validated['sku'],
            $validated['serial_number'] ?? null,
            $validated['checklist'],
            $validated['notes'] ?? null,
            auth()->user()
        );

        return redirect()->route('admin.qc.index')
            ->with('success', "Unit {$item->laptop->name} berhasil LOLOS QC dan siap dijual dengan SKU: {$validated['sku']}. Stok bertambah!");
    }

    public function reject(Request $request, ProductItem $item): RedirectResponse
    {
        $validated = $request->validate([
            'checklist' => 'required|array',
            'defect_reason' => 'required|string|max:1000',
        ]);

        $this->inventoryService->failQc(
            $item,
            $validated['checklist'],
            $validated['defect_reason'],
            auth()->user()
        );

        return redirect()->route('admin.qc.index')
            ->with('warning', "Unit {$item->laptop->name} ditandai GAGAL QC (Karantina / Rusak) dan TIDAK dimasukkan ke stok jual.");
    }
}
