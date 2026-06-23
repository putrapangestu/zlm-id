# Spec Modul E — Fix Compare Modal "Add to Compare"

## Problem
Modal "Pilih Produk" di halaman compare tidak berfungsi dengan benar karena:
1. `loadCompareProducts()` membaca existing IDs dari localStorage, bukan dari session → produk yang sudah ditambahkan masih muncul sebagai available
2. `addCompareFromModal()` masih memanipulasi localStorage yang tidak diperlukan

## Solusi

### 1. Rewrite `loadCompareProducts()` — Baca existing IDs dari Server

```javascript
async function loadCompareProducts(search) {
    const list = document.getElementById('compareProductList');
    list.innerHTML = '<div class="text-center py-8 text-sm text-gray-400">Memuat produk...</div>';
    
    try {
        // Step 1: Dapatkan existing compare IDs dari session
        const idsRes = await fetch('{{ route('compare.ids') }}');
        const idsData = await idsRes.json();
        const existingIds = (idsData.ids || []).map(String);
        
        // Step 2: Fetch products
        let url = '{{ route('compare.products') }}';
        if (search) url += '?search=' + encodeURIComponent(search);
        
        const res = await fetch(url);
        const data = await res.json();
        
        if (!data.products || data.products.length === 0) {
            list.innerHTML = '<div class="text-center py-8 text-sm text-gray-400">Produk tidak ditemukan.</div>';
            return;
        }
        
        // Step 3: Render — disable yang sudah ada di compare
        list.innerHTML = data.products.map(p => {
            const productId = String(p.id);
            const disabled = existingIds.includes(productId);
            
            return '<div class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors ' + 
                   (disabled ? 'opacity-50' : 'cursor-pointer') + '" ' + 
                   (disabled ? '' : 'onclick="addCompareFromModal(\'' + p.id + '\', \'' + 
                   (p.name || '').replace(/'/g, "\\'") + '\', \'' + (p.image_url_full || '') + '\')"') + '>' +
                   // ... render card
                   '</div>';
        }).join('');
        
    } catch (e) {
        list.innerHTML = '<div class="text-center py-8 text-sm text-red-400">Gagal memuat produk.</div>';
    }
}
```

### 2. Bersihkan `addCompareFromModal()` — Hapus localStorage

```javascript
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
            location.reload();  // Refresh halaman untuk menampilkan produk baru
        } else {
            showToast(res.message, 'info');
        }
    })
    .catch(() => showToast('Gagal menambahkan produk', 'error'));
}
```

### 3. Hapus Variabel Global `compareModalOpen`

Tidak diperlukan lagi — bisa dihapus atau dibiarkan.

### 4. Update Fungsi `openCompareModal()`

```javascript
function openCompareModal() {
    document.getElementById('compareModal').classList.remove('hidden');
    document.getElementById('compareSearchInput').value = '';
    loadCompareProducts('');
}
```

## Files Changed

| File | Action |
|------|--------|
| `resources/views/landing/compare.blade.php` | MODIFY (loadCompareProducts, addCompareFromModal) |

## Testing

- Buka /compare dengan 2 produk sudah ditambahkan
- Klik + Tambah Produk
- Modal muncul, produk yang sudah ada di compare tampil disabled dengan label "Sudah ditambahkan"
- Klik produk yang available → berhasil ditambahkan, page reload
- Cari produk di search input → hasil filter sesuai
- Tambah sampai 3 produk → coba tambah lagi → dapat pesan "Maksimal 3 produk"
