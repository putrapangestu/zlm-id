@extends('layouts.landing')

@section('title', 'Checkout')

@section('content')
<div class="bg-gray-50 min-h-screen py-12 lg:py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center gap-2 text-sm text-gray-500 font-medium">
                <li>
                    <a href="{{ route('landing.home') }}" class="hover:text-[#DF5E1D] transition-colors duration-200 flex items-center gap-1.5 rounded-md focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DF5E1D]/50">
                        <iconify-icon icon="solar:home-2-linear" class="text-base"></iconify-icon>
                        Home
                    </a>
                </li>
                <li class="flex items-center text-gray-300">
                    <iconify-icon icon="solar:alt-arrow-right-linear"></iconify-icon>
                </li>
                <li>
                    <a href="{{ route('landing.search') }}" class="hover:text-[#DF5E1D] transition-colors duration-200 rounded-md focus:outline-none focus-visible:ring-2 focus-visible:ring-[#DF5E1D]/50">Products</a>
                </li>
                <li class="flex items-center text-gray-300">
                    <iconify-icon icon="solar:alt-arrow-right-linear"></iconify-icon>
                </li>
                <li class="text-[#363230] truncate">Checkout</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-12">
            <h1 class="text-4xl lg:text-5xl font-semibold tracking-tight text-[#363230] mb-3">Checkout</h1>
            <p class="text-gray-500 max-w-2xl text-base leading-relaxed">Complete your purchase with secure payment processing</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Shipping Information -->
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-8">
                    <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-lg bg-[#DF5E1D]/10 flex items-center justify-center">
                            <iconify-icon icon="solar:box-linear" class="text-[#DF5E1D] text-lg"></iconify-icon>
                        </div>
                        <h2 class="text-2xl font-semibold tracking-tight text-[#363230]">Shipping Address</h2>
                    </div>

                    <form class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-[#363230] mb-2">First Name</label>
                                <input type="text" placeholder="John" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#363230] mb-2">Last Name</label>
                                <input type="text" placeholder="Doe" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#363230] mb-2">Email Address</label>
                            <input type="email" placeholder="john@example.com" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#363230] mb-2">Phone Number</label>
                            <input type="tel" placeholder="+1 (555) 000-0000" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#363230] mb-2">Street Address</label>
                            <input type="text" placeholder="123 Main Street" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-[#363230] mb-2">City</label>
                                <input type="text" placeholder="New York" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#363230] mb-2">State/Province</label>
                                <input type="text" placeholder="NY" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#363230] mb-2">ZIP Code</label>
                                <input type="text" placeholder="10001" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#363230] mb-2">Country</label>
                            <select class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white appearance-none cursor-pointer">
                                <option>United States</option>
                                <option>Canada</option>
                                <option>United Kingdom</option>
                                <option>Australia</option>
                                <option>Other</option>
                            </select>
                        </div>
                    </form>
                </div>

                <!-- Payment Information -->
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-8">
                    <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-lg bg-[#DF5E1D]/10 flex items-center justify-center">
                            <iconify-icon icon="solar:card-linear" class="text-[#DF5E1D] text-lg"></iconify-icon>
                        </div>
                        <h2 class="text-2xl font-semibold tracking-tight text-[#363230]">Payment Method</h2>
                    </div>

                    <div class="space-y-4">
                        <label class="flex items-center p-4 border-2 border-[#DF5E1D] rounded-lg cursor-pointer bg-[#DF5E1D]/5">
                            <input type="radio" name="payment" checked class="w-4 h-4 accent-[#DF5E1D]">
                            <span class="ml-3 flex items-center gap-2 text-sm font-medium text-[#363230]">
                                <iconify-icon icon="solar:card-linear" class="text-lg"></iconify-icon>
                                Credit / Debit Card
                            </span>
                        </label>
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-gray-300 transition-colors">
                            <input type="radio" name="payment" class="w-4 h-4 accent-[#DF5E1D]">
                            <span class="ml-3 flex items-center gap-2 text-sm font-medium text-[#363230]">
                                <iconify-icon icon="solar:wallet-linear" class="text-lg"></iconify-icon>
                                Digital Wallet
                            </span>
                        </label>
                        <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-gray-300 transition-colors">
                            <input type="radio" name="payment" class="w-4 h-4 accent-[#DF5E1D]">
                            <span class="ml-3 flex items-center gap-2 text-sm font-medium text-[#363230]">
                                <iconify-icon icon="solar:bank-linear" class="text-lg"></iconify-icon>
                                Bank Transfer
                            </span>
                        </label>
                    </div>

                    <!-- Card Details -->
                    <div class="mt-6 space-y-5 p-6 bg-gray-50 rounded-xl border border-gray-100">
                        <div>
                            <label class="block text-sm font-medium text-[#363230] mb-2">Card Number</label>
                            <input type="text" placeholder="1234 5678 9012 3456" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-white">
                        </div>
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-[#363230] mb-2">Expiry Date</label>
                                <input type="text" placeholder="MM / YY" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#363230] mb-2">CVV</label>
                                <input type="text" placeholder="123" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-white">
                            </div>
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" checked class="w-4 h-4 rounded accent-[#DF5E1D]">
                            <span class="text-sm text-gray-600">Save card for future purchases</span>
                        </label>
                    </div>
                </div>

                <!-- Review Order -->
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-8">
                    <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-lg bg-[#DF5E1D]/10 flex items-center justify-center">
                            <iconify-icon icon="solar:check-circle-linear" class="text-[#DF5E1D] text-lg"></iconify-icon>
                        </div>
                        <h2 class="text-2xl font-semibold tracking-tight text-[#363230]">Review Order</h2>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-16 h-16 rounded-lg bg-white border border-gray-200 flex items-center justify-center">
                                    <img src="https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&q=80&w=200" alt="Laptop" class="w-full h-full object-contain">
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-[#363230]">Premium Laptop Pro</p>
                                    <p class="text-xs text-gray-500">Qty: 1</p>
                                </div>
                            </div>
                            <p class="text-sm font-semibold text-[#363230]">$1,999.00</p>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-16 h-16 rounded-lg bg-white border border-gray-200 flex items-center justify-center">
                                    <img src="https://images.unsplash.com/photo-1625842268584-8f3296236761?auto=format&fit=crop&q=80&w=200" alt="Laptop" class="w-full h-full object-contain">
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-[#363230]">Business UltraBook</p>
                                    <p class="text-xs text-gray-500">Qty: 1</p>
                                </div>
                            </div>
                            <p class="text-sm font-semibold text-[#363230]">$1,499.00</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm p-8 sticky top-32 space-y-6">
                    <div class="flex items-center gap-3 pb-6 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-lg bg-[#DF5E1D]/10 flex items-center justify-center">
                            <iconify-icon icon="solar:receipt-linear" class="text-[#DF5E1D] text-lg"></iconify-icon>
                        </div>
                        <h3 class="text-xl font-semibold tracking-tight text-[#363230]">Summary</h3>
                    </div>

                    <!-- Items -->
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal (2 items)</span>
                            <span class="font-medium text-[#363230]">$3,498.00</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium text-[#363230]">$19.99</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium text-[#363230]">$279.84</span>
                        </div>
                    </div>

                    <!-- Promo Code -->
                    <div class="space-y-2 py-6 border-y border-gray-100">
                        <p class="text-xs font-semibold text-[#363230] uppercase tracking-wide">Promo Code</p>
                        <div class="flex gap-2">
                            <input type="text" placeholder="Enter code" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white">
                            <button class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">Apply</button>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-[#363230]">Total</span>
                            <span class="text-2xl font-semibold text-[#DF5E1D]">$3,797.83</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3 pt-4">
                            <button class="w-full bg-gradient-to-b from-[#DF5E1D] to-[#d05619] text-white py-3 px-4 rounded-xl text-sm font-semibold hover:from-[#d05619] hover:to-[#c45218] transition-all shadow-sm flex items-center justify-center gap-2">
                                <iconify-icon icon="solar:lock-linear" class="text-base"></iconify-icon>
                                Place Order
                            </button>
                            <a href="{{ route('landing.search') }}" class="w-full block text-center bg-gray-100 text-gray-600 py-3 px-4 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">
                                Continue Shopping
                            </a>
                        </div>

                        <!-- Trust Badges -->
                        <div class="space-y-3 pt-4 border-t border-gray-100">
                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                <iconify-icon icon="solar:shield-check-linear" class="text-[#DF5E1D] text-base"></iconify-icon>
                                <span>SSL Encrypted &amp; Secure</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                <iconify-icon icon="solar:truck-linear" class="text-[#DF5E1D] text-base"></iconify-icon>
                                <span>Free Shipping on Orders</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                <iconify-icon icon="solar:refresh-circular-linear" class="text-[#DF5E1D] text-base"></iconify-icon>
                                <span>30-Day Returns</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
