@extends('layouts.landing')

@section('title', 'Detail Artikel - ZLM.ID')

@section('content')
<div class="bg-gray-50 min-h-screen py-12 lg:py-20 relative">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Breadcrumbs -->
        <nav class="mb-10 lg:mb-12">
            <ol class="flex items-center gap-2 text-sm text-gray-500 font-medium">
                <li>
                    <a href="{{ route('landing.home') }}" class="hover:text-[#DF5E1D] transition-colors duration-200 flex items-center gap-1.5 rounded-md">
                        <iconify-icon icon="solar:home-2-linear" class="text-base" style="stroke-width: 1.5;"></iconify-icon>
                        Beranda
                    </a>
                </li>
                <li class="flex items-center text-gray-300">
                    <iconify-icon icon="solar:alt-arrow-right-linear" style="stroke-width: 1.5;"></iconify-icon>
                </li>
                <li>
                    <a href="{{ route('landing.articles') }}" class="hover:text-[#DF5E1D] transition-colors duration-200 rounded-md">Pusat Pengetahuan</a>
                </li>
                <li class="flex items-center text-gray-300">
                    <iconify-icon icon="solar:alt-arrow-right-linear" style="stroke-width: 1.5;"></iconify-icon>
                </li>
                <li class="text-[#363230] truncate">Detail Artikel</li>
            </ol>
        </nav>

        <!-- Article Container -->
        <article class="bg-white rounded-3xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            <!-- Hero Image -->
            <div class="relative h-64 md:h-96 w-full bg-gray-100 overflow-hidden">
                <img src="https://images.unsplash.com/photo-1555680202-c86f0e12f086?auto=format&fit=crop&q=80&w=1200" alt="Article Cover" class="w-full h-full object-cover">
                <!-- Overlay Gradient -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                
                <!-- Category Badge in Hero -->
                <div class="absolute bottom-6 left-6 md:bottom-8 md:left-10 bg-[#DF5E1D] text-white px-3.5 py-1.5 rounded-lg text-xs font-semibold shadow-lg uppercase tracking-wider inline-flex items-center gap-1.5">
                    <iconify-icon icon="solar:tag-linear"></iconify-icon>
                    Panduan
                </div>
            </div>

            <!-- Content Area -->
            <div class="p-6 md:p-10 lg:p-12">
                <!-- Meta Info -->
                <div class="flex flex-wrap items-center gap-4 text-xs md:text-sm text-gray-500 mb-6 pb-6 border-b border-gray-100">
                    <div class="flex items-center gap-1.5">
                        <iconify-icon icon="solar:calendar-date-linear" class="text-lg"></iconify-icon>
                        15 Apr 2026
                    </div>
                    <div class="w-1 h-1 rounded-full bg-gray-300"></div>
                    <div class="flex items-center gap-1.5">
                        <iconify-icon icon="solar:clock-circle-linear" class="text-lg"></iconify-icon>
                        8 min read
                    </div>
                    <div class="w-1 h-1 rounded-full bg-gray-300"></div>
                    <div class="flex items-center gap-1.5 bg-gray-50 px-2 py-1 rounded-md border border-gray-100">
                        <iconify-icon icon="solar:user-linear" class="text-lg text-gray-400"></iconify-icon>
                        Ditulis oleh <span class="font-medium text-[#363230]">Admin ZLM</span>
                    </div>
                </div>

                <!-- Title -->
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-semibold tracking-tight text-[#363230] mb-8 leading-tight">
                    Memahami Perbedaan ARM vs x86 untuk Workload Modern
                </h1>

                <!-- Body Content (Prose style) -->
                <div class="prose prose-gray max-w-none text-gray-600 prose-headings:text-[#363230] prose-headings:font-semibold prose-a:text-[#DF5E1D] hover:prose-a:text-[#c45218] prose-img:rounded-2xl prose-img:border prose-img:border-gray-200/60 prose-img:shadow-sm text-base md:text-lg leading-relaxed">
                    <p class="text-xl text-gray-500 font-medium mb-8 leading-relaxed">
                        Arsitektur prosesor sedang mengalami perubahan besar. Dengan semakin banyaknya laptop premium beralih dari x86 ke ARM, bagaimana dampaknya bagi workflow Anda sehari-hari?
                    </p>
                    
                    <p>
                        Selama dekade terakhir, prosesor x86 (seperti Intel dan AMD) mendominasi pasar PC. Namun, terobosan dari berbagai vendor silicon akhir-akhir ini telah memperkenalkan arsitektur ARM (seperti seri Apple M dan Snapdragon X Elite) ke kelas workstation mobile. Kombinasi performa komputasi tinggi dan efisiensi baterai yang ekstrem membuatnya menjadi sorotan utama.
                    </p>

                    <h3 class="text-2xl font-bold text-[#363230] mt-8 mb-4">Apa Itu Arsitektur x86?</h3>
                    <p>
                        x86 adalah arsitektur instruksi kompleks (CISC) yang dirancang untuk menangani tugas rumit dalam sedikit instruksi. Hampir seluruh aplikasi Windows dan game PC dibangun di atas x86. Kekuatannya terletak pada kompabilitas mundur (backward compatibility) dan raw power. Sayangnya, desain ini sering kali membutuhkan daya lebih besar dan menghasilkan temperatur tinggi (thermal throttling).
                    </p>

                    <div class="bg-[#DF5E1D]/5 border-l-4 border-[#DF5E1D] p-5 rounded-r-xl my-8">
                        <p class="m-0 text-sm md:text-base text-gray-700 font-medium">
                            <strong class="text-[#363230] block mb-1">Catatan Penting:</strong>
                            Jika Anda menggunakan software legacy berumur puluhan tahun atau game kompetitif tertentu, arsitektur x86 masih menjadi standar yang paling aman untuk digunakan.
                        </p>
                    </div>

                    <h3 class="text-2xl font-bold text-[#363230] mt-8 mb-4">Keunggulan Arsitektur ARM</h3>
                    <p>
                        ARM menggunakan desain Reduced Instruction Set Computing (RISC). Desain ini mengeksekusi instruksi yang lebih sederhana namun jauh lebih cepat. Awalnya didesain untuk smartphone karena butuh daya baterai super hemat, kini instruksinya cukup solid untuk me-render video 4K atau memproses model AI lokal.
                    </p>
                    
                    <ul class="list-disc pl-6 space-y-3 mb-6">
                        <li><strong class="text-[#363230]">Daya Tahan Baterai Maksimal:</strong> Laptop berbasis ARM umumnya menawarkan daya tahan baterai 15-20 jam.</li>
                        <li><strong class="text-[#363230]">Thermal Rendah:</strong> Anda hampir tidak akan pernah mendengar kipas berputar kencang, bahkan pada desain tanpa kipas sama sekali.</li>
                        <li><strong class="text-[#363230]">Integrasi NPU:</strong> Sangat responsif dalam pemrosesan kecerdasan buatan dan neural engine tasks.</li>
                    </ul>

                    <h3 class="text-2xl font-bold text-[#363230] mt-8 mb-4">Kesimpulan: Mana yang Cocok?</h3>
                    <p>
                        Bagi para programmer web, video editor, dan profesional bisnis yang mobilitasnya tinggi, ARM adalah masa depan. Laptopnya akan selalu dingin dan siap dibawa seharian penuh. Namun, jika Anda adalah seorang arsitek 3D, pemain game PC hardcore, atau menggunakan software akuntansi spesifik, pilihan laptop x86 akan menyelamatkan Anda dari mimpi buruk kompabilitas.
                    </p>
                </div>
                
                <!-- Share Area -->
                <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex flex-wrap gap-2">
                        <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-medium hover:bg-gray-200 transition-colors cursor-pointer">#Processor</span>
                        <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-medium hover:bg-gray-200 transition-colors cursor-pointer">#Review</span>
                        <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-medium hover:bg-gray-200 transition-colors cursor-pointer">#Teknologi</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-gray-500">Bagikan:</span>
                        <button class="w-9 h-9 rounded-full bg-white border border-gray-200 shadow-sm flex items-center justify-center text-gray-500 hover:text-[#DF5E1D] hover:border-[#DF5E1D]/30 hover:bg-[#DF5E1D]/5 transition-all">
                            <iconify-icon icon="solar:rounded-link-linear" class="text-lg"></iconify-icon>
                        </button>
                    </div>
                </div>
            </div>
        </article>

        <!-- Related Content -->
        <div class="mt-16">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-semibold tracking-tight text-[#363230]">Baca Juga</h3>
                <a href="{{ route('landing.articles') }}" class="text-sm font-medium text-[#DF5E1D] hover:text-[#c45218] flex items-center gap-1 group transition">
                    Lihat semua artikel
                    <iconify-icon icon="solar:arrow-right-linear" class="group-hover:translate-x-1 transition-transform"></iconify-icon>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Article Card (Mini) -->
                <a href="#" class="bg-white rounded-xl border border-gray-200/60 p-4 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group flex gap-4 items-center">
                    <div class="w-24 h-24 sm:w-32 sm:h-32 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                        <img src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&q=80&w=400" alt="Thumbnail" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div>
                        <div class="text-[10px] uppercase tracking-wider text-[#DF5E1D] font-semibold mb-1 bg-[#DF5E1D]/10 inline-block px-2 py-0.5 rounded">Tips</div>
                        <h4 class="text-sm sm:text-base font-semibold text-[#363230] group-hover:text-[#DF5E1D] transition-colors line-clamp-2 mb-2">
                            Optimasi Laptop Workstation Mobile Anda
                        </h4>
                        <div class="text-xs text-gray-500 flex items-center gap-1.5">
                            <iconify-icon icon="solar:clock-circle-linear" class="text-gray-400"></iconify-icon>
                            6 min read
                        </div>
                    </div>
                </a>

                <!-- Article Card (Mini) -->
                <a href="#" class="bg-white rounded-xl border border-gray-200/60 p-4 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group flex gap-4 items-center">
                    <div class="w-24 h-24 sm:w-32 sm:h-32 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                        <img src="https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?auto=format&fit=crop&q=80&w=400" alt="Thumbnail" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div>
                        <div class="text-[10px] uppercase tracking-wider text-purple-600 font-semibold mb-1 bg-purple-100 inline-block px-2 py-0.5 rounded">Review</div>
                        <h4 class="text-sm sm:text-base font-semibold text-[#363230] group-hover:text-purple-600 transition-colors line-clamp-2 mb-2">
                            OLED vs Mini-LED: Dilema Profesional Konten
                        </h4>
                        <div class="text-xs text-gray-500 flex items-center gap-1.5">
                            <iconify-icon icon="solar:clock-circle-linear" class="text-gray-400"></iconify-icon>
                            10 min read
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
