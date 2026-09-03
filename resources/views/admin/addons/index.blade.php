@extends('layouts.admin')

@section('title', 'Kelola Paket Add-Ons & Bundling')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <iconify-icon icon="solar:box-minimalistic-bold-duotone" class="text-[#DF5E1D] text-3xl"></iconify-icon>
                Paket Add-Ons & Bundling Laptop
            </h1>
            <p class="text-sm text-gray-500 mt-1">Kelola pilihan paket bundling, proteksi, dan aksesoris yang dapat dipilih pelanggan saat checkout atau pembelian via WhatsApp.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.addons.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#DF5E1D] hover:bg-[#c44f15] text-white text-sm font-semibold rounded-xl shadow-sm transition">
                <iconify-icon icon="solar:add-circle-bold" class="text-lg"></iconify-icon>
                Tambah Paket Add-On
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Paket</p>
                <h3 class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center text-[#DF5E1D]">
                <iconify-icon icon="solar:boxes-bold-duotone" class="text-2xl"></iconify-icon>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Paket Aktif</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['active'] }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <iconify-icon icon="solar:check-circle-bold-duotone" class="text-2xl"></iconify-icon>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Rekomendasi (Thumbs Up)</p>
                <h3 class="text-2xl font-bold text-rose-600 mt-1">{{ $stats['recommended'] }}</h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600">
                <iconify-icon icon="solar:like-bold-duotone" class="text-2xl"></iconify-icon>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
        <form method="GET" action="{{ route('admin.addons.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-6 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama paket atau deskripsi..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#DF5E1D] focus:ring-1 focus:ring-[#DF5E1D]">
                <iconify-icon icon="solar:magnifer-linear" class="absolute left-3 top-2.5 text-gray-400 text-lg"></iconify-icon>
            </div>

            <div class="sm:col-span-3">
                <select name="status" class="w-full py-2 px-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#DF5E1D]">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="sm:col-span-3 flex gap-2">
                <button type="submit" class="flex-1 py-2 px-4 bg-gray-900 hover:bg-black text-white text-sm font-semibold rounded-xl transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'recommended']))
                    <a href="{{ route('admin.addons.index') }}" class="py-2 px-3 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-xl transition flex items-center justify-center">
                        <iconify-icon icon="solar:restart-linear" class="text-lg"></iconify-icon>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-semibold border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Paket Add-On</th>
                        <th class="px-6 py-4">Harga Tambahan</th>
                        <th class="px-6 py-4">Urutan</th>
                        <th class="px-6 py-4">Rekomendasi 👍</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($addons as $addon)
                        <tr class="hover:bg-gray-50/70 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-200 overflow-hidden flex items-center justify-center shrink-0">
                                        @if($addon->image_url_full)
                                            <img src="{{ $addon->image_url_full }}" alt="{{ $addon->name }}" class="w-full h-full object-cover">
                                        @else
                                            <iconify-icon icon="solar:gift-bold-duotone" class="text-[#DF5E1D] text-2xl"></iconify-icon>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-gray-900">{{ $addon->name }}</span>
                                            @if($addon->is_recommended)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-200">
                                                    <iconify-icon icon="solar:like-bold" class="text-xs"></iconify-icon> Recommended
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-500 line-clamp-1 mt-0.5">{{ $addon->description ?? 'Tidak ada deskripsi' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($addon->price <= 0)
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-gray-100 text-gray-700 font-mono">Gratis (Rp 0)</span>
                                @else
                                    <span class="text-sm font-bold text-[#DF5E1D] font-mono">+Rp {{ number_format($addon->price, 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-600">{{ $addon->sort_order }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.addons.toggle-recommended', $addon) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold transition {{ $addon->is_recommended ? 'bg-rose-500 text-white shadow-sm' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                                        <iconify-icon icon="solar:like-bold" class="text-xs"></iconify-icon>
                                        {{ $addon->is_recommended ? 'Direkomendasikan' : 'Biasa' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.addons.toggle-active', $addon) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold transition {{ $addon->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-gray-100 text-gray-500' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $addon->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                        {{ $addon->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.addons.edit', $addon) }}" class="p-2 rounded-xl bg-gray-100 text-gray-700 hover:bg-orange-50 hover:text-[#DF5E1D] transition" title="Edit Paket">
                                        <iconify-icon icon="solar:pen-bold" class="text-base"></iconify-icon>
                                    </a>
                                    <form action="{{ route('admin.addons.destroy', $addon) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket add-on ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl bg-gray-100 text-gray-700 hover:bg-rose-50 hover:text-rose-600 transition" title="Hapus Paket">
                                            <iconify-icon icon="solar:trash-bin-trash-bold" class="text-base"></iconify-icon>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <iconify-icon icon="solar:box-minimalistic-linear" class="text-4xl"></iconify-icon>
                                <p class="text-sm mt-2">Belum ada paket Add-On yang ditambahkan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($addons->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $addons->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
