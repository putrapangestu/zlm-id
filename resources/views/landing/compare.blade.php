@extends('layouts.landing')

@section('title', 'Compare Laptops')

@section('content')
<div class="min-h-screen py-12 lg:py-20">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <style>
            @keyframes slideUpFade {
                0% { opacity: 0; transform: translateY(1rem); }
                100% { opacity: 1; transform: translateY(0); }
            }
            @keyframes expandWidth {
                0% { width: 0; opacity: 0; }
                100% { opacity: 1; }
            }
            .animate-slide-up {
                animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                opacity: 0;
            }
            .animate-bar {
                animation: expandWidth 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                opacity: 0;
            }

            /* Custom Scrollbar for the table if needed */
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #d1d5db; }
        </style>

        <!-- Breadcrumb -->
        <nav class="mb-10 lg:mb-14 animate-slide-up" style="animation-delay: 0s;">
            <ol class="flex items-center gap-2 text-sm text-gray-500 font-medium">
                <li>
                    <a href="#" class="hover:text-[#DF5E1D] transition-colors duration-200 flex items-center gap-1.5 rounded-md focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DF5E1D]/50">
                        <iconify-icon icon="solar:home-2-linear" class="text-base" style="stroke-width: 1.5;"></iconify-icon>
                        Beranda
                    </a>
                </li>
                <li class="flex items-center text-gray-300">
                    <iconify-icon icon="solar:alt-arrow-right-linear" style="stroke-width: 1.5;"></iconify-icon>
                </li>
                <li>
                    <a href="#" class="hover:text-[#DF5E1D] transition-colors duration-200 rounded-md focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DF5E1D]/50">Produk</a>
                </li>
                <li class="flex items-center text-gray-300">
                    <iconify-icon icon="solar:alt-arrow-right-linear" style="stroke-width: 1.5;"></iconify-icon>
                </li>
                <li class="text-[#363230] truncate">Perbandingan</li>
            </ol>
        </nav>

        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-12 animate-slide-up" style="animation-delay: 0.1s;">
            <div>
                <h1 class="text-3xl lg:text-4xl font-medium tracking-tight text-[#363230] mb-3">Produk Terpilih</h1>
                <p class="text-base text-gray-500 max-w-xl">Bandingkan perangkat pilihan Anda untuk merekomposisi laptop terbaik.</p>
            </div>

            <!-- Custom Toggle -->
            <div class="flex items-center gap-3 bg-white px-4 py-2.5 rounded-2xl border border-gray-200/80 shadow-sm">
                <span class="text-xs font-medium text-gray-500">Soroti Perbedaan</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" value="" class="sr-only peer" checked="">
                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#DF5E1D]"></div>
                </label>
            </div>
        </div>

        <!-- Comparison Container -->
        <div class="bg-white rounded-3xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden animate-slide-up relative" style="animation-delay: 0.2s;">

            <!-- Sticky Product Header -->
            <div class="sticky top-0 z-30 bg-white/90 backdrop-blur-xl border-b border-gray-200/60 shadow-sm supports-[backdrop-filter]:bg-white/60">
                <div class="grid grid-cols-2 lg:grid-cols-[240px_1fr_1fr] relative">

                    <!-- Empty Top Left for Desktop -->
                    <div class="hidden lg:flex items-end p-6 border-r border-gray-100">
                        <span class="text-sm font-medium text-gray-400 uppercase tracking-widest">Spesifikasi</span>
                    </div>

                    <!-- VS Badge -->
                    <div class="absolute left-1/2 lg:left-[calc(50%+120px)] top-1/2 -translate-x-1/2 -translate-y-1/2 z-10 w-10 h-10 bg-white rounded-full border border-gray-200/80 shadow-md flex items-center justify-center text-xs font-medium text-[#DF5E1D] tracking-widest mt-4 lg:mt-0">
                        VS
                    </div>

                    <!-- Product 1 Header -->
                    <div class="p-6 relative group border-r border-gray-100 flex flex-col items-center text-center">
                        <div class="relative w-32 h-32 lg:w-40 lg:h-40 mb-6 cursor-pointer">
                            <img src="https://hoirqrkdgbmvpwutwuwj.supabase.co/storage/v1/object/public/assets/assets/917d6f93-fb36-439a-8c48-884b67b35381_1600w.jpg" alt="Laptop 1" class="w-full h-full object-contain mix-blend-multiply transition-transform duration-700 ease-out group-hover:scale-110 group-hover:-translate-y-2">
                        </div>
                        <span class="text-[10px] font-medium text-[#DF5E1D] tracking-widest uppercase bg-[#DF5E1D]/10 px-2.5 py-1 rounded-md mb-3">
                            Apple
                        </span>
                        <h3 class="text-base lg:text-lg font-medium tracking-tight text-[#363230] mb-2 line-clamp-1">MacBook Pro 14" M3</h3>
                        <div class="text-lg lg:text-xl font-medium tracking-tight text-[#363230] mb-5">$1,599</div>
                        <button class="w-full bg-white border border-gray-200 shadow-sm text-[#363230] py-2.5 px-4 rounded-xl text-xs font-medium hover:bg-gray-50 hover:border-gray-300 transition-all duration-300 flex items-center justify-center gap-2 group-hover:border-[#DF5E1D]/30 group-hover:text-[#DF5E1D]">
                            <iconify-icon icon="solar:cart-large-2-linear" class="text-sm" style="stroke-width: 1.5;"></iconify-icon>
                            <span class="hidden sm:inline">Lihat Detail</span>
                        </button>
                    </div>

                    <!-- Product 2 Header -->
                    <div class="p-6 relative group flex flex-col items-center text-center bg-gray-50/30">
                        <div class="relative w-32 h-32 lg:w-40 lg:h-40 mb-6 cursor-pointer">
                            <img src="https://hoirqrkdgbmvpwutwuwj.supabase.co/storage/v1/object/public/assets/assets/c543a9e1-f226-4ced-80b0-feb8445a75b9_1600w.jpg" alt="Laptop 2" class="w-full h-full object-contain mix-blend-multiply transition-transform duration-700 ease-out group-hover:scale-110 group-hover:-translate-y-2">
                        </div>
                        <span class="text-[10px] font-medium text-gray-500 tracking-widest uppercase bg-gray-100 border border-gray-200/60 px-2.5 py-1 rounded-md mb-3">
                            Dell
                        </span>
                        <h3 class="text-base lg:text-lg font-medium tracking-tight text-[#363230] mb-2 line-clamp-1">XPS 15 OLED</h3>
                        <div class="text-lg lg:text-xl font-medium tracking-tight text-[#363230] mb-5">$1,499</div>
                        <button class="w-full bg-white border border-gray-200 shadow-sm text-[#363230] py-2.5 px-4 rounded-xl text-xs font-medium hover:bg-gray-50 hover:border-gray-300 transition-all duration-300 flex items-center justify-center gap-2 group-hover:border-[#DF5E1D]/30 group-hover:text-[#DF5E1D]">
                            <iconify-icon icon="solar:cart-large-2-linear" class="text-sm" style="stroke-width: 1.5;"></iconify-icon>
                            <span class="hidden sm:inline">Lihat Detail</span>
                        </button>
                    </div>

                </div>
            </div>

            <!-- Table Body -->
            <div class="divide-y divide-gray-100">

                <!-- Category 1: Performance -->
                <div class="bg-gray-50/80 py-3 px-6 text-xs font-medium text-gray-400 uppercase tracking-widest border-y border-gray-100">
                    Performa
                </div>

                <!-- Spec Row 1 -->
                <div class="grid grid-cols-2 lg:grid-cols-[240px_1fr_1fr] group hover:bg-orange-50/30 transition-colors duration-300">
                    <!-- Mobile Label -->
                    <div class="col-span-2 lg:hidden text-xs text-center py-2 text-gray-400 bg-gray-50/50 border-b border-gray-50">Prosesor</div>
                    <!-- Desktop Label -->
                    <div class="hidden lg:flex items-center p-6 border-r border-gray-100">
                        <div class="flex items-center gap-3 text-sm font-medium text-gray-600">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:bg-white group-hover:border-orange-100 transition-colors duration-300">
                                <iconify-icon icon="solar:cpu-linear" class="text-lg text-gray-400 group-hover:text-[#DF5E1D] transition-colors duration-300" style="stroke-width: 1.5;"></iconify-icon>
                            </div>
                            Prosesor
                        </div>
                    </div>
                    <!-- Value 1 -->
                    <div class="p-5 lg:p-6 text-sm text-[#363230] text-center lg:text-left border-r border-gray-100 flex flex-col justify-center">
                        <span class="font-medium bg-orange-50 text-[#DF5E1D] inline-block px-2 py-1 rounded-md self-center lg:self-start w-fit mb-2 border border-orange-100/50">Apple M3</span>
                        <span class="text-gray-500 text-xs">8-core CPU, 10-core GPU</span>
                    </div>
                    <!-- Value 2 -->
                    <div class="p-5 lg:p-6 text-sm text-[#363230] text-center lg:text-left bg-gray-50/10 flex flex-col justify-center">
                        <span class="font-medium inline-block px-2 py-1 rounded-md self-center lg:self-start w-fit mb-2">Intel Core i7</span>
                        <span class="text-gray-500 text-xs">13th Gen i7-13700H, 14 cores</span>
                    </div>
                </div>

                <!-- Spec Row 2 (With Visual Bar) -->
                <div class="grid grid-cols-2 lg:grid-cols-[240px_1fr_1fr] group hover:bg-orange-50/30 transition-colors duration-300">
                    <div class="col-span-2 lg:hidden text-xs text-center py-2 text-gray-400 bg-gray-50/50 border-b border-gray-50">Memory (RAM)</div>
                    <div class="hidden lg:flex items-center p-6 border-r border-gray-100">
                        <div class="flex items-center gap-3 text-sm font-medium text-gray-600">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:bg-white group-hover:border-orange-100 transition-colors duration-300">
                                <iconify-icon icon="solar:ram-linear" class="text-lg text-gray-400 group-hover:text-[#DF5E1D] transition-colors duration-300" style="stroke-width: 1.5;"></iconify-icon>
                            </div>
                            Memori (RAM)
                        </div>
                    </div>
                    <!-- Value 1 -->
                    <div class="p-5 lg:p-6 border-r border-gray-100 flex flex-col justify-center">
                        <div class="text-sm font-medium text-[#363230] text-center lg:text-left mb-2">8GB Unified Memory</div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-[#DF5E1D] h-1.5 rounded-full animate-bar" style="width: 25%; animation-delay: 0.5s;"></div>
                        </div>
                    </div>
                    <!-- Value 2 -->
                    <div class="p-5 lg:p-6 bg-gray-50/10 flex flex-col justify-center">
                        <div class="text-sm font-medium text-[#363230] text-center lg:text-left mb-2">16GB DDR5</div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-gray-400 h-1.5 rounded-full animate-bar" style="width: 50%; animation-delay: 0.6s;"></div>
                        </div>
                    </div>
                </div>

                <!-- Spec Row 3 (Storage) -->
                <div class="grid grid-cols-2 lg:grid-cols-[240px_1fr_1fr] group hover:bg-orange-50/30 transition-colors duration-300">
                    <div class="col-span-2 lg:hidden text-xs text-center py-2 text-gray-400 bg-gray-50/50 border-b border-gray-50">Penyimpanan</div>
                    <div class="hidden lg:flex items-center p-6 border-r border-gray-100">
                        <div class="flex items-center gap-3 text-sm font-medium text-gray-600">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:bg-white group-hover:border-orange-100 transition-colors duration-300">
                                <iconify-icon icon="solar:database-linear" class="text-lg text-gray-400 group-hover:text-[#DF5E1D] transition-colors duration-300" style="stroke-width: 1.5;"></iconify-icon>
                            </div>
                            Penyimpanan
                        </div>
                    </div>
                    <div class="p-5 lg:p-6 text-sm text-[#363230] text-center lg:text-left border-r border-gray-100 flex items-center justify-center lg:justify-start">
                        512GB SSD
                    </div>
                    <div class="p-5 lg:p-6 text-sm text-[#363230] text-center lg:text-left bg-gray-50/10 flex items-center justify-center lg:justify-start">
                        <span class="font-medium bg-orange-50 text-[#DF5E1D] inline-block px-2 py-1 rounded-md border border-orange-100/50">1TB SSD</span>
                    </div>
                </div>

                <!-- Category 2: Display & Design -->
                <div class="bg-gray-50/80 py-3 px-6 text-xs font-medium text-gray-400 uppercase tracking-widest border-y border-gray-100">
                    Layar & Desain
                </div>

                <!-- Spec Row 4 -->
                <div class="grid grid-cols-2 lg:grid-cols-[240px_1fr_1fr] group hover:bg-orange-50/30 transition-colors duration-300">
                    <div class="col-span-2 lg:hidden text-xs text-center py-2 text-gray-400 bg-gray-50/50 border-b border-gray-50">Layar</div>
                    <div class="hidden lg:flex items-center p-6 border-r border-gray-100">
                        <div class="flex items-center gap-3 text-sm font-medium text-gray-600">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:bg-white group-hover:border-orange-100 transition-colors duration-300">
                                <iconify-icon icon="solar:monitor-linear" class="text-lg text-gray-400 group-hover:text-[#DF5E1D] transition-colors duration-300" style="stroke-width: 1.5;"></iconify-icon>
                            </div>
                            Layar
                        </div>
                    </div>
                    <div class="p-5 lg:p-6 text-sm text-[#363230] text-center lg:text-left border-r border-gray-100 flex flex-col justify-center">
                        <span class="font-medium mb-1">14.2-inch Liquid Retina XDR</span>
                        <span class="text-gray-500 text-xs">3024 x 1964, 120Hz ProMotion</span>
                    </div>
                    <div class="p-5 lg:p-6 text-sm text-[#363230] text-center lg:text-left bg-gray-50/10 flex flex-col justify-center">
                        <span class="font-medium mb-1">15.6-inch OLED Touch</span>
                        <span class="text-gray-500 text-xs">3456 x 2160, 60Hz</span>
                    </div>
                </div>

                <!-- Spec Row 5 (Weight Visuals) -->
                <div class="grid grid-cols-2 lg:grid-cols-[240px_1fr_1fr] group hover:bg-orange-50/30 transition-colors duration-300">
                    <div class="col-span-2 lg:hidden text-xs text-center py-2 text-gray-400 bg-gray-50/50 border-b border-gray-50">Berat</div>
                    <div class="hidden lg:flex items-center p-6 border-r border-gray-100">
                        <div class="flex items-center gap-3 text-sm font-medium text-gray-600">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:bg-white group-hover:border-orange-100 transition-colors duration-300">
                                <iconify-icon icon="solar:case-minimalistic-linear" class="text-lg text-gray-400 group-hover:text-[#DF5E1D] transition-colors duration-300" style="stroke-width: 1.5;"></iconify-icon>
                            </div>
                            Berat
                        </div>
                    </div>
                    <div class="p-5 lg:p-6 border-r border-gray-100 flex items-center justify-center lg:justify-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-orange-50 border border-orange-100 text-[#DF5E1D] flex items-center justify-center text-xs font-medium shadow-sm group-hover:scale-110 transition-transform duration-300">
                            1.55
                        </div>
                        <span class="text-sm font-medium text-[#363230]">kg</span>
                    </div>
                    <div class="p-5 lg:p-6 bg-gray-50/10 flex items-center justify-center lg:justify-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-gray-50 border border-gray-200 text-gray-500 flex items-center justify-center text-xs font-medium group-hover:scale-110 transition-transform duration-300">
                            1.92
                        </div>
                        <span class="text-sm font-medium text-[#363230]">kg</span>
                    </div>
                </div>

                <!-- Spec Row 6 (Battery) -->
                <div class="grid grid-cols-2 lg:grid-cols-[240px_1fr_1fr] group hover:bg-orange-50/30 transition-colors duration-300">
                    <div class="col-span-2 lg:hidden text-xs text-center py-2 text-gray-400 bg-gray-50/50 border-b border-gray-50">Daya Baterai</div>
                    <div class="hidden lg:flex items-center p-6 border-r border-gray-100">
                        <div class="flex items-center gap-3 text-sm font-medium text-gray-600">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:bg-white group-hover:border-orange-100 transition-colors duration-300">
                                <iconify-icon icon="solar:battery-charge-linear" class="text-lg text-gray-400 group-hover:text-[#DF5E1D] transition-colors duration-300" style="stroke-width: 1.5;"></iconify-icon>
                            </div>
                            Daya Baterai
                        </div>
                    </div>
                    <div class="p-5 lg:p-6 border-r border-gray-100 flex flex-col justify-center">
                        <div class="flex justify-between text-xs mb-2">
                            <span class="font-medium text-[#DF5E1D]">Up to 22 hrs</span>
                            <span class="text-gray-400">Pemenang</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-gradient-to-r from-[#DF5E1D] to-[#f4854b] h-1.5 rounded-full animate-bar" style="width: 100%; animation-delay: 0.7s;"></div>
                        </div>
                    </div>
                    <div class="p-5 lg:p-6 bg-gray-50/10 flex flex-col justify-center">
                        <div class="flex justify-between text-xs mb-2">
                            <span class="font-medium text-[#363230]">Up to 12 hrs</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-gray-400 h-1.5 rounded-full animate-bar" style="width: 55%; animation-delay: 0.8s;"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </div>
</div>
@endsection

