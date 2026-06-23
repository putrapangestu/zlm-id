# TRX-2B Implementation Plan: RajaOngkir Service Layer

## Effort: Medium

## File Structure
| File | Tipe | Keterangan |
|------|------|------------|
| `config/rajaongkir.php` | BARU | Konfigurasi API key RajaOngkir |
| `app/Services/RajaOngkirService.php` | BARU | Service class untuk komunikasi RajaOngkir API |
| `app/Http/Controllers/ShippingController.php` | BARU | Controller untuk AJAX endpoint shipping |
| `.env` | DIUBAH | Tambah RAJAONGKIR_* environment variables |

## Implementation Order

### Step 1: Tambah `.env`
```
RAJAONGKIR_API_KEY=jYu063WU44d88d3750bc05bdvWbh0F0W
RAJAONGKIR_BASE_URL=https://api.rajaongkir.com/starter
RAJAONGKIR_ORIGIN_CITY_ID=152
```

### Step 2: Buat `config/rajaongkir.php`
- `api_key`, `base_url`, `origin_city_id`, `couriers` (default: ['jne', 'pos', 'tiki'])

### Step 3: Buat `app/Services/RajaOngkirService.php`
- Namespace: `App\Services`
- Methods:
  - `getProvinces(): array` — GET `/province`
    - Header: `key: {api_key}`
    - Return `[{province_id, province}]` dari `rajaongkir.results`
  - `getCities(?int $provinceId = null): array` — GET `/city?province={id}`
    - Return `[{city_id, city_name, type, postal_code}]`
  - `getCost(int $origin, int $destination, int $weight, string $courier): array`
    - POST `/cost` dengan form params
    - Return hasil dari `rajaongkir.results`
- Error handling: cek status code, throw Exception, Log::error
- Gunakan `Illuminate\Support\Facades\Http` untuk HTTP calls

### Step 4: Buat `app/Http/Controllers/ShippingController.php`
- Constructor injection: `RajaOngkirService $rajaOngkir`
- Methods:
  - `provinces()` → return JSON `$rajaOngkir->getProvinces()`
  - `cities(Request $request)` → validasi `province_id`, return JSON
  - `cost(Request $request)` → validasi `destination`, `weight`
    - Loop semua courier, combine hasilnya
    - Return JSON array

### Step 5: Tambah Route ke `routes/web.php`
Di dalam grup `middleware ['auth', 'verified']`:
```php
Route::get('/shipping/provinces', [ShippingController::class, 'provinces'])->name('shipping.provinces');
Route::get('/shipping/cities', [ShippingController::class, 'cities'])->name('shipping.cities');
Route::post('/shipping/cost', [ShippingController::class, 'cost'])->name('shipping.cost');
```

## Dependencies Internal
- Config file → Service → Controller → Routes
- Order model harus sudah punya shipping fields (TRX-1)

## API / Interface
```php
namespace App\Services;

class RajaOngkirService {
    public function getProvinces(): array
    public function getCities(?int $provinceId = null): array
    public function getCost(int $origin, int $destination, int $weight, string $courier): array
}
```

```php
namespace App\Http\Controllers;

class ShippingController {
    public function provinces()  // GET → JSON
    public function cities(Request $request)  // GET ?province_id= → JSON
    public function cost(Request $request)  // POST {destination, weight} → JSON
}
```

## Data Flow
```
Browser (Alpine.js checkout)
    ↓ GET /shipping/provinces
    ↓ GET /shipping/cities?province_id=X
    ↓ POST /shipping/cost {destination, weight}
ShippingController (proxy)
    ↓
RajaOngkirService
    ↓ HTTP GET/POST
RajaOngkir API
    ↓ Response
Browser → display shipping options
```

## Test Plan
- Test config: `config('rajaongkir.api_key')` return value
- Test `RajaOngkirService` dengan mock HTTP:
  - Mock response provinces → array terformat
  - Mock response cities → array terformat  
  - Mock response cost → array dengan courier + costs
- Test error: mock failed response → exception
- Test ShippingController endpoints via HTTP test
- Integration test: GET `/shipping/provinces` → return JSON 200
