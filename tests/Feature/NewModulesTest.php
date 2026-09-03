<?php

namespace Tests\Feature;

use App\Models\Laptop;
use App\Models\LaptopVariant;
use App\Models\Order;
use App\Models\ProductItem;
use App\Models\Restock;
use App\Models\Setting;
use App\Models\User;
use App\Services\InventoryService;
use App\Services\WhatsAppService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class NewModulesTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $karyawan;
    protected User $customer;
    protected Laptop $laptop;

    protected function setUp(): void
    {
        parent::setUp();

        // Create standard roles
        $adminRole = Role::create(['name' => 'admin']);
        $karyawanRole = Role::create(['name' => 'karyawan']);
        $customerRole = Role::create(['name' => 'customer']);

        // Create permissions
        $permissions = [
            'pos.access', 'qc.view', 'qc.inspect', 'restock.view', 'restock.create',
            'restock.print', 'returns.view', 'returns.process', 'laptops.view',
            'members.view', 'members.manage', 'users.manage', 'reports.purchases',
            'reports.profit_loss', 'reports.product_stats', 'settings.manage',
            'transactions.view', 'transactions.confirm', 'categories.manage',
            'articles.manage', 'sliders.manage'
        ];

        foreach ($permissions as $perm) {
            Permission::create(['name' => $perm]);
        }

        $this->admin = User::factory()->create([
            'name' => 'Admin ZLM',
            'email' => 'admin@zlm.id',
        ]);
        $this->admin->assignRole('admin');

        $this->karyawan = User::factory()->create([
            'name' => 'Karyawan QC & POS',
            'email' => 'karyawan@zlm.id',
        ]);
        $this->karyawan->assignRole('karyawan');
        $this->karyawan->givePermissionTo(['qc.view', 'qc.inspect', 'pos.access', 'restock.view']);

        $this->customer = User::factory()->create([
            'name' => 'Member Gold',
            'email' => 'member@gmail.com',
            'member_tier' => 'gold',
            'member_points' => 150,
        ]);
        $this->customer->assignRole('customer');

        $this->laptop = Laptop::factory()->create([
            'name' => 'ThinkPad T14s Gen 3',
            'brand' => 'Lenovo',
            'price' => 12000000,
            'stock' => 0,
            'uninspected_stock' => 0,
            'qc_passed_stock' => 0,
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'is_discount_active' => true,
        ]);
    }

    public function test_discount_accessors_work_correctly(): void
    {
        $this->assertTrue($this->laptop->has_discount);
        $this->assertEquals(1200000, $this->laptop->discount_amount);
        $this->assertEquals(10800000, $this->laptop->final_price);
    }

    public function test_member_tier_discount_rates(): void
    {
        $this->assertEquals(3.0, $this->customer->tier_discount_percentage);

        $this->customer->update(['member_tier' => 'platinum']);
        $this->assertEquals(5.0, $this->customer->tier_discount_percentage);
    }

    public function test_restock_flow_creates_pending_qc_units_without_incrementing_sellable_stock(): void
    {
        $inventoryService = app(InventoryService::class);

        $restock = $inventoryService->createRestock([
            'supplier_name' => 'Distributor Jakarta',
            'purchase_date' => now()->format('Y-m-d'),
            'items' => [
                [
                    'laptop_id' => $this->laptop->id,
                    'quantity' => 3,
                    'purchase_price' => 8000000,
                ]
            ]
        ], $this->admin);

        $this->laptop->refresh();

        // 3 items created as pending QC
        $this->assertEquals(3, $this->laptop->uninspected_stock);
        $this->assertEquals(0, $this->laptop->stock); // Sellable stock remains 0 until QC pass
        $this->assertEquals(3, ProductItem::where('restock_id', $restock->id)->count());
    }

    public function test_qc_approval_assigns_sku_and_increments_sellable_stock(): void
    {
        $inventoryService = app(InventoryService::class);

        $inventoryService->createRestock([
            'supplier_name' => 'Distributor Jakarta',
            'purchase_date' => now()->format('Y-m-d'),
            'items' => [
                [
                    'laptop_id' => $this->laptop->id,
                    'quantity' => 1,
                    'purchase_price' => 8000000,
                ]
            ]
        ], $this->admin);

        $item = ProductItem::where('laptop_id', $this->laptop->id)->first();
        $this->assertEquals('pending', $item->qc_status);

        // Approve QC with SKU
        $sku = 'SKU-LEN-2026-TEST1';
        $inventoryService->passQc(
            $item,
            $sku,
            'SN12345678',
            ['screen' => 'ok', 'keyboard' => 'ok'],
            'Unit mulus Grade A',
            $this->karyawan
        );

        $item->refresh();
        $this->laptop->refresh();

        $this->assertEquals('passed', $item->qc_status);
        $this->assertEquals($sku, $item->sku);
        $this->assertEquals(1, $this->laptop->stock); // Sellable stock is now 1
        $this->assertEquals(0, $this->laptop->uninspected_stock);
        $this->assertEquals(1, $this->laptop->qc_passed_stock);

        $this->assertDatabaseHas('stock_movements', [
            'laptop_id' => $this->laptop->id,
            'type' => 'QC_PASSED',
            'quantity' => 1,
        ]);
    }

    public function test_granular_employee_permissions(): void
    {
        // Karyawan has qc.view permission
        $response = $this->actingAs($this->karyawan)->get('/admin/qc');
        $response->assertStatus(200);

        // Karyawan does NOT have users.manage permission
        $response = $this->actingAs($this->karyawan)->get('/admin/users');
        $response->assertStatus(403);

        // Admin has superadmin bypass and can access users.manage
        $response = $this->actingAs($this->admin)->get('/admin/users');
        $response->assertStatus(200);
    }

    public function test_pos_bootstrap_and_offline_sync(): void
    {
        // Set stock > 0 for POS catalog
        $this->laptop->update(['stock' => 5, 'qc_passed_stock' => 5]);

        // 1. Bootstrap JSON
        $response = $this->actingAs($this->karyawan)->get('/pos/bootstrap');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'products',
                'categories',
                'members',
                'settings',
            ]
        ]);

        // 2. Offline Sync
        $clientUuid = (string) Str::uuid();
        $syncData = [
            'orders' => [
                [
                    'client_order_uuid' => $clientUuid,
                    'payment_method' => 'cash',
                    'subtotal' => 10800000,
                    'discount' => 0,
                    'tax' => 1188000,
                    'total' => 11988000,
                    'cash_tendered' => 12000000,
                    'change_due' => 12000,
                    'member_id' => $this->customer->id,
                    'items' => [
                        [
                            'laptop_id' => $this->laptop->id,
                            'quantity' => 1,
                            'unit_price' => 10800000,
                        ]
                    ]
                ]
            ]
        ];

        $syncResponse = $this->actingAs($this->karyawan)->postJson('/pos/sync', $syncData);
        $syncResponse->assertStatus(200);
        $syncResponse->assertJsonFragment([
            'status' => 'synced',
            'client_order_uuid' => $clientUuid,
        ]);

        $this->laptop->refresh();
        $this->assertEquals(4, $this->laptop->stock); // Decremented from 5 to 4

        // Idempotency: re-submitting same UUID should return already_synced
        $reSyncResponse = $this->actingAs($this->karyawan)->postJson('/pos/sync', $syncData);
        $reSyncResponse->assertJsonFragment([
            'status' => 'already_synced',
        ]);
    }

    public function test_contact_page_can_be_viewed_and_submitted(): void
    {
        $response = $this->get('/kontak-kami');
        $response->assertStatus(200);
        $response->assertSee('Kunjungi Store Kami atau Hubungi Kami');

        $postResponse = $this->post('/kontak-kami', [
            'name' => 'Ahmad Dani',
            'email' => 'ahmad@example.com',
            'phone' => '081299998888',
            'subject' => 'Tanya Stok ThinkPad',
            'message' => 'Halo apakah unit ThinkPad T14s masih ready?',
        ]);

        $postResponse->assertRedirect();
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'ahmad@example.com',
            'name' => 'Ahmad Dani',
        ]);
    }

    public function test_whatsapp_notification_respects_toggle_setting(): void
    {
        Setting::setValue('wa_notification_enabled', '0');
        $service = app(WhatsAppService::class);

        $result = $service->sendMessage('081234567890', 'Test');
        $this->assertFalse($result['success']);
        $this->assertEquals('WhatsApp notification is disabled in settings.', $result['message']);
    }
}
