# Spec Modul C — Perbaikan Compare Feature

## Problem

1. **`removeFromCompare()` tidak didefinisikan** — Tombol X (hapus) di compare page (line 79 compare.blade.php) memanggil fungsi yang tidak ada → error JS, tombol tidak berfungsi.
2. **Dual storage (session + localStorage)** — Compare data disimpan di session (server) DAN localStorage (client). Keduanya bisa out-of-sync, menyebabkan bug di modal (produk yang sudah ditambahkan masih muncul).
3. **Tombol Compare di detail page tidak perlu** — User ingin compare hanya muncul di halaman search/katalog dan halaman compare, bukan di detail product.
4. **Floating compare widget tidak konsisten** — Kadang muncul/kadang tidak karena baca dari session sementara localStorage punya data sendiri.

## Solusi

### 1. Hapus localStorage untuk Compare (Ganti dengan Session)

Di `compare.blade.php`:

**`loadCompareProducts()` — line 198:**
```javascript
// SEBELUM (SALAH):
const compareIds = JSON.parse(localStorage.getItem('laptopsToCompare') || '[]').map(p => String(p.id));

// SESUDAH (BENAR):
const compareIds = await fetch('{{ route('compare.ids') }}').then(r => r.json()).then(d => d.ids || []);
```

TAPI karena `loadCompareProducts()` sudah async (pake fetch), lebih baik fetch `/compare/ids` dulu sebelum fetch products:

```javascript
async function loadCompareProducts(search) {
    const list = document.getElementById('compareProductList');
    list.innerHTML = '<div class="text-center py-8 text-sm text-gray-400">Memuat produk...</div>';
    
    try {
        // 1. Dapatkan IDs yang sudah ada di session
        const idsRes = await fetch('{{ route('compare.ids') }}');
        const idsData = await idsRes.json();
        const existingIds = idsData.ids || [];
        
        // 2. Fetch products
        let url = '{{ route('compare.products') }}';
        if (search) url += '?search=' + encodeURIComponent(search);
        
        const res = await fetch(url);
        const data = await res.json();
        
        // 3. Filter + render
        // ... render, filter berdasarkan existingIds
    } catch (e) {
        list.innerHTML = '<div class="text-center py-8 text-sm text-red-400">Gagal memuat produk.</div>';
    }
}
```

**`addCompareFromModal()` — line 229-249:**
```javascript
// Hapus semua manipulasi localStorage
function addCompareFromModal(id, name, image) {
    fetch('{{ route('compare.add') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ laptop_id: id }),
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showToast(res.message, 'success');
            closeCompareModal();
            location.reload();
        } else {
            showToast(res.message, 'info');
        }
    })
    .catch(() => showToast('Gagal menambahkan produk', 'error'));
}
```

### 2. Tambah Fungsi `removeFromCompare()`

Di `compare.blade.php` (dalam tag `<script>`):

```javascript
function removeFromCompare(laptopId) {
    fetch('{{ route('compare.remove', '') }}/' + laptopId, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            showToast(res.message, 'success');
            location.reload();
        }
    })
    .catch(() => showToast('Gagal menghapus produk', 'error'));
}
```

### 3. Hapus Tombol Compare dari Detail Page

Di `detail.blade.php` — Hapus button Compare:
```blade
{{-- HAPUS block ini --}}
<button type="button" onclick="addToCompare('{{ $laptop->id }}')" class="...">
    ...
    <span>Compare</span>
</button>
```

### 4. Update Fungsi `addToCompare` Global

Pastikan fungsi `addToCompare` hanya tersedia di halaman yang memiliki tombol compare (home, search), pindahkan dari detail.blade.js.

### 5. Floating Compare Widget

Tetap menggunakan session (sudah benar). Tidak perlu perubahan.

## Files Changed

| File | Action |
|------|--------|
| `resources/views/landing/compare.blade.php` | MODIFY (hapus localStorage, tambah removeFromCompare) |
| `resources/views/landing/detail.blade.php` | MODIFY (hapus tombol Compare) |
| `resources/views/landing/home.blade.php` | MODIFY (pastikan addToCompare ada) |
| `resources/views/landing/search.blade.php` | MODIFY (pastikan addToCompare ada) |

## Testing

- Buka /compare → klik X pada produk → produk terhapus, page reload
- Buka /compare → klik + Tambah Produk → modal muncul
- Cari produk di modal → produk yang sudah ada di compare harus di-disabled
- Tambah produk dari modal → berhasil, page reload
- Buka detail page → tombol Compare tidak ada
- Buka search/home → tombol Compare ada dan berfungsi
- Floating widget muncul dengan count yang benar
