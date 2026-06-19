<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Inter', Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 24px; }
        .card { background: white; border-radius: 16px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .header { text-align: center; margin-bottom: 24px; }
        .header h1 { color: #DF5E1D; font-size: 24px; margin: 0; }
        .order-details { background: #f9f9f9; border-radius: 12px; padding: 20px; margin: 20px 0; }
        .order-details table { width: 100%; border-collapse: collapse; }
        .order-details td { padding: 8px 0; font-size: 14px; color: #363230; }
        .order-details td:last-child { text-align: right; font-weight: 600; }
        .btn { display: inline-block; background: #DF5E1D; color: white; text-decoration: none; padding: 12px 32px; border-radius: 8px; font-weight: 500; margin-top: 16px; }
        .btn-secondary { display: inline-block; background: white; color: #DF5E1D; text-decoration: none; padding: 12px 32px; border-radius: 8px; font-weight: 500; margin-top: 16px; border: 1px solid #DF5E1D; }
        .footer { text-align: center; margin-top: 24px; color: #9ca3af; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>ZLM.ID</h1>
                <p style="color: #6b7280; font-size: 16px;">Paket Telah Tiba! ✅</p>
            </div>
            
            <p style="font-size: 14px; color: #363230;">Halo <strong>{{ $order->user->name }}</strong>,</p>
            <p style="font-size: 14px; color: #6b7280;">Pesanan kamu sudah berhasil diterima. Terima kasih sudah berbelanja di ZLM.ID!</p>
            
            <div class="order-details">
                <table>
                    <tr><td>Order Number</td><td><strong>{{ $order->order_number }}</strong></td></tr>
                    <tr><td>Tanggal Diterima</td><td>{{ now()->format('d M Y H:i') }}</td></tr>
                    <tr><td>Total</td><td><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></td></tr>
                </table>
            </div>

            <div style="text-align: center; margin-top: 24px;">
                <p style="font-size: 14px; color: #6b7280; margin-bottom: 16px;">Bantu kami dengan memberikan review untuk produk yang kamu beli!</p>
                @if($order->items->first()?->laptop)
                <a href="{{ route('landing.detail', $order->items->first()?->laptop) }}" class="btn">Beri Review</a>
                @else
                <a href="{{ route('landing.search') }}" class="btn">Belanja Lagi</a>
                @endif
            </div>
            
            <div class="footer">
                <p>ZLM.ID | support@zlm.id</p>
                <p>© {{ date('Y') }} ZLM.ID. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
