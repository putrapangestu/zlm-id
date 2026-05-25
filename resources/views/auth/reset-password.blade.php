<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password — ZLM.ID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .reset-gradient {
            background: linear-gradient(135deg, #363230 0%, #1a1816 50%, #2d2825 100%);
        }

        .floating-card {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .glow-dot {
            animation: glow 3s ease-in-out infinite;
        }

        @keyframes glow {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.8; }
        }

        .input-focus:focus {
            border-color: #DF5E1D;
            box-shadow: 0 0 0 3px rgba(223, 94, 29, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 selection:bg-[#DF5E1D] selection:text-white min-h-screen">

    <div class="min-h-screen flex">
        <!-- Left Panel - Branding -->
        <div class="hidden lg:flex lg:w-1/2 reset-gradient relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute inset-0">
                <div class="absolute top-20 left-10 w-32 h-32 bg-[#DF5E1D]/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-32 right-16 w-48 h-48 bg-[#DF5E1D]/8 rounded-full blur-3xl"></div>
                <div class="absolute top-1/2 left-1/3 w-24 h-24 bg-white/5 rounded-full blur-2xl"></div>

                <!-- Grid pattern -->
                <div class="absolute inset-0 opacity-[0.03]"
                     style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 30px 30px;"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 flex flex-col justify-between p-12 w-full">
                <!-- Logo -->
                <div>
                    <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 bg-[#DF5E1D] rounded-xl flex items-center justify-center shadow-lg shadow-[#DF5E1D]/20 group-hover:scale-105 transition">
                            <img src="{{ asset('assets/logo.png') }}" alt="ZLM.ID" class="w-7 h-7 object-contain filter brightness-0 invert">
                        </div>
                        <span class="text-white text-xl font-semibold tracking-tight">ZLM.ID</span>
                    </a>
                </div>

                <!-- Center Illustration -->
                <div class="flex-1 flex flex-col items-center justify-center -mt-8">
                    <div class="floating-card mb-10">
                        <!-- Laptop Icon Illustration -->
                        <div class="relative">
                            <div class="w-48 h-48 bg-white/5 rounded-3xl border border-white/10 backdrop-blur-sm flex items-center justify-center">
                                <iconify-icon icon="solar:laptop-minimalistic-bold-duotone" class="text-[#DF5E1D]" style="font-size: 96px;"></iconify-icon>
                            </div>
                            <!-- Floating accent dots -->
                            <div class="absolute -top-3 -right-3 w-6 h-6 bg-[#DF5E1D] rounded-full glow-dot"></div>
                            <div class="absolute -bottom-2 -left-2 w-4 h-4 bg-[#DF5E1D]/60 rounded-full glow-dot" style="animation-delay: 1s;"></div>
                            <div class="absolute top-1/2 -right-6 w-3 h-3 bg-white/30 rounded-full glow-dot" style="animation-delay: 2s;"></div>
                        </div>
                    </div>

                    <h2 class="text-white text-2xl font-semibold text-center mb-3">
                        Temukan Laptop Impianmu
                    </h2>
                    <p class="text-white/50 text-center text-sm max-w-xs leading-relaxed">
                        Laptop berkualitas dengan harga terbaik. Gratis ongkir dan garansi resmi untuk setiap pembelian.
                    </p>
                </div>

                <!-- Bottom Features -->
                <div class="flex items-center gap-6 text-white/40 text-xs">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="solar:verified-check-bold" class="text-[#DF5E1D] text-base"></iconify-icon>
                        <span>Garansi Resmi</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="solar:delivery-bold" class="text-[#DF5E1D] text-base"></iconify-icon>
                        <span>Gratis Ongkir</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="solar:shield-check-bold" class="text-[#DF5E1D] text-base"></iconify-icon>
                        <span>100% Original</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Reset Password Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex items-center justify-center gap-3 mb-10">
                    <div class="w-10 h-10 bg-[#DF5E1D] rounded-xl flex items-center justify-center shadow-lg shadow-[#DF5E1D]/20">
                        <img src="{{ asset('assets/logo.png') }}" alt="ZLM.ID" class="w-7 h-7 object-contain filter brightness-0 invert">
                    </div>
                    <span class="text-[#363230] text-xl font-semibold tracking-tight">ZLM.ID</span>
                </div>

                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-2xl font-semibold text-[#363230] mb-2">Reset Password</h1>
                    <p class="text-gray-500 text-sm">Buat password baru untuk akun Anda</p>
                </div>

                <!-- Reset Password Form -->
                <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <iconify-icon icon="solar:letter-linear" class="text-gray-400 text-lg"></iconify-icon>
                            </div>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $request->email) }}"
                                required
                                autocomplete="email"
                                readonly
                                class="input-focus w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-600 placeholder-gray-400 outline-none cursor-not-allowed @error('email') border-red-400 @enderror"
                            >
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <iconify-icon icon="solar:danger-circle-linear" class="text-sm"></iconify-icon>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <iconify-icon icon="solar:lock-keyhole-linear" class="text-gray-400 text-lg"></iconify-icon>
                            </div>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Minimal 8 karakter"
                                class="input-focus w-full pl-11 pr-12 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 outline-none transition-all duration-200 @error('password') border-red-400 @enderror"
                            >
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition">
                                <iconify-icon id="eye-icon" icon="solar:eye-linear" class="text-lg"></iconify-icon>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <iconify-icon icon="solar:danger-circle-linear" class="text-sm"></iconify-icon>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <iconify-icon icon="solar:lock-keyhole-linear" class="text-gray-400 text-lg"></iconify-icon>
                            </div>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Ulangi password baru"
                                class="input-focus w-full pl-11 pr-12 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 outline-none transition-all duration-200 @error('password') border-red-400 @enderror"
                            >
                            <button type="button" onclick="toggleConfirmPassword()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition">
                                <iconify-icon id="eye-icon-confirm" icon="solar:eye-linear" class="text-lg"></iconify-icon>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full py-3 px-4 bg-[#DF5E1D] hover:bg-[#c45218] text-white font-medium rounded-xl transition-all duration-200 text-sm flex items-center justify-center gap-2 shadow-lg shadow-[#DF5E1D]/20 hover:shadow-[#DF5E1D]/30 hover:-translate-y-0.5 active:translate-y-0"
                    >
                        <iconify-icon icon="solar:refresh-circle-linear" class="text-lg"></iconify-icon>
                        Reset Password
                    </button>
                </form>

                <!-- Back to Login -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="text-[#DF5E1D] hover:text-[#c45218] font-medium transition inline-flex items-center gap-1">
                                <iconify-icon icon="solar:arrow-left-linear" class="text-sm"></iconify-icon>
                                Kembali ke Login
                            </a>
                        @endif
                    </p>
                </div>

                <!-- Back to Home -->
                <div class="mt-6 text-center">
                    <a href="{{ url('/') }}" class="text-xs text-gray-400 hover:text-gray-600 transition inline-flex items-center gap-1">
                        <iconify-icon icon="solar:arrow-left-linear" class="text-sm"></iconify-icon>
                        Kembali ke Beranda
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('icon', 'solar:eye-closed-linear');
            } else {
                input.type = 'password';
                icon.setAttribute('icon', 'solar:eye-linear');
            }
        }

        function toggleConfirmPassword() {
            const input = document.getElementById('password_confirmation');
            const icon = document.getElementById('eye-icon-confirm');
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('icon', 'solar:eye-closed-linear');
            } else {
                input.type = 'password';
                icon.setAttribute('icon', 'solar:eye-linear');
            }
        }
    </script>

</body>
</html>
