# TRX-3: User Checkout — Wajib Xendit + RajaOngkir Shipping

## Tujuan
Memodifikasi checkout user: **WAJIB Xendit** (tanpa pilihan metode) + perhitungan ongkos kirim via RajaOngkir.

## File Baru
- `app/Http/Controllers/ShippingController.php` (endpoints AJAX untuk RajaOngkir)

## File Diubah
- `app/Http/Controllers/OrderController.php`
- `resources/views/orders/checkout.blade.php`
- `resources/views/orders/confirmation.blade.php`
- `routes/web.php`

## Route Baru
```php
// Shipping AJAX (RajaOngkir)
Route::get('/shipping/provinces', [ShippingController::class, 'provinces'])->name('shipping.provinces');
Route::get('/shipping/cities', [ShippingController::class, 'cities'])->name('shipping.cities');
Route::post('/shipping/cost', [ShippingController::class, 'cost'])->name('shipping.cost');

// Xendit callback
Route::get('/orders/{order}/xendit/callback', [OrderController::class, 'xenditCallback'])->name('orders.xendit.callback');
```

## Detail Implementasi

### 1. ShippingController
```php
<?php

namespace App\Http\Controllers;

use App\Services\RajaOngkirService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function __construct(
        protected RajaOngkirService $rajaOngkir
    ) {}

    public function provinces()
    {
        try {
            $provinces = $this->rajaOngkir->getProvinces();
            return response()->json(['data' => $provinces]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function cities(Request $request)
    {
        $request->validate(['province_id' => 'required|integer']);
        
        try {
            $cities = $this->rajaOngkir->getCities($request->province_id);
            return response()->json(['data' => $cities]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function cost(Request $request)
    {
        $request->validate([
            'destination' => 'required|integer',
            'weight' => 'required|integer|min:1',
        ]);
        
        try {
            $origin = config('rajaongkir.origin_city_id');
            $weight = $request->weight;
            $destination = $request->destination;
            
            $allCosts = [];
            $couriers = config('rajaongkir.couriers', ['jne', 'pos', 'tiki']);
            
            foreach ($couriers as $courier) {
                $costs = $this->rajaOngkir->getCost($origin, $destination, $weight, $courier);
                $allCosts = array_merge($allCosts, $costs);
            }
            
            return response()->json(['data' => $allCosts]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
```

### 2. Checkout View (`checkout.blade.php`)

**Payment Method**: HILANG (tidak ada pilihan — forced Xendit)

**Shipping Section** dengan RajaOngkir (Alpine.js untuk interaktif):

```html
<div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6">
    <h2 class="text-lg font-semibold text-[#363230] mb-4">Shipping Address</h2>
    
    <div class="space-y-4" x-data="shippingCalculator()">
        <!-- Alamat -->
        <div>
            <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-2">Street Address</label>
            <input type="text" name="shipping_address" value="{{ old('shipping_address') }}" placeholder="Jl. Contoh No. 123" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all focus:bg-white" required>
        </div>
        
        <!-- Province -->
        <div>
            <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-2">Province</label>
            <select name="shipping_province" x-model="provinceId" @change="loadCities" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white" required>
                <option value="">-- Pilih Provinsi --</option>
                <template x-for="prov in provinces" :key="prov.province_id">
                    <option :value="prov.province_id" x-text="prov.province"></option>
                </template>
            </select>
        </div>
        
        <!-- City -->
        <div>
            <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-2">City</label>
            <select name="shipping_city_id" x-model="cityId" @change="loadShippingCost" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white" required>
                <option value="">-- Pilih Kota --</option>
                <template x-for="city in cities" :key="city.city_id">
                    <option :value="city.city_id" x-text="city.type + ' ' + city.city_name"></option>
                </template>
            </select>
        </div>
        
        <!-- Hidden inputs for shipping details -->
        <input type="hidden" name="shipping_city_name" x-model="cityName">
        <input type="hidden" name="shipping_province_name" x-model="provinceName">
        
        <!-- Postal Code & Phone -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-2">Postal Code</label>
                <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code') }}" placeholder="12345" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all focus:bg-white" required>
            </div>
            <div>
                <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-2">Phone</label>
                <input type="text" name="shipping_phone" value="{{ old('shipping_phone') }}" placeholder="081234567890" class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all focus:bg-white" required>
            </div>
        </div>
        
        <!-- Shipping Cost Options -->
        <div x-show="loadingShipping" class="text-center py-4">
            <p class="text-sm text-gray-500">Menghitung ongkos kirim...</p>
        </div>
        
        <div x-show="shippingOptions.length > 0 && !loadingShipping">
            <label class="block text-xs font-semibold text-[#363230] uppercase tracking-wide mb-3">Pilih Kurir</label>
            <div class="space-y-2">
                <template x-for="(option, idx) in shippingOptions" :key="idx">
                    <label class="flex items-center gap-3 p-3 border-2 rounded-xl cursor-pointer transition-all"
                           :class="selectedShipping === idx ? 'border-[#DF5E1D] bg-orange-50/30' : 'border-gray-200 hover:border-gray-300'">
                        <input type="radio" name="shipping_option" :value="idx" x-model="selectedShipping" @change="updateTotal" class="accent-[#DF5E1D]">
                        <div class="flex-1 flex items-center justify-between">
                            <div>
                                <p class="font-medium text-[#363230] text-sm" x-text="option.courier + ' - ' + option.service"></p>
                                <p class="text-xs text-gray-500" x-text="'Estimasi: ' + option.etd + ' hari'"></p>
                            </div>
                            <p class="font-semibold text-[#363230]" x-text="'Rp ' + formatPrice(option.cost)"></p>
                        </div>
                    </label>
                </template>
            </div>
        </div>
        
        <div x-show="!loadingShipping && shippingOptions.length === 0 && cityId" class="text-center py-4">
            <p class="text-sm text-amber-600">Tidak ada opsi pengiriman tersedia untuk kota ini.</p>
        </div>
    </div>
</div>
```

### 3. Alpine.js Component untuk Shipping
```javascript
function shippingCalculator() {
    return {
        provinces: [],
        provinceId: '',
        cities: [],
        cityId: '',
        provinceName: '',
        cityName: '',
        shippingOptions: [],
        selectedShipping: null,
        loadingShipping: false,
        
        init() {
            fetch('/shipping/provinces')
                .then(r => r.json())
                .then(d => { this.provinces = d.data; });
        },
        
        loadCities() {
            this.cityId = '';
            this.cities = [];
            this.shippingOptions = [];
            this.selectedShipping = null;
            
            if (!this.provinceId) return;
            
            const prov = this.provinces.find(p => p.province_id == this.provinceId);
            this.provinceName = prov ? prov.province : '';
            
            fetch(`/shipping/cities?province_id=${this.provinceId}`)
                .then(r => r.json())
                .then(d => { this.cities = d.data; });
        },
        
        loadShippingCost() {
            this.shippingOptions = [];
            this.selectedShipping = null;
            
            if (!this.cityId) return;
            
            const city = this.cities.find(c => c.city_id == this.cityId);
            this.cityName = city ? city.type + ' ' + city.city_name : '';
            
            // Get total weight from cart
            const weight = {{ $cart->items->sum(fn($i) => $i->laptop->weight * $i->quantity) }} * 1000 || 1000;
            
            this.loadingShipping = true;
            
            fetch('/shipping/cost', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ destination: this.cityId, weight: weight })
            })
                .then(r => r.json())
                .then(d => {
                    this.loadingShipping = false;
                    if (d.data) {
                        // Flatten costs from all couriers
                        const options = [];
                        d.data.forEach(courier => {
                            courier.costs.forEach(cost => {
                                options.push({
                                    courier: courier.code.toUpperCase(),
                                    courier_name: courier.name,
                                    service: cost.service,
                                    description: cost.description,
                                    cost: cost.cost[0].value,
                                    etd: cost.cost[0].etd,
                                });
                            });
                        });
                        this.shippingOptions = options;
                    }
                })
                .catch(() => { this.loadingShipping = false; });
        },
        
        updateTotal() {
            // Dispatch event to update total display
            this.$dispatch('shipping-selected', this.shippingOptions[this.selectedShipping]);
        },
        
        formatPrice(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
    };
}
```

### 4. Updated Total Sidebar
```html
<div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm p-6 sticky top-24"
     x-data="{ shippingCost: 0, subtotal: {{ $cart->total }}, tax: {{ round($cart->total * 0.11, 2) }} }"
     @shipping-selected.window="shippingCost = $event.detail.cost">
    
    <h2 class="text-lg font-semibold text-[#363230] mb-4">Total</h2>
    
    <div class="space-y-3 text-sm">
        <div class="flex justify-between">
            <span class="text-gray-500">Subtotal</span>
            <span class="font-medium text-[#363230]">Rp {{ number_format($cart->total, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">Tax (11%)</span>
            <span class="font-medium text-[#363230]">Rp {{ number_format(round($cart->total * 0.11), 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-500">Shipping</span>
            <span class="font-medium text-[#363230]" x-text="'Rp ' + (shippingCost ? shippingCost.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '0')"></span>
        </div>
        <input type="hidden" name="shipping_cost" x-model="shippingCost">
        <input type="hidden" name="shipping_courier" x-model="shippingOptions.length ? shippingOptions[selectedShipping]?.courier : ''">
        <input type="hidden" name="shipping_service" x-model="shippingOptions.length ? shippingOptions[selectedShipping]?.service : ''">
        <input type="hidden" name="shipping_etd" x-model="shippingOptions.length ? shippingOptions[selectedShipping]?.etd : ''">
        
        <div class="border-t border-gray-100 pt-3 flex justify-between">
            <span class="font-semibold text-[#363230]">Total</span>
            <span class="text-xl font-semibold text-[#363230]" x-text="'Rp ' + (subtotal + tax + shippingCost).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')"></span>
        </div>
    </div>
    
    <button type="submit" class="w-full bg-[#DF5E1D] text-white py-3 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors mt-6">
        Bayar dengan Xendit →
    </button>
</div>
```

### 5. Updated `OrderController@placeOrder`
```php
public function placeOrder(Request $request)
{
    $cart = Cart::where('user_id', auth()->id())
        ->with('items.laptop', 'items.variant')
        ->first();

    if (!$cart || $cart->items->isEmpty()) {
        return redirect()->route('cart.index')->with('error', 'Cart is empty.');
    }

    // Validasi stock
    foreach ($cart->items as $item) {
        if ($item->laptop->stock < $item->quantity) {
            return redirect()->back()->with('error', "Insufficient stock for {$item->laptop->name}.");
        }
        if ($item->variant && $item->variant->stock < $item->quantity) {
            return redirect()->back()->with('error', "Insufficient stock for variant {$item->variant->name}.");
        }
    }

    // Validasi form
    $request->validate([
        'shipping_address' => 'required|string|max:255',
        'shipping_city_id' => 'required|string',
        'shipping_city_name' => 'required|string',
        'shipping_province_name' => 'required|string',
        'shipping_postal_code' => 'required|string|max:20',
        'shipping_phone' => 'required|string|max:20',
        'shipping_cost' => 'required|numeric|min:0',
        'shipping_courier' => 'required|string',
        'shipping_service' => 'required|string',
        'shipping_etd' => 'nullable|string',
        'notes' => 'nullable|string|max:500',
    ]);

    $subtotal = $cart->total;
    $tax = round($subtotal * 0.11, 2);
    $shippingCost = (float) $request->shipping_cost;
    $total = $subtotal + $tax + $shippingCost;

    // Buat Order
    $order = Order::create([
        'user_id' => auth()->id(),
        'subtotal' => $subtotal,
        'tax' => $tax,
        'total' => $total,
        'status' => 'pending',
        'payment_method' => 'xendit', // WAJIB Xendit untuk user
        'payment_status' => 'unpaid',
        'notes' => $request->notes,
        'shipping_address' => $request->shipping_address,
        'shipping_city' => $request->shipping_city_name,
        'shipping_province' => $request->shipping_province_name,
        'shipping_postal_code' => $request->shipping_postal_code,
        'shipping_phone' => $request->shipping_phone,
        // RajaOngkir fields
        'shipping_cost' => $shippingCost,
        'shipping_courier' => $request->shipping_courier,
        'shipping_service' => $request->shipping_service,
        'shipping_etd' => $request->shipping_etd,
        'shipping_city_id' => $request->shipping_city_id,
        'shipping_city_name' => $request->shipping_city_name,
        'shipping_province_name' => $request->shipping_province_name,
    ]);

    // Create items & kurangi stock (existing)
    foreach ($cart->items as $item) {
        $order->items()->create([
            'laptop_id' => $item->laptop_id,
            'laptop_variant_id' => $item->laptop_variant_id,
            'product_name' => $item->laptop->name,
            'variant_name' => $item->variant?->name,
            'quantity' => $item->quantity,
            'unit_price' => $item->unit_price,
            'subtotal' => $item->subtotal,
        ]);

        $item->laptop->decrement('stock', $item->quantity);
        if ($item->variant) {
            $item->variant->decrement('stock', $item->quantity);
        }
    }

    // Hapus cart
    $cart->items()->delete();
    $cart->delete();

    // Create Xendit invoice
    try {
        $xendit = app(\App\Services\XenditService::class);
        $invoice = $xendit->createInvoice($order);

        $order->update([
            'xendit_invoice_id' => $invoice['id'],
            'xendit_invoice_url' => $invoice['invoice_url'],
            'xendit_expiry' => $invoice['expiry_date'],
        ]);

        return redirect()->away($invoice['invoice_url']);
    } catch (\Exception $e) {
        Log::error('Xendit invoice creation failed', [
            'order_id' => $order->id,
            'error' => $e->getMessage(),
        ]);

        return redirect()->route('orders.confirmation', $order)
            ->with('warning', 'Order created but payment link failed. Please contact support.');
    }
}
```

### 6. Xendit Callback Handler
```php
public function xenditCallback(Request $request, Order $order)
{
    if ($order->user_id !== auth()->id()) {
        abort(403);
    }
    
    $status = $request->query('status', '');
    
    if ($status === 'success' || $status === 'paid') {
        // Verify with Xendit API
        if ($order->xendit_invoice_id && $order->payment_status === 'unpaid') {
            try {
                $xendit = app(\App\Services\XenditService::class);
                $invoiceStatus = $xendit->getInvoiceStatus($order->xendit_invoice_id);
                
                if ($invoiceStatus['status'] === 'PAID') {
                    $order->update([
                        'payment_status' => 'paid',
                        'status' => 'processing',
                        'paid_at' => now(),
                    ]);
                    return redirect()->route('orders.confirmation', $order)
                        ->with('success', 'Payment successful!');
                }
            } catch (\Exception $e) {
                Log::error('Xendit callback verification failed', ['error' => $e->getMessage()]);
            }
        }
        return redirect()->route('orders.confirmation', $order)
            ->with('success', 'Payment completed. Please wait for confirmation.');
    }
    
    return redirect()->route('orders.confirmation', $order)
        ->with('error', 'Payment was cancelled or failed. Please try again.');
}
```

## Definisi Selesai
- [ ] Checkout menampilkan province → city → shipping cost flow (RajaOngkir)
- [ ] Shipping cost options real-time via AJAX
- [ ] Tidak ada pilihan payment method — forced Xendit
- [ ] Xendit invoice terbuat setelah submit checkout
- [ ] User redirect ke Xendit invoice page
- [ ] Callback handler menangani redirect dari Xendit
- [ ] Error handling untuk RajaOngkir dan Xendit API failure
- [ ] Total sudah termasuk ongkos kirim
