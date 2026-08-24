const fs = require('fs');

const filePath = 'c:/wira/projek/web/zlm-id/resources/views/landing/articles.blade.php';
let content = fs.readFileSync(filePath, 'utf8');

const startMarker = '        <!-- Search & Filter -->';
const endMarker = '        <!-- Load More Button -->';

const startIndex = content.indexOf(startMarker);
const endIndex = content.indexOf(endMarker);

if (startIndex !== -1 && endIndex !== -1) {
    const newSection = `        <style>
            /* Hide scrollbar for category pills */
            .hide-scrollbar::-webkit-scrollbar {
                display: none;
            }
        </style>

        <!-- Search & Filter -->
        <div class="mb-10 lg:mb-12">
            <div class="flex flex-col lg:flex-row lg:items-center gap-6 justify-between">
                <!-- Pills Categories -->
                <div class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0 hide-scrollbar" style="-ms-overflow-style: none; scrollbar-width: none;">
                    <button class="px-5 py-2.5 rounded-full bg-[#363230] text-white text-sm font-semibold shrink-0 shadow-md">Semua</button>
                    <button class="px-5 py-2.5 rounded-full bg-white border border-gray-200 text-gray-600 text-sm font-medium hover:border-[#DF5E1D] hover:text-[#DF5E1D] transition-colors shrink-0">Panduan</button>
                    <button class="px-5 py-2.5 rounded-full bg-white border border-gray-200 text-gray-600 text-sm font-medium hover:border-[#DF5E1D] hover:text-[#DF5E1D] transition-colors shrink-0">Review</button>
                    <button class="px-5 py-2.5 rounded-full bg-white border border-gray-200 text-gray-600 text-sm font-medium hover:border-[#DF5E1D] hover:text-[#DF5E1D] transition-colors shrink-0">Tips & Trik</button>
                    <button class="px-5 py-2.5 rounded-full bg-white border border-gray-200 text-gray-600 text-sm font-medium hover:border-[#DF5E1D] hover:text-[#DF5E1D] transition-colors shrink-0">Berita</button>
                </div>
                <!-- Search -->
                <div class="relative w-full lg:w-72 shrink-0">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <iconify-icon icon="solar:magnifer-linear" class="text-lg"></iconify-icon>
                    </div>
                    <input type="text" placeholder="Cari artikel..." class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] shadow-sm transition-all">
                </div>
            </div>
        </div>

        <!-- Headline Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-16 lg:mb-20">
            
            <!-- Main Featured Article (Left, Col-span-8) -->
            <a href="{{ route('landing.article-detail', 1) }}" class="lg:col-span-8 block group cursor-pointer relative rounded-[2rem] overflow-hidden shadow-md hover:shadow-xl transition-all duration-500 h-[400px] lg:h-[500px]">
                <div class="absolute inset-0 bg-gray-900">
                    <img src="https://images.unsplash.com/photo-1555680202-c86f0e12f086?auto=format&fit=crop&q=80&w=1200" alt="CPU Architecture" class="w-full h-full object-cover opacity-70 group-hover:scale-105 group-hover:opacity-80 transition-all duration-700">
                </div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                <div class="absolute inset-0 flex flex-col justify-end p-6 sm:p-8 lg:p-12">
                    <div class="flex items-center gap-3 mb-4 lg:mb-5">
                        <span class="bg-[#DF5E1D] text-white px-3.5 py-1.5 rounded-lg text-xs font-bold tracking-wider uppercase">Panduan Utama</span>
                        <span class="text-sm font-medium text-gray-300">15 Apr 2026 • 8 min read</span>
                    </div>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-3 lg:mb-4 leading-snug group-hover:text-[#DF5E1D] transition-colors">
                        Memahami Perbedaan ARM vs x86 untuk Workload Modern di Tahun 2026
                    </h2>
                    <p class="text-gray-300 text-sm md:text-base line-clamp-2 max-w-3xl leading-relaxed">
                        Perbandingan mendalam tentang arsitektur processor masa depan dan dampaknya terhadap performa software, battery life seharian, dan thermal throttling di laptop premium.
                    </p>
                </div>
            </a>

            <!-- Stacked Secondary Articles (Right, Col-span-4) -->
            <div class="lg:col-span-4 flex flex-col gap-6">
                <!-- Secondary 1 -->
                <a href="{{ route('landing.article-detail', 1) }}" class="flex-1 bg-white rounded-[2rem] p-6 lg:p-8 border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-xl transition-all duration-300 group flex flex-col justify-center">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-[#3b82f6] text-xs font-bold uppercase tracking-wider bg-blue-50 px-2.5 py-1 rounded-md">Tips</span>
                        <span class="text-xs font-medium text-gray-400">12 Apr 2026</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#363230] mb-3 leading-snug group-hover:text-[#DF5E1D] transition-colors line-clamp-3">
                        Optimasi Laptop Workstation Mobile Anda Agar Selalu Ngebut
                    </h3>
                    <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed">
                        Konfigurasi software esensial dan rutinitas maintenance untuk mencegah penurunan performa drastis dari waktu ke waktu.
                    </p>
                </a>
                
                <!-- Secondary 2 -->
                <a href="{{ route('landing.article-detail', 1) }}" class="flex-1 bg-white rounded-[2rem] p-6 lg:p-8 border border-gray-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-xl transition-all duration-300 group flex flex-col justify-center">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-[#8b5cf6] text-xs font-bold uppercase tracking-wider bg-purple-50 px-2.5 py-1 rounded-md">Review</span>
                        <span class="text-xs font-medium text-gray-400">10 Apr 2026</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#363230] mb-3 leading-snug group-hover:text-[#DF5E1D] transition-colors line-clamp-3">
                        OLED vs Mini-LED: Dilema Profesional Konten Kontemporer
                    </h3>
                    <p class="text-sm text-gray-500 line-clamp-2 leading-relaxed">
                        Analisis obyektif tentang akurasi warna dan mitigasi risiko burn-in untuk fotografer profesional dan video editor.
                    </p>
                </a>
            </div>
            
        </div>

        <!-- Latest Articles Grid -->
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-2xl font-bold text-[#363230] flex items-center gap-3">
                <iconify-icon icon="solar:clock-circle-bold-duotone" class="text-[#DF5E1D] text-3xl"></iconify-icon>
                Artikel Terbaru
            </h3>
            <div class="h-[1px] flex-1 bg-gray-200 ml-6 hidden sm:block"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
            <!-- Article 4 -->
            <a href="{{ route('landing.article-detail', 1) }}" class="block bg-transparent group cursor-pointer">
                <div class="relative h-60 rounded-3xl bg-gray-100 overflow-hidden mb-5">
                    <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&q=80&w=600" alt="Gaming" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md text-rose-600 px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest shadow-sm">Gaming</div>
                </div>
                <div>
                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-3 font-medium">
                        <span>08 Apr 2026</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                        <span>7 min read</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#363230] mb-3 leading-snug group-hover:text-[#DF5E1D] transition-colors line-clamp-2">
                        Panduan Memilih Laptop Gaming Terbaik di Tahun 2026 Sesuai Budget Anda
                    </h3>
                    <p class="text-sm text-gray-500 line-clamp-3 leading-relaxed">
                        Panduan komprehensif untuk menyeimbangkan antara performa GPU, tingkat refresh rate layar, dan seberapa panas perangkat saat digunakan maraton.
                    </p>
                </div>
            </a>

            <!-- Article 5 -->
            <a href="{{ route('landing.article-detail', 1) }}" class="block bg-transparent group cursor-pointer">
                <div class="relative h-60 rounded-3xl bg-gray-100 overflow-hidden mb-5">
                    <img src="https://images.unsplash.com/photo-1516321318423-f06140cf6439?auto=format&fit=crop&q=80&w=600" alt="Business" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md text-emerald-600 px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest shadow-sm">Bisnis</div>
                </div>
                <div>
                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-3 font-medium">
                        <span>05 Apr 2026</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                        <span>9 min read</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#363230] mb-3 leading-snug group-hover:text-[#DF5E1D] transition-colors line-clamp-2">
                        5 Laptop Bisnis Kelas Atas untuk Produktivitas Eksekutif Maksimal
                    </h3>
                    <p class="text-sm text-gray-500 line-clamp-3 leading-relaxed">
                        Evaluasi jajaran laptop enterprise dengan menyorot tajam pada keunggulan keamanan hardware-level, ketahanan baterai, dan bobot ultra-ringan.
                    </p>
                </div>
            </a>

            <!-- Article 6 -->
            <a href="{{ route('landing.article-detail', 1) }}" class="block bg-transparent group cursor-pointer">
                <div class="relative h-60 rounded-3xl bg-gray-100 overflow-hidden mb-5">
                    <img src="https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&q=80&w=600" alt="Student Laptop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-md text-amber-600 px-3.5 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest shadow-sm">Pelajar</div>
                </div>
                <div>
                    <div class="flex items-center gap-2 text-xs text-gray-500 mb-3 font-medium">
                        <span>02 Apr 2026</span>
                        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                        <span>5 min read</span>
                    </div>
                    <h3 class="text-xl font-bold text-[#363230] mb-3 leading-snug group-hover:text-[#DF5E1D] transition-colors line-clamp-2">
                        Rekomendasi Laptop Terbaik untuk Mahasiswa dengan Budget Terbatas
                    </h3>
                    <p class="text-sm text-gray-500 line-clamp-3 leading-relaxed">
                        Kami menguji belasan laptop terjangkau yang cukup tangguh untuk keperluan study, coding tingkat dasar, hingga hiburan akhir pekan.
                    </p>
                </div>
            </a>
        </div>\n\n`;
    
    content = content.substring(0, startIndex) + newSection + content.substring(endIndex);
    fs.writeFileSync(filePath, content, 'utf8');
    console.log('Successfully updated the articles layout.');
} else {
    console.log('Could not find markers.', {startIndex, endIndex});
}
