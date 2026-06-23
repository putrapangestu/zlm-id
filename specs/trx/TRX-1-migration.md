# TRX-1: Database Migration — Payment & Shipping Fields

## Tujuan
Menambahkan kolom ke tabel `orders` untuk mendukung pembayaran Xendit, Manual Transfer, dan Ongkos Kirim RajaOngkir.

## File Baru
- `database/migrations/YYYY_MM_DD_HHMMSS_add_payment_and_shipping_fields_to_orders_table.php`

## Detail Migration

### Payment Fields (Xendit + Manual Transfer)

| Kolom | Tipe | Nullable | Default | Keterangan |
|-------|------|----------|---------|------------|
| `xendit_invoice_id` | `string`, varchar(255) | Yes | null | ID invoice dari Xendit |
| `xendit_invoice_url` | `text` | Yes | null | URL pembayaran Xendit |
| `xendit_expiry` | `timestamp` | Yes | null | Waktu kadaluarsa invoice Xendit |
| `proof_of_transfer` | `string`, varchar(255) | Yes | null | Path file bukti transfer |
| `paid_at` | `timestamp` | Yes | null | Waktu pembayaran dikonfirmasi |
| `approved_by` | `uuid` (foreign to users) | Yes | null | Admin yang mengkonfirmasi pembayaran |

### Shipping Fields (RajaOngkir)

| Kolom | Tipe | Nullable | Default | Keterangan |
|-------|------|----------|---------|------------|
| `shipping_cost` | `decimal(15, 2)` | Yes | null | Biaya ongkos kirim |
| `shipping_courier` | `string(50)` | Yes | null | Kode kurir (jne, pos, tiki) |
| `shipping_service` | `string(100)` | Yes | null | Nama layanan (REG, OKE, etc.) |
| `shipping_etd` | `string(50)` | Yes | null | Estimasi waktu kirim |
| `shipping_city_id` | `string(20)` | Yes | null | ID kota tujuan (RajaOngkir) |
| `shipping_city_name` | `string(255)` | Yes | null | Nama kota tujuan |
| `shipping_province_name` | `string(255)` | Yes | null | Nama provinsi tujuan |

### Update payment_method constraint
Nilai yang didukung: `xendit`, `manual_transfer`
(Biarkan sebagai string kolom, tidak perlu enum — lebih flexible)

### Update payment_status constraint
Nilai yang didukung: `unpaid`, `pending_verification`, `paid`, `expired`, `failed`

### Migration Code Pattern
```php
Schema::table('orders', function (Blueprint $table) {
    // Payment fields
    $table->string('xendit_invoice_id')->nullable()->after('payment_status');
    $table->text('xendit_invoice_url')->nullable()->after('xendit_invoice_id');
    $table->timestamp('xendit_expiry')->nullable()->after('xendit_invoice_url');
    $table->string('proof_of_transfer')->nullable()->after('xendit_expiry');
    $table->timestamp('paid_at')->nullable()->after('proof_of_transfer');
    $table->foreignUuid('approved_by')->nullable()->after('paid_at')->constrained('users')->nullOnDelete();
    
    // Shipping fields
    $table->decimal('shipping_cost', 15, 2)->nullable()->after('approved_by');
    $table->string('shipping_courier', 50)->nullable()->after('shipping_cost');
    $table->string('shipping_service', 100)->nullable()->after('shipping_courier');
    $table->string('shipping_etd', 50)->nullable()->after('shipping_service');
    $table->string('shipping_city_id', 20)->nullable()->after('shipping_etd');
    $table->string('shipping_city_name', 255)->nullable()->after('shipping_city_id');
    $table->string('shipping_province_name', 255)->nullable()->after('shipping_city_name');
});
```

### Definisi Selesai
- [ ] File migration dibuat
- [ ] `php artisan migrate` berhasil tanpa error
- [ ] `php artisan migrate:rollback` berhasil
- [ ] Semua kolom baru bisa diisi dan dibaca
