# TRX-4 Implementation Plan: Upload Bukti Transfer (Manual)

## Effort: Small

## File Structure
| File | Tipe | Keterangan |
|------|------|------------|
| `app/Http/Controllers/ProofUploadController.php` | BARU | Controller untuk upload bukti transfer |
| `routes/web.php` | DIUBAH | Tambah route POST upload proof |
| `resources/views/orders/confirmation.blade.php` | DIUBAH | Tambah form upload + info rekening |
| `resources/views/orders/history.blade.php` | DIUBAH | Tambah tombol upload proof |

## Implementation Order

### Step 1: Buat `ProofUploadController`
- Method: `upload(Request $request, Order $order)`
- Authorization gate:
  - `$order->user_id !== auth()->id()` → 403 (hanya pemilik)
  - `$order->payment_method !== 'manual_transfer'` → redirect back error
  - `$order->payment_status !== 'unpaid'` → redirect back error
- Validasi: `proof` → required, file, mimes:jpg,jpeg,png,pdf, max:2048
- Simpan: `$request->file('proof')->store('proof-of-transfer', 'public')`
- Update: `proof_of_transfer` = path, `payment_status` = 'pending_verification'
- Return redirect back with success message

### Step 2: Tambah Route
Di grup `middleware ['auth', 'verified']`:
```php
Route::post('/orders/{order}/proof', [ProofUploadController::class, 'upload'])
    ->name('orders.proof.upload');
```

### Step 3: Update Confirmation View
- Jika `payment_method === 'manual_transfer' && payment_status === 'unpaid'`:
  - Tampilkan section **Informasi Rekening Tujuan**:
    ```
    Transfer ke:
    Bank BCA
    123-456-7890
    a.n. PT ZLM ID
    ```
  - Tampilkan **Form Upload**:
    - `<input type="file" name="proof" accept=".jpg,.jpeg,.png,.pdf">`
    - Tombol submit "Upload Bukti Transfer"
  - Form: POST ke `route('orders.proof.upload', $order)`, `enctype="multipart/form-data"`
- Jika `payment_status === 'pending_verification'`:
  - Tampilkan badge "Menunggu Verifikasi Admin"
  - Tampilkan preview file yang sudah diupload (jika ada)

### Step 4: Update History View
- Jika `payment_method === 'manual_transfer' && payment_status === 'unpaid'`:
  - Tambah tombol "Upload Proof" → link ke confirmation page
- Jika `payment_status === 'pending_verification'`:
  - Tampilkan badge "Menunggu Verifikasi"

## Dependencies Internal
- TRX-1 (migration) — `proof_of_transfer` column harus ada
- ProofUploadController → routes → views

## API / Interface
```php
namespace App\Http\Controllers;

class ProofUploadController {
    public function upload(Request $request, Order $order): RedirectResponse
}
```

## Data Flow
```
User (di history/confirmation page)
    ↓ Klik "Upload Proof"
Halaman confirmation → form upload
    ↓ Pilih file + submit
ProofUploadController::upload()
    ↓ Validasi ownership + payment status
    ↓ Store file ke storage/app/public/proof-of-transfer/
    ↓ Update order: proof_of_transfer, payment_status = pending_verification
Redirect back + success message
```

## Test Plan
- Unit test: upload dengan user bukan pemilik → 403
- Unit test: upload untuk order xendit → error
- Unit test: upload untuk order yang sudah paid → error
- Unit test: validasi file type (PDF allowed, PNG allowed, EXE rejected)
- Unit test: validasi file size > 2MB → error
- Integration test: upload file → check stored path
- Integration test: payment_status berubah unpaid → pending_verification
