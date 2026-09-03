<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [
            [
                'name' => 'Memahami Perbedaan Arsitektur Prosesor Laptop: Panduan Lengkap 2026',
                'slug' => 'memahami-perbedaan-arsitektur-prosesor-laptop-panduan-lengkap-2026',
                'category' => 'Panduan',
                'author' => 'Tim Teknis ZLM',
                'date' => now()->subDays(1),
                'thumbnail' => 'https://images.unsplash.com/photo-1555680202-c86f0e12f086?auto=format&fit=crop&q=80&w=1200',
                'excerpt' => 'Panduan menyeluruh memilih prosesor Intel, AMD Ryzen, dan Apple Silicon sesuai kebutuhan coding, editing video, dan gaming.',
                'description' => '<h3>Memilih Prosesor yang Tepat Sesuai Beban Kerja</h3><p>Dalam memilih laptop modern, prosesor (CPU) adalah otak utama yang menentukan responsivitas dan daya tahan baterai perangkat Anda. Di era komputasi saat ini, pertimbangan tidak lagi sekadar berapa GHz clock speed-nya, melainkan bagaimana efisiensi arsitektur dan core yang disematkan.</p><p>Untuk kebutuhan komputasi harian dan perkantoran, prosesor 6-core hemat daya sudah sangat mencukupi. Namun bagi profesional di bidang video editing dan software engineering, CPU dengan arsitektur hybrid (Performance core dan Efficiency core) memberikan efisiensi daya luar biasa saat dibawa bepergian.</p>',
                'is_published' => true,
                'views_count' => 142,
                'meta_title' => 'Panduan Memilih Prosesor Laptop Terbaik 2026 — ZLM.ID',
                'meta_description' => 'Pelajari perbedaan prosesor laptop Intel Core, AMD Ryzen, dan Apple Silicon. Panduan lengkap memilih CPU laptop sesuai kebutuhan dan budget Anda.',
                'meta_keywords' => 'prosesor laptop, panduan cpu, intel vs ryzen, apple silicon, beli laptop zlm',
            ],
            [
                'name' => 'Optimasi Laptop Workstation Mobile Anda Agar Selalu Ngebut',
                'slug' => 'optimasi-laptop-workstation-mobile-anda-agar-selalu-ngebut',
                'category' => 'Tips & Trik',
                'author' => 'Hendri Wijaya',
                'date' => now()->subDays(2),
                'thumbnail' => 'https://images.unsplash.com/photo-1587202372775-e229f172b9d7?auto=format&fit=crop&q=80&w=1200',
                'excerpt' => 'Konfigurasi software esensial dan rutinitas maintenance untuk mencegah penurunan performa drastis dari waktu ke waktu.',
                'description' => '<h3>Tips Menjaga Suhu dan Performa Workstation Tetap Maksimal</h3><p>Laptop workstation seperti seri ThinkPad P atau Dell Precision dirancang untuk beban kerja render 3D dan kalkulasi data berat. Namun seiring waktu, debu pada heatsink dan background process Windows dapat menurunkan performa clock speed.</p><p>Lakukan pembersihan ventilasi secara berkala, ganti thermal paste berkualitas setiap 1-2 tahun, dan pastikan mode performa grafis diskrit aktif saat terhubung ke charger AC.</p>',
                'is_published' => true,
                'views_count' => 310,
                'meta_title' => 'Tips Optimasi Laptop Workstation Agar Tetap Kencang — ZLM.ID',
                'meta_description' => 'Panduan konfigurasi software dan perawatan hardware laptop workstation mobile agar performa rendering dan editing tetap kencang.',
                'meta_keywords' => 'optimasi workstation, laptop rendering, thermal paste, tips laptop',
            ],
            [
                'name' => 'OLED vs Mini-LED: Dilema Profesional Konten Kreator Modern',
                'slug' => 'oled-vs-mini-led-dilema-profesional-konten-kreator-modern',
                'category' => 'Review',
                'author' => 'Siti Rahmawati',
                'date' => now()->subDays(3),
                'thumbnail' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&q=80&w=1200',
                'excerpt' => 'Analisis obyektif tentang akurasi warna gamut DCI-P3 dan mitigasi risiko burn-in untuk fotografer profesional dan video editor.',
                'description' => '<h3>Perbandingan Kualitas Layar untuk Pekerjaan Kreatif</h3><p>Panel layar laptop kini semakin memanjakan mata dengan kehadiran teknologi OLED dan Mini-LED. Bagi desainer grafis dan colorist video, kepekatan warna hitam dan akurasi 100% DCI-P3 adalah syarat mutlak.</p><p>OLED unggul pada kontras rasio per pixel, sementara Mini-LED menawarkan peak brightness lebih tinggi untuk pekerjaan outdoor tanpa khawatir risiko burn-in statis.</p>',
                'is_published' => true,
                'views_count' => 425,
                'meta_title' => 'OLED vs Mini-LED: Mana Layar Laptop Terbaik untuk Kreator? — ZLM.ID',
                'meta_description' => 'Bandingkan kelebihan dan kekurangan panel layar laptop OLED vs Mini-LED untuk kebutuhan editing foto, video, dan desain grafis.',
                'meta_keywords' => 'layar oled vs mini led, laptop desainer, monitor akurat, review layar',
            ],
            [
                'name' => 'Review Lenovo ThinkPad T480: Masihkah Menjadi Raja Laptop Kerja Bekas?',
                'slug' => 'review-lenovo-thinkpad-t480-masihkah-menjadi-raja-laptop-kerja-bekas',
                'category' => 'Review',
                'author' => 'Ahmad Fauzi',
                'date' => now()->subDays(5),
                'thumbnail' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&q=80&w=1200',
                'excerpt' => 'Ulasan mendalam durabilitas, kemudahan upgrade RAM & SSD, serta kenyamanan keyboard legendaris ThinkPad T480 di tahun ini.',
                'description' => '<h3>Legenda Laptop Bisnis yang Tak Lekang Waktu</h3><p>Lenovo ThinkPad T480 tetap menjadi salah satu pilihan laptop second paling diburu di katalog ZLM.ID. Bukan tanpa alasan, laptop ini memiliki kombinasi port lengkap (termasuk Thunderbolt 3), keyboard berprofil empuk khas seri T, serta slot RAM dan storage yang mudah di-upgrade.</p><p>Setelah melalui proses Quality Control (QC) 21 titik uji di ZLM.ID, unit yang kami sediakan memiliki kesehatan baterai di atas 85% dan siap pakai untuk kebutuhan coding, skripsi, maupun operasional kantor.</p>',
                'is_published' => true,
                'views_count' => 289,
                'meta_title' => 'Review Lengkap Lenovo ThinkPad T480 Bekas Bergaransi — ZLM.ID',
                'meta_description' => 'Review Lenovo ThinkPad T480: keyboard terbaik, baterai ganda, dan kemudahan upgrade. Simak ulasan performa dan kondisi unit bergaransi dari ZLM.ID.',
                'meta_keywords' => 'review thinkpad t480, laptop second murah, thinkpad bekas jogja, laptop coding',
            ],
            [
                'name' => '5 Tips Merawat Baterai Laptop Agar Awet dan Tidak Cepat Kembung',
                'slug' => '5-tips-merawat-baterai-laptop-agar-awet-dan-tidak-cepat-kembung',
                'category' => 'Tips & Trik',
                'author' => 'Reza Pratama',
                'date' => now()->subDays(9),
                'thumbnail' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&q=80&w=1200',
                'excerpt' => 'Ketahui batas charging optimal, pengaruh suhu kerja, dan pengaturan power threshold untuk memperpanjang usia baterai laptop Anda.',
                'description' => '<h3>Kebiasaan Sepele yang Merusak Baterai Laptop</h3><p>Baterai lithium-ion pada laptop modern memiliki batasan siklus charge (biasanya 300-500 cycle count). Untuk menjaga agar kesehatan baterai (battery health) tetap prima selama bertahun-tahun, hindari membiarkan baterai drop di bawah 20% sebelum dicolok ke charger.</p><p>Gunakan fitur Battery Charge Threshold jika laptop Anda mendukungnya (misal Lenovo Vantage atau ASUS Battery Health Charging) yang membatasi pengisian maksimal di 80% saat laptop sering terhubung ke colokan listrik terus menerus.</p>',
                'is_published' => true,
                'views_count' => 376,
                'meta_title' => '5 Cara Merawat Baterai Laptop Agar Awet Bertahun-tahun — ZLM.ID',
                'meta_description' => 'Tips ampuh menjaga battery health laptop tetap awet. Hindari baterai cepat drop dan kembung dengan langkah mudah berikut.',
                'meta_keywords' => 'tips baterai laptop, rawat baterai, battery health laptop, servis laptop',
            ],
            [
                'name' => 'Standar Uji QC ZLM.ID: Mengapa Laptop Bekas Kami Dijamin Bebas Minus Kritis',
                'slug' => 'standar-uji-qc-zlm-id-mengapa-laptop-bekas-kami-dijamin-bebas-minus-kritis',
                'category' => 'Berita',
                'author' => 'Tim QC ZLM.ID',
                'date' => now()->subDays(14),
                'thumbnail' => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&q=80&w=1200',
                'excerpt' => 'Transparansi proses inspeksi barang masuk dari supplier hingga lolos uji fungsi layar, keyboard, suhu stres CPU, dan garansi penggantian unit.',
                'description' => '<h3>Komitmen Transparansi Kondisi Unit di ZLM.ID</h3><p>Bagi pembeli laptop bekas, kekhawatiran terbesar adalah kondisi tersembunyi yang baru bermasalah setelah beberapa minggu pemakaian. Di ZLM.ID, setiap unit yang datang dari supplier wajib melewati tahapan Quality Control (QC) ketat sebelum dipajang di katalog.</p><p>Pemeriksaan mencakup stress test CPU & GPU selama 30 menit, pengecekan keyboard per tombol, pengujian dead-pixel dan white spot layar, serta uji ketahanan baterai nyata. Hasil QC kami laporkan secara terbuka di deskripsi produk.</p>',
                'is_published' => true,
                'views_count' => 512,
                'meta_title' => 'Mengenal Standar Quality Control Laptop di ZLM.ID',
                'meta_description' => 'Pelajari bagaimana ZLM.ID menguji setiap laptop bekas dengan inspeksi 21 titik uji agar pembeli mendapatkan unit prima tanpa kendala tersembunyi.',
                'meta_keywords' => 'qc zlm id, garansi laptop bekas, beli laptop aman, laptop lolos qc',
            ],
        ];

        foreach ($articles as $data) {
            Article::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
