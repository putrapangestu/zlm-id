@extends('layouts.admin')

@section('title', 'Add Testimonial')
@section('heading', 'Add Testimonial')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.testimonials.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-[#DF5E1D] transition-colors">
            <iconify-icon icon="solar:arrow-left-linear"></iconify-icon>
            Back to Testimonials
        </a>
    </div>

    <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm p-6 space-y-5">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                    placeholder="Customer name">
                @error('name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                <input type="text" name="position" id="position" value="{{ old('position') }}"
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                    placeholder="e.g., Software Engineer">
                @error('position')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content <span class="text-red-500">*</span></label>
                <textarea name="content" id="content" rows="4" required
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm"
                    placeholder="What the customer said...">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating <span class="text-red-500">*</span></label>
                <select name="rating" id="rating" required
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm">
                    @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}" {{ old('rating', 5) == $i ? 'selected' : '' }}>
                            {{ $i }} {{ Str::plural('star', $i) }}
                        </option>
                    @endfor
                </select>
                @error('rating')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Photo</label>
                <input type="file" name="photo" id="photo" accept="image/*"
                    class="w-full rounded-lg border-gray-200 focus:ring-[#DF5E1D] focus:border-[#DF5E1D] text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                <p class="text-xs text-gray-500 mt-1">Max 2MB. JPG, PNG, or WebP.</p>
                @error('photo')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-[#DF5E1D] focus:ring-[#DF5E1D]">
                <label for="is_active" class="text-sm text-gray-700">Active (visible on website)</label>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.testimonials.index') }}" class="px-4 py-2.5 text-sm font-medium text-gray-700 hover:text-[#363230] transition-colors">
                Cancel
            </a>
            <button type="submit" class="bg-[#DF5E1D] text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-[#c45218] transition">
                Save Testimonial
            </button>
        </div>
    </form>
</div>
@endsection
