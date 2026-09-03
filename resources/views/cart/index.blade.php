@extends('layouts.landing')

@section('title', 'Shopping Cart')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20">
    <h1 class="text-3xl font-semibold tracking-tight text-[#363230] mb-8">Shopping Cart</h1>

    @if ($cart->items->count() > 0)
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
            <div class="divide-y divide-gray-100">
                @foreach ($cart->items as $item)
                    <div class="flex items-center gap-4 p-4 sm:p-6">
                        <div class="w-20 h-20 bg-gray-50 rounded-xl flex items-center justify-center p-2 border border-gray-100">
                            @if ($item->laptop->image_url)
                                <img src="{{ $item->laptop->image_url_full }}" alt="" class="w-full h-full object-contain mix-blend-multiply">
                            @else
                                <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-3xl text-gray-300"></iconify-icon>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-[#363230]">{{ $item->laptop->name }}</p>
                            @if ($item->addon)
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-[#166534] bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md mt-1">
                                    <iconify-icon icon="solar:gift-bold" class="text-xs"></iconify-icon>
                                    Bundle: {{ $item->addon->name }} (+Rp {{ number_format($item->addon_price, 0, ',', '.') }})
                                </span>
                            @endif
                            <p class="text-xs text-gray-400 mt-0.5">{{ $item->laptop->brand }} &bull; {{ $item->laptop->processor }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <form method="POST" action="{{ route('cart.update', $item) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="10" class="w-16 px-2 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-center focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20">
                            </form>

                            <p class="text-base font-medium text-[#363230] w-24 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>

                            <form method="POST" action="{{ route('cart.remove', $item) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-1">
                                    <iconify-icon icon="solar:trash-bin-trash-linear" class="text-lg"></iconify-icon>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="p-6 bg-gray-50/50 border-t border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-base font-medium text-[#363230]">Total</span>
                    <span class="text-2xl font-semibold text-[#363230]">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('landing.checkout') }}" class="block w-full bg-[#DF5E1D] text-white py-3 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors text-center">
                    Proceed to Checkout
                </a>
            </div>
        </div>
    @else
        <div class="text-center py-20">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <iconify-icon icon="solar:cart-large-2-linear" class="text-3xl text-gray-300"></iconify-icon>
            </div>
            <h2 class="text-xl font-semibold text-[#363230] mb-2">Your cart is empty</h2>
            <p class="text-gray-500 mb-6">Looks like you haven't added any laptops yet.</p>
            <a href="{{ route('landing.search') }}" class="inline-flex bg-[#DF5E1D] text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors">
                Browse Laptops
            </a>
        </div>
    @endif
</div>
@endsection
