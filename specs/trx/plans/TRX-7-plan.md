# TRX-7 Implementation Plan: User Transaction History Enhancement

## Effort: Medium

## File Structure
| File | Tipe | Keterangan |
|------|------|------------|
| `resources/views/orders/history.blade.php` | DIUBAH | Major: payment info, shipping info, action buttons |
| `resources/views/orders/confirmation.blade.php` | DIUBAH | Major: payment section, shipping breakdown, total breakdown |
| `app/Http/Controllers/OrderController.php` | DIUBAH | Minor: eager load tambahan |

## Implementation Order

### Step 1: Update OrderController@history
- Tambah eager load: `->with('items.laptop')` — sudah ada, tapi perlu pastikan
- Tidak perlu perubahan logic besar — hanya view update

### Step 2: Update OrderController@confirmation
- Pastikan eager load: `$order->load('items.laptop', 'items.variant')` — sudah ada

### Step 3: Update History View (`history.blade.php`)
- **Payment Status Badge** (ganti status badge existing dengan yang lebih detail):
  - `unpaid` → amber, "Unpaid"
  - `pending_verification` → blue, "Pending Verification"
  - `paid` → emerald, "Paid"
  - `expired` → rose, "Expired"
  - `failed` → red, "Failed"
- **Payment Method Badge**:
  - `xendit` → blue, "Xendit"
  - `manual_transfer` → amber, "Manual Transfer"
- **Shipping Info**:
  - Jika ada `shipping_courier`: tampilkan "JNE - REG (2-3 hari)"
- **Action Buttons** (ganti "View Details" saja dengan conditional):
  - Jika Xendit + unpaid: tombol "Pay Now" → link ke `$order->xendit_invoice_url` (target _blank)
  - Jika Manual Transfer + unpaid: tombol "Upload Proof" → link ke confirmation
  - Jika paid: tampilkan teks "Paid on 12 May 2025"
  - Tetap ada link "View Details"

### Step 4: Update Confirmation View (`confirmation.blade.php`)
- **Shipping Info Section** (baru, jika ada shipping data):
  ```
  Courier: JNE - REG (2-3 hari)
  Cost: Rp 15.000
  ```
- **Payment Section** (baru, setelah order summary):
  - Jika Xendit:
    - Jika paid: "Lunas" dengan icon check-circle emerald
    - Jika unpaid: link "Bayar Sekarang via Xendit" (button orange)
  - Jika Manual Transfer:
    - Informasi rekening BCA (static)
    - Jika unpaid: form upload proof (dari TRX-4)
    - Jika pending_verification: badge + preview file
    - Jika paid: "Lunas"
- **Total Breakdown**:
  ```
  Subtotal: Rp 15.000.000
  Tax (11%): Rp 1.650.000
  Shipping: Rp 15.000
  ─────────────────────
  Grand Total: Rp 16.665.000
  ```
- Tata letak: ubah dari single column center ke dua kolom (kiri: info, kanan: payment)

## Dependencies Internal
- TRX-1 (migration) — shipping fields + payment fields
- TRX-4 (upload proof) — form upload di confirmation view

## Data Flow
```
User → /orders (history)
    ↓
Tampilkan list orders dengan:
  - Payment status badge (color coded)
  - Payment method badge
  - Shipping info
  - Action button (Pay Now / Upload Proof / Paid on date)

User → /orders/{order} (confirmation)
    ↓
Tampilkan detail dengan:
  - Order summary + items
  - Payment section (Xendit link / Manual form)
  - Shipping info
  - Total breakdown
```

## Test Plan
- View test: history menampilkan payment status badge untuk semua status
- View test: history menampilkan tombol Pay Now untuk Xendit unpaid
- View test: history menampilkan tombol Upload Proof untuk manual unpaid
- View test: confirmation menampilkan payment section sesuai metode
- View test: confirmation menampilkan total breakdown
- Edge case: shipping_courier null → tidak tampil error
- Edge case: xendit_invoice_url null → tidak tampil link
