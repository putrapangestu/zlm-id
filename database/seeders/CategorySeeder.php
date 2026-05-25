<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Gaming', 'slug' => 'gaming', 'description' => 'High-performance laptops with dedicated GPUs for gaming', 'icon' => 'solar:gamepad-linear'],
            ['name' => 'Business', 'slug' => 'business', 'description' => 'Professional laptops for enterprise and productivity', 'icon' => 'solar:briefcase-linear'],
            ['name' => 'Student', 'slug' => 'student', 'description' => 'Affordable laptops for study and everyday use', 'icon' => 'solar:diploma-linear'],
            ['name' => 'Ultrabook', 'slug' => 'ultrabook', 'description' => 'Thin and light premium laptops for portability', 'icon' => 'solar:laptop-minimalistic-linear'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
