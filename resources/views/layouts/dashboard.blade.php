<html lang="en" class="antialiased scroll-smooth"><head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet">
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
</head>
<body class="font-sans bg-[#FAFAFA] text-[#363230] selection:bg-[#DF5E1D]/20 selection:text-[#DF5E1D] overflow-hidden">

<div class="flex h-screen w-full">

    <!-- Sidebar (Desktop) -->
    <aside class="w-64 hidden lg:flex flex-col bg-white border-r border-gray-200/60 z-20 flex-shrink-0">
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 border-b border-gray-100">
            <div class="text-xl font-medium tracking-tighter text-[#363230]">
                SYS<span class="text-[#DF5E1D]">TM</span>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex-1 overflow-y-auto py-6 px-4 flex flex-col gap-1">
            <div class="text-[10px] font-medium text-gray-400 uppercase tracking-widest px-2 mb-2">Overview</div>

            <!-- Active Link -->
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl bg-orange-50/50 text-[#DF5E1D] transition-colors duration-200 group">
                <iconify-icon icon="solar:widget-5-linear" class="text-lg" style="stroke-width: 1.5;"></iconify-icon>
                <span class="text-sm font-medium">Dashboard</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-[#363230] transition-colors duration-200 group">
                <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-lg group-hover:text-[#363230] transition-colors" style="stroke-width: 1.5;"></iconify-icon>
                <span class="text-sm font-medium">Inventory</span>
                <span class="ml-auto bg-gray-100 text-gray-500 py-0.5 px-2 rounded-md text-[10px] font-medium group-hover:bg-gray-200 transition-colors">124</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-[#363230] transition-colors duration-200 group">
                <iconify-icon icon="solar:cart-large-minimalistic-linear" class="text-lg group-hover:text-[#363230] transition-colors" style="stroke-width: 1.5;"></iconify-icon>
                <span class="text-sm font-medium">Orders</span>
                <div class="ml-auto w-1.5 h-1.5 rounded-full bg-[#DF5E1D]"></div>
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-[#363230] transition-colors duration-200 group">
                <iconify-icon icon="solar:users-group-two-rounded-linear" class="text-lg group-hover:text-[#363230] transition-colors" style="stroke-width: 1.5;"></iconify-icon>
                <span class="text-sm font-medium">Customers</span>
            </a>

            <div class="text-[10px] font-medium text-gray-400 uppercase tracking-widest px-2 mt-6 mb-2">Settings</div>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-[#363230] transition-colors duration-200 group">
                <iconify-icon icon="solar:chart-square-linear" class="text-lg group-hover:text-[#363230] transition-colors" style="stroke-width: 1.5;"></iconify-icon>
                <span class="text-sm font-medium">Analytics</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-gray-500 hover:bg-gray-50 hover:text-[#363230] transition-colors duration-200 group">
                <iconify-icon icon="solar:settings-linear" class="text-lg group-hover:text-[#363230] transition-colors" style="stroke-width: 1.5;"></iconify-icon>
                <span class="text-sm font-medium">Preferences</span>
            </a>
        </div>

        <!-- User Profile -->
        <div class="p-4 border-t border-gray-100">
            <button class="flex items-center w-full gap-3 p-2 rounded-xl hover:bg-gray-50 transition-colors text-left">
                <div class="w-8 h-8 rounded-full bg-gray-200 overflow-hidden flex-shrink-0 border border-gray-300">
                    <img src="https://ui-avatars.com/api/?name=Admin+User&amp;background=f3f4f6&amp;color=374151" alt="Admin" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-[#363230] truncate">Admin User</div>
                    <div class="text-xs text-gray-500 truncate">admin@system.io</div>
                </div>
                <iconify-icon icon="solar:alt-arrow-right-linear" class="text-gray-400"></iconify-icon>
            </button>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- Top Header -->
        <header class="h-16 flex-shrink-0 bg-white/90 backdrop-blur-xl border-b border-gray-200/60 shadow-sm supports-[backdrop-filter]:bg-white/60 z-10 flex items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <button class="lg:hidden p-2 -ml-2 text-gray-500 hover:bg-gray-50 rounded-xl transition-colors">
                    <iconify-icon icon="solar:hamburger-menu-linear" class="text-xl" style="stroke-width: 1.5;"></iconify-icon>
                </button>
                <h1 class="text-lg font-medium tracking-tight text-[#363230] hidden sm:block">Dashboard Overview</h1>
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

        <!-- Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto space-y-8">

                <!-- Date Filter & Actions -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-slide-up" style="animation-delay: 0s;">
                    <div class="flex items-center gap-2 text-sm font-medium text-gray-500 bg-white px-1 border border-gray-200/60 rounded-xl shadow-sm w-fit">
                        <button class="px-3 py-1.5 rounded-lg bg-gray-50 text-[#363230] shadow-sm m-1 transition-colors">Today</button>
                        <button class="px-3 py-1.5 rounded-lg hover:bg-gray-50 hover:text-[#363230] m-1 transition-colors">7D</button>
                        <button class="px-3 py-1.5 rounded-lg hover:bg-gray-50 hover:text-[#363230] m-1 transition-colors">30D</button>
                        <button class="px-3 py-1.5 rounded-lg hover:bg-gray-50 hover:text-[#363230] m-1 transition-colors">YTD</button>
                    </div>
                    <button class="bg-[#363230] text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-800 transition-colors shadow-sm flex items-center justify-center gap-2">
                        <iconify-icon icon="solar:export-linear" style="stroke-width: 1.5;"></iconify-icon>
                        Export Report
                    </button>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    <!-- Stat 1 -->
                    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 animate-slide-up relative overflow-hidden group" style="animation-delay: 0.1s;">
                        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 group-hover:scale-110 transition-all duration-500 text-[#DF5E1D]">
                            <iconify-icon icon="solar:wallet-money-linear" class="text-6xl"></iconify-icon>
                        </div>
                        <div class="flex justify-between items-start mb-4 relative z-10">
                            <div class="text-xs font-medium text-gray-400 uppercase tracking-widest">Gross Revenue</div>
                            <div class="w-8 h-8 rounded-lg bg-orange-50 border border-orange-100 flex items-center justify-center text-[#DF5E1D]">
                                <iconify-icon icon="solar:dollar-linear" style="stroke-width: 1.5;"></iconify-icon>
                            </div>
                        </div>
                        <div class="text-2xl font-medium tracking-tight text-[#363230] mb-2 relative z-10">$142,390.00</div>
                        <div class="flex items-center gap-2 relative z-10">
                            <div class="text-[10px] font-medium text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100/50 flex items-center gap-0.5">
                                <iconify-icon icon="solar:arrow-right-up-linear"></iconify-icon>
                                12.5%
                            </div>
                            <span class="text-[10px] text-gray-400">vs last month</span>
                        </div>
                    </div>

                    <!-- Stat 2 -->
                    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 animate-slide-up relative overflow-hidden group" style="animation-delay: 0.2s;">
                        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 group-hover:scale-110 transition-all duration-500 text-[#DF5E1D]">
                            <iconify-icon icon="solar:bag-check-linear" class="text-6xl"></iconify-icon>
                        </div>
                        <div class="flex justify-between items-start mb-4 relative z-10">
                            <div class="text-xs font-medium text-gray-400 uppercase tracking-widest">Total Orders</div>
                            <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500 group-hover:border-orange-100 group-hover:bg-orange-50 group-hover:text-[#DF5E1D] transition-colors">
                                <iconify-icon icon="solar:cart-large-2-linear" style="stroke-width: 1.5;"></iconify-icon>
                            </div>
                        </div>
                        <div class="text-2xl font-medium tracking-tight text-[#363230] mb-2 relative z-10">1,204</div>
                        <div class="flex items-center gap-2 relative z-10">
                            <div class="text-[10px] font-medium text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100/50 flex items-center gap-0.5">
                                <iconify-icon icon="solar:arrow-right-up-linear"></iconify-icon>
                                8.2%
                            </div>
                            <span class="text-[10px] text-gray-400">vs last month</span>
                        </div>
                    </div>

                    <!-- Stat 3 -->
                    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 animate-slide-up relative overflow-hidden group" style="animation-delay: 0.3s;">
                        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 group-hover:scale-110 transition-all duration-500 text-[#DF5E1D]">
                            <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-6xl"></iconify-icon>
                        </div>
                        <div class="flex justify-between items-start mb-4 relative z-10">
                            <div class="text-xs font-medium text-gray-400 uppercase tracking-widest">Active Laptops</div>
                            <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500 group-hover:border-orange-100 group-hover:bg-orange-50 group-hover:text-[#DF5E1D] transition-colors">
                                <iconify-icon icon="solar:devices-linear" style="stroke-width: 1.5;"></iconify-icon>
                            </div>
                        </div>
                        <div class="text-2xl font-medium tracking-tight text-[#363230] mb-2 relative z-10">342</div>
                        <div class="flex items-center gap-2 relative z-10">
                            <div class="text-[10px] font-medium text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100/50 flex items-center gap-0.5">
                                <iconify-icon icon="solar:arrow-right-down-linear"></iconify-icon>
                                2.1%
                            </div>
                            <span class="text-[10px] text-gray-400">vs last month</span>
                        </div>
                    </div>

                    <!-- Stat 4 -->
                    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 animate-slide-up relative overflow-hidden group" style="animation-delay: 0.4s;">
                        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 group-hover:scale-110 transition-all duration-500 text-[#DF5E1D]">
                            <iconify-icon icon="solar:users-group-two-rounded-linear" class="text-6xl"></iconify-icon>
                        </div>
                        <div class="flex justify-between items-start mb-4 relative z-10">
                            <div class="text-xs font-medium text-gray-400 uppercase tracking-widest">New Customers</div>
                            <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500 group-hover:border-orange-100 group-hover:bg-orange-50 group-hover:text-[#DF5E1D] transition-colors">
                                <iconify-icon icon="solar:user-plus-linear" style="stroke-width: 1.5;"></iconify-icon>
                            </div>
                        </div>
                        <div class="text-2xl font-medium tracking-tight text-[#363230] mb-2 relative z-10">89</div>
                        <div class="flex items-center gap-2 relative z-10">
                            <div class="text-[10px] font-medium text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100/50 flex items-center gap-0.5">
                                <iconify-icon icon="solar:arrow-right-up-linear"></iconify-icon>
                                24.5%
                            </div>
                            <span class="text-[10px] text-gray-400">vs last month</span>
                        </div>
                    </div>
                </div>

                <!-- Main Grid Layout -->
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-8">

                    <!-- Recent Orders Table -->
                    <div class="xl:col-span-2 bg-white rounded-3xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden animate-slide-up flex flex-col" style="animation-delay: 0.5s;">
                        <div class="p-5 sm:p-6 flex items-center justify-between border-b border-gray-100">
                            <h2 class="text-base font-medium tracking-tight text-[#363230]">Recent Orders</h2>
                            <a href="#" class="text-xs font-medium text-[#DF5E1D] hover:text-[#363230] transition-colors flex items-center gap-1">
                                View all <iconify-icon icon="solar:alt-arrow-right-linear"></iconify-icon>
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[600px]">
                                <thead>
                                    <tr class="bg-gray-50/50">
                                        <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest border-b border-gray-100">Order ID</th>
                                        <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest border-b border-gray-100">Product</th>
                                        <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest border-b border-gray-100">Date</th>
                                        <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest border-b border-gray-100">Amount</th>
                                        <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest border-b border-gray-100">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 text-sm">
                                    <tr class="hover:bg-gray-50/50 transition-colors group">
                                        <td class="py-4 px-6 font-medium text-[#363230]">#ORD-7352</td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center p-1">
                                                    <img src="https://hoirqrkdgbmvpwutwuwj.supabase.co/storage/v1/object/public/assets/assets/917d6f93-fb36-439a-8c48-884b67b35381_1600w.jpg" class="w-full h-full object-contain mix-blend-multiply" alt="MacBook">
                                                </div>
                                                <span class="text-[#363230] truncate max-w-[150px]">MacBook Pro 14" M3</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-gray-500">Today, 10:24 AM</td>
                                        <td class="py-4 px-6 font-medium text-[#363230]">$1,599.00</td>
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-[10px] font-medium bg-emerald-50 text-emerald-600 border border-emerald-100/50">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Completed
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50/50 transition-colors group">
                                        <td class="py-4 px-6 font-medium text-[#363230]">#ORD-7351</td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center p-1">
                                                    <img src="https://hoirqrkdgbmvpwutwuwj.supabase.co/storage/v1/object/public/assets/assets/c543a9e1-f226-4ced-80b0-feb8445a75b9_1600w.jpg" class="w-full h-full object-contain mix-blend-multiply" alt="XPS">
                                                </div>
                                                <span class="text-[#363230] truncate max-w-[150px]">Dell XPS 15 OLED</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-gray-500">Yesterday, 15:45</td>
                                        <td class="py-4 px-6 font-medium text-[#363230]">$1,499.00</td>
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-[10px] font-medium bg-orange-50 text-[#DF5E1D] border border-orange-100/50">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#DF5E1D]"></span>
                                                Processing
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50/50 transition-colors group">
                                        <td class="py-4 px-6 font-medium text-[#363230]">#ORD-7350</td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center p-1">
                                                    <img src="https://hoirqrkdgbmvpwutwuwj.supabase.co/storage/v1/object/public/assets/assets/917d6f93-fb36-439a-8c48-884b67b35381_1600w.jpg" class="w-full h-full object-contain mix-blend-multiply" alt="MacBook Air">
                                                </div>
                                                <span class="text-[#363230] truncate max-w-[150px]">MacBook Air 15" M2</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-gray-500">Yesterday, 09:12</td>
                                        <td class="py-4 px-6 font-medium text-[#363230]">$1,299.00</td>
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-[10px] font-medium bg-emerald-50 text-emerald-600 border border-emerald-100/50">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Completed
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50/50 transition-colors group">
                                        <td class="py-4 px-6 font-medium text-[#363230]">#ORD-7349</td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center p-1">
                                                    <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-gray-400"></iconify-icon>
                                                </div>
                                                <span class="text-[#363230] truncate max-w-[150px]">Lenovo ThinkPad X1</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-6 text-gray-500">Oct 24, 2023</td>
                                        <td class="py-4 px-6 font-medium text-[#363230]">$1,899.00</td>
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-[10px] font-medium bg-gray-100 text-gray-500 border border-gray-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                                Refunded
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Top Selling Products -->
                    <div class="bg-white rounded-3xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden animate-slide-up flex flex-col" style="animation-delay: 0.6s;">
                        <div class="p-5 sm:p-6 flex items-center justify-between border-b border-gray-100">
                            <h2 class="text-base font-medium tracking-tight text-[#363230]">Top Selling Laptops</h2>
                            <button class="text-gray-400 hover:text-[#363230] transition-colors rounded-md focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DF5E1D]/50">
                                <iconify-icon icon="solar:menu-dots-bold" class="text-lg"></iconify-icon>
                            </button>
                        </div>

                        <div class="p-5 sm:p-6 flex-1 flex flex-col gap-5">

                            <!-- Product Item 1 -->
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center p-1.5 group-hover:border-orange-100 transition-colors">
                                        <img src="https://hoirqrkdgbmvpwutwuwj.supabase.co/storage/v1/object/public/assets/assets/917d6f93-fb36-439a-8c48-884b67b35381_1600w.jpg" class="w-full h-full object-contain mix-blend-multiply" alt="MacBook">
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-[#363230] mb-0.5 line-clamp-1">MacBook Pro 14" M3</div>
                                        <div class="text-xs text-gray-500">124 sales</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-[#363230] mb-0.5">$198,276</div>
                                    <div class="text-[10px] font-medium text-emerald-600">In Stock</div>
                                </div>
                            </div>

                            <!-- Product Item 2 -->
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center p-1.5 group-hover:border-orange-100 transition-colors">
                                        <img src="https://hoirqrkdgbmvpwutwuwj.supabase.co/storage/v1/object/public/assets/assets/c543a9e1-f226-4ced-80b0-feb8445a75b9_1600w.jpg" class="w-full h-full object-contain mix-blend-multiply" alt="XPS">
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-[#363230] mb-0.5 line-clamp-1">Dell XPS 15 OLED</div>
                                        <div class="text-xs text-gray-500">98 sales</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-[#363230] mb-0.5">$146,902</div>
                                    <div class="text-[10px] font-medium text-amber-600">Low Stock</div>
                                </div>
                            </div>

                            <!-- Product Item 3 -->
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center p-1.5 group-hover:border-orange-100 transition-colors">
                                        <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-2xl text-gray-300"></iconify-icon>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-[#363230] mb-0.5 line-clamp-1">MacBook Air 15" M2</div>
                                        <div class="text-xs text-gray-500">85 sales</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-[#363230] mb-0.5">$110,415</div>
                                    <div class="text-[10px] font-medium text-emerald-600">In Stock</div>
                                </div>
                            </div>

                        </div>

                        <!-- Card Footer -->
                        <div class="p-4 border-t border-gray-50 bg-gray-50/30">
                            <button class="w-full py-2 px-4 rounded-xl text-xs font-medium text-gray-500 hover:text-[#363230] hover:bg-white border border-transparent hover:border-gray-200/80 shadow-sm transition-all duration-300">
                                View full report
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</div>


</body></html>
