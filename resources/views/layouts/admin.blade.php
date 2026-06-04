<!DOCTYPE html>
<html lang="en" class="antialiased scroll-smooth"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') — ZLM.ID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        @keyframes slideUpFade {
            0% { opacity: 0; transform: translateY(1rem); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-up {
            animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
    </style>
    @stack('styles')
</head>
<body class="font-sans bg-[#FAFAFA] text-[#363230] selection:bg-[#DF5E1D]/20 selection:text-[#DF5E1D] overflow-hidden">

<div class="flex h-screen w-full">
    <aside id="admin-sidebar" class="w-64 hidden lg:flex flex-col bg-white border-r border-gray-200/60 z-30 flex-shrink-0 fixed lg:relative inset-y-0 left-0 shadow-2xl lg:shadow-none">
        <div class="h-16 flex items-center px-6 border-b border-gray-100">
            <a href="{{ route('landing.home') }}" class="text-xl font-medium tracking-tighter text-[#363230] flex items-center gap-2">
                <img src="{{ asset('assets/logo.png') }}" alt="ZLM.ID" class="h-7 w-7 object-contain">
                ZLM<span class="text-[#DF5E1D]">.ID</span>
            </a>
        </div>

        <div class="flex-1 overflow-y-auto py-6 px-4 flex flex-col gap-1">
            <div class="text-[10px] font-medium text-gray-400 uppercase tracking-widest px-2 mb-2">Overview</div>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl @if(request()->routeIs('admin.dashboard')) bg-orange-50/50 text-[#DF5E1D] @else text-gray-500 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors duration-200 group">
                <iconify-icon icon="solar:widget-5-linear" class="text-lg" style="stroke-width: 1.5;"></iconify-icon>
                <span class="text-sm font-medium">Dashboard</span>
            </a>

            <a href="{{ route('admin.laptops.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl @if(request()->routeIs('admin.laptops.*')) bg-orange-50/50 text-[#DF5E1D] @else text-gray-500 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors duration-200 group">
                <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-lg" style="stroke-width: 1.5;"></iconify-icon>
                <span class="text-sm font-medium">Laptops</span>
            </a>

            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl @if(request()->routeIs('admin.categories.*')) bg-orange-50/50 text-[#DF5E1D] @else text-gray-500 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors duration-200 group">
                <iconify-icon icon="solar:folder-linear" class="text-lg" style="stroke-width: 1.5;"></iconify-icon>
                <span class="text-sm font-medium">Categories</span>
            </a>

            <a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl @if(request()->routeIs('admin.transactions.*')) bg-orange-50/50 text-[#DF5E1D] @else text-gray-500 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors duration-200 group">
                <iconify-icon icon="solar:wallet-linear" class="text-lg" style="stroke-width: 1.5;"></iconify-icon>
                <span class="text-sm font-medium">Transactions</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl @if(request()->routeIs('admin.orders.*')) bg-orange-50/50 text-[#DF5E1D] @else text-gray-500 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors duration-200 group">
                <iconify-icon icon="solar:cart-large-minimalistic-linear" class="text-lg" style="stroke-width: 1.5;"></iconify-icon>
                <span class="text-sm font-medium">Orders</span>
            </a>

            <a href="{{ route('admin.customers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl @if(request()->routeIs('admin.customers.*')) bg-orange-50/50 text-[#DF5E1D] @else text-gray-500 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors duration-200 group">
                <iconify-icon icon="solar:users-group-two-rounded-linear" class="text-lg" style="stroke-width: 1.5;"></iconify-icon>
                <span class="text-sm font-medium">Customers</span>
            </a>

            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl @if(request()->routeIs('admin.settings.*')) bg-orange-50/50 text-[#DF5E1D] @else text-gray-500 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors duration-200 group">
                <iconify-icon icon="solar:settings-linear" class="text-lg" style="stroke-width: 1.5;"></iconify-icon>
                <span class="text-sm font-medium">Settings</span>
            </a>
        </div>

        <div class="p-4 border-t border-gray-100">
            <div class="flex items-center w-full gap-3 p-2 rounded-xl">
                <div class="w-8 h-8 rounded-full bg-gray-200 overflow-hidden flex-shrink-0 border border-gray-300">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=DF5E1D&color=fff" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-[#363230] truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Logout">
                        <iconify-icon icon="solar:logout-3-linear"></iconify-icon>
                    </button>
                </form>
            </div>
        </div>
        {{-- Backdrop for mobile --}}
        <div onclick="document.getElementById('admin-sidebar').classList.add('hidden')" class="fixed inset-0 bg-black/30 z-[-1] lg:hidden"></div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <header class="h-16 flex-shrink-0 bg-white/90 backdrop-blur-xl border-b border-gray-200/60 shadow-sm supports-[backdrop-filter]:bg-white/60 z-10 flex items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <button onclick="document.getElementById('admin-sidebar').classList.toggle('hidden')" class="lg:hidden p-2 -ml-2 text-gray-500 hover:bg-gray-50 rounded-xl transition-colors">
                    <iconify-icon icon="solar:hamburger-menu-linear" class="text-xl" style="stroke-width: 1.5;"></iconify-icon>
                </button>
                <h1 class="text-lg font-medium tracking-tight text-[#363230]">@yield('heading', 'Dashboard')</h1>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('landing.home') }}" class="text-sm text-gray-500 hover:text-[#DF5E1D] transition-colors flex items-center gap-1">
                    <iconify-icon icon="solar:shop-linear"></iconify-icon>
                    <span class="hidden sm:inline">Store</span>
                </a>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto">
                @if (session('success'))
                    <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm flex items-center gap-2 animate-slide-up">
                        <iconify-icon icon="solar:check-circle-linear" class="text-emerald-500"></iconify-icon>
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</div>

@stack('scripts')
</body></html>
