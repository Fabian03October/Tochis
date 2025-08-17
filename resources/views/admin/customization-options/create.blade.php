@extends('layouts.app')

@section('title', 'Crear Opción de Personalización - Sistema POS')
@section('page-title', 'Nueva Opción de Personalización')

@section('content')
<div class="fade-in">
    <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('admin.customization-options.index') }}" 
               class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>

        <form action="{{ route('admin.customization-options.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nombre de la Opción
                </label>
                <input type="text" 
                       name="name" 
                       id="name"
                       value="{{ old('name') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Ej: Sin tomate, Extra queso..."
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                    Tipo de Opción
                </label>
                <select name="type" 
                        id="type"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required>
                    <option value="">Seleccionar tipo</option>
                    <option value="observation" {{ old('type') == 'observation' ? 'selected' : '' }}>
                        Observación (Quitar ingrediente)
                    </option>
                    <option value="specialty" {{ old('type') == 'specialty' ? 'selected' : '' }}>
                        Especialidad (Agregar ingrediente)
                    </option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4" id="price-field">
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                    Precio Adicional
                </label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500">$</span>
                    <input type="number" 
                           name="price" 
                           id="price"
                           value="{{ old('price', '0.00') }}"
                           step="0.01"
                           min="0"
                           class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>
                <p class="mt-1 text-sm text-gray-500">Solo las especialidades pueden tener precio adicional</p>
                @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                    Orden de Aparición
                </label>
                <input type="number" 
                       name="sort_order" 
                       id="sort_order"
                       value="{{ old('sort_order', '0') }}"
                       min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="0">
                <p class="mt-1 text-sm text-gray-500">Menor número aparece primero</p>
                @error('sort_order')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex space-x-4">
                <button type="submit" 
                        class="flex-1 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    <i class="fas fa-save mr-2"></i>Guardar
                </button>
                <a href="{{ route('admin.customization-options.index') }}" 
                   class="flex-1 bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded text-center">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('type').addEventListener('change', function() {
    const priceField = document.getElementById('price');
    const priceInput = document.getElementById('price');
    
    if (this.value === 'observation') {
        priceInput.value = '0.00';
        priceInput.readOnly = true;
        priceInput.classList.add('bg-gray-100');
    } else {
        priceInput.readOnly = false;
        priceInput.classList.remove('bg-gray-100');
    }
});
</script>
@endsection
