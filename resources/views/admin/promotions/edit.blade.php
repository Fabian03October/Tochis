@extends('layouts.app')

@section('title', 'Editar Promoción - Sistema POS')
@section('page-title', 'Editar Promoción')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Editar Promoción</h1>
            <p class="text-gray-600">Modifica los detalles de la promoción: {{ $promotion->name }}</p>
        </div>
        <a href="{{ route('admin.promotions.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver a Promociones
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-lg">
        <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column - Basic Info -->
                <div class="space-y-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Información Básica</h3>
                    
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre de la Promoción *
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $promotion->name) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
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
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Describe los detalles de la promoción...">{{ old('description', $promotion->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Discount Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Descuento *
                        </label>
                        <div class="flex space-x-4">
                            <label class="flex items-center">
                                <input type="radio" 
                                       name="type" 
                                       value="percentage" 
                                       {{ old('type', $promotion->type) === 'percentage' ? 'checked' : '' }}
                                       class="mr-2 text-blue-600 focus:ring-blue-500">
                                Porcentaje (%)
                            </label>
                            <label class="flex items-center">
                                <input type="radio" 
                                       name="type" 
                                       value="fixed" 
                                       {{ old('type', $promotion->type) === 'fixed' ? 'checked' : '' }}
                                       class="mr-2 text-blue-600 focus:ring-blue-500">
                                Monto Fijo ($)
                            </label>
                        </div>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Discount Value -->
                    <div>
                        <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-2">
                            Valor del Descuento *
                        </label>
                        <div class="relative">
                            <span id="discount-symbol" class="absolute left-3 top-2 text-gray-500">
                                {{ old('type', $promotion->type) === 'percentage' ? '%' : '$' }}
                            </span>
                            <input type="number" 
                                   id="discount_value" 
                                   name="discount_value" 
                                   value="{{ old('discount_value', $promotion->discount_value) }}"
                                   step="0.01" 
                                   min="0" 
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                                   required>
                        </div>
                        @error('discount_value')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Apply To -->
                    <div>
                        <label for="apply_to" class="block text-sm font-medium text-gray-700 mb-2">
                            Aplicar a *
                        </label>
                        <select id="apply_to" 
                                name="apply_to" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="all" {{ old('apply_to', $promotion->apply_to) === 'all' ? 'selected' : '' }}>
                                Todos los productos
                            </option>
                            <option value="category" {{ old('apply_to', $promotion->apply_to) === 'category' ? 'selected' : '' }}>
                                Categorías específicas
                            </option>
                            <option value="product" {{ old('apply_to', $promotion->apply_to) === 'product' ? 'selected' : '' }}>
                                Productos específicos
                            </option>
                        </select>
                        @error('apply_to')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Categories/Products selection (hidden by default) -->
                    <div id="category-selection" class="{{ old('apply_to', $promotion->apply_to) !== 'category' ? 'hidden' : '' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Seleccionar Categorías
                        </label>
                        <div class="max-h-40 overflow-y-auto border border-gray-300 rounded-md p-3">
                            @foreach($categories as $category)
                                <label class="flex items-center py-1">
                                    <input type="checkbox" 
                                           name="category_ids[]" 
                                           value="{{ $category->id }}"
                                           {{ in_array($category->id, old('category_ids', $promotion->category_ids ?? [])) ? 'checked' : '' }}
                                           class="mr-2 text-blue-600 focus:ring-blue-500">
                                    {{ $category->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div id="product-selection" class="{{ old('apply_to', $promotion->apply_to) !== 'product' ? 'hidden' : '' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Seleccionar Productos
                        </label>
                        <div class="max-h-40 overflow-y-auto border border-gray-300 rounded-md p-3">
                            @foreach($products as $product)
                                <label class="flex items-center py-1">
                                    <input type="checkbox" 
                                           name="product_ids[]" 
                                           value="{{ $product->id }}"
                                           {{ in_array($product->id, old('product_ids', $promotion->product_ids ?? [])) ? 'checked' : '' }}
                                           class="mr-2 text-blue-600 focus:ring-blue-500">
                                    {{ $product->name }} - ${{ number_format($product->price, 2) }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Column - Constraints & Timing -->
                <div class="space-y-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Restricciones y Programación</h3>
                    
                    <!-- Minimum Amount -->
                    <div>
                        <label for="minimum_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Monto Mínimo de Compra
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" 
                                   id="minimum_amount" 
                                   name="minimum_amount" 
                                   value="{{ old('minimum_amount', $promotion->minimum_amount) }}"
                                   step="0.01" 
                                   min="0" 
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Dejar vacío para sin mínimo</p>
                        @error('minimum_amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Max Uses -->
                    <div>
                        <label for="max_uses" class="block text-sm font-medium text-gray-700 mb-2">
                            Máximo Número de Usos
                        </label>
                        <input type="number" 
                               id="max_uses" 
                               name="max_uses" 
                               value="{{ old('max_uses', $promotion->max_uses) }}"
                               min="1" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Dejar vacío para uso ilimitado</p>
                        @error('max_uses')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha y Hora de Inicio *
                        </label>
                        <input type="datetime-local" 
                               id="start_date" 
                               name="start_date" 
                               value="{{ old('start_date', $promotion->start_date->format('Y-m-d\TH:i')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                               required>
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha y Hora de Fin *
                        </label>
                        <input type="datetime-local" 
                               id="end_date" 
                               name="end_date" 
                               value="{{ old('end_date', $promotion->end_date->format('Y-m-d\TH:i')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                               required>
                        @error('end_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Quick Duration Buttons -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Duración Rápida
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" onclick="setQuickDuration(1)" class="px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-md">
                                1 día
                            </button>
                            <button type="button" onclick="setQuickDuration(3)" class="px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-md">
                                3 días
                            </button>
                            <button type="button" onclick="setQuickDuration(7)" class="px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-md">
                                1 semana
                            </button>
                            <button type="button" onclick="setQuickDuration(30)" class="px-3 py-2 text-sm bg-gray-100 hover:bg-gray-200 rounded-md">
                                1 mes
                            </button>
                        </div>
                    </div>

                    <!-- Active Toggle -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}
                                   class="mr-2 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-gray-700">
                                Promoción Activa
                            </span>
                        </label>
                        <p class="mt-1 text-sm text-gray-500">Si está desactivada, no se aplicará aunque esté en el rango de fechas</p>
                    </div>

                    <!-- Preview Section -->
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <h4 class="text-sm font-medium text-blue-900 mb-2">Vista Previa</h4>
                        <div id="promotion-preview" class="text-sm text-blue-800">
                            <!-- Preview will be updated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 flex items-center justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.promotions.index') }}" class="btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Actualizar Promoción
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle apply_to change
    document.getElementById('apply_to').addEventListener('change', function() {
        const categoryDiv = document.getElementById('category-selection');
        const productDiv = document.getElementById('product-selection');
        
        if (this.value === 'category') {
            categoryDiv.classList.remove('hidden');
            productDiv.classList.add('hidden');
        } else if (this.value === 'product') {
            productDiv.classList.remove('hidden');
            categoryDiv.classList.add('hidden');
        } else {
            categoryDiv.classList.add('hidden');
            productDiv.classList.add('hidden');
        }
        updatePreview();
    });

    // Handle discount type change
    document.querySelectorAll('input[name="type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const symbol = document.getElementById('discount-symbol');
            symbol.textContent = this.value === 'percentage' ? '%' : '$';
            updatePreview();
        });
    });

    // Update preview on input changes
    ['name', 'discount_value', 'minimum_amount'].forEach(fieldName => {
        document.getElementById(fieldName).addEventListener('input', updatePreview);
    });

    function updatePreview() {
        const name = document.getElementById('name').value || 'Nueva Promoción';
        const type = document.querySelector('input[name="type"]:checked')?.value || 'percentage';
        const value = document.getElementById('discount_value').value || '0';
        const applyTo = document.getElementById('apply_to').value;
        const minimum = document.getElementById('minimum_amount').value;
        
        let preview = `<strong>${name}</strong><br>`;
        
        if (type === 'percentage') {
            preview += `${value}% de descuento `;
        } else {
            preview += `$${value} de descuento `;
        }
        
        if (applyTo === 'all') {
            preview += 'en todos los productos';
        } else if (applyTo === 'category') {
            preview += 'en categorías seleccionadas';
        } else {
            preview += 'en productos seleccionados';
        }
        
        if (minimum && minimum > 0) {
            preview += `<br>Compra mínima: $${minimum}`;
        }
        
        document.getElementById('promotion-preview').innerHTML = preview;
    }

    // Quick duration buttons
    window.setQuickDuration = function(days) {
        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');
        
        if (!startInput.value) {
            const now = new Date();
            startInput.value = now.toISOString().slice(0, 16);
        }
        
        const startDate = new Date(startInput.value);
        const endDate = new Date(startDate);
        endDate.setDate(endDate.getDate() + days);
        
        endInput.value = endDate.toISOString().slice(0, 16);
    };

    // Initialize preview
    updatePreview();
});
</script>
@endsection
