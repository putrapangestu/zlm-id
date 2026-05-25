@extends('layouts.admin')

@section('title', 'Customer Detail — ' . $customer->name)
@section('heading', $customer->name)

@section('content')
<div class="space-y-6">
    <div>
        <a href="{{ route('admin.customers.index') }}" class="text-sm text-gray-400 hover:text-[#363230] transition-colors inline-flex items-center gap-1">
            <iconify-icon icon="solar:arrow-left-linear" class="text-base"></iconify-icon>
            Kembali ke Customers
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
        <div class="flex flex-col sm:flex-row gap-6 items-start">
            <div class="w-16 h-16 rounded-full bg-[#363230] flex items-center justify-center text-white text-xl font-bold shrink-0">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-1">
                    <h2 class="text-lg font-semibold text-[#363230]">{{ $customer->name }}</h2>
                    @if ($customer->email_verified_at)
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-emerald-50 text-emerald-600">Active</span>
                    @else
                        <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-gray-100 text-gray-500">Inactive</span>
                    @endif
                </div>
                <p class="text-sm text-gray-500">{{ $customer->email }}</p>
                <div class="flex flex-wrap gap-4 mt-3 text-xs text-gray-400">
                    <span>Role: <strong class="text-[#363230]">{{ $customer->getRoleNames()->implode(', ') ?: 'buyer' }}</strong></span>
                    <span>Member since: <strong class="text-[#363230]">{{ $customer->created_at->format('M Y') }}</strong></span>
                    @if ($lastOrderDate)
                        <span>Last order: <strong class="text-[#363230]">{{ \Carbon\Carbon::parse($lastOrderDate)->format('d M Y') }}</strong></span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 text-center">
            <p class="text-2xl font-bold text-[#363230]">{{ $orderCount }}</p>
            <p class="text-xs text-gray-500 mt-1">Pesanan</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 text-center">
            <p class="text-2xl font-bold text-[#363230]">Rp {{ number_format($totalSpending, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Belanja</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 text-center">
            <p class="text-2xl font-bold text-[#363230]">{{ $reviewCount }}</p>
            <p class="text-xs text-gray-500 mt-1">Review</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-[#363230]">Pesanan Terakhir</h3>
        </div>
        @if ($orderCount > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Order</th>
                            <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Date</th>
                            <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Items</th>
                            <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Total</th>
                            <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @foreach ($customer->orders as $order)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6 font-medium text-[#363230]">{{ $order->order_number }}</td>
                                <td class="py-4 px-6 text-gray-500">{{ $order->created_at->format('d M Y') }}</td>
                                <td class="py-4 px-6 text-gray-500">{{ $order->items->count() }} item</td>
                                <td class="py-4 px-6 font-medium text-[#363230]">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td class="py-4 px-6">
                                    @if ($order->status === 'completed')
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-emerald-50 text-emerald-600">Selesai</span>
                                    @elseif ($order->status === 'pending')
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-yellow-50 text-yellow-600">Pending</span>
                                    @elseif ($order->status === 'cancelled')
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-rose-50 text-rose-600">Dibatalkan</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-medium bg-gray-100 text-gray-600">{{ $order->status }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-12 text-center text-sm text-gray-500">Belum ada pesanan.</div>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-[#363230]">Review Terakhir</h3>
        </div>
        @if ($reviewCount > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50">
                            <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Product</th>
                            <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Rating</th>
                            <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Date</th>
                            <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Review</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-sm">
                        @foreach ($customer->reviews as $review)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="py-4 px-6 font-medium text-[#363230]">{{ $review->laptop?->name ?? '-' }}</td>
                                <td class="py-4 px-6">
                                    <span class="text-yellow-500">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                                </td>
                                <td class="py-4 px-6 text-gray-400 text-xs">{{ $review->created_at->format('d M Y') }}</td>
                                <td class="py-4 px-6 text-gray-500 max-w-xs truncate">{{ $review->comment ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-12 text-center text-sm text-gray-500">Belum ada review.</div>
        @endif
    </div>
</div>
@endsection
