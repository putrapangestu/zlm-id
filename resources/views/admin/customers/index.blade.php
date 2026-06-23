@extends('layouts.admin')

@section('title', 'Pelanggan')
@section('heading', 'Pelanggan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">Total: <strong>{{ $users->total() }}</strong> pelanggan</p>
    </div>

    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label for="search" class="block text-xs font-medium text-gray-500 mb-1">Cari</label>
            <input type="text" id="search" name="search" value="{{ request('search') }}"
                   class="px-4 py-2 rounded-xl border border-gray-200 text-sm outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all w-60"
                   placeholder="Cari nama/email...">
        </div>
        <div>
            <label for="status" class="block text-xs font-medium text-gray-500 mb-1">Status</label>
            <select id="status" name="status"
                    class="px-4 py-2 rounded-xl border border-gray-200 text-sm outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>Semua</option>
                <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <button type="submit"
                class="px-4 py-2 rounded-xl bg-[#363230] text-white text-sm font-medium hover:bg-[#DF5E1D] transition-all duration-300">
            Terapkan
        </button>
        @if (request('search') || request('status'))
            <a href="{{ route('admin.customers.index') }}"
               class="px-4 py-2 rounded-xl border border-gray-200 text-sm text-gray-500 hover:text-gray-700 transition-all">
                Reset
            </a>
        @endif
    </form>

    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Name</th>
                        <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Email</th>
                        <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Since</th>
                        <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-4 px-6">
                                <a href="{{ route('admin.customers.show', $user) }}" class="font-medium text-[#363230] hover:text-[#DF5E1D] transition-colors">
                                    {{ $user->name }}
                                </a>
                            </td>
                            <td class="py-4 px-6 text-gray-500">{{ $user->email }}</td>
                            <td class="py-4 px-6">
                                @if ($user->email_verified_at)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium bg-emerald-50 text-emerald-600">Active</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium bg-gray-100 text-gray-500">Inactive</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-gray-400 text-xs">{{ $user->created_at->format('M Y') }}</td>
                            <td class="py-4 px-6">
                                <a href="{{ route('admin.customers.show', $user) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#DF5E1D]/5 text-[#DF5E1D] text-xs font-medium hover:bg-[#DF5E1D]/10 transition-colors">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-500">Pelanggan tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
