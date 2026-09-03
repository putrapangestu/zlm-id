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
            'store_name' => 'ZLM.ID Laptop Store',
            'bank_name' => 'BCA',
            'bank_account' => '123-456-7890',
            'bank_holder' => 'PT ZLM ID INDONESIA',
            'store_description' => 'Premium laptop store — engineered excellence for professionals, creators, and gamers. Jaminan unit lolos Quality Control dan bergaransi.',
            'store_address' => 'Jl. Soekarno Hatta No. 45, Lowokwaru, Kota Malang, Jawa Timur 65141',
            'store_phone' => '+62 812-3456-7890',
            'store_email' => 'contact@zlm.id',
            'store_google_maps' => '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3951.4449832785724!2d112.61339177579738!3d-7.952924979241513!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78827915555555%3A0x123456789abcdef!2sMalang!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>',
            'store_whatsapp' => '6281234567890',
            'social_instagram' => 'https://instagram.com/zlm.id',
            'social_facebook' => 'https://facebook.com/zlm.id',
            'social_tiktok' => 'https://tiktok.com/@zlm.id',
            'social_youtube' => 'https://youtube.com/@zlm.id',
            'store_logo' => '',
            'store_opening_hours' => 'Senin - Minggu: 09:00 - 21:00 WIB',

            // WhatsApp Notification Settings (Toggle ON/OFF)
            'wa_notification_enabled' => '1',
            'wa_provider' => 'fonnte', // fonnte, wablas, generic
            'wa_api_token' => 'sample_wa_token_zlm',
            'wa_sender_phone' => '6281234567890',
            'wa_admin_phone' => '6281234567890',
            'wa_notify_order_created' => '1',
            'wa_notify_payment_success' => '1',
            'wa_notify_order_shipped' => '1',
            'wa_notify_restock' => '1',
            'wa_notify_return_status' => '1',

            // Dot Matrix Printing Settings
            'dotmatrix_header' => 'ZLM.ID — PUSAT LAPTOP BERKUALITAS',
            'dotmatrix_address' => 'Jl. Soekarno Hatta No. 45, Malang | Telp/WA: 0812-3456-7890',
            'dotmatrix_footer' => 'Barang yang sudah dibeli dan lolos QC tercatat resmi di sistem ZLM.ID',
            'dotmatrix_paper_width' => '9.5inch',

            // Member Discount Rates (%)
            'member_discount_bronze' => '0',
            'member_discount_silver' => '1.5',
            'member_discount_gold' => '3',
            'member_discount_platinum' => '5',
        ];

        foreach ($defaults as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
