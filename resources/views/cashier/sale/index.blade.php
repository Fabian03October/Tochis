@extends('layouts.app')

@section('title', 'Nueva Venta - Sistema POS')
@section('page-title', 'Punto de Venta')

@section('content')
<div class="fade-in" id="pos-app">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-screen">
        <!-- Products Section -->
        <div class="lg:col-span-2">
            <!-- Categories -->
            <div class="bg-white rounded-lg shadow mb-4">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-tags mr-2 text-blue-600"></i>
                        Categorías
                    </h3>
                </div>
                <div class="p-4">
                    <div class="flex flex-wrap gap-2">
                        <button onclick="filterByCategory('all')" 
                                class="category-btn active px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition duration-200"
                                data-category="all">
                            <i class="fas fa-th-large mr-2"></i>
                            Todos
                        </button>
                        @foreach($categories as $category)
                            @if($category->activeProducts->count() > 0)
                                <button onclick="filterByCategory({{ $category->id }})" 
                                        class="category-btn px-4 py-2 text-white rounded-lg hover:opacity-80 transition duration-200"
                                        style="background-color: {{ $category->color }}"
                                        data-category="{{ $category->id }}">
                                    {{ $category->name }}
                                    <span class="ml-2 bg-white bg-opacity-20 px-2 py-1 rounded-full text-xs">
                                        {{ $category->activeProducts->count() }}
                                    </span>
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-box mr-2 text-green-600"></i>
                            Productos
                        </h3>
                        <div class="relative">
                            <input type="text" 
                                   id="search-product" 
                                   placeholder="Buscar producto o código..."
                                   class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="p-4 h-96 overflow-y-auto">
                    <div id="products-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($categories as $category)
                            @foreach($category->activeProducts as $product)
                                <div class="product-card border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200 cursor-pointer"
                                     data-category="{{ $category->id }}"
                                     data-product-id="{{ $product->id }}"
                                     data-product-name="{{ strtolower($product->name) }}"
                                     data-product-code="{{ $product->barcode }}"
                                     data-has-options="{{ $product->category->is_customizable ? 'true' : 'false' }}"
                                     onclick="handleProductClick({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }}, {{ $product->is_food ? 'true' : 'false' }})">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" 
                                             alt="{{ $product->name }}"
                                             class="w-full h-20 object-cover rounded-lg mb-2">
                                    @else
                                        <div class="w-full h-20 bg-gray-100 rounded-lg mb-2 flex items-center justify-center">
                                            @if($product->is_food)
                                                <i class="fas fa-utensils text-gray-400 text-2xl"></i>
                                            @else
                                                <i class="fas fa-box text-gray-400 text-2xl"></i>
                                            @endif
                                        </div>
                                    @endif
                                    
                                    <div class="relative">
                                        @if($product->is_food)
                                            <span class="absolute -top-1 -right-1 bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">
                                                <i class="fas fa-utensils mr-1"></i>Comida
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <h4 class="font-medium text-gray-900 text-sm mb-1 line-clamp-2">{{ $product->name }}</h4>
                                    <p class="text-lg font-bold text-green-600">${{ number_format($product->price, 2) }}</p>
                                    <p class="text-xs text-gray-500">Stock: {{ $product->stock }}</p>
                                    
                                    @if($product->options->count() > 0)
                                        <span class="inline-block mt-1 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">
                                            <i class="fas fa-cog mr-1"></i>Personalizable
                                        </span>
                                    @endif
                                    
                                    @if($product->stock <= $product->min_stock)
                                        <span class="inline-block mt-1 px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">
                                            Stock Bajo
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
            <div class="bg-white rounded-lg shadow h-full flex flex-col">
                <!-- Cart Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-shopping-cart mr-2 text-purple-600"></i>
                            Carrito
                        </h3>
                        <button onclick="clearCart()" 
                                class="text-red-600 hover:text-red-800 text-sm"
                                id="clear-cart-btn" style="display: none;">
                            <i class="fas fa-trash mr-1"></i>
                            Limpiar
                        </button>
                    </div>
                </div>

                <!-- Cart Items -->
                <div class="flex-1 overflow-y-auto p-4">
                    <div id="cart-items" class="space-y-3">
                        <div id="empty-cart" class="text-center py-8">
                            <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">El carrito está vacío</p>
                            <p class="text-sm text-gray-400">Selecciona productos para agregar</p>
                        </div>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="border-t border-gray-200 p-4 space-y-4">
                    <!-- Totals -->
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span>Subtotal:</span>
                            <span id="subtotal">$0.00</span>
                        </div>
                        
                        <!-- Promociones disponibles -->
                        <div id="available-promotions" class="hidden">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-2">
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-tag text-yellow-600 mr-2"></i>
                                    <span class="text-sm font-medium text-yellow-800">Promociones Disponibles</span>
                                </div>
                                <div id="promotions-list" class="space-y-1 text-xs text-yellow-700">
                                    <!-- Las promociones se cargarán aquí -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- Descuentos aplicados -->
                        <div id="applied-discounts" class="hidden">
                            <div class="flex justify-between text-sm text-green-600">
                                <span><i class="fas fa-percent mr-1"></i>Descuentos:</span>
                                <span id="discount-amount">-$0.00</span>
                            </div>
                            <div id="discount-details" class="text-xs text-green-500 space-y-1 ml-4">
                                <!-- Los detalles de descuentos se mostrarán aquí -->
                            </div>
                        </div>
                        
                        <div class="flex justify-between text-sm">
                            <span>Impuesto:</span>
                            <span id="tax">$0.00</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold border-t pt-2">
                            <span>Total:</span>
                            <span id="total">$0.00</span>
                        </div>
                    </div>

                    <!-- Observaciones generales -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-comment mr-1"></i>Observaciones de la orden
                        </label>
                        <textarea id="sale-notes" 
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Observaciones generales para toda la orden..."></textarea>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago</label>
                        <select id="payment-method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="cash">Efectivo</option>
                            <option value="card">Tarjeta</option>
                            <option value="transfer">Transferencia</option>
                        </select>
                    </div>

                    <!-- Payment Amount -->
                    <div id="payment-section" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Monto Pagado</label>
                        <input type="number" 
                               id="paid-amount" 
                               step="0.01" 
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0.00">
                        <div id="change-display" class="mt-2 text-sm text-green-600" style="display: none;">
                            <strong>Cambio: $<span id="change-amount">0.00</span></strong>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-2">
                        <button onclick="processSale()" 
                                id="process-sale-btn"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed"
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

// Add product to cart - Function called when clicking a product
function handleProductClick(productId, productName, price, stock, isFood = false) {
    console.log('Product clicked:', {productId, productName, price, stock, isFood});
    
    // Obtener información adicional del producto
    const productCard = document.querySelector(`[data-product-id="${productId}"]`);
    const categoryId = productCard ? productCard.dataset.category : null;
    
    // If the product is food and has customization options, show modal
    if (isFood) {
        // Check if this category has customization options
        const hasOptions = productCard.dataset.hasOptions === 'true';
        
        if (hasOptions) {
            openCustomizationModal(productId, productName, price, stock, categoryId);
            return;
        }
    }
    
    // Add directly to cart without customization
    addToCart(productId, productName, price, stock, isFood, categoryId);
}

// Filter products by category
function filterByCategory(categoryId) {
    // Update active button
    document.querySelectorAll('.category-btn').forEach(btn => {
        btn.classList.remove('active', 'bg-blue-600', 'text-white');
        btn.classList.add('bg-gray-100', 'text-gray-700');
    });
    
    const activeBtn = document.querySelector(`[data-category="${categoryId}"]`);
    activeBtn.classList.remove('bg-gray-100', 'text-gray-700');
    activeBtn.classList.add('active', 'bg-blue-600', 'text-white');

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
    document.getElementById('no-products').style.display = visibleCount === 0 ? 'block' : 'none';
}

// Search products
document.getElementById('search-product').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const products = document.querySelectorAll('.product-card');
    let visibleCount = 0;

    products.forEach(product => {
        const productName = product.dataset.productName;
        const productBarcode = product.dataset.productBarcode;
        
        if (productName.includes(searchTerm) || (productBarcode && productBarcode.includes(searchTerm))) {
            product.style.display = 'block';
            visibleCount++;
        } else {
            product.style.display = 'none';
        }
    });

    document.getElementById('no-products').style.display = visibleCount === 0 ? 'block' : 'none';
});

// Add product to cart
function addToCart(productId, productName, price, stock, isFood = false, categoryId = null) {
    console.log('Adding to cart:', {productId, productName, price, stock, categoryId});
    
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
            stock: stock,
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
function openCustomizationModal(productId, productName, price, stock, categoryId = null) {
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
        stock: stock,
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
    cart = cart.filter(item => item.id !== productId);
    updateCartDisplay();
}

// Update quantity
function updateQuantity(productId, quantity) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        if (quantity <= 0) {
            removeFromCart(productId);
        } else if (quantity <= item.stock) {
            item.quantity = quantity;
            updateCartDisplay();
        } else {
            alert('No hay suficiente stock disponible');
        }
    }
}

// Clear cart
function clearCart() {
    if (confirm('¿Estás seguro de que deseas limpiar el carrito?')) {
        cart = [];
        updateCartDisplay();
    }
}

// Remove cart item by index
function removeCartItem(index) {
    cart.splice(index, 1);
    updateCartDisplay();
}

// Update cart item quantity by index
function updateCartItemQuantity(index, quantity) {
    if (quantity <= 0) {
        removeCartItem(index);
    } else {
        cart[index].quantity = quantity;
        updateCartDisplay();
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
            cartItems.innerHTML = '<div id="empty-cart" class="text-center py-8"><i class="fas fa-shopping-cart text-4xl text-gray-300 mb-4"></i><p class="text-gray-500">El carrito está vacío</p><p class="text-sm text-gray-400">Selecciona productos para agregar</p></div>';
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
            
            cartHTML += `
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900 text-sm">${item.originalName || item.name}</h4>
                        <p class="text-sm text-gray-600">$${item.price.toFixed(2)} c/u</p>
                        ${customizations}
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="updateCartItemQuantity(${index}, ${item.quantity - 1})" 
                                class="w-6 h-6 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-minus text-xs"></i>
                        </button>
                        <span class="w-8 text-center font-medium">${item.quantity}</span>
                        <button onclick="updateCartItemQuantity(${index}, ${item.quantity + 1})" 
                                class="w-6 h-6 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-plus text-xs"></i>
                        </button>
                        <button onclick="removeCartItem(${index})" 
                                class="w-6 h-6 bg-red-100 hover:bg-red-200 text-red-600 rounded-full flex items-center justify-center ml-2">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        
        cartItems.innerHTML = cartHTML;
    }
    
    calculateTotals();
    console.log('Cart display updated successfully');
    
    } catch (error) {
        console.error('ERROR in updateCartDisplay:', error);
        alert('Error actualizando carrito: ' + error.message);
    }
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
        } else {
            processSaleBtn.disabled = true;
            paymentSection.style.display = 'none';
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
    if (cart.length === 0) {
        alert('El carrito está vacío');
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
            observations: item.observations || [],
            specialties: item.specialties || []
        })),
        payment_method: paymentMethod,
        paid_amount: paidAmount,
        notes: saleNotes
    };
    
    // Show loading
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
            updateCartDisplay();
            document.getElementById('paid-amount').value = '';
            document.getElementById('change-display').style.display = 'none';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la venta');
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

function addDirectlyToCart(productId, productName, price, stock) {
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
                stock: stock,
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
        stock: currentProduct.stock,
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
