<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BUKTI RESTOCK {{ $restock->restock_number }} — ZLM.ID</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Courier New', Courier, Consolas, monospace;
            font-size: 11pt;
            color: #000;
            background: #fff;
            padding: 15px;
            line-height: 1.3;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            border: 1px dashed #444;
            padding: 15px 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header h1 {
            font-size: 15pt;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .header p {
            font-size: 9.5pt;
        }
        .doc-title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 10px 0;
            letter-spacing: 0.5px;
        }
        .meta-grid {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 10pt;
        }
        .meta-col {
            width: 48%;
        }
        .meta-row {
            display: flex;
            margin-bottom: 3px;
        }
        .meta-label {
            width: 140px;
        }
        .meta-val {
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 15px;
            font-size: 10pt;
        }
        th, td {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 5px 6px;
        }
        th {
            text-align: left;
            font-weight: bold;
            background: #f9f9f9;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .total-section {
            border-top: 2px solid #000;
            padding-top: 8px;
            margin-bottom: 25px;
            font-size: 11pt;
            font-weight: bold;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
            text-align: center;
            font-size: 10pt;
        }
        .sig-box {
            width: 30%;
        }
        .sig-space {
            height: 60px;
        }
        .footer-note {
            margin-top: 20px;
            border-top: 1px dotted #888;
            padding-top: 5px;
            font-size: 8.5pt;
            text-align: center;
            color: #333;
        }
        .print-btn-bar {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-print {
            font-family: sans-serif;
            background: #DF5E1D;
            color: white;
            border: none;
            padding: 10px 24px;
            font-size: 13px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
        }
        @media print {
            .print-btn-bar {
                display: none;
            }
            .container {
                border: none;
                padding: 0;
                max-width: 100%;
            }
            body {
                padding: 0;
            }
            @page {
                size: 216mm 140mm; /* Continuous Form Half-Letter / 9.5x5.5 or 9.5x11 */
                margin: 5mm;
            }
        }
    </style>
</head>
<body>

    <div class="print-btn-bar">
        <button class="btn-print" onclick="window.print()">🖨️ CETAK FORMAT CONTINUOUS FORM / DOT MATRIX</button>
    </div>

    <div class="container">
        <div class="header">
            <h1>{{ config('settings.dotmatrix_header', 'ZLM.ID — PUSAT LAPTOP BERKUALITAS') }}</h1>
            <p>{{ config('settings.dotmatrix_address', 'Jl. Soekarno Hatta No. 45, Malang | Telp/WA: 0812-3456-7890') }}</p>
        </div>

        <div class="doc-title">BUKTI PENERIMAAN BARANG & RESTOCK INVENTORI</div>

        <div class="meta-grid">
            <div class="meta-col">
                <div class="meta-row">
                    <span class="meta-label">NO. RESTOCK</span>: <span class="meta-val">{{ $restock->restock_number }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">TANGGAL TERIMA</span>: <span>{{ $restock->purchase_date->format('d/m/Y') }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">NO. FAKTUR VENDOR</span>: <span>{{ $restock->invoice_number ?? '-' }}</span>
                </div>
            </div>
            <div class="meta-col">
                <div class="meta-row">
                    <span class="meta-label">NAMA SUPPLIER</span>: <span class="meta-val">{{ $restock->supplier_name }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">TELP / WA VENDOR</span>: <span>{{ $restock->supplier_phone ?? '-' }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-label">PETUGAS GUDANG</span>: <span>{{ $restock->creator->name }}</span>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 30px;" class="text-center">NO</th>
                    <th>NAMA BARANG / TIPE LAPTOP</th>
                    <th>VARIAN & SPEK</th>
                    <th style="width: 60px;" class="text-center">QTY</th>
                    <th style="width: 140px;" class="text-right">HARGA BELI (HPP)</th>
                    <th style="width: 150px;" class="text-right">SUBTOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($restock->items as $idx => $item)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td><strong>{{ $item->laptop->name }}</strong> ({{ $item->laptop->brand }})</td>
                    <td>{{ $item->variant?->name ?? 'Standard' }} ({{ $item->variant?->ram ?? $item->laptop->ram }} / {{ $item->variant?->storage ?? $item->laptop->storage }})</td>
                    <td class="text-center font-bold">{{ $item->quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->purchase_price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <div style="display: flex; justify-content: space-between;">
                <span>TOTAL BARANG: {{ $restock->items->sum('quantity') }} UNIT (STATUS: PENDING QUALITY CONTROL)</span>
                <span>TOTAL HPP: Rp {{ number_format($restock->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        @if($restock->notes)
        <div style="font-size: 9.5pt; margin-bottom: 15px;">
            <strong>Catatan:</strong> {{ $restock->notes }}
        </div>
        @endif

        <div class="signatures">
            <div class="sig-box">
                <p>Pengirim / Supplier,</p>
                <div class="sig-space"></div>
                <p>( {{ $restock->supplier_name }} )</p>
            </div>
            <div class="sig-box">
                <p>Penerima / Gudang,</p>
                <div class="sig-space"></div>
                <p>( {{ $restock->creator->name }} )</p>
            </div>
            <div class="sig-box">
                <p>Mengetahui / Admin,</p>
                <div class="sig-space"></div>
                <p>( ........................ )</p>
            </div>
        </div>

        <div class="footer-note">
            {{ config('settings.dotmatrix_footer', 'Barang yang sudah diterima tercatat resmi di sistem ZLM.ID dan wajib lolos Quality Control sebelum dijual.') }}
            <br>Dicetak pada: {{ date('d/m/Y H:i:s') }}
        </div>
    </div>

</body>
</html>
