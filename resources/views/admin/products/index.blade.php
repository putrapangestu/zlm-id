@extends('layouts.admin')

@section('title', 'Master Data Barang & Sparepart — ZLM.ID Admin')
@section('heading', 'Master Data Barang & Sparepart')

@section('content')
<div class="space-y-6">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Item Barang</span>
                <div class="w-8 h-8 rounded-xl bg-orange-50 flex items-center justify-center text-[#DF5E1D]">
                    <iconify-icon icon="solar:box-bold" class="text-lg"></iconify-icon>
                </div>
            </div>
            <p class="text-2xl font-bold text-[#363230] mt-2">{{ $stats['total_items'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Part, sparepart & aksesoris</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Total Stok Fisik</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <iconify-icon icon="solar:box-minimalistic-bold" class="text-lg"></iconify-icon>
                </div>
            </div>
            <p class="text-2xl font-bold text-emerald-600 mt-2">{{ number_format($stats['total_stock'], 0, ',', '.') }} <span class="text-xs font-normal text-gray-400">Unit</span></p>
            <p class="text-xs text-gray-500 mt-1">Tersedia di gudang/toko</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Total Valuasi HPP</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <iconify-icon icon="solar:wallet-bold" class="text-lg"></iconify-icon>
                </div>
            </div>
            <p class="text-2xl font-bold text-[#363230] mt-2">Rp {{ number_format($stats['total_asset'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Estimasi modal stok tersimpan</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Stok Menipis (≤3)</span>
                <div class="w-8 h-8 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600">
                    <iconify-icon icon="solar:danger-triangle-bold" class="text-lg"></iconify-icon>
                </div>
            </div>
            <p class="text-2xl font-bold text-rose-600 mt-2">{{ $stats['low_stock'] }} <span class="text-xs font-normal text-gray-400">Item</span></p>
            <p class="text-xs text-gray-500 mt-1">Perlu restock segera</p>
        </div>
    </div>

    {{-- Filter & Action Header --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <form method="GET" class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                <div class="relative flex-1 sm:w-64">
                    <iconify-icon icon="solar:magnifer-linear" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></iconify-icon>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, SKU, merk..."
                        class="w-full bg-gray-50 border border-gray-200 text-xs rounded-xl py-2 pl-9 pr-3 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">
                </div>
                <select name="category_id" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-xs rounded-xl py-2 px-3 focus:outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-xs rounded-xl py-2 px-3 focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="active" @selected(request('status') === 'active')>Aktif</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Non-aktif</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-medium transition-colors">
                    Filter
                </button>
            </form>

            @can('products.manage')
            <a href="{{ route('admin.products.create') }}" class="w-full sm:w-auto px-4 py-2.5 bg-[#DF5E1D] hover:bg-[#c45218] text-white rounded-xl text-xs font-bold shadow-sm transition-all flex items-center justify-center gap-1.5">
                <iconify-icon icon="solar:add-circle-bold" class="text-base"></iconify-icon>
                <span>Tambah Barang Baru</span>
            </a>
            @endcan
        </div>
    </div>

    {{-- Products Table --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">#</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Barang / Nama</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">SKU</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Kategori</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest text-right">Harga Modal (HPP)</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest text-right">Harga Jual</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest text-center">Stok</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-6 text-gray-400 text-xs">{{ $products->firstItem() + $loop->index }}</td>
                        <td class="py-4 px-6">
                            <span class="font-bold text-[#363230] block">{{ $product->name }}</span>
                            @if($product->description)
                                <span class="text-[11px] text-gray-400 line-clamp-1">{{ $product->description }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 font-mono text-xs font-semibold text-[#363230]">
                            <span class="bg-gray-100 px-2 py-0.5 rounded border border-gray-200">
                                {{ $product->sku }}
                            </span>
                        </td>
                        <td class="py-4 px-6 text-xs text-gray-600">
                            {{ $product->category->name ?? '-' }}
                        </td>
                        <td class="py-4 px-6 text-right text-xs font-semibold text-gray-700">
                            Rp {{ number_format($product->cost_price, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-6 text-right text-xs font-bold text-[#DF5E1D]">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($product->stock <= 0)
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-600 border border-rose-200">
                                    Habis (0)
                                </span>
                            @elseif($product->stock <= 3)
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-600 border border-amber-200">
                                    {{ $product->stock }} (Menipis)
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ $product->stock }} Unit
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                @can('products.manage')
                                <a href="{{ route('admin.products.edit', $product) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors" title="Edit Barang">
                                    <iconify-icon icon="solar:pen-linear" class="text-base"></iconify-icon>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus barang {{ $product->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-500 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors" title="Hapus">
                                        <iconify-icon icon="solar:trash-bin-trash-linear" class="text-base"></iconify-icon>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-gray-400">
                            <iconify-icon icon="solar:box-linear" class="text-4xl mb-2"></iconify-icon>
                            <p class="text-sm">Belum ada data barang atau sparepart yang dicatat.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $products->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
