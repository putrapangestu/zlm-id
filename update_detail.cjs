const fs = require('fs');

const filePath = 'c:/wira/projek/web/zlm-id/resources/views/landing/detail.blade.php';
let content = fs.readFileSync(filePath, 'utf8');

const startMarker = '        <!-- Product Detail Section -->';
const endMarker = '        <!-- Similar Laptops Section -->';

const startIndex = content.indexOf(startMarker);
const endIndex = content.indexOf(endMarker);

if (startIndex !== -1 && endIndex !== -1) {
    const newSection = `        <!-- Product Detail Section (3 Column Layout) -->
        <style>
            /* Custom styles for Pros & Cons lists */
            .prose-custom ul {
                list-style: none;
                padding-left: 0;
                margin: 0;
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
            }
            .prose-custom.prose-emerald ul li {
                position: relative;
                padding-left: 1.5rem;
            }
            .prose-custom.prose-emerald ul li::before {
                content: '✓';
                position: absolute;
                left: 0;
                color: #10b981;
                font-weight: bold;
            }
            .prose-custom.prose-rose ul li {
                position: relative;
                padding-left: 1.5rem;
            }
            .prose-custom.prose-rose ul li::before {
                content: '×';
                position: absolute;
                left: 0;
                color: #f43f5e;
                font-weight: bold;
            }
            .prose-custom p { margin-bottom: 0.5rem; }
            .prose-custom p:last-child { margin-bottom: 0; }
        </style>
        <div class="mb-10 lg:mb-16">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start relative">
                
                <!-- 1. Left: Product Image Gallery (Sticky) -->
                <div class="lg:col-span-4 lg:sticky lg:top-24 z-30">
                    <div class="bg-white rounded-3xl border border-gray-200/60 shadow-sm overflow-hidden p-4 sm:p-6 mb-6 lg:mb-0">
                        <input type="checkbox" id="zoom-image" class="peer hidden">

                        <label for="zoom-image" class="relative w-full aspect-square flex items-center justify-center bg-gray-50/50 rounded-2xl border border-gray-100 overflow-hidden mb-4 group cursor-zoom-in transition-all duration-300 hover:border-gray-300">
                            @if ($laptop->image_url)
                                <img id="main-product-image" src="{{ $laptop->image_url_full }}" alt="{{ $laptop->name }}" class="w-full h-full object-contain p-6 mix-blend-multiply transition-transform duration-700 ease-out group-hover:scale-110">
                            @else
                                <img id="main-product-image" src="https://placehold.co/800x600/363230/DF5E1D?text=ZLM" alt="{{ $laptop->name }}" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110">
                            @endif

                            <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-md border border-gray-200 text-[#DF5E1D] px-3 py-1 rounded-lg text-[10px] font-bold shadow-sm uppercase tracking-wider">
                                {{ $laptop->categories->first()?->name ?? 'General' }}
                            </div>

                            <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center pointer-events-none">
                                <div class="bg-white/90 backdrop-blur text-gray-800 p-2.5 rounded-full shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                    <iconify-icon icon="solar:magnifer-zoom-in-linear" class="text-xl block" style="stroke-width: 1.5;"></iconify-icon>
                                </div>
                            </div>
                        </label>

                        <div class="fixed inset-0 z-[100] bg-white/95 backdrop-blur-xl opacity-0 pointer-events-none peer-checked:opacity-100 peer-checked:pointer-events-auto transition-all duration-500 flex items-center justify-center">
                            <label for="zoom-image" class="absolute inset-0 cursor-zoom-out"></label>
                            <label for="zoom-image" class="absolute top-6 right-6 w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-200 hover:text-gray-900 cursor-pointer transition-colors z-10">
                                <iconify-icon icon="solar:close-circle-linear" class="text-2xl" style="stroke-width: 1.5;"></iconify-icon>
                            </label>
                            <div class="relative w-full max-w-5xl max-h-[90vh] p-4 scale-95 peer-checked:scale-100 transition-transform duration-500 ease-out z-0">
                                @if ($laptop->image_url)
                                    <img id="lightbox-image" src="{{ $laptop->image_url_full }}" alt="{{ $laptop->name }}" class="w-full h-full object-contain">
                                @else
                                    <img id="lightbox-image" src="https://placehold.co/1200x800/363230/DF5E1D?text=ZLM" alt="{{ $laptop->name }}" class="w-full h-full object-contain rounded-xl shadow-2xl">
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-4 gap-3">
                            <div class="aspect-square bg-white rounded-xl border-2 border-[#DF5E1D] overflow-hidden cursor-pointer group relative p-1" onclick="document.getElementById('main-product-image').src=this.querySelector('img').src; document.getElementById('lightbox-image').src=this.querySelector('img').src; document.querySelectorAll('.grid-cols-4 > div').forEach(el => {el.classList.remove('border-[#DF5E1D]', 'border-2'); el.classList.add('border-gray-200', 'border');}); this.classList.remove('border-gray-200', 'border'); this.classList.add('border-[#DF5E1D]', 'border-2');">
                                <img src="{{ $laptop->image_url_full ?? 'https://placehold.co/800x600/363230/DF5E1D?text=ZLM' }}" class="w-full h-full object-contain mix-blend-multiply">
                            </div>
                            @forelse ($laptop->images as $image)
                                <div class="aspect-square bg-white rounded-xl border border-gray-200 overflow-hidden cursor-pointer hover:border-[#DF5E1D]/50 transition-colors group relative p-1" onclick="document.getElementById('main-product-image').src=this.querySelector('img').src; document.getElementById('lightbox-image').src=this.querySelector('img').src; document.querySelectorAll('.grid-cols-4 > div').forEach(el => {el.classList.remove('border-[#DF5E1D]', 'border-2'); el.classList.add('border-gray-200', 'border');}); this.classList.remove('border-gray-200', 'border'); this.classList.add('border-[#DF5E1D]', 'border-2');">
                                    <img src="{{ Storage::url($image->image_url) }}" alt="{{ $image->caption ?? 'Product image' }}" class="w-full h-full object-contain mix-blend-multiply">
                                </div>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- 2. Center: Product Info -->
                <div class="lg:col-span-5 flex flex-col">
                    <div class="mb-6">
                        <div class="flex items-center gap-3 mb-3">
                            <span class="text-xs font-bold text-gray-500 tracking-widest uppercase">
                                {{ $laptop->brand }}
                            </span>
                        </div>
                        <h1 class="text-2xl lg:text-3xl font-medium tracking-tight text-[#363230] mb-4 leading-snug">{{ $laptop->name }}</h1>
                        
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-gray-600 mb-6 bg-gray-50/50 p-4 rounded-xl border border-gray-100">
                            @if($laptop->processor)
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="solar:cpu-linear" class="text-gray-400"></iconify-icon>
                                <span class="spec-processor">{{ $laptop->processor }}</span>
                            </div>
                            @endif
                            @if($laptop->ram)
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="solar:ram-linear" class="text-gray-400"></iconify-icon>
                                <span class="spec-ram">{{ $laptop->ram }}</span>
                            </div>
                            @endif
                            @if($laptop->storage)
                            <div class="flex items-center gap-2">
                                <iconify-icon icon="solar:database-linear" class="text-gray-400"></iconify-icon>
                                <span class="spec-storage">{{ $laptop->storage }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="prose prose-sm text-gray-500 leading-relaxed max-w-none mb-8">
                            <p>{{ $laptop->description }}</p>
                        </div>
                    </div>

                    @if ($laptop->variants->count() > 0)
                        <div class="mb-8 p-5 bg-white border border-gray-200/80 rounded-2xl shadow-sm">
                            <label class="block text-sm font-bold text-[#363230] mb-4">Pilih Varian Produk</label>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($laptop->variants as $variant)
                                    <label class="variant-option cursor-pointer group">
                                        <input type="radio" name="variant_id" 
                                               value="{{ $variant->id }}" 
                                               data-price="{{ $laptop->price + $variant->price_modifier }}"
                                               data-stock="{{ $variant->stock }}"
                                               data-image="{{ $variant->image_url_full ?? $laptop->image_url_full }}"
                                               data-ram="{{ $variant->ram ?? $laptop->ram }}"
                                               data-storage="{{ $variant->storage ?? $laptop->storage }}"
                                               data-graphics="{{ $variant->graphics ?? $laptop->graphics }}"
                                               data-display="{{ $variant->display ?? $laptop->display }}"
                                               data-weight="{{ $variant->weight ?? $laptop->weight }}"
                                               data-battery="{{ $variant->battery_life ?? $laptop->battery_life }}"
                                               class="peer hidden">
                                        <div class="px-4 py-3 rounded-xl border-2 border-gray-200 peer-checked:border-[#DF5E1D] peer-checked:bg-[#DF5E1D]/5 group-hover:border-[#DF5E1D]/40 transition-all flex flex-col">
                                            <span class="text-sm font-semibold text-gray-700 peer-checked:text-[#DF5E1D]">{{ $variant->name }}</span>
                                            @if ($variant->price_modifier > 0)
                                                <span class="text-xs text-gray-500 peer-checked:text-[#DF5E1D]/80 mt-1">+Rp {{ number_format($variant->price_modifier, 0, ',', '.') }}</span>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                        <div class="bg-emerald-50/50 p-5 rounded-2xl border border-emerald-100">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                    <iconify-icon icon="solar:check-circle-bold" class="text-lg"></iconify-icon>
                                </div>
                                <h3 class="text-sm font-bold text-emerald-900">Kelebihan</h3>
                            </div>
                            @if ($laptop->kelebihan)
                            <div class="prose-custom prose-emerald text-sm text-emerald-800/80 leading-relaxed">
                                {!! $laptop->kelebihan !!}
                            </div>
                            @else
                            <p class="text-xs text-emerald-600/60 italic">Belum ada data.</p>
                            @endif
                        </div>

                        <div class="bg-rose-50/50 p-5 rounded-2xl border border-rose-100">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                                    <iconify-icon icon="solar:close-circle-bold" class="text-lg"></iconify-icon>
                                </div>
                                <h3 class="text-sm font-bold text-rose-900">Kekurangan</h3>
                            </div>
                            @if ($laptop->kekurangan)
                            <div class="prose-custom prose-rose text-sm text-rose-800/80 leading-relaxed">
                                {!! $laptop->kekurangan !!}
                            </div>
                            @else
                            <p class="text-xs text-rose-600/60 italic">Belum ada data.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 3. Right: Action Card (Sticky) -->
                <div class="lg:col-span-3 lg:sticky lg:top-24 z-30 mt-8 lg:mt-0">
                    <div class="bg-white rounded-3xl border border-gray-200/80 shadow-[0_8px_30px_rgb(0,0,0,0.06)] p-6 flex flex-col relative overflow-hidden">
                        
                        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#DF5E1D] to-[#f4844b]"></div>

                        <h3 class="text-lg font-bold text-[#363230] mb-4">Atur Pembelian</h3>

                        <div class="mb-6">
                            <span class="text-xs text-gray-400 font-medium tracking-wide uppercase mb-1 block">Total Harga</span>
                            <div id="product-price" class="text-2xl xl:text-3xl font-bold tracking-tight text-[#DF5E1D]">
                                Rp {{ number_format($laptop->price, 0, ',', '.') }}
                            </div>
                        </div>

                        <hr class="border-gray-100 mb-6">

                        <div id="stock-badge" class="mb-6">
                            @if ($laptop->stock > 0)
                                <div class="flex items-center gap-2.5 text-sm font-medium text-emerald-600 bg-emerald-50 px-3 py-2 rounded-lg border border-emerald-100">
                                    <iconify-icon icon="solar:check-circle-bold" class="text-lg"></iconify-icon>
                                    Stok Tersedia ({{ $laptop->stock }})
                                </div>
                            @else
                                <div class="flex items-center gap-2.5 text-sm font-medium text-rose-600 bg-rose-50 px-3 py-2 rounded-lg border border-rose-100">
                                    <iconify-icon icon="solar:close-circle-bold" class="text-lg"></iconify-icon>
                                    Stok Habis
                                </div>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('cart.add') }}" class="flex flex-col gap-3 mb-6">
                            @csrf
                            <input type="hidden" name="laptop_id" value="{{ $laptop->id }}">
                            <input type="hidden" name="variant_id" id="selectedVariantId" value="">
                            <input type="hidden" name="quantity" value="1">

                            <button id="addToCartBtn" type="submit" class="w-full bg-[#DF5E1D] shadow-md shadow-[#DF5E1D]/20 text-white py-3.5 px-4 rounded-xl text-sm font-bold hover:bg-[#c45218] hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed group" @if ($laptop->stock <= 0) disabled @endif>
                                <iconify-icon icon="solar:cart-large-2-bold" class="text-lg group-hover:-translate-y-0.5 transition-transform"></iconify-icon>
                                <span id="addToCartText">Masukkan Keranjang</span>
                            </button>
                            
                            <button type="button" onclick="toggleDetailWishlist('{{ $laptop->id }}')" data-wishlist-btn data-laptop-id="{{ $laptop->id }}" class="w-full bg-white border-2 border-gray-200 text-gray-700 py-3 px-4 rounded-xl text-sm font-bold hover:border-rose-200 hover:bg-rose-50 hover:text-rose-600 transition-all duration-300 flex items-center justify-center gap-2 group">
                                <iconify-icon icon="solar:heart-bold" class="text-lg text-gray-300 group-hover:text-rose-500 transition-colors"></iconify-icon>
                                <span>Simpan ke Wishlist</span>
                            </button>
                        </form>

                        <div class="flex items-center justify-center gap-4 mt-auto pt-4 border-t border-gray-100">
                            <span class="text-xs text-gray-400 font-medium">Bagikan:</span>
                            <div class="flex items-center gap-2">
                                <button onclick="shareProduct()" class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-[#DF5E1D] hover:text-white transition-colors">
                                    <iconify-icon icon="solar:share-circle-bold" class="text-sm"></iconify-icon>
                                </button>
                                <button onclick="copyProductLink()" class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-[#DF5E1D] hover:text-white transition-colors">
                                    <iconify-icon icon="solar:link-bold" class="text-sm"></iconify-icon>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
        
        <script>
        document.querySelectorAll('.variant-option input').forEach(radio => {
            radio.addEventListener('change', function() {
                const variantInput = document.getElementById('selectedVariantId');
                if (variantInput) variantInput.value = this.value;
                
                const priceEl = document.getElementById('product-price');
                if (priceEl && this.dataset.price) {
                    priceEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(this.dataset.price);
                }
                
                const mainImage = document.getElementById('main-product-image');
                if (mainImage && this.dataset.image) {
                    mainImage.src = this.dataset.image;
                    const lightboxImage = document.getElementById('lightbox-image');
                    if (lightboxImage) lightboxImage.src = this.dataset.image;
                }
                
                const specFields = [
                    { key: 'ram', selector: '.spec-ram' },
                    { key: 'storage', selector: '.spec-storage' },
                    { key: 'graphics', selector: '.spec-graphics' },
                    { key: 'display', selector: '.spec-display' },
                    { key: 'battery', selector: '.spec-battery' },
                    { key: 'weight', selector: '.spec-weight' },
                    { key: 'processor', selector: '.spec-processor' }
                ];
                
                specFields.forEach(function(field) {
                    const el = document.querySelector(field.selector);
                    if (el && this.dataset[field.key]) {
                        let value = this.dataset[field.key];
                        if (field.key === 'weight') value = value + ' kg';
                        el.textContent = value;
                    }
                }.bind(this));
                
                const stock = parseInt(this.dataset.stock);
                const stockBadge = document.getElementById('stock-badge');
                if (stockBadge) {
                    if (stock > 0) {
                        stockBadge.innerHTML = '<div class="flex items-center gap-2.5 text-sm font-medium text-emerald-600 bg-emerald-50 px-3 py-2 rounded-lg border border-emerald-100"><iconify-icon icon="solar:check-circle-bold" class="text-lg"></iconify-icon>Stok Tersedia (' + stock + ')</div>';
                    } else {
                        stockBadge.innerHTML = '<div class="flex items-center gap-2.5 text-sm font-medium text-rose-600 bg-rose-50 px-3 py-2 rounded-lg border border-rose-100"><iconify-icon icon="solar:close-circle-bold" class="text-lg"></iconify-icon>Stok Habis</div>';
                    }
                }
                
                const cartBtn = document.getElementById('addToCartBtn');
                if (cartBtn) {
                    cartBtn.disabled = stock <= 0;
                }
            });
        });
        </script>

`;
    
    content = content.substring(0, startIndex) + newSection + '\n' + content.substring(endIndex);
    fs.writeFileSync(filePath, content, 'utf8');
    console.log('Successfully updated the detail section.');
} else {
    console.log('Could not find markers.', {startIndex, endIndex});
}
