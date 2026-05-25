<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Laptop;
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'kelebihan' => 'nullable|string',
            'kekurangan' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'is_featured' => 'boolean',
            'categories' => 'array|exists:categories,id',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['image_url'] = $this->handleImageUpload($request);

        $laptop = Laptop::create($data);

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
        $laptop->load(['categories', 'variants']);

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
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'kelebihan' => 'nullable|string',
            'kekurangan' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'is_featured' => 'boolean',
            'categories' => 'array|exists:categories,id',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            $this->deleteImageFile($laptop->image_url);
            $data['image_url'] = $this->handleImageUpload($request);
        } elseif ($request->boolean('remove_image')) {
            $this->deleteImageFile($laptop->image_url);
            $data['image_url'] = null;
        }

        $laptop->update($data);

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

    private function deleteImageFile(?string $path): void
    {
        if (!$path) {
            return;
        }

        if (!str_starts_with($path, 'http') && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
