@extends('layouts.landing')

@section('title', 'Profile')

@section('content')
<div class="bg-gray-50 min-h-screen py-12 lg:py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
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
                <li class="text-[#363230] truncate">My Profile</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-12">
            <h1 class="text-4xl lg:text-5xl font-semibold tracking-tight text-[#363230] mb-3">My Account</h1>
            <p class="text-gray-500 max-w-2xl text-base leading-relaxed">Manage your profile and view your purchase history</p>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm mb-8">
            <div class="flex border-b border-gray-100">
                <button onclick="switchTab('profile')" id="tab-profile-btn" class="flex-1 px-6 py-4 text-center text-sm font-semibold text-gray-600 hover:text-[#363230] transition-colors border-b-2 border-transparent hover:border-gray-200 active-tab" data-tab="profile">
                    <div class="flex items-center justify-center gap-2">
                        <iconify-icon icon="solar:user-linear" class="text-lg"></iconify-icon>
                        <span>Profile</span>
                    </div>
                </button>
                <button onclick="switchTab('transactions')" id="tab-transactions-btn" class="flex-1 px-6 py-4 text-center text-sm font-semibold text-gray-600 hover:text-[#363230] transition-colors border-b-2 border-transparent hover:border-gray-200" data-tab="transactions">
                    <div class="flex items-center justify-center gap-2">
                        <iconify-icon icon="solar:receipt-linear" class="text-lg"></iconify-icon>
                        <span>Transaction History</span>
                    </div>
                </button>
            </div>

            <!-- Tab Content -->
            <div class="p-8">
                <!-- Profile Tab -->
                <div id="profile" class="tab-content active">
                    <div class="space-y-8">
                        <!-- Profile Overview -->
                        <div class="flex flex-col sm:flex-row gap-6 items-start pb-8 border-b border-gray-100">
                            <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-[#DF5E1D] to-[#d05619] flex items-center justify-center flex-shrink-0">
                                <iconify-icon icon="solar:user-bold" class="text-5xl text-white"></iconify-icon>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-2xl font-semibold text-[#363230] mb-1">John Doe</h2>
                                <p class="text-gray-500 mb-4">john.doe@example.com</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-50 border border-green-200 text-xs font-medium text-green-700">
                                        <iconify-icon icon="solar:verified-check-linear" class="text-base"></iconify-icon>
                                        Verified
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-xs font-medium text-blue-700">
                                        <iconify-icon icon="solar:star-linear" class="text-base"></iconify-icon>
                                        Premium | Member since Mar 2024
                                    </span>
                                </div>
                            </div>
                            <button onclick="toggleEditMode()" class="px-6 py-2.5 bg-[#DF5E1D] text-white rounded-lg text-sm font-semibold hover:bg-[#d05619] transition-colors">
                                Edit Profile
                            </button>
                        </div>

                        <!-- Profile Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Personal Information -->
                            <div class="space-y-5">
                                <h3 class="text-lg font-semibold text-[#363230] flex items-center gap-2">
                                    <iconify-icon icon="solar:user-circle-linear" class="text-xl"></iconify-icon>
                                    Personal Information
                                </h3>
                                <div class="space-y-4">
                                    <div class="edit-field">
                                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">First Name</label>
                                        <p class="text-sm text-[#363230] font-medium view-mode">John</p>
                                        <input type="text" value="John" placeholder="First Name" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white edit-mode hidden">
                                    </div>
                                    <div class="edit-field">
                                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Last Name</label>
                                        <p class="text-sm text-[#363230] font-medium view-mode">Doe</p>
                                        <input type="text" value="Doe" placeholder="Last Name" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white edit-mode hidden">
                                    </div>
                                    <div class="edit-field">
                                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Email Address</label>
                                        <p class="text-sm text-[#363230] font-medium view-mode">john.doe@example.com</p>
                                        <input type="email" value="john.doe@example.com" placeholder="Email" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white edit-mode hidden">
                                    </div>
                                    <div class="edit-field">
                                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Phone Number</label>
                                        <p class="text-sm text-[#363230] font-medium view-mode">+1 (555) 123-4567</p>
                                        <input type="tel" value="+1 (555) 123-4567" placeholder="Phone Number" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white edit-mode hidden">
                                    </div>
                                </div>
                            </div>

                            <!-- Address Information -->
                            <div class="space-y-5">
                                <h3 class="text-lg font-semibold text-[#363230] flex items-center gap-2">
                                    <iconify-icon icon="solar:map-point-linear" class="text-xl"></iconify-icon>
                                    Address
                                </h3>
                                <div class="space-y-4">
                                    <div class="edit-field">
                                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Street Address</label>
                                        <p class="text-sm text-[#363230] font-medium view-mode">123 Main Street, Suite 100</p>
                                        <input type="text" value="123 Main Street, Suite 100" placeholder="Street Address" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white edit-mode hidden">
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="edit-field">
                                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">City</label>
                                            <p class="text-sm text-[#363230] font-medium view-mode">New York</p>
                                            <input type="text" value="New York" placeholder="City" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white edit-mode hidden">
                                        </div>
                                        <div class="edit-field">
                                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">State/Province</label>
                                            <p class="text-sm text-[#363230] font-medium view-mode">NY</p>
                                            <input type="text" value="NY" placeholder="State" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white edit-mode hidden">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="edit-field">
                                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">ZIP Code</label>
                                            <p class="text-sm text-[#363230] font-medium view-mode">10001</p>
                                            <input type="text" value="10001" placeholder="ZIP Code" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white edit-mode hidden">
                                        </div>
                                        <div class="edit-field">
                                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Country</label>
                                            <p class="text-sm text-[#363230] font-medium view-mode">United States</p>
                                            <input type="text" value="United States" placeholder="Country" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#DF5E1D]/20 focus:border-[#DF5E1D] transition-all bg-gray-50 focus:bg-white edit-mode hidden">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Settings -->
                        <div class="pt-8 border-t border-gray-100 space-y-5">
                            <h3 class="text-lg font-semibold text-[#363230] flex items-center gap-2">
                                <iconify-icon icon="solar:settings-linear" class="text-xl"></iconify-icon>
                                Account Settings
                            </h3>
                            <div class="space-y-3">
                                <label class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 cursor-pointer hover:border-gray-300 transition-colors">
                                    <input type="checkbox" checked class="w-4 h-4 rounded accent-[#DF5E1D]">
                                    <span class="text-sm text-gray-600">Receive promotional emails</span>
                                </label>
                                <label class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 cursor-pointer hover:border-gray-300 transition-colors">
                                    <input type="checkbox" checked class="w-4 h-4 rounded accent-[#DF5E1D]">
                                    <span class="text-sm text-gray-600">Receive order updates</span>
                                </label>
                                <label class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 cursor-pointer hover:border-gray-300 transition-colors">
                                    <input type="checkbox" class="w-4 h-4 rounded accent-[#DF5E1D]">
                                    <span class="text-sm text-gray-600">Two-factor authentication</span>
                                </label>
                            </div>
                        </div>

                        <!-- Edit Mode Buttons -->
                        <div id="edit-buttons" class="hidden flex gap-3 pt-8 border-t border-gray-100">
                            <button onclick="toggleEditMode()" class="flex-1 px-6 py-3 bg-[#DF5E1D] text-white rounded-lg text-sm font-semibold hover:bg-[#d05619] transition-colors flex items-center justify-center gap-2">
                                <iconify-icon icon="solar:check-circle-linear" class="text-base"></iconify-icon>
                                Save Changes
                            </button>
                            <button onclick="toggleEditMode()" class="flex-1 px-6 py-3 bg-gray-100 text-gray-600 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Transactions Tab -->
                <div id="transactions" class="tab-content hidden">
                    <div class="space-y-4">
                        <div class="text-sm text-gray-500 mb-6">
                            Showing <span class="font-semibold text-[#363230]">12</span> transactions
                        </div>

                        <!-- Transaction List -->
                        <div class="space-y-3">
                            <!-- Transaction 1 -->
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-5 bg-gray-50 rounded-xl border border-gray-100 hover:border-gray-200 hover:bg-white transition-all cursor-pointer group">
                                <div class="flex items-center gap-4 flex-1">
                                    <div class="w-12 h-12 rounded-lg bg-green-50 border border-green-100 flex items-center justify-center flex-shrink-0">
                                        <iconify-icon icon="solar:check-circle-linear" class="text-lg text-green-600"></iconify-icon>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-[#363230] mb-1">Order #ORD-2024-001</p>
                                        <p class="text-xs text-gray-500">Premium Laptop Pro + Business UltraBook</p>
                                        <p class="text-xs text-gray-400 mt-1">April 18, 2024</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 sm:justify-end">
                                    <div class="text-right">
                                        <p class="text-base font-semibold text-[#363230]">$3,797.83</p>
                                        <span class="inline-block px-2 py-1 rounded-md bg-green-100 text-green-700 text-xs font-semibold">Completed</span>
                                    </div>
                                    <button class="w-8 h-8 rounded-lg border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-gray-100 transition-colors opacity-0 group-hover:opacity-100">
                                        <iconify-icon icon="solar:arrow-right-linear" class="text-base"></iconify-icon>
                                    </button>
                                </div>
                            </div>

                            <!-- Transaction 2 -->
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-5 bg-gray-50 rounded-xl border border-gray-100 hover:border-gray-200 hover:bg-white transition-all cursor-pointer group">
                                <div class="flex items-center gap-4 flex-1">
                                    <div class="w-12 h-12 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center flex-shrink-0">
                                        <iconify-icon icon="solar:clock-circle-linear" class="text-lg text-blue-600"></iconify-icon>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-[#363230] mb-1">Order #ORD-2024-002</p>
                                        <p class="text-xs text-gray-500">Gaming Powerhouse X1</p>
                                        <p class="text-xs text-gray-400 mt-1">April 15, 2024</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 sm:justify-end">
                                    <div class="text-right">
                                        <p class="text-base font-semibold text-[#363230]">$2,499.00</p>
                                        <span class="inline-block px-2 py-1 rounded-md bg-blue-100 text-blue-700 text-xs font-semibold">Pending</span>
                                    </div>
                                    <button class="w-8 h-8 rounded-lg border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-gray-100 transition-colors opacity-0 group-hover:opacity-100">
                                        <iconify-icon icon="solar:arrow-right-linear" class="text-base"></iconify-icon>
                                    </button>
                                </div>
                            </div>

                            <!-- Transaction 3 -->
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-5 bg-gray-50 rounded-xl border border-gray-100 hover:border-gray-200 hover:bg-white transition-all cursor-pointer group">
                                <div class="flex items-center gap-4 flex-1">
                                    <div class="w-12 h-12 rounded-lg bg-green-50 border border-green-100 flex items-center justify-center flex-shrink-0">
                                        <iconify-icon icon="solar:check-circle-linear" class="text-lg text-green-600"></iconify-icon>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-[#363230] mb-1">Order #ORD-2024-003</p>
                                        <p class="text-xs text-gray-500">Student Budget Laptop</p>
                                        <p class="text-xs text-gray-400 mt-1">April 10, 2024</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 sm:justify-end">
                                    <div class="text-right">
                                        <p class="text-base font-semibold text-[#363230]">$799.99</p>
                                        <span class="inline-block px-2 py-1 rounded-md bg-green-100 text-green-700 text-xs font-semibold">Completed</span>
                                    </div>
                                    <button class="w-8 h-8 rounded-lg border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-gray-100 transition-colors opacity-0 group-hover:opacity-100">
                                        <iconify-icon icon="solar:arrow-right-linear" class="text-base"></iconify-icon>
                                    </button>
                                </div>
                            </div>

                            <!-- Transaction 4 -->
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-5 bg-gray-50 rounded-xl border border-gray-100 hover:border-gray-200 hover:bg-white transition-all cursor-pointer group">
                                <div class="flex items-center gap-4 flex-1">
                                    <div class="w-12 h-12 rounded-lg bg-orange-50 border border-orange-100 flex items-center justify-center flex-shrink-0">
                                        <iconify-icon icon="solar:truck-linear" class="text-lg text-orange-600"></iconify-icon>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-[#363230] mb-1">Order #ORD-2024-004</p>
                                        <p class="text-xs text-gray-500">Business Ultrabook Pro Max</p>
                                        <p class="text-xs text-gray-400 mt-1">April 5, 2024</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 sm:justify-end">
                                    <div class="text-right">
                                        <p class="text-base font-semibold text-[#363230]">$1,699.00</p>
                                        <span class="inline-block px-2 py-1 rounded-md bg-orange-100 text-orange-700 text-xs font-semibold">Shipped</span>
                                    </div>
                                    <button class="w-8 h-8 rounded-lg border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-gray-100 transition-colors opacity-0 group-hover:opacity-100">
                                        <iconify-icon icon="solar:arrow-right-linear" class="text-base"></iconify-icon>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- View All Transactions Link -->
                        <div class="text-center pt-6">
                            <a href="#" class="inline-flex items-center gap-2 text-[#DF5E1D] font-semibold hover:text-[#d05619] transition-colors">
                                View All Transactions
                                <iconify-icon icon="solar:arrow-right-linear" class="text-base"></iconify-icon>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8">
            <a href="{{ route('landing.search') }}" class="p-6 bg-white rounded-2xl border border-gray-200/80 shadow-sm hover:shadow-md hover:border-gray-300 transition-all text-center group">
                <div class="w-12 h-12 rounded-lg bg-[#DF5E1D]/10 flex items-center justify-center mx-auto mb-3 group-hover:bg-[#DF5E1D]/20 transition-colors">
                    <iconify-icon icon="solar:bag-linear" class="text-[#DF5E1D] text-2xl"></iconify-icon>
                </div>
                <p class="text-sm font-semibold text-[#363230] mb-1">Continue Shopping</p>
                <p class="text-xs text-gray-500">Browse our laptop collection</p>
            </a>
            <a href="{{ route('landing.checkout') }}" class="p-6 bg-white rounded-2xl border border-gray-200/80 shadow-sm hover:shadow-md hover:border-gray-300 transition-all text-center group">
                <div class="w-12 h-12 rounded-lg bg-[#DF5E1D]/10 flex items-center justify-center mx-auto mb-3 group-hover:bg-[#DF5E1D]/20 transition-colors">
                    <iconify-icon icon="solar:card-linear" class="text-[#DF5E1D] text-2xl"></iconify-icon>
                </div>
                <p class="text-sm font-semibold text-[#363230] mb-1">Make Payment</p>
                <p class="text-xs text-gray-500">Process pending orders</p>
            </a>
            <a href="{{ route('landing.home') }}" class="p-6 bg-white rounded-2xl border border-gray-200/80 shadow-sm hover:shadow-md hover:border-gray-300 transition-all text-center group">
                <div class="w-12 h-12 rounded-lg bg-[#DF5E1D]/10 flex items-center justify-center mx-auto mb-3 group-hover:bg-[#DF5E1D]/20 transition-colors">
                    <iconify-icon icon="solar:support-linear" class="text-[#DF5E1D] text-2xl"></iconify-icon>
                </div>
                <p class="text-sm font-semibold text-[#363230] mb-1">Contact Support</p>
                <p class="text-xs text-gray-500">Need help with your order</p>
            </a>
        </div>
    </div>
</div>

<script>
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
            tab.classList.remove('active');
        });

        // Remove active class from all buttons
        document.querySelectorAll('[data-tab]').forEach(btn => {
            btn.classList.remove('!border-[#DF5E1D]', '!text-[#DF5E1D]');
        });

        // Show selected tab
        document.getElementById(tabName).classList.remove('hidden');
        document.getElementById(tabName).classList.add('active');

        // Add active class to clicked button
        document.getElementById('tab-' + tabName + '-btn').classList.add('!border-[#DF5E1D]', '!text-[#DF5E1D]');
    }

    function toggleEditMode() {
        const editButtons = document.getElementById('edit-buttons');
        const viewModes = document.querySelectorAll('.view-mode');
        const editModes = document.querySelectorAll('.edit-mode');

        editButtons.classList.toggle('hidden');

        viewModes.forEach(el => el.classList.toggle('hidden'));
        editModes.forEach(el => el.classList.toggle('hidden'));
    }

    // Initialize active tab styling
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('tab-profile-btn').classList.add('!border-[#DF5E1D]', '!text-[#DF5E1D]');
    });
</script>

<style>
    [data-tab] {
        position: relative;
        transition: all 0.3s ease;
    }

    [data-tab]::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #DF5E1D;
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
    }

    [data-tab].active::after,
    [data-tab].\!\-border-\[\#DF5E1D\]::after {
        transform: scaleX(1);
    }

    .tab-content.active {
        animation: fadeIn 0.3s ease-in;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection
