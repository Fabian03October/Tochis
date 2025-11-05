<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Hamburguesas
            ['name' => 'Hamburguesa Clásica', 'price' => 85.00, 'cost' => 45.00, 'category' => 'Hamburguesas', 'is_food' => true],
            ['name' => 'Hamburguesa con Queso', 'price' => 95.00, 'cost' => 50.00, 'category' => 'Hamburguesas', 'is_food' => true],
            ['name' => 'Hamburguesa BBQ', 'price' => 110.00, 'cost' => 60.00, 'category' => 'Hamburguesas', 'is_food' => true],
            ['name' => 'Hamburguesa Doble Carne', 'price' => 135.00, 'cost' => 75.00, 'category' => 'Hamburguesas', 'is_food' => true],
            ['name' => 'Hamburguesa Vegetariana', 'price' => 90.00, 'cost' => 48.00, 'category' => 'Hamburguesas', 'is_food' => true],

            // Pizzas
            ['name' => 'Pizza Margarita Personal', 'price' => 120.00, 'cost' => 65.00, 'category' => 'Pizzas', 'is_food' => true],
            ['name' => 'Pizza Pepperoni Personal', 'price' => 140.00, 'cost' => 75.00, 'category' => 'Pizzas', 'is_food' => true],
            ['name' => 'Pizza Hawaiana Personal', 'price' => 145.00, 'cost' => 78.00, 'category' => 'Pizzas', 'is_food' => true],
            ['name' => 'Pizza Suprema Familiar', 'price' => 350.00, 'cost' => 180.00, 'category' => 'Pizzas', 'is_food' => true],

            // Pollo Frito
            ['name' => 'Pollo Frito 2 Piezas', 'price' => 95.00, 'cost' => 50.00, 'category' => 'Pollo Frito', 'is_food' => true],
            ['name' => 'Pollo Frito 4 Piezas', 'price' => 180.00, 'cost' => 95.00, 'category' => 'Pollo Frito', 'is_food' => true],
            ['name' => 'Nuggets de Pollo 10 pzs', 'price' => 85.00, 'cost' => 45.00, 'category' => 'Pollo Frito', 'is_food' => true],
            ['name' => 'Alitas Picantes 8 pzs', 'price' => 120.00, 'cost' => 65.00, 'category' => 'Pollo Frito', 'is_food' => true],

            // Papas Fritas
            ['name' => 'Papas Fritas Chicas', 'price' => 35.00, 'cost' => 18.00, 'category' => 'Papas Fritas', 'is_food' => true],
            ['name' => 'Papas Fritas Medianas', 'price' => 45.00, 'cost' => 23.00, 'category' => 'Papas Fritas', 'is_food' => true],
            ['name' => 'Papas Fritas Grandes', 'price' => 55.00, 'cost' => 28.00, 'category' => 'Papas Fritas', 'is_food' => true],
            ['name' => 'Papas con Queso', 'price' => 65.00, 'cost' => 33.00, 'category' => 'Papas Fritas', 'is_food' => true],

            // Bebidas
            ['name' => 'Coca Cola 600ml', 'price' => 25.00, 'cost' => 12.00, 'category' => 'Bebidas', 'is_food' => false],
            ['name' => 'Sprite 600ml', 'price' => 25.00, 'cost' => 12.00, 'category' => 'Bebidas', 'is_food' => false],
            ['name' => 'Agua Natural 500ml', 'price' => 18.00, 'cost' => 8.00, 'category' => 'Bebidas', 'is_food' => false],
            ['name' => 'Jugo de Naranja 400ml', 'price' => 30.00, 'cost' => 15.00, 'category' => 'Bebidas', 'is_food' => false],
            ['name' => 'Café Americano', 'price' => 22.00, 'cost' => 8.00, 'category' => 'Bebidas', 'is_food' => false],

            // Postres
            ['name' => 'Helado de Vainilla', 'price' => 45.00, 'cost' => 22.00, 'category' => 'Postres', 'is_food' => true],
            ['name' => 'Helado de Chocolate', 'price' => 45.00, 'cost' => 22.00, 'category' => 'Postres', 'is_food' => true],
            ['name' => 'Pay de Manzana', 'price' => 35.00, 'cost' => 18.00, 'category' => 'Postres', 'is_food' => true],
            ['name' => 'Galletas con Chispas', 'price' => 28.00, 'cost' => 12.00, 'category' => 'Postres', 'is_food' => true],

            // Ensaladas
            ['name' => 'Ensalada César', 'price' => 75.00, 'cost' => 35.00, 'category' => 'Ensaladas', 'is_food' => true],
            ['name' => 'Ensalada Mixta', 'price' => 65.00, 'cost' => 30.00, 'category' => 'Ensaladas', 'is_food' => true],
            ['name' => 'Ensalada de Pollo', 'price' => 85.00, 'cost' => 45.00, 'category' => 'Ensaladas', 'is_food' => true],

            // Wraps y Burritos
            ['name' => 'Wrap de Pollo', 'price' => 70.00, 'cost' => 35.00, 'category' => 'Wraps y Burritos', 'is_food' => true],
            ['name' => 'Burrito de Carne', 'price' => 80.00, 'cost' => 40.00, 'category' => 'Wraps y Burritos', 'is_food' => true],
            ['name' => 'Wrap Vegetariano', 'price' => 65.00, 'cost' => 32.00, 'category' => 'Wraps y Burritos', 'is_food' => true],
            ['name' => 'Quesadilla Grande', 'price' => 55.00, 'cost' => 28.00, 'category' => 'Wraps y Burritos', 'is_food' => true],
        ];

        foreach ($products as $productData) {
            $category = Category::where('name', $productData['category'])->first();
            
            if ($category) {
                Product::updateOrCreate(
                    ['name' => $productData['name']], // Buscar por nombre
                    [
                        'price' => $productData['price'],
                        'cost' => $productData['cost'],
                        'category_id' => $category->id,
                        'is_food' => $productData['is_food'],
                    ]
                );
            }
        }
    }
}
