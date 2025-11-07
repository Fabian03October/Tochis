<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Combo;
use App\Models\Product;

class ComboSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Combo 1: Combo Tochis Familiar
        $combo1 = Combo::create([
            'name' => 'Combo Tochis Familiar',
            'description' => 'Perfecto para compartir en familia: 2 hamburguesas, 1 coca cola y 1 helado',
            'price' => 200.00,
            'original_price' => 0, // Se calculará después
            'discount_amount' => 0, // Se calculará después
            'is_active' => true,
            'min_items' => 3,
            'auto_suggest' => true
        ]);

        // Obtener Platillos para el combo 1
        $hamburguesa1 = Product::where('name', 'LIKE', '%Hamburguesa Clásica%')->first();
        $hamburguesa2 = Product::where('name', 'LIKE', '%Hamburguesa con Queso%')->first();
        $cocaCola = Product::where('name', 'LIKE', '%Coca Cola%')->first();
        $helado = Product::where('name', 'LIKE', '%Helado%')->first();

        if ($hamburguesa1 && $hamburguesa2 && $cocaCola && $helado) {
            // Asociar Platillos al combo
            $combo1->products()->attach([
                $hamburguesa1->id => ['quantity' => 1, 'is_required' => true, 'is_alternative' => false],
                $hamburguesa2->id => ['quantity' => 1, 'is_required' => true, 'is_alternative' => false],
                $cocaCola->id => ['quantity' => 1, 'is_required' => true, 'is_alternative' => false],
                $helado->id => ['quantity' => 1, 'is_required' => false, 'is_alternative' => true],
            ]);

            // Calcular precio original
            $originalPrice = ($hamburguesa1->price + $hamburguesa2->price + $cocaCola->price + $helado->price);
            $combo1->update([
                'original_price' => $originalPrice,
                'discount_amount' => $originalPrice - 200.00
            ]);
        }

        // Combo 2: Combo Pollo Supremo
        $combo2 = Combo::create([
            'name' => 'Combo Pollo Supremo',
            'description' => 'Lo mejor del pollo: 2 piezas de pollo frito, papas fritas y bebida',
            'price' => 150.00,
            'original_price' => 0,
            'discount_amount' => 0,
            'is_active' => true,
            'min_items' => 2,
            'auto_suggest' => true
        ]);

        $pollo = Product::where('name', 'LIKE', '%Pollo Frito%')->first();
        $papas = Product::where('name', 'LIKE', '%Papas%')->first();
        $bebida = Product::where('name', 'LIKE', '%Pepsi%')->first();

        if ($pollo && $papas && $bebida) {
            $combo2->products()->attach([
                $pollo->id => ['quantity' => 2, 'is_required' => true, 'is_alternative' => false],
                $papas->id => ['quantity' => 1, 'is_required' => true, 'is_alternative' => false],
                $bebida->id => ['quantity' => 1, 'is_required' => true, 'is_alternative' => false],
            ]);

            $originalPrice = ($pollo->price * 2) + $papas->price + $bebida->price;
            $combo2->update([
                'original_price' => $originalPrice,
                'discount_amount' => $originalPrice - 150.00
            ]);
        }

        // Combo 3: Combo Pizza Party
        $combo3 = Combo::create([
            'name' => 'Combo Pizza Party',
            'description' => 'Para los amantes de la pizza: 1 pizza familiar, 2 bebidas',
            'price' => 180.00,
            'original_price' => 0,
            'discount_amount' => 0,
            'is_active' => true,
            'min_items' => 2,
            'auto_suggest' => true
        ]);

        $pizza = Product::where('name', 'LIKE', '%Pizza Suprema Familiar%')->first();
        $bebida1 = Product::where('name', 'LIKE', '%Coca Cola%')->first();
        $bebida2 = Product::where('name', 'LIKE', '%Sprite%')->first();

        if ($pizza && $bebida1 && $bebida2) {
            $combo3->products()->attach([
                $pizza->id => ['quantity' => 1, 'is_required' => true, 'is_alternative' => false],
                $bebida1->id => ['quantity' => 1, 'is_required' => false, 'is_alternative' => true],
                $bebida2->id => ['quantity' => 1, 'is_required' => false, 'is_alternative' => true],
            ]);

            $originalPrice = $pizza->price + $bebida1->price + $bebida2->price;
            $combo3->update([
                'original_price' => $originalPrice,
                'discount_amount' => $originalPrice - 180.00
            ]);
        }

        $this->command->info('Combos de ejemplo creados exitosamente');
    }
}
