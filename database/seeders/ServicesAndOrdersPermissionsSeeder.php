<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class ServicesAndOrdersPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Create services permissions
            'services.view',
            'services.create',
            'services.edit',
            'services.delete',

            // Create orders permissions
            'orders.view',
            'orders.create',
            'orders.edit',
            'orders.delete',

            // Create order items permissions
            'orderitems.view',
            'orderitems.create',
            'orderitems.edit',
            'orderitems.delete',
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

        // Assign permissions to client role
        $clientRole = Role::findByName('client');
        $clientPermissions = [
            'services.view',
            'orders.view', 'orders.create', 'orders.edit', 'orders.delete',
            'orderitems.view', 'orderitems.create', 'orderitems.edit', 'orderitems.delete',
        ];
        $clientRole->givePermissionTo($clientPermissions);

        // Assign permissions to an accountant role
        $accountantRole = Role::findByName('accountant');
        $accountantPermissions = [
            'services.view',
            'orders.view',
            'orderitems.view',
        ];
        $accountantRole->givePermissionTo($accountantPermissions);

        // Assign permissions to an partner role
        $partnerRole = Role::findByName('partner');
        $partnerPermissions = [
            'services.view',
            'orders.view', 'orders.create', 'orders.edit', 'orders.delete',
            'orderitems.view', 'orderitems.create', 'orderitems.edit', 'orderitems.delete',
        ];
        $partnerRole->givePermissionTo($partnerPermissions);
    }
}
