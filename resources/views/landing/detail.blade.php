@extends('layouts.landing')

@section('title', $laptop->name)

@section('content')

@php
    $storePhone = \App\Models\Setting::getValue('wa_admin_phone', '6281234567890');
    $cleanPhone = preg_replace('/[^0-9]/', '', $storePhone);
    if (str_starts_with($cleanPhone, '0')) {
        $cleanPhone = '62' . substr($cleanPhone, 1);
    }
    $productUrl = request()->fullUrl();
    
    // Default bundle
    $defaultBundle = $addons->firstWhere('price', 0) ?? $addons->first();
@endphp

<div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 min-h-screen py-6 lg:py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8 lg:mb-10">
        <ol class="flex items-center gap-2 text-xs font-medium text-gray-400">
            <li>
                <a href="{{ route('landing.home') }}" class="hover:text-[#DF5E1D] transition-colors flex items-center gap-1.5">
                    <iconify-icon icon="solar:home-2-linear" class="text-sm"></iconify-icon>
                    Beranda
                </a>
            </li>
            <li class="text-gray-300">/</li>
            <li>
                <a href="{{ route('landing.search') }}" class="hover:text-[#DF5E1D] transition-colors">Katalog Laptop</a>
            </li>
            <li class="text-gray-300">/</li>
            <li class="text-gray-800 font-bold truncate max-w-[220px] sm:max-w-none">{{ $laptop->name }}</li>
        </ol>
    </nav>

    <!-- Product Detail Section (3 Column Layout) -->
    <style>
        .prose-custom ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .prose-custom.prose-emerald ul li {
            position: relative;
            padding-left: 1.5rem;
        }
        .prose-custom.prose-emerald ul li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #10b981;
            font-weight: bold;
        }
        .prose-custom.prose-rose ul li {
            position: relative;
            padding-left: 1.5rem;
        }
        .prose-custom.prose-rose ul li::before {
            content: '×';
            position: absolute;
            left: 0;
            color: #f43f5e;
            font-weight: bold;
        }
        .prose-custom p { margin-bottom: 0.5rem; }
        .prose-custom p:last-child { margin-bottom: 0; }
    </style>

    <div class="mb-12 lg:mb-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start relative">
            
            <!-- 1. Left: Product Image Gallery (Sticky) -->
            <div class="lg:col-span-4 lg:sticky lg:top-24 z-20">
                <div class="bg-white rounded-3xl border border-gray-200/70 shadow-sm overflow-hidden p-4 sm:p-6 mb-6 lg:mb-0">
                    <input type="checkbox" id="zoom-image" class="peer hidden">

                    <label for="zoom-image" class="relative w-full aspect-square flex items-center justify-center bg-gray-50/50 rounded-2xl border border-gray-100 overflow-hidden mb-4 group cursor-zoom-in transition-all duration-300 hover:border-gray-300">
                        @if ($laptop->image_url)
                            <img id="main-product-image" src="{{ $laptop->image_url_full }}" alt="{{ $laptop->name }}" class="w-full h-full object-contain p-4 mix-blend-multiply transition-transform duration-700 ease-out group-hover:scale-110">
                        @else
                            <img id="main-product-image" src="https://placehold.co/800x600/363230/DF5E1D?text=ZLM" alt="{{ $laptop->name }}" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110">
                        @endif

                        <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md border border-gray-200 text-[#DF5E1D] px-3 py-1 rounded-lg text-[10px] font-bold shadow-sm uppercase tracking-wider">
                            {{ $laptop->categories->first()?->name ?? $laptop->brand }}
                        </div>

                        {{-- Sold Out Overlay Badge --}}
                        @if ($laptop->stock <= 0)
                            <div class="absolute inset-0 bg-black/40 backdrop-blur-[1px] flex items-center justify-center z-10">
                                <span class="bg-rose-600 text-white font-extrabold text-xs uppercase px-4 py-2 rounded-xl shadow-lg tracking-wider">
                                    Habis Terjual
                                </span>
                            </div>
                        @endif

                        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center pointer-events-none">
                            <div class="bg-white/90 backdrop-blur text-gray-800 p-2.5 rounded-full shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                <iconify-icon icon="solar:magnifer-zoom-in-linear" class="text-xl block"></iconify-icon>
                            </div>
                        </div>
                    </label>

                    {{-- Lightbox Modal --}}
                    <div class="fixed inset-0 z-[100] bg-white/95 backdrop-blur-xl opacity-0 pointer-events-none peer-checked:opacity-100 peer-checked:pointer-events-auto transition-all duration-500 flex items-center justify-center">
                        <label for="zoom-image" class="absolute inset-0 cursor-zoom-out"></label>
                        <label for="zoom-image" class="absolute top-6 right-6 w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-200 hover:text-gray-900 cursor-pointer transition-colors z-10">
                            <iconify-icon icon="solar:close-circle-linear" class="text-2xl"></iconify-icon>
                        </label>
                        <div class="relative w-full max-w-5xl max-h-[90vh] p-4 scale-95 peer-checked:scale-100 transition-transform duration-500 ease-out z-0">
                            @if ($laptop->image_url)
                                <img id="lightbox-image" src="{{ $laptop->image_url_full }}" alt="{{ $laptop->name }}" class="w-full h-full object-contain">
                            @else
                                <img id="lightbox-image" src="https://placehold.co/1200x800/363230/DF5E1D?text=ZLM" alt="{{ $laptop->name }}" class="w-full h-full object-contain rounded-xl shadow-2xl">
                            @endif
                        </div>
                    </div>

                    {{-- Image Thumbnails Grid --}}
                    <div class="grid grid-cols-4 gap-2.5">
                        <div class="aspect-square bg-white rounded-xl border-2 border-[#DF5E1D] overflow-hidden cursor-pointer group relative p-1" onclick="document.getElementById('main-product-image').src=this.querySelector('img').src; document.getElementById('lightbox-image').src=this.querySelector('img').src;">
                            <img src="{{ $laptop->image_url_full ?? 'https://placehold.co/800x600/363230/DF5E1D?text=ZLM' }}" class="w-full h-full object-contain mix-blend-multiply">
                        </div>
                        @forelse ($laptop->images as $image)
                            <div class="aspect-square bg-white rounded-xl border border-gray-200 overflow-hidden cursor-pointer hover:border-[#DF5E1D]/50 transition-colors group relative p-1" onclick="document.getElementById('main-product-image').src=this.querySelector('img').src; document.getElementById('lightbox-image').src=this.querySelector('img').src;">
                                <img src="{{ Storage::url($image->image_url) }}" alt="{{ $image->caption ?? 'Product image' }}" class="w-full h-full object-contain mix-blend-multiply">
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- 2. Center: Product Specifications, Bundle Selector & Description -->
            <div class="lg:col-span-5 flex flex-col space-y-6">
                <div>
                    <span class="text-xs font-bold text-gray-400 tracking-widest uppercase block mb-1">
                        {{ $laptop->brand }}
                    </span>
                    <h1 class="text-2xl lg:text-3xl font-bold tracking-tight text-[#363230] leading-snug">{{ $laptop->name }}</h1>
                    
                    {{-- Quick Specs Badges --}}
                    <div class="flex flex-wrap items-center gap-2.5 text-xs text-gray-600 mt-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        @if($laptop->processor)
                        <div class="flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-xl border border-gray-200/80 shadow-2xs">
                            <iconify-icon icon="solar:cpu-bold" class="text-[#DF5E1D]"></iconify-icon>
                            <span class="font-semibold">{{ $laptop->processor }}</span>
                        </div>
                        @endif
                        @if($laptop->ram)
                        <div class="flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-xl border border-gray-200/80 shadow-2xs">
                            <iconify-icon icon="solar:ram-bold" class="text-[#DF5E1D]"></iconify-icon>
                            <span class="font-semibold">{{ $laptop->ram }}</span>
                        </div>
                        @endif
                        @if($laptop->storage)
                        <div class="flex items-center gap-1.5 bg-white px-3 py-1.5 rounded-xl border border-gray-200/80 shadow-2xs">
                            <iconify-icon icon="solar:database-bold" class="text-[#DF5E1D]"></iconify-icon>
                            <span class="font-semibold">{{ $laptop->storage }}</span>
                        </div>
                        @endif
                    </div>

                    {{-- BUNDLE / ADD-ONS SELECTION SECTION (Gambar 1) --}}
                    @if($addons->count() > 0)
                        <div class="mt-6 pt-5 border-t border-gray-100">
                            <div class="flex items-center gap-2 mb-3">
                                <h3 class="text-sm font-extrabold text-gray-900 tracking-wide uppercase">
                                    BUNDLE: <span id="selected-bundle-title" class="text-[#258548] font-bold">{{ $defaultBundle?->name ?? 'Non Bundle' }}</span>
                                </h3>
                            </div>

                            <div class="flex flex-wrap items-center gap-2.5" id="bundle-selector-container">
                                @foreach($addons as $addon)
                                    @php
                                        $isSelected = ($defaultBundle && $defaultBundle->id === $addon->id);
                                    @endphp
                                    <button type="button"
                                            onclick="selectBundle('{{ $addon->id }}', '{{ addslashes($addon->name) }}', {{ (float)$addon->price }}, this)"
                                            data-addon-id="{{ $addon->id }}"
                                            data-addon-name="{{ $addon->name }}"
                                            data-addon-price="{{ (float)$addon->price }}"
                                            class="bundle-pill relative flex items-center gap-2.5 py-2 px-4 rounded-full border text-xs font-bold transition-all duration-200 shadow-2xs {{ $isSelected ? 'bg-white border-[#22c55e] text-[#166534] ring-1 ring-[#22c55e]' : 'bg-gray-100/80 hover:bg-gray-200/70 border-transparent text-gray-700' }}">
                                        
                                        {{-- Optional Bundle Image Thumbnail --}}
                                        <div class="w-6 h-6 rounded-full bg-white border border-gray-200/80 overflow-hidden flex items-center justify-center shrink-0">
                                            @if($addon->image_url_full)
                                                <img src="{{ $addon->image_url_full }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                <iconify-icon icon="solar:laptop-minimalistic-bold" class="text-[#DF5E1D] text-xs"></iconify-icon>
                                            @endif
                                        </div>

                                        <span>{{ $addon->name }}</span>
                                        @if($addon->price > 0)
                                            <span class="text-[10px] font-mono text-[#DF5E1D]">(+Rp {{ number_format($addon->price, 0, ',', '.') }})</span>
                                        @endif

                                        {{-- Thumbs Up Recommended Badge (Gambar 1) --}}
                                        @if($addon->is_recommended)
                                            <span class="absolute -top-1.5 -right-1.5 w-5 h-5 rounded-full bg-rose-500 text-white flex items-center justify-center shadow-sm" title="Direkomendasikan">
                                                <iconify-icon icon="solar:like-bold" class="text-[10px]"></iconify-icon>
                                            </span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>

                            <p id="bundle-description-text" class="text-xs text-gray-500 mt-2.5 italic">
                                {{ $defaultBundle?->description ?? '' }}
                            </p>
                        </div>
                    @endif

                    <div class="prose prose-sm text-gray-600 leading-relaxed max-w-none mt-6">
                        {!! $laptop->description !!}
                    </div>
                </div>

                {{-- Pros and Cons --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-emerald-50/60 p-5 rounded-2xl border border-emerald-100">
                        <div class="flex items-center gap-2.5 mb-3">
                            <div class="w-7 h-7 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                <iconify-icon icon="solar:check-circle-bold" class="text-base"></iconify-icon>
                            </div>
                            <h3 class="text-xs font-bold text-emerald-900 uppercase tracking-wider">Kelebihan Unit</h3>
                        </div>
                        @if ($laptop->kelebihan)
                        <div class="prose-custom prose-emerald text-xs text-emerald-800/90 leading-relaxed">
                            {!! $laptop->kelebihan !!}
                        </div>
                        @else
                        <p class="text-xs text-emerald-600/70 italic">Kondisi fisik mulus, baterai teruji prima.</p>
                        @endif
                    </div>

                    <div class="bg-rose-50/60 p-5 rounded-2xl border border-rose-100">
                        <div class="flex items-center gap-2.5 mb-3">
                            <div class="w-7 h-7 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                                <iconify-icon icon="solar:close-circle-bold" class="text-base"></iconify-icon>
                            </div>
                            <h3 class="text-xs font-bold text-rose-900 uppercase tracking-wider">Catatan Fisik</h3>
                        </div>
                        @if ($laptop->kekurangan)
                        <div class="prose-custom prose-rose text-xs text-rose-800/90 leading-relaxed">
                            {!! $laptop->kekurangan !!}
                        </div>
                        @else
                        <p class="text-xs text-rose-600/70 italic">Lecet pemakaian wajar (Grade A- / mulus).</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 3. Right: Action Card & WhatsApp Button (Sticky) -->
            <div class="lg:col-span-3 lg:sticky lg:top-24 z-20 mt-8 lg:mt-0">
                <div class="bg-white rounded-3xl border border-gray-200/80 shadow-md p-6 flex flex-col relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#DF5E1D] to-[#f4844b]"></div>

                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Rincian Harga & Pembelian</h3>

                    {{-- Pricing --}}
                    <div class="mb-5">
                        @if($laptop->has_discount)
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="px-2 py-0.5 rounded-md bg-rose-50 text-rose-600 border border-rose-200 text-[11px] font-bold">
                                    @if($laptop->discount_type === 'percentage')
                                        DISKON {{ (int)$laptop->discount_value }}%
                                    @else
                                        HEMAT Rp {{ number_format($laptop->discount_value, 0, ',', '.') }}
                                    @endif
                                </span>
                                <span class="text-xs line-through text-gray-400 font-semibold font-mono">
                                    Rp {{ number_format($laptop->price, 0, ',', '.') }}
                                </span>
                            </div>
                        @endif
                        <div class="text-2xl xl:text-3xl font-extrabold tracking-tight text-[#DF5E1D] font-mono" id="display-final-price">
                            Rp {{ number_format($laptop->final_price + ($defaultBundle ? $defaultBundle->price : 0), 0, ',', '.') }}
                        </div>
                        <div id="display-bundle-calc" class="text-[11px] text-gray-500 mt-1 {{ ($defaultBundle && $defaultBundle->price > 0) ? '' : 'hidden' }}">
                            Termasuk bundle <strong id="calc-bundle-name">{{ $defaultBundle?->name }}</strong> (+Rp <span id="calc-bundle-price">{{ number_format($defaultBundle?->price ?? 0, 0, ',', '.') }}</span>)
                        </div>
                    </div>

                    {{-- Stock Status Indicator --}}
                    <div class="mb-5">
                        @if ($laptop->stock <= 0)
                            <div class="flex items-center gap-2 text-xs font-bold text-rose-700 bg-rose-50 px-3.5 py-2.5 rounded-xl border border-rose-200">
                                <iconify-icon icon="solar:close-circle-bold" class="text-base text-rose-600"></iconify-icon>
                                <span>Stok Habis Terjual</span>
                            </div>
                        @elseif ($laptop->stock <= 2)
                            <div class="flex items-center gap-2 text-xs font-bold text-amber-700 bg-amber-50 px-3.5 py-2.5 rounded-xl border border-amber-200">
                                <iconify-icon icon="solar:info-circle-bold" class="text-base text-amber-600"></iconify-icon>
                                <span>Stok Menipis (Sisa {{ $laptop->stock }} Unit)</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-xs font-bold text-emerald-700 bg-emerald-50 px-3.5 py-2.5 rounded-xl border border-emerald-200">
                                <iconify-icon icon="solar:check-circle-bold" class="text-base text-emerald-600"></iconify-icon>
                                <span>Unit Siap Kirim ({{ $laptop->stock }} Unit)</span>
                            </div>
                        @endif
                    </div>

                    {{-- Action Form & Buttons --}}
                    <div class="space-y-3">
                        {{-- WhatsApp Purchase Button (Prominent Green) --}}
                        <a id="wa-purchase-btn" href="#" target="_blank" rel="noopener noreferrer"
                           class="w-full bg-[#25D366] hover:bg-[#20bd5a] text-white py-3.5 px-4 rounded-xl text-xs font-extrabold shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2 group">
                            <iconify-icon icon="solar:chat-round-dots-bold" class="text-lg group-hover:scale-110 transition-transform"></iconify-icon>
                            <span>{{ $laptop->stock > 0 ? 'Beli via WhatsApp' : 'Tanyakan Unit via WhatsApp' }}</span>
                        </a>

                        {{-- Add to Cart Form --}}
                        <form method="POST" action="{{ route('cart.add') }}" class="space-y-2" id="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="laptop_id" value="{{ $laptop->id }}">
                            <input type="hidden" name="addon_id" id="form-addon-id" value="{{ $defaultBundle?->id }}">
                            <input type="hidden" name="quantity" value="1">

                            <button type="submit" @disabled($laptop->stock <= 0)
                                class="w-full bg-[#DF5E1D] hover:bg-[#c45218] disabled:opacity-40 disabled:cursor-not-allowed text-white py-3 px-4 rounded-xl text-xs font-bold shadow-xs transition-all flex items-center justify-center gap-2">
                                <iconify-icon icon="solar:cart-large-2-bold" class="text-base"></iconify-icon>
                                <span>Masukkan ke Keranjang</span>
                            </button>
                        </form>

                        {{-- Wishlist Button --}}
                        <button type="button" onclick="toggleDetailWishlist('{{ $laptop->id }}')" data-wishlist-btn data-laptop-id="{{ $laptop->id }}"
                                class="w-full bg-white border border-gray-200 hover:border-rose-300 text-gray-700 hover:text-rose-600 hover:bg-rose-50/50 py-2.5 px-4 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
                            <iconify-icon icon="solar:heart-linear" class="text-base text-gray-400"></iconify-icon>
                            <span>Simpan ke Wishlist</span>
                        </button>
                    </div>

                    {{-- Garansi & Keamanan Toko --}}
                    <div class="mt-6 pt-4 border-t border-gray-100 space-y-2 text-[11px] text-gray-500">
                        <div class="flex items-center gap-2">
                            <iconify-icon icon="solar:shield-check-bold" class="text-[#DF5E1D] text-sm"></iconify-icon>
                            <span>{{ $laptop->warranty ?: 'Garansi Toko Resmi ZLM.ID 1 Bulan' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <iconify-icon icon="solar:box-bold" class="text-[#DF5E1D] text-sm"></iconify-icon>
                            <span>Packing Kayu & Bubble Wrap Aman</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Technical Specifications Table Section (Gambar 2 & 3) -->
    <div class="mb-16 lg:mb-24 max-w-5xl mx-auto">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-orange-50 text-[#DF5E1D] mb-3">
                <iconify-icon icon="solar:settings-minimalistic-bold" class="text-xl"></iconify-icon>
            </div>
            <h2 class="text-xl lg:text-2xl font-bold tracking-tight text-[#363230]">Spesifikasi Teknis Lengkap</h2>
            <p class="text-xs text-gray-500 mt-1">Detail hardware, port colokan I/O, dan fitur bawaan unit {{ $laptop->name }}.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Main Hardware Specs --}}
            <div class="bg-white p-4 rounded-2xl border border-gray-200/70 shadow-2xs">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Prosesor (CPU)</p>
                <p class="text-xs font-bold text-[#363230]">{{ $laptop->processor }}</p>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-gray-200/70 shadow-2xs">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Memori (RAM)</p>
                <p class="text-xs font-bold text-[#363230]">{{ $laptop->ram }}</p>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-gray-200/70 shadow-2xs">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Penyimpanan (Storage)</p>
                <p class="text-xs font-bold text-[#363230]">{{ $laptop->storage }}</p>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-gray-200/70 shadow-2xs">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Kartu Grafis (GPU)</p>
                <p class="text-xs font-bold text-[#363230]">{{ $laptop->graphics ?: 'Integrated Graphics' }}</p>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-gray-200/70 shadow-2xs">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Ukuran & Tipe Layar</p>
                <p class="text-xs font-bold text-[#363230]">{{ $laptop->display ?: '14 inch IPS' }}</p>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-gray-200/70 shadow-2xs">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Daya Tahan Baterai</p>
                <p class="text-xs font-bold text-[#363230]">{{ $laptop->battery_life ?: 'Tergantung Pemakaian' }}</p>
            </div>

            {{-- Additional Extended Specs (Gambar 2 & 3) --}}
            @if($laptop->camera)
                <div class="bg-white p-4 rounded-2xl border border-gray-200/70 shadow-2xs">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Webcam / Kamera</p>
                    <p class="text-xs font-bold text-[#363230]">{{ $laptop->camera }}</p>
                </div>
            @endif

            @if($laptop->audio)
                <div class="bg-white p-4 rounded-2xl border border-gray-200/70 shadow-2xs">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Audio & Speaker</p>
                    <p class="text-xs font-bold text-[#363230]">{{ $laptop->audio }}</p>
                </div>
            @endif

            @if($laptop->connectivity)
                <div class="bg-white p-4 rounded-2xl border border-gray-200/70 shadow-2xs">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Konektivitas Nirkabel</p>
                    <p class="text-xs font-bold text-[#363230]">{{ $laptop->connectivity }}</p>
                </div>
            @endif

            @if($laptop->color)
                <div class="bg-white p-4 rounded-2xl border border-gray-200/70 shadow-2xs">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Warna Casing</p>
                    <p class="text-xs font-bold text-[#363230]">{{ $laptop->color }}</p>
                </div>
            @endif

            @if($laptop->weight)
                <div class="bg-white p-4 rounded-2xl border border-gray-200/70 shadow-2xs">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Berat Fisik</p>
                    <p class="text-xs font-bold text-[#363230]">{{ $laptop->weight }} kg</p>
                </div>
            @endif

            @if($laptop->warranty)
                <div class="bg-white p-4 rounded-2xl border border-gray-200/70 shadow-2xs">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Garansi Unit</p>
                    <p class="text-xs font-bold text-[#363230]">{{ $laptop->warranty }}</p>
                </div>
            @endif

            {{-- Dedicated Full I/O Ports Card (Gambar 2 & 3) --}}
            @if(count($laptop->ports_list) > 0 || !empty($laptop->ports))
                <div class="md:col-span-3 bg-white p-5 rounded-2xl border border-gray-200/70 shadow-2xs">
                    <div class="flex items-center gap-2 mb-3">
                        <iconify-icon icon="solar:usb-bold-duotone" class="text-[#DF5E1D] text-lg"></iconify-icon>
                        <p class="text-xs text-gray-900 font-extrabold uppercase tracking-wider">I/O Ports & Konektor (Colokan Lengkap):</p>
                    </div>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs font-medium text-gray-700 font-mono">
                        @foreach($laptop->ports_list as $port)
                            <li class="flex items-center gap-2 bg-gray-50/80 p-2.5 rounded-xl border border-gray-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#DF5E1D] shrink-0"></span>
                                <span>{{ $port }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <!-- Similar Laptops Section -->
    @if ($similar->count() > 0)
        <div class="border-t border-gray-200/60 pt-12 mb-16">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-[#363230]">Laptop Serupa</h2>
                    <p class="text-xs text-gray-400">Pilihan model lain yang setara.</p>
                </div>
                <a href="{{ route('landing.search') }}" class="text-xs font-bold text-[#DF5E1D] hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach ($similar as $item)
                    <div class="bg-white rounded-2xl border border-gray-200/70 p-4 shadow-2xs hover:shadow-md transition-all flex flex-col justify-between group">
                        <div>
                            <div class="relative aspect-video rounded-xl bg-gray-50 overflow-hidden mb-3 flex items-center justify-center">
                                @if ($item->image_url_full)
                                    <img src="{{ $item->image_url_full }}" alt="" class="w-full h-full object-contain p-2 group-hover:scale-105 transition-transform">
                                @else
                                    <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-gray-300 text-4xl"></iconify-icon>
                                @endif
                            </div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $item->brand }}</span>
                            <h3 class="text-xs font-bold text-[#363230] group-hover:text-[#DF5E1D] transition-colors line-clamp-1 mt-0.5">
                                {{ $item->name }}
                            </h3>
                            <p class="text-[11px] text-gray-500 mt-1 line-clamp-1">{{ $item->processor }} &bull; {{ $item->ram }}</p>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
                            <span class="font-mono font-bold text-xs text-[#DF5E1D]">
                                Rp {{ number_format($item->final_price, 0, ',', '.') }}
                            </span>
                            <a href="{{ route('landing.detail', $item) }}" class="px-3 py-1.5 bg-orange-50 hover:bg-[#DF5E1D] text-[#DF5E1D] hover:text-white rounded-lg text-xs font-bold transition">
                                Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
const baseLaptopPrice = {{ (float)$laptop->final_price }};
const baseLaptopName = "{{ addslashes($laptop->name) }}";
const baseLaptopBrand = "{{ addslashes($laptop->brand) }}";
const baseLaptopSpecs = "{{ addslashes($laptop->processor . ' / ' . $laptop->ram . ' / ' . $laptop->storage) }}";
const storePhoneClean = "{{ $cleanPhone }}";
const currentProductUrl = "{{ $productUrl }}";

let currentSelectedBundle = {
    id: "{{ $defaultBundle?->id ?? '' }}",
    name: "{{ addslashes($defaultBundle?->name ?? 'Non Bundle') }}",
    price: {{ (float)($defaultBundle?->price ?? 0) }}
};

function selectBundle(addonId, addonName, addonPrice, btnEl) {
    currentSelectedBundle = {
        id: addonId,
        name: addonName,
        price: parseFloat(addonPrice || 0)
    };

    // 1. Update UI active button
    document.querySelectorAll('.bundle-pill').forEach(btn => {
        btn.classList.remove('bg-white', 'border-[#22c55e]', 'text-[#166534]', 'ring-1', 'ring-[#22c55e]');
        btn.classList.add('bg-gray-100/80', 'border-transparent', 'text-gray-700');
    });

    btnEl.classList.remove('bg-gray-100/80', 'border-transparent', 'text-gray-700');
    btnEl.classList.add('bg-white', 'border-[#22c55e]', 'text-[#166534]', 'ring-1', 'ring-[#22c55e]');

    // 2. Update text label
    const titleEl = document.getElementById('selected-bundle-title');
    if (titleEl) titleEl.innerText = addonName;

    // 3. Update hidden form input
    const formInput = document.getElementById('form-addon-id');
    if (formInput) formInput.value = addonId;

    // 4. Update live price calculation
    const totalPrice = baseLaptopPrice + currentSelectedBundle.price;
    const priceEl = document.getElementById('display-final-price');
    if (priceEl) {
        priceEl.innerText = 'Rp ' + totalPrice.toLocaleString('id-ID');
    }

    const calcBox = document.getElementById('display-bundle-calc');
    const calcName = document.getElementById('calc-bundle-name');
    const calcPrice = document.getElementById('calc-bundle-price');
    if (calcBox && calcName && calcPrice) {
        if (currentSelectedBundle.price > 0) {
            calcBox.classList.remove('hidden');
            calcName.innerText = addonName;
            calcPrice.innerText = currentSelectedBundle.price.toLocaleString('id-ID');
        } else {
            calcBox.classList.add('hidden');
        }
    }

    // 5. Update WhatsApp URL
    updateWhatsAppUrl();
}

function updateWhatsAppUrl() {
    const total = baseLaptopPrice + currentSelectedBundle.price;
    const bundleText = currentSelectedBundle.price > 0 
        ? `${currentSelectedBundle.name} (+Rp ${currentSelectedBundle.price.toLocaleString('id-ID')})`
        : currentSelectedBundle.name;

    const message = `Halo ZLM.ID, saya ingin membeli unit laptop ini:\n\n`
        + `• *Model:* ${baseLaptopName}\n`
        + `• *Brand:* ${baseLaptopBrand}\n`
        + `• *Pilihan Bundle:* ${bundleText}\n`
        + `• *Total Estimasi:* Rp ${total.toLocaleString('id-ID')}\n`
        + `• *Spek:* ${baseLaptopSpecs}\n`
        + `• *Link Produk:* ${currentProductUrl}\n\n`
        + `Apakah unit dan paket bundling ini masih tersedia? Terima kasih!`;

    const encoded = encodeURIComponent(message);
    const waBtn = document.getElementById('wa-purchase-btn');
    if (waBtn) {
        waBtn.href = `https://wa.me/${storePhoneClean}?text=${encoded}`;
    }
}

// Initial call
document.addEventListener('DOMContentLoaded', () => {
    updateWhatsAppUrl();
});

function toggleDetailWishlist(id) {
    fetch("{{ route('wishlist.toggle') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ laptop_id: id })
    })
    .then(res => res.json())
    .then(data => {
        if (typeof window.showToast === 'function') {
            window.showToast(data.message || 'Wishlist berhasil diperbarui!');
        }
    })
    .catch(() => {
        if (typeof window.showToast === 'function') {
            window.showToast('Silakan login terlebih dahulu untuk menyimpan ke wishlist.', 'info');
        }
    });
}
</script>
@endpush
@endsection
