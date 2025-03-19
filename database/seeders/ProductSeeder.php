<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 5 random products
        \App\Models\Product::factory()->count(5)->create();

        // Create a specific product
        \App\Models\Product::factory()->create([
            'item_code' => 'IC-10001',
            'description' => 'Premium Product',
            'unit_price' => 999.99
        ]);
    }
}
