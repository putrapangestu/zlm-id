<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products (laptops)
     */
    public function index()
    {
        $products = Product::latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created product in storage
     */
    public function store(Request $request)
    {
        // Validation would go here
        // Implementation for database storage would go here

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified product
     */
    public function show($id)
    {
        $product = Product::with('images')->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product
     */
    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified product in storage
     */
    public function update(Request $request, $id)
    {
        // Validation would go here
        // Implementation for database update would go here

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product from storage
     */
    public function destroy($id)
    {
        // Implementation for database deletion would go here

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
