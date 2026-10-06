<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MarketingRoleSeeder extends Seeder
{
    public function run(): void
    {
        $permission = Permission::firstOrCreate([
            'name' => 'pos.showcase',
            'guard_name' => 'web',
        ]);

        $role = Role::firstOrCreate([
            'name' => 'marketing',
            'guard_name' => 'web',
        ]);

        $role->syncPermissions([$permission]);
    }
}
