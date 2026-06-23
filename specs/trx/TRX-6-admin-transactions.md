# TRX-6: Admin Transaction Management

## Tujuan
Membangun halaman admin untuk mengelola semua transaksi (orders) dengan data real + admin bisa buat transaksi dengan pilihan metode bayar (Xendit / Manual Transfer).

## File Baru
- `resources/views/admin/transactions/create.blade.php`
- `resources/views/admin/transactions/show.blade.php`

## File Diubah
- `app/Http/Controllers/Admin/TransactionController.php`
- `resources/views/admin/transactions/index.blade.php` (data real dari DB)
- `routes/web.php`

## Detail Implementasi

### 1. TransactionController — Full CRUD
```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Laptop;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'items.laptop');
        
        // Search by order number or customer
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        
        // Filter payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        // Filter date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $orders = $query->latest()->paginate(20);
        
        // Stats real
        $stats = [
            'total' => Order::count(),
            'paid' => Order::where('payment_status', 'paid')->count(),
            'pending' => Order::whereIn('payment_status', ['unpaid', 'pending_verification'])->count(),
            'revenue' => Order::where('payment_status', 'paid')->sum('total'),
        ];
        
        return view('admin.transactions.index', compact('orders', 'stats'));
    }
    
    public function show(Order $order)
    {
        $order->load('user', 'items.laptop', 'items.variant');
        return view('admin.transactions.show', compact('order'));
    }
    
    public function confirmPayment(Request $request, Order $order)
    {
        if ($order->payment_status !== 'pending_verification') {
            return redirect()->back()->with('error', 'Payment is not pending verification.');
        }
        
        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
            'paid_at' => now(),
            'approved_by' => auth()->id(),
        ]);
        
        return redirect()->back()->with('success', 'Payment confirmed successfully.');
    }
    
    public function create()
    {
        $customers = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        })->orderBy('name')->get();
        
        $laptops = Laptop::where('stock', '>', 0)->orderBy('name')->get();
        
        return view('admin.transactions.create', compact('customers', 'laptops'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.laptop_id' => 'required|exists:laptops,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:xendit,manual_transfer',
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_province' => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_phone' => 'required|string|max:20',
            'shipping_cost' => 'nullable|numeric|min:0',
            'shipping_courier' => 'nullable|string|max:50',
            'shipping_service' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Hitung total
        $subtotal = 0;
        $orderItems = [];
        
        foreach ($validated['items'] as $itemData) {
            $laptop = Laptop::findOrFail($itemData['laptop_id']);
            $price = $laptop->price;
            $itemSubtotal = $price * $itemData['quantity'];
            $subtotal += $itemSubtotal;
            
            $orderItems[] = [
                'laptop_id' => $laptop->id,
                'product_name' => $laptop->name,
                'quantity' => $itemData['quantity'],
                'unit_price' => $price,
                'subtotal' => $itemSubtotal,
            ];
            
            // Kurangi stock
            $laptop->decrement('stock', $itemData['quantity']);
        }
        
        $tax = round($subtotal * 0.11, 2);
        $shippingCost = (float) ($validated['shipping_cost'] ?? 0);
        $total = $subtotal + $tax + $shippingCost;
        
        $order = Order::create([
            'user_id' => $validated['user_id'],
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'status' => 'pending',
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'unpaid',
            'notes' => $validated['notes'] ?? null,
            'shipping_address' => $validated['shipping_address'],
            'shipping_city' => $validated['shipping_city'],
            'shipping_province' => $validated['shipping_province'],
            'shipping_postal_code' => $validated['shipping_postal_code'],
            'shipping_phone' => $validated['shipping_phone'],
            'shipping_cost' => $shippingCost,
            'shipping_courier' => $validated['shipping_courier'] ?? null,
            'shipping_service' => $validated['shipping_service'] ?? null,
        ]);
        
        foreach ($orderItems as $itemData) {
            $order->items()->create($itemData);
        }
        
        // Handle payment method
        if ($validated['payment_method'] === 'xendit') {
            try {
                $xendit = app(XenditService::class);
                $invoice = $xendit->createInvoice($order);
                
                $order->update([
                    'xendit_invoice_id' => $invoice['id'],
                    'xendit_invoice_url' => $invoice['invoice_url'],
                    'xendit_expiry' => $invoice['expiry_date'],
                ]);
                
                return redirect()->route('admin.transactions.show', $order)
                    ->with('success', 'Transaction created. Xendit invoice ready.');
            } catch (\Exception $e) {
                Log::error('Failed to create Xendit invoice for admin order', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
                return redirect()->route('admin.transactions.show', $order)
                    ->with('warning', 'Transaction created but Xendit invoice failed: ' . $e->getMessage());
            }
        }
        
        // Manual transfer
        return redirect()->route('admin.transactions.show', $order)
            ->with('success', 'Transaction created successfully. Awaiting payment from customer.');
    }
}
```

### 2. Routes (Admin)
```php
Route::prefix('transactions')->name('transactions.')->group(function () {
    Route::get('/', [TransactionController::class, 'index'])->name('index');
    Route::get('/create', [TransactionController::class, 'create'])->name('create');
    Route::post('/', [TransactionController::class, 'store'])->name('store');
    Route::get('/{order}', [TransactionController::class, 'show'])->name('show');
    Route::post('/{order}/confirm-payment', [TransactionController::class, 'confirmPayment'])->name('confirm-payment');
});
```

### 3. Index View
Data real dari database — ganti dummy data. Desain table dan stats tetap sama seperti sebelumnya.

### 4. Show View
Halaman detail transaksi:

```
┌────────────────────────────────────────────────────────┐
│ Transaction Detail: #ORD-XXXXXX    [← Back]            │
├────────────────────────────────────────────────────────┤
│ Status Badges: [Pending] [Unpaid] [Xendit]            │
│                                                        │
│ ┌─── Customer Info ────────────────────────────────┐  │
│ │ Nama: John Doe                                   │  │
│ │ Email: john@email.com                            │  │
│ │ Alamat: Jl. Merdeka No.1, Jakarta, DKI Jakarta   │  │
│ │ HP: 081234567890                                 │  │
│ └──────────────────────────────────────────────────┘  │
│                                                        │
│ ┌─── Order Items ─────────────────────────────────┐  │
│ │ Item              │ Qty │ Price    │ Subtotal   │  │
│ │ Lenovo ThinkPad   │ 1   │ 15.000.000│ 15.000.000│  │
│ │ ...               │     │          │            │  │
│ ├──────────────────┼─────┼─────────┼───────────┤  │
│ │ Subtotal          │     │          │ 15.000.000│  │
│ │ Tax (11%)         │     │          │ 1.650.000 │  │
│ │ Shipping (JNE REG)│     │          │ 15.000    │  │
│ │ Total             │     │          │ 16.665.000│  │
│ └──────────────────────────────────────────────────┘  │
│                                                        │
│ ┌─── Payment Info ────────────────────────────────┐  │
│ │ Method: [Xendit] or [Manual Transfer]            │  │
│ │ Status: [Unpaid / Pending Verification / Paid]   │  │
│ │ Jika Manual: [Lihat Bukti Transfer (gambar)]     │  │
│ │ Jika Manual + Pending Verif: [Confirm Payment]   │  │
│ └──────────────────────────────────────────────────┘  │
│                                                        │
│ ┌─── Shipping Info ───────────────────────────────┐  │
│ │ Courier: JNE - REG (2-3 hari)                   │  │
│ │ Cost: Rp 15.000                                  │  │
│ └──────────────────────────────────────────────────┘  │
└────────────────────────────────────────────────────────┘
```

### 5. Create View
Form untuk admin membuat transaksi baru:

```
┌──────────────────────────────────────────────┐
│ Create New Transaction                       │
├──────────────────────────────────────────────┤
│ Customer: [▼ Pilih Customer]                 │
│                                              │
│ Items:                                       │
│ ┌─────────────────────────────────────────┐  │
│ │ Laptop: [▼ Pilih Laptop]   Qty: [__]  │  │
│ │ [+ Add Item]                            │  │
│ └─────────────────────────────────────────┘  │
│                                              │
│ Payment Method:                              │
│ ○ [Xendit] — Online, otomatis               │
│ ○ [Manual Transfer] — Upload bukti nanti    │
│                                              │
│ Shipping:                                    │
│ Alamat: [_____________________________]      │
│ Kota:   [________________]  Prov: [_____]   │
│ Pos:    [______]          HP: [__________]  │
│ Ongkir: [______] (opsional, manual input)   │
│                                              │
│ Notes:                                       │
│ [_____________________________]              │
│                                              │
│ [Create Transaction]                         │
└──────────────────────────────────────────────┘
```

### Definisi Selesai
- [ ] Index menampilkan data real dari orders table
- [ ] Stats real dari database
- [ ] Show menampilkan detail lengkap (customer, items, payment, shipping)
- [ ] Confirm payment button berfungsi untuk manual_transfer
- [ ] Create form untuk admin buat transaksi baru
- [ ] Pilih metode bayar: Xendit / Manual Transfer
- [ ] RajaOngkir integration optional (admin bisa input manual ongkir)
- [ ] Semua route aman (middleware auth + role:admin)
