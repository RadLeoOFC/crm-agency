<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Language::create([
            'code' => 'ru',
            'name' => 'Russian',
        ]);

        Language::create([
            'code' => 'en',
            'name' => 'English',
        ]);

        Language::create([
            'code' => 'bg',
            'name' => 'Bulgarian',
        ]);

        Language::create([
            'code' => 'de',
            'name' => 'German',
        ]);

    }
}
