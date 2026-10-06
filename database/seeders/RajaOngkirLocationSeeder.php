<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class RajaOngkirLocationSeeder extends Seeder
{
    public function run(): void
    {
        $provinceSource = $this->readDump('provinces');
        $citySource = $this->readDump('cities');
        $subdistrictSource = $this->readDump('subdistricts');

        $provinceIds = [];
        $provinces = [];
        foreach ($provinceSource as $row) {
            $localId = (int) $this->requiredValue($row, 'id', 'provinces');
            $rajaOngkirId = $this->nullableInteger($row['id_rajaongkir'] ?? null);
            $provinceIds[(string) $localId] = $localId;
            $provinces[] = [
                'id' => $localId,
                'id_rajaongkir' => $rajaOngkirId,
                'name' => $this->requiredValue($row, 'name', 'provinces'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $cityIdsBySourceId = [];
        $cities = [];
        foreach ($citySource as $row) {
            $localId = (int) $this->requiredValue($row, 'id', 'cities');
            $sourceProvinceId = (string) $this->requiredValue($row, 'province_id', 'cities');
            $provinceLocalId = $provinceIds[$sourceProvinceId] ?? null;
            if ($provinceLocalId === null) {
                throw new RuntimeException("City row {$localId} references an unknown source province ID {$sourceProvinceId}.");
            }

            $rajaOngkirId = $this->nullableInteger($row['id_rajaongkir'] ?? null);
            $cityIdsBySourceId[(string) $localId] = $localId;

            $cities[] = [
                'id' => $localId,
                'province_id' => $provinceLocalId,
                'id_rajaongkir' => $rajaOngkirId,
                'name' => $this->requiredValue($row, 'name', 'cities'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $subdistricts = [];
        foreach ($subdistrictSource as $row) {
            $localId = (int) $this->requiredValue($row, 'id', 'subdistricts');
            $sourceCityId = (string) $this->requiredValue($row, 'city_id', 'subdistricts');
            $cityLocalId = $cityIdsBySourceId[$sourceCityId] ?? null;
            if ($cityLocalId === null) {
                throw new RuntimeException("Subdistrict row {$localId} references an unknown local city ID {$sourceCityId}.");
            }

            $subdistricts[] = [
                'id' => $localId,
                'city_id' => $cityLocalId,
                'id_rajaongkir' => $this->nullableInteger($row['id_rajaongkir'] ?? null),
                'name' => $this->requiredValue($row, 'name', 'subdistricts'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::transaction(function () use ($provinces, $cities, $subdistricts): void {
            $this->upsertRows('provinces', $provinces, ['id_rajaongkir', 'name', 'updated_at']);
            $this->upsertRows('cities', $cities, ['province_id', 'id_rajaongkir', 'name', 'updated_at']);
            $this->upsertRows('subdistricts', $subdistricts, ['city_id', 'id_rajaongkir', 'name', 'updated_at']);
        });

        $this->command?->info(sprintf(
            'Imported %d provinces, %d cities, and %d subdistricts with local IDs and RajaOngkir IDs.',
            count($provinces),
            count($cities),
            count($subdistricts)
        ));
    }

    private function readDump(string $table): array
    {
        $path = database_path("{$table}.sql");
        if (! is_file($path)) {
            throw new RuntimeException("Required RajaOngkir location dump is missing: {$path}");
        }

        $sql = file_get_contents($path);
        if ($sql === false) {
            throw new RuntimeException("Could not read RajaOngkir location dump: {$path}");
        }

        $pattern = '/INSERT INTO `'.preg_quote($table, '/').'` \(([^)]*)\) VALUES\s*/';
        preg_match_all($pattern, $sql, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);
        if ($matches === []) {
            throw new RuntimeException("No INSERT data for `{$table}` was found in {$path}.");
        }

        $rows = [];
        foreach ($matches as $match) {
            preg_match_all('/`([^`]+)`/', $match[1][0], $columnMatches);
            $columns = $columnMatches[1];
            $position = $match[0][1] + strlen($match[0][0]);
            array_push($rows, ...$this->parseValueRows($sql, $position, $columns, $table));
        }

        return $rows;
    }

    private function parseValueRows(string $sql, int $position, array $columns, string $table): array
    {
        $length = strlen($sql);
        $rows = [];

        while ($position < $length) {
            while ($position < $length && (ctype_space($sql[$position]) || $sql[$position] === ',')) {
                $position++;
            }

            if ($position >= $length || $sql[$position] === ';') {
                break;
            }
            if ($sql[$position] !== '(') {
                throw new RuntimeException("Unexpected SQL data while reading `{$table}` dump.");
            }
            $position++;

            $values = [];
            while (true) {
                while ($position < $length && ctype_space($sql[$position])) {
                    $position++;
                }

                if ($position >= $length) {
                    throw new RuntimeException("Unexpected end of `{$table}` SQL dump.");
                }

                if ($sql[$position] === "'") {
                    $values[] = $this->parseSqlString($sql, $position, $table);
                } else {
                    $start = $position;
                    while ($position < $length && ! in_array($sql[$position], [',', ')'], true)) {
                        $position++;
                    }
                    $value = trim(substr($sql, $start, $position - $start));
                    $values[] = strtoupper($value) === 'NULL' ? null : $value;
                }

                while ($position < $length && ctype_space($sql[$position])) {
                    $position++;
                }

                if ($position < $length && $sql[$position] === ',') {
                    $position++;

                    continue;
                }
                if ($position < $length && $sql[$position] === ')') {
                    $position++;
                    break;
                }

                throw new RuntimeException("Malformed value row in `{$table}` SQL dump.");
            }

            if (count($values) !== count($columns)) {
                throw new RuntimeException("Column/value count mismatch in `{$table}` SQL dump.");
            }
            $rows[] = array_combine($columns, $values);
        }

        return $rows;
    }

    private function parseSqlString(string $sql, int &$position, string $table): string
    {
        $length = strlen($sql);
        $position++;
        $value = '';

        while ($position < $length) {
            $character = $sql[$position++];
            if ($character === '\\') {
                if ($position >= $length) {
                    break;
                }
                $escaped = $sql[$position++];
                $value .= match ($escaped) {
                    '0' => "\0",
                    'b' => "\x08",
                    'n' => "\n",
                    'r' => "\r",
                    't' => "\t",
                    'Z' => "\x1a",
                    default => $escaped,
                };

                continue;
            }
            if ($character === "'") {
                if ($position < $length && $sql[$position] === "'") {
                    $value .= "'";
                    $position++;

                    continue;
                }

                return $value;
            }
            $value .= $character;
        }

        throw new RuntimeException("Unterminated string in `{$table}` SQL dump.");
    }

    private function requiredValue(array $row, string $column, string $table): string
    {
        $value = $row[$column] ?? null;
        if ($value === null || $value === '') {
            throw new RuntimeException("Required `{$column}` is missing in `{$table}` SQL dump.");
        }

        return (string) $value;
    }

    private function nullableInteger(mixed $value): ?int
    {
        return $value === null || $value === '' ? null : (int) $value;
    }

    private function upsertRows(string $table, array $rows, array $updateColumns): void
    {
        foreach (array_chunk($rows, 100) as $chunk) {
            DB::table($table)->upsert($chunk, ['id'], $updateColumns);
        }
    }
}
