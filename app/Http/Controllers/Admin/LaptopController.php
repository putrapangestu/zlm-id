<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Laptop;
use App\Models\LaptopImage;
use App\Models\ProductItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LaptopController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status', 'all'); // 'all', 'active', 'inactive'
        $stockStatus = $request->get('stock_status', 'all'); // 'all', 'in_stock', 'sold_out'

        $query = Laptop::with('categories', 'productItems');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('processor', 'like', "%{$search}%")
                    ->orWhereHas('productItems', function ($sq) use ($search) {
                        $sq->where('sku', 'like', "%{$search}%");
                    });
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        if ($stockStatus === 'in_stock') {
            $query->where('stock', '>', 0);
        } elseif ($stockStatus === 'sold_out') {
            $query->where('stock', '<=', 0);
        }

        $laptops = $query->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.laptops.index', compact('laptops', 'search', 'status', 'stockStatus'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();

        return view('admin.laptops.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_type' => 'nullable|in:none,fixed,percentage',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_start_date' => 'nullable|date',
            'discount_end_date' => 'nullable|date|after_or_equal:discount_start_date',
            'processor' => 'required|string|max:255',
            'ram' => 'required|string|max:255',
            'storage' => 'required|string|max:255',
            'graphics' => 'nullable|string|max:255',
            'display' => 'nullable|string|max:255',
            'ports' => 'nullable|string',
            'camera' => 'nullable|string|max:255',
            'audio' => 'nullable|string|max:255',
            'connectivity' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'warranty' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|min:0',
            'battery_life' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'kelebihan' => 'nullable|string',
            'kekurangan' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'categories' => 'array|exists:categories,id',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;
        $data['discount_type'] = $data['discount_type'] ?? 'none';
        $data['discount_value'] = $data['discount_value'] ?? 0;

        // Handle multiple image uploads
        $images = $request->file('images', []);
        if (!empty($images)) {
            $data['image_url'] = $this->handleImageUploadDirect($images[0]);
        } else {
            $data['image_url'] = null;
        }

        $laptop = Laptop::create($data);

        // Save additional images to laptop_images table
        $sortOrder = 0;
        foreach ($images as $index => $image) {
            $path = $this->handleImageUploadDirect($image);
            if ($path) {
                LaptopImage::create([
                    'laptop_id' => $laptop->id,
                    'image_url' => $path,
                    'sort_order' => $sortOrder++,
                    'caption' => null,
                ]);
            }
        }

        if ($request->has('categories')) {
            $laptop->categories()->attach($request->categories);
        }

        return redirect()->route('admin.laptops.index')
            ->with('success', 'Laptop berhasil ditambahkan dan siap dijual.');
    }

    public function show(Laptop $laptop)
    {
        $laptop->load('categories', 'productItems.inspector', 'images');

        return view('admin.laptops.show', compact('laptop'));
    }

    public function edit(Laptop $laptop)
    {
        $categories = Category::where('is_active', true)->get();
        $laptop->load(['categories', 'images']);

        return view('admin.laptops.edit', compact('laptop', 'categories'));
    }

    public function update(Request $request, Laptop $laptop)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_type' => 'nullable|in:none,fixed,percentage',
            'discount_value' => 'nullable|numeric|min:0',
            'discount_start_date' => 'nullable|date',
            'discount_end_date' => 'nullable|date|after_or_equal:discount_start_date',
            'processor' => 'required|string|max:255',
            'ram' => 'required|string|max:255',
            'storage' => 'required|string|max:255',
            'graphics' => 'nullable|string|max:255',
            'display' => 'nullable|string|max:255',
            'ports' => 'nullable|string',
            'camera' => 'nullable|string|max:255',
            'audio' => 'nullable|string|max:255',
            'connectivity' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'warranty' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|min:0',
            'battery_life' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:laptop_images,id',
            'kelebihan' => 'nullable|string',
            'kekurangan' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'categories' => 'array|exists:categories,id',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        $data['discount_type'] = $data['discount_type'] ?? 'none';
        $data['discount_value'] = $data['discount_value'] ?? 0;

        // Handle new image uploads
        $images = $request->file('images', []);
        if (!empty($images)) {
            $data['image_url'] = $this->handleImageUploadDirect($images[0]);
        }

        // Handle image deletions
        $deleteIds = $request->input('delete_images', []);
        if (!empty($deleteIds)) {
            $pathsBeingDeleted = $this->getImagePathsToDelete($deleteIds, $laptop->id);
            $mainImageBeingDeleted = in_array($laptop->image_url, $pathsBeingDeleted);

            $imagesToDelete = LaptopImage::whereIn('id', $deleteIds)->where('laptop_id', $laptop->id)->get();
            foreach ($imagesToDelete as $img) {
                $this->deleteImageFile($img->image_url);
                $img->delete();
            }

            if ($mainImageBeingDeleted && empty($data['image_url'])) {
                $nextImage = LaptopImage::where('laptop_id', $laptop->id)->orderBy('sort_order')->first();
                $data['image_url'] = $nextImage?->image_url;
            }
        }

        $laptop->update($data);

        // Save new images to laptop_images table
        $maxSort = LaptopImage::where('laptop_id', $laptop->id)->max('sort_order') ?? -1;
        $sortOrder = $maxSort + 1;
        foreach ($images as $index => $image) {
            $path = $this->handleImageUploadDirect($image);
            if ($path) {
                LaptopImage::create([
                    'laptop_id' => $laptop->id,
                    'image_url' => $path,
                    'sort_order' => $sortOrder++,
                    'caption' => null,
                ]);
            }
        }

        if ($request->has('categories')) {
            $laptop->categories()->sync($request->categories);
        } else {
            $laptop->categories()->detach();
        }

        return redirect()->route('admin.laptops.index')
            ->with('success', 'Data laptop & spesifikasi berhasil diperbarui.');
    }

    public function toggleStatus(Laptop $laptop)
    {
        $laptop->update([
            'is_active' => !$laptop->is_active,
        ]);

        $statusText = $laptop->is_active ? 'Diaktifkan' : 'Dinonaktifkan';

        return redirect()->back()
            ->with('success', "Status produk {$laptop->name} berhasil {$statusText}.");
    }

    public function apiSearchTemplates(Request $request): JsonResponse
    {
        $q = trim($request->get('q', ''));

        $query = Laptop::with(['categories', 'productItems' => function ($sq) {
            $sq->whereNotNull('sku')->select('id', 'laptop_id', 'sku', 'qc_status');
        }]);

        if ($q) {
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                    ->orWhere('brand', 'like', "%{$q}%")
                    ->orWhere('processor', 'like', "%{$q}%")
                    ->orWhereHas('productItems', function ($sq) use ($q) {
                        $sq->where('sku', 'like', "%{$q}%");
                    });
            });
        }

        $laptops = $query->latest()->take(20)->get()->map(function ($laptop) {
            $skus = $laptop->productItems->pluck('sku')->filter()->values()->toArray();
            return [
                'id' => $laptop->id,
                'name' => $laptop->name,
                'brand' => $laptop->brand,
                'price' => $laptop->price,
                'processor' => $laptop->processor,
                'ram' => $laptop->ram,
                'storage' => $laptop->storage,
                'graphics' => $laptop->graphics,
                'display' => $laptop->display,
                'ports' => $laptop->ports,
                'camera' => $laptop->camera,
                'audio' => $laptop->audio,
                'connectivity' => $laptop->connectivity,
                'color' => $laptop->color,
                'warranty' => $laptop->warranty,
                'weight' => $laptop->weight,
                'battery_life' => $laptop->battery_life,
                'description' => $laptop->description,
                'kelebihan' => $laptop->kelebihan,
                'kekurangan' => $laptop->kekurangan,
                'image_url' => $laptop->image_url,
                'category_ids' => $laptop->categories->pluck('id')->toArray(),
                'skus' => $skus,
                'sku_display' => count($skus) > 0 ? implode(', ', array_slice($skus, 0, 3)) : 'Belum ada SKU',
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $laptops,
        ]);
    }

    public function destroy(Laptop $laptop)
    {
        foreach ($laptop->images as $image) {
            $this->deleteImageFile($image->image_url);
        }

        $this->deleteImageFile($laptop->image_url);

        $laptop->delete();

        return redirect()->route('admin.laptops.index')
            ->with('success', 'Laptop berhasil dihapus.');
    }

    private function handleImageUploadDirect($file): ?string
    {
        if (!$file) {
            return null;
        }

        return $file->store('laptops', 'public');
    }

    private function deleteImageFile(?string $path): void
    {
        if (!$path) {
            return;
        }

        if (!str_starts_with($path, 'http') && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function getImagePathsToDelete(array $deleteIds, string $laptopId): array
    {
        return LaptopImage::whereIn('id', $deleteIds)
            ->where('laptop_id', $laptopId)
            ->pluck('image_url')
            ->toArray();
    }
}
