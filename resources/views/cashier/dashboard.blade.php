@extends('layouts.app')

@section('title', 'Dashboard - TOCHIS')
@section('page-title', 'Dashboard del Cajero')

@push('styles')
<style>
    .tochis-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(249, 115, 22, 0.1);
        transition: all 0.3s ease;
    }
    
    .tochis-card:hover {
        box-shadow: 0 8px 30px rgba(249, 115, 22, 0.15);
        transform: translateY(-2px);
    }
    
    .stat-card {
        background: linear-gradient(135deg, #fff 0%, #fef7ed 100%);
        border: 2px solid #fed7aa;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        border-color: #f97316;
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(249, 115, 22, 0.2);
    }
    
    .tochis-gradient {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
    }
</style>
@endpush

@section('header-actions')
    @if(!$currentCashCut)
        <a href="{{ route('cashier.cash-cut.index') }}" class="btn-warning">
            <i class="fas fa-cash-register mr-2"></i>
            Abrir Caja
        </a>
    @endif
    <a href="{{ route('cashier.sale.index') }}" class="btn-primary">
        <i class="fas fa-utensils mr-2"></i>
        Nueva Venta
    </a>
@endsection

@section('content')
<div class="fade-in">
    @if(!$currentCashCut)
        <!-- Alert for unopened cash cut -->
        <div class="bg-orange-50 border-l-4 border-orange-400 p-6 rounded-lg mb-8 shadow-md">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-orange-400 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-bold text-orange-800">¡Caja Cerrada!</h3>
                    <p class="text-orange-700 mt-1">Necesitas abrir la caja antes de realizar ventas en TOCHIS.</p>
                    <a href="{{ route('cashier.cash-cut.index') }}" class="inline-flex items-center mt-3 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors duration-200 font-semibold">
                        <i class="fas fa-cash-register mr-2"></i>
                        Abrir Caja Ahora
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Today's Sales -->
        <div class="stat-card rounded-2xl p-6">
            <div class="flex items-center">
                <div class="p-4 rounded-full tochis-gradient text-white shadow-lg">
                    <i class="fas fa-calendar-day text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-bold text-gray-600 uppercase tracking-wide">Ventas de Hoy</p>
                    <p class="text-3xl font-bold text-gray-800">${{ number_format($todayRevenue, 2) }}</p>
                    <p class="text-sm text-orange-600 font-semibold">{{ $todaySalesCount }} ventas realizadas</p>
                </div>
            </div>
        </div>

        <!-- Shift Sales -->
        <div class="stat-card rounded-2xl p-6">
            <div class="flex items-center">
                <div class="p-4 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-lg">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-bold text-gray-600 uppercase tracking-wide">Ventas del Turno</p>
                    <p class="text-3xl font-bold text-gray-800">${{ number_format($shiftRevenue, 2) }}</p>
                    <p class="text-sm text-blue-600 font-semibold">{{ $shiftSalesCount }} ventas en turno</p>
                </div>
            </div>
        </div>

        <!-- Month Sales -->
        <div class="stat-card rounded-2xl p-6">
            <div class="flex items-center">
                <div class="p-4 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 text-white shadow-lg">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-bold text-gray-600 uppercase tracking-wide">Ventas del Mes</p>
                    <p class="text-3xl font-bold text-gray-800">${{ number_format($totalRevenueThisMonth, 2) }}</p>
                    <p class="text-sm text-purple-600 font-semibold">{{ $totalSalesThisMonth }} ventas totales</p>
                </div>
            </div>
        </div>

        <!-- Cash Cut Status -->
        <div class="stat-card rounded-2xl p-6">
            <div class="flex items-center">
                <div class="p-4 rounded-full {{ $currentCashCut ? 'bg-gradient-to-br from-green-500 to-green-600' : 'bg-gradient-to-br from-red-500 to-red-600' }} text-white shadow-lg">
                    <i class="fas fa-cash-register text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-bold text-gray-600 uppercase tracking-wide">Estado de Caja</p>
                    @if($currentCashCut)
                        <p class="text-2xl font-bold text-green-600">Abierta</p>
                        <p class="text-sm text-green-600 font-semibold">Desde {{ $currentCashCut->opened_at->format('H:i') }}</p>
                    @else
                        <p class="text-2xl font-bold text-red-600">Cerrada</p>
                        <p class="text-sm text-red-600 font-semibold">Necesita apertura</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Sales -->
        <div class="tochis-card overflow-hidden">
            <div class="px-6 py-4 tochis-gradient">
                <h3 class="text-lg font-bold text-white flex items-center">
                    <i class="fas fa-receipt mr-3"></i>
                    Últimas Ventas
                </h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentSales as $sale)
                    <div class="p-6 hover:bg-orange-50 transition-colors duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $sale->sale_number }}</p>
                                <p class="text-sm text-gray-600">{{ $sale->created_at->format('d/m/Y H:i') }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $sale->saleDetails->count() }} artículos - 
                                    {{ ucfirst($sale->payment_method) }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold text-green-600">${{ number_format($sale->total, 2) }}</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">
                        <i class="fas fa-receipt text-4xl text-gray-300 mb-2"></i>
                        <p>No hay ventas recientes</p>
                    </div>
                @endforelse
            </div>
            @if($recentSales->count() > 0)
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                    <a href="{{ route('cashier.sale.history') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Ver todas las ventas →
                    </a>
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-bolt mr-2 text-orange-600"></i>
                    Acciones Rápidas
                </h3>
            </div>
            <div class="p-6 space-y-4">
                @if($currentCashCut)
                    <a href="{{ route('cashier.sale.index') }}" 
                       class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition duration-200">
                        <i class="fas fa-shopping-cart text-blue-600 text-xl mr-4"></i>
                        <div>
                            <p class="font-medium text-gray-700">Nueva Venta</p>
                            <p class="text-sm text-gray-500">Realizar una nueva venta</p>
                        </div>
                    </a>
                @endif

                <a href="{{ route('cashier.sale.history') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-green-50 hover:border-green-300 transition duration-200">
                    <i class="fas fa-history text-green-600 text-xl mr-4"></i>
                    <div>
                        <p class="font-medium text-gray-700">Historial de Ventas</p>
                        <p class="text-sm text-gray-500">Ver ventas anteriores</p>
                    </div>
                </a>

                <a href="{{ route('cashier.cash-cut.index') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 transition duration-200">
                    <i class="fas fa-calculator text-purple-600 text-xl mr-4"></i>
                    <div>
                        <p class="font-medium text-gray-700">Corte de Caja</p>
                        <p class="text-sm text-gray-500">
                            @if($currentCashCut)
                                Cerrar corte actual
                            @else
                                Abrir nueva caja
                            @endif
                        </p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    @if($currentCashCut)
        <!-- Current Cash Cut Info -->
        <div class="mt-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                    Información del Turno Actual
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">Monto Inicial</p>
                        <p class="text-lg font-semibold text-gray-900">${{ number_format($currentCashCut->initial_amount, 2) }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">Ventas</p>
                        <p class="text-lg font-semibold text-green-600">${{ number_format($currentCashCut->sales_amount, 2) }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">Total Esperado</p>
                        <p class="text-lg font-semibold text-blue-600">${{ number_format($currentCashCut->initial_amount + $currentCashCut->sales_amount, 2) }}</p>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">Hora Apertura</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $currentCashCut->opened_at->format('H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
