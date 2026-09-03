<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Category;
use App\Models\HeroSlider;
use App\Models\Laptop;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class LaptopController extends Controller
{
    public function index()
    {
        $featured = Laptop::active()->featured()->with('categories')->take(8)->get();
        if ($featured->isEmpty()) {
            $featured = Laptop::active()->with('categories')->take(8)->get();
        }
        $categories = Category::where('is_active', true)->get();
        $testimonials = Testimonial::where('is_active', true)->latest()->take(3)->get();
        $sliders = HeroSlider::active()->sorted()->get();

        return view('landing.home', compact('featured', 'categories', 'testimonials', 'sliders'));
    }

    public function search(Request $request)
    {
        $query = Laptop::active()->with('categories');

        if ($request->has('category') && $request->category !== 'all') {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('brand')) {
            $query->where('brand', $request->brand);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('processor', 'like', "%{$search}%")
                    ->orWhere('ram', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // Sort logic
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $laptops = $query->paginate(12)->withQueryString();
        $brands = Laptop::active()->distinct()->pluck('brand');
        $maxPrice = Laptop::active()->max('price') ?? 50000000;
        $categories = Category::where('is_active', true)->get();

        return view('landing.search', compact('laptops', 'brands', 'maxPrice', 'categories'));
    }

    public function show($id)
    {
        $laptop = Laptop::with(['categories', 'reviews.user', 'images'])->findOrFail($id);

        $categoryIds = $laptop->categories->pluck('id');
        $similar = Laptop::active()
            ->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('categories.id', $categoryIds);
            })
            ->where('laptops.id', '!=', $laptop->id)
            ->with('categories')
            ->take(4)
            ->get();

        $reviews = $laptop->reviews()->with('user')->latest()->paginate(10);
        $addons = Addon::active()->sorted()->get();

        return view('landing.detail', compact('laptop', 'similar', 'reviews', 'addons'));
    }

    public function checkout()
    {
        return view('landing.checkout');
    }

    public function profile()
    {
        return view('landing.profile');
    }
}
