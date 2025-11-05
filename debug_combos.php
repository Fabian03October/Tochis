<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Combo;
use App\Models\Product;

echo "🔍 VERIFICANDO COMBOS EN LA BASE DE DATOS\n";
echo "==========================================\n\n";

try {
    // Verificar combos totales
    $totalCombos = Combo::count();
    echo "📊 Total de combos en BD: {$totalCombos}\n";
    
    // Verificar combos activos
    $activeCombos = Combo::where('is_active', true)->count();
    echo "✅ Combos activos: {$activeCombos}\n";
    
    // Verificar combos con auto_suggest
    $autoSuggestCombos = Combo::where('is_active', true)->where('auto_suggest', true)->count();
    echo "🎯 Combos con auto-suggest: {$autoSuggestCombos}\n\n";
    
    // Listar todos los combos
    echo "📋 LISTA DE COMBOS:\n";
    echo "-------------------\n";
    
    $combos = Combo::with('products')->get();
    foreach ($combos as $combo) {
        echo "ID: {$combo->id}\n";
        echo "Nombre: {$combo->name}\n";
        echo "Activo: " . ($combo->is_active ? 'SÍ' : 'NO') . "\n";
        echo "Auto-suggest: " . ($combo->auto_suggest ? 'SÍ' : 'NO') . "\n";
        echo "Precio: \${$combo->price}\n";
        echo "Productos ({$combo->products->count()}):\n";
        
        foreach ($combo->products as $product) {
            echo "  - {$product->name} (ID: {$product->id})\n";
        }
        echo "\n";
    }
    
    // Verificar productos en carrito de ejemplo
    echo "🛒 VERIFICANDO PRODUCTOS PARA COMBO:\n";
    echo "------------------------------------\n";
    
    $productIds = [1, 2]; // IDs de los productos que veo en tu imagen
    foreach ($productIds as $id) {
        $product = Product::find($id);
        if ($product) {
            echo "ID {$id}: {$product->name} - \${$product->price}\n";
        } else {
            echo "ID {$id}: PRODUCTO NO ENCONTRADO\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
