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

            <div class="hidden md:flex items-center gap-3">
                <!-- Compare Button -->
                <a href="{{ route('landing.compare') }}" class="relative p-2 text-gray-600 hover:text-[#DF5E1D] transition" title="Compare">
                    <iconify-icon icon="solar:scale-linear" class="text-xl"></iconify-icon>
                    @php $compareNavCount = count(session('compare', [])); @endphp
                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-[#DF5E1D] text-white text-[10px] font-bold rounded-full flex items-center justify-center compare-count" style="{{ $compareNavCount > 0 ? '' : 'display:none' }}">{{ $compareNavCount }}</span>
                </a>

                <!-- Cart Button -->
                <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 hover:text-[#DF5E1D] transition" title="Cart">
                    <iconify-icon icon="solar:bag-4-linear" class="text-xl"></iconify-icon>
                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-[#DF5E1D] text-white text-[10px] font-bold rounded-full flex items-center justify-center cart-count" style="display:none">0</span>
                </a>

                @auth
                    <!-- User Dropdown Menu -->
                    <div class="relative group">
                        <button class="nav-user flex items-center gap-2 px-3 py-2 rounded-md transition font-medium text-sm
                            {{ request()->routeIs('landing.home') ? 'text-white/90 hover:text-white' : 'text-gray-600 hover:text-[#DF5E1D]' }}">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-semibold
                                {{ request()->routeIs('landing.home') ? 'bg-white/20 text-white' : 'bg-[#DF5E1D]/10 text-[#DF5E1D]' }}">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                            <span class="hidden lg:inline">{{ auth()->user()->name ?? 'User' }}</span>
                            <iconify-icon icon="solar:alt-arrow-down-linear" class="text-base group-hover:rotate-180 transition-transform"></iconify-icon>
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-0 w-52 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#DF5E1D] transition flex items-center gap-2">
                                <iconify-icon icon="solar:user-linear" class="text-base"></iconify-icon>
                                Profil
                            </a>
                            <a href="{{ route('wishlist.index') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#DF5E1D] transition flex items-center gap-2">
                                <iconify-icon icon="solar:heart-linear" class="text-base"></iconify-icon>
                                Wishlist
                            </a>
                            <a href="{{ route('orders.history') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#DF5E1D] transition flex items-center gap-2">
                                <iconify-icon icon="solar:box-linear" class="text-base"></iconify-icon>
                                Orders
                            </a>
                            @role('admin')
                                <div class="border-t border-gray-100"></div>
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#DF5E1D] transition flex items-center gap-2">
                                    <iconify-icon icon="solar:widget-5-linear" class="text-base"></iconify-icon>
                                    Dashboard
                                </a>
                            @endrole
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2 rounded-b-lg">
                                    <iconify-icon icon="solar:logout-3-linear" class="text-base"></iconify-icon>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-white bg-[#DF5E1D] hover:bg-[#c45218] px-4 py-2 rounded-md transition font-medium">
                        Sign In
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button onclick="toggleMobileMenu()" class="text-[#363230] hover:opacity-70 transition" aria-label="Toggle menu">
                    <iconify-icon icon="solar:menu-dots-bold" class="text-2xl"></iconify-icon>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu Overlay --}}
    <div id="mobile-menu" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="toggleMobileMenu()"></div>
        <div class="absolute left-0 top-0 bottom-0 w-72 max-w-[85vw] bg-white shadow-2xl overflow-y-auto">
            <div class="flex items-center justify-between p-4 border-b border-gray-100">
                <a href="{{ route('landing.home') }}" class="text-[#363230] text-xl font-semibold tracking-tighter flex items-center gap-2" onclick="toggleMobileMenu()">
                    <img src="{{ asset('assets/logo.png') }}" alt="ZLM.ID" class="h-7 w-7 object-contain">
                    ZLM.ID
                </a>
                <button onclick="toggleMobileMenu()" class="p-2 text-gray-500 hover:text-gray-800 rounded-lg hover:bg-gray-100 transition-colors">
                    <iconify-icon icon="solar:close-circle-linear" class="text-2xl"></iconify-icon>
                </button>
            </div>
            <div class="p-4 space-y-1">
                <a href="{{ route('landing.home') }}" onclick="toggleMobileMenu()" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-[#DF5E1D]/5 hover:text-[#DF5E1D] transition-colors">
                    <iconify-icon icon="solar:home-2-linear" class="text-lg"></iconify-icon>
                    Beranda
                </a>
                <a href="{{ route('landing.search') }}" onclick="toggleMobileMenu()" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-[#DF5E1D]/5 hover:text-[#DF5E1D] transition-colors">
                    <iconify-icon icon="solar:magnifer-linear" class="text-lg"></iconify-icon>
                    Katalog
                </a>
                <a href="{{ route('landing.articles') }}" onclick="toggleMobileMenu()" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-[#DF5E1D]/5 hover:text-[#DF5E1D] transition-colors">
                    <iconify-icon icon="solar:document-linear" class="text-lg"></iconify-icon>
                    Artikel
                </a>
                <hr class="my-2 border-gray-100">
                <a href="{{ route('landing.compare') }}" onclick="toggleMobileMenu()" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-[#DF5E1D]/5 hover:text-[#DF5E1D] transition-colors">
                    <iconify-icon icon="solar:scale-linear" class="text-lg"></iconify-icon>
                    Bandingkan
                </a>
                <a href="{{ route('cart.index') }}" onclick="toggleMobileMenu()" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-[#DF5E1D]/5 hover:text-[#DF5E1D] transition-colors">
                    <iconify-icon icon="solar:bag-4-linear" class="text-lg"></iconify-icon>
                    Keranjang
                </a>
                @auth
                <hr class="my-2 border-gray-100">
                <a href="{{ route('profile.edit') }}" onclick="toggleMobileMenu()" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-[#DF5E1D]/5 hover:text-[#DF5E1D] transition-colors">
                    <iconify-icon icon="solar:user-linear" class="text-lg"></iconify-icon>
                    Profil
                </a>
                <a href="{{ route('wishlist.index') }}" onclick="toggleMobileMenu()" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-gray-700 hover:bg-[#DF5E1D]/5 hover:text-[#DF5E1D] transition-colors">
                    <iconify-icon icon="solar:heart-linear" class="text-lg"></iconify-icon>
                    Wishlist
                </a>
                @role('admin')
                <a href="{{ route('admin.dashboard') }}" onclick="toggleMobileMenu()" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium bg-[#DF5E1D]/10 text-[#DF5E1D] hover:bg-[#DF5E1D]/20 transition-colors">
                    <iconify-icon icon="solar:widget-5-linear" class="text-lg"></iconify-icon>
                    Dashboard Admin
                </a>
                @endrole
                <div class="border-t border-gray-100 my-2"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
                        <iconify-icon icon="solar:logout-3-linear" class="text-lg"></iconify-icon>
                        Logout
                    </button>
                </form>
                @else
                <hr class="my-2 border-gray-100">
                <a href="{{ route('login') }}" onclick="toggleMobileMenu()" class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm font-medium bg-[#DF5E1D] text-white hover:bg-[#c45218] transition-colors">
                    <iconify-icon icon="solar:login-3-linear" class="text-lg"></iconify-icon>
                    Sign In
                </a>
                @endauth
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
