<?php

use App\Models\Category;
use App\Models\Product;

echo "=== DIAGNÓSTICO DE CATEGORÍAS Y PRODUCTOS ===\n\n";

$categories = Category::where('is_active', true)->with(['products' => function($query) {
    $query->where('is_active', true);
}])->get();

foreach ($categories as $category) {
    echo "Categoría: {$category->name}\n";
    echo "Productos activos: {$category->products->count()}\n";
    
    if ($category->products->count() > 0) {
        foreach ($category->products as $product) {
            $stockInfo = $product->is_food ? "(comida - sin stock)" : "(stock: {$product->stock})";
            echo "  - {$product->name} {$stockInfo}\n";
        }
    } else {
        echo "  ⚠️ Sin productos activos\n";
    }
    echo "\n";
}

echo "=== RESUMEN ===\n";
echo "Total categorías activas: " . $categories->count() . "\n";
echo "Total productos activos: " . Product::where('is_active', true)->count() . "\n";
echo "Productos de comida: " . Product::where('is_active', true)->where('is_food', true)->count() . "\n";
echo "Productos con stock: " . Product::where('is_active', true)->where('is_food', false)->where('stock', '>', 0)->count() . "\n";
