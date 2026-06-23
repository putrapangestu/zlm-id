@extends('layouts.admin')

@section('title', 'Transaksi (Legacy)')
@section('heading', 'Transaksi')

@section('content')
<div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Order ID</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Customer</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Items</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Shipping</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Total</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Actions</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse ($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-4 px-6 font-medium text-[#363230]">#{{ $order->id }}</td>
                        <td class="py-4 px-6 text-gray-500">{{ $order->user?->name ?? 'N/A' }}</td>
                        <td class="py-4 px-6 text-gray-500">{{ $order->items->count() }}</td>
                        <td class="py-4 px-6 text-gray-500 max-w-[180px] truncate" title="{{ $order->shipping_city }}, {{ $order->shipping_province }}">
                            @if ($order->shipping_address)
                                <span class="text-xs">{{ $order->shipping_city }}, {{ $order->shipping_province }}</span>
                            @else
                                <span class="text-xs text-gray-300">—</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-[#363230]">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
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
                            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="flex gap-1">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="text-xs bg-gray-50 border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 transition-all cursor-pointer">
                                    <option value="pending" @selected($order->status == 'pending')>Pending</option>
                                    <option value="processing" @selected($order->status == 'processing')>Processing</option>
                                    <option value="shipped" @selected($order->status == 'shipped')>Shipped</option>
                                    <option value="delivered" @selected($order->status == 'delivered')>Delivered</option>
                                    <option value="cancelled" @selected($order->status == 'cancelled')>Cancelled</option>
                                </select>
                            </form>
                        </td>
                        <td class="py-4 px-6 text-gray-400 text-xs">{{ $order->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-gray-500">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($orders->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection