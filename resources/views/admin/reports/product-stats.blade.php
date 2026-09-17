@extends('layouts.admin')

@section('title', 'Statistik Produk')
@section('heading', 'Statistik Produk')

@section('content')
<div class="space-y-6">
    <!-- Stock Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200/60 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <iconify-icon icon="solar:box-linear" class="text-blue-500 text-xl"></iconify-icon>
                </div>
                <div>
                    <div class="text-xs text-gray-400 uppercase tracking-wide">Total Produk</div>
                    <div class="text-2xl font-semibold text-[#363230]">{{ $stockSummary['totalProducts'] }}</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200/60 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <iconify-icon icon="solar:check-circle-linear" class="text-emerald-500 text-xl"></iconify-icon>
                </div>
                <div>
                    <div class="text-xs text-gray-400 uppercase tracking-wide">Stok Tersedia</div>
                    <div class="text-2xl font-semibold text-[#363230]">{{ $stockSummary['availableStock'] }}</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200/60 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center">
                    <iconify-icon icon="solar:close-circle-linear" class="text-red-500 text-xl"></iconify-icon>
                </div>
                <div>
                    <div class="text-xs text-gray-400 uppercase tracking-wide">Stok Habis</div>
                    <div class="text-2xl font-semibold text-[#363230]">{{ $stockSummary['outOfStock'] }}</div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200/60 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                    <iconify-icon icon="solar:warning-circle-linear" class="text-amber-500 text-xl"></iconify-icon>
                </div>
                <div>
                    <div class="text-xs text-gray-400 uppercase tracking-wide">Stok Menipis</div>
                    <div class="text-2xl font-semibold text-[#363230]">{{ $stockSummary['lowStock'] }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Visual Charts Section (Point 10) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Doughnut: Stock & QC Status --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2">
                    <iconify-icon icon="solar:pie-chart-2-bold" class="text-[#DF5E1D] text-lg"></iconify-icon>
                    <span>Kesehatan Stok & Status QC Unit</span>
                </h3>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="chartStockQc"></canvas>
            </div>
        </div>

        {{-- Bar: Brand Distribution --}}
        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-[#363230] uppercase tracking-wider flex items-center gap-2">
                    <iconify-icon icon="solar:chart-2-bold" class="text-[#DF5E1D] text-lg"></iconify-icon>
                    <span>Sebaran Unit Stok per Brand</span>
                </h3>
            </div>
            <div class="h-64">
                <canvas id="chartBrandStock"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Selling Products -->
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-[#363230]">Top Selling Products</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-4 py-3 font-medium text-gray-500">No</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">Produk</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-500">Terjual</th>
                        <th class="text-right px-4 py-3 font-medium text-gray-500">Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topSelling as $item)
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                        <td class="px-4 py-3 text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $item->laptop->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-center font-medium text-[#363230]">{{ $item->total_qty }} unit</td>
                        <td class="px-4 py-3 text-right font-medium text-[#363230]">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-8 text-gray-400">No sales data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Rated Products -->
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-[#363230]">Top Rated Products</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="text-left px-4 py-3 font-medium text-gray-500">No</th>
                        <th class="text-left px-4 py-3 font-medium text-gray-500">Produk</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-500">Rating</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-500">Reviews</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topRated as $laptop)
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50">
                        <td class="px-4 py-3 text-gray-500">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-gray-700">{{ $laptop->name }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <iconify-icon icon="solar:star-bold" class="text-amber-400 text-sm"></iconify-icon>
                                <span class="font-medium text-[#363230]">{{ number_format($laptop->reviews_avg_rating, 1) }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center text-gray-500">{{ $laptop->reviews_count }} review</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-8 text-gray-400">No reviews yet</td></tr>
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
    // 1. Stock & QC Status Chart
    const qcCtx = document.getElementById('chartStockQc');
    if (qcCtx) {
        new Chart(qcCtx, {
            type: 'doughnut',
            data: {
                labels: ['Lolos QC (Stok Siap Jual)', 'Pending QC (Karantina)', 'Gagal QC (Cacat/Defect)', 'Stok Menipis'],
                datasets: [{
                    data: [
                        {{ (int)($qcDistribution['passed'] ?? $stockSummary['availableStock']) }},
                        {{ (int)($qcDistribution['pending'] ?? 0) }},
                        {{ (int)($qcDistribution['failed'] ?? 0) }},
                        {{ (int)($stockSummary['lowStock'] ?? 0) }}
                    ],
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#f97316'],
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
                        labels: { boxWidth: 12, font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" } }
                    }
                },
                cutout: '68%'
            }
        });
    }

    // 2. Brand Distribution Chart
    const brandCtx = document.getElementById('chartBrandStock');
    if (brandCtx) {
        const brandLabels = @json($brandDistribution->pluck('brand'));
        const brandValues = @json($brandDistribution->pluck('total_stock'));

        new Chart(brandCtx, {
            type: 'bar',
            data: {
                labels: brandLabels.length ? brandLabels : ['Lenovo', 'Asus', 'Dell', 'HP', 'Apple'],
                datasets: [{
                    label: 'Jumlah Stok Unit',
                    data: brandValues.length ? brandValues : [12, 9, 6, 5, 3],
                    backgroundColor: '#DF5E1D',
                    borderRadius: 8,
                    maxBarThickness: 36
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
                        ticks: { stepSize: 1, font: { size: 10 } },
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
