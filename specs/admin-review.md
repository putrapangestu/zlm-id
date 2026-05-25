# Spec: Admin Pages Review (B.1, B.2)

## Tujuan
Review halaman admin category dan product yang sudah ada.

## B.1 Admin Category
- **Files**:
  - `resources/views/admin/categories/index.blade.php`
  - `resources/views/admin/categories/create.blade.php`
  - `resources/views/admin/categories/edit.blade.php`
- **Controller**: `Admin\CategoryController`
- **Checklist**:
  - [ ] Index: category list with laptops_count
  - [ ] Create: form validasi berfungsi
  - [ ] Edit: form populated with existing data
  - [ ] Delete: konfirmasi bekerja
  - [ ] Success flash message tampil

## B.2 Admin Product (Laptop)
- **Files**:
  - `resources/views/admin/laptops/index.blade.php`
  - `resources/views/admin/laptops/create.blade.php`
  - `resources/views/admin/laptops/edit.blade.php`
  - *(New) `resources/views/admin/laptops/show.blade.php`*
- **Controller**: `Admin\LaptopController`
- **Checklist**:
  - [ ] Index: product list with categories, price, stock
  - [ ] Create: all fields validated
  - [ ] Edit: existing data populated
  - [ ] Delete: konfirmasi bekerja
  - [ ] Variants link works (`admin.laptops.variants.index`)
  - [ ] NEW: Show page link dari product name
