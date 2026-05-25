<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']);
        Role::create(['name' => 'buyer']);

        $user = User::factory()->create([
            'name' => 'Admin ZLM',
            'email' => 'admin@zlm.id',
            'password' => Hash::make('admin123'),
        ]);
        $user->assignRole('admin');
    }
}
