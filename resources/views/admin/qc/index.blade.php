@extends('layouts.admin')

@section('title', 'Pengecekan Barang (QC) — ZLM.ID Admin')
@section('heading', 'Pengecekan Barang (Quality Control)')

@section('content')
<div class="space-y-6">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Pending QC</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                    <iconify-icon icon="solar:clock-circle-bold" class="text-lg"></iconify-icon>
                </div>
            </div>
            <p class="text-2xl font-bold text-[#363230] mt-2">{{ $stats['total_pending'] }} <span class="text-xs font-normal text-gray-400">Unit</span></p>
            <p class="text-xs text-gray-500 mt-1">Belum dapat dijual sebelum lolos</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Lolos QC (Stok Jual)</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <iconify-icon icon="solar:check-circle-bold" class="text-lg"></iconify-icon>
                </div>
            </div>
            <p class="text-2xl font-bold text-emerald-600 mt-2">{{ $stats['total_passed'] }} <span class="text-xs font-normal text-gray-400">Unit</span></p>
            <p class="text-xs text-gray-500 mt-1">Memiliki SKU resmi & siap jual</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Gagal QC (Defect)</span>
                <div class="w-8 h-8 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600">
                    <iconify-icon icon="solar:close-circle-bold" class="text-lg"></iconify-icon>
                </div>
            </div>
            <p class="text-2xl font-bold text-rose-600 mt-2">{{ $stats['total_failed'] }} <span class="text-xs font-normal text-gray-400">Unit</span></p>
            <p class="text-xs text-gray-500 mt-1">Karantina teknisi / retur supplier</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Kelulusan QC</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <iconify-icon icon="solar:chart-2-bold" class="text-lg"></iconify-icon>
                </div>
            </div>
            <p class="text-2xl font-bold text-[#363230] mt-2">{{ $stats['pass_rate'] }}%</p>
            <p class="text-xs text-gray-500 mt-1">Tingkat mutu barang restock</p>
        </div>
    </div>

    {{-- Tabs & Filters --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-4">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            {{-- Status Tabs --}}
            <div class="flex items-center gap-1.5 p-1 bg-gray-100/80 rounded-xl w-full md:w-auto overflow-x-auto">
                <a href="{{ route('admin.qc.index', ['status' => 'pending', 'search' => request('search')]) }}"
                    class="px-4 py-2 rounded-lg text-xs font-medium transition-all whitespace-nowrap {{ $status === 'pending' ? 'bg-white text-[#DF5E1D] shadow-sm font-semibold' : 'text-gray-600 hover:text-[#363230]' }}">
                    Pending QC ({{ $stats['total_pending'] }})
                </a>
                <a href="{{ route('admin.qc.index', ['status' => 'passed', 'search' => request('search')]) }}"
                    class="px-4 py-2 rounded-lg text-xs font-medium transition-all whitespace-nowrap {{ $status === 'passed' ? 'bg-white text-emerald-600 shadow-sm font-semibold' : 'text-gray-600 hover:text-[#363230]' }}">
                    Lolos QC ({{ $stats['total_passed'] }})
                </a>
                <a href="{{ route('admin.qc.index', ['status' => 'failed', 'search' => request('search')]) }}"
                    class="px-4 py-2 rounded-lg text-xs font-medium transition-all whitespace-nowrap {{ $status === 'failed' ? 'bg-white text-rose-600 shadow-sm font-semibold' : 'text-gray-600 hover:text-[#363230]' }}">
                    Gagal QC ({{ $stats['total_failed'] }})
                </a>
                <a href="{{ route('admin.qc.index', ['status' => 'all', 'search' => request('search')]) }}"
                    class="px-4 py-2 rounded-lg text-xs font-medium transition-all whitespace-nowrap {{ $status === 'all' ? 'bg-white text-[#363230] shadow-sm font-semibold' : 'text-gray-600 hover:text-[#363230]' }}">
                    Semua Data
                </a>
            </div>

            {{-- Search Bar --}}
            <form method="GET" class="flex items-center gap-2 w-full md:w-80">
                <input type="hidden" name="status" value="{{ $status }}">
                <div class="relative flex-1">
                    <iconify-icon icon="solar:magnifer-linear" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></iconify-icon>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari SKU, Nama Laptop..."
                        class="w-full bg-gray-50 border border-gray-200 text-xs rounded-xl py-2 pl-9 pr-3 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-medium transition-colors">
                    Cari
                </button>
            </form>
        </div>
    </div>

    {{-- Items Table --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">#</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Produk & Varian</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Restock / Batch</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">SKU & Serial</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Status QC</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Inspektor</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($items as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-6 text-gray-400 text-xs">{{ $items->firstItem() + $loop->index }}</td>

                        {{-- Laptop --}}
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gray-100 border border-gray-200 overflow-hidden shrink-0 flex items-center justify-center">
                                    @if($item->laptop->image_url_full)
                                        <img src="{{ $item->laptop->image_url_full }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-gray-400 text-xl"></iconify-icon>
                                    @endif
                                </div>
                                <div>
                                    <span class="font-medium text-[#363230] block">{{ $item->laptop->name }}</span>
                                    <span class="text-xs text-gray-400">{{ $item->variant?->name ?? 'Standard' }} &bull; {{ $item->laptop->brand }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Restock Info --}}
                        <td class="py-4 px-6">
                            @if($item->restock)
                                <a href="{{ route('admin.restocks.show', $item->restock) }}" class="text-xs font-semibold text-[#DF5E1D] hover:underline block">
                                    {{ $item->restock->restock_number }}
                                </a>
                                <span class="text-[11px] text-gray-400">{{ $item->restock->supplier_name }} &bull; {{ $item->restock->purchase_date->format('d/m/Y') }}</span>
                            @else
                                <span class="text-xs text-gray-400">Restock Manual</span>
                            @endif
                        </td>

                        {{-- SKU & Serial --}}
                        <td class="py-4 px-6">
                            @if($item->sku)
                                <span class="inline-block font-mono text-xs font-semibold text-[#363230] bg-gray-100 px-2 py-0.5 rounded border border-gray-200">
                                    {{ $item->sku }}
                                </span>
                            @else
                                <span class="text-xs text-amber-600 italic">Belum ada SKU</span>
                            @endif
                            @if($item->serial_number)
                                <span class="block text-[11px] text-gray-400 mt-0.5">SN: {{ $item->serial_number }}</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="py-4 px-6">
                            @if($item->qc_status === 'passed')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <iconify-icon icon="solar:check-circle-bold" class="text-emerald-500"></iconify-icon>
                                    Lolos QC (Stok Aktif)
                                </span>
                            @elseif($item->qc_status === 'failed')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                    <iconify-icon icon="solar:close-circle-bold" class="text-rose-500"></iconify-icon>
                                    Gagal QC (Defect)
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">
                                    <iconify-icon icon="solar:clock-circle-bold" class="text-amber-500"></iconify-icon>
                                    Pending QC
                                </span>
                            @endif
                        </td>

                        {{-- Inspector --}}
                        <td class="py-4 px-6">
                            @if($item->inspector)
                                <span class="text-xs text-[#363230] font-medium block">{{ $item->inspector->name }}</span>
                                <span class="text-[11px] text-gray-400">{{ $item->qc_at?->format('d M Y H:i') }}</span>
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="py-4 px-6 text-right">
                            @can('qc.inspect')
                                <a href="{{ route('admin.qc.inspect', $item) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#DF5E1D] text-white hover:bg-[#c45218] rounded-xl text-xs font-medium shadow-sm transition-colors">
                                    <iconify-icon icon="solar:checklist-minimalistic-linear"></iconify-icon>
                                    <span>{{ $item->qc_status === 'pending' ? 'Periksa QC' : 'Re-Inspeksi' }}</span>
                                </a>
                            @else
                                <span class="text-xs text-gray-400 italic">Hanya Lihat</span>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-16 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <iconify-icon icon="solar:box-minimalistic-linear" class="text-4xl text-gray-200"></iconify-icon>
                                <p class="text-sm">Tidak ada barang dalam kategori ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($items->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $items->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
