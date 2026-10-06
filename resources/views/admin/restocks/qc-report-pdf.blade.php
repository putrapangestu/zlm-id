<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Inspection Report — {{ $restock->restock_number }}</title>
    <style>
        @page { margin: 12mm; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #202938; font: 9px DejaVu Sans, sans-serif; }
        .report { page-break-after: always; }
        .report:last-child { page-break-after: auto; }
        .heading { border-bottom: 2px solid #df5e1d; padding-bottom: 8px; margin-bottom: 10px; }
        .heading h1 { margin: 0 0 3px; color: #df5e1d; font-size: 17px; }
        .heading p { margin: 0; color: #64748b; }
        h2 { margin: 12px 0 5px; padding-bottom: 3px; border-bottom: 1px solid #cbd5e1; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 7px; }
        th, td { border: 1px solid #cbd5e1; padding: 5px 6px; vertical-align: top; }
        th { background: #f1f5f9; text-align: left; font-weight: bold; }
        .meta td { width: 25%; }
        .label { display: block; margin-bottom: 2px; color: #64748b; font-size: 7px; font-weight: bold; text-transform: uppercase; }
        .value { font-weight: bold; }
        .muted { color: #64748b; }
        .pass { color: #15803d; font-weight: bold; }
        .fail { color: #b91c1c; font-weight: bold; }
        .grade { color: #df5e1d; font-size: 12px; font-weight: bold; }
        .two-col { width: 100%; table-layout: fixed; }
        .two-col td { width: 50%; }
        .signoff { margin-top: 20px; }
        .signoff td { height: 48px; text-align: center; vertical-align: bottom; }
        .small { font-size: 8px; }
    </style>
</head>
<body>
@foreach($items as $item)
    @php
        $checklist = $item->qc_checklist ?? [];
        $isMac = $item->inspection_platform === 'mac';
        $stateText = fn ($field) => match ($checklist[$field] ?? null) {
            'ok', 'match' => 'Normal / Sesuai',
            'minor' => 'Catatan Minor',
            'defect', 'mismatch' => 'Perlu Perhatian',
            default => 'Belum dicatat',
        };
        $noteText = fn ($field) => $checklist[$field . '_notes'] ?? '—';
        $ram = $item->variant?->ram ?: $item->laptop->ram;
        $storage = $item->variant?->storage ?: $item->laptop->storage;
        $graphics = $item->variant?->graphics ?: $item->laptop->graphics;
        $display = $item->variant?->display ?: $item->laptop->display;
    @endphp
    <article class="report">
        <header class="heading">
            <h1>INSPECTION REPORT {{ $isMac ? 'MACBOOK' : 'WINDOWS' }}</h1>
            <p>ZLM.ID — Laporan inspeksi perangkat &bull; Batch {{ $restock->restock_number }}</p>
        </header>

        <table class="meta">
            <tr>
                <td><span class="label">Inspection Date</span><span class="value">{{ $item->qc_at?->format('d/m/Y') ?? now()->format('d/m/Y') }}</span></td>
                <td><span class="label">Inspector</span><span class="value">{{ $item->inspector?->name ?? '—' }}</span></td>
                <td><span class="label">Issued</span><span class="value">{{ $item->qc_at?->format('d/m/Y') ?? '—' }}</span></td>
                <td><span class="label">Run Time</span><span class="value">—</span></td>
            </tr>
            <tr>
                <td><span class="label">Brand</span><span class="value">{{ $item->laptop->brand ?: '—' }}</span></td>
                <td colspan="2"><span class="label">Model</span><span class="value">{{ $item->laptop->name }}{{ $item->variant ? ' — ' . $item->variant->name : '' }}</span></td>
                <td><span class="label">{{ $isMac ? 'macOS' : 'OS' }}</span><span class="value">{{ $isMac ? 'macOS' : 'Windows' }}</span></td>
            </tr>
        </table>

        <h2>Surface &amp; Grade Result</h2>
        <table class="two-col">
            <tr>
                <td><span class="label">Surface</span><span class="value">{{ $stateText('body') }}</span><br><span class="muted small">{{ $noteText('body') }}</span></td>
                <td><span class="label">Rate (%)</span><span class="value">{{ $item->inspection_grade === 'A' ? '100' : ($item->inspection_grade === 'B' ? '85' : '70') }}%</span></td>
            </tr>
        </table>

        <h2>Function</h2>
        <table>
            <thead><tr><th>Komponen</th><th>Hasil inspeksi</th><th>Catatan</th></tr></thead>
            <tbody>
                <tr><td>Keyboard Button</td><td>{{ $stateText('keyboard') }}</td><td>{{ $noteText('keyboard') }}</td></tr>
                <tr><td>LCD Screen</td><td>{{ $stateText('screen') }}</td><td>{{ $noteText('screen') }}</td></tr>
                <tr><td>Speaker</td><td>{{ $stateText('speaker') }}</td><td>{{ $noteText('speaker') }}</td></tr>
                <tr><td>Webcam</td><td>{{ $stateText('webcam') }}</td><td>{{ $noteText('webcam') }}</td></tr>
                <tr><td>{{ $isMac ? 'USB C Port' : 'USB Port' }}</td><td>{{ $stateText('ports') }}</td><td>{{ $noteText('ports') }}</td></tr>
                <tr><td>Charging Port / Battery</td><td>{{ $stateText('battery') }}</td><td>{{ $noteText('battery') }}</td></tr>
                @unless($isMac)
                    <tr><td>Hinge</td><td>{{ $stateText('body') }}</td><td>{{ $noteText('body') }}</td></tr>
                @endunless
                <tr><td>Specification Check</td><td>{{ $stateText('specs') }}</td><td>{{ $noteText('specs') }}</td></tr>
            </tbody>
        </table>

        <h2>Device Info</h2>
        <table class="two-col">
            <tr>
                <td><span class="label">Processor / Model</span><span class="value">{{ $item->laptop->processor ?: '—' }}</span></td>
                <td><span class="label">Vendor / Base Clock</span><span class="value">{{ strtok($item->laptop->processor ?: '—', ' ') }}</span></td>
            </tr>
            <tr>
                <td><span class="label">RAM / Channel / Speed</span><span class="value">{{ $ram ?: '—' }}</span></td>
                <td><span class="label">Storage / Capacity</span><span class="value">{{ $storage ?: '—' }}</span></td>
            </tr>
            <tr>
                <td><span class="label">Graphics</span><span class="value">{{ $graphics ?: '—' }}</span></td>
                <td><span class="label">Screen Size / Resolution / Panel</span><span class="value">{{ $display ?: '—' }}</span></td>
            </tr>
            <tr>
                <td><span class="label">Battery / Health</span><span class="value">{{ $noteText('battery') }}</span></td>
                <td><span class="label">Model Number</span><span class="value">{{ $item->laptop->sku ?: '—' }}</span></td>
            </tr>
        </table>

        <table class="two-col">
            <tr>
                <td><span class="label">Grade Result</span><span class="grade">{{ $item->inspection_grade }} — QC LOLOS</span></td>
                <td><span class="label">Device Serial Number</span><span class="value">{{ $item->serial_number ?: '—' }}</span></td>
            </tr>
        </table>

        <h2>Additional Features &amp; Notes</h2>
        <table class="two-col">
            <tr>
                <td><span class="label">Fitur / Ports</span><span class="value">{{ $item->laptop->ports ?: $item->laptop->camera ?: '—' }}</span></td>
                <td><span class="label">Garansi</span><span class="value">{{ $item->laptop->warranty ?: '—' }}</span></td>
            </tr>
            <tr>
                <td colspan="2"><span class="label">Notes</span><span class="value">{{ $item->qc_notes ?: '—' }}</span></td>
            </tr>
            <tr>
                <td><span class="label">Restock / Supplier</span><span class="value">{{ $restock->restock_number }} / {{ $restock->supplier_name }}</span></td>
                <td><span class="label">SKU Unit</span><span class="value">{{ $item->sku ?: '—' }}</span></td>
            </tr>
        </table>

        <table class="signoff">
            <tr><td>Inspector<br><br><strong>{{ $item->inspector?->name ?? '—' }}</strong></td><td>QC Result<br><br><strong class="pass">PASSED</strong></td></tr>
        </table>
    </article>
@endforeach
</body>
</html>
