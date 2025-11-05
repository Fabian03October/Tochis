<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\User;
use Carbon\Carbon;

class VerifyDashboardData extends Command
{
    protected $signature = 'dashboard:verify';
    protected $description = 'Verify dashboard data consistency';

    public function handle()
    {
        $this->info('🔍 VERIFICANDO DATOS DEL DASHBOARD ADMIN VS BASE DE DATOS');
        $this->info('================================================================');
        $this->newLine();

        // 1. Verificar total de productos
        $totalProducts = Product::count();
        $this->info('📦 PRODUCTOS:');
        $this->line("   - Total en BD: {$totalProducts}");

        $activeProducts = Product::where('is_active', true)->count();
        $this->line("   - Productos activos: {$activeProducts}");
        $this->newLine();

        // 2. Verificar total de categorías
        $totalCategories = Category::count();
        $this->info('🏷️ CATEGORÍAS:');
        $this->line("   - Total en BD: {$totalCategories}");
        $this->newLine();

        // 3. Verificar total de cajeros
        $totalCashiers = User::where('role', 'cashier')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalUsers = User::count();
        $this->info('👥 USUARIOS:');
        $this->line("   - Total usuarios: {$totalUsers}");
        $this->line("   - Total cajeros: {$totalCashiers}");
        $this->line("   - Total admins: {$totalAdmins}");
        $this->newLine();

        // 4. Verificar productos con stock bajo
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')->count();
        $this->info('⚠️ PRODUCTOS CON STOCK BAJO:');
        $this->line("   - Total con stock bajo: {$lowStockProducts}");

        $lowStockDetails = Product::whereColumn('stock', '<=', 'min_stock')->get();
        if ($lowStockDetails->count() > 0) {
            $this->line("   - Detalles:");
            foreach ($lowStockDetails as $product) {
                $this->line("     * {$product->name}: Stock actual {$product->stock}, Mínimo {$product->min_stock}");
            }
        } else {
            $this->line("   - ✅ No hay productos con stock bajo");
        }
        $this->newLine();

        // 5. Verificar ventas de hoy
        $todaySales = Sale::whereDate('created_at', today())->where('status', 'completed');
        $todayRevenue = $todaySales->sum('total');
        $todaySalesCount = $todaySales->count();

        $this->info('💰 VENTAS DE HOY (' . today()->format('d/m/Y') . '):');
        $this->line("   - Total ingresos: $" . number_format($todayRevenue, 2));
        $this->line("   - Número de ventas: {$todaySalesCount}");
        $this->newLine();

        // 6. Verificar ventas del mes
        $monthSales = Sale::whereMonth('created_at', Carbon::now()->month)
                         ->whereYear('created_at', Carbon::now()->year)
                         ->where('status', 'completed');
        $monthRevenue = $monthSales->sum('total');
        $monthSalesCount = $monthSales->count();

        $this->info('📅 VENTAS DEL MES (' . Carbon::now()->format('m/Y') . '):');
        $this->line("   - Total ingresos: $" . number_format($monthRevenue, 2));
        $this->line("   - Número de ventas: {$monthSalesCount}");
        $this->newLine();

        $this->info('✅ Verificación completada');
        return 0;
    }
}
