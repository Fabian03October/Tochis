<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Promotion;

class FixPromotions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:promotions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix promotions data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Corrigiendo promociones...');

        // Obtener la promoción existente
        $promotion = Promotion::find(1);
        
        if (!$promotion) {
            $this->error('No se encontró la promoción con ID 1');
            return 1;
        }

        $this->info("Promoción actual: {$promotion->name}");
        $this->info("Apply to: {$promotion->apply_to}");
        $this->info("Type: " . ($promotion->type ?? 'NULL'));
        $this->info("Discount value: " . ($promotion->discount_value ?? 'NULL'));

        // Actualizar la promoción
        $promotion->update([
            'name' => 'Descuento en Hamburguesas',
            'description' => '15% de descuento en todas las hamburguesas',
            'type' => 'percentage',
            'discount_value' => 15.00,
            'apply_to' => 'category',
            'minimum_amount' => 50.00, // Reducir monto mínimo
        ]);

        $this->info('Promoción actualizada exitosamente');

        // Verificar que esté asociada correctamente a la categoría de Hamburguesas
        $this->info('Categorías asociadas: ' . $promotion->fresh()->categories->pluck('name')->implode(', '));

        return 0;
    }
}
