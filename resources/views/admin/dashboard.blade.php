@extends('layouts.admin')

@section('title', 'Dashboard Operasional — ZLM.ID Admin')
@section('heading', 'Pusat Kendali Operasional')

@section('content')
<div class="space-y-6">

    <!-- 1. ACTION NEEDED STRIP (ALERTS) -->
    <div class="bg-white rounded-2xl border border-gray-200/60 p-4 sm:p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <span class="flex h-2.5 w-2.5 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#DF5E1D] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#DF5E1D]"></span>
                </span>
                <h3 class="text-xs font-bold text-[#363230] uppercase tracking-wider">Tindakan Mendesak Hari Ini</h3>
            </div>
            <span class="text-[11px] text-gray-400">Sinkronisasi operasional toko &amp; gudang</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
            <!-- Verifikasi Pembayaran -->
            <a href="{{ route('admin.transactions.index') }}" class="p-3 rounded-xl border transition-all flex flex-col justify-between {{ $actionAlerts['pending_verifications'] > 0 ? 'bg-amber-50/70 border-amber-200/80 hover:bg-amber-100/60 text-amber-900' : 'bg-gray-50/50 border-gray-200/50 text-gray-600 hover:bg-gray-50' }}">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold">Verifikasi Bayar</span>
                    <iconify-icon icon="solar:clock-circle-linear" class="text-base {{ $actionAlerts['pending_verifications'] > 0 ? 'text-amber-600' : 'text-gray-400' }}"></iconify-icon>
                </div>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-xl font-extrabold {{ $actionAlerts['pending_verifications'] > 0 ? 'text-amber-700' : 'text-gray-700' }}">
                        {{ $actionAlerts['pending_verifications'] }}
                    </span>
                    <span class="text-[10px] text-gray-500">perlu dicek</span>
                </div>
            </a>

            <!-- Siap Dikemas / Dikirim -->
            <a href="{{ route('admin.transactions.index') }}" class="p-3 rounded-xl border transition-all flex flex-col justify-between {{ $actionAlerts['need_shipping'] > 0 ? 'bg-blue-50/70 border-blue-200/80 hover:bg-blue-100/60 text-blue-900' : 'bg-gray-50/50 border-gray-200/50 text-gray-600 hover:bg-gray-50' }}">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold">Siap Dikirim</span>
                    <iconify-icon icon="solar:box-minimalistic-linear" class="text-base {{ $actionAlerts['need_shipping'] > 0 ? 'text-blue-600' : 'text-gray-400' }}"></iconify-icon>
                </div>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-xl font-extrabold {{ $actionAlerts['need_shipping'] > 0 ? 'text-blue-700' : 'text-gray-700' }}">
                        {{ $actionAlerts['need_shipping'] }}
                    </span>
                    <span class="text-[10px] text-gray-500">sudah lunas</span>
                </div>
            </a>

            <!-- Antrean QC Masuk -->
            <a href="{{ route('admin.qc.index') }}" class="p-3 rounded-xl border transition-all flex flex-col justify-between {{ $actionAlerts['pending_qc'] > 0 ? 'bg-orange-50/70 border-orange-200/80 hover:bg-orange-100/60 text-orange-950' : 'bg-gray-50/50 border-gray-200/50 text-gray-600 hover:bg-gray-50' }}">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold">Antrean QC</span>
                    <iconify-icon icon="solar:checklist-minimalistic-linear" class="text-base {{ $actionAlerts['pending_qc'] > 0 ? 'text-[#DF5E1D]' : 'text-gray-400' }}"></iconify-icon>
                </div>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-xl font-extrabold {{ $actionAlerts['pending_qc'] > 0 ? 'text-[#DF5E1D]' : 'text-gray-700' }}">
                        {{ $actionAlerts['pending_qc'] }}
                    </span>
                    <span class="text-[10px] text-gray-500">unit masuk</span>
                </div>
            </a>

            <!-- Peringatan Stok Kritis -->
            <a href="{{ route('admin.laptops.index') }}" class="p-3 rounded-xl border transition-all flex flex-col justify-between {{ $actionAlerts['critical_stock_count'] > 0 ? 'bg-red-50/70 border-red-200/80 hover:bg-red-100/60 text-red-950' : 'bg-gray-50/50 border-gray-200/50 text-gray-600 hover:bg-gray-50' }}">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold">Stok Kritis</span>
                    <iconify-icon icon="solar:danger-triangle-linear" class="text-base {{ $actionAlerts['critical_stock_count'] > 0 ? 'text-red-600' : 'text-gray-400' }}"></iconify-icon>
                </div>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-xl font-extrabold {{ $actionAlerts['critical_stock_count'] > 0 ? 'text-red-700' : 'text-gray-700' }}">
                        {{ $actionAlerts['critical_stock_count'] }}
                    </span>
                    <span class="text-[10px] text-gray-500">&le; 2 unit tersisa</span>
                </div>
            </a>

            <!-- Retur Aktif -->
            <a href="{{ route('admin.returns.index') }}" class="p-3 rounded-xl border transition-all flex flex-col justify-between {{ $actionAlerts['pending_returns'] > 0 ? 'bg-purple-50/70 border-purple-200/80 hover:bg-purple-100/60 text-purple-950' : 'bg-gray-50/50 border-gray-200/50 text-gray-600 hover:bg-gray-50' }}">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold">Retur Aktif</span>
                    <iconify-icon icon="solar:refresh-square-linear" class="text-base {{ $actionAlerts['pending_returns'] > 0 ? 'text-purple-600' : 'text-gray-400' }}"></iconify-icon>
                </div>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-xl font-extrabold {{ $actionAlerts['pending_returns'] > 0 ? 'text-purple-700' : 'text-gray-700' }}">
                        {{ $actionAlerts['pending_returns'] }}
                    </span>
                    <span class="text-[10px] text-gray-500">belum tuntas</span>
                </div>
            </a>
        </div>
    </div>

    <!-- 2. CORE KPI CARDS (4 GRIDS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Penjualan Hari Ini -->
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-3">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Penjualan Hari Ini</span>
                <div class="w-8 h-8 rounded-lg bg-orange-50 text-[#DF5E1D] flex items-center justify-center">
                    <iconify-icon icon="solar:wallet-money-linear" class="text-base"></iconify-icon>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-[#363230] mb-1">
                Rp {{ number_format($todayStats->revenue, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-gray-500 flex items-center gap-1.5">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>{{ $todayStats->total_orders }} pesanan masuk hari ini</span>
            </div>
        </div>

        <!-- Omset Bulan Berjalan -->
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-3">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Omset Bulan Ini</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <iconify-icon icon="solar:calendar-date-linear" class="text-base"></iconify-icon>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-[#363230] mb-1">
                Rp {{ number_format($monthStats->revenue, 0, ',', '.') }}
            </div>
            <div class="text-[11px] text-gray-500">
                Total {{ $monthStats->total_orders }} pesanan terverifikasi
            </div>
        </div>

        <!-- Performa Channel Penjualan (Hari Ini) -->
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 relative overflow-hidden">
            <div class="flex justify-between items-start mb-3">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Channel Penjualan Hari Ini</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <iconify-icon icon="solar:shop-2-linear" class="text-base"></iconify-icon>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-500 flex items-center gap-1">
                        <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-orange-500"></iconify-icon> Web Online
                    </span>
                    <span class="font-bold text-[#363230]">Rp {{ number_format($todayStats->revenue_web, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-500 flex items-center gap-1">
                        <iconify-icon icon="solar:shop-linear" class="text-emerald-500"></iconify-icon> Kasir POS
                    </span>
                    <span class="font-bold text-[#363230]">Rp {{ number_format($todayStats->revenue_pos, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Kesehatan Stok & QC -->
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 relative overflow-hidden">
            <div class="flex justify-between items-start mb-3">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Kondisi Fisik Gudang</span>
                <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                    <iconify-icon icon="solar:box-minimalistic-linear" class="text-base"></iconify-icon>
                </div>
            </div>
            <div class="text-2xl font-extrabold text-[#363230] mb-1">
                {{ $stockSummary['ready_units'] }} <span class="text-xs font-semibold text-gray-400">Unit Siap Jual</span>
            </div>
            <div class="text-[11px] text-gray-500 flex items-center gap-3">
                <span class="text-orange-600 font-semibold">{{ $stockSummary['in_qc'] }} di QC</span>
                <span>&bull;</span>
                <span class="text-gray-400">{{ $stockSummary['total_models'] }} tipe laptop</span>
            </div>
        </div>
    </div>

    <!-- 3. TWO-COLUMN OPERATIONAL WORKFLOW -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <!-- Kolom Kiri (8 cols): 5 Pesanan Butuh Diproses Segera -->
        <div class="lg:col-span-8 bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden flex flex-col">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-[#363230] flex items-center gap-2">
                        <iconify-icon icon="solar:cart-large-2-linear" class="text-base text-[#DF5E1D]"></iconify-icon>
                        Antrean Pesanan Menunggu Tindakan
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">Prioritaskan pesanan yang menunggu verifikasi bukti transfer atau perlu dipacking</p>
                </div>
                <a href="{{ route('admin.transactions.index') }}" class="text-xs font-bold text-[#DF5E1D] hover:underline flex items-center gap-1">
                    Semua Transaksi &rarr;
                </a>
            </div>

            <div class="p-0 flex-1">
                @if($ordersToProcess->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/60 text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-100">
                                    <th class="py-3 px-5">No. Pesanan</th>
                                    <th class="py-3 px-5">Pelanggan</th>
                                    <th class="py-3 px-5">Total</th>
                                    <th class="py-3 px-5">Status Bayar</th>
                                    <th class="py-3 px-5">Channel</th>
                                    <th class="py-3 px-5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-xs">
                                @foreach($ordersToProcess as $order)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="py-3.5 px-5 font-mono font-bold text-[#363230]">
                                            <a href="{{ route('admin.transactions.show', $order->id) }}" class="hover:text-[#DF5E1D] transition-colors">
                                                {{ $order->order_number }}
                                            </a>
                                            <div class="text-[10px] font-sans font-normal text-gray-400">
                                                {{ $order->created_at->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-5">
                                            <div class="font-semibold text-[#363230]">{{ $order->user?->name ?? 'Guest / In-Store' }}</div>
                                            <div class="text-[10px] text-gray-400">{{ $order->items->count() }} item produk</div>
                                        </td>
                                        <td class="py-3.5 px-5 font-bold text-[#363230]">
                                            Rp {{ number_format($order->total, 0, ',', '.') }}
                                        </td>
                                        <td class="py-3.5 px-5">
                                            @if($order->payment_status === 'pending_verification')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                                    Cek Bukti Bayar
                                                </span>
                                            @elseif($order->payment_status === 'paid')
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    Lunas (Kemas)
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-5">
                                            @if($order->source === 'pos' || $order->source === 'offline')
                                                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">POS</span>
                                            @else
                                                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">WEB</span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-5 text-right">
                                            <a href="{{ route('admin.transactions.show', $order->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#DF5E1D] hover:bg-[#c45218] text-white rounded-lg text-xs font-bold transition shadow-2xs">
                                                <span>Proses</span>
                                                <iconify-icon icon="solar:arrow-right-linear" class="text-xs"></iconify-icon>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12 px-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-2">
                            <iconify-icon icon="solar:check-circle-bold" class="text-2xl"></iconify-icon>
                        </div>
                        <h4 class="text-xs font-bold text-[#363230]">Semua Pesanan Tertangani!</h4>
                        <p class="text-[11px] text-gray-400 mt-0.5">Tidak ada antrean pesanan yang menunggu verifikasi bayar atau pengiriman.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Kolom Kanan (4 cols): Top Selling & Alert Stok Menipis -->
        <div class="lg:col-span-4 space-y-6">

            <!-- Card 1: Top 5 Laptop Terlaris Bulan Ini -->
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                    <h3 class="text-xs font-bold text-[#363230] uppercase tracking-wider flex items-center gap-1.5">
                        <iconify-icon icon="solar:fire-bold" class="text-base text-orange-500"></iconify-icon>
                        Terlaris Bulan Ini
                    </h3>
                    <span class="text-[10px] text-gray-400">Unit terjual</span>
                </div>

                @if(count($topSelling) > 0)
                    <div class="space-y-3">
                        @foreach($topSelling as $index => $item)
                            <div class="flex items-center justify-between text-xs group">
                                <div class="flex items-center gap-2.5 overflow-hidden">
                                    <span class="w-5 h-5 rounded-full font-bold text-[10px] flex items-center justify-center shrink-0 {{ $index === 0 ? 'bg-amber-100 text-amber-800 font-extrabold' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="truncate">
                                        <div class="font-bold text-[#363230] truncate group-hover:text-[#DF5E1D] transition-colors">
                                            {{ $item->laptop?->name ?? 'Laptop #' . $item->laptop_id }}
                                        </div>
                                        <div class="text-[10px] text-gray-400">
                                            Sisa stok: {{ $item->laptop?->stock ?? 0 }} unit
                                        </div>
                                    </div>
                                </div>
                                <span class="font-extrabold text-[#DF5E1D] shrink-0 ml-2">
                                    {{ $item->total_sold }} terjual
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 text-center py-4">Belum ada data penjualan tercatat bulan ini.</p>
                @endif
            </div>

            <!-- Card 2: Peringatan Stok Menipis / Habis -->
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                    <h3 class="text-xs font-bold text-[#363230] uppercase tracking-wider flex items-center gap-1.5">
                        <iconify-icon icon="solar:danger-circle-bold" class="text-base text-red-500"></iconify-icon>
                        Perlu Restock Segera
                    </h3>
                    <a href="{{ route('admin.restocks.create') }}" class="text-[10px] font-bold text-[#DF5E1D] hover:underline">+ Restock</a>
                </div>

                @if($criticalStocks->count() > 0)
                    <div class="space-y-3">
                        @foreach($criticalStocks as $laptop)
                            <div class="flex items-center justify-between text-xs">
                                <div class="truncate pr-2">
                                    <a href="{{ route('admin.laptops.edit', $laptop->id) }}" class="font-bold text-[#363230] hover:text-[#DF5E1D] transition-colors block truncate">
                                        {{ $laptop->name }}
                                    </a>
                                    <span class="text-[10px] text-gray-400">{{ $laptop->brand }}</span>
                                </div>
                                <div class="shrink-0">
                                    @if($laptop->stock <= 0)
                                        <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-red-100 text-red-700">
                                            Habis (0)
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">
                                            Sisa {{ $laptop->stock }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-gray-400 text-center py-4">Semua stok laptop dalam kondisi aman.</p>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection