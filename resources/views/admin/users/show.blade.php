@extends('layouts.admin')

@section('title', 'Detail Pengguna — ZLM.ID Admin')
@section('heading', 'Detail Pengguna')

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-400">
        <a href="{{ route('admin.users.index') }}" class="hover:text-[#DF5E1D] transition-colors">Users</a>
        <iconify-icon icon="solar:alt-arrow-right-linear" style="stroke-width: 1.5;"></iconify-icon>
        <span class="text-[#363230] font-medium">{{ $user->name }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- User Info Card --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
                <div class="flex flex-col items-center text-center mb-6">
                    <div class="w-16 h-16 rounded-2xl bg-[#DF5E1D]/10 flex items-center justify-center text-xl font-semibold text-[#DF5E1D] mb-3">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <h3 class="text-lg font-semibold text-[#363230]">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>

                <div class="space-y-4 border-t border-gray-100 pt-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Role</span>
                        @php
                            $roleName = $user->roles->first()?->name ?? 'user';
                            $roleClass = match($roleName) {
                                'admin' => 'bg-orange-50 text-[#DF5E1D]',
                                'customer' => 'bg-blue-50 text-blue-600',
                                default => 'bg-gray-50 text-gray-600',
                            };
                        @endphp
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $roleClass }}">{{ ucfirst($roleName) }}</span>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Member since</span>
                        <span class="text-[#363230] font-medium">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Email verified</span>
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center gap-1 text-emerald-600 text-xs font-medium">
                                <iconify-icon icon="solar:check-circle-bold" style="stroke-width: 1.5;"></iconify-icon>
                                Verified
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-gray-400 text-xs font-medium">
                                <iconify-icon icon="solar:close-circle-bold" style="stroke-width: 1.5;"></iconify-icon>
                                Not verified
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Total orders</span>
                        <span class="text-[#363230] font-medium">{{ $orders->total() }}</span>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-500">Total spent</span>
                        <span class="text-[#363230] font-semibold">Rp {{ number_format($totalSpent, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-100 flex gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="flex-1 text-center px-4 py-2.5 rounded-xl text-sm font-medium text-[#DF5E1D] bg-[#DF5E1D]/10 hover:bg-[#DF5E1D]/20 transition-colors">
                        Edit User
                    </a>
                </div>
            </div>
        </div>

        {{-- Order History --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-[#363230]">Order History</h3>
                </div>

                @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50">
                                <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Order #</th>
                                <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Date</th>
                                <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Items</th>
                                <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Total</th>
                                <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Payment</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm">
                            @foreach($orders as $order)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6 font-medium text-[#363230]">{{ $order->order_number }}</td>
                                <td class="py-4 px-6 text-gray-500 text-xs">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="py-4 px-6 text-gray-500">{{ $order->items->count() }}</td>
                                <td class="py-4 px-6 text-[#363230] font-medium">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium
                                        @switch($order->status)
                                            @case('pending') bg-amber-50 text-amber-600 @break
                                            @case('processing') bg-blue-50 text-blue-600 @break
                                            @case('shipped') bg-purple-50 text-purple-600 @break
                                            @case('delivered') bg-emerald-50 text-emerald-600 @break
                                            @case('cancelled') bg-rose-50 text-rose-600 @break
                                            @default bg-gray-50 text-gray-600
                                        @endswitch">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="text-xs
                                        @if($order->payment_status === 'paid') text-emerald-600
                                        @elseif($order->payment_status === 'unpaid') text-amber-600
                                        @else text-gray-500 @endif">
                                        {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $orders->links() }}
                </div>
                @endif

                @else
                <div class="py-16 text-center">
                    <iconify-icon icon="solar:bag-check-linear" class="text-4xl text-gray-200 mb-3"></iconify-icon>
                    <p class="text-sm text-gray-400">No orders yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
