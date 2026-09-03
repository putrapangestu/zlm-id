@extends('layouts.admin')

@section('title', 'Restock Barang & Pembelian — ZLM.ID Admin')
@section('heading', 'Restock Barang & Pembelian')

@section('content')
<div class="space-y-6">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm">
            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Batch Restock</span>
            <p class="text-2xl font-bold text-[#363230] mt-2">{{ $stats['total_batches'] }} <span class="text-xs font-normal text-gray-400">Batch</span></p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm">
            <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Total Nilai Pembelian (HPP)</span>
            <p class="text-2xl font-bold text-emerald-600 mt-2">Rp {{ number_format($stats['total_invested'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm">
            <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Total Unit Diterima</span>
            <p class="text-2xl font-bold text-[#363230] mt-2">{{ $stats['total_units'] }} <span class="text-xs font-normal text-gray-400">Unit</span></p>
        </div>
    </div>

    {{-- Actions & Filters --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <form method="GET" class="flex flex-1 items-center gap-3 w-full">
                <div class="relative flex-1">
                    <iconify-icon icon="solar:magnifer-linear" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></iconify-icon>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Restock, Supplier, No. Invoice..."
                        class="w-full bg-gray-50 border border-gray-200 text-xs rounded-xl py-2.5 pl-9 pr-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-medium transition-colors">
                    Filter
                </button>
            </form>

            @can('restock.create')
            <a href="{{ route('admin.restocks.create') }}" class="bg-[#DF5E1D] text-white px-5 py-2.5 rounded-xl text-xs font-semibold hover:bg-[#c45218] transition-colors shadow-sm flex items-center gap-2 whitespace-nowrap">
                <iconify-icon icon="solar:box-minimalistic-linear" class="text-base"></iconify-icon>
                <span>Input Restock Baru</span>
            </a>
            @endcan
        </div>
    </div>

    {{-- Restocks Table --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">#</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">No. Restock & Tanggal</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Supplier</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Item & Unit</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Total Biaya (HPP)</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Status QC</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($restocks as $restock)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-6 text-gray-400 text-xs">{{ $restocks->firstItem() + $loop->index }}</td>

                        {{-- Batch No & Date --}}
                        <td class="py-4 px-6">
                            <span class="font-mono font-bold text-xs text-[#363230] block">{{ $restock->restock_number }}</span>
                            <span class="text-xs text-gray-400">{{ $restock->purchase_date->format('d M Y') }}</span>
                        </td>

                        {{-- Supplier --}}
                        <td class="py-4 px-6">
                            <span class="font-medium text-[#363230] block">{{ $restock->supplier_name }}</span>
                            <span class="text-xs text-gray-400">Inv: {{ $restock->invoice_number ?? '-' }}</span>
                        </td>

                        {{-- Items Qty --}}
                        <td class="py-4 px-6">
                            <span class="font-semibold text-[#363230] block">{{ $restock->items->sum('quantity') }} Unit</span>
                            <span class="text-[11px] text-gray-400">{{ $restock->items->count() }} Jenis Produk</span>
                        </td>

                        {{-- Total Amount --}}
                        <td class="py-4 px-6">
                            <span class="font-bold text-emerald-600 font-mono text-xs">
                                Rp {{ number_format($restock->total_amount, 0, ',', '.') }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="py-4 px-6">
                            @if($restock->status === 'completed')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <iconify-icon icon="solar:check-circle-bold" class="text-emerald-500"></iconify-icon>
                                    Semua Lolos/Dicek
                                </span>
                            @elseif($restock->status === 'partially_checked')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                    <iconify-icon icon="solar:clock-circle-linear" class="text-blue-500"></iconify-icon>
                                    Sebagian QC
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    <iconify-icon icon="solar:clock-circle-bold" class="text-amber-500"></iconify-icon>
                                    Baru Diterima (Pending QC)
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.restocks.show', $restock) }}" class="p-2 text-gray-500 hover:text-[#DF5E1D] hover:bg-orange-50 rounded-lg transition-colors" title="Lihat Detail & QC">
                                    <iconify-icon icon="solar:eye-linear" class="text-lg"></iconify-icon>
                                </a>

                                @can('restock.print')
                                <a href="{{ route('admin.restocks.print', $restock) }}" target="_blank" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Cetak Kertas Dot Matrix">
                                    <iconify-icon icon="solar:printer-linear" class="text-lg"></iconify-icon>
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <iconify-icon icon="solar:box-minimalistic-linear" class="text-4xl text-gray-200"></iconify-icon>
                                <p class="text-sm">Belum ada riwayat restock barang.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($restocks->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $restocks->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
