@extends('layouts.admin')

@section('title', 'Tambah Varian')
@section('heading', 'Tambah Varian')

@section('content')
<div class="w-full max-w-full">
    <form method="POST" action="{{ route('admin.laptops.variants.store', $laptop) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 space-y-5">
            <p class="text-sm text-gray-500">Adding variant for: <strong>{{ $laptop->name }}</strong></p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Variant Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. 16GB / 512GB" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" required placeholder="e.g. mbp16-32-1t" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('sku') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price Modifier (Rp)</label>
                    <input type="number" step="0.01" name="price_modifier" value="{{ old('price_modifier', 0) }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('price_modifier') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('stock') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">RAM (override)</label>
                    <input type="text" name="ram" value="{{ old('ram') }}" placeholder="e.g. 32GB DDR5" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Storage (override)</label>
                    <input type="text" name="storage" value="{{ old('storage') }}" placeholder="e.g. 1TB SSD" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Graphics (override)</label>
                    <input type="text" name="graphics" value="{{ old('graphics') }}" placeholder="e.g. RTX 4060" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Display (override)</label>
                    <input type="text" name="display" value="{{ old('display') }}" placeholder="e.g. 15.6\" FHD IPS" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                    <input type="number" step="0.01" min="0" name="weight" value="{{ old('weight') }}" placeholder="e.g. 1.5" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Battery Life</label>
                    <input type="text" name="battery_life" value="{{ old('battery_life') }}" placeholder="e.g. Up to 10 hours" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Variant Image</label>
                @include('admin.variants._image_upload')
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" checked class="w-4 h-4 text-[#DF5E1D] accent-[#DF5E1D]">
                <label for="is_active" class="text-sm text-gray-600">Active</label>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-[#DF5E1D] text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors">
                Create Variant
            </button>
            <a href="{{ route('admin.laptops.variants.index', $laptop) }}" class="text-sm text-gray-500 hover:text-[#363230] transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection
