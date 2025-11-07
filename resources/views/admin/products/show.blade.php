@extends('layouts.app')

@section('title', $product->name . ' - Sistema POS')
@section('page-title', 'Detalle del Platillo')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ $product->name }}</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Categoría: {{ $product->category->name }}
                </p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="btn-primary">
                    <i class="fas fa-edit mr-2"></i>
                    Editar Platillo
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver a Platillos
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Product Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                        Información del Platillo
                    </h3>
                </div>
                
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product->name }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Categoría</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      style="background-color: {{ $product->category->color }}20; color: {{ $product->category->color }};">
                                    {{ $product->category->name }}
                                </span>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Estado</dt>
                            <dd class="mt-1">
                                @if($product->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>
                                        Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>
                                        Inactivo
                                    </span>
                                @endif
                            </dd>
                        </div>
                        
                        @if($product->description)
                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $product->description }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Pricing Information -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-dollar-sign mr-2 text-green-600"></i>
                        Información de Platillos
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <p class="text-2xl font-bold text-red-600">${{ number_format($product->cost, 2) }}</p>
                            <p class="text-sm text-gray-600">Costo</p>
                        </div>
                        
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <p class="text-2xl font-bold text-green-600">${{ number_format($product->price, 2) }}</p>
                            <p class="text-sm text-gray-600">Precio de Venta</p>
                        </div>
                        
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            @php
                                $margin = $product->price - $product->cost;
                                $marginPercent = $product->cost > 0 ? ($margin / $product->cost) * 100 : 0;
                            @endphp
                            <p class="text-2xl font-bold text-blue-600">${{ number_format($margin, 2) }}</p>
                            <p class="text-sm text-gray-600">Ganancia ({{ number_format($marginPercent, 2) }}%)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Sales -->
            @if($recentSales->count() > 0)
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-chart-line mr-2 text-gray-600"></i>
                            Ventas Recientes
                        </h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Venta
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cantidad
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Precio Unit.
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($recentSales as $saleDetail)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $saleDetail->sale->sale_number }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $saleDetail->sale->created_at->format('d/m/Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ $saleDetail->sale->created_at->format('H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $saleDetail->quantity }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">${{ number_format($saleDetail->unit_price, 2) }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-green-600">${{ number_format($saleDetail->total, 2) }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Product Image -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-image mr-2 text-purple-600"></i>
                        Imagen del Platillo
                    </h3>
                </div>
                
                <div class="p-6">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-64 object-cover rounded-lg border border-gray-200">
                    @else
                        <div class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                                <p class="text-sm text-gray-500">Sin imagen</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-chart-bar mr-2 text-indigo-600"></i>
                        Estadísticas Rápidas
                    </h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-blue-900">Total Vendido</p>
                            <p class="text-xs text-blue-700">Últimos 30 días</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-blue-600">{{ $totalQuantitySold }}</p>
                            <p class="text-xs text-blue-700">unidades</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-green-900">Ingresos</p>
                            <p class="text-xs text-green-700">Últimos 30 días</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-green-600">${{ number_format($totalRevenue, 2) }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-purple-900">Promedio/Venta</p>
                            <p class="text-xs text-purple-700">Cantidad por venta</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-purple-600">{{ number_format($averageQuantityPerSale, 1) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-lightning-bolt mr-2 text-yellow-600"></i>
                        Acciones Rápidas
                    </h3>
                </div>
                
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.products.edit', $product) }}" 
                       class="w-full btn-primary text-center">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Platillo
                    </a>
                    
                    @if($product->is_active)
                        <button onclick="toggleStatus(false)" 
                                class="w-full btn-secondary">
                            <i class="fas fa-eye-slash mr-2"></i>
                            Desactivar Platillo
                        </button>
                    @else
                        <button onclick="toggleStatus(true)" 
                                class="w-full btn-success">
                            <i class="fas fa-eye mr-2"></i>
                            Activar Platillo
                        </button>
                    @endif
                    
                    <button onclick="confirmDelete()" 
                            class="w-full btn-danger">
                        <i class="fas fa-trash mr-2"></i>
                        Eliminar Platillo
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Forms -->
    <form id="toggle-form" method="POST" action="{{ route('admin.products.update', $product) }}" style="display: none;">
        @csrf
        @method('PUT')
        <input type="hidden" name="name" value="{{ $product->name }}">
        <input type="hidden" name="description" value="{{ $product->description }}">
        <input type="hidden" name="category_id" value="{{ $product->category_id }}">
        <input type="hidden" name="cost" value="{{ $product->cost }}">
        <input type="hidden" name="price" value="{{ $product->price }}">
        <input type="hidden" name="stock" value="{{ $product->stock }}">
        <input type="hidden" name="min_stock" value="{{ $product->min_stock }}">
        <input type="hidden" name="is_active" value="0" id="is_active_input">
    </form>

    <form id="delete-form" method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
</div>

<script>
function toggleStatus(status) {
    const action = status ? 'activar' : 'desactivar';
    if (confirm(`¿Estás seguro de que deseas ${action} este Platillo?`)) {
        document.getElementById('is_active_input').value = status ? '1' : '0';
        document.getElementById('toggle-form').submit();
    }
}

function confirmDelete() {
    if (confirm('¿Estás seguro de que deseas eliminar este Platillo? Esta acción no se puede deshacer.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection
