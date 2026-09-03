@extends('layouts.admin')

@section('title', 'Master Brand Laptop')
@section('heading', 'Master Brand Laptop')

@section('content')
<div class="space-y-6">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-200/70 shadow-xs">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Total Brand</span>
            <p class="text-2xl font-extrabold text-[#363230] mt-1">{{ $stats['total_brands'] }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200/70 shadow-xs">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Brand Aktif</span>
            <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $stats['active_brands'] }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200/70 shadow-xs">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Model Laptop Terikat</span>
            <p class="text-2xl font-extrabold text-[#DF5E1D] mt-1">{{ $stats['total_laptops'] }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200/70 shadow-xs">
            <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block">Total Stok Fisik</span>
            <p class="text-2xl font-extrabold text-blue-600 mt-1">{{ $stats['total_stock'] }} unit</p>
        </div>
    </div>

    {{-- Action & Search Bar --}}
    <div class="bg-white p-4 rounded-2xl border border-gray-200/70 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.brands.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-72">
                <iconify-icon icon="solar:magnifer-linear" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></iconify-icon>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama brand..."
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2 pl-10 pr-4 text-xs font-medium focus:outline-none focus:border-[#DF5E1D]">
            </div>
            <select name="status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 rounded-xl py-2 px-3 text-xs font-medium focus:outline-none focus:border-[#DF5E1D]">
                <option value="">Semua Status</option>
                <option value="active" @selected(request('status') === 'active')>Aktif</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
            </select>
            <button type="submit" class="px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">Filter</button>
            @if(request('search') || request('status'))
                <a href="{{ route('admin.brands.index') }}" class="text-xs text-gray-400 hover:text-gray-600 font-semibold">Reset</a>
            @endif
        </form>

        <a href="{{ route('admin.brands.create') }}" class="px-4 py-2.5 bg-[#DF5E1D] hover:bg-[#c45218] text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs shrink-0 w-full sm:w-auto justify-center">
            <iconify-icon icon="solar:add-circle-linear" class="text-base"></iconify-icon>
            <span>Tambah Brand Baru</span>
        </a>
    </div>

    {{-- Brand Table --}}
    <div class="bg-white rounded-3xl border border-gray-200/70 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="py-3.5 px-6 font-bold text-gray-400 uppercase tracking-wider w-16">No</th>
                        <th class="py-3.5 px-6 font-bold text-gray-400 uppercase tracking-wider">Logo & Nama Brand</th>
                        <th class="py-3.5 px-6 font-bold text-gray-400 uppercase tracking-wider text-center">Jumlah Model</th>
                        <th class="py-3.5 px-6 font-bold text-gray-400 uppercase tracking-wider text-center">Stok Unit</th>
                        <th class="py-3.5 px-6 font-bold text-gray-400 uppercase tracking-wider text-center">Status</th>
                        <th class="py-3.5 px-6 font-bold text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($brands as $index => $brand)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6 text-gray-400 font-bold">{{ $brands->firstItem() + $index }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-200/80 p-1 flex items-center justify-center shrink-0 overflow-hidden">
                                        @if($brand->logo_url_full)
                                            <img src="{{ $brand->logo_url_full }}" alt="{{ $brand->name }}" class="w-full h-full object-contain">
                                        @else
                                            <span class="font-bold text-[#DF5E1D] text-xs">{{ strtoupper(substr($brand->name, 0, 2)) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.brands.show', $brand) }}" class="font-bold text-gray-900 hover:text-[#DF5E1D] transition-colors text-sm">
                                            {{ $brand->name }}
                                        </a>
                                        <p class="text-[11px] text-gray-400 font-mono">{{ $brand->slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="font-bold text-gray-700 bg-gray-100 px-2.5 py-1 rounded-lg">
                                    {{ $brand->laptops_count }} model
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-lg">
                                    {{ $brand->total_stock }} unit
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <form method="POST" action="{{ route('admin.brands.toggle-active', $brand) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" title="Klik untuk ubah status" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full font-bold text-[10px] transition {{ $brand->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $brand->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                        <span>{{ $brand->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                    </button>
                                </form>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.brands.show', $brand) }}" class="p-1.5 text-gray-500 hover:text-[#DF5E1D] hover:bg-orange-50 rounded-lg transition" title="Lihat Statistik & Laptop">
                                        <iconify-icon icon="solar:eye-linear" class="text-base"></iconify-icon>
                                    </a>
                                    <a href="{{ route('admin.brands.edit', $brand) }}" class="p-1.5 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <iconify-icon icon="solar:pen-linear" class="text-base"></iconify-icon>
                                    </a>
                                    <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" onsubmit="return confirm('Hapus brand ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                            <iconify-icon icon="solar:trash-bin-trash-linear" class="text-base"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <iconify-icon icon="solar:box-minimalistic-linear" class="text-4xl text-gray-200 mb-2"></iconify-icon>
                                <p class="text-xs">Belum ada data brand laptop yang sesuai.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($brands->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $brands->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
