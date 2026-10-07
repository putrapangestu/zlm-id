<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Inspection Report — {{ $restock->restock_number }}</title>
    <style>
        @page { size: A4 portrait; margin: 6mm; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #202020; font: 6px DejaVu Sans, sans-serif; }
        .report { page-break-after: always; }
        .report:last-child { page-break-after: auto; }
        .heading { display: table; width: 100%; margin-bottom: 4px; }
        .brand, .report-title { display: table-cell; vertical-align: middle; color: #888; }
        .brand { width: 65%; font-size: 20px; font-weight: bold; line-height: .9; }
        .brand small { display: inline-block; margin-left: 2px; font-size: 7px; line-height: .95; }
        .report-title { text-align: right; font-size: 15px; font-weight: bold; line-height: .95; }
        table { width: 100%; margin: 0 0 4px; border-collapse: collapse; table-layout: fixed; background: #fff; }
        th, td { border: 1px solid #777; padding: 2px; vertical-align: middle; overflow-wrap: anywhere; }
        th { background: #ddd; text-align: left; font-weight: bold; }
        .main th, .main .label-cell { background: #e8e8e8; }
        .main .sideways { background: #f6ad75; text-align: center; font-weight: bold; }
        .main .center, .center { text-align: center; }
        .main .top-rule td { border-top: 2px solid #555; }
        .main .grade { background: #facc15; font-weight: bold; }
        .checks { white-space: nowrap; }
        .extra td, .extra th { padding: 3px; }
        .parts { page-break-inside: avoid; }
        .parts th { text-align: center; }
        .parts .money { text-align: right; white-space: nowrap; }
        .section-title { margin: 4px 0 2px; font-size: 7px; font-weight: bold; }
        .history-note { margin-top: 2px; font-size: 6px; }
        .footer { margin-top: 5px; text-align: right; color: #555; font-size: 6px; }
    </style>
</head>
<body>
@foreach($items as $item)
    @php
        $checklist = $item->qc_checklist ?? [];
        $isMac = $item->inspection_platform === 'mac';
        $received = $item->received_specs ?? [];
        $processor = array_key_exists('processor', $received) ? $received['processor'] : $item->laptop->processor;
        $ram = array_key_exists('ram', $received) ? $received['ram'] : ($item->variant?->ram ?: $item->laptop->ram);
        $ram2 = array_key_exists('ram_2', $received) ? $received['ram_2'] : $item->laptop->ram_2;
        $storage = array_key_exists('storage', $received) ? $received['storage'] : ($item->variant?->storage ?: $item->laptop->storage);
        $storage2 = array_key_exists('storage_2', $received) ? $received['storage_2'] : $item->laptop->storage_2;
        $graphics = array_key_exists('graphics', $received) ? $received['graphics'] : ($item->variant?->graphics ?: $item->laptop->graphics);
        $display = array_key_exists('display', $received) ? $received['display'] : ($item->variant?->display ?: $item->laptop->display);
        $date = $item->qc_at?->format('d F Y') ?? now()->format('d F Y');
        $grade = $item->inspection_grade;
        $rate = $grade === 'A' ? '100%' : ($grade === 'B' ? '85%' : '70%');
        $status = fn ($field) => match ($checklist[$field] ?? null) {
            'ok', 'match' => 'Normal / Sesuai',
            'minor' => 'Catatan Minor',
            'defect', 'mismatch' => 'Perlu Perhatian',
            default => 'Belum dicatat',
        };
        $mark = fn ($field, $expected) => ($checklist[$field] ?? null) === $expected ? '✓' : '☐';
        $note = fn ($field) => $checklist[$field . '_notes'] ?? '';
        $partsTotal = (float) $item->parts->sum('total_cost');
    @endphp
    <article class="report">
        <header class="heading">
            <div class="brand">ZLM <small>Zona<br>Laptop<br>Malang</small></div>
            <div class="report-title">Inspection<br>Report</div>
        </header>

        <table class="main">
            <colgroup>
                @for($column = 0; $column < 20; $column++)
                    <col style="width: {{ $column === 0 ? '5%' : ($column === 1 ? '10%' : '4.47%') }}">
                @endfor
            </colgroup>
            <tbody>
                <tr>
                    <th colspan="2">Inspection Date</th><td colspan="5">{{ $date }}</td>
                    <th colspan="5">Inspector</th><td colspan="7">{{ $item->inspector?->name ?? '—' }}</td><td></td>
                </tr>
                <tr>
                    <th colspan="2">Issued</th><td colspan="5">{{ $date }}</td>
                    <th colspan="5">Run Time</th><td colspan="7">—</td><td></td>
                </tr>
                <tr>
                    <td rowspan="2"></td><th>Brand</th><td colspan="2">{{ $item->laptop->brand ?: '—' }}</td>
                    <th colspan="3">Model</th><td colspan="8">{{ $item->laptop->name }}{{ $item->variant ? ' — ' . $item->variant->name : '' }}</td>
                    <th colspan="2">OS</th><td colspan="3">{{ $isMac ? 'macOS' : 'Windows' }}</td>
                </tr>
                <tr>
                    <th>Surface</th>
                    <td colspan="3">{{ $mark('body', 'ok') }} Normal</td>
                    <td colspan="3">{{ $mark('body', 'minor') }} Dent / Catatan Minor</td>
                    <td colspan="5">{{ $mark('body', 'defect') }} Crack</td>
                    <td colspan="4">☐ Busted</td>
                    <th colspan="2">Rate (%)</th><td>{{ $rate }}</td>
                </tr>

                <tr>
                    <td class="sideways" rowspan="7">FUNCTION</td>
                    <th>Keyboard Button</th>
                    <td colspan="3">{{ $mark('keyboard', 'ok') }} Functional</td>
                    <td colspan="5">{{ $mark('keyboard', 'minor') }} Malfunction</td>
                    <td colspan="7">{{ $mark('keyboard', 'defect') }} Dysfunction</td>
                    <td colspan="3" rowspan="7"><strong>Note:</strong><br>{{ $item->qc_notes ?: 'All Normal' }}</td>
                </tr>
                <tr>
                    <th>LCD Screen</th><td colspan="3">{{ $mark('screen', 'ok') }} Normal</td>
                    <td colspan="3">{{ $mark('screen', 'minor') }} Deadpixel</td>
                    <td colspan="5">☐ Vignette</td><td colspan="4">{{ $mark('screen', 'defect') }} White Spot</td>
                </tr>
                <tr>
                    <th>Speaker</th><td colspan="3">{{ $mark('speaker', 'ok') }} Normal</td>
                    <td colspan="3">{{ $mark('speaker', 'minor') }} Beret</td>
                    <td colspan="5">☐ Pecah</td><td colspan="4">{{ $mark('speaker', 'defect') }} Dysfunction</td>
                </tr>
                <tr><th>Webcam</th><td colspan="6">{{ $mark('webcam', 'ok') }} Normal</td><td colspan="9">{{ $mark('webcam', 'defect') }} Dysfunction</td></tr>
                <tr><th>{{ $isMac ? 'USB C Port' : 'USB Port' }}</th><td colspan="6">{{ $mark('ports', 'ok') }} Normal</td><td colspan="9">{{ $mark('ports', 'defect') }} Dysfunction</td></tr>
                <tr><th>Charging Port</th><td colspan="6">{{ $mark('battery', 'ok') }} Normal</td><td colspan="9">{{ $mark('battery', 'defect') }} Dysfunction</td></tr>
                <tr><th>Hinge</th><td colspan="6">{{ $mark('body', 'ok') }} Normal</td><td colspan="9">{{ $mark('body', 'defect') }} Dysfunction</td></tr>

                <tr class="top-rule">
                    <td class="sideways" rowspan="18">DEVICE INFO</td>
                    <th rowspan="2">Processor</th><th colspan="3">Vendor</th><td colspan="7">{{ strtok($processor ?: '—', ' ') }}</td>
                    <th colspan="5">Base Clock</th><td colspan="3">—</td>
                </tr>
                <tr><th colspan="3">Model</th><td colspan="7">{{ $processor ?: '—' }}</td><th colspan="5">Boost Clock</th><td colspan="3">—</td></tr>
                <tr>
                    <th rowspan="3">RAM</th><th colspan="3">Channel Count</th><td colspan="3">—</td>
                    <th colspan="4">Speed (MHz)</th><td colspan="4">—</td><th colspan="4">Part Number</th>
                </tr>
                <tr><th colspan="3" rowspan="2">Capacity</th><td colspan="3">{{ $ram ?: '—' }}</td><th colspan="4">Type</th><td colspan="4">—</td><td colspan="4">—</td></tr>
                <tr><td colspan="3">{{ $ram2 ?: '—' }}</td><td colspan="4">—</td><td colspan="4">—</td><td colspan="4">—</td></tr>
                <tr>
                    <th rowspan="5">Storage</th><th colspan="4">Storage 1</th><td colspan="6">{{ $storage ?: '—' }}</td>
                    <th colspan="6">Storage 2</th><td colspan="2">{{ $storage2 ?: 'None' }}</td>
                </tr>
                <tr><th colspan="10">Capacity</th><th colspan="8">Capacity</th></tr>
                <tr><td colspan="10">{{ $storage ?: '—' }}</td><td colspan="8">{{ $storage2 ?: '—' }}</td></tr>
                <tr><th colspan="4">Storage Health</th><td colspan="6">—</td><th colspan="6">Storage Health</th><td colspan="2">—</td></tr>
                <tr><th colspan="4">Power On Time</th><td colspan="6">—</td><th colspan="6">Power On Time</th><td colspan="2">—</td></tr>
                <tr><th rowspan="3">Graphics</th><th colspan="6">Category</th><th colspan="4">Memory</th><th colspan="8">Manufactured / Type</th></tr>
                <tr><th colspan="6">Integrated GPU</th><td colspan="4">—</td><td colspan="8">{{ $graphics ?: '—' }}</td></tr>
                <tr><th colspan="6">Dedicated GPU</th><td colspan="4">—</td><td colspan="8">{{ $graphics ?: 'None' }}</td></tr>
                <tr><th rowspan="2">Screen</th><th colspan="3">Size (inch)</th><td colspan="4">{{ $display ?: '—' }}</td><th colspan="5">Resolution</th><td colspan="6">{{ $display ?: '—' }}</td></tr>
                <tr><th colspan="3">Refresh Rate</th><td colspan="4">—</td><th colspan="5">Panel Type</th><td colspan="6">{{ $display ?: '—' }}</td></tr>
                <tr><th rowspan="3">Battery</th><th colspan="3">Type</th><td colspan="3">{{ $item->laptop->battery_life ?: '—' }}</td><th colspan="6">Factory Capacity</th><td colspan="6">{{ $item->laptop->battery_life ?: '—' }}</td></tr>
                <tr><th colspan="3">Active Charge</th><td colspan="3">—</td><th colspan="6">Estimated Battery Health (%)</th><td colspan="6">{{ $note('battery') ?: '—' }}</td></tr>
                <tr><th colspan="3">Model Number</th><td colspan="15">{{ $item->laptop->sku ?: '—' }}</td></tr>
                <tr class="grade">
                    <th colspan="2">GRADE RESULT</th><td colspan="3">Grade {{ $grade }}</td>
                    <th colspan="6">DEVICE SERIAL NUMBER</th><td colspan="9">{{ $item->serial_number ?: '—' }}</td>
                </tr>
            </tbody>
        </table>

        <table class="extra">
            <colgroup><col style="width:23%"><col style="width:4%"><col style="width:17%"><col style="width:56%"></colgroup>
            <thead><tr><th colspan="3">ADDITIONAL FEATURES</th><th>NOTES</th></tr></thead>
            <tbody>
                <tr><td><strong>Windows Hello</strong></td><td class="center">☐</td><td>—</td><td rowspan="5"><strong>Restock:</strong> {{ $restock->restock_number }}<br><strong>Supplier:</strong> {{ $restock->supplier_name }}<br><strong>Warranty:</strong> {{ $item->laptop->warranty ?: '—' }}<br><strong>Ports:</strong> {{ $item->laptop->ports ?: '—' }}<br><strong>Color:</strong> {{ $item->laptop->color ?: '—' }}<br><strong>Catatan QC:</strong> {{ $item->qc_notes ?: '—' }}</td></tr>
                <tr><td><strong>Fingerprint</strong></td><td class="center">☐</td><td>—</td></tr>
                <tr><td><strong>Keyboard Backlight</strong></td><td class="center">☐</td><td>—</td></tr>
                <tr><td><strong>Camera Shutter</strong></td><td class="center">{{ ($checklist['webcam'] ?? null) === 'ok' ? '✓' : '☐' }}</td><td>{{ $status('webcam') }}</td></tr>
                <tr><td><strong>Touchscreen</strong></td><td class="center">☐</td><td>—</td></tr>
            </tbody>
        </table>

        <div class="section-title">Riwayat Pergantian Part &amp; Biaya Tambahan</div>
        <table class="parts">
            <thead><tr><th style="width:16%">SKU Sparepart</th><th>Nama Sparepart / Tindakan Servis</th><th style="width:7%">Qty</th><th style="width:17%">Harga Satuan</th><th style="width:17%">Total Harga</th><th>Catatan</th></tr></thead>
            <tbody>
                @forelse($item->parts as $part)
                    <tr>
                        <td>{{ $part->product?->sku ?: '—' }}</td>
                        <td>{{ $part->part_name }}</td>
                        <td class="center">{{ $part->quantity }}</td>
                        <td class="money">Rp {{ number_format((float) $part->unit_cost, 0, ',', '.') }}</td>
                        <td class="money">Rp {{ number_format((float) $part->total_cost, 0, ',', '.') }}</td>
                        <td>{{ $part->notes ?: '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="center">Tidak ada pergantian part yang dicatat.</td></tr>
                @endforelse
                @if($item->parts->isNotEmpty())
                    <tr><th colspan="4" style="text-align:right">Total Biaya Tambahan</th><th class="money">Rp {{ number_format($partsTotal, 0, ',', '.') }}</th><td></td></tr>
                @endif
            </tbody>
        </table>
        <div class="footer">Inspector: {{ $item->inspector?->name ?? '—' }} &nbsp; | &nbsp; {{ $item->qc_at?->format('d/m/Y H:i') ?? '—' }} &nbsp; | &nbsp; SKU Unit: {{ $item->sku ?: '—' }}</div>
    </article>
@endforeach
</body>
</html>
