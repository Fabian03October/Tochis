@extends('layouts.app')

@section('title', 'Editar Combo - Sistema POS')
@section('page-title', 'Editar Combo')

@section('content')
<div class="fade-in">
    <!-- Header Actions -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Editar: {{ $combo->name }}</h2>
                <p class="mt-2 text-sm text-gray-600">
                    Modifica la información del combo
                </p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none flex space-x-3">
                <a href="{{ route('admin.combos.show', $combo) }}" 
                   class="btn-secondary">
                    <i class="fas fa-eye mr-2"></i>Ver Combo
                </a>
                <a href="{{ route('admin.combos.index') }}" 
                   class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Volver
                </a>
            </div>
        </div>
    </div>
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        Por favor corrige los siguientes errores:
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.combos.update', $combo) }}" method="POST" id="comboForm">
        @csrf
        @method('PUT')
        
        <div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
            <!-- Panel Principal -->
            <div class="lg:col-span-8">
                <!-- Información Básica -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Información Básica del Combo
                        </h3>
                    </div>
                    
                    <div class="px-6 py-4 space-y-6">
                        <!-- Nombre -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">
                                Nombre del Combo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $combo->name) }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Ej: Combo Familiar Deluxe"
                                   required>
                        </div>
                        
                        <!-- Descripción -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">
                                Descripción del Combo
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Describe que incluye este combo...">{{ old('description', $combo->description) }}</textarea>
                        </div>
                        
                        <!-- Precios -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">
                                    Precio del Combo <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" 
                                           id="price" 
                                           name="price" 
                                           step="0.01" 
                                           min="0"
                                           value="{{ old('price', $combo->price) }}"
                                           class="pl-7 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="0.00"
                                           required>
                                </div>
                            </div>
                            
                            <div>
                                <label for="min_items" class="block text-sm font-medium text-gray-700">
                                    Productos Mínimos <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       id="min_items" 
                                       name="min_items" 
                                       min="2"
                                       value="{{ old('min_items', $combo->min_items) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       required>
                                <p class="mt-1 text-sm text-gray-500">Mínimo 2 productos</p>
                            </div>
                        </div>
                        
                        <!-- Configuración -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $combo->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Combo activo
                                </label>
                            </div>
                            
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="auto_suggest" 
                                       name="auto_suggest" 
                                       value="1"
                                       {{ old('auto_suggest', $combo->auto_suggest) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="auto_suggest" class="ml-2 block text-sm text-gray-900">
                                    Sugerencia automática
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Selección de Productos -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden mt-6">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Productos del Combo
                        </h3>
                        <p class="mt-1 text-sm text-gray-600">Selecciona los productos que incluirá este combo</p>
                    </div>
                    
                    <div class="px-6 py-4">
                        <!-- Buscador de productos -->
                        <div class="mb-4">
                            <input type="text" 
                                   id="productSearch" 
                                   placeholder="Buscar productos..."
                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <!-- Lista de productos -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto" id="productsList">
                            @foreach($products as $product)
                                @php
                                    $isSelected = $combo->products->contains($product->id);
                                @endphp
                                <div class="product-item border rounded-lg p-3 hover:bg-gray-50 transition-colors duration-200 {{ $isSelected ? 'border-blue-500 bg-blue-50' : 'border-gray-200' }}" 
                                     data-product-name="{{ strtolower($product->name) }}"
                                     data-category-name="{{ strtolower($product->category->name) }}">
                                    <div class="flex items-start space-x-3">
                                        <input type="checkbox" 
                                               name="products[]" 
                                               value="{{ $product->id }}"
                                               {{ $isSelected ? 'checked' : '' }}
                                               class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded product-checkbox"
                                               onchange="updateProductSelection(this)">
                                        
                                        <div class="flex-1 min-w-0">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" 
                                                     alt="{{ $product->name }}"
                                                     class="w-12 h-12 rounded-lg object-cover mb-2">
                                            @else
                                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center mb-2">
                                                    <i class="fas fa-utensils text-gray-400"></i>
                                                </div>
                                            @endif
                                            
                                            <h4 class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</h4>
                                            <p class="text-xs text-gray-500">{{ $product->category->name }}</p>
                                            <p class="text-sm font-semibold text-green-600">${{ number_format($product->price, 2) }}</p>
                                            
                                            @if(!$product->is_active)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-1">
                                                    No disponible
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-4 text-sm text-gray-600">
                            <span id="selectedCount">{{ $combo->products->count() }}</span> productos seleccionados
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Panel Lateral -->
            <div class="lg:col-span-4 mt-6 lg:mt-0">
                <!-- Resumen del Combo -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Resumen del Combo
                        </h3>
                    </div>
                    
                    <div class="px-6 py-4 space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="text-sm text-gray-600">Precio Individual Total</div>
                            <div class="text-xl font-bold text-gray-900" id="originalPrice">
                                ${{ number_format($combo->products->sum('price'), 2) }}
                            </div>
                        </div>
                        
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="text-sm text-green-600">Precio del Combo</div>
                            <div class="text-xl font-bold text-green-800" id="comboPrice">
                                ${{ number_format($combo->price, 2) }}
                            </div>
                        </div>
                        
                        <div class="bg-orange-50 p-4 rounded-lg">
                            <div class="text-sm text-orange-600">Ahorro para el Cliente</div>
                            <div class="text-xl font-bold text-orange-800" id="savings">
                                ${{ number_format($combo->products->sum('price') - $combo->price, 2) }}
                            </div>
                            <div class="text-sm text-orange-600" id="savingsPercentage">
                                ({{ $combo->products->sum('price') > 0 ? number_format((($combo->products->sum('price') - $combo->price) / $combo->products->sum('price')) * 100, 1) : 0 }}% descuento)
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Productos Seleccionados -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden mt-6">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            Productos Seleccionados
                        </h3>
                    </div>
                    
                    <div class="px-6 py-4">
                        <div id="selectedProductsList" class="space-y-2">
                            @foreach($combo->products as $product)
                                <div class="selected-product flex items-center justify-between p-2 bg-gray-50 rounded" data-product-id="{{ $product->id }}">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium">{{ $product->name }}</span>
                                    </div>
                                    <span class="text-sm text-green-600 font-semibold">${{ number_format($product->price, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                        
                        <div id="noProductsMessage" class="text-center py-4 text-gray-500 {{ $combo->products->count() > 0 ? 'hidden' : '' }}">
                            <i class="fas fa-box-open text-2xl mb-2"></i>
                            <p>No hay productos seleccionados</p>
                        </div>
                    </div>
                </div>
                
                <!-- Botones de Acción -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden mt-6">
                    <div class="px-6 py-4 space-y-3">
                        <button type="submit" class="w-full btn-primary">
                            <i class="fas fa-save mr-2"></i>Actualizar Combo
                        </button>
                        
                        <a href="{{ route('admin.combos.show', $combo) }}" class="w-full btn-secondary text-center">
                            <i class="fas fa-eye mr-2"></i>Ver Combo
                        </a>
                        
                        <a href="{{ route('admin.combos.index') }}" class="w-full btn-secondary text-center">
                            <i class="fas fa-times mr-2"></i>Cancelar
                        </a>
                    </div>
                </div>
            </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productSearch = document.getElementById('productSearch');
    const productsList = document.getElementById('productsList');
    const selectedCount = document.getElementById('selectedCount');
    const selectedProductsList = document.getElementById('selectedProductsList');
    const noProductsMessage = document.getElementById('noProductsMessage');
    const originalPrice = document.getElementById('originalPrice');
    const comboPrice = document.getElementById('comboPrice');
    const savings = document.getElementById('savings');
    const savingsPercentage = document.getElementById('savingsPercentage');
    const priceInput = document.getElementById('price');
    
    // Buscar productos
    productSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const productItems = productsList.querySelectorAll('.product-item');
        
        productItems.forEach(item => {
            const productName = item.dataset.productName;
            const categoryName = item.dataset.categoryName;
            
            if (productName.includes(searchTerm) || categoryName.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
    
    // Actualizar precio del combo cuando cambia
    priceInput.addEventListener('input', updateCalculations);
    
    // Función para actualizar cálculos
    function updateCalculations() {
        const selectedProducts = document.querySelectorAll('.product-checkbox:checked');
        let totalOriginalPrice = 0;
        
        selectedProducts.forEach(checkbox => {
            const productItem = checkbox.closest('.product-item');
            const priceText = productItem.querySelector('.text-green-600').textContent;
            const price = parseFloat(priceText.replace('$', '').replace(',', ''));
            totalOriginalPrice += price;
        });
        
        const comboPriceValue = parseFloat(priceInput.value) || 0;
        const savingsValue = totalOriginalPrice - comboPriceValue;
        const savingsPercentageValue = totalOriginalPrice > 0 ? (savingsValue / totalOriginalPrice) * 100 : 0;
        
        originalPrice.textContent = '$' + totalOriginalPrice.toFixed(2);
        comboPrice.textContent = '$' + comboPriceValue.toFixed(2);
        savings.textContent = '$' + Math.max(0, savingsValue).toFixed(2);
        savingsPercentage.textContent = '(' + Math.max(0, savingsPercentageValue).toFixed(1) + '% descuento)';
    }
});

function updateProductSelection(checkbox) {
    const productItem = checkbox.closest('.product-item');
    const productId = checkbox.value;
    const productName = productItem.querySelector('h4').textContent;
    const productPrice = productItem.querySelector('.text-green-600').textContent;
    
    if (checkbox.checked) {
        // Agregar producto
        productItem.classList.add('border-blue-500', 'bg-blue-50');
        productItem.classList.remove('border-gray-200');
        
        const selectedProduct = document.createElement('div');
        selectedProduct.className = 'selected-product flex items-center justify-between p-2 bg-gray-50 rounded';
        selectedProduct.dataset.productId = productId;
        selectedProduct.innerHTML = `
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium">${productName}</span>
            </div>
            <span class="text-sm text-green-600 font-semibold">${productPrice}</span>
        `;
        
        document.getElementById('selectedProductsList').appendChild(selectedProduct);
    } else {
        // Remover producto
        productItem.classList.remove('border-blue-500', 'bg-blue-50');
        productItem.classList.add('border-gray-200');
        
        const selectedProduct = document.querySelector(`.selected-product[data-product-id="${productId}"]`);
        if (selectedProduct) {
            selectedProduct.remove();
        }
    }
    
    // Actualizar contador
    const selectedProductsCount = document.querySelectorAll('.product-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = selectedProductsCount;
    
    // Mostrar/ocultar mensaje de productos vacíos
    const noProductsMessage = document.getElementById('noProductsMessage');
    if (selectedProductsCount === 0) {
        noProductsMessage.classList.remove('hidden');
    } else {
        noProductsMessage.classList.add('hidden');
    }
    
    // Actualizar cálculos
    updateCalculations();
}

function updateCalculations() {
    const selectedProducts = document.querySelectorAll('.product-checkbox:checked');
    let totalOriginalPrice = 0;
    
    selectedProducts.forEach(checkbox => {
        const productItem = checkbox.closest('.product-item');
        const priceText = productItem.querySelector('.text-green-600').textContent;
        const price = parseFloat(priceText.replace('$', '').replace(',', ''));
        totalOriginalPrice += price;
    });
    
    const priceInput = document.getElementById('price');
    const comboPriceValue = parseFloat(priceInput.value) || 0;
    const savingsValue = totalOriginalPrice - comboPriceValue;
    const savingsPercentageValue = totalOriginalPrice > 0 ? (savingsValue / totalOriginalPrice) * 100 : 0;
    
    document.getElementById('originalPrice').textContent = '$' + totalOriginalPrice.toFixed(2);
    document.getElementById('comboPrice').textContent = '$' + comboPriceValue.toFixed(2);
    document.getElementById('savings').textContent = '$' + Math.max(0, savingsValue).toFixed(2);
    document.getElementById('savingsPercentage').textContent = '(' + Math.max(0, savingsPercentageValue).toFixed(1) + '% descuento)';
}
</script>
@endsection
