<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default invoice counter
        \App\Models\Counter::factory()->create([
            'key' => 'invoice',
            'prefix' => 'INV-',
            'value' => '1'
        ]);

        // Create a default order counter
        \App\Models\Counter::factory()->create([
            'key' => 'order',
            'prefix' => 'ORD-',
            'value' => '1'
        ]);
    }
}
