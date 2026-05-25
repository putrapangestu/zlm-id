<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompareController extends Controller
{
    public function index(): View
    {
        $ids = session('compare', []);
        $laptops = [];

        if (!empty($ids)) {
            $laptops = Laptop::with('categories', 'variants')
                ->whereIn('id', $ids)
                ->get()
                ->keyBy('id');
        }

        return view('landing.compare', compact('laptops', 'ids'));
    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'laptop_id' => ['required', 'string', 'exists:laptops,id'],
        ]);

        $compare = session('compare', []);

        if (in_array($request->laptop_id, $compare)) {
            return response()->json([
                'success' => false,
                'message' => 'Produk sudah ada dalam daftar perbandingan.',
            ], 409);
        }

        if (count($compare) >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Maksimal 3 produk dapat dibandingkan.',
            ], 422);
        }

        $compare[] = $request->laptop_id;
        session(['compare' => $compare]);

        return response()->json([
            'success' => true,
            'message' => 'Ditambahkan ke perbandingan.',
            'count' => count($compare),
        ]);
    }

    public function remove(string $laptop): JsonResponse
    {
        $compare = session('compare', []);
        $compare = array_values(array_filter($compare, fn($id) => $id !== $laptop));
        session(['compare' => $compare]);

        return response()->json([
            'success' => true,
            'message' => 'Dihapus dari perbandingan.',
            'count' => count($compare),
        ]);
    }

    public function clear(): JsonResponse
    {
        session()->forget('compare');

        return response()->json([
            'success' => true,
            'message' => 'Daftar perbandingan dikosongkan.',
        ]);
    }

    public function ids(): JsonResponse
    {
        return response()->json([
            'ids' => session('compare', []),
        ]);
    }

    public function products(Request $request): JsonResponse
    {
        $query = Laptop::with('categories');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        $products = $query->orderBy('name')->take(20)->get();

        return response()->json([
            'products' => $products,
        ]);
    }
}
