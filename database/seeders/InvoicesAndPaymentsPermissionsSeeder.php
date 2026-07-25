<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class InvoicesAndPaymentsPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Create invoices permissions
            'invoices.view',
            'invoices.create',
            'invoices.edit',
            'invoices.delete',
            'invoices.publish',
            'invoices.send',
            'invoices.paymentPage',

            // Create payments permissions
            'payments.view',
            'payments.create',
            'payments.edit',
            'payments.delete',
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
            'invoices.view', 'invoices.paymentPage', 'payments.view'
        ];
        $clientRole->givePermissionTo($clientPermissions);

        // Assign permissions to an accountant role
        $accountantRole = Role::findByName('accountant');
        $accountantRole->givePermissionTo($permissions);

        // Assign permissions to an partner role
        $partnerRole = Role::findByName('partner');
        $partnerPermissions = [
            'invoices.view', 'invoices.paymentPage', 'payments.view'
        ];
        $partnerRole->givePermissionTo($partnerPermissions);
    }
}
