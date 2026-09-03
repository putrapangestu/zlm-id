@extends('layouts.landing')

@section('title', 'ZLM.ID - Premium Laptop Store')

@section('content')
<!-- Include Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<!-- Hero Slider Section -->
<div id="hero-slider" class="relative w-full hero-aspect">
    <div class="swiper heroSwiper w-full h-full">
        <div class="swiper-wrapper">
            @if ($sliders->count() > 0)
                @foreach ($sliders as $index => $slider)
                    <div class="swiper-slide relative">
                        @php
                            $isEntireLink = $slider->button_url && empty($slider->button_text);
                            $tag = $isEntireLink ? 'a' : 'div';
                        @endphp
                        
                        <{{ $tag }} 
                            @if($isEntireLink) href="{{ $slider->button_url }}" @endif 
                            class="block w-full h-full relative overflow-hidden">
                            
                            {{-- Image (Akan otomatis terpotong proporsional memenuhi wadah) --}}
                            @if ($slider->image_url)
                                <img src="{{ $slider->image_url }}" alt="{{ $slider->title }}" class="absolute inset-0 w-full h-full object-cover object-center">
                            @else
                                <img src="https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&q=80&w=1600" alt="Default Banner" class="absolute inset-0 w-full h-full object-cover object-center">
                            @endif

                            {{-- Overlay Text (Muncul di atas gambar HANYA jika ada teks) --}}
                            @if ($slider->title || $slider->description || $slider->button_text)
                                <!-- Overlay solid gelap penuh (50%) agar teks super kontras -->
                                <div class="absolute inset-0 bg-black/50 flex items-end justify-center md:items-center">
                                    <div class="text-center px-6 pb-16 md:pb-0 max-w-4xl w-full">
                                        @if ($slider->subtitle)
                                            <span class="inline-block bg-[#DF5E1D] text-white text-xs px-3 py-1 rounded-full font-bold mb-3 shadow-md">{{ $slider->subtitle }}</span>
                                        @endif
                                        
                                        @if ($slider->title)
                                            <h1 class="text-2xl md:text-4xl lg:text-5xl font-bold text-white mb-3 md:mb-4 drop-shadow-lg leading-tight">
                                                {{ $slider->title }}
                                            </h1>
                                        @endif
                                        
                                        @if ($slider->description)
                                            <p class="text-sm md:text-lg text-gray-100 mb-6 drop-shadow-md max-w-2xl mx-auto">
                                                {{ $slider->description }}
                                            </p>
                                        @endif

                                        {{-- Tombol Link Khusus (HANYA muncul jika Button Text diisi) --}}
                                        @if ($slider->button_text && $slider->button_url)
                                            <a href="{{ $slider->button_url }}" class="relative z-10 inline-flex items-center gap-2 bg-[#DF5E1D] text-white px-6 md:px-8 py-3 rounded-full text-sm font-semibold hover:bg-[#c45218] transition shadow-xl">
                                                {{ $slider->button_text }}
                                                <iconify-icon icon="solar:arrow-right-linear" class="text-lg"></iconify-icon>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </{{ $tag }}>
                    </div>
                @endforeach
            @else
                {{-- Fallback Static Slide jika kosong --}}
                <div class="swiper-slide relative">
                    <div class="w-full h-full relative overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&q=80&w=1600" alt="Premium Laptop" class="absolute inset-0 w-full h-full object-cover object-center">
                        <div class="absolute inset-0 bg-black/50 flex items-end justify-center md:items-center">
                            <div class="text-center px-6 pb-16 md:pb-0 max-w-3xl w-full">
                                <span class="inline-block bg-[#DF5E1D] text-white text-xs px-3 py-1 rounded-full font-bold mb-3 shadow-md">PROMO SPESIAL</span>
                                <h1 class="text-2xl md:text-4xl lg:text-5xl font-bold text-white mb-4 drop-shadow-lg leading-tight">
                                    Produktivitas Jadi Lebih Mudah
                                </h1>
                                <p class="text-sm md:text-lg text-gray-100 mb-6 drop-shadow-md max-w-xl mx-auto">
                                    Temukan perangkat impian untuk mendukung pekerjaan Anda sehari-hari dengan jaminan harga dan service terbaik.
                                </p>
                                <a href="{{ route('landing.search') }}" class="inline-flex items-center gap-2 bg-[#DF5E1D] text-white px-8 py-3 rounded-full text-sm font-semibold hover:bg-[#c45218] transition shadow-xl">
                                    Lihat Katalog
                                    <iconify-icon icon="solar:arrow-right-linear" class="text-lg"></iconify-icon>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        {{-- Custom Pagination Dots (Digimap style) --}}
        <div class="swiper-pagination"></div>
    </div>
</div>

<!-- Include Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.heroSwiper', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            // Mengaktifkan fitur geser dengan 2 jari (trackpad) atau mouse scroll
            mousewheel: {
                forceToAxis: true, // Hanya bereaksi terhadap scroll horizontal (kiri-kanan)
                thresholdDelta: 15, // Diturunkan agar jauh lebih ringan/sensitif saat disentuh pertama kali
                thresholdTime: 400, // Memberikan jeda antar scroll (mencegah slide loncat berkali-kali karena momentum)
            },
            effect: 'slide',
        });
    });
</script>

<style>
    /* Hero Slider Styles */
    .heroSwiper {
        width: 100%;
        height: auto;
    }
    .hero-aspect {
        aspect-ratio: 4 / 5;
        min-height: 400px;
    }
    @media (min-width: 768px) {
        .hero-aspect {
            aspect-ratio: 21 / 9; /* Desktop/Laptop (Ultrawide Cinematic) */
            min-height: auto;
        }
    }

    /* Custom Styling untuk pagination Swiper agar sesuai tema ZLM (Digimap layout) */
    .heroSwiper {
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0;
        display: block;
    }
    .heroSwiper .swiper-slide {
        display: block;
        width: 100%;
        height: 100%;
    }
    .heroSwiper .swiper-pagination-bullet {
        width: 8px;
        height: 8px;
        background: #d1d5db; /* gray-300 */
        opacity: 1;
        transition: all 0.3s ease;
        margin: 0 3px !important;
    }
    .heroSwiper .swiper-pagination-bullet-active {
        background: #DF5E1D; /* Orange ZLM */
        transform: scale(1.2);
    }
    .heroSwiper .swiper-pagination {
        position: absolute !important;
        background: rgba(255, 255, 255, 0.9);
        padding: 6px 12px;
        border-radius: 9999px;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        border: 1px solid #f3f4f6;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        width: auto !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        bottom: 24px !important; /* Force bottom position clearly inside image */
        z-index: 20 !important;
    }
</style>

<!-- Features / Why Choose Us -->
<section class="py-12 md:py-16 bg-gray-50/50">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
            <div class="flex flex-col">
                <div class="w-10 h-10 rounded text-[#DF5E1D] flex items-center mb-4">
                    <iconify-icon icon="solar:shield-check-linear" class="text-3xl"></iconify-icon>
                </div>
                <h3 class="text-base font-semibold text-[#363230] mb-2">Teruji Kualitasnya</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Setiap unit laptop bekas telah melewati proses Quality Control ketat untuk menjamin performanya.</p>
            </div>

            <div class="flex flex-col">
                <div class="w-10 h-10 rounded text-[#DF5E1D] flex items-center mb-4">
                    <iconify-icon icon="solar:routing-linear" class="text-3xl"></iconify-icon>
                </div>
                <h3 class="text-base font-semibold text-[#363230] mb-2">Pengiriman Aman</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Tersedia layanan pick-up di Malang dan pengiriman super aman ke seluruh Indonesia dengan asuransi.</p>
            </div>

            <div class="flex flex-col">
                <div class="w-10 h-10 rounded text-[#DF5E1D] flex items-center mb-4">
                    <iconify-icon icon="solar:tag-price-linear" class="text-3xl"></iconify-icon>
                </div>
                <h3 class="text-base font-semibold text-[#363230] mb-2">Harga Bersahabat</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Kami terus memantau harga pasar agar Anda selalu mendapat rasio harga ke performa laptop yang paling jujur.</p>
            </div>

            <div class="flex flex-col">
                <div class="w-10 h-10 rounded text-[#DF5E1D] flex items-center mb-4">
                    <iconify-icon icon="solar:chat-round-dots-linear" class="text-3xl"></iconify-icon>
                </div>
                <h3 class="text-base font-semibold text-[#363230] mb-2">Garansi Toko Jelas</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Kami memberikan jaminan dan garansi servis untuk unit laptop dari setiap masalah paska pembelian.</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Laptops Section -->
<section id="featured" class="py-24">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-10">
            <h2 class="text-3xl font-semibold tracking-tight text-[#363230] mb-2">Koleksi Pilihan ZLM.ID</h2>
            <p class="text-gray-500">Unit-unit laptop bekas pilihan dengan kualitas terbaik dan bergaransi.</p>
        </div>

        <!-- Category Tabs -->
        <div class="flex flex-wrap justify-center gap-6 md:gap-12 mb-10 border-b border-gray-200 px-4">
            <!-- Tab 'All' -->
            <button onclick="filterFeatured('all', this)" class="featured-tab active flex flex-col items-center gap-2 pb-4 border-b-2 border-black text-[#363230] hover:text-black transition-colors" data-tab="all">
                <iconify-icon icon="solar:widget-linear" class="text-2xl"></iconify-icon>
                <span class="text-sm font-medium">Semua</span>
            </button>

            @foreach ($categories->take(4) as $cat)
            <button onclick="filterFeatured('{{ $cat->slug }}', this)" class="featured-tab flex flex-col items-center gap-2 pb-4 border-b-2 border-transparent text-gray-400 hover:text-black hover:border-gray-300 transition-colors" data-tab="{{ $cat->slug }}">
                <iconify-icon icon="{{ $cat->icon ?? 'solar:laptop-minimalistic-linear' }}" class="text-2xl"></iconify-icon>
                <span class="text-sm font-medium">{{ $cat->name }}</span>
            </button>
            @endforeach

            <!-- View All Link -->
            <a href="{{ route('landing.search') }}" class="flex flex-col items-center gap-2 pb-4 border-b-2 border-transparent text-gray-400 hover:text-[#DF5E1D] hover:border-[#DF5E1D] transition-colors">
                <iconify-icon icon="solar:tag-price-linear" class="text-2xl"></iconify-icon>
                <span class="text-sm font-medium">Lihat Lainnya</span>
            </a>
        </div>

        <!-- Dynamic Container (Grid or Slider) -->
        <div id="featured-container" class="px-4 md:px-12 mb-10 relative group">
            <div class="swiper featuredSwiper !pb-2">
                <div class="swiper-wrapper !items-start">
                @foreach ($featured as $laptop)
                    <div class="swiper-slide featured-slide" data-category="{{ $laptop->categories->first()?->slug ?? 'uncategorized' }}">
                        <div class="bg-white rounded-xl border border-gray-200 hover:border-gray-300 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden flex flex-col group relative p-5">
                    
                    <!-- Badge Category (Solid Orange) -->
                    <div class="absolute top-5 left-5 bg-[#DF5E1D] text-white px-2.5 py-1 text-[10px] font-bold uppercase rounded-sm z-10 shadow-sm">
                        {{ $laptop->categories->first()?->name ?? 'Featured' }}
                    </div>

                    <!-- Image -->
                    <div class="relative h-40 bg-white overflow-hidden flex items-center justify-center mb-6 mt-4">
                        @if ($laptop->image_url)
                            <img src="{{ $laptop->image_url_full }}" alt="{{ $laptop->name }}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                        @else
                            <img src="https://placehold.co/600x400/363230/DF5E1D?text=ZLM" alt="{{ $laptop->name }}" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                        @endif
                    </div>

                    <div class="flex flex-col flex-grow">
                        <!-- Title -->
                        <h3 class="text-[15px] font-bold text-[#363230] mb-2 leading-snug group-hover:text-[#DF5E1D] transition-colors">
                            {{ $laptop->name }}
                        </h3>

                        <!-- Brand -->
                        <p class="text-[11px] text-gray-500 font-bold tracking-widest uppercase mb-4">
                            {{ $laptop->brand }}
                        </p>

                        <!-- Pricing -->
                        <div class="mb-4">
                            <p class="text-[12px] text-[#363230] font-medium mb-0.5">Harga ZLM</p>
                            <p class="text-[17px] font-bold text-[#DF5E1D] tracking-tight mb-1">
                                Rp {{ number_format($laptop->price, 0, ',', '.') }}
                            </p>
                        </div>

                        <!-- Divider -->
                        <hr class="border-gray-200 mb-4">

                        <!-- Specs (Text-only layout like ASUS) -->
                        <div class="mb-2 space-y-1.5 overflow-hidden transition-all duration-300" style="max-height: 42px;">
                            @if($laptop->processor)
                                <p class="text-[11px] text-gray-600 leading-relaxed">{{ $laptop->processor }}</p>
                            @endif
                            @if($laptop->ram)
                                <p class="text-[11px] text-gray-600 leading-relaxed">{{ $laptop->ram }}</p>
                            @endif
                            @if($laptop->storage)
                                <p class="text-[11px] text-gray-600 leading-relaxed">{{ $laptop->storage }}</p>
                            @endif
                            @if($laptop->graphics)
                                <p class="text-[11px] text-gray-600 leading-relaxed">{{ $laptop->graphics }}</p>
                            @endif
                            @if($laptop->display)
                                <p class="text-[11px] text-gray-600 leading-relaxed">{{ $laptop->display }}</p>
                            @endif
                            @if($laptop->battery_life)
                                <p class="text-[11px] text-gray-600 leading-relaxed">{{ $laptop->battery_life }}</p>
                            @endif
                            @if($laptop->weight)
                                <p class="text-[11px] text-gray-600 leading-relaxed">{{ $laptop->weight }} kg</p>
                            @endif
                        </div>

                        <!-- See Less / More Toggle -->
                        <div onclick="toggleSpecs(this)" class="text-[11px] text-[#DF5E1D] flex items-center gap-1 cursor-pointer mb-4 hover:underline w-fit">
                            <span class="toggle-text">See more</span> 
                            <iconify-icon icon="solar:alt-arrow-down-linear" class="toggle-icon"></iconify-icon>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col gap-2 mt-auto">
                            <!-- Beli (Primary Action) -->
                            <button onclick="window.location.href='{{ route('landing.detail', $laptop) }}'" class="w-full bg-[#DF5E1D] text-white py-2 rounded-md text-[13px] font-bold hover:bg-[#c45218] transition-colors shadow-sm">
                                Beli
                            </button>
                            <!-- Pelajari (Secondary Action) -->
                            <a href="{{ route('landing.detail', $laptop) }}" class="w-full text-center border-2 border-[#DF5E1D] text-[#DF5E1D] py-1.5 rounded-md text-[13px] font-bold hover:bg-[#DF5E1D]/5 transition-colors">
                                Pelajari
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
                </div>
            </div>
            <!-- Navigation Buttons -->
            <div class="swiper-button-prev !text-[#DF5E1D] !left-0 md:-left-6 !w-12 !h-12 !mt-[-24px] after:hidden hidden md:flex opacity-0 group-hover:opacity-100 transition-all duration-300 hover:!bg-[#DF5E1D] hover:!text-white bg-white rounded-full shadow-[0_4px_20px_rgba(0,0,0,0.1)] border border-gray-100 items-center justify-center cursor-pointer z-10">
                <iconify-icon icon="solar:alt-arrow-left-linear" class="text-2xl"></iconify-icon>
            </div>
            <div class="swiper-button-next !text-[#DF5E1D] !right-0 md:-right-6 !w-12 !h-12 !mt-[-24px] after:hidden hidden md:flex opacity-0 group-hover:opacity-100 transition-all duration-300 hover:!bg-[#DF5E1D] hover:!text-white bg-white rounded-full shadow-[0_4px_20px_rgba(0,0,0,0.1)] border border-gray-100 items-center justify-center cursor-pointer z-10">
                <iconify-icon icon="solar:alt-arrow-right-linear" class="text-2xl"></iconify-icon>
            </div>
        </div>
    </div>
</section>

<!-- Bento Grid Promo Section -->
<section class="py-12 bg-gray-50 border-y border-gray-100">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        @php
            // Mengambil 3 produk acak dari daftar laptop unggulan
            $bentoLaptops = isset($featured) && $featured->count() >= 3 ? $featured->random(3) : (isset($featured) ? $featured->take(3) : collect([]));
        @endphp

        @if($bentoLaptops->count() >= 3)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6">
            <!-- Left Large Card -->
            <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden flex flex-col justify-between p-8 text-center group transition-all duration-300 hover:shadow-xl hover:border-transparent h-full relative">
                <div class="mb-8 mt-4 z-10">
                    <span class="text-[#DF5E1D] text-[10px] font-bold tracking-widest uppercase mb-3 block">BARU</span>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $bentoLaptops[0]->name }}</h3>
                    <p class="text-gray-500 mb-6 text-sm">Mulai dari Rp {{ number_format($bentoLaptops[0]->price, 0, ',', '.') }}</p>
                    <a href="{{ route('landing.detail', $bentoLaptops[0]) }}" class="inline-block bg-[#DF5E1D] text-white px-7 py-3 rounded-full text-sm font-semibold hover:bg-[#c45218] transition-colors shadow-sm">Tambah ke keranjang</a>
                </div>
                <div class="flex-1 flex items-end justify-center relative">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-50/50 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <img src="{{ $bentoLaptops[0]->image_url_full ?: 'https://placehold.co/600x400/363230/DF5E1D?text=ZLM' }}" alt="{{ $bentoLaptops[0]->name }}" class="w-full max-w-[400px] object-contain group-hover:-translate-y-2 transition-transform duration-500 z-10" style="max-height: 320px;">
                </div>
            </div>

            <!-- Right Column -->
            <div class="flex flex-col gap-4 lg:gap-6">
                <!-- Top Card -->
                <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden flex flex-col sm:flex-row items-center p-6 sm:p-8 group transition-all duration-300 hover:shadow-xl hover:border-transparent flex-1 relative">
                    <div class="w-full sm:w-1/2 flex justify-center mb-6 sm:mb-0 relative z-10">
                        <img src="{{ $bentoLaptops[1]->image_url_full ?: 'https://placehold.co/600x400/363230/DF5E1D?text=ZLM' }}" alt="{{ $bentoLaptops[1]->name }}" class="w-full max-w-[180px] object-contain group-hover:-translate-y-2 transition-transform duration-500" style="max-height: 200px;">
                    </div>
                    <div class="w-full sm:w-1/2 text-center flex flex-col justify-center items-center z-10">
                        <span class="text-[#DF5E1D] text-[10px] font-bold tracking-widest uppercase mb-2 block">BARU</span>
                        <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-2 line-clamp-1">{{ $bentoLaptops[1]->name }}</h3>
                        <p class="text-gray-500 text-xs md:text-sm mb-4">Mulai dari Rp {{ number_format($bentoLaptops[1]->price, 0, ',', '.') }}</p>
                        <a href="{{ route('landing.detail', $bentoLaptops[1]) }}" class="inline-block bg-[#DF5E1D] text-white px-5 py-2.5 rounded-full text-xs font-semibold hover:bg-[#c45218] transition-colors shadow-sm">Tambah ke keranjang</a>
                    </div>
                    <div class="absolute right-0 bottom-0 top-0 w-1/2 bg-gradient-to-l from-gray-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>

                <!-- Bottom Card -->
                <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden flex flex-col sm:flex-row items-center p-6 sm:p-8 group transition-all duration-300 hover:shadow-xl hover:border-transparent flex-1 relative">
                    <div class="w-full sm:w-1/2 flex justify-center mb-6 sm:mb-0 relative z-10">
                        <img src="{{ $bentoLaptops[2]->image_url_full ?: 'https://placehold.co/600x400/363230/DF5E1D?text=ZLM' }}" alt="{{ $bentoLaptops[2]->name }}" class="w-full max-w-[180px] object-contain group-hover:-translate-y-2 transition-transform duration-500" style="max-height: 200px;">
                    </div>
                    <div class="w-full sm:w-1/2 text-center flex flex-col justify-center items-center z-10">
                        <span class="text-[#DF5E1D] text-[10px] font-bold tracking-widest uppercase mb-2 block">BARU</span>
                        <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-2 line-clamp-1">{{ $bentoLaptops[2]->name }}</h3>
                        <p class="text-gray-500 text-xs md:text-sm mb-4">Mulai dari Rp {{ number_format($bentoLaptops[2]->price, 0, ',', '.') }}</p>
                        <a href="{{ route('landing.detail', $bentoLaptops[2]) }}" class="inline-block bg-[#DF5E1D] text-white px-5 py-2.5 rounded-full text-xs font-semibold hover:bg-[#c45218] transition-colors shadow-sm">Tambah ke keranjang</a>
                    </div>
                    <div class="absolute right-0 bottom-0 top-0 w-1/2 bg-gradient-to-l from-gray-50/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Categories Section -->
<section class="py-20 bg-white border-y border-gray-200/60">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 max-w-2xl mx-auto">
            <h2 class="text-3xl font-semibold tracking-tight text-[#363230] mb-3">Pilih Sesuai Kebutuhan</h2>
            <p class="text-gray-500">Cari model laptop idaman Anda berdasarkan segmentasi penggunaan harian.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            @foreach ($categories as $category)
                <a href="{{ route('landing.search', ['category' => $category->slug]) }}" class="group block p-6 bg-gray-50 rounded-xl border border-gray-200/60 hover:bg-white hover:border-[#DF5E1D]/50 hover:shadow-sm transition-all duration-300">
                    <div class="w-12 h-12 bg-white rounded-lg border border-gray-200 flex items-center justify-center mb-4 group-hover:text-[#DF5E1D] transition-colors shadow-sm">
                        <iconify-icon icon="{{ $category->icon ?? 'solar:laptop-minimalistic-linear' }}" class="text-2xl"></iconify-icon>
                    </div>
                    <h3 class="text-base font-semibold text-[#363230] mb-1">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $category->description }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-24 bg-gray-100/50 border-t border-gray-200/50">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-semibold tracking-tight text-[#363230] mb-3">Apa Kata Pelanggan Kami</h2>
            <p class="text-gray-500">Ribuan orang puas berbelanja laptop second di ZLM.ID Malang.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($testimonials as $testimonial)
                <div class="bg-white p-8 rounded-xl border border-gray-200/60 shadow-sm">
                    <div class="flex text-[#DF5E1D] mb-4 gap-1">
                        @for ($i = 0; $i < $testimonial->rating; $i++)
                            <iconify-icon icon="solar:star-bold"></iconify-icon>
                        @endfor
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">"{{ $testimonial->content }}"</p>
                    <div class="flex items-center gap-3">
                        @if ($testimonial->photo)
                            <img src="{{ Storage::url($testimonial->photo) }}" class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-sm font-medium text-gray-500 border border-gray-200">
                                {{ substr($testimonial->name, 0, 2) }}
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-semibold text-[#363230]">{{ $testimonial->name }}</p>
                            @if ($testimonial->position)
                                <p class="text-xs text-gray-400">{{ $testimonial->position }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Insights / Blog Section -->
<section class="py-24 bg-white">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <h2 class="text-3xl font-semibold tracking-tight text-[#363230] mb-2">Artikel Terkini</h2>
                <p class="text-gray-500">Informasi tips, panduan hardware, serta perawatan laptop Anda.</p>
            </div>
            <a href="{{ route('landing.articles') }}" class="text-sm font-medium text-[#DF5E1D] hover:text-[#c45218] flex items-center gap-1 group transition">
                Read Journal
                <iconify-icon icon="solar:arrow-right-linear" class="group-hover:translate-x-1 transition-transform"></iconify-icon>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Article 1 -->
            <article class="group cursor-pointer">
                <div class="relative h-52 rounded-xl overflow-hidden mb-5 border border-gray-100">
                    <img src="https://images.unsplash.com/photo-1555680202-c86f0e12f086?auto=format&amp;fit=crop&amp;q=80&amp;w=800" alt="Motherboard" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="flex items-center gap-2 text-xs font-medium text-gray-400 mb-3">
                    <span class="text-[#DF5E1D] bg-[#DF5E1D]/10 px-2 py-0.5 rounded">Architecture</span>
                    <span>•</span>
                    <span>May 12, 2026</span>
                </div>
                <h3 class="text-lg font-semibold text-[#363230] mb-2 group-hover:text-[#DF5E1D] transition-colors leading-snug">
                    Understanding ARM vs x86 for Modern Workloads
                </h3>
                <p class="text-sm text-gray-500 line-clamp-2">
                    A technical comparison of architecture types and how they impact battery life, thermal throttling, and compilation times.
                </p>
            </article>

            <!-- Article 2 -->
            <article class="group cursor-pointer">
                <div class="relative h-52 rounded-xl overflow-hidden mb-5 border border-gray-100">
                    <img src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&amp;fit=crop&amp;q=80&amp;w=800" alt="Keyboard" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="flex items-center gap-2 text-xs font-medium text-gray-400 mb-3">
                    <span class="text-gray-600 bg-gray-100 px-2 py-0.5 rounded">Guide</span>
                    <span>•</span>
                    <span>May 08, 2026</span>
                </div>
                <h3 class="text-lg font-semibold text-[#363230] mb-2 group-hover:text-[#DF5E1D] transition-colors leading-snug">
                    Optimizing Your Mobile Workstation
                </h3>
                <p class="text-sm text-gray-500 line-clamp-2">
                    Essential software configurations and hardware maintenance routines to prevent performance degradation over time.
                </p>
            </article>

            <!-- Article 3 -->
            <article class="group cursor-pointer">
                <div class="relative h-52 rounded-xl overflow-hidden mb-5 border border-gray-100">
                    <img src="https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?auto=format&amp;fit=crop&amp;q=80&amp;w=800" alt="Laptop Screen" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="flex items-center gap-2 text-xs font-medium text-gray-400 mb-3">
                    <span class="text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Review</span>
                    <span>•</span>
                    <span>Apr 29, 2026</span>
                </div>
                <h3 class="text-lg font-semibold text-[#363230] mb-2 group-hover:text-[#DF5E1D] transition-colors leading-snug">
                    OLED vs Mini-LED: The Creator's Dilemma
                </h3>
                <p class="text-sm text-gray-500 line-clamp-2">
                    Analyzing color accuracy, contrast ratios, and burn-in risks for professional photo and video editors.
                </p>
            </article>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-20 bg-[#363230] border-t border-white/10 relative overflow-hidden">
    <!-- Subtle background element -->
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-[#DF5E1D] opacity-10 blur-[100px] rounded-full pointer-events-none translate-x-1/2 translate-y-1/2"></div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-3xl font-semibold tracking-tight text-white mb-4">Selangkah Lebih Dulu</h2>
        <p class="text-sm text-gray-400 mb-8 max-w-xl mx-auto">
            Dapatkan informasi stock terbaru dan penawaran diskon laptop ZLM.ID secara eksklusif.
        </p>

        <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
            <div class="relative flex-grow">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <iconify-icon icon="solar:letter-linear" class="text-gray-400"></iconify-icon>
                </div>
                <input type="email" placeholder="Email address" required="" class="w-full pl-10 pr-4 py-2.5 bg-white/5 border border-white/10 rounded-lg text-sm text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/50 focus:border-[#DF5E1D] transition-all">
            </div>
            <button type="submit" class="bg-[#DF5E1D] text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-[#c45218] transition-colors whitespace-nowrap shadow-sm">
                Subscribe
            </button>
        </form>
        <p class="text-xs text-gray-500 mt-4">We respect your inbox. Unsubscribe at any time.</p>
    </div>
</section>

<script>
    const WISHLIST_STORAGE_KEY = 'wishlistLaptops';

    function getWishlist() {
        try {
            return JSON.parse(localStorage.getItem(WISHLIST_STORAGE_KEY)) || [];
        } catch (e) {
            return [];
        }
    }

    function saveWishlist(list) {
        localStorage.setItem(WISHLIST_STORAGE_KEY, JSON.stringify(list));
        updateWishlistButtons();
    }

    function toggleWishlist(id) {
        const wishlist = getWishlist();
        const index = wishlist.indexOf(id);

        if (index > -1) {
            wishlist.splice(index, 1);
        } else {
            wishlist.push(id);
        }

        saveWishlist(wishlist);
    }

    function updateWishlistButtons() {
        const wishlist = getWishlist();
        document.querySelectorAll('[data-wishlist-btn]').forEach(btn => {
            const id = parseInt(btn.dataset.laptopId);
            if (wishlist.includes(id)) {
                btn.classList.add('text-red-500', 'bg-red-50', 'border-red-200');
                btn.classList.remove('text-gray-600', 'hover:bg-red-50');
            } else {
                btn.classList.remove('text-red-500', 'bg-red-50', 'border-red-200');
                btn.classList.add('text-gray-600');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateWishlistButtons();
        updateCompareBadge();
    });

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

    // ===== Specs Toggle =====
    function toggleSpecs(btn) {
        const container = btn.previousElementSibling;
        const textSpan = btn.querySelector('.toggle-text');
        const icon = btn.querySelector('.toggle-icon');
        
        if (container.style.maxHeight === '42px') {
            container.style.maxHeight = '500px';
            textSpan.textContent = 'See less';
            icon.setAttribute('icon', 'solar:alt-arrow-up-linear');
        } else {
            container.style.maxHeight = '42px';
            textSpan.textContent = 'See more';
            icon.setAttribute('icon', 'solar:alt-arrow-down-linear');
        }

        // Update Swiper auto height after transition
        setTimeout(() => {
            if (typeof featuredSwiperInstance !== 'undefined' && featuredSwiperInstance) {
                featuredSwiperInstance.updateAutoHeight(300);
            }
        }, 300);
    }

    // ===== Featured Slider & Tab Filtering =====
    let allSlides = []; // store raw HTML for each slide
    let featuredSwiperInstance = null;

    function initFeaturedSwiper() {
        if (featuredSwiperInstance) {
            featuredSwiperInstance.destroy(true, true);
        }
        featuredSwiperInstance = new Swiper('.featuredSwiper', {
            slidesPerView: 1,
            spaceBetween: 16,
            autoHeight: true,
            navigation: {
                nextEl: '#featured-container .swiper-button-next',
                prevEl: '#featured-container .swiper-button-prev',
            },
            breakpoints: {
                640: { slidesPerView: 2, spaceBetween: 16 },
                1024: { slidesPerView: 4, spaceBetween: 24 },
            },
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Collect all slides
        document.querySelectorAll('.featured-slide').forEach(slide => {
            // Make sure display is block in case it was hidden
            slide.style.display = 'block';
            // Ensure swiper-slide is present when stored
            slide.classList.add('swiper-slide');
            allSlides.push({
                category: slide.dataset.category,
                html: slide.outerHTML
            });
        });
        initFeaturedSwiper();
    });

    function renderFeatured(category) {
        let htmlContent = '';
        
        allSlides.forEach(item => {
            if (category === 'all' || item.category === category) {
                htmlContent += item.html;
            }
        });

        const container = document.getElementById('featured-container');

        // Render as Swiper
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = htmlContent;
        tempDiv.querySelectorAll('.featured-slide').forEach(el => el.classList.add('swiper-slide'));
        htmlContent = tempDiv.innerHTML;

        container.innerHTML = `
            <div class="swiper featuredSwiper !pb-2">
                <div class="swiper-wrapper !items-start">
                    ${htmlContent}
                </div>
            </div>
            <!-- Navigation Buttons -->
            <div class="swiper-button-prev !text-[#DF5E1D] !left-0 md:-left-6 !w-12 !h-12 !mt-[-24px] after:hidden hidden md:flex opacity-0 group-hover:opacity-100 transition-all duration-300 hover:!bg-[#DF5E1D] hover:!text-white bg-white rounded-full shadow-[0_4px_20px_rgba(0,0,0,0.1)] border border-gray-100 items-center justify-center cursor-pointer z-10">
                <iconify-icon icon="solar:alt-arrow-left-linear" class="text-2xl"></iconify-icon>
            </div>
            <div class="swiper-button-next !text-[#DF5E1D] !right-0 md:-right-6 !w-12 !h-12 !mt-[-24px] after:hidden hidden md:flex opacity-0 group-hover:opacity-100 transition-all duration-300 hover:!bg-[#DF5E1D] hover:!text-white bg-white rounded-full shadow-[0_4px_20px_rgba(0,0,0,0.1)] border border-gray-100 items-center justify-center cursor-pointer z-10">
                <iconify-icon icon="solar:alt-arrow-right-linear" class="text-2xl"></iconify-icon>
            </div>
        `;
        
        initFeaturedSwiper();
        
        // Re-apply wishlist styling since DOM was recreated
        if (typeof updateWishlistButtons === 'function') {
            updateWishlistButtons();
        }
        if (typeof updateCompareBadge === 'function') {
            updateCompareBadge();
        }
    }

    function filterFeatured(category, btn) {
        // Update styling active tab
        document.querySelectorAll('.featured-tab').forEach(t => {
            t.classList.remove('border-black', 'text-[#363230]', 'active');
            t.classList.add('border-transparent', 'text-gray-400');
        });
        btn.classList.remove('border-transparent', 'text-gray-400');
        btn.classList.add('border-black', 'text-[#363230]', 'active');

        renderFeatured(category);
    }
</script>
@include('components.floating-compare')
@endsection

