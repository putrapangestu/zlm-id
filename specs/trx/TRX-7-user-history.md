# TRX-7: User Transaction History Enhancement

## Tujuan
Meningkatkan halaman riwayat order user dengan informasi pembayaran dan ongkos kirim.

## File Diubah
- `resources/views/orders/history.blade.php`
- `resources/views/orders/confirmation.blade.php`
- `app/Http/Controllers/OrderController.php`

## Detail Implementasi

### 1. Enhanced History View
Setiap card order menampilkan:

**Payment Status Badge**:
```blade
@switch($order->payment_status)
    @case('unpaid')
        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-600 border border-amber-100">
            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
            Unpaid
        </span>
    @case('pending_verification')
        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-600 border border-blue-100">
            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
            Pending Verification
        </span>
    @case('paid')
        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600 border border-emerald-100">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            Paid
        </span>
    @case('expired')
        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-600 border border-rose-100">
            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
            Expired
        </span>
    @case('failed')
        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-600 border border-red-100">
            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
            Failed
        </span>
@endswitch
```

**Payment Method Badge**:
```blade
@if($order->payment_method === 'xendit')
    <span class="text-xs bg-blue-50 text-blue-600 px-2 py-0.5 rounded">Xendit</span>
@else
    <span class="text-xs bg-amber-50 text-amber-600 px-2 py-0.5 rounded">Manual Transfer</span>
@endif
```

**Shipping Info**:
```blade
@if($order->shipping_courier)
    <p class="text-xs text-gray-500">
        {{ $order->shipping_courier }} - {{ $order->shipping_service }}
        ({{ $order->shipping_etd }} hari)
    </p>
@endif
```

**Action Buttons**:
- Jika `payment_method === 'xendit' && payment_status === 'unpaid'`: Tombol "Pay Now" → link ke `$order->xendit_invoice_url`
- Jika `payment_method === 'manual_transfer' && payment_status === 'unpaid'`: Tombol "Upload Proof" → link ke confirmation page
- Jika `payment_status === 'paid'`: Tampilkan "Paid on {date}"

### 2. Enhanced Confirmation View
- Tampilkan **shipping info** (kurir, service, ongkir, estimasi)
- Jika Xendit unpaid: Link pembayaran Xendit + instruksi
- Jika Manual Transfer unpaid: Informasi rekening + upload form
- Break down total: subtotal, tax, shipping, grand total

### 3. Confirmation — Xendit Status
```blade
@if($order->payment_method === 'xendit')
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
        <h2 class="text-lg font-semibold text-[#363230] mb-4">Pembayaran Xendit</h2>
        
        @if($order->payment_status === 'paid')
            <div class="flex items-center gap-3 text-emerald-600">
                <iconify-icon icon="solar:check-circle-bold" class="text-2xl"></iconify-icon>
                <span class="font-medium">Lunas</span>
            </div>
        @else
            <p class="text-sm text-gray-500 mb-4">
                Silakan selesaikan pembayaran melalui Xendit.
            </p>
            <a href="{{ $order->xendit_invoice_url }}" target="_blank"
               class="inline-flex items-center gap-2 bg-[#DF5E1D] text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors">
                <iconify-icon icon="solar:external-link-linear"></iconify-icon>
                Bayar Sekarang via Xendit
            </a>
        @endif
    </div>
@endif
```

### Definisi Selesai
- [ ] History menampilkan payment status badge + payment method badge
- [ ] History menampilkan shipping info (courier, service, cost)
- [ ] Tombol "Pay Now" untuk Xendit unpaid
- [ ] Tombol "Upload Proof" untuk Manual Transfer unpaid
- [ ] Confirmation menampilkan detail pembayaran sesuai metode
- [ ] Total breakdown termasuk ongkos kirim
