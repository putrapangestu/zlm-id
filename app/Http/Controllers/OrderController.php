<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;

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

        return view('orders.checkout', compact('cart'));
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

        $subtotal = $cart->total;
        $tax = round($subtotal * 0.11, 2);
        $total = $subtotal + $tax;

        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_province' => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_phone' => 'required|string|max:20',
            'notes' => 'nullable|string|max:500',
        ]);

        $order = Order::create([
            'user_id' => auth()->id(),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'notes' => $request->input('notes'),
            'shipping_address' => $request->shipping_address,
            'shipping_city' => $request->shipping_city,
            'shipping_province' => $request->shipping_province,
            'shipping_postal_code' => $request->shipping_postal_code,
            'shipping_phone' => $request->shipping_phone,
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

        return redirect()->route('orders.confirmation', $order)
            ->with('success', 'Order placed successfully!');
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
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('orders.history', compact('orders'));
    }
}
