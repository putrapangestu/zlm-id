<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Laptop;
use App\Models\ProductItem;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaptopCatalogSpreadsheetService
{
    private const HEADERS = [
        'NO',
        'TANGGAL',
        'LAPTOP',
        'MERK',
        'TYPE',
        'PROCESSOR',
        'VGA',
        'STORAGE',
        'RAM',
        'LAYAR',
        'WARNA',
        'KELENGKAPAN',
        'KONDISI',
        'FISIK',
        'MASA GARANSI',
        'SERIAL NUMBER',
        'SKU',
        'MODAL LAPTOP',
        'DUS',
        'RAM TAMBAHAN',
        'STORAGE TAMBAHAN',
        'SPARE PART',
        'LAIN-LAIN',
        'TOTAL MODAL',
        'HARGA JUAL',
        'STATUS QC',
        'STATUS UNIT',
    ];

    private const SHEETS = [
        'Produk Ready' => 'ready',
        'Produk Belum Ready' => 'not_ready',
        'Produk Habis' => 'sold',
    ];

    public function makeSpreadsheet(bool $withData = true): Spreadsheet
    {
        $spreadsheet = new Spreadsheet;
        $spreadsheet->removeSheetByIndex(0);
        $itemsByStatus = [];

        if ($withData) {
            $itemsByStatus = ProductItem::query()
                ->with('laptop')
                ->whereHas('laptop')
                ->get()
                ->groupBy(fn (ProductItem $item) => $this->itemStatus($item));
        }

        foreach (self::SHEETS as $title => $status) {
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle($title);
            $sheet->fromArray(self::HEADERS, null, 'A1');
            $this->styleSheet($sheet);

            if ($withData) {
                foreach ($itemsByStatus->get($status, collect())->values() as $index => $item) {
                    foreach ($this->exportRow($item, $index + 1) as $column => $value) {
                        $cell = Coordinate::stringFromColumnIndex($column + 1).($index + 2);
                        if (is_string($value)) {
                            $sheet->setCellValueExplicit($cell, $value, DataType::TYPE_STRING);
                        } else {
                            $sheet->setCellValue($cell, $value);
                        }
                    }
                }
            }
        }

        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    /**
     * Import unit rows, upserting by SKU or serial number and keeping stock counters in sync.
     *
     * @return array{created: int, updated: int}
     */
    public function import(string $path, ?string $userId): array
    {
        $spreadsheet = IOFactory::load($path);
        $rows = $this->readRows($spreadsheet);

        if ($rows === []) {
            throw ValidationException::withMessages(['file' => 'Tidak ada data laptop yang dapat diimpor.']);
        }

        $identities = [];
        $errors = [];
        foreach ($rows as $row) {
            if ($row['sku'] === '' && $row['serial_number'] === '') {
                $errors[] = "{$row['sheet']} baris {$row['row_number']}: SKU atau serial number wajib diisi agar impor ulang tidak menggandakan unit.";

                continue;
            }

            foreach (['sku', 'serial_number'] as $key) {
                if ($row[$key] === '') {
                    continue;
                }
                $identity = $key.':'.mb_strtolower($row[$key]);
                if (isset($identities[$identity])) {
                    $errors[] = "{$row['sheet']} baris {$row['row_number']}: {$key} {$row[$key]} tercantum lebih dari sekali.";
                } else {
                    $identities[$identity] = true;
                }
            }

            if ($row['name'] === '') {
                $errors[] = "{$row['sheet']} baris {$row['row_number']}: nama laptop wajib diisi.";
            }
            if ($row['brand'] === '') {
                $errors[] = "{$row['sheet']} baris {$row['row_number']}: merk wajib diisi.";
            }
            if ($row['price'] === null || $row['price'] < 0) {
                $errors[] = "{$row['sheet']} baris {$row['row_number']}: harga jual wajib berupa angka.";
            }
        }

        if ($errors !== []) {
            throw ValidationException::withMessages(['file' => $errors]);
        }

        return DB::transaction(function () use ($rows, $userId): array {
            $created = 0;
            $updated = 0;
            $stockChanges = [];

            foreach ($rows as $row) {
                $item = $this->findExistingItem($row);
                $oldLaptop = $item ? Laptop::query()->whereKey($item->laptop_id)->lockForUpdate()->first() : null;
                $oldStatus = $item ? $this->itemStatus($item) : null;
                $laptop = $this->findOrCreateLaptop($row, $oldLaptop);
                $qcStatus = $this->qcStatusForSheetStatus($row['status']);

                if ($oldLaptop && $oldStatus) {
                    $this->addStockChange($stockChanges, $oldLaptop, $oldStatus, $item->qc_status, -1);
                }

                $attributes = [
                    'laptop_id' => $laptop->id,
                    'sku' => $row['sku'] !== '' ? $row['sku'] : null,
                    'serial_number' => $row['serial_number'] !== '' ? $row['serial_number'] : null,
                    'received_specs' => array_merge($item?->received_specs ?? [], $row['received_specs']),
                    'base_cost' => $row['base_cost'],
                    'additional_cost' => $row['additional_cost'],
                    'final_cost' => $row['final_cost'],
                    'qc_status' => $qcStatus,
                    'is_sold' => $row['status'] === 'sold',
                ];

                if ($item) {
                    $item->update($attributes);
                    $updated++;
                } else {
                    $item = ProductItem::create($attributes);
                    $created++;
                }

                $this->addStockChange($stockChanges, $laptop, $row['status'], $qcStatus, 1);
            }

            foreach ($stockChanges as $laptopId => $change) {
                $laptop = Laptop::query()->whereKey($laptopId)->lockForUpdate()->first();
                if (! $laptop) {
                    continue;
                }

                $stockBefore = (int) ($laptop->stock ?? 0);
                $stockAfter = max(0, $stockBefore + $change['ready']);
                $laptop->update([
                    'stock' => $stockAfter,
                    'qc_passed_stock' => max(0, (int) ($laptop->qc_passed_stock ?? 0) + $change['ready']),
                    'uninspected_stock' => max(0, (int) ($laptop->uninspected_stock ?? 0) + $change['pending']),
                ]);

                if ($stockAfter !== $stockBefore) {
                    StockMovement::create([
                        'laptop_id' => $laptop->id,
                        'type' => 'ADJUSTMENT',
                        'quantity' => $stockAfter - $stockBefore,
                        'stock_before' => $stockBefore,
                        'stock_after' => $stockAfter,
                        'notes' => 'Perubahan stok melalui impor katalog laptop.',
                        'user_id' => $userId,
                    ]);
                }
            }

            return compact('created', 'updated');
        });
    }

    private function readRows(Spreadsheet $spreadsheet): array
    {
        $rows = [];
        $errors = [];
        $multipleSheets = $spreadsheet->getSheetCount() > 1;

        foreach ($spreadsheet->getAllSheets() as $sheet) {
            $headerRow = $this->findHeaderRow($sheet);
            if ($headerRow === null) {
                $errors[] = "Sheet {$sheet->getTitle()}: header LAPTOP dan MERK tidak ditemukan.";

                continue;
            }

            $sheetStatus = $this->sheetStatus($sheet->getTitle(), $multipleSheets);
            if ($sheetStatus === null) {
                $errors[] = "Sheet {$sheet->getTitle()}: gunakan nama Produk Ready, Produk Belum Ready, atau Produk Habis.";

                continue;
            }

            $headers = $this->mapHeaders($sheet, $headerRow);
            if ($sheet->getHighestDataRow() <= $headerRow) {
                continue;
            }

            foreach (range($headerRow + 1, $sheet->getHighestDataRow()) as $rowNumber) {
                $nameValue = trim((string) $sheet->getCell([$headers['name'], $rowNumber])->getValue());
                if (str_starts_with(strtoupper($nameValue), 'COLUMN')) {
                    continue;
                }

                $row = $this->readRow($sheet, $headers, $rowNumber, $sheetStatus);
                if ($row['name'] === '' && $row['sku'] === '' && $row['serial_number'] === '') {
                    continue;
                }

                if (count($rows) >= 5000) {
                    $errors[] = 'Maksimal 5.000 unit dapat diimpor sekaligus.';
                    break 2;
                }

                $row['sheet'] = $sheet->getTitle();
                $row['row_number'] = $rowNumber;
                $rows[] = $row;
            }
        }

        if ($errors !== []) {
            throw ValidationException::withMessages(['file' => $errors]);
        }

        return $rows;
    }

    private function findHeaderRow(Worksheet $sheet): ?int
    {
        for ($row = 1; $row <= min(10, $sheet->getHighestDataRow()); $row++) {
            $values = [];
            $lastColumn = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());
            for ($column = 1; $column <= $lastColumn; $column++) {
                $values[] = $this->normalizeHeader($sheet->getCell([$column, $row])->getValue());
            }

            if (in_array('LAPTOP', $values, true) && (in_array('MERK', $values, true) || in_array('MEREK', $values, true) || in_array('BRAND', $values, true))) {
                return $row;
            }
        }

        return null;
    }

    private function mapHeaders(Worksheet $sheet, int $headerRow): array
    {
        $aliases = [
            'name' => ['LAPTOP', 'NAMA LAPTOP', 'PRODUCT'],
            'brand' => ['MERK', 'MEREK', 'BRAND'],
            'type' => ['TYPE', 'TIPE'],
            'processor' => ['PROCESSOR', 'PROSESOR', 'CPU'],
            'graphics' => ['VGA', 'GRAPHICS', 'GPU'],
            'storage' => ['STORAGE', 'PENYIMPANAN'],
            'ram' => ['RAM'],
            'display' => ['LAYAR', 'DISPLAY'],
            'color' => ['WARNA', 'COLOR'],
            'completeness' => ['KELENGKAPAN', 'ACCESSORIES', 'AKSESORIS'],
            'condition' => ['KONDISI', 'CONDITION'],
            'physical_grade' => ['FISIK', 'KONDISI FISIK'],
            'warranty' => ['MASA GARANSI', 'GARANSI', 'WARRANTY'],
            'serial_number' => ['SERIAL NUMBER', 'SERIAL', 'SN'],
            'sku' => ['SKU', 'KODE SKU'],
            'base_cost' => ['MODAL LAPTOP', 'MODAL', 'HARGA MODAL'],
            'box_cost' => ['DUS', 'BIAYA DUS'],
            'ram_cost' => ['RAM TAMBAHAN', 'BIAYA RAM'],
            'storage_cost' => ['STORAGE TAMBAHAN', 'BIAYA STORAGE'],
            'spare_cost' => ['SPARE PART', 'BIAYA SPARE PART'],
            'other_cost' => ['LAIN-LAIN', 'LAIN LAIN', 'BIAYA LAIN'],
            'final_cost' => ['TOTAL MODAL', 'TOTAL HPP'],
            'price' => ['HARGA JUAL', 'PRICE'],
            'purchase_date' => ['TANGGAL', 'TANGGAL BELI', 'PURCHASE DATE'],
            'qc_status' => ['STATUS QC'],
            'unit_status' => ['STATUS UNIT', 'STATUS'],
        ];
        $normalizedAliases = [];
        foreach ($aliases as $field => $fieldAliases) {
            foreach ($fieldAliases as $alias) {
                $normalizedAliases[$this->normalizeHeader($alias)] = $field;
            }
        }

        $headers = [];
        $lastColumn = Coordinate::columnIndexFromString($sheet->getHighestDataColumn());
        for ($column = 1; $column <= $lastColumn; $column++) {
            $normalized = $this->normalizeHeader($sheet->getCell([$column, $headerRow])->getValue());
            if (isset($normalizedAliases[$normalized])) {
                $headers[$normalizedAliases[$normalized]] = $column;
            }
        }

        return $headers;
    }

    private function readRow(Worksheet $sheet, array $headers, int $rowNumber, string $sheetStatus): array
    {
        $value = fn (string $field) => isset($headers[$field])
            ? $sheet->getCell([$headers[$field], $rowNumber])->getValue()
            : null;

        $status = $sheetStatus;
        if ($sheet->getParent()?->getSheetCount() === 1 && $value('unit_status') !== null) {
            $status = $this->statusFromLabel((string) $value('unit_status')) ?? $status;
        }

        $baseCost = $this->parseMoney($value('base_cost')) ?? 0;
        $itemCosts = [
            'box_cost' => $this->parseMoney($value('box_cost')) ?? 0,
            'ram_cost' => $this->parseMoney($value('ram_cost')) ?? 0,
            'storage_cost' => $this->parseMoney($value('storage_cost')) ?? 0,
            'spare_cost' => $this->parseMoney($value('spare_cost')) ?? 0,
            'other_cost' => $this->parseMoney($value('other_cost')) ?? 0,
        ];
        $additionalCost = array_sum($itemCosts);
        $finalCost = $this->parseMoney($value('final_cost')) ?? ($baseCost + $additionalCost);
        if ($additionalCost === 0 && $finalCost > $baseCost) {
            $additionalCost = $finalCost - $baseCost;
        }

        $purchaseDate = $this->parseDate($value('purchase_date'));
        if ($value('purchase_date') !== null && $value('purchase_date') !== '' && $purchaseDate === null) {
            throw ValidationException::withMessages([
                'file' => "{$sheet->getTitle()} baris {$rowNumber}: format tanggal tidak valid.",
            ]);
        }

        $receivedSpecs = array_filter([
            'type' => $this->stringValue($value('type')),
            'completeness' => $this->stringValue($value('completeness')),
            'condition' => $this->stringValue($value('condition')),
            'physical_grade' => $this->stringValue($value('physical_grade')),
            'color' => $this->stringValue($value('color')),
            'warranty' => $this->stringValue($value('warranty')),
            'purchase_date' => $purchaseDate,
            'item_costs' => array_filter($itemCosts, fn ($cost) => $cost !== 0),
        ], fn ($entry) => $entry !== null && $entry !== []);

        return [
            'name' => trim((string) ($value('name') ?? '')),
            'brand' => trim((string) ($value('brand') ?? '')),
            'sku' => trim((string) ($value('sku') ?? '')),
            'serial_number' => trim((string) ($value('serial_number') ?? '')),
            'processor' => $this->stringValue($value('processor')),
            'graphics' => $this->stringValue($value('graphics')),
            'storage' => $this->stringValue($value('storage')),
            'ram' => $this->stringValue($value('ram')),
            'display' => $this->stringValue($value('display')),
            'color' => $this->stringValue($value('color')),
            'warranty' => $this->stringValue($value('warranty')),
            'price' => $this->parseMoney($value('price')),
            'base_cost' => $baseCost,
            'additional_cost' => $additionalCost,
            'final_cost' => $finalCost,
            'status' => $status,
            'received_specs' => $receivedSpecs,
        ];
    }

    private function findExistingItem(array $row): ?ProductItem
    {
        $bySku = $row['sku'] !== ''
            ? ProductItem::query()->where('sku', $row['sku'])->lockForUpdate()->first()
            : null;
        $bySerial = $row['serial_number'] !== ''
            ? ProductItem::query()->where('serial_number', $row['serial_number'])->lockForUpdate()->first()
            : null;

        if ($bySku && $bySerial && $bySku->id !== $bySerial->id) {
            throw ValidationException::withMessages([
                'file' => "{$row['sheet']} baris {$row['row_number']}: SKU dan serial number menunjuk ke unit yang berbeda.",
            ]);
        }

        return $bySku ?? $bySerial;
    }

    private function findOrCreateLaptop(array $row, ?Laptop $existingLaptop): Laptop
    {
        $laptop = $existingLaptop ?? Laptop::query()
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($row['name'])])
            ->whereRaw('LOWER(COALESCE(brand, \'\')) = ?', [mb_strtolower($row['brand'])])
            ->whereRaw('LOWER(COALESCE(processor, \'\')) = ?', [mb_strtolower($row['processor'] ?? '')])
            ->whereRaw('LOWER(COALESCE(ram, \'\')) = ?', [mb_strtolower($row['ram'] ?? '')])
            ->whereRaw('LOWER(COALESCE(storage, \'\')) = ?', [mb_strtolower($row['storage'] ?? '')])
            ->whereRaw('LOWER(COALESCE(graphics, \'\')) = ?', [mb_strtolower($row['graphics'] ?? '')])
            ->whereRaw('LOWER(COALESCE(display, \'\')) = ?', [mb_strtolower($row['display'] ?? '')])
            ->lockForUpdate()
            ->first();

        $brand = Brand::query()->firstOrCreate(
            ['name' => $row['brand']],
            ['slug' => Str::slug($row['brand']), 'is_active' => true],
        );

        $attributes = [
            'name' => $row['name'],
            'brand' => $brand->name,
            'brand_id' => $brand->id,
            'price' => $row['price'],
            'processor' => $row['processor'],
            'ram' => $row['ram'],
            'storage' => $row['storage'],
            'graphics' => $row['graphics'],
            'display' => $row['display'],
        ];

        if ($laptop) {
            $laptop->update(array_filter($attributes, fn ($value) => $value !== null));

            return $laptop->fresh();
        }

        return Laptop::create(array_merge([
            'description' => 'Data katalog diimpor dari file stok laptop.',
            'color' => $row['color'],
            'warranty' => $row['warranty'],
        ], $attributes));
    }

    private function addStockChange(array &$changes, Laptop $laptop, string $status, string $qcStatus, int $amount): void
    {
        $changes[$laptop->id] ??= ['ready' => 0, 'pending' => 0];
        if ($status === 'ready') {
            $changes[$laptop->id]['ready'] += $amount;
        }
        if ($qcStatus === 'pending') {
            $changes[$laptop->id]['pending'] += $amount;
        }
    }

    private function qcStatusForSheetStatus(string $status): string
    {
        return match ($status) {
            'ready' => 'passed',
            'sold' => 'sold',
            default => 'pending',
        };
    }

    private function itemStatus(ProductItem $item): string
    {
        if (in_array($item->qc_status, ['pending', 'failed', 'returned'], true)) {
            return 'not_ready';
        }
        if ($item->qc_status === 'sold' || $item->is_sold) {
            return 'sold';
        }

        return $item->qc_status === 'passed' ? 'ready' : 'not_ready';
    }

    private function sheetStatus(string $title, bool $multipleSheets): ?string
    {
        $title = $this->normalizeHeader($title);
        if (str_contains($title, 'BELUM') || str_contains($title, 'NOT READY')) {
            return 'not_ready';
        }
        if (str_contains($title, 'HABIS') || str_contains($title, 'SOLD')) {
            return 'sold';
        }
        if (str_contains($title, 'READY')) {
            return 'ready';
        }

        return $multipleSheets ? null : 'ready';
    }

    private function statusFromLabel(string $label): ?string
    {
        $label = $this->normalizeHeader($label);
        if (str_contains($label, 'BELUM') || str_contains($label, 'PENDING') || str_contains($label, 'NOT READY')) {
            return 'not_ready';
        }
        if (str_contains($label, 'HABIS') || str_contains($label, 'SOLD') || str_contains($label, 'TERJUAL')) {
            return 'sold';
        }
        if (str_contains($label, 'READY') || str_contains($label, 'SIAP') || str_contains($label, 'PASSED') || str_contains($label, 'LOLOS')) {
            return 'ready';
        }
        if (str_contains($label, 'QC')) {
            return 'not_ready';
        }

        return null;
    }

    private function exportRow(ProductItem $item, int $number): array
    {
        $laptop = $item->laptop;
        $specs = $item->received_specs ?? [];
        $itemCosts = $specs['item_costs'] ?? [];
        $status = $this->itemStatus($item);

        return [
            $number,
            $specs['purchase_date'] ?? $item->created_at?->format('Y-m-d'),
            $laptop->name,
            $laptop->brand,
            $specs['type'] ?? null,
            $laptop->processor,
            $laptop->graphics,
            $laptop->storage,
            $laptop->ram,
            $laptop->display,
            $specs['color'] ?? $laptop->color,
            $specs['completeness'] ?? null,
            $specs['condition'] ?? null,
            $specs['physical_grade'] ?? null,
            $specs['warranty'] ?? $laptop->warranty,
            $item->serial_number,
            $item->sku,
            (float) $item->base_cost,
            (float) ($itemCosts['box_cost'] ?? 0),
            (float) ($itemCosts['ram_cost'] ?? 0),
            (float) ($itemCosts['storage_cost'] ?? 0),
            (float) ($itemCosts['spare_cost'] ?? 0),
            (float) ($itemCosts['other_cost'] ?? 0),
            (float) $item->final_cost,
            (float) $laptop->price,
            $item->qc_status,
            match ($status) {
                'ready' => 'Ready',
                'sold' => 'Habis',
                default => 'Belum Ready',
            },
        ];
    }

    private function styleSheet(Worksheet $sheet): void
    {
        $sheet->freezePane('A2');
        $sheet->setAutoFilter('A1:'.Coordinate::stringFromColumnIndex(count(self::HEADERS)).'1');
        $sheet->getStyle('A1:'.Coordinate::stringFromColumnIndex(count(self::HEADERS)).'1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DF5E1D']],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(24);
        foreach (range(1, count(self::HEADERS)) as $column) {
            $sheet->getColumnDimension(Coordinate::stringFromColumnIndex($column))->setAutoSize(true);
        }
    }

    private function normalizeHeader(mixed $value): string
    {
        return trim((string) preg_replace('/[^A-Z0-9]+/', ' ', Str::ascii(Str::upper((string) $value))));
    }

    private function stringValue(mixed $value): ?string
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        return trim((string) $value);
    }

    private function parseMoney(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_numeric($value)) {
            return (float) $value;
        }

        $digits = preg_replace('/[^0-9-]/', '', (string) $value);

        return $digits === '' || $digits === '-' ? null : (float) $digits;
    }

    private function parseDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (is_numeric($value)) {
            return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
        }

        $timestamp = strtotime((string) $value);

        return $timestamp === false ? null : date('Y-m-d', $timestamp);
    }
}
