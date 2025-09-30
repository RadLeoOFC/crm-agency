<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::firstOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name' => 'Demo Client',
                'contact_person' => 'Иван Иванов',
                'phone' => '+359123456789',
                'company' => 'Demo Company Ltd',
                'country' => 'Bulgaria',
                'city' => 'Sofia',
                'address' => 'ул. Демонстрации, 1',
                'vat_number' => 'BG123456789',
            ]
        );
    }
}
