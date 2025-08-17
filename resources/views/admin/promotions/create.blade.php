@extends('layouts.app')

@section('title', 'Nueva Promoción - Sistema POS')
@section('page-title', 'Nueva Promoción')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Nueva Promoción</h1>
            <p class="text-gray-600">Crea una nueva promoción con descuentos y ofertas especiales</p>
        </div>
        <a href="{{ route('admin.promotions.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <form method="POST" action="{{ route('admin.promotions.store') }}" class="p-6 space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de la Promoción *
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                           placeholder="Ej: Descuento en Hamburguesas"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Descuento *
                    </label>
                    <select id="type" 
                            name="type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('type') border-red-500 @enderror"
                            required>
                        <option value="">Seleccionar tipo</option>
                        <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Porcentaje (%)</option>
                        <option value="fixed_amount" {{ old('type') === 'fixed_amount' ? 'selected' : '' }}>Monto Fijo ($)</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Descripción
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                          placeholder="Descripción detallada de la promoción...">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Discount Configuration -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div>
                    <label for="discount_value" class="block text-sm font-medium text-gray-700 mb-2">
                        Valor del Descuento *
                    </label>
                    <div class="relative">
                        <input type="number" 
                               id="discount_value" 
                               name="discount_value" 
                               value="{{ old('discount_value') }}"
                               step="0.01" 
                               min="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('discount_value') border-red-500 @enderror"
                               placeholder="0.00"
                               required>
                        <div id="discount_symbol" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500">
                            <span id="symbol_text">%</span>
                        </div>
                    </div>
                    @error('discount_value')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="minimum_amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Monto Mínimo
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" 
                               id="minimum_amount" 
                               name="minimum_amount" 
                               value="{{ old('minimum_amount', '0') }}"
                               step="0.01" 
                               min="0"
                               class="w-full pl-7 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('minimum_amount') border-red-500 @enderror"
                               placeholder="0.00">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Compra mínima para aplicar la promoción</p>
                    @error('minimum_amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="max_uses" class="block text-sm font-medium text-gray-700 mb-2">
                        Máximo de Usos
                    </label>
                    <input type="number" 
                           id="max_uses" 
                           name="max_uses" 
                           value="{{ old('max_uses') }}"
                           min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('max_uses') border-red-500 @enderror"
                           placeholder="Ilimitado">
                    <p class="mt-1 text-xs text-gray-500">Dejar vacío para uso ilimitado</p>
                    @error('max_uses')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Application Scope -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-4">
                    Aplicar Promoción A *
                </label>
                <div class="space-y-4">
                    <label class="flex items-center">
                        <input type="radio" 
                               name="apply_to" 
                               value="all" 
                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
                               {{ old('apply_to') === 'all' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Todos los productos</span>
                    </label>
                    
                    <label class="flex items-center">
                        <input type="radio" 
                               name="apply_to" 
                               value="category" 
                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
                               {{ old('apply_to') === 'category' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Categorías específicas</span>
                    </label>
                    
                    <label class="flex items-center">
                        <input type="radio" 
                               name="apply_to" 
                               value="product" 
                               class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300"
                               {{ old('apply_to') === 'product' ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Productos específicos</span>
                    </label>
                </div>
                @error('apply_to')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Categories Selection -->
            <div id="categories_section" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Seleccionar Categorías
                </label>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-4">
                    @foreach($categories as $category)
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="applicable_items[]" 
                                   value="{{ $category->id }}"
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Products Selection -->
            <div id="products_section" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Seleccionar Productos
                </label>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-4">
                    @foreach($products as $product)
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="applicable_items[]" 
                                   value="{{ $product->id }}"
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-700">{{ $product->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Time Configuration -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Configuración de Tiempo</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha y Hora de Inicio *
                        </label>
                        <input type="datetime-local" 
                               id="start_date" 
                               name="start_date" 
                               value="{{ old('start_date', now()->format('Y-m-d\TH:i')) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('start_date') border-red-500 @enderror"
                               required>
                        @error('start_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Duración *
                        </label>
                        <select id="duration_type" 
                                name="duration_type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('duration_type') border-red-500 @enderror"
                                required>
                            <option value="">Seleccionar</option>
                            <option value="hours" {{ old('duration_type') === 'hours' ? 'selected' : '' }}>Horas</option>
                            <option value="days" {{ old('duration_type') === 'days' ? 'selected' : '' }}>Días</option>
                        </select>
                        @error('duration_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration_value" class="block text-sm font-medium text-gray-700 mb-2">
                            Duración *
                        </label>
                        <input type="number" 
                               id="duration_value" 
                               name="duration_value" 
                               value="{{ old('duration_value', '1') }}"
                               min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('duration_value') border-red-500 @enderror"
                               placeholder="1"
                               required>
                        @error('duration_value')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            Ejemplos: "12 horas", "5 días"
                        </p>
                    </div>
                </div>
            </div>

            <!-- Preview Section -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Vista Previa</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div id="preview_content" class="text-gray-500">
                        Complete el formulario para ver la vista previa de la promoción
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.promotions.index') }}" class="btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Crear Promoción
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const symbolText = document.getElementById('symbol_text');
    const applyToRadios = document.querySelectorAll('input[name="apply_to"]');
    const categoriesSection = document.getElementById('categories_section');
    const productsSection = document.getElementById('products_section');
    
    // Update discount symbol based on type
    typeSelect.addEventListener('change', function() {
        if (this.value === 'percentage') {
            symbolText.textContent = '%';
        } else if (this.value === 'fixed_amount') {
            symbolText.textContent = '$';
        }
        updatePreview();
    });
    
    // Show/hide applicable items sections
    applyToRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            categoriesSection.classList.add('hidden');
            productsSection.classList.add('hidden');
            
            if (this.value === 'category') {
                categoriesSection.classList.remove('hidden');
            } else if (this.value === 'product') {
                productsSection.classList.remove('hidden');
            }
            updatePreview();
        });
    });
    
    // Update preview
    function updatePreview() {
        const name = document.getElementById('name').value;
        const type = document.getElementById('type').value;
        const discountValue = document.getElementById('discount_value').value;
        const applyTo = document.querySelector('input[name="apply_to"]:checked')?.value;
        const startDate = document.getElementById('start_date').value;
        const durationType = document.getElementById('duration_type').value;
        const durationValue = document.getElementById('duration_value').value;
        
        const previewContent = document.getElementById('preview_content');
        
        if (!name || !type || !discountValue || !applyTo) {
            previewContent.innerHTML = '<span class="text-gray-500">Complete el formulario para ver la vista previa de la promoción</span>';
            return;
        }
        
        let discountText = type === 'percentage' ? `${discountValue}%` : `$${discountValue}`;
        let applyText = applyTo === 'all' ? 'todos los productos' : 
                       applyTo === 'category' ? 'categorías seleccionadas' : 'productos seleccionados';
        let durationText = durationType && durationValue ? `${durationValue} ${durationType === 'hours' ? 'horas' : 'días'}` : '';
        
        previewContent.innerHTML = `
            <div class="space-y-2">
                <h4 class="font-medium text-gray-900">${name}</h4>
                <p class="text-sm text-gray-600">
                    <strong>Descuento:</strong> ${discountText} en ${applyText}
                </p>
                ${durationText ? `<p class="text-sm text-gray-600"><strong>Duración:</strong> ${durationText}</p>` : ''}
                ${startDate ? `<p class="text-sm text-gray-600"><strong>Inicia:</strong> ${new Date(startDate).toLocaleString()}</p>` : ''}
            </div>
        `;
    }
    
    // Add event listeners for preview updates
    document.getElementById('name').addEventListener('input', updatePreview);
    document.getElementById('discount_value').addEventListener('input', updatePreview);
    document.getElementById('start_date').addEventListener('change', updatePreview);
    document.getElementById('duration_type').addEventListener('change', updatePreview);
    document.getElementById('duration_value').addEventListener('input', updatePreview);
    
    // Trigger initial preview
    updatePreview();
});
</script>
@endpush
@endsection
