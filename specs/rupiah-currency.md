# Spec: Rupiah Currency (C.2)

## Tujuan
Semua display harga menggunakan Rupiah (Rp) dengan format Indonesia.

## Format Rupiah
```
Rp {{ number_format($harga, 0, ',', '.') }}
```

Contoh:
| Nilai | Format |
|-------|--------|
| 15000000 | Rp 15.000.000 |
| 2500000.50 | Rp 2.500.001 (dibulatkan) |

## Perubahan per File

### 1. landing/home.blade.php
- Line 98: `${{ number_format($laptop->price, 0) }}`
  → `Rp {{ number_format($laptop->price, 0, ',', '.') }}`

### 2. landing/search.blade.php
- Line 174: `${{ number_format($laptop->price, 0) }}`
  → `Rp {{ number_format($laptop->price, 0, ',', '.') }}`

### 3. landing/detail.blade.php
- Line 117: `${{ number_format($laptop->price, 2) }}`
  → `Rp {{ number_format($laptop->price, 0, ',', '.') }}`
- Line 183: `${{ number_format($laptop->price, 0) }}` (similar section)
  → `Rp {{ number_format($laptop->price, 0, ',', '.') }}`

### 4. landing/detail.blade.php — variant price display
- Line 147: `+${{ number_format($variant->price_modifier, 0) }}`
  → `Rp {{ number_format($variant->price_modifier, 0, ',', '.') }}`

### 5. admin/laptops/index.blade.php
- Line 33: `${{ number_format($laptop->price, 2) }}`
  → `Rp {{ number_format($laptop->price, 0, ',', '.') }}`

### 6. admin/laptops/show.blade.php (NEW)
- Price: `Rp {{ number_format($laptop->price, 0, ',', '.') }}`
- Variant price modifier: `Rp {{ number_format($variant->price_modifier, 0, ',', '.') }}`
