# Spec Modul B — Admin Variant Management & Frontend Variant Switching

## Problem

**Problem 1 (Admin):** Halaman show laptop di admin (`admin/laptops/show.blade.php`) hanya menampilkan section Variants jika kondisi `$laptop->variants->count() > 0`. Jika laptop belum punya variant sama sekali, section tersebut **tidak muncul** — user bingung "variant-nya ada dimana?" dan tidak ada cara untuk menambahkan variant dari halaman tersebut.

**Problem 2 (Frontend):** Detail page menampilkan variant options (radio button) yang hanya mengupdate price. Ketika user memilih variant berbeda, gambar utama, spec table, dan stock badge tidak berubah.

---

## Solution 1 — Admin: Variant Section Selalu Muncul

### File: `resources/views/admin/laptops/show.blade.php`

**Hapus kondisi `@if` di line 158-193**, ganti dengan:

```blade
{{-- Variants --}}
<div class="bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-base font-semibold text-[#363230] flex items-center gap-2">
            <iconify-icon icon="solar:git-branch-linear" class="text-[#DF5E1D]"></iconify-icon>
            Variants
        </h3>
        <div class="flex items-center gap-2">
            @if ($laptop->variants->count() > 0)
                <a href="{{ route('admin.laptops.variants.index', $laptop) }}" 
                   class="text-xs text-gray-500 hover:text-[#DF5E1D] font-medium transition-colors flex items-center gap-1">
                    <iconify-icon icon="solar:settings-linear" class="text-sm"></iconify-icon>
                    Manage Variants
                </a>
            @endif
            <a href="{{ route('admin.laptops.variants.create', $laptop) }}" 
               class="bg-[#DF5E1D] text-white px-3 py-1.5 rounded-lg text-xs font-medium hover:bg-[#c45218] transition-colors flex items-center gap-1">
                <iconify-icon icon="solar:add-circle-linear" class="text-sm"></iconify-icon>
                Add Variant
            </a>
        </div>
    </div>

    @if ($laptop->variants->count() > 0)
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Name</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Price Modifier</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Stock</th>
                    <th class="py-3 px-6 text-[10px] font-medium text-gray-400 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($laptop->variants as $variant)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3 px-6 font-medium text-[#363230]">{{ $variant->name }}</td>
                        <td class="py-3 px-6 text-gray-600">Rp {{ number_format($variant->price_modifier, 0, ',', '.') }}</td>
                        <td class="py-3 px-6">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-medium {{ $variant->stock > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                {{ $variant->stock }}
                            </span>
                        </td>
                        <td class="py-3 px-6">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.variants.edit', $variant) }}" class="text-gray-400 hover:text-blue-500 transition-colors" title="Edit">
                                    <iconify-icon icon="solar:pen-linear"></iconify-icon>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="p-8 text-center">
            <iconify-icon icon="solar:git-branch-linear" class="text-3xl text-gray-200 mb-3"></iconify-icon>
            <p class="text-sm text-gray-500 mb-4">Belum ada variant untuk laptop ini.</p>
            <a href="{{ route('admin.laptops.variants.create', $laptop) }}" 
               class="inline-flex items-center gap-1.5 bg-[#DF5E1D] text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-[#c45218] transition-colors">
                <iconify-icon icon="solar:add-circle-linear"></iconify-icon>
                Tambah Variant Pertama
            </a>
        </div>
    @endif
</div>
```

### Key Changes:
- `@if` dihapus → section selalu muncul
- Header selalu visible dengan judul "Variants" + 2 tombol action ("Manage Variants" + "Add Variant")
- "Manage Variants" hanya muncul kalau sudah ada variant
- "Add Variant" selalu muncul
- Empty state dengan ikon dan tombol CTA ketika belum ada variant

---

## Solution 2 — Frontend: Variant Switching di Detail Page

### File: `resources/views/landing/detail.blade.php`

### 2.1 Embed Data Variant

Tambah `data-*` attributes ke setiap variant option:

```blade
@foreach ($laptop->variants as $variant)
    <label class="variant-option cursor-pointer">
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
        <div class="px-4 py-2.5 rounded-xl border-2 border-gray-200 peer-checked:border-[#DF5E1D] ...">
            <span class="font-medium">{{ $variant->name }}</span>
        </div>
    </label>
@endforeach
```

### 2.2 Tambah Identifiers di HTML

Elemen-elemen yang perlu diupdate saat variant berubah harus punya id/class:

- **Main image**: tambah `id="main-product-image"` pada `<img>` di dalam label zoom
- **Spec table cells**: tambah class:
  - `<td class="px-6 py-5 text-[#363230] spec-ram">{{ $laptop->ram }}</td>`
  - `<td class="px-6 py-5 text-[#363230] spec-storage">{{ $laptop->storage }}</td>`
  - `<td class="px-6 py-5 text-[#363230] spec-graphics">{{ $laptop->graphics }}</td>`
  - `<td class="px-6 py-5 text-[#363230] spec-display">{{ $laptop->display }}</td>`
  - `<td class="px-6 py-5 text-[#363230] spec-weight">{{ $laptop->weight }} kg</td>`
  - `<td class="px-6 py-5 text-[#363230] spec-battery">{{ $laptop->battery_life }}</td>`
- **Stock badge**: tambah `id="stock-badge"` pada div pembungkus stock
- **Add to cart button**: tambah `id="addToCartBtn"` pada button submit
- **Price**: sudah ada class `.text-4xl`

### 2.3 Update JavaScript Handler

Ganti script variant yang ada (line 178-186) dengan:

```javascript
document.querySelectorAll('.variant-option input').forEach(radio => {
    radio.addEventListener('change', function() {
        // 1. Update hidden input for cart form
        const variantInput = document.getElementById('selectedVariantId');
        if (variantInput) variantInput.value = this.value;
        
        // 2. Update price
        const priceEl = document.querySelector('#product-price');
        if (priceEl && this.dataset.price) {
            priceEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(this.dataset.price);
        }
        
        // 3. Update main image
        const mainImage = document.getElementById('main-product-image');
        if (mainImage && this.dataset.image) {
            mainImage.src = this.dataset.image;
            // Also update lightbox image
            const lightboxImage = document.getElementById('lightbox-image');
            if (lightboxImage) lightboxImage.src = this.dataset.image;
        }
        
        // 4. Update specs table
        const specFields = ['ram', 'storage', 'graphics', 'display', 'weight', 'battery'];
        specFields.forEach(field => {
            const el = document.querySelector('.spec-' + field);
            if (el && this.dataset[field]) {
                let value = this.dataset[field];
                if (field === 'weight') value += ' kg';
                el.textContent = value;
            }
        });
        
        // 5. Update stock badge
        const stock = parseInt(this.dataset.stock);
        const stockBadge = document.getElementById('stock-badge');
        if (stockBadge) {
            if (stock > 0) {
                stockBadge.innerHTML = '<div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 px-3.5 py-2 rounded-xl text-xs font-medium border border-emerald-200/60 shadow-sm">' +
                    '<iconify-icon icon="solar:check-circle-linear" class="text-sm"></iconify-icon>' +
                    'Stok Tersedia (' + stock + ')</div>';
            } else {
                stockBadge.innerHTML = '<div class="inline-flex items-center gap-2 bg-rose-50 text-rose-600 px-3.5 py-2 rounded-xl text-xs font-medium border border-rose-200/60 shadow-sm">' +
                    '<iconify-icon icon="solar:close-circle-linear" class="text-sm"></iconify-icon>' +
                    'Stok Habis</div>';
            }
        }
        
        // 6. Update add-to-cart button
        const cartBtn = document.getElementById('addToCartBtn');
        if (cartBtn) {
            cartBtn.disabled = stock <= 0;
        }
    });
});
```

### 2.4 Handling: Tidak Ada Variant

Jika `$laptop->variants->count() === 0`, tampilkan data laptop default (sama seperti sekarang) — tidak perlu JS variant switching.

---

## Files Changed

| File | Action |
|------|--------|
| `resources/views/admin/laptops/show.blade.php` | MODIFY (variant section selalu muncul) |
| `resources/views/landing/detail.blade.php` | MODIFY (data attributes + identifiers + JS) |

## Testing

**Admin:**
- Buka admin show laptop yang TIDAK punya variant → muncul section "Variants" dengan empty state + tombol "Tambah Variant Pertama"
- Klik tombol → masuk ke halaman create variant
- Buka admin show laptop yang SUDAH punya variant → muncul table variant + tombol "Manage Variants" + "Add Variant"

**Frontend:**
- Pilih variant → gambar utama berganti
- Pilih variant → RAM, Storage, Graphics, Display, Weight, Battery di spec table berubah
- Pilih variant → stock badge berubah (tersedia/habis)
- Pilih variant → harga berubah
- Tanpa variant → tampil data laptop default
- Add-to-cart button disable/enable sesuai stock variant
