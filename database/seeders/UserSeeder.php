<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Customer::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@example.com',
        ]);
        \App\Models\Driver::factory()->create([
            'name' => 'Test Driver',
            'email' => 'driver@example.com',
        ]);
    }
}
