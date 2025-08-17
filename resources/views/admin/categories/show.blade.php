@extends('layouts.app')

@section('title', 'Categoría: ' . $category->name . ' - Sistema POS')
@section('page-title', 'Detalles de Categoría')

@section('breadcrumb')
    <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:text-blue-800">Categorías</a>
    <span class="mx-2">/</span>
    <span class="text-gray-500">{{ $category->name }}</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.categories.edit', $category) }}" class="btn-primary">
        <i class="fas fa-edit mr-2"></i>
        Editar
    </a>
@endsection

@section('content')
<div class="fade-in">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Category Details -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                        Información de la Categoría
                    </h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <div class="flex items-center mt-1">
                            <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $category->color }}"></div>
                            <p class="text-lg font-semibold text-gray-900">{{ $category->name }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                        <p class="mt-1 text-gray-900">{{ $category->description ?: 'Sin descripción' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Color</label>
                        <div class="flex items-center mt-1">
                            <div class="w-8 h-8 rounded-lg border border-gray-300 mr-3" style="background-color: {{ $category->color }}"></div>
                            <span class="text-gray-900 font-mono">{{ $category->color }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <div class="mt-1">
                            @if($category->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Activa
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Inactiva
                                </span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha de Creación</label>
                        <p class="mt-1 text-gray-900">{{ $category->created_at->format('d/m/Y H:i') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Última Actualización</label>
                        <p class="mt-1 text-gray-900">{{ $category->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Category Stats -->
            <div class="bg-white rounded-lg shadow overflow-hidden mt-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-chart-bar mr-2 text-green-600"></i>
                        Estadísticas
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-2xl font-bold text-blue-600">{{ $category->products->count() }}</p>
                            <p class="text-sm text-gray-600">Total Productos</p>
                        </div>
                        
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <p class="text-2xl font-bold text-green-600">{{ $category->products->where('is_active', true)->count() }}</p>
                            <p class="text-sm text-gray-600">Productos Activos</p>
                        </div>
                        
                        <div class="text-center p-4 bg-orange-50 rounded-lg">
                            <p class="text-2xl font-bold text-orange-600">${{ number_format($category->products->sum('price'), 2) }}</p>
                            <p class="text-sm text-gray-600">Valor Total</p>
                        </div>
                        
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <p class="text-2xl font-bold text-purple-600">{{ $category->products->sum('stock') }}</p>
                            <p class="text-sm text-gray-600">Stock Total</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-box mr-2 text-purple-600"></i>
                            Productos en esta Categoría
                        </h3>
                        <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn-primary">
                            <i class="fas fa-plus mr-2"></i>
                            Agregar Producto
                        </a>
                    </div>
                </div>

                @if($category->products->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Producto
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Precio
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Stock
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($category->products as $product)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                @if($product->image)
                                                    <img src="{{ Storage::url($product->image) }}" 
                                                         alt="{{ $product->name }}"
                                                         class="w-10 h-10 rounded-lg object-cover mr-3">
                                                @else
                                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                                        <i class="fas fa-box text-gray-400"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                                    @if($product->barcode)
                                                        <div class="text-sm text-gray-500">{{ $product->barcode }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">${{ number_format($product->price, 2) }}</div>
                                            @if($product->cost)
                                                <div class="text-sm text-gray-500">Costo: ${{ number_format($product->cost, 2) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $product->stock }}</div>
                                            @if($product->hasLowStock())
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Stock Bajo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($product->is_active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Activo
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Inactivo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('admin.products.show', $product) }}" 
                                                   class="text-blue-600 hover:text-blue-900 transition duration-200"
                                                   title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 transition duration-200"
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-6 text-center">
                        <i class="fas fa-box text-4xl text-gray-300 mb-4"></i>
                        <p class="text-lg font-medium text-gray-900">No hay productos en esta categoría</p>
                        <p class="text-sm text-gray-500 mb-4">Comienza agregando productos a esta categoría</p>
                        <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn-primary">
                            <i class="fas fa-plus mr-2"></i>
                            Agregar Primer Producto
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
