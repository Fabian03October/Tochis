<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Hamburguesas',
                'description' => 'Hamburguesas clásicas, especiales y gourmet',
                'color' => '#DC2626',
                'is_customizable' => true,
            ],
            [
                'name' => 'Pizzas',
                'description' => 'Pizzas individuales y familiares con diferentes ingredientes',
                'color' => '#F59E0B',
                'is_customizable' => true,
            ],
            [
                'name' => 'Pollo Frito',
                'description' => 'Piezas de pollo frito, nuggets y alitas',
                'color' => '#EF4444',
                'is_customizable' => true,
            ],
            [
                'name' => 'Papas Fritas',
                'description' => 'Papas fritas en diferentes tamaños y estilos',
                'color' => '#F59E0B',
                'is_customizable' => true,
            ],
            [
                'name' => 'Bebidas',
                'description' => 'Refrescos, jugos, agua y bebidas calientes',
                'color' => '#3B82F6',
                'is_customizable' => true,
            ],
            [
                'name' => 'Postres',
                'description' => 'Helados, pasteles, galletas y dulces',
                'color' => '#EC4899',
                'is_customizable' => false,
            ],
            [
                'name' => 'Ensaladas',
                'description' => 'Ensaladas frescas y saludables',
                'color' => '#10B981',
                'is_customizable' => true,
            ],
            [
                'name' => 'Wraps y Burritos',
                'description' => 'Wraps, burritos y tacos',
                'color' => '#84CC16',
                'is_customizable' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
