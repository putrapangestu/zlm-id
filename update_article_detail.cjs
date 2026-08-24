const fs = require('fs');

const filePath = 'c:/wira/projek/web/zlm-id/resources/views/landing/article-detail.blade.php';
let content = fs.readFileSync(filePath, 'utf8');

const startMarker = '    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">';
const endMarker = '    </div>\n</div>\n@endsection';

const startIndex = content.indexOf(startMarker);
const endIndex = content.indexOf(endMarker);

if (startIndex !== -1 && endIndex !== -1) {
    const newSection = `    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Breadcrumbs -->
        <nav class="mb-12 max-w-3xl mx-auto">
            <ol class="flex items-center justify-center gap-2 text-sm text-gray-500 font-medium">
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

        <!-- Article Header -->
        <header class="mb-10 text-center max-w-4xl mx-auto">
            <!-- Category & Date -->
            <div class="flex items-center justify-center gap-3 mb-6">
                <span class="bg-[#DF5E1D]/10 text-[#DF5E1D] border border-[#DF5E1D]/20 px-3.5 py-1.5 rounded-lg text-xs font-bold tracking-widest uppercase shadow-sm">Panduan</span>
                <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                <span class="text-sm font-medium text-gray-500">15 Apr 2026</span>
            </div>

            <!-- Title -->
            <h1 class="text-3xl md:text-5xl lg:text-[3.5rem] font-bold tracking-tight text-[#363230] mb-8 leading-[1.15]">
                Memahami Perbedaan ARM vs x86 untuk Workload Modern
            </h1>

            <!-- Meta & Author -->
            <div class="flex items-center justify-center gap-4 text-sm text-gray-500 mb-2">
                <div class="flex items-center gap-2.5">
                    <img src="https://ui-avatars.com/api/?name=Admin+ZLM&background=F3F4F6&color=363230" alt="Admin ZLM" class="w-9 h-9 rounded-full border border-gray-200">
                    <span>Ditulis oleh <span class="font-bold text-[#363230]">Admin ZLM</span></span>
                </div>
                <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                <div class="flex items-center gap-1.5 font-medium">
                    <iconify-icon icon="solar:clock-circle-linear" class="text-lg"></iconify-icon>
                    8 min read
                </div>
            </div>
        </header>

        <!-- Hero Image (Wide) -->
        <div class="max-w-5xl mx-auto mb-16">
            <figure class="rounded-[2rem] overflow-hidden bg-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-gray-200/60 relative group w-full">
                <img src="https://images.unsplash.com/photo-1555680202-c86f0e12f086?auto=format&fit=crop&q=80&w=1600" alt="Article Cover" class="w-full h-[40vh] md:h-[60vh] object-cover group-hover:scale-[1.02] transition-transform duration-1000 ease-out">
            </figure>
        </div>

        <!-- Main Content Area -->
        <div class="max-w-[700px] mx-auto">
            <!-- Drop Cap Styling -->
            <style>
                .prose-editorial > p:first-of-type::first-letter {
                    float: left;
                    font-size: 5.5rem;
                    line-height: 0.8;
                    padding-right: 0.75rem;
                    padding-top: 0.5rem;
                    font-weight: 800;
                    color: #DF5E1D;
                    font-family: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
                }
            </style>

            <article class="prose prose-lg md:prose-xl prose-gray max-w-none text-gray-700 prose-editorial prose-headings:text-[#363230] prose-headings:font-bold prose-h3:text-2xl prose-a:text-[#DF5E1D] hover:prose-a:text-[#c45218] prose-img:rounded-[2rem] prose-img:border prose-img:border-gray-200/60 prose-img:shadow-md leading-relaxed mb-16">
                <p class="text-xl md:text-2xl text-gray-500 font-medium mb-10 leading-relaxed">
                    Arsitektur prosesor sedang mengalami perubahan besar. Dengan semakin banyaknya laptop premium beralih dari x86 ke ARM, bagaimana dampaknya bagi workflow Anda sehari-hari?
                </p>
                
                <p>
                    Selama dekade terakhir, prosesor x86 (seperti Intel dan AMD) mendominasi pasar PC. Namun, terobosan dari berbagai vendor silicon akhir-akhir ini telah memperkenalkan arsitektur ARM (seperti seri Apple M dan Snapdragon X Elite) ke kelas workstation mobile. Kombinasi performa komputasi tinggi dan efisiensi baterai yang ekstrem membuatnya menjadi sorotan utama.
                </p>

                <h3 class="mt-12 mb-6">Apa Itu Arsitektur x86?</h3>
                <p>
                    x86 adalah arsitektur instruksi kompleks (CISC) yang dirancang untuk menangani tugas rumit dalam sedikit instruksi. Hampir seluruh aplikasi Windows dan game PC dibangun di atas x86. Kekuatannya terletak pada kompabilitas mundur (backward compatibility) dan raw power. Sayangnya, desain ini sering kali membutuhkan daya lebih besar dan menghasilkan temperatur tinggi (thermal throttling).
                </p>

                <div class="bg-gray-50/80 border-l-4 border-[#DF5E1D] p-6 rounded-r-2xl my-10 shadow-sm">
                    <p class="m-0 text-base md:text-lg text-gray-700 font-medium">
                        <strong class="text-[#363230] block mb-2">Catatan Penting:</strong>
                        Jika Anda menggunakan software legacy berumur puluhan tahun atau game kompetitif tertentu, arsitektur x86 masih menjadi standar yang paling aman untuk digunakan.
                    </p>
                </div>

                <h3 class="mt-12 mb-6">Keunggulan Arsitektur ARM</h3>
                <p>
                    ARM menggunakan desain Reduced Instruction Set Computing (RISC). Desain ini mengeksekusi instruksi yang lebih sederhana namun jauh lebih cepat. Awalnya didesain untuk smartphone karena butuh daya baterai super hemat, kini instruksinya cukup solid untuk me-render video 4K atau memproses model AI lokal.
                </p>
                
                <ul class="list-disc pl-6 space-y-4 my-8 marker:text-[#DF5E1D]">
                    <li><strong class="text-[#363230]">Daya Tahan Baterai Maksimal:</strong> Laptop berbasis ARM umumnya menawarkan daya tahan baterai 15-20 jam.</li>
                    <li><strong class="text-[#363230]">Thermal Rendah:</strong> Anda hampir tidak akan pernah mendengar kipas berputar kencang, bahkan pada desain tanpa kipas sama sekali.</li>
                    <li><strong class="text-[#363230]">Integrasi NPU:</strong> Sangat responsif dalam pemrosesan kecerdasan buatan dan neural engine tasks.</li>
                </ul>

                <h3 class="mt-12 mb-6">Kesimpulan: Mana yang Cocok?</h3>
                <p>
                    Bagi para programmer web, video editor, dan profesional bisnis yang mobilitasnya tinggi, ARM adalah masa depan. Laptopnya akan selalu dingin dan siap dibawa seharian penuh. Namun, jika Anda adalah seorang arsitek 3D, pemain game PC hardcore, atau menggunakan software akuntansi spesifik, pilihan laptop x86 akan menyelamatkan Anda dari mimpi buruk kompabilitas.
                </p>
            </article>
            
            <!-- Tags & Share (Bottom) -->
            <div class="pt-10 border-t border-gray-200/60 flex flex-col sm:flex-row items-center justify-between gap-6">
                <div class="flex flex-wrap gap-2.5 justify-center sm:justify-start">
                    <span class="bg-gray-100/80 text-gray-600 px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-gray-200 transition-colors cursor-pointer">#Processor</span>
                    <span class="bg-gray-100/80 text-gray-600 px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-gray-200 transition-colors cursor-pointer">#Review</span>
                    <span class="bg-gray-100/80 text-gray-600 px-3.5 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider hover:bg-gray-200 transition-colors cursor-pointer">#Teknologi</span>
                </div>

                <div class="flex items-center gap-4">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400">Bagikan:</span>
                    <button class="w-10 h-10 rounded-full bg-white border-2 border-gray-100 shadow-sm flex items-center justify-center text-gray-500 hover:text-[#DF5E1D] hover:border-[#DF5E1D]/30 hover:bg-[#DF5E1D]/5 transition-all">
                        <iconify-icon icon="solar:rounded-link-linear" class="text-xl"></iconify-icon>
                    </button>
                    <button class="w-10 h-10 rounded-full bg-white border-2 border-gray-100 shadow-sm flex items-center justify-center text-gray-500 hover:text-[#1DA1F2] hover:border-[#1DA1F2]/30 hover:bg-[#1DA1F2]/5 transition-all">
                        <iconify-icon icon="formkit:twitter" class="text-sm"></iconify-icon>
                    </button>
                </div>
            </div>
        </div>

        <!-- Related Content -->
        <div class="max-w-4xl mx-auto mt-24 pt-16 border-t border-gray-200/60">
            <div class="flex items-center justify-between mb-10">
                <h3 class="text-3xl font-bold tracking-tight text-[#363230]">Baca Juga</h3>
                <a href="{{ route('landing.articles') }}" class="text-sm font-bold text-[#DF5E1D] hover:text-[#c45218] flex items-center gap-1.5 group transition bg-[#DF5E1D]/5 px-4 py-2 rounded-lg">
                    Lihat semua
                    <iconify-icon icon="solar:arrow-right-linear" class="group-hover:translate-x-1 transition-transform"></iconify-icon>
                </a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-10">
                <!-- Article Card (Borderless) -->
                <a href="#" class="block bg-transparent group cursor-pointer">
                    <div class="relative h-56 rounded-3xl bg-gray-100 overflow-hidden mb-5">
                        <img src="https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&q=80&w=600" alt="Thumbnail" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md text-[#3b82f6] px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest shadow-sm">Tips</div>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-[#363230] group-hover:text-[#DF5E1D] transition-colors line-clamp-2 mb-3 leading-snug">
                            Optimasi Laptop Workstation Mobile Anda
                        </h4>
                        <div class="text-xs text-gray-500 font-medium flex items-center gap-2">
                            <iconify-icon icon="solar:clock-circle-linear" class="text-gray-400 text-sm"></iconify-icon>
                            6 min read
                        </div>
                    </div>
                </a>

                <!-- Article Card (Borderless) -->
                <a href="#" class="block bg-transparent group cursor-pointer">
                    <div class="relative h-56 rounded-3xl bg-gray-100 overflow-hidden mb-5">
                        <img src="https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?auto=format&fit=crop&q=80&w=600" alt="Thumbnail" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md text-[#8b5cf6] px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest shadow-sm">Review</div>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-[#363230] group-hover:text-[#DF5E1D] transition-colors line-clamp-2 mb-3 leading-snug">
                            OLED vs Mini-LED: Dilema Profesional Konten
                        </h4>
                        <div class="text-xs text-gray-500 font-medium flex items-center gap-2">
                            <iconify-icon icon="solar:clock-circle-linear" class="text-gray-400 text-sm"></iconify-icon>
                            10 min read
                        </div>
                    </div>
                </a>
            </div>
        </div>

`;
    
    content = content.substring(0, startIndex) + newSection + content.substring(endIndex);
    fs.writeFileSync(filePath, content, 'utf8');
    console.log('Successfully updated the article detail layout.');
} else {
    console.log('Could not find markers.', {startIndex, endIndex});
}
