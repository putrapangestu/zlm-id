# TRX-2B: RajaOngkir Service Layer

## Tujuan
Membuat service class untuk berkomunikasi dengan RajaOngkir API guna menghitung ongkos kirim.

## File Baru
- `app/Services/RajaOngkirService.php`
- `config/rajaongkir.php`

## File Diubah
- `.env` — tambah konfigurasi RajaOngkir

## Detail Implementasi

### 1. Config: `config/rajaongkir.php`
```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | RajaOngkir API Configuration
    |--------------------------------------------------------------------------
    |
    | Base URL untuk Starter: https://api.rajaongkir.com/starter
    | Courier tersedia: jne, pos, tiki
    |
    */
    
    'api_key' => env('RAJAONGKIR_API_KEY'),
    'base_url' => env('RAJAONGKIR_BASE_URL', 'https://api.rajaongkir.com/starter'),
    
    /*
    | Origin city ID untuk perhitungan ongkos kirim.
    | Contoh: 152 = Kota Jakarta Pusat
    | Sesuaikan dengan lokasi toko/warehouse.
    */
    'origin_city_id' => env('RAJAONGKIR_ORIGIN_CITY_ID', 152),
    
    /*
    | Daftar kurir yang digunakan
    */
    'couriers' => ['jne', 'pos', 'tiki'],
];
```

### 2. .env additions
```
RAJAONGKIR_API_KEY=jYu063WU44d88d3750bc05bdvWbh0F0W
RAJAONGKIR_BASE_URL=https://api.rajaongkir.com/starter
RAJAONGKIR_ORIGIN_CITY_ID=152
```

### 3. RajaOngkirService Methods

#### `getProvinces(): array`
Get daftar semua provinsi Indonesia.

**Endpoint**: `GET https://api.rajaongkir.com/starter/province`

**Headers**: `key: {api_key}`

**Response Format** (RajaOngkir):
```json
{
  "rajaongkir": {
    "status": { "code": 200, "description": "OK" },
    "results": [
      { "province_id": "1", "province": "Bali" },
      { "province_id": "2", "province": "Bangka Belitung" }
    ]
  }
}
```

**Return**: collection provinsi `[{province_id, province}]`

#### `getCities(?int $provinceId = null): array`
Get daftar kota. Optional filter by province.

**Endpoint**: `GET https://api.rajaongkir.com/starter/city?province={id}`

**Response Format**:
```json
{
  "rajaongkir": {
    "results": [
      { "city_id": "1", "province_id": "21", "city_name": "Aceh Barat", "postal_code": "23681", "type": "Kabupaten" }
    ]
  }
}
```

**Return**: collection kota `[{city_id, city_name, type, postal_code}]`

#### `getCost(int $origin, int $destination, int $weight, string $courier): array`
Hitung ongkos kirim dari origin ke destination dengan berat tertentu.

**Endpoint**: `POST https://api.rajaongkir.com/starter/cost`

**Headers**: `key: {api_key}`, `Content-Type: application/x-www-form-urlencoded`

**Body** (form params):
```
origin={origin_city_id}
destination={destination_city_id}
weight={total_weight_in_grams}
courier={jne|pos|tiki}
```

**Response Format**:
```json
{
  "rajaongkir": {
    "status": { "code": 200 },
    "origin_details": { "city_id": "152", "city_name": "Jakarta Pusat" },
    "destination_details": { "city_id": "23", "city_name": "Bandung" },
    "results": [
      {
        "code": "jne",
        "name": "JNE",
        "costs": [
          {
            "service": "REG",
            "description": "Layanan Reguler",
            "cost": [{ "value": 15000, "etd": "2-3", "note": "" }]
          },
          {
            "service": "OKE",
            "description": "Ongkos Kirim Ekonomis",
            "cost": [{ "value": 10000, "etd": "3-5", "note": "" }]
          }
        ]
      }
    ]
  }
}
```

**Return**: array berisi daftar kurir + layanan + biaya + estimasi

### 4. Error Handling
```php
try {
    $response = Http::withHeaders([
        'key' => config('rajaongkir.api_key'),
    ])->post(config('rajaongkir.base_url') . '/cost', [
        'origin' => $origin,
        'destination' => $destination,
        'weight' => $weight,
        'courier' => $courier,
    ]);
    
    $body = $response->json();
    
    if ($response->failed() || ($body['rajaongkir']['status']['code'] ?? 500) !== 200) {
        throw new \Exception($body['rajaongkir']['status']['description'] ?? 'RajaOngkir API Error');
    }
    
    return $body['rajaongkir']['results'] ?? [];
} catch (\Exception $e) {
    Log::error('RajaOngkir API Error:', ['message' => $e->getMessage()]);
    throw $e;
}
```

### 5. Controller Endpoints untuk AJAX Checkout
Untuk mendukung interaktif di halaman checkout, perlu 3 endpoint di controller:

**File baru/Diubah**: `app/Http/Controllers/ShippingController.php` (BARU)

```php
Route::get('/shipping/provinces', [ShippingController::class, 'provinces'])->name('shipping.provinces');
Route::get('/shipping/cities', [ShippingController::class, 'cities'])->name('shipping.cities');
Route::post('/shipping/cost', [ShippingController::class, 'cost'])->name('shipping.cost');
```

#### ShippingController methods:

**provinces()**: Proxy ke RajaOngkir getProvinces(), return JSON
**cities(Request $request)**: Proxy ke RajaOngkir getCities($request->province_id), return JSON
**cost(Request $request)**: Validasi `destination`, panggil getCost untuk semua courier, return kombinasi

### Definisi Selesai
- [ ] `config/rajaongkir.php` bisa diakses
- [ ] `RajaOngkirService::getProvinces()` return data provinsi
- [ ] `RajaOngkirService::getCities()` return data kota
- [ ] `RajaOngkirService::getCost()` return ongkos kirim
- [ ] `ShippingController` endpoints siap untuk AJAX checkout
- [ ] Error handling untuk API failure
- [ ] Response caching (opsional, untuk mempercepat)
