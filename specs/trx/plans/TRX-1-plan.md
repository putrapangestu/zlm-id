# TRX-1 Implementation Plan: Database Migration — Payment & Shipping Fields

## Effort: Small

## File Structure
| File | Tipe | Keterangan |
|------|------|------------|
| `database/migrations/2026_06_04_100001_add_payment_and_shipping_fields_to_orders_table.php` | BARU | Migration untuk menambah kolom payment & shipping |

## Implementation Order

### Step 1: Buat Migration File
- Buat file migration dengan nama timestamp `2026_06_04_100001` (atau tanggal aktual)
- Struktur: `Schema::table('orders', function (Blueprint $table) { ... })`
- Tambah kolom payment: `xendit_invoice_id`, `xendit_invoice_url`, `xendit_expiry`, `proof_of_transfer`, `paid_at`, `approved_by`
- Tambah kolom shipping: `shipping_cost`, `shipping_courier`, `shipping_service`, `shipping_etd`, `shipping_city_id`, `shipping_city_name`, `shipping_province_name`
- Foreign key `approved_by` → `users.id` dengan `nullOnDelete()`
- Semua kolom nullable
- Letakkan kolom `xendit_invoice_id` setelah `payment_status`, urut sesuai spek

### Step 2: Update Order Model (`app/Models/Order.php`)
- Tambah field baru ke `$fillable` array:
  ```
  'xendit_invoice_id', 'xendit_invoice_url', 'xendit_expiry', 'proof_of_transfer',
  'paid_at', 'approved_by', 'shipping_cost', 'shipping_courier', 'shipping_service',
  'shipping_etd', 'shipping_city_id', 'shipping_city_name', 'shipping_province_name'
  ```
- Tambah casts untuk `shipping_cost` → `decimal:2`, `xendit_expiry` → `datetime`, `paid_at` → `datetime`
- Tambah relasi `approvedBy()` → `BelongsTo(User::class, 'approved_by')`

### Step 3: Jalankan Migration
- `php artisan migrate`
- Test `php artisan migrate:rollback` dan `migrate` lagi

## Dependencies Internal
- Tidak ada dependencies internal — file tunggal

## API / Interface
- **Order Model** — kolom baru accessible via `$order->xendit_invoice_id`, dll.
- **Relasi**: `$order->approvedBy` → User model

## Data Flow
```
Migration → orders table (new columns)
                ↓
         Order Model (fillable + casts + relations)
                ↓
         All controllers can access new fields
```

## Test Plan
- `php artisan migrate` → success, tidak ada error
- `php artisan migrate:rollback` → success, kolom hilang
- `php artisan migrate` lagi → kolom muncul kembali
- Insert order manual via tinker dengan data baru → semua kolom terisi
- Foreign key `approved_by` → test nullOnDelete works
