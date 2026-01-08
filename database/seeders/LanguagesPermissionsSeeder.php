<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class LanguagesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions = [
            // Create languages permissions
            'languages.view' => 'View languages',
            'languages.create' => 'Create languages',
            'languages.edit' => 'Edit languages',
            'languages.delete' => 'Delete languages',
        ];

        foreach ($permissions as $name => $description) {
            Permission::create(['name' => $name, 'guard_name' => 'web']);
        }

        // Assign permissions to admin role
        $adminRole = Role::findByName('admin');
        $admin->givePermissionTo(Permission::all());
    }
}
