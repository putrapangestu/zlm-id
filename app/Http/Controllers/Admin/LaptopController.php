<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Laptop;
use App\Models\LaptopImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LaptopController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $laptops = Laptop::with('categories', 'variants')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('processor', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.laptops.index', compact('laptops', 'search'));
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
            'processor' => 'required|string|max:255',
            'ram' => 'required|string|max:255',
            'storage' => 'required|string|max:255',
            'graphics' => 'nullable|string|max:255',
            'display' => 'nullable|string|max:255',
            'weight' => 'nullable|numeric|min:0',
            'battery_life' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'kelebihan' => 'nullable|string',
            'kekurangan' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'is_featured' => 'boolean',
            'categories' => 'array|exists:categories,id',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');

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
            ->with('success', 'Laptop created successfully.');
    }

    public function show(Laptop $laptop)
    {
        $laptop->load('categories', 'variants');

        return view('admin.laptops.show', compact('laptop'));
    }

    public function edit(Laptop $laptop)
    {
        $categories = Category::where('is_active', true)->get();
        $laptop->load(['categories', 'variants', 'images']);

        return view('admin.laptops.edit', compact('laptop', 'categories'));
    }

    public function update(Request $request, Laptop $laptop)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'processor' => 'required|string|max:255',
            'ram' => 'required|string|max:255',
            'storage' => 'required|string|max:255',
            'graphics' => 'nullable|string|max:255',
            'display' => 'nullable|string|max:255',
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
            'categories' => 'array|exists:categories,id',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');

        // Handle new image uploads
        $images = $request->file('images', []);
        if (!empty($images)) {
            $data['image_url'] = $this->handleImageUploadDirect($images[0]);
        }

        // Handle image deletions
        $deleteIds = $request->input('delete_images', []);
        if (!empty($deleteIds)) {
            // Check if the current main image is being deleted
            $pathsBeingDeleted = $this->getImagePathsToDelete($deleteIds, $laptop->id);
            $mainImageBeingDeleted = in_array($laptop->image_url, $pathsBeingDeleted);

            // Delete from storage and DB
            $imagesToDelete = LaptopImage::whereIn('id', $deleteIds)->where('laptop_id', $laptop->id)->get();
            foreach ($imagesToDelete as $img) {
                $this->deleteImageFile($img->image_url);
                $img->delete();
            }

            // If main image was deleted and no new image uploaded, promote next image
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
            ->with('success', 'Laptop updated successfully.');
    }

    public function destroy(Laptop $laptop)
    {
        // Delete all laptop images from storage
        foreach ($laptop->images as $image) {
            $this->deleteImageFile($image->image_url);
        }

        // Delete the main image
        $this->deleteImageFile($laptop->image_url);

        $laptop->delete();

        return redirect()->route('admin.laptops.index')
            ->with('success', 'Laptop deleted successfully.');
    }

    private function handleImageUpload(Request $request): ?string
    {
        if (!$request->hasFile('image')) {
            return null;
        }

        return $request->file('image')->store('laptops', 'public');
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
