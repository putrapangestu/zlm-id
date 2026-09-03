@extends('layouts.admin')

@section('title', 'Laporan Pembelian & Restock')
@section('heading', 'Laporan Pembelian')

@section('content')
<div class="space-y-6">

    {{-- Tabs: Supplier Restock vs Customer Orders --}}
    <div class="flex items-center gap-2 border-b border-gray-200/80 pb-3">
        <a href="{{ route('admin.reports.purchases', ['type' => 'supplier']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition {{ ($type ?? 'supplier') === 'supplier' ? 'bg-[#DF5E1D] text-white shadow-xs' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200/80' }}">
            <iconify-icon icon="solar:box-minimalistic-bold" class="mr-1"></iconify-icon>
            Pembelian Supplier (Restock Masuk)
        </a>
        <a href="{{ route('admin.reports.purchases', ['type' => 'customer']) }}" 
           class="px-4 py-2 rounded-xl text-xs font-bold transition {{ ($type ?? 'supplier') === 'customer' ? 'bg-[#DF5E1D] text-white shadow-xs' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200/80' }}">
            <iconify-icon icon="solar:cart-bold" class="mr-1"></iconify-icon>
            Pesanan Penjualan Pelanggan
        </a>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
        <form method="GET" action="{{ route('admin.reports.purchases') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <input type="hidden" name="type" value="{{ $type ?? 'supplier' }}">

            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Mulai Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded-xl border-gray-200 text-xs focus:ring-[#DF5E1D] focus:border-[#DF5E1D]">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded-xl border-gray-200 text-xs focus:ring-[#DF5E1D] focus:border-[#DF5E1D]">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full rounded-xl border-gray-200 text-xs focus:ring-[#DF5E1D] focus:border-[#DF5E1D]">
                    <option value="">Semua Status</option>
                    @if(($type ?? 'supplier') === 'supplier')
                        <option value="received" @selected(request('status') === 'received')>Diterima</option>
                        <option value="partially_checked" @selected(request('status') === 'partially_checked')>Sebagian QC</option>
                        <option value="completed" @selected(request('status') === 'completed')>Selesai QC</option>
                    @else
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        <option value="processing" @selected(request('status') === 'processing')>Processing</option>
                        <option value="shipped" @selected(request('status') === 'shipped')>Shipped</option>
                        <option value="delivered" @selected(request('status') === 'delivered')>Delivered</option>
                        <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                    @endif
                </select>
            </div>
            @if(($type ?? 'supplier') === 'customer')
            <div>
                <label class="block text-xs font-medium text-gray-700 mb-1">Status Pembayaran</label>
                <select name="payment_status" class="w-full rounded-xl border-gray-200 text-xs focus:ring-[#DF5E1D] focus:border-[#DF5E1D]">
                    <option value="">Semua</option>
                    <option value="paid" @selected(request('payment_status') === 'paid')>Lunas</option>
                    <option value="unpaid" @selected(request('payment_status') === 'unpaid')>Belum Bayar</option>
                </select>
            </div>
            @else
            <div></div>
            @endif

            <div class="flex items-end gap-2">
                <button type="submit" class="bg-[#DF5E1D] text-white px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-[#c45218] transition shadow-2xs">Filter</button>
                <a href="{{ route('admin.reports.purchases', ['type' => $type ?? 'supplier']) }}" class="px-4 py-2.5 border border-gray-200 rounded-xl text-xs text-gray-600 hover:bg-gray-50 transition">Reset</a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-xs">
            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">
                {{ ($type ?? 'supplier') === 'supplier' ? 'Total Batch Restock' : 'Total Pesanan' }}
            </div>
            <div class="text-2xl font-extrabold text-[#363230]">
                {{ $summary['total_batches'] ?? $summary['total_orders'] ?? 0 }}
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-xs">
            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">
                {{ ($type ?? 'supplier') === 'supplier' ? 'Total Nilai Pembelian (HPP)' : 'Total Omset Penjualan' }}
            </div>
            <div class="text-2xl font-extrabold font-mono text-[#DF5E1D]">
                Rp {{ number_format($summary['total_purchases'] ?? $summary['total_revenue'] ?? 0, 0, ',', '.') }}
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200/60 p-5 shadow-xs">
            <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wide mb-1">
                {{ ($type ?? 'supplier') === 'supplier' ? 'Total Unit Masuk' : 'Rata-Rata per Pesanan' }}
            </div>
            <div class="text-2xl font-extrabold text-[#363230]">
                @if(($type ?? 'supplier') === 'supplier')
                    {{ number_format($summary['total_units'] ?? 0) }} Unit
                @else
                    Rp {{ number_format($summary['avg_order'] ?? 0, 0, ',', '.') }}
                @endif
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-3xl border border-gray-200/60 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200">
                        <th class="px-4 py-3.5 font-bold text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3.5 font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        @if(($type ?? 'supplier') === 'supplier')
                            <th class="px-4 py-3.5 font-bold text-gray-500 uppercase tracking-wider">No. Batch</th>
                            <th class="px-4 py-3.5 font-bold text-gray-500 uppercase tracking-wider">Supplier</th>
                            <th class="px-4 py-3.5 font-bold text-gray-500 uppercase tracking-wider">Model Laptop</th>
                            <th class="text-center px-4 py-3.5 font-bold text-gray-500 uppercase tracking-wider">Qty Unit</th>
                            <th class="text-right px-4 py-3.5 font-bold text-gray-500 uppercase tracking-wider">Total Nilai</th>
                            <th class="text-center px-4 py-3.5 font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        @else
                            <th class="px-4 py-3.5 font-bold text-gray-500 uppercase tracking-wider">Order #</th>
                            <th class="px-4 py-3.5 font-bold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                            <th class="text-center px-4 py-3.5 font-bold text-gray-500 uppercase tracking-wider">Items</th>
                            <th class="text-right px-4 py-3.5 font-bold text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="text-center px-4 py-3.5 font-bold text-gray-500 uppercase tracking-wider">Pembayaran</th>
                            <th class="text-center px-4 py-3.5 font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @if(($type ?? 'supplier') === 'supplier')
                        @forelse ($records as $restock)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3.5 text-gray-400 font-bold">{{ $records->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3.5 text-gray-700 font-medium">{{ \Carbon\Carbon::parse($restock->purchase_date)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3.5 font-mono font-bold text-[#DF5E1D]">
                                <a href="{{ route('admin.restocks.show', $restock) }}" class="hover:underline">
                                    {{ $restock->restock_number }}
                                </a>
                            </td>
                            <td class="px-4 py-3.5 text-gray-800 font-semibold">{{ $restock->supplier_name }}</td>
                            <td class="px-4 py-3.5 text-gray-600">
                                {{ $restock->items->map(fn($it) => ($it->laptop->name ?? 'Model Baru') . ' (' . $it->quantity . 'x)')->join(', ') }}
                            </td>
                            <td class="px-4 py-3.5 text-center font-bold text-gray-800">{{ $restock->items->sum('quantity') }} unit</td>
                            <td class="px-4 py-3.5 text-right font-mono font-bold text-[#363230]">Rp {{ number_format($restock->total_amount, 0, ',', '.') }}</td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold
                                    @if($restock->status === 'completed') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif($restock->status === 'partially_checked') bg-blue-50 text-blue-700 border border-blue-200
                                    @else bg-amber-50 text-amber-700 border border-amber-200 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $restock->status)) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center py-10 text-gray-400">Belum ada data batch restock</td></tr>
                        @endforelse
                    @else
                        @forelse ($records as $order)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3.5 text-gray-400 font-bold">{{ $records->firstItem() + $loop->index }}</td>
                            <td class="px-4 py-3.5 text-gray-700 font-medium">{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3.5 font-mono font-bold text-[#DF5E1D]">
                                <a href="{{ route('admin.transactions.show', $order) }}" class="hover:underline">
                                    {{ $order->order_number }}
                                </a>
                            </td>
                            <td class="px-4 py-3.5 text-gray-800 font-semibold">{{ $order->user->name ?? $order->customer_name ?? 'Pelanggan POS' }}</td>
                            <td class="px-4 py-3.5 text-center text-gray-700">{{ $order->items_count ?? $order->items->count() }}</td>
                            <td class="px-4 py-3.5 text-right font-mono font-bold text-[#363230]">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold
                                    @if($order->payment_status === 'paid') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif($order->payment_status === 'unpaid') bg-amber-50 text-amber-700 border border-amber-200
                                    @else bg-gray-100 text-gray-500 @endif">
                                    {{ $order->payment_status }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold
                                    @if($order->status === 'delivered') bg-emerald-50 text-emerald-700 border border-emerald-200
                                    @elseif($order->status === 'shipped') bg-blue-50 text-blue-700 border border-blue-200
                                    @elseif($order->status === 'processing') bg-purple-50 text-purple-700 border border-purple-200
                                    @else bg-gray-100 text-gray-500 @endif">
                                    {{ $order->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center py-10 text-gray-400">Belum ada data pesanan</td></tr>
                        @endforelse
                    @endif
                </tbody>
            </table>
        </div>
        @if($records->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $records->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
