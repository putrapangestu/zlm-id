<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TechLaptop International</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .text-primary { color: #DF5E1D; }
        .bg-primary { background-color: #DF5E1D; }
        .bg-primary-light { background-color: #FF6B35; }
        .bg-dark { background: linear-gradient(135deg, #2d2926 0%, #363230 100%); }

        /* ✅ Custom animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(12deg); }
            50% { transform: translateY(-20px) rotate(12deg); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .slide-in-left {
            animation: slideInLeft 0.8s ease-out forwards;
        }

        /* ✅ Glassmorphism effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        /* ✅ Custom input focus */
        .custom-input:focus {
            border-color: #DF5E1D;
            box-shadow: 0 0 0 3px rgba(223, 94, 29, 0.1);
        }

        /* ✅ Button hover effect */
        .btn-primary:hover {
            background: linear-gradient(135deg, #FF6B35 0%, #DF5E1D 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(223, 94, 29, 0.4);
        }

        .btn-social:hover {
            background-color: #f9fafb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        /* ✅ Checkbox custom style */
        .custom-checkbox:checked {
            background-color: #DF5E1D;
            border-color: #DF5E1D;
        }

        /* ✅ Pattern background */
        .pattern-bg {
            background-image:
                radial-gradient(circle at 20% 50%, rgba(223, 94, 29, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(223, 94, 29, 0.05) 0%, transparent 50%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 pattern-bg">
    <!-- ✅ Main Container -->
    <div class="max-w-5xl w-full glass-effect rounded-3xl shadow-2xl overflow-hidden flex flex-col md:flex-row fade-in-up">

        <!-- ✅ Left Panel - Enhanced -->
        <div class="hidden md:flex md:w-1/2 bg-dark p-12 flex-col justify-between text-white relative overflow-hidden slide-in-left">
            <!-- Decorative gradient overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-transparent"></div>

            <!-- Logo & Brand -->
            <div class="z-10 mb-8">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold">TechLaptop</span>
                </div>

                <h2 class="text-4xl font-bold mb-4 leading-tight">
                    Temukan Performa<br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">
                        Tanpa Batas
                    </span>
                </h2>
                <p class="text-gray-300 text-lg leading-relaxed">
                    Masuk untuk mengakses penawaran eksklusif dan kelola pesanan laptop gaming premium Anda.
                </p>
            </div>

            <!-- ✅ Features List -->
            <div class="z-10 space-y-4 mb-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <span class="text-gray-300">Gratis Ongkir Se-Indonesia</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <span class="text-gray-300">Garansi Resmi 2 Tahun</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <span class="text-gray-300">Cicilan 0% Hingga 12 Bulan</span>
                </div>
            </div>

            <!-- ✅ Floating Laptop Icon -->
            <div class="absolute -bottom-10 -right-10 opacity-10 float-animation">
                <svg width="450" height="450" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M20 18H4V6h16v12zM22 4H2v16h20V4zM14 20h-4v2h4v-2z"/>
                </svg>
            </div>

            <!-- ✅ Stats -->
            <div class="z-10 grid grid-cols-3 gap-4 pt-8 border-t border-white/10">
                <div>
                    <div class="text-2xl font-bold text-orange-400">50K+</div>
                    <div class="text-xs text-gray-400">Pengguna Aktif</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-orange-400">4.9</div>
                    <div class="text-xs text-gray-400">Rating Toko</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-orange-400">100%</div>
                    <div class="text-xs text-gray-400">Produk Original</div>
                </div>
            </div>

            <!-- Footer -->
            <div class="z-10 text-xs text-gray-500 mt-8">
                © 2026 TechLaptop International. All rights reserved.
            </div>
        </div>

        <!-- ✅ Right Panel - Enhanced Form -->
        <div class="w-full md:w-1/2 p-8 md:p-12 bg-white">
            <!-- Mobile Logo -->
            <div class="md:hidden flex items-center justify-center gap-2 mb-8">
                <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold text-gray-900">TechLaptop</span>
            </div>

            <!-- ✅ Header -->
            <div class="mb-8 text-center md:text-left">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    Selamat Datang Kembali! 👋
                </h1>
                <p class="text-gray-500">
                    Silakan masukkan detail akun Anda untuk melanjutkan
                </p>
            </div>

            <!-- ✅ Form -->
            <form action="#" class="space-y-5" id="loginForm">
                <!-- Email Input -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <input
                            type="email"
                            placeholder="nama@perusahaan.com"
                            class="w-full pl-12 pr-4 py-3.5 rounded-xl border-2 border-gray-200 custom-input outline-none transition-all duration-200 text-gray-900 placeholder-gray-400"
                            required
                        >
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="text-sm font-semibold text-gray-700">
                            Kata Sandi
                        </label>
                        <a href="#" class="text-sm font-semibold text-primary hover:text-orange-600 transition-colors">
                            Lupa Password?
                        </a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input
                            type="password"
                            placeholder="••••••••"
                            id="passwordInput"
                            class="w-full pl-12 pr-12 py-3.5 rounded-xl border-2 border-gray-200 custom-input outline-none transition-all duration-200 text-gray-900"
                            required
                        >
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <svg id="eyeIcon" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            id="remember"
                            class="h-4 w-4 custom-checkbox border-gray-300 rounded transition-colors"
                        >
                        <label for="remember" class="ml-2 block text-sm text-gray-700 font-medium cursor-pointer">
                            Ingat perangkat ini
                        </label>
                    </div>
                </div>

                <!-- ✅ Submit Button with Loading State -->
                <button
                    type="submit"
                    class="w-full bg-primary text-white py-4 rounded-xl font-semibold btn-primary transition-all duration-300 shadow-lg shadow-orange-200 flex items-center justify-center gap-2"
                >
                    <span>Masuk Sekarang</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </form>

            <!-- ✅ Divider -->
            <div class="mt-8">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-gray-500 font-medium">Atau masuk dengan</span>
                    </div>
                </div>

                <!-- ✅ Social Login Buttons -->
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <button class="flex items-center justify-center px-4 py-3 border-2 border-gray-200 rounded-xl btn-social transition-all duration-200">
                        <img class="h-5 w-5 mr-2" src="https://www.svgrepo.com/show/355037/google.svg" alt="Google">
                        <span class="text-sm font-semibold text-gray-700">Google</span>
                    </button>
                    <button class="flex items-center justify-center px-4 py-3 border-2 border-gray-200 rounded-xl btn-social transition-all duration-200">
                        <img class="h-5 w-5 mr-2" src="https://www.svgrepo.com/show/446866/apple.svg" alt="Apple">
                        <span class="text-sm font-semibold text-gray-700">Apple</span>
                    </button>
                </div>
            </div>

            <!-- ✅ Sign Up Link -->
            <p class="mt-8 text-center text-sm text-gray-600">
                Belum punya akun?
                <a href="#" class="font-bold text-primary hover:text-orange-600 transition-colors hover:underline ml-1">
                    Daftar Gratis Sekarang
                </a>
            </p>

            <!-- ✅ Trust Badge -->
            <div class="mt-6 flex items-center justify-center gap-2 text-xs text-gray-500">
                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>Login Aman dengan Enkripsi SSL</span>
            </div>
        </div>
    </div>

    <!-- ✅ JavaScript for interactions -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }

        // Form submit with loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const btnText = btn.querySelector('span');
            const btnIcon = btn.querySelector('svg');

            // Show loading state
            btnText.textContent = 'Memproses...';
            btnIcon.classList.add('animate-spin');
            btn.disabled = true;

            // Simulate API call
            setTimeout(() => {
                btnText.textContent = 'Masuk Sekarang';
                btnIcon.classList.remove('animate-spin');
                btn.disabled = false;
                alert('Login berhasil! (Demo)');
            }, 2000);
        });
    </script>
</body>
</html>
