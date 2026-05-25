# Spec: Kelebihan & Kekurangan Product (Modul D)

## Tujuan
Tambah field `kelebihan` (advantages) dan `kekurangan` (disadvantages) ke setiap produk laptop, tampil di landing detail dan admin.

---

## D.1 Migration

**File baru**: `database/migrations/[timestamp]_add_kelebihan_kekurangan_to_laptops_table.php`

```php
Schema::table('laptops', function (Blueprint $table) {
    $table->text('kelebihan')->nullable()->after('image_url');
    $table->text('kekurangan')->nullable()->after('kelebihan');
});
```

## D.2 Model

**File**: `app/Models/Laptop.php`
Tambahkan ke `$fillable`:
```php
'kelebihan',
'kekurangan',
```

## D.3 Controller

**File**: `app/Http/Controllers/Admin/LaptopController.php`

**Store validation** — tambah:
```php
'kelebihan' => 'nullable|string',
'kekurangan' => 'nullable|string',
```

**Update validation** — tambah:
```php
'kelebihan' => 'nullable|string',
'kekurangan' => 'nullable|string',
```

## D.4 Admin Form (Create & Edit) — dengan Trix Editor

Semua text field yang panjang (Description, Kelebihan, Kekurangan) menggunakan **Trix Editor** (WYSIWYG).

### Setup Trix (CDN)
Di `create.blade.php` dan `edit.blade.php`, push ke stack:
```blade
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<style>
  trix-editor { min-height: 200px; }
  trix-toolbar .trix-button-group { margin-bottom: 0; }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endpush
```

### Penggunaan di Form
```blade
{{-- Description --}}
<label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
<input id="description" name="description" type="hidden" value="{{ old('description', $laptop->description ?? '') }}">
<trix-editor input="description" class="trix-content"></trix-editor>
```

### Form Layout — Tambah setelah Image URL (sebelum Categories):
```
┌──────────────────────────────────────────┐
│ Description (Trix Editor — WYSIWYG)      │
│ ┌──────────────────────────────────────┐│
│ │        Bold / Italic / List / Link   ││
│ │        [toolbar]                     ││
│ ├──────────────────────────────────────┤│
│ │  Rich text content...               ││
│ │                                      ││
│ └──────────────────────────────────────┘│
│                                          │
│ Kelebihan (Trix Editor)                 │
│ ┌──────────────────────────────────────┐│
│ │  • Performa tinggi                   ││
│ │  • Build quality premium             ││
│ │  • Battery tahan lama                ││
│ └──────────────────────────────────────┘│
│                                          │
│ Kekurangan (Trix Editor)                │
│ ┌──────────────────────────────────────┐│
│ │  • Harga premium                     ││
│ │  • Tidak ada GPU dedicated           ││
│ └──────────────────────────────────────┘│
└──────────────────────────────────────────┘
```

### Edit (`admin/laptops/edit.blade.php`)
Same pattern, gunakan `old('field', $laptop->field)`.

## D.5 Admin Detail View

**File**: `resources/views/admin/laptops/show.blade.php` (BARU)
Tampilkan setelah Technical Specifications:

```
★ Kelebihan
• Performa tinggi untuk multitasking
• Build quality premium (carbon fiber)
• Battery tahan 15 jam

★ Kekurangan
• Harga premium
• Tidak ada dedicated GPU
• Port terbatas
```

Render HTML langsung dari Trix:
```blade
<div class="prose prose-sm max-w-none">
    {!! $laptop->kelebihan !!}
</div>
```

## D.6 Landing Detail

**File**: `resources/views/landing/detail.blade.php`
Tambah setelah Technical Specifications table:

```
★ Kelebihan & Kekurangan
┌───────────────┬──────────────────────┐
│ ✅ KELEBIHAN  │ ❌ KEKURANGAN        │
│               │                      │
│ • Performa    │ • Harga mahal        │
│ • Build bagus │ • Berat             │
│ • Layar       │ • Fan noise         │
└───────────────┴──────────────────────┘
```

Gunakan 2-column grid layout.
Parse: `collect(explode("\n", $laptop->kelebihan))->filter()` untuk loop.

**Catatan**: Karena data disimpan sebagai HTML (dari Trix Editor), output langsung dengan `{!! !!}` untuk render HTML.
Gunakan `{!! nl2br(e($laptop->kelebihan)) !!}` atau `{!! $laptop->kelebihan !!}` untuk render bullet list / rich text.

**Desain**:
```blade
@if ($laptop->kelebihan || $laptop->kekurangan)
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 my-12">
    @if ($laptop->kelebihan)
    <div class="bg-emerald-50/50 rounded-2xl border border-emerald-200/60 p-6">
        <h3 class="font-semibold text-emerald-800 mb-3 flex items-center gap-2 text-lg">
            ✅ Kelebihan
        </h3>
        <div class="prose prose-sm max-w-none text-gray-700">
            {!! $laptop->kelebihan !!}
        </div>
    </div>
    @endif
    @if ($laptop->kekurangan)
    <div class="bg-rose-50/50 rounded-2xl border border-rose-200/60 p-6">
        <h3 class="font-semibold text-rose-800 mb-3 flex items-center gap-2 text-lg">
            ❌ Kekurangan
        </h3>
        <div class="prose prose-sm max-w-none text-gray-700">
            {!! $laptop->kekurangan !!}
        </div>
    </div>
    @endif
</div>
@endif
```
