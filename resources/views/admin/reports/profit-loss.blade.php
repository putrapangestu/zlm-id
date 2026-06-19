@extends('layouts.admin')

@section('title', 'Laporan Laba Rugi')
@section('heading', 'Laporan Laba Rugi')

@section('content')
<div class="space-y-6">
    <!-- Filter Form -->
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Period</label>
                <select name="period" class="w-full rounded-lg border-gray-200 text-sm focus:ring-[#DF5E1D] focus:border-[#DF5E1D]">
                    <option value="monthly" @selected($period === 'monthly')>Monthly</option>
                    <option value="custom" @selected($period === 'custom')>Custom Range</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full rounded-lg border-gray-200 text-sm focus:ring-[#DF5E1D] focus:border-[#DF5E1D]">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full rounded-lg border-gray-200 text-sm focus:ring-[#DF5E1D] focus:border-[#DF5E1D]">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="bg-[#DF5E1D] text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-[#c45218] transition">Filter</button>
                <a href="{{ route('admin.reports.profit-loss') }}" class="px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">Reset</a>
            </div>
        </form>
    </div>

    <!-- Period Info -->
    <div class="bg-white rounded-xl border border-gray-200/60 p-5">
        <div class="text-sm text-gray-500">Periode: <span class="font-medium text-[#363230]">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</span></div>
        <div class="text-sm text-gray-500 mt-1">Total Transaksi: <span class="font-medium text-[#363230]">{{ $ordersCount }} order</span></div>
    </div>

    <!-- Financial Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Pendapatan -->
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Pendapatan</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Total Pendapatan</span>
                    <span class="text-lg font-semibold text-[#363230]">Rp {{ number_format($revenue, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Biaya Pengiriman</span>
                    <span class="text-lg font-semibold text-[#363230]">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Pajak (PPN)</span>
                    <span class="text-lg font-semibold text-[#363230]">Rp {{ number_format($taxTotal, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- HPP & Laba -->
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-4">Harga Pokok & Laba</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">HPP (Harga Pokok Penjualan)</span>
                    <span class="text-lg font-semibold text-[#363230]">Rp {{ number_format($hpp, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Laba Kotor</span>
                    <span class="text-lg font-semibold {{ $grossProfit >= 0 ? 'text-emerald-600' : 'text-red-600' }}">Rp {{ number_format($grossProfit, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Biaya Operasional (Pengiriman)</span>
                    <span class="text-lg font-semibold text-[#363230]">Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-3 bg-gray-50 rounded-xl px-4 mt-2">
                    <span class="text-sm font-semibold text-[#363230]">LABA BERSIH</span>
                    <span class="text-xl font-bold {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-red-600' }}">Rp {{ number_format($netProfit, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200/60 p-5">
            <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Total Pendapatan</div>
            <div class="text-2xl font-semibold text-[#363230]">Rp {{ number_format($revenue, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200/60 p-5">
            <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Total HPP</div>
            <div class="text-2xl font-semibold text-[#363230]">Rp {{ number_format($hpp, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200/60 p-5">
            <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Laba Kotor</div>
            <div class="text-2xl font-semibold {{ $grossProfit >= 0 ? 'text-emerald-600' : 'text-red-600' }}">Rp {{ number_format($grossProfit, 0, ',', '.') }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200/60 p-5">
            <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Laba Bersih</div>
            <div class="text-2xl font-semibold {{ $netProfit >= 0 ? 'text-emerald-600' : 'text-red-600' }}">Rp {{ number_format($netProfit, 0, ',', '.') }}</div>
        </div>
    </div>
</div>
@endsection
