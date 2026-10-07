@extends('layouts.admin')

@section('title', 'Laporan Laba Rugi & HPP — ZLM.ID Admin')
@section('heading', 'Laporan Laba Rugi & Analisis HPP')

@section('content')
<div class="space-y-6">

    {{-- Filter Form --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-5">
        <form method="GET" class="flex flex-col md:flex-row items-end justify-between gap-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 w-full md:w-auto flex-1">
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Periode</label>
                    <select name="period" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                        <option value="monthly" @selected($period === 'monthly')>Bulan Berjalan</option>
                        <option value="yearly" @selected($period === 'yearly')>Tahun Berjalan</option>
                        <option value="custom" @selected($period === 'custom')>Rentang Kustom</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-500 uppercase mb-1">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold focus:outline-none focus:border-[#DF5E1D]">
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <button type="submit" class="px-5 py-2.5 bg-[#DF5E1D] hover:bg-[#c45218] text-white rounded-xl text-xs font-bold shadow-sm transition flex items-center gap-1.5">
                    <iconify-icon icon="solar:filter-bold" class="text-base"></iconify-icon>
                    <span>Terapkan Filter</span>
                </button>
                <a href="{{ route('admin.reports.profit-loss') }}" class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Executive Summary KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        {{-- Total Revenue --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Total Pendapatan</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <iconify-icon icon="solar:wallet-money-bold" class="text-lg"></iconify-icon>
                </div>
            </div>
            <p class="text-2xl font-black text-[#363230] font-mono">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <div class="flex items-center justify-between text-[11px] text-gray-500 pt-1 border-t border-gray-100">
                <span>Online: <strong>Rp {{ number_format($onlineRevenue, 0, ',', '.') }}</strong></span>
                <span>POS: <strong>Rp {{ number_format($posRevenue, 0, ',', '.') }}</strong></span>
            </div>
        </div>

        {{-- HPP Terjual --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">HPP Barang Terjual</span>
                <div class="w-8 h-8 rounded-xl bg-orange-50 text-[#DF5E1D] flex items-center justify-center">
                    <iconify-icon icon="solar:box-minimalistic-bold" class="text-lg"></iconify-icon>
                </div>
            </div>
            <p class="text-2xl font-black text-[#DF5E1D] font-mono">Rp {{ number_format($totalHppSold, 0, ',', '.') }}</p>
            <div class="flex items-center justify-between text-[11px] text-gray-500 pt-1 border-t border-gray-100">
                <span>Unit: <strong>Rp {{ number_format($baseCostSold, 0, ',', '.') }}</strong></span>
                <span>Part QC: <strong>Rp {{ number_format($qcPartsCostSold, 0, ',', '.') }}</strong></span>
            </div>
        </div>

        {{-- Pajak Transaksi --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Pajak Transaksi (PPN)</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <iconify-icon icon="solar:bill-list-bold" class="text-lg"></iconify-icon>
                </div>
            </div>
            <p class="text-2xl font-black text-amber-600 font-mono">Rp {{ number_format($taxTotal, 0, ',', '.') }}</p>
            <div class="text-[11px] text-gray-500 pt-1 border-t border-gray-100">Tidak dihitung sebagai laba kotor</div>
        </div>

        {{-- Laba Kotor --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Laba Kotor (Gross)</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $grossMarginPercent >= 15 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $grossMarginPercent }}% Margin
                </span>
            </div>
            <p class="text-2xl font-black {{ $grossProfit >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-mono">
                Rp {{ number_format($grossProfit, 0, ',', '.') }}
            </p>
            <div class="text-[11px] text-gray-500 pt-1 border-t border-gray-100">
                Omset setelah pajak dikurangi HPP unit & sparepart
            </div>
        </div>

        {{-- Laba Bersih --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Laba Bersih (Net)</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $netMarginPercent >= 10 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                    {{ $netMarginPercent }}% Margin
                </span>
            </div>
            <p class="text-2xl font-black {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-rose-600' }} font-mono">
                Rp {{ number_format($netProfit, 0, ',', '.') }}
            </p>
            <div class="text-[11px] text-gray-500 pt-1 border-t border-gray-100">
                Setelah diskon member & biaya kirim
            </div>
        </div>
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Donut Chart: Komposisi Keuangan --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm">
            <h3 class="text-xs font-bold text-[#363230] uppercase tracking-wider mb-4 flex items-center gap-2">
                <iconify-icon icon="solar:pie-chart-bold" class="text-[#DF5E1D] text-lg"></iconify-icon>
                <span>Komposisi Nilai Pendapatan</span>
            </h3>
            <div class="h-60 flex items-center justify-center">
                <canvas id="chartProfitComposition"></canvas>
            </div>
        </div>

        {{-- Bar Chart: Perbandingan Ringkasan Finansial --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm">
            <h3 class="text-xs font-bold text-[#363230] uppercase tracking-wider mb-4 flex items-center gap-2">
                <iconify-icon icon="solar:chart-2-bold" class="text-[#DF5E1D] text-lg"></iconify-icon>
                <span>Perbandingan Finansial Periode Terpilih</span>
            </h3>
            <div class="h-60">
                <canvas id="chartProfitBar"></canvas>
            </div>
        </div>
    </div>

    {{-- Detailed P&L Financial Statement --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2">
                <iconify-icon icon="solar:document-text-bold" class="text-[#DF5E1D] text-lg"></iconify-icon>
                <span>Laporan Laba Rugi Komprehensif</span>
            </h3>
            <span class="text-xs text-gray-400">
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} &ndash; {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }} &bull; {{ $ordersCount }} Transaksi
            </span>
        </div>

        <div class="p-6">
            <div class="max-w-3xl mx-auto space-y-4 text-xs">

                {{-- 1. PENDAPATAN USAHA --}}
                <div class="border-b border-gray-200 pb-3">
                    <div class="flex justify-between font-bold text-gray-900 text-sm mb-2">
                        <span>1. PENDAPATAN USAHA (TERMASUK PPN)</span>
                        <span class="font-mono">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                    </div>
                    <div class="space-y-1.5 pl-4 text-gray-600">
                        <div class="flex justify-between">
                            <span>Penjualan Toko Online (Website)</span>
                            <span class="font-mono text-gray-800">Rp {{ number_format($onlineRevenue, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Penjualan Kasir Langsung (POS Toko)</span>
                            <span class="font-mono text-gray-800">Rp {{ number_format($posRevenue, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Pendapatan Ongkos Kirim Pelanggan</span>
                            <span class="font-mono text-gray-800">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Pajak Transaksi (PPN, dikeluarkan sebelum laba kotor)</span>
                            <span class="font-mono text-amber-700">(Rp {{ number_format($taxTotal, 0, ',', '.') }})</span>
                        </div>
                    </div>
                </div>

                {{-- 2. HARGA POKOK PENJUALAN (HPP) --}}
                <div class="border-b border-gray-200 pb-3">
                    <div class="flex justify-between font-bold text-rose-700 text-sm mb-2">
                        <span>2. HARGA POKOK PENJUALAN (HPP)</span>
                        <span class="font-mono">(Rp {{ number_format($totalHppSold, 0, ',', '.') }})</span>
                    </div>
                    <div class="space-y-1.5 pl-4 text-gray-600">
                        <div class="flex justify-between">
                            <span>Modal Dasar Unit Laptop Terjual (Purchase Price)</span>
                            <span class="font-mono text-gray-800">Rp {{ number_format($baseCostSold, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Biaya Sparepart & Perbaikan QC Terpasang</span>
                            <span class="font-mono text-gray-800">Rp {{ number_format($qcPartsCostSold, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- LABA KOTOR --}}
                <div class="flex justify-between font-bold text-gray-900 bg-gray-50 px-4 py-2.5 rounded-xl border border-gray-200">
                    <span class="uppercase">LABA KOTOR (GROSS PROFIT)</span>
                    <span class="font-mono text-emerald-600">Rp {{ number_format($grossProfit, 0, ',', '.') }}</span>
                </div>

                {{-- 3. BEBAN OPERASIONAL & POTONGAN --}}
                <div class="border-b border-gray-200 pb-3">
                    <div class="flex justify-between font-bold text-gray-900 text-sm mb-2">
                        <span>3. BEBAN OPERASIONAL & DISKON</span>
                        <span class="font-mono text-rose-700">(Rp {{ number_format($shippingCost + $memberDiscounts, 0, ',', '.') }})</span>
                    </div>
                    <div class="space-y-1.5 pl-4 text-gray-600">
                        <div class="flex justify-between">
                            <span>Biaya Ekspedisi / Ongkos Kirim</span>
                            <span class="font-mono text-gray-800">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Diskon Loyalitas Member / Promo</span>
                            <span class="font-mono text-gray-800">Rp {{ number_format($memberDiscounts, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- LABA BERSIH BERJALAN --}}
                <div class="flex justify-between items-center font-extrabold text-white bg-slate-900 px-5 py-3.5 rounded-xl shadow-sm">
                    <span class="text-sm uppercase tracking-wider">LABA BERSIH USAHA (NET PROFIT)</span>
                    <span class="text-base font-mono text-emerald-400">Rp {{ number_format($netProfit, 0, ',', '.') }}</span>
                </div>

                {{-- Info Belanja Supplier --}}
                <div class="p-3.5 rounded-xl bg-blue-50/60 border border-blue-200/80 text-blue-900 flex items-center justify-between">
                    <div>
                        <span class="font-bold block">Informasi Belanja Restock Baru (Supplier)</span>
                        <span class="text-[11px] text-blue-700">Total modal yang dibelanjakan untuk menambah stok unit di periode ini.</span>
                    </div>
                    <span class="font-mono font-bold text-sm text-blue-900">Rp {{ number_format($restockPurchasesTotal, 0, ',', '.') }}</span>
                </div>

            </div>
        </div>
    </div>

    {{-- Transaction Trend --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2">
                <iconify-icon icon="solar:chart-bold" class="text-[#DF5E1D] text-lg"></iconify-icon>
                <span>Grafik Transaksi {{ ucfirst($transactionChartUnit) }}</span>
            </h3>
            <span class="text-xs text-gray-400">{{ $ordersCount }} transaksi dalam periode terpilih</span>
        </div>
        <div class="p-5">
            <div class="h-72">
                <canvas id="chartTransactions"></canvas>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodSelector = document.querySelector('select[name="period"]');
    periodSelector?.addEventListener('change', function () {
        const now = new Date();
        const formatDate = date => [
            date.getFullYear(),
            String(date.getMonth() + 1).padStart(2, '0'),
            String(date.getDate()).padStart(2, '0')
        ].join('-');
        const startInput = document.querySelector('input[name="start_date"]');
        const endInput = document.querySelector('input[name="end_date"]');
        if (!startInput || !endInput || this.value === 'custom') return;
        startInput.value = this.value === 'yearly'
            ? `${now.getFullYear()}-01-01`
            : `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}-01`;
        endInput.value = formatDate(now);
    });

    // 1. Composition Donut Chart
    const compCtx = document.getElementById('chartProfitComposition');
    if (compCtx) {
        new Chart(compCtx, {
            type: 'doughnut',
            data: {
                labels: ['Modal Unit Terjual', 'Biaya Sparepart QC', 'Laba Bersih', 'Beban Kirim/Diskon', 'Pajak Transaksi'],
                datasets: [{
                    data: [
                        {{ (float)$baseCostSold }},
                        {{ (float)$qcPartsCostSold }},
                        {{ max(0, (float)$netProfit) }},
                        {{ (float)($shippingCost + $memberDiscounts) }},
                        {{ (float)$taxTotal }}
                    ],
                    backgroundColor: ['#64748b', '#DF5E1D', '#10b981', '#f59e0b', '#fbbf24'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, font: { size: 10, family: "'Plus Jakarta Sans', sans-serif" } }
                    }
                },
                cutout: '65%'
            }
        });
    }

    // 2. Overview Bar Chart
    const barCtx = document.getElementById('chartProfitBar');
    if (barCtx) {
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Total Omset (termasuk PPN)', 'Total Beban HPP', 'Pajak Transaksi', 'Laba Kotor Usaha', 'Laba Bersih Akhir'],
                datasets: [{
                    label: 'Nominal (Rp)',
                    data: [
                        {{ (float)$totalRevenue }},
                        {{ (float)$totalHppSold }},
                        {{ (float)$taxTotal }},
                        {{ (float)$grossProfit }},
                        {{ (float)$netProfit }}
                    ],
                    backgroundColor: ['#3b82f6', '#DF5E1D', '#f59e0b', '#10b981', '#059669'],
                    borderRadius: 8,
                    maxBarThickness: 48
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(value);
                            },
                            font: { size: 10 }
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11, weight: 'bold' } }
                    }
                }
            }

            const transactionCtx = document.getElementById('chartTransactions');
            if (transactionCtx) {
                new Chart(transactionCtx, {
                    data: {
                        labels: @json($transactionChartLabels),
                        datasets: [
                            {
                                type: 'bar',
                                label: 'Jumlah Transaksi',
                                data: @json($transactionChartCounts),
                                backgroundColor: '#DF5E1D',
                                borderRadius: 6,
                                yAxisID: 'y',
                            },
                            {
                                type: 'line',
                                label: 'Omzet (Rp)',
                                data: @json($transactionChartRevenue),
                                borderColor: '#2563eb',
                                backgroundColor: '#2563eb',
                                tension: 0.25,
                                yAxisID: 'y1',
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        scales: {
                            y: { beginAtZero: true, title: { display: true, text: 'Transaksi' }, ticks: { precision: 0 } },
                            y1: {
                                beginAtZero: true,
                                position: 'right',
                                grid: { drawOnChartArea: false },
                                title: { display: true, text: 'Omzet (Rp)' },
                                ticks: { callback: value => 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(value) }
                            }
                        }
                    }
                });
            }
        });
    }
});
</script>
@endpush
