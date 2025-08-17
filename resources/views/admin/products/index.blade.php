@extends('layouts.app')

@section('title', 'Productos - Sistema POS')
@section('page-title', 'Gestión de Productos')

@section('content')
<div class="fade-in">
    <!-- Header Actions -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Productos</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Gestiona el inventario de productos de tu punto de venta.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <a href="{{ route('admin.products.create') }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    Nuevo Producto
                </a>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <form method="GET" action="{{ route('admin.products.index') }}" class="space-y-4 md:space-y-0 md:flex md:items-end md:space-x-4">
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                    Buscar productos
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" 
                           name="search" 
                           id="search"
                           value="{{ request('search') }}"
                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Nombre, código o descripción...">
                </div>
            </div>

            <div class="flex-shrink-0">
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                    Categoría
                </label>
                <select name="category" 
                        id="category"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todas las categorías</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-shrink-0">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Estado
                </label>
                <select name="status" 
                        id="status"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>

            <div class="flex-shrink-0">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-filter mr-2"></i>
                    Filtrar
                </button>
            </div>

            @if(request()->hasAny(['search', 'category', 'status']))
                <div class="flex-shrink-0">
                    <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                        <i class="fas fa-times mr-2"></i>
                        Limpiar
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-6">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 overflow-hidden">
                    <!-- Product Image -->
                    <div class="relative h-48 bg-gray-200">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-box text-4xl text-gray-400"></i>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="absolute top-2 right-2">
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
                        </div>

                        <!-- Stock Badge -->
                        <div class="absolute top-2 left-2">
                            @if($product->stock <= $product->min_stock)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Stock Bajo
                                </span>
                            @elseif($product->stock <= ($product->min_stock * 2))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-warning mr-1"></i>
                                    Poco Stock
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="p-4">
                        <div class="mb-2">
                            <h3 class="text-lg font-semibold text-gray-900 truncate" title="{{ $product->name }}">
                                {{ $product->name }}
                            </h3>
                            <p class="text-sm text-gray-500">{{ $product->barcode }}</p>
                        </div>

                        <div class="mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                  style="background-color: {{ $product->category->color }}20; color: {{ $product->category->color }};">
                                {{ $product->category->name }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <p class="text-lg font-bold text-green-600">${{ number_format($product->price, 2) }}</p>
                                @if($product->cost > 0)
                                    <p class="text-sm text-gray-500">Costo: ${{ number_format($product->cost, 2) }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">Stock: {{ $product->stock }}</p>
                                <p class="text-xs text-gray-500">Min: {{ $product->min_stock }}</p>
                            </div>
                        </div>

                        @if($product->description)
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2" title="{{ $product->description }}">
                                {{ $product->description }}
                            </p>
                        @endif

                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.products.show', $product) }}" 
                               class="flex-1 btn-secondary text-center">
                                <i class="fas fa-eye mr-1"></i>
                                Ver
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" 
                               class="flex-1 btn-primary text-center">
                                <i class="fas fa-edit mr-1"></i>
                                Editar
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 rounded-lg shadow">
                <div class="flex-1 flex justify-between sm:hidden">
                    @if($products->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-500 bg-white cursor-default">
                            Anterior
                        </span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Anterior
                        </a>
                    @endif

                    @if($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Siguiente
                        </a>
                    @else
                        <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-500 bg-white cursor-default">
                            Siguiente
                        </span>
                    @endif
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Mostrando
                            <span class="font-medium">{{ $products->firstItem() }}</span>
                            a
                            <span class="font-medium">{{ $products->lastItem() }}</span>
                            de
                            <span class="font-medium">{{ $products->total() }}</span>
                            productos
                        </p>
                    </div>
                    <div>
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-box text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                @if(request()->hasAny(['search', 'category', 'status']))
                    No se encontraron productos
                @else
                    No hay productos registrados
                @endif
            </h3>
            <p class="text-gray-500 mb-6">
                @if(request()->hasAny(['search', 'category', 'status']))
                    Intenta ajustar los filtros de búsqueda o crear un nuevo producto.
                @else
                    Comienza agregando tu primer producto al inventario.
                @endif
            </p>
            
            @if(request()->hasAny(['search', 'category', 'status']))
                <div class="space-x-4">
                    <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                        <i class="fas fa-times mr-2"></i>
                        Limpiar Filtros
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="btn-primary">
                        <i class="fas fa-plus mr-2"></i>
                        Nuevo Producto
                    </a>
                </div>
            @else
                <a href="{{ route('admin.products.create') }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    Crear mi primer producto
                </a>
            @endif
        </div>
    @endif
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
