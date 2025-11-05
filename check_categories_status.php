<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Cargar la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 VERIFICANDO ESTADO DE CATEGORÍAS\n";
echo "==================================\n\n";

try {
    $categories = DB::table('categories')->select('id', 'name', 'is_active', 'is_customizable')->get();
    
    foreach ($categories as $category) {
        $status = $category->is_active ? '✅ Activa' : '❌ Inactiva';
        $customizable = $category->is_customizable ? '🛠️ Personalizable' : '📦 No personalizable';
        
        echo "ID: {$category->id} | {$category->name}\n";
        echo "   Estado: {$status}\n";
        echo "   Opciones: {$customizable}\n\n";
    }
    
    echo "Total de categorías: " . count($categories) . "\n";
    echo "Categorías activas: " . collect($categories)->where('is_active', 1)->count() . "\n";
    echo "Categorías inactivas: " . collect($categories)->where('is_active', 0)->count() . "\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
