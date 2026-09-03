@extends('layouts.admin')

@section('title', 'Manajemen Retur Barang — ZLM.ID Admin')
@section('heading', 'Manajemen Retur Barang')

@section('content')
<div class="space-y-6">

    {{-- Stats & Quick Actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-gray-200/60 p-4 shadow-xs">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Retur Pelanggan</span>
            <p class="text-xl font-extrabold text-[#363230] mt-1">{{ $stats['total_customer'] }} <span class="text-xs font-normal text-gray-400">Kasus</span></p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200/60 p-4 shadow-xs">
            <span class="text-[11px] font-bold text-orange-500 uppercase tracking-wider">Total Retur Supplier</span>
            <p class="text-xl font-extrabold text-[#DF5E1D] mt-1">{{ $stats['total_supplier'] }} <span class="text-xs font-normal text-gray-400">Kasus</span></p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200/60 p-4 shadow-xs">
            <span class="text-[11px] font-bold text-amber-500 uppercase tracking-wider">Menunggu Persetujuan</span>
            <p class="text-xl font-extrabold text-amber-600 mt-1">{{ $stats['total_pending'] }} <span class="text-xs font-normal text-gray-400">Kasus</span></p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200/60 p-4 shadow-xs">
            <span class="text-[11px] font-bold text-emerald-500 uppercase tracking-wider">Retur Selesai</span>
            <p class="text-xl font-extrabold text-emerald-600 mt-1">{{ $stats['total_completed'] }} <span class="text-xs font-normal text-gray-400">Kasus</span></p>
        </div>
    </div>

    {{-- Filter & Action Bar --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-xs p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        {{-- Type Tabs --}}
        <div class="inline-flex p-1 bg-gray-100 rounded-2xl gap-1 shrink-0">
            <a href="{{ route('admin.returns.index', ['type' => 'all', 'status' => request('status')]) }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ ($type ?? 'all') === 'all' ? 'bg-[#DF5E1D] text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                Semua
            </a>
            <a href="{{ route('admin.returns.index', ['type' => 'customer', 'status' => request('status')]) }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ ($type ?? '') === 'customer' ? 'bg-[#DF5E1D] text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                Retur Pelanggan
            </a>
            <a href="{{ route('admin.returns.index', ['type' => 'supplier', 'status' => request('status')]) }}"
                class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition {{ ($type ?? '') === 'supplier' ? 'bg-[#DF5E1D] text-white shadow-xs' : 'text-gray-600 hover:text-gray-900' }}">
                Retur ke Supplier
            </a>
        </div>

        {{-- Search & Filters --}}
        <form method="GET" class="flex flex-wrap items-center gap-3 flex-1">
            <input type="hidden" name="type" value="{{ $type ?? 'all' }}">
            <div class="relative flex-1 min-w-[200px]">
                <iconify-icon icon="solar:magnifer-linear" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></iconify-icon>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Retur, Pelanggan, Supplier..."
                    class="w-full bg-gray-50 border border-gray-200 text-xs rounded-xl py-2 pl-9 pr-4 focus:outline-none focus:border-[#DF5E1D]">
            </div>
            <select name="status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-xs text-[#363230] rounded-xl py-2 px-3 focus:outline-none focus:border-[#DF5E1D]">
                <option value="">Semua Status</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="approved" @selected(request('status') === 'approved')>Disetujui</option>
                <option value="item_received" @selected(request('status') === 'item_received')>Unit Diterima</option>
                <option value="completed" @selected(request('status') === 'completed')>Selesai</option>
                <option value="rejected" @selected(request('status') === 'rejected')>Ditolak</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                Filter
            </button>
        </form>

        {{-- Create Supplier Return Button --}}
        <a href="{{ route('admin.returns.create-supplier') }}" class="px-4 py-2 bg-[#DF5E1D] hover:bg-[#c45218] text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs shrink-0">
            <iconify-icon icon="solar:add-circle-bold" class="text-base"></iconify-icon>
            <span>+ Retur ke Supplier</span>
        </a>
    </div>

    {{-- Returns Table --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50/70 border-b border-gray-100">
                        <th class="py-3.5 px-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">No. Retur & Jenis</th>
                        <th class="py-3.5 px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pihak Terkait</th>
                        <th class="py-3.5 px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Barang / Batch</th>
                        <th class="py-3.5 px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Alasan Retur</th>
                        <th class="py-3.5 px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="py-3.5 px-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($returns as $ret)
                    <tr class="hover:bg-gray-50/60 transition-colors">
                        <td class="py-3.5 px-5">
                            <span class="font-mono font-bold text-gray-900 block">{{ $ret->return_number }}</span>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                @if ($ret->return_type === 'supplier')
                                    <span class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-purple-50 text-purple-700 border border-purple-200">Retur Supplier</span>
                                @else
                                    <span class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-blue-50 text-blue-700 border border-blue-200">Retur Pelanggan</span>
                                @endif
                                <span class="text-[10px] text-gray-400">{{ $ret->created_at->format('d/m/y H:i') }}</span>
                            </div>
                        </td>

                        <td class="py-3.5 px-4">
                            @if ($ret->return_type === 'supplier')
                                <span class="font-bold text-gray-900 block">{{ $ret->supplier_name }}</span>
                                <span class="text-[11px] text-gray-400">{{ $ret->supplier_phone ?? 'Supplier Restock' }}</span>
                            @else
                                <span class="font-bold text-gray-900 block">{{ $ret->user?->name ?? 'Pelanggan' }}</span>
                                <span class="text-[11px] text-gray-400">Order: {{ $ret->order?->order_number ?? '-' }}</span>
                            @endif
                        </td>

                        <td class="py-3.5 px-4">
                            <span class="font-bold text-gray-800 block">
                                {{ $ret->orderItem?->laptop?->name ?? $ret->productItem?->laptop?->name ?? $ret->restock?->restock_number ?? 'Barang Restock' }}
                            </span>
                            @if ($ret->productItem?->sku)
                                <span class="text-[10px] font-mono text-purple-600 block">SKU: {{ $ret->productItem->sku }}</span>
                            @endif
                        </td>

                        <td class="py-3.5 px-4">
                            <span class="font-bold text-gray-700 capitalize">{{ str_replace('_', ' ', $ret->reason) }}</span>
                            <span class="text-[11px] text-gray-400 block truncate max-w-xs mt-0.5">{{ $ret->customer_notes }}</span>
                        </td>

                        <td class="py-3.5 px-4">
                            @php
                                $statusClass = match($ret->status) {
                                    'approved' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'item_received' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    default => 'bg-amber-50 text-amber-700 border-amber-200',
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $ret->status)) }}
                            </span>
                        </td>

                        <td class="py-3.5 px-5 text-right">
                            <a href="{{ route('admin.returns.show', $ret) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                                <iconify-icon icon="solar:eye-linear"></iconify-icon>
                                <span>Detail</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400">
                            <iconify-icon icon="solar:refresh-square-linear" class="text-4xl text-gray-200 mb-2"></iconify-icon>
                            <p class="text-xs">Tidak ada data permohonan retur barang.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($returns->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $returns->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
