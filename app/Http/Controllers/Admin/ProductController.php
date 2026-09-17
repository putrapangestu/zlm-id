<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('category');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->get('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($status = $request->get('status')) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        $stats = [
            'total_items' => Product::count(),
            'total_stock' => Product::sum('stock'),
            'total_asset' => Product::selectRaw('SUM(stock * cost_price) as total')->value('total') ?? 0,
            'low_stock' => Product::where('stock', '<=', 3)->where('stock', '>', 0)->count(),
        ];

        return view('admin.products.index', compact('products', 'categories', 'stats'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:2000',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'brand' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['sku'])) {
            $prefix = 'BRG';
            $validated['sku'] = $prefix . '-' . date('ymd') . '-' . strtoupper(Str::random(4));
        }

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        $validated['cost_price'] = $validated['cost_price'] ?? 0;
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', "Barang '{$validated['name']}' berhasil ditambahkan ke Master Barang.");
    }

    public function edit(Product $product): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:2000',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'brand' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $validated['cost_price'] = $validated['cost_price'] ?? 0;
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : false;

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', "Data barang '{$product->name}' berhasil diperbarui.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', "Barang '{$name}' berhasil dihapus.");
    }

    /**
     * API search for QC Additional Parts picker (Gambar 2)
     */
    public function apiSearch(Request $request): JsonResponse
    {
        $query = Product::where('is_active', true);

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $items = $query->take(20)->get(['id', 'name', 'sku', 'price', 'cost_price', 'stock']);

        return response()->json($items);
    }
}
