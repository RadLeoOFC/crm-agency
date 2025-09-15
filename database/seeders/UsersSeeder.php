<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;


class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin User',
            'password' => Hash::make('adminpass'),
        ]);

        // Если есть роли
        $user->assignRole('admin');


        $user = User::firstOrCreate([
            'email' => 'manager@example.com',
        ], [
            'name' => 'Manager User',
            'password' => Hash::make('managerpass'),
        ]);

        // Если есть роли
        $user->assignRole('manager');


        $user = User::firstOrCreate([
            'email' => 'client@example.com',
        ], [
            'name' => 'Client User',
            'password' => Hash::make('clientpass'),
        ]);

        // Если есть роли
        $user->assignRole('client');


        $user = User::firstOrCreate([
            'email' => 'accountant@example.com',
        ], [
            'name' => 'Accountant User',
            'password' => Hash::make('accountantpass'),
        ]);

        // Если есть роли
        $user->assignRole('accountant');


        $user = User::firstOrCreate([
            'email' => 'partner@example.com',
        ], [
            'name' => 'Partner User',
            'password' => Hash::make('partnerpass'),
        ]);

        // Если есть роли
        $user->assignRole('partner');
    }
}

