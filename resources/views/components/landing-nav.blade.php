<!-- Top Orange Bar -->
<div class="bg-[#DF5E1D] text-white text-[11px] sm:text-xs py-2 px-4 text-center font-medium">
    Ekstra 500K untuk Click & Collect <a href="#" class="underline font-bold ml-1">CEK PROMO DI SINI</a>
</div>

<!-- Main Navigation -->
<nav id="navbar" class="bg-white border-b border-gray-200 sticky top-0 z-50 w-full transition-all duration-300 shadow-sm">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Upper Navbar (Logo, Search, Icons) -->
        <div class="flex items-center justify-between h-20 gap-4">
            
            <!-- Logo -->
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('landing.home') }}" class="flex items-center gap-2 hover:opacity-80 transition">
                    <img src="{{ asset('assets/logo.png') }}" alt="ZLM.ID" class="h-8 w-8 object-contain">
                    <span class="text-xl font-bold tracking-tighter text-[#363230]">ZLM.ID</span>
                </a>
            </div>

            <!-- Search Bar (Middle) -->
            <div class="hidden md:flex flex-1 max-w-xl mx-8">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <iconify-icon icon="solar:magnifer-linear" class="text-gray-400 text-lg"></iconify-icon>
                    </div>
                    <input type="text" class="block w-full pl-10 pr-3 py-2 border-none rounded-full bg-gray-100 text-sm placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-gray-300 focus:bg-white transition-colors" placeholder="Cari">
                </div>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-4 shrink-0">
                <div class="hidden lg:flex items-center gap-2 text-xs font-semibold text-gray-600 hover:text-black cursor-pointer mr-2">
                    <iconify-icon icon="solar:shop-linear" class="text-lg"></iconify-icon>
                    <span class="uppercase tracking-wide">ZLM.ID STORE MALANG</span>
                </div>
                
                @auth
                    <!-- User Dropdown Menu -->
                    <div class="relative group">
                        <button class="p-2 text-gray-600 hover:text-black transition">
                            <iconify-icon icon="solar:user-linear" class="text-2xl"></iconify-icon>
                        </button>
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-0 w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Profil</a>
                            <a href="{{ route('orders.history') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Pesanan Saya</a>
                            @role('admin')
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">Admin Dashboard</a>
                            @endrole
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">Keluar</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="p-2 text-gray-600 hover:text-black transition">
                        <iconify-icon icon="solar:user-linear" class="text-2xl"></iconify-icon>
                    </a>
                @endauth
                
                <!-- Cart -->
                <a href="{{ route('cart.index') }}" class="p-2 text-gray-600 hover:text-black transition relative">
                    <iconify-icon icon="solar:cart-large-2-linear" class="text-2xl"></iconify-icon>
                    <span class="absolute top-1 right-0 w-3.5 h-3.5 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center cart-count" style="display:none">0</span>
                </a>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button onclick="toggleMobileMenu()" class="text-gray-600 hover:text-black transition p-2" aria-label="Toggle menu">
                        <iconify-icon icon="solar:hamburger-menu-linear" class="text-2xl"></iconify-icon>
                    </button>
                </div>
            </div>
        </div>

        <!-- Lower Navbar (Categories) -->
        <div class="hidden md:flex items-center justify-center space-x-8 py-3 text-[13px] font-medium text-gray-700">
            <a href="{{ route('landing.home') }}" class="hover:text-[#DF5E1D] transition">Beranda</a>
            <a href="{{ route('landing.search') }}" class="hover:text-[#DF5E1D] transition">Katalog</a>
            <a href="{{ route('landing.search', ['category' => 'gaming']) }}" class="hover:text-[#DF5E1D] transition">Laptop Gaming</a>
            <a href="{{ route('landing.search', ['category' => 'bisnis']) }}" class="hover:text-[#DF5E1D] transition">Ultrabook / Bisnis</a>
            <a href="{{ route('landing.articles') }}" class="hover:text-[#DF5E1D] transition">Artikel / Blog</a>
            <span class="text-gray-300">|</span>
            <a href="{{ route('landing.compare') }}" class="hover:text-[#DF5E1D] transition">Bandingkan Laptop</a>
        </div>
    </div>

    {{-- Mobile Menu Overlay --}}
    <div id="mobile-menu" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="toggleMobileMenu()"></div>
        <div class="absolute right-0 top-0 bottom-0 w-72 max-w-[85vw] bg-white shadow-2xl overflow-y-auto">
            <div class="flex items-center justify-between p-4 border-b border-gray-100">
                <span class="text-xl font-bold tracking-tighter text-black">ZLM.ID</span>
                <button onclick="toggleMobileMenu()" class="p-2 text-gray-500 hover:text-black rounded-lg transition-colors">
                    <iconify-icon icon="solar:close-circle-linear" class="text-2xl"></iconify-icon>
                </button>
            </div>
            
            <div class="p-4">
                <!-- Mobile Search -->
                <div class="relative w-full mb-6">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <iconify-icon icon="solar:magnifer-linear" class="text-gray-400"></iconify-icon>
                    </div>
                    <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500" placeholder="Cari">
                </div>

                <div class="space-y-1 font-medium text-sm text-gray-700">
                    <a href="{{ route('landing.home') }}" class="block py-2.5 border-b border-gray-50 hover:text-[#DF5E1D]">Beranda</a>
                    <a href="{{ route('landing.search') }}" class="block py-2.5 border-b border-gray-50 hover:text-[#DF5E1D]">Katalog</a>
                    <a href="{{ route('landing.search', ['category' => 'gaming']) }}" class="block py-2.5 border-b border-gray-50 hover:text-[#DF5E1D]">Laptop Gaming</a>
                    <a href="{{ route('landing.search', ['category' => 'bisnis']) }}" class="block py-2.5 border-b border-gray-50 hover:text-[#DF5E1D]">Ultrabook / Bisnis</a>
                    <a href="{{ route('landing.articles') }}" class="block py-2.5 border-b border-gray-50 hover:text-[#DF5E1D]">Artikel / Blog</a>
                    <a href="{{ route('landing.compare') }}" class="block py-2.5 border-b border-gray-50 hover:text-[#DF5E1D]">Bandingkan Laptop</a>
                </div>
            </div>
        </div>
    </div>

    <script>
    function toggleMobileMenu() {
        var menu = document.getElementById('mobile-menu');
        if (menu) {
            menu.classList.toggle('hidden');
            document.body.style.overflow = menu.classList.contains('hidden') ? '' : 'hidden';
        }
    }
    </script>
</nav>
