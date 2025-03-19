<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 invoices, each with 2-5 items
        Invoice::factory()
            ->count(10)
            ->create()
            ->each(function ($invoice) {
                // Calculate items
                $items = InvoiceItem::factory()
                    ->count(rand(2, 5))
                    ->make([
                        'invoice_id' => $invoice->id
                    ]);
                
                // Insert items
                $invoice->items()->saveMany($items);
                
                // Recalculate invoice totals
                $sub_total = $items->sum('amount');
                $discount = $invoice->discount;
                $total = $sub_total - $discount;
                
                // Update invoice
                $invoice->update([
                    'sub_total' => $sub_total,
                    'total' => $total
                ]);
            });
    }
}
