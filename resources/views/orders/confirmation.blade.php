@extends('layouts.landing')

@section('title', 'Order Confirmation')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20 text-center">
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-8">
        <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <iconify-icon icon="solar:check-circle-bold" class="text-3xl text-emerald-500"></iconify-icon>
        </div>

        <h1 class="text-2xl font-semibold text-[#363230] mb-2">Order Placed!</h1>
        <p class="text-gray-500 mb-6">Thank you for your purchase.</p>

        <div class="bg-gray-50 rounded-xl p-6 mb-8 text-left">
            <div class="flex justify-between mb-2">
                <span class="text-sm text-gray-500">Order Number</span>
                <span class="text-sm font-medium text-[#363230]">{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="text-sm text-gray-500">Status</span>
                <span class="text-sm font-medium text-amber-600">{{ ucfirst($order->status) }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="text-sm text-gray-500">Payment</span>
                <span class="text-sm font-medium text-amber-600">{{ ucfirst($order->payment_status) }}</span>
            </div>
            <div class="flex justify-between pt-2 border-t border-gray-200">
                <span class="text-sm font-semibold text-[#363230]">Total</span>
                <span class="text-lg font-semibold text-[#363230]">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
            </div>

            @if ($order->shipping_address)
            <div class="border-t border-gray-200 pt-4 mt-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-2">Shipping Address</p>
                <p class="text-sm text-[#363230]">{{ $order->shipping_address }}</p>
                <p class="text-sm text-[#363230]">{{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</p>
                <p class="text-sm text-[#363230]">{{ $order->shipping_phone }}</p>
            </div>
            @endif
        </div>

        <div class="space-y-3">
            <a href="{{ route('orders.history') }}" class="block w-full bg-[#DF5E1D] text-white py-3 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors">
                View Order History
            </a>
            <a href="{{ route('landing.home') }}" class="block w-full bg-gray-50 border border-gray-200 text-gray-600 py-3 rounded-xl text-sm font-medium hover:bg-white hover:border-gray-300 transition-colors">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection
