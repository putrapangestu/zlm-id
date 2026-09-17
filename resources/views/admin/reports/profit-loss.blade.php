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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
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
                Omset dikurangi HPP unit & sparepart
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
                        <span>1. PENDAPATAN USAHA</span>
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
                            <span>Pajak Transaksi (PPN)</span>
                            <span class="font-mono text-gray-800">Rp {{ number_format($taxTotal, 0, ',', '.') }}</span>
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

    {{-- Recent Sold Items Breakdown --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2">
                <iconify-icon icon="solar:bag-check-bold" class="text-[#DF5E1D] text-lg"></iconify-icon>
                <span>Sampel Transaksi Penjualan & Margin Unit</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-gray-50/70 border-b border-gray-100">
                        <th class="py-3 px-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">No. Order</th>
                        <th class="py-3 px-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Pelanggan</th>
                        <th class="py-3 px-5 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Produk Terjual</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Harga Jual</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">HPP Unit</th>
                        <th class="py-3 px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Margin Bersih</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentSoldOrders as $order)
                        @foreach($order->items as $item)
                            @php
                                $price = (float)($item->unit_price ?: ($item->price ?: 0));
                                $finalCost = $item->productItem ? (float)$item->productItem->final_cost : ($price * 0.7);
                                $itemMargin = $price - $finalCost;
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3 px-5 font-mono font-bold text-[#363230]">
                                    <a href="{{ route('admin.transactions.show', $order) }}" class="text-[#DF5E1D] hover:underline">
                                        #{{ $order->order_number }}
                                    </a>
                                </td>
                                <td class="py-3 px-5 text-gray-700">
                                    {{ $order->user->name ?? 'Pelanggan Umum' }}
                                </td>
                                <td class="py-3 px-5">
                                    <span class="font-semibold text-gray-800">{{ $item->product_name ?: ($item->laptop->name ?? 'Laptop') }}</span>
                                    @if($item->productItem?->sku)
                                        <span class="block text-[10px] text-gray-400 font-mono">SKU: {{ $item->productItem->sku }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-bold text-gray-900">
                                    Rp {{ number_format($price, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono text-gray-600">
                                    Rp {{ number_format($finalCost, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 text-right font-mono font-bold {{ $itemMargin >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    Rp {{ number_format($itemMargin, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">Belum ada transaksi dalam periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Composition Donut Chart
    const compCtx = document.getElementById('chartProfitComposition');
    if (compCtx) {
        new Chart(compCtx, {
            type: 'doughnut',
            data: {
                labels: ['Modal Unit Terjual', 'Biaya Sparepart QC', 'Laba Bersih', 'Beban Kirim/Diskon'],
                datasets: [{
                    data: [
                        {{ (float)$baseCostSold }},
                        {{ (float)$qcPartsCostSold }},
                        {{ max(0, (float)$netProfit) }},
                        {{ (float)($shippingCost + $memberDiscounts) }}
                    ],
                    backgroundColor: ['#64748b', '#DF5E1D', '#10b981', '#f59e0b'],
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
                labels: ['Total Omset Pendapatan', 'Total Beban HPP', 'Laba Kotor Usaha', 'Laba Bersih Akhir'],
                datasets: [{
                    label: 'Nominal (Rp)',
                    data: [
                        {{ (float)$totalRevenue }},
                        {{ (float)$totalHppSold }},
                        {{ (float)$grossProfit }},
                        {{ (float)$netProfit }}
                    ],
                    backgroundColor: ['#3b82f6', '#DF5E1D', '#10b981', '#059669'],
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
        });
    }
});
</script>
@endpush
