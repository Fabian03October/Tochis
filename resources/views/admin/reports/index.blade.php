@extends('layouts.app')

@section('title', 'Reportes - Sistema POS')
@section('page-title', 'Reportes y Análisis')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Reportes y Análisis</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Analiza el rendimiento de tu punto de venta con reportes detallados.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <button onclick="exportReport()" class="btn-primary">
                    <i class="fas fa-download mr-2"></i>
                    Exportar Reporte
                </button>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('admin.reports.index') }}" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
            <div class="flex-1">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Inicio
                </label>
                <input type="date" 
                       name="start_date" 
                       id="start_date"
                       value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="flex-1">
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Fecha de Fin
                </label>
                <input type="date" 
                       name="end_date" 
                       id="end_date"
                       value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="flex-shrink-0">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-filter mr-2"></i>
                    Filtrar
                </button>
            </div>

            <div class="flex-shrink-0">
                <a href="{{ route('admin.reports.index') }}" class="btn-secondary">
                    <i class="fas fa-refresh mr-2"></i>
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-green-600"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Ventas Totales</dt>
                        <dd class="text-lg font-medium text-gray-900">${{ number_format($totalSales, 2) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Transacciones</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($totalTransactions) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-cube text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Productos Vendidos</dt>
                        <dd class="text-lg font-medium text-gray-900">{{ number_format($totalProductsSold) }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-gray-100 rounded-md flex items-center justify-center">
                        <i class="fas fa-chart-line text-gray-600"></i>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Promedio por Venta</dt>
                        <dd class="text-lg font-medium text-gray-900">${{ number_format($averageSale, 2) }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Top Products -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-star mr-2 text-yellow-600"></i>
                    Productos Más Vendidos
                </h3>
            </div>
            
            <div class="p-6">
                @if($topProducts->count() > 0)
                    <div class="space-y-4">
                        @foreach($topProducts as $product)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        @if($product->image)
                                            <img src="{{ Storage::url($product->image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="h-10 w-10 object-cover rounded-lg">
                                        @else
                                            <div class="h-10 w-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-box text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-green-600">{{ $product->total_sold }}</p>
                                    <p class="text-sm text-gray-500">vendidos</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-chart-bar text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No hay datos de productos para el período seleccionado.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sales by Category -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-tags mr-2 text-blue-600"></i>
                    Ventas por Categoría
                </h3>
            </div>
            
            <div class="p-6">
                @if($salesByCategory->count() > 0)
                    <div class="space-y-4">
                        @foreach($salesByCategory as $category)
                            <div class="flex items-center justify-between p-4 rounded-lg"
                                 style="background-color: {{ $category->color }}10;">
                                <div class="flex items-center space-x-3">
                                    <div class="w-4 h-4 rounded-full" 
                                         style="background-color: {{ $category->color }};"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $category->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $category->products_count }} productos</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold" style="color: {{ $category->color }};">
                                        ${{ number_format($category->total_sales, 2) }}
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $category->total_quantity }} vendidos</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-tags text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No hay datos de categorías para el período seleccionado.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Daily Sales Chart -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-chart-area mr-2 text-green-600"></i>
                Ventas Diarias
            </h3>
        </div>
        
        <div class="p-6">
            @if($dailySales->count() > 0)
                <div class="h-64 relative">
                    <canvas id="dailySalesChart"></canvas>
                </div>
            @else
                <div class="text-center py-16">
                    <i class="fas fa-chart-line text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No hay datos de ventas para el período seleccionado.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Payment Methods -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-credit-card mr-2 text-purple-600"></i>
                    Métodos de Pago
                </h3>
            </div>
            
            <div class="p-6">
                @if($paymentMethods->count() > 0)
                    <div class="space-y-4">
                        @foreach($paymentMethods as $method)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        @if($method->payment_method === 'cash')
                                            <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                                                <i class="fas fa-money-bill text-green-600"></i>
                                            </div>
                                        @elseif($method->payment_method === 'card')
                                            <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                                                <i class="fas fa-credit-card text-blue-600"></i>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                                                <i class="fas fa-mobile-alt text-purple-600"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            @if($method->payment_method === 'cash')
                                                Efectivo
                                            @elseif($method->payment_method === 'card')
                                                Tarjeta
                                            @else
                                                Otro
                                            @endif
                                        </p>
                                        <p class="text-sm text-gray-500">{{ $method->count }} transacciones</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-900">${{ number_format($method->total, 2) }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ number_format(($method->total / $totalSales) * 100, 1) }}%
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-credit-card text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No hay datos de métodos de pago para el período seleccionado.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-exclamation-triangle mr-2 text-red-600"></i>
                    Productos con Stock Bajo
                </h3>
            </div>
            
            <div class="p-6">
                @if($lowStockProducts->count() > 0)
                    <div class="space-y-4">
                        @foreach($lowStockProducts as $product)
                            <div class="flex items-center justify-between p-4 bg-red-50 rounded-lg border border-red-200">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        @if($product->image)
                                            <img src="{{ Storage::url($product->image) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="h-10 w-10 object-cover rounded-lg">
                                        @else
                                            <div class="h-10 w-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-box text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $product->category->name }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-red-600">{{ $product->stock }}</p>
                                    <p class="text-sm text-gray-500">Min: {{ $product->min_stock }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.products.index', ['status' => '1']) }}" 
                           class="text-sm text-blue-600 hover:text-blue-800">
                            Ver todos los productos →
                        </a>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-4xl text-green-300 mb-4"></i>
                        <p class="text-green-600 font-medium">¡Excelente!</p>
                        <p class="text-gray-500">Todos los productos tienen stock suficiente.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Sales -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-clock mr-2 text-indigo-600"></i>
                Ventas Recientes
            </h3>
        </div>
        
        @if($recentSales->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Venta
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cajero
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Artículos
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Método
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentSales as $sale)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $sale->sale_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $sale->user->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $sale->created_at->format('d/m/Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $sale->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $sale->saleDetails->sum('quantity') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($sale->payment_method === 'cash') bg-green-100 text-green-800
                                        @elseif($sale->payment_method === 'card') bg-blue-100 text-blue-800
                                        @else bg-purple-100 text-purple-800 @endif">
                                        {{ ucfirst($sale->payment_method) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-green-600">${{ number_format($sale->total, 2) }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">No hay ventas para el período seleccionado.</p>
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Daily Sales Chart
@if($dailySales->count() > 0)
const ctx = document.getElementById('dailySalesChart').getContext('2d');
const dailySalesChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($dailySales->pluck('date')),
        datasets: [{
            label: 'Ventas Diarias',
            data: @json($dailySales->pluck('total')),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 2,
            fill: true,
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        }
    }
});
@endif

function exportReport() {
    // Esta función puede implementar la exportación a PDF/Excel
    alert('Función de exportación en desarrollo');
}
</script>
@endsection
