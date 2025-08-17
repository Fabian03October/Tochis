<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\CashCut;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Corte de caja actual
        $currentCashCut = CashCut::where('user_id', $userId)
                                 ->where('status', 'open')
                                 ->first();

        // Ventas de hoy
        $todaySales = Sale::where('user_id', $userId)
                         ->today()
                         ->completed();

        $todayRevenue = $todaySales->sum('total');
        $todaySalesCount = $todaySales->count();

        // Ventas del turno actual (desde apertura de caja)
        $shiftSales = null;
        $shiftRevenue = 0;
        $shiftSalesCount = 0;

        if ($currentCashCut) {
            $shiftSales = Sale::where('user_id', $userId)
                             ->where('created_at', '>=', $currentCashCut->opened_at)
                             ->completed();
            
            $shiftRevenue = $shiftSales->sum('total');
            $shiftSalesCount = $shiftSales->count();
        }

        // Últimas ventas
        $recentSales = Sale::where('user_id', $userId)
                          ->with(['saleDetails.product'])
                          ->orderBy('created_at', 'desc')
                          ->limit(5)
                          ->get();

        // Estadísticas del cajero
        $totalSalesThisMonth = Sale::where('user_id', $userId)
                                  ->whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year)
                                  ->completed()
                                  ->count();

        $totalRevenueThisMonth = Sale::where('user_id', $userId)
                                    ->whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->completed()
                                    ->sum('total');

        return view('cashier.dashboard', compact(
            'currentCashCut',
            'todayRevenue',
            'todaySalesCount',
            'shiftRevenue',
            'shiftSalesCount',
            'recentSales',
            'totalSalesThisMonth',
            'totalRevenueThisMonth'
        ));
    }
}
