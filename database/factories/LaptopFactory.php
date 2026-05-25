<?php

namespace Database\Factories;

use App\Models\Laptop;
use Illuminate\Database\Eloquent\Factories\Factory;

class LaptopFactory extends Factory
{
    protected $model = Laptop::class;

    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'brand' => fake()->randomElement(['TechPro', 'AlphaBook', 'NovaTech', 'OmniPC', 'SwiftLab']),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 500, 4000),
            'processor' => fake()->randomElement(['Intel Core i5', 'Intel Core i7', 'Intel Core i9', 'AMD Ryzen 5', 'AMD Ryzen 7', 'AMD Ryzen 9', 'Apple M1', 'Apple M2', 'Apple M3']),
            'ram' => fake()->randomElement(['8GB', '16GB', '32GB', '64GB']),
            'storage' => fake()->randomElement(['256GB SSD', '512GB SSD', '1TB SSD', '2TB SSD']),
            'graphics' => fake()->randomElement(['Intel UHD Graphics', 'Intel Iris Xe', 'NVIDIA GeForce RTX 3050', 'NVIDIA GeForce RTX 4060', 'NVIDIA GeForce RTX 4090', 'AMD Radeon', 'Apple GPU (10-core)', 'Apple GPU (16-core)']),
            'display' => fake()->randomElement(['13.3-inch IPS', '14-inch IPS', '15.6-inch OLED', '16-inch IPS', '17.3-inch IPS']),
            'weight' => fake()->randomFloat(2, 1.0, 3.5),
            'battery_life' => fake()->numberBetween(4, 20) . ' hours',
            'stock' => fake()->numberBetween(5, 100),
            'is_featured' => fake()->boolean(40),
        ];
    }
}
