@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<style>
    trix-editor { min-height: 180px; }
    trix-toolbar .trix-button-group { margin-bottom: 0; }
    .trix-button-row { flex-wrap: wrap; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endpush

@section('title', 'Tambah Laptop Baru')
@section('heading', 'Tambah Laptop Baru')

@section('content')
<div class="space-y-6">

    {{-- Top Action Bar with Auto-Fill Button --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-gray-200/60 shadow-xs">
        <div>
            <h2 class="text-sm font-bold text-[#363230]">Formulir Input Spesifikasi Laptop</h2>
            <p class="text-xs text-gray-500">Isi spesifikasi unit laptop atau salin cepat dari model/SKU yang sudah ada sebelumnya.</p>
        </div>

        <button type="button" onclick="openAutofillModal()" class="px-4 py-2.5 bg-orange-50 hover:bg-orange-100 text-[#DF5E1D] border border-orange-200/80 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs shrink-0">
            <iconify-icon icon="solar:copy-bold" class="text-base text-[#DF5E1D]"></iconify-icon>
            <span>Cari & Salin dari Laptop yang Sudah Ada</span>
        </button>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <form method="POST" action="{{ route('admin.laptops.store') }}" enctype="multipart/form-data" class="xl:col-span-2 space-y-6">
            @csrf

            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 space-y-5">
                
                {{-- Status & Visibility --}}
                <div class="flex items-center justify-between p-3.5 bg-gray-50 rounded-2xl border border-gray-200/80">
                    <div>
                        <span class="text-xs font-bold text-[#363230] block">Status Publikasi Produk</span>
                        <span class="text-[11px] text-gray-500">Jika aktif, produk dapat dicari dan dibeli di toko online & kasir POS.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true)) class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#DF5E1D]"></div>
                    </label>
                </div>

                {{-- Name & Brand --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Model Laptop <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: ThinkPad T14s Gen 3 AMD"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Brand / Merek <span class="text-red-500">*</span></label>
                        <input type="text" name="brand" value="{{ old('brand') }}" required placeholder="Contoh: Lenovo, Dell, Asus, Apple"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                        @error('brand') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Price & Initial Stock --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Harga Jual Normal (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" step="1000" name="price" value="{{ old('price') }}" required placeholder="12500000"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold focus:outline-none focus:border-[#DF5E1D]">
                        @error('price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Stok Jual Siap Pakai (Unit) <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold focus:outline-none focus:border-[#DF5E1D]">
                        @error('stock') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Diskon per Barang --}}
                <div class="p-4 bg-orange-50/50 rounded-2xl border border-orange-200/60 space-y-3">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="solar:sale-bold" class="text-[#DF5E1D] text-lg"></iconify-icon>
                        <span class="text-xs font-bold text-[#363230] uppercase tracking-wider">Pengaturan Promo Diskon</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Jenis Diskon</label>
                            <select name="discount_type" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                                <option value="none" @selected(old('discount_type') === 'none')>Tanpa Diskon</option>
                                <option value="percentage" @selected(old('discount_type') === 'percentage')>Persentase (%)</option>
                                <option value="fixed" @selected(old('discount_type') === 'fixed')>Nominal Tetap (Rp)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Nilai Diskon</label>
                            <input type="number" name="discount_value" value="{{ old('discount_value', 0) }}" min="0" step="0.1" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold focus:outline-none focus:border-[#DF5E1D]" placeholder="Misal: 10 atau 500000">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Mulai Tanggal</label>
                            <input type="date" name="discount_start_date" value="{{ old('discount_start_date') }}" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#DF5E1D]">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Berakhir Tanggal</label>
                            <input type="date" name="discount_end_date" value="{{ old('discount_end_date') }}" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#DF5E1D]">
                        </div>
                    </div>
                </div>

                {{-- Hardware Specifications --}}
                <div class="space-y-4 pt-2">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest">Spesifikasi Hardware & Fisik</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Processor <span class="text-red-500">*</span></label>
                            <input type="text" name="processor" value="{{ old('processor') }}" required placeholder="Contoh: AMD Ryzen 7 PRO 6850U"
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                            @error('processor') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">RAM / Memori <span class="text-red-500">*</span></label>
                            <input type="text" name="ram" value="{{ old('ram') }}" required placeholder="Contoh: 16GB LPDDR5 6400MHz"
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                            @error('ram') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Storage / SSD <span class="text-red-500">*</span></label>
                            <input type="text" name="storage" value="{{ old('storage') }}" required placeholder="Contoh: 512GB NVMe PCIe Gen4"
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                            @error('storage') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kartu Grafis (GPU)</label>
                            <input type="text" name="graphics" value="{{ old('graphics') }}" placeholder="Contoh: AMD Radeon 680M / RTX 3050"
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Layar / Display</label>
                            <input type="text" name="display" value="{{ old('display') }}" placeholder="Contoh: 14' IPS WUXGA 100% sRGB"
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Berat (kg)</label>
                            <input type="number" step="0.01" name="weight" value="{{ old('weight', 1.35) }}"
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kesehatan / Daya Baterai</label>
                            <input type="text" name="battery_life" value="{{ old('battery_life') }}" placeholder="Contoh: 57Wh (Hingga 8 Jam)"
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                        </div>
                    </div>

                    {{-- I/O Ports & Additional Hardware Specs (Gambar 2 & 3) --}}
                    <div class="pt-3 border-t border-gray-100 space-y-4">
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-xs font-bold text-gray-700 uppercase">
                                    I/O Ports / Port Colokan (Gambar 2 & 3)
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
                                class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono focus:outline-none focus:border-[#DF5E1D]">{{ old('ports') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Webcam / Kamera</label>
                                <input type="text" name="camera" value="{{ old('camera') }}" placeholder="Contoh: 720p HD with Privacy Shutter"
                                    class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Audio & Speaker</label>
                                <input type="text" name="audio" value="{{ old('audio') }}" placeholder="Contoh: Stereo speakers, 1.5W x2, Dolby Audio"
                                    class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Konektivitas Nirkabel</label>
                                <input type="text" name="connectivity" value="{{ old('connectivity') }}" placeholder="Contoh: Wi-Fi 6 (11ax 2x2) + BT5.2"
                                    class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Warna Casing</label>
                                <input type="text" name="color" value="{{ old('color') }}" placeholder="Contoh: Arctic Grey / Thunder Black"
                                    class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Info Garansi</label>
                                <input type="text" name="warranty" value="{{ old('warranty', 'Garansi Toko 1 Bulan') }}" placeholder="Contoh: Garansi Toko 1 Bulan"
                                    class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Description & Trix --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Deskripsi Lengkap Laptop <span class="text-red-500">*</span></label>
                    <input id="description" name="description" type="hidden" value="{{ old('description') }}">
                    <trix-editor input="description" class="trix-content bg-gray-50 rounded-2xl border border-gray-200 p-3"></trix-editor>
                    @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Poin Kelebihan</label>
                        <input id="kelebihan" name="kelebihan" type="hidden" value="{{ old('kelebihan') }}">
                        <trix-editor input="kelebihan" class="trix-content bg-gray-50 rounded-2xl border border-gray-200 p-3"></trix-editor>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Poin Kekurangan / Catatan Fisik</label>
                        <input id="kekurangan" name="kekurangan" type="hidden" value="{{ old('kekurangan') }}">
                        <trix-editor input="kekurangan" class="trix-content bg-gray-50 rounded-2xl border border-gray-200 p-3"></trix-editor>
                    </div>
                </div>

                {{-- Images --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Foto Produk Laptop</label>
                    @include('admin.laptops._multi_image_upload', [
                        'inputName' => 'images[]',
                        'dropzoneId' => 'laptop-dropzone',
                        'gridId' => 'laptop-preview-grid',
                    ])
                </div>

                {{-- Categories & Featured --}}
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Kategori Laptop</label>
                    <div class="flex flex-wrap gap-3">
                        @foreach ($categories as $cat)
                            <label class="flex items-center gap-2 p-2.5 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-orange-50 hover:border-[#DF5E1D]/50 transition">
                                <input type="checkbox" name="categories[]" value="{{ $cat->id }}" class="w-4 h-4 text-[#DF5E1D] accent-[#DF5E1D]">
                                <span class="text-xs font-semibold text-gray-700">{{ $cat->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center gap-2 pt-2">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1" @checked(old('is_featured')) class="w-4 h-4 text-[#DF5E1D] accent-[#DF5E1D]">
                    <label for="is_featured" class="text-xs font-bold text-gray-700">Tampilkan sebagai Produk Unggulan (Featured)</label>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="bg-[#DF5E1D] hover:bg-[#c45218] text-white px-8 py-3 rounded-xl text-xs font-extrabold transition shadow-md flex items-center gap-2">
                    <iconify-icon icon="solar:check-circle-bold" class="text-base"></iconify-icon>
                    <span>SIMPAN & PUBLIKASIKAN LAPTOP</span>
                </button>
                <a href="{{ route('admin.laptops.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-900 px-4 py-3">Batal</a>
            </div>
        </form>

        {{-- Right Side Info Cards --}}
        <div class="xl:col-span-1 space-y-5">
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 space-y-4">
                <h3 class="text-xs font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2">
                    <iconify-icon icon="solar:info-circle-bold" class="text-[#DF5E1D] text-lg"></iconify-icon>
                    Petunjuk Input Laptop
                </h3>
                <ul class="space-y-3 text-xs text-gray-500">
                    <li class="flex items-start gap-2">
                        <span class="w-5 h-5 rounded-full bg-orange-100 text-[#DF5E1D] flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">1</span>
                        <span>Gunakan tombol <strong>"Cari & Salin"</strong> di atas jika model laptop pernah di-input sebelumnya untuk mengisi otomatis semua field.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-5 h-5 rounded-full bg-orange-100 text-[#DF5E1D] flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">2</span>
                        <span>Jika laptop masuk dari pembelian supplier, disarankan membuat melalui menu <strong>Restock Barang</strong> agar tercatat HPP dan batch QC.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="w-5 h-5 rounded-full bg-orange-100 text-[#DF5E1D] flex items-center justify-center font-bold text-[10px] shrink-0 mt-0.5">3</span>
                        <span>Format foto yang didukung: JPG, PNG, WebP (maksimal 2MB per gambar).</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Auto-Fill Modal --}}
@include('admin.laptops._autofill_modal')

@endsection
