<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Penjualan - {{ $order->order_number }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:ital,wght@0,400;0,700;1,400&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #e2e8f0;
            font-family: 'Courier Prime', 'Courier New', Courier, monospace;
            color: #1e293b;
            padding: 20px 10px;
            font-size: 12.5px;
            line-height: 1.35;
        }

        .no-print-bar {
            max-width: 900px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 20px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            border: none;
        }

        .btn-primary { background: #DF5E1D; color: #fff; }
        .btn-primary:hover { background: #c45218; }
        .btn-secondary { background: #f1f5f9; color: #475569; }

        /* Continuous Paper Canvas (Warna Pink Khas Kertas NCR Continuous Form) */
        .paper-container {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            background: #fdf2f4; /* Pinkish tint matching NCR paper in photo */
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border: 1px solid #fbcfe8;
            padding: 24px 32px;
        }

        /* Tractor feed perforated edge styling */
        .paper-container::before, .paper-container::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 18px;
            background-image: radial-gradient(#cbd5e1 3px, transparent 3.5px);
            background-size: 18px 18px;
            background-repeat: repeat-y;
            opacity: 0.7;
        }
        .paper-container::before { left: 4px; border-right: 1px dashed #f472b6; }
        .paper-container::after { right: 4px; border-left: 1px dashed #f472b6; }

        .invoice-content {
            margin: 0 16px;
            color: #09090b;
        }

        .top-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .store-brand {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            max-width: 50%;
        }

        .store-logo-text {
            font-size: 32px;
            font-weight: 900;
            color: #2563eb;
            line-height: 1;
            letter-spacing: -1px;
            font-family: sans-serif;
            border: 2px solid #2563eb;
            padding: 4px 6px;
            border-radius: 6px;
        }

        .store-details h2 {
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .store-details p {
            font-size: 11.5px;
            line-height: 1.25;
            color: #18181b;
        }

        .meta-table {
            border-collapse: collapse;
            font-size: 12px;
            line-height: 1.35;
        }

        .meta-table td {
            vertical-align: top;
            padding: 1px 4px;
        }

        .divider {
            border-top: 1px solid #18181b;
            margin: 6px 0;
        }

        .double-divider {
            border-top: 1px dashed #18181b;
            margin: 6px 0;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .items-table th {
            text-align: left;
            padding: 4px 6px;
            border-top: 1px solid #18181b;
            border-bottom: 1px solid #18181b;
            font-weight: 700;
            text-transform: uppercase;
        }

        .items-table td {
            padding: 3px 6px;
            vertical-align: top;
        }

        .item-spec {
            font-size: 11px;
            color: #27272a;
            padding-left: 20px;
        }

        .item-sn {
            font-size: 11px;
            font-weight: 700;
            padding-left: 20px;
        }

        /* Bottom Grid */
        .bottom-section {
            display: grid;
            grid-template-columns: 42% 23% 35%;
            gap: 8px;
            margin-top: 8px;
            font-size: 11.5px;
        }

        .signatures-col {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sig-row {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            text-align: center;
        }

        .sig-space {
            height: 48px;
        }

        .totals-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .totals-table td {
            padding: 1.5px 2px;
        }

        .terms {
            margin-top: 12px;
            padding-top: 6px;
            border-top: 1px dashed #52525b;
            font-size: 10.5px;
            line-height: 1.35;
        }

        .terms ul {
            list-style-type: none;
        }

        .terms li::before {
            content: "- ";
        }

        @page {
            size: A4 landscape;
            margin: 5mm;
        }

        @media print {
            * {
                box-sizing: border-box;
            }

            html,
            body {
                width: 100%;
                margin: 0;
                padding: 0;
                background: #fdf2f4;
            }

            .no-print-bar {
                display: none !important;
            }

            .paper-container {
                max-width: none;
                margin: 0;
                padding: 24px 32px;
                box-shadow: none;
                border: none;
                page-break-after: avoid;
            }

            .paper-container::before,
            .paper-container::after {
                display: none;
            }
        }
    </style>
</head>
<body>

    {{-- Controls --}}
    <div class="no-print-bar">
        <div>
            <strong style="font-size: 14px; color: #0f172a;">Cetak Faktur Penjualan Dot Matrix (Gambar 1)</strong>
            <p style="font-size: 12px; color: #64748b;">Format pas continuous form kertas rangkap ZLM 9.5 x 5.5 inch.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.transactions.show', $order) }}" class="btn btn-secondary">
                <iconify-icon icon="solar:arrow-left-linear"></iconify-icon>
                <span>Kembali</span>
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <iconify-icon icon="solar:printer-bold"></iconify-icon>
                <span>Cetak Faktur (Dot Matrix)</span>
            </button>
        </div>
    </div>

    {{-- Paper Container --}}
    <div class="paper-container">
        <div class="invoice-content">

            {{-- Top Header Row --}}
            <div class="top-row">
                <div class="store-brand">
                    <div class="store-logo-text">ZLM</div>
                    <div class="store-details">
                        <h2>FAKTUR PENJUALAN</h2>
                        <p><strong>{{ config('settings.store_name', 'ZLM.ID (Zona Laptop Malang)') }}</strong></p>
                        <p>{{ config('settings.store_address', 'Jl. Melati No.50 Kav-E, Kelurahan Lowokwaru, Kecamatan Lowokwaru, Kota Malang') }}</p>
                        <p>{{ config('settings.store_phone', '085-100-285-005') }}</p>
                    </div>
                </div>

                <div class="header-meta">
                    <table class="meta-table">
                        <tr>
                            <td>No Transaksi</td>
                            <td>:</td>
                            <td><strong>{{ $order->order_number }}</strong></td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>:</td>
                            <td>{{ $order->created_at->format('d/m/Y H.i.s') }}</td>
                        </tr>
                        <tr>
                            <td>Pelanggan</td>
                            <td>:</td>
                            <td><strong>{{ $order->user->name ?? 'Pelanggan Umum' }}</strong> - Customer</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>{{ $order->shipping_address ?: 'Ambil di Toko / Malang' }}</td>
                        </tr>
                        <tr>
                            <td>No. Telp.</td>
                            <td>:</td>
                            <td>{{ $order->user->phone ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Items Table --}}
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 4%;">No.</th>
                        <th style="width: 48%;">Nama Item</th>
                        <th style="width: 14%; text-align: center;">Jml Satuan</th>
                        <th style="width: 13%; text-align: right;">Harga</th>
                        <th style="width: 7%; text-align: right;">Pot</th>
                        <th style="width: 14%; text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $idx => $item)
                        @php
                            $laptop = $item->laptop;
                            $variant = $item->variant;
                            $productItem = $item->productItem;
                            $price = $item->unit_price ?: ($item->price ?: ($item->subtotal / max(1, $item->quantity)));
                            $discount = (float)($item->discount_amount ?? 0);
                        @endphp
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>
                                <strong>{{ strtoupper($item->product_name ?: ($laptop->name ?? 'LAPTOP')) }}</strong>
                            </td>
                            <td style="text-align: center;">{{ $item->quantity }} PCS</td>
                            <td style="text-align: right;">{{ number_format($price, 0, ',', '.') }}</td>
                            <td style="text-align: right;">{{ number_format($discount, 0, ',', '.') }}</td>
                            <td style="text-align: right;">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @if($laptop)
                            <tr>
                                <td></td>
                                <td colspan="5" class="item-spec">
                                    {{ strtoupper($variant?->storage ?? $laptop->storage) }} {{ strtoupper($variant?->ram ?? $laptop->ram) }} {{ strtoupper($laptop->display ?? '') }} {{ strtoupper($laptop->color ?? '') }}
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td colspan="5" class="item-sn">
                                    S/N &nbsp; {{ $productItem?->serial_number ?: ($productItem?->sku ?: ($laptop->sku ?: '-')) }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>

            <div class="divider"></div>

            {{-- Bottom Section --}}
            <div class="bottom-section">

                {{-- Left: Notes & Signatures --}}
                <div class="signatures-col">
                    <div>
                        <strong>Keterangan :</strong>
                        <span>{{ $order->notes ?: ('Garansi ' . ($order->items->first()?->laptop?->warranty ?: 'Toko Resmi 1 Bulan')) }}</span>
                    </div>

                    <div class="sig-row">
                        <div>
                            <div>Hormat Kami</div>
                            <div class="sig-space"></div>
                            <div><strong>ZLM.ID</strong></div>
                        </div>
                        <div>
                            <div>Penerima</div>
                            <div class="sig-space"></div>
                            <div><strong>{{ $order->user->name ?? 'Customer' }}-Customer</strong></div>
                        </div>
                    </div>
                </div>

                {{-- Middle: Quantity & Discount --}}
                <div>
                    <table class="meta-table">
                        <tr>
                            <td>Jml Item</td>
                            <td>:</td>
                            <td>{{ $order->items->sum('quantity') }}</td>
                        </tr>
                        <tr>
                            <td>Potongan</td>
                            <td>:</td>
                            <td>0 % &nbsp;&nbsp;&nbsp;&nbsp; 0</td>
                        </tr>
                    </table>
                </div>

                {{-- Right: Financial Breakdown --}}
                <div>
                    @php
                        $isCash = in_array(strtolower($order->payment_method), ['cash', 'tunai']);
                        $isDebit = in_array(strtolower($order->payment_method), ['xendit', 'transfer', 'debit', 'bank_transfer', 'qris', 'manual_transfer']);
                        $paidTotal = ($order->payment_status === 'paid') ? $order->total : 0;
                    @endphp
                    <table class="totals-table">
                        <tr>
                            <td>Sub Total</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Total Akhir</td>
                            <td>:</td>
                            <td style="text-align: right;"><strong>{{ number_format($order->total, 0, ',', '.') }}</strong></td>
                        </tr>
                        <tr>
                            <td>DP PO</td>
                            <td>:</td>
                            <td style="text-align: right;">0</td>
                        </tr>
                        <tr>
                            <td>Tunai</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ $isCash ? number_format($order->total, 0, ',', '.') : '0' }}</td>
                        </tr>
                        <tr>
                            <td>K. Debit</td>
                            <td>:</td>
                            <td style="text-align: right;">{{ $isDebit ? number_format($order->total, 0, ',', '.') : '0' }}</td>
                        </tr>
                        <tr>
                            <td>K. Kredit</td>
                            <td>:</td>
                            <td style="text-align: right;">0</td>
                        </tr>
                        <tr>
                            <td>Kembali</td>
                            <td>:</td>
                            <td style="text-align: right;">0</td>
                        </tr>
                    </table>
                </div>

            </div>

            {{-- Terms & Conditions Footer --}}
            <div class="terms">
                <ul>
                    <li>Barang yang sudah dibeli tidak dapat ditukar / dikembalikan dengan alasan apapun</li>
                    <li>Kami tidak bertanggung jawab atas Software / Data yang terinstall</li>
                    <li>Dead pixel, Software, dan Cacat fisik tidak dapat digaransikan</li>
                    <li>Dapatkan Diskon 5% jika telah melakukan transaksi sebanyak 3 kali</li>
                </ul>
            </div>

        </div>
    </div>

</body>
</html>
