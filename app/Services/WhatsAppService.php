<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProductReturn;
use App\Models\Restock;
use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function isEnabled(): bool
    {
        $enabled = Setting::getValue('wa_notification_enabled', '1');
        return filter_var($enabled, FILTER_VALIDATE_BOOLEAN) || $enabled === '1';
    }

    public function sendMessage(string $targetPhone, string $message): array
    {
        if (!$this->isEnabled()) {
            Log::info("WhatsApp notification is disabled. Skipping message to: {$targetPhone}");
            return ['status' => false, 'success' => false, 'message' => 'WhatsApp notification is disabled in settings.'];
        }

        // Clean phone number (convert 08xx to 628xx)
        $phone = preg_replace('/[^0-9]/', '', $targetPhone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $provider = Setting::getValue('wa_provider', 'fonnte');
        $token = Setting::getValue('wa_api_token', '');

        if (empty($token) || $token === 'sample_wa_token_zlm') {
            Log::warning("WhatsApp token is not configured or in sample mode. Message to {$phone}: \n{$message}");
            return ['status' => true, 'message' => 'Notification logged (Sample / Demo mode).'];
        }

        try {
            if ($provider === 'fonnte') {
                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->timeout(10)->post('https://api.fonnte.com/send', [
                    'target' => $phone,
                    'message' => $message,
                ]);

                return ['status' => $response->successful(), 'response' => $response->json()];
            } elseif ($provider === 'wablas') {
                $response = Http::withHeaders([
                    'Authorization' => $token,
                ])->timeout(10)->post('https://borneo.wablas.com/api/send-message', [
                    'phone' => $phone,
                    'message' => $message,
                ]);

                return ['status' => $response->successful(), 'response' => $response->json()];
            } else {
                // Generic Webhook
                $endpoint = Setting::getValue('wa_webhook_url', '');
                if ($endpoint) {
                    $response = Http::timeout(10)->post($endpoint, [
                        'phone' => $phone,
                        'message' => $message,
                        'token' => $token,
                    ]);
                    return ['status' => $response->successful(), 'response' => $response->json()];
                }
            }

            return ['status' => true, 'message' => 'Message processed.'];
        } catch (\Exception $e) {
            Log::error("Failed to send WhatsApp to {$phone}: " . $e->getMessage());
            return ['status' => false, 'error' => $e->getMessage()];
        }
    }

    public function sendOrderCreated(Order $order): void
    {
        if (!$this->isEnabled() || Setting::getValue('wa_notify_order_created', '1') !== '1') {
            return;
        }

        $customerPhone = $order->shipping_phone ?? $order->user?->phone_number;
        $customerName = $order->user?->name ?? 'Pelanggan ZLM';
        $totalFormatted = 'Rp ' . number_format($order->total, 0, ',', '.');
        $storeName = Setting::getValue('store_name', 'ZLM.ID');

        $message = "🎉 *Halo {$customerName}! Pesanan Anda Berhasil Dibuat*\n\n"
            . "Terima kasih telah berbelanja di *{$storeName}*.\n"
            . "• No. Pesanan: *{$order->order_number}*\n"
            . "• Total: *{$totalFormatted}*\n"
            . "• Metode Pembayaran: *" . strtoupper($order->payment_method) . "*\n"
            . "• Status: *" . strtoupper($order->status) . "*\n\n"
            . "Silakan selesaikan pembayaran untuk memproses pengiriman unit laptop bergaransi Anda.\n"
            . "Terima kasih telah mempercayai {$storeName}!";

        if ($customerPhone) {
            $this->sendMessage($customerPhone, $message);
        }

        // Notify Admin
        $adminPhone = Setting::getValue('wa_admin_phone', '');
        if ($adminPhone) {
            $adminMessage = "🔔 *PESANAN BARU MASUK*\n\n"
                . "• No. Pesanan: *{$order->order_number}*\n"
                . "• Pelanggan: {$customerName}\n"
                . "• Total: {$totalFormatted}\n"
                . "• Sumber: *" . strtoupper($order->source) . "*\n"
                . "Silakan cek dashboard admin ZLM.ID.";
            $this->sendMessage($adminPhone, $adminMessage);
        }
    }

    public function sendPaymentSuccess(Order $order): void
    {
        if (!$this->isEnabled() || Setting::getValue('wa_notify_payment_success', '1') !== '1') {
            return;
        }

        $customerPhone = $order->shipping_phone ?? $order->user?->phone_number;
        $customerName = $order->user?->name ?? 'Pelanggan ZLM';
        $storeName = Setting::getValue('store_name', 'ZLM.ID');

        $message = "✅ *Pembayaran Terkonfirmasi!*\n\n"
            . "Halo {$customerName}, pembayaran untuk pesanan *{$order->order_number}* telah kami terima.\n"
            . "Pesanan Anda sedang dipersiapkan dan dicek Quality Control akhir sebelum dikirim.\n\n"
            . "Salam,\n*Tim {$storeName}*";

        if ($customerPhone) {
            $this->sendMessage($customerPhone, $message);
        }
    }

    public function sendOrderShipped(Order $order): void
    {
        if (!$this->isEnabled() || Setting::getValue('wa_notify_order_shipped', '1') !== '1') {
            return;
        }

        $customerPhone = $order->shipping_phone ?? $order->user?->phone_number;
        $customerName = $order->user?->name ?? 'Pelanggan ZLM';
        $courier = strtoupper($order->shipping_courier ?? 'Kurir');
        $resi = $order->tracking_number ?? '-';

        $message = "🚚 *Pesanan Sedang Dikirim!*\n\n"
            . "Halo {$customerName}, pesanan *{$order->order_number}* telah diserahkan ke jasa ekspedisi.\n"
            . "• Ekspedisi: *{$courier}*\n"
            . "• No. Resi: *{$resi}*\n\n"
            . "Anda dapat melacak status pengiriman melalui menu Lacak Pesanan di website ZLM.ID.\nTerima kasih!";

        if ($customerPhone) {
            $this->sendMessage($customerPhone, $message);
        }
    }

    public function sendRestockAlert(Restock $restock): void
    {
        if (!$this->isEnabled() || Setting::getValue('wa_notify_restock', '1') !== '1') {
            return;
        }

        $adminPhone = Setting::getValue('wa_admin_phone', '');
        if (!$adminPhone) return;

        $totalQty = $restock->items()->sum('quantity');
        $totalAmount = 'Rp ' . number_format($restock->total_amount, 0, ',', '.');

        $message = "📦 *RESTOCK BARANG MASUK*\n\n"
            . "• No. Restock: *{$restock->restock_number}*\n"
            . "• Supplier: {$restock->supplier_name}\n"
            . "• Total Unit: *{$totalQty} unit (Pending QC)*\n"
            . "• Total Nilai: {$totalAmount}\n\n"
            . "Unit barang telah masuk sistem dan siap untuk proses inspeksi QC sebelum dijual.";

        $this->sendMessage($adminPhone, $message);
    }

    public function sendReturnStatus(ProductReturn $return): void
    {
        if (!$this->isEnabled() || Setting::getValue('wa_notify_return_status', '1') !== '1') {
            return;
        }

        $customerPhone = $return->user?->phone_number;
        $customerName = $return->user?->name ?? 'Pelanggan';

        $statusText = match ($return->status) {
            'approved' => 'DISETUJUI. Silakan kirimkan unit sesuai instruksi admin.',
            'rejected' => 'DITOLAK. Mohon cek catatan dari admin di detail akun Anda.',
            'item_received' => 'UNIT TELAH DITERIMA oleh tim teknisi kami untuk pengecekan.',
            'completed' => 'SELESAI. Pengembalian/penggantian unit telah tuntas.',
            default => strtoupper($return->status),
        };

        $message = "🔄 *Update Status Retur Barang*\n\n"
            . "Halo {$customerName}, permohonan retur *{$return->return_number}* saat ini: *{$statusText}*\n\n"
            . ($return->admin_notes ? "Catatan Admin:\n_{$return->admin_notes}_\n\n" : "")
            . "Terima kasih atas kerja samanya.";

        if ($customerPhone) {
            $this->sendMessage($customerPhone, $message);
        }
    }
}
