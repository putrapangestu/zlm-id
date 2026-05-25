# Spec: Landing Pages Review (A.1, A.2, A.3)

## Tujuan
Review halaman landing yang sudah ada untuk memastikan tidak ada broken link, error, atau masalah tampilan.

## A.1 Landing Home
- **File**: `resources/views/landing/home.blade.php`
- **Checklist**:
  - [ ] Hero section renders correctly
  - [ ] Featured products loop (`$featured`) tidak error
  - [ ] Categories loop (`$categories`) tidak error
  - [ ] All icons load dari Iconify
  - [ ] All internal links (route names) valid
  - [ ] Wishlist/Compare JavaScript functions work
  - [ ] Newsletter form tidak broken

## A.2 Landing Katalog (Search)
- **File**: `resources/views/landing/search.blade.php`
- **Checklist**:
  - [ ] Filter form submits correctly
  - [ ] Category radio buttons work
  - [ ] Brand dropdown works
  - [ ] Price range inputs work
  - [ ] Product grid renders with pagination
  - [ ] Empty state shown when no results
  - [ ] Compare floating card JavaScript works
  - [ ] All buttons/linked correctly

## A.3 Landing Detail
- **File**: `resources/views/landing/detail.blade.php`
- **Checklist**:
  - [ ] Image gallery with lightbox works
  - [ ] Technical specs table renders
  - [ ] Variant selection works (JavaScript)
  - [ ] Add to Cart form submits correctly
  - [ ] Wishlist button toggles
  - [ ] Compare button works
  - [ ] Similar products section renders
  - [ ] Breadcrumb navigation correct
