<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class RefactorProductCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refactor-product-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refactors product categories for better filtering.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting product category refactoring...');

        $updates = [
            'Pedas Asin' => 'Pedas Asin',
            'Keju' => 'Keju',
            'Balado' => 'Balado',
        ];

        foreach ($updates as $variant => $newCategory) {
            $updatedCount = Product::where('variant', $variant)->update(['category' => $newCategory]);
            if ($updatedCount > 0) {
                $this->info("Updated category for variant '{$variant}' to '{$newCategory}'. Affected rows: {$updatedCount}");
            } else {
                $this->line("No products found with variant '{$variant}'. Nothing to update.");
            }
        }

        $this->info('Category refactoring complete.');
        return 0;
    }
}