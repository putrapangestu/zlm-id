# TRX-9 Implementation Plan: Admin Sidebar — Transactions Link

## Effort: Tiny

## File Structure
| File | Tipe | Keterangan |
|------|------|------------|
| `resources/views/layouts/admin.blade.php` | DIUBAH | Tambah menu Transactions di sidebar |

## Implementation Order

### Step 1: Identifikasi Posisi Menu
- Setelah menu **Categories** (`route('admin.categories.*')`)
- Sebelum menu **Orders** (`route('admin.orders.*')`)
- Atau setelah **Orders** — tergantung preferensi navigasi
- **Rekomendasi**: Letakkan setelah Categories (sebelum Orders) sesuai spek

### Step 2: Tambah Link di Sidebar
```html
<a href="{{ route('admin.transactions.index') }}" 
   class="flex items-center gap-3 px-3 py-2.5 rounded-xl 
   @if(request()->routeIs('admin.transactions.*')) bg-orange-50/50 text-[#DF5E1D] 
   @else text-gray-500 hover:bg-gray-50 hover:text-[#363230] @endif 
   transition-colors duration-200 group">
    <iconify-icon icon="solar:wallet-linear" class="text-lg" style="stroke-width: 1.5;"></iconify-icon>
    <span class="text-sm font-medium">Transactions</span>
</a>
```
- Icon pilih: `solar:wallet-linear` (paling relevan untuk transaksi)
- Active state: route prefix `admin.transactions.*`
- Sisipkan di antara menu Categories dan Orders

## Dependencies Internal
- TRX-6 (routes) — route `admin.transactions.index` harus sudah terdaftar

## Data Flow
```
User klik "Transactions" di sidebar
    ↓
Route: admin.transactions.index
    ↓
TransactionController@index
    ↓
Tampilkan halaman index transaksi
```

## Test Plan
- Visual: link muncul di sidebar dengan icon
- Visual: active state berubah ketika di halaman transactions
- Functional: klik link → navigasi ke route('admin.transactions.index')
