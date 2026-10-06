<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Cart;
use App\Models\City;
use App\Models\Order;
use App\Services\InventoryService;
use App\Services\WinpayService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use LogicException;

class OrderController extends Controller
{
    public function __construct(
        protected InventoryService $inventoryService,
        protected WinpayService $winpayService,
    ) {}

    public function checkout()
    {
        $cart = Cart::where('user_id', auth()->id())
            ->with('items.laptop', 'items.addon')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $totalWeight = $cart->items->sum(fn ($item) => ($item->laptop->weight ?: 1.4) * $item->quantity);
        $paymentGateway = config('payment.gateway');

        if (! in_array($paymentGateway, ['winpay', 'xendit'], true)) {
            throw new LogicException('PAYMENT_GATEWAY must be either "winpay" or "xendit".');
        }

        return view('orders.checkout', compact('cart', 'totalWeight', 'paymentGateway'));
    }

    public function placeOrder(Request $request)
    {
        $cart = Cart::where('user_id', auth()->id())
            ->with('items.laptop', 'items.addon')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        // Validasi ketersediaan stok
        foreach ($cart->items as $item) {
            $laptop = $item->laptop;
            if ($laptop->stock < $item->quantity) {
                return redirect()->back()->with('error', "Stok tidak mencukupi untuk unit {$laptop->name}.");
            }
        }

        $paymentGateway = config('payment.gateway');
        if (! in_array($paymentGateway, ['winpay', 'xendit'], true)) {
            throw new LogicException('PAYMENT_GATEWAY must be either "winpay" or "xendit".');
        }

        $validated = $request->validate([
            'shipping_address' => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:500',
            'shipping_cost' => 'required|numeric|min:0',
            'shipping_courier' => 'required|string|max:50',
            'shipping_service' => 'required|string|max:100',
            'shipping_etd' => 'nullable|string|max:50',
            'shipping_city_id' => [
                'required',
                'integer',
                Rule::exists('cities', 'id')->whereNotNull('id_rajaongkir'),
            ],
            'payment_method' => $paymentGateway === 'winpay'
                ? 'nullable|in:winpay_qris,winpay_va'
                : 'prohibited',
            'payment_channel' => 'exclude_unless:payment_method,winpay_va|required|in:BRI,BNI,MANDIRI,PERMATA,BSI,MUAMALAT,BCA,CIMB,SINARMAS,BNC',
        ]);

        $subtotal = $cart->total;
        $tax = round($subtotal * (float) config('settings.tax_rate', 11) / 100, 2);
        $shippingCost = (float) $validated['shipping_cost'];
        $total = $subtotal + $tax + $shippingCost;
        $shippingCity = City::with('province')->findOrFail($validated['shipping_city_id']);

        $order = Order::create([
            'user_id' => auth()->id(),
            'source' => 'online',
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping_cost' => $shippingCost,
            'total' => $total,
            'status' => 'pending',
            'payment_method' => $paymentGateway === 'xendit'
                ? 'xendit'
                : ($validated['payment_method'] ?? 'winpay_qris'),
            'payment_status' => 'unpaid',
            'notes' => $validated['notes'] ?? null,
            'shipping_address' => $validated['shipping_address'],
            'shipping_city' => $shippingCity->name,
            'shipping_province' => $shippingCity->province->name,
            'shipping_postal_code' => $validated['shipping_postal_code'],
            'shipping_phone' => $validated['shipping_phone'],
            'shipping_courier' => $validated['shipping_courier'],
            'shipping_service' => $validated['shipping_service'],
            'shipping_etd' => $validated['shipping_etd'] ?? null,
            'shipping_city_id' => (string) $shippingCity->id,
            'shipping_city_name' => $shippingCity->name,
            'shipping_province_name' => $shippingCity->province->name,
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'laptop_id' => $item->laptop_id,
                'laptop_variant_id' => null,
                'addon_id' => $item->addon_id,
                'addon_name' => $item->addon?->name,
                'addon_price' => $item->addon_price ?? 0,
                'product_name' => $item->laptop->name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal' => $item->subtotal,
            ]);

            // Deduct stock using InventoryService
            $this->inventoryService->reduceStockForSale($item->laptop, null, $item->quantity, $order, auth()->user());
        }

        $cart->items()->delete();
        $cart->delete();

        // Kirim email konfirmasi
        try {
            Mail::to($order->user->email)->queue(new OrderConfirmationMail($order));
        } catch (\Exception $e) {
            Log::warning('Failed sending order email: ' . $e->getMessage());
        }

        if ($order->payment_method === 'xendit') {
            try {
                $invoice = app(\App\Services\XenditService::class)->createInvoice($order);
                $order->update([
                    'xendit_invoice_id' => $invoice['id'],
                    'xendit_invoice_url' => $invoice['invoice_url'],
                    'xendit_expiry' => $invoice['expiry_date'],
                ]);

                return redirect()->away($invoice['invoice_url']);
            } catch (\Exception $e) {
                Log::error('Xendit invoice creation failed', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
        } else {
            try {
                $payment = $order->payment_method === 'winpay_qris'
                    ? $this->winpayService->createQrisPayment($order)
                    : $this->winpayService->createVirtualAccount($order, $validated['payment_channel']);

                $order->update([
                    'winpay_reference' => $payment['reference'],
                    'winpay_contract_id' => $payment['contract_id'],
                    'winpay_qr_url' => $payment['qr_url'],
                    'winpay_qr_content' => $payment['qr_content'],
                    'winpay_virtual_account_no' => $payment['virtual_account_no'],
                    'winpay_channel' => $payment['channel'],
                    'winpay_expiry' => $payment['expiry'],
                ]);
            } catch (\Exception $e) {
                Log::error('Winpay payment creation failed', [
                    'order_id' => $order->id,
                    'payment_method' => $order->payment_method,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if (
            ($order->payment_method === 'xendit' && ! $order->xendit_invoice_url)
            || (str_starts_with($order->payment_method, 'winpay_') && ! $order->winpay_reference)
        ) {
            return redirect()->route('orders.confirmation', $order)
                ->with('warning', 'Order berhasil dibuat, tetapi gagal menghubungi gateway pembayaran. Silakan coba lagi atau hubungi admin.');
        }

        return redirect()->route('orders.confirmation', $order);
    }

    public function xenditCallback(Request $request, Order $order): RedirectResponse
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $status = $request->query('status');

        if ($status === 'success' || $status === 'paid') {
            try {
                $xenditService = app(\App\Services\XenditService::class);
                $invoiceStatus = $xenditService->getInvoiceStatus($order->xendit_invoice_id);

                if ($invoiceStatus['status'] === 'PAID') {
                    $order->update([
                        'payment_status' => 'paid',
                        'paid_at' => now(),
                    ]);
                    return redirect()->route('orders.confirmation', $order)
                        ->with('success', 'Pembayaran berhasil!');
                }
            } catch (\Exception $e) {
                Log::error('Xendit callback verification failed', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return redirect()->route('orders.confirmation', $order)
            ->with('error', 'Pembayaran belum selesai. Silakan coba lagi.');
    }

    public function confirmation(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.laptop', 'items.addon');

        return view('orders.confirmation', compact('order'));
    }

    public function history()
    {
        $orders = Order::byUser(auth()->id())
            ->with('items.laptop', 'items.addon')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('orders.history', compact('orders'));
    }
}
