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
                    Find your perfect <br>
                    <span class="text-[#DF5E1D]">workstation.</span>
                </h1>
                <p class="text-lg text-gray-400 mb-8 max-w-xl leading-relaxed">
                    Discover engineered excellence. From high-performance gaming rigs to ultra-portable business machines, find the device that matches your ambition.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#" class="bg-[#DF5E1D] text-white px-6 py-3 rounded-md text-sm font-medium hover:bg-[#c45218] transition shadow-sm flex items-center gap-2">
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
                <h2 class="text-3xl font-semibold tracking-tight text-[#363230] mb-2">Featured Collection</h2>
                <p class="text-gray-500">Handpicked devices for uncompromising performance.</p>
            </div>
            <a href="#" class="text-sm font-medium text-[#DF5E1D] hover:text-[#c45218] flex items-center gap-1 group transition">
                View all models
                <iconify-icon icon="solar:arrow-right-linear" class="group-hover:translate-x-1 transition-transform"></iconify-icon>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <!-- Product Card 1 -->
            <div class="bg-white rounded-xl border border-gray-200/80 hover:border-gray-300 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col group relative">

                <!-- Image -->
                <div class="relative h-52 bg-gray-50 overflow-hidden flex items-center justify-center p-4 border-b border-gray-100">
                    <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&amp;fit=crop&amp;q=80&amp;w=800" alt="MacBook Pro" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500">

                    <!-- Badge -->
                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm border border-gray-200 text-[#363230] px-2.5 py-1 rounded-md text-xs font-medium shadow-sm">
                        Bestseller
                    </div>
                </div>

                <!-- Content -->
                <div class="p-5 flex flex-col flex-grow">
                    <!-- Brand -->
                    <p class="text-xs text-gray-400 font-medium tracking-wide uppercase mb-1">Apple</p>

                    <!-- Title -->
                    <h3 class="text-base font-semibold text-[#363230] mb-4 line-clamp-2 leading-snug group-hover:text-[#DF5E1D] transition-colors">
                        MacBook Pro 16" M3 Max
                    </h3>

                    <!-- Specs (Using Icons) -->
                    <div class="mb-6 space-y-2.5 flex-grow">
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <iconify-icon icon="solar:cpu-linear" class="text-gray-400 text-base"></iconify-icon>
                            <span class="truncate">Apple M3 Max 16-core</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <iconify-icon icon="solar:ram-linear" class="text-gray-400 text-base"></iconify-icon>
                            <span>36GB Unified</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <iconify-icon icon="solar:database-linear" class="text-gray-400 text-base"></iconify-icon>
                            <span>1TB SSD</span>
                        </div>
                    </div>

                    <!-- Price & Actions -->
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-xl font-semibold tracking-tight text-[#363230] mb-4">
                            $3,499
                        </p>
                        <div class="flex gap-2 items-center">
                            <!-- Wishlist Button -->
                            <button onclick="toggleWishlist(1)" data-wishlist-btn data-laptop-id="1" class="w-9 h-9 rounded-lg border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-all" title="Add to Wishlist">
                                <iconify-icon icon="solar:heart-linear" class="text-lg"></iconify-icon>
                            </button>

                            <!-- Add to Compare Button -->
                            <button onclick="addToCompare(1, 'MacBook Pro 16 M3 Max', 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&q=80&w=800')" class="w-9 h-9 rounded-lg border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all" title="Add to Compare">
                                <iconify-icon icon="solar:scale-linear" class="text-lg"></iconify-icon>
                            </button>

                            <!-- View Details Button -->
                            <a href="/laptop/1" class="flex-1 px-3 py-2 rounded-lg bg-gradient-to-b from-[#DF5E1D] to-[#d05619] text-white flex items-center justify-center hover:from-[#d05619] hover:to-[#c45218] transition-all font-medium text-xs gap-1" title="View Details">
                                <iconify-icon icon="solar:arrow-right-linear" class="text-base"></iconify-icon>
                                <span class="hidden sm:inline">Details</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Card 2 -->
            <div class="bg-white rounded-xl border border-gray-200/80 hover:border-gray-300 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col group relative">

                <!-- Image -->
                <div class="relative h-52 bg-gray-50 overflow-hidden flex items-center justify-center p-4 border-b border-gray-100">
                    <img src="https://images.unsplash.com/photo-1600861194942-f883de0dfe96?auto=format&amp;fit=crop&amp;q=80&amp;w=800" alt="Razer Blade" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500">

                    <!-- Badge -->
                    <div class="absolute top-3 right-3 bg-[#DF5E1D] text-white px-2.5 py-1 rounded-md text-xs font-medium shadow-sm">
                        Featured
                    </div>
                </div>

                <!-- Content -->
                <div class="p-5 flex flex-col flex-grow">
                    <!-- Brand -->
                    <p class="text-xs text-gray-400 font-medium tracking-wide uppercase mb-1">Razer</p>

                    <!-- Title -->
                    <h3 class="text-base font-semibold text-[#363230] mb-4 line-clamp-2 leading-snug group-hover:text-[#DF5E1D] transition-colors">
                        Blade 16 Advanced
                    </h3>

                    <!-- Specs (Using Icons) -->
                    <div class="mb-6 space-y-2.5 flex-grow">
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <iconify-icon icon="solar:cpu-linear" class="text-gray-400 text-base"></iconify-icon>
                            <span class="truncate">Intel Core i9-14900HX</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <iconify-icon icon="solar:ram-linear" class="text-gray-400 text-base"></iconify-icon>
                            <span>32GB DDR5</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <iconify-icon icon="solar:database-linear" class="text-gray-400 text-base"></iconify-icon>
                            <span>2TB PCIe 4.0</span>
                        </div>
                    </div>

                    <!-- Price & Actions -->
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-xl font-semibold tracking-tight text-[#363230] mb-4">
                            $4,299
                        </p>
                        <div class="flex gap-2 items-center">
                            <!-- Wishlist Button -->
                            <button onclick="toggleWishlist(2)" data-wishlist-btn data-laptop-id="2" class="w-9 h-9 rounded-lg border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-all" title="Add to Wishlist">
                                <iconify-icon icon="solar:heart-linear" class="text-lg"></iconify-icon>
                            </button>

                            <!-- Add to Compare Button -->
                            <button onclick="addToCompare(2, 'Razer Blade 16 Advanced', 'https://images.unsplash.com/photo-1600861194942-f883de0dfe96?auto=format&fit=crop&q=80&w=800')" class="w-9 h-9 rounded-lg border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all" title="Add to Compare">
                                <iconify-icon icon="solar:scale-linear" class="text-lg"></iconify-icon>
                            </button>

                            <!-- View Details Button -->
                            <a href="/laptop/2" class="flex-1 px-3 py-2 rounded-lg bg-gradient-to-b from-[#DF5E1D] to-[#d05619] text-white flex items-center justify-center hover:from-[#d05619] hover:to-[#c45218] transition-all font-medium text-xs gap-1" title="View Details">
                                <iconify-icon icon="solar:arrow-right-linear" class="text-base"></iconify-icon>
                                <span class="hidden sm:inline">Details</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Card 3 -->
            <div class="bg-white rounded-xl border border-gray-200/80 hover:border-gray-300 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col group relative">

                <!-- Image -->
                <div class="relative h-52 bg-gray-50 overflow-hidden flex items-center justify-center p-4 border-b border-gray-100">
                    <img src="https://images.unsplash.com/photo-1531297484001-80022131f5a1?auto=format&amp;fit=crop&amp;q=80&amp;w=800" alt="Dell XPS" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500">

                    <!-- Badge -->
                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm border border-gray-200 text-[#363230] px-2.5 py-1 rounded-md text-xs font-medium shadow-sm">
                        Ultrabook
                    </div>
                </div>

                <!-- Content -->
                <div class="p-5 flex flex-col flex-grow">
                    <!-- Brand -->
                    <p class="text-xs text-gray-400 font-medium tracking-wide uppercase mb-1">Dell</p>

                    <!-- Title -->
                    <h3 class="text-base font-semibold text-[#363230] mb-4 line-clamp-2 leading-snug group-hover:text-[#DF5E1D] transition-colors">
                        XPS 14 OLED
                    </h3>

                    <!-- Specs (Using Icons) -->
                    <div class="mb-6 space-y-2.5 flex-grow">
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <iconify-icon icon="solar:cpu-linear" class="text-gray-400 text-base"></iconify-icon>
                            <span class="truncate">Intel Core Ultra 7</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <iconify-icon icon="solar:ram-linear" class="text-gray-400 text-base"></iconify-icon>
                            <span>16GB LPDDR5X</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-gray-500">
                            <iconify-icon icon="solar:database-linear" class="text-gray-400 text-base"></iconify-icon>
                            <span>512GB SSD</span>
                        </div>
                    </div>

                    <!-- Price & Actions -->
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-xl font-semibold tracking-tight text-[#363230] mb-4">
                            $1,699
                        </p>
                        <div class="flex gap-2 items-center">
                            <!-- Wishlist Button -->
                            <button onclick="toggleWishlist(3)" data-wishlist-btn data-laptop-id="3" class="w-9 h-9 rounded-lg border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-all" title="Add to Wishlist">
                                <iconify-icon icon="solar:heart-linear" class="text-lg"></iconify-icon>
                            </button>

                            <!-- Add to Compare Button -->
                            <button onclick="addToCompare(3, 'Dell XPS 14 OLED', 'https://images.unsplash.com/photo-1531297484001-80022131f5a1?auto=format&fit=crop&q=80&w=800')" class="w-9 h-9 rounded-lg border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all" title="Add to Compare">
                                <iconify-icon icon="solar:scale-linear" class="text-lg"></iconify-icon>
                            </button>

                            <!-- View Details Button -->
                            <a href="/laptop/3" class="flex-1 px-3 py-2 rounded-lg bg-gradient-to-b from-[#DF5E1D] to-[#d05619] text-white flex items-center justify-center hover:from-[#d05619] hover:to-[#c45218] transition-all font-medium text-xs gap-1" title="View Details">
                                <iconify-icon icon="solar:arrow-right-linear" class="text-base"></iconify-icon>
                                <span class="hidden sm:inline">Details</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-20 bg-white border-y border-gray-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 max-w-2xl mx-auto">
            <h2 class="text-3xl font-semibold tracking-tight text-[#363230] mb-3">Shop by Architecture</h2>
            <p class="text-gray-500">Filter through our catalog based on your specific computing requirements.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <!-- Category 1 -->
            <a href="#" class="group block p-6 bg-gray-50 rounded-xl border border-gray-200/60 hover:bg-white hover:border-[#DF5E1D]/50 hover:shadow-sm transition-all duration-300">
                <div class="w-12 h-12 bg-white rounded-lg border border-gray-200 flex items-center justify-center mb-4 group-hover:text-[#DF5E1D] transition-colors shadow-sm">
                    <iconify-icon icon="solar:gamepad-linear" class="text-2xl"></iconify-icon>
                </div>
                <h3 class="text-base font-semibold text-[#363230] mb-1">Gaming</h3>
                <p class="text-sm text-gray-500">High refresh rates &amp; discrete GPUs.</p>
            </a>

            <!-- Category 2 -->
            <a href="#" class="group block p-6 bg-gray-50 rounded-xl border border-gray-200/60 hover:bg-white hover:border-[#DF5E1D]/50 hover:shadow-sm transition-all duration-300">
                <div class="w-12 h-12 bg-white rounded-lg border border-gray-200 flex items-center justify-center mb-4 group-hover:text-[#DF5E1D] transition-colors shadow-sm">
                    <iconify-icon icon="solar:briefcase-linear" class="text-2xl"></iconify-icon>
                </div>
                <h3 class="text-base font-semibold text-[#363230] mb-1">Business</h3>
                <p class="text-sm text-gray-500">Enterprise security &amp; reliability.</p>
            </a>

            <!-- Category 3 -->
            <a href="#" class="group block p-6 bg-gray-50 rounded-xl border border-gray-200/60 hover:bg-white hover:border-[#DF5E1D]/50 hover:shadow-sm transition-all duration-300">
                <div class="w-12 h-12 bg-white rounded-lg border border-gray-200 flex items-center justify-center mb-4 group-hover:text-[#DF5E1D] transition-colors shadow-sm">
                    <iconify-icon icon="solar:diploma-linear" class="text-2xl"></iconify-icon>
                </div>
                <h3 class="text-base font-semibold text-[#363230] mb-1">Student</h3>
                <p class="text-sm text-gray-500">Value, battery life &amp; portability.</p>
            </a>

            <!-- Category 4 -->
            <a href="#" class="group block p-6 bg-gray-50 rounded-xl border border-gray-200/60 hover:bg-white hover:border-[#DF5E1D]/50 hover:shadow-sm transition-all duration-300">
                <div class="w-12 h-12 bg-white rounded-lg border border-gray-200 flex items-center justify-center mb-4 group-hover:text-[#DF5E1D] transition-colors shadow-sm">
                    <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-2xl"></iconify-icon>
                </div>
                <h3 class="text-base font-semibold text-[#363230] mb-1">Ultrabook</h3>
                <p class="text-sm text-gray-500">Thin, lightweight &amp; premium build.</p>
            </a>
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
                <h3 class="text-base font-semibold text-[#363230] mb-2">Verified Hardware</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Every unit is physically inspected and tested to ensure peak performance before shipping.</p>
            </div>

            <div class="flex flex-col">
                <div class="w-10 h-10 rounded text-[#DF5E1D] flex items-center mb-4">
                    <iconify-icon icon="solar:routing-linear" class="text-3xl"></iconify-icon>
                </div>
                <h3 class="text-base font-semibold text-[#363230] mb-2">Express Delivery</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Next-day shipping available on all in-stock items with secure, insured packaging.</p>
            </div>

            <div class="flex flex-col">
                <div class="w-10 h-10 rounded text-[#DF5E1D] flex items-center mb-4">
                    <iconify-icon icon="solar:tag-price-linear" class="text-3xl"></iconify-icon>
                </div>
                <h3 class="text-base font-semibold text-[#363230] mb-2">Price Match</h3>
                <p class="text-sm text-gray-500 leading-relaxed">We dynamically monitor market prices to ensure you always get the most competitive rate.</p>
            </div>

            <div class="flex flex-col">
                <div class="w-10 h-10 rounded text-[#DF5E1D] flex items-center mb-4">
                    <iconify-icon icon="solar:chat-round-dots-linear" class="text-3xl"></iconify-icon>
                </div>
                <h3 class="text-base font-semibold text-[#363230] mb-2">Expert Support</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Access to dedicated technical staff for configuration assistance and troubleshooting.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-24 bg-gray-100/50 border-t border-gray-200/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-semibold tracking-tight text-[#363230] mb-3">Client Feedback</h2>
            <p class="text-gray-500">Hear from professionals who rely on our hardware.</p>
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
                <h2 class="text-3xl font-semibold tracking-tight text-[#363230] mb-2">Hardware Insights</h2>
                <p class="text-gray-500">Technical deep dives and buying guides.</p>
            </div>
            <a href="#" class="text-sm font-medium text-[#DF5E1D] hover:text-[#c45218] flex items-center gap-1 group transition">
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
        <h2 class="text-3xl font-semibold tracking-tight text-white mb-4">Stay at the forefront</h2>
        <p class="text-sm text-gray-400 mb-8 max-w-xl mx-auto">
            Subscribe to receive early access to new releases, technical breakdowns, and exclusive procurement offers.
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
    // Local Storage Management for Compare and Wishlist
    const COMPARE_STORAGE_KEY = 'laptopsToCompare';
    const WISHLIST_STORAGE_KEY = 'wishlistLaptops';

    function getCompareList() {
        try {
            return JSON.parse(localStorage.getItem(COMPARE_STORAGE_KEY)) || [];
        } catch (e) {
            return [];
        }
    }

    function saveCompareList(list) {
        localStorage.setItem(COMPARE_STORAGE_KEY, JSON.stringify(list));
    }

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

    function addToCompare(id, name, image) {
        let compareList = getCompareList();

        if (compareList.length >= 2) {
            const existingIndex = compareList.findIndex(item => item.id === id);
            if (existingIndex === -1) {
                showToast('Maximum 2 items can be compared', 'info');
                return;
            }
        }

        if (!compareList.find(item => item.id === id)) {
            compareList.push({ id, name, image });
            saveCompareList(compareList);
            showToast(`${name} added to compare`, 'success');
        }
    }

    function removeFromCompare(id) {
        let compareList = getCompareList();
        compareList = compareList.filter(item => item.id !== id);
        saveCompareList(compareList);
    }

    function clearAllCompare() {
        localStorage.removeItem(COMPARE_STORAGE_KEY);
        showToast('Compare list cleared', 'success');
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-4 py-3 rounded-lg text-white text-sm font-medium shadow-lg z-50 transition-all duration-300 ${
            type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Initialize wishlist buttons on page load
    document.addEventListener('DOMContentLoaded', updateWishlistButtons);
</script>
@endsection
