<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => 'INV-' . str_pad($this->faker->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'customer_id' => Customer::factory(),
            'date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'due_date' => $this->faker->dateTimeBetween('now', '+30 days'),
            'reference' => $this->faker->optional()->bothify('REF-####'),
            'terms_and_conditions' => $this->faker->paragraph(),
            'sub_total' => $this->faker->randomFloat(2, 100, 10000),
            'discount' => $this->faker->randomFloat(2, 0, 1000),
            'total' => $this->faker->randomFloat(2, 100, 10000),
        ];
    }
}
