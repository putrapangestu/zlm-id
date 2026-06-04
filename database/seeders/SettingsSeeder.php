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
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
