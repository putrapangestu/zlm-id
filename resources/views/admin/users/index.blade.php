@extends('layouts.admin')

@section('title', 'Manajemen Pengguna — ZLM.ID Admin')
@section('heading', 'Manajemen Pengguna & Hak Akses')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-[#363230]">Daftar Pengguna & Karyawan</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola akun Admin, Karyawan Toko, Pelanggan, serta hak akses Spatie Permission</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-[#DF5E1D] text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors shadow-sm flex items-center gap-2 w-fit">
            <iconify-icon icon="solar:user-plus-linear" style="stroke-width: 1.5;"></iconify-icon>
            Tambah Pengguna
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <iconify-icon icon="solar:magnifer-linear" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="stroke-width: 1.5;"></iconify-icon>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, No. HP, No. Member..."
                    class="w-full bg-gray-50 border border-gray-200 text-sm text-[#363230] placeholder-gray-400 rounded-xl py-2.5 pl-9 pr-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all">
            </div>
            <div class="relative">
                <iconify-icon icon="solar:shield-user-linear" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="stroke-width: 1.5;"></iconify-icon>
                <select name="role" class="appearance-none bg-gray-50 border border-gray-200 text-sm text-[#363230] rounded-xl py-2.5 pl-9 pr-10 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all cursor-pointer">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" @selected(request('role') === $role->name)>{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
                <iconify-icon icon="solar:alt-arrow-down-linear" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" style="stroke-width: 1.5;"></iconify-icon>
            </div>
            <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-medium transition-colors">
                Filter
            </button>
            @if(request('search') || request('role'))
                <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-red-500 px-3 py-2.5 rounded-xl text-sm transition-colors flex items-center gap-1">
                    <iconify-icon icon="solar:close-circle-linear" style="stroke-width: 1.5;"></iconify-icon>
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">#</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Nama & Member</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Kontak</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Role & Akses</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest text-center">Pesanan</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Terdaftar</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        {{-- No --}}
                        <td class="py-4 px-6 text-gray-400 text-xs">{{ $users->firstItem() + $loop->index }}</td>

                        {{-- Name & Member --}}
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-[#DF5E1D]/10 flex items-center justify-center text-xs font-semibold text-[#DF5E1D] shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <span class="font-medium text-[#363230] block">{{ $user->name }}</span>
                                    <span class="text-[11px] text-gray-400">ID: {{ $user->member_number ?? 'Non-Member' }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- Kontak --}}
                        <td class="py-4 px-6">
                            <div class="text-xs text-gray-600 font-medium">{{ $user->email }}</div>
                            <div class="text-[11px] text-gray-400">{{ $user->phone_number ?? '-' }}</div>
                        </td>

                        {{-- Role & Akses --}}
                        <td class="py-4 px-6">
                            @php
                                $roleName = $user->roles->first()?->name ?? 'customer';
                                $roleClass = match($roleName) {
                                    'admin' => 'bg-purple-50 text-purple-700 border-purple-100/80',
                                    'karyawan' => 'bg-blue-50 text-blue-700 border-blue-100/80',
                                    default => 'bg-emerald-50 text-emerald-700 border-emerald-100/80',
                                };
                                $dotColor = match($roleName) {
                                    'admin' => 'bg-purple-600',
                                    'karyawan' => 'bg-blue-600',
                                    default => 'bg-emerald-600',
                                };
                            @endphp
                            <div class="flex flex-col gap-1 items-start">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $roleClass }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                    {{ ucfirst($roleName) }}
                                </span>
                                @if($roleName === 'karyawan')
                                    <span class="text-[10px] text-gray-500 font-medium">
                                        {{ $user->permissions->count() }} Hak Akses Khusus
                                    </span>
                                @elseif($roleName === 'admin')
                                    <span class="text-[10px] text-purple-500 font-medium">Semua Modul</span>
                                @endif
                            </div>
                        </td>

                        {{-- Orders --}}
                        <td class="py-4 px-6 text-center">
                            <span class="inline-block px-2 py-0.5 rounded-md bg-gray-100 text-xs text-gray-700 font-medium">
                                {{ $user->orders_count }}
                            </span>
                        </td>

                        {{-- Registered --}}
                        <td class="py-4 px-6 text-gray-400 text-xs">{{ $user->created_at->format('d M Y') }}</td>

                        {{-- Actions --}}
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.users.show', $user) }}" class="p-2 text-gray-400 hover:text-[#DF5E1D] hover:bg-orange-50 rounded-lg transition-colors" title="Lihat">
                                    <iconify-icon icon="solar:eye-linear" style="stroke-width: 1.5;"></iconify-icon>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-gray-400 hover:text-[#DF5E1D] hover:bg-orange-50 rounded-lg transition-colors" title="Edit Hak Akses">
                                    <iconify-icon icon="solar:pen-linear" style="stroke-width: 1.5;"></iconify-icon>
                                </a>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <iconify-icon icon="solar:trash-bin-2-linear" style="stroke-width: 1.5;"></iconify-icon>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-16 px-6 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-3">
                                <iconify-icon icon="solar:users-group-rounded-linear" class="text-5xl text-gray-200" style="stroke-width: 1.5;"></iconify-icon>
                                <p class="text-sm">Tidak ada data pengguna ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
