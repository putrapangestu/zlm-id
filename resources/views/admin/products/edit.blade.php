@extends('layouts.admin')

@section('title', 'Edit Barang — ZLM.ID Admin')
@section('heading', 'Edit Data Barang / Sparepart')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center gap-2 text-sm text-gray-400">
        <a href="{{ route('admin.products.index') }}" class="hover:text-[#DF5E1D] transition-colors">Master Barang</a>
        <iconify-icon icon="solar:alt-arrow-right-linear"></iconify-icon>
        <span class="text-[#363230] font-medium">Edit: {{ $product->name }}</span>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Barang --}}
            <div>
                <label for="name" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">
                    Nama Barang / Sparepart <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10"
                    placeholder="Nama barang...">
                @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- SKU & Kategori --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="sku" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">
                        Kode SKU Barang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">
                    @error('sku') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">
                        Kategori Barang
                    </label>
                    <select id="category_id" name="category_id"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Brand & Stok --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="brand" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">
                        Merk / Brand (Opsional)
                    </label>
                    <input type="text" id="brand" name="brand" value="{{ old('brand', $product->brand) }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">
                    @error('brand') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="stock" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">
                        Jumlah Stok Fisik <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">
                    @error('stock') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Harga Modal (HPP) & Harga Jual --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="cost_price" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">
                        Harga Modal / Beli HPP (Rp)
                    </label>
                    <input type="number" id="cost_price" name="cost_price" value="{{ old('cost_price', (int)$product->cost_price) }}" min="0" step="1000"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">
                    <p class="text-[11px] text-gray-400 mt-1">Biaya modal saat digunakan pada inspeksi QC laptop</p>
                    @error('cost_price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="price" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">
                        Harga Jual Retail (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="price" name="price" value="{{ old('price', (int)$product->price) }}" min="0" step="1000" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono font-bold text-[#DF5E1D] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">
                    @error('price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="description" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">
                    Deskripsi / Spesifikasi Barang
                </label>
                <textarea id="description" name="description" rows="3"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">{{ old('description', $product->description) }}</textarea>
                @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" id="is_active" name="is_active" value="1" @checked(old('is_active', $product->is_active))
                    class="rounded text-[#DF5E1D] focus:ring-[#DF5E1D]/20">
                <label for="is_active" class="text-xs font-medium text-gray-700">
                    Barang aktif (dapat dipilih di modul QC dan kasir toko)
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-semibold transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-[#DF5E1D] hover:bg-[#c45218] text-white rounded-xl text-xs font-bold shadow-sm transition-all flex items-center gap-2">
                    <iconify-icon icon="solar:check-circle-bold" class="text-base"></iconify-icon>
                    <span>Perbarui Barang</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
