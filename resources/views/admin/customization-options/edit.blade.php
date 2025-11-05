@extends('layouts.app')

@section('title', 'Editar Opción de Personalización - Sistema POS')
@section('page-title', 'Editar Opción de Personalización')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Editar Opción de Personalización</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Modifica la información de "{{ $customizationOption->name }}".
                </p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <a href="{{ route('admin.customization-options.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver a Opciones
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulario -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-orange-100 rounded-lg p-2 mr-3">
                            <i class="fas fa-edit text-orange-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Información de la Opción</h3>
                    </div>
                </div>

                <form action="{{ route('admin.customization-options.update', $customizationOption) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Nombre -->
                    <div>
                        <label for="name" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-blue-500"></i>
                            Nombre de la Opción *
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name"
                               value="{{ old('name', $customizationOption->name) }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('name') border-red-500 @enderror"
                               placeholder="Ej: Sin tomate, Extra queso, Sin cebolla..."
                               required>
                        @error('name')
                            <p class="flex items-center mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Tipo -->
                    <div>
                        <label for="type" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-list mr-2 text-green-500"></i>
                            Tipo de Opción *
                        </label>
                        <select name="type" 
                                id="type"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('type') border-red-500 @enderror"
                                required>
                            <option value="">Seleccionar tipo de opción</option>
                            <option value="observation" {{ old('type', $customizationOption->type) == 'observation' ? 'selected' : '' }}>
                                🚫 Observación (Quitar ingrediente)
                            </option>
                            <option value="specialty" {{ old('type', $customizationOption->type) == 'specialty' ? 'selected' : '' }}>
                                ➕ Especialidad (Agregar ingrediente)
                            </option>
                        </select>
                        @error('type')
                            <p class="flex items-center mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-sm text-gray-600 mt-1">
                            Las observaciones son gratuitas, las especialidades pueden tener precio adicional.
                        </p>
                    </div>

                    <!-- Precio -->
                    <div id="price-field">
                        <label for="price" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign mr-2 text-purple-500"></i>
                            Precio Adicional
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" 
                                   name="price" 
                                   id="price"
                                   value="{{ old('price', $customizationOption->price) }}"
                                   step="0.01"
                                   min="0"
                                   class="block w-full pl-7 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('price') border-red-500 @enderror"
                                   placeholder="0.00">
                        </div>
                        @error('price')
                            <p class="flex items-center mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-sm text-gray-600 mt-1">
                            Para observaciones (quitar ingredientes) usa $0.00
                        </p>
                    </div>

                    <!-- Orden -->
                    <div>
                        <label for="sort_order" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-sort-numeric-down mr-2 text-orange-500"></i>
                            Orden de Aparición
                        </label>
                        <input type="number" 
                               name="sort_order" 
                               id="sort_order"
                               value="{{ old('sort_order', $customizationOption->sort_order) }}"
                               min="0"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('sort_order') border-red-500 @enderror"
                               placeholder="0">
                        @error('sort_order')
                            <p class="flex items-center mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-sm text-gray-600 mt-1">
                            Menor número aparece primero. Usa 0 para que aparezca al final.
                        </p>
                    </div>

                    <!-- Estado Activo -->
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" 
                                   name="is_active" 
                                   id="is_active"
                                   value="1"
                                   {{ old('is_active', $customizationOption->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm font-medium text-gray-900">
                                Opción activa
                            </label>
                        </div>
                        <p class="text-sm text-gray-600 mt-1 ml-6">
                            Solo las opciones activas aparecerán en el punto de venta.
                        </p>
                    </div>

                    <!-- Botones -->
                    <div class="flex space-x-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.customization-options.index') }}" class="btn-secondary flex-1 text-center">
                            <i class="fas fa-times mr-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn-primary flex-1">
                            <i class="fas fa-save mr-2"></i>Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Vista Previa y Estado Actual -->
        <div class="space-y-6">
            <!-- Estado Actual -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-blue-100 rounded-lg p-2 mr-3">
                            <i class="fas fa-info-circle text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Estado Actual</h3>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Tipo:</span>
                            <span class="px-2 py-1 text-xs rounded-full {{ $customizationOption->type === 'observation' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $customizationOption->type === 'observation' ? '🚫 Observación' : '➕ Especialidad' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Precio:</span>
                            <span class="text-sm font-bold text-gray-900">${{ number_format($customizationOption->price, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Orden:</span>
                            <span class="text-sm text-gray-900">{{ $customizationOption->sort_order }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Estado:</span>
                            <span class="px-2 py-1 text-xs rounded-full {{ $customizationOption->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $customizationOption->is_active ? '✅ Activo' : '❌ Inactivo' }}
                            </span>
                        </div>
                        <div class="pt-2 border-t border-gray-200">
                            <span class="text-sm font-medium text-gray-600">Creado:</span>
                            <p class="text-sm text-gray-900">{{ $customizationOption->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">Actualizado:</span>
                            <p class="text-sm text-gray-900">{{ $customizationOption->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vista Previa -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-green-100 rounded-lg p-2 mr-3">
                            <i class="fas fa-eye text-green-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Vista Previa</h3>
                    </div>
                </div>
                
                <div class="p-6">
                    <div id="preview-card" class="bg-gray-50 border-2 border-gray-200 rounded-lg p-4 transition-all duration-300">
                        <div class="flex items-center">
                            <div id="preview-icon" class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-gray-200">
                                <i class="fas fa-cog text-gray-400"></i>
                            </div>
                            <div class="flex-1">
                                <h4 id="preview-name" class="font-medium text-gray-600">{{ $customizationOption->name }}</h4>
                                <div class="flex items-center mt-1">
                                    <span id="preview-price" class="text-sm text-gray-500">${{ number_format($customizationOption->price, 2) }}</span>
                                    <span class="mx-2 text-gray-400">•</span>
                                    <span id="preview-order" class="text-sm text-gray-500">Orden: {{ $customizationOption->sort_order }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updatePreview() {
    const name = document.getElementById('name').value || '{{ $customizationOption->name }}';
    const type = document.getElementById('type').value;
    const price = parseFloat(document.getElementById('price').value) || 0;
    const sortOrder = document.getElementById('sort_order').value || 0;
    
    // Actualizar elementos de vista previa
    document.getElementById('preview-name').textContent = name;
    document.getElementById('preview-price').textContent = `$${price.toFixed(2)}`;
    document.getElementById('preview-order').textContent = `Orden: ${sortOrder}`;
    
    // Actualizar icono y colores según el tipo
    const iconElement = document.getElementById('preview-icon');
    const cardElement = document.getElementById('preview-card');
    
    if (type === 'observation') {
        iconElement.innerHTML = '<i class="fas fa-minus text-red-600"></i>';
        iconElement.className = 'w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-red-100';
        cardElement.className = 'bg-red-50 border-2 border-red-200 rounded-lg p-4 transition-all duration-300';
    } else if (type === 'specialty') {
        iconElement.innerHTML = '<i class="fas fa-plus text-green-600"></i>';
        iconElement.className = 'w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-green-100';
        cardElement.className = 'bg-green-50 border-2 border-green-200 rounded-lg p-4 transition-all duration-300';
    } else {
        iconElement.innerHTML = '<i class="fas fa-cog text-gray-400"></i>';
        iconElement.className = 'w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-gray-200';
        cardElement.className = 'bg-gray-50 border-2 border-gray-200 rounded-lg p-4 transition-all duration-300';
    }
}

// Controlar campo de precio según el tipo
function handleTypeChange() {
    const type = document.getElementById('type').value;
    const priceInput = document.getElementById('price');
    
    if (type === 'observation') {
        priceInput.value = '0.00';
        priceInput.readOnly = true;
        priceInput.classList.add('bg-gray-100');
    } else {
        priceInput.readOnly = false;
        priceInput.classList.remove('bg-gray-100');
    }
    
    updatePreview();
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('name').addEventListener('input', updatePreview);
    document.getElementById('type').addEventListener('change', handleTypeChange);
    document.getElementById('price').addEventListener('input', updatePreview);
    document.getElementById('sort_order').addEventListener('input', updatePreview);
    
    // Debug del formulario
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const formData = new FormData(form);
        console.log('Form data being submitted:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }
    });
    
    // Actualización inicial
    updatePreview();
});
</script>
@endsection