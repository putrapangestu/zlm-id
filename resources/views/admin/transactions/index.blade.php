@extends('layouts.dashboard')

@section('title', 'Transaction Management - ZLM.ID Admin')
@section('page-title', 'Transaction Management')

@section('content')
<div class="space-y-6">

    {{-- Header with Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-semibold text-[#363230]">Transaction Management</h2>
            <p class="text-sm text-gray-500 mt-1">Monitor and manage all customer transactions</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <iconify-icon icon="solar:magnifer-linear" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" style="stroke-width: 1.5;"></iconify-icon>
                <input type="text" placeholder="Search transactions..." class="w-64 bg-white border border-gray-200 text-sm text-[#363230] placeholder-gray-400 rounded-xl py-2 pl-9 pr-4 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all">
            </div>
            <button class="flex items-center gap-2 bg-white border border-gray-200 text-sm text-gray-600 px-4 py-2 rounded-xl hover:bg-gray-50 transition-colors">
                <iconify-icon icon="solar:filter-linear" style="stroke-width: 1.5;"></iconify-icon>
                Filter
            </button>
            <button class="flex items-center gap-2 bg-[#DF5E1D] text-white text-sm font-medium px-4 py-2 rounded-xl hover:bg-orange-600 transition-colors shadow-sm">
                <iconify-icon icon="solar:export-linear" style="stroke-width: 1.5;"></iconify-icon>
                Export
            </button>
        </div>
    </div>

    {{-- Stats Strip (dummy) --}}
    @php
        $stats = [
            'total'   => 2,
            'success' => 1,
            'pending' => 1,
            'revenue' => 23250000,
        ];
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-gray-200/60 rounded-2xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
            <p class="text-[10px] font-medium uppercase tracking-widest text-gray-400 mb-2">Total Transaksi</p>
            <p class="text-2xl font-bold text-[#363230]">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-400 mt-1.5">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1"></span>Bulan ini
            </p>
        </div>
        <div class="bg-white border border-gray-200/60 rounded-2xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
            <p class="text-[10px] font-medium uppercase tracking-widest text-gray-400 mb-2">Berhasil</p>
            <p class="text-2xl font-bold text-emerald-600">{{ $stats['success'] }}</p>
            <p class="text-xs text-gray-400 mt-1.5">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 mr-1"></span>50% dari total
            </p>
        </div>
        <div class="bg-white border border-gray-200/60 rounded-2xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
            <p class="text-[10px] font-medium uppercase tracking-widest text-gray-400 mb-2">Menunggu</p>
            <p class="text-2xl font-bold text-amber-500">{{ $stats['pending'] }}</p>
            <p class="text-xs text-gray-400 mt-1.5">
                <span class="inline-block w-2 h-2 rounded-full bg-amber-400 mr-1"></span>Perlu konfirmasi
            </p>
        </div>
        <div class="bg-white border border-gray-200/60 rounded-2xl p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
            <p class="text-[10px] font-medium uppercase tracking-widest text-gray-400 mb-2">Total Pendapatan</p>
            <p class="text-xl font-bold text-[#363230]">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1.5">
                <span class="inline-block w-2 h-2 rounded-full bg-blue-400 mr-1"></span>Bulan berjalan
            </p>
        </div>
    </div>

    {{-- Dummy Transactions Data --}}
    @php
        $transactions = [
            [
                'id'               => 1,
                'invoice_number'   => 'TRX-20240501',
                'customer_name'    => 'Rizky Aditya',
                'customer_email'   => 'rizky.aditya@email.com',
                'customer_address' => 'Jl. Sudirman No. 12, Jakarta Pusat',
                'created_at'       => '08 Mei 2025',
                'created_time'     => '14:32',
                'payment_label'    => 'Transfer Bank',
                'payment_icon'     => 'solar:transfer-horizontal-linear',
                'payment_class'    => 'bg-blue-50 text-blue-600',
                'total_amount'     => 14500000,
                'status_label'     => 'Berhasil',
                'status_class'     => 'bg-emerald-50 text-emerald-600 border-emerald-100/50',
                'status_dot'       => 'bg-emerald-500',
            ],
            [
                'id'               => 2,
                'invoice_number'   => 'TRX-20240502',
                'customer_name'    => 'Sari Melinda',
                'customer_email'   => 'sari.melinda@gmail.com',
                'customer_address' => 'Jl. Gatot Subroto No. 45, Bandung',
                'created_at'       => '07 Mei 2025',
                'created_time'     => '09:15',
                'payment_label'    => 'GoPay',
                'payment_icon'     => 'solar:wallet-linear',
                'payment_class'    => 'bg-purple-50 text-purple-600',
                'total_amount'     => 8750000,
                'status_label'     => 'Menunggu',
                'status_class'     => 'bg-amber-50 text-amber-600 border-amber-100/50',
                'status_dot'       => 'bg-amber-500',
            ],
        ];
    @endphp

    {{-- Transactions Table --}}
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">No. Transaksi</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Info Customer</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Tanggal</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Metode Pembayaran</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Total</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="py-4 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @foreach($transactions as $transaction)
                    <tr class="hover:bg-gray-50/50 transition-colors">

                        {{-- No. Transaksi --}}
                        <td class="py-4 px-6">
                            <span class="font-mono text-xs font-semibold text-[#DF5E1D] bg-orange-50 px-2.5 py-1 rounded-lg">
                                #{{ $transaction['invoice_number'] }}
                            </span>
                        </td>

                        {{-- Info Customer --}}
                        <td class="py-4 px-6">
                            <div class="font-medium text-[#363230]">{{ $transaction['customer_name'] }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $transaction['customer_email'] }}</div>
                            <div class="text-xs text-gray-400 mt-0.5 max-w-[200px] truncate">{{ $transaction['customer_address'] }}</div>
                        </td>

                        {{-- Tanggal --}}
                        <td class="py-4 px-6">
                            <div class="text-[#363230]">{{ $transaction['created_at'] }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">{{ $transaction['created_time'] }} WIB</div>
                        </td>

                        {{-- Metode Pembayaran --}}
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium {{ $transaction['payment_class'] }}">
                                <iconify-icon icon="{{ $transaction['payment_icon'] }}" style="stroke-width: 1.5;"></iconify-icon>
                                {{ $transaction['payment_label'] }}
                            </span>
                        </td>

                        {{-- Total --}}
                        <td class="py-4 px-6 font-semibold text-[#363230]">
                            Rp {{ number_format($transaction['total_amount'], 0, ',', '.') }}
                        </td>

                        {{-- Status --}}
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium border {{ $transaction['status_class'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $transaction['status_dot'] }}"></span>
                                {{ $transaction['status_label'] }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="#" class="p-2 text-gray-400 hover:text-[#DF5E1D] hover:bg-orange-50 rounded-lg transition-colors" title="Lihat Detail">
                                    <iconify-icon icon="solar:eye-linear" style="stroke-width: 1.5;"></iconify-icon>
                                </a>
                                <a href="#" class="p-2 text-gray-400 hover:text-[#DF5E1D] hover:bg-orange-50 rounded-lg transition-colors" title="Edit">
                                    <iconify-icon icon="solar:pen-linear" style="stroke-width: 1.5;"></iconify-icon>
                                </a>
                                <button type="button" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <iconify-icon icon="solar:trash-bin-2-linear" style="stroke-width: 1.5;"></iconify-icon>
                                </button>
                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
            <div class="text-xs text-gray-400">
                Menampilkan <span class="font-medium text-gray-600">1</span> –
                <span class="font-medium text-gray-600">2</span> dari
                <span class="font-medium text-gray-600">2</span> transaksi
            </div>
            <div class="flex items-center gap-2">
                <button class="px-3 py-1.5 text-xs font-medium text-gray-400 bg-white border border-gray-200 rounded-lg disabled:opacity-40 disabled:cursor-not-allowed" disabled>
                    Sebelumnya
                </button>
                <button class="px-3 py-1.5 text-xs font-medium text-gray-400 bg-white border border-gray-200 rounded-lg disabled:opacity-40 disabled:cursor-not-allowed" disabled>
                    Berikutnya
                </button>
            </div>
        </div>
    </div>

</div>
@endsection
