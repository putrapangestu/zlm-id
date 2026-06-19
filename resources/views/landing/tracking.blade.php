@extends('layouts.landing')

@section('title', 'Lacak Pesanan - ZLM.ID')

@php
    $trackingSteps = [
        'pending' => ['label' => 'Pesanan Dibuat', 'icon' => 'solar:bag-check-linear'],
        'processing' => ['label' => 'Diproses', 'icon' => 'solar:box-minimalistic-linear'],
        'shipped' => ['label' => 'Dikirim', 'icon' => 'solar:truck-linear'],
        'delivered' => ['label' => 'Diterima', 'icon' => 'solar:round-star-bold'],
    ];
    $statusOrder = ['pending', 'processing', 'shipped', 'delivered'];
    $currentStatusIndex = isset($order) ? array_search($order->status, $statusOrder) : -1;
    $psLabels = ['unpaid' => 'Belum Bayar', 'pending_verification' => 'Verifikasi', 'paid' => 'Lunas', 'expired' => 'Expired', 'failed' => 'Gagal'];
    $psColors = ['unpaid' => 'bg-yellow-100 text-yellow-700', 'pending_verification' => 'bg-blue-100 text-blue-700', 'paid' => 'bg-green-100 text-green-700', 'expired' => 'bg-rose-100 text-rose-700', 'failed' => 'bg-red-100 text-red-700'];
@endphp

@section('content')
<div class="min-h-screen bg-gray-50/50">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 py-12 lg:py-20">

        @if(!isset($order))
            {{-- Search Form --}}
            <div class="text-center mb-10">
                <div class="w-16 h-16 bg-[#DF5E1D]/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <iconify-icon icon="solar:map-point-linear" class="text-3xl text-[#DF5E1D]"></iconify-icon>
                </div>
                <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-[#363230] mb-2">Lacak Pesanan Anda</h1>
                <p class="text-gray-500">Masukkan nomor pesanan atau nomor resi untuk melihat status pengiriman</p>
            </div>

            @if(session('error'))
            <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm flex items-center gap-2">
                <iconify-icon icon="solar:info-circle-linear"></iconify-icon>
                {{ session('error') }}
            </div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 sm:p-8">
                <form method="POST" action="{{ route('tracking.by-number') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="reference" class="block text-sm font-medium text-[#363230] mb-2">Nomor Pesanan / Resi</label>
                        <div class="relative">
                            <iconify-icon icon="solar:magnifer-linear" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" style="stroke-width: 1.5;"></iconify-icon>
                            <input type="text" id="reference" name="reference" value="{{ old('reference') }}" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-11 pr-4 py-3 text-sm text-[#363230] placeholder-gray-400 focus:outline-none focus:border-[#DF5E1D]/30 focus:ring-4 focus:ring-[#DF5E1D]/10 transition-all"
                                placeholder="Contoh: ORD-ABC12345 atau JP0123456789">
                        </div>
                        @error('reference')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full bg-[#DF5E1D] text-white px-6 py-3 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors shadow-sm flex items-center justify-center gap-2">
                        <iconify-icon icon="solar:magnifer-linear" style="stroke-width: 1.5;"></iconify-icon>
                        Lacak Pesanan
                    </button>
                </form>
            </div>

        @else
            {{-- Tracking Result --}}
            <div class="text-center mb-8">
                <a href="{{ route('tracking.index') }}" class="inline-flex items-center gap-1 text-sm text-[#DF5E1D] hover:text-[#c45218] transition-colors mb-4">
                    <iconify-icon icon="solar:arrow-left-linear" style="stroke-width: 1.5;"></iconify-icon>
                    Lacak pesanan lain
                </a>
                <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight text-[#363230]">Detail Pesanan</h1>
            </div>

            {{-- Order Info --}}
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                    <div>
                        <p class="text-xs text-gray-400 uppercase tracking-widest font-medium mb-1">Order Number</p>
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
                            'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                            'processing' => 'bg-blue-50 text-blue-600 border-blue-100',
                            'shipped' => 'bg-purple-50 text-purple-600 border-purple-100',
                            'delivered' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                            'cancelled' => 'bg-rose-50 text-rose-600 border-rose-100',
                            default => 'bg-gray-50 text-gray-600 border-gray-100',
                        };
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-medium border {{ $statusColor }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Total</p>
                        <p class="text-base font-semibold text-[#363230]">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Payment</p>
                        <span class="px-2 py-1 rounded-lg text-xs font-medium {{ $psColors[$order->payment_status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $psLabels[$order->payment_status] ?? $order->payment_status }}
                        </span>
                    </div>
                </div>

                @if($order->tracking_number || $order->shipping_courier)
                <div class="mt-4 pt-4 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 text-sm">
                    @if($order->shipping_courier)
                    <span class="text-gray-500">Kurir: <strong class="text-[#363230]">{{ strtoupper($order->shipping_courier) }} - {{ $order->shipping_service }}</strong></span>
                    @endif
                    @if($order->tracking_number)
                    <span class="text-gray-500">Resi: <strong class="text-[#363230]">{{ $order->tracking_number }}</strong></span>
                    @endif
                </div>
                @endif

                @if($order->estimated_delivery)
                <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-2 text-sm">
                    <iconify-icon icon="solar:calendar-linear" class="text-[#DF5E1D]" style="stroke-width: 1.5;"></iconify-icon>
                    <span class="text-gray-500">Estimasi tiba: <strong class="text-[#363230]">{{ $order->estimated_delivery->format('d M Y') }}</strong></span>
                </div>
                @endif
            </div>

            {{-- Tracking Timeline --}}
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 mb-6">
                <h2 class="text-sm font-semibold text-[#363230] mb-6">Status Pengiriman</h2>

                <div class="relative">
                    @foreach($trackingSteps as $stepKey => $step)
                        @php
                            $stepIndex = array_search($stepKey, $statusOrder);
                            $isCompleted = $currentStatusIndex > $stepIndex;
                            $isCurrent = $order->status === $stepKey;
                            $isFuture = $currentStatusIndex < $stepIndex;

                            // Find tracking event for this step
                            $event = null;
                            if(is_array($order->tracking_history)) {
                                foreach($order->tracking_history as $h) {
                                    if($h['status'] === $stepKey) {
                                        $event = $h;
                                        break;
                                    }
                                }
                            }

                            // For pending step, use created_at
                            if($stepKey === 'pending' && !$event) {
                                $event = ['timestamp' => $order->created_at->toIso8601String(), 'description' => 'Pesanan berhasil dibuat', 'location' => null];
                            }
                        @endphp

                        <div class="flex gap-4 {{ !$loop->last ? 'pb-8' : '' }}">
                            {{-- Icon --}}
                            <div class="relative flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center z-10 shrink-0
                                    @if($isCompleted) bg-emerald-100 text-emerald-600
                                    @elseif($isCurrent) bg-[#DF5E1D]/10 text-[#DF5E1D]
                                    @else bg-gray-100 text-gray-300 @endif">
                                    @if($isCompleted)
                                        <iconify-icon icon="solar:check-read-bold" style="stroke-width: 1.5;"></iconify-icon>
                                    @else
                                        <iconify-icon icon="{{ $step['icon'] }}" style="stroke-width: 1.5;"></iconify-icon>
                                    @endif
                                </div>
                                @if(!$loop->last)
                                <div class="w-0.5 flex-1 mt-2
                                    @if($isCompleted || $isCurrent) bg-emerald-200
                                    @else bg-gray-200 @endif"></div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 pt-1.5">
                                <p class="text-sm font-medium {{ $isFuture ? 'text-gray-300' : 'text-[#363230]' }}">{{ $step['label'] }}</p>
                                @if($event && !$isFuture)
                                <p class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($event['timestamp'])->format('d M Y, H:i') }}</p>
                                @if(!empty($event['description']))
                                <p class="text-xs text-gray-500 mt-1">{{ $event['description'] }}</p>
                                @endif
                                @if(!empty($event['location']))
                                <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                                    <iconify-icon icon="solar:map-point-linear" style="stroke-width: 1.5;"></iconify-icon>
                                    {{ $event['location'] }}
                                </p>
                                @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Order Items --}}
            @if($order->items && $order->items->count() > 0)
            <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
                <h2 class="text-sm font-semibold text-[#363230] mb-4">Item yang Dipesan</h2>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 bg-gray-200 rounded-lg flex-shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-[#363230] truncate">{{ $item->product_name }}</p>
                            @if($item->variant_name)
                            <p class="text-xs text-gray-400">{{ $item->variant_name }}</p>
                            @endif
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-medium text-[#363230]">x{{ $item->quantity }}</p>
                            <p class="text-xs text-gray-400">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        @endif
    </div>
</div>
@endsection
