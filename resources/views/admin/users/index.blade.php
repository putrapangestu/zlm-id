@extends('layouts.dashboard')

@section('title', 'User Management - ZLM.ID Admin')
@section('page-title', 'User Management')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-[#363230]">User Management</h2>
            <p class="text-sm text-gray-500 mt-1">Manage admin and customer accounts</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <iconify-icon icon="solar:magnifer-linear" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="stroke-width: 1.5;"></iconify-icon>
                <input type="text" placeholder="Search users..." class="w-64 bg-white border border-gray-200 text-sm text-[#363230] placeholder-gray-400 rounded-xl py-2 pl-9 pr-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all">
            </div>
            <a href="#" class="bg-[#DF5E1D] text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-orange-600 transition-colors shadow-sm flex items-center gap-2">
                <iconify-icon icon="solar:plus-linear" style="stroke-width: 1.5;"></iconify-icon>
                Add User
            </a>
        </div>
    </div>

    {{-- Dummy Data --}}
    @php
        $users = [
            [
                'id'         => 1,
                'name'       => 'Rizky Aditya',
                'email'      => 'rizky.aditya@email.com',
                'role'       => 'admin',
                'role_label' => 'Admin',
                'role_class' => 'bg-orange-50 text-[#DF5E1D] border-orange-100/60',
                'avatar'     => 'RA',
                'avatar_bg'  => 'bg-orange-100 text-orange-600',
                'created_at' => '3 days ago',
            ],
            [
                'id'         => 2,
                'name'       => 'Sari Melinda',
                'email'      => 'sari.melinda@gmail.com',
                'role'       => 'customer',
                'role_label' => 'Customer',
                'role_class' => 'bg-blue-50 text-blue-600 border-blue-100/60',
                'avatar'     => 'SM',
                'avatar_bg'  => 'bg-blue-100 text-blue-600',
                'created_at' => '5 days ago',
            ],
        ];
    @endphp

    {{-- Users Table --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Nama</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Email</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Role</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors group">

                        {{-- Nama --}}
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-semibold shrink-0 {{ $user['avatar_bg'] }}">
                                    {{ $user['avatar'] }}
                                </div>
                                <div>
                                    <div class="font-medium text-[#363230]">{{ $user['name'] }}</div>
                                    <div class="text-xs text-gray-400">Joined {{ $user['created_at'] }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Email --}}
                        <td class="py-4 px-6 text-gray-500">{{ $user['email'] }}</td>

                        {{-- Role --}}
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border {{ $user['role_class'] }}">
                                <span class="w-1.5 h-1.5 rounded-full
                                    {{ $user['role'] === 'admin' ? 'bg-[#DF5E1D]' : 'bg-blue-500' }}">
                                </span>
                                {{ $user['role_label'] }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="#" class="p-2 text-gray-400 hover:text-[#DF5E1D] hover:bg-orange-50 rounded-lg transition-colors" title="Lihat">
                                    <iconify-icon icon="solar:eye-linear" style="stroke-width: 1.5;"></iconify-icon>
                                </a>
                                <a href="#" class="p-2 text-gray-400 hover:text-[#DF5E1D] hover:bg-orange-50 rounded-lg transition-colors" title="Edit">
                                    <iconify-icon icon="solar:pen-linear" style="stroke-width: 1.5;"></iconify-icon>
                                </a>
                                <button type="button" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <iconify-icon icon="solar:trash-bin-2-linear" style="stroke-width: 1.5;"></iconify-icon>
                                </button>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-16 px-6 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-3">
                                <iconify-icon icon="solar:users-group-rounded-linear" class="text-5xl text-gray-200" style="stroke-width: 1.5;"></iconify-icon>
                                <p class="text-sm">Belum ada user ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <div class="text-xs text-gray-400">
                Menampilkan <span class="font-medium text-gray-600">1</span> –
                <span class="font-medium text-gray-600">2</span> dari
                <span class="font-medium text-gray-600">2</span> users
            </div>
            <div class="flex items-center gap-2">
                <button class="px-3 py-1.5 text-xs font-medium text-gray-400 bg-white border border-gray-200 rounded-lg disabled:opacity-40 disabled:cursor-not-allowed" disabled>
                    Sebelumnya
                </button>
                <button class="px-3 py-1.5 text-xs font-medium text-gray-400 bg-white border border-gray-200 rounded-lg disabled:opacity-40 disabled:cursor-not-allowed" disabled>
                    Berikutnya
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
