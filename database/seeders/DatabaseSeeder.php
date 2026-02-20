<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // сиды вызываем по очереди
        // we call the seeds one by one
        $this->call([
            RolesAndPermissionsSeeder::class,
            UsersSeeder::class,
            LanguagesPermissionsSeeder::class,
            LanguagesSeeder::class,
        ]);
    }
}
