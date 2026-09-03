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

        $rawCompareFields = \App\Models\Setting::getValue('compare_fields');
        $activeCompareFields = $rawCompareFields ? (is_array($rawCompareFields) ? $rawCompareFields : json_decode($rawCompareFields, true)) : null;
        
        $allSpecDefinitions = [
            'price' => ['label' => 'Harga Produk', 'icon' => 'solar:tag-price-linear', 'type' => 'price'],
            'processor' => ['label' => 'Processor', 'icon' => 'solar:cpu-linear', 'type' => 'text'],
            'ram' => ['label' => 'Memory (RAM)', 'icon' => 'solar:ram-linear', 'type' => 'text'],
            'storage' => ['label' => 'Storage / SSD', 'icon' => 'solar:database-linear', 'type' => 'text'],
            'graphics' => ['label' => 'Kartu Grafis (GPU)', 'icon' => 'solar:graph-new-linear', 'type' => 'text'],
            'display' => ['label' => 'Layar / Display', 'icon' => 'solar:monitor-linear', 'type' => 'text'],
            'ports' => ['label' => 'I/O Ports Colokan', 'icon' => 'solar:usb-linear', 'type' => 'multiline'],
            'camera' => ['label' => 'Webcam / Kamera', 'icon' => 'solar:videocamera-linear', 'type' => 'text'],
            'audio' => ['label' => 'Audio & Speaker', 'icon' => 'solar:volume-loud-linear', 'type' => 'text'],
            'connectivity' => ['label' => 'Konektivitas Nirkabel', 'icon' => 'solar:wi-fi-router-linear', 'type' => 'text'],
            'color' => ['label' => 'Warna Casing', 'icon' => 'solar:pallete-2-linear', 'type' => 'text'],
            'warranty' => ['label' => 'Garansi Unit', 'icon' => 'solar:shield-check-linear', 'type' => 'text'],
            'weight' => ['label' => 'Bobot / Berat', 'icon' => 'solar:case-minimalistic-linear', 'type' => 'weight'],
            'battery_life' => ['label' => 'Daya Baterai', 'icon' => 'solar:battery-charge-linear', 'type' => 'text'],
            'kelebihan' => ['label' => 'Poin Kelebihan', 'icon' => 'solar:like-linear', 'type' => 'html'],
            'kekurangan' => ['label' => 'Poin Kekurangan', 'icon' => 'solar:dislike-linear', 'type' => 'html'],
        ];

        // Filter only active specs (default: all active if null)
        $enabledSpecs = [];
        foreach ($allSpecDefinitions as $key => $spec) {
            if ($activeCompareFields === null || in_array($key, $activeCompareFields)) {
                $enabledSpecs[$key] = $spec;
            }
        }

        return view('landing.compare', compact('laptops', 'ids', 'enabledSpecs'));
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
