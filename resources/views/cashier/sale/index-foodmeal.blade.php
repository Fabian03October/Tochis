@extends('layouts.app-modern')

@section('title', 'Nueva Venta - TOCHIS')
@section('page-title', 'Punto de Venta')

@push('styles')
<style>
    .foodmeal-container {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        min-height: 100vh;
        font-family: 'Inter', sans-serif;
        padding: 0;
    }
    
    .main-content-wrapper {
        display: flex;
        gap: 24px;
        padding: 24px;
        height: calc(100vh - 100px);
    }
    
    .left-content {
        flex: 1;
        overflow-y: auto;
    }
    
    .main-h                                                 </div>                data-product-id="{{ $product->id }}"
                                 data-product-name="{{ strtolower($product->name) }}"
                                 onclick="handleProductClick({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->is_food ? 'true' : 'false' }})">
        background: white;
        padding: 24px 30px;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 24px;
    }
    
    .welcome-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }
    
    .welcome-text h1 {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }
    
    .search-container {
        position: relative;
    }
    
    .search-bar {
        background: #f3f4f6;
        border: none;
        border-radius: 12px;
        padding: 12px 20px 12px 50px;
        width: 400px;
        font-size: 14px;
        outline: none;
    }
    
    .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }
    
    .promotional-banner {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 32px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .banner-content h2 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
    }
    
    .banner-content p {
        font-size: 14px;
        opacity: 0.9;
        margin: 0;
    }
    
    .banner-food-image {
        position: absolute;
        right: 30px;
        top: 50%;
        transform: translateY(-50%);
        width: 120px;
        height: 120px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
    }
    
    .categories-section {
        background: white;
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
    }
    
    .view-all-link {
        color: #f97316;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
    }
    
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 16px;
        max-width: 600px;
    }
    
    .category-circle {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .category-circle:hover {
        border-color: #f97316;
        transform: translateY(-2px);
    }
    
    .category-circle.active {
        background: #f97316;
        color: white;
        border-color: #f97316;
    }
    
    .category-icon {
        width: 48px;
        height: 48px;
        background: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 20px;
        color: #f97316;
    }
    
    .category-circle.active .category-icon {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }
    
    .category-name {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
    }
    
    .category-circle.active .category-name {
        color: white;
    }
    
    .products-section {
        background: white;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }
    
    .product-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
    }
    
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
        border-color: #f97316;
    }
    
    .product-image {
        width: 100%;
        height: 180px;
        background: linear-gradient(135deg, #fed7aa 0%, #fb923c 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .product-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: #ef4444;
        color: white;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .add-btn {
        position: absolute;
        bottom: 12px;
        right: 12px;
        width: 36px;
        height: 36px;
        background: #f97316;
        color: white;
        border: none;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .add-btn:hover {
        background: #ea580c;
        transform: scale(1.1);
    }
    
    .product-info {
        padding: 16px;
    }
    
    .product-name {
        font-size: 16px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 4px;
    }
    
    .product-price {
        font-size: 18px;
        font-weight: 700;
        color: #f97316;
    }
    
    .product-rating {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 8px;
    }
    
    .stars {
        color: #fbbf24;
        font-size: 14px;
    }
    
    .rating-text {
        font-size: 12px;
        color: #6b7280;
        margin-left: 4px;
    }
    
    /* Cart Styles */
    .cart-sidebar {
        width: 360px;
        background: white;
        border-radius: 20px;
        padding: 24px;
        height: fit-content;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        flex-shrink: 0;
    }
    
    .balance-card {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        border-radius: 16px;
        padding: 20px;
        color: white;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    
    .balance-label {
        font-size: 14px;
        opacity: 0.9;
        margin-bottom: 4px;
    }
    
    .balance-amount {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 12px;
    }
    
    .balance-actions {
        display: flex;
        gap: 8px;
    }
    
    .balance-btn {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .balance-btn:hover {
        background: rgba(255, 255, 255, 0.3);
    }
    
    .order-menu-title {
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 16px;
    }
    
    .cart-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
    }
    
    .cart-item:last-child {
        border-bottom: none;
    }
    
    .cart-item-image {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #fed7aa 0%, #fb923c 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .cart-item-info {
        flex: 1;
    }
    
    .cart-item-name {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 2px;
    }
    
    .cart-item-quantity {
        font-size: 12px;
        color: #6b7280;
    }
    
    .cart-item-price {
        font-size: 14px;
        font-weight: 700;
        color: #f97316;
    }
    
    .order-total {
        border-top: 2px solid #f3f4f6;
        padding-top: 16px;
        margin-top: 16px;
    }
    
    .total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 14px;
        color: #6b7280;
    }
    
    .total-final {
        display: flex;
        justify-content: space-between;
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        margin-top: 8px;
    }
    
    .checkout-btn {
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 16px;
        width: 100%;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 16px;
        transition: all 0.3s ease;
    }
    
    .checkout-btn:hover {
        background: #2563eb;
        transform: translateY(-1px);
    }
    
    .checkout-btn:disabled {
        background: #d1d5db;
        cursor: not-allowed;
        transform: none;
    }
    
    .empty-cart {
        text-align: center;
        padding: 40px 20px;
        color: #6b7280;
    }
    
    .empty-cart-icon {
        font-size: 48px;
        color: #d1d5db;
        margin-bottom: 16px;
    }
    
    /* Responsive */
    @media (max-width: 1400px) {
        .products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 1024px) {
        .main-content-wrapper {
            flex-direction: column;
        }
        
        .cart-sidebar {
            width: 100%;
        }
        
        .products-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .search-bar {
            width: 300px;
        }
    }
    
    @media (max-width: 640px) {
        .products-grid {
            grid-template-columns: 1fr;
        }
        
        .welcome-section {
            flex-direction: column;
            gap: 16px;
        }
        
        .search-bar {
            width: 100%;
        }
        
        .categories-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="foodmeal-container" id="pos-app">
    <div class="main-content-wrapper">
        <!-- Left Content -->
        <div class="left-content">
            <!-- Header -->
            <div class="main-header">
                <div class="welcome-section">
                    <div class="welcome-text">
                        <h1>Hola, {{ auth()->user()->name }}</h1>
                    </div>
                    <div class="search-container">
                        <input type="text" class="search-bar" placeholder="¿Qué quieres ordenar hoy?" id="product-search">
                        <i class="fas fa-search search-icon"></i>
                    </div>
                </div>
                
                <!-- Promotional Banner -->
                <div class="promotional-banner">
                    <div class="banner-content">
                        <h2>Descuento de Voucher</h2>
                        <p>Hasta 20%</p>
                        <p style="font-size: 12px; margin-top: 8px;">Para hacer tu día más delicioso, obtén tus descuentos favoritos de TOCHIS</p>
                    </div>
                    <div class="banner-food-image">
                        <i class="fas fa-utensils"></i>
                    </div>
                </div>
            </div>
            
            <!-- Categories -->
            <div class="categories-section">
                <div class="section-header">
                    <h3 class="section-title">Categoría</h3>
                    <a href="#" class="view-all-link">Ver todo →</a>
                </div>
                <div class="categories-grid">
                    <div class="category-circle active" onclick="filterByCategory('all')" data-category="all">
                        <div class="category-icon">
                            <i class="fas fa-th-large"></i>
                        </div>
                        <div class="category-name">Todos</div>
                    </div>
                    @foreach($categories as $category)
                        @if($category->activeProducts->count() > 0)
                            <div class="category-circle" onclick="filterByCategory({{ $category->id }})" data-category="{{ $category->id }}">
                                <div class="category-icon">
                                    <i class="fas fa-utensils"></i>
                                </div>
                                <div class="category-name">{{ $category->name }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            
            <!-- Products -->
            <div class="products-section">
                <div class="section-header">
                    <h3 class="section-title">Platos Populares</h3>
                    <a href="#" class="view-all-link">Ver todo →</a>
                </div>
                <div class="products-grid">
                    @foreach($categories as $category)
                        @foreach($category->activeProducts as $product)
                            <div class="product-card"
                                 data-category="{{ $category->id }}"
                                 data-product-id="{{ $product->id }}"
                                 data-product-name="{{ strtolower($product->name) }}"
                                 onclick="handleProductClick({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->is_food ? 'true' : 'false' }})">
                                
                                <div class="product-image">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                                    @else
                                        <i class="fas fa-utensils text-white text-4xl"></i>
                                    @endif
                                    
                                    @if($product->stock <= $product->min_stock)
                                        <div class="product-badge">Poco Stock</div>
                                    @endif
                                    
                                    <button class="add-btn">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                
                                <div class="product-info">
                                    <div class="product-name">{{ $product->name }}</div>
                                    <div class="product-price">${{ number_format($product->price, 2) }}</div>
                                    <div class="product-rating">
                                        <div class="stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <span class="rating-text">4.9 km • 21 min</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Cart Sidebar -->
        <div class="cart-sidebar">
            <!-- Balance Card -->
            <div class="balance-card">
                <div class="balance-label">Balance</div>
                <div class="balance-amount">$12.000</div>
                <div class="balance-actions">
                    <button class="balance-btn">
                        <i class="fas fa-plus mr-1"></i> Top Up
                    </button>
                    <button class="balance-btn">
                        <i class="fas fa-exchange-alt mr-1"></i> Transfer
                    </button>
                </div>
            </div>
            
            <!-- Order Menu -->
            <div class="order-menu-title">Menú de Orden</div>
            
            <!-- Cart Items -->
            <div id="cart-items">
                <div id="empty-cart" class="empty-cart">
                    <div class="empty-cart-icon">
                        <i class="fas fa-shopping-basket"></i>
                    </div>
                    <p>Tu orden está vacía</p>
                    <p style="font-size: 12px; color: #9ca3af;">Selecciona deliciosos Platillos para agregar</p>
                </div>
            </div>
            
            <!-- Order Total -->
            <div id="order-total" class="order-total" style="display: none;">
                <div class="total-row">
                    <span>Servicio</span>
                    <span id="service-fee">+$1.25</span>
                </div>
                <div class="total-final">
                    <span>Total</span>
                    <span id="total-amount">$0.00</span>
                </div>
            </div>
            
            <!-- Checkout Button -->
            <button class="checkout-btn" id="checkout-btn" disabled onclick="processSale()">
                <i class="fas fa-credit-card mr-2"></i>
                Checkout
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let cart = [];
let subtotal = 0;
let serviceFee = 1.25;
let total = 0;

// Filter products by category
function filterByCategory(categoryId) {
    // Update active category
    document.querySelectorAll('.category-circle').forEach(circle => {
        circle.classList.remove('active');
    });
    document.querySelector(`[data-category="${categoryId}"]`).classList.add('active');
    
    // Show/hide products
    document.querySelectorAll('.product-card').forEach(card => {
        if (categoryId === 'all' || card.dataset.category === categoryId.toString()) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Add product to cart
function handleProductClick(productId, productName, price, isFood = false) {
    const existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: productId,
            name: productName,
            price: price,
            quantity: 1,
            isFood: isFood
        });
    }
    
    updateCartDisplay();
}

// Update cart display
function updateCartDisplay() {
    const cartItemsContainer = document.getElementById('cart-items');
    const emptyCart = document.getElementById('empty-cart');
    const orderTotal = document.getElementById('order-total');
    const checkoutBtn = document.getElementById('checkout-btn');
    
    if (cart.length === 0) {
        emptyCart.style.display = 'block';
        orderTotal.style.display = 'none';
        checkoutBtn.disabled = true;
        return;
    }
    
    emptyCart.style.display = 'none';
    orderTotal.style.display = 'block';
    checkoutBtn.disabled = false;
    
    // Clear existing items
    const existingItems = cartItemsContainer.querySelectorAll('.cart-item');
    existingItems.forEach(item => item.remove());
    
    // Add cart items
    cart.forEach(item => {
        const cartItem = document.createElement('div');
        cartItem.className = 'cart-item';
        cartItem.innerHTML = `
            <div class="cart-item-image">
                <i class="fas fa-utensils text-white"></i>
            </div>
            <div class="cart-item-info">
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-quantity">${item.quantity}</div>
            </div>
            <div class="cart-item-price">+$${(item.price * item.quantity).toFixed(2)}</div>
        `;
        cartItemsContainer.appendChild(cartItem);
    });
    
    // Calculate totals
    subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    total = subtotal + serviceFee;
    
    document.getElementById('service-fee').textContent = `+$${serviceFee.toFixed(2)}`;
    document.getElementById('total-amount').textContent = `$${total.toFixed(2)}`;
}

// Process sale
function processSale() {
    if (cart.length === 0) {
        alert('El carrito está vacío');
        return;
    }
    
    // Simulate processing
    alert('¡Venta procesada exitosamente!');
    cart = [];
    updateCartDisplay();
}

// Search functionality
document.getElementById('product-search').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    document.querySelectorAll('.product-card').forEach(card => {
        const productName = card.dataset.productName;
        if (productName.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateCartDisplay();
});
</script>
@endpush
