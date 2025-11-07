@extends('layouts.app')

@section('title', 'Nueva Venta - TOCHIS')
{{-- 1. Título de página estándar --}}
@section('page-title')
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Punto de Venta</h1>
        <p class="text-gray-400 text-sm">Crea una nueva orden de cliente</p>
    </div>
@endsection

@push('styles')
<style>
    /* Tu excelente CSS personalizado se mantiene intacto */
    .tochis-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(249, 115, 22, 0.1);
        transition: all 0.3s ease;
    }
    
    .tochis-card:hover {
        box-shadow: 0 8px 30px rgba(249, 115, 22, 0.15);
        transform: translateY(-2px);
    }
    
    .product-card {
        background: white;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid transparent;
        overflow: hidden;
    }
    
    .product-card:hover {
        border-color: #f97316;
        box-shadow: 0 10px 25px rgba(249, 115, 22, 0.2);
        transform: translateY(-4px) scale(1.02);
    }
    
    .category-btn {
        transition: all 0.2s ease;
        border-radius: 8px;
        font-weight: 500;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
        white-space: nowrap;
    }
    
    .category-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .category-btn.active {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%) !important;
        color: white !important;
        box-shadow: 0 2px 8px rgba(249, 115, 22, 0.3);
        border-color: #f97316;
    }
    
    .category-btn.active:hover {
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.4);
    }
    
    .tochis-gradient {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
    }
    
    .tochis-gradient-light {
        background: linear-gradient(135deg, #fed7aa 0%, #fb923c 100%);
    }
    
    .cart-item {
        transition: all 0.3s ease;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }
    
    .cart-item:hover {
        border-color: #f97316;
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.1);
    }
    
    .combo-suggestion-panel {
        background: linear-gradient(135deg, #fef7e0 0%, #fed7aa 100%);
        border: 2px solid #f59e0b;
        animation: slideInFromTop 0.4s ease-out;
    }
    
    @keyframes slideInFromTop {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .discount-item {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        border-left: 4px solid #22c55e;
    }
    
    .promotion-badge {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border: 1px solid #f87171;
    }
</style>
@endpush

@section('content')
<div class="fade-in" id="pos-app">

    {{-- 2. Alertas Estándar (Añadidas) --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3 text-green-500"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    
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

    {{-- 3. Layout Principal (h-screen eliminado) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="tochis-card mb-4">
                <div class="px-4 py-2 tochis-gradient rounded-t-2xl">
                    <h3 class="text-sm font-bold text-white flex items-center">
                        <i class="fas fa-tags mr-2 text-sm"></i>
                        Categorías
                    </h3>
                </div>
                <div class="px-4 py-3">
                    <div class="flex flex-wrap gap-2">
                        {{-- Botón "Todos" (Estilo original mantenido, es único de esta vista) --}}
                        <button onclick="filterByCategory('all')" 
                                class="category-btn active px-3 py-1.5 text-xs bg-gray-100 text-gray-700 hover:bg-gray-200 transition duration-200 rounded-lg font-medium flex items-center"
                                data-category="all">
                            <i class="fas fa-th-large mr-1.5 text-xs"></i>
                            Todos
                        </button>
                        @foreach($categories as $category)
                            @if($category->activeProducts->count() > 0)
                                <button onclick="filterByCategory({{ $category->id }})" 
                                        class="category-btn px-3 py-1.5 text-xs text-white hover:opacity-90 transition duration-200 rounded-lg font-medium flex items-center"
                                        style="background: linear-gradient(135deg, {{ $category->color }}, {{ $category->color }}dd)"
                                        data-category="{{ $category->id }}">
                                    <i class="fas fa-utensils mr-1.5 text-xs"></i>
                                    {{ $category->name }}
                                    <span class="ml-2 bg-white bg-opacity-30 px-1.5 py-0.5 rounded-full text-xs font-bold">
                                        {{ $category->activeProducts->count() }}
                                    </span>
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="tochis-card overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="fas fa-hamburger mr-3 text-gray-500"></i>
                            Menú de Platillos
                        </h3>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            {{-- 4. Barra de búsqueda estandarizada --}}
                            <input type="text" 
                                   id="search-product" 
                                   placeholder="Buscar platillos..."
                                   class="block w-80 pl-12 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                        </div>
                    </div>
                </div>
                
                <div class="p-6 h-96 overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100">
                    <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                        @foreach($categories as $category)
                            @foreach($category->activeProducts as $product)
                                <div class.="product-card bg-white border-2 border-transparent rounded-xl p-4 hover:shadow-xl cursor-pointer transform hover:-translate-y-2"
                                     data-category="{{ $category->id }}"
                                     data-product-id="{{ $product->id }}"
                                     data-product-name="{{ strtolower($product->name) }}"
                                     data-has-options="{{ $product->category->is_customizable ? 'true' : 'false' }}"
                                     onclick="handleProductClick({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->is_food ? 'true' : 'false' }})">
                                    
                                    <div class="relative mb-4">
                                        @if($product->image)
                                            <img src="{{ Storage::url($product->image) }}" 
                                                 alt="{{ $product->name }}"
                                                 class="w-full h-28 object-cover rounded-lg shadow-md">
                                        @else
                                            <div class="w-full h-28 tochis-gradient-light rounded-lg flex items-center justify-center shadow-md">
                                                @if($product->is_food)
                                                    <i class="fas fa-utensils text-white text-3xl"></i>
                                                @else
                                                    <i class="fas fa-box text-white text-3xl"></i>
                                                @endif
                                            </div>
                                        @endif
                                        
                                        @if($product->is_food)
                                            <div class="absolute top-2 right-2">
                                                <span class="tochis-gradient text-white text-xs px-3 py-1 rounded-full shadow-lg flex items-center font-bold">
                                                    <i class="fas fa-utensils mr-1"></i>Comida
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <h4 class="font-semibold text-gray-900 text-sm leading-tight line-clamp-2 min-h-[2.5rem]">
                                            {{ $product->name }}
                                        </h4>
                                        
                                        <div class="text-center">
                                            <p class="text-xl font-bold text-green-600">
                                                ${{ number_format($product->price, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    @if($product->options->count() > 0)
                                        <span class="inline-block mt-1 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                            <i class="fas fa-cog mr-1"></i>Personalizable
                                        </span>
                                    @endif
                                    
                                    @if($product->preparation_time)
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-clock mr-1"></i>{{ $product->preparation_time }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                    
                    <div id="no-products" class="hidden text-center py-8">
                        <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No se encontraron Platillos</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="tochis-card h-full flex flex-col">
                <div class="px-6 py-4 tochis-gradient rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <i class="fas fa-shopping-basket mr-3"></i>
                            Orden de Compra
                        </h3>
                        <div class="flex items-center space-x-2">
                            <button onclick="resetAppliedCombos()" 
                                    class="text-white hover:text-orange-200 text-xs font-medium transition-colors duration-200"
                                    title="Resetear sugerencias de combos">
                                <i class="fas fa-redo mr-1"></i>
                                Combos
                            </button>
                            <button onclick="clearCart()" 
                                    class="text-white hover:text-orange-200 text-sm font-semibold transition-colors duration-200"
                                    id="clear-cart-btn" style="display: none;">
                                <i class="fas fa-trash-alt mr-1"></i>
                                Limpiar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto p-6 bg-gradient-to-b from-orange-50 to-white">
                    <div id="cart-items" class="space-y-4">
                        <div id="empty-cart" class="text-center py-12">
                            <div class="tochis-gradient-light rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-shopping-basket text-white text-2xl"></i>
                            </div>
                            <p class="text-gray-600 font-semibold">Tu orden está vacía</p>
                            <p class="text-sm text-gray-500 mt-1">Selecciona deliciosos Platillos para agregar</p>
                        </div>
                    </div>
                </div>

                <div class="border-t-2 border-gray-200 p-6 bg-white space-y-4">
                    <div class="space-y-3">
                        <div class="flex justify-between text-base font-medium">
                            <span class="text-gray-700">Subtotal:</span>
                            <span id="subtotal" class="text-gray-800">$0.00</span>
                        </div>
                        
                        <div id="available-promotions" class="hidden">
                            <div class="bg-orange-50 border-2 border-orange-200 rounded-lg p-4 mb-3">
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-fire text-orange-500 mr-2"></i>
                                    <h4 class="font-bold text-orange-800">¡Ofertas Especiales!</h4>
                                </div>
                                <div id="promotions-list" class="space-y-2 text-sm text-orange-700">
                                    </div>
                            </div>
                        </div>
                        
                        <div id="applied-discounts" class="hidden">
                            <div class="flex justify-between text-base font-medium text-green-600">
                                <span><i class="fas fa-percentage mr-2"></i>Descuentos:</span>
                                <span id="discount-amount">-$0.00</span>
                            </div>
                            <div id="discount-details" class="text-sm text-green-600 space-y-1 ml-6 mt-1">
                                </div>
                        </div>
                        
                        <div class="flex justify-between text-base font-medium">
                            <span class="text-gray-700">Impuesto:</span>
                            <span id="tax" class="text-gray-800">$0.00</span>
                        </div>
                        <div class="flex justify-between text-xl font-bold border-t-2 border-gray-200 pt-3">
                            <span class="text-gray-800">Total a Pagar:</span>
                            <span id="total" class="text-gray-700">$0.00</span>
                        </div>
                    </div>

                    <div id="combo-suggestions" class="hidden mb-4">
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-300 rounded-xl p-5 shadow-lg">
                            <div class="flex items-center mb-4">
                                <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-lightbulb text-white text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="text-lg font-bold text-yellow-800">¡Sugerencia de Combo!</h4>
                                    <p class="text-sm text-yellow-700">Ahorra dinero con estas combinaciones</p>
                                </div>
                            </div>
                            <div id="combo-suggestions-list" class="space-y-3">
                                </div>
                        </div>
                    </div>

                    <div>
                        <label for="sale-notes" class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-sticky-note mr-2 text-gray-500"></i>
                            Notas Especiales
                        </label>
                        <textarea id="sale-notes" 
                                  rows="3"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Observaciones especiales para esta orden..."></textarea>
                    </div>

                    <div>
                        <label for="payment-method" class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-credit-card mr-2 text-gray-500"></i>
                            Método de Pago
                        </label>
                        <select id="payment-method" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium">
                            <option value="cash">💵 Efectivo</option>
                            <option value="card">💳 Tarjeta</option>
                            <option value="transfer">🏦 Transferencia</option>
                        </select>
                    </div>

                    <div id="payment-section" style="display: none;">
                        <label for="paid-amount" class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign mr-2 text-gray-500"></i>
                            Monto Pagado
                        </label>
                        <input type="number" 
                               id="paid-amount" 
                               step="0.01" 
                               min="0"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg font-bold"
                               placeholder="0.00">
                        <div id="change-display" class="mt-3 p-3 bg-green-50 border-l-4 border-green-400 rounded-lg" style="display: none;">
                            <p class="text-green-800 font-bold flex items-center">
                                <i class="fas fa-hand-holding-usd mr-2"></i>
                                Cambio: $<span id="change-amount">0.00</span>
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <button onclick="processSale()" 
                                id="process-sale-btn"
                                class="w-full tochis-gradient hover:from-orange-600 hover:to-orange-700 text-white font-bold py-4 px-6 rounded-lg transition-all duration-300 disabled:bg-gray-400 disabled:cursor-not-allowed transform hover:scale-105 shadow-lg hover:shadow-xl"
                                disabled>
                            <i class="fas fa-cash-register mr-2"></i>
                            Procesar Venta
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 
============================================================
MODAL DE PERSONALIZACIÓN
============================================================
--}}
<div id="customizeModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" onclick="closeCustomizeModal()">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                            <i class="fas fa-cog mr-2 text-blue-600"></i>Personalizar Platillo
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 id="product-name" class="font-medium text-gray-900"></h4>
                                <p id="product-price" class="text-lg font-bold text-green-600"></p>
                            </div>

                            <div id="observations-section" class="hidden">
                                <h5 class="font-medium text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-times-circle mr-2 text-red-600"></i>Quitar ingredientes
                                </h5>
                                <div id="observations-list" class="space-y-2 bg-red-50 p-3 rounded-lg max-h-32 overflow-y-auto">
                                    </div>
                            </div>

                            <div id="specialties-section" class="hidden">
                                <h5 class="font-medium text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-plus-circle mr-2 text-green-600"></i>Agregar extras
                                </h5>
                                <div id="specialties-list" class="space-y-2 bg-green-50 p-3 rounded-lg max-h-32 overflow-y-auto">
                                    </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cantidad</label>
                                <div class="flex items-center justify-center space-x-4">
                                    <button type="button" onclick="updateQuantity(-1)" 
                                            class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" id="modal-quantity" value="1" min="1" 
                                           class="w-20 text-center text-xl font-bold border-2 border-gray-300 rounded-lg py-2">
                                    <button type="button" onclick="updateQuantity(1)" 
                                            class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-full">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="modal-notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-sticky-note mr-1"></i>Instrucciones especiales
                                </label>
                                {{-- 6. Input Estandarizado --}}
                                <textarea id="modal-notes" rows="3" 
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                          placeholder="Ej: Sin sal, término medio, extra caliente..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                {{-- 7. Botones Estandarizados --}}
                <button type="button" onclick="addCustomizedToCart()" 
                        class="btn-primary w-full sm:w-auto sm:ml-3">
                    <i class="fas fa-cart-plus mr-2"></i>Agregar al carrito
                </button>
                <button type="button" onclick="closeCustomizeModal()" 
                        class="btn-secondary w-full sm:w-auto mt-3 sm:mt-0">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- Tu script masivo va aquí. Lo he omitido por brevedad, pero debe ir todo el bloque <script>...</script> que tenías. --}}
<script>
let cart = [];
let subtotal = 0;
let tax = 0;
let total = 0;
let availablePromotions = [];
let appliedPromotions = [];
let totalDiscount = 0;

// Variables para combos
let availableCombos = [];
let suggestedCombos = [];
let appliedCombos = []; 
let hasActiveCombo = false; 
let comboCheckTimeout = null;

// --- Mover 'onclick' a 'addEventListener' ---
document.addEventListener('DOMContentLoaded', function() {
    
    // Filtro de categorías
    document.querySelectorAll('.category-btn').forEach(button => {
        button.addEventListener('click', () => filterByCategory(button.dataset.category));
    });

    // Limpiar carrito
    const clearCartBtn = document.getElementById('clear-cart-btn');
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', clearCart);
    }
    
    // Resetear combos
    const resetCombosBtn = document.querySelector('button[onclick="resetAppliedCombos()"]');
    if (resetCombosBtn) {
        resetCombosBtn.onclick = null; // Remover onclick
        resetCombosBtn.addEventListener('click', resetAppliedCombos);
    }
    
    // Búsqueda de productos
    document.getElementById('search-product').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const products = document.querySelectorAll('.product-card');
        let visibleCount = 0;

        products.forEach(product => {
            const productName = product.dataset.productName;
            
            // Lógica para mostrar/ocultar basado en categoría activa
            const activeCategoryBtn = document.querySelector('.category-btn.active');
            const currentCategory = activeCategoryBtn ? activeCategoryBtn.dataset.category : 'all';
            const inCategory = (currentCategory === 'all' || product.dataset.category === currentCategory);

            if (productName.includes(searchTerm) && inCategory) {
                product.style.display = 'block';
                visibleCount++;
            } else {
                product.style.display = 'none';
            }
        });

        document.getElementById('no-products').style.display = visibleCount === 0 ? 'block' : 'none';
    });
    
    // Clic en tarjeta de producto
    document.getElementById('products-grid').addEventListener('click', function(e) {
        const productCard = e.target.closest('.product-card');
        if (productCard) {
            const { productId, productName, hasOptions } = productCard.dataset;
            // Encontrar el precio del elemento
            const priceText = productCard.querySelector('.text-green-600').textContent;
            const price = parseFloat(priceText.replace('$', ''));
            // Encontrar si es comida (basado en el ícono)
            const isFood = productCard.querySelector('.fa-utensils') !== null;
            
            handleProductClick(productId, productName, price, isFood, hasOptions === 'true');
        }
    });

    // --- Lógica del Carrito (Delegación de eventos) ---
    const cartItemsContainer = document.getElementById('cart-items');
    cartItemsContainer.addEventListener('click', function(e) {
        const target = e.target;
        const itemRow = target.closest('.cart-item-row'); // Asumimos que cada item tiene esta clase
        
        if (!itemRow) return;
        const index = parseInt(itemRow.dataset.index, 10);
        
        // Botón de remover
        if (target.closest('.remove-item-btn')) {
            removeCartItem(index);
            return;
        }
        
        // Botón de restar
        if (target.closest('.decrement-qty-btn')) {
            updateCartItemQuantity(index, cart[index].quantity - 1);
            return;
        }
        
        // Botón de sumar
        if (target.closest('.increment-qty-btn')) {
            updateCartItemQuantity(index, cart[index].quantity + 1);
            return;
        }
    });
    
    // --- Lógica del Modal de Personalización ---
    document.getElementById('add-customized-btn').addEventListener('click', addCustomizedToCart);
    document.getElementById('close-modal-btn').addEventListener('click', closeCustomizeModal);
    document.getElementById('modal-qty-minus').addEventListener('click', () => updateQuantity(-1));
    document.getElementById('modal-qty-plus').addEventListener('click', () => updateQuantity(1));
    
    // Delegación para opciones de personalización
    const optionsContainer = document.getElementById('customizeModal');
    optionsContainer.addEventListener('change', function(e) {
        if (e.target.matches('input[name="observations[]"]')) {
            const optionId = e.target.value;
            const optionLabel = e.target.closest('label').textContent.trim();
            toggleOption(optionId, optionLabel, 0, 'observation', e.target.checked);
        }
        if (e.target.matches('input[name="specialties[]"]')) {
            const optionId = e.target.value;
            const optionLabel = e.target.closest('label').querySelector('.option-name').textContent.trim();
            const priceText = e.target.closest('label').querySelector('.option-price')?.textContent || '';
            const price = parseFloat(priceText.replace('+$', '')) || 0;
            toggleOption(optionId, optionLabel, price, 'specialty', e.target.checked);
        }
    });
    
    // --- Lógica de Pago ---
    document.getElementById('process-sale-btn').addEventListener('click', processSale);
    
    const paymentMethodSelect = document.getElementById('payment-method');
    const paymentSection = document.getElementById('payment-section');
    const paidAmountInput = document.getElementById('paid-amount');
    
    paymentMethodSelect.addEventListener('change', function() {
        const paymentMethod = this.value;
        
        if (paymentMethod === 'cash') {
            paymentSection.style.display = 'block';
            paidAmountInput.placeholder = 'Monto recibido en efectivo';
            paidAmountInput.addEventListener('input', calculateChange);
            calculateChange(); // Calcular por si ya hay un valor
        } else {
            paymentSection.style.display = 'none';
            document.getElementById('change-display').style.display = 'none';
            paidAmountInput.removeEventListener('input', calculateChange);
        }
    });
    
    // --- Inicialización ---
    filterByCategory('all'); // Filtrar por "Todos" al cargar
    updateCartDisplay();
    loadAvailablePromotions();
    paymentMethodSelect.dispatchEvent(new Event('change'));
    
}); // Fin de DOMContentLoaded


// ============================================
// LÓGICA DE LA APLICACIÓN (Funciones)
// =HAZ DEJADO EL SCRIPT VACÍO. PEGA AQUÍ TODO EL JAVASCRIPT QUE YA TENÍAS=
// ============================================

// --- Lógica de Clic de Producto ---
function handleProductClick(productId, productName, price, isFood = false, hasOptions = false) {
    console.log('Product clicked:', {productId, productName, price, isFood, hasOptions});
    
    const categoryId = document.querySelector(`[data-product-id="${productId}"]`)?.dataset.category || null;

    if (isFood && hasOptions) {
        openCustomizationModal(productId, productName, price, categoryId);
        return;
    }
    
    addToCart(productId, productName, price, isFood, categoryId);
}

// --- Lógica de Filtros ---
function filterByCategory(categoryId) {
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.classList.remove('active');
        // Quitar estilos de color explícitos si no es el botón "Todos"
        if(btn.dataset.category !== 'all') {
             btn.style.background = `linear-gradient(135deg, ${btn.dataset.color}, ${btn.dataset.color}dd)`;
             btn.classList.add('text-white');
        } else {
            btn.classList.add('bg-gray-100', 'text-gray-700');
        }
    });
    
    const activeBtn = document.querySelector(`[data-category="${categoryId}"]`);
    if (activeBtn) {
        activeBtn.classList.add('active');
        if (categoryId === 'all') {
             activeBtn.classList.remove('bg-gray-100', 'text-gray-700');
        } else {
             activeBtn.style.background = ''; // Dejar que la clase .active maneje el color
        }
    }
    
    // Lógica de filtrado (combinada con búsqueda)
    document.getElementById('search-product').dispatchEvent(new Event('input'));
}

// --- Lógica de Carrito ---
function addToCart(productId, productName, price, isFood, categoryId, observations = [], specialties = [], notes = '') {
    const finalPrice = price + specialties.reduce((sum, s) => sum + s.price, 0);

    const existingItem = cart.find(item => 
        item.id === productId &&
        JSON.stringify(item.observations || []) === JSON.stringify(observations || []) &&
        JSON.stringify(item.specialties || []) === JSON.stringify(specialties || []) &&
        item.notes === notes
    );
    
    if (existingItem) {
        existingItem.quantity++;
    } else {
        cart.push({
            id: productId,
            name: productName,
            originalName: productName, // Guardar nombre original
            price: finalPrice,
            basePrice: price,
            quantity: 1,
            isFood: isFood,
            categoryId: categoryId,
            observations: observations,
            specialties: specialties,
            notes: notes
        });
    }
    updateCartDisplay();
}

function removeCartItem(index) {
    const itemToRemove = cart[index];
    if (!itemToRemove) return;

    cart.splice(index, 1);

    // Si se elimina un descuento de combo, resetear el flag
    if (itemToRemove.isComboDiscount) {
        const remainingComboDiscounts = cart.filter(item => item.isComboDiscount);
        if (remainingComboDiscounts.length === 0) {
            hasActiveCombo = false;
        }
    }
    
    appliedPromotions = [];
    totalDiscount = 0;
    
    updateCartDisplay();
}

function updateCartItemQuantity(index, quantity) {
    if (cart[index] && cart[index].isComboDiscount) return;
    
    if (quantity <= 0) {
        removeCartItem(index);
    } else if (cart[index]) {
        cart[index].quantity = quantity;
        appliedPromotions = [];
        totalDiscount = 0;
        updateCartDisplay();
    }
}

function clearCart() {
    if (confirm('¿Estás seguro de que deseas limpiar el carrito?')) {
        cart = [];
        appliedPromotions = [];
        totalDiscount = 0;
        appliedCombos = [];
        hasActiveCombo = false;
        updateCartDisplay();
        hideCombos();
    }
}

function updateCartDisplay() {
    const cartItems = document.getElementById('cart-items');
    const clearBtn = document.getElementById('clear-cart-btn');
    
    if (cart.length === 0) {
        cartItems.innerHTML = `<div id="empty-cart" class="text-center py-12">...</div>`; // (código de empty-cart)
        clearBtn.style.display = 'none';
    } else {
        clearBtn.style.display = 'block';
        let cartHTML = '';
        
        cart.forEach((item, index) => {
            const isComboDiscount = item.isComboDiscount || false;
            const priceText = isComboDiscount ? `-$${Math.abs(item.price).toFixed(2)}` : `$${item.price.toFixed(2)}`;
            const itemClass = isComboDiscount ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200';
            
            let customizations = '';
            if (item.observations && item.observations.length > 0) {
                customizations += `<div class="text-xs text-red-600 mt-1"><i class="fas fa-minus-circle mr-1"></i>Sin: ${item.observations.map(o => o.name).join(', ')}</div>`;
            }
            if (item.specialties && item.specialties.length > 0) {
                customizations += `<div class="text-xs text-green-600 mt-1"><i class="fas fa-plus-circle mr-1"></i>Con: ${item.specialties.map(s => s.name).join(', ')}</div>`;
            }
            if (item.notes) {
                customizations += `<div class="text-xs text-gray-600 mt-1 italic"><i class="fas fa-sticky-note mr-1"></i>${item.notes}</div>`;
            }

            cartHTML += `
                <div class="cart-item-row flex items-center justify-between p-3 border ${itemClass} rounded-lg" data-index="${index}">
                    <div class="flex-1 min-w-0">
                        <h4 class="font-medium ${isComboDiscount ? 'text-green-700' : 'text-gray-900'} text-sm truncate">${item.originalName || item.name}</h4>
                        <p class="text-sm ${isComboDiscount ? 'text-green-600 font-medium' : 'text-gray-600'}">${priceText} ${!isComboDiscount ? 'c/u' : ''}</p>
                        ${customizations}
                    </div>
                    <div class="flex items-center space-x-2 ml-2">
                        ${!isComboDiscount ? `
                            <button class="decrement-qty-btn w-6 h-6 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center"><i class="fas fa-minus text-xs"></i></button>
                            <span class="w-8 text-center font-medium">${item.quantity}</span>
                            <button class="increment-qty-btn w-6 h-6 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center"><i class="fas fa-plus text-xs"></i></button>
                        ` : `
                            <span class="text-sm text-green-600 font-medium px-2">DESCUENTO</span>
                        `}
                        <button class="remove-item-btn w-6 h-6 ${isComboDiscount ? 'bg-green-100 hover:bg-green-200 text-green-600' : 'bg-red-100 hover:bg-red-200 text-red-600'} rounded-full flex items-center justify-center">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        cartItems.innerHTML = cartHTML;
    }
    
    calculateTotals();
    checkForComboSuggestions();
}

function calculateTotals() {
    subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    
    try {
        calculateApplicablePromotions();
    } catch (error) {
        console.error('Error calculando promociones:', error);
        totalDiscount = 0;
    }
    
    tax = 0; // Sin impuestos por ahora
    total = subtotal - totalDiscount; // El subtotal ya incluye descuentos de combo (precio negativo)
    
    document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('tax').textContent = `$${tax.toFixed(2)}`;
    document.getElementById('total').textContent = `$${total.toFixed(2)}`;
    
    const processSaleBtn = document.getElementById('process-sale-btn');
    const paymentSection = document.getElementById('payment-section');
    
    if (cart.length > 0) {
        processSaleBtn.disabled = false;
        paymentSection.style.display = 'block';
    } else {
        processSaleBtn.disabled = true;
        paymentSection.style.display = 'none';
    }
}

function calculateChange() {
    const paidAmount = parseFloat(document.getElementById('paid-amount').value) || 0;
    const changeDisplay = document.getElementById('change-display');
    const changeAmount = document.getElementById('change-amount');
    
    if (paidAmount >= total) {
        const change = paidAmount - total;
        changeAmount.textContent = change.toFixed(2);
        changeDisplay.style.display = 'block';
    } else {
        changeDisplay.style.display = 'none';
    }
}

// --- Lógica del Modal ---
let currentProduct = null;
let selectedOptions = [];

function openCustomizationModal(productId, productName, price, categoryId) {
    const modal = document.getElementById('customizeModal');
    document.getElementById('product-name').textContent = productName;
    document.getElementById('product-price').textContent = '$' + parseFloat(price).toFixed(2);
    
    document.querySelectorAll('#customizeModal input[type="checkbox"]').forEach(cb => cb.checked = false);
    document.getElementById('modal-quantity').value = 1;
    document.getElementById('modal-notes').value = '';
    
    loadProductOptions(productId);
    
    currentProduct = { id: productId, name: productName, price: price, categoryId: categoryId };
    selectedOptions = [];
    
    modal.classList.remove('hidden');
}

function loadProductOptions(productId) {
    const observationsList = document.getElementById('observations-list');
    const specialtiesList = document.getElementById('specialties-list');
    const observationsSection = document.getElementById('observations-section');
    const specialtiesSection = document.getElementById('specialties-section');
    
    observationsList.innerHTML = '';
    specialtiesList.innerHTML = '';
    
    fetch(`/api/customization-options?product_id=${productId}`)
        .then(response => response.json())
        .then(data => {
            if (data.observations && data.observations.length > 0) {
                observationsSection.classList.remove('hidden');
                data.observations.forEach(opt => {
                    observationsList.innerHTML += `
                        <label class="flex items-center space-x-2 p-2 hover:bg-red-100 rounded cursor-pointer">
                            <input type="checkbox" name="observations[]" value="${opt.id}" class="text-red-600 focus:ring-red-500">
                            <span class="option-name text-sm">${opt.name}</span>
                        </label>
                    `;
                });
            } else {
                observationsSection.classList.add('hidden');
            }
            
            if (data.specialties && data.specialties.length > 0) {
                specialtiesSection.classList.remove('hidden');
                data.specialties.forEach(opt => {
                    specialtiesList.innerHTML += `
                        <label class="flex items-center space-x-2 p-2 hover:bg-green-100 rounded cursor-pointer">
                            <input type="checkbox" name="specialties[]" value="${opt.id}" class="text-green-600 focus:ring-green-500">
                            <span class="option-name text-sm flex-1">${opt.name}</span>
                            ${opt.price > 0 ? `<span class="option-price text-xs text-green-600 ml-auto">+$${opt.price}</span>` : ''}
                        </label>
                    `;
                });
            } else {
                specialtiesSection.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error loading customization options:', error);
            observationsSection.classList.add('hidden');
            specialtiesSection.classList.add('hidden');
        });
}

function closeCustomizeModal() {
    document.getElementById('customizeModal').classList.add('hidden');
    currentProduct = null;
    selectedOptions = [];
}

function updateQuantity(change) {
    const input = document.getElementById('modal-quantity');
    const newValue = parseInt(input.value) + change;
    if (newValue >= 1) {
        input.value = newValue;
    }
}

function toggleOption(id, name, price, type, isChecked) {
    const index = selectedOptions.findIndex(opt => opt.id == id);
    if (isChecked) {
        if (index === -1) {
            selectedOptions.push({ id, name, price, type });
        }
    } else {
        if (index > -1) {
            selectedOptions.splice(index, 1);
        }
    }
}

function addCustomizedToCart() {
    if (!currentProduct) return;
    
    const quantity = parseInt(document.getElementById('modal-quantity').value);
    const notes = document.getElementById('modal-notes').value;
    
    const observations = selectedOptions.filter(option => option.type === 'observation');
    const specialties = selectedOptions.filter(option => option.type === 'specialty');
    
    addToCart(
        currentProduct.id, 
        currentProduct.name, 
        currentProduct.price, 
        true, // isFood
        currentProduct.categoryId, 
        observations, 
        specialties, 
        notes
    );
    
    closeCustomizeModal();
}

// --- Lógica de Promociones ---
async function loadAvailablePromotions() {
    try {
        const response = await fetch('{{ route("cashier.sale.promotions") }}');
        availablePromotions = await response.json();
    } catch (error) {
        console.error('Error cargando promociones:', error);
        availablePromotions = [];
    }
}

function calculateApplicablePromotions() {
    appliedPromotions = [];
    totalDiscount = 0;
    if (cart.length === 0 || availablePromotions.length === 0 || hasActiveCombo) {
        updatePromotionsDisplay();
        return;
    }

    const currentSubtotal = cart.reduce((sum, item) => !item.isComboDiscount ? sum + (item.price * item.quantity) : sum, 0);
    const saleDetails = cart.filter(item => !item.isComboDiscount).map(item => ({
        id: item.id,
        categoryId: item.categoryId,
        subtotal: item.price * item.quantity,
        quantity: item.quantity
    }));

    availablePromotions.forEach(promotion => {
        if (promotion.minimum_amount && currentSubtotal < promotion.minimum_amount) return;

        let applicableAmount = 0;
        if (promotion.apply_to === 'all') {
            applicableAmount = currentSubtotal;
        } else if (promotion.apply_to === 'category') {
            const promotionCategoryIds = promotion.category_ids || [];
            saleDetails.forEach(detail => {
                if (promotionCategoryIds.includes(parseInt(detail.categoryId))) {
                    applicableAmount += detail.subtotal;
                }
            });
        } else if (promotion.apply_to === 'product') {
            const promotionProductIds = promotion.product_ids || [];
            saleDetails.forEach(detail => {
                if (promotionProductIds.includes(parseInt(detail.id))) {
                    applicableAmount += detail.subtotal;
                }
            });
        }

        if (applicableAmount > 0) {
            let discount = 0;
            if (promotion.type === 'percentage') {
                discount = (applicableAmount * parseFloat(promotion.discount_value)) / 100;
            } else if (promotion.type === 'fixed') { // Asegúrate que el tipo sea 'fixed'
                discount = Math.min(parseFloat(promotion.discount_value), applicableAmount);
            }

            if (discount > 0) {
                appliedPromotions.push({ ...promotion, calculatedDiscount: discount });
                totalDiscount += discount;
            }
        }
    });

    updatePromotionsDisplay();
}

function updatePromotionsDisplay() {
    const availableDiv = document.getElementById('available-promotions');
    const appliedDiv = document.getElementById('applied-discounts');
    const promotionsListDiv = document.getElementById('promotions-list');
    const discountDetailsDiv = document.getElementById('discount-details');
    const discountAmountSpan = document.getElementById('discount-amount');

    if (hasActiveCombo) {
        availableDiv.classList.add('hidden');
    } else {
        const unappliedPromotions = availablePromotions.filter(promo => 
            !appliedPromotions.some(applied => applied.id === promo.id)
        );
        if (unappliedPromotions.length > 0) {
            availableDiv.classList.remove('hidden');
            promotionsListDiv.innerHTML = unappliedPromotions.map(promo => `
                <div class="flex justify-between items-center">
                    <span>${promo.name}</span>
                    <span class="font-medium">${promo.type === 'percentage' ? promo.discount_value + '%' : '$' + promo.discount_value}</span>
                </div>
                ${promo.minimum_amount > 0 ? `<div class="text-xs opacity-75">Mín: $${promo.minimum_amount}</div>` : ''}
            `).join('');
        } else {
            availableDiv.classList.add('hidden');
        }
    }

    if (totalDiscount > 0) {
        appliedDiv.classList.remove('hidden');
        discountAmountSpan.textContent = `-$${totalDiscount.toFixed(2)}`;
        discountDetailsDiv.innerHTML = appliedPromotions.map(promo => `
            <div class="flex justify-between">
                <span>${promo.name}</span>
                <span>-$${(promo.calculatedDiscount || 0).toFixed(2)}</span>
            </div>
        `).join('');
    } else {
        appliedDiv.classList.add('hidden');
    }
}

// --- Lógica de Combos ---
function checkForComboSuggestions() {
    if (comboCheckTimeout) clearTimeout(comboCheckTimeout);
    if (cart.length < 2) {
        hideCombos();
        return;
    }
    
    comboCheckTimeout = setTimeout(() => {
        const cartData = cart.filter(item => !item.isComboDiscount).map(item => ({
            id: parseInt(item.id),
            quantity: item.quantity
        }));
        
        if (cartData.length < 2) {
             hideCombos();
             return;
        }

        fetch('{{ route("cashier.sale.combos.suggest") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ cart_products: cartData })
        })
        .then(response => response.json())
        .then(data => {
            if (data.has_suggestions && data.suggestions.length > 0) {
                showComboSuggestions(data.suggestions);
            } else {
                hideCombos();
            }
        })
        .catch(error => {
            console.error('Error en checkForComboSuggestions:', error);
            hideCombos();
        });
    }, 1000);
}

function showComboSuggestions(suggestions) {
    if (hasActiveCombo) {
        hideCombos();
        return;
    }
    
    const filteredSuggestions = suggestions.filter(suggestion => !appliedCombos.includes(suggestion.combo.id));
    suggestedCombos = filteredSuggestions;
    
    if (filteredSuggestions.length === 0) {
        hideCombos();
        return;
    }
    
    const comboListDiv = document.getElementById('combo-suggestions-list');
    comboListDiv.innerHTML = ''; // Limpiar
    
    filteredSuggestions.forEach((suggestion, index) => {
        const combo = suggestion.combo;
        const matchLevel = suggestion.match_level;
        const missingProducts = suggestion.missing_products;
        
        const comboHtml = `
            <div class="bg-white border-2 border-gray-200 rounded-xl p-5 shadow-md...">
                <h4 class="font-bold text-gray-900 text-lg">${combo.name}</h4>
                <p class="text-sm text-gray-600">${combo.description}</p>
                ${missingProducts.length === 0 ? `
                    <button onclick="applyCombo(${combo.id})" class="w-full bg-green-600 ...">
                        <i class="fas fa-check mr-2"></i> Aplicar Combo Completo
                    </button>
                ` : `
                    <button onclick="addMissingProducts(${JSON.stringify(missingProducts).replace(/"/g, '&quot;')}, ${combo.id})" class="w-full bg-blue-600 ...">
                        <i class="fas fa-plus mr-2"></i> Agregar Platillos Faltantes
                    </button>
                `}
                <button onclick="dismissCombo(${index})" class="w-full bg-gray-500 ...">
                    <i class="fas fa-times mr-2"></i> Descartar
                </button>
            </div>
        `;
        comboListDiv.innerHTML += comboHtml;
    });
    
    document.getElementById('combo-suggestions').classList.remove('hidden');
    playComboNotification();
}

function hideCombos() {
    const comboSuggestionsDiv = document.getElementById('combo-suggestions');
    if (comboSuggestionsDiv) {
        comboSuggestionsDiv.classList.add('hidden');
    }
}

function applyCombo(comboId) {
    const cartData = cart.filter(item => !item.isComboDiscount).map(item => ({
        id: parseInt(item.id),
        quantity: item.quantity
    }));
    
    fetch('{{ route("cashier.sale.combos.apply") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ combo_id: comboId, cart_products: cartData })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const comboData = data.combo;
            const savings = parseFloat(comboData.savings) || 0;
            
            if (savings > 0) {
                const discountItem = {
                    id: 'combo-discount-' + comboId,
                    name: `🎉 Descuento ${comboData.name}`,
                    price: -savings,
                    quantity: 1,
                    isComboDiscount: true,
                    comboId: comboId,
                    originalName: `Descuento ${comboData.name}`,
                    specialties: [],
                    observations: []
                };
                
                const existingDiscountIndex = cart.findIndex(item => item.isComboDiscount && item.comboId === comboId);
                
                if (existingDiscountIndex !== -1) {
                    cart[existingDiscountIndex] = discountItem;
                } else {
                    cart.push(discountItem);
                }
                
                updateCartDisplay();
                showSuccessMessage(`🎉 ${data.message}`);
            } else {
                showSuccessMessage('✅ Combo aplicado');
            }
            
            if (!appliedCombos.includes(comboId)) {
                appliedCombos.push(comboId);
            }
            hasActiveCombo = true;
            hideCombos();
        } else {
            alert(data.message || "Error al aplicar el combo");
        }
    })
    .catch(error => console.error('Error aplicando combo:', error));
}

async function addMissingProducts(missingProducts, comboId) {
    if (typeof missingProducts === 'string') {
        try {
            missingProducts = JSON.parse(missingProducts.replace(/&quot;/g, '"'));
        } catch (error) {
            console.error('Error parseando missingProducts:', error);
            return;
        }
    }
    
    if (!Array.isArray(missingProducts) || missingProducts.length === 0) return;
    
    try {
        for (const product of missingProducts) {
            const cartItem = {
                id: product.id,
                name: product.name,
                price: parseFloat(product.price),
                quantity: 1,
                specialties: [],
                observations: [],
                originalName: product.name,
                categoryId: product.category_id // Asumiendo que la API lo envía
            };
            
            const existingIndex = cart.findIndex(item => 
                item.id === product.id && 
                (!item.specialties || item.specialties.length === 0) &&
                (!item.observations || item.observations.length === 0)
            );
            
            if (existingIndex !== -1) {
                cart[existingIndex].quantity += 1;
            } else {
                cart.push(cartItem);
            }
        }
        
        updateCartDisplay();
        
        setTimeout(() => {
            showSuccessMessage(`✅ Se agregaron ${missingProducts.length} platillos. Aplicando combo...`);
            applyCombo(comboId); // Aplicar el combo automáticamente
        }, 500);
        
    } catch (error) {
        console.error('Error en addMissingProducts:', error);
    }
}

function dismissCombo(index) {
    if (suggestedCombos[index]) {
        const comboId = suggestedCombos[index].combo.id;
        if (!appliedCombos.includes(comboId)) {
            appliedCombos.push(comboId);
        }
        
        // Remover del DOM
        const comboListDiv = document.getElementById('combo-suggestions-list');
        if (comboListDiv.children[index]) {
             comboListDiv.children[index].remove();
        }
       
        if (comboListDiv.children.length === 0) {
            hideCombos();
        }
        showSuccessMessage('Sugerencia descartada');
    }
}

function resetAppliedCombos() {
    if (confirm('¿Quieres resetear las sugerencias de combos?')) {
        appliedCombos = [];
        showSuccessMessage('Sugerencias de combos reseteadas');
        checkForComboSuggestions(); // Volver a verificar
    }
}

function playComboNotification() {
    try {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
        oscillator.frequency.setValueAtTime(1000, audioContext.currentTime + 0.1);
        gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.5);
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.5);
    } catch (error) {
        console.log('Audio no disponible:', error);
    }
}

function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg z-50';
    alertDiv.innerHTML = `<div class="flex items-center"><i class="fas fa-check-circle mr-2"></i><span>${message}</span></div>`;
    document.body.appendChild(alertDiv);
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.parentNode.removeChild(alertDiv);
        }
    }, 5000);
}


// --- Lógica de Venta ---
function processSale() {
    if (cart.length === 0) {
        alert('Tu orden está vacía.');
        return;
    }
    
    const paymentMethod = document.getElementById('payment-method').value;
    let paidAmount = total;
    
    if (paymentMethod === 'cash') {
        paidAmount = parseFloat(document.getElementById('paid-amount').value) || 0;
        if (paidAmount < total) {
            alert('El monto pagado es insuficiente');
            return;
        }
    }
    
    const saleNotes = document.getElementById('sale-notes').value || '';
    
    // Filtrar los descuentos de combo antes de enviar
    const saleData = {
        products: cart.filter(item => !item.isComboDiscount).map(item => ({
            id: item.id,
            quantity: item.quantity,
            price: item.price,
            observations: item.observations || [],
            specialties: item.specialties || []
        })),
        // Enviar los descuentos de combo por separado
        combo_discounts: cart.filter(item => item.isComboDiscount).map(item => ({
            combo_id: item.comboId,
            discount_amount: Math.abs(item.price)
        })),
        payment_method: paymentMethod,
        paid_amount: paidAmount,
        notes: saleNotes,
        subtotal: subtotal,
        total_discount: totalDiscount,
        total_amount: total
    };

    const processSaleBtn = document.getElementById('process-sale-btn');
    const originalText = processSaleBtn.innerHTML;
    processSaleBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Procesando...';
    processSaleBtn.disabled = true;
    
    fetch('{{ route("cashier.sale.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(saleData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Venta realizada exitosamente!\nTotal: $${total.toFixed(2)}\nCambio: $${(data.change || 0).toFixed(2)}`);
            cart = [];
            appliedPromotions = [];
            totalDiscount = 0;
            appliedCombos = [];
            hasActiveCombo = false;
            updateCartDisplay();
            document.getElementById('paid-amount').value = '';
            document.getElementById('change-display').style.display = 'none';
            document.getElementById('sale-notes').value = '';
        } else {
            alert('Error: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error al procesar la venta:', error);
        alert('Error al procesar la venta: ' + error.message);
    })
    .finally(() => {
        processSaleBtn.innerHTML = originalText;
        // El botón se reactivará automáticamente por updateCartDisplay si el carrito > 0 (lo cual no será el caso)
        // O lo podemos forzar aquí si se queda deshabilitado
         updateCartDisplay(); // Esto lo pondrá en 'disabled'
    });
}
</script>
@endpush