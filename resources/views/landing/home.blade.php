@extends('layouts.landing')

@section('title', 'ZLM.ID - Premium Laptop Store')

@section('content')
<!-- Hero Section -->
<div class="relative bg-[#363230] pt-20 pb-20 lg:pt-32 lg:pb-28 overflow-hidden">
    <!-- Subtle background glow -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-[#DF5E1D] opacity-20 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-center">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[#DF5E1D] text-xs font-medium mb-6">
                    <iconify-icon icon="solar:stars-linear"></iconify-icon>
                    New 2026 Models Available
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-semibold tracking-tight text-white mb-6 leading-tight">
                    Toko Laptop Bekas Berkualitas di <br>
                    <span class="text-[#DF5E1D]">Malang.</span>
                </h1>
                <p class="text-lg text-gray-400 mb-8 max-w-xl leading-relaxed">
                    ZLM.ID hadir menyediakan berbagai pilihan laptop bekas second berkualitas dengan jaminan harga dan service terbaik di Malang. Temukan perangkat impian yang sesuai dengan pekerjaan Anda!
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('landing.search') }}" class="bg-[#DF5E1D] text-white px-6 py-3 rounded-md text-sm font-medium hover:bg-[#c45218] transition shadow-sm flex items-center gap-2">
                        Explore Catalog
                        <iconify-icon icon="solar:arrow-right-linear"></iconify-icon>
                    </a>
                    <a href="#featured" class="bg-white/5 text-white border border-white/10 px-6 py-3 rounded-md text-sm font-medium hover:bg-white/10 transition flex items-center gap-2">
                        View Featured
                    </a>
                </div>
            </div>
            <div class="relative lg:ml-auto">
                <div class="relative rounded-2xl overflow-hidden border border-white/10 shadow-2xl bg-white/5 p-2">
                    <img src="https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&amp;fit=crop&amp;q=80&amp;w=1200" alt="Premium Laptop" class="w-full h-auto rounded-xl object-cover aspect-[4/3]">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Laptops Section -->
<section id="featured" class="py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <h2 class="text-3xl font-semibold tracking-tight text-[#363230] mb-2">Koleksi Pilihan ZLM.ID</h2>
                <p class="text-gray-500">Unit-unit laptop bekas pilihan dengan kualitas terbaik dan bergaransi.</p>
            </div>
            <a href="{{ route('landing.search') }}" class="text-sm font-medium text-[#DF5E1D] hover:text-[#c45218] flex items-center gap-1 group transition">
                View all models
                <iconify-icon icon="solar:arrow-right-linear" class="group-hover:translate-x-1 transition-transform"></iconify-icon>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            @foreach ($featured as $laptop)
                <div class="bg-white rounded-xl border border-gray-200/80 hover:border-gray-300 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col group relative">

                    <div class="relative h-52 bg-gray-50 overflow-hidden flex items-center justify-center p-4 border-b border-gray-100">
                        @if ($laptop->image_url)
                            <img src="{{ $laptop->image_url_full }}" alt="{{ $laptop->name }}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                        @else
                            <img src="https://placehold.co/600x400/363230/DF5E1D?text=ZLM" alt="{{ $laptop->name }}" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                        @endif

                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm border border-gray-200 text-[#363230] px-2.5 py-1 rounded-md text-xs font-medium shadow-sm">
                            {{ $laptop->categories->first()?->name ?? 'Featured' }}
                        </div>
                    </div>

                    <div class="p-5 flex flex-col flex-grow">
                        <p class="text-xs text-gray-400 font-medium tracking-wide uppercase mb-1">{{ $laptop->brand }}</p>

                        <h3 class="text-base font-semibold text-[#363230] mb-4 line-clamp-2 leading-snug group-hover:text-[#DF5E1D] transition-colors">
                            {{ $laptop->name }}
                        </h3>

                        <div class="mb-6 space-y-2.5 flex-grow">
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <iconify-icon icon="solar:cpu-linear" class="text-gray-400 text-base"></iconify-icon>
                                <span class="truncate">{{ $laptop->processor }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <iconify-icon icon="solar:ram-linear" class="text-gray-400 text-base"></iconify-icon>
                                <span>{{ $laptop->ram }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-500">
                                <iconify-icon icon="solar:database-linear" class="text-gray-400 text-base"></iconify-icon>
                                <span>{{ $laptop->storage }}</span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100">
                            <p class="text-xl font-semibold tracking-tight text-[#363230] mb-4">
                                Rp {{ number_format($laptop->price, 0, ',', '.') }}
                            </p>
                            <div class="flex gap-2 items-center">
                                <button onclick="toggleWishlist('{{ $laptop->id }}')" data-wishlist-btn data-laptop-id="{{ $laptop->id }}" class="w-9 h-9 rounded-lg border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-all" title="Add to Wishlist">
                                    <iconify-icon icon="solar:heart-linear" class="text-lg"></iconify-icon>
                                </button>

                                <button onclick="addToCompare('{{ $laptop->id }}')" data-compare-btn data-laptop-id="{{ $laptop->id }}" class="compare-btn w-9 h-9 rounded-lg border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all" title="Add to Compare">
                                    <iconify-icon icon="solar:scale-linear" class="text-lg"></iconify-icon>
                                </button>

                                <a href="{{ route('landing.detail', $laptop->id) }}" class="flex-1 px-3 py-2 rounded-lg bg-gradient-to-b from-[#DF5E1D] to-[#d05619] text-white flex items-center justify-center hover:from-[#d05619] hover:to-[#c45218] transition-all font-medium text-xs gap-1" title="View Details">
                                    <iconify-icon icon="solar:arrow-right-linear" class="text-base"></iconify-icon>
                                    <span class="hidden sm:inline">Details</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-20 bg-white border-y border-gray-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

<!-- Features / Why Choose Us -->
<section class="py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

<!-- Testimonials Section -->
<section class="py-24 bg-gray-100/50 border-t border-gray-200/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-semibold tracking-tight text-[#363230] mb-3">Apa Kata Pelanggan Kami</h2>
            <p class="text-gray-500">Ribuan orang puas berbelanja laptop second di ZLM.ID Malang.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Review 1 -->
            <div class="bg-white p-8 rounded-xl border border-gray-200/60 shadow-sm">
                <div class="flex text-[#DF5E1D] mb-4 gap-1">
                    <iconify-icon icon="solar:star-bold"></iconify-icon>
                    <iconify-icon icon="solar:star-bold"></iconify-icon>
                    <iconify-icon icon="solar:star-bold"></iconify-icon>
                    <iconify-icon icon="solar:star-bold"></iconify-icon>
                    <iconify-icon icon="solar:star-bold"></iconify-icon>
                </div>
                <p class="text-gray-600 text-sm leading-relaxed mb-6">
                    "The configuration options were exactly what my development team needed. Fast shipping and the machines arrived in pristine condition."
                </p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-sm font-medium text-gray-500 border border-gray-200">
                        SJ
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#363230]">Sarah Johnson</p>
                        <p class="text-xs text-gray-400">Software Engineer</p>
                    </div>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="bg-white p-8 rounded-xl border border-gray-200/60 shadow-sm">
                <div class="flex text-[#DF5E1D] mb-4 gap-1">
                    <iconify-icon icon="solar:star-bold"></iconify-icon>
                    <iconify-icon icon="solar:star-bold"></iconify-icon>
                    <iconify-icon icon="solar:star-bold"></iconify-icon>
                    <iconify-icon icon="solar:star-bold"></iconify-icon>
                    <iconify-icon icon="solar:star-bold"></iconify-icon>
                </div>
                <p class="text-gray-600 text-sm leading-relaxed mb-6">
                    "I used their comparison tool to find a lightweight rig for video editing on the go. The recommended XPS model has been flawless."
                </p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-sm font-medium text-gray-500 border border-gray-200">
                        MC
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#363230]">Mike Chen</p>
                        <p class="text-xs text-gray-400">Content Creator</p>
                    </div>
                </div>
            </div>

            <!-- Review 3 -->
            <div class="bg-white p-8 rounded-xl border border-gray-200/60 shadow-sm">
                <div class="flex text-[#DF5E1D] mb-4 gap-1">
                    <iconify-icon icon="solar:star-bold"></iconify-icon>
                    <iconify-icon icon="solar:star-bold"></iconify-icon>
                    <iconify-icon icon="solar:star-bold"></iconify-icon>
                    <iconify-icon icon="solar:star-bold"></iconify-icon>
                    <iconify-icon icon="solar:star-bold"></iconify-icon>
                </div>
                <p class="text-gray-600 text-sm leading-relaxed mb-6">
                    "Their corporate procurement process is seamless. Deployed 50 ThinkPads across our new branch without a single hitch or delay."
                </p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-sm font-medium text-gray-500 border border-gray-200">
                        ED
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#363230]">Emma Davis</p>
                        <p class="text-xs text-gray-400">IT Director</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Insights / Blog Section -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
</script>
@include('components.floating-compare')
@endsection

