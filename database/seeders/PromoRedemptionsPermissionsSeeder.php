<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class PromoRedemptionsPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Create promo redemptions permissions
            'promoredemptions.view',
            'promoredemptions.create',
            'promoredemptions.edit',
            'promoredemptions.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Assign permissions to admin role
        $adminRole = Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);

        // Assign permissions to manager role
        $managerRole = Role::findByName('manager');
        $managerRole->givePermissionTo($permissions);
    }
}
