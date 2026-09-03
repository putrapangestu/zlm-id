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
    <form method="POST" action="{{ route('admin.laptops.update', $laptop) }}" enctype="multipart/form-data" class="xl:col-span-2 space-y-6">
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Brand / Merek</label>
                    <select name="brand_id" id="brand_id" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                        <option value="">-- Pilih Brand Laptop --</option>
                        @foreach ($brands as $b)
                            <option value="{{ $b->id }}" data-name="{{ $b->name }}" @selected(old('brand_id', $laptop->brand_id) === $b->id || old('brand', $laptop->brand) === $b->name)>{{ $b->name }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="brand" id="brand" value="{{ old('brand', $laptop->brand) }}">
                    @error('brand_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Normal (Rp)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $laptop->price) }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok Jual Lolos QC (Unit)</label>
                    <input type="number" name="stock" value="{{ old('stock', $laptop->stock) }}" required class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                    @error('stock') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Diskon per Barang --}}
            <div class="p-4 bg-orange-50/50 rounded-2xl border border-orange-200/60 space-y-4">
                <div class="flex items-center gap-2">
                    <iconify-icon icon="solar:sale-bold" class="text-[#DF5E1D] text-lg"></iconify-icon>
                    <span class="text-xs font-bold text-[#363230] uppercase tracking-wider">Pengaturan Diskon Khusus Barang Ini</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Jenis Diskon</label>
                        <select name="discount_type" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#DF5E1D]">
                            <option value="none" @selected(old('discount_type', $laptop->discount_type) === 'none')>Tanpa Diskon (Normal)</option>
                            <option value="percentage" @selected(old('discount_type', $laptop->discount_type) === 'percentage')>Persentase (%)</option>
                            <option value="fixed" @selected(old('discount_type', $laptop->discount_type) === 'fixed')>Potongan Tetap (Rp)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Nilai Diskon</label>
                        <input type="number" name="discount_value" value="{{ old('discount_value', $laptop->discount_value) }}" min="0" step="0.1" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold focus:outline-none focus:border-[#DF5E1D]" placeholder="Misal: 10 atau 500000">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Mulai Berlaku (Opsional)</label>
                        <input type="date" name="discount_start_date" value="{{ old('discount_start_date', $laptop->discount_start_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#DF5E1D]">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Berakhir Pada (Opsional)</label>
                        <input type="date" name="discount_end_date" value="{{ old('discount_end_date', $laptop->discount_end_date?->format('Y-m-d')) }}" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#DF5E1D]">
                    </div>
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

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Battery Life</label>
                <input type="text" name="battery_life" value="{{ old('battery_life', $laptop->battery_life) }}" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                @error('battery_life') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- I/O Ports & Additional Hardware Specs (Gambar 2 & 3) --}}
            <div class="pt-4 border-t border-gray-100 space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-bold text-gray-700 uppercase">
                            I/O Ports / Port Colokan 
                        </label>
                        <span class="text-[10px] text-gray-400">1 baris per jenis port colokan</span>
                    </div>
                    <textarea name="ports" rows="4" placeholder="Contoh:
1x USB 2.0 Type-A (data speed up to 480Mbps)
1x USB 3.2 Gen 1 Type-A (data speed up to 5Gbps)
1x USB-C 3.2 Gen 1 (support data transfer, Power Delivery and DisplayPort 1.2)
1x HDMI 1.4
1x 3.5mm Combo Audio Jack / headphone jack
1x Card reader
1x Power connector"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono focus:outline-none focus:border-[#DF5E1D]">{{ old('ports', $laptop->ports) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Webcam / Kamera</label>
                        <input type="text" name="camera" value="{{ old('camera', $laptop->camera) }}" placeholder="Contoh: 720p HD with Privacy Shutter"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Audio & Speaker</label>
                        <input type="text" name="audio" value="{{ old('audio', $laptop->audio) }}" placeholder="Contoh: Stereo speakers, 1.5W x2, Dolby Audio"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Konektivitas Nirkabel</label>
                        <input type="text" name="connectivity" value="{{ old('connectivity', $laptop->connectivity) }}" placeholder="Contoh: Wi-Fi 6 (11ax 2x2) + BT5.2"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Warna Casing</label>
                        <input type="text" name="color" value="{{ old('color', $laptop->color) }}" placeholder="Contoh: Arctic Grey / Thunder Black"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Info Garansi</label>
                        <input type="text" name="warranty" value="{{ old('warranty', $laptop->warranty ?? 'Garansi Toko 1 Bulan') }}" placeholder="Contoh: Garansi Toko 1 Bulan"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Images</label>
                @include('admin.laptops._multi_image_upload', [
                    'inputName' => 'images[]',
                    'dropzoneId' => 'laptop-dropzone',
                    'gridId' => 'laptop-preview-grid',
                    'existingImages' => $laptop->images,
                ])
                <p class="mt-2 text-xs text-gray-400">Centang gambar yang ingin dihapus. Gambar baru akan ditambahkan setelah gambar yang ada.</p>
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
                    <dt class="text-gray-400">Unit QC</dt>
                    <dd class="font-medium text-[#363230]">{{ $laptop->productItems->count() }} unit</dd>
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

@push('scripts')
<script>
document.getElementById('brand_id')?.addEventListener('change', function() {
    const sel = this.options[this.selectedIndex];
    document.getElementById('brand').value = sel ? sel.getAttribute('data-name') : '';
});
</script>
@endpush
@endsection
