<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class GrantShowcaseAccessToAdminsSeeder extends Seeder
{
    public function run(): void
    {
        $permission = Permission::firstOrCreate([
            'name' => 'pos.showcase',
            'guard_name' => 'web',
        ]);

        $adminRole = Role::where('name', 'admin')
            ->where('guard_name', 'web')
            ->first();

        if ($adminRole) {
            $adminRole->givePermissionTo($permission);
        }
    }
}
