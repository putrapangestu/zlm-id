@extends('layouts.admin')

@section('title', $article->name . ' — ZLM.ID Admin')
@section('heading', 'Detail Artikel')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Back Button & Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.articles.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-[#DF5E1D] transition-colors">
            <iconify-icon icon="solar:arrow-left-linear" class="text-base"></iconify-icon>
            Kembali ke Daftar Artikel
        </a>
        <div class="flex items-center gap-2">
            <a href="{{ route('landing.article-detail', $article->slug) }}" target="_blank" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 transition-colors flex items-center gap-1.5">
                <iconify-icon icon="solar:eye-linear" class="text-base"></iconify-icon>
                Buka di Web
            </a>
            <a href="{{ route('admin.articles.edit', $article->id) }}" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-[#DF5E1D] hover:bg-[#c45218] transition-colors shadow-sm flex items-center gap-1.5">
                <iconify-icon icon="solar:pen-linear" class="text-base"></iconify-icon>
                Edit Artikel
            </a>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden p-6 sm:p-8 space-y-6">
        <div>
            <div class="flex items-center gap-2.5 mb-3">
                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-orange-50 text-[#DF5E1D] border border-orange-100 uppercase">
                    {{ $article->category }}
                </span>
                @if($article->is_published)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Published
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500">
                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Draft
                    </span>
                @endif
                <span class="text-xs text-gray-400">&bull;</span>
                <span class="text-xs text-gray-400">{{ $article->date ? $article->date->format('d M Y') : '' }}</span>
                <span class="text-xs text-gray-400">&bull;</span>
                <span class="text-xs text-gray-400">Penulis: <strong>{{ $article->author }}</strong></span>
            </div>

            <h1 class="text-xl sm:text-2xl font-bold text-[#363230] leading-snug">
                {{ $article->name }}
            </h1>
            <p class="text-xs font-mono text-gray-400 mt-1">Slug URL: /articles/{{ $article->slug }}</p>
        </div>

        @if($article->thumbnail)
            <div class="aspect-video max-h-80 rounded-xl overflow-hidden bg-gray-100">
                <img src="{{ $article->thumbnail_url_full }}" alt="" class="w-full h-full object-cover">
            </div>
        @endif

        @if($article->excerpt)
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 text-xs text-gray-600 leading-relaxed italic">
                "{{ $article->excerpt }}"
            </div>
        @endif

        <div class="pt-4 border-t border-gray-100">
            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Isi Konten Artikel</h4>
            <div class="prose prose-sm max-w-none text-[#363230] text-xs leading-relaxed space-y-3">
                {!! $article->description !!}
            </div>
        </div>

        @if($article->meta_title || $article->meta_description)
            <div class="pt-4 border-t border-gray-100">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Informasi SEO</h4>
                <div class="text-xs text-gray-600 space-y-1">
                    <p><strong>Meta Title:</strong> {{ $article->seo_meta_title }}</p>
                    <p><strong>Meta Description:</strong> {{ $article->seo_meta_description }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
