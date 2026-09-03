@extends('layouts.admin')

@section('title', 'Manajemen Artikel — ZLM.ID Admin')
@section('heading', 'Manajemen Artikel & Blog')

@section('content')
<div class="space-y-6">
    <!-- Success Alert -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-3 text-sm animate-slide-up">
            <iconify-icon icon="solar:check-circle-bold" class="text-xl text-emerald-500"></iconify-icon>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Header Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-[#363230]">Daftar Artikel</h2>
            <p class="text-xs text-gray-400 mt-0.5">Kelola panduan belanja, ulasan laptop, dan tips teknologi untuk pelanggan</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.articles.create') }}" class="bg-[#DF5E1D] text-white px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-[#c45218] transition-colors shadow-sm flex items-center gap-2">
                <iconify-icon icon="solar:add-circle-linear" class="text-base"></iconify-icon>
                <span>Tulis Artikel Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <form method="GET" action="{{ route('admin.articles.index') }}" class="bg-white rounded-2xl border border-gray-200/60 p-4 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex flex-col sm:flex-row gap-3 items-center justify-between">
        <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <!-- Search -->
            <div class="relative flex-1 sm:w-72">
                <iconify-icon icon="solar:magnifer-linear" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base"></iconify-icon>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau penulis..." class="w-full bg-gray-50 border border-gray-200 text-xs text-[#363230] placeholder-gray-400 rounded-xl py-2 pl-9 pr-4 focus:outline-none focus:border-[#DF5E1D]/40 focus:ring-2 focus:ring-[#DF5E1D]/10 transition-all">
            </div>

            <!-- Category Filter -->
            <select name="category" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-xs text-[#363230] rounded-xl py-2 px-3 focus:outline-none focus:border-[#DF5E1D]/40 transition-all">
                <option value="all">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>

            <!-- Status Filter -->
            <select name="status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-xs text-[#363230] rounded-xl py-2 px-3 focus:outline-none focus:border-[#DF5E1D]/40 transition-all">
                <option value="">Semua Status</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
            </select>
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
            @if(request()->anyFilled(['search', 'category', 'status']))
                <a href="{{ route('admin.articles.index') }}" class="text-xs text-gray-400 hover:text-[#DF5E1D] underline px-2">Reset Filter</a>
            @endif
            <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3.5 py-2 rounded-xl text-xs font-semibold transition-colors">
                Terapkan
            </button>
        </div>
    </form>

    <!-- Articles Table -->
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        @if($articles->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-100">
                            <th class="py-3.5 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Artikel</th>
                            <th class="py-3.5 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kategori</th>
                            <th class="py-3.5 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Penulis</th>
                            <th class="py-3.5 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="py-3.5 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tanggal</th>
                            <th class="py-3.5 px-6 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs">
                        @foreach($articles as $article)
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="py-3.5 px-6">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-14 h-11 rounded-xl bg-gray-100 border border-gray-200/60 overflow-hidden shrink-0">
                                            <img src="{{ $article->thumbnail_url_full }}" alt="" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <a href="{{ route('landing.article-detail', $article->slug) }}" target="_blank" class="font-bold text-[#363230] group-hover:text-[#DF5E1D] transition-colors line-clamp-1">
                                                {{ $article->name }}
                                            </a>
                                            <div class="text-[11px] text-gray-400 mt-0.5 flex items-center gap-2">
                                                <span>/articles/{{ $article->slug }}</span>
                                                <span>&bull;</span>
                                                <span>{{ $article->views_count }} views</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-6">
                                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-orange-50 text-[#DF5E1D] border border-orange-100">
                                        {{ $article->category }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-6 text-gray-600 font-medium">
                                    {{ $article->author }}
                                </td>
                                <td class="py-3.5 px-6">
                                    @if($article->is_published)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-600 border border-emerald-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-medium bg-gray-100 text-gray-500">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-6 text-gray-500">
                                    {{ $article->date ? $article->date->format('d M Y') : '-' }}
                                </td>
                                <td class="py-3.5 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('landing.article-detail', $article->slug) }}" target="_blank" class="p-1.5 text-gray-400 hover:text-[#DF5E1D] hover:bg-orange-50 rounded-lg transition-colors" title="Lihat di Web">
                                            <iconify-icon icon="solar:eye-linear" class="text-base"></iconify-icon>
                                        </a>
                                        <a href="{{ route('admin.articles.edit', $article->id) }}" class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                            <iconify-icon icon="solar:pen-2-linear" class="text-base"></iconify-icon>
                                        </a>
                                        <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                <iconify-icon icon="solar:trash-bin-trash-linear" class="text-base"></iconify-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-gray-100">
                {{ $articles->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <div class="w-14 h-14 rounded-2xl bg-orange-50 text-[#DF5E1D] flex items-center justify-center mx-auto mb-3">
                    <iconify-icon icon="solar:document-text-linear" class="text-3xl"></iconify-icon>
                </div>
                <h3 class="text-base font-bold text-[#363230]">Belum ada artikel</h3>
                <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">Mulai buat artikel, review laptop, atau panduan pertama Anda untuk menarik trafik pembeli.</p>
                <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center gap-2 mt-4 bg-[#DF5E1D] text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-[#c45218] transition-colors shadow-sm">
                    <iconify-icon icon="solar:add-circle-linear" class="text-base"></iconify-icon>
                    Tulis Artikel Sekarang
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
