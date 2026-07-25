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
        Language::firstOrCreate([
            'code' => 'ru',
            'name' => 'Russian',
        ]);

        Language::firstOrCreate([
            'code' => 'en',
            'name' => 'English',
        ]);

        Language::firstOrCreate([
            'code' => 'bg',
            'name' => 'Bulgarian',
        ]);

        Language::firstOrCreate([
            'code' => 'de',
            'name' => 'German',
        ]);

    }
}
