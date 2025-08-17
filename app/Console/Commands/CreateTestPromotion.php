<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Promotion;
use App\Models\Category;

class CreateTestPromotion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:test-promotion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test promotion for bebidas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creando promoción de prueba para Bebidas...');

        // Buscar la categoría de Bebidas
        $bebidasCategory = Category::where('name', 'Bebidas')->first();
        
        if (!$bebidasCategory) {
            $this->error('No se encontró la categoría de Bebidas');
            return 1;
        }

        $this->info("Categoría encontrada: {$bebidasCategory->name} (ID: {$bebidasCategory->id})");

        // Crear promoción para bebidas
        $promotion = Promotion::create([
            'name' => 'Descuento en Bebidas',
            'description' => '10% de descuento en todas las bebidas',
            'type' => 'percentage',
            'discount_value' => 10.00,
            'apply_to' => 'category',
            'minimum_amount' => 20.00,
            'is_active' => true,
            'start_date' => now(),
            'end_date' => now()->addDays(30),
            'created_by' => 1, // Usuario admin
        ]);

        // Asociar con la categoría de bebidas
        $promotion->categories()->attach($bebidasCategory->id);

        $this->info("Promoción creada exitosamente con ID: {$promotion->id}");
        $this->info("Asociada a categoría: {$bebidasCategory->name}");

        // Verificar que la promoción se creó correctamente
        $promotion->load('categories');
        $this->info("Categorías asociadas: " . $promotion->categories->pluck('name')->implode(', '));

        return 0;
    }
}
