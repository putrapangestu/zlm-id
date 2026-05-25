<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ZLM.ID') }} &mdash; @yield('title', 'Authentication')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; }
        iconify-icon { stroke-width: 1.5; }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex">
        <!-- Left Panel - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-[#363230] relative overflow-hidden items-center justify-center">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-[#DF5E1D] opacity-20 blur-[120px] rounded-full pointer-events-none"></div>
            <div class="relative z-10 text-center max-w-md mx-auto px-8">
                <a href="{{ route('landing.home') }}" class="inline-flex items-center gap-3 mb-8">
                    <img src="{{ asset('assets/logo.png') }}" alt="ZLM.ID" class="h-14 w-14 object-contain brightness-0 invert">
                    <span class="text-4xl font-bold tracking-tighter text-white">ZLM.ID</span>
                </a>
                <h2 class="text-2xl font-semibold text-white mb-4">Premium Laptop Marketplace</h2>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Curated workstations for developers, creators, and enterprises. Every unit verified, tested, and ready to ship.
                </p>
                <div class="mt-10 flex justify-center gap-10">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-white">500+</p>
                        <p class="text-xs text-gray-500 mt-1">Models</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-white">15K+</p>
                        <p class="text-xs text-gray-500 mt-1">Customers</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-white">99%</p>
                        <p class="text-xs text-gray-500 mt-1">Satisfaction</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Form -->
        <div class="flex-1 flex items-center justify-center px-6 py-12 bg-white">
            <div class="w-full max-w-sm">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex items-center justify-center gap-2 mb-8">
                    <img src="{{ asset('assets/logo.png') }}" alt="ZLM.ID" class="h-8 w-8 object-contain">
                    <span class="text-xl font-bold tracking-tighter text-[#363230]">ZLM.ID</span>
                </div>

                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>