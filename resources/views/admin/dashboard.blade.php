@extends('layouts.app')

@section('title', 'Dashboard - Administrador')
@section('page-title', 'Dashboard del Administrador')

@section('content')
<div class="fade-in">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Products -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-box text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Platillos</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>

        <!-- Total Categories -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-tags text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Categorías</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalCategories }}</p>
                </div>
            </div>
        </div>

        <!-- Total Cashiers -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Cajeros</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalCashiers }}</p>
                </div>
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Stock Bajo</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $lowStockProducts }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Today's Sales -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-calendar-day mr-2 text-blue-600"></i>
                Ventas de Hoy
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Ventas</p>
                    <p class="text-2xl font-bold text-green-600">${{ number_format($todayRevenue, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Número de Ventas</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $todaySalesCount }}</p>
                </div>
            </div>
        </div>

        <!-- Month's Sales -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-calendar-alt mr-2 text-green-600"></i>
                Ventas del Mes
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Ventas</p>
                    <p class="text-2xl font-bold text-green-600">${{ number_format($monthRevenue, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">Número de Ventas</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $monthSalesCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Lists -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-chart-line mr-2 text-purple-600"></i>
                Ventas por Día (Últimos 7 días)
            </h3>
            <div class="space-y-3">
                @foreach($salesByDay as $day)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">{{ $day['date'] }}</span>
                        <div class="flex items-center">
                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                <div class="bg-blue-600 h-2 rounded-full" 
                                     style="width: {{ $monthRevenue > 0 ? ($day['sales'] / $monthRevenue * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-900">${{ number_format($day['sales'], 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-trophy mr-2 text-yellow-600"></i>
                Platillos Más Vendidos (30 días)
            </h3>
            <div class="space-y-3">
                @forelse($topProducts as $item)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">{{ $item['product']->name }}</p>
                            <p class="text-sm text-gray-600">{{ $item['product']->category->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-blue-600">{{ $item['total_sold'] }}</p>
                            <p class="text-xs text-gray-500">unidades</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-4">No hay datos disponibles</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-bolt mr-2 text-gray-600"></i>
                Acciones Rápidas
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.products.create') }}" class="btn-primary flex items-center justify-center">
                    <i class="fas fa-plus-circle mr-3"></i>
                    <span>Agregar Platillo</span>
                </a>

                <a href="{{ route('admin.categories.create') }}" class="btn-success flex items-center justify-center">
                    <i class="fas fa-plus-circle mr-3"></i>
                    <span>Nueva Categoría</span>
                </a>

                <a href="{{ route('admin.reports.sales') }}" class="btn-secondary flex items-center justify-center">
                    <i class="fas fa-chart-bar mr-3"></i>
                    <span>Ver Reportes</span>
                </a>

                <a href="{{ route('admin.products.index') }}?filter=low_stock" class="btn-warning flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle mr-3"></i>
                    <span>Stock Bajo</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
