<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;

echo "🔍 VERIFICANDO DATOS DEL DASHBOARD ADMIN VS BASE DE DATOS\n";
echo "================================================================\n\n";

// 1. Verificar total de productos
$totalProducts = Product::count();
echo "📦 PRODUCTOS:\n";
echo "   - Total en BD: {$totalProducts}\n";

$activeProducts = Product::where('is_active', true)->count();
echo "   - Productos activos: {$activeProducts}\n";

$inactiveProducts = Product::where('is_active', false)->count();
echo "   - Productos inactivos: {$inactiveProducts}\n\n";

// 2. Verificar total de categorías
$totalCategories = Category::count();
echo "🏷️ CATEGORÍAS:\n";
echo "   - Total en BD: {$totalCategories}\n";

$activeCategories = Category::where('is_active', true)->count();
echo "   - Categorías activas: {$activeCategories}\n\n";

// 3. Verificar total de cajeros
$totalCashiers = User::where('role', 'cashier')->count();
$totalAdmins = User::where('role', 'admin')->count();
$totalUsers = User::count();
echo "👥 USUARIOS:\n";
echo "   - Total usuarios: {$totalUsers}\n";
echo "   - Total cajeros: {$totalCashiers}\n";
echo "   - Total admins: {$totalAdmins}\n\n";

// 4. Verificar productos con stock bajo
$lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')->count();
echo "⚠️ PRODUCTOS CON STOCK BAJO:\n";
echo "   - Total con stock bajo: {$lowStockProducts}\n";

// Mostrar detalles de productos con stock bajo
$lowStockDetails = Product::whereColumn('stock', '<=', 'min_stock')->get();
if ($lowStockDetails->count() > 0) {
    echo "   - Detalles:\n";
    foreach ($lowStockDetails as $product) {
        echo "     * {$product->name}: Stock actual {$product->stock}, Mínimo {$product->min_stock}\n";
    }
} else {
    echo "   - ✅ No hay productos con stock bajo\n";
}
echo "\n";

// 5. Verificar ventas de hoy
$todaySales = Sale::whereDate('created_at', today())->where('status', 'completed');
$todayRevenue = $todaySales->sum('total');
$todaySalesCount = $todaySales->count();

echo "💰 VENTAS DE HOY (" . today()->format('d/m/Y') . "):\n";
echo "   - Total ingresos: $" . number_format($todayRevenue, 2) . "\n";
echo "   - Número de ventas: {$todaySalesCount}\n";

// Verificar todas las ventas de hoy (incluyendo no completadas)
$allTodaySales = Sale::whereDate('created_at', today())->count();
$pendingSales = Sale::whereDate('created_at', today())->where('status', '!=', 'completed')->count();
echo "   - Total ventas (todas): {$allTodaySales}\n";
echo "   - Ventas pendientes: {$pendingSales}\n\n";

// 6. Verificar ventas del mes
$monthSales = Sale::whereMonth('created_at', Carbon::now()->month)
                 ->whereYear('created_at', Carbon::now()->year)
                 ->where('status', 'completed');
$monthRevenue = $monthSales->sum('total');
$monthSalesCount = $monthSales->count();

echo "📅 VENTAS DEL MES (" . Carbon::now()->format('m/Y') . "):\n";
echo "   - Total ingresos: $" . number_format($monthRevenue, 2) . "\n";
echo "   - Número de ventas: {$monthSalesCount}\n\n";

// 7. Verificar productos más vendidos (últimos 30 días)
echo "🏆 PRODUCTOS MÁS VENDIDOS (últimos 30 días):\n";

$topProducts = Product::with(['saleDetails' => function($query) {
    $query->whereHas('sale', function($q) {
        $q->where('created_at', '>=', Carbon::now()->subDays(30))
          ->where('status', 'completed');
    });
}])
->get()
->map(function($product) {
    $totalSold = $product->saleDetails->sum('quantity');
    return [
        'product' => $product,
        'total_sold' => $totalSold
    ];
})
->sortByDesc('total_sold')
->take(5);

if ($topProducts->count() > 0) {
    foreach ($topProducts as $index => $item) {
        $rank = $index + 1;
        echo "   {$rank}. {$item['product']->name}: {$item['total_sold']} vendidos\n";
    }
} else {
    echo "   - No hay ventas en los últimos 30 días\n";
}
echo "\n";

// 8. Verificar ventas por día (últimos 7 días)
echo "📊 VENTAS POR DÍA (últimos 7 días):\n";
for ($i = 6; $i >= 0; $i--) {
    $date = Carbon::now()->subDays($i);
    $sales = Sale::whereDate('created_at', $date)->where('status', 'completed')->sum('total');
    $count = Sale::whereDate('created_at', $date)->where('status', 'completed')->count();
    echo "   {$date->format('d/m/Y')}: $" . number_format($sales, 2) . " ({$count} ventas)\n";
}
echo "\n";

// 9. Verificar integridad de datos
echo "🔧 VERIFICACIÓN DE INTEGRIDAD:\n";

// Verificar si hay ventas sin detalles
$salesWithoutDetails = Sale::whereDoesntHave('saleDetails')->count();
echo "   - Ventas sin detalles: {$salesWithoutDetails}\n";

// Verificar si hay detalles de venta huérfanos
$orphanDetails = \App\Models\SaleDetail::whereDoesntHave('sale')->count();
echo "   - Detalles de venta huérfanos: {$orphanDetails}\n";

// Verificar productos sin categoría
$productsWithoutCategory = Product::whereNull('category_id')->count();
echo "   - Productos sin categoría: {$productsWithoutCategory}\n";

// Verificar categorías sin productos
$categoriesWithoutProducts = Category::whereDoesntHave('products')->count();
echo "   - Categorías sin productos: {$categoriesWithoutProducts}\n";

echo "\n================================================================\n";
echo "✅ Verificación completada\n";
