# TRX-10 Implementation Plan: Configurable Tax Rate (Admin Settings)

## Effort: Medium

## File Structure
| File | Tipe | Keterangan |
|------|------|------------|
| `database/migrations/2026_06_04_100002_create_settings_table.php` | BARU | Migration table settings |
| `app/Models/Setting.php` | BARU | Model key-value settings |
| `database/seeders/SettingsSeeder.php` | BARU | Seeder default settings |
| `app/Http/Controllers/Admin/SettingController.php` | BARU | Controller CRUD settings |
| `resources/views/admin/settings/index.blade.php` | BARU | View form settings |
| `app/Providers/AppServiceProvider.php` | DIUBAH | Load settings ke config di boot |
| `app/Http/Controllers/OrderController.php` | DIUBAH | Ganti 0.11 dengan config |
| `app/Http/Controllers/Admin/TransactionController.php` | DIUBAH | Ganti 0.11 dengan config |
| `resources/views/orders/checkout.blade.php` | DIUBAH | Ganti "Tax (11%)" dengan config |
| `resources/views/layouts/admin.blade.php` | DIUBAH | Tambah menu Settings di sidebar |
| `routes/web.php` | DIUBAH | Tambah route admin settings |
| `composer.json` | DIUBAH | Auto-load helpers.php jika perlu |

## Implementation Order

### Step 1: Buat Migration `create_settings_table`
- `Schema::create('settings', ...)`
- Columns: `key` (string, primary), `value` (text, nullable), `timestamps()`

### Step 2: Buat Model `Setting.php`
- `$primaryKey = 'key'`, `$incrementing = false`, `$keyType = 'string'`
- `$fillable = ['key', 'value']`
- Static helpers:
  - `getValue(string $key, mixed $default = null): mixed`
  - `setValue(string $key, mixed $value): void`

### Step 3: Buat `SettingsSeeder`
- Default keys: `tax_rate => 11`, `store_name => ZLM.ID`, `bank_name => BCA`, `bank_account => 123-456-7890`, `bank_holder => PT ZLM ID`
- Gunakan `firstOrCreate`
- Registrasi di `DatabaseSeeder.php` jika perlu

### Step 4: Update `AppServiceProvider::boot()`
- Load semua settings: `Setting::pluck('value', 'key')`
- Set ke `config(['settings' => $settings])`
- Handle exception jika table belum exist (fresh migration)
- Skip saat running console commands (kecuali unit tests)

### Step 5: Buat Helper Functions (optional)
- Jika file `app/helpers.php` belum ada, buat:
  ```php
  function taxRate(): float { return (float) (config('settings.tax_rate', 11)); }
  function calculateTax(float $amount): float { return round($amount * taxRate() / 100, 2); }
  ```
- Register auto-load di `composer.json` `autoload.files` jika perlu

### Step 6: Replace Hardcoded 0.11 di OrderController
- `placeOrder`: ganti `round($subtotal * 0.11, 2)` → `calculateTax($subtotal)` atau langsung `round($subtotal * (float) config('settings.tax_rate', 11) / 100, 2)`

### Step 7: Replace Hardcoded 0.11 di TransactionController
- `store`: same replacement

### Step 8: Replace Hardcoded di Checkout View
- `Tax (11%)` → `Tax ({{ config('settings.tax_rate', 11) }}%)`
- Hitung tax: `$tax = round($subtotal * (float) config('settings.tax_rate', 11) / 100, 2)`

### Step 9: Buat `SettingController`
- `index()` → load settings, return view
- `update(Request $request)` → validasi `tax_rate` (required, numeric, 0-100), set value, refresh config, redirect success

### Step 10: Buat View `admin/settings/index.blade.php`
- Layout: `layouts.admin`
- Form: input number untuk tax_rate (min 0, max 100, step 0.1)
- Submit button "Save Settings"
- Tampilkan current value

### Step 11: Tambah Route
```php
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('index');
    Route::post('/', [SettingController::class, 'update'])->name('update');
});
```
Di dalam grup `admin` + `role:admin`.

### Step 12: Tambah Sidebar Link
Di `layouts/admin.blade.php`, setelah menu Transactions:
```html
<a href="{{ route('admin.settings.index') }}" ...>
    <iconify-icon icon="solar:settings-linear" ...>
    <span>Settings</span>
</a>
```

### Step 13: Jalankan Migration + Seeder
```bash
php artisan migrate
php artisan db:seed --class=SettingsSeeder
```

## Dependencies Internal
- Independent at migration level (tabel baru, tidak related ke orders)
- Tapi touches banyak file yang sudah ada
- Sebaiknya dikerjakan setelah TRX-1 (untuk konsistensi)

## API / Interface
```php
namespace App\Models;

class Setting extends Model {
    public static function getValue(string $key, mixed $default = null): mixed
    public static function setValue(string $key, mixed $value): void
}
```

```php
namespace App\Http\Controllers\Admin;

class SettingController {
    public function index()
    public function update(Request $request)
}
```

## Data Flow
```
AppServiceProvider::boot()
    ↓
Setting::pluck('value', 'key') → config('settings.*')
    ↓
Di controller/view: config('settings.tax_rate', 11)
    ↓
Admin Settings Page
    ↓
SettingController@update → Setting::setValue('tax_rate', ...)
    ↓
Refresh config → Next request pakai value baru
```

## Test Plan
- Unit test: migration create settings table
- Unit test: Setting::getValue return default jika key tidak ada
- Unit test: Setting::setValue update existing key
- Unit test: seeder mengisi default values
- Unit test: AppServiceProvider load settings ke config
- Unit test: helper taxRate() return config value
- Integration test: admin update tax rate → config berubah
- Integration test: checkout menghitung tax dengan rate baru
