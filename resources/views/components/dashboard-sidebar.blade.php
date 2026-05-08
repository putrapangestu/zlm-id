<!-- Sidebar (Desktop) -->
<aside class="w-64 hidden lg:flex flex-col bg-white border-r border-gray-200/60 z-20 flex-shrink-0">
    <!-- Logo -->
    <div class="h-16 flex items-center px-6 border-b border-gray-100 gap-3">
        <img src="{{ asset('assets/logo.png') }}" alt="ZLM.ID Logo" class="h-8 w-auto">
        <div class="text-xl font-medium tracking-tighter text-[#363230]">
            ZLM.<span class="text-[#DF5E1D]">ID</span>
        </div>
    </div>

    <!-- Navigation -->
    <div class="flex-1 overflow-y-auto py-6 px-4 flex flex-col gap-1">
        <div class="text-[10px] font-medium text-gray-400 uppercase tracking-widest px-2 mb-2">Main Menu</div>

        <!-- Dashboard Link -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('dashboard') ? 'bg-orange-50/50 text-[#DF5E1D]' : 'text-gray-500 hover:bg-gray-50 hover:text-[#363230]' }} transition-colors duration-200 group">
            <iconify-icon icon="solar:widget-5-linear" class="text-lg" style="stroke-width: 1.5;"></iconify-icon>
            <span class="text-sm font-medium">Dashboard</span>
        </a>

        <!-- Product Management Link -->
        <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.products.*') ? 'bg-orange-50/50 text-[#DF5E1D]' : 'text-gray-500 hover:bg-gray-50 hover:text-[#363230]' }} transition-colors duration-200 group">
            <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-lg group-hover:text-[#363230] transition-colors" style="stroke-width: 1.5;"></iconify-icon>
            <span class="text-sm font-medium">Product Management</span>
        </a>

        <!-- Transaction Link -->
        <a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.transactions.*') ? 'bg-orange-50/50 text-[#DF5E1D]' : 'text-gray-500 hover:bg-gray-50 hover:text-[#363230]' }} transition-colors duration-200 group">
            <iconify-icon icon="solar:cart-large-minimalistic-linear" class="text-lg group-hover:text-[#363230] transition-colors" style="stroke-width: 1.5;"></iconify-icon>
            <span class="text-sm font-medium">Transactions</span>
        </a>

        <!-- User Management Link -->
        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.users.*') ? 'bg-orange-50/50 text-[#DF5E1D]' : 'text-gray-500 hover:bg-gray-50 hover:text-[#363230]' }} transition-colors duration-200 group">
            <iconify-icon icon="solar:users-group-two-rounded-linear" class="text-lg group-hover:text-[#363230] transition-colors" style="stroke-width: 1.5;"></iconify-icon>
            <span class="text-sm font-medium">User Management</span>
        </a>

        <div class="text-[10px] font-medium text-gray-400 uppercase tracking-widest px-2 mt-6 mb-2">Content & Reports</div>

        <!-- Article Management Link -->
        <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->routeIs('admin.articles.*') ? 'bg-orange-50/50 text-[#DF5E1D]' : 'text-gray-500 hover:bg-gray-50 hover:text-[#363230]' }} transition-colors duration-200 group">
            <iconify-icon icon="solar:document-text-linear" class="text-lg group-hover:text-[#363230] transition-colors" style="stroke-width: 1.5;"></iconify-icon>
            <span class="text-sm font-medium">Article Management</span>
        </a>

        <div class="text-[10px] font-medium text-gray-400 uppercase tracking-widest px-2 mt-6 mb-2">Account</div>

        <!-- Logout Link -->
        <form action="{{ route('auth.logout') }}" method="POST" class="w-full">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500 hover:bg-red-50/50 hover:text-red-600 transition-colors duration-200 group">
                <iconify-icon icon="solar:logout-3-linear" class="text-lg group-hover:text-red-600 transition-colors" style="stroke-width: 1.5;"></iconify-icon>
                <span class="text-sm font-medium">Logout</span>
            </button>
        </form>
    </div>

    <!-- User Profile -->
    <div class="p-4 border-t border-gray-100">
        <button class="flex items-center w-full gap-3 p-2 rounded-xl hover:bg-gray-50 transition-colors text-left">
            <div class="w-8 h-8 rounded-full bg-gray-200 overflow-hidden flex-shrink-0 border border-gray-300">
                <img src="https://ui-avatars.com/api/?name=Admin+User&background=f3f4f6&color=374151" alt="Admin" class="w-full h-full object-cover">
            </div>
            <div class="flex-1 min-w-0">
                <div class="text-sm font-medium text-[#363230] truncate">Admin User</div>
                <div class="text-xs text-gray-500 truncate">admin@zlm.id</div>
            </div>
            <iconify-icon icon="solar:alt-arrow-right-linear" class="text-gray-400"></iconify-icon>
        </button>
    </div>
</aside>
