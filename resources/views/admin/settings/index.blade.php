@extends('layouts.admin')

@section('title', 'Pengaturan Sistem — ZLM.ID Admin')
@section('heading', 'Pengaturan Sistem')

@section('content')
<div x-data="{ tab: '{{ $tab }}' }" class="max-w-4xl">

    {{-- Tab Navigation --}}
    <div class="flex flex-wrap gap-1.5 mb-8 bg-gray-100/80 p-1.5 rounded-2xl w-fit">
        <button @click="tab = 'general'" :class="{ 'bg-white shadow-sm text-[#DF5E1D] font-bold': tab === 'general', 'text-gray-500 hover:text-gray-700 font-medium': tab !== 'general' }" class="px-4 py-2 rounded-xl text-xs transition-all flex items-center gap-1.5">
            <iconify-icon icon="solar:settings-linear"></iconify-icon>
            <span>Umum & Pajak</span>
        </button>
        <button @click="tab = 'whatsapp'" :class="{ 'bg-white shadow-sm text-[#25D366] font-bold': tab === 'whatsapp', 'text-gray-500 hover:text-gray-700 font-medium': tab !== 'whatsapp' }" class="px-4 py-2 rounded-xl text-xs transition-all flex items-center gap-1.5">
            <iconify-icon icon="solar:chat-round-dots-linear"></iconify-icon>
            <span>WhatsApp Notifikasi</span>
        </button>
        <button @click="tab = 'dotmatrix'" :class="{ 'bg-white shadow-sm text-blue-600 font-bold': tab === 'dotmatrix', 'text-gray-500 hover:text-gray-700 font-medium': tab !== 'dotmatrix' }" class="px-4 py-2 rounded-xl text-xs transition-all flex items-center gap-1.5">
            <iconify-icon icon="solar:printer-linear"></iconify-icon>
            <span>Printer Dot Matrix</span>
        </button>
        <button @click="tab = 'comparison'" :class="{ 'bg-white shadow-sm text-[#DF5E1D] font-bold': tab === 'comparison', 'text-gray-500 hover:text-gray-700 font-medium': tab !== 'comparison' }" class="px-4 py-2 rounded-xl text-xs transition-all flex items-center gap-1.5">
            <iconify-icon icon="solar:scale-linear"></iconify-icon>
            <span>Perbandingan Perangkat (Compare)</span>
        </button>
        <button @click="tab = 'location'" :class="{ 'bg-white shadow-sm text-[#363230] font-bold': tab === 'location', 'text-gray-500 hover:text-gray-700 font-medium': tab !== 'location' }" class="px-4 py-2 rounded-xl text-xs transition-all flex items-center gap-1.5">
            <iconify-icon icon="solar:map-point-linear"></iconify-icon>
            <span>Lokasi & Maps</span>
        </button>
        <button @click="tab = 'social'" :class="{ 'bg-white shadow-sm text-[#363230] font-bold': tab === 'social', 'text-gray-500 hover:text-gray-700 font-medium': tab !== 'social' }" class="px-4 py-2 rounded-xl text-xs transition-all flex items-center gap-1.5">
            <iconify-icon icon="solar:share-circle-linear"></iconify-icon>
            <span>Media Sosial</span>
        </button>
    </div>

    <!-- Tab: General -->
    <form x-show="tab === 'general'" x-cloak method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <input type="hidden" name="_tab" value="general">

        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 sm:p-8 space-y-5">
            <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider pb-3 border-b border-gray-100">Informasi Toko & Pajak</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="store_name" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Nama Toko <span class="text-red-500">*</span></label>
                    <input type="text" name="store_name" id="store_name" value="{{ config('settings.store_name', 'ZLM.ID') }}" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">
                </div>

                <div>
                    <label for="tax_rate" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Tarif Pajak PPN (%)</label>
                    <input type="number" name="tax_rate" id="tax_rate" value="{{ config('settings.tax_rate', '11') }}" step="0.1" min="0" max="100" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">
                </div>
            </div>

            <div>
                <label for="store_description" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Deskripsi / Tagline Toko</label>
                <textarea name="store_description" id="store_description" rows="3"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">{{ config('settings.store_description', '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="store_email" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Email Toko</label>
                    <input type="email" name="store_email" id="store_email" value="{{ config('settings.store_email', '') }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">
                </div>

                <div>
                    <label for="store_phone" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Nomor Telepon / CS</label>
                    <input type="text" name="store_phone" id="store_phone" value="{{ config('settings.store_phone', '') }}"
                        class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">
                </div>
            </div>

            <div>
                <label for="store_opening_hours" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Jam Operasional Toko</label>
                <input type="text" name="store_opening_hours" id="store_opening_hours" value="{{ config('settings.store_opening_hours', '') }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10"
                    placeholder="Senin - Minggu: 09:00 - 21:00 WIB">
            </div>

            <div>
                <label for="store_logo" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Logo Toko</label>
                @if (config('settings.store_logo'))
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . config('settings.store_logo')) }}" alt="Logo" class="h-12 object-contain rounded-lg border border-gray-200 p-1">
                    </div>
                @endif
                <input type="file" name="store_logo" id="store_logo" accept="image/*"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs text-[#363230] file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-gray-200">
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-[#DF5E1D] text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-[#c45218] transition shadow-sm">
                Simpan Pengaturan
            </button>
        </div>
    </form>

    <!-- Tab: WhatsApp Notifications (ON/OFF) -->
    <div x-show="tab === 'whatsapp'" x-cloak class="space-y-6">
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="_tab" value="whatsapp">

            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 sm:p-8 space-y-6">
                {{-- Master Switch --}}
                <div class="flex items-center justify-between p-4 bg-emerald-50/70 border border-emerald-200/80 rounded-2xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-xl">
                            <iconify-icon icon="solar:whatsapp-bold"></iconify-icon>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-gray-900">Aktifkan Notifikasi WhatsApp Otomatis</h3>
                            <p class="text-xs text-gray-500">Kirim pesan otomatis ke pelanggan & admin untuk setiap update transaksi.</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="wa_notification_enabled" value="1" class="sr-only peer" @checked(config('settings.wa_notification_enabled', '1') == '1')>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#25D366]"></div>
                    </label>
                </div>

                {{-- Provider & Token --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="wa_provider" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Provider Gateway WhatsApp</label>
                        <select name="wa_provider" id="wa_provider"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]">
                            <option value="fonnte" @selected(config('settings.wa_provider') === 'fonnte')>Fonnte (Rekomendasi Indonesia)</option>
                            <option value="wablas" @selected(config('settings.wa_provider') === 'wablas')>Wablas</option>
                            <option value="generic" @selected(config('settings.wa_provider') === 'generic')>Custom Webhook API</option>
                        </select>
                    </div>

                    <div>
                        <label for="wa_admin_phone" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Nomor WhatsApp Admin (Penerima Notifikasi)</label>
                        <input type="text" name="wa_admin_phone" id="wa_admin_phone" value="{{ config('settings.wa_admin_phone', '') }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]"
                            placeholder="081234567890">
                    </div>

                    <div class="md:col-span-2">
                        <label for="wa_api_token" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">API Token / Secret Key</label>
                        <input type="password" name="wa_api_token" id="wa_api_token" value="{{ config('settings.wa_api_token', '') }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-mono text-[#363230] focus:outline-none focus:border-[#DF5E1D]"
                            placeholder="Masukkan API token WhatsApp Gateway Anda...">
                    </div>
                </div>

                {{-- Notification Triggers Checkboxes --}}
                <div class="pt-4 border-t border-gray-100">
                    <h4 class="text-xs font-bold text-[#363230] uppercase tracking-wider mb-3">Event Notifikasi yang Dikirim</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-200/60 cursor-pointer">
                            <input type="checkbox" name="wa_notify_order_created" value="1" class="rounded text-[#DF5E1D] focus:ring-[#DF5E1D]/20" @checked(config('settings.wa_notify_order_created', '1') == '1')>
                            <span class="text-xs font-medium text-gray-700">Pesanan Baru Dibuat (Ke Pelanggan & Admin)</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-200/60 cursor-pointer">
                            <input type="checkbox" name="wa_notify_payment_success" value="1" class="rounded text-[#DF5E1D] focus:ring-[#DF5E1D]/20" @checked(config('settings.wa_notify_payment_success', '1') == '1')>
                            <span class="text-xs font-medium text-gray-700">Pembayaran Sukses / Dikonfirmasi</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-200/60 cursor-pointer">
                            <input type="checkbox" name="wa_notify_order_shipped" value="1" class="rounded text-[#DF5E1D] focus:ring-[#DF5E1D]/20" @checked(config('settings.wa_notify_order_shipped', '1') == '1')>
                            <span class="text-xs font-medium text-gray-700">Pesanan Dikirim (+ Nomor Resi)</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-200/60 cursor-pointer">
                            <input type="checkbox" name="wa_notify_restock" value="1" class="rounded text-[#DF5E1D] focus:ring-[#DF5E1D]/20" @checked(config('settings.wa_notify_restock', '1') == '1')>
                            <span class="text-xs font-medium text-gray-700">Restock Barang Masuk (Ke Admin)</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-200/60 cursor-pointer">
                            <input type="checkbox" name="wa_notify_return_status" value="1" class="rounded text-[#DF5E1D] focus:ring-[#DF5E1D]/20" @checked(config('settings.wa_notify_return_status', '1') == '1')>
                            <span class="text-xs font-medium text-gray-700">Perubahan Status Retur Barang</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="bg-[#DF5E1D] text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-[#c45218] transition shadow-sm">
                        Simpan Pengaturan WhatsApp
                    </button>
                </div>
            </div>
        </form>

        {{-- Test WhatsApp Send Box --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 sm:p-8 space-y-4">
            <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2 pb-2 border-b border-gray-100">
                <iconify-icon icon="solar:plain-bold" class="text-[#25D366] text-lg"></iconify-icon>
                Uji Coba Kirim WhatsApp (Test Send)
            </h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nomor WhatsApp Tujuan</label>
                    <input type="text" id="test-phone" placeholder="081234567890" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pesan Percobaan</label>
                    <input type="text" id="test-message" value="Halo! Ini pesan tes notifikasi dari sistem ZLM.ID Laptop Store." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs">
                </div>
            </div>

            <button type="button" onclick="sendTestWa()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-2">
                <iconify-icon icon="solar:plain-linear"></iconify-icon>
                <span>Kirim Pesan Percobaan</span>
            </button>
            <div id="test-wa-result" class="text-xs font-medium"></div>
        </div>
    </div>

    <!-- Tab: Dot Matrix Printer Settings -->
    <form x-show="tab === 'dotmatrix'" x-cloak method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="_tab" value="dotmatrix">

        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 sm:p-8 space-y-5">
            <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider pb-3 border-b border-gray-100">
                Konfigurasi Cetak Kertas Kontinu (Dot Matrix Continuous Form)
            </h3>

            <div>
                <label for="dotmatrix_header" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Header Cetakan / Kop Perusahaan</label>
                <input type="text" name="dotmatrix_header" id="dotmatrix_header" value="{{ config('settings.dotmatrix_header', 'ZLM.ID — PUSAT LAPTOP BERKUALITAS') }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]">
            </div>

            <div>
                <label for="dotmatrix_address" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Baris Alamat & Kontak</label>
                <input type="text" name="dotmatrix_address" id="dotmatrix_address" value="{{ config('settings.dotmatrix_address', 'Jl. Soekarno Hatta No. 45, Malang | Telp/WA: 0812-3456-7890') }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]">
            </div>

            <div>
                <label for="dotmatrix_footer" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Catatan Footer / Syarat Barang</label>
                <input type="text" name="dotmatrix_footer" id="dotmatrix_footer" value="{{ config('settings.dotmatrix_footer', 'Barang yang sudah diterima tercatat resmi di sistem ZLM.ID dan wajib lolos Quality Control sebelum dijual.') }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]">
            </div>

            <div>
                <label for="dotmatrix_paper_width" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Ukuran Kertas Continuous Form</label>
                <select name="dotmatrix_paper_width" id="dotmatrix_paper_width" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm">
                    <option value="9.5x11" @selected(config('settings.dotmatrix_paper_width') === '9.5x11')>Continuous Form 9.5" x 11" (Standar LX-310 / LQ-310)</option>
                    <option value="9.5x5.5" @selected(config('settings.dotmatrix_paper_width') === '9.5x5.5')>Continuous Form Wartel/Bagi Dua 9.5" x 5.5"</option>
                    <option value="A4" @selected(config('settings.dotmatrix_paper_width') === 'A4')>Kertas A4 Biasa (Monospace)</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-[#DF5E1D] text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-[#c45218] transition shadow-sm">
                Simpan Format Printer
            </button>
        </div>
    </form>

    <!-- Tab: Comparison Specs Settings -->
    @php
        $rawCompareFields = config('settings.compare_fields');
        $activeCompareFields = $rawCompareFields ? (is_array($rawCompareFields) ? $rawCompareFields : json_decode($rawCompareFields, true)) : null;
        $allCompareSpecs = [
            'price' => ['label' => 'Harga Produk (Rp)', 'desc' => 'Menampilkan harga beli normal / diskon'],
            'processor' => ['label' => 'Processor / CPU', 'desc' => 'Tipe dan seri processor laptop'],
            'ram' => ['label' => 'RAM / Memori', 'desc' => 'Kapasitas dan tipe RAM'],
            'storage' => ['label' => 'Storage / SSD', 'desc' => 'Kapasitas dan jenis penyimpanan'],
            'graphics' => ['label' => 'Kartu Grafis (GPU)', 'desc' => 'VGA / Kartu grafis terpasang'],
            'display' => ['label' => 'Layar / Display', 'desc' => 'Ukuran, resolusi, dan panel layar'],
            'ports' => ['label' => 'I/O Ports / Port Colokan', 'desc' => 'Kelengkapan port USB, Type-C, HDMI, dll.'],
            'camera' => ['label' => 'Webcam / Kamera', 'desc' => 'Resolusi kamera dan fitur privacy'],
            'audio' => ['label' => 'Audio & Speaker', 'desc' => 'Sistem speaker dan teknologi audio'],
            'connectivity' => ['label' => 'Konektivitas Nirkabel', 'desc' => 'Wi-Fi, Bluetooth, dan jaringan'],
            'color' => ['label' => 'Warna Casing', 'desc' => 'Warna fisik unit laptop'],
            'warranty' => ['label' => 'Informasi Garansi', 'desc' => 'Durasi dan cakupan garansi toko'],
            'weight' => ['label' => 'Bobot / Berat (kg)', 'desc' => 'Berat fisik perangkat laptop'],
            'battery_life' => ['label' => 'Kesehatan / Daya Baterai', 'desc' => 'Kapasitas dan estimasi daya tahan baterai'],
            'kelebihan' => ['label' => 'Poin Kelebihan', 'desc' => 'Daftar keunggulan unit'],
            'kekurangan' => ['label' => 'Poin Kekurangan / Catatan Fisik', 'desc' => 'Catatan kondisi fisik / minus unit'],
        ];
    @endphp
    <form x-show="tab === 'comparison'" x-cloak method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="_tab" value="comparison">

        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 sm:p-8 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-100">
                <div>
                    <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider">
                        Pengaturan Parameter Perbandingan Laptop (Compare Device)
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">Pilih parameter spesifikasi mana saja yang ingin ditampilkan ke pembeli di halaman perbandingan produk (/compare).</p>
                </div>

                <div class="flex items-center gap-2">
                    <button type="button" onclick="toggleAllCompareSpecs(true)" class="px-3 py-1.5 bg-orange-50 text-[#DF5E1D] hover:bg-orange-100 rounded-xl text-xs font-bold transition">
                        Pilih Semua (Default)
                    </button>
                    <button type="button" onclick="toggleAllCompareSpecs(false)" class="px-3 py-1.5 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-xs font-bold transition">
                        Hapus Pilihan
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" id="compare-specs-grid">
                @foreach($allCompareSpecs as $specKey => $specInfo)
                    @php
                        // Default is TRUE if settings not yet set
                        $isChecked = $activeCompareFields === null ? true : in_array($specKey, $activeCompareFields);
                    @endphp
                    <label class="flex items-start gap-3 p-3.5 bg-gray-50/80 hover:bg-orange-50/50 rounded-2xl border border-gray-200/70 hover:border-[#DF5E1D]/40 cursor-pointer transition">
                        <input type="checkbox" name="compare_fields[]" value="{{ $specKey }}" @checked($isChecked)
                            class="compare-spec-checkbox w-4 h-4 rounded text-[#DF5E1D] accent-[#DF5E1D] mt-0.5">
                        <div>
                            <span class="text-xs font-bold text-[#363230] block">{{ $specInfo['label'] }}</span>
                            <span class="text-[11px] text-gray-400">{{ $specInfo['desc'] }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-[#DF5E1D] text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-[#c45218] transition shadow-sm">
                Simpan Parameter Perbandingan
            </button>
        </div>
    </form>

    <!-- Tab: Location & Maps -->
    <form x-show="tab === 'location'" x-cloak method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="_tab" value="location">

        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 sm:p-8 space-y-5">
            <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider pb-3 border-b border-gray-100">Lokasi Fisik & Google Maps</h3>

            <div>
                <label for="store_address" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Alamat Lengkap Toko</label>
                <textarea name="store_address" id="store_address" rows="3"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]"
                    placeholder="Jl. Raya Malang No. 123...">{{ config('settings.store_address', '') }}</textarea>
            </div>

            <div>
                <label for="store_google_maps" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Google Maps Embed Code / Iframe HTML</label>
                <textarea name="store_google_maps" id="store_google_maps" rows="4"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-mono text-[#363230] focus:outline-none focus:border-[#DF5E1D]"
                    placeholder="<iframe src='https://www.google.com/maps/embed?...' ...></iframe>">{{ config('settings.store_google_maps', '') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Buka Google Maps > Bagikan > Sematkan Peta > Salin kode HTML iframe ke sini.</p>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-[#DF5E1D] text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-[#c45218] transition shadow-sm">
                Simpan Lokasi
            </button>
        </div>
    </form>

    <!-- Tab: Social Media -->
    <form x-show="tab === 'social'" x-cloak method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="_tab" value="social">

        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 sm:p-8 space-y-5">
            <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider pb-3 border-b border-gray-100">Media Sosial Resmi</h3>

            <div>
                <label for="store_whatsapp" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Nomor WhatsApp Toko</label>
                <input type="text" name="store_whatsapp" id="store_whatsapp" value="{{ config('settings.store_whatsapp', '') }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#DF5E1D]"
                    placeholder="6281234567890">
            </div>

            <div>
                <label for="social_instagram" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Instagram URL</label>
                <input type="url" name="social_instagram" id="social_instagram" value="{{ config('settings.social_instagram', '') }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#DF5E1D]"
                    placeholder="https://instagram.com/zlm.id">
            </div>

            <div>
                <label for="social_facebook" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">Facebook Page URL</label>
                <input type="url" name="social_facebook" id="social_facebook" value="{{ config('settings.social_facebook', '') }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#DF5E1D]">
            </div>

            <div>
                <label for="social_tiktok" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">TikTok URL</label>
                <input type="url" name="social_tiktok" id="social_tiktok" value="{{ config('settings.social_tiktok', '') }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#DF5E1D]">
            </div>

            <div>
                <label for="social_youtube" class="block text-xs font-bold text-[#363230] uppercase mb-1.5">YouTube Channel URL</label>
                <input type="url" name="social_youtube" id="social_youtube" value="{{ config('settings.social_youtube', '') }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#DF5E1D]">
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-[#DF5E1D] text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-[#c45218] transition shadow-sm">
                Simpan Media Sosial
            </button>
        </div>
    </form>

</div>

@push('scripts')
<script>
function toggleAllCompareSpecs(checkAll) {
    document.querySelectorAll('.compare-spec-checkbox').forEach(cb => {
        cb.checked = checkAll;
    });
}

function sendTestWa() {
    const phone = document.getElementById('test-phone').value;
    const message = document.getElementById('test-message').value;
    const resultDiv = document.getElementById('test-wa-result');

    if (!phone) {
        if (typeof window.showToast === 'function') {
            window.showToast('Masukkan nomor WhatsApp tujuan terlebih dahulu.', 'warning');
        }
        return;
    }

    resultDiv.innerHTML = '<span class="text-blue-500">Mengirim pesan percobaan...</span>';

    fetch('{{ route("admin.settings.test-wa") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ phone, message })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status) {
            resultDiv.innerHTML = '<span class="text-emerald-600 font-bold">✅ ' + (data.message || 'Pesan percobaan berhasil diproses.') + '</span>';
            if (typeof window.showToast === 'function') {
                window.showToast('Pesan WhatsApp percobaan berhasil terkirim!');
            }
        } else {
            resultDiv.innerHTML = '<span class="text-rose-600 font-bold">❌ Gagal: ' + (data.error || data.message) + '</span>';
        }
    })
    .catch(err => {
        resultDiv.innerHTML = '<span class="text-rose-600 font-bold">❌ Terjadi error: ' + err.message + '</span>';
    });
}
</script>
@endpush
@endsection
