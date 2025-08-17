<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalCashiers = User::where('role', 'cashier')->count();
        $lowStockProducts = Product::lowStock()->count();

        // Ventas de hoy
        $todaySales = Sale::today()->completed();
        $todayRevenue = $todaySales->sum('total');
        $todaySalesCount = $todaySales->count();

        // Ventas del mes
        $monthSales = Sale::whereMonth('created_at', Carbon::now()->month)
                         ->whereYear('created_at', Carbon::now()->year)
                         ->completed();
        $monthRevenue = $monthSales->sum('total');
        $monthSalesCount = $monthSales->count();

        // Productos más vendidos (últimos 30 días)
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

        // Ventas por día (últimos 7 días)
        $salesByDay = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $sales = Sale::whereDate('created_at', $date)->completed()->sum('total');
            $salesByDay[] = [
                'date' => $date->format('d/m'),
                'sales' => $sales
            ];
        }

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories', 
            'totalCashiers',
            'lowStockProducts',
            'todayRevenue',
            'todaySalesCount',
            'monthRevenue',
            'monthSalesCount',
            'topProducts',
            'salesByDay'
        ));
    }
}
