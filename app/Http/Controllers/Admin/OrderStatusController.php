<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderDeliveredMail;
use App\Mail\OrderShippedMail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderStatusController extends Controller
{
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        // Handle tracking fields when status changes to shipped
        if ($validated['status'] === 'shipped' && $order->status !== 'shipped') {
            $request->validate([
                'tracking_number' => 'required|string|max:100',
            ]);
            $order->update([
                'tracking_number' => $request->tracking_number,
                'shipped_at' => now(),
                'status' => 'shipped',
            ]);
            $order->addTrackingEvent('shipped', 'Paket telah dikirim', 'Gudang ZLM.ID');
            // Kirim email notifikasi
            Mail::to($order->user->email)->queue(new OrderShippedMail($order));
        }
        // Handle delivered status
        elseif ($validated['status'] === 'delivered' && $order->status !== 'delivered') {
            $order->update(['status' => 'delivered']);
            $order->addTrackingEvent('delivered', 'Paket telah diterima', $order->shipping_city_name);
            // Kirim email notifikasi
            Mail::to($order->user->email)->queue(new OrderDeliveredMail($order));
        }
        // Handle processing status
        elseif ($validated['status'] === 'processing' && $order->status !== 'processing') {
            $order->update(['status' => 'processing']);
            $order->addTrackingEvent('processing', 'Pesanan sedang diproses', 'Gudang ZLM.ID');
        }
        // Handle pending
        elseif ($validated['status'] === 'pending' && $order->status !== 'pending') {
            $order->update(['status' => 'pending']);
        }
        // Handle cancelled
        elseif ($validated['status'] === 'cancelled' && $order->status !== 'cancelled') {
            $order->update(['status' => 'cancelled']);
            $order->addTrackingEvent('cancelled', 'Pesanan dibatalkan', null);
        }

        return redirect()->back()->with('success', "Order status updated to {$validated['status']}.");
    }

    public function tracking(Order $order)
    {
        return view('admin.orders.tracking', compact('order'));
    }
}
