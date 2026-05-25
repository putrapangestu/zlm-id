<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $items = Wishlist::where('user_id', auth()->id())
            ->with('laptop.categories')
            ->latest()
            ->get();

        return view('wishlist.index', compact('items'));
    }

    public function toggle(Request $request)
    {
        $data = $request->validate([
            'laptop_id' => 'required|exists:laptops,id',
        ]);

        $existing = Wishlist::where('user_id', auth()->id())
            ->where('laptop_id', $data['laptop_id'])
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'removed']);
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'laptop_id' => $data['laptop_id'],
        ]);

        return response()->json(['status' => 'added']);
    }
}
