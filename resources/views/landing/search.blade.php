
@extends('layouts.landing')

@section('title', 'Cari Laptop')

@section('content')
<div class="bg-gray-50 min-h-screen py-12 lg:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-12">
            <h1 class="text-3xl md:text-4xl font-semibold tracking-tight text-[#363230] mb-3">Temukan Laptop Idamanmu</h1>
            <p class="text-gray-500 max-w-2xl text-sm md:text-base leading-relaxed">Jelajahi koleksi laptop bekas berkualitas tinggi kami. Gunakan filter untuk menemukan spesifikasi yang paling cocok dengan kebutuhanmu.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
            <!-- Sidebar Filters -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm hover:shadow-md p-6 sticky top-20 z-40 backdrop-blur-sm bg-white/95 transition-all duration-300">
                    <div class="flex items-center gap-2 mb-6 pb-4 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-lg bg-[#DF5E1D]/10 flex items-center justify-center">
                            <iconify-icon icon="solar:filter-linear" class="text-[#DF5E1D] text-lg"></iconify-icon>
                        </div>
                        <h3 class="text-lg font-semibold tracking-tight text-[#363230]">Filter Set</h3>
                    </div>

                    <form method="GET" action="{{ route('landing.search') }}" class="space-y-6">
                        <!-- Search -->
                        <div>
                            <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-2">Pencarian</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                    <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                                </div>
                                <input type="text" name="search" placeholder="Laptop, prosesor..." value="{{ request('search') }}" class="w-full pl-9 pr-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div class="pt-4 border-t border-gray-100">
                            <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-3">Kategori</label>
                            <div class="space-y-3">
                                <label class="flex items-center group cursor-pointer">
                                    <input type="radio" name="category" value="all" @checked(!request('category')) class="w-4 h-4 text-[#DF5E1D] bg-gray-100 border-gray-300 focus:ring-[#DF5E1D] accent-[#DF5E1D] cursor-pointer transition-all">
                                    <span class="ml-3 text-sm text-gray-600 group-hover:text-[#363230] transition-colors">Semua Kategori</span>
                                </label>
                                @foreach (['gaming' => 'Gaming', 'business' => 'Business', 'student' => 'Student', 'ultrabook' => 'Ultrabook'] as $value => $label)
                                    <label class="flex items-center group cursor-pointer">
                                        <input type="radio" name="category" value="{{ $value }}" @checked(request('category') === $value) class="w-4 h-4 text-[#DF5E1D] bg-gray-100 border-gray-300 focus:ring-[#DF5E1D] accent-[#DF5E1D] cursor-pointer transition-all">
                                        <span class="ml-3 text-sm text-gray-600 group-hover:text-[#363230] transition-colors">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Brand Filter -->
                        <div class="pt-4 border-t border-gray-100">
                            <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-3">Merek</label>
                            <div class="relative">
                                <select name="brand" class="w-full pl-3 pr-10 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 appearance-none focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all cursor-pointer">
                                    <option value="">Semua Merek</option>
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
                            <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-3">Rentang Harga</label>
                            <div class="flex items-center gap-2 mb-2">
                                <input type="number" name="min_price" placeholder="Min $" value="{{ request('min_price') }}" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                                <span class="text-gray-400">-</span>
                                <input type="number" name="max_price" placeholder="Max $" value="{{ request('max_price') }}" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
                            </div>
                            <p class="text-xs text-gray-400">Batas maks: Rp {{ number_format($maxPrice, 0) }}</p>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="pt-6 space-y-3">
                            <button type="submit" class="w-full bg-[#DF5E1D] text-white py-2.5 rounded-lg text-sm font-medium hover:bg-[#c45218] transition-colors shadow-sm flex items-center justify-center gap-2">
                                Terapkan Filter
                            </button>
                            <a href="{{ route('landing.search') }}" class="w-full block text-center bg-gray-50 border border-gray-200 text-gray-600 py-2.5 rounded-lg text-sm font-medium hover:bg-white hover:text-[#363230] hover:border-gray-300 transition-colors">
                                Atur Ulang
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
                        Menampilkan <span class="font-semibold text-[#363230]">{{ $products->count() }}</span> dari
                        <span class="font-semibold text-[#363230]">{{ $products->total() }}</span> hasil
                    </p>
                    <div class="relative inline-block w-48">
                        <select class="w-full pl-3 pr-10 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-600 appearance-none focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all cursor-pointer shadow-sm">
                            <option>Produk Terbaru</option>
                            <option>Harga: Rendah ke Tinggi</option>
                            <option>Harga: Tinggi ke Rendah</option>
                            <option>Paling Populer</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                            <iconify-icon icon="solar:sort-from-top-to-bottom-linear"></iconify-icon>
                        </div>
                    </div>
                </div>

                <!-- Laptop Cards Grid -->
                @if ($products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($products as $laptop)
                            <div class="bg-white rounded-xl border border-gray-200/80 hover:border-gray-300 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col group relative">

                                <!-- Stock Status Overlay -->
                                @if ($laptop->stock === 0)
                                    <div class="absolute inset-0 z-10 bg-white/60 backdrop-blur-[2px] flex items-center justify-center">
                                        <div class="bg-gray-900 text-white px-4 py-2 rounded-md text-sm font-semibold shadow-xl tracking-wide">
                                            Stok Habis
                                        </div>
                                    </div>
                                @endif

                                <!-- Image -->
                                <div class="relative h-52 bg-gray-50 overflow-hidden flex items-center justify-center border-b border-gray-100">
                                    @if ($laptop->image_url)
                                        <img src="{{ $laptop->image_url }}" alt="{{ $laptop->name }}" class="w-full h-full object-cover mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <!-- Minimalist high-quality placeholder from Unsplash -->
                                        <img src="https://images.unsplash.com/photo-1531297484001-80022131f5a1?auto=format&fit=crop&q=80&w=600" alt="{{ $laptop->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 rounded">
                                    @endif

                                    <!-- Badge -->
                                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm border border-gray-200 text-[#363230] px-2.5 py-1 rounded-md text-xs font-medium shadow-sm">
                                        {{ ucfirst($laptop->category) }}
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
                                            Rp{{ number_format($laptop->price, 0, ',', '.') }}
                                        </p>
                                        <div class="flex gap-2 items-center">
                                            <!-- Wishlist Button -->
                                            <button onclick="toggleWishlist({{ $laptop->id }})" data-wishlist-btn data-laptop-id="{{ $laptop->id }}" class="w-9 h-9 rounded-lg border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-all" title="Add to Wishlist">
                                                <iconify-icon icon="solar:heart-linear" class="text-lg"></iconify-icon>
                                            </button>

                                            <!-- Add to Compare Button -->
                                            <button onclick="addToCompare({{ $laptop->id }}, '{{ $laptop->name }}', '{{ $laptop->image_url }}')" class="w-9 h-9 rounded-lg border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all" title="Add to Compare">
                                                <iconify-icon icon="solar:scale-linear" class="text-lg"></iconify-icon>
                                            </button>

                                            <!-- View Details Button -->
                                            <a href="{{ route('landing.detail', $laptop->id) }}" class="flex-1 px-3 py-2 rounded-lg bg-gradient-to-b from-[#DF5E1D] to-[#d05619] text-white flex items-center justify-center hover:from-[#d05619] hover:to-[#c45218] transition-all font-medium text-xs gap-1" title="View Details">
                                                <iconify-icon icon="solar:arrow-right-linear" class="text-base"></iconify-icon>
                                                <span class="hidden sm:inline">Detail</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12 flex justify-center">
                        {{ $products->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm p-16 text-center flex flex-col items-center justify-center min-h-[400px]">
                        <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 text-gray-400 border border-gray-100">
                            <iconify-icon icon="solar:ghost-linear" class="text-3xl"></iconify-icon>
                        </div>
                        <h3 class="text-lg font-semibold tracking-tight text-[#363230] mb-2">Produk Tidak Ditemukan</h3>
                        <p class="text-sm text-gray-500 mb-6 max-w-sm">Kami tidak menemukan laptop yang sesuai dengan filter Anda. Silakan atur ulang parameter filter atau hapus semua pencarian.</p>
                        <a href="{{ route('landing.search') }}" class="inline-flex items-center gap-2 bg-gray-50 border border-gray-200 text-gray-600 px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-white hover:text-[#363230] hover:border-gray-300 transition-colors">
                            <iconify-icon icon="solar:refresh-linear"></iconify-icon>
                            Hapus Semua Filter
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Floating Compare Card -->
<div id="floatingCompare" class="fixed bottom-8 right-8 z-50 opacity-0 translate-y-4 transform transition-all duration-300 pointer-events-none w-80 max-w-[calc(100vw-2rem)]">
    <div class="bg-white rounded-2xl shadow-2xl border border-gray-200 p-4 pointer-events-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-[#DF5E1D]/10 flex items-center justify-center">
                    <iconify-icon icon="solar:scale-linear" class="text-[#DF5E1D] text-base"></iconify-icon>
                </div>
                <div>
                    <p class="text-xs text-gray-500 font-medium">Bandingkan</p>
                    <p class="text-sm font-semibold text-[#363230]"><span id="compareCount">0</span>/2</p>
                </div>
            </div>
            <button onclick="toggleFloatingCard(false)" class="w-6 h-6 rounded-md text-gray-400 hover:bg-gray-100 hover:text-gray-600 flex items-center justify-center transition-all">
                <iconify-icon icon="solar:close-circle-linear" class="text-lg"></iconify-icon>
            </button>
        </div>

        <!-- Compare Items List -->
        <div id="compareList" class="space-y-2 mb-4 max-h-56 overflow-y-auto">
            <!-- Items will be inserted here by JavaScript -->
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-2 pt-3 border-t border-gray-100">
            <a href="{{ route('landing.compare') }}" class="flex-1 bg-gradient-to-b from-[#DF5E1D] to-[#d05619] text-white py-2.5 px-3 rounded-lg text-xs font-semibold hover:from-[#d05619] hover:to-[#c45218] transition-all flex items-center justify-center gap-1.5">
                <iconify-icon icon="solar:eye-linear" class="text-base"></iconify-icon>
                <span>Lihat Bandingkan</span>
            </a>
            <button onclick="clearAllCompare()" class="flex-1 bg-gray-100 text-gray-600 py-2.5 px-3 rounded-lg text-xs font-semibold hover:bg-gray-200 transition-all">
                <span>Hapus Semua</span>
            </button>
        </div>
    </div>
</div>

<script>
    // Local Storage Management for Compare
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
        updateFloatingButton();
    }

    function addToCompare(id, name, image) {
        let compareList = getCompareList();

        // Prevent duplicates and limit to 2 items
        if (compareList.length >= 2) {
            showToast('Anda hanya dapat membandingkan maksimal 2 laptop');
            return;
        }

        if (!compareList.find(item => item.id === id)) {
            compareList.push({ id, name, image });
            saveCompareList(compareList);

            // Show toast notification
            showToast(`${name} ditambahkan untuk dibandingkan!`);
        } else {
            showToast('Sudah ditambahkan sebelumnya');
        }
    }

    function removeFromCompare(id) {
        let compareList = getCompareList();
        const removed = compareList.find(item => item.id === id);
        compareList = compareList.filter(item => item.id !== id);
        saveCompareList(compareList);
        if (removed) {
            showToast(`${removed.name} dihapus dari perbandingan`);
        }
    }

    function toggleWishlist(id) {
        let wishlist = JSON.parse(localStorage.getItem(WISHLIST_STORAGE_KEY)) || [];
        const index = wishlist.indexOf(id);

        if (index > -1) {
            wishlist.splice(index, 1);
            showToast('Dihapus dari wishlist');
        } else {
            wishlist.push(id);
            showToast('Ditambahkan ke wishlist!');
        }

        localStorage.setItem(WISHLIST_STORAGE_KEY, JSON.stringify(wishlist));
        updateWishlistButtons();
    }

    function updateFloatingButton() {
        const compareList = getCompareList();
        const floatingBtn = document.getElementById('floatingCompare');
        const compareCount = document.getElementById('compareCount');
        const compareListEl = document.getElementById('compareList');

        compareCount.textContent = compareList.length;

        // Update items list
        compareListEl.innerHTML = compareList.map(item => `
            <div class="flex items-center gap-2 p-2 bg-gray-50 rounded-lg border border-gray-100 hover:border-gray-200 transition-all group">
                <img src="${item.image}" alt="${item.name}" class="w-10 h-10 object-contain rounded">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-[#363230] truncate line-clamp-2">${item.name}</p>
                </div>
                <button onclick="removeFromCompare(${item.id})" class="w-6 h-6 rounded text-gray-400 hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100">
                    <iconify-icon icon="solar:trash-bin-minimalistic-linear" class="text-base"></iconify-icon>
                </button>
            </div>
        `).join('');

        if (compareList.length > 0) {
            floatingBtn.classList.remove('opacity-0', 'translate-y-4', 'pointer-events-none');
            floatingBtn.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
        } else {
            floatingBtn.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
            floatingBtn.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
        }
    }

    function toggleFloatingCard(show) {
        const floatingBtn = document.getElementById('floatingCompare');
        if (show === false) {
            floatingBtn.classList.add('opacity-0', 'translate-y-4', 'pointer-events-none');
            floatingBtn.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
        }
    }

    function clearAllCompare() {
        localStorage.removeItem(COMPARE_STORAGE_KEY);
        updateFloatingButton();
        showToast('Daftar perbandingan dibersihkan');
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

    function showToast(message) {
        const toast = document.createElement('div');
        toast.className = 'fixed top-24 right-8 bg-gray-900 text-white px-6 py-3 rounded-lg shadow-lg text-sm font-medium z-50 animate-in fade-in slide-in-from-top-2 duration-300';
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('animate-out', 'fade-out', 'slide-out-to-top-2', 'duration-300');
            setTimeout(() => toast.remove(), 300);
        }, 2000);
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateFloatingButton();
        updateWishlistButtons();
    });
</script>
@endsection

