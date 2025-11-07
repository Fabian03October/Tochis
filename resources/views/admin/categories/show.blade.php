@extends('layouts.admin')

{{-- 1. Título enriquecido (estilo create/edit) --}}
@section('title')
<div>
    <h1 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h1>
    <p class="text-gray-400 text-sm">Detalles de la categoría y Platillos asociados</p>
</div>
@endsection

{{-- 2. Animación fade-in (estilo create/edit) --}}
@section('styles')
<style>
    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@section('content')
<div class="fade-in">
    {{-- 3. Contenedor max-w-6xl (estilo create/edit) --}}
    <div class="max-w-6xl mx-auto">
        
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver a Categorías
            </a>
            
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn-primary">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </a>
                
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta categoría? Esta acción no se puede deshacer.')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-danger">
                        <i class="fas fa-trash mr-2"></i>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>

        {{-- 4. Grid de 3 columnas (1 para info, 2 para Platillos) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-1 space-y-6">

                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="bg-green-100 rounded-lg p-2 mr-3">
                                <i class="fas fa-eye text-green-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Vista Previa</h3>
                        </div>
                    </div>
                    <div class="p-4 space-y-4">
                        <div id="category-preview" class="border-2 rounded-xl p-4 transition-all duration-300" style="background: linear-gradient(135deg, {{ $category->color }}20, {{ $category->color }}40); border-color: {{ $category->color }}60;">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div id="preview-color-indicator" class="w-4 h-4 rounded-full mr-3 border-2 border-white shadow-sm" style="background-color: {{ $category->color }};"></div>
                                    <div>
                                        <h4 id="preview-name" class="text-lg font-bold text-gray-800">{{ $category->name }}</h4>
                                        <p id="preview-description" class="text-sm text-gray-600">{{ $category->description ?: 'Sin descripción' }}</p>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end space-y-1">
                                    @if($category->is_customizable)
                                    <div class="bg-orange-100 px-2 py-1 rounded-full">
                                        <span class="text-xs font-semibold text-orange-800">Personalizable</span>
                                    </div>
                                    @endif
                                    @if($category->is_active)
                                    <div class="bg-green-100 px-2 py-1 rounded-full">
                                        <span class="text-xs font-semibold text-green-800">Activa</span>
                                    </div>
                                    @else
                                    <div class="bg-red-100 px-2 py-1 rounded-full">
                                        <span class="text-xs font-semibold text-red-800">Inactiva</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="bg-blue-100 rounded-lg p-2 mr-3">
                                <i class="fas fa-chart-bar text-blue-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Estadísticas</h3>
                        </div>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-center">
                            <p class="text-2xl font-bold text-blue-700">{{ $category->products()->count() }}</p>
                            <p class="text-xs text-blue-600 font-medium">Platillos Totales</p>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-center">
                            <p class="text-2xl font-bold text-green-700">{{ $category->products()->where('is_active', true)->count() }}</p>
                            <p class="text-xs text-green-600 font-medium">Platillos Activos</p>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <h5 class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-info-circle mr-2"></i>
                                Información
                            </h5>
                            <ul class="text-xs text-gray-600 space-y-1">
                                <li class="flex justify-between"><span>Creación:</span> <span>{{ $category->created_at->format('d/m/Y H:i') }}</span></li>
                                <li class="flex justify-between"><span>Actualización:</span> <span>{{ $category->updated_at->format('d/m/Y H:i') }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-2">
                {{-- 5. Estilo de tarjeta de create/edit aplicado aquí --}}
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="bg-purple-100 rounded-lg p-2 mr-3">
                                    <i class="fas fa-box text-purple-600"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Platillos en esta Categoría
                                    <span class="ml-2 text-sm font-normal text-gray-500">({{ $category->products->count() }})</span>
                                </h3>
                            </div>
                            <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn-success">
                                <i class="fas fa-plus mr-2"></i>
                                Agregar Platillo
                            </a>
                        </div>
                    </div>

                    {{-- El contenido de tu tabla ya está muy bien estilado, así que se mantiene --}}
                    @if($category->products->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                            <i class="fas fa-box mr-1"></i>
                                            Platillo
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
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">No hay Platillos en esta categoría</h3>
                                <p class="text-gray-500 mb-6 max-w-md text-center">
                                    Esta categoría aún no tiene Platillos asociados. Comienza agregando Platillos para llenar tu inventario.
                                </p>
                                <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" class="btn-success">
                                    <i class="fas fa-plus mr-2"></i>
                                    Agregar Primer Platillo
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection