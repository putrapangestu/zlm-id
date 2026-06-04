@extends('layouts.admin')
@section('title', 'Transactions')
@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-[#363230]">Transactions</h1>
        <a href="{{ route('admin.transactions.create') }}" 
           class="px-4 py-2 bg-[#DF5E1D] text-white rounded-xl hover:bg-[#c94f14] transition-colors text-sm font-medium">
            + New Transaction
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-gray-200">
            <p class="text-sm text-gray-500">Total Orders</p>
            <p class="text-2xl font-bold text-[#363230]">{{ $stats['total_orders'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200">
            <p class="text-sm text-gray-500">Paid</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['total_paid'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['total_pending'] }}</p>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200">
            <p class="text-sm text-gray-500">Total Revenue</p>
            <p class="text-2xl font-bold text-[#363230]">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white p-4 rounded-xl border border-gray-200">
        <form method="GET" class="flex gap-4">
            <input type="text" name="search" placeholder="Search order or customer..." 
                   value="{{ request('search') }}"
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <select name="payment_method" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">All Methods</option>
                <option value="xendit" {{ request('payment_method') == 'xendit' ? 'selected' : '' }}>Xendit</option>
                <option value="manual_transfer" {{ request('payment_method') == 'manual_transfer' ? 'selected' : '' }}>Manual Transfer</option>
            </select>
            <select name="payment_status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="">All Status</option>
                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="pending_verification" {{ request('payment_status') == 'pending_verification' ? 'selected' : '' }}>Pending Verification</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-[#363230] rounded-lg hover:bg-gray-200 text-sm">Filter</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 text-sm font-semibold text-[#363230]">Order</th>
                    <th class="text-left px-4 py-3 text-sm font-semibold text-[#363230]">Customer</th>
                    <th class="text-left px-4 py-3 text-sm font-semibold text-[#363230]">Date</th>
                    <th class="text-left px-4 py-3 text-sm font-semibold text-[#363230]">Method</th>
                    <th class="text-left px-4 py-3 text-sm font-semibold text-[#363230]">Total</th>
                    <th class="text-left px-4 py-3 text-sm font-semibold text-[#363230]">Status</th>
                    <th class="text-left px-4 py-3 text-sm font-semibold text-[#363230]">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-medium text-[#363230]">{{ $order->order_number }}</td>
                    <td class="px-4 py-3 text-sm">
                        <p class="font-medium text-[#363230]">{{ $order->user->name ?? '-' }}</p>
                        <p class="text-gray-500 text-xs">{{ $order->user->email ?? '' }}</p>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if($order->payment_method === 'xendit')
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-medium">Xendit</span>
                        @else
                            <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs font-medium">Manual</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm font-medium">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        @php
                            $statusColors = ['unpaid' => 'bg-yellow-100 text-yellow-700', 'pending_verification' => 'bg-orange-100 text-orange-700', 'paid' => 'bg-green-100 text-green-700', 'failed' => 'bg-red-100 text-red-700'];
                            $statusLabels = ['unpaid' => 'Unpaid', 'pending_verification' => 'Pending Verification', 'paid' => 'Paid', 'failed' => 'Failed'];
                        @endphp
                        <span class="px-2 py-1 rounded-lg text-xs font-medium {{ $statusColors[$order->payment_status] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ $statusLabels[$order->payment_status] ?? $order->payment_status }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.transactions.show', $order) }}" 
                           class="text-[#DF5E1D] hover:underline text-sm font-medium">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500 text-sm">No transactions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection
