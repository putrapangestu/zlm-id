<?php

namespace Tests\Feature;

use App\Models\Addon;
use App\Models\Laptop;
use App\Models\User;
use App\Models\Cart;
use App\Services\XenditService;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddonsAndSpecsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $buyer;
    private Laptop $laptop;
    private Addon $addon;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'buyer']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->buyer = User::factory()->create();
        $this->buyer->assignRole('buyer');

        $this->laptop = Laptop::factory()->create([
            'name' => 'ThinkPad X1 Carbon Gen 10',
            'brand' => 'Lenovo',
            'price' => 15000000,
            'processor' => 'Intel Core i7-1260P',
            'ram' => '16GB LPDDR5',
            'storage' => '512GB NVMe SSD',
            'display' => '14 inch 2.8K OLED',
            'ports' => "2x Thunderbolt 4 / USB4 40Gbps\n2x USB 3.2 Gen 1 Type-A\n1x HDMI 2.0b\n1x 3.5mm Headphone / microphone combo jack",
            'camera' => '1080p FHD RGB+IR with Privacy Shutter',
            'audio' => 'Stereo speakers, 2W x2 woofers and 0.8W x2 tweeters, Dolby Atmos',
            'connectivity' => 'Intel Wi-Fi 6E AX211 + Bluetooth 5.1',
            'color' => 'Deep Black',
            'warranty' => 'Garansi Resmi Lenovo 3 Tahun',
            'stock' => 5,
            'is_active' => true,
        ]);

        $this->addon = Addon::create([
            'name' => 'PAKET HEMAT',
            'price' => 200000,
            'description' => 'Antigores + Mouse + Tas Laptop',
            'is_recommended' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Mock Xendit
        $this->mock(XenditService::class, function ($mock) {
            $mock->shouldReceive('createInvoice')
                ->andReturn([
                    'id' => 'mock-inv-id',
                    'invoice_url' => 'https://mock.xendit.co/inv',
                    'expiry_date' => now()->addDay()->toISOString(),
                    'status' => 'PENDING',
                ]);
        });
    }

    public function test_laptop_ports_list_accessor_parses_multiline_ports(): void
    {
        $portsList = $this->laptop->ports_list;

        $this->assertIsArray($portsList);
        $this->assertCount(4, $portsList);
        $this->assertEquals('2x Thunderbolt 4 / USB4 40Gbps', $portsList[0]);
        $this->assertEquals('1x 3.5mm Headphone / microphone combo jack', $portsList[3]);
        $this->assertEquals('1080p FHD RGB+IR with Privacy Shutter', $this->laptop->camera);
        $this->assertEquals('Deep Black', $this->laptop->color);
    }

    public function test_admin_can_crud_addons_and_toggle_recommendation(): void
    {
        // 1. Admin views addons index
        $response = $this->actingAs($this->admin)->get(route('admin.addons.index'));
        $response->assertStatus(200);
        $response->assertSee('PAKET HEMAT');

        // 2. Admin creates a new addon
        $createResponse = $this->actingAs($this->admin)->post(route('admin.addons.store'), [
            'name' => '+ANTIGORES LAYAR',
            'price' => 50000,
            'description' => 'Bonus antigores jernih',
            'is_recommended' => 1,
            'is_active' => 1,
            'sort_order' => 2,
        ]);
        $createResponse->assertRedirect(route('admin.addons.index'));
        $this->assertDatabaseHas('addons', ['name' => '+ANTIGORES LAYAR', 'price' => 50000]);

        $createdAddon = Addon::where('name', '+ANTIGORES LAYAR')->first();

        // 3. Toggle recommendation
        $toggleResponse = $this->actingAs($this->admin)->patch(route('admin.addons.toggle-recommended', $createdAddon));
        $toggleResponse->assertRedirect();
        $this->assertFalse($createdAddon->fresh()->is_recommended);

        // 4. Toggle active
        $toggleActiveResponse = $this->actingAs($this->admin)->patch(route('admin.addons.toggle-active', $createdAddon));
        $toggleActiveResponse->assertRedirect();
        $this->assertFalse($createdAddon->fresh()->is_active);
    }

    public function test_cart_add_with_addon_calculates_combined_subtotal(): void
    {
        $response = $this->actingAs($this->buyer)->post('/cart/add', [
            'laptop_id' => $this->laptop->id,
            'addon_id' => $this->addon->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect('/cart');
        $this->assertDatabaseHas('cart_items', [
            'laptop_id' => $this->laptop->id,
            'addon_id' => $this->addon->id,
            'addon_price' => 200000.00,
            'quantity' => 2,
            'unit_price' => 15000000.00,
        ]);

        $cart = Cart::where('user_id', $this->buyer->id)->first();
        $cartItem = $cart->items->first();
        // Subtotal = (15.000.000 + 200.000) * 2 = 30.400.000
        $this->assertEquals(30400000.00, $cartItem->subtotal);
    }

    public function test_place_order_stores_addon_details_in_order_items(): void
    {
        $cart = Cart::create(['user_id' => $this->buyer->id]);
        $cart->items()->create([
            'laptop_id' => $this->laptop->id,
            'addon_id' => $this->addon->id,
            'addon_price' => 200000.00,
            'quantity' => 1,
            'unit_price' => 15000000.00,
        ]);

        $response = $this->actingAs($this->buyer)->post('/orders', [
            'shipping_address' => 'Jl. Ijen No. 88',
            'shipping_city' => 'Malang',
            'shipping_province' => 'Jawa Timur',
            'shipping_postal_code' => '65115',
            'shipping_phone' => '081234567890',
            'shipping_cost' => 30000,
            'shipping_courier' => 'jne',
            'shipping_service' => 'REG',
            'shipping_etd' => '1-2 hari',
            'shipping_city_id' => '256',
            'shipping_city_name' => 'Malang',
            'shipping_province_name' => 'Jawa Timur',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $this->buyer->id,
            'subtotal' => 15200000.00, // 15.000.000 + 200.000
        ]);

        $this->assertDatabaseHas('order_items', [
            'laptop_id' => $this->laptop->id,
            'addon_id' => $this->addon->id,
            'addon_name' => 'PAKET HEMAT',
            'addon_price' => 200000.00,
            'product_name' => $this->laptop->name,
            'quantity' => 1,
        ]);
    }

    public function test_api_search_templates_returns_extended_hardware_specs(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/laptops/api/templates?q=ThinkPad');
        $response->assertStatus(200);

        $data = $response->json('data.0');
        $this->assertEquals('ThinkPad X1 Carbon Gen 10', $data['name']);
        $this->assertStringContainsString('Thunderbolt 4', $data['ports']);
        $this->assertEquals('1080p FHD RGB+IR with Privacy Shutter', $data['camera']);
        $this->assertEquals('Deep Black', $data['color']);
    }

    public function test_public_detail_page_renders_bundle_pills_and_io_ports(): void
    {
        $response = $this->get(route('landing.detail', $this->laptop));
        $response->assertStatus(200);

        // Bundle selector section
        $response->assertSee('BUNDLE:');
        $response->assertSee('PAKET HEMAT');

        // Detailed I/O Ports
        $response->assertSee('I/O Ports');
        $response->assertSee('2x Thunderbolt 4 / USB4 40Gbps');
        $response->assertSee('1080p FHD RGB+IR with Privacy Shutter');
        $response->assertSee('Deep Black');
    }
}
