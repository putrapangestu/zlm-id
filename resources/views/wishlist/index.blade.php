@extends('layouts.landing')

@section('title', 'My Wishlist')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20">
    <h1 class="text-3xl font-semibold tracking-tight text-[#363230] mb-8">My Wishlist</h1>

    @if ($items->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($items as $item)
                <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group relative">
                    <div class="relative h-44 bg-gray-50 flex items-center justify-center p-4 border-b border-gray-100">
                        @if ($item->laptop->image_url)
                            <img src="{{ $item->laptop->image_url_full }}" alt="" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                        @else
                            <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-4xl text-gray-300"></iconify-icon>
                        @endif
                        <button onclick="toggleWishlist('{{ $item->laptop_id }}')" class="absolute top-3 right-3 w-8 h-8 bg-white rounded-lg border border-gray-200 flex items-center justify-center text-red-500 shadow-sm hover:bg-red-50 transition-colors">
                            <iconify-icon icon="solar:heart-bold" class="text-lg"></iconify-icon>
                        </button>
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-gray-400 font-medium uppercase mb-1">{{ $item->laptop->brand }}</p>
                        <h3 class="font-medium text-[#363230] mb-2">{{ $item->laptop->name }}</h3>
                        <p class="text-lg font-semibold text-[#363230]">Rp {{ number_format($item->laptop->price, 0, ',', '.') }}</p>
                        <a href="{{ route('landing.detail', $item->laptop_id) }}" class="mt-3 block w-full text-center bg-[#DF5E1D] text-white py-2 rounded-lg text-sm font-medium hover:bg-[#c45218] transition-colors">
                            View Details
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-20">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <iconify-icon icon="solar:heart-linear" class="text-3xl text-gray-300"></iconify-icon>
            </div>
            <h2 class="text-xl font-semibold text-[#363230] mb-2">Your wishlist is empty</h2>
            <p class="text-gray-500 mb-6">Save your favorite laptops for later.</p>
            <a href="{{ route('landing.search') }}" class="inline-flex bg-[#DF5E1D] text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors">
                Browse Laptops
            </a>
        </div>
    @endif
</div>
@endsection
