<nav class="bg-white shadow-sm">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-xl font-semibold text-gray-900">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="hidden md:flex md:items-center md:space-x-8">
                <a href="{{ route('home') }}"
                    class="text-gray-700 hover:text-gray-900 {{ request()->routeIs('home') ? 'text-gray-900 font-semibold' : '' }}">
                    Home
                </a>
                <a href="#" class="text-gray-700 hover:text-gray-900">
                    About
                </a>
                <a href="#" class="text-gray-700 hover:text-gray-900">
                    Contact
                </a>
            </div>

            <div class="flex items-center md:hidden">
                <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-gray-900 focus:outline-none"
                    aria-expanded="false">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>
