<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Laptop;
use App\Models\LaptopVariant;
use App\Models\Category;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'buyer']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_admin(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    public function test_buyer_cannot_access_admin(): void
    {
        $buyer = User::factory()->create();
        $buyer->assignRole('buyer');

        $response = $this->actingAs($buyer)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_laptop(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->post('/admin/laptops', [
            'name' => 'Test Laptop',
            'brand' => 'TestBrand',
            'description' => 'A test laptop',
            'price' => 999.99,
            'processor' => 'Intel i7',
            'ram' => '16GB',
            'storage' => '512GB SSD',
            'graphics' => 'Intel UHD',
            'display' => '14-inch IPS',
            'stock' => 10,
            'categories' => [$category->id],
        ]);

        $response->assertRedirect('/admin/laptops');
        $this->assertDatabaseHas('laptops', ['name' => 'Test Laptop']);
    }

    public function test_admin_can_create_variant(): void
    {
        $laptop = Laptop::factory()->create();

        $response = $this->actingAs($this->admin)->post("/admin/laptops/{$laptop->id}/variants", [
            'name' => '1TB SSD',
            'sku' => 'TL-1TB',
            'price_modifier' => 200.00,
            'stock' => 10,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('laptop_variants', ['sku' => 'TL-1TB']);
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/categories', [
            'name' => 'Gaming',
        ]);

        $response->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', ['name' => 'Gaming']);
    }
}
