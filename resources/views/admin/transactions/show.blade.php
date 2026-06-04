@extends('layouts.admin')
@section('title', 'Transaction: #' . $order->order_number)
@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.transactions.index') }}" class="text-gray-400 hover:text-[#363230]">
                <iconify-icon icon="solar:arrow-left-linear" class="text-xl"></iconify-icon>
            </a>
            <h1 class="text-2xl font-bold text-[#363230]">#{{ $order->order_number }}</h1>
        </div>
        <div class="flex gap-2">
            @if($order->payment_method === 'manual_transfer' && $order->payment_status === 'pending_verification')
            <form action="{{ route('admin.transactions.confirm-payment', $order) }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Konfirmasi pembayaran ini?')"
                        class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 text-sm font-medium">
                    Confirm Payment
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Status Badges --}}
    <div class="flex gap-3">
        @php
            $pmLabels = ['xendit' => 'Xendit', 'manual_transfer' => 'Manual Transfer'];
            $pmColors = ['xendit' => 'bg-blue-100 text-blue-700', 'manual_transfer' => 'bg-purple-100 text-purple-700'];
            $psLabels = ['unpaid' => 'Unpaid', 'pending_verification' => 'Pending Verification', 'paid' => 'Paid', 'failed' => 'Failed'];
            $psColors = ['unpaid' => 'bg-yellow-100 text-yellow-700', 'pending_verification' => 'bg-orange-100 text-orange-700', 'paid' => 'bg-green-100 text-green-700', 'failed' => 'bg-red-100 text-red-700'];
            $osLabels = ['pending' => 'Pending', 'processing' => 'Processing', 'completed' => 'Completed', 'cancelled' => 'Cancelled'];
            $osColors = ['pending' => 'bg-yellow-100 text-yellow-700', 'processing' => 'bg-blue-100 text-blue-700', 'completed' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-red-100 text-red-700'];
        @endphp
        <span class="px-3 py-1.5 rounded-lg text-xs font-medium {{ $pmColors[$order->payment_method] ?? 'bg-gray-100' }}">
            {{ $pmLabels[$order->payment_method] ?? $order->payment_method }}
        </span>
        <span class="px-3 py-1.5 rounded-lg text-xs font-medium {{ $psColors[$order->payment_status] ?? 'bg-gray-100' }}">
            {{ $psLabels[$order->payment_status] ?? $order->payment_status }}
        </span>
        <span class="px-3 py-1.5 rounded-lg text-xs font-medium {{ $osColors[$order->status] ?? 'bg-gray-100' }}">
            {{ $osLabels[$order->status] ?? $order->status }}
        </span>
    </div>

    <div class="grid grid-cols-3 gap-6">
        {{-- Customer Info --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200">
            <h3 class="text-lg font-semibold text-[#363230] mb-4">Customer</h3>
            <div class="space-y-2 text-sm">
                <p><span class="text-gray-500">Name:</span> <span class="font-medium">{{ $order->user->name ?? '-' }}</span></p>
                <p><span class="text-gray-500">Email:</span> <span class="font-medium">{{ $order->user->email ?? '-' }}</span></p>
                <p><span class="text-gray-500">Phone:</span> <span class="font-medium">{{ $order->shipping_phone ?? '-' }}</span></p>
                <p><span class="text-gray-500">Address:</span> <span class="font-medium">{{ $order->shipping_address ?? '-' }}</span></p>
            </div>
        </div>

        {{-- Payment Info --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200">
            <h3 class="text-lg font-semibold text-[#363230] mb-4">Payment</h3>
            <div class="space-y-2 text-sm">
                <p><span class="text-gray-500">Method:</span> <span class="font-medium">{{ $pmLabels[$order->payment_method] ?? $order->payment_method }}</span></p>
                <p><span class="text-gray-500">Status:</span> <span class="font-medium">{{ $psLabels[$order->payment_status] ?? $order->payment_status }}</span></p>
                @if($order->xendit_invoice_url)
                <p><a href="{{ $order->xendit_invoice_url }}" target="_blank" class="text-[#DF5E1D] hover:underline">View Xendit Invoice →</a></p>
                @endif
                @if($order->proof_of_transfer)
                <p><a href="{{ Storage::url($order->proof_of_transfer) }}" target="_blank" class="text-[#DF5E1D] hover:underline">View Proof of Transfer →</a></p>
                @endif
                @if($order->paid_at)
                <p><span class="text-gray-500">Paid at:</span> <span class="font-medium">{{ $order->paid_at->format('d M Y H:i') }}</span></p>
                <p><span class="text-gray-500">Approved by:</span> <span class="font-medium">{{ $order->approvedBy->name ?? '-' }}</span></p>
                @endif
            </div>
        </div>

        {{-- Shipping Info --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200">
            <h3 class="text-lg font-semibold text-[#363230] mb-4">Shipping</h3>
            <div class="space-y-2 text-sm">
                <p><span class="text-gray-500">Address:</span> <span class="font-medium">{{ $order->shipping_address ?? '-' }}</span></p>
                <p><span class="text-gray-500">Courier:</span> <span class="font-medium">{{ strtoupper($order->shipping_courier ?? '-') }}</span></p>
                <p><span class="text-gray-500">Service:</span> <span class="font-medium">{{ $order->shipping_service ?? '-' }}</span></p>
                <p><span class="text-gray-500">Cost:</span> <span class="font-medium">{{ $order->shipping_cost ? 'Rp '.number_format($order->shipping_cost, 0, ',', '.') : '-' }}</span></p>
                <p><span class="text-gray-500">ETD:</span> <span class="font-medium">{{ $order->shipping_etd ?? '-' }}</span></p>
            </div>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="mt-6 bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-[#363230]">Order Items</h3>
        </div>
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-6 py-3 text-sm font-semibold text-[#363230]">Product</th>
                    <th class="text-center px-6 py-3 text-sm font-semibold text-[#363230]">Qty</th>
                    <th class="text-right px-6 py-3 text-sm font-semibold text-[#363230]">Price</th>
                    <th class="text-right px-6 py-3 text-sm font-semibold text-[#363230]">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->items as $item)
                <tr class="border-b border-gray-100">
                    <td class="px-6 py-3 text-sm font-medium text-[#363230]">{{ $item->laptop->name ?? 'Product' }}</td>
                    <td class="px-6 py-3 text-sm text-center">{{ $item->quantity }}</td>
                    <td class="px-6 py-3 text-sm text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="px-6 py-3 text-sm text-right font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500 text-sm">No items</td></tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50">
                <tr><td colspan="3" class="px-6 py-2 text-sm text-right text-gray-500">Subtotal</td>
                    <td class="px-6 py-2 text-sm text-right font-medium">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td></tr>
                <tr><td colspan="3" class="px-6 py-2 text-sm text-right text-gray-500">Tax ({{ config('settings.tax_rate', 11) }}%)</td>
                    <td class="px-6 py-2 text-sm text-right font-medium">Rp {{ number_format($order->tax, 0, ',', '.') }}</td></tr>
                @if($order->shipping_cost)
                <tr><td colspan="3" class="px-6 py-2 text-sm text-right text-gray-500">Shipping</td>
                    <td class="px-6 py-2 text-sm text-right font-medium">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td></tr>
                @endif
                <tr class="border-t border-gray-200">
                    <td colspan="3" class="px-6 py-3 text-sm text-right font-bold text-[#363230]">Total</td>
                    <td class="px-6 py-3 text-sm text-right font-bold text-[#DF5E1D]">Rp {{ number_format($order->total, 0, ',', '.') }}</td></tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
