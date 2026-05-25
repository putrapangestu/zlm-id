# Spec: Admin Product Detail View (B.3)

## Tujuan
Buat halaman detail produk di area admin untuk melihat semua informasi produk secara lengkap.

## Route
```
GET /admin/laptops/{laptop}
→ Admin\LaptopController@show
→ route name: admin.laptops.show
```

## View
- **File baru**: `resources/views/admin/laptops/show.blade.php`
- **Extends**: `layouts.admin`

## Data dari Controller
`Admin\LaptopController@show` (sudah ada):
```php
$laptop->load('categories', 'variants');
return view('admin.laptops.show', compact('laptop'));
```

## UI Layout
```
┌────────────────────────────────────────────────────────────┐
│ [← Back to Laptops]          [Edit] [Delete]              │
├────────────────────────────────────────────────────────────┤
│                                                            │
│  ┌──────────────────────┬─────────────────────────────────┐│
│  │  PRODUCT IMAGE       │  PRODUCT INFO                   ││
│  │                      │                                 ││
│  │  [     image        ]│  Brand badge: Lenovo            ││
│  │   600x400 px         │  Name: ThinkPad X1 Carbon Gen11 ││
│  │                      │  Price: Rp 15.000.000           ││
│  │                      │  Stock: [5 In Stock]            ││
│  │                      │  Featured: [✓ Yes / ✗ No]      ││
│  │                      │  Categories: Business, Premium  ││
│  │                      │                                 ││
│  └──────────────────────┴─────────────────────────────────┘│
│                                                            │
│  ★ Technical Specifications                                │
│  ┌────────────────────────────────────────────────────┐   │
│  │  Processor │ Intel Core i7-13700H                  │   │
│  │  RAM       │ 32GB DDR5                             │   │
│  │  Storage   │ 1TB NVMe SSD                          │   │
│  │  Graphics  │ Intel Iris Xe                         │   │
│  │  Display   │ 14" WUXGA IPS                         │   │
│  │  Weight    │ 1.2 kg                                │   │
│  │  Battery   │ Up to 15 hours                        │   │
│  └────────────────────────────────────────────────────┘   │
│                                                            │
│  ★ Variants                                                │
│  ┌─────────────┬──────────────────┬──────────┐            │
│  │ Name        │ Price Modifier   │ Stock    │            │
│  ├─────────────┼──────────────────┼──────────┤            │
│  │ Standard    │ Rp 0             │ 5        │            │
│  │ Pro         │ +Rp 2.000.000   │ 2        │            │
│  └─────────────┴──────────────────┴──────────┘            │
│                                                            │
│  ★ Description                                             │
│  "A premium ultrabook for professionals..."                │
└────────────────────────────────────────────────────────────┘
```

## Tombol Aksi
- **Edit**: `<a href="{{ route('admin.laptops.edit', $laptop) }}">` — button primary
- **Delete**: `<form method="POST" action="{{ route('admin.laptops.destroy', $laptop) }}">` — button danger with confirm
- **Back**: `<a href="{{ route('admin.laptops.index') }}">` — link text

## Juga perlu:
- **routes/web.php**: tambah `Route::get('/laptops/{laptop}', ...)` di admin group
- **admin/laptops/index.blade.php**: nama produk jadi link ke show
