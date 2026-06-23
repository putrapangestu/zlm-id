# Spec Modul A — Multi-Image Gallery untuk Laptop

## Problem
Laptop cuma punya 1 field `image_url`. Detail page pake 3 gambar Unsplash hardcoded sebagai "supplemental images". User ingin bisa upload banyak gambar per laptop.

## Solusi

### 1. Migration Baru: `create_laptop_images_table`

```sql
- id (uuid) PK
- laptop_id (uuid) FK → laptops.id (cascade on delete)
- image_url (string) — path ke storage
- sort_order (integer) — default 0
- caption (string, nullable)
- timestamps
```

### 2. Model: `App\Models\LaptopImage`

- `$fillable`: laptop_id, image_url, sort_order, caption
- `$casts`: sort_order => integer
- Relasi: `belongsTo Laptop`

### 3. Update Model `Laptop`

- Hapus `$appends = ['image_url_full']` — pindah jadi method biasa
- Ubah accessor `getImageUrlFullAttribute()` jadi method `getImageUrlFull()` atau biarkan saja
- **Tambahkan**:
  ```php
  public function images(): HasMany
  {
      return $this->hasMany(LaptopImage::class)->orderBy('sort_order');
  }
  ```
- **Tambahkan** accessor untuk first image:
  ```php
  public function getThumbnailAttribute(): ?string
  {
      $first = $this->images()->first();
      return $first ? $first->image_url_full : ($this->image_url ? Storage::url($this->image_url) : null);
  }
  ```
  (Ini untuk kompatibilitas ke belakang)

### 4. Update Admin LaptopController

- `store()`: setelah create laptop, handle multiple image upload
  - Terima field `images[]` (array of files)
  - Simpan ke `storage/app/public/laptops/`
  - Simpan path ke `laptop_images` table
  - **PENTING**: `image_url` di laptops table tetap diisi dari gambar pertama (untuk backward compat)

- `update()`: handle:
  - Upload gambar baru (append)
  - Delete gambar existing (via checkbox/hidden input `delete_images[]`)
  - Reorder (via hidden input `image_order[]`)

- `destroy()`: delete semua gambar related

### 5. Admin View — Multi Image Upload

Di `resources/views/admin/laptops/create.blade.php` dan `edit.blade.php`:

- Ganti single image upload dengan multi-image upload component
- Pakai drag-and-drop dropzone (bisa reuse `_image_upload.blade.php` yang sudah ada, tapi modif untuk multiple)
- Atau buat component baru `_multi_image_upload.blade.php`
- Setiap gambar yang sudah diupload menampilkan preview dengan tombol hapus

### 6. Detail View — Dynamic Gallery

Di `resources/views/landing/detail.blade.php`:

- Ganti 3 hardcoded Unsplash images dengan:
  ```blade
  @foreach ($laptop->images as $image)
      <div class="aspect-square ...">
          <img src="{{ Storage::url($image->image_url) }}" alt="...">
      </div>
  @endforeach
  ```
- Jika tidak ada images, tampilkan placeholder
- Main image: pakai `$laptop->image_url_full` (tetap pakai field `image_url` dari laptop untuk default/main image)

### 7. Backward Compatibility

- Field `image_url` di laptops table tetap ada dan diisi
- `image_url_full` accessor tetap bisa dipakai
- Laptop yang tidak punya multiple images tetap berfungsi normal

## Files Changed

| File | Action |
|------|--------|
| `database/migrations/xxxx_create_laptop_images_table.php` | CREATE |
| `app/Models/LaptopImage.php` | CREATE |
| `app/Models/Laptop.php` | MODIFY (tambah relasi, hapus append) |
| `app/Http/Controllers/Admin/LaptopController.php` | MODIFY (multi-upload) |
| `resources/views/admin/laptops/create.blade.php` | MODIFY (multi-image upload) |
| `resources/views/admin/laptops/edit.blade.php` | MODIFY (multi-image upload) |
| `resources/views/admin/variants/_multi_image_upload.blade.php` | CREATE |
| `resources/views/landing/detail.blade.php` | MODIFY (dynamic gallery) |
| `resources/views/landing/search.blade.php` | MODIFY (pakai thumbnail) |
| `resources/views/landing/home.blade.php` | MODIFY (pakai thumbnail) |

## Testing

- Upload 3 gambar via admin → muncul di detail page
- Hapus 1 gambar di admin → hanya 2 tersisa di detail
- Laptop tanpa gambar → placeholder muncul
- Admin bisa reorder gambar
