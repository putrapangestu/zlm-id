@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<style>
    trix-editor { min-height: 200px; }
    trix-toolbar .trix-button-group { margin-bottom: 0; }
    .trix-button-row { flex-wrap: wrap; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endpush

@section('title', 'Create Laptop')
@section('heading', 'Create Laptop')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    <form method="POST" action="{{ route('admin.laptops.store') }}" class="xl:col-span-2 space-y-6">
        @csrf

        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                    <input type="text" name="brand" value="{{ old('brand') }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('brand') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <input id="description" name="description" type="hidden" value="{{ old('description') }}">
                <trix-editor input="description" class="trix-content"></trix-editor>
                @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (Rp)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('stock') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Processor</label>
                    <input type="text" name="processor" value="{{ old('processor') }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('processor') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">RAM</label>
                    <input type="text" name="ram" value="{{ old('ram') }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('ram') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Storage</label>
                    <input type="text" name="storage" value="{{ old('storage') }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('storage') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Graphics</label>
                    <input type="text" name="graphics" value="{{ old('graphics') }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('graphics') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Display</label>
                    <input type="text" name="display" value="{{ old('display') }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('display') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                    <input type="number" step="0.01" name="weight" value="{{ old('weight') }}" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('weight') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Battery Life</label>
                    <input type="text" name="battery_life" value="{{ old('battery_life') }}" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('battery_life') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
                    <input type="url" name="image_url" value="{{ old('image_url') }}" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('image_url') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kelebihan</label>
                <input id="kelebihan" name="kelebihan" type="hidden" value="{{ old('kelebihan') }}">
                <trix-editor input="kelebihan" class="trix-content"></trix-editor>
                <p class="mt-1 text-xs text-gray-400">Tulis poin kelebihan produk. Gunakan list bullet untuk setiap poin.</p>
                @error('kelebihan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kekurangan</label>
                <input id="kekurangan" name="kekurangan" type="hidden" value="{{ old('kekurangan') }}">
                <trix-editor input="kekurangan" class="trix-content"></trix-editor>
                <p class="mt-1 text-xs text-gray-400">Tulis poin kekurangan produk. Gunakan list bullet untuk setiap poin.</p>
                @error('kekurangan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
                <div class="flex flex-wrap gap-3">
                    @foreach ($categories as $cat)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="categories[]" value="{{ $cat->id }}" class="w-4 h-4 text-[#DF5E1D] accent-[#DF5E1D]">
                            <span class="text-sm text-gray-600">{{ $cat->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('categories') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" class="w-4 h-4 text-[#DF5E1D] accent-[#DF5E1D]">
                <label for="is_featured" class="text-sm text-gray-600">Featured product</label>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-[#DF5E1D] text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors">
                Create Laptop
            </button>
            <a href="{{ route('admin.laptops.index') }}" class="text-sm text-gray-500 hover:text-[#363230] transition-colors">Cancel</a>
        </div>
    </form>

    <div class="xl:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Informasi</h3>
            <ul class="space-y-3 text-sm text-gray-500">
                <li class="flex items-start gap-2">
                    <iconify-icon icon="solar:info-circle-linear" class="text-base text-[#DF5E1D] mt-0.5 shrink-0"></iconify-icon>
                    <span>Setelah produk dibuat, admin dapat menambahkan varian (warna/penyimpanan) dari halaman detail produk.</span>
                </li>
                <li class="flex items-start gap-2">
                    <iconify-icon icon="solar:gallery-linear" class="text-base text-[#DF5E1D] mt-0.5 shrink-0"></iconify-icon>
                    <span>Gunakan URL gambar dari layanan hosting eksternal untuk menghindari file besar di server.</span>
                </li>
                <li class="flex items-start gap-2">
                    <iconify-icon icon="solar:star-linear" class="text-base text-[#DF5E1D] mt-0.5 shrink-0"></iconify-icon>
                    <span>Centang "Featured" jika produk ini ingin ditampilkan di halaman utama.</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
