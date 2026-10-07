<?php

namespace Tests\Feature;

use App\Models\Laptop;
use App\Models\ProductItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LaptopCatalogSpreadsheetTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_export_creates_three_status_sheets_with_catalog_and_unit_data(): void
    {
        $laptop = Laptop::factory()->create([
            'name' => 'ThinkPad X1 Carbon',
            'brand' => 'Lenovo',
            'stock' => 1,
        ]);

        ProductItem::create([
            'laptop_id' => $laptop->id,
            'sku' => 'READY-001',
            'serial_number' => 'SN-READY',
            'qc_status' => 'passed',
            'is_sold' => false,
            'base_cost' => 10000000,
            'final_cost' => 10100000,
        ]);
        ProductItem::create([
            'laptop_id' => $laptop->id,
            'sku' => 'PENDING-001',
            'serial_number' => 'SN-PENDING',
            'qc_status' => 'pending',
            'is_sold' => false,
        ]);
        ProductItem::create([
            'laptop_id' => $laptop->id,
            'sku' => 'SOLD-001',
            'serial_number' => 'SN-SOLD',
            'qc_status' => 'sold',
            'is_sold' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.laptops.export'));

        $response->assertOk();
        $path = tempnam(sys_get_temp_dir(), 'laptop-export-');
        file_put_contents($path, $response->streamedContent());
        $spreadsheet = IOFactory::load($path);
        unlink($path);

        $this->assertSame(['Produk Ready', 'Produk Belum Ready', 'Produk Habis'], $spreadsheet->getSheetNames());
        $this->assertSame('READY-001', $spreadsheet->getSheetByName('Produk Ready')->getCell('Q2')->getValue());
        $this->assertSame('PENDING-001', $spreadsheet->getSheetByName('Produk Belum Ready')->getCell('Q2')->getValue());
        $this->assertSame('SOLD-001', $spreadsheet->getSheetByName('Produk Habis')->getCell('Q2')->getValue());
        $spreadsheet->disconnectWorksheets();
    }

    public function test_import_upserts_unit_rows_from_three_sheets_without_duplicating_stock(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'laptop-import-');
        $spreadsheet = new Spreadsheet;
        $spreadsheet->getActiveSheet()->setTitle('Produk Ready');
        $this->writeImportSheet($spreadsheet->getActiveSheet(), [
            $this->unitRow('SKU-READY', 'SN-READY'),
        ]);
        $pending = $spreadsheet->createSheet();
        $pending->setTitle('Produk Belum Ready');
        $this->writeImportSheet($pending, [
            $this->unitRow('SKU-PENDING', 'SN-PENDING'),
        ]);
        $sold = $spreadsheet->createSheet();
        $sold->setTitle('Produk Habis');
        $this->writeImportSheet($sold, [
            $this->unitRow('SKU-SOLD', 'SN-SOLD'),
        ]);
        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        $upload = UploadedFile::fake()->createWithContent('inventory.xlsx', file_get_contents($path));
        unlink($path);

        $response = $this->actingAs($this->admin)->post(route('admin.laptops.import'), ['file' => $upload]);
        $response->assertRedirect(route('admin.laptops.index'));
        $response->assertSessionHas('success', 'Impor selesai: 3 unit baru dan 0 unit diperbarui.');

        $laptop = Laptop::query()->where('name', 'ThinkPad X1 Carbon')->firstOrFail();
        $this->assertSame(1, $laptop->stock);
        $this->assertSame(1, $laptop->uninspected_stock);
        $this->assertSame(3, ProductItem::query()->where('laptop_id', $laptop->id)->count());
        $this->assertSame('sold', ProductItem::query()->where('sku', 'SKU-SOLD')->value('qc_status'));

        $secondUpload = UploadedFile::fake()->createWithContent('inventory.xlsx', $this->makeWorkbookContent());
        $this->actingAs($this->admin)->post(route('admin.laptops.import'), ['file' => $secondUpload])
            ->assertSessionHas('success', 'Impor selesai: 0 unit baru dan 3 unit diperbarui.');

        $this->assertSame(3, ProductItem::query()->where('laptop_id', $laptop->id)->count());
        $this->assertSame(1, $laptop->fresh()->stock);
        $this->assertSame(1, $laptop->fresh()->uninspected_stock);
    }

    public function test_import_accepts_the_legacy_single_sheet_workbook(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'legacy-laptop-import-');
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Update Stock Laptop Total');
        $sheet->fromArray([
            [
                'NO', 'TANGGAL', 'LAPTOP', 'MERK', 'TYPE', 'PROCESSOR', 'VGA', 'STORAGE', 'RAM',
                'LAYAR', 'WARNA', 'KELENGKAPAN', 'KONDISI', 'FISIK', 'MASA GARANSI', 'SERIAL NUMBER',
                'SKU', 'MODAL LAPTOP', 'DUS', 'RAM', 'STORAGE', 'SPARE PART', 'LAIN LAIN', 'TOTAL MODAL',
                'HARGA JUAL',
            ],
        ], null, 'B4');
        $sheet->fromArray([
            array_map(fn ($column) => 'Column'.$column, range(1, 25)),
            [
                1, 46277, 'Acer Aspire Lite 14', 'Acer', 'Aspire Lite 14', 'Intel Core 3 N355',
                'Intel Graphics', '256GB', '8GB', '14 inch', 'Silver', 'Laptop + Charger', 'Mulus',
                0.96, 'Januari 2027', 'SERIAL-LEGACY', 'SKU-LEGACY', 4400000, 45000, 0, 0, 0, 0, 4445000,
                7450000,
            ],
        ], null, 'B5');
        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        $upload = UploadedFile::fake()->createWithContent('legacy.xlsx', file_get_contents($path));
        unlink($path);
        $this->actingAs($this->admin)->post(route('admin.laptops.import'), ['file' => $upload])
            ->assertSessionHas('success', 'Impor selesai: 1 unit baru dan 0 unit diperbarui.');

        $this->assertSame(1, ProductItem::query()->count());
        $this->assertSame('SKU-LEGACY', ProductItem::query()->value('sku'));
        $this->assertSame('2026-09-12', ProductItem::query()->first()->received_specs['purchase_date']);
        $this->assertSame(1, Laptop::query()->where('name', 'Acer Aspire Lite 14')->value('stock'));
    }

    private function makeWorkbookContent(): string
    {
        $path = tempnam(sys_get_temp_dir(), 'laptop-import-again-');
        $spreadsheet = new Spreadsheet;
        $spreadsheet->getActiveSheet()->setTitle('Produk Ready');
        $this->writeImportSheet($spreadsheet->getActiveSheet(), [$this->unitRow('SKU-READY', 'SN-READY')]);
        $pending = $spreadsheet->createSheet();
        $pending->setTitle('Produk Belum Ready');
        $this->writeImportSheet($pending, [$this->unitRow('SKU-PENDING', 'SN-PENDING')]);
        $sold = $spreadsheet->createSheet();
        $sold->setTitle('Produk Habis');
        $this->writeImportSheet($sold, [$this->unitRow('SKU-SOLD', 'SN-SOLD')]);
        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();
        $content = file_get_contents($path);
        unlink($path);

        return $content;
    }

    private function writeImportSheet(Worksheet $sheet, array $rows): void
    {
        $sheet->fromArray([
            [
                'NO', 'TANGGAL', 'LAPTOP', 'MERK', 'TYPE', 'PROCESSOR', 'VGA', 'STORAGE', 'RAM',
                'LAYAR', 'WARNA', 'KELENGKAPAN', 'KONDISI', 'FISIK', 'MASA GARANSI', 'SERIAL NUMBER',
                'SKU', 'MODAL LAPTOP', 'DUS', 'RAM TAMBAHAN', 'STORAGE TAMBAHAN', 'SPARE PART',
                'LAIN-LAIN', 'TOTAL MODAL', 'HARGA JUAL', 'STATUS QC', 'STATUS UNIT',
            ],
            ...$rows,
        ]);
    }

    private function unitRow(string $sku, string $serial): array
    {
        return [
            1, '2026-10-07', 'ThinkPad X1 Carbon', 'Lenovo', 'X1 Carbon Gen 12', 'Intel Core Ultra 7',
            'Intel Graphics', '1TB SSD', '32GB', '14 inch', 'Black', 'Laptop + Charger', 'Like New',
            'A', 'Garansi 1 Tahun', $serial, $sku, 10000000, 50000, 0, 0, 0, 0, 10050000, 15000000,
            'passed', 'Ready',
        ];
    }
}
