@extends('layouts.admin')

@section('title', $laptop->name)
@section('heading', $laptop->name)

@section('content')
<div class="space-y-6">

    {{-- Back & Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.laptops.index') }}" class="text-sm text-gray-500 hover:text-[#363230] transition-colors flex items-center gap-1.5 font-medium">
            <iconify-icon icon="solar:alt-arrow-left-linear"></iconify-icon>
            Kembali ke Daftar Laptop
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('landing.detail', $laptop) }}" target="_blank" class="bg-white border border-gray-200 text-gray-700 px-3.5 py-2 rounded-xl text-xs font-bold hover:bg-gray-50 hover:text-[#DF5E1D] transition-colors flex items-center gap-1.5 shadow-2xs">
                <iconify-icon icon="solar:eye-linear" class="text-base"></iconify-icon>
                Lihat di Web
            </a>
            <a href="{{ route('admin.laptops.edit', $laptop) }}" class="bg-white border border-gray-200 text-gray-700 px-3.5 py-2 rounded-xl text-xs font-bold hover:bg-gray-50 hover:text-blue-600 hover:border-blue-200 transition-colors flex items-center gap-1.5 shadow-2xs">
                <iconify-icon icon="solar:pen-linear" class="text-base"></iconify-icon>
                Edit Laptop
            </a>
            <form method="POST" action="{{ route('admin.laptops.destroy', $laptop) }}" onsubmit="return confirm('Hapus produk laptop ini secara permanen?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-white border border-gray-200 text-gray-700 px-3.5 py-2 rounded-xl text-xs font-bold hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-colors flex items-center gap-1.5 shadow-2xs">
                    <iconify-icon icon="solar:trash-bin-trash-linear" class="text-base"></iconify-icon>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    {{-- Image + Info Card --}}
    <div class="bg-white rounded-3xl border border-gray-200/70 shadow-sm overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-0">
            {{-- Image Gallery --}}
            <div class="lg:col-span-4 bg-gradient-to-b from-gray-50 to-white p-6 flex flex-col items-center justify-center border-b lg:border-b-0 lg:border-r border-gray-200/60">
                <div class="w-full aspect-square max-w-xs flex items-center justify-center rounded-2xl bg-white border border-gray-100 p-4 shadow-2xs mb-4">
                    @if ($laptop->image_url)
                        <img src="{{ $laptop->image_url_full }}" alt="{{ $laptop->name }}" class="w-full h-full object-contain mix-blend-multiply">
                    @else
                        <img src="https://placehold.co/400x300/363230/DF5E1D?text=ZLM" alt="{{ $laptop->name }}" class="w-full h-full object-contain">
                    @endif
                </div>

                @if($laptop->images->count() > 0)
                    <div class="grid grid-cols-4 gap-2 w-full max-w-xs">
                        @foreach($laptop->images as $img)
                            <div class="aspect-square bg-white rounded-xl border border-gray-200 p-1 overflow-hidden flex items-center justify-center">
                                <img src="{{ Storage::url($img->image_url) }}" alt="" class="w-full h-full object-contain mix-blend-multiply">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Main Info --}}
            <div class="lg:col-span-8 p-6 lg:p-8 space-y-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[11px] font-bold text-[#DF5E1D] tracking-widest uppercase bg-[#DF5E1D]/10 px-3 py-1 rounded-full border border-[#DF5E1D]/20">
                                {{ $laptop->brand }}
                            </span>
                            @if ($laptop->is_active)
                                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1 rounded-full">
                                    Aktif Dijual
                                </span>
                            @else
                                <span class="text-[11px] font-bold text-gray-500 bg-gray-100 border border-gray-200 px-3 py-1 rounded-full">
                                    Nonaktif / Draft
                                </span>
                            @endif
                        </div>
                        <h1 class="text-2xl font-bold tracking-tight text-[#363230]">{{ $laptop->name }}</h1>
                    </div>
                    <div class="flex-shrink-0">
                        @if ($laptop->stock > 0)
                            <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 px-3.5 py-1.5 rounded-xl text-xs font-bold border border-emerald-200/80 shadow-2xs">
                                <iconify-icon icon="solar:check-circle-bold" class="text-base text-emerald-600"></iconify-icon>
                                Tersedia ({{ $laptop->stock }} Unit)
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-rose-50 text-rose-700 px-3.5 py-1.5 rounded-xl text-xs font-bold border border-rose-200/80 shadow-2xs">
                                <iconify-icon icon="solar:close-circle-bold" class="text-base text-rose-600"></iconify-icon>
                                Stok Habis / Terjual
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Price & Quick Stats --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 bg-gray-50/80 rounded-2xl border border-gray-100">
                    <div>
                        <span class="text-gray-400 text-[10px] uppercase tracking-wider font-bold block">Harga Dasar</span>
                        <p class="text-lg font-bold font-mono text-[#363230] mt-0.5">Rp {{ number_format($laptop->price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-[10px] uppercase tracking-wider font-bold block">Harga Final</span>
                        <p class="text-lg font-extrabold font-mono text-[#DF5E1D] mt-0.5">Rp {{ number_format($laptop->final_price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-[10px] uppercase tracking-wider font-bold block">Diskon</span>
                        <p class="text-xs font-bold mt-1.5">
                            @if ($laptop->has_discount)
                                <span class="text-rose-600 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-md">
                                    {{ $laptop->discount_type === 'percentage' ? (int)$laptop->discount_value . '%' : 'Rp ' . number_format($laptop->discount_value, 0, ',', '.') }}
                                </span>
                            @else
                                <span class="text-gray-400">Tidak ada</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-[10px] uppercase tracking-wider font-bold block">Featured</span>
                        <p class="text-xs font-bold mt-1.5">
                            @if ($laptop->is_featured)
                                <span class="text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">Ya (Unggulan)</span>
                            @else
                                <span class="text-gray-400">Tidak</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-gray-400 font-bold uppercase tracking-wider block mb-1">Kategori:</span>
                        <div class="flex gap-1.5 flex-wrap">
                            @forelse ($laptop->categories as $cat)
                                <span class="bg-gray-100 text-gray-700 font-semibold px-2.5 py-1 rounded-lg border border-gray-200/60">{{ $cat->name }}</span>
                            @empty
                                <span class="text-gray-400">Belum ada kategori</span>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-400 font-bold uppercase tracking-wider block mb-1">Garansi Unit:</span>
                        <p class="font-semibold text-gray-700">{{ $laptop->warranty ?: 'Garansi Toko Resmi ZLM.ID 1 Bulan' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Technical Specifications Card (Gambar 2 & 3) --}}
    <div class="bg-white rounded-3xl border border-gray-200/70 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-[#363230] flex items-center gap-2">
                <iconify-icon icon="solar:settings-minimalistic-bold" class="text-[#DF5E1D] text-lg"></iconify-icon>
                Spesifikasi Teknis Lengkap & Hardware
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Prosesor (CPU)</span>
                    <p class="text-xs font-bold text-gray-800">{{ $laptop->processor }}</p>
                </div>
                <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Memori (RAM)</span>
                    <p class="text-xs font-bold text-gray-800">{{ $laptop->ram }}</p>
                </div>
                <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Penyimpanan (Storage)</span>
                    <p class="text-xs font-bold text-gray-800">{{ $laptop->storage }}</p>
                </div>
                <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Kartu Grafis (GPU)</span>
                    <p class="text-xs font-bold text-gray-800">{{ $laptop->graphics ?: 'Integrated Graphics' }}</p>
                </div>
                <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Layar & Resolusi</span>
                    <p class="text-xs font-bold text-gray-800">{{ $laptop->display ?: '—' }}</p>
                </div>
                <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Baterai</span>
                    <p class="text-xs font-bold text-gray-800">{{ $laptop->battery_life ?: '—' }}</p>
                </div>
                <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Webcam / Kamera</span>
                    <p class="text-xs font-bold text-gray-800">{{ $laptop->camera ?: '—' }}</p>
                </div>
                <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Audio & Speaker</span>
                    <p class="text-xs font-bold text-gray-800">{{ $laptop->audio ?: '—' }}</p>
                </div>
                <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Konektivitas Nirkabel</span>
                    <p class="text-xs font-bold text-gray-800">{{ $laptop->connectivity ?: '—' }}</p>
                </div>
                <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Warna Casing</span>
                    <p class="text-xs font-bold text-gray-800">{{ $laptop->color ?: '—' }}</p>
                </div>
                <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Berat Fisik</span>
                    <p class="text-xs font-bold text-gray-800">{{ $laptop->weight ? $laptop->weight . ' kg' : '—' }}</p>
                </div>
                <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-100">
                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block mb-1">Info Garansi</span>
                    <p class="text-xs font-bold text-gray-800">{{ $laptop->warranty ?: 'Garansi Toko 1 Bulan' }}</p>
                </div>
            </div>

            {{-- Dedicated Full I/O Ports Listing --}}
            @if(count($laptop->ports_list) > 0 || !empty($laptop->ports))
                <div class="p-4 bg-orange-50/40 rounded-2xl border border-orange-100">
                    <div class="flex items-center gap-2 mb-2.5">
                        <iconify-icon icon="solar:usb-bold" class="text-[#DF5E1D] text-base"></iconify-icon>
                        <span class="text-xs font-extrabold text-[#363230] uppercase tracking-wider">I/O Ports & Port Colokan Lengkap:</span>
                    </div>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs font-mono font-semibold text-gray-700">
                        @foreach($laptop->ports_list as $port)
                            <li class="flex items-center gap-2 bg-white px-3 py-2 rounded-xl border border-gray-200/70 shadow-2xs">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#DF5E1D] shrink-0"></span>
                                <span>{{ $port }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    {{-- Kelebihan & Kekurangan --}}
    @if ($laptop->kelebihan || $laptop->kekurangan)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if ($laptop->kelebihan)
            <div class="bg-white rounded-3xl border border-emerald-100 shadow-sm p-6 bg-emerald-50/20">
                <h3 class="text-sm font-bold text-emerald-900 mb-3 flex items-center gap-2">
                    <iconify-icon icon="solar:check-circle-bold" class="text-emerald-600 text-lg"></iconify-icon>
                    Poin Kelebihan Unit
                </h3>
                <div class="prose prose-sm max-w-none text-emerald-950 text-xs leading-relaxed">
                    {!! $laptop->kelebihan !!}
                </div>
            </div>
            @endif
            @if ($laptop->kekurangan)
            <div class="bg-white rounded-3xl border border-rose-100 shadow-sm p-6 bg-rose-50/20">
                <h3 class="text-sm font-bold text-rose-900 mb-3 flex items-center gap-2">
                    <iconify-icon icon="solar:close-circle-bold" class="text-rose-600 text-lg"></iconify-icon>
                    Catatan Fisik / Kekurangan
                </h3>
                <div class="prose prose-sm max-w-none text-rose-950 text-xs leading-relaxed">
                    {!! $laptop->kekurangan !!}
                </div>
            </div>
            @endif
        </div>
    @endif

    {{-- Unit Fisik & Hasil Pengecekan QC (Daftar SKU) --}}
    <div class="bg-white rounded-3xl border border-gray-200/70 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-[#363230] flex items-center gap-2">
                    <iconify-icon icon="solar:shield-check-bold" class="text-[#DF5E1D] text-lg"></iconify-icon>
                    Daftar Unit Fisik & Hasil Pengecekan QC
                </h3>
                <p class="text-xs text-gray-400 mt-0.5">Semua unit fisik dan SKU yang terbit untuk produk ini</p>
            </div>
            <a href="{{ route('admin.qc.index') }}" class="px-3.5 py-2 bg-orange-50 hover:bg-[#DF5E1D] text-[#DF5E1D] hover:text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                <iconify-icon icon="solar:shield-check-bold" class="text-base"></iconify-icon>
                <span>Buka Modul QC</span>
            </a>
        </div>

        @if ($laptop->productItems->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-100">
                            <th class="py-3 px-6 font-bold text-gray-500 uppercase tracking-wider">SKU Unit</th>
                            <th class="py-3 px-6 font-bold text-gray-500 uppercase tracking-wider">Serial Number (SN)</th>
                            <th class="py-3 px-6 font-bold text-gray-500 uppercase tracking-wider">Grade Fisik</th>
                            <th class="py-3 px-6 font-bold text-gray-500 uppercase tracking-wider">Status QC</th>
                            <th class="py-3 px-6 font-bold text-gray-500 uppercase tracking-wider">Status Penjualan</th>
                            <th class="py-3 px-6 font-bold text-gray-500 uppercase tracking-wider">Pemeriksa (QC)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($laptop->productItems as $item)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-3.5 px-6 font-mono font-bold text-purple-700">
                                    {{ $item->sku ?: 'Belum Terbit SKU' }}
                                </td>
                                <td class="py-3.5 px-6 font-mono text-gray-600">
                                    {{ $item->serial_number ?: '-' }}
                                </td>
                                <td class="py-3.5 px-6">
                                    <span class="font-bold text-gray-700 bg-gray-100 px-2 py-0.5 rounded-md">
                                        Grade {{ $item->physical_grade ?: 'A' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-6">
                                    @if ($item->qc_status === 'passed')
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">
                                            <iconify-icon icon="solar:check-circle-bold"></iconify-icon>
                                            Lolos QC
                                        </span>
                                    @elseif ($item->qc_status === 'failed')
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-md">
                                            <iconify-icon icon="solar:close-circle-bold"></iconify-icon>
                                            Gagal QC
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md">
                                            <iconify-icon icon="solar:clock-circle-bold"></iconify-icon>
                                            Menunggu QC
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-6">
                                    @if ($item->is_sold)
                                        <span class="text-gray-400 font-bold bg-gray-100 px-2 py-0.5 rounded-md">Terjual</span>
                                    @else
                                        <span class="text-emerald-600 font-bold bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">Tersedia</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-6 text-gray-500">
                                    {{ $item->inspector?->name ?? 'Sistem / Admin' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-8 text-center text-gray-400">
                <iconify-icon icon="solar:box-minimalistic-linear" class="text-3xl text-gray-300 mb-2"></iconify-icon>
                <p class="text-xs">Belum ada unit fisik / QC yang tercatat untuk laptop ini.</p>
            </div>
        @endif
    </div>

    {{-- Description --}}
    <div class="bg-white rounded-3xl border border-gray-200/70 shadow-sm p-6">
        <h3 class="text-sm font-bold text-[#363230] mb-3 flex items-center gap-2">
            <iconify-icon icon="solar:notes-linear" class="text-[#DF5E1D] text-lg"></iconify-icon>
            Deskripsi Produk
        </h3>
        <div class="prose prose-sm max-w-none text-gray-700 text-xs leading-relaxed">
            {!! $laptop->description !!}
        </div>
    </div>

</div>
@endsection
