<!-- Navigation - White Background -->
<nav class="sticky top-0 w-full z-50 bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex-shrink-0">
                <a href="{{ route('landing.home') }}" class="text-[#363230] text-xl font-semibold tracking-tighter flex items-center gap-2 hover:opacity-80 transition">
                    <img src="{{ asset('assets/logo.png') }}" alt="ZLM.ID" class="h-8 w-8 object-contain">
                    ZLM.ID
                </a>
            </div>

            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-8 text-sm">
                    <a href="{{ route('landing.home') }}" class="text-gray-600 hover:text-[#DF5E1D] transition font-medium">Beranda</a>
                    <a href="{{ route('landing.search') }}" class="text-gray-600 hover:text-[#DF5E1D] transition font-medium">Katalog</a>
                    <a href="{{ route('landing.articles') }}" class="text-gray-600 hover:text-[#DF5E1D] transition font-medium">Artikel</a>
                </div>
            </div>

            <div class="hidden md:flex items-center gap-4">
                @auth
                    <!-- User Dropdown Menu -->
                    <div class="relative group">
                        <button class="flex items-center gap-2 px-3 py-2 rounded-md text-gray-600 hover:text-[#DF5E1D] transition font-medium text-sm relative">
                            <div class="w-8 h-8 rounded-full bg-[#DF5E1D]/10 flex items-center justify-center text-[#DF5E1D] font-semibold">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                            <span>{{ auth()->user()->name ?? 'User' }}</span>
                            <iconify-icon icon="solar:alt-arrow-down-linear" class="text-base group-hover:rotate-180 transition-transform"></iconify-icon>
                        </button>

                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-0 w-48 bg-white border border-gray-200 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <a href="{{ route('landing.profile') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#DF5E1D] transition flex items-center gap-2">
                                <iconify-icon icon="solar:user-linear" class="text-base"></iconify-icon>
                                Profile
                            </a>
                            <a href="{{ route('landing.profile') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-[#DF5E1D] transition flex items-center gap-2">
                                <iconify-icon icon="solar:settings-linear" class="text-base"></iconify-icon>
                                Pengaturan
                            </a>
                            <div class="border-t border-gray-100"></div>
                            <form method="POST" action="{{ route('auth.logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                                    <iconify-icon icon="solar:logout-3-linear" class="text-base"></iconify-icon>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('auth.login') }}" class="text-sm text-white bg-[#DF5E1D] hover:bg-[#c45218] px-4 py-2 rounded-md transition font-medium">
                        Sign In
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button class="text-[#363230] hover:opacity-70 transition">
                    <iconify-icon icon="solar:menu-dots-bold" class="text-2xl"></iconify-icon>
                </button>
            </div>
        </div>
    </div>
</nav>
