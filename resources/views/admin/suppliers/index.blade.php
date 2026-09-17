@extends('layouts.admin')

@section('title', 'Master Data Supplier — ZLM.ID Admin')
@section('heading', 'Master Data Supplier')

@section('content')
<div class="space-y-6">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Supplier</span>
                <div class="w-8 h-8 rounded-xl bg-orange-50 flex items-center justify-center text-[#DF5E1D]">
                    <iconify-icon icon="solar:users-group-two-rounded-bold" class="text-lg"></iconify-icon>
                </div>
            </div>
            <p class="text-2xl font-bold text-[#363230] mt-2">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Mitra penyedia barang restock</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Supplier Aktif</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <iconify-icon icon="solar:check-circle-bold" class="text-lg"></iconify-icon>
                </div>
            </div>
            <p class="text-2xl font-bold text-emerald-600 mt-2">{{ $stats['active'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Siap dipilih saat restock barang</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Batch Restock Terhubung</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <iconify-icon icon="solar:box-bold" class="text-lg"></iconify-icon>
                </div>
            </div>
            <p class="text-2xl font-bold text-[#363230] mt-2">{{ $stats['total_batches'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Total riwayat pembelian dari supplier</p>
        </div>
    </div>

    {{-- Filter & Action Header --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            <form method="GET" class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                <div class="relative flex-1 sm:w-64">
                    <iconify-icon icon="solar:magnifer-linear" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></iconify-icon>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, telp, kontak..."
                        class="w-full bg-gray-50 border border-gray-200 text-xs rounded-xl py-2 pl-9 pr-3 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10">
                </div>
                <select name="status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-xs rounded-xl py-2 px-3 focus:outline-none">
                    <option value="">Semua Status</option>
                    <option value="active" @selected(request('status') === 'active')>Aktif</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Non-aktif</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-medium transition-colors">
                    Filter
                </button>
            </form>

            @can('suppliers.manage')
            <a href="{{ route('admin.suppliers.create') }}" class="w-full sm:w-auto px-4 py-2.5 bg-[#DF5E1D] hover:bg-[#c45218] text-white rounded-xl text-xs font-bold shadow-sm transition-all flex items-center justify-center gap-1.5">
                <iconify-icon icon="solar:add-circle-bold" class="text-base"></iconify-icon>
                <span>Tambah Supplier Baru</span>
            </a>
            @endcan
        </div>
    </div>

    {{-- Suppliers Table --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">#</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Nama Supplier</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Kontak / Person</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Alamat</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest text-center">Restock</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($suppliers as $supplier)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-6 text-gray-400 text-xs">{{ $suppliers->firstItem() + $loop->index }}</td>
                        <td class="py-4 px-6">
                            <span class="font-bold text-[#363230] block">{{ $supplier->name }}</span>
                            @if($supplier->notes)
                                <span class="text-[11px] text-gray-400 line-clamp-1">{{ $supplier->notes }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            @if($supplier->phone)
                                <span class="font-mono text-xs font-semibold text-[#363230] block">{{ $supplier->phone }}</span>
                            @endif
                            <span class="text-xs text-gray-500">{{ $supplier->contact_person ?? ($supplier->email ?? '-') }}</span>
                        </td>
                        <td class="py-4 px-6 text-xs text-gray-600 max-w-xs truncate">
                            {{ $supplier->address ?? '-' }}
                        </td>
                        <td class="py-4 px-6 text-center">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                {{ $supplier->restocks_count }} Batch
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            @if($supplier->is_active)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                    Non-aktif
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                @can('suppliers.manage')
                                <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-colors" title="Edit Supplier">
                                    <iconify-icon icon="solar:pen-linear" class="text-base"></iconify-icon>
                                </a>
                                <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Hapus supplier {{ $supplier->name }}?')">
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
                        <td colspan="7" class="py-12 text-center text-gray-400">
                            <iconify-icon icon="solar:users-group-two-rounded-linear" class="text-4xl mb-2"></iconify-icon>
                            <p class="text-sm">Belum ada data supplier yang tercatat.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($suppliers->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $suppliers->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
