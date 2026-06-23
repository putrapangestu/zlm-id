<?php

namespace Database\Seeders;

use App\Models\HeroSlider;
use Illuminate\Database\Seeder;

class HeroSliderSeeder extends Seeder
{
    public function run(): void
    {
        $sliders = [
            [
                'title' => 'Toko Laptop Bekas Berkualitas di Malang',
                'subtitle' => 'New 2026 Models Available',
                'description' => 'ZLM.ID hadir menyediakan berbagai pilihan laptop bekas second berkualitas dengan jaminan harga dan service terbaik di Malang. Temukan perangkat impian yang sesuai dengan kebutuhan Anda!',
                'button_text' => 'Explore Catalog',
                'button_url' => '/search',
                'image' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&q=80&w=1200',
                'sort_order' => 0,
                'is_active' => true,
            ],
            [
                'title' => 'Garansi Servis & Sparepart',
                'subtitle' => 'Beli dengan Aman',
                'description' => 'Setiap unit laptop yang kami jual dilengkapi dengan garansi toko yang jelas. Kami juga menyediakan layanan purna jual dan konsultasi gratis.',
                'button_text' => 'Lihat Koleksi',
                'button_url' => '/search',
                'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&q=80&w=1200',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Smart Search — Cari Laptop Idealmu',
                'subtitle' => 'AI-Powered Recommendation',
                'description' => 'Bingung milih laptop? Gunakan fitur Smart Search kami. Cukup jawab beberapa pertanyaan, kami rekomendasikan laptop yang paling cocok untuk Anda!',
                'button_text' => 'Coba Smart Search',
                'button_url' => '/smart-search',
                'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&q=80&w=1200',
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($sliders as $slider) {
            HeroSlider::firstOrCreate(
                ['title' => $slider['title']],
                $slider
            );
        }

        $this->command->info('Default hero sliders seeded successfully.');
    }
}
