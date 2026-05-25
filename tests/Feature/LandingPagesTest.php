<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Laptop;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_loads(): void
    {
        Laptop::factory()->count(3)->create(['is_featured' => true]);
        Category::factory()->count(2)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Featured');
    }

    public function test_search_page_loads(): void
    {
        Laptop::factory()->create(['name' => 'TestBook Pro']);
        Laptop::factory()->create(['brand' => 'TestBrand']);

        $response = $this->get('/search');

        $response->assertStatus(200);
        $response->assertSee('TestBook Pro');
        $response->assertSee('TestBrand');
    }

    public function test_search_filters_by_query(): void
    {
        Laptop::factory()->create(['name' => 'MacBook Pro']);
        Laptop::factory()->create(['name' => 'ThinkPad']);

        $response = $this->get('/search?search=MacBook');

        $response->assertStatus(200);
        $response->assertSee('MacBook Pro');
        $response->assertDontSee('ThinkPad');
    }

    public function test_detail_page_loads(): void
    {
        $laptop = Laptop::factory()->create();

        $response = $this->get("/laptop/{$laptop->id}");

        $response->assertStatus(200);
        $response->assertSee($laptop->name);
    }

    public function test_compare_page_loads(): void
    {
        $laptops = Laptop::factory()->count(3)->create();
        $ids = $laptops->pluck('id')->implode(',');

        $response = $this->get("/compare?ids={$ids}");

        $response->assertStatus(200);
    }
}
