# Spec: Nav Role Fix (C.1)

## Tujuan
Mengganti `@can('admin')` dengan `@role('admin')` di landing-nav.blade.php karena project menggunakan Spatie Permission (bukan Laravel Gate).

## File
- `resources/views/components/landing-nav.blade.php`

## Perubahan
- Line 58: `@can('admin')` → `@role('admin')`
- Line 64: `@endcan` → `@endrole`
