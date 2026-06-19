<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'tax_rate' => '11',
            'store_name' => 'ZLM.ID',
            'bank_name' => 'BCA',
            'bank_account' => '123-456-7890',
            'bank_holder' => 'PT ZLM ID',
            'store_description' => 'Premium laptop store — engineered excellence for professionals, creators, and gamers.',
            'store_address' => 'Jl. Raya Malang No. 123, Malang, Jawa Timur',
            'store_phone' => '+62 123 4567 8910',
            'store_email' => 'support@zlm.id',
            'store_google_maps' => '',
            'store_whatsapp' => '6212345678910',
            'social_instagram' => 'https://instagram.com/zlm.id',
            'social_facebook' => 'https://facebook.com/zlm.id',
            'social_tiktok' => 'https://tiktok.com/@zlm.id',
            'social_youtube' => 'https://youtube.com/@zlm.id',
            'store_logo' => '',
            'store_opening_hours' => 'Sen - Sab: 09:00 - 18:00',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
