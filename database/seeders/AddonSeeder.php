<?php

namespace Database\Seeders;

use App\Models\Addon;
use Illuminate\Database\Seeder;

class AddonSeeder extends Seeder
{
    public function run(): void
    {
        $bundles = [
            [
                'name' => 'Non Bundle',
                'price' => 0,
                'description' => 'Paket standar unit laptop beserta charger dan dus original.',
                'is_recommended' => true,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => '+ANTIGORES',
                'price' => 50000,
                'description' => 'Bonus pemasangan screen protector antigores jernih & body protector pelindung casing laptop.',
                'is_recommended' => false,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'PAKET ACCESSORIES',
                'price' => 150000,
                'description' => 'Termasuk Mouse Wireless silent click, Mousepad anti-slip, & Sleeve Case laptop premium.',
                'is_recommended' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'PAKET HEMAT',
                'price' => 200000,
                'description' => 'Paket combo lengkap (+Antigores Layar + Mouse Wireless + Mousepad + Tas Laptop ZLM.ID). Pilihan paling hemat & direkomendasikan.',
                'is_recommended' => true,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'CAMPAIGN',
                'price' => 100000,
                'description' => 'Paket bundling spesial event campaign bulanan dengan merchandise eksklusif.',
                'is_recommended' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($bundles as $bundle) {
            Addon::firstOrCreate(['name' => $bundle['name']], $bundle);
        }
    }
}
