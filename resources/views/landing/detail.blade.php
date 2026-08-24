@extends('layouts.landing')

@section('title', $laptop->name)

@section('content')

<div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 min-h-screen py-12 lg:py-20">
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
                            <img id="main-product-image" src="{{ $laptop->image_url_full }}" alt="{{ $laptop->name }}" class="w-full h-full object-contain p-8 mix-blend-multiply transition-transform duration-700 ease-out group-hover:scale-110">
                        @else
                            <img id="main-product-image" src="https://placehold.co/800x600/363230/DF5E1D?text=ZLM" alt="{{ $laptop->name }}" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110">
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
                                <img id="lightbox-image" src="{{ $laptop->image_url_full }}" alt="{{ $laptop->name }}" class="w-full h-full object-contain">
                            @else
                                <img id="lightbox-image" src="https://placehold.co/1200x800/363230/DF5E1D?text=ZLM" alt="{{ $laptop->name }}" class="w-full h-full object-contain rounded-xl shadow-2xl">
                            @endif
                        </div>
                    </div>

                    <!-- Supplemental Images -->
                    <div class="grid grid-cols-3 gap-4">
                        @forelse ($laptop->images as $image)
                            <div class="aspect-square bg-white rounded-xl border border-gray-200/60 overflow-hidden cursor-pointer group relative">
                                <img src="{{ Storage::url($image->image_url) }}" alt="{{ $image->caption ?? 'Product image' }}" 
                                     class="w-full h-full object-contain p-2 group-hover:scale-105 transition-all duration-500">
                            </div>
                        @empty
                            {{-- placeholder jika tidak ada images --}}
                            <div class="aspect-square bg-white rounded-xl border border-gray-200/60 overflow-hidden flex items-center justify-center bg-gradient-to-b from-gray-50 to-gray-100">
                                <div class="flex flex-col items-center gap-2 text-gray-400">
                                    <iconify-icon icon="solar:gallery-linear" class="text-2xl" style="stroke-width: 1.5;"></iconify-icon>
                                    <span class="text-xs font-medium">Belum ada gambar</span>
                                </div>
                            </div>
                        @endforelse
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
                                <span id="product-price" class="text-4xl font-medium tracking-tight text-[#363230]">Rp {{ number_format($laptop->price, 0, ',', '.') }}</span>
                                {{-- <span class="text-sm text-gray-400">USD</span> --}}
                            </div>
                        </div>
                        <div id="stock-badge" class="text-right">
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
                                        <input type="radio" name="variant_id" 
                                               value="{{ $variant->id }}" 
                                               data-price="{{ $laptop->price + $variant->price_modifier }}"
                                               data-stock="{{ $variant->stock }}"
                                               data-image="{{ $variant->image_url_full ?? $laptop->image_url_full }}"
                                               data-ram="{{ $variant->ram ?? $laptop->ram }}"
                                               data-storage="{{ $variant->storage ?? $laptop->storage }}"
                                               data-graphics="{{ $variant->graphics ?? $laptop->graphics }}"
                                               data-display="{{ $variant->display ?? $laptop->display }}"
                                               data-weight="{{ $variant->weight ?? $laptop->weight }}"
                                               data-battery="{{ $variant->battery_life ?? $laptop->battery_life }}"
                                               class="peer hidden">
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

                        <button id="addToCartBtn" type="submit" class="flex-1 bg-gradient-to-b from-[#DF5E1D] to-[#d05619] shadow-sm text-white py-4 px-6 rounded-2xl text-sm font-medium hover:from-[#d05619] hover:to-[#c45218] hover:shadow-md transition-all duration-300 flex items-center justify-center gap-2.5 disabled:opacity-50 disabled:cursor-not-allowed group" @if ($laptop->stock <= 0) disabled @endif>
                            <iconify-icon icon="solar:cart-large-2-linear" class="text-xl group-hover:-translate-y-0.5 group-hover:scale-110 transition-all duration-300"></iconify-icon>
                            <span id="addToCartText">Add to Cart</span>
                        </button>
                        <button type="button" onclick="toggleDetailWishlist('{{ $laptop->id }}')" data-wishlist-btn data-laptop-id="{{ $laptop->id }}" class="sm:w-auto w-full bg-white border border-gray-200 shadow-sm text-[#363230] py-4 px-6 rounded-2xl text-sm font-medium hover:bg-gray-50 hover:border-gray-300 hover:shadow transition-all duration-300 flex items-center justify-center gap-2.5 group">
                            <iconify-icon icon="solar:heart-linear" class="text-xl text-gray-400 group-hover:text-rose-500 group-hover:scale-110 transition-all duration-300" style="stroke-width: 1.5;"></iconify-icon>
                            <span>Save</span>
                        </button>

                    </form>

                    <script>
                    document.querySelectorAll('.variant-option input').forEach(radio => {
                        radio.addEventListener('change', function() {
                            // 1. Update hidden input for cart form
                            const variantInput = document.getElementById('selectedVariantId');
                            if (variantInput) variantInput.value = this.value;
                            
                            // 2. Update price
                            const priceEl = document.getElementById('product-price');
                            if (priceEl && this.dataset.price) {
                                priceEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(this.dataset.price);
                            }
                            
                            // 3. Update main image
                            const mainImage = document.getElementById('main-product-image');
                            if (mainImage && this.dataset.image) {
                                mainImage.src = this.dataset.image;
                                // Also update lightbox image
                                const lightboxImage = document.getElementById('lightbox-image');
                                if (lightboxImage) lightboxImage.src = this.dataset.image;
                            }
                            
                            // 4. Update specs table
                            const specFields = [
                                { key: 'ram', selector: '.spec-ram' },
                                { key: 'storage', selector: '.spec-storage' },
                                { key: 'graphics', selector: '.spec-graphics' },
                                { key: 'display', selector: '.spec-display' },
                                { key: 'battery', selector: '.spec-battery' },
                                { key: 'weight', selector: '.spec-weight' },
                            ];
                            
                            specFields.forEach(function(field) {
                                const el = document.querySelector(field.selector);
                                if (el && this.dataset[field.key]) {
                                    let value = this.dataset[field.key];
                                    if (field.key === 'weight') value = value + ' kg';
                                    el.textContent = value;
                                }
                            }.bind(this));
                            
                            // 5. Update stock badge
                            const stock = parseInt(this.dataset.stock);
                            const stockBadge = document.getElementById('stock-badge');
                            if (stockBadge) {
                                if (stock > 0) {
                                    stockBadge.innerHTML = '<div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 px-3.5 py-2 rounded-xl text-xs font-medium border border-emerald-200/60 shadow-sm">' +
                                        '<iconify-icon icon="solar:check-circle-linear" class="text-sm" style="stroke-width: 1.5;"></iconify-icon>' +
                                        'Stok Tersedia (' + stock + ')</div>';
                                } else {
                                    stockBadge.innerHTML = '<div class="inline-flex items-center gap-2 bg-rose-50 text-rose-600 px-3.5 py-2 rounded-xl text-xs font-medium border border-rose-200/60 shadow-sm">' +
                                        '<iconify-icon icon="solar:close-circle-linear" class="text-sm" style="stroke-width: 1.5;"></iconify-icon>' +
                                        'Stok Habis</div>';
                                }
                            }
                            
                            // 6. Update add-to-cart button state
                            const cartBtn = document.getElementById('addToCartBtn');
                            if (cartBtn) {
                                cartBtn.disabled = stock <= 0;
                            }
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
                            <button onclick="shareProduct()" title="Bagikan produk ini" class="w-10 h-10 rounded-full bg-white border border-gray-200 shadow-sm flex items-center justify-center text-gray-500 hover:text-[#DF5E1D] hover:border-[#DF5E1D]/30 hover:bg-[#DF5E1D]/5 transition-all duration-300 hover:-translate-y-0.5">
                                <iconify-icon icon="solar:share-circle-linear" class="text-lg" style="stroke-width: 1.5;"></iconify-icon>
                            </button>
                            <button onclick="copyProductLink()" title="Salin link produk" class="w-10 h-10 rounded-full bg-white border border-gray-200 shadow-sm flex items-center justify-center text-gray-500 hover:text-[#DF5E1D] hover:border-[#DF5E1D]/30 hover:bg-[#DF5E1D]/5 transition-all duration-300 hover:-translate-y-0.5">
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

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 lg:gap-6">
                <!-- Processor -->
                <div class="bg-white p-5 lg:p-6 rounded-2xl border border-gray-200/60 shadow-sm hover:shadow-md hover:border-[#DF5E1D]/40 transition-all duration-300 group flex flex-col justify-between">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center mb-4 group-hover:bg-[#DF5E1D]/10 group-hover:border-[#DF5E1D]/20 transition-colors duration-300">
                        <iconify-icon icon="solar:cpu-linear" class="text-xl text-gray-400 group-hover:text-[#DF5E1D] transition-colors duration-300" style="stroke-width: 1.5;"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mb-1.5">Prosesor</p>
                        <p class="text-sm font-medium text-[#363230] leading-snug">{{ $laptop->processor }}</p>
                    </div>
                </div>

                <!-- RAM -->
                <div class="bg-white p-5 lg:p-6 rounded-2xl border border-gray-200/60 shadow-sm hover:shadow-md hover:border-[#DF5E1D]/40 transition-all duration-300 group flex flex-col justify-between">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center mb-4 group-hover:bg-[#DF5E1D]/10 group-hover:border-[#DF5E1D]/20 transition-colors duration-300">
                        <iconify-icon icon="solar:ram-linear" class="text-xl text-gray-400 group-hover:text-[#DF5E1D] transition-colors duration-300" style="stroke-width: 1.5;"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mb-1.5">Memori (RAM)</p>
                        <p class="text-sm font-medium text-[#363230] leading-snug spec-ram">{{ $laptop->ram }}</p>
                    </div>
                </div>

                <!-- Storage -->
                <div class="bg-white p-5 lg:p-6 rounded-2xl border border-gray-200/60 shadow-sm hover:shadow-md hover:border-[#DF5E1D]/40 transition-all duration-300 group flex flex-col justify-between">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center mb-4 group-hover:bg-[#DF5E1D]/10 group-hover:border-[#DF5E1D]/20 transition-colors duration-300">
                        <iconify-icon icon="solar:database-linear" class="text-xl text-gray-400 group-hover:text-[#DF5E1D] transition-colors duration-300" style="stroke-width: 1.5;"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mb-1.5">Penyimpanan</p>
                        <p class="text-sm font-medium text-[#363230] leading-snug spec-storage">{{ $laptop->storage }}</p>
                    </div>
                </div>

                <!-- Graphics -->
                <div class="bg-white p-5 lg:p-6 rounded-2xl border border-gray-200/60 shadow-sm hover:shadow-md hover:border-[#DF5E1D]/40 transition-all duration-300 group flex flex-col justify-between">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center mb-4 group-hover:bg-[#DF5E1D]/10 group-hover:border-[#DF5E1D]/20 transition-colors duration-300">
                        <iconify-icon icon="solar:monitor-camera-linear" class="text-xl text-gray-400 group-hover:text-[#DF5E1D] transition-colors duration-300" style="stroke-width: 1.5;"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mb-1.5">Grafis</p>
                        <p class="text-sm font-medium text-[#363230] leading-snug spec-graphics">{{ $laptop->graphics }}</p>
                    </div>
                </div>

                <!-- Display -->
                <div class="bg-white p-5 lg:p-6 rounded-2xl border border-gray-200/60 shadow-sm hover:shadow-md hover:border-[#DF5E1D]/40 transition-all duration-300 group flex flex-col justify-between">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center mb-4 group-hover:bg-[#DF5E1D]/10 group-hover:border-[#DF5E1D]/20 transition-colors duration-300">
                        <iconify-icon icon="solar:monitor-linear" class="text-xl text-gray-400 group-hover:text-[#DF5E1D] transition-colors duration-300" style="stroke-width: 1.5;"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mb-1.5">Layar</p>
                        <p class="text-sm font-medium text-[#363230] leading-snug spec-display">{{ $laptop->display }}</p>
                    </div>
                </div>

                <!-- Battery -->
                <div class="bg-white p-5 lg:p-6 rounded-2xl border border-gray-200/60 shadow-sm hover:shadow-md hover:border-[#DF5E1D]/40 transition-all duration-300 group flex flex-col justify-between">
                    <div class="w-10 h-10 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center mb-4 group-hover:bg-[#DF5E1D]/10 group-hover:border-[#DF5E1D]/20 transition-colors duration-300">
                        <iconify-icon icon="solar:battery-charge-linear" class="text-xl text-gray-400 group-hover:text-[#DF5E1D] transition-colors duration-300" style="stroke-width: 1.5;"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider mb-1.5">Daya Baterai</p>
                        <p class="text-sm font-medium text-[#363230] leading-snug spec-battery">{{ $laptop->battery_life ?: 'Tergantung Pemakaian' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kelebihan & Kekurangan Section -->
        <div class="bg-white rounded-3xl border border-gray-200/60 shadow-sm overflow-hidden mb-16 lg:mb-24">
            <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-gray-100">
                <!-- Kelebihan -->
                <div class="p-8 lg:p-10 bg-gradient-to-br from-emerald-50/30 to-transparent hover:from-emerald-50/60 transition-colors duration-500">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100/50 text-emerald-600 flex items-center justify-center">
                            <iconify-icon icon="solar:like-linear" class="text-2xl" style="stroke-width: 1.5;"></iconify-icon>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-[#363230]">Kelebihan</h3>
                            <p class="text-xs text-gray-500">Alasan memilih produk ini</p>
                        </div>
                    </div>
                    @if ($laptop->kelebihan)
                    <div class="prose prose-sm prose-emerald max-w-none text-gray-600 leading-relaxed">
                        {!! $laptop->kelebihan !!}
                    </div>
                    @else
                    <div class="flex items-center gap-2 text-sm text-gray-400 italic bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                        <iconify-icon icon="solar:info-circle-linear" class="text-lg"></iconify-icon>
                        Belum ada informasi kelebihan.
                    </div>
                    @endif
                </div>

                <!-- Kekurangan -->
                <div class="p-8 lg:p-10 bg-gradient-to-br from-rose-50/30 to-transparent hover:from-rose-50/60 transition-colors duration-500">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-rose-100/50 text-rose-600 flex items-center justify-center">
                            <iconify-icon icon="solar:dislike-linear" class="text-2xl" style="stroke-width: 1.5;"></iconify-icon>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-[#363230]">Kekurangan</h3>
                            <p class="text-xs text-gray-500">Hal yang perlu dipertimbangkan</p>
                        </div>
                    </div>
                    @if ($laptop->kekurangan)
                    <div class="prose prose-sm prose-rose max-w-none text-gray-600 leading-relaxed">
                        {!! $laptop->kekurangan !!}
                    </div>
                    @else
                    <div class="flex items-center gap-2 text-sm text-gray-400 italic bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                        <iconify-icon icon="solar:info-circle-linear" class="text-lg"></iconify-icon>
                        Belum ada informasi kekurangan.
                    </div>
                    @endif
                </div>
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
                        <div class="group bg-white rounded-2xl border border-gray-200/80 hover:border-[#DF5E1D]/50 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col relative p-5">
                            
                            <!-- Stock Status Overlay -->
                            @if ($similarProduct->stock === 0)
                                <div class="absolute inset-0 z-20 bg-white/60 backdrop-blur-[2px] flex items-center justify-center">
                                    <div class="bg-gray-900 text-white px-4 py-2 rounded-md text-sm font-semibold shadow-xl tracking-wide">
                                        Stok Habis
                                    </div>
                                </div>
                            @endif

                            <!-- Badge Category (Solid Orange) -->
                            <div class="absolute top-5 left-5 bg-[#DF5E1D] text-white px-2.5 py-1 text-[10px] font-bold uppercase rounded-sm z-10 shadow-sm">
                                {{ $similarProduct->categories->first()?->name ?? 'Featured' }}
                            </div>

                            <!-- Image -->
                            <div class="relative h-40 bg-white overflow-hidden flex items-center justify-center mb-6 mt-4">
                                @if ($similarProduct->image_url)
                                    <img src="{{ $similarProduct->image_url_full }}" alt="{{ $similarProduct->name }}" class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <img src="https://placehold.co/600x400/363230/DF5E1D?text=ZLM" alt="{{ $similarProduct->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @endif
                            </div>

                            <div class="flex flex-col flex-grow">
                                <!-- Title -->
                                <h3 class="text-[15px] font-bold text-[#363230] mb-2 leading-snug group-hover:text-[#DF5E1D] transition-colors">
                                    {{ $similarProduct->name }}
                                </h3>

                                <!-- Brand -->
                                <p class="text-[11px] text-gray-500 font-bold tracking-widest uppercase mb-4">
                                    {{ $similarProduct->brand }}
                                </p>

                                <!-- Pricing -->
                                <div class="mb-4">
                                    <p class="text-[12px] text-[#363230] font-medium mb-0.5">Harga ZLM</p>
                                    <p class="text-[17px] font-bold text-[#DF5E1D] tracking-tight mb-1">
                                        Rp {{ number_format($similarProduct->price, 0, ',', '.') }}
                                    </p>
                                </div>

                                <!-- Divider -->
                                <hr class="border-gray-200 mb-4">

                                <!-- Specs (Text-only layout like ASUS) -->
                                <div class="mb-2 space-y-1.5 overflow-hidden transition-all duration-300" style="max-height: 42px;">
                                    @if($similarProduct->processor)
                                        <p class="text-[11px] text-gray-600 leading-relaxed">{{ $similarProduct->processor }}</p>
                                    @endif
                                    @if($similarProduct->ram)
                                        <p class="text-[11px] text-gray-600 leading-relaxed">{{ $similarProduct->ram }}</p>
                                    @endif
                                    @if($similarProduct->storage)
                                        <p class="text-[11px] text-gray-600 leading-relaxed">{{ $similarProduct->storage }}</p>
                                    @endif
                                    @if($similarProduct->graphics)
                                        <p class="text-[11px] text-gray-600 leading-relaxed">{{ $similarProduct->graphics }}</p>
                                    @endif
                                    @if($similarProduct->display)
                                        <p class="text-[11px] text-gray-600 leading-relaxed">{{ $similarProduct->display }}</p>
                                    @endif
                                </div>

                                <!-- Toggle Button for Specs -->
                                <button onclick="toggleSpecs(this)" class="text-[10px] font-bold text-gray-400 hover:text-[#DF5E1D] uppercase tracking-wider flex items-center gap-1 transition-colors mt-1 mb-5">
                                    <span>LIHAT LAINNYA</span>
                                    <iconify-icon icon="solar:alt-arrow-down-linear" class="text-xs transition-transform duration-300"></iconify-icon>
                                </button>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-2 mt-auto pt-4 border-t border-gray-100">
                                <!-- View Details Button -->
                                <a href="{{ route('landing.detail', $similarProduct->id) }}" class="flex-1 py-2 rounded-sm bg-[#DF5E1D] text-white flex items-center justify-center hover:bg-[#c45218] transition-colors font-bold text-[11px] tracking-wider uppercase">
                                    Detail
                                </a>
                                
                                <!-- Wishlist Button -->
                                <button onclick="toggleWishlist({{ $similarProduct->id }})" data-wishlist-btn data-laptop-id="{{ $similarProduct->id }}" class="w-9 h-9 rounded-sm border border-gray-200 text-gray-600 flex items-center justify-center hover:border-red-500 hover:text-red-500 transition-colors group relative" title="Add to Wishlist">
                                    <iconify-icon icon="solar:heart-linear" class="text-base"></iconify-icon>
                                </button>

                                <!-- Add to Compare Button -->
                                <button onclick="addToCompare('{{ $similarProduct->id }}')" data-compare-btn data-laptop-id="{{ $similarProduct->id }}" class="w-9 h-9 rounded-sm border border-gray-200 text-gray-600 flex items-center justify-center hover:border-[#DF5E1D] hover:text-[#DF5E1D] transition-colors group relative" title="Add to Compare">
                                    <iconify-icon icon="solar:scale-linear" class="text-base"></iconify-icon>
                                </button>
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

    // ===== Share Product Function =====
    function shareProduct() {
        const url = window.location.href;
        const title = document.title;

        if (navigator.share) {
            navigator.share({
                title: title,
                text: 'Lihat produk ini di ZLM.ID',
                url: url,
            })
            .then(() => showToast('Berhasil dibagikan!', 'success'))
            .catch((err) => {
                if (err.name !== 'AbortError') {
                    copyToClipboard(url);
                }
            });
        } else {
            copyToClipboard(url);
        }
    }

    // ===== Copy Product Link Function =====
    function copyProductLink() {
        const url = window.location.href;
        copyToClipboard(url);
    }

    // ===== Copy to Clipboard Utility =====
    function copyToClipboard(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text)
                .then(() => showToast('Link disalin ke clipboard!', 'success'))
                .catch(() => fallbackCopy(text));
        } else {
            fallbackCopy(text);
        }
    }

    // ===== Fallback Copy for Older Browsers =====
    function fallbackCopy(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        textarea.style.pointerEvents = 'none';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            showToast('Link disalin ke clipboard!', 'success');
        } catch (e) {
            showToast('Gagal menyalin link', 'error');
        }
        document.body.removeChild(textarea);
    }

    // ===== Toggle Specs Function for Cards =====
    function toggleSpecs(btn) {
        const specsContainer = btn.previousElementSibling;
        const icon = btn.querySelector('iconify-icon');
        const text = btn.querySelector('span');
        
        if (specsContainer.style.maxHeight === '42px' || specsContainer.style.maxHeight === '') {
            specsContainer.style.maxHeight = specsContainer.scrollHeight + 'px';
            icon.setAttribute('icon', 'solar:alt-arrow-up-linear');
            text.textContent = 'SEMBUNYIKAN';
            btn.classList.add('text-[#DF5E1D]');
        } else {
            specsContainer.style.maxHeight = '42px';
            icon.setAttribute('icon', 'solar:alt-arrow-down-linear');
            text.textContent = 'LIHAT LAINNYA';
            btn.classList.remove('text-[#DF5E1D]');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateWishlistButtons();
        updateCompareBadge();
    });
</script>
@endsection
