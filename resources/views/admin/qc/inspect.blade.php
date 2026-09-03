@extends('layouts.admin')

@section('title', 'Inspeksi QC Unit — ZLM.ID Admin')
@section('heading', 'Formulir Inspeksi Quality Control')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-400">
        <a href="{{ route('admin.qc.index') }}" class="hover:text-[#DF5E1D] transition-colors">Quality Control</a>
        <iconify-icon icon="solar:alt-arrow-right-linear" style="stroke-width: 1.5;"></iconify-icon>
        <span class="text-[#363230] font-medium">Inspeksi Unit: {{ $item->laptop->name }}</span>
    </div>

    {{-- Product Summary Banner --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gray-50 border border-gray-200 overflow-hidden shrink-0 flex items-center justify-center">
                    @if($item->laptop->image_url_full)
                        <img src="{{ $item->laptop->image_url_full }}" alt="" class="w-full h-full object-cover">
                    @else
                        <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-gray-400 text-3xl"></iconify-icon>
                    @endif
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-lg font-bold text-[#363230]">{{ $item->laptop->name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                            {{ $item->variant?->name ?? 'Standard' }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">
                        Brand: <strong>{{ $item->laptop->brand }}</strong> &bull;
                        Harga Jual: <strong class="text-[#DF5E1D]">Rp {{ number_format($item->variant ? $item->variant->final_price : $item->laptop->final_price, 0, ',', '.') }}</strong>
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-2 text-xs bg-gray-50 p-3 rounded-xl border border-gray-100">
                <span class="px-2.5 py-1 bg-white rounded-lg border border-gray-200 text-gray-700"><strong>CPU:</strong> {{ $item->laptop->processor }}</span>
                <span class="px-2.5 py-1 bg-white rounded-lg border border-gray-200 text-gray-700"><strong>RAM:</strong> {{ $item->variant?->ram ?? $item->laptop->ram }}</span>
                <span class="px-2.5 py-1 bg-white rounded-lg border border-gray-200 text-gray-700"><strong>Storage:</strong> {{ $item->variant?->storage ?? $item->laptop->storage }}</span>
                <span class="px-2.5 py-1 bg-white rounded-lg border border-gray-200 text-gray-700"><strong>Display:</strong> {{ $item->laptop->display ?? '14 inch' }}</span>
            </div>
        </div>
    </div>

    {{-- Inspection Form Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Checklist Form (Left 2 cols) --}}
        <div class="lg:col-span-2 space-y-6">
            <form id="form-approve-qc" method="POST" action="{{ route('admin.qc.approve', $item) }}" class="space-y-6">
                @csrf

                <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 space-y-5">
                    <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2 pb-3 border-b border-gray-100">
                        <iconify-icon icon="solar:checklist-linear" class="text-[#DF5E1D] text-lg"></iconify-icon>
                        Checklist Fisik & Fungsional Unit
                    </h3>

                    {{-- 1. Layar --}}
                    <div class="p-4 rounded-xl bg-gray-50/80 border border-gray-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <span class="text-sm font-semibold text-[#363230] block">1. Layar & Display</span>
                            <span class="text-xs text-gray-500">Cek dead pixel, white spot, backlight bleed, kecerahan, & refresh rate.</span>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <label class="flex items-center gap-1.5 text-xs font-medium text-emerald-700 cursor-pointer">
                                <input type="radio" name="checklist[screen]" value="ok" checked class="text-emerald-600 focus:ring-emerald-500">
                                <span>Mulus (100%)</span>
                            </label>
                            <label class="flex items-center gap-1.5 text-xs font-medium text-amber-700 cursor-pointer">
                                <input type="radio" name="checklist[screen]" value="minor" class="text-amber-600 focus:ring-amber-500">
                                <span>Minor Defect</span>
                            </label>
                            <label class="flex items-center gap-1.5 text-xs font-medium text-rose-700 cursor-pointer">
                                <input type="radio" name="checklist[screen]" value="defect" class="text-rose-600 focus:ring-rose-500">
                                <span>Rusak/Gagal</span>
                            </label>
                        </div>
                    </div>

                    {{-- 2. Keyboard & Touchpad --}}
                    <div class="p-4 rounded-xl bg-gray-50/80 border border-gray-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <span class="text-sm font-semibold text-[#363230] block">2. Keyboard & Touchpad</span>
                            <span class="text-xs text-gray-500">Tes semua tuts tombol, backlight keyboard, click touchpad & gesture.</span>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <label class="flex items-center gap-1.5 text-xs font-medium text-emerald-700 cursor-pointer">
                                <input type="radio" name="checklist[keyboard]" value="ok" checked class="text-emerald-600 focus:ring-emerald-500">
                                <span>Normal Semua</span>
                            </label>
                            <label class="flex items-center gap-1.5 text-xs font-medium text-rose-700 cursor-pointer">
                                <input type="radio" name="checklist[keyboard]" value="defect" class="text-rose-600 focus:ring-rose-500">
                                <span>Tuts Mati</span>
                            </label>
                        </div>
                    </div>

                    {{-- 3. Baterai & Pengisian Daya --}}
                    <div class="p-4 rounded-xl bg-gray-50/80 border border-gray-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <span class="text-sm font-semibold text-[#363230] block">3. Baterai & Charger</span>
                            <span class="text-xs text-gray-500">Battery health > 80%, daya simpan tahan lama, pengisian adapter normal.</span>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <label class="flex items-center gap-1.5 text-xs font-medium text-emerald-700 cursor-pointer">
                                <input type="radio" name="checklist[battery]" value="ok" checked class="text-emerald-600 focus:ring-emerald-500">
                                <span>Bagus (>80%)</span>
                            </label>
                            <label class="flex items-center gap-1.5 text-xs font-medium text-amber-700 cursor-pointer">
                                <input type="radio" name="checklist[battery]" value="minor" class="text-amber-600 focus:ring-amber-500">
                                <span>Drop Sedang</span>
                            </label>
                            <label class="flex items-center gap-1.5 text-xs font-medium text-rose-700 cursor-pointer">
                                <input type="radio" name="checklist[battery]" value="defect" class="text-rose-600 focus:ring-rose-500">
                                <span>Bocor / Mati</span>
                            </label>
                        </div>
                    </div>

                    {{-- 4. Fisik & Casing --}}
                    <div class="p-4 rounded-xl bg-gray-50/80 border border-gray-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <span class="text-sm font-semibold text-[#363230] block">4. Kemulusan Fisik & Engsel</span>
                            <span class="text-xs text-gray-500">Engsel kokoh, casing mulus tanpa retak/penyok, baut lengkap.</span>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <label class="flex items-center gap-1.5 text-xs font-medium text-emerald-700 cursor-pointer">
                                <input type="radio" name="checklist[body]" value="ok" checked class="text-emerald-600 focus:ring-emerald-500">
                                <span>Mulus (Grade A)</span>
                            </label>
                            <label class="flex items-center gap-1.5 text-xs font-medium text-amber-700 cursor-pointer">
                                <input type="radio" name="checklist[body]" value="minor" class="text-amber-600 focus:ring-amber-500">
                                <span>Lecet Wajar (B)</span>
                            </label>
                            <label class="flex items-center gap-1.5 text-xs font-medium text-rose-700 cursor-pointer">
                                <input type="radio" name="checklist[body]" value="defect" class="text-rose-600 focus:ring-rose-500">
                                <span>Pecah/Penyok</span>
                            </label>
                        </div>
                    </div>

                    {{-- 5. Port I/O & Konektivitas --}}
                    <div class="p-4 rounded-xl bg-gray-50/80 border border-gray-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <span class="text-sm font-semibold text-[#363230] block">5. Port USB, HDMI, WiFi, Bluetooth</span>
                            <span class="text-xs text-gray-500">Semua port mendeteksi perangkat, sinyal WiFi & Bluetooth stabil.</span>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <label class="flex items-center gap-1.5 text-xs font-medium text-emerald-700 cursor-pointer">
                                <input type="radio" name="checklist[ports]" value="ok" checked class="text-emerald-600 focus:ring-emerald-500">
                                <span>Normal Semua</span>
                            </label>
                            <label class="flex items-center gap-1.5 text-xs font-medium text-rose-700 cursor-pointer">
                                <input type="radio" name="checklist[ports]" value="defect" class="text-rose-600 focus:ring-rose-500">
                                <span>Port Rusak</span>
                            </label>
                        </div>
                    </div>

                    {{-- 6. Kamera, Speaker & Mic --}}
                    <div class="p-4 rounded-xl bg-gray-50/80 border border-gray-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <span class="text-sm font-semibold text-[#363230] block">6. Webcam, Speaker & Microphone</span>
                            <span class="text-xs text-gray-500">Webcam menyala jernih, speaker suara stereo tanpa kresek.</span>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <label class="flex items-center gap-1.5 text-xs font-medium text-emerald-700 cursor-pointer">
                                <input type="radio" name="checklist[webcam]" value="ok" checked class="text-emerald-600 focus:ring-emerald-500">
                                <span>Normal Jernih</span>
                            </label>
                            <label class="flex items-center gap-1.5 text-xs font-medium text-rose-700 cursor-pointer">
                                <input type="radio" name="checklist[webcam]" value="defect" class="text-rose-600 focus:ring-rose-500">
                                <span>Rusak/Mati</span>
                            </label>
                        </div>
                    </div>

                    {{-- 7. Kesesuaian Spesifikasi --}}
                    <div class="p-4 rounded-xl bg-gray-50/80 border border-gray-200/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <span class="text-sm font-semibold text-[#363230] block">7. Kesesuaian Spesifikasi Hardware</span>
                            <span class="text-xs text-gray-500">Processor, kapasitas RAM, tipe SSD & GPU sesuai data katalog.</span>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <label class="flex items-center gap-1.5 text-xs font-medium text-emerald-700 cursor-pointer">
                                <input type="radio" name="checklist[specs]" value="match" checked class="text-emerald-600 focus:ring-emerald-500">
                                <span>Cocok Sesuai</span>
                            </label>
                            <label class="flex items-center gap-1.5 text-xs font-medium text-rose-700 cursor-pointer">
                                <input type="radio" name="checklist[specs]" value="mismatch" class="text-rose-600 focus:ring-rose-500">
                                <span>Tidak Cocok</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- SKU & Serial Number Input Box --}}
                <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 space-y-4">
                    <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2 pb-3 border-b border-gray-100">
                        <iconify-icon icon="solar:barcode-linear" class="text-[#DF5E1D] text-lg"></iconify-icon>
                        Penetapan SKU & Serial Unit (Syarat Lolos QC)
                    </h3>

                    <div class="p-3 bg-amber-50/60 border border-amber-200/60 rounded-xl text-xs text-amber-800">
                        <strong>Aturan Bisnis:</strong> Barang hanya lolos QC dan bertambah ke stok jual jika diberikan <strong>SKU resmi</strong> unik.
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="sku" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">SKU Unit QC (Wajib)</label>
                            <div class="flex gap-2">
                                <input type="text" id="sku" name="sku" value="{{ old('sku', $item->sku ?? '') }}" required
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono font-semibold text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10"
                                    placeholder="SKU-ZLM-XXXXX">
                                <button type="button" onclick="generateSku()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-medium text-gray-700 whitespace-nowrap" title="Generate SKU Otomatis">
                                    Generate
                                </button>
                            </div>
                            @error('sku')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="serial_number" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Serial Number Fisik (Opsional)</label>
                            <input type="text" id="serial_number" name="serial_number" value="{{ old('serial_number', $item->serial_number ?? '') }}"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10"
                                placeholder="SN dari stiker pabrik">
                        </div>
                    </div>

                    <div>
                        <label for="notes" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Catatan Hasil QC / Grade Fisik</label>
                        <textarea id="notes" name="notes" rows="3"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10"
                            placeholder="Contoh: Unit Grade A mulus 98%, battery health 92%, include charger original.">{{ old('notes', $item->qc_notes) }}</textarea>
                    </div>
                </div>

                {{-- Action Submit --}}
                <div class="flex items-center justify-between pt-2">
                    <a href="{{ route('admin.qc.index') }}" class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors">
                        Kembali ke Daftar QC
                    </a>
                    <button type="submit" class="px-7 py-3 rounded-xl text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                        <iconify-icon icon="solar:check-circle-bold" class="text-lg"></iconify-icon>
                        <span>Loloskan QC & Masukkan ke Stok Jual</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Reject / Defect Panel (Right 1 col) --}}
        <div class="space-y-6">
            <div class="bg-white rounded-2xl border border-rose-200/80 shadow-sm p-6">
                <div class="flex items-center gap-2 text-rose-600 font-bold text-sm pb-3 border-b border-rose-100">
                    <iconify-icon icon="solar:danger-triangle-bold" class="text-xl"></iconify-icon>
                    <span>Gagalkan QC (Unit Defect)</span>
                </div>

                <p class="text-xs text-gray-500 mt-3 leading-relaxed">
                    Jika unit memiliki kerusakan fisik berat atau fungsional yang tidak dapat ditoleransi, tandai unit ini sebagai <strong>Gagal QC</strong> untuk dikarantina.
                </p>

                <form method="POST" action="{{ route('admin.qc.reject', $item) }}" class="mt-4 space-y-4" onsubmit="return confirm('Apakah Anda yakin ingin menolak/menggagalkan unit ini?')">
                    @csrf
                    <input type="hidden" name="checklist[status]" value="failed">

                    <div>
                        <label for="defect_reason" class="block text-xs font-bold text-[#363230] uppercase mb-1">Alasan Kerusakan / Cacat</label>
                        <textarea id="defect_reason" name="defect_reason" rows="4" required
                            class="w-full bg-rose-50/50 border border-rose-200 rounded-xl p-3 text-xs text-[#363230] focus:outline-none focus:border-rose-400"
                            placeholder="Jelaskan detail kerusakan fisik/mesin (misal: Layar garis vertikal, port charger longgar)..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors flex items-center justify-center gap-2">
                        <iconify-icon icon="solar:close-circle-linear" class="text-base"></iconify-icon>
                        <span>Tolak Unit (Karantina / Retur Supplier)</span>
                    </button>
                </form>
            </div>

            {{-- Info Box --}}
            <div class="bg-gray-50 rounded-2xl border border-gray-200/60 p-5 text-xs text-gray-600 space-y-2">
                <div class="font-bold text-[#363230] flex items-center gap-1.5">
                    <iconify-icon icon="solar:info-circle-linear" class="text-[#DF5E1D] text-base"></iconify-icon>
                    <span>Prinsip Quality Control ZLM</span>
                </div>
                <p>
                    1. <strong>Barang Lolos:</strong> Diberi SKU resmi, stok jual toko online & kasir bertambah 1 unit.
                </p>
                <p>
                    2. <strong>Barang Gagal:</strong> Tidak mendapat SKU, tidak masuk ke stok jual, unit masuk ke log mutasi stok QC Failed.
                </p>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
function generateSku() {
    const brand = '{{ strtoupper(substr($item->laptop->brand, 0, 3)) }}';
    const dateStr = '{{ date("ymd") }}';
    const randomStr = Math.random().toString(36).substring(2, 6).toUpperCase();
    const sku = `SKU-${brand}-${dateStr}-${randomStr}`;
    document.getElementById('sku').value = sku;
}

// Auto generate if empty
document.addEventListener('DOMContentLoaded', function() {
    const skuInput = document.getElementById('sku');
    if (!skuInput.value) {
        generateSku();
    }
});
</script>
@endpush
@endsection
