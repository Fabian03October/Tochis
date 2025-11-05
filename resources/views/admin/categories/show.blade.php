@extends('layouts.admin')

@section('title', 'Ver Categoría')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
                <p class="text-gray-600 text-sm mt-1">Categoría de productos</p>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>
                Editar
            </a>
            
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        onclick="return confirm('¿Estás seguro de que quieres eliminar esta categoría?')"
                        class="btn-danger">
                    <i class="fas fa-trash mr-2"></i>
                    Eliminar
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Category Info Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-blue-100 rounded-lg p-2 mr-3">
                            <i class="fas fa-info-circle text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Información General</h3>
                    </div>
                </div>
                
                <div class="p-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Nombre y Color</label>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-lg shadow-md" 
                                 style="background: linear-gradient(135deg, {{ $category->color }}, {{ $category->color }}88)">
                            </div>
                            <div>
                                <p class="text-lg font-bold text-gray-900">{{ $category->name }}</p>
                                <p class="text-xs text-gray-500 font-mono">{{ $category->color }}</p>
                            </div>
                        </div>
                    </div>

                    @if($category->description)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Descripción</label>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-sm text-gray-700">{{ $category->description }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Estado</label>
                            @if($category->is_active)
                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Activa
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Inactiva
                                </span>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Personalizable</label>
                            @if($category->is_customizable)
                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Sí
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    No
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="space-y-3">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-2xl font-bold text-blue-700">{{ $category->products->count() }}</p>
                                    <p class="text-sm text-blue-600 font-medium">Productos</p>
                                </div>
                                <div class="bg-blue-200 rounded-lg p-2">
                                    <i class="fas fa-box text-blue-700"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-3 border-t border-gray-100">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Creado</label>
                            <p class="text-xs text-gray-700">{{ $category->created_at->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Actualizado</label>
                            <p class="text-xs text-gray-700">{{ $category->updated_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-purple-100 rounded-lg p-2 mr-3">
                                <i class="fas fa-box text-purple-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Productos en esta Categoría
                                <span class="ml-2 text-sm font-normal text-gray-500">({{ $category->products->count() }})</span>
                            </h3>
                        </div>
                        <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn-success">
                            <i class="fas fa-plus mr-2"></i>
                            Agregar Producto
                        </a>
                    </div>
                </div>

                @if($category->products->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        <i class="fas fa-box mr-1"></i>
                                        Producto
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        <i class="fas fa-dollar-sign mr-1"></i>
                                        Precio
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        <i class="fas fa-toggle-on mr-1"></i>
                                        Estado
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">
                                        <i class="fas fa-cog mr-1"></i>
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($category->products as $product)
                                    <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-pink-50 transition duration-300 group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                @if($product->image)
                                                    <img src="{{ Storage::url($product->image) }}" 
                                                         alt="{{ $product->name }}"
                                                         class="w-10 h-10 rounded-lg object-cover mr-3 shadow-md group-hover:shadow-lg transition duration-300">
                                                @else
                                                    <div class="w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg transition duration-300">
                                                        <i class="fas fa-box text-gray-400 text-sm"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="text-sm font-semibold text-gray-900 group-hover:text-purple-600 transition duration-300">
                                                        {{ $product->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">${{ number_format($product->price, 2) }}</div>
                                            @if($product->cost)
                                                <div class="text-xs text-gray-500">Costo: ${{ number_format($product->cost, 2) }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($product->is_active)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-green-100 to-green-200 text-green-800 shadow-sm">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Activo
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-red-100 to-red-200 text-red-800 shadow-sm">
                                                    <i class="fas fa-times-circle mr-1"></i>
                                                    Inactivo
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('admin.products.show', $product) }}" 
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 hover:text-blue-700 transition duration-200 transform hover:scale-110"
                                                   title="Ver detalles">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product) }}" 
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 hover:bg-indigo-200 hover:text-indigo-700 transition duration-200 transform hover:scale-110"
                                                   title="Editar">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="bg-gradient-to-br from-purple-100 to-pink-100 rounded-full p-6 mb-6">
                                <i class="fas fa-box text-4xl text-purple-500"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No hay productos en esta categoría</h3>
                            <p class="text-gray-500 mb-6 max-w-md text-center">
                                Esta categoría aún no tiene productos asociados. Comienza agregando productos para llenar tu inventario.
                            </p>
                            <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn-success">
                                <i class="fas fa-plus mr-2"></i>
                                Agregar Primer Producto
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection