@extends('layouts.app')

@section('title', 'Nueva Venta - TOCHIS')
@section('page-title', 'Punto de Venta')

@push('styles')
<style>
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
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 h-screen">
        <!-- Products Section -->
        <div class="lg:col-span-2">
            <!-- Categories -->
            <div class="tochis-card mb-4">
                <div class="px-4 py-2 tochis-gradient rounded-t-2xl">
                    <h3 class="text-sm font-bold text-white flex items-center">
                        <i class="fas fa-tags mr-2 text-sm"></i>
                        Categorías
                    </h3>
                </div>
                <div class="px-4 py-3">
                    <div class="flex flex-wrap gap-2">
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

            <!-- Products Grid -->
            <div class="tochis-card overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="fas fa-hamburger mr-3 text-gray-500"></i>
                            Menú de Productos
                        </h3>
                        <div class="relative">
                            <input type="text" 
                                   id="search-product" 
                                   placeholder="Buscar deliciosos platillos..."
                                   class="pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 transition-all duration-300 w-80">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-6 h-96 overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100">
                    <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                        @foreach($categories as $category)
                            @foreach($category->activeProducts as $product)
                                <div class="product-card bg-white border-2 border-transparent rounded-xl p-4 hover:shadow-xl cursor-pointer transform hover:-translate-y-2"
                                     data-category="{{ $category->id }}"
                                     data-product-id="{{ $product->id }}"
                                     data-product-name="{{ strtolower($product->name) }}"
                                     data-has-options="{{ $product->category->is_customizable ? 'true' : 'false' }}"
                                     onclick="handleProductClick({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->is_food ? 'true' : 'false' }})">
                                    
                                    <!-- Imagen del producto -->
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
                                        
                                        <!-- Badge de tipo de producto -->
                                        @if($product->is_food)
                                            <div class="absolute top-2 right-2">
                                                <span class="tochis-gradient text-white text-xs px-3 py-1 rounded-full shadow-lg flex items-center font-bold">
                                                    <i class="fas fa-utensils mr-1"></i>Comida
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Información del producto -->
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
                        <p class="text-gray-500">No se encontraron productos</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="lg:col-span-1">
            <div class="tochis-card h-full flex flex-col">
                <!-- Cart Header -->
                <div class="px-6 py-4 tochis-gradient rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <i class="fas fa-shopping-basket mr-3"></i>
                            Orden de Compra
                        </h3>
                        <button onclick="clearCart()" 
                                class="text-white hover:text-orange-200 text-sm font-semibold transition-colors duration-200"
                                id="clear-cart-btn" style="display: none;">
                            <i class="fas fa-trash-alt mr-1"></i>
                            Limpiar
                        </button>
                    </div>
                </div>

                <!-- Cart Items -->
                <div class="flex-1 overflow-y-auto p-6 bg-gradient-to-b from-orange-50 to-white">
                    <div id="cart-items" class="space-y-4">
                        <div id="empty-cart" class="text-center py-12">
                            <div class="tochis-gradient-light rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-shopping-basket text-white text-2xl"></i>
                            </div>
                            <p class="text-gray-600 font-semibold">Tu orden está vacía</p>
                            <p class="text-sm text-gray-500 mt-1">Selecciona deliciosos platillos para agregar</p>
                        </div>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="border-t-2 border-gray-200 p-6 bg-white space-y-4">
                    <!-- Totals -->
                    <div class="space-y-3">
                        <div class="flex justify-between text-base font-medium">
                            <span class="text-gray-700">Subtotal:</span>
                            <span id="subtotal" class="text-gray-800">$0.00</span>
                        </div>
                        
                        <!-- Promociones disponibles -->
                        <div id="available-promotions" class="hidden">
                            <div class="bg-orange-50 border-2 border-orange-200 rounded-lg p-4 mb-3">
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-fire text-orange-500 mr-2"></i>
                                    <h4 class="font-bold text-orange-800">¡Ofertas Especiales!</h4>
                                </div>
                                <div id="promotions-list" class="space-y-2 text-sm text-orange-700">
                                    <!-- Las promociones se cargarán aquí -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- Descuentos aplicados -->
                        <div id="applied-discounts" class="hidden">
                            <div class="flex justify-between text-base font-medium text-green-600">
                                <span><i class="fas fa-percentage mr-2"></i>Descuentos:</span>
                                <span id="discount-amount">-$0.00</span>
                            </div>
                            <div id="discount-details" class="text-sm text-green-600 space-y-1 ml-6 mt-1">
                                <!-- Los detalles de descuentos se mostrarán aquí -->
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

                    <!-- Sugerencias de Combos -->
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
                                <!-- Las sugerencias se cargarán aquí -->
                            </div>
                        </div>
                    </div>

                    <!-- Notas de la venta -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-sticky-note mr-2 text-gray-500"></i>
                            Notas Especiales
                        </label>
                        <textarea id="sale-notes" 
                                  rows="3"
                                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-500 transition-all duration-300"
                                  placeholder="Observaciones especiales para esta orden..."></textarea>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-credit-card mr-2 text-gray-500"></i>
                            Método de Pago
                        </label>
                        <select id="payment-method" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-500 transition-all duration-300 font-medium">
                            <option value="cash">💵 Efectivo</option>
                            <option value="card">💳 Tarjeta</option>
                            <option value="transfer">🏦 Transferencia</option>
                        </select>
                    </div>

                    <!-- Payment Amount -->
                    <div id="payment-section" style="display: none;">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign mr-2 text-gray-500"></i>
                            Monto Pagado
                        </label>
                        <input type="number" 
                               id="paid-amount" 
                               step="0.01" 
                               min="0"
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-500 transition-all duration-300 text-lg font-bold"
                               placeholder="0.00">
                        <div id="change-display" class="mt-3 p-3 bg-green-50 border-l-4 border-green-400 rounded-lg" style="display: none;">
                            <p class="text-green-800 font-bold flex items-center">
                                <i class="fas fa-hand-holding-usd mr-2"></i>
                                Cambio: $<span id="change-amount">0.00</span>
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
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

@endsection

@push('scripts')
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
let comboCheckTimeout = null;

// Add product to cart - Function called when clicking a product
function handleProductClick(productId, productName, price, isFood = false) {
    console.log('Product clicked:', {productId, productName, price, isFood});
    
    // Obtener información adicional del producto
    const productCard = document.querySelector(`[data-product-id="${productId}"]`);
    const categoryId = productCard ? productCard.dataset.category : null;
    
    // If the product is food and has customization options, show modal
    if (isFood) {
        // Check if this category has customization options
        const hasOptions = productCard.dataset.hasOptions === 'true';
        
        if (hasOptions) {
            openCustomizationModal(productId, productName, price, categoryId);
            return;
        }
    }
    
    // Add directly to cart without customization
    addToCart(productId, productName, price, isFood, categoryId);
}

// Filter products by category
function filterByCategory(categoryId) {
    // Update active button - Reset all buttons first
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.classList.remove('active');
        // Reset all to default gray style for "Todos los Productos"
        if (btn.dataset.category === 'all') {
            btn.classList.add('bg-gray-100', 'text-gray-700');
            btn.classList.remove('bg-blue-600', 'text-white');
        }
    });
    
    // Set active button
    const activeBtn = document.querySelector(`[data-category="${categoryId}"]`);
    if (activeBtn) {
        activeBtn.classList.add('active');
        // If it's the "all" button, make sure it has the correct active styling
        if (categoryId === 'all') {
            activeBtn.classList.remove('bg-gray-100', 'text-gray-700');
        }
    }

    // Filter products
    const products = document.querySelectorAll('.product-card');
    let visibleCount = 0;

    products.forEach(product => {
        if (categoryId === 'all' || product.dataset.category === categoryId.toString()) {
            product.style.display = 'block';
            visibleCount++;
        } else {
            product.style.display = 'none';
        }
    });

    // Show/hide no products message
    const noProductsElement = document.getElementById('no-products');
    if (noProductsElement) {
        noProductsElement.style.display = visibleCount === 0 ? 'block' : 'none';
    }
    
    console.log(`Filtered by category: ${categoryId}, visible products: ${visibleCount}`);
}

// Search products
document.getElementById('search-product').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const products = document.querySelectorAll('.product-card');
    let visibleCount = 0;

    products.forEach(product => {
        const productName = product.dataset.productName;
        
        if (productName.includes(searchTerm)) {
            product.style.display = 'block';
            visibleCount++;
        } else {
            product.style.display = 'none';
        }
    });

    document.getElementById('no-products').style.display = visibleCount === 0 ? 'block' : 'none';
});

// Add product to cart
function addToCart(productId, productName, price, isFood = false, categoryId = null) {
    console.log('Adding to cart:', {productId, productName, price, categoryId});
    
    // Agregar directamente al carrito (sin personalización)
    const existingItem = cart.find(item => item.id === productId && 
                                          JSON.stringify(item.observations || []) === JSON.stringify([]) &&
                                          JSON.stringify(item.specialties || []) === JSON.stringify([]));
    
    if (existingItem) {
        existingItem.quantity++;
        console.log('Updated existing item quantity:', existingItem.quantity);
    } else {
        const newItem = {
            id: productId,
            name: productName,
            price: price,
            quantity: 1,
            categoryId: categoryId,
            observations: [],
            specialties: []
        };
        cart.push(newItem);
        console.log('Added new item to cart:', newItem);
    }
    
    updateCartDisplay();
    console.log('Cart updated, current cart:', cart);
}

// Abrir modal de personalización
function openCustomizationModal(productId, productName, price, categoryId = null) {
    const modal = document.getElementById('customizeModal');
    const modalProductName = document.getElementById('product-name');
    const modalProductPrice = document.getElementById('product-price');
    
    // Establecer información del producto
    modalProductName.textContent = productName;
    modalProductPrice.textContent = '$' + parseFloat(price).toFixed(2);
    
    // Limpiar selecciones previas
    const checkboxes = modal.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(checkbox => checkbox.checked = false);
    
    // Reset cantidad y notas
    document.getElementById('modal-quantity').value = 1;
    document.getElementById('modal-notes').value = '';
    
    // Cargar opciones del producto desde el servidor
    loadProductOptions(productId);
    
    // Guardar información del producto actual (corregido para usar variable global)
    currentProduct = {
        id: productId,
        name: productName,
        price: price,
        categoryId: categoryId
    };
    selectedOptions = [];
    
    console.log('Modal opened for product:', currentProduct);
    
    // Mostrar modal
    modal.classList.remove('hidden');
}

// Cargar opciones del producto
function loadProductOptions(productId) {
    // Aquí haremos una petición AJAX para cargar las opciones específicas del producto
    fetch(`/api/products/${productId}/options`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateOptionsInModal(data.data);
            } else {
                updateOptionsInModal({observations: [], specialties: []});
            }
        })
        .catch(error => {
            console.log('Error cargando opciones:', error);
            // Si no hay conexión o error, usar opciones por defecto
            updateOptionsInModal({observations: [], specialties: []});
        });
}

// Actualizar opciones en el modal
function updateOptionsInModal(options) {
    const observationsContainer = document.getElementById('observations-list');
    const specialtiesContainer = document.getElementById('specialties-list');
    const observationsSection = document.getElementById('observations-section');
    const specialtiesSection = document.getElementById('specialties-section');
    
    // Limpiar contenedores
    observationsContainer.innerHTML = '';
    specialtiesContainer.innerHTML = '';
    
    // Agregar observaciones (remover ingredientes)
    if (options.observations && options.observations.length > 0) {
        observationsSection.classList.remove('hidden');
        options.observations.forEach((observation, index) => {
            const checkboxHtml = `
                <label class="flex items-center space-x-2 p-2 hover:bg-red-100 rounded cursor-pointer">
                    <input type="checkbox" name="observations[]" value="${observation.id}" 
                           class="text-red-600 focus:ring-red-500">
                    <span class="text-sm">${observation.name}</span>
                </label>
            `;
            observationsContainer.innerHTML += checkboxHtml;
        });
    } else {
        observationsSection.classList.add('hidden');
    }
    
    // Agregar especialidades (agregar ingredientes)
    if (options.specialties && options.specialties.length > 0) {
        specialtiesSection.classList.remove('hidden');
        options.specialties.forEach((specialty, index) => {
            const checkboxHtml = `
                <label class="flex items-center space-x-2 p-2 hover:bg-green-100 rounded cursor-pointer">
                    <input type="checkbox" name="specialties[]" value="${specialty.id}" 
                           class="text-green-600 focus:ring-green-500">
                    <span class="text-sm">${specialty.name}</span>
                    ${specialty.price > 0 ? `<span class="text-xs text-green-600 ml-auto">+$${specialty.price}</span>` : ''}
                </label>
            `;
            specialtiesContainer.innerHTML += checkboxHtml;
        });
    } else {
        specialtiesSection.classList.add('hidden');
    }
}

// Cerrar modal de personalización
function closeCustomizationModal() {
    document.getElementById('customizeModal').classList.add('hidden');
}

// Alias para compatibilidad
function closeCustomizeModal() {
    closeCustomizationModal();
}

// Remove product from cart
function removeFromCart(productId) {
    console.log('🗑️ Removiendo producto del carrito:', productId);
    cart = cart.filter(item => item.id !== productId);
    
    // Limpiar promociones aplicadas al remover productos
    appliedPromotions = [];
    totalDiscount = 0;
    
    updateCartDisplay();
    console.log('✅ Producto removido y promociones recalculadas');
}

// Update quantity
function updateQuantity(productId, quantity) {
    console.log('🔢 Actualizando cantidad:', productId, 'nueva cantidad:', quantity);
    const item = cart.find(item => item.id === productId);
    if (item) {
        if (quantity <= 0) {
            removeFromCart(productId);
        } else {
            item.quantity = quantity;
            
            // Limpiar y recalcular promociones al cambiar cantidades
            appliedPromotions = [];
            totalDiscount = 0;
            
            updateCartDisplay();
            console.log('✅ Cantidad actualizada y promociones recalculadas');
        }
    }
}

// Clear cart
function clearCart() {
    if (confirm('¿Estás seguro de que deseas limpiar el carrito?')) {
        console.log('🧹 Limpiando carrito completo');
        cart = [];
        
        // Limpiar todas las promociones y descuentos
        appliedPromotions = [];
        totalDiscount = 0;
        
        updateCartDisplay();
        hideCombos(); // Ocultar sugerencias de combos
        console.log('✅ Carrito limpiado completamente');
    }
}

// Remove cart item by index
function removeCartItem(index) {
    console.log('🗑️ Removiendo item del carrito por índice:', index);
    const itemToRemove = cart[index];
    
    if (!itemToRemove) {
        console.warn('⚠️ No se encontró el item en el índice:', index);
        return;
    }
    
    // Si se elimina un descuento de combo, solo removerlo
    if (itemToRemove.isComboDiscount) {
        cart.splice(index, 1);
        updateCartDisplay();
        showSuccessMessage('💰 Descuento de combo removido');
        console.log('✅ Descuento de combo removido');
        return;
    }
    
    // Si se elimina un producto normal, remover y recalcular promociones
    cart.splice(index, 1);
    
    // Limpiar y recalcular promociones al remover items
    appliedPromotions = [];
    totalDiscount = 0;
    
    updateCartDisplay();
    console.log('✅ Item removido y promociones recalculadas');
}

// Update cart item quantity by index
function updateCartItemQuantity(index, quantity) {
    console.log('🔢 Actualizando cantidad de item por índice:', index, 'nueva cantidad:', quantity);
    
    // No permitir modificar descuentos de combo
    if (cart[index] && cart[index].isComboDiscount) {
        console.log('⚠️ No se puede modificar cantidad de descuento de combo');
        return;
    }
    
    if (quantity <= 0) {
        removeCartItem(index);
    } else if (cart[index]) {
        cart[index].quantity = quantity;
        
        // Limpiar y recalcular promociones al cambiar cantidades
        appliedPromotions = [];
        totalDiscount = 0;
        
        updateCartDisplay();
        console.log('✅ Cantidad de item actualizada y promociones recalculadas');
    }
}

// Update cart display
function updateCartDisplay() {
    try {
        console.log('=== UPDATING CART DISPLAY ===');
        console.log('Current cart:', cart);
        
        const cartItems = document.getElementById('cart-items');
        const emptyCart = document.getElementById('empty-cart');
        const clearBtn = document.getElementById('clear-cart-btn');
        
        if (!cartItems) {
            console.error('cart-items element not found!');
            return;
        }
        
        if (cart.length === 0) {
            cartItems.innerHTML = '<div id="empty-cart" class="text-center py-12"><div class="tochis-gradient-light rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4"><i class="fas fa-shopping-basket text-white text-2xl"></i></div><p class="text-gray-600 font-semibold">Tu orden está vacía</p><p class="text-sm text-gray-500 mt-1">Selecciona deliciosos platillos para agregar</p></div>';
            if (clearBtn) clearBtn.style.display = 'none';
        } else {
            if (clearBtn) clearBtn.style.display = 'block';
            let cartHTML = '';
            
            cart.forEach((item, index) => {
            // Crear ID único para cada item del carrito (incluye personalizaciones)
            const uniqueId = `${item.id}_${index}`;
            
            // Mostrar personalizaciones si existen
            let customizations = '';
            if (item.observations && item.observations.length > 0) {
                customizations += `<div class="text-xs text-red-600 mt-1">
                    <i class="fas fa-minus-circle mr-1"></i>Sin: ${item.observations.map(o => o.name).join(', ')}
                </div>`;
            }
            if (item.specialties && item.specialties.length > 0) {
                customizations += `<div class="text-xs text-green-600 mt-1">
                    <i class="fas fa-plus-circle mr-1"></i>Con: ${item.specialties.map(s => s.name).join(', ')}
                </div>`;
            }
            
            // Determinar si es un descuento de combo
            const isComboDiscount = item.isComboDiscount || false;
            const itemPrice = parseFloat(item.price);
            const priceText = isComboDiscount ? 
                (itemPrice < 0 ? `-$${Math.abs(itemPrice).toFixed(2)}` : `$${itemPrice.toFixed(2)}`) : 
                `$${itemPrice.toFixed(2)} c/u`;
            
            // Clase CSS especial para descuentos
            const itemClass = isComboDiscount ? 'bg-green-50 border-green-200' : 'border-gray-200';
            
            cartHTML += `
                <div class="flex items-center justify-between p-3 border ${itemClass} rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium ${isComboDiscount ? 'text-green-700' : 'text-gray-900'} text-sm">${item.originalName || item.name}</h4>
                        <p class="text-sm ${isComboDiscount ? 'text-green-600 font-medium' : 'text-gray-600'}">${priceText}</p>
                        ${customizations}
                    </div>
                    <div class="flex items-center space-x-2">
                        ${!isComboDiscount ? `
                            <button onclick="updateCartItemQuantity(${index}, ${item.quantity - 1})" 
                                    class="w-6 h-6 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="fas fa-minus text-xs"></i>
                            </button>
                            <span class="w-8 text-center font-medium">${item.quantity}</span>
                            <button onclick="updateCartItemQuantity(${index}, ${item.quantity + 1})" 
                                    class="w-6 h-6 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="fas fa-plus text-xs"></i>
                            </button>
                        ` : `
                            <span class="text-sm text-green-600 font-medium px-2">DESCUENTO</span>
                        `}
                        <button onclick="removeCartItem(${index})" 
                                class="w-6 h-6 ${isComboDiscount ? 'bg-green-100 hover:bg-green-200 text-green-600' : 'bg-red-100 hover:bg-red-200 text-red-600'} rounded-full flex items-center justify-center ml-2">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        
        cartItems.innerHTML = cartHTML;
    }
    
    calculateTotals();
    
    // Verificar combos después de actualizar el carrito
    checkForComboSuggestions();
    
    console.log('Cart display updated successfully');
    
    } catch (error) {
        console.error('ERROR in updateCartDisplay:', error);
        alert('Error actualizando carrito: ' + error.message);
    }
}

// Update cart totals (función requerida por algunas funciones de combo)
function updateCartTotals() {
    console.log('🧮 Actualizando totales del carrito...');
    calculateTotals();
    updateCartDisplay();
}

// Calculate totals
function calculateTotals() {
    try {
        subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        
        // Calcular promociones aplicables (de forma segura)
        try {
            calculateApplicablePromotions();
        } catch (error) {
            console.error('Error calculando promociones:', error);
            totalDiscount = 0;
        }
        
        tax = 0; // No tax for now
        total = subtotal + tax - totalDiscount;
        
        document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('tax').textContent = `$${tax.toFixed(2)}`;
        document.getElementById('total').textContent = `$${total.toFixed(2)}`;
        
        // Update process sale button
        const processSaleBtn = document.getElementById('process-sale-btn');
        const paymentSection = document.getElementById('payment-section');
        
        if (cart.length > 0) {
            processSaleBtn.disabled = false;
            paymentSection.style.display = 'block';
            console.log('✅ Botón de procesar venta habilitado - Carrito tiene', cart.length, 'elementos');
        } else {
            processSaleBtn.disabled = true;
            paymentSection.style.display = 'none';
            console.log('❌ Botón de procesar venta deshabilitado - Carrito vacío');
        }
    } catch (error) {
        console.error('Error en calculateTotals:', error);
        // Fallback en caso de error
        document.getElementById('subtotal').textContent = '$0.00';
        document.getElementById('tax').textContent = '$0.00';
        document.getElementById('total').textContent = '$0.00';
    }
}

// Payment amount change
document.getElementById('paid-amount').addEventListener('input', function(e) {
    const paidAmount = parseFloat(e.target.value) || 0;
    const change = paidAmount - total;
    const changeDisplay = document.getElementById('change-display');
    const changeAmount = document.getElementById('change-amount');
    
    if (paidAmount >= total && total > 0) {
        changeAmount.textContent = change.toFixed(2);
        changeDisplay.style.display = 'block';
    } else {
        changeDisplay.style.display = 'none';
    }
});

// Process sale
function processSale() {
    console.log('🚀 Función processSale() iniciada');
    console.log('📦 Carrito actual:', cart);
    console.log('💰 Total actual:', total);
    
    if (cart.length === 0) {
        alert('Tu orden está vacía. ¡Agrega algunos deliciosos platillos!');
        return;
    }
    
    const paymentMethod = document.getElementById('payment-method').value;
    let paidAmount = total; // Por defecto, para tarjeta y transferencia
    
    // Solo para efectivo necesitamos validar el monto pagado
    if (paymentMethod === 'cash') {
        paidAmount = parseFloat(document.getElementById('paid-amount').value) || 0;
        if (paidAmount < total) {
            alert('El monto pagado es insuficiente');
            return;
        }
    }
    
    const saleNotes = document.getElementById('sale-notes').value || '';
    
    const saleData = {
        products: cart.map(item => ({
            id: item.id,
            quantity: item.quantity,
            price: item.price, // Incluir precio para cálculo de descuentos
            observations: item.observations || [],
            specialties: item.specialties || []
        })),
        payment_method: paymentMethod,
        paid_amount: paidAmount,
        notes: saleNotes
    };
    
    console.log('Datos de venta a enviar:', saleData);
    
    // Show loading
    const processSaleBtn = document.getElementById('process-sale-btn');
    const originalText = processSaleBtn.innerHTML;
    
    // Verificar CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        alert('Error: Token CSRF no encontrado. Recarga la página.');
        processSaleBtn.innerHTML = originalText;
        processSaleBtn.disabled = false;
        return;
    }
    
    console.log('CSRF Token encontrado:', csrfToken.getAttribute('content').substring(0, 10) + '...');
    
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
    .then(response => {
        console.log('Respuesta del servidor - Status:', response.status);
        console.log('Respuesta del servidor - OK:', response.ok);
        console.log('Content-Type:', response.headers.get('Content-Type'));
        
        if (!response.ok) {
            // Intentar leer el error como JSON o como texto
            return response.text().then(text => {
                console.log('Error del servidor (HTML/texto):', text);
                try {
                    const jsonError = JSON.parse(text);
                    throw new Error(jsonError.message || `HTTP error! status: ${response.status}`);
                } catch {
                    throw new Error(`Error del servidor: ${response.status} - ${text.substring(0, 200)}...`);
                }
            });
        }
        
        // Verificar si la respuesta es JSON
        const contentType = response.headers.get('Content-Type');
        if (!contentType || !contentType.includes('application/json')) {
            return response.text().then(text => {
                console.log('Respuesta no es JSON. Content-Type:', contentType);
                console.log('Contenido recibido:', text.substring(0, 500) + '...');
                throw new Error('El servidor no devolvió JSON válido. Posible error interno.');
            });
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Datos recibidos:', data);
        if (data.success) {
            alert(`Venta realizada exitosamente!\nTotal: $${total.toFixed(2)}\nCambio: $${(data.change || 0).toFixed(2)}`);
            cart = [];
            updateCartDisplay();
            document.getElementById('paid-amount').value = '';
            document.getElementById('change-display').style.display = 'none';
            document.getElementById('sale-notes').value = '';
        } else {
            alert('Error: ' + (data.message || 'Error desconocido'));
        }
    })
    .catch(error => {
        console.error('Error completo:', error);
        console.error('Tipo de error:', typeof error);
        console.error('Stack trace:', error.stack);
        alert('Error al procesar la venta: ' + (error.message || 'Error de conexión'));
    })
    .finally(() => {
        processSaleBtn.innerHTML = originalText;
        processSaleBtn.disabled = false;
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateCartDisplay();
    
    // Setup payment method change handler
    const paymentMethodSelect = document.getElementById('payment-method');
    const paymentSection = document.getElementById('payment-section');
    const paidAmountInput = document.getElementById('paid-amount');
    
    paymentMethodSelect.addEventListener('change', function() {
        const paymentMethod = this.value;
        
        if (paymentMethod === 'cash') {
            paymentSection.style.display = 'block';
            paidAmountInput.placeholder = 'Monto recibido en efectivo';
            paidAmountInput.addEventListener('input', calculateChange);
        } else {
            paymentSection.style.display = 'none';
            document.getElementById('change-display').style.display = 'none';
            paidAmountInput.removeEventListener('input', calculateChange);
        }
    });
    
    // Trigger initial state
    paymentMethodSelect.dispatchEvent(new Event('change'));
    
    // Agregar event listener alternativo al botón de procesar venta
    const processSaleBtn = document.getElementById('process-sale-btn');
    if (processSaleBtn) {
        processSaleBtn.addEventListener('click', function(e) {
            console.log('🔘 Click detectado en botón de procesar venta');
            if (!this.disabled) {
                // No prevenir default aquí porque queremos que funcione el onclick también
                console.log('✅ Botón habilitado, ejecutando processSale()');
            } else {
                console.log('⚠️ Botón está deshabilitado');
                e.preventDefault();
            }
        });
        console.log('📌 Event listener agregado al botón de procesar venta');
    } else {
        console.error('❌ No se encontró el botón process-sale-btn');
    }
});

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

// Nuevas funciones para personalización
let currentProduct = null;
let selectedOptions = [];

function addDirectlyToCart(productId, productName, price) {
    try {
        console.log('Adding to cart:', productName);
        
        // Buscar item existente
        const existingIndex = cart.findIndex(item => 
            item.id === productId && 
            (!item.observations || item.observations.length === 0) &&
            (!item.specialties || item.specialties.length === 0)
        );
        
        if (existingIndex !== -1) {
            cart[existingIndex].quantity++;
            console.log('Updated quantity for existing item');
        } else {
            cart.push({
                id: productId,
                name: productName,
                price: parseFloat(price),
                quantity: 1,
                observations: [],
                specialties: []
            });
            console.log('Added new item to cart');
        }
        
        updateCartDisplay();
        console.log('Cart updated successfully');
        
    } catch (error) {
        console.error('ERROR in addDirectlyToCart:', error);
        alert('Error agregando al carrito: ' + error.message);
    }
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

function addCustomizedToCart() {
    console.log('addCustomizedToCart called');
    console.log('currentProduct:', currentProduct);
    
    if (!currentProduct) {
        console.log('No currentProduct, returning');
        return;
    }
    
    const quantity = parseInt(document.getElementById('modal-quantity').value);
    const notes = document.getElementById('modal-notes').value;
    
    console.log('quantity:', quantity, 'notes:', notes);
    console.log('selectedOptions:', selectedOptions);
    
    // Separar observaciones de especialidades
    const observations = selectedOptions.filter(option => option.type === 'observation');
    const specialties = selectedOptions.filter(option => option.type === 'specialty');
    
    // Calcular precio con especialidades (las observaciones son gratis)
    let finalPrice = parseFloat(currentProduct.price);
    specialties.forEach(specialty => {
        finalPrice += parseFloat(specialty.price || 0);
    });
    
    console.log('finalPrice:', finalPrice);
    console.log('observations:', observations);
    console.log('specialties:', specialties);
    
    // Agregar al carrito con estructura correcta
    const cartItem = {
        id: currentProduct.id,
        name: currentProduct.name,
        originalName: currentProduct.name,
        price: finalPrice,
        basePrice: parseFloat(currentProduct.price),
        quantity: quantity,
        categoryId: currentProduct.categoryId,
        observations: observations,
        specialties: specialties,
        notes: notes
    };
    
    console.log('cartItem:', cartItem);
    
    addItemToCart(cartItem);
    closeCustomizeModal();
}

function addItemToCart(item) {
    console.log('addItemToCart called with:', item);
    console.log('current cart before:', cart);
    
    // Buscar si ya existe un item idéntico (mismo producto, mismas observaciones y especialidades)
    const existingIndex = cart.findIndex(cartItem => 
        cartItem.id === item.id && 
        JSON.stringify(cartItem.observations || []) === JSON.stringify(item.observations || []) &&
        JSON.stringify(cartItem.specialties || []) === JSON.stringify(item.specialties || []) &&
        cartItem.notes === item.notes
    );
    
    console.log('existingIndex:', existingIndex);
    
    if (existingIndex > -1) {
        cart[existingIndex].quantity += item.quantity;
        console.log('Updated existing item');
    } else {
        cart.push(item);
        console.log('Added new item to cart');
    }
    
    console.log('current cart after:', cart);
    
    updateCartDisplay();
}

function loadProductOptions(productId) {
    const observationsSection = document.getElementById('observations-section');
    const specialtiesSection = document.getElementById('specialties-section');
    const observationsList = document.getElementById('observations-list');
    const specialtiesList = document.getElementById('specialties-list');
    
    // Limpiar listas
    observationsList.innerHTML = '';
    specialtiesList.innerHTML = '';
    
    // Cargar opciones específicas del producto desde el servidor
    fetch(`/api/customization-options?product_id=${productId}`)
        .then(response => response.json())
        .then(data => {
            // Cargar observaciones
            if (data.observations && data.observations.length > 0) {
                data.observations.forEach(observation => {
                    const div = document.createElement('div');
                    div.className = 'flex items-center';
                    div.innerHTML = `
                        <input type="checkbox" 
                               id="option-${observation.id}" 
                               class="mr-2" 
                               onchange="toggleOption(${observation.id}, '${observation.name}', ${observation.price}, 'observation')">
                        <label for="option-${observation.id}" class="flex-1">${observation.name}</label>
                    `;
                    observationsList.appendChild(div);
                });
                observationsSection.classList.remove('hidden');
            } else {
                observationsSection.classList.add('hidden');
            }
            
            // Cargar especialidades
            if (data.specialties && data.specialties.length > 0) {
                data.specialties.forEach(specialty => {
                    const div = document.createElement('div');
                    div.className = 'flex items-center';
                    const priceText = specialty.price > 0 ? `<span class="text-green-600">+$${parseFloat(specialty.price).toFixed(2)}</span>` : '';
                    div.innerHTML = `
                        <input type="checkbox" 
                               id="option-${specialty.id}" 
                               class="mr-2" 
                               onchange="toggleOption(${specialty.id}, '${specialty.name}', ${specialty.price}, 'specialty')">
                        <label for="option-${specialty.id}" class="flex-1">${specialty.name}</label>
                        ${priceText}
                    `;
                    specialtiesList.appendChild(div);
                });
                specialtiesSection.classList.remove('hidden');
            } else {
                specialtiesSection.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error loading customization options:', error);
            // En caso de error, ocultar secciones
            observationsSection.classList.add('hidden');
            specialtiesSection.classList.add('hidden');
        });
}

function toggleOption(id, name, price, type) {
    const index = selectedOptions.findIndex(opt => opt.id === id);
    if (index > -1) {
        selectedOptions.splice(index, 1);
    } else {
        selectedOptions.push({
            id: id, 
            name: name, 
            price: parseFloat(price || 0),
            type: type
        });
    }
    console.log('selectedOptions after toggle:', selectedOptions);
}

// ==================== PROMOCIONES ====================

// Cargar promociones disponibles al iniciar
async function loadAvailablePromotions() {
    try {
        const response = await fetch('{{ route("cashier.sale.promotions") }}');
        if (response.ok) {
            const promotions = await response.json();
            availablePromotions = promotions || [];
            console.log('Promociones cargadas:', promotions);
        } else {
            console.warn('No se pudieron cargar las promociones:', response.status);
            availablePromotions = [];
        }
    } catch (error) {
        console.error('Error cargando promociones:', error);
        availablePromotions = [];
    }
}

// Calcular promociones aplicables al carrito actual
function calculateApplicablePromotions() {
    try {
        appliedPromotions = [];
        totalDiscount = 0;

        if (cart.length === 0 || availablePromotions.length === 0) {
            updatePromotionsDisplay();
            return;
        }

        const currentSubtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        
        // Crear estructura de detalles de venta desde el carrito
        const saleDetails = cart.map(item => ({
            id: item.id,
            categoryId: item.categoryId,
            subtotal: item.price * item.quantity,
            quantity: item.quantity
        }));

        availablePromotions.forEach(promotion => {
            try {
                // Verificar monto mínimo si existe
                if (promotion.minimum_amount && currentSubtotal < promotion.minimum_amount) {
                    return;
                }

                let applicableAmount = 0;

                if (promotion.apply_to === 'all') {
                    applicableAmount = currentSubtotal;
                } else if (promotion.apply_to === 'category') {
                    // Aplicar solo a productos de categorías específicas
                    const promotionCategoryIds = promotion.category_ids || [];
                    console.log('Promoción para categorías:', promotion.name);
                    console.log('IDs de categorías de la promoción:', promotionCategoryIds);
                    console.log('Detalles del carrito:', saleDetails);
                    
                    saleDetails.forEach(detail => {
                        console.log(`Producto: ${detail.id}, Categoría: ${detail.categoryId}, ¿Incluido?:`, promotionCategoryIds.includes(parseInt(detail.categoryId)));
                        if (promotionCategoryIds.includes(parseInt(detail.categoryId))) {
                            applicableAmount += detail.subtotal;
                            console.log(`Agregando ${detail.subtotal} al monto aplicable. Total: ${applicableAmount}`);
                        }
                    });
                } else if (promotion.apply_to === 'product') {
                    // Aplicar solo a productos específicos
                    const promotionProductIds = promotion.product_ids || [];
                    
                    saleDetails.forEach(detail => {
                        if (promotionProductIds.includes(parseInt(detail.id))) {
                            applicableAmount += detail.subtotal;
                        }
                    });
                }

                if (applicableAmount > 0) {
                    let discount = 0;
                    
                    // Parsing más seguro del descuento
                    if (promotion.discount_text && promotion.discount_text.includes('%')) {
                        const match = promotion.discount_text.match(/(\d+\.?\d*)%/);
                        if (match) {
                            const percentage = parseFloat(match[1]);
                            discount = (applicableAmount * percentage) / 100;
                        }
                    } else if (promotion.discount_text && promotion.discount_text.includes('$')) {
                        const match = promotion.discount_text.match(/\$(\d+\.?\d*)/);
                        if (match) {
                            const fixedAmount = parseFloat(match[1]);
                            discount = Math.min(fixedAmount, applicableAmount);
                        }
                    }

                    if (discount > 0) {
                        appliedPromotions.push({
                            ...promotion,
                            calculatedDiscount: discount
                        });
                        totalDiscount += discount;
                    }
                }
            } catch (error) {
                console.error('Error procesando promoción:', promotion, error);
            }
        });

        updatePromotionsDisplay();
    } catch (error) {
        console.error('Error en calculateApplicablePromotions:', error);
        // En caso de error, continuamos sin promociones
        appliedPromotions = [];
        totalDiscount = 0;
        updatePromotionsDisplay();
    }
}

// Actualizar la visualización de promociones
function updatePromotionsDisplay() {
    try {
        const availableDiv = document.getElementById('available-promotions');
        const appliedDiv = document.getElementById('applied-discounts');
        const promotionsListDiv = document.getElementById('promotions-list');
        const discountDetailsDiv = document.getElementById('discount-details');
        const discountAmountSpan = document.getElementById('discount-amount');

        // Verificar que los elementos existan
        if (!availableDiv || !appliedDiv || !promotionsListDiv || !discountDetailsDiv || !discountAmountSpan) {
            console.log('Elementos de promociones no encontrados en el DOM');
            return;
        }

        // Mostrar promociones disponibles que no se han aplicado
        const unappliedPromotions = availablePromotions.filter(promo => 
            !appliedPromotions.some(applied => applied.id === promo.id)
        );

        if (unappliedPromotions.length > 0) {
            availableDiv.classList.remove('hidden');
            promotionsListDiv.innerHTML = unappliedPromotions.map(promo => `
                <div class="flex justify-between items-center">
                    <span>${promo.name || 'Promoción'}</span>
                    <span class="font-medium">${promo.discount_text || ''}</span>
                </div>
                ${promo.minimum_text ? `<div class="text-xs opacity-75">${promo.minimum_text}</div>` : ''}
            `).join('');
        } else {
            availableDiv.classList.add('hidden');
        }

        // Mostrar descuentos aplicados
        if (appliedPromotions.length > 0) {
            appliedDiv.classList.remove('hidden');
            discountAmountSpan.textContent = `-$${totalDiscount.toFixed(2)}`;
            discountDetailsDiv.innerHTML = appliedPromotions.map(promo => `
                <div class="flex justify-between">
                    <span>${promo.name || 'Descuento'}</span>
                    <span>-$${(promo.calculatedDiscount || 0).toFixed(2)}</span>
                </div>
            `).join('');
        } else {
            appliedDiv.classList.add('hidden');
        }
    } catch (error) {
        console.error('Error en updatePromotionsDisplay:', error);
    }
}

// Inicializar promociones al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    loadAvailablePromotions();
});

// ============================================
// FUNCIONES PARA DETECCIÓN DE COMBOS
// ============================================

/**
 * Verificar si hay combos sugeridos basados en el carrito actual
 */
function checkForComboSuggestions() {
    console.log('🔍 INICIANDO VERIFICACIÓN DE COMBOS');
    console.log('📦 Cart length:', cart.length);
    console.log('📦 Cart contents:', cart);
    
    // Limpiar timeout anterior para evitar múltiples llamadas
    if (comboCheckTimeout) {
        clearTimeout(comboCheckTimeout);
    }
    
    // Solo verificar si hay al menos 2 productos en el carrito
    if (cart.length < 2) {
        console.log('❌ No hay suficientes productos (mínimo 2). Ocultando combos.');
        hideCombos();
        return;
    }
    
    console.log('✅ Carrito tiene suficientes productos. Configurando timeout...');
    
    // Debounce para evitar muchas llamadas a la API
    comboCheckTimeout = setTimeout(() => {
        console.log('� EJECUTANDO VERIFICACIÓN DE COMBOS');
        
        const cartData = cart.map(item => ({
            id: parseInt(item.id),
            quantity: item.quantity,
            name: item.name,
            price: item.price
        }));
        
        console.log('📤 Enviando datos al servidor:', cartData);
        
        // Verificar token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        console.log('🔐 CSRF Token:', csrfToken ? 'Presente' : 'AUSENTE');
        console.log('🔐 Token completo:', csrfToken);
        
        fetch('{{ route("cashier.sale.combos.suggest") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                cart_products: cartData
            })
        })
        .then(response => {
            console.log('📨 Respuesta recibida. Status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('📊 RESPUESTA COMPLETA DEL SERVIDOR:', data);
            
            if (data.has_suggestions && data.suggestions.length > 0) {
                console.log('✅ Se encontraron sugerencias:', data.suggestions.length);
                showComboSuggestions(data.suggestions);
            } else {
                console.log('❌ No hay sugerencias disponibles');
                hideCombos();
            }
        })
        .catch(error => {
            console.error('❌ ERROR EN LA VERIFICACIÓN DE COMBOS:', error);
            hideCombos();
        });
    }, 1000); // Esperar 1 segundo después del último cambio
}

/**
 * Mostrar sugerencias de combos al cajero
 */
function showComboSuggestions(suggestions) {
    console.log('🎯 MOSTRANDO SUGERENCIAS DE COMBOS');
    console.log('📋 Sugerencias recibidas:', suggestions);
    
    const comboSuggestionsDiv = document.getElementById('combo-suggestions');
    const comboListDiv = document.getElementById('combo-suggestions-list');
    
    if (!comboSuggestionsDiv || !comboListDiv) {
        console.error('❌ Elementos de combos no encontrados en el DOM');
        console.log('🔍 combo-suggestions div:', comboSuggestionsDiv);
        console.log('🔍 combo-suggestions-list div:', comboListDiv);
        return;
    }
    
    console.log('✅ Elementos DOM encontrados. Limpiando contenido anterior...');
    
    // Limpiar sugerencias anteriores
    comboListDiv.innerHTML = '';
    
    console.log('🔄 Procesando', suggestions.length, 'sugerencias...');
    
    suggestions.forEach((suggestion, index) => {
        const combo = suggestion.combo;
        const matchLevel = suggestion.match_level;
        const missingProducts = suggestion.missing_products;
        
        const comboHtml = `
            <div class="bg-white border-2 border-gray-200 rounded-xl p-5 shadow-md hover:shadow-lg transition-all duration-300">
                <!-- Header del Combo -->
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-gray-500 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-box-open text-white text-lg"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 text-lg">${combo.name}</h4>
                        <p class="text-sm text-gray-600">${combo.description}</p>
                    </div>
                </div>
                
                <!-- Precios y Ahorro -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl font-bold text-green-700">$${parseFloat(combo.price).toFixed(2)}</span>
                        <span class="text-lg text-gray-500 line-through">$${parseFloat(combo.original_price).toFixed(2)}</span>
                    </div>
                    <div class="bg-green-100 text-green-800 px-4 py-2 rounded-full font-bold text-sm">
                        <i class="fas fa-piggy-bank mr-1"></i>
                        Ahorra $${parseFloat(combo.savings).toFixed(2)}
                    </div>
                </div>
                
                <!-- Información de Coincidencia -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="bg-blue-50 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-blue-600">${parseFloat(matchLevel.percentage).toFixed(0)}%</div>
                        <div class="text-xs text-blue-700 font-medium">Coincidencia</div>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-purple-600">${matchLevel.matched_products}/${matchLevel.total_products}</div>
                        <div class="text-xs text-purple-700 font-medium">Productos</div>
                    </div>
                </div>
                
                ${missingProducts.length > 0 ? `
                    <!-- Productos por Agregar -->
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-plus-circle text-orange-600 mr-2 text-lg"></i>
                            <span class="font-bold text-orange-700">Productos por agregar:</span>
                        </div>
                        <div class="grid grid-cols-1 gap-2">
                            ${missingProducts.map(p => `
                                <div class="bg-white border border-orange-200 rounded-lg px-3 py-2 text-sm text-orange-700 font-medium">
                                    <i class="fas fa-utensils mr-2"></i>${p.name}
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : ''}
                
                <!-- Botones de Acción -->
                <div class="flex flex-col gap-2">
                    ${missingProducts.length === 0 ? `
                        <button onclick="applyCombo(${combo.id})" 
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center">
                            <i class="fas fa-check mr-2"></i>
                            Aplicar Combo Completo
                        </button>
                    ` : `
                        <button onclick="addMissingProducts(${JSON.stringify(missingProducts).replace(/"/g, '&quot;')}, ${combo.id})" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center">
                            <i class="fas fa-plus mr-2"></i>
                            Agregar Productos Faltantes
                        </button>
                    `}
                    
                    <button onclick="dismissCombo(${index})" 
                            class="w-full bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i>
                        Descartar Esta Sugerencia
                    </button>
                </div>
            </div>
        `;
        
        comboListDiv.innerHTML += comboHtml;
    });
    
    console.log('✅ HTML de combos generado. Mostrando panel...');
    
    // Mostrar el panel de sugerencias
    comboSuggestionsDiv.classList.remove('hidden');
    
    console.log('🎉 PANEL DE COMBOS VISIBLE');
    
    // Notificación sonora suave (opcional)
    playComboNotification();
}

/**
 * Ocultar sugerencias de combos
 */
function hideCombos() {
    console.log('🫥 OCULTANDO SUGERENCIAS DE COMBOS');
    const comboSuggestionsDiv = document.getElementById('combo-suggestions');
    if (comboSuggestionsDiv) {
        comboSuggestionsDiv.classList.add('hidden');
        console.log('✅ Panel de combos ocultado');
    } else {
        console.error('❌ No se encontró el elemento combo-suggestions para ocultar');
    }
}

/**
 * Aplicar combo al carrito
 */
function applyCombo(comboId) {
    console.log('🎯 Aplicando combo:', comboId);
    
    const cartData = cart.map(item => ({
        id: parseInt(item.id),
        quantity: item.quantity
    }));
    
    fetch('{{ route("cashier.sale.combos.apply") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            combo_id: comboId,
            cart_products: cartData
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('✅ Combo aplicado:', data);
            
            // Aplicar descuento del combo
            const comboData = data.combo;
            const savings = parseFloat(comboData.savings) || 0;
            
            if (savings > 0) {
                // Agregar descuento como item especial en el carrito
                const discountItem = {
                    id: 'combo-discount-' + comboId,
                    name: `🎉 Descuento ${comboData.name}`,
                    price: -savings, // Precio negativo para descuento
                    quantity: 1,
                    isComboDiscount: true,
                    comboId: comboId,
                    originalName: `Descuento ${comboData.name}`,
                    specialties: [],
                    observations: []
                };
                
                // Verificar si ya existe un descuento para este combo
                const existingDiscountIndex = cart.findIndex(item => 
                    item.isComboDiscount && item.comboId === comboId
                );
                
                if (existingDiscountIndex !== -1) {
                    // Reemplazar descuento existente
                    cart[existingDiscountIndex] = discountItem;
                    console.log('🔄 Descuento de combo actualizado');
                } else {
                    // Agregar nuevo descuento
                    cart.push(discountItem);
                    console.log('✅ Descuento de combo agregado');
                }
                
                // Actualizar visualización
                updateCartDisplay();
                updateCartTotals();
                
                // Mostrar mensaje de éxito con ahorro
                showSuccessMessage(`🎉 ${data.message}`);
            } else {
                showSuccessMessage('✅ Combo aplicado exitosamente');
            }
            
            // Ocultar sugerencias
            hideCombos();
            
        } else {
            console.error('❌ Error en respuesta:', data);
            console.log('Response data:', data);
        }
    })
    .catch(error => {
        console.error('❌ Error aplicando combo:', error);
        console.log('Catch error details:', error.message);
    });
}

/**
 * Mostrar productos faltantes para completar combo
 */
function showMissingProducts(missingProducts) {
    const productNames = missingProducts.map(p => p.name).join('\n- ');
    
    if (confirm(`Para completar este combo, agrega:\n\n- ${productNames}\n\n¿Quieres que te ayude a encontrarlos?`)) {
        // Aquí podrías highlighting los productos en la lista
        highlightMissingProducts(missingProducts);
    }
}

/**
 * Resaltar productos faltantes en la interfaz
 */
function highlightMissingProducts(missingProducts) {
    // Limpiar highlights anteriores
    document.querySelectorAll('.product-card').forEach(card => {
        card.classList.remove('ring-2', 'ring-orange-400', 'bg-orange-50');
    });
    
    // Resaltar productos faltantes
    missingProducts.forEach(product => {
        const productCard = document.querySelector(`[data-product-id="${product.id}"]`);
        if (productCard) {
            productCard.classList.add('ring-2', 'ring-orange-400', 'bg-orange-50');
            
            // Scroll hacia el primer producto
            if (missingProducts.indexOf(product) === 0) {
                productCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
    
    // Remover highlights después de 10 segundos
    setTimeout(() => {
        document.querySelectorAll('.product-card').forEach(card => {
            card.classList.remove('ring-2', 'ring-orange-400', 'bg-orange-50');
        });
    }, 10000);
}

/**
 * Descartar sugerencia de combo
 */
function dismissCombo(index) {
    const comboElements = document.querySelectorAll('#combo-suggestions-list > div');
    if (comboElements[index]) {
        comboElements[index].remove();
    }
    
    // Si no quedan sugerencias, ocultar panel
    const remainingSuggestions = document.querySelectorAll('#combo-suggestions-list > div');
    if (remainingSuggestions.length === 0) {
        hideCombos();
    }
}

/**
 * Agregar productos faltantes al carrito
 */
async function addMissingProducts(missingProducts, comboId) {
    console.log('🛒 AGREGANDO PRODUCTOS FALTANTES AL CARRITO');
    console.log('📦 Productos faltantes (raw):', missingProducts);
    console.log('🎯 Combo ID:', comboId);
    
    // Si missingProducts es una cadena, parsearla
    if (typeof missingProducts === 'string') {
        try {
            // Reemplazar las entidades HTML de vuelta a comillas
            missingProducts = missingProducts.replace(/&quot;/g, '"');
            missingProducts = JSON.parse(missingProducts);
            console.log('✅ Productos parseados:', missingProducts);
        } catch (error) {
            console.error('❌ Error parseando productos:', error);
            showSuccessMessage('❌ Error procesando productos faltantes');
            return;
        }
    }
    
    // Verificar que tenemos un array válido
    if (!Array.isArray(missingProducts) || missingProducts.length === 0) {
        console.error('❌ No hay productos válidos para agregar');
        showSuccessMessage('❌ No hay productos para agregar');
        return;
    }
    
    console.log('📦 Productos procesados:', missingProducts);
    
    try {
        // Agregar cada producto faltante al carrito
        for (const product of missingProducts) {
            console.log(`➕ Agregando ${product.name} al carrito`);
            
            // Crear un item de carrito simple con la información disponible
            const cartItem = {
                id: product.id,
                name: product.name,
                price: parseFloat(product.price),
                quantity: 1,
                specialties: [],
                observations: [],
                originalName: product.name
            };
            
            // Verificar si el producto ya existe en el carrito
            const existingIndex = cart.findIndex(item => 
                item.id === product.id && 
                JSON.stringify(item.specialties || []) === JSON.stringify([]) &&
                JSON.stringify(item.observations || []) === JSON.stringify([])
            );
            
            if (existingIndex !== -1) {
                // Si existe, aumentar cantidad
                cart[existingIndex].quantity += 1;
                console.log(`🔄 Aumentada cantidad de ${product.name} a ${cart[existingIndex].quantity}`);
            } else {
                // Si no existe, agregar nuevo item
                cart.push(cartItem);
                console.log(`✅ ${product.name} agregado al carrito`);
            }
        }
        
        // Actualizar display del carrito
        updateCartDisplay();
        updateCartTotals();
        
        // Mostrar mensaje de éxito
        setTimeout(() => {
            showSuccessMessage(`✅ Se agregaron ${missingProducts.length} productos faltantes al carrito`);
            console.log('🔄 Verificando combos nuevamente...');
            checkForComboSuggestions();
        }, 500);
        
    } catch (error) {
        console.error('❌ Error al agregar productos faltantes:', error);
        // Solo mostrar error si realmente hay un problema, no el mensaje genérico
        console.error('Error details:', error.message);
    }
}



/**
 * Mostrar mensaje de éxito
 */
function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg z-50';
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(alertDiv);
    
    // Remover después de 5 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.parentNode.removeChild(alertDiv);
        }
    }, 5000);
}

/**
 * Reproducir notificación sonora suave
 */
function playComboNotification() {
    // Audio context para sonido suave
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

// ============================================
// FUNCIONES DE PRUEBA Y DEBUG - REMOVER EN PRODUCCIÓN
// ============================================

/**
 * Función de prueba para forzar detección de combos
 */
function testComboDetection() {
    console.log('🧪 INICIANDO PRUEBA DE DETECCIÓN DE COMBOS');
    
    if (cart.length === 0) {
        alert('❌ Agrega algunos productos al carrito primero para probar la detección de combos');
        return;
    }
    
    console.log('📦 Forzando verificación con carrito actual:', cart);
    checkForComboSuggestions();
}

/**
 * Función de prueba para mostrar panel de combos con datos ficticios
 */
function showTestCombos() {
    console.log('🧪 MOSTRANDO COMBOS DE PRUEBA');
    
    const testSuggestions = [
        {
            combo: {
                id: 1,
                name: "Combo de Prueba",
                description: "Este es un combo de prueba para verificar la interfaz",
                price: 150.00,
                original_price: 200.00,
                savings: 50.00
            },
            match_level: {
                percentage: 85,
                matched_products: 2,
                total_products: 3
            },
            missing_products: [
                { id: 999, name: "Producto Faltante de Prueba" }
            ]
        }
    ];
    
    showComboSuggestions(testSuggestions);
}
</script>

<!-- Modal para personalizar productos -->
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
                            <i class="fas fa-cog mr-2 text-blue-600"></i>Personalizar Producto
                        </h3>
                        
                        <div class="space-y-4">
                            <!-- Información del producto -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 id="product-name" class="font-medium text-gray-900"></h4>
                                <p id="product-price" class="text-lg font-bold text-green-600"></p>
                            </div>

                            <!-- Observaciones -->
                            <div id="observations-section" class="hidden">
                                <h5 class="font-medium text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-times-circle mr-2 text-red-600"></i>Quitar ingredientes
                                </h5>
                                <div id="observations-list" class="space-y-2 bg-red-50 p-3 rounded-lg">
                                    <!-- Se llenarán dinámicamente -->
                                </div>
                            </div>

                            <!-- Especialidades -->
                            <div id="specialties-section" class="hidden">
                                <h5 class="font-medium text-gray-900 mb-2 flex items-center">
                                    <i class="fas fa-plus-circle mr-2 text-green-600"></i>Agregar extras
                                </h5>
                                <div id="specialties-list" class="space-y-2 bg-green-50 p-3 rounded-lg">
                                    <!-- Se llenarán dinámicamente -->
                                </div>
                            </div>

                            <!-- Cantidad -->
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

                            <!-- Notas adicionales -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-sticky-note mr-1"></i>Instrucciones especiales
                                </label>
                                <textarea id="modal-notes" rows="3" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                          placeholder="Ej: Sin sal, término medio, extra caliente..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="addCustomizedToCart()" 
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                    <i class="fas fa-cart-plus mr-2"></i>Agregar al carrito
                </button>
                <button type="button" onclick="closeCustomizeModal()" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

@endpush

<style>
/* Estilos personalizados para los cards de productos */
.product-card {
    min-height: 200px;
    max-width: 280px;
    margin: 0 auto;
}

.product-card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Mejoras para el grid responsivo */
@media (max-width: 640px) {
    #products-grid {
        grid-template-columns: repeat(1, 1fr);
        gap: 1rem;
    }
}

@media (min-width: 641px) and (max-width: 768px) {
    #products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }
}

@media (min-width: 769px) and (max-width: 1024px) {
    #products-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
}

@media (min-width: 1025px) and (max-width: 1280px) {
    #products-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }
}

@media (min-width: 1281px) {
    #products-grid {
        grid-template-columns: repeat(5, 1fr);
        gap: 1.5rem;
    }
}

/* Animaciones suaves */
.product-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.product-card:active {
    transform: translateY(0) scale(0.98);
}

/* Mejora visual para los badges */
.product-card .bg-orange-500 {
    background: linear-gradient(135deg, #f97316, #ea580c);
    backdrop-filter: blur(10px);
}

/* Efectos de hover mejorados */
.product-card:hover .text-green-600 {
    color: #16a34a;
    font-weight: 700;
}
</style>
