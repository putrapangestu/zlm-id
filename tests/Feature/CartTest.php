<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Laptop;
use App\Models\Cart;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Laptop $laptop;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'buyer']);

        $this->user = User::factory()->create();
        $this->user->assignRole('buyer');

        $this->laptop = Laptop::factory()->create(['price' => 1500.00, 'stock' => 5]);
    }

    public function test_guest_can_view_cart(): void
    {
        $response = $this->get('/cart');

        $response->assertStatus(200);
    }

    public function test_guest_can_add_to_cart(): void
    {
        $response = $this->post('/cart/add', [
            'laptop_id' => $this->laptop->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect('/cart');
        $this->assertDatabaseHas('cart_items', [
            'laptop_id' => $this->laptop->id,
            'quantity' => 1,
        ]);
    }

    public function test_auth_user_can_add_to_cart(): void
    {
        $response = $this->actingAs($this->user)->post('/cart/add', [
            'laptop_id' => $this->laptop->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect('/cart');
        $this->assertDatabaseHas('cart_items', [
            'laptop_id' => $this->laptop->id,
            'quantity' => 2,
        ]);
    }

    public function test_cannot_add_out_of_stock_laptop(): void
    {
        $outOfStockLaptop = Laptop::factory()->create(['stock' => 0]);

        $response = $this->post('/cart/add', [
            'laptop_id' => $outOfStockLaptop->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('laptop_id');
    }

    public function test_cart_item_quantity_can_be_updated(): void
    {
        $cart = Cart::create(['user_id' => $this->user->id]);
        $item = $cart->items()->create([
            'laptop_id' => $this->laptop->id,
            'quantity' => 1,
            'unit_price' => 1500.00,
        ]);

        $response = $this->actingAs($this->user)->patch("/cart/{$item->id}", [
            'quantity' => 3,
        ]);

        $response->assertRedirect('/cart');
        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'quantity' => 3,
        ]);
    }

    public function test_cart_item_can_be_removed(): void
    {
        $cart = Cart::create(['user_id' => $this->user->id]);
        $item = $cart->items()->create([
            'laptop_id' => $this->laptop->id,
            'quantity' => 1,
            'unit_price' => 1500.00,
        ]);

        $response = $this->actingAs($this->user)->delete("/cart/{$item->id}");

        $response->assertRedirect('/cart');
        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }
}
