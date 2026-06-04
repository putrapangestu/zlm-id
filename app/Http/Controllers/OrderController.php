<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = Cart::where('user_id', auth()->id())
            ->with('items.laptop', 'items.variant')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $totalWeight = $cart->items->sum(fn ($item) => $item->laptop->weight * $item->quantity);

        return view('orders.checkout', compact('cart', 'totalWeight'));
    }

    public function placeOrder(Request $request)
    {
        $cart = Cart::where('user_id', auth()->id())
            ->with('items.laptop', 'items.variant')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Validasi stock availability
        foreach ($cart->items as $item) {
            $laptop = $item->laptop;
            if ($laptop->stock < $item->quantity) {
                return redirect()->back()->with('error', "Insufficient stock for {$laptop->name}.");
            }
            if ($item->variant && $item->variant->stock < $item->quantity) {
                return redirect()->back()->with('error', "Insufficient stock for variant {$item->variant->name}.");
            }
        }

        $validated = $request->validate([
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_province' => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:500',
            'shipping_cost' => 'required|numeric|min:0',
            'shipping_courier' => 'required|string|max:50',
            'shipping_service' => 'required|string|max:100',
            'shipping_etd' => 'nullable|string|max:50',
            'shipping_city_id' => 'required|string|max:20',
            'shipping_city_name' => 'required|string|max:255',
            'shipping_province_name' => 'required|string|max:255',
        ]);

        $subtotal = $cart->total;
        $tax = round($subtotal * (float) config('settings.tax_rate', 11) / 100, 2);
        $shippingCost = (float) $validated['shipping_cost'];
        $total = $subtotal + $tax + $shippingCost;

        $order = Order::create([
            'user_id' => auth()->id(),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping_cost' => $shippingCost,
            'total' => $total,
            'status' => 'pending',
            'payment_method' => 'xendit',
            'payment_status' => 'unpaid',
            'notes' => $validated['notes'] ?? null,
            'shipping_address' => $validated['shipping_address'],
            'shipping_city' => $validated['shipping_city'],
            'shipping_province' => $validated['shipping_province'],
            'shipping_postal_code' => $validated['shipping_postal_code'],
            'shipping_phone' => $validated['shipping_phone'],
            'shipping_courier' => $validated['shipping_courier'],
            'shipping_service' => $validated['shipping_service'],
            'shipping_etd' => $validated['shipping_etd'] ?? null,
            'shipping_city_id' => $validated['shipping_city_id'],
            'shipping_city_name' => $validated['shipping_city_name'],
            'shipping_province_name' => $validated['shipping_province_name'],
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'laptop_id' => $item->laptop_id,
                'laptop_variant_id' => $item->laptop_variant_id,
                'product_name' => $item->laptop->name,
                'variant_name' => $item->variant?->name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'subtotal' => $item->subtotal,
            ]);

            // Kurangi stock
            $item->laptop->decrement('stock', $item->quantity);
            if ($item->variant) {
                $item->variant->decrement('stock', $item->quantity);
            }
        }

        $cart->items()->delete();
        $cart->delete();

        // Buat Xendit Invoice
        try {
            $xenditService = app(\App\Services\XenditService::class);
            $invoice = $xenditService->createInvoice($order);
            $order->update([
                'xendit_invoice_id' => $invoice['id'],
                'xendit_invoice_url' => $invoice['invoice_url'],
                'xendit_expiry' => $invoice['expiry_date'],
            ]);
            // Redirect ke Xendit
            return redirect()->away($invoice['invoice_url']);
        } catch (\Exception $e) {
            Log::error('Xendit invoice creation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('orders.confirmation', $order)
                ->with('warning', 'Order berhasil dibuat, tetapi gagal menghubungi gateway pembayaran. Silakan hubungi admin.');
        }
    }

    public function xenditCallback(Request $request, Order $order): RedirectResponse
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $status = $request->query('status');

        if ($status === 'success' || $status === 'paid') {
            // Verify via Xendit API
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

        $order->load('items.laptop', 'items.variant');

        return view('orders.confirmation', compact('order'));
    }

    public function history()
    {
        $orders = Order::byUser(auth()->id())
            ->with('items.laptop')
            ->latest()
            ->paginate(10);

        return view('orders.history', compact('orders'));
    }
}
