@extends('layouts.landing')

@section('title', $laptop->name)

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 min-h-screen py-12 lg:py-20">
        <!-- Breadcrumb -->
        <nav class="mb-10 lg:mb-14">
            <ol class="flex items-center gap-2 text-sm text-gray-500 font-medium">
                <li>
                    <a href="{{ route('landing.home') }}" class="hover:text-[#DF5E1D] transition-colors duration-200 flex items-center gap-1.5 rounded-md focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DF5E1D]/50">
                        <iconify-icon icon="solar:home-2-linear" class="text-base" style="stroke-width: 1.5;"></iconify-icon>
                        Beranda
                    </a>
                </li>
                <li class="flex items-center text-gray-300">
                    <iconify-icon icon="solar:alt-arrow-right-linear" style="stroke-width: 1.5;"></iconify-icon>
                </li>
                <li>
                    <a href="{{ route('landing.search') }}" class="hover:text-[#DF5E1D] transition-colors duration-200 rounded-md focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DF5E1D]/50">Produk</a>
                </li>
                <li class="flex items-center text-gray-300">
                    <iconify-icon icon="solar:alt-arrow-right-linear" style="stroke-width: 1.5;"></iconify-icon>
                </li>
                <li class="text-[#363230] truncate max-w-[200px] sm:max-w-none">{{ $laptop->name }}</li>
            </ol>
        </nav>

        <!-- Product Detail Section -->
        <div class="bg-white rounded-3xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden mb-16 lg:mb-24 transition-all duration-500 hover:shadow-[0_8px_40px_rgb(0,0,0,0.08)]">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 relative">

                <!-- Vertical Divider (LG+) -->
                <div class="hidden lg:block absolute top-0 bottom-0 left-1/2 w-px bg-gradient-to-b from-transparent via-gray-200/80 to-transparent"></div>

                <!-- Product Image Gallery (Left) -->
                <div class="p-8 lg:p-14 flex flex-col justify-center bg-gradient-to-br from-gray-50/50 to-white">

                    <!-- Lightbox Implementation using Checkbox Hack -->
                    <input type="checkbox" id="zoom-image" class="peer hidden">

                    <!-- Main Image Area -->
                    <label for="zoom-image" class="relative w-full aspect-square md:aspect-[4/3] lg:aspect-square flex items-center justify-center bg-white rounded-2xl border border-gray-200/50 shadow-sm overflow-hidden mb-6 group cursor-zoom-in transition-all duration-300 hover:border-gray-300 hover:shadow-md">
                        @if ($laptop->image_url)
                            <img src="{{ $laptop->image_url_full }}" alt="{{ $laptop->name }}" class="w-full h-full object-contain p-8 mix-blend-multiply transition-transform duration-700 ease-out group-hover:scale-110">
                        @else
                            <img src="https://placehold.co/800x600/363230/DF5E1D?text=ZLM" alt="{{ $laptop->name }}" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110">
                        @endif

                        <!-- Floating Category Badge -->
                        <div class="absolute top-5 left-5 bg-white/80 backdrop-blur-md border border-gray-200/50 text-[#363230] px-3 py-1.5 rounded-lg text-xs font-medium shadow-sm uppercase tracking-wide">
                            {{ $laptop->categories->first()?->name ?? 'General' }}
                        </div>

                        <!-- Hover Overlay Hint -->
                        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center pointer-events-none">
                            <div class="bg-white/90 backdrop-blur text-gray-800 p-3 rounded-full shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                <iconify-icon icon="solar:magnifer-zoom-in-linear" class="text-xl block" style="stroke-width: 1.5;"></iconify-icon>
                            </div>
                        </div>
                    </label>

                    <!-- Fullscreen Lightbox Overlay -->
                    <div class="fixed inset-0 z-50 bg-white/95 backdrop-blur-xl opacity-0 pointer-events-none peer-checked:opacity-100 peer-checked:pointer-events-auto transition-all duration-500 flex items-center justify-center">
                        <label for="zoom-image" class="absolute inset-0 cursor-zoom-out"></label>
                        <label for="zoom-image" class="absolute top-6 right-6 w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-200 hover:text-gray-900 cursor-pointer transition-colors z-10">
                            <iconify-icon icon="solar:close-circle-linear" class="text-2xl" style="stroke-width: 1.5;"></iconify-icon>
                        </label>
                        <div class="relative w-full max-w-5xl max-h-[90vh] p-4 scale-95 peer-checked:scale-100 transition-transform duration-500 ease-out z-0">
                            @if ($laptop->image_url)
                                <img src="{{ $laptop->image_url_full }}" alt="{{ $laptop->name }}" class="w-full h-full object-contain">
                            @else
                                <img src="https://placehold.co/1200x800/363230/DF5E1D?text=ZLM" alt="{{ $laptop->name }}" class="w-full h-full object-contain rounded-xl shadow-2xl">
                            @endif
                        </div>
                    </div>

                    <!-- Supplemental Images -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="aspect-square bg-white rounded-xl border border-gray-200/60 overflow-hidden cursor-pointer group relative">
                            <img src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&q=80&w=400" alt="Detail 1" class="w-full h-full object-cover opacity-70 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                            <div class="absolute inset-0 ring-2 ring-transparent group-hover:ring-[#DF5E1D]/20 inset-ring transition-all duration-300 rounded-xl"></div>
                        </div>
                        <div class="aspect-square bg-white rounded-xl border border-gray-200/60 overflow-hidden cursor-pointer group relative">
                            <img src="https://images.unsplash.com/photo-1625842268584-8f3296236761?auto=format&fit=crop&q=80&w=400" alt="Detail 2" class="w-full h-full object-cover opacity-70 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                            <div class="absolute inset-0 ring-2 ring-transparent group-hover:ring-[#DF5E1D]/20 inset-ring transition-all duration-300 rounded-xl"></div>
                        </div>
                        <div class="aspect-square bg-white rounded-xl border border-gray-200/60 overflow-hidden flex items-center justify-center bg-gradient-to-b from-gray-50 to-gray-100 cursor-pointer group transition-all duration-300 hover:border-[#DF5E1D]/30 hover:shadow-inner">
                            <div class="flex flex-col items-center gap-2 text-gray-400 group-hover:text-[#DF5E1D] transition-colors transform group-hover:scale-105 duration-300">
                                <iconify-icon icon="solar:gallery-linear" class="text-2xl" style="stroke-width: 1.5;"></iconify-icon>
                                <span class="text-xs font-medium">Lihat Semua</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Info (Right) -->
                <div class="p-8 lg:p-14 flex flex-col justify-center">

                    <!-- Header -->
                    <div class="mb-10">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-xs font-semibold text-[#DF5E1D] tracking-widest uppercase bg-[#DF5E1D]/10 px-3 py-1 rounded-full border border-[#DF5E1D]/20">
                                {{ $laptop->brand }}
                            </span>
                        </div>
                        <h1 class="text-3xl lg:text-4xl font-medium tracking-tight text-[#363230] mb-5 leading-tight">{{ $laptop->name }}</h1>
                        <p class="text-base text-gray-500 leading-relaxed">{{ $laptop->description }}</p>
                    </div>

                    <!-- Price & Stock -->
                    <div class="flex items-end justify-between mb-10 pb-10 border-b border-gray-100">
                        <div class="flex flex-col gap-1">
                            <span class="text-sm text-gray-400 font-medium tracking-wide uppercase">Total Harga</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-medium tracking-tight text-[#363230]">Rp {{ number_format($laptop->price, 0, ',', '.') }}</span>
                                {{-- <span class="text-sm text-gray-400">USD</span> --}}
                            </div>
                        </div>
                        <div class="text-right">
                            @if ($laptop->stock > 0)
                                <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 px-3.5 py-2 rounded-xl text-xs font-medium border border-emerald-200/60 shadow-sm">
                                    <iconify-icon icon="solar:check-circle-linear" class="text-sm" style="stroke-width: 1.5;"></iconify-icon>
                                    Stok Tersedia ({{ $laptop->stock }})
                                </div>
                            @else
                                <div class="inline-flex items-center gap-2 bg-rose-50 text-rose-600 px-3.5 py-2 rounded-xl text-xs font-medium border border-rose-200/60 shadow-sm">
                                    <iconify-icon icon="solar:close-circle-linear" class="text-sm" style="stroke-width: 1.5;"></iconify-icon>
                                    Stok Habis
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Variant Selection -->
                    @if ($laptop->variants->count() > 0)
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Variant</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($laptop->variants as $variant)
                                    <label class="variant-option cursor-pointer">
                                        <input type="radio" name="variant_id" value="{{ $variant->id }}" data-price="{{ $laptop->price + $variant->price_modifier }}" data-stock="{{ $variant->stock }}" class="peer hidden">
                                        <div class="px-4 py-2.5 rounded-xl border-2 border-gray-200 peer-checked:border-[#DF5E1D] peer-checked:bg-[#DF5E1D]/5 text-sm text-gray-600 peer-checked:text-[#DF5E1D] hover:border-gray-300 transition-all">
                                            <span class="font-medium">{{ $variant->name }}</span>
                                            @if ($variant->price_modifier > 0)
                                                <span class="text-xs ml-1">+Rp {{ number_format($variant->price_modifier, 0, ',', '.') }}</span>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <form method="POST" action="{{ route('cart.add') }}" class="flex flex-col sm:flex-row gap-4 mb-10">
                        @csrf
                        <input type="hidden" name="laptop_id" value="{{ $laptop->id }}">
                        <input type="hidden" name="variant_id" id="selectedVariantId" value="">
                        <input type="hidden" name="quantity" value="1">

                        <button type="submit" class="flex-1 bg-gradient-to-b from-[#DF5E1D] to-[#d05619] shadow-sm text-white py-4 px-6 rounded-2xl text-sm font-medium hover:from-[#d05619] hover:to-[#c45218] hover:shadow-md transition-all duration-300 flex items-center justify-center gap-2.5 disabled:opacity-50 disabled:cursor-not-allowed group" @if ($laptop->stock <= 0) disabled @endif>
                            <iconify-icon icon="solar:cart-large-2-linear" class="text-xl group-hover:-translate-y-0.5 group-hover:scale-110 transition-all duration-300"></iconify-icon>
                            <span id="addToCartText">Add to Cart</span>
                        </button>
                        <button type="button" onclick="toggleDetailWishlist('{{ $laptop->id }}')" data-wishlist-btn data-laptop-id="{{ $laptop->id }}" class="sm:w-auto w-full bg-white border border-gray-200 shadow-sm text-[#363230] py-4 px-6 rounded-2xl text-sm font-medium hover:bg-gray-50 hover:border-gray-300 hover:shadow transition-all duration-300 flex items-center justify-center gap-2.5 group">
                            <iconify-icon icon="solar:heart-linear" class="text-xl text-gray-400 group-hover:text-rose-500 group-hover:scale-110 transition-all duration-300" style="stroke-width: 1.5;"></iconify-icon>
                            <span>Save</span>
                        </button>
                        <button type="button" onclick="addToCompare('{{ $laptop->id }}')" class="sm:w-auto w-full bg-white border border-gray-200 shadow-sm text-[#363230] py-4 px-6 rounded-2xl text-sm font-medium hover:bg-gray-50 hover:border-gray-300 hover:shadow transition-all duration-300 flex items-center justify-center gap-2.5 group">
                            <iconify-icon icon="solar:scale-linear" class="text-xl text-gray-400 group-hover:text-blue-500 group-hover:scale-110 transition-all duration-300" style="stroke-width: 1.5;"></iconify-icon>
                            <span>Compare</span>
                        </button>
                    </form>

                    <script>
                    document.querySelectorAll('.variant-option input').forEach(radio => {
                        radio.addEventListener('change', function() {
                            document.getElementById('selectedVariantId').value = this.value;
                            const price = this.dataset.price;
                            const stock = this.dataset.stock;
                            document.querySelector('.text-4xl').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
                        });
                    });
                    </script>

                    <!-- Additional Details / Share -->
                    <div class="flex items-center justify-between mt-auto">
                        <div class="flex items-center gap-3">
                            <div class="flex -space-x-2">
                                <img class="w-8 h-8 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img=1" alt="User avatar">
                                <img class="w-8 h-8 rounded-full border-2 border-white" src="https://i.pravatar.cc/100?img=2" alt="User avatar">
                                <div class="w-8 h-8 rounded-full border-2 border-white bg-gray-100 flex items-center justify-center text-[10px] font-medium text-gray-500">+12</div>
                            </div>
                            <span class="text-xs text-gray-500 font-medium">Dilirik Banyak Orang</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <button class="w-10 h-10 rounded-full bg-white border border-gray-200 shadow-sm flex items-center justify-center text-gray-500 hover:text-[#DF5E1D] hover:border-[#DF5E1D]/30 hover:bg-[#DF5E1D]/5 transition-all duration-300 hover:-translate-y-0.5">
                                <iconify-icon icon="solar:share-circle-linear" class="text-lg" style="stroke-width: 1.5;"></iconify-icon>
                            </button>
                            <button class="w-10 h-10 rounded-full bg-white border border-gray-200 shadow-sm flex items-center justify-center text-gray-500 hover:text-[#DF5E1D] hover:border-[#DF5E1D]/30 hover:bg-[#DF5E1D]/5 transition-all duration-300 hover:-translate-y-0.5">
                                <iconify-icon icon="solar:link-linear" class="text-lg" style="stroke-width: 1.5;"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Technical Specifications Table Section -->
        <div class="mb-16 lg:mb-24 max-w-4xl mx-auto">
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white border border-gray-200/80 shadow-sm mb-4">
                    <iconify-icon icon="solar:settings-minimalistic-linear" class="text-2xl text-[#DF5E1D]" style="stroke-width: 1.5;"></iconify-icon>
                </div>
                <h2 class="text-2xl lg:text-3xl font-medium tracking-tight text-[#363230] mb-3">Spesifikasi Teknis</h2>
                <p class="text-sm text-gray-500 max-w-lg mx-auto">Semua yang perlu Anda ketahui tentang {{ $laptop->name }}, ada di bawah ini.</p>
            </div>

            <div class="bg-white rounded-3xl border border-gray-200/60 shadow-sm overflow-hidden transition-all hover:shadow-md duration-300">

                        @if ($laptop->battery_life)

                        @endif
                        @if ($laptop->weight)

                        @endif
                    <table class="w-full text-sm text-left">
                    <tbody class="divide-y divide-gray-100">
                        <tr class="group hover:bg-gray-50/50 transition-colors duration-200">
                            <th scope="row" class="px-6 py-5 font-medium text-gray-600 w-1/3 sm:w-1/4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:bg-white group-hover:border-gray-200 transition-colors duration-300">
                                        <iconify-icon icon="solar:cpu-linear" class="text-lg text-gray-400 group-hover:text-[#DF5E1D] transition-colors duration-300" style="stroke-width: 1.5;"></iconify-icon>
                                    </div>
                                    Prosesor
                                </div>
                            </th>
                            <td class="px-6 py-5 text-[#363230]">{{ $laptop->processor }}</td>
                        </tr>
                        <tr class="group hover:bg-gray-50/50 transition-colors duration-200">
                            <th scope="row" class="px-6 py-5 font-medium text-gray-600">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:bg-white group-hover:border-gray-200 transition-colors duration-300">
                                        <iconify-icon icon="solar:ram-linear" class="text-lg text-gray-400 group-hover:text-[#DF5E1D] transition-colors duration-300" style="stroke-width: 1.5;"></iconify-icon>
                                    </div>
                                    Memori (RAM)
                                </div>
                            </th>
                            <td class="px-6 py-5 text-[#363230]">{{ $laptop->ram }}</td>
                        </tr>
                        <tr class="group hover:bg-gray-50/50 transition-colors duration-200">
                            <th scope="row" class="px-6 py-5 font-medium text-gray-600">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:bg-white group-hover:border-gray-200 transition-colors duration-300">
                                        <iconify-icon icon="solar:database-linear" class="text-lg text-gray-400 group-hover:text-[#DF5E1D] transition-colors duration-300" style="stroke-width: 1.5;"></iconify-icon>
                                    </div>
                                    Penyimpanan
                                </div>
                            </th>
                            <td class="px-6 py-5 text-[#363230]">{{ $laptop->storage }}</td>
                        </tr>
                        <tr class="group hover:bg-gray-50/50 transition-colors duration-200">
                            <th scope="row" class="px-6 py-5 font-medium text-gray-600">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:bg-white group-hover:border-gray-200 transition-colors duration-300">
                                        <iconify-icon icon="solar:monitor-camera-linear" class="text-lg text-gray-400 group-hover:text-[#DF5E1D] transition-colors duration-300" style="stroke-width: 1.5;"></iconify-icon>
                                    </div>
                                    Grafis
                                </div>
                            </th>
                            <td class="px-6 py-5 text-[#363230]">{{ $laptop->graphics }}</td>
                        </tr>
                        <tr class="group hover:bg-gray-50/50 transition-colors duration-200">
                            <th scope="row" class="px-6 py-5 font-medium text-gray-600">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:bg-white group-hover:border-gray-200 transition-colors duration-300">
                                        <iconify-icon icon="solar:monitor-linear" class="text-lg text-gray-400 group-hover:text-[#DF5E1D] transition-colors duration-300" style="stroke-width: 1.5;"></iconify-icon>
                                    </div>
                                    Layar
                                </div>
                            </th>
                            <td class="px-6 py-5 text-[#363230]">{{ $laptop->display }}</td>
                        </tr><tr class="group hover:bg-gray-50/50 transition-colors duration-200">
                            <th scope="row" class="px-6 py-5 font-medium text-gray-600">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:bg-white group-hover:border-gray-200 transition-colors duration-300">
                                        <iconify-icon icon="solar:battery-charge-linear" class="text-lg text-gray-400 group-hover:text-[#DF5E1D] transition-colors duration-300" style="stroke-width: 1.5;"></iconify-icon>
                                    </div>
                                    Daya Baterai
                                </div>
                            </th>
                                <td class="px-6 py-5 text-[#363230]">{{ $laptop->battery_life }}</td>
                        </tr><tr class="group hover:bg-gray-50/50 transition-colors duration-200">
                            <th scope="row" class="px-6 py-5 font-medium text-gray-600">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:bg-white group-hover:border-gray-200 transition-colors duration-300">
                                        <iconify-icon icon="solar:case-minimalistic-linear" class="text-lg text-gray-400 group-hover:text-[#DF5E1D] transition-colors duration-300" style="stroke-width: 1.5;"></iconify-icon>
                                    </div>
                                    Berat
                                </div>
                            </th>
                                <td class="px-6 py-5 text-[#363230]">{{ $laptop->weight }} kg</td>
                        </tr></tbody>
                </table>
            </div>
        </div>

        <!-- Kelebihan & Kekurangan Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 my-16">
            <div class="bg-emerald-50/50 rounded-2xl border border-emerald-200/60 p-6 lg:p-8">
                <h3 class="text-lg font-semibold text-emerald-800 mb-4 flex items-center gap-2.5">
                    <iconify-icon icon="solar:like-linear" class="text-xl text-emerald-500"></iconify-icon>
                    Kelebihan
                </h3>
                @if ($laptop->kelebihan)
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! $laptop->kelebihan !!}
                </div>
                @else
                <p class="text-sm text-gray-400 italic">Belum ada informasi kelebihan.</p>
                @endif
            </div>
            <div class="bg-rose-50/50 rounded-2xl border border-rose-200/60 p-6 lg:p-8">
                <h3 class="text-lg font-semibold text-rose-800 mb-4 flex items-center gap-2.5">
                    <iconify-icon icon="solar:dislike-linear" class="text-xl text-rose-500"></iconify-icon>
                    Kekurangan
                </h3>
                @if ($laptop->kekurangan)
                <div class="prose prose-sm max-w-none text-gray-700">
                    {!! $laptop->kekurangan !!}
                </div>
                @else
                <p class="text-sm text-gray-400 italic">Belum ada informasi kekurangan.</p>
                @endif
            </div>
        </div>

        <!-- Similar Laptops Section -->
        @if ($similar->count() > 0)
            <div class="border-t border-gray-200/60 pt-16 lg:pt-20">
                <div class="flex items-end justify-between mb-10">
                    <div>
                        <h2 class="text-2xl font-medium tracking-tight text-[#363230] mb-2">Model Serupa</h2>
                        <p class="text-sm text-gray-500">Jelajahi opsi lain di kategori ini.</p>
                    </div>
                    <a href="{{ route('landing.search') }}" class="text-sm font-medium text-[#363230] hover:text-[#DF5E1D] transition-colors duration-300 flex items-center gap-1.5 group pb-1 border-b border-transparent hover:border-[#DF5E1D]/30">
                        Lihat semua
                        <iconify-icon icon="solar:arrow-right-linear" class="transform group-hover:translate-x-1 transition-transform duration-300" style="stroke-width: 1.5;"></iconify-icon>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                    @foreach ($similar as $similarProduct)
                        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:-translate-y-1.5 transition-all duration-400 overflow-hidden flex flex-col group relative">

                            <!-- Card Image -->
                            <a href="{{ route('landing.detail', $similarProduct->id) }}" class="relative h-56 bg-gradient-to-b from-gray-50 to-white overflow-hidden flex items-center justify-center border-b border-gray-100">
                                @if ($similarProduct->image_url)
                                    <img src="{{ $similarProduct->image_url_full }}" alt="{{ $similarProduct->name }}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-110 transition-transform duration-700 ease-out">
                                @else
                                    <img src="https://placehold.co/600x400/363230/DF5E1D?text=ZLM" alt="{{ $similarProduct->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
                                @endif

                                <!-- Hover Overlay & Badge -->
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors duration-300"></div>
                                <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-md border border-gray-200/80 text-[#363230] px-2.5 py-1 rounded-md text-[10px] font-semibold shadow-sm tracking-wide uppercase">
                                    {{ $similarProduct->categories->first()?->name ?? 'General' }}
                                </div>
                            </a>

                            <!-- Card Content -->
                            <div class="p-6 flex flex-col flex-grow">
                                <p class="text-[11px] text-gray-400 font-semibold tracking-widest uppercase mb-2">{{ $similarProduct->brand }}</p>
                                <h3 class="text-base font-medium text-[#363230] mb-4 line-clamp-2 leading-snug group-hover:text-[#DF5E1D] transition-colors duration-300">
                                    <a href="{{ route('landing.detail', $similarProduct->id) }}" class="focus:outline-none">
                                        <span class="absolute inset-0" aria-hidden="true"></span>
                                        {{ $similarProduct->name }}
                                    </a>
                                </h3>

                                <div class="mb-6 space-y-3 flex-grow">
                                    <div class="flex items-center gap-2.5 text-xs text-gray-500 bg-gray-50/50 py-1.5 px-2 rounded-md">
                                        <iconify-icon icon="solar:cpu-linear" class="text-gray-400 text-sm"></iconify-icon>
                                        <span class="truncate font-medium">{{ $similarProduct->processor }}</span>
                                    </div>
                                    <div class="flex items-center gap-2.5 text-xs text-gray-500 bg-gray-50/50 py-1.5 px-2 rounded-md">
                                        <iconify-icon icon="solar:ram-linear" class="text-gray-400 text-sm"></iconify-icon>
                                        <span class="truncate font-medium">{{ $similarProduct->ram }} • {{ $similarProduct->storage }}</span>
                                    </div>
                                </div>

                                <div class="flex justify-between items-end pt-5 border-t border-gray-100 mt-auto relative z-10">
                                    <div>
                                        <span class="text-[10px] text-gray-400 font-medium uppercase tracking-wider block mb-0.5">Harga</span>
                                        <p class="text-lg font-medium tracking-tight text-[#363230]">
                                            Rp {{ number_format($similarProduct->price, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="w-10 h-10 rounded-xl bg-white border border-gray-200 text-gray-400 flex items-center justify-center group-hover:bg-[#DF5E1D] group-hover:text-white group-hover:border-[#DF5E1D] group-hover:shadow-md transition-all duration-300 shadow-sm" title="View Details">
                                        <iconify-icon icon="solar:arrow-right-up-linear" class="text-lg"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

{{-- Reviews Section --}}
<div class="border-t border-gray-200/60 pt-16 lg:pt-20 mb-16">
    <h2 class="text-2xl font-medium tracking-tight text-[#363230] mb-10">Customer Reviews</h2>

    @if ($reviews->count() > 0)
    <div class="space-y-6">
        @foreach ($reviews as $review)
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-sm font-medium text-gray-600 shrink-0">
                    {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-sm font-semibold text-[#363230]">{{ $review->user->name ?? 'Anonymous' }}</p>
                        <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex text-amber-400 text-sm mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <iconify-icon icon="{{ $i <= $review->rating ? 'solar:star-bold' : 'solar:star-linear' }}"></iconify-icon>
                        @endfor
                    </div>
                    @if ($review->comment)
                    <p class="text-sm text-gray-600">{{ $review->comment }}</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @if ($reviews->hasPages())
    <div class="mt-8">{{ $reviews->links() }}</div>
    @endif
    @else
    <div class="text-center py-12 bg-gray-50 rounded-2xl border border-gray-200/60">
        <iconify-icon icon="solar:chat-round-dots-linear" class="text-4xl text-gray-300 mb-3"></iconify-icon>
        <p class="text-sm text-gray-500">Belum ada review untuk produk ini.</p>
    </div>
    @endif
</div>

{{-- Review Form --}}
@auth
<div class="border-t border-gray-200/60 pt-16 lg:pt-20 mb-16">
    <h2 class="text-2xl font-medium tracking-tight text-[#363230] mb-6">Write a Review</h2>
    <form method="POST" action="{{ route('reviews.store', $laptop) }}" class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
            <div class="flex gap-1 text-2xl" id="rating-stars">
                @for ($i = 1; $i <= 5; $i++)
                    <button type="button" onclick="setRating({{ $i }})" class="text-gray-300 hover:text-amber-400 transition-colors rating-star" data-value="{{ $i }}">
                        <iconify-icon icon="solar:star-linear"></iconify-icon>
                    </button>
                @endfor
            </div>
            <input type="hidden" name="rating" id="rating-input" value="5">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Review</label>
            <textarea name="comment" rows="4" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all" placeholder="Share your experience with this product..."></textarea>
        </div>
        <button type="submit" class="bg-[#DF5E1D] text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors">
            Submit Review
        </button>
    </form>
</div>

<script>
function setRating(val) {
    document.getElementById('rating-input').value = val;
    document.querySelectorAll('.rating-star').forEach(function(star, i) {
        var icon = star.querySelector('iconify-icon');
        if (i < val) {
            icon.setAttribute('icon', 'solar:star-bold');
            star.classList.add('text-amber-400');
            star.classList.remove('text-gray-300');
        } else {
            icon.setAttribute('icon', 'solar:star-linear');
            star.classList.remove('text-amber-400');
            star.classList.add('text-gray-300');
        }
    });
}
</script>
@else
<div class="border-t border-gray-200/60 pt-16 lg:pt-20 mb-16 text-center">
    <p class="text-sm text-gray-500">
        <a href="{{ route('login') }}" class="text-[#DF5E1D] hover:underline font-medium">Login</a> to write a review.
    </p>
</div>
@endauth
</div>

<script>
    function toggleDetailWishlist(id) {
        const wishlist = getWishlist();
        const index = wishlist.indexOf(id);
        if (index > -1) {
            wishlist.splice(index, 1);
        } else {
            wishlist.push(id);
        }
        saveWishlist(wishlist);
        updateWishlistButtons();
    }

    function getWishlist() {
        try {
            return JSON.parse(localStorage.getItem('wishlistLaptops')) || [];
        } catch (e) {
            return [];
        }
    }

    function saveWishlist(list) {
        localStorage.setItem('wishlistLaptops', JSON.stringify(list));
        updateWishlistButtons();
    }

    function updateWishlistButtons() {
        const wishlist = getWishlist();
        document.querySelectorAll('[data-wishlist-btn]').forEach(btn => {
            const id = btn.dataset.laptopId ? parseInt(btn.dataset.laptopId) : null;
            if (id && wishlist.includes(id)) {
                btn.classList.add('text-red-500', 'bg-red-50', 'border-red-200');
                btn.classList.remove('text-gray-600');
                const icon = btn.querySelector('iconify-icon');
                if (icon) icon.setAttribute('icon', 'solar:heart-bold');
            } else {
                btn.classList.remove('text-red-500', 'bg-red-50', 'border-red-200');
                btn.classList.add('text-gray-600');
                const icon = btn.querySelector('iconify-icon');
                if (icon) icon.setAttribute('icon', 'solar:heart-linear');
            }
        });
    }

    // ===== Compare Functions (Session-based) =====
    function addToCompare(laptopId) {
        fetch('{{ route('compare.add') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ laptop_id: laptopId }),
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                showToast(res.message, 'success');
                updateCompareBadge();
            } else {
                showToast(res.message, 'info');
            }
        })
        .catch(function() {
            showToast('Gagal menambahkan ke perbandingan', 'error');
        });
    }

    function clearCompare() {
        fetch('{{ route('compare.clear') }}', {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                location.reload();
            }
        });
    }

    function showToast(message, type) {
        var toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 px-4 py-3 rounded-lg text-white text-sm font-medium shadow-lg z-50 transition-all duration-300 ' + (type === 'success' ? 'bg-emerald-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500');
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(function() {
            toast.style.opacity = '0';
            setTimeout(function() { toast.remove(); }, 300);
        }, 3000);
    }

    function updateCompareBadge() {
        fetch('{{ route('compare.ids') }}')
        .then(function(r) { return r.json(); })
        .then(function(res) {
            var count = res.ids ? res.ids.length : 0;
            var badge = document.querySelector('.compare-count');
            if (badge) {
                badge.textContent = count;
                badge.style.display = count > 0 ? 'flex' : 'none';
            }
            // Update floating compare widget
            var floatingWidget = document.getElementById('floating-compare');
            var compareBadge = document.getElementById('compare-badge');
            var compareCount = document.getElementById('compare-count');
            if (floatingWidget) {
                if (count > 0) {
                    floatingWidget.classList.remove('hidden');
                } else {
                    floatingWidget.classList.add('hidden');
                }
                if (compareBadge) compareBadge.textContent = count;
                if (compareCount) compareCount.textContent = count;
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateWishlistButtons();
        updateCompareBadge();
    });
</script>
@endsection
