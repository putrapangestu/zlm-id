@extends('layouts.landing')

@php
    $seoTitle = $article->seo_meta_title;
    $seoDescription = $article->seo_meta_description;
    $seoKeywords = $article->meta_keywords ?: "artikel {$article->name}, review laptop, panduan laptop, {$article->category}, zlm id";
    $seoImage = $article->thumbnail_url_full;
    $seoUrl = route('landing.article-detail', $article->slug);
    $seoType = 'article';
    $seoAuthor = $article->author;
@endphp

@section('title', $seoTitle)

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => route('landing.article-detail', $article->slug),
    ],
    'headline' => $article->name,
    'description' => $article->seo_meta_description,
    'image' => $article->thumbnail_url_full,
    'author' => [
        '@type' => 'Person',
        'name' => $article->author,
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'ZLM.ID',
        'logo' => [
            '@type' => 'ImageObject',
            'url' => asset('assets/logo.png'),
        ],
    ],
    'datePublished' => $article->date ? $article->date->toIso8601String() : $article->created_at->toIso8601String(),
    'dateModified' => $article->updated_at ? $article->updated_at->toIso8601String() : now()->toIso8601String(),
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen py-6 lg:py-10">
    <div class="max-w-[960px] mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6 lg:mb-8">
            <ol class="flex items-center flex-wrap gap-2 text-xs text-gray-500 font-medium">
                <li>
                    <a href="{{ route('landing.home') }}" class="hover:text-[#DF5E1D] transition-colors flex items-center gap-1">
                        <iconify-icon icon="solar:home-2-linear" class="text-sm"></iconify-icon> Beranda
                    </a>
                </li>
                <li><iconify-icon icon="solar:alt-arrow-right-linear" class="text-gray-400"></iconify-icon></li>
                <li>
                    <a href="{{ route('landing.articles') }}" class="hover:text-[#DF5E1D] transition-colors">Artikel & Panduan</a>
                </li>
                <li><iconify-icon icon="solar:alt-arrow-right-linear" class="text-gray-400"></iconify-icon></li>
                <li>
                    <a href="{{ route('landing.articles', ['category' => $article->category]) }}" class="hover:text-[#DF5E1D] transition-colors">
                        {{ $article->category }}
                    </a>
                </li>
            </ol>
        </nav>

        <!-- Article Container -->
        <article class="bg-white rounded-3xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden p-6 sm:p-10 lg:p-12 mb-12">
            <!-- Article Header -->
            <header class="mb-8 pb-8 border-b border-gray-100">
                <div class="flex items-center gap-2.5 mb-4 flex-wrap">
                    <span class="bg-[#DF5E1D] text-white text-[11px] font-bold uppercase tracking-wider px-3 py-1 rounded-lg shadow-2xs">
                        {{ $article->category }}
                    </span>
                    <span class="text-xs text-gray-400">
                        {{ $article->date ? $article->date->format('d F Y') : $article->created_at->format('d F Y') }}
                    </span>
                    <span class="text-xs text-gray-300">&bull;</span>
                    <span class="text-xs text-gray-400">{{ $article->reading_time }} menit baca</span>
                    <span class="text-xs text-gray-300">&bull;</span>
                    <span class="text-xs text-gray-400">{{ $article->views_count }} x dibaca</span>
                </div>

                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-[#363230] leading-tight tracking-tight mb-4">
                    {{ $article->name }}
                </h1>

                @if($article->excerpt)
                    <p class="text-sm sm:text-base text-gray-500 leading-relaxed font-normal">
                        {{ $article->excerpt }}
                    </p>
                @endif

                <!-- Author & Share -->
                <div class="mt-6 pt-6 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-orange-100 text-[#DF5E1D] font-bold flex items-center justify-center text-sm">
                            {{ strtoupper(substr($article->author, 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-xs font-bold text-[#363230]">{{ $article->author }}</div>
                            <div class="text-[11px] text-gray-400">Tim Redaksi & Kurasi ZLM.ID</div>
                        </div>
                    </div>

                    <!-- Share Buttons -->
                    <div class="flex items-center gap-2" x-data="{ copied: false }">
                        <span class="text-xs font-semibold text-gray-400 mr-1">Bagikan:</span>
                        <!-- WhatsApp -->
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($article->name . ' - ' . route('landing.article-detail', $article->slug)) }}" target="_blank" 
                           class="w-8 h-8 rounded-xl bg-gray-50 hover:bg-emerald-50 text-gray-600 hover:text-emerald-600 border border-gray-200 flex items-center justify-center transition-colors" title="Bagikan ke WhatsApp">
                            <iconify-icon icon="solar:chat-round-dots-linear" class="text-base"></iconify-icon>
                        </a>
                        <!-- Twitter / X -->
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->name) }}&url={{ urlencode(route('landing.article-detail', $article->slug)) }}" target="_blank" 
                           class="w-8 h-8 rounded-xl bg-gray-50 hover:bg-sky-50 text-gray-600 hover:text-sky-600 border border-gray-200 flex items-center justify-center transition-colors" title="Bagikan ke Twitter">
                            <iconify-icon icon="solar:share-circle-linear" class="text-base"></iconify-icon>
                        </a>
                        <!-- Copy Link -->
                        <button x-on:click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000)" 
                                class="h-8 px-2.5 rounded-xl bg-gray-50 hover:bg-orange-50 text-gray-600 hover:text-[#DF5E1D] border border-gray-200 text-xs font-semibold flex items-center gap-1.5 transition-colors">
                            <iconify-icon icon="solar:copy-linear" class="text-sm"></iconify-icon>
                            <span x-text="copied ? 'Tersalin!' : 'Salin URL'"></span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Featured Image -->
            @if($article->thumbnail)
                <div class="mb-8 rounded-2xl overflow-hidden aspect-video max-h-[460px] w-full bg-gray-100 shadow-sm">
                    <img src="{{ $article->thumbnail_url_full }}" alt="{{ $article->name }}" class="w-full h-full object-cover">
                </div>
            @endif

            <!-- Article Body (Trix content styling) -->
            <div class="prose prose-stone max-w-none text-[#363230] leading-relaxed text-sm sm:text-base space-y-4">
                {!! $article->description !!}
            </div>

            <!-- Call to Action Banner -->
            <div class="mt-12 p-6 sm:p-8 rounded-2xl bg-gradient-to-r from-orange-50 to-orange-100/60 border border-orange-200/80 flex flex-col sm:flex-row items-center justify-between gap-6">
                <div>
                    <h4 class="text-base font-bold text-[#363230] mb-1">Sedang Cari Laptop yang Tepat?</h4>
                    <p class="text-xs text-gray-600 max-w-md">Kunjungi katalog laptop kami dengan garansi resmi & lolos uji QC ketat sebelum dikirim ke rumah Anda.</p>
                </div>
                <a href="{{ route('landing.search') }}" class="shrink-0 bg-[#DF5E1D] hover:bg-[#c45218] text-white px-5 py-2.5 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                    <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-base"></iconify-icon>
                    Jelajahi Laptop
                </a>
            </div>
        </article>

        <!-- Related Articles -->
        @if(isset($relatedArticles) && $relatedArticles->count() > 0)
            <div class="mt-12">
                <h3 class="text-xl font-bold text-[#363230] mb-6 flex items-center gap-2">
                    <iconify-icon icon="solar:document-text-linear" class="text-[#DF5E1D]"></iconify-icon>
                    Artikel Terkait Lainnya
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedArticles as $related)
                        <div class="bg-white rounded-2xl border border-gray-200/60 overflow-hidden shadow-2xs hover:shadow-md transition-all flex flex-col justify-between group">
                            <a href="{{ route('landing.article-detail', $related->slug) }}" class="block aspect-video bg-gray-100 overflow-hidden">
                                <img src="{{ $related->thumbnail_url_full }}" alt="{{ $related->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </a>
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <div>
                                    <span class="text-[10px] font-bold text-[#DF5E1D] uppercase tracking-wider block mb-1">
                                        {{ $related->category }}
                                    </span>
                                    <h4 class="text-xs font-bold text-[#363230] group-hover:text-[#DF5E1D] transition-colors line-clamp-2 leading-snug">
                                        <a href="{{ route('landing.article-detail', $related->slug) }}">
                                            {{ $related->name }}
                                        </a>
                                    </h4>
                                </div>
                                <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-[11px] text-gray-400">
                                    <span>{{ $related->reading_time }} min read</span>
                                    <span class="font-bold text-[#DF5E1D]">Baca &rarr;</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
