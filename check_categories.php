<?php

use Illuminate\Support\Facades\DB;

// Verificar categorías
$categories = DB::table('categories')->select('name', 'is_customizable')->get();

echo "=== ESTADO DE CATEGORÍAS ===\n";
foreach ($categories as $category) {
    $status = $category->is_customizable ? 'SÍ' : 'NO';
    echo "{$category->name} - Personalizable: {$status}\n";
}

// Verificar opciones de personalización
$options = DB::table('product_customization_options')->select('name', 'type', 'is_active')->get();

echo "\n=== OPCIONES DISPONIBLES ===\n";
foreach ($options as $option) {
    $status = $option->is_active ? 'Activa' : 'Inactiva';
    echo "{$option->name} ({$option->type}) - {$status}\n";
}

// Verificar relaciones
$relations = DB::table('category_customization_option as cco')
    ->join('categories as c', 'c.id', '=', 'cco.category_id')
    ->join('product_customization_options as pco', 'pco.id', '=', 'cco.product_customization_option_id')
    ->select('c.name as category_name', 'pco.name as option_name')
    ->get();

echo "\n=== OPCIONES ASIGNADAS A CATEGORÍAS ===\n";
if ($relations->isEmpty()) {
    echo "No hay opciones asignadas a ninguna categoría aún.\n";
} else {
    foreach ($relations as $relation) {
        echo "{$relation->category_name} → {$relation->option_name}\n";
    }
}

echo "\n=== FIN DEL REPORTE ===\n";
