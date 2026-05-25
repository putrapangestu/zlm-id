<!-- Top Header -->
<header class="h-16 flex-shrink-0 bg-white/90 backdrop-blur-xl border-b border-gray-200/60 shadow-sm supports-[backdrop-filter]:bg-white/60 z-10 flex items-center justify-between px-4 sm:px-6 lg:px-8">
    <div class="flex items-center gap-4">
        <button class="lg:hidden p-2 -ml-2 text-gray-500 hover:bg-gray-50 rounded-xl transition-colors">
            <iconify-icon icon="solar:hamburger-menu-linear" class="text-xl" style="stroke-width: 1.5;"></iconify-icon>
        </button>
        <h1 class="text-lg font-medium tracking-tight text-[#363230] hidden sm:block">@yield('page-title', 'Dashboard Overview')</h1>
    </div>

    <div class="flex items-center gap-3 sm:gap-5">
        <!-- Search -->
        <div class="relative group hidden sm:block">
            <iconify-icon icon="solar:magnifer-linear" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-[#DF5E1D] transition-colors" style="stroke-width: 1.5;"></iconify-icon>
            <input type="text" placeholder="Search orders, products..." class="w-64 bg-gray-50 border border-transparent text-sm text-[#363230] placeholder-gray-400 rounded-xl py-2 pl-9 pr-4 focus:outline-none focus:bg-white focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all">
            <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-1">
                <kbd class="text-[10px] font-medium text-gray-400 bg-white border border-gray-200 rounded px-1.5 py-0.5">⌘</kbd>
                <kbd class="text-[10px] font-medium text-gray-400 bg-white border border-gray-200 rounded px-1.5 py-0.5">K</kbd>
            </div>
        </div>

        <div class="w-px h-6 bg-gray-200 hidden sm:block"></div>

        <!-- Notifications -->
        <button class="relative p-2 text-gray-500 hover:bg-gray-50 rounded-xl transition-colors">
            <iconify-icon icon="solar:bell-linear" class="text-xl" style="stroke-width: 1.5;"></iconify-icon>
            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-[#DF5E1D] border-2 border-white rounded-full"></span>
        </button>
    </div>
</header>
