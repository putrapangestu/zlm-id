<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Laptop;
use App\Services\XenditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller {
    public function __construct(
        protected XenditService $xenditService
    ) {}

    public function index(Request $request): View {
        $query = Order::with('user');

        // Filters
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        if ($paymentMethod = $request->get('payment_method')) {
            $query->where('payment_method', $paymentMethod);
        }
        if ($paymentStatus = $request->get('payment_status')) {
            $query->where('payment_status', $paymentStatus);
        }

        $orders = $query->latest()->paginate(20);
        $stats = [
            'total_orders' => Order::count(),
            'total_paid' => Order::where('payment_status', 'paid')->count(),
            'total_pending' => Order::where('payment_status', 'unpaid')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total'),
        ];

        return view('admin.transactions.index', compact('orders', 'stats'));
    }

    public function show(Order $order): View {
        $order->load('user', 'items.laptop', 'approvedBy');
        return view('admin.transactions.show', compact('order'));
    }

    public function create(): View {
        $customers = User::whereDoesntHave('roles', function($q) {
            $q->where('name', 'admin');
        })->get();
        $laptops = Laptop::where('stock', '>', 0)->get();
        return view('admin.transactions.create', compact('customers', 'laptops'));
    }

    public function store(Request $request): RedirectResponse {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.laptop_id' => 'required|exists:laptops,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:xendit,manual_transfer',
            'shipping_address' => 'nullable|string|max:500',
            'shipping_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Hitung subtotal + tax
        $subtotal = 0;
        $items = [];
        foreach ($validated['items'] as $item) {
            $laptop = Laptop::findOrFail($item['laptop_id']);
            if ($laptop->stock < $item['quantity']) {
                return redirect()->back()->with('error', "Stok {$laptop->name} tidak mencukupi.");
            }
            $subtotal += $laptop->price * $item['quantity'];
            $items[] = ['laptop' => $laptop, 'quantity' => $item['quantity']];
        }

        $taxRate = (float) config('settings.tax_rate', 11);
        $tax = round($subtotal * $taxRate / 100, 2);
        $shippingCost = (float) ($validated['shipping_cost'] ?? 0);
        $total = $subtotal + $tax + $shippingCost;

        // Create Order
        $order = Order::create([
            'user_id' => $validated['user_id'],
            'status' => 'pending',
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'payment_method' => $validated['payment_method'],
            'payment_status' => 'unpaid',
            'shipping_address' => $validated['shipping_address'] ?? null,
            'shipping_cost' => $shippingCost > 0 ? $shippingCost : null,
            'notes' => $validated['notes'] ?? null,
        ]);

        // Buat OrderItems
        foreach ($items as $item) {
            $order->items()->create([
                'laptop_id' => $item['laptop']->id,
                'quantity' => $item['quantity'],
                'price' => $item['laptop']->price,
                'subtotal' => $item['laptop']->price * $item['quantity'],
            ]);
            // Kurangi stock
            $item['laptop']->decrement('stock', $item['quantity']);
        }

        // Jika Xendit, buat invoice
        if ($validated['payment_method'] === 'xendit') {
            try {
                $invoice = $this->xenditService->createInvoice($order);
                $order->update([
                    'xendit_invoice_id' => $invoice['id'],
                    'xendit_invoice_url' => $invoice['invoice_url'],
                    'xendit_expiry' => $invoice['expiry_date'],
                ]);
            } catch (\Exception $e) {
                return redirect()->route('admin.transactions.show', $order)
                    ->with('warning', 'Transaksi dibuat, tetapi gagal membuat invoice Xendit: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.transactions.show', $order)
            ->with('success', 'Transaksi berhasil dibuat.');
    }

    public function confirmPayment(Request $request, Order $order): RedirectResponse {
        if ($order->payment_status !== 'pending_verification') {
            return redirect()->back()->with('error', 'Status pembayaran tidak dalam proses verifikasi.');
        }

        $order->update([
            'payment_status' => 'paid',
            'status' => 'processing',
            'paid_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }
}
