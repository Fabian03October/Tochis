@extends('layouts.app')

@section('title', 'Crear Producto - Sistema POS')
@section('page-title', 'Crear Nuevo Producto')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Crear Nuevo Producto</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Completa la información del producto para agregarlo al inventario.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver a Productos
                </a>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Basic Information -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                    Información Básica
                </h3>
            </div>
            
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Product Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre del Producto *
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               placeholder="Ej: Coca-Cola 600ml"
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
                              placeholder="Descripción detallada del producto...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Categoría *
                    </label>
                    <select id="category_id" 
                            name="category_id" 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('category_id') border-red-500 @enderror"
                            required>
                        <option value="">Selecciona una categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Si no encuentras la categoría apropiada, puedes 
                        <a href="{{ route('admin.categories.create') }}" target="_blank" class="text-blue-600 hover:text-blue-800">crear una nueva categoría</a>.
                    </p>
                </div>
            </div>
        </div>

        <!-- Pricing -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-dollar-sign mr-2 text-green-600"></i>
                    Precios
                </h3>
            </div>
            
            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Cost -->
                    <div>
                        <label for="cost" class="block text-sm font-medium text-gray-700 mb-2">
                            Costo
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" 
                                   id="cost" 
                                   name="cost" 
                                   step="0.01" 
                                   min="0"
                                   value="{{ old('cost', '0.00') }}"
                                   class="block w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('cost') border-red-500 @enderror"
                                   placeholder="0.00">
                        </div>
                        @error('cost')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">
                            Costo de adquisición del producto.
                        </p>
                    </div>

                    <!-- Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            Precio de Venta *
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="number" 
                                   id="price" 
                                   name="price" 
                                   step="0.01" 
                                   min="0.01"
                                   value="{{ old('price') }}"
                                   class="block w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('price') border-red-500 @enderror"
                                   placeholder="0.00"
                                   required>
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Margin Calculation -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Margen de Ganancia
                        </label>
                        <div class="p-3 bg-gray-50 rounded-md">
                            <div id="margin-display" class="text-sm">
                                <div class="text-gray-600">Introduce los precios para calcular</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image and Settings -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-image mr-2 text-purple-600"></i>
                    Imagen y Configuración
                </h3>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Product Image -->
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Imagen del Producto
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors duration-200">
                        <div class="space-y-1 text-center">
                            <div id="image-preview" class="hidden mb-4">
                                <img id="preview-img" src="" alt="Preview" class="mx-auto h-32 w-32 object-cover rounded-lg">
                            </div>
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                    <span>Seleccionar archivo</span>
                                    <input id="image" name="image" type="file" class="sr-only" accept="image/*">
                                </label>
                                <p class="pl-1">o arrastra y suelta</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                PNG, JPG, GIF hasta 2MB
                            </p>
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div>
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="is_active" 
                                   name="is_active" 
                                   type="checkbox" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="is_active" class="font-medium text-gray-700">
                                Producto Activo
                            </label>
                            <p class="text-gray-500">
                                Los productos activos aparecen en el punto de venta y pueden ser vendidos.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-4 bg-white rounded-lg shadow p-6">
            <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                <i class="fas fa-times mr-2"></i>
                Cancelar
            </a>
            <button type="submit" class="btn-primary">
                <i class="fas fa-save mr-2"></i>
                Crear Producto
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Margin calculation
    const costInput = document.getElementById('cost');
    const priceInput = document.getElementById('price');
    const marginDisplay = document.getElementById('margin-display');

    function calculateMargin() {
        const cost = parseFloat(costInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;

        if (cost > 0 && price > 0) {
            const margin = price - cost;
            const marginPercent = ((margin / cost) * 100).toFixed(2);
            
            marginDisplay.innerHTML = `
                <div class="text-green-600 font-medium">Ganancia: $${margin.toFixed(2)}</div>
                <div class="text-gray-600">Margen: ${marginPercent}%</div>
            `;
        } else if (price > 0) {
            marginDisplay.innerHTML = `
                <div class="text-blue-600">Precio: $${price.toFixed(2)}</div>
                <div class="text-gray-500">Introduce el costo para calcular margen</div>
            `;
        } else {
            marginDisplay.innerHTML = `
                <div class="text-gray-600">Introduce los precios para calcular</div>
            `;
        }
    }

    costInput.addEventListener('input', calculateMargin);
    priceInput.addEventListener('input', calculateMargin);
});
</script>
@endsection
