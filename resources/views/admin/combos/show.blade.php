@extends('layouts.app')

@section('title', 'Detalles del Combo - Sistema POS')
@section('page-title', 'Detalles del Combo')

@section('content')
<div class="fade-in">
    <!-- Header Actions -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">{{ $combo->name }}</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Información completa del combo seleccionado
                </p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none flex space-x-3">
                <a href="{{ route('admin.combos.edit', $combo) }}" 
                   class="btn-primary">
                    <i class="fas fa-edit mr-2"></i>Editar Combo
                </a>
                <a href="{{ route('admin.combos.index') }}" 
                   class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Volver
                </a>
            </div>
        </div>
    </div>
    <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
        <!-- Panel Principal -->
        <div class="lg:col-span-8">
            <!-- Información del Combo -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Información del Combo
                    </h3>
                </div>
                
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ $combo->name }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Estado</dt>
                            <dd class="mt-1">
                                @if($combo->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>Inactivo
                                    </span>
                                @endif
                            </dd>
                        </div>
                        
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $combo->description }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Precio del Combo</dt>
                            <dd class="mt-1 text-lg font-bold text-green-600">${{ number_format($combo->price, 2) }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Precio Original</dt>
                            <dd class="mt-1 text-sm text-gray-500 line-through">${{ number_format($combo->original_price, 2) }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Descuento</dt>
                            <dd class="mt-1 text-lg font-bold text-red-600">${{ number_format($combo->discount_amount, 2) }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Ahorro</dt>
                            <dd class="mt-1 text-lg font-bold text-orange-600">
                                {{ $combo->original_price > 0 ? number_format((($combo->original_price - $combo->price) / $combo->original_price) * 100, 1) : 0 }}%
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Ítems Mínimos</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $combo->min_items }} productos</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Sugerencia Automática</dt>
                            <dd class="mt-1">
                                @if($combo->auto_suggest)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-magic mr-1"></i>Habilitada
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-ban mr-1"></i>Deshabilitada
                                    </span>
                                @endif
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Fecha de Creación</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $combo->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $combo->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            
            <!-- Productos del Combo -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden mt-6">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Productos Incluidos
                        <span class="ml-2 text-sm text-gray-500">({{ $combo->products->count() }} productos)</span>
                    </h3>
                </div>
                
                @if($combo->products->count() > 0)
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Producto
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Categoría
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Precio Individual
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($combo->products as $product)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                @if($product->image)
                                                    <img class="h-10 w-10 rounded-full object-cover mr-3" 
                                                         src="{{ asset('storage/' . $product->image) }}" 
                                                         alt="{{ $product->name }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3">
                                                        <i class="fas fa-utensils text-gray-400"></i>
                                                    </div>
                                                @endif
                                                
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                                    @if($product->description)
                                                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ $product->description }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ $product->category->name }}
                                            </span>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            ${{ number_format($product->price, 2) }}
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($product->is_active)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Disponible
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    No disponible
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="px-6 py-8 text-center">
                        <i class="fas fa-box-open text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No hay productos asignados a este combo</p>
                        <a href="{{ route('admin.combos.edit', $combo) }}" 
                           class="mt-3 inline-flex items-center text-blue-600 hover:text-blue-500">
                            <i class="fas fa-plus mr-1"></i>Agregar productos
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Panel Lateral -->
        <div class="lg:col-span-4 mt-6 lg:mt-0">
            <!-- Estadísticas -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Estadísticas del Combo
                    </h3>
                </div>
                
                <div class="px-6 py-4 space-y-4">
                    <div class="bg-green-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-dollar-sign text-green-600 text-xl mr-3"></i>
                            <div>
                                <div class="text-sm text-green-600 font-medium">Precio del Combo</div>
                                <div class="text-2xl font-bold text-green-800">${{ number_format($combo->price, 2) }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-box text-blue-600 text-xl mr-3"></i>
                            <div>
                                <div class="text-sm text-blue-600 font-medium">Productos Incluidos</div>
                                <div class="text-2xl font-bold text-blue-800">{{ $combo->products->count() }}</div>
                            </div>
                        </div>
                    </div>
                    
                    @if($combo->original_price > 0)
                        <div class="bg-orange-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-percentage text-orange-600 text-xl mr-3"></i>
                                <div>
                                    <div class="text-sm text-orange-600 font-medium">Descuento</div>
                                    <div class="text-2xl font-bold text-orange-800">
                                        {{ number_format((($combo->original_price - $combo->price) / $combo->original_price) * 100, 1) }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Acciones Rápidas -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden mt-6">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Acciones Rápidas
                    </h3>
                </div>
                
                <div class="px-6 py-4 space-y-3">
                    <a href="{{ route('admin.combos.edit', $combo) }}" 
                       class="w-full flex items-center justify-center px-4 py-2 border border-blue-300 text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-md text-sm font-medium">
                        <i class="fas fa-edit mr-2"></i>Editar Combo
                    </a>
                    
                    <form action="{{ route('admin.combos.destroy', $combo) }}" method="POST" 
                          onsubmit="return confirm('¿Estás seguro de que quieres eliminar este combo?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 border border-red-300 text-red-700 bg-red-50 hover:bg-red-100 rounded-md text-sm font-medium">
                            <i class="fas fa-trash mr-2"></i>Eliminar Combo
                        </button>
                    </form>
                    
                    @if($combo->is_active)
                        <form action="{{ route('admin.combos.update', $combo) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="is_active" value="0">
                            <button type="submit" 
                                    class="w-full flex items-center justify-center px-4 py-2 border border-yellow-300 text-yellow-700 bg-yellow-50 hover:bg-yellow-100 rounded-md text-sm font-medium">
                                <i class="fas fa-pause mr-2"></i>Desactivar
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.combos.update', $combo) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="is_active" value="1">
                            <button type="submit" 
                                    class="w-full flex items-center justify-center px-4 py-2 border border-green-300 text-green-700 bg-green-50 hover:bg-green-100 rounded-md text-sm font-medium">
                                <i class="fas fa-play mr-2"></i>Activar
                            </button>
                        </form>
                    @endif
                </div>
            </div>
    </div>
</div>
@endsection
