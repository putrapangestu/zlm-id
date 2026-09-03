<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AddonController extends Controller
{
    public function index(Request $request): View
    {
        $query = Addon::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('recommended')) {
            $query->where('is_recommended', $request->boolean('recommended'));
        }

        $addons = $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $stats = [
            'total' => Addon::count(),
            'active' => Addon::where('is_active', true)->count(),
            'recommended' => Addon::where('is_recommended', true)->count(),
        ];

        return view('admin.addons.index', compact('addons', 'stats'));
    }

    public function create(): View
    {
        return view('admin.addons.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_recommended' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = [
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'is_recommended' => $request->boolean('is_recommended'),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('addons', 'public');
        }

        Addon::create($data);

        return redirect()->route('admin.addons.index')
            ->with('success', 'Paket Add-on / Bundling berhasil ditambahkan.');
    }

    public function edit(Addon $addon): View
    {
        return view('admin.addons.edit', compact('addon'));
    }

    public function update(Request $request, Addon $addon): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_recommended' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $data = [
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'] ?? null,
            'is_recommended' => $request->boolean('is_recommended'),
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $validated['sort_order'] ?? 0,
        ];

        if ($request->hasFile('image')) {
            if ($addon->image_url && Storage::disk('public')->exists($addon->image_url)) {
                Storage::disk('public')->delete($addon->image_url);
            }
            $data['image_url'] = $request->file('image')->store('addons', 'public');
        } elseif ($request->boolean('remove_image')) {
            if ($addon->image_url && Storage::disk('public')->exists($addon->image_url)) {
                Storage::disk('public')->delete($addon->image_url);
            }
            $data['image_url'] = null;
        }

        $addon->update($data);

        return redirect()->route('admin.addons.index')
            ->with('success', 'Paket Add-on / Bundling berhasil diperbarui.');
    }

    public function destroy(Addon $addon): RedirectResponse
    {
        if ($addon->image_url && Storage::disk('public')->exists($addon->image_url)) {
            Storage::disk('public')->delete($addon->image_url);
        }

        $addon->delete();

        return redirect()->route('admin.addons.index')
            ->with('success', 'Paket Add-on / Bundling berhasil dihapus.');
    }

    public function toggleRecommended(Addon $addon): RedirectResponse
    {
        $addon->update(['is_recommended' => !$addon->is_recommended]);

        $status = $addon->is_recommended ? 'ditandai sebagai rekomendasi 👍' : 'dihapus dari rekomendasi';
        return redirect()->back()->with('success', "Paket {$addon->name} berhasil {$status}.");
    }

    public function toggleActive(Addon $addon): RedirectResponse
    {
        $addon->update(['is_active' => !$addon->is_active]);

        $status = $addon->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Paket {$addon->name} berhasil {$status}.");
    }
}
