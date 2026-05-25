
@extends('layouts.landing')

@section('title', 'Search Laptops')

@section('content')
<div class="bg-gray-50 min-h-screen py-12 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-12">
            <h1 class="text-3xl md:text-4xl font-semibold tracking-tight text-[#363230] mb-3">Find Your Perfect Laptop</h1>
            <p class="text-gray-500 max-w-2xl text-sm md:text-base leading-relaxed">Browse our collection of premium engineered devices. Use the filters to find the exact specifications you need.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
            <!-- Sidebar Filters -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm hover:shadow-md p-6 sticky top-20 z-40 backdrop-blur-sm bg-white/95 transition-all duration-300">
                    <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-lg bg-[#DF5E1D]/10 flex items-center justify-center">
                            <iconify-icon icon="solar:filter-linear" class="text-[#DF5E1D] text-lg"></iconify-icon>
                        </div>
                        <h3 class="text-lg font-semibold tracking-tight text-[#363230]">Filters</h3>
                    </div>

                    <form method="GET" action="{{ route('landing.search') }}" class="space-y-6">
                        <!-- Search -->
                        <div>
                            <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-2">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                                </div>
                                <input type="text" name="search" placeholder="Laptop, processor..." value="{{ request('search') }}" class="w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div class="pt-4 border-t border-gray-100">
                            <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-3">Category</label>
                            <div class="space-y-3">
                                <label class="flex items-center group cursor-pointer">
                                    <input type="radio" name="category" value="all" @checked(!request('category')) class="w-4 h-4 text-[#DF5E1D] bg-gray-100 border-gray-300 focus:ring-[#DF5E1D] accent-[#DF5E1D] cursor-pointer transition-all">
                                    <span class="ml-3 text-sm text-gray-600 group-hover:text-[#363230] transition-colors">All Categories</span>
                                </label>
                                @foreach ($categories as $cat)
                                    <label class="flex items-center group cursor-pointer">
                                        <input type="radio" name="category" value="{{ $cat->slug }}" @checked(request('category') === $cat->slug) class="w-4 h-4 text-[#DF5E1D] bg-gray-100 border-gray-300 focus:ring-[#DF5E1D] accent-[#DF5E1D] cursor-pointer transition-all">
                                        <span class="ml-3 text-sm text-gray-600 group-hover:text-[#363230] transition-colors">{{ $cat->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Brand Filter -->
                        <div class="pt-4 border-t border-gray-100">
                            <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-3">Brand</label>
                            <div class="relative">
                                <select name="brand" class="w-full pl-3 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 appearance-none focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all cursor-pointer">
                                    <option value="">All Brands</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand }}" @selected(request('brand') === $brand)>{{ $brand }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                                    <iconify-icon icon="solar:alt-arrow-down-linear"></iconify-icon>
                                </div>
                            </div>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="pt-4 border-t border-gray-100">
                            <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-3">Price Range</label>
                            <div class="flex items-center gap-2 mb-2">
                                <input type="number" name="min_price" placeholder="Min Rp" value="{{ request('min_price') }}" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                                <span class="text-gray-400">-</span>
                                <input type="number" name="max_price" placeholder="Max Rp" value="{{ request('max_price') }}" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                            </div>
                            <p class="text-xs text-gray-400">Max: Rp {{ number_format($maxPrice, 0, ',', '.') }}</p>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="pt-6 space-y-3">
                            <button type="submit" class="w-full bg-[#DF5E1D] text-white py-2.5 rounded-lg text-sm font-medium hover:bg-[#c45218] transition-colors shadow-sm flex items-center justify-center gap-2">
                                Apply Filters
                            </button>
                            <a href="{{ route('landing.search') }}" class="w-full block text-center bg-gray-50 border border-gray-200 text-gray-600 py-2.5 rounded-lg text-sm font-medium hover:bg-white hover:text-[#363230] hover:border-gray-300 transition-colors">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="lg:col-span-3">
                <!-- Results Info -->
                <div class="mb-6 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                    <p class="text-sm text-gray-500">
                        Showing <span class="font-semibold text-[#363230]">{{ $laptops->count() }}</span> of
                        <span class="font-semibold text-[#363230]">{{ $laptops->total() }}</span> results
                    </p>
                    <form method="GET" action="{{ route('landing.search') }}" class="inline-block">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        @if(request('brand'))
                            <input type="hidden" name="brand" value="{{ request('brand') }}">
                        @endif
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        @if(request('min_price'))
                            <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                        @endif
                        @if(request('max_price'))
                            <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                        @endif
                        <div class="relative inline-block w-48">
                            <select name="sort" onchange="this.form.submit()" class="w-full pl-3 pr-10 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-600 appearance-none focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all cursor-pointer shadow-sm">
                                <option value="latest" @selected(request('sort') == 'latest')>Latest Arrivals</option>
                                <option value="price_asc" @selected(request('sort') == 'price_asc')>Price: Low to High</option>
                                <option value="price_desc" @selected(request('sort') == 'price_desc')>Price: High to Low</option>
                                <option value="popular" @selected(request('sort') == 'popular')>Most Popular</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                                <iconify-icon icon="solar:sort-from-top-to-bottom-linear"></iconify-icon>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Laptop Cards Grid -->
                @if ($laptops->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($laptops as $laptop)
                            <div class="bg-white rounded-xl border border-gray-200/80 hover:border-gray-300 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col group relative">

                                <!-- Stock Status Overlay -->
                                @if ($laptop->stock === 0)
                                    <div class="absolute inset-0 z-10 bg-white/60 backdrop-blur-[2px] flex items-center justify-center">
                                        <div class="bg-gray-900 text-white px-4 py-2 rounded-md text-sm font-semibold shadow-xl tracking-wide">
                                            Out of Stock
                                        </div>
                                    </div>
                                @endif

                                <!-- Image -->
                                <div class="relative h-52 bg-gray-50 overflow-hidden flex items-center justify-center p-4 border-b border-gray-100">
                                    @if ($laptop->image_url)
                                        <img src="{{ $laptop->image_url }}" alt="{{ $laptop->name }}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <!-- Minimalist high-quality placeholder from Unsplash -->
                                        <img src="https://placehold.co/600x400/363230/DF5E1D?text=ZLM" alt="{{ $laptop->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 rounded">
                                    @endif

                                    <!-- Badge -->
                                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm border border-gray-200 text-[#363230] px-2.5 py-1 rounded-md text-xs font-medium shadow-sm">
                                        {{ $laptop->categories->first()?->name ?? 'General' }}
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-5 flex flex-col flex-grow">
                                    <!-- Brand -->
                                    <p class="text-xs text-gray-400 font-medium tracking-wide uppercase mb-1">{{ $laptop->brand }}</p>

                                    <!-- Title -->
                                    <h3 class="text-base font-semibold text-[#363230] mb-4 line-clamp-2 leading-snug group-hover:text-[#DF5E1D] transition-colors">
                                        {{ $laptop->name }}
                                    </h3>

                                    <!-- Specs (Using Icons) -->
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

                                    <!-- Price & Actions -->
                                    <div class="pt-4 border-t border-gray-100">
                                        <p class="text-xl font-semibold tracking-tight text-[#363230] mb-4">
                                            Rp {{ number_format($laptop->price, 0, ',', '.') }}
                                        </p>
                                        <div class="flex gap-2 items-center">
                                            <!-- Wishlist Button -->
                                            <button onclick="toggleWishlist({{ $laptop->id }})" data-wishlist-btn data-laptop-id="{{ $laptop->id }}" class="w-9 h-9 rounded-lg border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-all" title="Add to Wishlist">
                                                <iconify-icon icon="solar:heart-linear" class="text-lg"></iconify-icon>
                                            </button>

                                            <!-- Add to Compare Button -->
                                            <button onclick="addToCompare('{{ $laptop->id }}')" data-compare-btn data-laptop-id="{{ $laptop->id }}" class="compare-btn w-9 h-9 rounded-lg border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all" title="Add to Compare">
                                                <iconify-icon icon="solar:scale-linear" class="text-lg"></iconify-icon>
                                            </button>

                                            <!-- View Details Button -->
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

                    <!-- Pagination -->
                    <div class="mt-12 flex justify-center">
                        {{ $laptops->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-16 text-center flex flex-col items-center justify-center min-h-[400px]">
                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 text-gray-400 border border-gray-100">
                            <iconify-icon icon="solar:ghost-linear" class="text-3xl"></iconify-icon>
                        </div>
                        <h3 class="text-lg font-semibold tracking-tight text-[#363230] mb-2">No hardware found</h3>
                        <p class="text-sm text-gray-500 mb-6 max-w-sm">We couldn't find any laptops matching your current filters. Try adjusting your parameters or clear all filters.</p>
                        <a href="{{ route('landing.search') }}" class="inline-flex items-center gap-2 bg-gray-50 border border-gray-200 text-gray-600 px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-white hover:text-[#363230] hover:border-gray-300 transition-colors">
                            <iconify-icon icon="solar:refresh-linear"></iconify-icon>
                            Clear All Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    const WISHLIST_STORAGE_KEY = 'wishlistLaptops';

    function toggleWishlist(id) {
        let wishlist = JSON.parse(localStorage.getItem(WISHLIST_STORAGE_KEY)) || [];
        const index = wishlist.indexOf(id);

        if (index > -1) {
            wishlist.splice(index, 1);
            showToast('Removed from wishlist');
        } else {
            wishlist.push(id);
            showToast('Added to wishlist!');
        }

        localStorage.setItem(WISHLIST_STORAGE_KEY, JSON.stringify(wishlist));
        updateWishlistButtons();
    }

    function updateWishlistButtons() {
        const wishlist = JSON.parse(localStorage.getItem(WISHLIST_STORAGE_KEY)) || [];
        document.querySelectorAll('[data-wishlist-btn]').forEach(btn => {
            const laptopId = parseInt(btn.dataset.laptopId);
            if (wishlist.includes(laptopId)) {
                btn.classList.add('bg-red-50', 'text-red-500', 'border-red-200');
                btn.classList.remove('bg-white', 'text-gray-600', 'border-gray-200');
            } else {
                btn.classList.remove('bg-red-50', 'text-red-500', 'border-red-200');
                btn.classList.add('bg-white', 'text-gray-600', 'border-gray-200');
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
