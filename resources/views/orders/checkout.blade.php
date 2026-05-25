@extends('layouts.landing')

@section('title', 'Checkout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20">
    <h1 class="text-3xl font-semibold tracking-tight text-[#363230] mb-8">Checkout</h1>

    <form method="POST" action="{{ route('orders.place') }}">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-[#363230] mb-4">Order Summary</h2>

                    <div class="divide-y divide-gray-100">
                        @foreach ($cart->items as $item)
                            <div class="flex items-center gap-4 py-4">
                                <div class="w-16 h-16 bg-gray-50 rounded-xl flex items-center justify-center p-2 border border-gray-100">
                                    @if ($item->laptop->image_url)
                                        <img src="{{ $item->laptop->image_url_full }}" alt="" class="w-full h-full object-contain mix-blend-multiply">
                                    @else
                                        <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-2xl text-gray-300"></iconify-icon>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-[#363230]">{{ $item->laptop->name }}</p>
                                    @if ($item->variant)
                                        <p class="text-sm text-gray-500">{{ $item->variant->name }}</p>
                                    @endif
                                    <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                </div>
                                <p class="font-medium text-[#363230]">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-[#363230] mb-4">Shipping Address</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-2">Street Address</label>
                            <input type="text" name="shipping_address" value="{{ old('shipping_address') }}" placeholder="Jl. Contoh No. 123" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all focus:bg-white" required>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-2">City</label>
                                <input type="text" name="shipping_city" value="{{ old('shipping_city') }}" placeholder="Jakarta" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all focus:bg-white" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-2">Province</label>
                                <select name="shipping_province" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white appearance-none cursor-pointer">
                                    <option value="">Pilih Provinsi</option>
                                    <option value="DKI Jakarta" @selected(old('shipping_province') == 'DKI Jakarta')>DKI Jakarta</option>
                                    <option value="Jawa Barat" @selected(old('shipping_province') == 'Jawa Barat')>Jawa Barat</option>
                                    <option value="Jawa Tengah" @selected(old('shipping_province') == 'Jawa Tengah')>Jawa Tengah</option>
                                    <option value="Jawa Timur" @selected(old('shipping_province') == 'Jawa Timur')>Jawa Timur</option>
                                    <option value="Banten" @selected(old('shipping_province') == 'Banten')>Banten</option>
                                    <option value="Sumatera Utara" @selected(old('shipping_province') == 'Sumatera Utara')>Sumatera Utara</option>
                                    <option value="Sulawesi Selatan" @selected(old('shipping_province') == 'Sulawesi Selatan')>Sulawesi Selatan</option>
                                    <option value="Kalimantan Timur" @selected(old('shipping_province') == 'Kalimantan Timur')>Kalimantan Timur</option>
                                    <option value="Bali" @selected(old('shipping_province') == 'Bali')>Bali</option>
                                    <option value="Lainnya" @selected(old('shipping_province') == 'Lainnya')>Lainnya</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-2">Postal Code</label>
                                <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" placeholder="12345" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all focus:bg-white" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-2">Phone</label>
                                <input type="text" name="shipping_phone" value="{{ old('shipping_phone') }}" placeholder="081234567890" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all focus:bg-white" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-[#363230] mb-4">Notes (Optional)</h2>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all" placeholder="Any special instructions...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 sticky top-24">
                    <h2 class="text-lg font-semibold text-[#363230] mb-4">Total</h2>

                    @php
                        $subtotal = $cart->total;
                        $tax = round($subtotal * 0.11, 2);
                        $total = $subtotal + $tax;
                    @endphp

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-medium text-[#363230]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tax (11%)</span>
                            <span class="font-medium text-[#363230]">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-gray-100 pt-3 flex justify-between">
                            <span class="font-semibold text-[#363230]">Total</span>
                            <span class="text-xl font-semibold text-[#363230]">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#DF5E1D] text-white py-3 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors mt-6">
                        Place Order
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
