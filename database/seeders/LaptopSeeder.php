<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Laptop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LaptopSeeder extends Seeder
{
    public function run(): void
    {
        $gaming = Category::where('slug', 'gaming')->first();
        $business = Category::where('slug', 'business')->first();
        $student = Category::where('slug', 'student')->first();
        $ultrabook = Category::where('slug', 'ultrabook')->first();

        $laptops = [
            // Gaming Laptops
            [
                'name' => 'ROG Zephyrus G16',
                'brand' => 'ASUS',
                'description' => 'High-performance gaming laptop with RTX 4090 graphics and 12th gen Intel processor. Perfect for competitive gaming and content creation.',
                'price' => 3499.99,
                'processor' => 'Intel Core i9-12900HK',
                'ram' => '32GB DDR5',
                'storage' => '1TB NVMe SSD',
                'graphics' => 'NVIDIA RTX 4090',
                'display' => '16" 4K OLED 120Hz',
                'weight' => 2.5,
                'battery_life' => '6 hours',
                'stock' => 5,
                'is_featured' => true,
                'categories' => [$gaming->id],
                'variants' => [
                    ['name' => '32GB / 1TB', 'sku' => 'rog-g16-32-1t', 'price_modifier' => 0, 'ram' => '32GB DDR5', 'storage' => '1TB NVMe SSD', 'stock' => 3],
                    ['name' => '64GB / 2TB', 'sku' => 'rog-g16-64-2t', 'price_modifier' => 400, 'ram' => '64GB DDR5', 'storage' => '2TB NVMe SSD', 'stock' => 2],
                ],
            ],
            [
                'name' => 'Legion Pro 9',
                'brand' => 'Lenovo',
                'description' => 'Ultimate gaming powerhouse with advanced cooling and RGB lighting. Designed for extreme gaming performance.',
                'price' => 3299.99,
                'processor' => 'Intel Core i9-13980HX',
                'ram' => '32GB DDR5',
                'storage' => '2TB NVMe SSD',
                'graphics' => 'NVIDIA RTX 4080',
                'display' => '16" QHD+ 240Hz',
                'weight' => 2.8,
                'battery_life' => '5 hours',
                'stock' => 3,
                'is_featured' => true,
                'categories' => [$gaming->id],
                'variants' => [
                    ['name' => '32GB / 2TB', 'sku' => 'legion9-32-2t', 'price_modifier' => 0, 'ram' => '32GB DDR5', 'storage' => '2TB NVMe SSD', 'stock' => 2],
                    ['name' => '64GB / 4TB', 'sku' => 'legion9-64-4t', 'price_modifier' => 500, 'ram' => '64GB DDR5', 'storage' => '4TB NVMe SSD', 'stock' => 1],
                ],
            ],
            [
                'name' => 'MSI Raider GE78',
                'brand' => 'MSI',
                'description' => 'Compact gaming laptop with powerful specs. Great for portable gaming without compromising performance.',
                'price' => 2899.99,
                'processor' => 'Intel Core i7-13700HX',
                'ram' => '24GB DDR5',
                'storage' => '1TB NVMe SSD',
                'graphics' => 'NVIDIA RTX 4070',
                'display' => '15.6" FHD 144Hz',
                'weight' => 2.6,
                'battery_life' => '4 hours',
                'stock' => 8,
                'is_featured' => false,
                'categories' => [$gaming->id],
                'variants' => [
                    ['name' => '24GB / 1TB', 'sku' => 'msi-ge78-24-1t', 'price_modifier' => 0, 'ram' => '24GB DDR5', 'storage' => '1TB NVMe SSD', 'stock' => 5],
                    ['name' => '32GB / 2TB', 'sku' => 'msi-ge78-32-2t', 'price_modifier' => 300, 'ram' => '32GB DDR5', 'storage' => '2TB NVMe SSD', 'stock' => 3],
                ],
            ],

            // Business Laptops
            [
                'name' => 'ThinkPad X1 Extreme',
                'brand' => 'Lenovo',
                'description' => 'Professional workstation for business professionals. Excellent build quality and performance for enterprise work.',
                'price' => 2299.99,
                'processor' => 'Intel Core i7-13700H',
                'ram' => '32GB DDR4',
                'storage' => '1TB NVMe SSD',
                'graphics' => 'NVIDIA RTX 4060',
                'display' => '16" 2.5K IPS',
                'weight' => 1.9,
                'battery_life' => '8 hours',
                'stock' => 12,
                'is_featured' => true,
                'categories' => [$business->id],
                'variants' => [
                    ['name' => '32GB / 1TB', 'sku' => 'tp-x1-32-1t', 'price_modifier' => 0, 'ram' => '32GB DDR4', 'storage' => '1TB NVMe SSD', 'stock' => 8],
                    ['name' => '64GB / 2TB', 'sku' => 'tp-x1-64-2t', 'price_modifier' => 350, 'ram' => '64GB DDR4', 'storage' => '2TB NVMe SSD', 'stock' => 4],
                ],
            ],
            [
                'name' => 'MacBook Pro 16"',
                'brand' => 'Apple',
                'description' => 'Premium professional laptop with M2 Max chip and stunning Retina display. Ideal for creative professionals.',
                'price' => 2499.99,
                'processor' => 'Apple M2 Max',
                'ram' => '32GB Unified Memory',
                'storage' => '1TB SSD',
                'graphics' => '12-core GPU',
                'display' => '16" Liquid Retina XDR',
                'weight' => 2.1,
                'battery_life' => '17 hours',
                'stock' => 7,
                'is_featured' => true,
                'categories' => [$business->id],
                'variants' => [
                    ['name' => '32GB / 1TB', 'sku' => 'mbp16-32-1t', 'price_modifier' => 0, 'ram' => '32GB Unified Memory', 'storage' => '1TB SSD', 'stock' => 4],
                    ['name' => '64GB / 2TB', 'sku' => 'mbp16-64-2t', 'price_modifier' => 600, 'ram' => '64GB Unified Memory', 'storage' => '2TB SSD', 'stock' => 3],
                ],
            ],
            [
                'name' => 'Dell XPS 17',
                'brand' => 'Dell',
                'description' => 'Premium Windows laptop for professionals. Combines power and portability in a sleek design.',
                'price' => 2199.99,
                'processor' => 'Intel Core i7-13700H',
                'ram' => '16GB DDR5',
                'storage' => '512GB NVMe SSD',
                'graphics' => 'NVIDIA RTX 4070',
                'display' => '17" OLED 3.5K',
                'weight' => 2.3,
                'battery_life' => '10 hours',
                'stock' => 6,
                'is_featured' => false,
                'categories' => [$business->id],
                'variants' => [
                    ['name' => '16GB / 512GB', 'sku' => 'xps17-16-512', 'price_modifier' => 0, 'ram' => '16GB DDR5', 'storage' => '512GB NVMe SSD', 'stock' => 3],
                    ['name' => '32GB / 1TB', 'sku' => 'xps17-32-1t', 'price_modifier' => 300, 'ram' => '32GB DDR5', 'storage' => '1TB NVMe SSD', 'stock' => 3],
                ],
            ],

            // Student Laptops
            [
                'name' => 'IdeaPad 3',
                'brand' => 'Lenovo',
                'description' => 'Budget-friendly laptop perfect for students. Great performance for everyday tasks and studies.',
                'price' => 599.99,
                'processor' => 'AMD Ryzen 5 5500U',
                'ram' => '8GB DDR4',
                'storage' => '512GB NVMe SSD',
                'graphics' => 'AMD Radeon Graphics',
                'display' => '15.6" HD',
                'weight' => 1.9,
                'battery_life' => '7 hours',
                'stock' => 25,
                'is_featured' => true,
                'categories' => [$student->id],
                'variants' => [
                    ['name' => '8GB / 512GB', 'sku' => 'ip3-8-512', 'price_modifier' => 0, 'ram' => '8GB DDR4', 'storage' => '512GB NVMe SSD', 'stock' => 15],
                    ['name' => '16GB / 1TB', 'sku' => 'ip3-16-1t', 'price_modifier' => 150, 'ram' => '16GB DDR4', 'storage' => '1TB NVMe SSD', 'stock' => 10],
                ],
            ],
            [
                'name' => 'VivoBook 15',
                'brand' => 'ASUS',
                'description' => 'Affordable and reliable laptop for students. Perfect for note-taking, research, and entertainment.',
                'price' => 649.99,
                'processor' => 'AMD Ryzen 7 5700U',
                'ram' => '8GB DDR4',
                'storage' => '256GB SSD',
                'graphics' => 'AMD Radeon Graphics',
                'display' => '15.6" FHD',
                'weight' => 1.8,
                'battery_life' => '8 hours',
                'stock' => 20,
                'is_featured' => false,
                'categories' => [$student->id],
                'variants' => [
                    ['name' => '8GB / 256GB', 'sku' => 'vb15-8-256', 'price_modifier' => 0, 'ram' => '8GB DDR4', 'storage' => '256GB SSD', 'stock' => 12],
                    ['name' => '16GB / 512GB', 'sku' => 'vb15-16-512', 'price_modifier' => 120, 'ram' => '16GB DDR4', 'storage' => '512GB SSD', 'stock' => 8],
                ],
            ],
            [
                'name' => 'Inspiron 15',
                'brand' => 'Dell',
                'description' => 'Student-friendly laptop with solid performance and great value for money.',
                'price' => 549.99,
                'processor' => 'Intel Core i5-1235U',
                'ram' => '8GB DDR4',
                'storage' => '256GB SSD',
                'graphics' => 'Intel Iris Xe',
                'display' => '15.6" FHD',
                'weight' => 1.75,
                'battery_life' => '7 hours',
                'stock' => 30,
                'is_featured' => false,
                'categories' => [$student->id],
                'variants' => [
                    ['name' => '8GB / 256GB', 'sku' => 'ins15-8-256', 'price_modifier' => 0, 'ram' => '8GB DDR4', 'storage' => '256GB SSD', 'stock' => 20],
                    ['name' => '16GB / 512GB', 'sku' => 'ins15-16-512', 'price_modifier' => 100, 'ram' => '16GB DDR4', 'storage' => '512GB SSD', 'stock' => 10],
                ],
            ],

            // Ultrabook
            [
                'name' => 'MacBook Air M2',
                'brand' => 'Apple',
                'description' => 'Ultra-thin and lightweight with exceptional battery life. Perfect for on-the-go professionals.',
                'price' => 1299.99,
                'processor' => 'Apple M2',
                'ram' => '8GB Unified Memory',
                'storage' => '512GB SSD',
                'graphics' => '10-core GPU',
                'display' => '13.6" Liquid Retina',
                'weight' => 1.24,
                'battery_life' => '18 hours',
                'stock' => 10,
                'is_featured' => true,
                'categories' => [$ultrabook->id],
                'variants' => [
                    ['name' => '8GB / 512GB', 'sku' => 'mba-m2-8-512', 'price_modifier' => 0, 'ram' => '8GB Unified Memory', 'storage' => '512GB SSD', 'stock' => 5],
                    ['name' => '16GB / 1TB', 'sku' => 'mba-m2-16-1t', 'price_modifier' => 300, 'ram' => '16GB Unified Memory', 'storage' => '1TB SSD', 'stock' => 5],
                ],
            ],
            [
                'name' => 'ZenBook 14',
                'brand' => 'ASUS',
                'description' => 'Sleek ultrabook with premium build quality and impressive performance. Ideal for travelers.',
                'price' => 1099.99,
                'processor' => 'AMD Ryzen 7 6800H',
                'ram' => '16GB DDR4',
                'storage' => '512GB NVMe SSD',
                'graphics' => 'AMD Radeon Graphics',
                'display' => '14" OLED FHD',
                'weight' => 1.4,
                'battery_life' => '13 hours',
                'stock' => 8,
                'is_featured' => false,
                'categories' => [$ultrabook->id],
                'variants' => [
                    ['name' => '16GB / 512GB', 'sku' => 'zb14-16-512', 'price_modifier' => 0, 'ram' => '16GB DDR4', 'storage' => '512GB NVMe SSD', 'stock' => 5],
                    ['name' => '32GB / 1TB', 'sku' => 'zb14-32-1t', 'price_modifier' => 250, 'ram' => '32GB DDR4', 'storage' => '1TB NVMe SSD', 'stock' => 3],
                ],
            ],
            [
                'name' => 'XPS 13 Plus',
                'brand' => 'Dell',
                'description' => 'Ultra-portable premium ultrabook. Minimalist design with maximum performance and battery life.',
                'price' => 1199.99,
                'processor' => 'Intel Core Ultra 7',
                'ram' => '16GB DDR5',
                'storage' => '512GB NVMe SSD',
                'graphics' => 'Intel Arc Graphics',
                'display' => '13.4" OLED',
                'weight' => 1.2,
                'battery_life' => '15 hours',
                'stock' => 6,
                'is_featured' => false,
                'categories' => [$ultrabook->id],
                'variants' => [
                    ['name' => '16GB / 512GB', 'sku' => 'xps13-16-512', 'price_modifier' => 0, 'ram' => '16GB DDR5', 'storage' => '512GB NVMe SSD', 'stock' => 3],
                    ['name' => '32GB / 1TB', 'sku' => 'xps13-32-1t', 'price_modifier' => 350, 'ram' => '32GB DDR5', 'storage' => '1TB NVMe SSD', 'stock' => 3],
                ],
            ],
        ];

        foreach ($laptops as $data) {
            $variants = $data['variants'] ?? [];
            $categoryIds = $data['categories'] ?? [];
            unset($data['variants'], $data['categories']);

            $laptop = Laptop::create($data);
            $laptop->categories()->attach($categoryIds);

            foreach ($variants as $variant) {
                $laptop->variants()->create($variant);
            }
        }
    }
}
