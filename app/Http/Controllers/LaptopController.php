<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use Illuminate\Http\Request;

class LaptopController extends Controller
{
    /**
     * Home page with laptop recommendations
     */
    public function index()
    {
        $featured = Laptop::featured()->take(6)->get();
        $categories = ['gaming', 'business', 'student', 'ultrabook'];

        return view('landing.home', compact('featured', 'categories'));
    }

    /**
     * Laptop search page with filters
     */
    public function search(Request $request)
    {
        $query = Laptop::query();

        // Filter by category
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
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

        $laptops = $query->paginate(12);
        $brands = Laptop::distinct()->pluck('brand');
        $maxPrice = Laptop::max('price');

        return view('landing.search', compact('laptops', 'brands', 'maxPrice'));
    }

    /**
     * Compare laptops page
     */
    public function compare(Request $request)
    {
        $selected = $request->input('laptops', []);
        $laptops = [];

        if (!empty($selected)) {
            $laptops = Laptop::whereIn('id', $selected)->get();
        }

        return view('landing.compare', compact('laptops'));
    }

    /**
     * Detail page for specific laptop
     */
    public function show($id)
    {
        $laptop = Laptop::findOrFail($id);

        // Get similar laptops (same category, different id)
        $similar = Laptop::where('category', $laptop->category)
            ->where('id', '!=', $id)
            ->take(4)
            ->get();

        return view('landing.detail', compact('laptop', 'similar'));
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
