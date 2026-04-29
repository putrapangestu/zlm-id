<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class LaptopController extends Controller
{
    /**
     * Home page with laptop recommendations
     */
    public function index()
    {
        $featured = Product::inRandomOrder()->take(6)->get();
        $categories = ['Gaming', 'Business', 'Student', 'Ultrabook'];

        return view('landing.home', compact('featured', 'categories'));
    }

    /**
     * Laptop search page with filters
     */
    public function search(Request $request)
    {
        $query = Product::query();

        // Filter by category
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('type', $request->category);
        }

        // Filter by price range
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter by brand
        if ($request->has('brand') && $request->brand) {
            $query->where('brand', $request->brand);
        }

        // Search by name or specifications
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('processor', 'like', "%$search%")
                    ->orWhere('ram', 'like', "%$search%");
            });
        }

        $products = $query->paginate(12);
        $brands = Product::distinct()->pluck('brand');
        $maxPrice = Product::max('price');

        return view('landing.search', compact('products', 'brands', 'maxPrice'));
    }

    /**
     * Compare laptops page
     */
    public function compare(Request $request)
    {
        $selected = $request->input('products', []);
        $products = [];

        if (!empty($selected)) {
            $products = Product::whereIn('id', $selected)->get();
        }

        return view('landing.compare', compact('products'));
    }

    /**
     * Detail page for specific laptop
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        // Get similar laptops (same type, different id)
        $similar = Product::where('type', $product->type)
            ->where('id', '!=', $id)
            ->take(4)
            ->get();

        return view('landing.detail', compact('product', 'similar'));
    }

    /**
     * Checkout page
     */
    public function checkout()
    {
        return view('landing.checkout');
    }

    /**
     * User profile page
     */
    public function profile()
    {
        return view('landing.profile');
    }
}
