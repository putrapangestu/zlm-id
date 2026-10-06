<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Laptop;
use App\Models\LaptopImage;
use App\Models\LaptopVariant;
use App\Models\User;
use Database\Seeders\GrantShowcaseAccessToAdminsSeeder;
use Database\Seeders\RoleAndUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PosShowcaseTest extends TestCase
{
    use RefreshDatabase;

    private User $showcaseUser;

    protected function setUp(): void
    {
        parent::setUp();

        Permission::create(['name' => 'pos.showcase', 'guard_name' => 'web']);
        $employeeRole = Role::create(['name' => 'karyawan', 'guard_name' => 'web']);

        $this->showcaseUser = User::factory()->create();
        $this->showcaseUser->assignRole($employeeRole);
        $this->showcaseUser->givePermissionTo('pos.showcase');
    }

    public function test_employee_with_showcase_permission_can_open_the_read_only_catalog(): void
    {
        $response = $this->actingAs($this->showcaseUser)->get(route('pos.showcase'));

        $response->assertOk()
            ->assertSee('Mode Showroom Offline.')
            ->assertSee('Mode Showroom Offline')
            ->assertDontSee('id="pos-cart-aside"', false)
            ->assertDontSee('BAYAR SEKARANG');
    }

    public function test_showroom_bootstrap_includes_active_out_of_stock_products_but_no_customer_or_qc_data(): void
    {
        $laptop = Laptop::factory()->create([
            'name' => 'Showroom Laptop',
            'sku' => 'SHOWROOM-001',
            'stock' => 0,
            'is_active' => true,
            'description' => 'Produk untuk katalog marketing.',
            'graphics' => 'Integrated graphics',
            'weight' => 1.25,
            'image_url' => 'laptops/main.jpg',
        ]);
        $laptop->images()->create([
            'image_url' => 'laptops/side.jpg',
            'caption' => 'Tampak samping',
            'sort_order' => 1,
        ]);
        $laptop->variants()->create([
            'name' => 'RAM 32 GB',
            'sku' => 'SHOWROOM-001-32',
            'price_modifier' => 1000000,
            'ram' => '32 GB',
            'graphics' => 'RTX 4060',
            'display' => '15.6 inch FHD',
            'weight' => 1.4,
            'battery_life' => '8 jam',
            'stock' => 3,
            'is_active' => true,
        ]);
        $laptop->variants()->create([
            'name' => 'Nonaktif',
            'sku' => 'SHOWROOM-001-OFF',
            'is_active' => false,
        ]);
        $category = Category::create([
            'name' => 'Gaming',
            'slug' => 'gaming',
            'is_active' => true,
        ]);
        $laptop->categories()->attach($category);

        $response = $this->actingAs($this->showcaseUser)->get(route('pos.showcase.bootstrap'));

        $response->assertOk()
            ->assertJsonPath('data.products.0.name', 'Showroom Laptop')
            ->assertJsonPath('data.products.0.sku', 'SHOWROOM-001')
            ->assertJsonPath('data.products.0.description', 'Produk untuk katalog marketing.')
            ->assertJsonPath('data.products.0.graphics', 'Integrated graphics')
            ->assertJsonPath('data.products.0.weight', '1.25')
            ->assertJsonPath('data.products.0.images.1.caption', 'Tampak samping')
            ->assertJsonPath('data.products.0.category_names.0', 'Gaming')
            ->assertJsonPath('data.products.0.variants.0.graphics', 'RTX 4060')
            ->assertJsonPath('data.products.0.variants.0.display', '15.6 inch FHD')
            ->assertJsonPath('data.products.0.variants.0.battery_life', '8 jam')
            ->assertJsonPath('data.products.0.variants.0.stock', 3)
            ->assertJsonCount(1, 'data.products.0.variants')
            ->assertJsonPath('data.qc_units', [])
            ->assertJsonPath('data.members', []);
    }

    public function test_users_without_showcase_permission_cannot_open_the_catalog(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('pos.showcase'))
            ->assertForbidden();
    }

    public function test_showcase_only_users_cannot_use_cashier_bootstrap_or_order_sync_endpoints(): void
    {
        $this->actingAs($this->showcaseUser)
            ->get(route('pos.bootstrap'))
            ->assertForbidden();

        $this->actingAs($this->showcaseUser)
            ->post(route('pos.sync'), [])
            ->assertForbidden();
    }

    public function test_admin_role_receives_showcase_permission_from_the_standard_seeder(): void
    {
        $this->seed(RoleAndUserSeeder::class);

        $admin = User::role('admin')->firstOrFail();

        $this->assertTrue($admin->can('pos.showcase'));

        $this->actingAs($admin)
            ->get(route('pos.showcase'))
            ->assertOk()
            ->assertSee('Mode Showroom');
    }

    public function test_showcase_access_can_be_granted_to_existing_admins_without_reseeding_employee_permissions(): void
    {
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole($adminRole);

        Permission::create(['name' => 'pos.access', 'guard_name' => 'web']);
        $employeeRole = Role::findByName('karyawan');
        $employeeRole->givePermissionTo('pos.access');

        $this->seed(GrantShowcaseAccessToAdminsSeeder::class);

        $this->assertTrue($admin->fresh()->can('pos.showcase'));
        $this->assertTrue($employeeRole->fresh()->hasPermissionTo('pos.access'));
        $this->assertFalse($employeeRole->fresh()->hasPermissionTo('pos.showcase'));
    }
}
