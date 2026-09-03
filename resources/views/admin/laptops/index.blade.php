@extends('layouts.admin')

@section('title', 'Katalog & Manajemen Laptop')
@section('heading', 'Katalog & Manajemen Laptop')

@section('content')
<div class="space-y-5">
    {{-- Filter & Action Bar --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-gray-200/60 shadow-xs">
        {{-- Search & Filter Form --}}
        <form method="GET" action="{{ route('admin.laptops.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative w-64">
                <iconify-icon icon="solar:magnifer-linear" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></iconify-icon>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama, brand, SKU..."
                    class="w-full pl-9 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                @if ($search)
                    <a href="{{ route('admin.laptops.index') }}" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-700">
                        <iconify-icon icon="solar:close-circle-linear"></iconify-icon>
                    </a>
                @endif
            </div>

            {{-- Filter Status Publikasi --}}
            <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                <option value="all" @selected(($status ?? 'all') === 'all')>Semua Status</option>
                <option value="active" @selected(($status ?? '') === 'active')>Aktif (Live Toko)</option>
                <option value="inactive" @selected(($status ?? '') === 'inactive')>Nonaktif / Belum QC</option>
            </select>

            {{-- Filter Ketersediaan Stok --}}
            <select name="stock_status" onchange="this.form.submit()" class="px-3 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                <option value="all" @selected(($stockStatus ?? 'all') === 'all')>Semua Stok</option>
                <option value="in_stock" @selected(($stockStatus ?? '') === 'in_stock')>Tersedia</option>
                <option value="sold_out" @selected(($stockStatus ?? '') === 'sold_out')>Habis Terjual</option>
            </select>

            <button type="submit" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                Filter
            </button>
        </form>

        {{-- Add Buttons --}}
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('admin.restocks.create') }}" class="px-4 py-2 bg-orange-50 hover:bg-orange-100 text-[#DF5E1D] border border-orange-200 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                <iconify-icon icon="solar:box-minimalistic-bold" class="text-base"></iconify-icon>
                <span>Input via Restock</span>
            </a>
            <a href="{{ route('admin.laptops.create') }}" class="px-4 py-2 bg-[#DF5E1D] hover:bg-[#c45218] text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <iconify-icon icon="solar:add-circle-bold" class="text-base"></iconify-icon>
                <span>Tambah Laptop</span>
            </a>
        </div>
    </div>

    {{-- Laptops Table --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/70 border-b border-gray-100">
                        <th class="py-3.5 px-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Produk Laptop</th>
                        <th class="py-3.5 px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Brand & Kategori</th>
                        <th class="py-3.5 px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Harga Jual</th>
                        <th class="py-3.5 px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Stok Siap Jual</th>
                        <th class="py-3.5 px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pending QC</th>
                        <th class="py-3.5 px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status Toko</th>
                        <th class="py-3.5 px-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-xs">
                    @forelse ($laptops as $laptop)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            {{-- Product Name & Image --}}
                            <td class="py-3.5 px-5 font-medium">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-gray-100 border border-gray-200/70 overflow-hidden flex items-center justify-center shrink-0">
                                        @if ($laptop->image_url_full)
                                            <img src="{{ $laptop->image_url_full }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-gray-400 text-xl"></iconify-icon>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('admin.laptops.show', $laptop) }}" class="font-bold text-[#363230] hover:text-[#DF5E1D] transition-colors line-clamp-1 block">
                                            {{ $laptop->name }}
                                        </a>
                                        <span class="text-[11px] text-gray-400 block mt-0.5">
                                            {{ $laptop->processor }} &bull; {{ $laptop->ram }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Brand & Categories --}}
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-gray-700 block uppercase text-[11px]">{{ $laptop->brand }}</span>
                                <div class="flex gap-1 flex-wrap mt-1">
                                    @foreach ($laptop->categories as $cat)
                                        <span class="text-[9px] font-semibold bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded-md">{{ $cat->name }}</span>
                                    @endforeach
                                </div>
                            </td>

                            {{-- Price & Promo --}}
                            <td class="py-3.5 px-4 font-mono font-bold">
                                @if ($laptop->has_discount)
                                    <span class="text-[10px] text-gray-400 line-through block">Rp {{ number_format($laptop->price, 0, ',', '.') }}</span>
                                    <span class="text-[#DF5E1D]">Rp {{ number_format($laptop->final_price, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-gray-900">Rp {{ number_format($laptop->price, 0, ',', '.') }}</span>
                                @endif
                            </td>

                            {{-- Stock Ready --}}
                            <td class="py-3.5 px-4">
                                @if ($laptop->stock <= 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Habis Terjual
                                    </span>
                                @elseif ($laptop->stock <= 2)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Sisa {{ $laptop->stock }} Unit
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $laptop->stock }} Unit
                                    </span>
                                @endif
                            </td>

                            {{-- Pending QC --}}
                            <td class="py-3.5 px-4">
                                @if ($laptop->uninspected_stock > 0)
                                    <a href="{{ route('admin.qc.index') }}" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100 transition" title="Klik untuk cek QC">
                                        <iconify-icon icon="solar:checklist-minimalistic-bold"></iconify-icon>
                                        {{ $laptop->uninspected_stock }} Pending QC
                                    </a>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>

                            {{-- Active / Inactive Status --}}
                            <td class="py-3.5 px-4">
                                <form method="POST" action="{{ route('admin.laptops.toggle-status', $laptop) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold cursor-pointer transition {{ $laptop->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-gray-100 text-gray-500 border border-gray-200 hover:bg-gray-200' }}" title="Klik untuk mengubah status aktif">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $laptop->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                        {{ $laptop->is_active ? 'Aktif (Live)' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>

                            {{-- Actions --}}
                            <td class="py-3.5 px-5 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.laptops.show', $laptop) }}" class="p-1.5 text-gray-400 hover:text-[#DF5E1D] hover:bg-orange-50 rounded-lg transition" title="Lihat Detail">
                                        <iconify-icon icon="solar:eye-linear" class="text-base"></iconify-icon>
                                    </a>
                                    <a href="{{ route('admin.laptops.edit', $laptop) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <iconify-icon icon="solar:pen-linear" class="text-base"></iconify-icon>
                                    </a>
                                    <form method="POST" action="{{ route('admin.laptops.destroy', $laptop) }}" onsubmit="return confirm('Hapus produk laptop ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                            <iconify-icon icon="solar:trash-bin-trash-linear" class="text-base"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-400">
                                <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-4xl mb-2 text-gray-200"></iconify-icon>
                                <p class="text-xs">Tidak ada data laptop ditemukan dengan kriteria ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($laptops->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $laptops->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
