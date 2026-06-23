# TRX-10: Configurable Tax Rate (Admin Settings)

## Tujuan
Membuat sistem settings yang memungkinkan admin mengatur persentase pajak dari admin panel, tanpa perlu mengubah kode.

## File Baru
- `database/migrations/YYYY_MM_DD_HHMMSS_create_settings_table.php`
- `app/Models/Setting.php`
- `app/Http/Controllers/Admin/SettingController.php`
- `resources/views/admin/settings/index.blade.php`
- `database/seeders/SettingsSeeder.php`

## File Diubah
- `app/Providers/AppServiceProvider.php`
- `app/Http/Controllers/OrderController.php` — ganti hardcoded 0.11
- `app/Http/Controllers/Admin/TransactionController.php` — ganti hardcoded 0.11
- `resources/views/orders/checkout.blade.php` — ganti hardcoded 0.11
- `routes/web.php` — tambah route admin settings

## Detail Implementasi

### 1. Migration: `create_settings_table`
```php
Schema::create('settings', function (Blueprint $table) {
    $table->string('key')->primary();
    $table->text('value')->nullable();
    $table->timestamps();
});
```
Primary key pakai `key` string — simple key-value store.

### 2. Model: `Setting.php`
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = self::find($key);
        return $setting ? $setting->value : $default;
    }

    public static function setValue(string $key, mixed $value): void
    {
        self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
```

### 3. Seeder: `SettingsSeeder`
```php
<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'tax_rate' => '11',
            'store_name' => 'ZLM.ID',
            'bank_name' => 'BCA',
            'bank_account' => '123-456-7890',
            'bank_holder' => 'PT ZLM ID',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
```

### 4. AppServiceProvider — Load Settings
```php
<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        try {
            if (app()->runningInConsole() && !app()->runningUnitTests()) {
                return; // Skip during migrations/commands
            }
            
            $settings = Setting::pluck('value', 'key')->toArray();
            config(['settings' => $settings]);
        } catch (\Exception $e) {
            // Table might not exist yet (fresh migration)
            config(['settings' => ['tax_rate' => 11]]);
        }
    }
}
```

### 5. Helper Function
Buat helper untuk akses tax rate dari mana saja:

```php
// Di app/helpers.php (atau langsung inline)
function taxRate(): float
{
    return (float) (config('settings.tax_rate', 11));
}

function calculateTax(float $amount): float
{
    return round($amount * taxRate() / 100, 2);
}
```

### 6. Replace All Hardcoded `0.11`

#### OrderController (checkout / placeOrder):
```php
// SEBELUM:
$tax = round($subtotal * 0.11, 2);

// SESUDAH:
$taxRate = (float) (config('settings.tax_rate', 11));
$tax = round($subtotal * $taxRate / 100, 2);
```

#### Checkout View (total sidebar):
```blade
{{-- SEBELUM --}}
<span class="text-gray-500">Tax (11%)</span>

{{-- SESUDAH --}}
<span class="text-gray-500">Tax ({{ config('settings.tax_rate', 11) }}%)</span>
```

#### Admin TransactionController (store):
```php
// SESUDAH
$taxRate = (float) (config('settings.tax_rate', 11));
$tax = round($subtotal * $taxRate / 100, 2);
```

### 7. Admin SettingController
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'tax_rate' => 'required|numeric|min:0|max:100',
        ]);

        Setting::setValue('tax_rate', $request->tax_rate);

        // Refresh config
        $allSettings = Setting::pluck('value', 'key')->toArray();
        config(['settings' => $allSettings]);

        return redirect()->route('admin.settings.index')
            ->with('success', "Tax rate updated to {$request->tax_rate}%");
    }
}
```

### 8. Admin Settings View
```blade
@extends('layouts.admin')

@section('title', 'Settings')
@section('heading', 'Settings')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-[#363230] mb-6">Tax Configuration</h3>
        
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-2">
                    Tax Rate (%)
                </label>
                <div class="flex items-center gap-3">
                    <input type="number" name="tax_rate" 
                           value="{{ old('tax_rate', $settings['tax_rate'] ?? 11) }}"
                           min="0" max="100" step="0.1"
                           class="w-32 px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] focus:bg-white">
                    <span class="text-gray-500 text-sm">%</span>
                </div>
                <p class="text-xs text-gray-400 mt-1.5">PPN / pajak yang diterapkan pada setiap transaksi. Default: 11%.</p>
            </div>
            
            <button type="submit" class="bg-[#DF5E1D] text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors">
                Save Settings
            </button>
        </form>
    </div>
</div>
@endsection
```

### 9. Routes
```php
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('index');
    Route::post('/', [SettingController::class, 'update'])->name('update');
});
```

### 10. Sidebar — Settings Link
Tambah di `layouts/admin.blade.php` setelah menu Transactions:
```html
<a href="{{ route('admin.settings.index') }}" 
   class="flex items-center gap-3 px-3 py-2.5 rounded-xl 
   @if(request()->routeIs('admin.settings.*')) bg-orange-50/50 text-[#DF5E1D] 
   @else text-gray-500 hover:bg-gray-50 hover:text-[#363230] @endif 
   transition-colors duration-200 group">
    <iconify-icon icon="solar:settings-linear" class="text-lg" style="stroke-width: 1.5;"></iconify-icon>
    <span class="text-sm font-medium">Settings</span>
</a>
```

## Definisi Selesai
- [ ] Settings table bisa diakses via model
- [ ] Seeder mengisi default tax_rate = 11
- [ ] AppServiceProvider load settings ke config
- [ ] Admin Settings page bisa ubah tax rate
- [ ] Semua perhitungan pajak menggunakan config (bukan hardcode 0.11)
- [ ] Sidebar admin ada menu Settings
