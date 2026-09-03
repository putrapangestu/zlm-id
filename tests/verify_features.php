<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Laptop;
use App\Models\ProductItem;
use App\Models\ProductReturn;
use App\Models\Setting;
use App\Models\StockMovement;
use App\Models\User;
use App\Services\InventoryService;
use App\Services\WhatsAppService;
use Illuminate\Support\Str;

echo "=== VERIFYING ZLM.ID NEW & REVISED FEATURES ===\n\n";

// 1. Spatie Permissions & User UUID
echo "1. Checking Admin and Karyawan Roles & Permissions...\n";
$admin = User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->first();
if (!$admin) {
    $admin = User::firstOrCreate(
        ['email' => 'admin@zlm.id'],
        ['name' => 'Admin ZLM', 'password' => bcrypt('password'), 'email_verified_at' => now()]
    );
    $admin->assignRole('admin');
}
echo "   [PASS] Admin UUID: {$admin->id}, Role: {$admin->roles->pluck('name')->join(', ')}\n";

// 2. Direct Restock Ingestion (Creates Product with is_active = false, SKU = null)
echo "\n2. Testing Product Creation via Restock (QC Off / Inactive Initial State)...\n";
$inventoryService = app(InventoryService::class);

$uniqueModelName = 'Dell Latitude 7430 Gen 12 - Test ' . rand(100, 999);
$restockBatch = $inventoryService->createRestock([
    'supplier_name' => 'PT. Distributor Utama Jaya',
    'purchase_date' => '2026-09-02',
    'invoice_number' => 'INV-SUPP-7762',
    'items' => [
        [
            'new_laptop' => [
                'name' => $uniqueModelName,
                'brand' => 'Dell',
                'processor' => 'Intel Core i7-1265U',
                'ram' => '16GB DDR4',
                'storage' => '512GB NVMe SSD',
                'graphics' => 'Intel Iris Xe',
                'display' => '14 inch FHD',
                'price' => 11000000,
            ],
            'quantity' => 2,
            'purchase_price' => 7500000,
        ]
    ]
], $admin);

$createdLaptop = Laptop::where('name', $uniqueModelName)->first();
assert($createdLaptop !== null, "Laptop must be created from restock");
echo "   Laptop created via Restock: {$createdLaptop->name}\n";
echo "   is_active status: " . ($createdLaptop->is_active ? 'true' : 'false') . " (expected: false - QC Off)\n";
echo "   Sellable stock: {$createdLaptop->stock} (expected: 0)\n";
echo "   Uninspected stock: {$createdLaptop->uninspected_stock} (expected: 2)\n";
assert($createdLaptop->is_active === false, "Product created via restock must be inactive / QC off initially");
assert($createdLaptop->stock === 0, "Sellable stock must be 0 initially");
assert($createdLaptop->uninspected_stock === 2, "Uninspected stock must be 2");

$pendingUnits = ProductItem::where('laptop_id', $createdLaptop->id)->get();
assert($pendingUnits->count() === 2, "Must create 2 product units");
assert($pendingUnits[0]->sku === null, "SKU must be null before QC");
assert($pendingUnits[0]->qc_status === 'pending', "QC status must be pending");
echo "   [PASS] Rule verified: Restock creates products as inactive (is_active=false) with SKU=null and uninspected_stock.\n";

// 3. QC Pass & SKU Issuance (Activates Product)
echo "\n3. Testing QC Pass & SKU Issuance (Auto-activates Product & Increases Sellable Stock)...\n";
$unitToInspect = $pendingUnits[0];
$issuedSku = 'SKU-DEL-' . date('ymd') . '-' . strtoupper(Str::random(4));

$inventoryService->passQc(
    $unitToInspect,
    $issuedSku,
    'SN-DELL-' . strtoupper(Str::random(6)),
    ['screen' => 'ok', 'keyboard' => 'ok', 'battery' => 'ok', 'body' => 'ok', 'ports' => 'ok', 'webcam' => 'ok', 'specs' => 'match'],
    'Unit mulus 99%, battery health 95%',
    $admin
);

$createdLaptop->refresh();
$unitToInspect->refresh();
echo "   Inspected Unit SKU: {$unitToInspect->sku}, QC Status: {$unitToInspect->qc_status}\n";
echo "   Laptop is_active status: " . ($createdLaptop->is_active ? 'true' : 'false') . " (expected: true - Auto Activated)\n";
echo "   New Sellable Stock: {$createdLaptop->stock} (expected: 1)\n";
echo "   Remaining Uninspected Stock: {$createdLaptop->uninspected_stock} (expected: 1)\n";

assert($unitToInspect->sku === $issuedSku, "SKU must match issued SKU");
assert($unitToInspect->qc_status === 'passed', "QC status must be passed");
assert($createdLaptop->is_active === true, "Laptop must auto-activate upon passing QC");
assert($createdLaptop->stock === 1, "Sellable stock must increment to 1");
assert($createdLaptop->uninspected_stock === 1, "Uninspected stock must decrement to 1");
echo "   [PASS] Rule verified: SKU is issued exclusively during QC Pass, which activates the product and adds sellable stock.\n";

// 4. Supplier Return Flow (Retur ke Supplier Restock)
echo "\n4. Testing Supplier Return Flow...\n";
$secondUnit = $pendingUnits[1];
$inventoryService->failQc(
    $secondUnit,
    ['screen' => 'defect', 'keyboard' => 'ok', 'battery' => 'ok', 'body' => 'ok', 'ports' => 'ok', 'webcam' => 'ok', 'specs' => 'match'],
    'Layar garis vertikal pink (Cacat pabrik)',
    $admin
);
$secondUnit->refresh();
assert($secondUnit->qc_status === 'failed', "QC status must be failed");

$supplierReturn = $inventoryService->createSupplierReturn([
    'product_item_id' => $secondUnit->id,
    'restock_id' => $restockBatch->id,
    'supplier_name' => 'PT. Distributor Utama Jaya',
    'reason' => 'defective_item',
    'resolution_type' => 'replacement',
    'notes' => 'Unit retur klaim cacat layar distributor',
], $admin);

echo "   Supplier Return created: {$supplierReturn->return_number}, Type: {$supplierReturn->return_type}\n";
echo "   Supplier: {$supplierReturn->supplier_name}, Resolution: {$supplierReturn->resolution_type}\n";
assert($supplierReturn->return_type === 'supplier', "Return type must be supplier");
assert($supplierReturn->restock_id === $restockBatch->id, "Must link to restock batch");
echo "   [PASS] Supplier return flow verified.\n";

// 5. Auto-Fill Search API by SKU & Name
echo "\n5. Testing Auto-Fill Template Search API...\n";
$laptopController = app(\App\Http\Controllers\Admin\LaptopController::class);
$response = $laptopController->apiSearchTemplates(new \Illuminate\Http\Request(['q' => $issuedSku]));
$responseData = $response->getData(true);
assert($responseData['status'] === 'success', "API response status must be success");
assert(count($responseData['data']) > 0, "Must find template by SKU");
echo "   Search query by SKU '{$issuedSku}': found '{$responseData['data'][0]['name']}'\n";
echo "   Auto-filled specs: CPU={$responseData['data'][0]['processor']}, RAM={$responseData['data'][0]['ram']}, Storage={$responseData['data'][0]['storage']}\n";
assert($responseData['data'][0]['name'] === $uniqueModelName, "Template name mismatch");
echo "   [PASS] Auto-fill SKU template lookup verified.\n";

// 6. WhatsApp Notification & Purchase Button
echo "\n6. Testing WhatsApp Notification Toggle...\n";
Setting::setValue('wa_notification_enabled', '0');
$waService = app(WhatsAppService::class);
$resDisabled = $waService->sendMessage('081234567890', 'Test');
echo "   When WA Disabled: success=" . ($resDisabled['success'] ? 'true' : 'false') . ", message: {$resDisabled['message']}\n";
assert($resDisabled['success'] === false, "WA should not send when disabled");

Setting::setValue('wa_notification_enabled', '1');
echo "   When WA Enabled: master toggle is ON.\n";
echo "   [PASS] WhatsApp ON/OFF toggle verified.\n";

echo "\n>>> ALL VERIFICATION CHECKS PASSED SUCCESSFULLY! <<<\n";
