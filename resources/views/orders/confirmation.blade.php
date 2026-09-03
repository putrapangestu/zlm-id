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
            <div class="border-t border-gray-200 pt-3 mt-3">
                <p class="text-xs text-gray-500 font-bold uppercase tracking-wide mb-2">Daftar Produk & Paket:</p>
                <div class="space-y-2">
                    @foreach ($order->items as $item)
                        <div class="flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-gray-800">{{ $item->product_name }}</span>
                                <span class="text-gray-400"> x {{ $item->quantity }}</span>
                                @if ($item->addon_name)
                                    <span class="block text-[11px] font-semibold text-[#166534]">
                                        Bundle: {{ $item->addon_name }} (+Rp {{ number_format($item->addon_price, 0, ',', '.') }})
                                    </span>
                                @endif
                            </div>
                            <span class="font-mono font-bold text-gray-700">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-between pt-3 border-t border-gray-200 mt-3">
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

            @if($order->shipping_courier)
            <div class="border-t border-gray-200 pt-4 mt-4">
                <p class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-2">Shipping</p>
                <div class="space-y-1 text-sm text-gray-600">
                    <p>Courier: <span class="font-medium text-[#363230]">{{ strtoupper($order->shipping_courier) }}</span></p>
                    <p>Service: <span class="font-medium text-[#363230]">{{ $order->shipping_service }}</span></p>
                    <p>Cost: <span class="font-medium text-[#363230]">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span></p>
                    @if($order->shipping_etd)
                    <p>ETD: <span class="font-medium text-[#363230]">{{ $order->shipping_etd }}</span></p>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Total Breakdown --}}
        <div class="mt-4 p-4 bg-gray-50 rounded-xl">
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Subtotal</span>
                    <span class="font-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Tax ({{ config('settings.tax_rate', 11) }}%)</span>
                    <span class="font-medium">Rp {{ number_format($order->tax, 0, ',', '.') }}</span>
                </div>
                @if($order->shipping_cost)
                <div class="flex justify-between">
                    <span class="text-gray-500">Shipping</span>
                    <span class="font-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between border-t border-gray-200 pt-2">
                    <span class="font-semibold text-[#363230]">Grand Total</span>
                    <span class="font-bold text-[#DF5E1D]">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <a href="{{ route('orders.history') }}" class="block w-full bg-[#DF5E1D] text-white py-3 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors">
                View Order History
            </a>
            <a href="{{ route('landing.home') }}" class="block w-full bg-gray-50 border border-gray-200 text-gray-600 py-3 rounded-xl text-sm font-medium hover:bg-white hover:border-gray-300 transition-colors">
                Continue Shopping
            </a>
        </div>

        {{-- Payment Section --}}
        <div class="mt-6 p-6 bg-white rounded-xl border border-gray-200">
            <h3 class="text-lg font-semibold text-[#363230] mb-4">Payment</h3>
            
            @if($order->payment_method === 'xendit')
                @if($order->payment_status === 'paid')
                <div class="flex items-center gap-3 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">Lunas</span>
                </div>
                @else
                <a href="{{ $order->xendit_invoice_url }}" target="_blank"
                   class="inline-block px-6 py-2.5 bg-[#DF5E1D] text-white rounded-xl hover:bg-[#c94f14] transition-colors text-sm font-medium">
                    Bayar Sekarang via Xendit →
                </a>
                @endif
            @elseif($order->payment_method === 'manual_transfer')
                @if($order->payment_status === 'paid')
                <div class="flex items-center gap-3 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">Lunas</span>
                </div>
                @endif
                {{-- Form upload dari TRX-4 sudah ada di bawah --}}
            @endif
        </div>

        {{-- Upload Bukti Transfer --}}
        @if($order->payment_method === 'manual_transfer' && $order->payment_status === 'unpaid')
        <div class="mt-6 p-6 bg-white rounded-xl border border-gray-200">
            <h3 class="text-lg font-semibold text-[#363230] mb-4">Upload Bukti Transfer</h3>
            
            {{-- Info Rekening --}}
            <div class="mb-4 p-4 bg-orange-50 rounded-lg border border-orange-200">
                <p class="text-sm font-medium text-orange-800 mb-2">Transfer ke:</p>
                <p class="text-sm text-[#363230]">
                    Bank {{ config('settings.bank_name', 'BCA') }}<br>
                    {{ config('settings.bank_account', '123-456-7890') }}<br>
                    a.n. {{ config('settings.bank_holder', 'PT ZLM ID') }}
                </p>
            </div>

            {{-- Form Upload --}}
            <form action="{{ route('orders.proof.upload', $order) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-[#363230] mb-1">Upload Bukti</label>
                    <input type="file" name="proof" accept=".jpg,.jpeg,.png,.pdf" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-[#DF5E1D] focus:border-[#DF5E1D]">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG, PDF. Maks: 2MB</p>
                </div>
                @error('proof')
                    <p class="text-sm text-red-500">{{ $message }}</p>
                @enderror
                <button type="submit" 
                        class="px-6 py-2.5 bg-[#DF5E1D] text-white rounded-xl hover:bg-[#c94f14] transition-colors text-sm font-medium">
                    Upload Bukti Transfer
                </button>
            </form>
        </div>
        @elseif($order->payment_status === 'pending_verification')
        <div class="mt-6 p-6 bg-yellow-50 rounded-xl border border-yellow-200">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="font-medium text-yellow-800">Bukti Transfer Terupload</p>
                    <p class="text-sm text-yellow-700">Pembayaran sedang diverifikasi oleh admin. Silakan tunggu konfirmasi.</p>
                </div>
            </div>
            @if($order->proof_of_transfer)
            <div class="mt-3">
                <a href="{{ Storage::url($order->proof_of_transfer) }}" target="_blank" 
                   class="text-sm text-[#DF5E1D] hover:underline">
                    Lihat Bukti Transfer
                </a>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
