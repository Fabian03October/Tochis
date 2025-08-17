<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductCustomizationOption;

class ProductCustomizationOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Observaciones (elementos a quitar)
        $observations = [
            ['name' => 'Sin tomate', 'price' => 0.00, 'sort_order' => 1],
            ['name' => 'Sin cebolla', 'price' => 0.00, 'sort_order' => 2],
            ['name' => 'Sin lechuga', 'price' => 0.00, 'sort_order' => 3],
            ['name' => 'Sin queso', 'price' => 0.00, 'sort_order' => 4],
            ['name' => 'Sin mayonesa', 'price' => 0.00, 'sort_order' => 5],
            ['name' => 'Sin ketchup', 'price' => 0.00, 'sort_order' => 6],
            ['name' => 'Sin mostaza', 'price' => 0.00, 'sort_order' => 7],
            ['name' => 'Sin pepinillos', 'price' => 0.00, 'sort_order' => 8],
            ['name' => 'Sin pan', 'price' => 0.00, 'sort_order' => 9],
            ['name' => 'Sin sal', 'price' => 0.00, 'sort_order' => 10],
            ['name' => 'Sin picante', 'price' => 0.00, 'sort_order' => 11],
            ['name' => 'Sin hielo', 'price' => 0.00, 'sort_order' => 12],
            ['name' => 'Sin azúcar', 'price' => 0.00, 'sort_order' => 13],
            ['name' => 'Sin cilantro', 'price' => 0.00, 'sort_order' => 14],
            ['name' => 'Sin salsa', 'price' => 0.00, 'sort_order' => 15],
        ];

        foreach ($observations as $observation) {
            ProductCustomizationOption::create([
                'name' => $observation['name'],
                'type' => 'observation',
                'price' => $observation['price'],
                'sort_order' => $observation['sort_order'],
                'is_active' => true
            ]);
        }

        // Especialidades (elementos a agregar con precio)
        $specialties = [
            ['name' => 'Extra queso', 'price' => 15.00, 'sort_order' => 1],
            ['name' => 'Extra carne', 'price' => 25.00, 'sort_order' => 2],
            ['name' => 'Extra tocino', 'price' => 20.00, 'sort_order' => 3],
            ['name' => 'Aguacate', 'price' => 12.00, 'sort_order' => 4],
            ['name' => 'Extra tomate', 'price' => 5.00, 'sort_order' => 5],
            ['name' => 'Extra lechuga', 'price' => 5.00, 'sort_order' => 6],
            ['name' => 'Extra cebolla', 'price' => 5.00, 'sort_order' => 7],
            ['name' => 'Extra papas', 'price' => 18.00, 'sort_order' => 8],
            ['name' => 'Queso americano', 'price' => 10.00, 'sort_order' => 9],
            ['name' => 'Queso suizo', 'price' => 15.00, 'sort_order' => 10],
            ['name' => 'Salsa BBQ', 'price' => 8.00, 'sort_order' => 11],
            ['name' => 'Salsa ranch', 'price' => 8.00, 'sort_order' => 12],
            ['name' => 'Salsa picante', 'price' => 5.00, 'sort_order' => 13],
            ['name' => 'Extra grande', 'price' => 20.00, 'sort_order' => 14],
            ['name' => 'Extra frío', 'price' => 0.00, 'sort_order' => 15],
            ['name' => 'Con limón', 'price' => 3.00, 'sort_order' => 16],
            ['name' => 'Pan integral', 'price' => 8.00, 'sort_order' => 17],
            ['name' => 'Pan de ajonjolí', 'price' => 10.00, 'sort_order' => 18],
        ];

        foreach ($specialties as $specialty) {
            ProductCustomizationOption::create([
                'name' => $specialty['name'],
                'type' => 'specialty',
                'price' => $specialty['price'],
                'sort_order' => $specialty['sort_order'],
                'is_active' => true
            ]);
        }
    }
}
