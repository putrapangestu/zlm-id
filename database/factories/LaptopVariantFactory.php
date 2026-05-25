<?php

namespace Database\Factories;

use App\Models\Laptop;
use App\Models\LaptopVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class LaptopVariantFactory extends Factory
{
    protected $model = LaptopVariant::class;

    public function definition(): array
    {
        return [
            'laptop_id' => Laptop::factory(),
            'name' => fake()->randomElement(['Default', 'Pro', 'Max', 'Ultra', 'Premium', 'Lite']),
            'sku' => strtoupper(fake()->bothify('VAR-####??')),
            'price_modifier' => fake()->randomFloat(2, -200, 800),
            'stock' => fake()->numberBetween(0, 50),
            'is_active' => true,
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }
}
