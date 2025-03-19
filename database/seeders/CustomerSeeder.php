<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 random customers
        \App\Models\Customer::factory()->count(10)->create();

        // Create a specific customer
        \App\Models\Customer::factory()->create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1 234 567 8900',
            'address' => '123 Main St, City, Country',
            'facebook' => 'https://facebook.com/johndoe',
            'x' => 'https://x.com/johndoe',
            'instagram' => 'https://instagram.com/johndoe',
            'linkedin' => 'https://linkedin.com/in/johndoe',
            'tiktok' => 'https://tiktok.com/@johndoe'
        ]);
    }
}
