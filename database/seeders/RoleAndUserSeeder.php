<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Define all granular permissions
        $permissions = [
            // POS Kasir
            'pos.access' => 'Akses Kasir POS',
            'pos.discount' => 'Berikan Diskon Kasir',

            // Quality Control (QC)
            'qc.view' => 'Lihat Daftar QC',
            'qc.inspect' => 'Lakukan Inspeksi QC & Loloskan SKU',

            // Restock Barang
            'restock.view' => 'Lihat Data Restock',
            'restock.create' => 'Tambah Batch Restock',
            'restock.print' => 'Cetak Dot Matrix Bukti Restock',

            // Retur Barang
            'returns.view' => 'Lihat Pengajuan Retur',
            'returns.process' => 'Proses & Putuskan Retur',

            // Laptop & Produk
            'laptops.view' => 'Lihat Produk & Varian',
            'laptops.create' => 'Tambah Produk Laptop',
            'laptops.edit' => 'Edit Spesifikasi & Diskon Produk',
            'laptops.delete' => 'Hapus Produk',

            // Kategori & Konten
            'categories.manage' => 'Kelola Kategori',
            'articles.manage' => 'Kelola Artikel Blog',
            'sliders.manage' => 'Kelola Hero Slider & Testimoni',

            // Transaksi & Pesanan
            'transactions.view' => 'Lihat Transaksi Penjualan',
            'transactions.confirm' => 'Konfirmasi Pembayaran Manual',

            // Member & Pelanggan
            'members.view' => 'Lihat Data Pelanggan & Member',
            'members.manage' => 'Kelola Poin & Tier Member',

            // Laporan
            'reports.purchases' => 'Lihat Laporan Pembelian',
            'reports.profit_loss' => 'Lihat Laporan Laba Rugi',
            'reports.product_stats' => 'Lihat Statistik Barang',

            // Pengaturan & Pengguna
            'settings.manage' => 'Kelola Pengaturan Sistem & WhatsApp',
            'users.manage' => 'Kelola User, Role & Permissions',
        ];

        foreach ($permissions as $name => $label) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // 2. Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        $karyawanRole = Role::firstOrCreate(['name' => 'karyawan', 'guard_name' => 'web']);
        $defaultKaryawanPermissions = [
            'pos.access',
            'qc.view',
            'qc.inspect',
            'restock.view',
            'restock.create',
            'restock.print',
            'returns.view',
            'returns.process',
            'laptops.view',
            'transactions.view',
            'members.view',
        ];
        $karyawanRole->syncPermissions($defaultKaryawanPermissions);

        Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);

        // 3. Create Default Users
        $admin = User::firstOrCreate(
            ['email' => 'admin@zlm.id'],
            [
                'name' => 'Admin ZLM',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'phone_number' => '081234567890',
                'member_number' => 'MBR-ADM001',
                'member_tier' => 'platinum',
            ]
        );
        $admin->syncRoles([$adminRole]);

        $karyawan = User::firstOrCreate(
            ['email' => 'karyawan@zlm.id'],
            [
                'name' => 'Karyawan Toko',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'phone_number' => '081298765432',
                'member_number' => 'MBR-KRY001',
                'member_tier' => 'gold',
            ]
        );
        $karyawan->syncRoles([$karyawanRole]);
        $karyawan->syncPermissions($defaultKaryawanPermissions);

        $customer = User::firstOrCreate(
            ['email' => 'customer@zlm.id'],
            [
                'name' => 'Customer Demo',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'phone_number' => '085712345678',
                'member_number' => 'MBR-CUS001',
                'member_tier' => 'silver',
                'member_points' => 150,
            ]
        );
        $customer->syncRoles(['customer']);
    }
}
