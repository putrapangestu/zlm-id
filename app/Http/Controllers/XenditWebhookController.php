<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\XenditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XenditWebhookController extends Controller
{
    public function handle(Request $request, XenditService $xendit): JsonResponse
    {
        Log::info('Xendit webhook received', ['payload' => $request->all()]);

        // Verify callback token
        $token = config('xendit.webhook_verification_token');
        if ($token) {
            $callbackToken = $request->header('x-callback-token');
            if (!$callbackToken || $callbackToken !== $token) {
                Log::warning('Xendit webhook: invalid callback token');
                return response()->json(['success' => false, 'error' => 'Invalid token'], 401);
            }
        }

        // Parse payload
        $externalId = $request->input('external_id');
        if (!$externalId) {
            Log::warning('Xendit webhook: no external_id');
            return response()->json(['success' => false, 'error' => 'No external_id'], 400);
        }

        $order = Order::find($externalId);
        if (!$order) {
            Log::warning('Xendit webhook: order not found', ['external_id' => $externalId]);
            return response()->json(['success' => false, 'error' => 'Order not found'], 404);
        }

        // Process status
        $status = $request->input('status', '');
        switch ($status) {
            case 'PAID':
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing',
                    'paid_at' => now(),
                ]);
                Log::info('Xendit webhook: order paid', ['order_id' => $order->id]);
                break;

            case 'EXPIRED':
                $order->update([
                    'payment_status' => 'expired',
                ]);
                Log::info('Xendit webhook: order expired', ['order_id' => $order->id]);
                break;

            case 'FAILED':
                $order->update([
                    'payment_status' => 'failed',
                ]);
                Log::info('Xendit webhook: order failed', ['order_id' => $order->id]);
                break;

            default:
                Log::warning('Xendit webhook: unhandled status', [
                    'status' => $status,
                    'order_id' => $order->id,
                ]);
        }

        return response()->json(['success' => true]);
    }
}
