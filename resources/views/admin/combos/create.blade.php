@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Crear Nuevo Combo</h1>
        <a href="{{ route('admin.combos.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('admin.combos.store') }}" method="POST" id="combo-form">
            @csrf
            
            <!-- Información básica del combo -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre del Combo *
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ej: Combo Tochis Familiar"
                           required>
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                        Precio del Combo *
                    </label>
                    <input type="number" 
                           name="price" 
                           id="price"
                           step="0.01"
                           min="0"
                           value="{{ old('price') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                           placeholder="200.00"
                           required>
                    @error('price')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Descripción
                </label>
                <textarea name="description" 
                          id="description"
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Descripción del combo...">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Configuración del combo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="min_items" class="block text-sm font-medium text-gray-700 mb-2">
                        Mínimo de productos para sugerir
                    </label>
                    <input type="number" 
                           name="min_items" 
                           id="min_items"
                           min="2"
                           value="{{ old('min_items', 2) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex items-center">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="auto_suggest" 
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                               {{ old('auto_suggest') ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Sugerir automáticamente</span>
                    </label>
                </div>

                <div class="flex items-center">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="is_active" 
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                               {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">Combo activo</span>
                    </label>
                </div>
            </div>

            <!-- Selector de productos -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Productos del Combo</h3>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Lista de productos disponibles -->
                    <div>
                        <h4 class="text-md font-medium text-gray-700 mb-3">Productos Disponibles</h4>
                        <div class="border border-gray-300 rounded-md p-4 max-h-96 overflow-y-auto">
                            @foreach($products->groupBy('category.name') as $categoryName => $categoryProducts)
                                <div class="mb-4">
                                    <h5 class="font-semibold text-gray-600 mb-2">{{ $categoryName }}</h5>
                                    @foreach($categoryProducts as $product)
                                        <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                                            <div class="flex items-center">
                                                <input type="checkbox" 
                                                       name="products[]" 
                                                       value="{{ $product->id }}"
                                                       id="product_{{ $product->id }}"
                                                       class="product-checkbox rounded border-gray-300 text-blue-600"
                                                       onchange="toggleProduct({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})">
                                                <label for="product_{{ $product->id }}" class="ml-2 text-sm text-gray-700 cursor-pointer">
                                                    {{ $product->name }}
                                                </label>
                                            </div>
                                            <span class="text-sm text-gray-500">${{ number_format($product->price, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Productos seleccionados -->
                    <div>
                        <h4 class="text-md font-medium text-gray-700 mb-3">Productos del Combo</h4>
                        <div class="border border-gray-300 rounded-md p-4 min-h-96">
                            <div id="selected-products" class="space-y-3">
                                <p class="text-gray-500 text-sm" id="no-products-message">
                                    Selecciona productos de la lista de la izquierda
                                </p>
                            </div>
                            
                            <!-- Resumen de precios -->
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span>Precio original:</span>
                                        <span id="original-price">$0.00</span>
                                    </div>
                                    <div class="flex justify-between font-semibold">
                                        <span>Precio del combo:</span>
                                        <span id="combo-price">$0.00</span>
                                    </div>
                                    <div class="flex justify-between text-green-600 font-semibold">
                                        <span>Ahorro:</span>
                                        <span id="savings">$0.00</span>
                                    </div>
                                    <div class="flex justify-between text-green-600 text-xs">
                                        <span>Descuento:</span>
                                        <span id="discount-percentage">0%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.combos.index') }}" class="btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn-primary" id="submit-btn">
                    Crear Combo
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let selectedProducts = {};
let originalPrice = 0;

function toggleProduct(productId, productName, productPrice) {
    const checkbox = document.getElementById(`product_${productId}`);
    const selectedContainer = document.getElementById('selected-products');
    const noProductsMessage = document.getElementById('no-products-message');
    
    if (checkbox.checked) {
        // Agregar producto
        selectedProducts[productId] = {
            name: productName,
            price: productPrice,
            quantity: 1
        };
        
        // Crear elemento en la lista de seleccionados
        const productDiv = document.createElement('div');
        productDiv.id = `selected_${productId}`;
        productDiv.className = 'flex items-center justify-between p-3 bg-blue-50 rounded-md';
        productDiv.innerHTML = `
            <div class="flex-1">
                <span class="font-medium text-gray-900">${productName}</span>
                <div class="text-sm text-gray-500">$${productPrice.toFixed(2)} c/u</div>
            </div>
            <div class="flex items-center space-x-2">
                <label class="text-sm text-gray-700">Cant:</label>
                <input type="number" 
                       name="quantities[${productId}]" 
                       value="1" 
                       min="1" 
                       max="10"
                       class="w-16 px-2 py-1 border border-gray-300 rounded text-center"
                       onchange="updateQuantity(${productId}, this.value, ${productPrice})">
                <button type="button" 
                        onclick="removeProduct(${productId})"
                        class="text-red-600 hover:text-red-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        selectedContainer.appendChild(productDiv);
        noProductsMessage.style.display = 'none';
    } else {
        // Remover producto
        removeProduct(productId);
    }
    
    updatePriceSummary();
}

function removeProduct(productId) {
    delete selectedProducts[productId];
    
    const productElement = document.getElementById(`selected_${productId}`);
    if (productElement) {
        productElement.remove();
    }
    
    const checkbox = document.getElementById(`product_${productId}`);
    if (checkbox) {
        checkbox.checked = false;
    }
    
    const selectedContainer = document.getElementById('selected-products');
    const noProductsMessage = document.getElementById('no-products-message');
    
    if (Object.keys(selectedProducts).length === 0) {
        noProductsMessage.style.display = 'block';
    }
    
    updatePriceSummary();
}

function updateQuantity(productId, quantity, price) {
    if (selectedProducts[productId]) {
        selectedProducts[productId].quantity = parseInt(quantity);
        updatePriceSummary();
    }
}

function updatePriceSummary() {
    originalPrice = 0;
    
    for (const productId in selectedProducts) {
        const product = selectedProducts[productId];
        originalPrice += product.price * product.quantity;
    }
    
    const comboPrice = parseFloat(document.getElementById('price').value) || 0;
    const savings = originalPrice - comboPrice;
    const discountPercentage = originalPrice > 0 ? (savings / originalPrice * 100) : 0;
    
    document.getElementById('original-price').textContent = `$${originalPrice.toFixed(2)}`;
    document.getElementById('combo-price').textContent = `$${comboPrice.toFixed(2)}`;
    document.getElementById('savings').textContent = `$${savings.toFixed(2)}`;
    document.getElementById('discount-percentage').textContent = `${discountPercentage.toFixed(1)}%`;
    
    // Validar que el combo tenga al menos 2 productos
    const submitBtn = document.getElementById('submit-btn');
    if (Object.keys(selectedProducts).length < 2) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Selecciona al menos 2 productos';
    } else {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Crear Combo';
    }
}

// Actualizar resumen cuando cambie el precio del combo
document.getElementById('price').addEventListener('input', updatePriceSummary);

// Validar formulario antes de enviar
document.getElementById('combo-form').addEventListener('submit', function(e) {
    if (Object.keys(selectedProducts).length < 2) {
        e.preventDefault();
        alert('Debes seleccionar al menos 2 productos para el combo');
        return false;
    }
    
    const comboPrice = parseFloat(document.getElementById('price').value);
    if (comboPrice >= originalPrice) {
        if (!confirm('El precio del combo es igual o mayor al precio original. ¿Estás seguro de continuar?')) {
            e.preventDefault();
            return false;
        }
    }
});
</script>
@endsection
