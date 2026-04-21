<nav class="bg-dark text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('landing.home') }}" class="text-2xl font-bold text-primary">
                    💻 LaptopHub
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('landing.home') }}"
                    class="hover:text-primary transition {{ request()->routeIs('landing.home') ? 'text-primary font-semibold' : '' }}">
                    Home
                </a>
                <a href="{{ route('landing.search') }}"
                    class="hover:text-primary transition {{ request()->routeIs('landing.search') ? 'text-primary font-semibold' : '' }}">
                    Shop
                </a>
                <a href="{{ route('landing.compare') }}"
                    class="hover:text-primary transition {{ request()->routeIs('landing.compare') ? 'text-primary font-semibold' : '' }}">
                    Compare
                </a>
            </div>

            <!-- Auth/Cart Section -->
            <div class="flex items-center space-x-4">
                <a href="#"
                    class="relative p-2 text-gray-300 hover:text-primary transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="absolute top-0 right-0 bg-primary text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                </a>
                <button class="md:hidden p-2 text-gray-300 hover:text-primary">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>
