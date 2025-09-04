<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class NormalizeProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:normalize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all products to a base price and stock, and set a discount for 3 random products.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting product normalization...');

        // Step 1: Update all products to base price and stock
        try {
            $updatedCount = Product::query()->update([
                'price' => 6000,
                'stock' => 20,
                'harga_diskon' => null, // Reset any existing discounts
            ]);
            $this->info("Successfully updated {$updatedCount} products to base price Rp 6.000 and stock 20.");
        } catch (\Exception $e) {
            $this->error("Failed to update base prices and stocks: " . $e->getMessage());
            return 1; // Indicate error
        }

        // Step 2: Apply discount to 3 random products
        try {
            $this->info('Applying discounts to 3 random products...');
            $randomProducts = Product::inRandomOrder()->take(3)->get();

            if ($randomProducts->count() < 3) {
                $this->warn('Could not find 3 products to apply discounts to. Found ' . $randomProducts->count() . '.');
            }

            foreach ($randomProducts as $product) {
                $product->price = 8000;
                $product->harga_diskon = 6000;
                $product->save();
                $this->line("Discount applied to: {$product->name}");
            }

            $this->info('Successfully applied discounts.');
        } catch (\Exception $e) {
            $this->error("Failed to apply discounts: " . $e->getMessage());
            return 1; // Indicate error
        }

        $this->info('Product normalization complete!');
        return 0; // Indicate success
    }
}
