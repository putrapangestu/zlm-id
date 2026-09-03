@extends('layouts.admin')

@section('title', 'Detail Brand: ' . $brand->name)
@section('heading', 'Detail Brand & Statistik')

@section('content')
<div class="space-y-6">

    {{-- Back & Actions --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.brands.index') }}" class="text-xs font-bold text-gray-500 hover:text-gray-900 flex items-center gap-1">
            <iconify-icon icon="solar:alt-arrow-left-linear"></iconify-icon>
            <span>Kembali ke Master Brand</span>
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.brands.edit', $brand) }}" class="px-3.5 py-2 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                <iconify-icon icon="solar:pen-linear"></iconify-icon>
                <span>Edit Brand</span>
            </a>
            <a href="{{ route('admin.laptops.create') }}" class="px-3.5 py-2 bg-[#DF5E1D] hover:bg-[#c45218] text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-2xs">
                <iconify-icon icon="solar:add-circle-linear"></iconify-icon>
                <span>Tambah Laptop untuk Brand Ini</span>
            </a>
        </div>
    </div>

    {{-- Brand Header Card --}}
    <div class="bg-white rounded-3xl border border-gray-200/70 p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-gray-50 border border-gray-200/80 p-2 flex items-center justify-center shrink-0 overflow-hidden">
                @if($brand->logo_url_full)
                    <img src="{{ $brand->logo_url_full }}" alt="{{ $brand->name }}" class="w-full h-full object-contain">
                @else
                    <span class="font-extrabold text-[#DF5E1D] text-lg">{{ strtoupper(substr($brand->name, 0, 2)) }}</span>
                @endif
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold text-[#363230]">{{ $brand->name }}</h1>
                    @if($brand->is_active)
                        <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold rounded-full">Aktif</span>
                    @else
                        <span class="px-2.5 py-0.5 bg-gray-100 text-gray-500 border border-gray-200 text-[10px] font-bold rounded-full">Nonaktif</span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-1">{{ $brand->description ?: 'Produsen resmi perangkat laptop ' . $brand->name }}</p>
            </div>
        </div>
    </div>

    {{-- Statistics Summary --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-200/70 shadow-xs">
            <div class="flex items-center gap-2 mb-1">
                <iconify-icon icon="solar:laptop-minimalistic-bold" class="text-[#DF5E1D] text-base"></iconify-icon>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Model Terikat</span>
            </div>
            <p class="text-2xl font-extrabold text-[#363230]">{{ $stats['total_models'] }} <span class="text-xs font-normal text-gray-400">model</span></p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200/70 shadow-xs">
            <div class="flex items-center gap-2 mb-1">
                <iconify-icon icon="solar:box-bold" class="text-blue-600 text-base"></iconify-icon>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Stok Fisik Tersedia</span>
            </div>
            <p class="text-2xl font-extrabold text-blue-600">{{ $stats['total_stock'] }} <span class="text-xs font-normal text-gray-400">unit</span></p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200/70 shadow-xs">
            <div class="flex items-center gap-2 mb-1">
                <iconify-icon icon="solar:check-circle-bold" class="text-emerald-600 text-base"></iconify-icon>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Unit Terjual</span>
            </div>
            <p class="text-2xl font-extrabold text-emerald-600">{{ $stats['sold_units'] }} <span class="text-xs font-normal text-gray-400">unit</span></p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-200/70 shadow-xs">
            <div class="flex items-center gap-2 mb-1">
                <iconify-icon icon="solar:wallet-bold" class="text-[#DF5E1D] text-base"></iconify-icon>
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Omset Penjualan</span>
            </div>
            <p class="text-xl font-extrabold font-mono text-[#DF5E1D]">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Laptops List --}}
    <div class="bg-white rounded-3xl border border-gray-200/70 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-xs font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2">
                <iconify-icon icon="solar:list-bold" class="text-[#DF5E1D] text-base"></iconify-icon>
                <span>Daftar Laptop Brand {{ $brand->name }}</span>
            </h3>
            <span class="text-xs text-gray-400 font-semibold">{{ $laptops->total() }} Produk Terdaftar</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="py-3 px-6 font-bold text-gray-400 uppercase tracking-wider">Nama Laptop</th>
                        <th class="py-3 px-6 font-bold text-gray-400 uppercase tracking-wider">Spesifikasi Singkat</th>
                        <th class="py-3 px-6 font-bold text-gray-400 uppercase tracking-wider">Harga Jual</th>
                        <th class="py-3 px-6 font-bold text-gray-400 uppercase tracking-wider text-center">Stok</th>
                        <th class="py-3 px-6 font-bold text-gray-400 uppercase tracking-wider text-center">Status</th>
                        <th class="py-3 px-6 font-bold text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($laptops as $laptop)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3.5 px-6 font-bold text-gray-900">
                                <a href="{{ route('admin.laptops.show', $laptop) }}" class="hover:text-[#DF5E1D] transition-colors">
                                    {{ $laptop->name }}
                                </a>
                            </td>
                            <td class="py-3.5 px-6 text-gray-500">
                                {{ $laptop->processor }} &bull; {{ $laptop->ram }} &bull; {{ $laptop->storage }}
                            </td>
                            <td class="py-3.5 px-6 font-mono font-bold text-[#DF5E1D]">
                                Rp {{ number_format($laptop->final_price, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-6 text-center font-bold">
                                @if($laptop->stock > 0)
                                    <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">{{ $laptop->stock }} Unit</span>
                                @else
                                    <span class="text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md">Habis</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-center">
                                @if($laptop->is_active)
                                    <span class="text-emerald-600 font-semibold text-[11px]">Aktif</span>
                                @else
                                    <span class="text-gray-400 font-semibold text-[11px]">Nonaktif</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.laptops.show', $laptop) }}" class="p-1.5 text-gray-500 hover:text-[#DF5E1D] transition" title="Lihat Detail">
                                        <iconify-icon icon="solar:eye-linear" class="text-base"></iconify-icon>
                                    </a>
                                    <a href="{{ route('admin.laptops.edit', $laptop) }}" class="p-1.5 text-gray-500 hover:text-blue-600 transition" title="Edit">
                                        <iconify-icon icon="solar:pen-linear" class="text-base"></iconify-icon>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">
                                <p class="text-xs">Belum ada model laptop yang terdaftar di bawah brand ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($laptops->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $laptops->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
