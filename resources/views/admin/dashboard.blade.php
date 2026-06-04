@extends('layouts.admin')

@section('title', 'Dashboard')
@section('heading', 'Dashboard Overview')

@section('content')
<div class="space-y-8">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 animate-slide-up relative overflow-hidden group" style="animation-delay: 0.1s;">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 group-hover:scale-110 transition-all duration-500 text-[#DF5E1D]">
                <iconify-icon icon="solar:laptop-minimalistic-linear" class="text-6xl"></iconify-icon>
            </div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="text-xs font-medium text-gray-400 uppercase tracking-widest">Products</div>
                <div class="w-8 h-8 rounded-lg bg-orange-50 border border-orange-100 flex items-center justify-center text-[#DF5E1D]">
                    <iconify-icon icon="solar:laptop-minimalistic-linear" style="stroke-width: 1.5;"></iconify-icon>
                </div>
            </div>
            <div class="text-2xl font-medium tracking-tight text-[#363230] mb-2 relative z-10">{{ $stats['total_laptops'] }}</div>
            <div class="flex items-center gap-2 relative z-10">
                <span class="text-[10px] text-gray-400">Active laptops in catalog</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 animate-slide-up relative overflow-hidden group" style="animation-delay: 0.2s;">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 group-hover:scale-110 transition-all duration-500 text-[#DF5E1D]">
                <iconify-icon icon="solar:users-group-two-rounded-linear" class="text-6xl"></iconify-icon>
            </div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="text-xs font-medium text-gray-400 uppercase tracking-widest">Users</div>
                <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500 group-hover:border-orange-100 group-hover:bg-orange-50 group-hover:text-[#DF5E1D] transition-colors">
                    <iconify-icon icon="solar:users-group-two-rounded-linear" style="stroke-width: 1.5;"></iconify-icon>
                </div>
            </div>
            <div class="text-2xl font-medium tracking-tight text-[#363230] mb-2 relative z-10">{{ $stats['total_users'] }}</div>
            <div class="flex items-center gap-2 relative z-10">
                <span class="text-[10px] text-gray-400">Registered accounts</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 animate-slide-up relative overflow-hidden group" style="animation-delay: 0.3s;">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 group-hover:scale-110 transition-all duration-500 text-[#DF5E1D]">
                <iconify-icon icon="solar:cart-large-2-linear" class="text-6xl"></iconify-icon>
            </div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="text-xs font-medium text-gray-400 uppercase tracking-widest">Orders</div>
                <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500 group-hover:border-orange-100 group-hover:bg-orange-50 group-hover:text-[#DF5E1D] transition-colors">
                    <iconify-icon icon="solar:cart-large-2-linear" style="stroke-width: 1.5;"></iconify-icon>
                </div>
            </div>
            <div class="text-2xl font-medium tracking-tight text-[#363230] mb-2 relative z-10">{{ $stats['total_orders'] }}</div>
            <div class="flex items-center gap-2 relative z-10">
                <span class="text-[10px] text-gray-400">Total orders placed</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 animate-slide-up relative overflow-hidden group" style="animation-delay: 0.4s;">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 group-hover:scale-110 transition-all duration-500 text-[#DF5E1D]">
                <iconify-icon icon="solar:dollar-linear" class="text-6xl"></iconify-icon>
            </div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="text-xs font-medium text-gray-400 uppercase tracking-widest">Revenue</div>
                <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500 group-hover:border-orange-100 group-hover:bg-orange-50 group-hover:text-[#DF5E1D] transition-colors">
                    <iconify-icon icon="solar:dollar-linear" style="stroke-width: 1.5;"></iconify-icon>
                </div>
            </div>
            <div class="text-2xl font-medium tracking-tight text-[#363230] mb-2 relative z-10">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
            <div class="flex items-center gap-2 relative z-10">
                <span class="text-[10px] text-gray-400">Total revenue</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 animate-slide-up relative overflow-hidden group" style="animation-delay: 0.5s;">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 group-hover:scale-110 transition-all duration-500 text-[#DF5E1D]">
                <iconify-icon icon="solar:clock-circle-linear" class="text-6xl"></iconify-icon>
            </div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="text-xs font-medium text-gray-400 uppercase tracking-widest">Pending Payments</div>
                <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500 group-hover:border-orange-100 group-hover:bg-orange-50 group-hover:text-[#DF5E1D] transition-colors">
                    <iconify-icon icon="solar:clock-circle-linear" style="stroke-width: 1.5;"></iconify-icon>
                </div>
            </div>
            <div class="text-2xl font-medium tracking-tight text-[#363230] mb-2 relative z-10">{{ $stats['pending_orders'] + $stats['pending_verification'] }}</div>
            <div class="flex items-center gap-2 relative z-10">
                <span class="text-[10px] text-gray-400">Awaiting payment &amp; verification</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200/60 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-5 animate-slide-up relative overflow-hidden group" style="animation-delay: 0.6s;">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 group-hover:scale-110 transition-all duration-500 text-[#DF5E1D]">
                <iconify-icon icon="solar:calendar-date-linear" class="text-6xl"></iconify-icon>
            </div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="text-xs font-medium text-gray-400 uppercase tracking-widest">Monthly Revenue</div>
                <div class="w-8 h-8 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500 group-hover:border-orange-100 group-hover:bg-orange-50 group-hover:text-[#DF5E1D] transition-colors">
                    <iconify-icon icon="solar:calendar-date-linear" style="stroke-width: 1.5;"></iconify-icon>
                </div>
            </div>
            <div class="text-2xl font-medium tracking-tight text-[#363230] mb-2 relative z-10">Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}</div>
            <div class="flex items-center gap-2 relative z-10">
                <span class="text-[10px] text-gray-400">Revenue this month</span>
            </div>
        </div>
    </div>
</div>
@endsection