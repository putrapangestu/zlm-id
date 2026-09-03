<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Laptop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(Request $request): View
    {
        $query = Brand::withCount('laptops');

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        if ($request->has('status') && in_array($request->status, ['active', 'inactive'])) {
            $query->where('is_active', $request->status === 'active');
        }

        $brands = $query->sorted()->paginate(15)->withQueryString();

        $stats = [
            'total_brands' => Brand::count(),
            'active_brands' => Brand::where('is_active', true)->count(),
            'total_laptops' => Laptop::count(),
            'total_stock' => Laptop::sum('stock'),
        ];

        return view('admin.brands.index', compact('brands', 'stats'));
    }

    public function create(): View
    {
        return view('admin.brands.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $logoUrl = null;
        if ($request->hasFile('logo')) {
            $logoUrl = $request->file('logo')->store('brands', 'public');
        }

        Brand::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'logo_url' => $logoUrl,
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.brands.index')
            ->with('success', "Brand '{$validated['name']}' berhasil ditambahkan.");
    }

    public function show(Brand $brand): View
    {
        $brand->loadCount('laptops');
        
        $laptops = $brand->laptops()
            ->with(['categories', 'productItems'])
            ->latest()
            ->paginate(12);

        $stats = [
            'total_models' => $brand->laptops()->count(),
            'total_stock' => $brand->total_stock,
            'sold_units' => $brand->sold_units,
            'total_revenue' => $brand->total_revenue,
        ];

        return view('admin.brands.show', compact('brand', 'laptops', 'stats'));
    }

    public function edit(Brand $brand): View
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        if ($request->hasFile('logo')) {
            if ($brand->logo_url && Storage::disk('public')->exists($brand->logo_url)) {
                Storage::disk('public')->delete($brand->logo_url);
            }
            $data['logo_url'] = $request->file('logo')->store('brands', 'public');
        }

        $brand->update($data);

        // Keep laptop string brand in sync
        Laptop::where('brand_id', $brand->id)->update(['brand' => $brand->name]);

        return redirect()->route('admin.brands.index')
            ->with('success', "Brand '{$brand->name}' berhasil diperbarui.");
    }

    public function toggleActive(Brand $brand): RedirectResponse
    {
        $brand->update([
            'is_active' => !$brand->is_active,
        ]);

        $status = $brand->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Status brand {$brand->name} berhasil {$status}.");
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $name = $brand->name;
        
        if ($brand->logo_url && Storage::disk('public')->exists($brand->logo_url)) {
            Storage::disk('public')->delete($brand->logo_url);
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')
            ->with('success', "Brand '{$name}' berhasil dihapus.");
    }
}
