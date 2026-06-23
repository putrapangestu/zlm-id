# TRX-9: Admin Sidebar — Transactions Link

## Tujuan
Menambahkan menu Transactions di sidebar admin.

## File Diubah
- `resources/views/layouts/admin.blade.php`

## Detail Implementasi

Tambahkan link setelah menu Categories (sebelum Orders):

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

### Icon options (pilih salah satu):
- `solar:wallet-linear` — wallet/dompet
- `solar:card-transfer-linear` — transfer kartu
- `solar:banknote-linear` — uang
- `solar:receipt-linear` — kuitansi

### Definisi Selesai
- [ ] Menu Transactions muncul di sidebar admin
- [ ] Active state: bg orange & text orange ketika di halaman transactions
- [ ] Link mengarah ke `route('admin.transactions.index')`
