<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Platilloption;

class PlatilloptionsSeeder extends Seeder
{
    public function run()
    {
        // Buscar Platillos de comida para agregar opciones
        $foodProducts = Product::where('is_food', true)->get();
        
        foreach ($foodProducts as $product) {
            // Observaciones (quitar ingredientes)
            $observations = [
                ['name' => 'Sin cebolla', 'price' => 0],
                ['name' => 'Sin tomate', 'price' => 0],
                ['name' => 'Sin lechuga', 'price' => 0],
                ['name' => 'Sin salsa', 'price' => 0],
                ['name' => 'Sin queso', 'price' => 0],
                ['name' => 'Sin chile', 'price' => 0],
            ];
            
            // Especialidades (agregar ingredientes extra)
            $specialties = [
                ['name' => 'Extra queso', 'price' => 15.00],
                ['name' => 'Extra carne', 'price' => 25.00],
                ['name' => 'Aguacate', 'price' => 20.00],
                ['name' => 'Tocino', 'price' => 18.00],
                ['name' => 'Doble carne', 'price' => 45.00],
                ['name' => 'Extra verduras', 'price' => 12.00],
            ];
            
            // Crear observaciones
            foreach ($observations as $obs) {
                Platilloption::create([
                    'product_id' => $product->id,
                    'name' => $obs['name'],
                    'type' => 'observation',
                    'price' => $obs['price'],
                    'is_active' => true,
                ]);
            }
            
            // Crear especialidades
            foreach ($specialties as $spec) {
                Platilloption::create([
                    'product_id' => $product->id,
                    'name' => $spec['name'],
                    'type' => 'specialty',
                    'price' => $spec['price'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
