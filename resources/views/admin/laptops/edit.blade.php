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

@section('title', 'Edit Laptop')
@section('heading', 'Edit Laptop')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    <form method="POST" action="{{ route('admin.laptops.update', $laptop) }}" class="xl:col-span-2 space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name', $laptop->name) }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Brand</label>
                    <input type="text" name="brand" value="{{ old('brand', $laptop->brand) }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('brand') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <input id="description" name="description" type="hidden" value="{{ old('description', $laptop->description) }}">
                <trix-editor input="description" class="trix-content"></trix-editor>
                @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price (Rp)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $laptop->price) }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number" name="stock" value="{{ old('stock', $laptop->stock) }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('stock') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Processor</label>
                    <input type="text" name="processor" value="{{ old('processor', $laptop->processor) }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('processor') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">RAM</label>
                    <input type="text" name="ram" value="{{ old('ram', $laptop->ram) }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('ram') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Storage</label>
                    <input type="text" name="storage" value="{{ old('storage', $laptop->storage) }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('storage') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Graphics</label>
                    <input type="text" name="graphics" value="{{ old('graphics', $laptop->graphics) }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('graphics') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Display</label>
                    <input type="text" name="display" value="{{ old('display', $laptop->display) }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('display') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                    <input type="number" step="0.01" name="weight" value="{{ old('weight', $laptop->weight) }}" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('weight') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Battery Life</label>
                    <input type="text" name="battery_life" value="{{ old('battery_life', $laptop->battery_life) }}" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('battery_life') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image URL</label>
                    <input type="url" name="image_url" value="{{ old('image_url', $laptop->image_url) }}" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('image_url') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kelebihan</label>
                <input id="kelebihan" name="kelebihan" type="hidden" value="{{ old('kelebihan', $laptop->kelebihan) }}">
                <trix-editor input="kelebihan" class="trix-content"></trix-editor>
                <p class="mt-1 text-xs text-gray-400">Tulis poin kelebihan produk. Gunakan list bullet untuk setiap poin.</p>
                @error('kelebihan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kekurangan</label>
                <input id="kekurangan" name="kekurangan" type="hidden" value="{{ old('kekurangan', $laptop->kekurangan) }}">
                <trix-editor input="kekurangan" class="trix-content"></trix-editor>
                <p class="mt-1 text-xs text-gray-400">Tulis poin kekurangan produk. Gunakan list bullet untuk setiap poin.</p>
                @error('kekurangan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Categories</label>
                <div class="flex flex-wrap gap-3">
                    @foreach ($categories as $cat)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="categories[]" value="{{ $cat->id }}" @checked(in_array($cat->id, $laptop->categories->pluck('id')->toArray())) class="w-4 h-4 text-[#DF5E1D] accent-[#DF5E1D]">
                            <span class="text-sm text-gray-600">{{ $cat->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('categories') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" @checked($laptop->is_featured) class="w-4 h-4 text-[#DF5E1D] accent-[#DF5E1D]">
                <label for="is_featured" class="text-sm text-gray-600">Featured product</label>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="bg-[#DF5E1D] text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors">
                Update Laptop
            </button>
            <a href="{{ route('admin.laptops.index') }}" class="text-sm text-gray-500 hover:text-[#363230] transition-colors">Cancel</a>
        </div>
    </form>

    <div class="xl:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Informasi Produk</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-gray-400">Harga</dt>
                    <dd class="font-medium text-[#363230]">Rp {{ number_format($laptop->price, 0, ',', '.') }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-gray-400">Stok</dt>
                    <dd class="font-medium text-[#363230]">{{ $laptop->stock }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-gray-400">Kategori</dt>
                    <dd class="font-medium text-[#363230]">{{ $laptop->categories->pluck('name')->join(', ') ?: '-' }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-gray-400">Varian</dt>
                    <dd class="font-medium text-[#363230]">{{ $laptop->variants->count() }} varian</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-gray-400">Dibuat</dt>
                    <dd class="font-medium text-[#363230]">{{ $laptop->created_at->format('d M Y') }}</dd>
                </div>
                <div class="flex justify-between py-2">
                    <dt class="text-gray-400">Featured</dt>
                    <dd class="font-medium {{ $laptop->is_featured ? 'text-emerald-600' : 'text-gray-400' }}">{{ $laptop->is_featured ? 'Ya' : 'Tidak' }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                <iconify-icon icon="solar:chart-2-linear" class="text-[#DF5E1D]"></iconify-icon>
                Lihat Detail
            </h3>
            <p class="text-sm text-gray-500">Kunjungi halaman detail produk untuk melihat informasi lengkap, spesifikasi, varian, dan riwayat penjualan.</p>
            <a href="{{ route('admin.laptops.show', $laptop) }}" class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-[#DF5E1D] hover:text-[#c45218] transition-colors">
                <iconify-icon icon="solar:eye-linear"></iconify-icon>
                Lihat Detail Produk
            </a>
        </div>
    </div>
</div>
@endsection
