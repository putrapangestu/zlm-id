<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ZLM.ID — Aplikasi Kasir POS (Offline-First)</title>

    <link rel="manifest" href="/pos-manifest.json">
    <meta name="theme-color" content="#DF5E1D">

    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Iconify -->
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        /* Thermal Receipt Print Styles */
        @media print {
            body * {
                visibility: hidden;
            }
            #pos-receipt-modal, #pos-receipt-modal * {
                visibility: visible;
            }
            #pos-receipt-modal {
                position: absolute;
                left: 0;
                top: 0;
                width: 58mm;
                max-width: 80mm;
                padding: 0;
                margin: 0;
                background: white;
            }
            .no-print {
                display: none !important;
            }
            @page {
                size: 58mm auto;
                margin: 0;
            }
        }
    </style>
</head>
<body class="bg-[#F4F5F7] text-[#363230] h-screen overflow-hidden flex flex-col select-none">

    {{-- Top POS Navigation Bar --}}
    <header class="bg-white border-b border-gray-200/80 px-4 py-2.5 flex items-center justify-between gap-4 shrink-0 shadow-xs z-20">
        {{-- Logo & Store Info --}}
        <div class="flex items-center gap-3 shrink-0">
            <div class="w-9 h-9 rounded-xl bg-[#DF5E1D] text-white flex items-center justify-center font-extrabold text-sm shadow-sm">
                ZLM
            </div>
            <div>
                <h1 class="text-sm font-bold text-gray-900 leading-tight">POS KASIR ZLM.ID</h1>
                <p class="text-[10px] text-gray-400">Kasir: <strong class="text-gray-700">{{ auth()->user()->name ?? 'Petugas' }}</strong></p>
            </div>
        </div>

        {{-- Barcode Quick Scanner Box --}}
        <div class="flex-1 max-w-md mx-4">
            <div class="relative">
                <iconify-icon icon="solar:barcode-linear" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></iconify-icon>
                <input type="text" id="barcode-scanner-input" autofocus placeholder="Scan Barcode / SKU Unit QC..."
                    class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2 pl-10 pr-4 text-xs font-mono font-semibold text-[#363230] focus:outline-none focus:border-[#DF5E1D] focus:ring-2 focus:ring-[#DF5E1D]/10 transition-all">
            </div>
        </div>

        {{-- Right Statuses & Actions --}}
        <div class="flex items-center gap-3 shrink-0">
            {{-- Network Badge --}}
            <div id="network-status-badge" class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold">
                <span id="network-status-dot" class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span id="network-status-text">Online</span>
            </div>

            {{-- Pending Sync Badge --}}
            <button onclick="PosApp.syncQueue()" id="pos-sync-queue-badge" class="hidden px-2.5 py-1 rounded-full bg-orange-500 text-white text-[11px] font-bold animate-bounce" title="Klik untuk paksa sinkronisasi ke server">
                0 Pending Sync
            </button>

            <a href="{{ route('admin.dashboard') }}" class="p-2 text-gray-400 hover:text-[#363230] hover:bg-gray-100 rounded-xl transition-colors" title="Kembali ke Panel Admin">
                <iconify-icon icon="solar:widget-2-linear" class="text-xl"></iconify-icon>
            </a>
        </div>
    </header>

    {{-- Main POS Workspace (2 Columns) --}}
    <main class="flex-1 flex overflow-hidden">

        {{-- Left Area: Product Catalog & Category Tabs (65%) --}}
        <section class="flex-1 flex flex-col p-4 overflow-hidden border-r border-gray-200/80">
            {{-- Category Pills & Search --}}
            <div class="flex items-center justify-between gap-3 mb-3 shrink-0">
                <div id="category-pills" class="flex items-center gap-2 overflow-x-auto pb-1 max-w-2xl scrollbar-none">
                    {{-- Rendered via JS --}}
                </div>

                <div class="relative w-56 shrink-0">
                    <iconify-icon icon="solar:magnifer-linear" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></iconify-icon>
                    <input type="text" oninput="PosApp.handleSearch(this.value)" placeholder="Cari tipe laptop..."
                        class="w-full bg-white border border-gray-200 rounded-xl py-1.5 pl-8 pr-3 text-xs focus:outline-none focus:border-[#DF5E1D]">
                </div>
            </div>

            {{-- Products Grid --}}
            <div id="products-grid" class="flex-1 overflow-y-auto grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 pr-1">
                {{-- Rendered via JS --}}
            </div>
        </section>

        {{-- Right Area: Cart & Checkout (35%) --}}
        <aside class="w-[380px] xl:w-[420px] bg-white flex flex-col shrink-0 shadow-lg z-10">

            {{-- Cart Header & Member Selector --}}
            <div class="p-4 border-b border-gray-100 space-y-3 shrink-0">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-sm text-[#363230] flex items-center gap-2">
                        <iconify-icon icon="solar:cart-large-2-linear" class="text-[#DF5E1D] text-lg"></iconify-icon>
                        Keranjang Transaksi
                    </h3>
                    <button onclick="PosApp.clearCart()" class="text-xs text-gray-400 hover:text-red-600 transition">
                        Kosongkan
                    </button>
                </div>

                {{-- Member Selector Banner --}}
                <div id="selected-member-display"></div>

                <button onclick="PosApp.openMemberModal()" class="w-full py-2 px-3 bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 flex items-center justify-center gap-2 transition">
                    <iconify-icon icon="solar:card-2-linear" class="text-base text-purple-600"></iconify-icon>
                    <span>Pilih Member / Diskon Loyalitas</span>
                </button>
            </div>

            {{-- Cart Items List --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-2 relative" id="cart-scroll-area">
                <div id="cart-empty-state" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 p-6 text-center">
                    <iconify-icon icon="solar:cart-cross-linear" class="text-5xl text-gray-200 mb-2"></iconify-icon>
                    <p class="text-xs font-medium">Keranjang masih kosong.</p>
                    <p class="text-[11px] text-gray-300 mt-1">Scan barcode SKU unit atau klik produk di katalog sebelah kiri.</p>
                </div>

                <div id="cart-items-container" class="space-y-2">
                    {{-- Rendered via JS --}}
                </div>
            </div>

            {{-- Cart Summary & Checkout Footer --}}
            <div class="p-4 bg-gray-50 border-t border-gray-200/70 space-y-3 shrink-0">
                <div class="space-y-1.5 text-xs">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal</span>
                        <span id="cart-subtotal" class="font-mono font-bold text-gray-700">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Diskon Member</span>
                        <span id="cart-discount" class="font-mono font-bold text-emerald-600">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>PPN (11%)</span>
                        <span id="cart-tax" class="font-mono font-bold text-gray-700">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-sm font-extrabold text-[#363230] pt-2 border-t border-gray-200">
                        <span>TOTAL AKHIR</span>
                        <span id="cart-total" class="font-mono text-base text-[#DF5E1D]">Rp 0</span>
                    </div>
                </div>

                <button id="cart-pay-btn" onclick="PosApp.openPaymentModal()" disabled
                    class="w-full py-3.5 bg-[#DF5E1D] hover:bg-[#c45218] text-white font-extrabold text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <iconify-icon icon="solar:wallet-money-bold" class="text-lg"></iconify-icon>
                    <span>BAYAR SEKARANG</span>
                </button>
            </div>
        </aside>

    </main>

    {{-- MODAL: Variant Selector --}}
    <div id="pos-variant-modal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div id="pos-variant-modal-content" class="bg-white rounded-3xl max-w-md w-full shadow-2xl overflow-hidden animate-scale"></div>
    </div>

    {{-- MODAL: Member Selector --}}
    <div id="pos-member-modal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl overflow-hidden p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <h3 class="font-bold text-sm text-[#363230] flex items-center gap-2">
                    <iconify-icon icon="solar:users-group-rounded-linear" class="text-purple-600 text-lg"></iconify-icon>
                    Pilih Member Pelanggan
                </h3>
                <button onclick="document.getElementById('pos-member-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-700">
                    <iconify-icon icon="solar:close-circle-linear" class="text-xl"></iconify-icon>
                </button>
            </div>

            <div id="pos-member-list" class="max-h-72 overflow-y-auto space-y-2 pr-1">
                {{-- Rendered via JS --}}
            </div>
        </div>
    </div>

    {{-- MODAL: Payment Handling (Cash / QRIS / Transfer) --}}
    <div id="pos-payment-modal" class="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-lg w-full shadow-2xl overflow-hidden p-6 space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                <div>
                    <h3 class="font-bold text-base text-[#363230]">Pembayaran Kasir</h3>
                    <p class="text-xs text-gray-500">Pilih metode bayar & input nominal uang diterima</p>
                </div>
                <button onclick="document.getElementById('pos-payment-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-700">
                    <iconify-icon icon="solar:close-circle-linear" class="text-xl"></iconify-icon>
                </button>
            </div>

            {{-- Total Display --}}
            <div class="p-4 bg-orange-50/70 border border-orange-200/80 rounded-2xl text-center">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Tagihan Belanja</span>
                <span id="payment-modal-total" class="text-3xl font-extrabold font-mono text-[#DF5E1D] mt-1 block">Rp 0</span>
            </div>

            {{-- Cash Inputs --}}
            <div class="space-y-3">
                <label class="block text-xs font-bold text-[#363230] uppercase">Uang Tunai Diterima (Cash Tendered)</label>
                <input type="number" id="cash-tendered-input" oninput="PosApp.calculateChange()" step="1000"
                    class="w-full bg-gray-50 border border-gray-200 rounded-2xl p-3 text-xl font-bold font-mono text-[#363230] focus:outline-none focus:border-[#DF5E1D]">

                {{-- Quick Cash Buttons --}}
                <div class="grid grid-cols-4 gap-2">
                    <button type="button" onclick="PosApp.setQuickCash(PosApp.currentTotals.total)" class="py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-bold text-gray-700">Uang Pas</button>
                    <button type="button" onclick="PosApp.setQuickCash(5000000)" class="py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-bold text-gray-700">5 Jt</button>
                    <button type="button" onclick="PosApp.setQuickCash(10000000)" class="py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-bold text-gray-700">10 Jt</button>
                    <button type="button" onclick="PosApp.setQuickCash(15000000)" class="py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-bold text-gray-700">15 Jt</button>
                </div>
            </div>

            {{-- Change Display --}}
            <div class="p-3.5 bg-gray-50 rounded-2xl border border-gray-200 flex items-center justify-between">
                <span class="text-xs font-bold text-gray-500 uppercase">Kembalian:</span>
                <span id="payment-change-display" class="text-xl font-bold font-mono text-emerald-600">Rp 0</span>
            </div>

            {{-- Action Buttons --}}
            <div class="grid grid-cols-3 gap-3 pt-2">
                <button type="button" onclick="PosApp.processTransaction('qris')" class="py-3 px-3 rounded-xl border border-gray-200 hover:border-blue-500 hover:bg-blue-50/50 text-xs font-bold text-gray-700 flex flex-col items-center gap-1 transition">
                    <iconify-icon icon="solar:qr-code-linear" class="text-xl text-blue-600"></iconify-icon>
                    <span>QRIS Statis/Dinamis</span>
                </button>
                <button type="button" onclick="PosApp.processTransaction('transfer')" class="py-3 px-3 rounded-xl border border-gray-200 hover:border-purple-500 hover:bg-purple-50/50 text-xs font-bold text-gray-700 flex flex-col items-center gap-1 transition">
                    <iconify-icon icon="solar:card-transfer-linear" class="text-xl text-purple-600"></iconify-icon>
                    <span>Transfer Bank / EDC</span>
                </button>
                <button type="button" id="payment-submit-btn" onclick="PosApp.processTransaction('cash')" class="py-3 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex flex-col items-center gap-1 transition shadow-md">
                    <iconify-icon icon="solar:wallet-money-bold" class="text-xl"></iconify-icon>
                    <span>Bayar Tunai</span>
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL: Thermal Receipt (58mm / 80mm Print) --}}
    <div id="pos-receipt-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
        <div class="bg-white rounded-3xl max-w-sm w-full shadow-2xl p-6 space-y-4">
            <div class="flex items-center justify-between no-print pb-2 border-b border-gray-100">
                <span class="text-xs font-bold text-emerald-600 flex items-center gap-1.5">
                    <iconify-icon icon="solar:check-circle-bold"></iconify-icon>
                    Transaksi Sukses!
                </span>
                <button onclick="document.getElementById('pos-receipt-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-700">
                    <iconify-icon icon="solar:close-circle-linear" class="text-xl"></iconify-icon>
                </button>
            </div>

            {{-- Receipt Paper Preview --}}
            <div id="pos-receipt-content" class="bg-gray-50 p-4 rounded-2xl border border-dashed border-gray-300">
                {{-- Rendered via JS --}}
            </div>

            {{-- Actions --}}
            <div class="grid grid-cols-2 gap-3 no-print pt-2">
                <button onclick="PosApp.printThermalReceipt()" class="py-3 px-4 bg-[#363230] text-white hover:bg-black rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
                    <iconify-icon icon="solar:printer-linear" class="text-base"></iconify-icon>
                    <span>Cetak Struk</span>
                </button>
                <button onclick="document.getElementById('pos-receipt-modal').classList.add('hidden')" class="py-3 px-4 bg-[#DF5E1D] text-white hover:bg-[#c45218] rounded-xl text-xs font-bold transition">
                    Transaksi Baru
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="/js/pos-db.js"></script>
    <script src="/js/pos-app.js"></script>
</body>
</html>
