<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Hasil QC - {{ $item->sku ?? $item->laptop->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
            background: #f8fafc;
            padding: 24px;
            font-size: 11px;
            line-height: 1.4;
        }

        .paper {
            background: #ffffff;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 20mm;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border-radius: 4px;
            position: relative;
        }

        .no-print-bar {
            width: 210mm;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 18px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            border: none;
            transition: all 0.15s;
        }

        .btn-primary {
            background: #DF5E1D;
            color: white;
        }
        .btn-primary:hover { background: #c45218; }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
        }
        .btn-secondary:hover { background: #e2e8f0; }

        /* Document Header */
        .doc-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 14px;
            margin-bottom: 16px;
        }

        .brand-info h1 {
            font-size: 20px;
            font-weight: 800;
            color: #DF5E1D;
            letter-spacing: -0.5px;
            margin-bottom: 2px;
        }

        .brand-info p {
            color: #64748b;
            font-size: 10px;
            line-height: 1.35;
        }

        .cert-badge {
            text-align: right;
        }

        .cert-badge .title {
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
        }

        .cert-badge .meta {
            font-size: 10px;
            color: #64748b;
            margin-top: 3px;
        }

        .status-pill {
            display: inline-block;
            margin-top: 6px;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-pill.passed {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
        }

        /* Unit Info Card */
        .section-title {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 16px;
        }

        .info-cell .label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 2px;
        }

        .info-cell .val {
            font-size: 11px;
            font-weight: 700;
            color: #0f172a;
        }

        .mono {
            font-family: 'JetBrains Mono', monospace;
        }

        /* Inspection Table */
        .table-wrap {
            margin-bottom: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        th {
            background: #f1f5f9;
            color: #334155;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.5px;
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }

        td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        tr:nth-child(even) td {
            background: #fafafa;
        }

        .badge-ok {
            background: #dcfce7;
            color: #166534;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 9px;
            display: inline-block;
        }

        .badge-minor {
            background: #fef3c7;
            color: #92400e;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 9px;
            display: inline-block;
        }

        .badge-defect {
            background: #fee2e2;
            color: #991b1b;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 700;
            font-size: 9px;
            display: inline-block;
        }

        /* Signatures */
        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 30px;
            padding-top: 10px;
        }

        .sign-box {
            text-align: center;
        }

        .sign-role {
            font-size: 10px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 50px;
        }

        .sign-name {
            font-size: 11px;
            font-weight: 800;
            color: #0f172a;
            border-top: 1px dashed #94a3b8;
            display: inline-block;
            min-width: 180px;
            padding-top: 4px;
        }

        .footer-note {
            margin-top: 24px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            font-size: 8.5px;
            color: #94a3b8;
            text-align: center;
            line-height: 1.3;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            .no-print-bar {
                display: none !important;
            }
            .paper {
                box-shadow: none;
                margin: 0;
                padding: 10mm 15mm;
                width: 100%;
                min-height: auto;
            }
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
        }
    </style>
</head>
<body>

    {{-- Top Action Bar (Hidden when printing) --}}
    <div class="no-print-bar">
        <div>
            <strong style="font-size: 13px; color: #0f172a;">Lembar Quality Control Resmi (A4)</strong>
            <p style="font-size: 11px; color: #64748b;">Siap cetak atau simpan sebagai file PDF.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.qc.inspect', $item) }}" class="btn btn-secondary">
                <iconify-icon icon="solar:arrow-left-linear"></iconify-icon>
                <span>Kembali</span>
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <iconify-icon icon="solar:printer-bold"></iconify-icon>
                <span>Cetak Lembar QC (PDF)</span>
            </button>
        </div>
    </div>

    {{-- A4 Paper Content --}}
    <div class="paper">

        {{-- Header --}}
        <div class="doc-header">
            <div class="brand-info">
                <h1>{{ config('settings.store_name', 'ZONA LAPTOP MALANG') }}</h1>
                <p>{{ config('settings.store_address', 'Jl. Sigura - Gura No. 25, Lowokwaru, Kota Malang, Jawa Timur') }}</p>
                <p>WhatsApp: {{ config('settings.store_phone', '0812-3456-7890') }} &bull; Website: www.zonalaptop.id</p>
            </div>
            <div class="cert-badge">
                <div class="title">LEMBAR INSPEKSI QUALITY CONTROL</div>
                <div class="meta">No. QC: <span class="mono">QC-{{ strtoupper(substr($item->id, 0, 8)) }}</span></div>
                <div class="meta">Tanggal: {{ $item->qc_at ? $item->qc_at->format('d F Y, H:i') : now()->format('d F Y, H:i') }} WIB</div>
                <div>
                    @if($item->qc_status === 'passed')
                        <span class="status-pill passed">&#10003; Lolos QC (Siap Jual)</span>
                    @else
                        <span class="status-pill" style="background:#fee2e2; color:#991b1b;">Gagal QC (Defect)</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Identitas Unit & Spesifikasi --}}
        <div class="section-title">
            <span>1. Identitas & Spesifikasi Unit Laptop</span>
            <span class="mono" style="font-size: 10px; color: #DF5E1D;">SKU: {{ $item->sku ?? '-' }}</span>
        </div>

        <div class="info-grid">
            <div class="info-cell">
                <div class="label">Nama Model Laptop</div>
                <div class="val">{{ $item->laptop->name }}</div>
            </div>
            <div class="info-cell">
                <div class="label">Brand / Merek</div>
                <div class="val">{{ $item->laptop->brand }}</div>
            </div>
            <div class="info-cell">
                <div class="label">Nomor SKU Resmi</div>
                <div class="val mono">{{ $item->sku ?? '-' }}</div>
            </div>
            <div class="info-cell">
                <div class="label">Serial Number (SN)</div>
                <div class="val mono">{{ $item->serial_number ?? '-' }}</div>
            </div>
            <div class="info-cell">
                <div class="label">Processor (CPU)</div>
                <div class="val">{{ $item->laptop->processor }}</div>
            </div>
            <div class="info-cell">
                <div class="label">Kapasitas RAM</div>
                <div class="val">{{ $item->variant?->ram ?? $item->laptop->ram }}</div>
            </div>
            <div class="info-cell">
                <div class="label">Media Penyimpanan</div>
                <div class="val">{{ $item->variant?->storage ?? $item->laptop->storage }}</div>
            </div>
            <div class="info-cell">
                <div class="label">Layar & Resolusi</div>
                <div class="val">{{ $item->laptop->display ?? '14 Inch' }}</div>
            </div>
        </div>

        {{-- Checklist Inspeksi --}}
        @php
            $cl = $item->qc_checklist ?? [];
        @endphp

        <div class="section-title">
            <span>2. Hasil Pemeriksaan Fisik & Fungsional (7 Poin Standar)</span>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th style="width: 4%;">No</th>
                        <th style="width: 25%;">Komponen Diuji</th>
                        <th style="width: 18%;">Status Kelayakan</th>
                        <th style="width: 53%;">Catatan Teknis Pemeriksaan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align:center;">1</td>
                        <td><strong>Layar & Display</strong><br><span style="color:#64748b; font-size:8.5px;">Pixel, WS, Bleeding, Brightness</span></td>
                        <td>
                            @if(($cl['screen'] ?? '') === 'ok')
                                <span class="badge-ok">&#10003; Normal (100%)</span>
                            @elseif(($cl['screen'] ?? '') === 'minor')
                                <span class="badge-minor">Minor Defect</span>
                            @else
                                <span class="badge-defect">Rusak</span>
                            @endif
                        </td>
                        <td>{{ $cl['screen_notes'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">2</td>
                        <td><strong>Keyboard & Touchpad</strong><br><span style="color:#64748b; font-size:8.5px;">Tuts, Backlight, Gesture</span></td>
                        <td>
                            @if(($cl['keyboard'] ?? '') === 'ok')
                                <span class="badge-ok">&#10003; Normal Semua</span>
                            @else
                                <span class="badge-defect">Bermasalah</span>
                            @endif
                        </td>
                        <td>{{ $cl['keyboard_notes'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">3</td>
                        <td><strong>Baterai & Pengisian</strong><br><span style="color:#64748b; font-size:8.5px;">Health, Adapter, Daya Simpan</span></td>
                        <td>
                            @if(($cl['battery'] ?? '') === 'ok')
                                <span class="badge-ok">&#10003; Bagus (>80%)</span>
                            @elseif(($cl['battery'] ?? '') === 'minor')
                                <span class="badge-minor">Drop Sedang</span>
                            @else
                                <span class="badge-defect">Bocor / Mati</span>
                            @endif
                        </td>
                        <td>{{ $cl['battery_notes'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">4</td>
                        <td><strong>Kemulusan Fisik & Engsel</strong><br><span style="color:#64748b; font-size:8.5px;">Grade Body, Engsel, Baut</span></td>
                        <td>
                            @if(($cl['body'] ?? '') === 'ok')
                                <span class="badge-ok">&#10003; Grade A (Mulus)</span>
                            @elseif(($cl['body'] ?? '') === 'minor')
                                <span class="badge-minor">Grade B (Lecet Wajar)</span>
                            @else
                                <span class="badge-defect">Pecah / Retak</span>
                            @endif
                        </td>
                        <td>{{ $cl['body_notes'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">5</td>
                        <td><strong>Port I/O & Konektivitas</strong><br><span style="color:#64748b; font-size:8.5px;">USB, HDMI, WiFi, Bluetooth</span></td>
                        <td>
                            @if(($cl['ports'] ?? '') === 'ok')
                                <span class="badge-ok">&#10003; Normal Terdeteksi</span>
                            @else
                                <span class="badge-defect">Port Rusak</span>
                            @endif
                        </td>
                        <td>{{ $cl['ports_notes'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">6</td>
                        <td><strong>Webcam, Audio & Mic</strong><br><span style="color:#64748b; font-size:8.5px;">Kamera Jernih, Suara Stereo</span></td>
                        <td>
                            @if(($cl['webcam'] ?? '') === 'ok')
                                <span class="badge-ok">&#10003; Jernih & Normal</span>
                            @else
                                <span class="badge-defect">Rusak / Mati</span>
                            @endif
                        </td>
                        <td>{{ $cl['webcam_notes'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;">7</td>
                        <td><strong>Kesesuaian Hardware</strong><br><span style="color:#64748b; font-size:8.5px;">CPU, RAM, SSD vs Katalog</span></td>
                        <td>
                            @if(($cl['specs'] ?? '') === 'match')
                                <span class="badge-ok">&#10003; 100% Cocok</span>
                            @else
                                <span class="badge-defect">Mismatch</span>
                            @endif
                        </td>
                        <td>{{ $cl['specs_notes'] ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Riwayat Penggantian Sparepart (jika ada) --}}
        @if($item->parts && $item->parts->count() > 0)
            <div class="section-title">
                <span>3. Riwayat Pergantian Part & Biaya Tambahan</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th style="width: 17%;">SKU Sparepart</th>
                            <th style="width: 29%;">Nama Sparepart / Tindakan Servis</th>
                            <th style="width: 7%; text-align: center;">Qty</th>
                            <th style="width: 15%; text-align: right;">Harga Satuan</th>
                            <th style="width: 15%; text-align: right;">Total Harga</th>
                            <th style="width: 12%;">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($item->parts as $idx => $part)
                            <tr>
                                <td style="text-align:center;">{{ $idx + 1 }}</td>
                                <td class="mono">{{ $part->product?->sku ?: '-' }}</td>
                                <td><strong>{{ $part->part_name }}</strong></td>
                                <td style="text-align:center;" class="mono">{{ $part->quantity }}</td>
                                <td style="text-align:right;" class="mono">Rp {{ number_format((float) $part->unit_cost, 0, ',', '.') }}</td>
                                <td style="text-align:right;" class="mono">Rp {{ number_format((float) $part->total_cost, 0, ',', '.') }}</td>
                                <td>{{ $part->notes ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Catatan Kesimpulan Teknisi --}}
        <div class="section-title">
            <span>{{ $item->parts && $item->parts->count() > 0 ? '4' : '3' }}. Kesimpulan & Catatan Inspektor</span>
        </div>
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 14px; font-size: 10px; color: #334155; margin-bottom: 20px;">
            {{ $item->qc_notes ?: 'Unit telah melalui seluruh rangkaian pengujian standar Zona Laptop Malang dan dinyatakan layak untuk diperjualbelikan kepada konsumen dengan garansi toko resmi.' }}
        </div>

        {{-- Tanda Tangan --}}
        <div class="signatures">
            <div class="sign-box">
                <div class="sign-role">Teknisi / QC Inspector,</div>
                <div class="sign-name">{{ $item->inspector->name ?? 'Tim QC ZLM' }}</div>
            </div>
            <div class="sign-box">
                <div class="sign-role">Kepala Toko / Supervisor,</div>
                <div class="sign-name">( ............................................ )</div>
            </div>
        </div>

        {{-- Footer Note --}}
        <div class="footer-note">
            Dokumen ini dicetak otomatis oleh Sistem Manajemen ZLM.ID pada {{ now()->format('d/m/Y H:i:s') }} dan sah sebagai sertifikat inspeksi kelayakan fisik laptop.
        </div>

    </div>

</body>
</html>
