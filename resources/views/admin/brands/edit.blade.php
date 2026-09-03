@extends('layouts.admin')

@section('title', 'Edit Brand: ' . $brand->name)
@section('heading', 'Edit Brand Laptop')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.brands.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-900 flex items-center gap-1">
            <iconify-icon icon="solar:alt-arrow-left-linear"></iconify-icon>
            <span>Kembali ke Master Brand</span>
        </a>
    </div>

    <form method="POST" action="{{ route('admin.brands.update', $brand) }}" enctype="multipart/form-data" class="bg-white rounded-3xl border border-gray-200/70 p-6 shadow-sm space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Brand <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $brand->name) }}" required
                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
            @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Logo Brand (Ganti Logo)</label>
            @if($brand->logo_url_full)
                <div class="flex items-center gap-3 mb-2 p-2 bg-gray-50 rounded-xl border border-gray-200/60 w-max">
                    <img src="{{ $brand->logo_url_full }}" alt="" class="h-8 object-contain">
                    <span class="text-[11px] text-gray-500">Logo saat ini</span>
                </div>
            @endif
            <input type="file" name="logo" accept="image/png,image/jpeg,image/webp,image/svg+xml"
                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#DF5E1D]">
            <p class="text-[10px] text-gray-400 mt-1">Kosongkan jika tidak ingin mengganti logo. Maks 2MB.</p>
            @error('logo') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Deskripsi / Informasi Brand (Opsional)</label>
            <textarea name="description" rows="3"
                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#DF5E1D]">{{ old('description', $brand->description) }}</textarea>
            @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Urutan Tampilan</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $brand->sort_order) }}" min="0"
                    class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
            </div>
            <div class="flex items-center pt-5">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $brand->is_active)) class="w-4 h-4 text-[#DF5E1D] accent-[#DF5E1D]">
                    <span class="text-xs font-bold text-gray-700">Status Aktif</span>
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex items-center gap-3">
            <button type="submit" class="bg-[#DF5E1D] hover:bg-[#c45218] text-white px-6 py-2.5 rounded-xl text-xs font-bold transition shadow-xs flex items-center gap-1.5">
                <iconify-icon icon="solar:check-circle-bold" class="text-base"></iconify-icon>
                <span>Perbarui Brand</span>
            </button>
            <a href="{{ route('admin.brands.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-900 px-3 py-2">Batal</a>
        </div>
    </form>
</div>
@endsection
