<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar — ZLM.ID</title>
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

        .register-gradient {
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
        <div class="hidden lg:flex lg:w-1/2 register-gradient relative overflow-hidden">
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

        <!-- Right Panel - Register Form -->
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
                    <h1 class="text-2xl font-semibold text-[#363230] mb-2">Buat Akun Baru</h1>
                    <p class="text-gray-500 text-sm">Daftar untuk mulai berbelanja laptop impianmu</p>
                </div>

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <iconify-icon icon="solar:user-linear" class="text-gray-400 text-lg"></iconify-icon>
                            </div>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autocomplete="name"
                                autofocus
                                placeholder="Nama lengkap Anda"
                                class="input-focus w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 outline-none transition-all duration-200 @error('name') border-red-400 @enderror"
                            >
                        </div>
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <iconify-icon icon="solar:danger-circle-linear" class="text-sm"></iconify-icon>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

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
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                placeholder="nama@email.com"
                                class="input-focus w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 outline-none transition-all duration-200 @error('email') border-red-400 @enderror"
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
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
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
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
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
                                placeholder="Ulangi password Anda"
                                class="input-focus w-full pl-11 pr-12 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 outline-none transition-all duration-200 @error('password') border-red-400 @enderror"
                            >
                            <button type="button" onclick="toggleConfirmPassword()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition">
                                <iconify-icon id="eye-icon-confirm" icon="solar:eye-linear" class="text-lg"></iconify-icon>
                            </button>
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="flex items-start gap-2">
                        <input
                            type="checkbox"
                            name="terms"
                            id="terms"
                            required
                            class="mt-0.5 w-4 h-4 rounded border-gray-300 text-[#DF5E1D] focus:ring-[#DF5E1D] focus:ring-offset-0 transition"
                        >
                        <label for="terms" class="text-sm text-gray-500">
                            Saya setuju dengan
                            <a href="#" class="text-[#DF5E1D] hover:text-[#c45218] font-medium transition">Syarat & Ketentuan</a>
                            dan
                            <a href="#" class="text-[#DF5E1D] hover:text-[#c45218] font-medium transition">Kebijakan Privasi</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full py-3 px-4 bg-[#DF5E1D] hover:bg-[#c45218] text-white font-medium rounded-xl transition-all duration-200 text-sm flex items-center justify-center gap-2 shadow-lg shadow-[#DF5E1D]/20 hover:shadow-[#DF5E1D]/30 hover:-translate-y-0.5 active:translate-y-0"
                    >
                        <iconify-icon icon="solar:user-plus-rounded-linear" class="text-lg"></iconify-icon>
                        Daftar
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="bg-gray-50 px-3 text-gray-400">atau</span>
                    </div>
                </div>

                <!-- Google Login -->
                <div>
                    <a href="{{ route('auth.google') }}" 
                       class="w-full flex items-center justify-center gap-3 px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 shadow-sm">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        <span>Lanjutkan dengan Google</span>
                    </a>
                </div>

                <!-- Divider -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        Sudah punya akun?
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="text-[#DF5E1D] hover:text-[#c45218] font-medium transition">
                                Masuk di sini
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
