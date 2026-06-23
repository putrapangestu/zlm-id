# USER-1: Admin Manajemen User (CRUD)

## Tujuan
Admin bisa mengelola semua user: melihat, menambah, mengedit, dan menghapus user.

## Implementasi

### 1. Update UserController
`app/Http/Controllers/Admin/UserController.php`

Methods:
- `index()` — List users (search, filter by role)
- `create()` — Form create user
- `store(Request)` — Simpan user baru
- `show(User $user)` — Detail user + riwayat order
- `edit(User $user)` — Form edit
- `update(Request, User $user)` — Update user
- `destroy(User $user)` — Soft delete user

### 2. Routes (sudah ada di web.php)
```php
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    // Tambah: create, store, show, edit, update, destroy
});
```

### 3. Views

#### `admin/users/index.blade.php`
- Table: No, Nama, Email, Role, Orders Count, Registered, Actions
- Search bar
- Filter role dropdown
- "Tambah User" button

#### `admin/users/create.blade.php`
- Name, Email, Password, Confirm Password, Role (dropdown dari Spatie roles)

#### `admin/users/edit.blade.php`
- Name, Email, Password (optional), Role

#### `admin/users/show.blade.php`
- Detail profil user
- Riwayat order user (table)
- Total belanja
- Status

### 4. Spatie Roles
Gunakan roles yang sudah ada: `admin`, `user`

### 5. Validasi
- Email unique (kecuali untuk user yang diedit)
- Password minimal 8 karakter (required saat create, optional saat edit)
- Minimal satu role harus dipilih

## Definisi Selesai
- [x] Index user dengan search & filter
- [x] Create user manual
- [x] Edit user (name, email, role, password opsional)
- [x] Detail user dengan riwayat order
- [x] Delete user (soft delete)
- [x] Validasi lengkap
