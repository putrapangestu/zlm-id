<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductSpreadsheetService
{
    public const HEADERS = [
        'NO',
        'KATEGORI',
        'NAMA BARANG',
        'SKU',
        'BRAND / MERK',
        'TIPE / MODEL',
        'JUMLAH STOK',
        'HARGA MODAL (HPP)',
        'HARGA JUAL',
        'STATUS',
        'VARIAN RAM',
        'VARIAN STORAGE',
        'SPESIFIKASI / KETERANGAN',
    ];

    /**
     * Generate template Spreadsheet or Export data Spreadsheet.
     */
    public function makeSpreadsheet(bool $withData = true, ?Collection $products = null): Spreadsheet
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Master Barang');

        // Set Headers
        $sheet->fromArray(self::HEADERS, null, 'A1');
        $this->styleHeader($sheet);

        $rowIdx = 2;

        if ($withData) {
            $items = $products ?? Product::with('category')->latest()->get();
            foreach ($items as $index => $item) {
                $row = [
                    $index + 1,
                    $item->category->name ?? 'Sparepart & Aksesoris',
                    $item->name,
                    $item->sku,
                    $item->brand ?? '-',
                    $item->type ?? '',
                    (int) $item->stock,
                    (float) $item->cost_price,
                    (float) $item->price,
                    $item->is_active ? 'READY' : 'NONAKTIF',
                    $item->ram ?? '',
                    $item->storage ?? '',
                    $item->description ?? '',
                ];

                $this->writeRow($sheet, $row, $rowIdx);
                $rowIdx++;
            }
        } else {
            // Include helpful sample rows for template
            $samples = [
                [
                    1,
                    'Suku Cadang',
                    'BACKCOVER ASPIRE 5 A514 GOLD',
                    'SCACBCAS5G041026001',
                    'ACER',
                    'ASPIRE 5 A514',
                    1,
                    500000,
                    650000,
                    'READY',
                    '',
                    '',
                    'Warna: Gold, Kondisi Baru',
                ],
                [
                    2,
                    'RAM',
                    'RAM ADATA 8GB DDR4 3200MHz',
                    'RADA83200423045002',
                    'ADATA',
                    'SODIMM',
                    5,
                    175000,
                    250000,
                    'READY',
                    '8GB 3200MHz DDR4',
                    '',
                    'Memory Laptop PC4',
                ],
                [
                    3,
                    'Charger / Adaptor',
                    'ADAPTOR ASUS SAMBUNG 45W 2.37A',
                    'AASS23745LB08056001',
                    'ASUS',
                    'SAMBUNG',
                    2,
                    170000,
                    240000,
                    'READY',
                    '',
                    '',
                    '45W, 2.37A, Colokan Lubang Besar',
                ],
                [
                    4,
                    'Storage',
                    'HDD SEAGATE 1TB 2.5 SATA',
                    'ZDEH2LDA',
                    'SEAGATE',
                    '2.5 INCH',
                    1,
                    0,
                    150000,
                    'READY',
                    '',
                    '1TB HDD SATA',
                    'SN: ZDEH2LDA, Sentinel 100%',
                ],
                [
                    5,
                    'Stylus Pen',
                    'STYLUS PEN ASUS HITAM',
                    'SPASHI31076001',
                    'ASUS',
                    'ACTIVE STYLUS',
                    1,
                    0,
                    120000,
                    'READY',
                    '',
                    '',
                    'Warna: Hitam, Kompatibel Zenbook/Vivobook Flip',
                ],
                [
                    6,
                    'Dus',
                    'DUS LAPTOP ACER ASPIRE 14 INCH KODE A',
                    'DACA05086001',
                    'ACER',
                    'ASPIRE 14 INCH',
                    1,
                    50000,
                    100000,
                    'READY',
                    '',
                    '',
                    'Kode Dus: A, Box Original',
                ],
            ];

            foreach ($samples as $sample) {
                $this->writeRow($sheet, $sample, $rowIdx);
                $rowIdx++;
            }
        }

        $this->autoFitColumns($sheet);

        return $spreadsheet;
    }

    /**
     * Import rows from an uploaded spreadsheet file.
     *
     * @return array{created: int, updated: int, failed: int, errors: array<string>}
     */
    public function import(string $filePath): array
    {
        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        $rows = $sheet->toArray(null, true, false, false);
        if (count($rows) <= 1) {
            return [
                'created' => 0,
                'updated' => 0,
                'failed' => 0,
                'errors' => ['File kosong atau hanya berisi baris header.'],
            ];
        }

        // Header mapping
        $headerRow = array_map(fn ($h) => trim((string) $h), $rows[0]);
        $colMap = [];
        foreach (self::HEADERS as $canonical) {
            foreach ($headerRow as $idx => $cell) {
                if (strcasecmp($cell, $canonical) === 0 || str_contains(strtoupper($cell), strtoupper(explode(' ', $canonical)[0]))) {
                    $colMap[$canonical] = $idx;
                    break;
                }
            }
        }

        $created = 0;
        $updated = 0;
        $failed = 0;
        $errors = [];

        // Category cache
        $categories = Category::pluck('id', 'name')->toArray();

        DB::beginTransaction();

        try {
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];

                // Check if row is totally blank
                $nonEmpty = array_filter($row, fn ($v) => $v !== null && trim((string) $v) !== '');
                if (empty($nonEmpty)) {
                    continue;
                }

                $rowNum = $i + 1;

                // Extract fields by index or fallback position
                $categoryName = trim((string) ($row[$colMap['KATEGORI'] ?? 1] ?? 'Sparepart'));
                $name = trim((string) ($row[$colMap['NAMA BARANG'] ?? 2] ?? ''));
                $sku = trim((string) ($row[$colMap['SKU'] ?? 3] ?? ''));
                $brand = trim((string) ($row[$colMap['BRAND / MERK'] ?? 4] ?? 'General'));
                $type = trim((string) ($row[$colMap['TIPE / MODEL'] ?? 5] ?? ''));
                $stockRaw = $row[$colMap['JUMLAH STOK'] ?? 6] ?? 1;
                $costRaw = $row[$colMap['HARGA MODAL (HPP)'] ?? 7] ?? 0;
                $priceRaw = $row[$colMap['HARGA JUAL'] ?? 8] ?? 0;
                $status = trim((string) ($row[$colMap['STATUS'] ?? 9] ?? 'READY'));
                $ram = trim((string) ($row[$colMap['VARIAN RAM'] ?? 10] ?? ''));
                $storage = trim((string) ($row[$colMap['VARIAN STORAGE'] ?? 11] ?? ''));
                $desc = trim((string) ($row[$colMap['SPESIFIKASI / KETERANGAN'] ?? 12] ?? ''));

                if (empty($name)) {
                    // Try to compose name from category + brand + type
                    if (!empty($brand) && !empty($type)) {
                        $name = trim("{$brand} {$type}");
                    } else {
                        $failed++;
                        $errors[] = "Baris #{$rowNum}: Kolom 'Nama Barang' tidak boleh kosong.";
                        continue;
                    }
                }

                // Resolve category ID
                if (empty($categoryName)) {
                    $categoryName = 'Sparepart & Aksesoris';
                }

                $catId = null;
                foreach ($categories as $catTitle => $id) {
                    if (strcasecmp($catTitle, $categoryName) === 0) {
                        $catId = $id;
                        break;
                    }
                }

                if (!$catId) {
                    $newCategory = Category::create([
                        'name' => $categoryName,
                        'slug' => Str::slug($categoryName) . '-' . Str::random(4),
                        'is_active' => true,
                    ]);
                    $catId = $newCategory->id;
                    $categories[$categoryName] = $catId;
                }

                $stock = max(0, (int) preg_replace('/[^\d]/', '', (string) $stockRaw));
                $costPrice = (float) preg_replace('/[^\d.]/', '', str_replace(',', '.', (string) $costRaw));
                $price = (float) preg_replace('/[^\d.]/', '', str_replace(',', '.', (string) $priceRaw));

                $isActive = true;
                $upperStatus = strtoupper($status);
                if (in_array($upperStatus, ['NONAKTIF', 'INACTIVE', 'KELUAR', 'PAIRED', 'HABIS', '0', 'FALSE'])) {
                    // For Dus or items paired/keluar, keep active false if requested
                    $isActive = !in_array($upperStatus, ['NONAKTIF', 'INACTIVE', '0', 'FALSE']);
                }

                // Match existing product by SKU if SKU provided
                $product = null;
                if (!empty($sku)) {
                    $product = Product::where('sku', $sku)->first();
                }

                if (!$product) {
                    // Try matching by exact name & brand
                    $product = Product::where('name', $name)->where('brand', $brand)->first();
                }

                $data = [
                    'name' => $name,
                    'category_id' => $catId,
                    'brand' => !empty($brand) ? $brand : 'General',
                    'type' => !empty($type) ? $type : null,
                    'stock' => $stock,
                    'cost_price' => $costPrice,
                    'price' => $price,
                    'ram' => !empty($ram) ? $ram : null,
                    'storage' => !empty($storage) ? $storage : null,
                    'description' => !empty($desc) ? $desc : null,
                    'is_active' => $isActive,
                ];

                if (!empty($sku)) {
                    $data['sku'] = $sku;
                }

                if ($product) {
                    $product->update($data);
                    $updated++;
                } else {
                    Product::create($data);
                    $created++;
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return [
            'created' => $created,
            'updated' => $updated,
            'failed' => $failed,
            'errors' => $errors,
        ];
    }

    private function writeRow(Worksheet $sheet, array $row, int $rowIdx): void
    {
        foreach ($row as $colIdx => $value) {
            $cell = Coordinate::stringFromColumnIndex($colIdx + 1) . $rowIdx;
            if (is_numeric($value) && !in_array($colIdx, [0, 6])) { // numbers like prices
                $sheet->setCellValueExplicit($cell, (string) $value, DataType::TYPE_NUMERIC);
            } else {
                $sheet->setCellValueExplicit($cell, (string) $value, DataType::TYPE_STRING);
            }
        }
    }

    private function styleHeader(Worksheet $sheet): void
    {
        $lastCol = Coordinate::stringFromColumnIndex(count(self::HEADERS));
        $headerRange = "A1:{$lastCol}1";

        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'DF5E1D'], // ZLM Brand Color
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D0D5DD'],
                ],
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(28);
    }

    private function autoFitColumns(Worksheet $sheet): void
    {
        $colCount = count(self::HEADERS);
        for ($i = 1; $i <= $colCount; $i++) {
            $colLetter = Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }
    }
}
