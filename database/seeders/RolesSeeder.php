<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // если используешь spatie/laravel-permission

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'manager', 'client', 'accountant', 'partner'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}

