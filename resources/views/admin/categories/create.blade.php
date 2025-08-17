@extends('layouts.app')

@section('title', 'Nueva Categoría - Sistema POS')
@section('page-title', 'Nueva Categoría')

@section('breadcrumb')
    <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:text-blue-800">Categorías</a>
    <span class="mx-2">/</span>
    <span class="text-gray-500">Nueva</span>
@endsection

@section('content')
<div class="fade-in">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-plus-circle mr-2 text-blue-600"></i>
                    Crear Nueva Categoría
                </h3>
                <p class="mt-1 text-sm text-gray-600">
                    Complete la información para crear una nueva categoría de productos.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.categories.store') }}" class="px-6 py-4 space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de la Categoría *
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                           placeholder="Ej: Bebidas, Snacks, Lácteos..."
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Descripción de la categoría (opcional)">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color -->
                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 mb-2">
                        Color de la Categoría *
                    </label>
                    <div class="flex items-center space-x-4">
                        <input type="color" 
                               id="color" 
                               name="color" 
                               value="{{ old('color', '#3B82F6') }}"
                               class="h-10 w-20 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('color') border-red-500 @enderror">
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">
                                Selecciona un color que identifique esta categoría en el sistema.
                            </p>
                        </div>
                    </div>
                    @error('color')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Color Presets -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Colores Sugeridos
                    </label>
                    <div class="grid grid-cols-8 gap-2">
                        @php
                            $presetColors = [
                                '#3B82F6', '#EF4444', '#10B981', '#F59E0B',
                                '#8B5CF6', '#EC4899', '#06B6D4', '#84CC16'
                            ];
                        @endphp
                        @foreach($presetColors as $preset)
                            <button type="button" 
                                    class="w-8 h-8 rounded-full border-2 border-gray-200 hover:border-gray-400 transition duration-200"
                                    style="background-color: {{ $preset }}"
                                    onclick="document.getElementById('color').value = '{{ $preset }}'">
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Personalizable -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Opciones de Personalización
                    </label>
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_customizable" 
                               name="is_customizable" 
                               value="1"
                               {{ old('is_customizable') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_customizable" class="ml-2 block text-sm text-gray-900">
                            Permitir personalización de productos en esta categoría
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-600">
                        Si está activo, los productos de esta categoría mostrarán opciones de personalización (observaciones y especialidades) en el punto de venta.
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Crear Categoría
                    </button>
                </div>
            </form>
        </div>

        <!-- Help Section -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                        Consejos para crear categorías
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Use nombres descriptivos y fáciles de entender</li>
                            <li>Elija colores distintivos para cada categoría</li>
                            <li>Las categorías ayudan a organizar mejor los productos</li>
                            <li>Puede agregar una descripción para mayor claridad</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
