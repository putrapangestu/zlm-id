# Spesifikasi Teknis: Modul H — Compare Landing Integration

## Deskripsi
Mengintegrasikan fitur compare (session-based dari Modul F) ke landing pages: home (`landing/home.blade.php`) dan katalog (`landing/search.blade.php`). Menambahkan floating compare widget fixed bottom-right, tombol compare AJAX di setiap product card, badge counter, dan toast notification.

## Prasyarat
Modul H **bergantung penuh** pada Modul F (CompareController + session system). Harus dikerjakan setelah Modul F selesai.

## File yang Dibuat
- `resources/views/components/floating-compare.blade.php` — BARU

## File yang Dimodifikasi

| File | Perubahan |
|------|-----------|
| `resources/views/landing/home.blade.php` | Ganti `addToCompare()` localStorage → AJAX, include floating widget |
| `resources/views/landing/search.blade.php` | Ganti tombol compare → AJAX, include floating widget |
| `public/js/compare.js` | AJAX handlers untuk add/remove/clear, update widget, toast |
| `resources/views/layouts/landing.blade.php` | Include `compare.js` di footer |

---

## Komponen: Floating Compare Widget

### File: `resources/views/components/floating-compare.blade.php`

**Posisi**: Fixed bottom-right, z-50, hanya muncul jika ada item di session compare.

```
                    ┌──────────────────────────┐
                    │  ⚖ 2 dari 3              │
                    │  [View Compare] [× Clear] │
                    └──────────────────────────┘
```

**Kondisi**:
- Sembunyi jika `count === 0` (class `hidden` atau `opacity-0 pointer-events-none`)
- Muncul dengan animasi slide-up jika `count > 0`
- Badge bulat merah menampilkan jumlah item

**Data**:
| Elemen | Sumber | Keterangan |
|--------|--------|------------|
| Badge count | `session('compare') count` | Via inline PHP di view |
| Link View | `route('landing.compare')` | Navigasi ke halaman compare |
| Clear button | `onclick="clearCompare()"` | AJAX call |

**Layout**:
```blade
@php $compareCount = count(session('compare', [])); @endphp

<div id="floating-compare"
     class="fixed bottom-6 right-6 z-50 {{ $compareCount > 0 ? '' : 'hidden' }} transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-200 p-4">
        <div class="flex items-center gap-3">
            <div class="relative">
                <iconify-icon icon="solar:scale-linear" class="text-2xl text-[#363230]"></iconify-icon>
                <span id="compare-badge"
                      class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">
                    {{ $compareCount }}
                </span>
            </div>
            <div>
                <p class="text-sm font-medium text-[#363230]">Compare <span id="compare-count">{{ $compareCount }}</span>/3</p>
                <div class="flex gap-2 mt-1">
                    <a href="{{ route('landing.compare') }}"
                       class="text-xs font-medium text-[#DF5E1D] hover:text-[#c45218] transition-colors">
                        View
                    </a>
                    <button onclick="clearCompare()"
                            class="text-xs font-medium text-gray-400 hover:text-red-500 transition-colors">
                        Clear
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
```

---

## Product Card — Tombol Compare

### Di Home & Search

Tombol compare yang sudah ada diubah dari localStorage call ke AJAX call:

**Before** (home.blade.php:105):
```html
<button onclick="addToCompare('{{ $laptop->id }}', '{{ $laptop->name }}', '{{ $laptop->image_url }}')"
        class="w-9 h-9 rounded-lg border ...">
    <iconify-icon icon="solar:scale-linear"></iconify-icon>
</button>
```

**After**:
```html
<button onclick="addToCompare('{{ $laptop->id }}')"
        data-compare-btn
        data-laptop-id="{{ $laptop->id }}"
        class="compare-btn w-9 h-9 rounded-lg border border-gray-200 text-gray-600 flex items-center justify-center hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all"
        title="Compare">
    <iconify-icon icon="solar:scale-linear" class="text-lg"></iconify-icon>
</button>
```

**State visual**: Jika laptop sudah ada di session compare, tombol di-highlight (bg-blue-50, text-blue-600, border-blue-200). Ini di-set oleh JavaScript saat halaman dimuat.

---

## JavaScript: `public/js/compare.js`

### Fungsi Utama

#### `addToCompare(laptopId)`
```js
function addToCompare(laptopId) {
    fetch('/compare/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ laptop_id: laptopId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            updateFloatingCompare(data.count);
            updateCompareButtons();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(() => showToast('Gagal terhubung ke server', 'error'));
}
```

#### `removeFromCompare(laptopId)`
```js
function removeFromCompare(laptopId) {
    fetch(`/compare/remove/${laptopId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            updateFloatingCompare(data.count);
            updateCompareButtons();
            // Jika di halaman compare, reload
            if (window.location.pathname === '/compare') location.reload();
        }
    });
}
```

#### `clearCompare()`
```js
function clearCompare() {
    fetch('/compare/clear', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken } })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            updateFloatingCompare(0);
            updateCompareButtons();
            if (window.location.pathname === '/compare') location.reload();
        }
    });
}
```

#### `updateFloatingCompare(count)`
```js
function updateFloatingCompare(count) {
    const widget = document.getElementById('floating-compare');
    const badge = document.getElementById('compare-badge');
    const countText = document.getElementById('compare-count');

    if (!widget) return;

    if (count > 0) {
        widget.classList.remove('hidden');
        if (badge) badge.textContent = count;
        if (countText) countText.textContent = count;
    } else {
        widget.classList.add('hidden');
    }
}
```

#### `updateCompareButtons()`
```js
function updateCompareButtons() {
    // GET daftar compare IDs via AJAX atau baca dari session via embedded data
    fetch('/compare/ids')
    .then(res => res.json())
    .then(data => {
        document.querySelectorAll('[data-compare-btn]').forEach(btn => {
            const id = btn.dataset.laptopId;
            if (data.ids.includes(id)) {
                btn.classList.add('bg-blue-50', 'text-blue-600', 'border-blue-200');
            } else {
                btn.classList.remove('bg-blue-50', 'text-blue-600', 'border-blue-200');
            }
        });
    });
}
```

> **Note**: Perlu tambahan endpoint `GET /compare/ids` di CompareController yang return `{ids: [...]}`.

#### `showToast(message, type)`
```js
// Sudah ada di home.blade.php — pindahkan ke compare.js
function showToast(message, type = 'info') {
    const colors = { success: 'bg-emerald-500', error: 'bg-red-500', info: 'bg-blue-500' };
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 ${colors[type] || colors.info} text-white px-5 py-3 rounded-xl text-sm font-medium shadow-lg z-[100] transition-all duration-300`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
```

### Event Initialization
```js
document.addEventListener('DOMContentLoaded', () => {
    updateCompareButtons();
    // Hapus localStorage compare jika ada migrasi
    localStorage.removeItem('laptopsToCompare');
});
```

---

## Layout Update

### `layouts/landing.blade.php`
```blade
{{-- Di bagian head --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Di bagian footer, sebelum </body> --}}
@push('scripts')
    <script src="{{ asset('js/compare.js') }}"></script>
@endpush
```

### `landing/home.blade.php`
- Hapus semua fungsi JavaScript yang sudah dipindahkan ke `compare.js`:
  - `getCompareList()`, `saveCompareList()`, `addToCompare()`, `removeFromCompare()`, `clearAllCompare()`, `showToast()`, `COMPARE_STORAGE_KEY`
  - Variabel `WISHLIST_STORAGE_KEY` dan fungsi wishlist TETAP di home blade (kecuali jika dipisah nanti)
- Tambah `@include('components.floating-compare')` di akhir content
- Update tombol compare: `onclick` → `addToCompare('{{ $laptop->id }}')` (tanpa parameter name/image)

### `landing/search.blade.php`
- Cari tombol scale/compare di product card, update ke AJAX call yang sama
- Tambah `@include('components.floating-compare')` di akhir content

---

## Data Flow Diagram

```
User klik ⚖ di product card
       │
       ▼
compare.js: addToCompare(laptopId)
       │
       ▼
POST /compare/add  { laptop_id: "uuid" }
       │
       ▼
CompareController@add
   ├── Validasi: laptop exists
   ├── Cek max 3 items
   ├── Cek duplicate
   ├── Push ke session('compare')
   └── Return JSON
       │
       ▼
compare.js: response handler
   ├── if success → showToast + updateFloatingCompare(count) + updateCompareButtons()
   └── if error → showToast(message, 'error')
```

---

## Dependency Graph
```
Modul H (Landing Widget)
   ├── H.1 Floating compare component (baru)
   ├── H.2 Home page (update tombol + include widget)
   ├── H.3 Search page (update tombol + include widget)
   ├── H.4 compare.js (AJAX + widget update + toast)
   └── H.5 Layout (include script)
       │
       └──── DEPENDS ON ──── Modul F (CompareController + session)
```

**Dependencies**:
- **WAJIB**: Modul F selesai (CompareController, routes, session system)
- Tidak bergantung pada Modul E, G

## States & Edge Cases

| State | Skenario | Perilaku |
|-------|----------|----------|
| Empty | Belum ada item compare | Widget hidden, tombol di product card normal |
| Active | Ada 1-3 item | Widget muncul dengan badge count, tombol item terpilih di-highlight |
| Max | 3 item sudah penuh, klik tombol baru | Toast error "Maksimal 3 produk" |
| Duplicate | Klik tombol item yang sudah di compare | Toast error "Produk sudah ada" |
| Remove | Klik tombol item yang sudah dipilih | Hapus dari session, update widget |
| Clear | Klik "Clear" di widget | Hapus semua, widget hidden |
| Migrasi | User punya localStorage lama | Hapus localStorage key setelah first load |
| Halaman Compare | Klik "View" dari widget | Navigasi ke `/compare` |
| 404 | Laptop dihapus dari DB | CompareController@add return 404 |

## Urutan Pengerjaan
1. Pindahkan `showToast()` dan fungsi compare dari `home.blade.php` ke `public/js/compare.js`
2. Buat komponen `floating-compare.blade.php`
3. Tambah `GET /compare/ids` endpoint di CompareController
4. Update `layouts/landing.blade.php` — include compare.js
5. Update `landing/home.blade.php` — ganti `addToCompare()` ke AJAX, include widget
6. Update `landing/search.blade.php` — ganti tombol compare ke AJAX, include widget
7. Hapus localStorage compare logic dari home.blade.php
8. Test: add/remove/clear, widget show/hide, toast muncul, halaman compare

## Definisi Selesai
- ✅ Floating compare widget muncul di home + search (fixed bottom-right)
- ✅ Widget hanya muncul jika ada item di session compare
- ✅ Badge counter menampilkan jumlah item (X/3)
- ✅ Tombol compare di setiap product card menggunakan AJAX (tanpa reload)
- ✅ Tombol item yang sudah di compare di-highlight (biru)
- ✅ Toast notification untuk setiap aksi (add/remove/clear/error)
- ✅ Tombol "View" di widget navigasi ke halaman compare
- ✅ Tombol "Clear" di widget menghapus semua item
- ✅ Fungsi JavaScript yang sudah dipindahkan ke file terpisah (tidak inline di blade)
- ✅ LocalStorage lama dibersihkan (migrasi)
- ✅ Responsive: widget di mobile tetap rapi
