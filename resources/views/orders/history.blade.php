@extends('layouts.landing')

@section('title', 'Order History')

@php
$psLabels = ['unpaid' => 'Unpaid', 'pending_verification' => 'Pending Verification', 'paid' => 'Paid', 'expired' => 'Expired', 'failed' => 'Failed'];
$psColors = ['unpaid' => 'bg-yellow-100 text-yellow-700', 'pending_verification' => 'bg-blue-100 text-blue-700', 'paid' => 'bg-green-100 text-green-700', 'expired' => 'bg-rose-100 text-rose-700', 'failed' => 'bg-red-100 text-red-700'];
@endphp

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20">
    <h1 class="text-3xl font-semibold tracking-tight text-[#363230] mb-8">Order History</h1>

    @if ($orders->count() > 0)
        <div class="space-y-4">
            @foreach ($orders as $order)
                <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-sm font-medium text-[#363230]">{{ $order->order_number }}</p>
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y H:i') }}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="px-2 py-1 rounded-lg text-xs font-medium {{ $psColors[$order->payment_status] ?? 'bg-gray-100' }}">
                                    {{ $psLabels[$order->payment_status] ?? $order->payment_status }}
                                </span>
                                @if($order->payment_method === 'xendit')
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-medium">Xendit</span>
                                @else
                                <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-lg text-xs font-medium">Manual Transfer</span>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium
                                @if ($order->status === 'pending') bg-amber-50 text-amber-600 border border-amber-100
                                @elseif ($order->status === 'completed') bg-emerald-50 text-emerald-600 border border-emerald-100
                                @else bg-gray-100 text-gray-600 border border-gray-200
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                            <p class="text-lg font-semibold text-[#363230] mt-2">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-xs text-gray-500 mb-2">{{ $order->items->count() }} item(s)</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($order->items as $item)
                                <span class="text-xs bg-gray-50 text-gray-600 px-2 py-1 rounded border border-gray-100">
                                    {{ $item->product_name }}
                                    @if ($item->variant_name)
                                        ({{ $item->variant_name }})
                                    @endif
                                    x{{ $item->quantity }}
                                </span>
                            @endforeach
                        </div>
                        @if($order->shipping_courier)
                        <p class="text-xs text-gray-500 mt-1">
                            {{ strtoupper($order->shipping_courier) }} - {{ $order->shipping_service }}
                            @if($order->shipping_etd) ({{ $order->shipping_etd }}) @endif
                        </p>
                        @endif
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div>
                            @if($order->payment_method === 'xendit' && $order->payment_status === 'unpaid' && $order->xendit_invoice_url)
                            <a href="{{ $order->xendit_invoice_url }}" target="_blank"
                               class="px-3 py-1.5 bg-[#DF5E1D] text-white rounded-lg text-xs font-medium hover:bg-[#c94f14]">
                                Pay Now
                            </a>
                            @elseif($order->payment_method === 'manual_transfer' && $order->payment_status === 'unpaid')
                            <a href="{{ route('orders.confirmation', $order) }}"
                               class="px-3 py-1.5 bg-purple-600 text-white rounded-lg text-xs font-medium hover:bg-purple-700">
                                Upload Proof
                            </a>
                            @elseif($order->payment_status === 'paid')
                            <span class="text-xs text-green-600 font-medium">Paid on {{ $order->paid_at ? $order->paid_at->format('d M Y') : '-' }}</span>
                            @endif
                        </div>
                        <a href="{{ route('orders.confirmation', $order) }}" class="text-sm text-[#DF5E1D] hover:text-[#c45218] transition-colors font-medium">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($orders->hasPages())
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-20">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <iconify-icon icon="solar:bag-check-linear" class="text-3xl text-gray-300"></iconify-icon>
            </div>
            <h2 class="text-xl font-semibold text-[#363230] mb-2">No orders yet</h2>
            <p class="text-gray-500 mb-6">You haven't placed any orders.</p>
            <a href="{{ route('landing.search') }}" class="inline-flex bg-[#DF5E1D] text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors">
                Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection
