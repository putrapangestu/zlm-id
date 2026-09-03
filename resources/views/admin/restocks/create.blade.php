@extends('layouts.admin')

@section('title', 'Tambah Batch Restock')
@section('heading', 'Tambah Batch Restock Barang')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-sm font-bold text-[#363230]">Formulir Restock Barang Masuk</h2>
            <p class="text-xs text-gray-500">Catat pembelian unit laptop baru atau tambah stok dari distributor/supplier.</p>
        </div>
        <a href="{{ route('admin.restocks.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-900 px-3 py-2">
            &larr; Kembali ke Daftar Restock
        </a>
    </div>

    {{-- Banner Informasi Alur QC --}}
    <div class="p-4 bg-amber-50/80 border border-amber-200/80 rounded-2xl flex items-start gap-3">
        <iconify-icon icon="solar:info-circle-bold" class="text-amber-600 text-xl shrink-0 mt-0.5"></iconify-icon>
        <div class="text-xs text-amber-900 space-y-0.5">
            <p class="font-bold">Alur Ingesti Inventori & Quality Control (QC):</p>
            <p class="text-amber-800 leading-relaxed">
                Unit barang yang di-restock akan langsung tercatat di inventori sistem sebagai <strong>Pending QC (Stok Belum Diinspeksi)</strong> dan status produk akan <strong>Nonaktif / QC Off</strong>. SKU unit dan aktivasi produk ke katalog toko akan <strong>terbit otomatis setelah unit diinspeksi & Lolos QC</strong>.
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.restocks.store') }}" class="space-y-6">
        @csrf

        {{-- 1. Informasi Supplier & Tanggal Pembelian --}}
        <div class="bg-white rounded-3xl border border-gray-200/70 p-6 shadow-xs space-y-4">
            <h3 class="text-xs font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2">
                <iconify-icon icon="solar:shop-2-bold" class="text-[#DF5E1D] text-lg"></iconify-icon>
                <span>1. Data Supplier & Tanggal Restock</span>
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Supplier / Distributor <span class="text-red-500">*</span></label>
                    <input type="text" name="supplier_name" value="{{ old('supplier_name') }}" required placeholder="Contoh: PT. Distributor Laptop Jaya"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                    @error('supplier_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Tanggal Pembelian / Restock <span class="text-red-500">*</span></label>
                    <input type="date" name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" required
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-bold focus:outline-none focus:border-[#DF5E1D]">
                    @error('purchase_date') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">No. Invoice / Faktur Supplier</label>
                    <input type="text" name="invoice_number" value="{{ old('invoice_number') }}" placeholder="Contoh: INV-SUPP-8891"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">No. Telp / WhatsApp Supplier</label>
                    <input type="text" name="supplier_phone" value="{{ old('supplier_phone') }}" placeholder="0812-xxxx-xxxx"
                        class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                </div>
            </div>
        </div>

        {{-- 2. Pilihan Mode Restock (Produk Baru vs Produk Lama) --}}
        <div class="bg-white rounded-3xl border border-gray-200/70 p-6 shadow-xs space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-gray-100 pb-4">
                <div>
                    <h3 class="text-xs font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2">
                        <iconify-icon icon="solar:box-minimalistic-bold" class="text-[#DF5E1D] text-lg"></iconify-icon>
                        <span>2. Barang yang Di-Restock</span>
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">Pilih apakah restock untuk model laptop baru atau menambah unit pada laptop lama</p>
                </div>

                {{-- Mode Switch Tabs --}}
                <div class="inline-flex p-1 bg-gray-100 rounded-2xl gap-1 shrink-0">
                    <button type="button" onclick="switchRestockMode('new_product')" id="tab-btn-new"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition bg-[#DF5E1D] text-white shadow-xs">
                        + Input Produk Baru
                    </button>
                    <button type="button" onclick="switchRestockMode('existing_product')" id="tab-btn-existing"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition text-gray-600 hover:text-gray-900">
                        Pilih Produk yang Ada
                    </button>
                </div>
            </div>

            <input type="hidden" name="entry_mode" id="entry_mode_input" value="new_product">

            {{-- MODE A: INPUT PRODUK BARU DARI RESTOCK --}}
            <div id="section-new-product" class="space-y-5">
                <div class="flex items-center justify-between p-3.5 bg-orange-50/60 rounded-2xl border border-orange-200/70">
                    <span class="text-xs text-gray-700 font-medium">Ingin menghemat waktu pengisian spesifikasi?</span>
                    <button type="button" onclick="openAutofillModal()" class="px-3.5 py-1.5 bg-[#DF5E1D] text-white rounded-xl text-xs font-bold hover:bg-[#c45218] transition flex items-center gap-1.5 shadow-xs">
                        <iconify-icon icon="solar:copy-bold"></iconify-icon>
                        <span>Cari & Salin dari Laptop yang Ada</span>
                    </button>
                </div>

                {{-- Laptop Identity --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Model Laptop <span class="text-red-500">*</span></label>
                        <input type="text" name="new_laptop[name]" id="new_laptop_name" value="{{ old('new_laptop.name') }}" placeholder="Contoh: ThinkPad T14s Gen 3 AMD"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Brand / Merek <span class="text-red-500">*</span></label>
                        <input type="text" name="new_laptop[brand]" id="new_laptop_brand" value="{{ old('new_laptop.brand') }}" placeholder="Contoh: Lenovo, Dell, Asus"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Rencana Harga Jual (Rp)</label>
                        <input type="number" step="1000" name="new_laptop[price]" id="new_laptop_price" value="{{ old('new_laptop.price') }}" placeholder="Otomatis / Manual"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-mono font-bold focus:outline-none focus:border-[#DF5E1D]">
                    </div>
                </div>

                {{-- Specs --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Processor <span class="text-red-500">*</span></label>
                        <input type="text" name="new_laptop[processor]" id="new_laptop_processor" value="{{ old('new_laptop.processor') }}" placeholder="AMD Ryzen 7 PRO 6850U"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">RAM / Memori <span class="text-red-500">*</span></label>
                        <input type="text" name="new_laptop[ram]" id="new_laptop_ram" value="{{ old('new_laptop.ram') }}" placeholder="16GB LPDDR5"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Storage / SSD <span class="text-red-500">*</span></label>
                        <input type="text" name="new_laptop[storage]" id="new_laptop_storage" value="{{ old('new_laptop.storage') }}" placeholder="512GB NVMe SSD"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kartu Grafis (GPU)</label>
                        <input type="text" name="new_laptop[graphics]" id="new_laptop_graphics" value="{{ old('new_laptop.graphics') }}" placeholder="AMD Radeon 680M"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Layar / Display</label>
                        <input type="text" name="new_laptop[display]" id="new_laptop_display" value="{{ old('new_laptop.display') }}" placeholder="14' WUXGA IPS"
                            class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Kategori</label>
                        <select name="new_laptop[categories][]" id="new_laptop_category" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                            <option value="">Pilih Kategori Utama...</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Restock Quantity & Purchase Price --}}
                <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Jumlah Unit Masuk (Qty) <span class="text-red-500">*</span></label>
                        <input type="number" name="new_quantity" id="new_quantity" value="{{ old('new_quantity', 1) }}" min="1" oninput="calculateNewTotal()"
                            class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-mono font-bold focus:outline-none focus:border-[#DF5E1D]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">HPP / Harga Beli per Unit (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" step="1000" name="new_purchase_price" id="new_purchase_price" value="{{ old('new_purchase_price', 0) }}" oninput="calculateNewTotal()"
                            class="w-full px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-mono font-bold focus:outline-none focus:border-[#DF5E1D]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Total Nilai Pembelian</label>
                        <div class="px-3.5 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-mono font-extrabold text-[#DF5E1D]" id="new_total_display">
                            Rp 0
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODE B: PILIH DARI PRODUK LAMA YANG SUDAH ADA --}}
            <div id="section-existing-product" class="space-y-4 hidden">
                <div id="existing-items-rows" class="space-y-3">
                    <div class="p-4 bg-gray-50 rounded-2xl border border-gray-200 grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                        <div class="sm:col-span-5">
                            <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Pilih Laptop</label>
                            <select name="items[0][laptop_id]" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                                <option value="">-- Pilih Model Laptop --</option>
                                @foreach ($laptops as $laptop)
                                    <option value="{{ $laptop->id }}">{{ $laptop->name }} ({{ $laptop->brand }} - Stok: {{ $laptop->stock }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Qty</label>
                            <input type="number" name="items[0][quantity]" value="1" min="1" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-mono font-bold focus:outline-none focus:border-[#DF5E1D]">
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">HPP / Beli (Rp)</label>
                            <input type="number" step="1000" name="items[0][purchase_price]" value="0" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs font-mono font-bold focus:outline-none focus:border-[#DF5E1D]">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Catatan</label>
                            <input type="text" name="items[0][notes]" placeholder="Batch 1" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#DF5E1D]">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Catatan Tambahan & Submit --}}
        <div class="bg-white rounded-3xl border border-gray-200/70 p-6 shadow-xs space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Catatan Batch Restock (Opsional)</label>
                <textarea name="notes" rows="2" placeholder="Catatan no resi ekspedisi, kondisi paket saat tiba, atau perjanjian garansi supplier..."
                    class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-[#DF5E1D]"></textarea>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-[#DF5E1D] hover:bg-[#c45218] text-white px-8 py-3.5 rounded-xl text-xs font-extrabold transition shadow-md flex items-center gap-2">
                    <iconify-icon icon="solar:check-circle-bold" class="text-base"></iconify-icon>
                    <span>SIMPAN RESTOCK & GENERATE UNIT QC</span>
                </button>
                <a href="{{ route('admin.restocks.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-900 px-4 py-3">Batal</a>
            </div>
        </div>
    </form>
</div>

{{-- Auto-Fill Modal --}}
@include('admin.laptops._autofill_modal')

<script>
function switchRestockMode(mode) {
    document.getElementById('entry_mode_input').value = mode;
    const btnNew = document.getElementById('tab-btn-new');
    const btnExisting = document.getElementById('tab-btn-existing');
    const secNew = document.getElementById('section-new-product');
    const secExisting = document.getElementById('section-existing-product');

    if (mode === 'new_product') {
        btnNew.className = 'px-4 py-2 rounded-xl text-xs font-bold transition bg-[#DF5E1D] text-white shadow-xs';
        btnExisting.className = 'px-4 py-2 rounded-xl text-xs font-bold transition text-gray-600 hover:text-gray-900';
        secNew.classList.remove('hidden');
        secExisting.classList.add('hidden');
    } else {
        btnExisting.className = 'px-4 py-2 rounded-xl text-xs font-bold transition bg-[#DF5E1D] text-white shadow-xs';
        btnNew.className = 'px-4 py-2 rounded-xl text-xs font-bold transition text-gray-600 hover:text-gray-900';
        secExisting.classList.remove('hidden');
        secNew.classList.add('hidden');
    }
}

function calculateNewTotal() {
    const qty = parseInt(document.getElementById('new_quantity').value || 0);
    const price = parseFloat(document.getElementById('new_purchase_price').value || 0);
    const total = qty * price;
    document.getElementById('new_total_display').innerText = 'Rp ' + total.toLocaleString('id-ID');
}
</script>
@endsection
