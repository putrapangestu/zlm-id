@extends('layouts.admin')

@section('title', 'Tulis Artikel Baru — ZLM.ID Admin')
@section('heading', 'Tulis Artikel Baru')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<style>
    trix-editor { min-height: 250px; }
    trix-toolbar .trix-button-group { margin-bottom: 0; }
    .trix-button-row { flex-wrap: wrap; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.articles.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-[#DF5E1D] transition-colors">
            <iconify-icon icon="solar:arrow-left-linear" class="text-base"></iconify-icon>
            Kembali ke Daftar Artikel
        </a>
    </div>

    @if (isset($errors) && $errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-xs space-y-1">
            <div class="font-bold">Periksa kembali formulir Anda:</div>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Konten Utama Card -->
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 sm:p-8 space-y-6">
            <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2 pb-3 border-b border-gray-100">
                <iconify-icon icon="solar:document-text-linear" class="text-lg text-[#DF5E1D]"></iconify-icon>
                Informasi Utama Artikel
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Judul Artikel -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-xs font-bold text-[#363230] uppercase tracking-wider mb-2">Judul Artikel <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                        class="w-full bg-gray-50 border border-gray-200 text-sm text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/40 focus:ring-2 focus:ring-[#DF5E1D]/10 transition-all font-medium"
                        placeholder="Contoh: 7 Tips Memilih Laptop Second Berkualitas untuk Mahasiswa">
                </div>

                <!-- Kategori -->
                <div>
                    <label for="category" class="block text-xs font-bold text-[#363230] uppercase tracking-wider mb-2">Kategori <span class="text-red-500">*</span></label>
                    <select id="category" name="category" required
                        class="w-full bg-gray-50 border border-gray-200 text-sm text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/40 focus:ring-2 focus:ring-[#DF5E1D]/10 transition-all">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Penulis -->
                <div>
                    <label for="author" class="block text-xs font-bold text-[#363230] uppercase tracking-wider mb-2">Penulis <span class="text-red-500">*</span></label>
                    <input type="text" id="author" name="author" value="{{ old('author', auth()->user()?->name ?? 'Tim Editorial ZLM') }}" required
                        class="w-full bg-gray-50 border border-gray-200 text-sm text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/40 focus:ring-2 focus:ring-[#DF5E1D]/10 transition-all">
                </div>

                <!-- Tanggal Terbit -->
                <div>
                    <label for="date" class="block text-xs font-bold text-[#363230] uppercase tracking-wider mb-2">Tanggal Terbit <span class="text-red-500">*</span></label>
                    <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required
                        class="w-full bg-gray-50 border border-gray-200 text-sm text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/40 focus:ring-2 focus:ring-[#DF5E1D]/10 transition-all">
                </div>

                <!-- Status Publikasi -->
                <div class="flex items-center pt-6">
                    <label class="relative flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', true) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#DF5E1D]"></div>
                        <span class="text-xs font-bold text-[#363230]">Publikasikan Langsung ke Pengunjung</span>
                    </label>
                </div>

                <!-- Gambar Thumbnail / Cover -->
                <div class="md:col-span-2">
                    <label for="thumbnail" class="block text-xs font-bold text-[#363230] uppercase tracking-wider mb-2">Gambar Sampul (Thumbnail)</label>
                    <input type="file" id="thumbnail" name="thumbnail" accept="image/*"
                        class="w-full bg-gray-50 border border-gray-200 text-xs text-[#363230] rounded-xl py-2.5 px-4 focus:outline-none focus:border-[#DF5E1D]/40 file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-orange-50 file:text-[#DF5E1D] hover:file:bg-orange-100">
                    <p class="text-[11px] text-gray-400 mt-1">Format: JPG, PNG, WEBP. Maks 2MB. Resolusi disarankan: 1200x630 px.</p>
                </div>

                <!-- Excerpt / Ringkasan Singkat -->
                <div class="md:col-span-2">
                    <label for="excerpt" class="block text-xs font-bold text-[#363230] uppercase tracking-wider mb-2">Ringkasan Singkat (Excerpt)</label>
                    <textarea id="excerpt" name="excerpt" rows="2"
                        class="w-full bg-gray-50 border border-gray-200 text-xs text-[#363230] rounded-xl p-3 focus:outline-none focus:border-[#DF5E1D]/40 focus:ring-2 focus:ring-[#DF5E1D]/10 transition-all resize-none"
                        placeholder="Ringkasan 1-2 kalimat yang akan tampil di kartu artikel pada halaman depan...">{{ old('excerpt') }}</textarea>
                </div>

                <!-- Konten Lengkap -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-xs font-bold text-[#363230] uppercase tracking-wider mb-2">Isi Konten Artikel <span class="text-red-500">*</span></label>
                    <input id="description" type="hidden" name="description" value="{{ old('description') }}">
                    <trix-editor input="description" class="bg-white border border-gray-200 rounded-xl text-xs text-[#363230] focus:outline-none focus:border-[#DF5E1D]/40 focus:ring-2 focus:ring-[#DF5E1D]/10"></trix-editor>
                </div>
            </div>
        </div>

        <!-- Pengaturan SEO Accordion / Card -->
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 sm:p-8 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2">
                    <iconify-icon icon="solar:magnifer-linear" class="text-lg text-emerald-600"></iconify-icon>
                    Optimasi Mesin Pencari (SEO)
                </h3>
                <span class="text-[11px] text-gray-400">Opsional — Otomatis terisi jika dikosongkan</span>
            </div>

            <div class="space-y-4">
                <div>
                    <label for="meta_title" class="block text-xs font-semibold text-[#363230] mb-1">Meta Title</label>
                    <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title') }}"
                        class="w-full bg-gray-50 border border-gray-200 text-xs text-[#363230] rounded-xl py-2 px-3 focus:outline-none focus:border-emerald-500 transition-all"
                        placeholder="Judul khusus untuk Google Search (default: Judul Artikel — ZLM.ID)">
                </div>

                <div>
                    <label for="meta_description" class="block text-xs font-semibold text-[#363230] mb-1">Meta Description</label>
                    <textarea id="meta_description" name="meta_description" rows="2"
                        class="w-full bg-gray-50 border border-gray-200 text-xs text-[#363230] rounded-xl p-3 focus:outline-none focus:border-emerald-500 transition-all resize-none"
                        placeholder="Deskripsi ringkas untuk snippet pencarian Google (150-160 karakter disarankan)...">{{ old('meta_description') }}</textarea>
                </div>

                <div>
                    <label for="meta_keywords" class="block text-xs font-semibold text-[#363230] mb-1">Meta Keywords</label>
                    <input type="text" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}"
                        class="w-full bg-gray-50 border border-gray-200 text-xs text-[#363230] rounded-xl py-2 px-3 focus:outline-none focus:border-emerald-500 transition-all"
                        placeholder="laptop bekas berkualitas, tips beli laptop, rekomendasi laptop murah">
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.articles.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-semibold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-[#DF5E1D] hover:bg-[#c45218] transition-colors shadow-sm flex items-center gap-2">
                <iconify-icon icon="solar:check-circle-linear" class="text-base"></iconify-icon>
                Simpan & Terbitkan Artikel
            </button>
        </div>
    </form>
</div>
@endsection
