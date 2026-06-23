# TESTI-1: Halaman Testimoni + CRUD

## Tujuan
Testimoni pelanggan bisa dikelola admin, ditampilkan secara dinamis di landing page, dan memiliki halaman khusus.

## Implementasi

### 1. Migration: `database/migrations/xxxx_create_testimonials_table.php`

```php
Schema::create('testimonials', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('name');
    $table->string('position')->nullable(); // Pekerjaan/jabatan
    $table->text('content'); // Isi testimoni
    $table->unsignedTinyInteger('rating')->default(5); // 1-5
    $table->string('photo')->nullable(); // Foto orang
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 2. Model: `app/Models/Testimonial.php`

### 3. Controller: `app/Http/Controllers/Admin/TestimonialController.php`
- `index()` — List testimoni
- `create()` — Form create
- `store(Request)` — Simpan testimoni
- `edit(Testimonial)` — Form edit
- `update(Request, Testimonial)` — Update
- `destroy(Testimonial)` — Delete

### 4. Public Controller: (optional, bisa langsung query di view)
Atau tambahkan method `testimonials()` di `LaptopController` / controller terpisah.

### 5. Routes
```php
// Public
Route::get('/testimonials', ...)->name('landing.testimonials');

// Admin
Route::resource('testimonials', TestimonialController::class);
```

### 6. Admin Views
- `admin/testimonials/index.blade.php` — Table dengan status toggle
- `admin/testimonials/create.blade.php` — Form
- `admin/testimonials/edit.blade.php` — Form edit

### 7. Public Views
- `landing/testimonials.blade.php` — Full testimonial page

### 8. Update Landing Page
`resources/views/landing/home.blade.php`
Ganti testimonial static dengan data dari database:
```php
$testimonials = Testimonial::where('is_active', true)->latest()->take(3)->get();
```

### 9. Admin Sidebar
Tambah link "Testimonials" di sidebar admin.

## Definisi Selesai
- [x] Migration + Model Testimonial
- [x] Admin CRUD testimonial
- [x] Halaman publik `/testimonials`
- [x] Landing page testimoni dinamis dari database
- [x] Star rating (1-5)
- [x] Status active/inactive
