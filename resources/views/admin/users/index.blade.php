@extends('layouts.admin')

@section('title', 'User Management - ZLM.ID Admin')
@section('heading', 'User Management')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-[#363230]">User Management</h2>
            <p class="text-sm text-gray-500 mt-1">Manage admin and customer accounts</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-[#DF5E1D] text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors shadow-sm flex items-center gap-2 w-fit">
            <iconify-icon icon="solar:plus-linear" style="stroke-width: 1.5;"></iconify-icon>
            Add User
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-4">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <iconify-icon icon="solar:magnifer-linear" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="stroke-width: 1.5;"></iconify-icon>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..."
                    class="w-full bg-gray-50 border border-gray-200 text-sm text-[#363230] placeholder-gray-400 rounded-xl py-2.5 pl-9 pr-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all">
            </div>
            <div class="relative">
                <iconify-icon icon="solar:shield-user-linear" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="stroke-width: 1.5;"></iconify-icon>
                <select name="role" class="appearance-none bg-gray-50 border border-gray-200 text-sm text-[#363230] rounded-xl py-2.5 pl-9 pr-10 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all cursor-pointer">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" @selected(request('role') === $role->name)>{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
                <iconify-icon icon="solar:alt-arrow-down-linear" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" style="stroke-width: 1.5;"></iconify-icon>
            </div>
            <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors">
                Filter
            </button>
            @if(request('search') || request('role'))
                <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-red-500 px-3 py-2.5 rounded-xl text-sm transition-colors flex items-center gap-1">
                    <iconify-icon icon="solar:close-circle-linear" style="stroke-width: 1.5;"></iconify-icon>
                    Clear
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
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Name</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Email</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Role</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest text-center">Orders</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Registered</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        {{-- No --}}
                        <td class="py-4 px-6 text-gray-400 text-xs">{{ $users->firstItem() + $loop->index }}</td>

                        {{-- Name --}}
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-[#DF5E1D]/10 flex items-center justify-center text-xs font-semibold text-[#DF5E1D] shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <span class="font-medium text-[#363230]">{{ $user->name }}</span>
                            </div>
                        </td>

                        {{-- Email --}}
                        <td class="py-4 px-6 text-gray-500">{{ $user->email }}</td>

                        {{-- Role --}}
                        <td class="py-4 px-6">
                            @php
                                $roleName = $user->roles->first()?->name ?? 'user';
                                $roleClass = match($roleName) {
                                    'admin' => 'bg-orange-50 text-[#DF5E1D] border-orange-100/60',
                                    'customer' => 'bg-blue-50 text-blue-600 border-blue-100/60',
                                    default => 'bg-gray-50 text-gray-600 border-gray-100/60',
                                };
                                $dotColor = match($roleName) {
                                    'admin' => 'bg-[#DF5E1D]',
                                    'customer' => 'bg-blue-500',
                                    default => 'bg-gray-400',
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border {{ $roleClass }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                                {{ ucfirst($roleName) }}
                            </span>
                        </td>

                        {{-- Orders --}}
                        <td class="py-4 px-6 text-center text-gray-500">{{ $user->orders_count }}</td>

                        {{-- Registered --}}
                        <td class="py-4 px-6 text-gray-400 text-xs">{{ $user->created_at->format('M d, Y') }}</td>

                        {{-- Actions --}}
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.users.show', $user) }}" class="p-2 text-gray-400 hover:text-[#DF5E1D] hover:bg-orange-50 rounded-lg transition-colors" title="View">
                                    <iconify-icon icon="solar:eye-linear" style="stroke-width: 1.5;"></iconify-icon>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-gray-400 hover:text-[#DF5E1D] hover:bg-orange-50 rounded-lg transition-colors" title="Edit">
                                    <iconify-icon icon="solar:pen-linear" style="stroke-width: 1.5;"></iconify-icon>
                                </a>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
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
                                <p class="text-sm">No users found.</p>
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
