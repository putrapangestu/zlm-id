<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Cart;
use App\Models\Laptop;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        $cart->load('items.laptop', 'items.addon');

        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'laptop_id' => 'required|exists:laptops,id',
            'addon_id' => 'nullable|exists:addons,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $laptop = Laptop::findOrFail($data['laptop_id']);

        if ($laptop->stock < 1) {
            return redirect()->back()->withErrors(['laptop_id' => 'Stok produk ini sedang habis.'])->withInput();
        }

        $addon = !empty($data['addon_id']) ? Addon::find($data['addon_id']) : null;
        $addonPrice = $addon ? (float)$addon->price : 0;

        $price = $laptop->final_price;
        $cart = $this->getCart();

        $existing = $cart->items()
            ->where('laptop_id', $laptop->id)
            ->where('addon_id', $addon?->id)
            ->first();

        if ($existing) {
            $existing->increment('quantity', $data['quantity']);
        } else {
            $cart->items()->create([
                'laptop_id' => $laptop->id,
                'laptop_variant_id' => null,
                'addon_id' => $addon?->id,
                'addon_price' => $addonPrice,
                'quantity' => $data['quantity'],
                'unit_price' => $price,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Laptop & paket bundle berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $item = \App\Models\CartItem::findOrFail($id);
        $item->update($data);

        return redirect()->route('cart.index')->with('success', 'Keranjang belanja berhasil diperbarui.');
    }

    public function remove($id)
    {
        $item = \App\Models\CartItem::findOrFail($id);
        $item->delete();

        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
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
