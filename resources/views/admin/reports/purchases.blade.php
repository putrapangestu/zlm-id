@extends('layouts.admin')

@section('title', 'Laporan Pembelian')
@section('heading', 'Laporan Pembelian')

@section('content')
<div class="space-y-6">
    <!-- Filter Form -->
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded-lg border-gray-200 text-sm focus:ring-[#DF5E1D] focus:border-[#DF5E1D]">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded-lg border-gray-200 text-sm focus:ring-[#DF5E1D] focus:border-[#DF5E1D]">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-lg border-gray-200 text-sm focus:ring-[#DF5E1D] focus:border-[#DF5E1D]">
                    <option value="">All</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="processing" @selected(request('status') === 'processing')>Processing</option>
                    <option value="shipped" @selected(request('status') === 'shipped')>Shipped</option>
                    <option value="delivered" @selected(request('status') === 'delivered')>Delivered</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Payment</label>
                <select name="payment_status" class="w-full rounded-lg border-gray-200 text-sm focus:ring-[#DF5E1D] focus:border-[#DF5E1D]">
                    <option value="">All</option>
                    <option value="paid" @selected(request('payment_status') === 'paid')>Paid</option>
                    <option value="unpaid" @selected(request('payment_status') === 'unpaid')>Unpaid</option>
                    <option value="pending_verification" @selected(request('payment_status') === 'pending_verification')>Pending Verification</option>
                    <option value="expired" @selected(request('payment_status') === 'expired')>Expired</option>
                    <option value="failed" @selected(request('payment_status') === 'failed')>Failed</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="bg-[#DF5E1D] text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-[#c45218] transition">Filter</button>
                <a href="{{ route('admin.reports.purchases') }}" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">Reset</a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-gray-200/60 p-5">
            <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Total Orders</div>
            <div class="text-2xl font-semibold text-[#363230]">{{ $summary['total_orders'] }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200/60 p-5">
            <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Total Revenue</div>
            <div class="text-2xl font-semibold text-[#363230]">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200/60 p-5">
            <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Avg Order Value</div>
            <div class="text-2xl font-semibold text-[#363230]">Rp {{ number_format($summary['avg_order'], 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-4 py-3 font-medium text-gray-500">No</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">Tanggal</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">Order #</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">Customer</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-500">Items</th>
                        <th class="text-right px-4 py-3 font-medium text-gray-500">Total</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-500">Payment</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                        <td class="px-4 py-3 text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $order->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 font-medium text-[#DF5E1D]">{{ $order->order_number }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $order->user->name }}</td>
                        <td class="px-4 py-3 text-center text-gray-700">{{ $order->items_count ?? $order->items->count() }}</td>
                        <td class="px-4 py-3 text-right font-medium text-[#363230]">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                @if($order->payment_status === 'paid') bg-emerald-50 text-emerald-600
                                @elseif($order->payment_status === 'unpaid') bg-yellow-50 text-yellow-600
                                @else bg-gray-100 text-gray-500 @endif">
                                {{ $order->payment_status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                @if($order->status === 'delivered') bg-emerald-50 text-emerald-600
                                @elseif($order->status === 'shipped') bg-blue-50 text-blue-600
                                @elseif($order->status === 'processing') bg-purple-50 text-purple-600
                                @else bg-gray-100 text-gray-500 @endif">
                                {{ $order->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-8 text-gray-400">No orders found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection
