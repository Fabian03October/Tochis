<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Combo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Muestra la vista de reportes con los datos.
     */
    public function index(Request $request)
    {
        // 1. Obtenemos todos los datos desde nuestro nuevo método
        $data = $this->getReportData($request);

        // 2. Pasamos los datos a la vista
        //    Usamos compact() o simplemente pasamos el array $data
        return view('admin.reports.index', $data);
    }

    /**
     * Genera y exporta el reporte en PDF.
     */
    public function exportPdf(Request $request)
    {
        try {
            // 1. Obtener los datos del reporte
            $data = $this->getReportData($request);

            // 2. Generar el PDF con DomPDF (mucho más simple)
            $pdf = Pdf::loadView('admin.reports.pdf-simple', $data);
            
            // 3. Configurar el PDF
            $pdf->setPaper('A4', 'portrait');
            
            // 4. Generar el nombre del archivo
            $filename = 'reporte_ventas_' . $data['startDate']->format('Y-m-d') . '_al_' . $data['endDate']->format('Y-m-d') . '.pdf';
            
            // 5. Descargar el PDF
            return $pdf->download($filename);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }

    /**
     * Método privado para obtener y unificar toda la lógica de reportes.
     */
    private function getReportData(Request $request)
    {
        // 1. Filtros de fecha (un solo lugar para definirlos)
        // Cambiar el rango por defecto para incluir todas las ventas existentes
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::parse('2025-08-01');
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::parse('2025-10-31');

        // 2. Estadísticas generales
        $salesQuery = Sale::whereBetween('created_at', [$startDate, $endDate->endOfDay()])
                          ->where('status', 'completed');
        
        // Clonamos la consulta base para no repetirla
        $totalSales = (clone $salesQuery)->sum('total');
        $totalTransactions = (clone $salesQuery)->count();

        $totalProductsSold = SaleDetail::whereHas('sale', function($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
                  ->where('status', 'completed');
        })->sum('quantity');

        $averageSale = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;

        // 3. Platillos más vendidos
        $topProducts = Product::select('products.*')
            ->selectRaw('SUM(sale_details.quantity) as total_sold')
            ->join('sale_details', 'products.id', '=', 'sale_details.product_id')
            ->join('sales', 'sale_details.sale_id', 'sales.id')
            ->whereBetween('sales.created_at', [$startDate, $endDate->endOfDay()])
            ->where('sales.status', 'completed')
            ->with('category')
            ->groupBy('products.id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();
            
        // 4. Ventas por categoría
        $salesByCategory = Category::select('categories.*')
            ->selectRaw('SUM(sale_details.quantity) as total_quantity')
            ->selectRaw('SUM(sale_details.subtotal) as total_sales')
            ->selectRaw('COUNT(DISTINCT products.id) as products_count')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('sale_details', 'products.id', '=', 'sale_details.product_id')
            ->join('sales', 'sale_details.sale_id', 'sales.id')
            ->whereBetween('sales.created_at', [$startDate, $endDate->endOfDay()])
            ->where('sales.status', 'completed')
            ->groupBy('categories.id')
            ->orderByDesc('total_sales')
            ->get();

        // 5. Ventas diarias (unificamos la consulta y el formato)
        $dailySales = Sale::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total) as total'))
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->where('status', 'completed')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                $item->date = Carbon::parse($item->date)->format('M d'); // Formato para gráficos
                return $item;
            });

        // 6. Métodos de pago (unificamos)
        $paymentMethods = Sale::select('payment_method')
            ->selectRaw('COUNT(*) as count')
            ->selectRaw('SUM(total) as total')
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->where('status', 'completed')
            ->groupBy('payment_method')
            ->get();

        // 7. Stock bajo (falta en tu exportPdf, ¡pero es útil!)
        $lowStockProducts = Product::where('stock', '<=', DB::raw('min_stock'))
            ->where('is_active', true)
            ->with('category')
            ->orderBy('stock')
            ->limit(10)
            ->get();

        // 8. Ventas recientes (unificamos)
        $recentSales = Sale::with(['user', 'saleDetails.product', 'saleDetails.combo'])
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->where('status', 'completed') // <-- Faltaba este filtro en tu consulta de PDF
            ->orderByDesc('created_at')
            ->limit(20) // Unificamos a 20
            ->get();

        // 9. Top Combos (unificamos)
        $topCombos = Combo::select('combos.*')
            ->selectRaw('COUNT(sale_details.id) as times_sold')
            ->selectRaw('SUM(sale_details.quantity) as total_quantity')
            ->join('sale_details', 'combos.id', '=', 'sale_details.combo_id')
            ->join('sales', 'sale_details.sale_id', 'sales.id')
            ->whereBetween('sales.created_at', [$startDate, $endDate->endOfDay()])
            ->where('sales.status', 'completed')
            ->where('combos.is_active', true)
            ->whereNotNull('sale_details.combo_id')
            ->groupBy('combos.id')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        // 10. Devolvemos todo en un array
        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalSales' => $totalSales,
            'totalTransactions' => $totalTransactions,
            'totalProductsSold' => $totalProductsSold,
            'averageSale' => $averageSale,
            'topProducts' => $topProducts,
            'salesByCategory' => $salesByCategory,
            'dailySales' => $dailySales,
            'paymentMethods' => $paymentMethods,
            'lowStockProducts' => $lowStockProducts,
            'recentSales' => $recentSales,
            'topCombos' => $topCombos
        ];
    }
}