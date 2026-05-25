# Spesifikasi Teknis: Modul G — Admin Customers Enhancement

## Deskripsi
Mengganti route inline closure untuk customers di admin dengan controller dedicated yang memiliki fitur search, filter, dan customer detail page. Admin dapat melihat data lengkap pelanggan termasuk status akun, riwayat pesanan, total belanja, dan review.

## Kebijakan SoftDelete
- User model WAJIB menggunakan `SoftDeletes` trait
- Migration baru: `add_deleted_at_to_users_table` (kolom `deleted_at` nullable timestamp)
- `index()` hanya menampilkan user dengan `deleted_at IS NULL` (default SoftDeletes behavior)
- `show()` dapat melihat detail user meskipun user sudah di-soft-delete? → Tidak, hanya aktif

## Masalah Saat Ini
- Route customers di `web.php:61-64` adalah inline closure di dalam `Route::middleware`
- Tidak ada controller — logika ada di closure
- View `admin/customers/index.blade.php` hanya menampilkan tabel sederhana (name, email, joined, verified)
- Tidak ada search/filter
- Tidak ada halaman detail customer
- User model belum menggunakan SoftDeletes

## Solusi
- Migration: tambah `deleted_at` ke tabel users
- Tambah `SoftDeletes` trait ke User model
- Buat `Admin\CustomerController` dengan method `index()` dan `show()`
- Index: search by name/email, filter by status (active/inactive), tampilkan orders count
- Show: profile, stats cards (orders count, total spending, reviews count), orders table, reviews table

## File yang Dibuat
- `database/migrations/YYYY_MM_DD_HHMMSS_add_deleted_at_to_users_table.php` — BARU (SoftDeletes)
- `app/Http/Controllers/Admin/CustomerController.php` — BARU
- `resources/views/admin/customers/show.blade.php` — BARU

## File yang Dimodifikasi
| File | Perubahan |
|------|-----------|
| `app/Models/User.php` | Tambah `SoftDeletes` trait + relasi `otps()` |
| `routes/web.php` | Ganti closure dengan route ke controller |
| `resources/views/admin/customers/index.blade.php` | Tambah search bar, filter status, kolom Status & Orders, ganti Spending → Actions |

## Controller: `Admin\CustomerController`

### `index(Request)`
```
GET /admin/customers?search=...&status=...
→ Query User::query() (SoftDeletes otomatis filter deleted_at IS NULL)
→ withCount('orders') — untuk tampilkan jumlah order di index
→ if $request->search: where('name', 'like', "%{$search}%")->orWhere('email', 'like', "...")
→ if $request->status == 'active': whereNotNull('email_verified_at')
→ if $request->status == 'inactive': whereNull('email_verified_at')
→ orderBy('created_at', 'desc')
→ paginate(20)
→ Return view('admin.customers.index', compact('users', 'search', 'statusFilter'))
```

Data per user di index:
| Field | Sumber | Keterangan |
|-------|--------|------------|
| `$user->name` | DB | Link ke halaman show |
| `$user->email` | DB | |
| `$user->email_verified_at` | DB | Active (not null) / Inactive (null) — badge |
| `$user->orders_count` | withCount | Jumlah order (angka saja, bukan link) |
| `$user->created_at` | DB | Format: M Y |

### `show(User $user)`
```
GET /admin/customers/{user}
→ $user->load(['orders.items', 'reviews.laptop'])
→ $totalSpending = $user->orders->sum('total')
→ $orderCount = $user->orders->count()
→ $reviewCount = $user->reviews->count()
→ $lastOrderDate = $user->orders->max('created_at')
→ Return view('admin.customers.show', compact(
    'customer', 'orders', 'reviews', 'totalSpending',
    'orderCount', 'reviewCount', 'lastOrderDate'
  ))
```

Data per customer di show:
| Bagian | Elemen | Sumber |
|--------|--------|--------|
| Profile | Name, Email, Role | `$customer->name`, `$customer->email`, `$customer->getRoleNames()` |
| Status | Active/Inactive, Member since, Last active | `email_verified_at`, `created_at`, `lastOrderDate` |
| Stats Card | Orders count | `$orderCount` → `X Pesanan` |
| Stats Card | Total spending | `$totalSpending` → `Rp X.XXX.XXX` |
| Stats Card | Reviews count | `$reviewCount` → `X Review` |
| Orders Table | Order#, Date, Items, Total, Status | `$orders` collection |
| Reviews Table | Product, Rating, Date, Review text | `$reviews` collection |

## Routes

### Di `routes/web.php` (dalam group admin)
```php
// Ganti closure dengan controller
use App\Http\Controllers\Admin\CustomerController;

Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::get('/customers/{user}', [CustomerController::class, 'show'])->name('customers.show');
```

### Yang Dihapus
```php
// Hapus closure lama (baris 61-64) + use statement untuk User di routes
Route::get('/customers', function () {
    $users = App\Models\User::orderBy('name')->paginate(20);
    return view('admin.customers.index', compact('users'));
})->name('customers.index');
```

## UI Design

### Index — Admin Customers (UPDATE)
```
┌────────────────────────────────────────────────────────────────┐
│  [Admin Sidebar]                                               │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  Customers                      [Total: 120 pelanggan]    │  │
│  │                                                           │  │
│  │  ┌──────────────────────┐ ┌──────────┐                   │  │
│  │  │ 🔍 Cari nama/email  │ │Status ▼  │ [Terapkan]        │  │
│  │  └──────────────────────┘ └──────────┘                   │  │
│  │                                                           │  │
│  │  ┌───────┬────────────┬──────────┬───────┬───────┬──────┐│  │
│  │  │ Name  │ Email      │ Status   │ Since │Orders │ Act. ││  │
│  │  ├───────┼────────────┼──────────┼───────┼───────┼──────┤│  │
│  │  │ Budi  │ budi@m.com │ 🟢 Active│ Jan25 │   5   │  👁  ││  │
│  │  │ Sari  │ sari@m.com │ ⚪ Inact.│ Mar25 │   2   │  👁  ││  │
│  │  └───────┴────────────┴──────────┴───────┴───────┴──────┘│  │
│  │                                                           │  │
│  │  [1] [2] [3] ... [Next]                                  │  │
│  └──────────────────────────────────────────────────────────┘  │
└────────────────────────────────────────────────────────────────┘
```

### Show — Admin Customer Detail (BARU)
```
┌────────────────────────────────────────────────────────────────┐
│  [Admin Sidebar]                                               │
│  ┌──────────────────────────────────────────────────────────┐  │
│  │  ← Customers  /  Budi Santoso                            │  │
│  │                                                           │  │
│  │  ┌──────────────────────────────────────────────────┐    │  │
│  │  │  Customer Profile                                │    │  │
│  │  │                                                   │    │  │
│  │  │  Budi Santoso                    Status: Active   │    │  │
│  │  │  budi@email.com                  Role: buyer      │    │  │
│  │  │  Member since: Jan 2025                           │    │  │
│  │  └──────────────────────────────────────────────────┘    │  │
│  │                                                           │  │
│  │  ┌──────────┬──────────────┬──────────┐                  │  │
│  │  │   5      │  Rp 45.000.000│    3    │                  │  │
│  │  │  Orders  │   Spending    │ Reviews  │                  │  │
│  │  └──────────┴──────────────┴──────────┘                  │  │
│  │                                                           │  │
│  │  ★ Recent Orders (5)                                     │  │
│  │  ┌───────┬──────────┬─────────┬──────────┬────────┬────┐│  │
│  │  │ Order │ Date     │ Items   │ Total    │ Status │    ││  │
│  │  ├───────┼──────────┼─────────┼──────────┼────────┼────┤│  │
│  │  │INV-001│ 12 Jan 26│ 2 items │ Rp 25jt  │ ✅     │ 👁 ││  │
│  │  │INV-002│ 05 Feb 26│ 1 item  │ Rp 20jt  │ ✅     │ 👁 ││  │
│  │  └───────┴──────────┴─────────┴──────────┴────────┴────┘│  │
│  │                                                           │  │
│  │  ★ Recent Reviews (3)                                    │  │
│  │  ┌──────────┬──────┬──────────┬─────────────────────┐   │  │
│  │  │ Product  │ Rate │ Date     │ Review               │   │  │
│  │  ├──────────┼──────┼──────────┼─────────────────────┤   │  │
│  │  │ ThinkPad │ ★★★★★│ 12 Jan   │ "Great laptop for"  │   │  │
│  │  │ ROG Zeph │ ★★★★ │ 20 Feb   │ "Powerful but heavy"│   │  │
│  │  └──────────┴──────┴──────────┴─────────────────────┘   │  │
│  └──────────────────────────────────────────────────────────┘  │
└────────────────────────────────────────────────────────────────┘
```

## Form Input — Search & Filter (Index)
| Field | Tipe Input | Required | Notes |
|-------|-----------|----------|-------|
| search | `<input type="text" placeholder="Cari nama/email...">` | No | LIKE query, submit on Enter |
| status | `<select>` | No | Options: `all`, `active`, `inactive` |

## Data Tampilan

### Index Table Columns
| Kolom | Sumber Data | Format |
|-------|------------|--------|
| Name | `$user->name` | Text, clickable → show route |
| Email | `$user->email` | Text |
| Status | `$user->email_verified_at` | Badge: `Active` (emerald bg, text emerald-600) / `Inactive` (gray bg, text gray-500) |
| Since | `$user->created_at` | `M Y` format |
| Orders | `$user->orders_count` | Number (from withCount) |
| Actions | Icon button (👁) | Link ke `admin.customers.show` |

### Show Page Cards
| Card | Data |
|------|------|
| Profile | Name, email, role (buyer/admin), active/inactive badge, member since date |
| Stats Card 1 | Orders count — `X Pesanan` |
| Stats Card 2 | Total spending — `Rp X.XXX.XXX` |
| Stats Card 3 | Reviews count — `X Review` |
| Orders Table | Order#, Date, Items count, Total (Rp), Status badge |
| Reviews Table | Product name (link), Rating stars, Date, Review text (truncated 100 chars) |

**Empty States**:
- No orders: "Belum ada pesanan."
- No reviews: "Belum ada review."
- No customers (search): "Pelanggan tidak ditemukan."

## Perubahan SoftDeletes pada User

### Migration Baru
```php
Schema::table('users', function (Blueprint $table) {
    $table->softDeletes(); // adds deleted_at column
});
```

### Model Update
```php
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable, HasRoles, SoftDeletes;
    // ...
}
```

### Dampak SoftDeletes
- `User::all()` otomatis exclude deleted users
- `User::withTrashed()->get()` untuk include deleted
- `$user->restore()` untuk mengembalikan
- SoftDeletes sudah digunakan di `Laptop.php` — konsisten dengan model lain

## Dependency Graph
```
Modul G (Admin Customers)
   ├── G.0 Migration: add_deleted_at_to_users (SoftDeletes)
   ├── G.0 Update User model (SoftDeletes trait)
   ├── G.1 CustomerController (index + show)
   ├── G.2 Routes → web.php (ganti closure)
   ├── G.3 index.blade.php update (search + filter status + new columns)
   └── G.4 show.blade.php baru (profile + stats + orders + reviews)
```

**Dependencies**:
- Bergantung pada: User model, Order model, Review model
- Tidak bergantung pada modul Fase 2 lain

## Urutan Pengerjaan
0. Migration `add_deleted_at_to_users_table` + SoftDeletes trait ke User
1. Buat `Admin\CustomerController` (index + show)
2. Update `routes/web.php`: ganti closure
3. Update `resources/views/admin/customers/index.blade.php`: search bar, filter status, kolom Status + Orders count
4. Buat `resources/views/admin/customers/show.blade.php`
5. Test: search, filter active/inactive, view detail, empty states

## Definisi Selesai
- ✅ Route customers menggunakan controller (bukan inline closure)
- ✅ User model menggunakan SoftDeletes + migration deleted_at
- ✅ Search customers by name/email
- ✅ Filter status: active (verified email) / inactive (unverified email)
- ✅ Tabel index menampilkan: Name, Email, Status (Active/Inactive badge), Since, Orders count, Actions
- ✅ Halaman detail customer dengan profile, stats cards (orders + spending + reviews), orders table, reviews table
- ✅ Pagination di index
- ✅ Empty state untuk pencarian tanpa hasil, tanpa order, tanpa review
- ✅ Format Rupiah untuk spending
- ✅ Badge Active (emerald) / Inactive (gray) dengan warna berbeda
