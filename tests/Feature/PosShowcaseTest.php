<?php

namespace Tests\Feature;

use App\Models\Laptop;
use App\Models\User;
use Database\Seeders\MarketingRoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PosShowcaseTest extends TestCase
{
    use RefreshDatabase;

    private User $marketer;

    protected function setUp(): void
    {
        parent::setUp();

        Permission::create(['name' => 'pos.showcase', 'guard_name' => 'web']);
        $marketingRole = Role::create(['name' => 'marketing', 'guard_name' => 'web']);
        $marketingRole->givePermissionTo('pos.showcase');

        $this->marketer = User::factory()->create();
        $this->marketer->assignRole($marketingRole);
    }

    public function test_marketing_can_open_the_product_showroom_without_register_controls(): void
    {
        $response = $this->actingAs($this->marketer)->get(route('pos.showcase'));

        $response->assertOk()
            ->assertSee('Mode Showroom Offline.')
            ->assertSee('Mode Showroom Offline')
            ->assertDontSee('id="pos-cart-aside"', false)
            ->assertDontSee('BAYAR SEKARANG');
    }

    public function test_showroom_bootstrap_includes_active_out_of_stock_products_but_no_customer_or_qc_data(): void
    {
        Laptop::factory()->create([
            'name' => 'Showroom Laptop',
            'stock' => 0,
            'is_active' => true,
            'description' => 'Produk untuk katalog marketing.',
            'graphics' => 'Integrated graphics',
        ]);

        $response = $this->actingAs($this->marketer)->get(route('pos.showcase.bootstrap'));

        $response->assertOk()
            ->assertJsonPath('data.products.0.name', 'Showroom Laptop')
            ->assertJsonPath('data.products.0.description', 'Produk untuk katalog marketing.')
            ->assertJsonPath('data.products.0.graphics', 'Integrated graphics')
            ->assertJsonPath('data.qc_units', [])
            ->assertJsonPath('data.members', []);
    }

    public function test_users_without_showcase_permission_cannot_open_the_marketing_catalog(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('pos.showcase'))
            ->assertForbidden();
    }

    public function test_marketing_users_cannot_use_cashier_bootstrap_or_order_sync_endpoints(): void
    {
        $this->actingAs($this->marketer)
            ->get(route('pos.bootstrap'))
            ->assertForbidden();

        $this->actingAs($this->marketer)
            ->post(route('pos.sync'), [])
            ->assertForbidden();
    }

    public function test_marketing_role_seeder_is_idempotent_and_grants_only_the_showcase_permission(): void
    {
        $this->seed(MarketingRoleSeeder::class);

        $role = Role::findByName('marketing');

        $this->assertSame(['pos.showcase'], $role->permissions->pluck('name')->all());
    }
}
