<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Laptop;
use App\Models\LaptopVariant;
use App\Models\Cart;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Laptop $laptop;
    private LaptopVariant $variant;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'buyer']);

        $this->user = User::factory()->create();
        $this->user->assignRole('buyer');

        $this->laptop = Laptop::factory()->create(['price' => 1500.00]);
        $this->variant = LaptopVariant::factory()->create([
            'laptop_id' => $this->laptop->id,
            'price_modifier' => 100.00,
            'stock' => 5,
        ]);
    }

    public function test_checkout_page_requires_auth(): void
    {
        $response = $this->get('/checkout');

        $response->assertRedirect('/login');
    }

    public function test_checkout_page_shows_empty_cart_message(): void
    {
        $response = $this->actingAs($this->user)->get('/checkout');

        $response->assertRedirect(route('cart.index'));
    }

    public function test_user_can_place_order(): void
    {
        $cart = Cart::create(['user_id' => $this->user->id]);
        $cart->items()->create([
            'laptop_id' => $this->laptop->id,
            'laptop_variant_id' => $this->variant->id,
            'quantity' => 2,
            'unit_price' => 1600.00,
        ]);

        $response = $this->actingAs($this->user)->post('/orders', [
            'notes' => 'Leave at door',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'subtotal' => 3200.00,
            'tax' => 352.00,
            'total' => 3552.00,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_name' => $this->laptop->name,
            'variant_name' => $this->variant->name,
            'quantity' => 2,
        ]);

        $this->assertDatabaseMissing('cart_items', [
            'cart_id' => $cart->id,
        ]);
    }

    public function test_order_confirmation_page(): void
    {
        $cart = Cart::create(['user_id' => $this->user->id]);
        $cart->items()->create([
            'laptop_id' => $this->laptop->id,
            'laptop_variant_id' => $this->variant->id,
            'quantity' => 1,
            'unit_price' => 1600.00,
        ]);

        $this->actingAs($this->user)->post('/orders');
        $order = $this->user->orders()->first();

        $response = $this->actingAs($this->user)->get("/orders/{$order->id}");

        $response->assertStatus(200);
        $response->assertSee($order->order_number);
    }

    public function test_user_cannot_view_others_order(): void
    {
        $otherUser = User::factory()->create();
        $otherUser->assignRole('buyer');

        $cart = Cart::create(['user_id' => $otherUser->id]);
        $cart->items()->create([
            'laptop_id' => $this->laptop->id,
            'laptop_variant_id' => $this->variant->id,
            'quantity' => 1,
            'unit_price' => 1600.00,
        ]);

        $this->actingAs($otherUser)->post('/orders');
        $order = $otherUser->orders()->first();

        $response = $this->actingAs($this->user)->get("/orders/{$order->id}");

        $response->assertStatus(403);
    }

    public function test_order_history_page(): void
    {
        $response = $this->actingAs($this->user)->get('/orders');

        $response->assertStatus(200);
    }
}
