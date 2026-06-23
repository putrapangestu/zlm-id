<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSliderRequest;
use App\Models\HeroSlider;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = HeroSlider::sorted()->paginate(10);
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(StoreSliderRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('sliders', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $request->sort_order ?? 0;

        HeroSlider::create($validated);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Hero slider created successfully.');
    }

    public function edit(HeroSlider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(StoreSliderRequest $request, HeroSlider $slider)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }
            $validated['image'] = $request->file('image')->store('sliders', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $request->sort_order ?? 0;

        $slider->update($validated);

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Hero slider updated successfully.');
    }

    public function destroy(HeroSlider $slider)
    {
        $slider->delete();

        return redirect()->route('admin.sliders.index')
            ->with('success', 'Hero slider moved to trash successfully.');
    }
}
