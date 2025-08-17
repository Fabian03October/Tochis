<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductCustomizationOption;

class CustomizationOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Observaciones (quitar ingredientes)
        $observations = [
            'Sin tomate',
            'Sin cebolla',
            'Sin verduras',
            'Sin picante',
            'Sin salsa',
            'Sin ajo',
            'Sin limón',
            'Sin cilantro'
        ];

        foreach ($observations as $index => $observation) {
            ProductCustomizationOption::create([
                'name' => $observation,
                'type' => 'observation',
                'price' => 0.00,
                'sort_order' => $index,
                'is_active' => true
            ]);
        }

        // Especialidades (agregar ingredientes con precio)
        $specialties = [
            ['name' => 'Extra queso', 'price' => 15.00],
            ['name' => 'Extra carne', 'price' => 25.00],
            ['name' => 'Extra tocino', 'price' => 20.00],
            ['name' => 'Doble porción', 'price' => 30.00],
            ['name' => 'Extra aguacate', 'price' => 12.00],
            ['name' => 'Extra salsa', 'price' => 5.00],
            ['name' => 'Pan integral', 'price' => 8.00],
            ['name' => 'Extra verduras', 'price' => 10.00]
        ];

        foreach ($specialties as $index => $specialty) {
            ProductCustomizationOption::create([
                'name' => $specialty['name'],
                'type' => 'specialty',
                'price' => $specialty['price'],
                'sort_order' => $index,
                'is_active' => true
            ]);
        }
    }
}
