<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Promotion;
use App\Models\Category;
use App\Models\Product;

class DebugPromotions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debug:promotions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Debug promotions system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== DIAGNÓSTICO DE PROMOCIONES ===');
        $this->newLine();

        // 1. Revisar todas las promociones
        $this->info('1. PROMOCIONES ACTIVAS:');
        $promotions = Promotion::where('is_active', true)->get();
        
        if ($promotions->isEmpty()) {
            $this->warn('No hay promociones activas');
        } else {
            foreach ($promotions as $promotion) {
                $this->line("ID: {$promotion->id} | Nombre: {$promotion->name}");
                $this->line("Aplicar a: {$promotion->apply_to}");
                $this->line("Tipo: " . ($promotion->type ?? 'NULL'));
                $this->line("Valor descuento: " . ($promotion->discount_value ?? 'NULL'));
                $this->line("Descuento texto: " . ($promotion->discount_text ?? 'NULL'));
                $this->line("Monto mínimo: " . ($promotion->minimum_amount ?: 'Sin mínimo'));
                
                // Cargar relaciones explícitamente
                $promotion->load('categories', 'products');
                $this->line("Categorías asociadas: " . $promotion->categories->pluck('id')->implode(', '));
                $this->line("Productos asociados: " . $promotion->products->pluck('id')->implode(', '));
                $this->line("---");
            }
        }

        // 2. Revisar categorías
        $this->newLine();
        $this->info('2. CATEGORÍAS DISPONIBLES:');
        $categories = Category::all();
        foreach ($categories as $category) {
            $this->line("ID: {$category->id} | Nombre: {$category->name}");
        }

        // 3. Revisar productos con sus categorías
        $this->newLine();
        $this->info('3. ALGUNOS PRODUCTOS CON CATEGORÍAS:');
        $products = Product::with('category')->take(10)->get();
        foreach ($products as $product) {
            $this->line("ID: {$product->id} | Nombre: {$product->name} | Categoría: {$product->category->name} (ID: {$product->category_id})");
        }

        // 4. Revisar tablas pivot
        $this->newLine();
        $this->info('4. TABLA PIVOT PROMOTION_CATEGORIES:');
        $pivotCategories = DB::table('promotion_categories')->get();
        if ($pivotCategories->isEmpty()) {
            $this->warn('No hay registros en la tabla promotion_categories');
        } else {
            foreach ($pivotCategories as $pivot) {
                $this->line("Promoción ID: {$pivot->promotion_id} | Categoría ID: {$pivot->category_id}");
            }
        }

        $this->newLine();
        $this->info('5. TABLA PIVOT PROMOTION_PRODUCTS:');
        $pivotProducts = DB::table('promotion_products')->get();
        if ($pivotProducts->isEmpty()) {
            $this->warn('No hay registros en la tabla promotion_products');
        } else {
            foreach ($pivotProducts as $pivot) {
                $this->line("Promoción ID: {$pivot->promotion_id} | Producto ID: {$pivot->product_id}");
            }
        }

        $this->newLine();
        $this->info('=== FIN DEL DIAGNÓSTICO ===');
        
        return 0;
    }
}
