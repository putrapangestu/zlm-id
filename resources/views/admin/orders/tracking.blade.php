@extends('layouts.admin')

@section('title', 'Update Tracking - ZLM.ID Admin')
@section('heading', 'Update Tracking')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('admin.orders.index') }}" class="hover:text-[#DF5E1D] transition-colors">Orders</a>
        <iconify-icon icon="solar:alt-arrow-right-linear" style="stroke-width: 1.5;"></iconify-icon>
        <span class="text-[#363230] font-medium">{{ $order->order_number }}</span>
        <iconify-icon icon="solar:alt-arrow-right-linear" style="stroke-width: 1.5;"></iconify-icon>
        <span class="text-[#363230] font-medium">Tracking</span>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 sm:p-8">
        {{-- Order Summary --}}
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-widest font-medium">Order</p>
                <p class="text-lg font-semibold text-[#363230]">{{ $order->order_number }}</p>
            </div>
            @php
                $statusLabel = match($order->status) {
                    'pending' => 'Menunggu',
                    'processing' => 'Diproses',
                    'shipped' => 'Dikirim',
                    'delivered' => 'Diterima',
                    'cancelled' => 'Dibatalkan',
                    default => ucfirst($order->status),
                };
                $statusColor = match($order->status) {
                    'pending' => 'bg-amber-50 text-amber-600',
                    'processing' => 'bg-blue-50 text-blue-600',
                    'shipped' => 'bg-purple-50 text-purple-600',
                    'delivered' => 'bg-emerald-50 text-emerald-600',
                    'cancelled' => 'bg-rose-50 text-rose-600',
                    default => 'bg-gray-50 text-gray-600',
                };
            @endphp
            <span class="px-3 py-1.5 rounded-full text-xs font-medium {{ $statusColor }}">{{ $statusLabel }}</span>
        </div>

        {{-- Update Status Form --}}
        <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="space-y-5">
            @csrf
            @method('PATCH')

            {{-- Status --}}
            <div>
                <label for="status" class="block text-sm font-medium text-[#363230] mb-1.5">Update Status</label>
                <div class="relative">
                    <select id="status" name="status"
                        class="appearance-none w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all cursor-pointer">
                        <option value="pending" @selected($order->status === 'pending')>Pending</option>
                        <option value="processing" @selected($order->status === 'processing')>Processing</option>
                        <option value="shipped" @selected($order->status === 'shipped')>Shipped</option>
                        <option value="delivered" @selected($order->status === 'delivered')>Delivered</option>
                        <option value="cancelled" @selected($order->status === 'cancelled')>Cancelled</option>
                    </select>
                    <iconify-icon icon="solar:alt-arrow-down-linear" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" style="stroke-width: 1.5;"></iconify-icon>
                </div>
            </div>

            {{-- Tracking Number --}}
            <div>
                <label for="tracking_number" class="block text-sm font-medium text-[#363230] mb-1.5">Nomor Resi</label>
                <input type="text" id="tracking_number" name="tracking_number" value="{{ $order->tracking_number }}"
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-[#363230] placeholder-gray-400 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all"
                    placeholder="Contoh: JP0123456789">
                <p class="mt-1 text-xs text-gray-400">Wajib diisi saat status diubah ke "Shipped"</p>
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-500 bg-gray-100 hover:bg-gray-200 transition-colors">
                    Back
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl text-sm font-medium text-white bg-[#DF5E1D] hover:bg-[#c45218] transition-colors shadow-sm">
                    Update Status
                </button>
            </div>
        </form>
    </div>

    {{-- Tracking History --}}
    @if(!empty($order->tracking_history) && count($order->tracking_history) > 0)
    <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 mt-6">
        <h3 class="text-sm font-semibold text-[#363230] mb-4">Riwayat Tracking</h3>
        <div class="space-y-3">
            @foreach(array_reverse($order->tracking_history) as $event)
            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl">
                <div class="w-8 h-8 rounded-full bg-[#DF5E1D]/10 flex items-center justify-center shrink-0 mt-0.5">
                    <iconify-icon icon="solar:clock-circle-linear" class="text-[#DF5E1D] text-sm" style="stroke-width: 1.5;"></iconify-icon>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-[#363230] uppercase">{{ $event['status'] }}</span>
                        <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($event['timestamp'])->format('d M Y, H:i') }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-0.5">{{ $event['description'] }}</p>
                    @if(!empty($event['location']))
                    <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                        <iconify-icon icon="solar:map-point-linear" style="stroke-width: 1.5;"></iconify-icon>
                        {{ $event['location'] }}
                    </p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
