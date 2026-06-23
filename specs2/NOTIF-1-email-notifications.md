# NOTIF-1: Email Notifikasi Pesanan

## Tujuan
Email otomatis terkirim ke user saat ada event penting pada pesanan mereka.

## Implementasi

### 1. Mailables

#### `app/Mail/OrderConfirmationMail.php`
Dikirim saat: Pesanan berhasil dibuat
Data: 
- `$order` (order number, items, total, shipping address)
- Link ke halaman order
- Link pembayaran (Xendit) jika unpaid

#### `app/Mail/OrderShippedMail.php`
Dikirim saat: Pesanan dikirim (tracking number di-update)
Data:
- `$order` (order number, items)
- `$trackingNumber`
- `$courier` (nama kurir)
- Link ke halaman tracking

#### `app/Mail/OrderDeliveredMail.php`
Dikirim saat: Status berubah jadi DELIVERED
Data:
- `$order` (order number)
- Konfirmasi penerimaan
- Link untuk memberikan review

### 2. Views

#### `resources/views/emails/order-confirmation.blade.php`
```
┌──────────────────────────────┐
│        ZLM.ID                │
│  Pesanan Dikonfirmasi!       │
│                              │
│  Halo {user.name},           │
│  Pesanan kamu sudah kami     │
│  terima.                     │
│                              │
│  Order: {order.order_number} │
│  Total: Rp {order.total}     │
│                              │
│  [Lihat Detail Pesanan]      │
└──────────────────────────────┘
```

#### `resources/views/emails/order-shipped.blade.php`
#### `resources/views/emails/order-delivered.blade.php`

### 3. Trigger Email
Di Model `Order` (atau via Observer jika perlu):

```php
// Saat order dibuat
use Illuminate\Support\Facades\Mail;
Mail::to($order->user->email)->send(new OrderConfirmationMail($order));

// Saat tracking di-update ke SHIPPING
Mail::to($order->user->email)->send(new OrderShippedMail($order));

// Saat status jadi DELIVERED
Mail::to($order->user->email)->send(new OrderDeliveredMail($order));
```

Trigger ditempatkan di:
- `OrderController@placeOrder` — untuk confirmation
- `Admin\OrderStatusController@update` — untuk shipped/delivered

### 4. Queue
Gunakan queue untuk mengirim email agar tidak blocking response:
```php
Mail::to($order->user->email)->queue(new OrderConfirmationMail($order));
```

Pastikan queue worker berjalan:
```bash
php artisan queue:work
```

## Definisi Selesai
- [x] 3 Mail classes: Confirmation, Shipped, Delivered
- [x] 3 Email templates dengan design ZLM.ID
- [x] Email terkirim otomatis di event yang sesuai
- [x] Menggunakan queue agar tidak blocking
