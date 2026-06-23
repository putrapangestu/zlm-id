@extends('layouts.admin')

@section('title', 'Tambah Hero Slider')
@section('heading', 'Tambah Hero Slider')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.sliders.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-[#DF5E1D] transition-colors">
            <iconify-icon icon="solar:arrow-left-linear"></iconify-icon>
            Back to Sliders
        </a>
    </div>

    <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm p-6 space-y-5">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                    placeholder="e.g., New 2026 Models Available">
                @error('title')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle') }}"
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                    placeholder="e.g., Premium Laptop Store Malang">
                @error('subtitle')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                    placeholder="Slider description text...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="button_text" class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                    <input type="text" name="button_text" id="button_text" value="{{ old('button_text') }}"
                        class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                        placeholder="e.g., Explore Catalog">
                    @error('button_text')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="button_url" class="block text-sm font-medium text-gray-700 mb-1">Button URL</label>
                    <input type="text" name="button_url" id="button_url" value="{{ old('button_url') }}"
                        class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                        placeholder="e.g., /search or https://...">
                    @error('button_url')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Background Image</label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                <p class="text-xs text-gray-500 mt-1">Max 5MB. JPG, PNG, or WebP. Recommended: 1920x800px.</p>
                @error('image')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                        class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                        placeholder="0">
                    @error('sort_order')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-2 pt-6">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-[#DF5E1D] focus:ring-[#DF5E1D]">
                    <label for="is_active" class="text-sm text-gray-700">Active (visible on website)</label>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.sliders.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:text-[#363230] transition-colors">
                Cancel
            </a>
            <button type="submit" class="bg-[#DF5E1D] text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-[#c45218] transition">
                Save Slider
            </button>
        </div>
    </form>
</div>
@endsection
