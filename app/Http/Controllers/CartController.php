<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Laptop;
use App\Models\LaptopVariant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        $cart->load('items.laptop', 'items.variant');

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'laptop_id' => 'required|exists:laptops,id',
            'variant_id' => 'nullable|exists:laptop_variants,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $laptop = Laptop::findOrFail($data['laptop_id']);
        $variant = null;
        $price = $laptop->price;

        if (!empty($data['variant_id'])) {
            $variant = LaptopVariant::findOrFail($data['variant_id']);

            if ($variant->stock < 1) {
                return redirect()->back()->withErrors(['variant_id' => 'This variant is out of stock.'])->withInput();
            }

            $price += $variant->price_modifier;
        }

        $cart = $this->getCart();

        $existing = $cart->items()
            ->where('laptop_id', $laptop->id)
            ->where('laptop_variant_id', $data['variant_id'])
            ->first();

        if ($existing) {
            $existing->increment('quantity', $data['quantity']);
        } else {
            $cart->items()->create([
                'laptop_id' => $laptop->id,
                'laptop_variant_id' => $data['variant_id'],
                'quantity' => $data['quantity'],
                'unit_price' => $price,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item added to cart.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $item = \App\Models\CartItem::findOrFail($id);
        $item->update($data);

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function remove($id)
    {
        $item = \App\Models\CartItem::findOrFail($id);
        $item->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    private function getCart(): Cart
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(
                ['user_id' => auth()->id()],
                ['session_id' => session()->getId()]
            );
        }

        $sessionId = session()->getId();

        return Cart::firstOrCreate(
            ['session_id' => $sessionId],
            ['user_id' => null]
        );
    }
}
