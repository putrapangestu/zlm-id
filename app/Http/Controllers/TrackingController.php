<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        return view('landing.tracking');
    }

    public function trackByNumber(Request $request)
    {
        $validated = $request->validate([
            'reference' => 'required|string|max:255',
        ]);

        $order = Order::where('order_number', $validated['reference'])
            ->orWhere('tracking_number', $validated['reference'])
            ->first();

        if (!$order) {
            return redirect()->route('tracking.index')
                ->with('error', 'Order not found. Please check your order number or tracking number.');
        }

        if (auth()->check() && $order->user_id === auth()->id()) {
            return redirect()->route('tracking.show', $order);
        }

        return view('landing.tracking', compact('order'));
    }

    public function show(Order $order)
    {
        if (auth()->id() !== $order->user_id) {
            abort(403);
        }

        $order->load('items');
        return view('landing.tracking', compact('order'));
    }
}
