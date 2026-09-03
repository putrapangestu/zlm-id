@extends('layouts.admin')

@section('title', 'Edit Paket Add-On: ' . $addon->name)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.addons.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-[#DF5E1D] transition mb-2">
                <iconify-icon icon="solar:arrow-left-linear" class="text-base"></iconify-icon>
                Kembali ke Daftar Paket
            </a>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <iconify-icon icon="solar:pen-bold-duotone" class="text-[#DF5E1D] text-3xl"></iconify-icon>
                Edit Paket Add-On / Bundle
            </h1>
        </div>
    </div>

    <form action="{{ route('admin.addons.update', $addon) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm space-y-5">
            <h3 class="text-base font-bold text-gray-900 border-b border-gray-100 pb-3 flex items-center gap-2">
                <iconify-icon icon="solar:info-circle-bold-duotone" class="text-[#DF5E1D] text-xl"></iconify-icon>
                Informasi Paket & Harga
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Nama Paket Add-On / Bundle <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $addon->name) }}" placeholder="Contoh: PAKET HEMAT, +ANTIGORES, Non Bundle" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#DF5E1D] focus:ring-1 focus:ring-[#DF5E1D] @error('name') border-rose-500 @enderror" required>
                    @error('name')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Harga Tambahan (Rp) <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-sm font-bold text-gray-400">Rp</span>
                        <input type="number" name="price" value="{{ old('price', $addon->price) }}" min="0" step="1000" placeholder="0" class="w-full pl-12 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-mono font-bold focus:outline-none focus:border-[#DF5E1D] focus:ring-1 focus:ring-[#DF5E1D] @error('price') border-rose-500 @enderror" required>
                    </div>
                    <span class="text-[11px] text-gray-400 mt-1 block">Isi 0 jika paket standar (Non Bundle) tanpa biaya tambahan.</span>
                    @error('price')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Deskripsi / Kelengkapan Paket
                </label>
                <textarea name="description" rows="3" placeholder="Jelaskan isi dan bonus dari paket ini (contoh: Termasuk Mouse Wireless, Mousepad, dan Tas Laptop)..." class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#DF5E1D] focus:ring-1 focus:ring-[#DF5E1D]">{{ old('description', $addon->description) }}</textarea>
                @error('description')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Foto / Thumbnail Paket
                    </label>
                    @if($addon->image_url_full)
                        <div class="flex items-center gap-3 mb-2 p-2 bg-gray-50 rounded-xl border border-gray-200">
                            <img src="{{ $addon->image_url_full }}" alt="{{ $addon->name }}" class="w-12 h-12 rounded-lg object-cover">
                            <label class="text-xs text-rose-600 flex items-center gap-1 cursor-pointer">
                                <input type="checkbox" name="remove_image" value="1" class="rounded border-gray-300 text-rose-600"> Hapus foto
                            </label>
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-[#DF5E1D] hover:file:bg-orange-100">
                    <span class="text-[11px] text-gray-400 mt-1 block">Format: JPG, PNG, WEBP. Maks 2MB.</span>
                    @error('image')
                        <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                        Urutan Tampilan
                    </label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $addon->sort_order) }}" min="0" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#DF5E1D] focus:ring-1 focus:ring-[#DF5E1D]">
                    <span class="text-[11px] text-gray-400 mt-1 block">Angka lebih kecil akan tampil lebih awal di urutan pil bundle.</span>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row gap-6">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_recommended" value="1" {{ old('is_recommended', $addon->is_recommended) ? 'checked' : '' }} class="w-4 h-4 text-[#DF5E1D] rounded border-gray-300 focus:ring-[#DF5E1D]">
                    <div>
                        <span class="text-sm font-bold text-gray-900 flex items-center gap-1.5">
                            <iconify-icon icon="solar:like-bold" class="text-rose-500"></iconify-icon>
                            Tandai Sebagai Rekomendasi (Thumbs Up)
                        </span>
                        <p class="text-xs text-gray-500">Akan menampilkan badge jempol merah/merona pada opsi bundle di halaman produk.</p>
                    </div>
                </label>

                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $addon->is_active) ? 'checked' : '' }} class="w-4 h-4 text-[#DF5E1D] rounded border-gray-300 focus:ring-[#DF5E1D]">
                    <div>
                        <span class="text-sm font-bold text-gray-900">Status Aktif</span>
                        <p class="text-xs text-gray-500">Tampilkan paket ini ke pelanggan.</p>
                    </div>
                </label>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.addons.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-xl transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-[#DF5E1D] hover:bg-[#c44f15] text-white text-sm font-semibold rounded-xl shadow-sm transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
