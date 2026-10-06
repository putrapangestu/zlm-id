<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\WinpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class WinpayWebhookController extends Controller
{
    public function qris(Request $request, WinpayService $winpay): JsonResponse
    {
        return $this->process(
            $request,
            $winpay,
            '/webhooks/winpay/v1.0/qr/qr-mpm-notify',
            'winpay_qris',
            'originalPartnerReferenceNo',
            'amount',
            'latestTransactionStatus',
            '00',
            '2005200',
            'success',
        );
    }

    public function virtualAccount(Request $request, WinpayService $winpay): JsonResponse
    {
        return $this->process(
            $request,
            $winpay,
            '/webhooks/winpay/v1.0/transfer-va/payment',
            'winpay_va',
            'trxId',
            'paidAmount',
            null,
            null,
            '2002500',
            'Successful',
        );
    }

    private function process(
        Request $request,
        WinpayService $winpay,
        string $signaturePath,
        string $paymentMethod,
        string $referenceField,
        string $amountField,
        ?string $statusField,
        ?string $successStatus,
        string $responseCode,
        string $responseMessage,
    ): JsonResponse {
        $payload = $request->json()->all();
        $headers = [
            'partner_id' => $request->header('X-Partner-ID'),
            'timestamp' => $request->header('X-Timestamp'),
            'signature' => $request->header('X-Signature'),
        ];

        try {
            if (! $winpay->verifyCallback($payload, $headers, $signaturePath)) {
                Log::warning('Winpay callback rejected: invalid signature', [
                    'path' => $signaturePath,
                    'partner_id' => $headers['partner_id'],
                ]);

                return response()->json(['responseCode' => '4010000', 'responseMessage' => 'Invalid signature'], 401);
            }
        } catch (RuntimeException $exception) {
            Log::error('Winpay callback verification is not configured', [
                'path' => $signaturePath,
                'error' => $exception->getMessage(),
            ]);

            return response()->json(['responseCode' => '5000000', 'responseMessage' => 'Callback verification unavailable'], 500);
        }

        $reference = $payload[$referenceField] ?? null;
        if (! is_string($reference) || $reference === '') {
            Log::warning('Winpay callback rejected: missing transaction reference', ['path' => $signaturePath]);

            return response()->json(['responseCode' => '4000000', 'responseMessage' => 'Missing transaction reference'], 400);
        }

        $order = Order::query()
            ->where('winpay_reference', $reference)
            ->where('payment_method', $paymentMethod)
            ->first();

        if (! $order) {
            Log::warning('Winpay callback order not found', [
                'reference' => $reference,
                'payment_method' => $paymentMethod,
            ]);

            return response()->json(['responseCode' => '4040000', 'responseMessage' => 'Order not found'], 404);
        }

        if ($statusField !== null && ($payload[$statusField] ?? null) !== $successStatus) {
            Log::info('Winpay callback received non-success status', [
                'order_id' => $order->id,
                'status' => $payload[$statusField] ?? null,
            ]);

            return response()->json(['responseCode' => $responseCode, 'responseMessage' => $responseMessage]);
        }

        $paymentAmount = $payload[$amountField] ?? null;
        $amount = is_array($paymentAmount) ? ($paymentAmount['value'] ?? null) : null;
        $currency = is_array($paymentAmount) ? ($paymentAmount['currency'] ?? null) : null;
        if ($currency !== 'IDR' || $this->normalizeAmount($amount) !== $this->normalizeAmount((string) $order->total)) {
            Log::warning('Winpay callback rejected: amount mismatch', [
                'order_id' => $order->id,
                'currency' => $currency,
                'amount' => $amount,
            ]);

            return response()->json(['responseCode' => '4000001', 'responseMessage' => 'Amount mismatch'], 400);
        }

        if ($order->payment_status !== 'paid') {
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
                'paid_at' => now(),
            ]);
        }

        Log::info('Winpay callback processed', [
            'order_id' => $order->id,
            'payment_method' => $paymentMethod,
        ]);

        return response()->json([
            'responseCode' => $responseCode,
            'responseMessage' => $responseMessage,
        ]);
    }

    private function normalizeAmount(mixed $amount): ?string
    {
        if (! is_string($amount) && ! is_int($amount) && ! is_float($amount)) {
            return null;
        }

        $amount = (string) $amount;
        if (! preg_match('/^\d+(?:\.\d{1,2})?$/', $amount)) {
            return null;
        }

        [$whole, $fraction] = array_pad(explode('.', $amount, 2), 2, '');

        return ltrim($whole, '0').'.'.str_pad($fraction, 2, '0');
    }
}
