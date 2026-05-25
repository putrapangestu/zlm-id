<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Gaming', 'Ultrabook', 'Workstation', 'Student',
                'Creator', 'Business', 'Budget', 'Premium',
            ]),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }
}
