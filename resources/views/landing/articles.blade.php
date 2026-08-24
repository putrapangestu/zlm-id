@extends('layouts.landing')

@section('title', 'Artikel - ZLM.ID')

@section('content')
<div class="bg-gray-50 min-h-screen py-12 lg:py-20">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-16">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-[#DF5E1D]/10 border border-[#DF5E1D]/30 text-[#DF5E1D] text-xs font-medium mb-4">
                <iconify-icon icon="solar:document-linear"></iconify-icon>
                Pusat Pengetahuan
            </div>
            <h1 class="text-4xl md:text-5xl font-semibold tracking-tight text-[#363230] mb-4">Artikel & Panduan</h1>
            <p class="text-lg text-gray-600 max-w-3xl">Jelajahi panduan mendalam, tips teknis, dan ulasan produk untuk membantu Anda membuat keputusan pembelian yang tepat.</p>
        </div>

        <!-- Search & Filter -->
        <div class="mb-12 flex flex-col sm:flex-row gap-4">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                    <iconify-icon icon="solar:magnifer-linear"></iconify-icon>
                </div>
                <input type="text" placeholder="Cari artikel..." class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all">
            </div>
            <select class="px-4 py-3 bg-white border border-gray-200 rounded-lg text-sm text-gray-600 appearance-none focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all cursor-pointer">
                <option>Semua Kategori</option>
                <option>Panduan Pembelian</option>
                <option>Ulasan Produk</option>
                <option>Tips & Trik</option>
                <option>Berita</option>
            </select>
        </div>

        <!-- Articles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Article 1 -->
            <a href="{{ route('landing.article-detail', 1) }}" class="block bg-white rounded-xl border border-gray-200/60 overflow-hidden hover:shadow-lg transition-all duration-300 group cursor-pointer">
                <div class="relative h-48 bg-gray-100 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1555680202-c86f0e12f086?auto=format&fit=crop&q=80&w=600" alt="CPU Architecture" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-3 left-3 bg-[#DF5E1D]/10 text-[#DF5E1D] px-3 py-1 rounded-md text-xs font-semibold">Panduan</div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                        <span>15 Apr 2026</span>
                        <span>•</span>
                        <span>8 min read</span>
                    </div>
                    <h3 class="text-lg font-semibold text-[#363230] mb-2 group-hover:text-[#DF5E1D] transition-colors line-clamp-2">
                        Memahami Perbedaan ARM vs x86 untuk Workload Modern
                    </h3>
                    <p class="text-sm text-gray-600 line-clamp-3">
                        Perbandingan mendalam tentang arsitektur processor dan dampaknya terhadap performa, battery life, dan thermal throttling di laptop modern.
                    </p>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-500">Admin</span>
                        <iconify-icon icon="solar:arrow-right-linear" class="text-[#DF5E1D] group-hover:translate-x-1 transition-transform"></iconify-icon>
                    </div>
                </div>
            </a>

            <!-- Article 2 -->
            <a href="{{ route('landing.article-detail', 1) }}" class="block bg-white rounded-xl border border-gray-200/60 overflow-hidden hover:shadow-lg transition-all duration-300 group cursor-pointer">
                <div class="relative h-48 bg-gray-100 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&q=80&w=600" alt="Laptop Tips" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-3 left-3 bg-blue-100 text-blue-600 px-3 py-1 rounded-md text-xs font-semibold">Tips</div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                        <span>12 Apr 2026</span>
                        <span>•</span>
                        <span>6 min read</span>
                    </div>
                    <h3 class="text-lg font-semibold text-[#363230] mb-2 group-hover:text-[#DF5E1D] transition-colors line-clamp-2">
                        Optimasi Laptop Workstation Mobile Anda
                    </h3>
                    <p class="text-sm text-gray-600 line-clamp-3">
                        Konfigurasi software esensial dan rutinitas maintenance untuk mencegah penurunan performa dari waktu ke waktu.
                    </p>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-500">Admin</span>
                        <iconify-icon icon="solar:arrow-right-linear" class="text-[#DF5E1D] group-hover:translate-x-1 transition-transform"></iconify-icon>
                    </div>
                </div>
            </a>

            <!-- Article 3 -->
            <a href="{{ route('landing.article-detail', 1) }}" class="block bg-white rounded-xl border border-gray-200/60 overflow-hidden hover:shadow-lg transition-all duration-300 group cursor-pointer">
                <div class="relative h-48 bg-gray-100 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?auto=format&fit=crop&q=80&w=600" alt="Display" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-3 left-3 bg-purple-100 text-purple-600 px-3 py-1 rounded-md text-xs font-semibold">Review</div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                        <span>10 Apr 2026</span>
                        <span>•</span>
                        <span>10 min read</span>
                    </div>
                    <h3 class="text-lg font-semibold text-[#363230] mb-2 group-hover:text-[#DF5E1D] transition-colors line-clamp-2">
                        OLED vs Mini-LED: Dilema Profesional Konten
                    </h3>
                    <p class="text-sm text-gray-600 line-clamp-3">
                        Analisis mendalam tentang akurasi warna, contrast ratio, dan risiko burn-in untuk fotografer dan video editor.
                    </p>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-500">Admin</span>
                        <iconify-icon icon="solar:arrow-right-linear" class="text-[#DF5E1D] group-hover:translate-x-1 transition-transform"></iconify-icon>
                    </div>
                </div>
            </a>

            <!-- Article 4 -->
            <a href="{{ route('landing.article-detail', 1) }}" class="block bg-white rounded-xl border border-gray-200/60 overflow-hidden hover:shadow-lg transition-all duration-300 group cursor-pointer">
                <div class="relative h-48 bg-gray-100 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&q=80&w=600" alt="Gaming" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-3 left-3 bg-red-100 text-red-600 px-3 py-1 rounded-md text-xs font-semibold">Gaming</div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                        <span>08 Apr 2026</span>
                        <span>•</span>
                        <span>7 min read</span>
                    </div>
                    <h3 class="text-lg font-semibold text-[#363230] mb-2 group-hover:text-[#DF5E1D] transition-colors line-clamp-2">
                        Memilih Laptop Gaming Terbaik di 2026
                    </h3>
                    <p class="text-sm text-gray-600 line-clamp-3">
                        Panduan lengkap untuk memilih laptop gaming berdasarkan GPU, refresh rate, dan kebutuhan thermal management.
                    </p>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-500">Admin</span>
                        <iconify-icon icon="solar:arrow-right-linear" class="text-[#DF5E1D] group-hover:translate-x-1 transition-transform"></iconify-icon>
                    </div>
                </div>
            </a>

            <!-- Article 5 -->
            <a href="{{ route('landing.article-detail', 1) }}" class="block bg-white rounded-xl border border-gray-200/60 overflow-hidden hover:shadow-lg transition-all duration-300 group cursor-pointer">
                <div class="relative h-48 bg-gray-100 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1516321318423-f06140cf6439?auto=format&fit=crop&q=80&w=600" alt="Business" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-3 left-3 bg-green-100 text-green-600 px-3 py-1 rounded-md text-xs font-semibold">Bisnis</div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                        <span>05 Apr 2026</span>
                        <span>•</span>
                        <span>9 min read</span>
                    </div>
                    <h3 class="text-lg font-semibold text-[#363230] mb-2 group-hover:text-[#DF5E1D] transition-colors line-clamp-2">
                        Laptop Bisnis untuk Produktivitas Maksimal
                    </h3>
                    <p class="text-sm text-gray-600 line-clamp-3">
                        Perbandingan laptop enterprise dengan fokus pada keamanan, battery life, dan portabilitas untuk profesional mobile.
                    </p>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-500">Admin</span>
                        <iconify-icon icon="solar:arrow-right-linear" class="text-[#DF5E1D] group-hover:translate-x-1 transition-transform"></iconify-icon>
                    </div>
                </div>
            </a>

            <!-- Article 6 -->
            <a href="{{ route('landing.article-detail', 1) }}" class="block bg-white rounded-xl border border-gray-200/60 overflow-hidden hover:shadow-lg transition-all duration-300 group cursor-pointer">
                <div class="relative h-48 bg-gray-100 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&q=80&w=600" alt="Student Laptop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-3 left-3 bg-yellow-100 text-yellow-600 px-3 py-1 rounded-md text-xs font-semibold">Pelajar</div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-3">
                        <span>02 Apr 2026</span>
                        <span>•</span>
                        <span>5 min read</span>
                    </div>
                    <h3 class="text-lg font-semibold text-[#363230] mb-2 group-hover:text-[#DF5E1D] transition-colors line-clamp-2">
                        Laptop Terbaik untuk Mahasiswa dengan Budget Terbatas
                    </h3>
                    <p class="text-sm text-gray-600 line-clamp-3">
                        Rekomendasi laptop affordable yang cukup powerful untuk study, coding, dan entertainment tanpa merogoh kocek terlalu dalam.
                    </p>
                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-500">Admin</span>
                        <iconify-icon icon="solar:arrow-right-linear" class="text-[#DF5E1D] group-hover:translate-x-1 transition-transform"></iconify-icon>
                    </div>
                </div>
            </a>
        </div>

        <!-- Load More Button -->
        <div class="mt-16 flex justify-center">
            <button class="px-8 py-3 bg-gray-100 border border-gray-200 text-gray-600 rounded-lg font-medium hover:bg-white hover:text-[#DF5E1D] hover:border-[#DF5E1D] transition-all">
                Muat Artikel Lebih Banyak
            </button>
        </div>
    </div>
</div>
@endsection
