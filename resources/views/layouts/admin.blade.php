<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') — ZLM.ID</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Iconify -->
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Trix Editor -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
    </style>
</head>
<body class="bg-[#FBFBFB] text-[#363230] antialiased">

<div class="flex h-screen overflow-hidden">
    {{-- Sidebar --}}
    <aside id="admin-sidebar" class="w-64 bg-white border-r border-gray-200/60 flex flex-col flex-shrink-0 z-30 transition-all duration-300">
        <div class="h-16 flex items-center px-6 border-b border-gray-100">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <img src="{{ asset('assets/logo.png') }}" alt="ZLM.ID" class="h-7 w-7 object-contain">
                <span class="font-bold text-lg tracking-tight text-[#363230]">ZLM<span class="text-[#DF5E1D]">.ID</span> Admin</span>
            </a>
        </div>

        <div class="flex-1 overflow-y-auto py-5 px-3 flex flex-col gap-1">

            {{-- 1. UTAMA --}}
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mb-1.5">Utama</div>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl @if(request()->routeIs('admin.dashboard')) bg-orange-50 text-[#DF5E1D] font-bold @else text-gray-600 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors text-xs font-medium">
                <iconify-icon icon="solar:widget-5-linear" class="text-lg"></iconify-icon>
                <span>Dashboard</span>
            </a>

            @can('pos.access')
            <a href="{{ route('pos.index') }}" target="_blank" class="flex items-center gap-3 px-3 py-2 rounded-xl bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition-colors text-xs font-bold my-1">
                <iconify-icon icon="solar:shop-2-bold" class="text-lg text-emerald-600"></iconify-icon>
                <span>Aplikasi Kasir POS</span>
                <span class="ml-auto px-1.5 py-0.5 bg-emerald-600 text-white rounded-md text-[9px]">OFFLINE</span>
            </a>
            @endcan

            {{-- 2. OPERASIONAL & INVENTORI --}}
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mt-3 mb-1.5">Inventori & QC</div>

            @can('qc.view')
            <a href="{{ route('admin.qc.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl @if(request()->routeIs('admin.qc.*')) bg-orange-50 text-[#DF5E1D] font-bold @else text-gray-600 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors text-xs font-medium">
                <iconify-icon icon="solar:checklist-minimalistic-linear" class="text-lg"></iconify-icon>
                <span>Pengecekan Barang (QC)</span>
            </a>
            @endcan

            @can('restock.view')
            <a href="{{ route('admin.restocks.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl @if(request()->routeIs('admin.restocks.*')) bg-orange-50 text-[#DF5E1D] font-bold @else text-gray-600 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors text-xs font-medium">
                <iconify-icon icon="solar:box-minimalistic-linear" class="text-lg"></iconify-icon>
                <span>Restock Barang</span>
            </a>
            @endcan

            @can('returns.view')
            <a href="{{ route('admin.returns.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl @if(request()->routeIs('admin.returns.*')) bg-orange-50 text-[#DF5E1D] font-bold @else text-gray-600 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors text-xs font-medium">
                <iconify-icon icon="solar:refresh-square-linear" class="text-lg"></iconify-icon>
                <span>Retur Barang</span>
            </a>
            @endcan

            @can('laptops.view')
            <a href="{{ route('admin.laptops.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl @if(request()->routeIs('admin.laptops.*')) bg-orange-50 text-[#DF5E1D] font-bold @else text-gray-600 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors text-xs font-medium">
                <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-lg"></iconify-icon>
                <span>Katalog Laptop & Diskon</span>
            </a>
            @endcan

            @can('categories.manage')
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl @if(request()->routeIs('admin.categories.*')) bg-orange-50 text-[#DF5E1D] font-bold @else text-gray-600 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors text-xs font-medium">
                <iconify-icon icon="solar:folder-linear" class="text-lg"></iconify-icon>
                <span>Kategori</span>
            </a>
            @endcan

            <a href="{{ route('admin.addons.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl @if(request()->routeIs('admin.addons.*')) bg-orange-50 text-[#DF5E1D] font-bold @else text-gray-600 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors text-xs font-medium">
                <iconify-icon icon="solar:box-minimalistic-linear" class="text-lg"></iconify-icon>
                <span>Paket Add-Ons & Bundle</span>
            </a>

            {{-- 3. PENJUALAN & PELANGGAN --}}
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mt-3 mb-1.5">Penjualan & Member</div>

            @can('transactions.view')
            <a href="{{ route('admin.transactions.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl @if(request()->routeIs('admin.transactions.*')) bg-orange-50 text-[#DF5E1D] font-bold @else text-gray-600 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors text-xs font-medium">
                <iconify-icon icon="solar:wallet-linear" class="text-lg"></iconify-icon>
                <span>Transaksi Toko</span>
            </a>
            @endcan

            @can('members.view')
            <a href="{{ route('admin.members.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl @if(request()->routeIs('admin.members.*')) bg-orange-50 text-[#DF5E1D] font-bold @else text-gray-600 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors text-xs font-medium">
                <iconify-icon icon="solar:card-2-linear" class="text-lg"></iconify-icon>
                <span>Member & Loyalitas</span>
            </a>
            @endcan

            @can('users.manage')
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl @if(request()->routeIs('admin.users.*')) bg-orange-50 text-[#DF5E1D] font-bold @else text-gray-600 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors text-xs font-medium">
                <iconify-icon icon="solar:shield-user-linear" class="text-lg"></iconify-icon>
                <span>Pengguna & Hak Akses</span>
            </a>
            @endcan

            {{-- 4. KONTEN & MARKETING --}}
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mt-3 mb-1.5">Konten & Marketing</div>

            @can('articles.manage')
            <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl @if(request()->routeIs('admin.articles.*')) bg-orange-50 text-[#DF5E1D] font-bold @else text-gray-600 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors text-xs font-medium">
                <iconify-icon icon="solar:document-text-linear" class="text-lg"></iconify-icon>
                <span>Artikel & Blog</span>
            </a>
            @endcan

            @can('sliders.manage')
            <a href="{{ route('admin.sliders.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl @if(request()->routeIs('admin.sliders.*')) bg-orange-50 text-[#DF5E1D] font-bold @else text-gray-600 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors text-xs font-medium">
                <iconify-icon icon="solar:gallery-linear" class="text-lg"></iconify-icon>
                <span>Hero Banner Slider</span>
            </a>
            @endcan

            <a href="{{ route('admin.testimonials.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl @if(request()->routeIs('admin.testimonials.*')) bg-orange-50 text-[#DF5E1D] font-bold @else text-gray-600 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors text-xs font-medium">
                <iconify-icon icon="solar:chat-round-dots-linear" class="text-lg"></iconify-icon>
                <span>Testimoni</span>
            </a>

            {{-- 5. LAPORAN KEUANGAN & BARANG --}}
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mt-3 mb-1.5">Laporan & Analisis</div>

            @can('reports.purchases')
            <a href="{{ route('admin.reports.purchases') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl @if(request()->routeIs('admin.reports.purchases')) bg-orange-50 text-[#DF5E1D] font-bold @else text-gray-600 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors text-xs font-medium">
                <iconify-icon icon="solar:document-add-linear" class="text-lg"></iconify-icon>
                <span>Laporan Pembelian</span>
            </a>
            @endcan

            @can('reports.profit_loss')
            <a href="{{ route('admin.reports.profit-loss') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl @if(request()->routeIs('admin.reports.profit-loss')) bg-orange-50 text-[#DF5E1D] font-bold @else text-gray-600 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors text-xs font-medium">
                <iconify-icon icon="solar:chart-linear" class="text-lg"></iconify-icon>
                <span>Laba Rugi & HPP</span>
            </a>
            @endcan

            @can('reports.product_stats')
            <a href="{{ route('admin.reports.product-stats') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl @if(request()->routeIs('admin.reports.product-stats')) bg-orange-50 text-[#DF5E1D] font-bold @else text-gray-600 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors text-xs font-medium">
                <iconify-icon icon="solar:graph-up-linear" class="text-lg"></iconify-icon>
                <span>Statistik Stok & QC</span>
            </a>
            @endcan

            {{-- 6. PENGATURAN --}}
            @can('settings.manage')
            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-3 mt-3 mb-1.5">Sistem</div>

            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl @if(request()->routeIs('admin.settings.*')) bg-orange-50 text-[#DF5E1D] font-bold @else text-gray-600 hover:bg-gray-50 hover:text-[#363230] @endif transition-colors text-xs font-medium">
                <iconify-icon icon="solar:settings-linear" class="text-lg"></iconify-icon>
                <span>Pengaturan Toko, WA & Printer</span>
            </a>
            @endcan

        </div>

        {{-- User Profile Footer --}}
        <div class="p-3 border-t border-gray-100">
            <div class="flex items-center w-full gap-2.5 p-2 bg-gray-50 rounded-xl">
                <div class="w-8 h-8 rounded-full bg-[#DF5E1D]/10 text-[#DF5E1D] font-bold flex items-center justify-center text-xs shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-xs font-bold text-[#363230] truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[10px] text-gray-400 capitalize">{{ auth()->user()->roles->first()?->name ?? 'Staff' }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 transition" title="Logout">
                        <iconify-icon icon="solar:logout-3-linear" class="text-base"></iconify-icon>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Main Area --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <header class="h-16 flex-shrink-0 bg-white border-b border-gray-200/60 shadow-xs z-10 flex items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <button onclick="document.getElementById('admin-sidebar').classList.toggle('hidden')" class="lg:hidden p-2 -ml-2 text-gray-500 hover:bg-gray-50 rounded-xl transition-colors">
                    <iconify-icon icon="solar:hamburger-menu-linear" class="text-xl"></iconify-icon>
                </button>
                <h1 class="text-base font-bold tracking-tight text-[#363230]">@yield('heading', 'Dashboard')</h1>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('landing.home') }}" target="_blank" class="px-3 py-1.5 rounded-xl border border-gray-200 text-xs font-medium text-gray-600 hover:text-[#DF5E1D] hover:border-[#DF5E1D] transition flex items-center gap-1.5">
                    <iconify-icon icon="solar:shop-linear"></iconify-icon>
                    <span>Lihat Toko Online</span>
                </a>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-6">
                @if (session('success'))
                    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-medium flex items-center gap-2">
                        <iconify-icon icon="solar:check-circle-bold" class="text-emerald-500 text-lg shrink-0"></iconify-icon>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="p-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-2xl text-xs font-medium flex items-center gap-2">
                        <iconify-icon icon="solar:danger-triangle-bold" class="text-amber-500 text-lg shrink-0"></iconify-icon>
                        <span>{{ session('warning') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-medium flex items-center gap-2">
                        <iconify-icon icon="solar:close-circle-bold" class="text-rose-500 text-lg shrink-0"></iconify-icon>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
