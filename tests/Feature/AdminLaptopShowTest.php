<?php

namespace Tests\Feature;

use App\Models\Laptop;
use App\Models\ProductItem;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLaptopShowTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Laptop $laptop;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');

        $this->laptop = Laptop::factory()->create([
            'name' => 'ThinkPad T14s Gen 3',
            'brand' => 'Lenovo',
            'price' => 12000000,
            'processor' => 'AMD Ryzen 7 PRO 6850U',
            'ram' => '16GB LPDDR5',
            'storage' => '512GB SSD',
            'ports' => "2x USB-C 3.2 Gen 2\n2x USB 3.2 Gen 1 Type-A\n1x HDMI 2.0b\n1x Audio jack",
            'camera' => '1080p FHD with Privacy Shutter',
            'audio' => 'Dolby Audio stereo speakers',
            'connectivity' => 'Wi-Fi 6E + Bluetooth 5.2',
            'color' => 'Thunder Black',
            'warranty' => 'Garansi Toko 1 Bulan',
            'stock' => 2,
            'is_active' => true,
        ]);

        ProductItem::create([
            'laptop_id' => $this->laptop->id,
            'sku' => 'SKU-LEN-T14S-001',
            'serial_number' => 'PF-2XYZ01',
            'physical_grade' => 'A',
            'qc_status' => 'passed',
            'is_sold' => false,
            'checked_at' => now(),
            'checked_by' => $this->admin->id,
        ]);
    }

    public function test_admin_can_view_laptop_show_page_without_variant_errors(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.laptops.show', $this->laptop));

        $response->assertStatus(200);
        $response->assertSee('ThinkPad T14s Gen 3');
        $response->assertSee('AMD Ryzen 7 PRO 6850U');
        $response->assertSee('SKU-LEN-T14S-001');
        $response->assertSee('Lolos QC');
        $response->assertSee('Thunder Black');
        $response->assertSee('2x USB-C 3.2 Gen 2');
        $response->assertDontSee('Add Variant');
        $response->assertDontSee('Manage Variants');
    }

    public function test_admin_can_view_laptop_edit_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.laptops.edit', $this->laptop));

        $response->assertStatus(200);
        $response->assertSee('ThinkPad T14s Gen 3');
        $response->assertSee('Unit QC');
    }
}
