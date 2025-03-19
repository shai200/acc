<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InvoiceItem>
 */
class InvoiceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->randomFloat(2, 1, 10);
        $unit_price = $this->faker->randomFloat(2, 10, 1000);
        
        return [
            'invoice_id' => Invoice::factory(),
            'product_id' => Product::factory(),
            'description' => $this->faker->sentence(),
            'quantity' => $quantity,
            'unit_price' => $unit_price,
            'amount' => $quantity * $unit_price,
        ];
    }
}
