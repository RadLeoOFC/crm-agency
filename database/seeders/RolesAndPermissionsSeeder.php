<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // check if the roles and permissions exist
        $roles = Role::all();
        $permissions = Permission::all();
        if ($roles->count() > 0 && $permissions->count() > 0) {
            return;
        }
        
        // Delete existing permissions and roles
        Permission::query()->delete();
        Role::query()->delete();

        // Create permissions
        $permissions = [
            // User management
            'users.view' => 'View users',
            'users.create' => 'Create users',
            'users.edit' => 'Edit users',
            'users.delete' => 'Delete users',
            
            // Client management
            'clients.view' => 'View clients',
            'clients.create' => 'Create clients',
            'clients.edit' => 'Edit clients',
            'clients.delete' => 'Delete clients',
            
            // Platform management
            'platforms.view' => 'View platforms',
            'platforms.create' => 'Create platforms',
            'platforms.edit' => 'Edit platforms',
            'platforms.delete' => 'Delete platforms',
            
            // Booking management
            'bookings.view' => 'View bookings',
            'bookings.create' => 'Create bookings',
            'bookings.edit' => 'Edit bookings',
            'bookings.delete' => 'Delete bookings',
            
            // Pricelist management
            'pricelists.view' => 'View pricelists',
            'pricelists.create' => 'Create pricelists',
            'pricelists.edit' => 'Edit pricelists',
            'pricelists.delete' => 'Delete pricelists',
            'pricelists.generateSlots' => 'Generate slots',
            
            // Price rules management
            'pricerules.view' => 'View pricerules',
            'pricerules.create' => 'Create pricerules',
            'pricerules.edit' => 'Edit pricerules',
            'pricerules.delete' => 'Delete pricerules',
            
            // Activity log
            'activity-logs.view' => 'View activity logs',
            
            // Role management
            'roles.view' => 'View roles',
            'roles.create' => 'Create roles',
            'roles.edit' => 'Edit roles',
            'roles.delete' => 'Delete roles',

            // Price overrides management
            'priceoverrides.view' => 'View priceoverrides',
            'priceoverrides.create' => 'Create priceoverrides',
            'priceoverrides.edit' => 'Edit priceoverrides',
            'priceoverrides.delete' => 'Delete priceoverrides',

            // Promocode Management
            'promocodes.view' => 'View promocodes',
            'promocodes.create' => 'Create promocodes',
            'promocodes.edit' => 'Edit promocodes',
            'promocodes.delete' => 'Delete promocodes',

            // Slot Management
            'slots.view' => 'View slots',
            'slots.create' => 'Create slots',
            'slots.edit' => 'Edit slots',
            'slots.delete' => 'Delete slots',
        ];

        foreach ($permissions as $name => $description) {
            Permission::create(['name' => $name, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions
        
        // Admin role - gets all permissions
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $admin->givePermissionTo(Permission::all());

        // Manager role
        $manager = Role::create(['name' => 'manager', 'guard_name' => 'web']);
        $managerPermissions = [
            'platforms.view','pricelists.view','slots.view','promocodes.view',
            'clients.view', 'clients.create', 'clients.edit', 'clients.delete',
            'bookings.view', 'bookings.create', 'bookings.edit', 'bookings.delete'
        ];

        $manager->givePermissionTo($managerPermissions);

        // Client role
        $client = Role::create(['name' => 'client', 'guard_name' => 'web']);
        $clientPermissions = [
            'bookings.view'
        ];

        $client->givePermissionTo($clientPermissions);

        // Accountant role
        $accountant = Role::create(['name' => 'accountant', 'guard_name' => 'web']);
        $accountantPermissions = [
            'platforms.view','pricelists.view','slots.view','clients.view','bookings.view'
        ];

        $accountant->givePermissionTo($accountantPermissions);


        // Partner role
        $partner = Role::create(['name' => 'partner', 'guard_name' => 'web']);
        $partnerPermissions = [
            'platforms.view','pricelists.view',
            'slots.view','slots.create', 'slots.edit', 'slots.delete',
            'bookings.view'
        ];

        $partner->givePermissionTo($partnerPermissions);
    }
}
