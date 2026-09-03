<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Laptop;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductItem;
use App\Models\Restock;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BrandAndFeaturesRevisionTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(['name' => 'admin']);
        
        $permissions = [
            'reports.purchases',
            'reports.profit_loss',
            'reports.product_stats',
            'settings.manage',
            'laptops.view',
            'laptops.manage',
            'restock.view',
            'restock.manage',
            'categories.manage',
            'users.manage',
            'transactions.view',
            'members.view',
            'articles.manage',
            'sliders.manage',
            'qc.view',
            'qc.pass',
            'qc.fail',
            'returns.view',
        ];

        foreach ($permissions as $p) {
            $perm = Permission::firstOrCreate(['name' => $p]);
            $role->givePermissionTo($perm);
        }

        $this->admin = User::factory()->create([
            'email' => 'admin@zlm.id',
        ]);
        $this->admin->assignRole('admin');
    }

    public function test_admin_can_view_brands_and_create_brand(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.brands.index'));
        $response->assertStatus(200);

        $createResponse = $this->actingAs($this->admin)->post(route('admin.brands.store'), [
            'name' => 'Alienware Gaming',
            'description' => 'Lini laptop gaming premium Alienware by Dell',
            'is_active' => 1,
            'sort_order' => 5,
        ]);

        $createResponse->assertRedirect(route('admin.brands.index'));
        $this->assertDatabaseHas('brands', [
            'name' => 'Alienware Gaming',
            'slug' => 'alienware-gaming',
        ]);
    }

    public function test_admin_brand_detail_shows_statistics(): void
    {
        $brand = Brand::firstOrCreate(
            ['name' => 'ThinkPad'],
            [
                'slug' => 'thinkpad',
                'description' => 'Laptop bisnis ThinkPad',
                'is_active' => true,
            ]
        );

        $laptop = Laptop::create([
            'name' => 'ThinkPad X1 Carbon Gen 10',
            'brand' => 'ThinkPad',
            'brand_id' => $brand->id,
            'description' => 'Ultrabook bisnis ringan',
            'price' => 18000000,
            'processor' => 'Intel Core i7-1260P',
            'ram' => '16GB LPDDR5',
            'storage' => '512GB SSD',
            'graphics' => 'Intel Iris Xe',
            'display' => '14" 2.8K OLED',
            'weight' => 1.12,
            'battery_life' => '57Wh (Hingga 8 Jam)',
            'stock' => 5,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.brands.show', $brand));
        $response->assertStatus(200);
        $response->assertSee('ThinkPad X1 Carbon Gen 10');
        $response->assertSee('5 Unit');
    }

    public function test_admin_can_toggle_brand_active_status(): void
    {
        $brand = Brand::firstOrCreate(
            ['name' => 'Razer Blade Special'],
            [
                'slug' => 'razer-blade-special',
                'is_active' => true,
            ]
        );

        $this->actingAs($this->admin)->patch(route('admin.brands.toggle-active', $brand));
        $this->assertFalse($brand->fresh()->is_active);

        $this->actingAs($this->admin)->patch(route('admin.brands.toggle-active', $brand));
        $this->assertTrue($brand->fresh()->is_active);
    }

    public function test_admin_can_configure_comparison_fields_and_landing_renders_them(): void
    {
        $brand = Brand::firstOrCreate(['name' => 'ASUS'], ['slug' => 'asus', 'is_active' => true]);
        
        $laptop1 = Laptop::create([
            'name' => 'ROG Zephyrus G14',
            'brand' => 'ASUS',
            'brand_id' => $brand->id,
            'description' => 'Laptop gaming portabel',
            'price' => 22000000,
            'processor' => 'AMD Ryzen 9 6900HS',
            'ram' => '32GB DDR5',
            'storage' => '1TB SSD',
            'graphics' => 'Radeon RX 6800S',
            'display' => '14" QHD 120Hz',
            'weight' => 1.72,
            'battery_life' => '76Wh',
            'ports' => "1x USB-C\n2x USB-A",
            'stock' => 3,
            'is_active' => true,
        ]);

        // Configure only RAM, CPU, and Ports
        $this->actingAs($this->admin)->post(route('admin.settings.update'), [
            '_tab' => 'comparison',
            'compare_fields' => ['processor', 'ram', 'ports'],
        ]);

        // Check landing compare page
        session(['compare' => [$laptop1->id]]);
        $response = $this->get(route('landing.compare'));
        $response->assertStatus(200);
        $response->assertSee('ROG Zephyrus G14');
        $response->assertSee('AMD Ryzen 9 6900HS');
        $response->assertSee('32GB DDR5');
    }

    public function test_multi_laptop_restock_batch(): void
    {
        $brand = Brand::firstOrCreate(['name' => 'Lenovo'], ['slug' => 'lenovo', 'is_active' => true]);
        
        $laptop1 = Laptop::create([
            'name' => 'ThinkPad T14 Gen 2',
            'brand' => 'Lenovo',
            'brand_id' => $brand->id,
            'description' => 'Laptop kantor',
            'price' => 8500000,
            'processor' => 'Intel Core i5-1135G7',
            'ram' => '16GB',
            'storage' => '256GB SSD',
            'graphics' => 'Intel Iris Xe',
            'display' => '14" FHD IPS',
            'weight' => 1.45,
            'battery_life' => '50Wh',
            'stock' => 0,
            'is_active' => true,
        ]);

        $laptop2 = Laptop::create([
            'name' => 'ThinkPad L14 Gen 2',
            'brand' => 'Lenovo',
            'brand_id' => $brand->id,
            'description' => 'Laptop kantor ekonomis',
            'price' => 7000000,
            'processor' => 'AMD Ryzen 5 PRO',
            'ram' => '8GB',
            'storage' => '256GB SSD',
            'graphics' => 'AMD Radeon Graphics',
            'display' => '14" HD',
            'weight' => 1.59,
            'battery_life' => '45Wh',
            'stock' => 0,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.restocks.store'), [
            'supplier_name' => 'PT. Distribusi Multi Utama',
            'purchase_date' => now()->format('Y-m-d'),
            'invoice_number' => 'INV-RESTOCK-MULTI-01',
            'entry_mode' => 'existing_product',
            'items' => [
                [
                    'laptop_id' => $laptop1->id,
                    'quantity' => 3,
                    'purchase_price' => 6000000,
                    'notes' => 'Batch 1 T14',
                ],
                [
                    'laptop_id' => $laptop2->id,
                    'quantity' => 2,
                    'purchase_price' => 5000000,
                    'notes' => 'Batch 1 L14',
                ],
            ],
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('restocks', [
            'supplier_name' => 'PT. Distribusi Multi Utama',
            'total_amount' => (3 * 6000000) + (2 * 5000000), // 28.000.000
        ]);

        $this->assertEquals(3, $laptop1->fresh()->uninspected_stock);
        $this->assertEquals(2, $laptop2->fresh()->uninspected_stock);
        $this->assertEquals(5, ProductItem::where('qc_status', 'pending')->count());
    }

    public function test_all_reports_endpoints_render_without_errors(): void
    {
        // 1. Purchases report - Supplier Restock
        $resSupplier = $this->actingAs($this->admin)->get(route('admin.reports.purchases', ['type' => 'supplier']));
        $resSupplier->assertStatus(200);

        // 2. Purchases report - Customer Orders
        $resCustomer = $this->actingAs($this->admin)->get(route('admin.reports.purchases', ['type' => 'customer']));
        $resCustomer->assertStatus(200);

        // 3. Profit Loss report
        $resProfitLoss = $this->actingAs($this->admin)->get(route('admin.reports.profit-loss'));
        $resProfitLoss->assertStatus(200);

        // 4. Product Stats report
        $resProductStats = $this->actingAs($this->admin)->get(route('admin.reports.product-stats'));
        $resProductStats->assertStatus(200);
    }
}
