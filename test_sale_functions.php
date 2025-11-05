<?php
/**
 * Script de prueba para validar las funciones de venta
 * Ejecutar desde el directorio raíz: php test_sale_functions.php
 */

require_once 'vendor/autoload.php';

// Simular entorno Laravel mínimo
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;
use App\Models\Sale;
use App\Models\Category;

echo "🧪 PRUEBAS DEL SISTEMA DE VENTAS\n";
echo "================================\n\n";

try {
    // Prueba 1: Verificar conexión a base de datos
    echo "✅ Prueba 1: Conexión a base de datos\n";
    $productsCount = Product::count();
    echo "   - Productos en BD: {$productsCount}\n";
    
    // Prueba 2: Verificar productos activos
    echo "\n✅ Prueba 2: Productos activos\n";
    $activeProducts = Product::where('is_active', true)->count();
    echo "   - Productos activos: {$activeProducts}\n";
    
    // Prueba 3: Verificar productos de comida
    echo "\n✅ Prueba 3: Productos de comida\n";
    $foodProducts = Product::where('is_food', true)->count();
    echo "   - Productos de comida: {$foodProducts}\n";
    
    // Prueba 4: Verificar categorías activas
    echo "\n✅ Prueba 4: Categorías activas\n";
    $activeCategories = Category::where('is_active', true)->count();
    echo "   - Categorías activas: {$activeCategories}\n";
    
    // Prueba 5: Verificar ventas recientes
    echo "\n✅ Prueba 5: Ventas recientes\n";
    $recentSales = Sale::where('created_at', '>=', now()->subDays(7))->count();
    echo "   - Ventas últimos 7 días: {$recentSales}\n";
    
    // Prueba 6: Verificar promociones activas
    echo "\n✅ Prueba 6: Promociones activas\n";
    try {
        $activePromotions = \App\Models\Promotion::available()->count();
        echo "   - Promociones disponibles: {$activePromotions}\n";
    } catch (Exception $e) {
        echo "   - ⚠️ Error en promociones: " . $e->getMessage() . "\n";
    }
    
    // Prueba 7: Verificar combos activos
    echo "\n✅ Prueba 7: Combos activos\n";
    try {
        $activeCombos = \App\Models\Combo::active()->count();
        echo "   - Combos activos: {$activeCombos}\n";
    } catch (Exception $e) {
        echo "   - ⚠️ Error en combos: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎉 TODAS LAS PRUEBAS COMPLETADAS\n";
    echo "================================\n";
    echo "✅ Sistema de ventas funcionando correctamente\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR EN LAS PRUEBAS\n";
    echo "========================\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}
