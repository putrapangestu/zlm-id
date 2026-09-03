<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('member_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        $users = $query->withCount('orders')->with('roles', 'permissions')->latest()->paginate(15);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        $permissionGroups = $this->getPermissionGroups();
        return view('admin.users.create', compact('roles', 'permissionGroups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone_number' => 'nullable|string|max:20',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
            'member_tier' => 'nullable|in:bronze,silver,gold,platinum',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'] ?? null,
            'member_tier' => $validated['member_tier'] ?? 'bronze',
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(), // User yang dibuat admin otomatis terverifikasi
        ]);

        $user->assignRole($validated['role']);

        if ($validated['role'] === 'karyawan' && !empty($validated['permissions'])) {
            $user->syncPermissions($validated['permissions']);
        } elseif ($validated['role'] === 'admin') {
            $user->syncPermissions(Permission::all());
        } else {
            $user->syncPermissions([]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna {$user->name} berhasil ditambahkan.");
    }

    public function show(User $user)
    {
        $orders = Order::where('user_id', $user->id)
            ->with('items.laptop')
            ->latest()
            ->paginate(10);

        $totalSpent = Order::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->sum('total');

        $user->load('roles', 'permissions');

        return view('admin.users.show', compact('user', 'orders', 'totalSpent'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $userRole = $user->roles->first()?->name;
        $permissionGroups = $this->getPermissionGroups();
        $userPermissions = $user->permissions->pluck('name')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'userRole', 'permissionGroups', 'userPermissions'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
            'member_tier' => 'nullable|in:bronze,silver,gold,platinum',
            'member_points' => 'nullable|integer|min:0',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'] ?? null,
            'member_tier' => $validated['member_tier'] ?? $user->member_tier,
            'member_points' => $validated['member_points'] ?? $user->member_points,
        ]);

        if ($validated['password']) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $user->syncRoles([$validated['role']]);

        if ($validated['role'] === 'karyawan') {
            $user->syncPermissions($validated['permissions'] ?? []);
        } elseif ($validated['role'] === 'admin') {
            $user->syncPermissions(Permission::all());
        } else {
            $user->syncPermissions([]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna {$user->name} berhasil dihapus.");
    }

    private function getPermissionGroups(): array
    {
        return [
            'Kasir & Transaksi POS' => [
                'pos.access' => 'Buka Aplikasi Kasir POS & Transaksi',
                'pos.discount' => 'Berikan Diskon Khusus Kasir',
            ],
            'Quality Control (QC)' => [
                'qc.view' => 'Lihat Daftar Barang Pending QC',
                'qc.inspect' => 'Lakukan Inspeksi Fisik, Loloskan SKU & Tolak Unit',
            ],
            'Restock Barang' => [
                'restock.view' => 'Lihat Riwayat & Daftar Restock',
                'restock.create' => 'Input Pembelian / Batch Restock Baru',
                'restock.print' => 'Cetak Format Kertas Dot Matrix',
            ],
            'Retur Barang' => [
                'returns.view' => 'Lihat Daftar Pengajuan Retur',
                'returns.process' => 'Setujui / Tolak Retur & Putuskan Mutasi Stok',
            ],
            'Produk Laptop & Stok' => [
                'laptops.view' => 'Lihat Katalog Laptop & Varian',
                'laptops.create' => 'Tambah Produk Laptop Baru',
                'laptops.edit' => 'Edit Spesifikasi, Harga & Diskon Produk',
                'laptops.delete' => 'Hapus Produk Laptop',
            ],
            'Kategori & Konten' => [
                'categories.manage' => 'Kelola Kategori Laptop',
                'articles.manage' => 'Kelola Artikel & Blog',
                'sliders.manage' => 'Kelola Slider Banner & Testimoni',
            ],
            'Penjualan & Transaksi Online' => [
                'transactions.view' => 'Lihat Semua Riwayat Transaksi Toko',
                'transactions.confirm' => 'Konfirmasi Pembayaran Manual & Tracking Resi',
            ],
            'Member & Pelanggan' => [
                'members.view' => 'Lihat Daftar Pelanggan & Member',
                'members.manage' => 'Kelola Tier Member, Poin & Kartu Digital',
            ],
            'Laporan Keuangan & Inventori' => [
                'reports.purchases' => 'Lihat Laporan Pembelian & Restock',
                'reports.profit_loss' => 'Lihat Laporan Laba Rugi & HPP',
                'reports.product_stats' => 'Lihat Statistik Valuasi & Performa Barang',
            ],
            'Pengaturan & Pengguna' => [
                'settings.manage' => 'Kelola Pengaturan Toko, WhatsApp, & Printer',
                'users.manage' => 'Kelola Akun, Role & Hak Akses Karyawan',
            ],
        ];
    }
}
