@extends('layouts.landing')

@section('title', 'Checkout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20">
    <h1 class="text-3xl font-semibold tracking-tight text-[#363230] mb-8">Checkout</h1>

    <form method="POST" action="{{ route('orders.place') }}">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-[#363230] mb-4">Order Summary</h2>

                    <div class="divide-y divide-gray-100">
                        @foreach ($cart->items as $item)
                            <div class="flex items-center gap-4 py-4">
                                <div class="w-16 h-16 bg-gray-50 rounded-xl flex items-center justify-center p-2 border border-gray-100">
                                    @if ($item->laptop->image_url)
                                        <img src="{{ $item->laptop->image_url_full }}" alt="" class="w-full h-full object-contain mix-blend-multiply">
                                    @else
                                        <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-2xl text-gray-300"></iconify-icon>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-[#363230]">{{ $item->laptop->name }}</p>
                                    @if ($item->addon)
                                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-[#166534] bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md mt-1">
                                            <iconify-icon icon="solar:gift-bold" class="text-xs"></iconify-icon>
                                            Bundle: {{ $item->addon->name }} (+Rp {{ number_format($item->addon_price, 0, ',', '.') }})
                                        </span>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $item->laptop->brand }} &bull; {{ $item->laptop->processor }} &bull; Qty: {{ $item->quantity }}</p>
                                </div>
                                <p class="font-medium text-[#363230]">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-[#363230] mb-4">Shipping Address</h2>
                    <div x-data="shippingCalculator()" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-2">Street Address</label>
                            <input type="text" name="shipping_address" value="{{ old('shipping_address') }}" placeholder="Jl. Contoh No. 123" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all focus:bg-white" required>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-2">Phone</label>
                                <input type="text" name="shipping_phone" value="{{ old('shipping_phone') }}" placeholder="081234567890" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all focus:bg-white" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-2">Postal Code</label>
                                <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" placeholder="12345" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all focus:bg-white" required>
                            </div>
                        </div>

                        {{-- Province --}}
                        <div>
                            <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-2">Province</label>
                            <select x-model="provinceId" @change="loadCities()" required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white appearance-none cursor-pointer">
                                <option value="">-- Select Province --</option>
                                <template x-for="province in provinces" :key="province.province_id">
                                    <option :value="province.province_id" x-text="province.province"></option>
                                </template>
                            </select>
                        </div>

                        {{-- City --}}
                        <div>
                            <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-2">City</label>
                            <select x-model="cityId" @change="loadShippingCost()" required
                                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white appearance-none cursor-pointer">
                                <option value="">-- Select City --</option>
                                <template x-for="city in cities" :key="city.city_id">
                                    <option :value="city.city_id" x-text="city.city_name + ' (' + city.type + ')'"></option>
                                </template>
                            </select>
                            <input type="hidden" name="shipping_city_id" x-model="cityId">
                            <input type="hidden" name="shipping_city_name" x-model="cityName">
                            <input type="hidden" name="shipping_province_name" x-model="provinceName">
                        </div>

                        {{-- Legacy fields for backward compat --}}
                        <input type="hidden" name="shipping_city" :value="cityName">
                        <input type="hidden" name="shipping_province" :value="provinceName">

                        {{-- Shipping Options --}}
                        <div x-show="shippingOptions.length > 0" x-transition>
                            <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-2">Shipping Options</label>

                            <div x-show="loadingShipping" class="flex items-center gap-2 text-sm text-gray-500 py-3">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                Loading shipping options...
                            </div>

                            <template x-for="(courier, idx) in shippingOptions" :key="idx">
                                <div class="mb-3">
                                    <p class="text-xs font-bold text-gray-500 mb-1 uppercase tracking-wide" x-text="courier.code + ' — ' + courier.name"></p>
                                    <template x-for="cost in courier.costs" :key="cost.service">
                                        <label class="flex items-center gap-3 p-3 border rounded-xl mb-2 cursor-pointer transition-all"
                                               :class="selectedKey === (courier.code + '-' + cost.service) ? 'border-[#DF5E1D] bg-orange-50' : 'border-gray-200 hover:bg-gray-50'">
                                            <input type="radio" name="shipping_service_selected"
                                                   class="accent-[#DF5E1D]"
                                                   :value="JSON.stringify({courier: courier.code, service: cost.service, cost: cost.cost[0].value, etd: cost.cost[0].etd})"
                                                   @change="selectShipping(courier.code, cost.service, cost.cost[0].value, cost.cost[0].etd)">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-[#363230]" x-text="cost.service"></p>
                                                <p class="text-xs text-gray-500" x-text="'Est. ' + cost.cost[0].etd"></p>
                                            </div>
                                            <p class="text-sm font-semibold text-[#DF5E1D] whitespace-nowrap" x-text="'Rp ' + numberFormat(cost.cost[0].value)"></p>
                                        </label>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <input type="hidden" name="shipping_cost" x-model="shippingCost">
                        <input type="hidden" name="shipping_courier" x-model="shippingCourier">
                        <input type="hidden" name="shipping_service" x-model="shippingService">
                        <input type="hidden" name="shipping_etd" x-model="shippingEtd">
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-[#363230] mb-4">Notes (Optional)</h2>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all" placeholder="Any special instructions...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="lg:col-span-1" x-data="orderSummary()" x-init="init()">
                <div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 sticky top-24">
                    <h2 class="text-lg font-semibold text-[#363230] mb-4">Order Total</h2>

                    @php
                        $subtotal = $cart->total;
                        $tax = round($subtotal * (float) config('settings.tax_rate', 11) / 100, 2);
                    @endphp

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Subtotal</span>
                            <span class="font-medium text-[#363230]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tax ({{ config('settings.tax_rate', 11) }}%)</span>
                            <span class="font-medium text-[#363230]">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Shipping</span>
                            <span class="font-medium text-[#363230]" x-text="shippingCost > 0 ? 'Rp ' + numberFormat(shippingCost) : '—'">—</span>
                        </div>
                        <div class="border-t border-gray-100 pt-3 flex justify-between">
                            <span class="font-semibold text-[#363230]">Total</span>
                            <span class="text-xl font-semibold text-[#363230]">Rp <span x-text="numberFormat({{ $subtotal }} + {{ $tax }} + shippingCost)"></span></span>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-blue-50 rounded-xl">
                        <div class="flex items-center gap-2">
                            <iconify-icon icon="solar:card-bold" class="text-blue-500"></iconify-icon>
                            <span class="text-xs font-medium text-blue-700">Pembayaran akan diproses melalui Xendit</span>
                        </div>
                    </div>

                    <div x-show="shippingCost == 0 || !shippingSelected" class="mt-4">
                        <p class="text-xs text-amber-600 text-center">Pilih opsi pengiriman terlebih dahulu</p>
                    </div>

                    <button type="submit" class="w-full bg-[#DF5E1D] text-white py-3 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors mt-6 disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="!shippingSelected">
                        Pay via Xendit
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function shippingCalculator() {
    return {
        provinces: [],
        cities: [],
        shippingOptions: [],
        provinceId: '',
        cityId: '',
        cityName: '',
        provinceName: '',
        shippingCost: 0,
        shippingCourier: '',
        shippingService: '',
        shippingEtd: '',
        selectedKey: '',
        loadingShipping: false,

        init() {
            fetch('/shipping/provinces')
                .then(r => r.json())
                .then(data => { this.provinces = data; })
                .catch(e => console.error('Failed to load provinces', e));
        },

        loadCities() {
            this.cities = [];
            this.cityId = '';
            this.shippingOptions = [];
            this.shippingSelected = false;
            const province = this.provinces.find(p => p.province_id == this.provinceId);
            this.provinceName = province ? province.province : '';

            if (!this.provinceId) return;

            fetch('/shipping/cities?province_id=' + this.provinceId)
                .then(r => r.json())
                .then(data => { this.cities = data; })
                .catch(e => console.error('Failed to load cities', e));
        },

        loadShippingCost() {
            this.shippingOptions = [];
            this.shippingSelected = false;
            if (!this.cityId) return;

            const city = this.cities.find(c => c.city_id == this.cityId);
            this.cityName = city ? city.city_name : '';

            this.loadingShipping = true;
            const weight = {{ $totalWeight ?? 1000 }};

            fetch('/shipping/cost', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({destination: this.cityId, weight: weight})
            })
            .then(r => r.json())
            .then(data => {
                this.shippingOptions = data.map(item => ({
                    code: item.code,
                    name: item.name,
                    costs: item.costs
                }));
            })
            .catch(e => console.error('Failed to load shipping cost', e))
            .finally(() => { this.loadingShipping = false; });
        },

        selectShipping(courier, service, cost, etd) {
            this.shippingCost = cost;
            this.shippingCourier = courier;
            this.shippingService = service;
            this.shippingEtd = etd;
            this.selectedKey = courier + '-' + service;

            window.dispatchEvent(new CustomEvent('shipping-selected', {
                detail: { cost: cost, courier: courier, service: service }
            }));
        },

        numberFormat(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    }
}

function orderSummary() {
    return {
        shippingCost: 0,
        shippingSelected: false,

        init() {
            window.addEventListener('shipping-selected', (e) => {
                this.shippingCost = e.detail.cost;
                this.shippingSelected = true;
            });
        },

        numberFormat(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    }
}
</script>
@endpush
