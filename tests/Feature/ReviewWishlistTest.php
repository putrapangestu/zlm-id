<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Laptop;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewWishlistTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Laptop $laptop;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'buyer']);
        Role::create(['name' => 'admin']);

        $this->user = User::factory()->create();
        $this->user->assignRole('buyer');

        $this->laptop = Laptop::factory()->create();
    }

    public function test_guest_can_see_reviews(): void
    {
        $this->laptop->reviews()->create([
            'user_id' => $this->user->id,
            'rating' => 5,
            'comment' => 'Great laptop!',
            'is_approved' => true,
        ]);

        $response = $this->get("/laptop/{$this->laptop->id}");

        $response->assertStatus(200);
    }

    public function test_auth_user_can_submit_review(): void
    {
        $response = $this->actingAs($this->user)->post("/laptop/{$this->laptop->id}/reviews", [
            'rating' => 4,
            'comment' => 'Very good!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->user->id,
            'laptop_id' => $this->laptop->id,
            'rating' => 4,
            'is_approved' => false,
        ]);
    }

    public function test_user_can_toggle_wishlist(): void
    {
        $response = $this->actingAs($this->user)->post('/wishlist/toggle', [
            'laptop_id' => $this->laptop->id,
        ]);

        $response->assertJson(['status' => 'added']);
        $this->assertDatabaseHas('wishlists', [
            'user_id' => $this->user->id,
            'laptop_id' => $this->laptop->id,
        ]);
    }

    public function test_user_can_remove_wishlist(): void
    {
        $this->user->wishlists()->create(['laptop_id' => $this->laptop->id]);

        $response = $this->actingAs($this->user)->post('/wishlist/toggle', [
            'laptop_id' => $this->laptop->id,
        ]);

        $response->assertJson(['status' => 'removed']);
        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $this->user->id,
            'laptop_id' => $this->laptop->id,
        ]);
    }

    public function test_wishlist_page_shows_items(): void
    {
        $this->user->wishlists()->create(['laptop_id' => $this->laptop->id]);

        $response = $this->actingAs($this->user)->get('/wishlist');

        $response->assertStatus(200);
        $response->assertSee($this->laptop->name);
    }
}
