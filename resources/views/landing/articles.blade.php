@extends('layouts.landing')

@php
    $seoTitle = 'Artikel, Panduan & Review Laptop Terbaru — ZLM.ID';
    $seoDescription = 'Dapatkan tips memilih laptop bekas berkualitas, panduan teknis, review performa spesifikasi, dan berita terbaru dunia laptop di ZLM.ID.';
    $seoKeywords = 'artikel laptop, panduan beli laptop, review laptop gaming, tips laptop bekas, spesifikasi laptop, zlm id';
    $seoUrl = route('landing.articles');
@endphp

@section('title', $seoTitle)

@section('content')
<div class="bg-gray-50 min-h-screen py-6 lg:py-8">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center gap-2 text-sm text-gray-500 font-medium">
                <li><a href="{{ route('landing.home') }}" class="hover:text-[#DF5E1D] transition-colors flex items-center gap-1"><iconify-icon icon="solar:home-2-linear" class="text-lg"></iconify-icon> Beranda</a></li>
                <li><iconify-icon icon="solar:alt-arrow-right-linear" class="text-gray-400"></iconify-icon></li>
                <li class="text-[#363230] font-semibold">Artikel & Panduan</li>
            </ol>
        </nav>

        <style>
            .hide-scrollbar::-webkit-scrollbar { display: none; }
        </style>

        <!-- Search & Filter Bar -->
        <div class="mb-10 lg:mb-12">
            <div class="flex flex-col lg:flex-row lg:items-center gap-6 justify-between">
                <!-- Pills Categories -->
                <div class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0 hide-scrollbar" style="-ms-overflow-style: none; scrollbar-width: none;">
                    <a href="{{ route('landing.articles', array_filter(['search' => request('search')])) }}" 
                       class="px-5 py-2.5 rounded-full text-sm font-semibold shrink-0 transition-all shadow-2xs {{ empty(request('category')) || request('category') === 'all' || request('category') === 'Semua' ? 'bg-[#363230] text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:border-[#DF5E1D] hover:text-[#DF5E1D]' }}">
                        Semua
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('landing.articles', array_filter(['category' => $cat, 'search' => request('search')])) }}" 
                           class="px-5 py-2.5 rounded-full text-sm font-semibold shrink-0 transition-all shadow-2xs {{ request('category') === $cat ? 'bg-[#363230] text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:border-[#DF5E1D] hover:text-[#DF5E1D]' }}">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>

                <!-- Search Form -->
                <form method="GET" action="{{ route('landing.articles') }}" class="relative w-full lg:w-80 shrink-0">
                    @if(request('category') && request('category') !== 'all')
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <iconify-icon icon="solar:magnifer-linear" class="text-lg"></iconify-icon>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari artikel..." 
                           class="w-full pl-11 pr-10 py-2.5 bg-white border border-gray-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] shadow-2xs transition-all">
                    @if(request('search'))
                        <a href="{{ route('landing.articles', array_filter(['category' => request('category')])) }}" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                            <iconify-icon icon="solar:close-circle-linear" class="text-base"></iconify-icon>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        @if(!empty($search))
            <div class="mb-8 flex items-center justify-between">
                <p class="text-sm text-gray-500">Hasil pencarian untuk: <strong class="text-[#363230]">"{{ $search }}"</strong> ({{ $articles->total() }} artikel)</p>
                <a href="{{ route('landing.articles') }}" class="text-xs font-semibold text-[#DF5E1D] hover:underline">Hapus Pencarian</a>
            </div>
        @endif

        <!-- Headline Section (1 Large Col-8 + 2 Secondary Col-4 Stacked) -->
        @if($featured)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-16 lg:mb-20">
                <!-- Main Featured Article (Left, Col-span-8) -->
                <a href="{{ route('landing.article-detail', $featured->slug) }}" class="{{ isset($secondary) && $secondary->count() > 0 ? 'lg:col-span-8' : 'lg:col-span-12' }} block group cursor-pointer relative rounded-[2rem] overflow-hidden shadow-md hover:shadow-xl transition-all duration-500 h-[400px] lg:h-[500px]">
                    <div class="absolute inset-0 bg-gray-900">
                        <img src="{{ $featured->thumbnail_url_full }}" alt="{{ $featured->name }}" class="w-full h-full object-cover opacity-70 group-hover:scale-105 group-hover:opacity-80 transition-all duration-700">
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/35 to-transparent"></div>
                    <div class="absolute inset-0 flex flex-col justify-end p-6 sm:p-8 lg:p-12">
                        <div class="flex items-center gap-3 mb-4 lg:mb-5">
                            <span class="bg-[#DF5E1D] text-white px-3.5 py-1.5 rounded-lg text-xs font-bold tracking-wider uppercase shadow-xs">
                                {{ $featured->category }}
                            </span>
                            <span class="text-sm font-medium text-gray-300">
                                {{ $featured->date ? $featured->date->format('d M Y') : '' }} • {{ $featured->reading_time }} min read
                            </span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-3 lg:mb-4 leading-snug group-hover:text-[#DF5E1D] transition-colors max-w-3xl">
                            {{ $featured->name }}
                        </h2>
                        @if($featured->excerpt)
                            <p class="text-gray-300 text-sm sm:text-base line-clamp-2 max-w-2xl leading-relaxed hidden sm:block">
                                {{ $featured->excerpt }}
                            </p>
                        @endif
                    </div>
                </a>

                <!-- Right Column with 2 Secondary Articles (Col-span-4) -->
                @if(isset($secondary) && $secondary->count() > 0)
                    <div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col gap-6">
                        @foreach($secondary as $item)
                            @php
                                $badgeColor = match($item->category) {
                                    'Tips & Trik', 'Tips' => 'text-[#3b82f6] bg-blue-50',
                                    'Review' => 'text-[#8b5cf6] bg-purple-50',
                                    'Berita' => 'text-[#10b981] bg-emerald-50',
                                    default => 'text-[#DF5E1D] bg-orange-50',
                                };
                            @endphp
                            <a href="{{ route('landing.article-detail', $item->slug) }}" class="flex-1 bg-white rounded-[2rem] p-6 lg:p-8 border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-xl transition-all duration-300 group flex flex-col justify-center">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="{{ $badgeColor }} text-xs font-bold uppercase tracking-wider px-2.5 py-1 rounded-md">
                                        {{ $item->category }}
                                    </span>
                                    <span class="text-xs font-medium text-gray-400">
                                        {{ $item->date ? $item->date->format('d M Y') : '' }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-[#363230] mb-3 leading-snug group-hover:text-[#DF5E1D] transition-colors line-clamp-3">
                                    {{ $item->name }}
                                </h3>
                                <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed">
                                    {{ $item->excerpt ?: Str::limit(strip_tags($item->description), 120) }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        <!-- Latest Articles Header -->
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-2xl font-bold text-[#363230] flex items-center gap-3">
                <iconify-icon icon="solar:clock-circle-bold-duotone" class="text-[#DF5E1D] text-3xl"></iconify-icon>
                Artikel Terbaru
            </h3>
            <div class="h-[1px] flex-1 bg-gray-200 ml-6 hidden sm:block"></div>
        </div>

        <!-- Grid Articles -->
        @if($articles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 mb-12">
                @foreach($articles as $article)
                    <article class="bg-white rounded-2xl border border-gray-200/60 overflow-hidden shadow-2xs hover:shadow-lg transition-all duration-300 flex flex-col group">
                        <!-- Thumbnail -->
                        <a href="{{ route('landing.article-detail', $article->slug) }}" class="block relative aspect-video overflow-hidden bg-gray-100">
                            <img src="{{ $article->thumbnail_url_full }}" alt="{{ $article->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <span class="absolute top-3 left-3 bg-[#363230]/80 backdrop-blur-xs text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md">
                                {{ $article->category }}
                            </span>
                        </a>

                        <!-- Body -->
                        <div class="p-5 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-2 text-[11px] text-gray-400 mb-2">
                                    <span>{{ $article->date ? $article->date->format('d M Y') : '' }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $article->reading_time }} min read</span>
                                </div>
                                <h3 class="text-base font-bold text-[#363230] group-hover:text-[#DF5E1D] transition-colors line-clamp-2 leading-snug mb-2">
                                    <a href="{{ route('landing.article-detail', $article->slug) }}">
                                        {{ $article->name }}
                                    </a>
                                </h3>
                                <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">
                                    {{ $article->excerpt ?: Str::limit(strip_tags($article->description), 110) }}
                                </p>
                            </div>

                            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between text-xs">
                                <span class="font-medium text-gray-400">Oleh <span class="text-[#363230] font-semibold">{{ $article->author }}</span></span>
                                <a href="{{ route('landing.article-detail', $article->slug) }}" class="text-[#DF5E1D] font-bold flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                                    Baca <iconify-icon icon="solar:arrow-right-linear"></iconify-icon>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                {{ $articles->links() }}
            </div>
        @elseif(!$featured)
            <!-- Empty State -->
            <div class="text-center py-20 bg-white rounded-3xl border border-gray-200/60 p-8 my-8">
                <div class="w-16 h-16 rounded-2xl bg-orange-50 text-[#DF5E1D] flex items-center justify-center mx-auto mb-4">
                    <iconify-icon icon="solar:document-text-linear" class="text-3xl"></iconify-icon>
                </div>
                <h3 class="text-lg font-bold text-[#363230]">Tidak Ada Artikel Ditemukan</h3>
                <p class="text-xs text-gray-500 mt-1 max-w-md mx-auto">Tidak menemukan artikel yang sesuai dengan filter atau kata kunci pencarian Anda.</p>
                <a href="{{ route('landing.articles') }}" class="inline-flex items-center gap-2 mt-4 bg-[#DF5E1D] text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-[#c45218] transition-colors shadow-sm">
                    Lihat Semua Artikel
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
