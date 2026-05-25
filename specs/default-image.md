# Spec: Default Image (C.3)

## Tujuan
Produk tanpa `image_url` harus menampilkan default placeholder sebagai fallback, bukan broken image.

## Strategi
Gunakan URL placeholder service dengan warna brand:
```
https://placehold.co/600x400/DF5E1D/FFFFFF?text=ZLM
```

Atau untuk local: buat file SVG di `public/assets/default-laptop.svg`

## File yang Diubah

### landing/home.blade.php (line ~63-67)
```
@if ($laptop->image_url)
    <img src="{{ $laptop->image_url }}" ...>
@else
    <img src="https://placehold.co/600x400/DF5E1D/FFFFFF?text=ZLM" ...>
@endif
```

### landing/search.blade.php (line ~133-138)
Same pattern as home.

### landing/detail.blade.php (line ~45-49)
Same pattern, tapi ukuran lebih besar:
```
https://placehold.co/800x600/DF5E1D/FFFFFF?text=ZLM
```

### admin/laptops/show.blade.php (NEW)
Pakai ukuran `400x300` untuk admin panel.

## Pattern yang Sama di Semua File
```blade
@if ($laptop->image_url)
    <img src="{{ $laptop->image_url }}" alt="{{ $laptop->name }}" ...>
@else
    <img src="https://placehold.co/600x400/DF5E1D/FFFFFF?text=ZLM" alt="{{ $laptop->name }}" ...>
@endif
```
