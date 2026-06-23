# Spec Modul B — Variant Switching di Detail Page

## Problem
Detail page menampilkan variant options (radio button) yang hanya mengupdate price. Ketika user memilih variant berbeda, gambar utama, spec table, dan stock badge tidak berubah.

## Solusi

### 1. Embed Data Variant di HTML

Setiap variant option perlu menyimpan data lengkap sebagai `data-*` attributes:

```blade
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
```

### 2. JavaScript Handler

Ganti event listener yang ada (line 178-186 di detail.blade.php) dengan yang lebih komprehensif:

```javascript
document.querySelectorAll('.variant-option input').forEach(radio => {
    radio.addEventListener('change', function() {
        // 1. Update hidden input for cart
        document.getElementById('selectedVariantId').value = this.value;
        
        // 2. Update price
        const price = this.dataset.price;
        document.querySelector('.text-4xl').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
        
        // 3. Update main image
        const mainImage = document.querySelector('#main-product-image');
        if (mainImage && this.dataset.image) {
            mainImage.src = this.dataset.image;
        }
        
        // 4. Update specs table
        const specMap = {
            'ram': '.spec-ram',
            'storage': '.spec-storage', 
            'graphics': '.spec-graphics',
            'display': '.spec-display',
            'weight': '.spec-weight',
            'battery': '.spec-battery'
        };
        
        for (const [key, selector] of Object.entries(specMap)) {
            const el = document.querySelector(selector);
            if (el && this.dataset[key]) {
                el.textContent = this.dataset[key] + (key === 'weight' ? ' kg' : '');
            }
        }
        
        // 5. Update stock badge
        const stock = parseInt(this.dataset.stock);
        const stockBadge = document.querySelector('#stock-badge');
        if (stockBadge) {
            if (stock > 0) {
                stockBadge.innerHTML = '<span class="...">Stok Tersedia (' + stock + ')</span>';
            } else {
                stockBadge.innerHTML = '<span class="...">Stok Habis</span>';
            }
        }
        
        // 6. Update add-to-cart button state
        const cartBtn = document.querySelector('#addToCartBtn');
        if (cartBtn) {
            cartBtn.disabled = stock <= 0;
        }
    });
});
```

### 3. Update HTML Structure Detail Page

- Tambah `id` atau `class` pada elemen yang perlu diupdate:
  - Main image: `id="main-product-image"`
  - Spec table cells: class `spec-ram`, `spec-storage`, dll
  - Stock badge: `id="stock-badge"` 
  - Add-to-cart button: `id="addToCartBtn"`
  - Harga: sudah ada (`.text-4xl`)

### 4. Handling "No Variant" Case

Jika laptop tidak punya variant, tampilkan default laptop data (seperti sekarang).

## Files Changed

| File | Action |
|------|--------|
| `resources/views/landing/detail.blade.php` | MODIFY (data attributes + JS) |

## Testing

- Pilih variant → gambar utama berubah
- Pilih variant → RAM, Storage, Graphics, Display, Weight, Battery di spec table berubah
- Pilih variant → stock badge berubah (hoki/abis)
- Pilih variant → harga berubah
- Kembali pilih variant lain → data berubah lagi
- Tanpa variant → tampil data laptop default
