# TRX-6 Implementation Plan: Admin Transaction Management

## Effort: Large

## File Structure
| File | Tipe | Keterangan |
|------|------|------------|
| `app/Http/Controllers/Admin/TransactionController.php` | DIUBAH | Major: full CRUD + confirm payment |
| `resources/views/admin/transactions/index.blade.php` | DIUBAH | Major: data real dari DB, ganti dummy |
| `resources/views/admin/transactions/show.blade.php` | BARU | Halaman detail transaksi |
| `resources/views/admin/transactions/create.blade.php` | BARU | Form create transaksi oleh admin |
| `routes/web.php` | DIUBAH | Tambah route group admin transactions |

## Implementation Order

### Step 1: Update TransactionController — Method `index()`
- Query Order dengan pagination (20 per page), eager load `user` + `items.laptop`
- Filter: search (order_number, user name/email), payment_method, payment_status, date range
- Hitung stats real: total orders, paid count, pending count, total revenue
- Return view dengan `compact('orders', 'stats')`
- Kirim juga data untuk filter dropdown (payment methods, statuses)

### Step 2: TransactionController — Method `show(Order $order)`
- Eager load `order->load('user', 'items.laptop', 'items.variant')`
- Load juga `order->approvedBy` (admin yang konfirmasi)
- Return view `admin.transactions.show`

### Step 3: TransactionController — Method `confirmPayment(Request $request, Order $order)`
- Check: `payment_status === 'pending_verification'` → jika tidak, redirect back error
- Update: `payment_status = paid`, `status = processing`, `paid_at = now()`, `approved_by = auth()->id()`
- Redirect back with success

### Step 4: TransactionController — Method `create()`
- Load customers: User yang bukan admin (whereDoesntHave role admin)
- Load laptops: Laptop dengan stock > 0
- Return view dengan `compact('customers', 'laptops')`

### Step 5: TransactionController — Method `store(Request $request)`
- Validasi: user_id, items array, payment_method (xendit/manual_transfer), shipping fields
- Hitung subtotal dari items + tax (11% atau dari config) + shipping_cost
- Create Order + OrderItems
- Kurangi stock laptop
- Jika xendit: panggil XenditService::createInvoice, update order dengan invoice data
- Jika manual_transfer: langsung redirect success (tunggu upload proof dari customer)
- Error handling untuk Xendit API failure

### Step 6: Update Index View (`admin/transactions/index.blade.php`)
- Ganti stats hardcoded dengan `$stats` dari controller
- Ganti tabel dummy dengan loop `foreach($orders as $order)`
- Kolom: order_number, customer info (name + email + address), created_at, payment_method, total, payment_status
- Aksi: link ke show, link ke create baru
- Pagination links
- Filter form: search input, payment method dropdown, payment status dropdown, date range

### Step 7: Buat Show View (`admin/transactions/show.blade.php`)
- Layout: `layouts.admin`
- Header: "Transaction Detail: #ORDER-XXXXX" + back button
- **Status Badges**: payment status, order status, payment method
- **Customer Info**: name, email, alamat lengkap, phone
- **Order Items Table**: laptop name, qty, unit price, subtotal
- **Totals**: subtotal, tax (11%), shipping, grand total
- **Payment Info**:
  - Method badge (Xendit/Manual Transfer)
  - Status badge
  - Jika Xendit: tampilkan invoice URL, expiry
  - Jika Manual Transfer + pending_verification: tampilkan link ke file proof + [Confirm Payment] button
  - Jika sudah paid: tampilkan paid_at + approved_by
- **Shipping Info**: courier, service, cost, estimasi, alamat tujuan
- **Timeline**: created_at, paid_at (jika ada)

### Step 8: Buat Create View (`admin/transactions/create.blade.php`)
- Layout: `layouts.admin`
- Form dengan:
  - Customer dropdown (select2 atau searchable select)
  - Items repeater: pilih laptop + quantity, dengan tombol "Add Item"
  - Payment Method: radio Xendit (online, otomatis) / Manual Transfer (upload bukti nanti)
  - Shipping fields: alamat, kota, provinsi (manual input), kode pos, HP, ongkir (manual, optional)
  - Notes textarea
  - Tombol "Create Transaction"

### Step 9: Update Routes
Di dalam grup `admin`+`role:admin`, replace route existing:
```php
Route::prefix('transactions')->name('transactions.')->group(function () {
    Route::get('/', [TransactionController::class, 'index'])->name('index');
    Route::get('/create', [TransactionController::class, 'create'])->name('create');
    Route::post('/', [TransactionController::class, 'store'])->name('store');
    Route::get('/{order}', [TransactionController::class, 'show'])->name('show');
    Route::post('/{order}/confirm-payment', [TransactionController::class, 'confirmPayment'])->name('confirm-payment');
});
```

## Dependencies Internal
- TRX-1 (migration) — semua kolom baru harus ada
- TRX-2 (XenditService) — untuk admin create transaction via Xendit
- TRX-10 (Tax Settings) — untuk tax rate dari config (bisa sementara hardcode)

## Dependencies Antar File
1. routes/web.php → update route group
2. TransactionController → semua method
3. index.blade.php → data real
4. show.blade.php → detail view
5. create.blade.php → form create

## API / Interface
```php
class TransactionController {
    public function index(Request $request)
    public function show(Order $order)
    public function create()
    public function store(Request $request)
    public function confirmPayment(Request $request, Order $order)
}
```

## Data Flow
```
Admin Dashboard / Sidebar → /admin/transactions
    ↓
Index: tampilkan semua orders (real data) + stats real
    ↓ Klik salah satu order
Show: detail lengkap + confirm payment button (jika manual transfer pending)
    ↓ Klik "Create New"
Create: form pilih customer + items + payment method
    ↓ Submit
Store: create order + (opsional) Xendit invoice
    ↓ Redirect ke show
```

## Test Plan
- Unit test: index dengan filter/search → query benar
- Unit test: confirmPayment untuk order non-pending → error
- Unit test: confirmPayment success → payment_status = paid, approved_by terisi
- Unit test: store validasi items kosong → error
- Unit test: store dengan xendit → XenditService dipanggil
- Integration test: full flow admin create → show → confirm
- View test: index menampilkan data real (bukan dummy)
- View test: show menampilkan customer info, items, totals
