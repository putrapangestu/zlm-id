# TRX-4: Upload Bukti Transfer (Manual — Admin Created)

## Tujuan
Fitur upload bukti transfer untuk transaksi yang dibuat admin dengan metode **Manual Transfer**.
User checkout biasa TIDAK menggunakan ini (karena wajib Xendit).

## Skenario
1. Admin membuat transaksi untuk customer dengan `payment_method = manual_transfer`
2. Customer melihat transaksi di history dengan status "Unpaid"
3. Customer upload bukti transfer
4. Status berubah jadi `pending_verification`
5. Admin konfirmasi → status jadi `paid`

## File Baru
- `app/Http/Controllers/ProofUploadController.php`

## File Diubah
- `routes/web.php`
- `resources/views/orders/confirmation.blade.php` (tampilkan form upload)
- `resources/views/orders/history.blade.php` (tampilkan tombol upload)

## Route
```php
Route::post('/orders/{order}/proof', [ProofUploadController::class, 'upload'])->name('orders.proof.upload');
```
(dalam grup middleware auth + verified)

## Detail Implementasi

### 1. ProofUploadController
```php
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProofUploadController extends Controller
{
    public function upload(Request $request, Order $order)
    {
        // Gate: hanya pemilik order
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Gate: hanya untuk manual_transfer dan unpaid
        if ($order->payment_method !== 'manual_transfer' || $order->payment_status !== 'unpaid') {
            return redirect()->back()->with('error', 'Invalid payment status.');
        }
        
        // Validasi file
        $request->validate([
            'proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        
        // Simpan file
        $path = $request->file('proof')->store('proof-of-transfer', 'public');
        
        // Update order
        $order->update([
            'proof_of_transfer' => $path,
            'payment_status' => 'pending_verification',
        ]);
        
        return redirect()->back()->with('success', 'Bukti transfer berhasil diupload. Menunggu verifikasi admin.');
    }
}
```

### 2. Informasi Rekening Tujuan
Tampilkan di confirmation page:
```
Transfer ke:
Bank BCA
123-456-7890
a.n. PT ZLM ID
```

### 3. Tampilkan di History
Untuk order dengan `payment_method = manual_transfer && payment_status = unpaid`:
- Tombol "Upload Proof" → link ke confirmation page (scroll ke form upload)

Untuk `payment_status = pending_verification`:
- Badge "Menunggu Verifikasi"

### Definisi Selesai
- [ ] Upload file berfungsi (jpg, jpeg, png, pdf, max 2MB)
- [ ] File tersimpan di `storage/app/public/proof-of-transfer/`
- [ ] Payment_status berubah unpaid → pending_verification
- [ ] Hanya pemilik order yang bisa upload
- [ ] Hanya untuk order dengan metode manual_transfer
