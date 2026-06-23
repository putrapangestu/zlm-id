<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\HeroSlider;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HeroSliderTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $buyer;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'buyer']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->buyer = User::factory()->create();
        $this->buyer->assignRole('buyer');
    }

    // ========================
    // SLIDER CRUD TESTS
    // ========================

    public function test_admin_can_access_slider_index(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/sliders');
        $response->assertStatus(200);
    }

    public function test_admin_can_access_slider_create(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/sliders/create');
        $response->assertStatus(200);
    }

    public function test_admin_can_create_slider(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/sliders', [
            'title' => 'Welcome Sale',
            'subtitle' => 'Big Discounts',
            'description' => 'Get up to 50% off on selected laptops.',
            'button_text' => 'Shop Now',
            'button_url' => '/search',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response->assertRedirect(route('admin.sliders.index'));
        $this->assertDatabaseHas('hero_sliders', [
            'title' => 'Welcome Sale',
            'subtitle' => 'Big Discounts',
        ]);
    }

    public function test_admin_can_edit_slider(): void
    {
        $slider = HeroSlider::create([
            'title' => 'Original Title',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $response = $this->actingAs($this->admin)->get("/admin/sliders/{$slider->id}/edit");
        $response->assertStatus(200);
    }

    public function test_admin_can_update_slider(): void
    {
        $slider = HeroSlider::create([
            'title' => 'Original Title',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $response = $this->actingAs($this->admin)->put("/admin/sliders/{$slider->id}", [
            'title' => 'Updated Title',
            'subtitle' => 'Updated Subtitle',
            'is_active' => false,
            'sort_order' => 2,
        ]);

        $response->assertRedirect(route('admin.sliders.index'));
        $this->assertDatabaseHas('hero_sliders', [
            'id' => $slider->id,
            'title' => 'Updated Title',
        ]);
    }

    public function test_admin_can_delete_slider(): void
    {
        $slider = HeroSlider::create([
            'title' => 'To Delete',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $response = $this->actingAs($this->admin)->delete("/admin/sliders/{$slider->id}");

        $response->assertRedirect(route('admin.sliders.index'));
        $this->assertSoftDeleted('hero_sliders', [
            'id' => $slider->id,
        ]);
    }

    public function test_guest_cannot_access_slider_admin(): void
    {
        $response = $this->get('/admin/sliders');
        $response->assertRedirect('/login');
    }

    public function test_non_admin_user_cannot_access_slider_admin(): void
    {
        $response = $this->actingAs($this->buyer)->get('/admin/sliders');
        $response->assertStatus(403);
    }

    // ========================
    // HOMEPAGE SLIDER TESTS
    // ========================

    public function test_homepage_shows_slider_when_data_exists(): void
    {
        HeroSlider::create([
            'title' => 'Welcome Sale',
            'is_active' => true,
            'sort_order' => 0,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('slider-item');
        $response->assertSee('Welcome Sale');
    }

    public function test_homepage_shows_fallback_when_no_sliders(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Toko Laptop Bekas Berkualitas');
    }

    // ========================
    // MODEL & SCOPES TESTS
    // ========================

    public function test_active_scope_only_returns_active_sliders(): void
    {
        HeroSlider::create(['title' => 'Active 1', 'is_active' => true, 'sort_order' => 0]);
        HeroSlider::create(['title' => 'Active 2', 'is_active' => true, 'sort_order' => 1]);
        HeroSlider::create(['title' => 'Inactive 1', 'is_active' => false, 'sort_order' => 2]);

        $activeSliders = HeroSlider::active()->get();

        $this->assertCount(2, $activeSliders);
        $this->assertTrue($activeSliders->pluck('title')->contains('Active 1'));
        $this->assertTrue($activeSliders->pluck('title')->contains('Active 2'));
        $this->assertFalse($activeSliders->pluck('title')->contains('Inactive 1'));
    }

    public function test_sorted_scope_orders_by_sort_order(): void
    {
        HeroSlider::create(['title' => 'Third', 'is_active' => true, 'sort_order' => 3]);
        HeroSlider::create(['title' => 'First', 'is_active' => true, 'sort_order' => 1]);
        HeroSlider::create(['title' => 'Second', 'is_active' => true, 'sort_order' => 2]);

        $sortedSliders = HeroSlider::sorted()->get();

        $this->assertEquals('First', $sortedSliders[0]->title);
        $this->assertEquals('Second', $sortedSliders[1]->title);
        $this->assertEquals('Third', $sortedSliders[2]->title);
    }
}
