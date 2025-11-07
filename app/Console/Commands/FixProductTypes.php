<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class FixProductTypes extends Command
{
    protected $signature = 'fix:product-types';
    protected $description = 'Fix product types - mark only food products as is_food=true';

    public function handle()
    {
        $this->info('Corrigiendo tipos de Platillos...');
        
        // Marcar todos los Platillos como NO comida primero
        Product::query()->update(['is_food' => false]);
        
        // Categorías que SÍ son de comida
        $foodCategories = [
            'Bebidas',
            'Snacks', 
            'Lácteos',
            'Panadería',
            'Frutas y Verduras',
            'Carnes'
        ];
        
        // Platillos específicos que SÍ son comida (por nombre)
        $foodProductNames = [
            'Coca Cola 600ml',
            'Pepsi 600ml', 
            'Agua Ciel 1L',
            'Jugo Del Valle Naranja 1L',
            'Sabritas Originales 45g',
            'Doritos Nacho 58g',
            'Galletas Marías Gamesa',
            'Chocolate Carlos V',
            'Leche Lala Entera 1L',
            'Yogurt Danone Fresa 1L',
            'Queso Oaxaca 400g',
            'Pan Blanco Bimbo',
            'Tortillas de Harina 1kg',
            'Gansito Marinela'
        ];
        
        // Actualizar Platillos de comida por categoría
        foreach ($foodCategories as $categoryName) {
            $updated = Product::whereHas('category', function($query) use ($categoryName) {
                $query->where('name', $categoryName);
            })->update(['is_food' => true]);
            
            if ($updated > 0) {
                $this->info("✓ Marcados {$updated} Platillos de la categoría '{$categoryName}' como comida");
            }
        }
        
        // Actualizar Platillos específicos por nombre
        foreach ($foodProductNames as $productName) {
            $updated = Product::where('name', $productName)->update(['is_food' => true]);
            if ($updated > 0) {
                $this->info("✓ Marcado '{$productName}' como comida");
            }
        }
        
        // Mostrar resumen
        $foodCount = Product::where('is_food', true)->count();
        $nonFoodCount = Product::where('is_food', false)->count();
        
        $this->info("\n=== RESUMEN ===");
        $this->info("Platillos de comida: {$foodCount}");
        $this->info("Platillos NO comida: {$nonFoodCount}");
        
        $this->info("\n=== PlatilloS NO COMIDA ===");
        $nonFoodProducts = Product::with('category')->where('is_food', false)->get();
        foreach ($nonFoodProducts as $product) {
            $this->line("- {$product->name} ({$product->category->name})");
        }
    }
}
