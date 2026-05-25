# PLAN.md — ZLM-ID Landing & Admin Modules

## Project Context
Laravel 13.x e-commerce laptop store with MySQL, Tailwind CSS, Alpine.js, Spatie Roles, Iconify Icons (Solar).

### Design System
- **Primary Color**: `#DF5E1D` (orange)
- **Text Color**: `#363230` (dark charcoal)
- **Font**: Inter (Google Fonts)
- **Border Radius**: `xl` (12px), `2xl` (16px)
- **Shadow**: `shadow-sm`, `shadow-md`, `shadow-lg`

---

## Module Breakdown

### Modul A: Landing Pages

#### A.1 Landing Home
**File**: `resources/views/landing/home.blade.php` (sudah ada — revisi harga & gambar)

**Data Controller** (`LaptopController@index`):
- `$featured` — 6 laptop teratas dengan `is_featured = true`
- `$categories` — semua kategori aktif

**UI Design**:
```
┌─────────────────────────────────────────────────────┐
│ [NAV] Logo | Beranda  Katalog  Artikel  [Compare][Cart][User] │
├─────────────────────────────────────────────────────┤
│                                                     │
│  ★ Hero Section — dark bg (#363230)                │
│  "Find your perfect workstation."                  │
│  [Explore Catalog] [View Featured]                 │
│  ┌──────────────────────────┐                      │
│  │   Laptop hero image      │                      │
│  └──────────────────────────┘                      │
│                                                     │
│  ★ Featured Collection (4 col grid)                │
│  ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐              │
│  │image │ │image │ │image │ │image │              │
│  │brand │ │brand │ │brand │ │brand │              │
│  │name  │ │name  │ │name  │ │name  │              │
│  │processor│ │proc  │ │proc  │ │proc  │              │
│  │RAM   │ │RAM   │ │RAM   │ │RAM   │              │
│  │storage│ │stor  │ │stor  │ │stor  │              │
│  │Rp xxx│ │Rp xxx│ │Rp xxx│ │Rp xxx│              │
│  │[♥][⚖][→Detail]││[♥][⚖][→Detail]│              │
│  └──────┘ └──────┘ └──────┘ └──────┘              │
│                                                     │
│  ★ Categories (4 col grid) — bg white              │
│  ★ Testimonials (3 col grid) — bg gray-50/100      │
│  ★ Articles/Blog (3 col grid) — bg white           │
│  ★ Newsletter — dark bg (#363230)                  │
├─────────────────────────────────────────────────────┤
│ [FOOTER]                                             │
└─────────────────────────────────────────────────────┘
```

#### A.2 Landing Katalog (Search/Catalog)
**File**: `resources/views/landing/search.blade.php` (sudah ada — revisi harga & gambar)

**Data Controller** (`LaptopController@search`):
- `$laptops` — paginated results (12/page) after filtering
- `$brands` — distinct brand list from DB
- `$maxPrice` — max price from all laptops
- `$categories` — semua kategori aktif

**UI Design**:
```
┌─────────────────────────────────────────────────────────┐
│ [NAV]                                              │
├──────────┬──────────────────────────────────────────────┤
│ FILTERS  │  RESULTS: "Showing X of Y results"           │
│ (sticky) │                                              │
│          │  ┌──────┐ ┌──────┐ ┌──────┐                 │
│ Search   │  │image │ │image │ │image │                 │
│ ──────── │  │brand │ │brand │ │brand │                 │
│ Category │  │name  │ │name  │ │name  │                 │
│ ○ All    │  │proc  │ │proc  │ │proc  │                 │
│ ○ Gaming │  │RAM   │ │RAM   │ │RAM   │                 │
│ ○ Busin..│  │storage│ │stor  │ │stor  │                 │
│          │  │Rp xxx│ │Rp xxx│ │Rp xxx│                 │
│ Brand    │  │[♥][⚖][→]││[♥][⚖][→]││[♥][⚖][→]│         │
│ [dropdown]│  └──────┘ └──────┘ └──────┘                 │
│          │                                              │
│ Price    │  [1] [2] [3] ... [Next] (pagination)         │
│ Min $__  │                                              │
│ Max $__  │  OR Empty State: "No hardware found"        │
│          │                                              │
│ [Apply]  │  ★ Floating Compare Card (bottom-right)      │
│ [Reset]  │  ┌─────────────────┐                        │
│          │  │ Compare 1/2     │                        │
│          │  │ [View][Clear]   │                        │
│          │  └─────────────────┘                        │
└──────────┴──────────────────────────────────────────────┘
```

**Card Product Detail (used in home & search)**:
| Elemen | Sumber Data | Tipe |
|--------|------------|------|
| Image | `$laptop->image_url` | Gambar URL, fallback ke default |
| Brand badge | `$laptop->brand` | Text (uppercase, small) |
| Nama produk | `$laptop->name` | Text (clamp 2 lines) |
| Processor | `$laptop->processor` | Text with CPU icon |
| RAM | `$laptop->ram` | Text with RAM icon |
| Storage | `$laptop->storage` | Text with Database icon |
| Harga (Rp) | `$laptop->price` | Format `Rp 15.000.000` |
| Wishlist btn | — | Heart icon, localStorage toggle |
| Compare btn | — | Scale icon, localStorage toggle |
| Detail btn | `route('landing.detail')` | Link ke detail page |
| Stock overlay | `$laptop->stock` | "Out of Stock" overlay jika 0 |

#### A.3 Landing Detail Katalog
**File**: `resources/views/landing/detail.blade.php` (sudah ada — revisi harga, gambar, + kelebihan/kekurangan)

**Data Controller** (`LaptopController@show`):
- `$laptop` — single laptop with categories, variants eager loaded
- `$similar` — 4 laptops in same categories (excl. current)

**UI Design**:
```
┌─────────────────────────────────────────────────────┐
│ [NAV]                                              │
├─────────────────────────────────────────────────────┤
│  Breadcrumb: Home > Products > Laptop Name          │
│                                                     │
│  ┌─────────────────────┬──────────────────────────┐│
│  │ IMAGE GALLERY       │  PRODUCT INFO            ││
│  │                     │                          ││
│  │  ┌───────────────┐  │  brand badge             ││
│  │  │  Main Image   │  │  Nama Produk (h1)        ││
│  │  │  (zoom on     │  │  Description             ││
│  │  │   hover)      │  │                          ││
│  │  └───────────────┘  │  Total Price: Rp xxx     ││
│  │                     │  [In Stock (5)]          ││
│  │  [img1][img2][+all]│                          ││
│  │                     │  Varian (radio buttons):  ││
│  │                     │  [○ Standard] [● Pro]     ││
│  │                     │                          ││
│  │                     │  [🛒 Add to Cart] [♥][⚖] ││
│  │                     │                          ││
│  │                     │  "Recently purchased"    ││
│  │                     │  [Share] [Copy Link]     ││
│  └─────────────────────┴──────────────────────────┘│
│                                                     │
│  ★ Technical Specifications                         │
│  ┌──────────────────────────────────────────┐      │
│  │ Processor  │ Intel Core i7-13700H        │      │
│  │ RAM       │ 32GB DDR5                    │      │
│  │ Storage   │ 1TB NVMe SSD                 │      │
│  │ Graphics  │ NVIDIA RTX 4070              │      │
│  │ Display   │ 16" QHD+ 240Hz              │      │
│  │ Battery   │ Up to 10 hours               │      │
│  │ Weight    │ 1.8 kg                       │      │
│  └──────────────────────────────────────────┘      │
│                                                     │
│  ★ Kelebihan & Kekurangan (NEW)                    │
│  ┌──────────────┬──────────────────────────┐      │
│  │ ✅ KELEBIHAN │ ❌ KEKURANGAN            │      │
│  │              │                          │      │
│  │  • Performa  │  • Harga mahal          │      │
│  │  • Build     │  • Berat                │      │
│  │  • Display   │  • Fan noise            │      │
│  └──────────────┴──────────────────────────┘      │
│                                                     │
│  ★ Similar Models (4 col grid)                      │
│  ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐              │
│  │card  │ │card  │ │card  │ │card  │              │
│  └──────┘ └──────┘ └──────┘ └──────┘              │
└─────────────────────────────────────────────────────┘
```

**Detail Kelebihan/Kekurangan di Landing Detail**:
Data ditampilkan dalam 2 kolom (split layout):
- Kolom kiri: **✅ Kelebihan** — list bullet points dari `$laptop->kelebihan` (diparsed per baris)
- Kolom kanan: **❌ Kekurangan** — list bullet points dari `$laptop->kekurangan` (diparsed per baris)

Format penyimpanan: text, 1 baris = 1 poin. Ditampilkan dengan `nl2br()` atau explode.

---

### Modul B: Admin Pages

#### B.1 Admin Category
**Files**: `index.blade.php`, `create.blade.php`, `edit.blade.php` (sudah ada)
**Controller**: `Admin\CategoryController`

**Form Input Detail — Create/Edit Category**:
| Field | Input Type | Required | Notes |
|-------|-----------|----------|-------|
| Name | `<input type="text">` | Yes | max 255 chars |
| Slug | `<input type="text">` | No | Auto-generated dari name jika kosong |
| Description | `<textarea rows="3">` | No | Text area |
| Icon | `<input type="text">` | No | Iconify class, e.g. `solar:gamepad-linear` |
| Image URL | `<input type="url">` | No | Full URL |
| Is Active | `<input type="checkbox">` | No | Default checked |

**UI Design — Index**:
```
┌───────────────────────────────────────────────────────┐
│ Categories                    [+ Add Category]        │
├───────────────────────────────────────────────────────┤
│ ┌───────────────────────────────────────────────────┐│
│ │ Name    │ Slug     │ Products │ Status  │ Actions ││
│ ├────────┼─────────┼──────────┼────────┼────────┤│
│ │ 📁Gaming│ gaming  │ 12      │ Active  │ ✏️ 🗑️ ││
│ │ 📁Bus.. │ business│ 8       │ Active  │ ✏️ 🗑️ ││
│ └────────┴─────────┴──────────┴────────┴────────┘│
│ [Pagination]                                        │
└───────────────────────────────────────────────────────┘
```

#### B.2 Admin Product (Laptop)
**Files**: `index.blade.php`, `create.blade.php`, `edit.blade.php` (sudah ada)
**Baru**: `show.blade.php` (perlu dibuat)
**Controller**: `Admin\LaptopController`
**Model**: `App\Models\Laptop` (+ fillable update)
**Migration**: NEW — `add_kelebihan_kekurangan_to_laptops_table`

**Struktur Database (kolom baru)**:
| Kolom | Tipe | Nullable | Default |
|-------|------|----------|---------|
| `kelebihan` | `text` | Yes | null |
| `kekurangan` | `text` | Yes | null |

**Form Input Detail — Create/Edit Laptop**:
| Field | Input Type | Required | Notes |
|-------|-----------|----------|-------|
| Name | `<input type="text">` | Yes | |
| Brand | `<input type="text">` | Yes | |
| Description | **Trix Editor** | Yes | WYSIWYG, menyimpan HTML |
| Price | `<input type="number" step="0.01">` | Yes | Dijadikan Rupiah di display |
| Stock | `<input type="number">` | Yes | |
| Processor | `<input type="text">` | Yes | |
| RAM | `<input type="text">` | Yes | e.g. "32GB DDR5" |
| Storage | `<input type="text">` | Yes | e.g. "1TB NVMe SSD" |
| Graphics | `<input type="text">` | No | |
| Display | `<input type="text">` | No | |
| Weight | `<input type="number" step="0.01">` | No | kg |
| Battery Life | `<input type="text">` | No | e.g. "Up to 10 hours" |
| Image URL | `<input type="url">` | No | Full URL |
| **Kelebihan** | **Trix Editor** | **No** | **WYSIWYG, 1 baris ≈ 1 poin (NEW)** |
| **Kekurangan** | **Trix Editor** | **No** | **WYSIWYG, 1 baris ≈ 1 poin (NEW)** |
| Categories | `<input type="checkbox">[]` | No | Checkbox group dari semua kategori |
| Is Featured | `<input type="checkbox">` | No | |

**UI Design — Index**:
```
┌───────────────────────────────────────────────────────────────┐
│ Laptops                                        [+ Add Laptop] │
├───────────────────────────────────────────────────────────────┤
│ ┌──────────┬───────┬─────────┬───────┬────────────┬────────┐│
│ │ Name     │ Brand │ Price   │ Stock │ Categories │ Actions││
│ ├──────────┼───────┼─────────┼───────┼────────────┼────────┤│
│ │ ThinkPad │ Lenovo│ Rp 15jt │ 5     │ Business   │ ✏️🌿🗑️ ││
│ │ (click → detail)│         │       │            │        ││
│ │ ROG Zeph..│ ASUS │ Rp 25jt │ 0     │ Gaming     │ ✏️🌿🗑️ ││
│ └──────────┴───────┴─────────┴───────┴────────────┴────────┘│
│ [Pagination]                                                   │
└───────────────────────────────────────────────────────────────┘
```

#### B.3 Admin Product Detail (NEW)
**File**: `resources/views/admin/laptops/show.blade.php` (BARU)

**UI Design**:
```
┌───────────────────────────────────────────────────────┐
│ Laptop Detail: ThinkPad X1 Carbon      [Edit] [Delete]│
├───────────────────────────────────────────────────────┤
│ ┌───────────────┬─────────────────────────────────┐  │
│ │  IMAGE        │  INFO                           │  │
│ │               │  Brand: Lenovo                  │  │
│ │  [image]      │  Price: Rp 15.000.000          │  │
│ │               │  Stock: 5 [In Stock]            │  │
│ │               │  Featured: Yes ✓                │  │
│ │               │  Categories: Business, Premium   │  │
│ └───────────────┴─────────────────────────────────┘  │
│                                                       │
│  ★ Technical Specifications                           │
│  ┌──────────────────────────────────────────────┐   │
│  │ Processor   │ Intel Core i7-13700H           │   │
│  │ RAM         │ 32GB DDR5                      │   │
│  │ Storage     │ 1TB NVMe SSD                  │   │
│  │ Graphics    │ Intel Iris Xe                 │   │
│  │ Display     │ 14" WUXGA IPS                 │   │
│  │ Weight      │ 1.2 kg                         │   │
│  │ Battery     │ Up to 15 hours                 │   │
│  └──────────────────────────────────────────────┘   │
│                                                       │
│  ★ Kelebihan                                          │
│  • Performa tinggi untuk multitasking                 │
│  • Build quality premium (carbon fiber)              │
│  • Battery tahan 15 jam                              │
│                                                       │
│  ★ Kekurangan                                          │
│  • Harga premium                                     │
│  • Tidak ada dedicated GPU                           │
│  • Port terbatas                                     │
│                                                       │
│  ★ Variants                                           │
│  ┌──────────────┬────────────────┬────────┐         │
│  │ Name         │ Price Modifier │ Stock  │         │
│  ├──────────────┼────────────────┼────────┤         │
│  │ Standard     │ Rp 0           │ 5      │         │
│  │ Pro          │ +Rp 2.000.000  │ 2      │         │
│  └──────────────┴────────────────┴────────┘         │
│                                                       │
│  ★ Description                                        │
│  "A premium ultrabook for professionals..."           │
│                                                       │
│  [← Back to Laptops]                                  │
└───────────────────────────────────────────────────────┘
```

---

### Modul C: Fixes & Polish

#### C.1 Nav Role Fix
**File**: `resources/views/components/landing-nav.blade.php`
- `@can('admin')` → `@role('admin')` (Spatie)
- `@endcan` → `@endrole`

#### C.2 Rupiah Currency (Semua Halaman)
Ubah semua display harga dari `$` (USD) ke `Rp` (Rupiah):

| Sebelum | Sesudah |
|---------|---------|
| `${{ number_format($price, 2) }}` | `Rp {{ number_format($price, 0, ',', '.') }}` |
| `${{ number_format($laptop->price, 0) }}` | `Rp {{ number_format($laptop->price, 0, ',', '.') }}` |

#### C.3 Default Image
Tambahkan default image di setiap tempat yang menampilkan gambar produk:

**Strategi**: Fallback ke placeholder service dengan warna brand.
```blade
$laptop->image_url ?? 'https://placehold.co/600x400/DF5E1D/FFFFFF?text=ZLM'
```

**File yang perlu diubah**:
- `landing/home.blade.php` — featured products image
- `landing/search.blade.php` — search results image
- `landing/detail.blade.php` — detail main image
- `admin/laptops/show.blade.php` — detail admin image (new)

---

### Modul D: Kelebihan & Kekurangan (NEW)

#### D.1 Migration — Tambah Kolom ke Tabel laptops
**File baru**: `database/migrations/YYYY_MM_DD_HHMMSS_add_kelebihan_kekurangan_to_laptops_table.php`
```php
Schema::table('laptops', function (Blueprint $table) {
    $table->text('kelebihan')->nullable()->after('image_url');
    $table->text('kekurangan')->nullable()->after('kelebihan');
});
```

#### D.2 Model Update
**File**: `app/Models/Laptop.php`
- Tambah `'kelebihan', 'kekurangan'` ke `$fillable`

#### D.3 Controller Update
**File**: `app/Http/Controllers/Admin/LaptopController.php`
- Tambah `'kelebihan' => 'nullable|string'` ke validasi store & update
- Tambah `'kekurangan' => 'nullable|string'` ke validasi store & update

#### D.4 Admin Form — Create & Edit
**File**: `resources/views/admin/laptops/create.blade.php`, `edit.blade.php`
- Ubah Description dari `<textarea>` jadi **Trix Editor** (WYSIWYG)
- Tambah field **Kelebihan** — **Trix Editor** (WYSIWYG)
- Tambah field **Kekurangan** — **Trix Editor** (WYSIWYG)
- Push Trix CSS & JS via `@push('scripts')` dan `@push('styles')`

#### D.5 Admin Detail View
**File**: `resources/views/admin/laptops/show.blade.php` (BARU)
- Tampilkan kelebihan sebagai bullet list
- Tampilkan kekurangan sebagai bullet list

#### D.6 Landing Detail View
**File**: `resources/views/landing/detail.blade.php`
- Tambah section "Kelebihan & Kekurangan" setelah specs table
- Split layout: ✅ kiri / ❌ kanan
- Parsing data: `{!! nl2br(e($laptop->kelebihan)) !!}` — output HTML dari Trix

#### D.7 Text Editor — Trix Integration
**Pilihan Editor**: **Trix** (by Basecamp) — ringan, tanpa API key, output HTML bersih.

**CDN**:
```html
<link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
```

**Cara Pakai di Blade**:
```blade
{{-- Hidden input untuk submit value --}}
<input id="description" name="description" type="hidden" value="{{ old('description', $laptop->description ?? '') }}">

{{-- Trix editor UI --}}
<trix-editor input="description" class="trix-content"></trix-editor>
```

**File yang diubah**:
- `admin/laptops/create.blade.php` — Description, Kelebihan, Kekurangan jadi Trix
- `admin/laptops/edit.blade.php` — Description, Kelebihan, Kekurangan jadi Trix

---

## Dependency Graph
```
D.1 (Migration) ──┐
D.2 (Model)     ──┤
                  ├──→ depends on: Nothing, independent
D.4 (Form + Trix) ──┼──→ depends on: D.1, D.2, D.3
D.5 (Show view)   ──┤
D.6 (Landing)     ──┘
D.7 (Trix Editor) ──┴──→ depends on: —, applied in D.4

B.3 (Admin Detail View)
  └── depends on: B.2 (Admin Product exists), D.5

C.2 (Rupiah)
  └── affects: A.1, A.2, A.3, B.2, B.3

C.3 (Default Image)
  └── affects: A.1, A.2, A.3, B.3
```

## Definisi Selesai
- ✅ Semua harga ditampilkan dalam format **Rp X.XXX.XXX**
- ✅ Produk tanpa gambar menampilkan **default placeholder** (tidak broken image)
- ✅ Admin product detail **menampilkan semua field + variants + categories + kelebihan + kekurangan**
- ✅ Landing detail **menampilkan kelebihan & kekurangan** di section terpisah
- ✅ Admin create/edit **bisa input kelebihan & kekurangan**
- ✅ Navigasi admin di landing **hanya muncul untuk user role admin**
- ✅ Nama produk di admin index **bisa diklik ke detail page**
- ✅ Semua halaman landing **tidak ada broken link/error**

## Urutan Pengerjaan
1. **D.1** Buat migration `add_kelebihan_kekurangan_to_laptops_table`
2. **D.2** Update Laptop model fillable
3. **D.3** Update Admin/LaptopController validasi
4. **D.7 + D.4** Integrasi Trix Editor + tambah field kelebihan/kekurangan di admin create & edit
5. **C.2** Ubah currency USD → Rupiah di semua landing & admin views
6. **C.3** Tambah default image fallback di semua views
7. **C.1** Fix nav role directive
8. **B.3 + route** Build admin product detail view (includes D.5, tampilkan HTML)
9. **D.6** Tambah kelebihan/kekurangan di landing detail (render HTML dari Trix)
10. **C.2 link** Tambah link nama produk → detail di admin index
11. **Review** All pages
