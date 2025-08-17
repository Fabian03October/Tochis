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
            ['name' => 'Hamburguesa Clásica', 'barcode' => '7501055363001', 'price' => 85.00, 'cost' => 45.00, 'stock' => 30, 'category' => 'Hamburguesas', 'is_food' => true],
            ['name' => 'Hamburguesa con Queso', 'barcode' => '7501055363002', 'price' => 95.00, 'cost' => 50.00, 'stock' => 25, 'category' => 'Hamburguesas', 'is_food' => true],
            ['name' => 'Hamburguesa BBQ', 'barcode' => '7501055363003', 'price' => 110.00, 'cost' => 60.00, 'stock' => 20, 'category' => 'Hamburguesas', 'is_food' => true],
            ['name' => 'Hamburguesa Doble Carne', 'barcode' => '7501055363004', 'price' => 135.00, 'cost' => 75.00, 'stock' => 15, 'category' => 'Hamburguesas', 'is_food' => true],
            ['name' => 'Hamburguesa Vegetariana', 'barcode' => '7501055363005', 'price' => 90.00, 'cost' => 48.00, 'stock' => 20, 'category' => 'Hamburguesas', 'is_food' => true],

            // Pizzas
            ['name' => 'Pizza Margarita Personal', 'barcode' => '7501055363010', 'price' => 120.00, 'cost' => 65.00, 'stock' => 15, 'category' => 'Pizzas', 'is_food' => true],
            ['name' => 'Pizza Pepperoni Personal', 'barcode' => '7501055363011', 'price' => 140.00, 'cost' => 75.00, 'stock' => 12, 'category' => 'Pizzas', 'is_food' => true],
            ['name' => 'Pizza Hawaiana Personal', 'barcode' => '7501055363012', 'price' => 145.00, 'cost' => 78.00, 'stock' => 10, 'category' => 'Pizzas', 'is_food' => true],
            ['name' => 'Pizza Suprema Familiar', 'barcode' => '7501055363013', 'price' => 350.00, 'cost' => 180.00, 'stock' => 8, 'category' => 'Pizzas', 'is_food' => true],

            // Pollo Frito
            ['name' => 'Pollo Frito 2 Piezas', 'barcode' => '7501055363020', 'price' => 95.00, 'cost' => 50.00, 'stock' => 25, 'category' => 'Pollo Frito', 'is_food' => true],
            ['name' => 'Pollo Frito 4 Piezas', 'barcode' => '7501055363021', 'price' => 180.00, 'cost' => 95.00, 'stock' => 15, 'category' => 'Pollo Frito', 'is_food' => true],
            ['name' => 'Nuggets de Pollo 10 pzs', 'barcode' => '7501055363022', 'price' => 85.00, 'cost' => 45.00, 'stock' => 20, 'category' => 'Pollo Frito', 'is_food' => true],
            ['name' => 'Alitas Picantes 8 pzs', 'barcode' => '7501055363023', 'price' => 120.00, 'cost' => 65.00, 'stock' => 18, 'category' => 'Pollo Frito', 'is_food' => true],

            // Papas Fritas
            ['name' => 'Papas Fritas Chicas', 'barcode' => '7501055363030', 'price' => 35.00, 'cost' => 18.00, 'stock' => 50, 'category' => 'Papas Fritas', 'is_food' => true],
            ['name' => 'Papas Fritas Medianas', 'barcode' => '7501055363031', 'price' => 45.00, 'cost' => 23.00, 'stock' => 40, 'category' => 'Papas Fritas', 'is_food' => true],
            ['name' => 'Papas Fritas Grandes', 'barcode' => '7501055363032', 'price' => 55.00, 'cost' => 28.00, 'stock' => 35, 'category' => 'Papas Fritas', 'is_food' => true],
            ['name' => 'Papas con Queso', 'barcode' => '7501055363033', 'price' => 65.00, 'cost' => 33.00, 'stock' => 25, 'category' => 'Papas Fritas', 'is_food' => true],

            // Bebidas
            ['name' => 'Coca Cola 600ml', 'barcode' => '7501055363040', 'price' => 25.00, 'cost' => 12.00, 'stock' => 100, 'category' => 'Bebidas', 'is_food' => false],
            ['name' => 'Sprite 600ml', 'barcode' => '7501055363041', 'price' => 25.00, 'cost' => 12.00, 'stock' => 80, 'category' => 'Bebidas', 'is_food' => false],
            ['name' => 'Agua Natural 500ml', 'barcode' => '7501055363042', 'price' => 18.00, 'cost' => 8.00, 'stock' => 120, 'category' => 'Bebidas', 'is_food' => false],
            ['name' => 'Jugo de Naranja 400ml', 'barcode' => '7501055363043', 'price' => 30.00, 'cost' => 15.00, 'stock' => 40, 'category' => 'Bebidas', 'is_food' => false],
            ['name' => 'Café Americano', 'barcode' => '7501055363044', 'price' => 22.00, 'cost' => 8.00, 'stock' => 60, 'category' => 'Bebidas', 'is_food' => false],

            // Postres
            ['name' => 'Helado de Vainilla', 'barcode' => '7501055363050', 'price' => 45.00, 'cost' => 22.00, 'stock' => 30, 'category' => 'Postres', 'is_food' => true],
            ['name' => 'Helado de Chocolate', 'barcode' => '7501055363051', 'price' => 45.00, 'cost' => 22.00, 'stock' => 25, 'category' => 'Postres', 'is_food' => true],
            ['name' => 'Pay de Manzana', 'barcode' => '7501055363052', 'price' => 35.00, 'cost' => 18.00, 'stock' => 15, 'category' => 'Postres', 'is_food' => true],
            ['name' => 'Galletas con Chispas', 'barcode' => '7501055363053', 'price' => 28.00, 'cost' => 12.00, 'stock' => 40, 'category' => 'Postres', 'is_food' => true],

            // Ensaladas
            ['name' => 'Ensalada César', 'barcode' => '7501055363060', 'price' => 75.00, 'cost' => 35.00, 'stock' => 20, 'category' => 'Ensaladas', 'is_food' => true],
            ['name' => 'Ensalada Mixta', 'barcode' => '7501055363061', 'price' => 65.00, 'cost' => 30.00, 'stock' => 25, 'category' => 'Ensaladas', 'is_food' => true],
            ['name' => 'Ensalada de Pollo', 'barcode' => '7501055363062', 'price' => 85.00, 'cost' => 45.00, 'stock' => 18, 'category' => 'Ensaladas', 'is_food' => true],

            // Wraps y Burritos
            ['name' => 'Wrap de Pollo', 'barcode' => '7501055363070', 'price' => 70.00, 'cost' => 35.00, 'stock' => 22, 'category' => 'Wraps y Burritos', 'is_food' => true],
            ['name' => 'Burrito de Carne', 'barcode' => '7501055363071', 'price' => 80.00, 'cost' => 40.00, 'stock' => 20, 'category' => 'Wraps y Burritos', 'is_food' => true],
            ['name' => 'Wrap Vegetariano', 'barcode' => '7501055363072', 'price' => 65.00, 'cost' => 32.00, 'stock' => 18, 'category' => 'Wraps y Burritos', 'is_food' => true],
            ['name' => 'Quesadilla Grande', 'barcode' => '7501055363073', 'price' => 55.00, 'cost' => 28.00, 'stock' => 25, 'category' => 'Wraps y Burritos', 'is_food' => true],
        ];

        foreach ($products as $productData) {
            $category = Category::where('name', $productData['category'])->first();
            
            if ($category) {
                Product::updateOrCreate(
                    ['barcode' => $productData['barcode']], // Buscar por barcode
                    [
                        'name' => $productData['name'],
                        'price' => $productData['price'],
                        'cost' => $productData['cost'],
                        'stock' => $productData['stock'],
                        'min_stock' => 10, // Stock mínimo por defecto
                        'category_id' => $category->id,
                        'is_food' => $productData['is_food'],
                    ]
                );
            }
        }
    }
}
