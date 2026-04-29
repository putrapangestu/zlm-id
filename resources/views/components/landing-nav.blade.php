<!-- Navigation - Dynamic Background -->
<nav id="navbar" class="fixed top-0 w-full z-50 transition-all duration-300
    {{ request()->routeIs('landing.home') ? 'bg-transparent' : 'bg-white border-b border-gray-200 shadow-sm' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('landing.home') }}" 
                   class="nav-text text-xl font-semibold tracking-tighter flex items-center gap-2 hover:opacity-80 transition
                   {{ request()->routeIs('landing.home') ? 'text-white' : 'text-[#363230]' }}">
                    <img src="{{ asset('assets/logo.png') }}" alt="ZLM.ID" class="h-8 w-8 object-contain">
                    ZLM.ID
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-8 text-sm">
                    <a href="{{ route('landing.home') }}" 
                       class="nav-link font-medium transition
                       {{ request()->routeIs('landing.home') ? 'text-white/90 hover:text-white' : 'text-gray-600 hover:text-[#DF5E1D]' }}">
                        Beranda
                    </a>
                    <a href="{{ route('landing.search') }}" 
                       class="nav-link font-medium transition
                       {{ request()->routeIs('landing.home') ? 'text-white/90 hover:text-white' : 'text-gray-600 hover:text-[#DF5E1D]' }}">
                        Katalog
                    </a>
                    <a href="{{ route('landing.articles') }}" 
                       class="nav-link font-medium transition
                       {{ request()->routeIs('landing.home') ? 'text-white/90 hover:text-white' : 'text-gray-600 hover:text-[#DF5E1D]' }}">
                        Artikel
                    </a>
                </div>
            </div>

            <!-- User Actions -->
            <div class="hidden md:flex items-center gap-4">
                @auth
                    <!-- User Dropdown Menu -->
                    <div class="relative group">
                        <button class="nav-user flex items-center gap-2 px-3 py-2 rounded-md transition font-medium text-sm
                            {{ request()->routeIs('landing.home') ? 'text-white/90 hover:text-white' : 'text-gray-600 hover:text-[#DF5E1D]' }}">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-semibold
                                {{ request()->routeIs('landing.home') ? 'bg-white/20 text-white' : 'bg-[#DF5E1D]/10 text-[#DF5E1D]' }}">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                            <span>{{ auth()->user()->name ?? 'User' }}</span>
                            <iconify-icon icon="solar:alt-arrow-down-linear" class="text-base group-hover:rotate-180 transition-transform"></iconify-icon>
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <a href="{{ route('landing.profile') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#DF5E1D] transition flex items-center gap-2 rounded-t-lg">
                                <iconify-icon icon="solar:user-linear" class="text-base"></iconify-icon>
                                Profil
                            </a>
                            <a href="{{ route('landing.profile') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#DF5E1D] transition flex items-center gap-2">
                                <iconify-icon icon="solar:settings-linear" class="text-base"></iconify-icon>
                                Pengaturan
                            </a>
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('auth.logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2 rounded-b-lg">
                                    <iconify-icon icon="solar:logout-3-linear" class="text-base"></iconify-icon>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" 
                       class="nav-button text-sm px-4 py-2 rounded-md transition font-medium
                       {{ request()->routeIs('landing.home') ? 'text-[#DF5E1D] bg-white hover:bg-gray-100' : 'text-white bg-[#DF5E1D] hover:bg-[#c45218]' }}">
                        Masuk
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button id="mobile-menu-btn" 
                        class="transition
                        {{ request()->routeIs('landing.home') ? 'text-white' : 'text-[#363230]' }}">
                    <iconify-icon icon="solar:menu-dots-bold" class="text-2xl"></iconify-icon>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200 shadow-lg">
        <div class="px-4 py-4 space-y-2">
            <a href="{{ route('landing.home') }}" class="block px-3 py-2 rounded-md text-gray-600 hover:bg-gray-50 hover:text-[#DF5E1D] transition font-medium">
                Beranda
            </a>
            <a href="{{ route('landing.search') }}" class="block px-3 py-2 rounded-md text-gray-600 hover:bg-gray-50 hover:text-[#DF5E1D] transition font-medium">
                Katalog
            </a>
            <a href="{{ route('landing.articles') }}" class="block px-3 py-2 rounded-md text-gray-600 hover:bg-gray-50 hover:text-[#DF5E1D] transition font-medium">
                Artikel
            </a>
            
            @auth
                <div class="border-t border-gray-200 pt-2 mt-2">
                    <a href="{{ route('landing.profile') }}" class="block px-3 py-2 rounded-md text-gray-600 hover:bg-gray-50 hover:text-[#DF5E1D] transition font-medium flex items-center gap-2">
                        <iconify-icon icon="solar:user-linear" class="text-base"></iconify-icon>
                        Profil
                    </a>
                    <a href="{{ route('landing.profile') }}" class="block px-3 py-2 rounded-md text-gray-600 hover:bg-gray-50 hover:text-[#DF5E1D] transition font-medium flex items-center gap-2">
                        <iconify-icon icon="solar:settings-linear" class="text-base"></iconify-icon>
                        Pengaturan
                    </a>
                    <form method="POST" action="{{ route('auth.logout') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-red-600 hover:bg-red-50 transition font-medium flex items-center gap-2">
                            <iconify-icon icon="solar:logout-3-linear" class="text-base"></iconify-icon>
                            Keluar
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-white bg-[#DF5E1D] hover:bg-[#c45218] transition font-medium text-center">
                    Masuk
                </a>
            @endauth
        </div>
    </div>
</nav>

<!-- ✅ JavaScript for Scroll Detection -->
@push('scripts')
<script>
    const navbar = document.getElementById('navbar');
    const isHomePage = {{ request()->routeIs('landing.home') ? 'true' : 'false' }};
    
    // ✅ Only apply scroll effect on homepage
    if (isHomePage) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                // ✅ Scrolled - White background
                navbar.classList.remove('bg-transparent');
                navbar.classList.add('bg-white', 'border-b', 'border-gray-200', 'shadow-sm');
                
                // Change text colors
                document.querySelectorAll('.nav-text').forEach(el => {
                    el.classList.remove('text-white');
                    el.classList.add('text-[#363230]');
                });
                
                document.querySelectorAll('.nav-link').forEach(el => {
                    el.classList.remove('text-white/90', 'hover:text-white');
                    el.classList.add('text-gray-600', 'hover:text-[#DF5E1D]');
                });
                
                document.querySelectorAll('.nav-user').forEach(el => {
                    el.classList.remove('text-white/90', 'hover:text-white');
                    el.classList.add('text-gray-600', 'hover:text-[#DF5E1D]');
                });
                
                document.querySelectorAll('.nav-user div').forEach(el => {
                    el.classList.remove('bg-white/20', 'text-white');
                    el.classList.add('bg-[#DF5E1D]/10', 'text-[#DF5E1D]');
                });
                
                document.querySelectorAll('.nav-button').forEach(el => {
                    el.classList.remove('text-[#DF5E1D]', 'bg-white', 'hover:bg-gray-100');
                    el.classList.add('text-white', 'bg-[#DF5E1D]', 'hover:bg-[#c45218]');
                });
                
                document.querySelectorAll('#mobile-menu-btn').forEach(el => {
                    el.classList.remove('text-white');
                    el.classList.add('text-[#363230]');
                });
                
            } else {
                // ✅ Top - Transparent background
                navbar.classList.remove('bg-white', 'border-b', 'border-gray-200', 'shadow-sm');
                navbar.classList.add('bg-transparent');
                
                // Change text colors back
                document.querySelectorAll('.nav-text').forEach(el => {
                    el.classList.remove('text-[#363230]');
                    el.classList.add('text-white');
                });
                
                document.querySelectorAll('.nav-link').forEach(el => {
                    el.classList.remove('text-gray-600', 'hover:text-[#DF5E1D]');
                    el.classList.add('text-white/90', 'hover:text-white');
                });
                
                document.querySelectorAll('.nav-user').forEach(el => {
                    el.classList.remove('text-gray-600', 'hover:text-[#DF5E1D]');
                    el.classList.add('text-white/90', 'hover:text-white');
                });
                
                document.querySelectorAll('.nav-user div').forEach(el => {
                    el.classList.remove('bg-[#DF5E1D]/10', 'text-[#DF5E1D]');
                    el.classList.add('bg-white/20', 'text-white');
                });
                
                document.querySelectorAll('.nav-button').forEach(el => {
                    el.classList.remove('text-white', 'bg-[#DF5E1D]', 'hover:bg-[#c45218]');
                    el.classList.add('text-[#DF5E1D]', 'bg-white', 'hover:bg-gray-100');
                });
                
                document.querySelectorAll('#mobile-menu-btn').forEach(el => {
                    el.classList.remove('text-[#363230]');
                    el.classList.add('text-white');
                });
            }
        });
    }
    
    // ✅ Mobile menu toggle
    document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });
</script>
@endpush