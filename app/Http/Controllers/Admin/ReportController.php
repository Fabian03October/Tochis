<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Filtros de fecha
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();

        // Estadísticas generales
        $totalSales = Sale::whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->where('status', 'completed')
            ->sum('total');

        $totalTransactions = Sale::whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->where('status', 'completed')
            ->count();

        $totalProductsSold = SaleDetail::whereHas('sale', function($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
                  ->where('status', 'completed');
        })->sum('quantity');

        $averageSale = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;

        // Platillos más vendidos
        $topProducts = Product::select('products.*')
            ->selectRaw('SUM(sale_details.quantity) as total_sold')
            ->join('sale_details', 'products.id', '=', 'sale_details.product_id')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->whereBetween('sales.created_at', [$startDate, $endDate->endOfDay()])
            ->where('sales.status', 'completed')
            ->with('category')
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        // Ventas por categoría
        $salesByCategory = Category::select('categories.*')
            ->selectRaw('SUM(sale_details.quantity) as total_quantity')
            ->selectRaw('SUM(sale_details.subtotal) as total_sales')
            ->selectRaw('COUNT(DISTINCT products.id) as products_count')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('sale_details', 'products.id', '=', 'sale_details.product_id')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.id')
            ->whereBetween('sales.created_at', [$startDate, $endDate->endOfDay()])
            ->where('sales.status', 'completed')
            ->groupBy('categories.id')
            ->orderByDesc('total_sales')
            ->get();

        // Ventas diarias
        $dailySales = Sale::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->where('status', 'completed')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                $item->date = Carbon::parse($item->date)->format('M d');
                return $item;
            });

        // Métodos de pago
        $paymentMethods = Sale::select('payment_method')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('SUM(total) as total')
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->where('status', 'completed')
            ->groupBy('payment_method')
            ->get();

        // Platillos con stock bajo
        $lowStockProducts = Product::where('stock', '<=', DB::raw('min_stock'))
            ->where('is_active', true)
            ->with('category')
            ->orderBy('stock')
            ->limit(10)
            ->get();

        // Ventas recientes
        $recentSales = Sale::with(['user', 'saleDetails'])
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->where('status', 'completed')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        return view('admin.reports.index', compact(
            'startDate',
            'endDate',
            'totalSales',
            'totalTransactions',
            'totalProductsSold',
            'averageSale',
            'topProducts',
            'salesByCategory',
            'dailySales',
            'paymentMethods',
            'lowStockProducts',
            'recentSales'
        ));
    }
}
