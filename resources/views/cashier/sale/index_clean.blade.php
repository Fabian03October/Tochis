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
    
    .product-card {
        cursor: pointer;
        transition: transform 0.2s;
    }
    
    .product-card:hover {
        transform: scale(1.02);
    }
    
    .category-btn {
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Panel Izquierdo - Productos -->
        <div class="col-md-8">
            <div class="tochis-card p-4">
                <h5 class="mb-4">Seleccionar Productos</h5>
                
                <!-- Categorías -->
                <div class="mb-4">
                    <div class="row">
                        @foreach($categories as $category)
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-primary w-100 category-btn" 
                                        onclick="filterByCategory({{ $category->id }})">
                                    {{ $category->name }}
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Productos -->
                <div class="row" id="products-container">
                    @foreach($products as $product)
                        <div class="col-md-4 mb-3 product-item" data-category="{{ $product->category_id }}">
                            <div class="card product-card" 
                                 onclick="handleProductClick({{ $product->id }}, '{{ $product->name }}', {{ $product->price }}, {{ $product->stock }}, {{ $product->is_food ? 'true' : 'false' }}, {{ $product->category_id }}, '{{ $product->category->name }}')">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $product->name }}</h6>
                                    <p class="card-text">${{ number_format($product->price, 2) }}</p>
                                    <small class="text-muted">Stock: {{ $product->stock }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Panel Derecho - Carrito -->
        <div class="col-md-4">
            <div class="tochis-card p-4">
                <div class="d-flex align-items-center mb-4">
                    <i class="fas fa-shopping-cart me-2 text-warning"></i>
                    <h5 class="mb-0">Orden de Compra</h5>
                </div>
                
                <!-- Items del carrito -->
                <div id="cart-items" class="mb-4" style="min-height: 300px;">
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tu orden está vacía</p>
                        <small class="text-muted">Selecciona deliciosos platillos para agregar</small>
                    </div>
                </div>
                
                <!-- Totales -->
                <div class="border-top pt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span id="subtotal">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Impuesto:</span>
                        <span id="tax">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total a Pagar:</strong>
                        <strong id="total" class="text-warning">$0.00</strong>
                    </div>
                    
                    <!-- Notas -->
                    <div class="mb-3">
                        <label class="form-label">Notas Especiales</label>
                        <textarea class="form-control" id="special-notes" rows="3" 
                                  placeholder="Observaciones especiales para esta orden..."></textarea>
                    </div>
                    
                    <!-- Botones -->
                    <button class="btn btn-warning w-100 mb-2" onclick="processOrder()">
                        <i class="fas fa-cash-register me-2"></i>Procesar Venta
                    </button>
                    <button class="btn btn-outline-secondary w-100" onclick="clearCart()">
                        <i class="fas fa-trash me-2"></i>Limpiar Todo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
console.log('JavaScript iniciando...');

// Variables básicas
let cart = [];

// Función para hacer clic en productos
function handleProductClick(productId, productName, price, stock, isFood, categoryId, categoryName) {
    console.log('=== INICIO handleProductClick ===');
    console.log('Parámetros recibidos:', {productId, productName, price, stock, isFood, categoryId, categoryName});
    
    // Buscar si el producto ya existe en el carrito
    let existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        existingItem.quantity += 1;
        console.log('Incrementando cantidad del producto existente');
    } else {
        const newItem = {
            id: productId,
            name: productName,
            price: parseFloat(price),
            quantity: 1,
            stock: parseInt(stock),
            isFood: isFood,
            categoryId: categoryId,
            categoryName: categoryName
        };
        cart.push(newItem);
        console.log('Agregando nuevo producto:', newItem);
    }
    
    console.log('Carrito después de agregar:', cart);
    updateCartDisplay();
}

// Función para filtrar categorías
function filterByCategory(categoryId) {
    console.log('Filtrando por categoría:', categoryId);
    const products = document.querySelectorAll('.product-item');
    
    products.forEach(product => {
        if (categoryId === 'all' || product.dataset.category == categoryId) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}

// Función para actualizar la vista del carrito
function updateCartDisplay() {
    console.log('=== INICIO updateCartDisplay ===');
    console.log('Carrito actual:', cart);
    
    const cartContainer = document.getElementById('cart-items');
    console.log('Contenedor encontrado:', cartContainer);
    
    if (!cartContainer) {
        console.error('No se encontró el contenedor del carrito #cart-items');
        return;
    }
    
    // Limpiar contenido actual
    cartContainer.innerHTML = '';
    
    if (cart.length === 0) {
        cartContainer.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <p class="text-muted">Tu orden está vacía</p>
                <small class="text-muted">Selecciona deliciosos platillos para agregar</small>
            </div>
        `;
        updateTotals();
        return;
    }
    
    // Agregar cada item del carrito
    cart.forEach((item, index) => {
        const itemElement = document.createElement('div');
        itemElement.className = 'cart-item border-bottom py-2 mb-2';
        itemElement.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div class="flex-grow-1">
                    <h6 class="mb-1">${item.name}</h6>
                    <small class="text-muted">$${item.price.toFixed(2)} c/u</small>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-outline-secondary" onclick="decreaseQuantity(${index})">-</button>
                    <span class="mx-2">${item.quantity}</span>
                    <button class="btn btn-sm btn-outline-secondary" onclick="increaseQuantity(${index})">+</button>
                    <button class="btn btn-sm btn-outline-danger ms-2" onclick="removeItem(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="text-end mt-1">
                <strong>$${(item.price * item.quantity).toFixed(2)}</strong>
            </div>
        `;
        cartContainer.appendChild(itemElement);
    });
    
    updateTotals();
    console.log('=== FIN updateCartDisplay ===');
}

// Funciones para manejar cantidades
function increaseQuantity(index) {
    cart[index].quantity += 1;
    updateCartDisplay();
}

function decreaseQuantity(index) {
    if (cart[index].quantity > 1) {
        cart[index].quantity -= 1;
    } else {
        cart.splice(index, 1);
    }
    updateCartDisplay();
}

function removeItem(index) {
    cart.splice(index, 1);
    updateCartDisplay();
}

// Función para actualizar totales
function updateTotals() {
    const subtotal = cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    const tax = subtotal * 0.16; // 16% de impuesto
    const total = subtotal + tax;
    
    document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById('tax').textContent = `$${tax.toFixed(2)}`;
    document.getElementById('total').textContent = `$${total.toFixed(2)}`;
}

// Función para limpiar carrito
function clearCart() {
    if (cart.length > 0 && confirm('¿Estás seguro de que quieres limpiar todo el carrito?')) {
        cart = [];
        updateCartDisplay();
    }
}

// Función para procesar orden
function processOrder() {
    if (cart.length === 0) {
        alert('El carrito está vacío. Agrega productos antes de procesar la venta.');
        return;
    }
    
    alert('Funcionalidad de procesamiento de venta en desarrollo...');
    console.log('Orden a procesar:', cart);
}

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado correctamente');
    console.log('Sistema POS inicializado y funcionando');
    updateCartDisplay();
});

console.log('Script cargado hasta el final');
</script>
@endpush
