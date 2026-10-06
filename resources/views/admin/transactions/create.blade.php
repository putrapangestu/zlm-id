@extends('layouts.admin')
@section('title', 'Buat Transaksi')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.transactions.index') }}" class="text-gray-400 hover:text-[#363230]">
            <iconify-icon icon="solar:arrow-left-linear" class="text-xl"></iconify-icon>
        </a>
        <h1 class="text-2xl font-bold text-[#363230]">Create Transaction</h1>
    </div>

    <form action="{{ route('admin.transactions.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Customer --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200">
            <h3 class="text-lg font-semibold text-[#363230] mb-4">Customer</h3>
            <div>
                <label class="block text-sm font-medium text-[#363230] mb-1">Select Customer</label>
                <select name="user_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="">-- Select Customer --</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                    @endforeach
                </select>
                @error('user_id')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Items --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200">
            <h3 class="text-lg font-semibold text-[#363230] mb-4">Items</h3>
            <div id="items-container">
                <div class="item-row flex gap-3 mb-3">
                    <select name="items[0][laptop_id]" required class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="">-- Select Laptop --</option>
                        @foreach($laptops as $laptop)
                        <option value="{{ $laptop->id }}" data-price="{{ $laptop->price }}">{{ $laptop->name }} (Rp {{ number_format($laptop->price, 0, ',', '.') }}) - Stock: {{ $laptop->stock }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="items[0][quantity]" placeholder="Qty" min="1" required
                           class="w-24 px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <button type="button" onclick="this.closest('.item-row').remove()" 
                            class="px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg text-sm">×</button>
                </div>
            </div>
            <button type="button" onclick="addItem()" 
                    class="mt-2 text-sm text-[#DF5E1D] hover:underline">+ Add Item</button>
            @error('items')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Payment Method --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200">
            <h3 class="text-lg font-semibold text-[#363230] mb-4">Payment Method</h3>
            <div class="space-y-3">
                <select id="payment_method" name="payment_method" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="xendit" @selected(old('payment_method', 'xendit') === 'xendit')>Xendit (Online)</option>
                    <option value="winpay_qris" @selected(old('payment_method') === 'winpay_qris')>Winpay — QRIS</option>
                    <option value="winpay_va" @selected(old('payment_method') === 'winpay_va')>Winpay — Virtual Account</option>
                    <option value="manual_transfer" @selected(old('payment_method') === 'manual_transfer')>Manual Transfer</option>
                </select>
                <p class="text-xs text-gray-500">Pilih metode online atau manual untuk customer.</p>

                <div id="payment-channel-block" hidden>
                    <label for="payment_channel" class="block text-sm font-medium text-[#363230] mb-1">Bank Virtual Account</label>
                    <select id="payment_channel" name="payment_channel"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="">-- Pilih Bank --</option>
                        @foreach (['BRI', 'BNI', 'MANDIRI', 'PERMATA', 'BSI', 'MUAMALAT', 'BCA', 'CIMB', 'SINARMAS', 'BNC'] as $bank)
                            <option value="{{ $bank }}" @selected(old('payment_channel') === $bank)>{{ $bank }}</option>
                        @endforeach
                    </select>
                    @error('payment_channel')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            @error('payment_method')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Shipping --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200">
            <h3 class="text-lg font-semibold text-[#363230] mb-4">Shipping (Optional)</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-[#363230] mb-1">Shipping Address</label>
                    <textarea name="shipping_address" rows="2" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('shipping_address') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#363230] mb-1">Shipping Cost (Rp)</label>
                    <input type="number" name="shipping_cost" value="{{ old('shipping_cost') }}" min="0" step="1000"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                </div>
            </div>
        </div>

        {{-- Notes --}}
        <div class="bg-white p-6 rounded-xl border border-gray-200">
            <h3 class="text-lg font-semibold text-[#363230] mb-4">Notes</h3>
            <textarea name="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">{{ old('notes') }}</textarea>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end">
            <button type="submit" 
                    class="px-6 py-3 bg-[#DF5E1D] text-white rounded-xl hover:bg-[#c94f14] transition-colors font-medium">
                Create Transaction
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
let itemIndex = 1;
const paymentMethod = document.getElementById('payment_method');
const paymentChannelBlock = document.getElementById('payment-channel-block');
const paymentChannel = document.getElementById('payment_channel');

function updatePaymentChannelVisibility() {
    const needsChannel = paymentMethod.value === 'winpay_va';
    paymentChannelBlock.hidden = !needsChannel;
    paymentChannel.required = needsChannel;
    if (!needsChannel) paymentChannel.value = '';
}

paymentMethod.addEventListener('change', updatePaymentChannelVisibility);
updatePaymentChannelVisibility();

function addItem() {
    const container = document.getElementById('items-container');
    const div = document.createElement('div');
    div.className = 'item-row flex gap-3 mb-3';
    div.innerHTML = `
        <select name="items[${itemIndex}][laptop_id]" required class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm">
            <option value="">-- Select Laptop --</option>
            @foreach($laptops as $laptop)
            <option value="{{ $laptop->id }}">{{ $laptop->name }} (Rp {{ number_format($laptop->price, 0, ',', '.') }}) - Stock: {{ $laptop->stock }}</option>
            @endforeach
        </select>
        <input type="number" name="items[${itemIndex}][quantity]" placeholder="Qty" min="1" required
               class="w-24 px-3 py-2 border border-gray-300 rounded-lg text-sm">
        <button type="button" onclick="this.closest('.item-row').remove()" 
                class="px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg text-sm">×</button>
    `;
    container.appendChild(div);
    itemIndex++;
}
</script>
@endpush
@endsection
