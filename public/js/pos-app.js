/**
 * ZLM.ID Offline-First POS Kasir Application
 */
const PosApp = {
    isOnline: navigator.onLine,
    isShowcase: window.POS_SHOWCASE_MODE === true,
    products: [],
    qcUnits: [],
    categories: [],
    members: [],
    settings: {},
    selectedCategory: 'all',
    searchQuery: '',
    cart: [],
    selectedMember: null,
    discountRate: 0,
    taxRate: 11,
    syncing: false,
    currentMobileTab: 'catalog',

    async init() {
        console.log('[PosApp] Initializing POS...');
        this.setupServiceWorker();
        this.setupNetworkListeners();
        if (!this.isShowcase) this.setupBarcodeScanner();

        // Mobile / Tablet responsiveness listener
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                document.getElementById('pos-catalog-section')?.classList.remove('hidden');
                document.getElementById('pos-cart-aside')?.classList.remove('hidden');
                document.getElementById('pos-cart-aside')?.classList.add('flex');
            } else {
                this.switchMobileTab(this.currentMobileTab);
            }
        });

        // Load cached data from IndexedDB first for instant startup
        await this.loadFromIndexedDB();
        this.renderCategories();
        this.renderProducts();
        this.renderCart();
        this.updateSyncBadge();

        // If online, fetch fresh data from server and sync pending queue
        if (this.isOnline) {
            await this.fetchBootstrap();
            if (!this.isShowcase) await this.syncQueue();
        }
    },

    setupServiceWorker() {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/pos-sw.js')
                .then(reg => console.log('[Service Worker] Registered:', reg.scope))
                .catch(err => console.warn('[Service Worker] Registration failed:', err));
        }
    },

    setupNetworkListeners() {
        window.addEventListener('online', async () => {
            this.isOnline = true;
            this.updateConnectivityUI();
            this.showToast(
                this.isShowcase ? 'Online kembali. Memperbarui katalog produk...' : 'Koneksi internet terhubung. Menyinkronkan data kasir...',
                'info'
            );
            await this.fetchBootstrap();
            if (!this.isShowcase) await this.syncQueue();
        });

        window.addEventListener('offline', () => {
            this.isOnline = false;
            this.updateConnectivityUI();
            this.showToast(
                this.isShowcase
                    ? 'Mode offline aktif. Menampilkan katalog yang terakhir disinkronkan.'
                    : 'Mode Offline Aktif. Transaksi disimpan di perangkat & disinkronkan otomatis saat online.',
                'warning'
            );
        });

        this.updateConnectivityUI();
    },

    updateConnectivityUI() {
        const badge = document.getElementById('network-status-badge');
        const text = document.getElementById('network-status-text');
        const dot = document.getElementById('network-status-dot');

        if (!badge) return;

        if (this.isOnline) {
            badge.className = 'flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-semibold';
            text.innerText = this.isShowcase ? 'Online (Katalog)' : 'Online (Sinkron Aktif)';
            dot.className = 'w-2 h-2 rounded-full bg-emerald-500 animate-pulse';
        } else {
            badge.className = 'flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-semibold';
            text.innerText = 'Offline (Lokal Disimpan)';
            dot.className = 'w-2 h-2 rounded-full bg-amber-500';
        }
    },

    async loadFromIndexedDB() {
        try {
            this.products = await PosDB.getAll(this.isShowcase ? 'showcase_products' : 'products');
            this.categories = await PosDB.getAll(this.isShowcase ? 'showcase_categories' : 'categories');
            if (!this.isShowcase) {
                this.qcUnits = await PosDB.getAll('qc_units');
                this.members = await PosDB.getAll('members');
            }
            console.log('[PosApp] Loaded from IndexedDB:', {
                products: this.products.length,
                members: this.members.length
            });
        } catch (e) {
            console.error('[PosApp] Failed to read IndexedDB:', e);
        }
    },

    async fetchBootstrap() {
        try {
            const bootstrapUrl = this.isShowcase ? '/pos/showcase/bootstrap' : '/pos/bootstrap';
            const res = await fetch(bootstrapUrl);
            if (!res.ok) throw new Error('Bootstrap HTTP Error');
            const data = await res.json();

            if (data.status === 'success') {
                this.products = data.data.products;
                this.qcUnits = data.data.qc_units;
                this.categories = data.data.categories;
                this.members = data.data.members;
                this.settings = data.data.settings;
                this.taxRate = data.data.settings.tax_rate ?? 11;

                // Cache in IndexedDB
                await PosDB.setAll(this.isShowcase ? 'showcase_products' : 'products', this.products);
                await PosDB.setAll(this.isShowcase ? 'showcase_categories' : 'categories', this.categories);
                if (!this.isShowcase) {
                    await PosDB.setAll('qc_units', this.qcUnits);
                    await PosDB.setAll('members', this.members);
                }

                this.renderCategories();
                this.renderProducts();
                console.log('[PosApp] Data cache updated from server');
            }
        } catch (err) {
            console.warn('[PosApp] Server unreachable, running in pure offline mode:', err);
        }
    },

    setupBarcodeScanner() {
        const inputs = [
            document.getElementById('barcode-scanner-input'),
            document.getElementById('barcode-scanner-input-mobile')
        ].filter(Boolean);

        inputs.forEach(input => {
            input.addEventListener('keydown', async (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const code = input.value.trim();
                    if (!code) return;

                    input.value = '';
                    await this.handleBarcodeScanned(code);
                }
            });
        });
    },

    async handleBarcodeScanned(code) {
        if (this.isShowcase) return;

        // 1. Check in QC Units by exact SKU or Serial
        const qcUnit = await PosDB.findBySku(code);
        if (qcUnit) {
            const product = this.products.find(p => p.id === qcUnit.laptop_id);
            if (product) {
                const variant = qcUnit.variant_id ? product.variants.find(v => v.id === qcUnit.variant_id) : null;
                this.addToCart(product, variant, qcUnit.id);
                this.showToast(`Unit SKU: ${code} berhasil ditambahkan ke keranjang!`, 'success');
                return;
            }
        }

        // 2. Fallback search in products by name/id
        const product = this.products.find(p => p.id === code || p.name.toLowerCase().includes(code.toLowerCase()));
        if (product) {
            this.addToCart(product, null, null);
            this.showToast(`Produk ${product.name} ditambahkan!`, 'success');
            return;
        }

        this.showToast(`Barcode / SKU "${code}" tidak ditemukan dalam stok QC aktif.`, 'error');
    },

    renderCategories() {
        const container = document.getElementById('category-pills');
        if (!container) return;

        let html = `
            <button onclick="PosApp.selectCategory('all')" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all whitespace-nowrap ${this.selectedCategory === 'all' ? 'bg-[#DF5E1D] text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'}">
                Semua Produk (${this.products.length})
            </button>
        `;

        this.categories.forEach(cat => {
            const count = this.products.filter(p => p.category_ids && p.category_ids.includes(cat.id)).length;
            html += `
                <button onclick="PosApp.selectCategory('${cat.id}')" class="px-4 py-2 rounded-xl text-xs font-semibold transition-all whitespace-nowrap ${this.selectedCategory === cat.id ? 'bg-[#DF5E1D] text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'}">
                    ${this.escapeHtml(cat.name)} (${count})
                </button>
            `;
        });

        container.innerHTML = html;
    },

    selectCategory(catId) {
        this.selectedCategory = catId;
        this.renderCategories();
        this.renderProducts();
    },

    handleSearch(query) {
        this.searchQuery = query.toLowerCase();
        this.renderProducts();
    },

    renderProducts() {
        const grid = document.getElementById('products-grid');
        if (!grid) return;

        let filtered = this.products;

        if (this.selectedCategory !== 'all') {
            filtered = filtered.filter(p => p.category_ids && p.category_ids.includes(this.selectedCategory));
        }

        if (this.searchQuery) {
            filtered = filtered.filter(p =>
                p.name.toLowerCase().includes(this.searchQuery) ||
                p.brand.toLowerCase().includes(this.searchQuery) ||
                (p.processor && p.processor.toLowerCase().includes(this.searchQuery))
            );
        }

        if (filtered.length === 0) {
            grid.innerHTML = `
                <div class="col-span-full py-16 text-center text-gray-400">
                    <iconify-icon icon="solar:box-minimalistic-linear" class="text-4xl"></iconify-icon>
                    <p class="text-xs mt-2">${this.isShowcase && !this.isOnline ? 'Katalog belum tersimpan di perangkat ini. Hubungkan internet satu kali untuk mengunduh katalog.' : 'Tidak ada produk ditemukan dengan kriteria ini.'}</p>
                </div>
            `;
            return;
        }

        grid.innerHTML = filtered.map(p => this.renderProductCard(p)).join('');
    },

    renderProductCard(p) {
        const name = this.escapeHtml(p.name);
        const brand = this.escapeHtml(p.brand);
        const image = this.escapeHtml(p.image ?? '');
        const processor = this.escapeHtml(p.processor ?? '');
        const ram = this.escapeHtml(p.ram ?? '');
        const price = Number(p.final_price || 0).toLocaleString('id-ID');

        if (this.isShowcase) {
            return `
            <button type="button" class="text-left bg-white rounded-2xl border border-gray-200/70 p-3.5 shadow-sm hover:border-[#DF5E1D]/50 hover:shadow-md transition-all flex flex-col justify-between group"
                    onclick="PosApp.showShowcaseDetails('${p.id}')">
                <div class="w-full">
                    <div class="aspect-[4/3] rounded-xl bg-gray-50 border border-gray-100 overflow-hidden mb-3 flex items-center justify-center p-2 relative">
                        ${image ? `<img src="${image}" alt="${name}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition">` : `<iconify-icon icon="solar:laptop-minimalistic-linear" class="text-gray-300 text-3xl"></iconify-icon>`}
                        ${p.has_discount ? `<span class="absolute top-2 left-2 px-2 py-0.5 bg-rose-500 text-white rounded-md text-[10px] font-bold">PROMO</span>` : ''}
                    </div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase">${brand}</span>
                    <h4 class="text-sm font-bold text-[#363230] line-clamp-2 mt-0.5 leading-snug">${name}</h4>
                    <p class="text-xs text-gray-500 mt-1">${processor}${processor && ram ? ' &bull; ' : ''}${ram}</p>
                </div>
                <div class="mt-3 pt-2.5 border-t border-gray-100 w-full flex items-center justify-between gap-2">
                    <span class="text-base font-extrabold text-[#DF5E1D] font-mono">Rp ${price}</span>
                    <span class="text-[11px] font-bold text-gray-600">Spesifikasi <iconify-icon icon="solar:arrow-right-linear"></iconify-icon></span>
                </div>
            </button>
            `;
        }

        return `
            <div class="bg-white rounded-2xl border border-gray-200/70 p-3.5 shadow-sm hover:border-[#DF5E1D]/50 hover:shadow-md transition-all flex flex-col justify-between group cursor-pointer" onclick="PosApp.handleProductClick('${p.id}')">
                <div>
                    <div class="aspect-[4/3] rounded-xl bg-gray-50 border border-gray-100 overflow-hidden mb-3 flex items-center justify-center p-2 relative">
                        ${image ? `<img src="${image}" alt="${name}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition">` : `<iconify-icon icon="solar:laptop-minimalistic-linear" class="text-gray-300 text-3xl"></iconify-icon>`}
                        ${p.has_discount ? `<span class="absolute top-2 left-2 px-2 py-0.5 bg-rose-500 text-white rounded-md text-[10px] font-bold">PROMO</span>` : ''}
                        <span class="absolute bottom-2 right-2 px-2 py-0.5 bg-gray-900/80 backdrop-blur-sm text-white rounded-md text-[10px] font-semibold">Stok: ${p.stock}</span>
                    </div>

                    <span class="text-[10px] font-bold text-gray-400 uppercase">${brand}</span>
                    <h4 class="text-xs font-bold text-[#363230] line-clamp-2 mt-0.5 leading-snug">${name}</h4>
                    <p class="text-[11px] text-gray-500 mt-1">${processor} &bull; ${ram}</p>
                </div>

                <div class="mt-3 pt-2.5 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        ${p.has_discount ? `<span class="text-[10px] text-gray-400 line-through block">Rp ${Number(p.price || 0).toLocaleString('id-ID')}</span>` : ''}
                        <span class="text-sm font-extrabold text-[#DF5E1D] font-mono">Rp ${price}</span>
                    </div>
                    <button class="w-8 h-8 rounded-xl bg-orange-50 text-[#DF5E1D] flex items-center justify-center hover:bg-[#DF5E1D] hover:text-white transition">
                        <iconify-icon icon="solar:plus-linear" class="text-lg"></iconify-icon>
                    </button>
                </div>
            </div>
        `;
    },

    showShowcaseDetails(productId) {
        if (!this.isShowcase) return;

        const product = this.products.find(item => item.id === productId);
        const modal = document.getElementById('pos-showcase-modal');
        const content = document.getElementById('pos-showcase-modal-content');
        if (!product || !modal || !content) return;

        const escape = value => this.escapeHtml(value ?? '');
        const image = product.image
            ? `<img src="${escape(product.image)}" alt="${escape(product.name)}" class="w-full h-full object-contain">`
            : `<iconify-icon icon="solar:laptop-minimalistic-linear" class="text-gray-300 text-6xl"></iconify-icon>`;
        const specs = [
            ['Prosesor', product.processor],
            ['Memori', product.ram],
            ['Penyimpanan', product.storage],
            ['Grafis', product.graphics],
            ['Layar', product.display],
            ['Baterai', product.battery_life],
            ['Konektivitas', product.connectivity],
            ['Port', product.ports],
            ['Kamera', product.camera],
            ['Audio', product.audio],
            ['Warna', product.color],
            ['Garansi', product.warranty],
        ].filter(([, value]) => value);

        content.innerHTML = `
            <div class="p-5 sm:p-7">
                <div class="flex justify-end">
                    <button type="button" onclick="document.getElementById('pos-showcase-modal').classList.add('hidden')" class="rounded-full p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-700" aria-label="Tutup detail produk">
                        <iconify-icon icon="solar:close-circle-linear" class="text-2xl"></iconify-icon>
                    </button>
                </div>
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="aspect-square rounded-2xl bg-gray-50 border border-gray-100 p-5 flex items-center justify-center">${image}</div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-[#DF5E1D]">${escape(product.brand)}</p>
                        <h2 class="mt-1 text-2xl font-extrabold text-[#363230]">${escape(product.name)}</h2>
                        <p class="mt-3 text-xl font-extrabold text-[#DF5E1D] font-mono">Rp ${Number(product.final_price || 0).toLocaleString('id-ID')}</p>
                        ${product.description ? `<p class="mt-4 text-sm leading-relaxed text-gray-600">${escape(product.description)}</p>` : ''}
                        <div class="mt-5 grid grid-cols-1 sm:grid-cols-2 gap-2">
                            ${specs.map(([label, value]) => `
                                <div class="rounded-xl bg-gray-50 px-3 py-2">
                                    <p class="text-[10px] font-semibold uppercase text-gray-400">${label}</p>
                                    <p class="mt-0.5 text-xs font-semibold text-gray-700">${escape(value)}</p>
                                </div>
                            `).join('')}
                        </div>
                        ${product.variants?.length ? `
                            <div class="mt-5">
                                <h3 class="text-xs font-bold uppercase tracking-wide text-gray-500 mb-2">Varian</h3>
                                <div class="space-y-2">
                                    ${product.variants.map(variant => `
                                        <div class="flex items-center justify-between rounded-xl border border-gray-100 px-3 py-2 text-xs">
                                            <span class="font-semibold text-gray-700">${escape(variant.name)} — ${escape(variant.ram)} / ${escape(variant.storage)}</span>
                                            <span class="font-bold text-[#DF5E1D] whitespace-nowrap">Rp ${Number(variant.price || 0).toLocaleString('id-ID')}</span>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        ` : ''}
                    </div>
                </div>
                ${product.kelebihan || product.kekurangan ? `
                    <div class="mt-6 grid gap-3 sm:grid-cols-2">
                        ${product.kelebihan ? `<div class="rounded-xl bg-emerald-50 p-4"><h3 class="text-xs font-bold text-emerald-800">Keunggulan</h3><p class="mt-1 text-sm text-emerald-900">${escape(product.kelebihan)}</p></div>` : ''}
                        ${product.kekurangan ? `<div class="rounded-xl bg-amber-50 p-4"><h3 class="text-xs font-bold text-amber-800">Catatan</h3><p class="mt-1 text-sm text-amber-900">${escape(product.kekurangan)}</p></div>` : ''}
                    </div>
                ` : ''}
            </div>
        `;

        modal.classList.remove('hidden');
    },

    handleProductClick(productId) {
        const product = this.products.find(p => p.id === productId);
        if (!product) return;

        if (this.isShowcase) {
            this.showShowcaseDetails(productId);
            return;
        }

        this.addToCart(product, null, null);
    },

    escapeHtml(value) {
        return String(value).replace(/[&<>"']/g, character => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
        })[character]);
    },

    showVariantModal(product) {
        const modal = document.getElementById('pos-variant-modal');
        const content = document.getElementById('pos-variant-modal-content');
        if (!modal || !content) return;

        content.innerHTML = `
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div>
                        <h3 class="font-bold text-sm text-[#363230]">${product.name}</h3>
                        <p class="text-xs text-gray-500">Pilih varian laptop yang dibeli pelanggan</p>
                    </div>
                    <button onclick="document.getElementById('pos-variant-modal').classList.add('hidden')" class="p-1 text-gray-400 hover:text-gray-700">
                        <iconify-icon icon="solar:close-circle-linear" class="text-xl"></iconify-icon>
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-2.5">
                    ${product.variants.map(v => `
                        <button onclick="PosApp.selectVariantAndAdd('${product.id}', '${v.id}')" class="p-3 rounded-xl border border-gray-200 hover:border-[#DF5E1D] hover:bg-orange-50/50 text-left transition flex items-center justify-between">
                            <div>
                                <span class="font-bold text-xs text-[#363230] block">${v.name}</span>
                                <span class="text-[11px] text-gray-500">${v.ram} &bull; ${v.storage}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-mono font-bold text-xs text-[#DF5E1D]">Rp ${v.price.toLocaleString('id-ID')}</span>
                                <span class="text-[10px] text-gray-400 block">Stok: ${v.stock}</span>
                            </div>
                        </button>
                    `).join('')}
                </div>
            </div>
        `;

        modal.classList.remove('hidden');
    },

    selectVariantAndAdd(productId, variantId) {
        const product = this.products.find(p => p.id === productId);
        const variant = product?.variants.find(v => v.id === variantId);
        if (product && variant) {
            this.addToCart(product, variant, null);
        }
        document.getElementById('pos-variant-modal').classList.add('hidden');
    },

    addToCart(product, variant = null, productItemId = null) {
        if (this.isShowcase) return;

        const cartKey = `${product.id}_${variant ? variant.id : 'std'}`;
        const existing = this.cart.find(c => c.cartKey === cartKey);

        const unitPrice = variant ? variant.price : product.final_price;

        if (existing) {
            existing.quantity += 1;
        } else {
            this.cart.push({
                cartKey,
                laptop_id: product.id,
                variant_id: variant ? variant.id : null,
                product_item_id: productItemId,
                name: product.name,
                variant_name: variant ? variant.name : 'Standard',
                unit_price: unitPrice,
                quantity: 1,
            });
        }

        if (window.innerWidth < 1024) {
            this.showToast(`${product.name} masuk keranjang`, 'info');
        }

        this.renderCart();
    },

    updateCartQty(cartKey, delta) {
        const item = this.cart.find(c => c.cartKey === cartKey);
        if (!item) return;

        item.quantity += delta;
        if (item.quantity <= 0) {
            this.cart = this.cart.filter(c => c.cartKey !== cartKey);
        }

        this.renderCart();
    },

    removeFromCart(cartKey) {
        this.cart = this.cart.filter(c => c.cartKey !== cartKey);
        this.renderCart();
    },

    clearCart() {
        if (this.cart.length === 0) return;
        if (confirm('Kosongkan keranjang transaksi?')) {
            this.cart = [];
            this.selectedMember = null;
            this.renderCart();
        }
    },

    switchMobileTab(tab) {
        this.currentMobileTab = tab;
        const catalogSec = document.getElementById('pos-catalog-section');
        const cartAside = document.getElementById('pos-cart-aside');
        const tabBtnCatalog = document.getElementById('tab-btn-catalog');
        const tabBtnCart = document.getElementById('tab-btn-cart');
        const mobileCartBar = document.getElementById('pos-mobile-cart-bar');

        if (!catalogSec || !cartAside) return;

        if (tab === 'catalog') {
            catalogSec.classList.remove('hidden');
            cartAside.classList.add('hidden');
            cartAside.classList.remove('flex');

            if (tabBtnCatalog) {
                tabBtnCatalog.className = 'flex-1 py-2 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 bg-[#DF5E1D] text-white shadow-xs';
            }
            if (tabBtnCart) {
                tabBtnCart.className = 'flex-1 py-2 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 bg-gray-100 text-gray-600 hover:bg-gray-200 relative';
            }

            if (mobileCartBar) {
                if (this.cart.length > 0) {
                    mobileCartBar.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
                } else {
                    mobileCartBar.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
                }
            }
        } else {
            catalogSec.classList.add('hidden');
            cartAside.classList.remove('hidden');
            cartAside.classList.add('flex');

            if (tabBtnCatalog) {
                tabBtnCatalog.className = 'flex-1 py-2 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 bg-gray-100 text-gray-600 hover:bg-gray-200';
            }
            if (tabBtnCart) {
                tabBtnCart.className = 'flex-1 py-2 px-3 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 bg-[#DF5E1D] text-white shadow-xs relative';
            }

            if (mobileCartBar) {
                mobileCartBar.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
            }
        }
    },

    renderCart() {
        const container = document.getElementById('cart-items-container');
        const emptyState = document.getElementById('cart-empty-state');
        const subtotalEl = document.getElementById('cart-subtotal');
        const discountEl = document.getElementById('cart-discount');
        const taxEl = document.getElementById('cart-tax');
        const totalEl = document.getElementById('cart-total');
        const payBtn = document.getElementById('cart-pay-btn');

        if (!container) return;

        const mobileBadge = document.getElementById('mobile-cart-badge');
        const barCount = document.getElementById('bar-cart-count');
        const barTotal = document.getElementById('bar-cart-total');
        const mobileCartBar = document.getElementById('pos-mobile-cart-bar');

        if (this.cart.length === 0) {
            container.innerHTML = '';
            emptyState?.classList.remove('hidden');
            if (subtotalEl) subtotalEl.innerText = 'Rp 0';
            if (discountEl) discountEl.innerText = 'Rp 0';
            if (taxEl) taxEl.innerText = 'Rp 0';
            if (totalEl) totalEl.innerText = 'Rp 0';
            if (payBtn) payBtn.disabled = true;

            if (mobileBadge) mobileBadge.classList.add('hidden');
            if (barCount) barCount.innerText = '0';
            if (barTotal) barTotal.innerText = 'Rp 0';
            if (mobileCartBar) mobileCartBar.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');

            this.currentTotals = { subtotal: 0, discount: 0, member_discount_amount: 0, tax: 0, total: 0 };
            return;
        }

        emptyState?.classList.add('hidden');
        if (payBtn) payBtn.disabled = false;

        let subtotal = 0;
        container.innerHTML = this.cart.map(item => {
            const itemTotal = item.unit_price * item.quantity;
            subtotal += itemTotal;

            return `
                <div class="p-3 bg-gray-50/80 rounded-xl border border-gray-200/60 flex items-center justify-between gap-2">
                    <div class="flex-1 min-w-0">
                        <h5 class="text-xs font-bold text-[#363230] truncate">${item.name}</h5>
                        <span class="text-[10px] text-gray-400">${item.variant_name} &bull; Rp ${item.unit_price.toLocaleString('id-ID')}</span>
                    </div>

                    <div class="flex items-center gap-1.5 shrink-0">
                        <button onclick="PosApp.updateCartQty('${item.cartKey}', -1)" class="w-6 h-6 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 hover:bg-gray-100">-</button>
                        <span class="w-6 text-center text-xs font-bold text-[#363230]">${item.quantity}</span>
                        <button onclick="PosApp.updateCartQty('${item.cartKey}', 1)" class="w-6 h-6 rounded-lg bg-white border border-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 hover:bg-gray-100">+</button>
                    </div>

                    <div class="text-right shrink-0 min-w-[70px]">
                        <span class="font-mono font-bold text-xs text-[#363230]">Rp ${itemTotal.toLocaleString('id-ID')}</span>
                    </div>
                </div>
            `;
        }).join('');

        // Calculate Discounts & Tax
        let memberDiscountAmount = 0;
        if (this.selectedMember && this.selectedMember.discount_percentage > 0) {
            memberDiscountAmount = (subtotal * this.selectedMember.discount_percentage) / 100;
        }

        const totalDiscount = memberDiscountAmount;
        const taxableAmount = Math.max(0, subtotal - totalDiscount);
        const taxAmount = (taxableAmount * this.taxRate) / 100;
        const grandTotal = taxableAmount + taxAmount;

        if (subtotalEl) subtotalEl.innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
        if (discountEl) discountEl.innerText = totalDiscount > 0 ? '-Rp ' + totalDiscount.toLocaleString('id-ID') : 'Rp 0';
        if (taxEl) taxEl.innerText = 'Rp ' + taxAmount.toLocaleString('id-ID');
        if (totalEl) totalEl.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');

        // Update Mobile Badges and Floating Bar
        const totalQty = this.cart.reduce((sum, item) => sum + item.quantity, 0);
        if (mobileBadge) {
            mobileBadge.innerText = totalQty;
            mobileBadge.classList.remove('hidden');
        }
        if (barCount) barCount.innerText = totalQty;
        if (barTotal) barTotal.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');

        if (mobileCartBar) {
            if (this.cart.length > 0 && this.currentMobileTab === 'catalog') {
                mobileCartBar.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
            } else {
                mobileCartBar.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
            }
        }

        this.currentTotals = {
            subtotal,
            discount: totalDiscount,
            member_discount_amount: memberDiscountAmount,
            tax: taxAmount,
            total: grandTotal
        };
    },

    openMemberModal() {
        const modal = document.getElementById('pos-member-modal');
        const listEl = document.getElementById('pos-member-list');
        if (!modal || !listEl) return;

        listEl.innerHTML = this.members.map(m => `
            <div onclick="PosApp.selectMember('${m.id}')" class="p-3 rounded-xl border border-gray-200 hover:border-[#DF5E1D] hover:bg-orange-50/40 cursor-pointer transition flex items-center justify-between">
                <div>
                    <span class="font-bold text-xs text-[#363230] block">${m.name}</span>
                    <span class="text-[10px] text-gray-400 font-mono">${m.member_number ?? 'MBR'} &bull; ${m.phone ?? '-'}</span>
                </div>
                <div class="text-right">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-purple-50 text-purple-700 border border-purple-200">${m.tier} (${m.discount_percentage}%)</span>
                    <span class="text-[10px] text-gray-400 block mt-0.5">${m.points} Pts</span>
                </div>
            </div>
        `).join('');

        modal.classList.remove('hidden');
    },

    selectMember(memberId) {
        this.selectedMember = this.members.find(m => m.id === memberId);
        document.getElementById('pos-member-modal').classList.add('hidden');

        const displayEl = document.getElementById('selected-member-display');
        if (displayEl) {
            displayEl.innerHTML = `
                <div class="flex items-center justify-between p-2.5 bg-orange-50/70 border border-orange-200/80 rounded-xl text-xs">
                    <div>
                        <span class="font-bold text-gray-900">${this.selectedMember.name}</span>
                        <span class="text-[10px] text-purple-600 font-bold block">${this.selectedMember.tier.toUpperCase()} (${this.selectedMember.discount_percentage}% Diskon)</span>
                    </div>
                    <button onclick="PosApp.removeMember()" class="text-gray-400 hover:text-red-600">
                        <iconify-icon icon="solar:close-circle-linear" class="text-lg"></iconify-icon>
                    </button>
                </div>
            `;
        }

        this.renderCart();
    },

    removeMember() {
        this.selectedMember = null;
        const displayEl = document.getElementById('selected-member-display');
        if (displayEl) displayEl.innerHTML = '';
        this.renderCart();
    },

    openPaymentModal() {
        if (this.isShowcase) return;
        if (this.cart.length === 0) return;
        const modal = document.getElementById('pos-payment-modal');
        const totalDisplay = document.getElementById('payment-modal-total');
        if (!modal || !totalDisplay) return;

        totalDisplay.innerText = 'Rp ' + this.currentTotals.total.toLocaleString('id-ID');
        document.getElementById('cash-tendered-input').value = this.currentTotals.total;
        this.calculateChange();

        modal.classList.remove('hidden');
    },

    calculateChange() {
        const cash = parseFloat(document.getElementById('cash-tendered-input').value || 0);
        const total = this.currentTotals.total;
        const change = cash - total;

        const changeEl = document.getElementById('payment-change-display');
        const submitBtn = document.getElementById('payment-submit-btn');

        if (change >= 0) {
            changeEl.innerText = 'Rp ' + change.toLocaleString('id-ID');
            changeEl.className = 'text-xl font-bold font-mono text-emerald-600';
            submitBtn.disabled = false;
        } else {
            changeEl.innerText = 'Kurang Rp ' + Math.abs(change).toLocaleString('id-ID');
            changeEl.className = 'text-base font-bold font-mono text-rose-600';
            submitBtn.disabled = true;
        }
    },

    setQuickCash(amount) {
        document.getElementById('cash-tendered-input').value = amount;
        this.calculateChange();
    },

    async processTransaction(paymentMethod = 'cash') {
        if (this.isShowcase) return;

        const clientUuid = crypto.randomUUID();
        const cashTendered = parseFloat(document.getElementById('cash-tendered-input').value || this.currentTotals.total);
        const changeDue = Math.max(0, cashTendered - this.currentTotals.total);

        const orderData = {
            client_order_uuid: clientUuid,
            order_number: 'POS-' + Math.random().toString(36).substring(2, 8).toUpperCase(),
            items: this.cart,
            subtotal: this.currentTotals.subtotal,
            discount: this.currentTotals.discount,
            member_discount_amount: this.currentTotals.member_discount_amount,
            tax: this.currentTotals.tax,
            total: this.currentTotals.total,
            payment_method: paymentMethod,
            cash_tendered: cashTendered,
            change_due: changeDue,
            member_id: this.selectedMember ? this.selectedMember.id : null,
            member_name: this.selectedMember ? this.selectedMember.name : null,
            notes: 'Transaksi Kasir POS',
            created_at: new Date().toISOString(),
        };

        // 1. Save to Offline Orders & Sync Queue in IndexedDB
        await PosDB.put('offline_orders', orderData);
        await PosDB.put('sync_queue', orderData);

        // 2. Decrement local cached products stock immediately
        for (const item of this.cart) {
            const prod = this.products.find(p => p.id === item.laptop_id);
            if (prod) {
                prod.stock = Math.max(0, prod.stock - item.quantity);
                await PosDB.put('products', prod);
            }
        }

        // Close Modal, Clear Cart & Render Receipt
        document.getElementById('pos-payment-modal').classList.add('hidden');
        this.cart = [];
        this.selectedMember = null;
        this.renderProducts();
        this.renderCart();
        this.updateSyncBadge();

        this.showReceiptModal(orderData);

        // 3. Attempt Background Sync if online
        if (this.isOnline) {
            this.syncQueue();
        }
    },

    showReceiptModal(order) {
        const modal = document.getElementById('pos-receipt-modal');
        const container = document.getElementById('pos-receipt-content');
        if (!modal || !container) return;

        container.innerHTML = `
            <div class="text-center pb-3 border-b border-dashed border-gray-300 font-mono text-xs">
                <h2 class="font-bold text-sm">${this.settings.store_name ?? 'ZLM.ID STORE'}</h2>
                <p class="text-[11px] text-gray-500">${this.settings.store_address ?? 'Malang, Jawa Timur'}</p>
                <p class="text-[10px] text-gray-400">Telp/WA: ${this.settings.store_phone ?? '-'}</p>
            </div>

            <div class="py-2 border-b border-dashed border-gray-300 font-mono text-[11px] space-y-1">
                <div class="flex justify-between"><span>No. Struk:</span><span class="font-bold">${order.order_number}</span></div>
                <div class="flex justify-between"><span>Waktu:</span><span>${new Date(order.created_at).toLocaleString('id-ID')}</span></div>
                <div class="flex justify-between"><span>Kasir / Metode:</span><span>${order.payment_method.toUpperCase()}</span></div>
                ${order.member_name ? `<div class="flex justify-between text-[#DF5E1D]"><span>Member:</span><span>${order.member_name}</span></div>` : ''}
            </div>

            <div class="py-2 border-b border-dashed border-gray-300 font-mono text-[11px] space-y-1.5">
                ${order.items.map(item => `
                    <div class="flex justify-between items-start">
                        <div class="pr-2">
                            <span class="font-bold block">${item.name}</span>
                            <span class="text-[10px] text-gray-500">${item.quantity} x Rp ${item.unit_price.toLocaleString('id-ID')}</span>
                        </div>
                        <span class="font-bold">Rp ${(item.quantity * item.unit_price).toLocaleString('id-ID')}</span>
                    </div>
                `).join('')}
            </div>

            <div class="py-2 border-b border-dashed border-gray-300 font-mono text-[11px] space-y-1">
                <div class="flex justify-between"><span>Subtotal:</span><span>Rp ${order.subtotal.toLocaleString('id-ID')}</span></div>
                ${order.discount > 0 ? `<div class="flex justify-between text-emerald-600"><span>Diskon:</span><span>-Rp ${order.discount.toLocaleString('id-ID')}</span></div>` : ''}
                <div class="flex justify-between"><span>PPN (${this.taxRate}%):</span><span>Rp ${order.tax.toLocaleString('id-ID')}</span></div>
                <div class="flex justify-between font-bold text-xs pt-1 border-t border-gray-200"><span>TOTAL:</span><span class="text-[#DF5E1D]">Rp ${order.total.toLocaleString('id-ID')}</span></div>
                <div class="flex justify-between text-gray-500 pt-1"><span>Tunai / Bayar:</span><span>Rp ${order.cash_tendered.toLocaleString('id-ID')}</span></div>
                <div class="flex justify-between font-bold"><span>Kembalian:</span><span>Rp ${order.change_due.toLocaleString('id-ID')}</span></div>
            </div>

            <div class="text-center pt-3 font-mono text-[10px] text-gray-400">
                <p>Terima kasih atas kunjungan Anda!</p>
                <p>Garansi resmi berlaku dengan menunjukkan struk ini.</p>
            </div>
        `;

        modal.classList.remove('hidden');
    },

    printThermalReceipt() {
        window.print();
    },

    async syncQueue() {
        if (this.isShowcase) return;
        if (this.syncing || !this.isOnline) return;
        this.syncing = true;

        try {
            const queue = await PosDB.getAll('sync_queue');
            if (queue.length === 0) {
                this.syncing = false;
                this.updateSyncBadge();
                return;
            }

            console.log(`[PosApp] Syncing ${queue.length} pending orders to server...`);

            const res = await fetch('/pos/sync', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ orders: queue })
            });

            if (!res.ok) throw new Error('Sync endpoint returned HTTP ' + res.status);
            const result = await res.json();

            if (result.status === 'success' && result.results) {
                for (const item of result.results) {
                    if (item.status === 'synced' || item.status === 'already_synced') {
                        await PosDB.remove('sync_queue', item.client_order_uuid);
                    }
                }
                this.showToast(`Berhasil menyinkronkan ${result.synced_count} transaksi ke database server!`, 'success');
            }
        } catch (e) {
            console.warn('[PosApp] Sync failed, will retry on next connection:', e);
        } finally {
            this.syncing = false;
            this.updateSyncBadge();
        }
    },

    async updateSyncBadge() {
        const badge = document.getElementById('pos-sync-queue-badge');
        if (!badge) return;

        const queue = await PosDB.getAll('sync_queue');
        if (queue.length > 0) {
            badge.innerText = `${queue.length} Pending Sync`;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    },

    showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-5 left-5 z-50 px-4 py-3 rounded-2xl shadow-xl text-xs font-semibold text-white flex items-center gap-2 transition-all transform duration-300 ${
            type === 'success' ? 'bg-emerald-600' :
            type === 'warning' ? 'bg-amber-600' :
            type === 'error' ? 'bg-rose-600' : 'bg-blue-600'
        }`;
        toast.innerHTML = `<iconify-icon icon="solar:info-circle-bold" class="text-base"></iconify-icon><span>${message}</span>`;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }
};

document.addEventListener('DOMContentLoaded', () => {
    PosApp.init();
});
