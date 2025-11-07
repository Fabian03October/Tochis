<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Product;

class CheckData extends Command
{
    protected $signature = 'check:data';
    protected $description = 'Check categories and products data';

    public function handle()
    {
        $this->info('=== VERIFICACIÓN DE DATOS ===');
        
        $totalCategories = Category::count();
        $activeCategories = Category::where('is_active', true)->count();
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $foodProducts = Product::where('is_food', true)->count();
        $productsWithStock = Product::where('stock', '>', 0)->count();
        
        $this->info("Total Categorías: {$totalCategories}");
        $this->info("Categorías Activas: {$activeCategories}");
        $this->info("Total Platillos: {$totalProducts}");
        $this->info("Platillos Activos: {$activeProducts}");
        $this->info("Platillos de Comida: {$foodProducts}");
        $this->info("Platillos con Stock > 0: {$productsWithStock}");
        
        $this->info("\n=== CATEGORÍAS CON PlatilloS ===");
        $categories = Category::active()->with(['activeProducts'])->get();
        
        foreach ($categories as $category) {
            $productCount = $category->activeProducts->count();
            $this->info("- {$category->name}: {$productCount} Platillos");
            
            if ($productCount > 0) {
                foreach ($category->activeProducts as $product) {
                    $status = $product->is_food ? '(Comida)' : "(Stock: {$product->stock})";
                    $this->line("  * {$product->name} {$status}");
                }
            }
        }
    }
}
