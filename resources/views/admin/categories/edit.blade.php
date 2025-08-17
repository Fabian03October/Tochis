@extends('layouts.app')

@section('title', 'Editar Categoría - Sistema POS')
@section('page-title', 'Editar Categoría')

@section('breadcrumb')
    <a href="{{ route('admin.categories.index') }}" class="text-blue-600 hover:text-blue-800">Categorías</a>
    <span class="mx-2">/</span>
    <span class="text-gray-500">Editar</span>
@endsection

@section('content')
<div class="fade-in">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-edit mr-2 text-blue-600"></i>
                    Editar Categoría: {{ $category->name }}
                </h3>
            </div>

            <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="px-6 py-4 space-y-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de la Categoría *
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $category->name) }}"
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
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
                              class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $category->description) }}</textarea>
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
                               value="{{ old('color', $category->color) }}"
                               class="h-10 w-20 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('color') border-red-500 @enderror">
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">
                                Color actual: <span class="inline-block w-4 h-4 rounded-full ml-2" style="background-color: {{ $category->color }}"></span>
                            </p>
                        </div>
                    </div>
                    @error('color')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1"
                               {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Categoría activa</span>
                    </label>
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
                               {{ old('is_customizable', $category->is_customizable) ? 'checked' : '' }}
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
                        Actualizar Categoría
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
