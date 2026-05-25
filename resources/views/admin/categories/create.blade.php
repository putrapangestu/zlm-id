@extends('layouts.admin')

@section('title', 'Create Category')
@section('heading', 'Create Category')

@section('content')
<form method="POST" action="{{ route('admin.categories.store') }}" enctype="multipart/form-data" class="space-y-8">
    @csrf

    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="px-8 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50/50 to-white">
            <h3 class="text-base font-semibold text-[#363230] flex items-center gap-2">
                <iconify-icon icon="solar:info-circle-linear" class="text-[#DF5E1D]"></iconify-icon>
                Informasi Kategori
            </h3>
            <p class="text-xs text-gray-400 mt-1">Lengkapi detail kategori produk di bawah ini.</p>
        </div>
        <div class="p-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-8 gap-y-6">
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kategori <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all"
                        placeholder="cth: Gaming, Ultrabook, Office">
                    @error('name') <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1"><iconify-icon icon="solar:danger-circle-linear"></iconify-icon> {{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Icon (Iconify class)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" placeholder="solar:gamepad-linear"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    <p class="mt-1.5 text-xs text-gray-400 flex items-center gap-1"><iconify-icon icon="solar:info-circle-linear" class="text-gray-300"></iconify-icon> Dari iconify.design</p>
                    @error('icon') <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1"><iconify-icon icon="solar:danger-circle-linear"></iconify-icon> {{ $message }}</p> @enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category Image</label>
                    @include('admin.variants._image_upload', [
                        'inputId' => 'category-image-input',
                        'dropzoneId' => 'category-dropzone',
                        'previewId' => 'category-preview',
                        'emptyId' => 'category-empty',
                        'infoId' => 'category-info',
                        'removeBtnId' => 'category-remove',
                    ])
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="4"
                        class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all resize-none"
                        placeholder="Deskripsi singkat tentang kategori ini...">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1"><iconify-icon icon="solar:danger-circle-linear"></iconify-icon> {{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-3 p-4 bg-gray-50/80 rounded-xl border border-gray-100">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked
                        class="w-5 h-5 text-[#DF5E1D] accent-[#DF5E1D] rounded-lg">
                    <div>
                        <label for="is_active" class="text-sm font-medium text-gray-700 cursor-pointer">Active</label>
                        <p class="text-xs text-gray-400">Kategori aktif akan muncul di halaman toko</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between">
        <a href="{{ route('admin.categories.index') }}" class="text-sm text-gray-400 hover:text-[#363230] transition-colors flex items-center gap-1.5">
            <iconify-icon icon="solar:alt-arrow-left-linear"></iconify-icon>
            Kembali
        </a>
        <div class="flex items-center gap-3">
            <button type="submit" class="bg-[#DF5E1D] text-white px-8 py-2.5 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors shadow-sm shadow-[#DF5E1D]/20 flex items-center gap-2">
                <iconify-icon icon="solar:add-circle-linear"></iconify-icon>
                Create Category
            </button>
        </div>
    </div>
</form>
@endsection
