<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laptop;
use App\Models\LaptopVariant;
use Illuminate\Http\Request;

class LaptopVariantController extends Controller
{
    public function index(Laptop $laptop)
    {
        $variants = $laptop->variants()->latest()->paginate(10);

        return view('admin.variants.index', compact('laptop', 'variants'));
    }

    public function create(Laptop $laptop)
    {
        return view('admin.variants.create', compact('laptop'));
    }

    public function store(Request $request, Laptop $laptop)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:laptop_variants,sku',
            'price_modifier' => 'required|numeric|min:0',
            'ram' => 'nullable|string|max:255',
            'storage' => 'nullable|string|max:255',
            'graphics' => 'nullable|string|max:255',
            'display' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|min:0',
            'battery_life' => 'nullable|string|max:255',
            'image_url' => 'nullable|url|max:2048',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['laptop_id'] = $laptop->id;

        $laptop->variants()->create($data);

        return redirect()->route('admin.laptops.variants.index', $laptop)
            ->with('success', 'Variant created successfully.');
    }

    public function show(LaptopVariant $variant)
    {
        return redirect()->route('admin.laptops.edit', $variant->laptop);
    }

    public function edit(LaptopVariant $variant)
    {
        $laptop = $variant->laptop;

        return view('admin.variants.edit', compact('laptop', 'variant'));
    }

    public function update(Request $request, LaptopVariant $variant)
    {
        $laptop = $variant->laptop;

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:laptop_variants,sku,' . $variant->id,
            'price_modifier' => 'required|numeric|min:0',
            'ram' => 'nullable|string|max:255',
            'storage' => 'nullable|string|max:255',
            'graphics' => 'nullable|string|max:255',
            'display' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|min:0',
            'battery_life' => 'nullable|string|max:255',
            'image_url' => 'nullable|url|max:2048',
            'stock' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $variant->update($data);

        return redirect()->route('admin.laptops.variants.index', $laptop)
            ->with('success', 'Variant updated successfully.');
    }

    public function destroy(LaptopVariant $variant)
    {
        $laptop = $variant->laptop;

        $variant->delete();

        return redirect()->route('admin.laptops.variants.index', $laptop)
            ->with('success', 'Variant deleted successfully.');
    }
}
