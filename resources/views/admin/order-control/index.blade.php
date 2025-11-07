@extends('layouts.admin')

@section('title', 'Control de Órdenes')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="bg-gradient-to-r from-orange-500 to-red-600 text-white p-6 rounded-xl shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">🍳 Control de Órdenes</h1>
                <p class="text-orange-100 mt-2">Gestiona las órdenes de cocina en tiempo real</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-center">
                    <div class="text-2xl font-bold" id="kitchen-count">{{ $kitchenOrders->count() }}</div>
                    <div class="text-sm text-orange-100">En Cocina</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold" id="delivered-count">{{ $deliveredOrders->count() }}</div>
                    <div class="text-sm text-orange-100">Entregadas Hoy</div>
                </div>
                <button onclick="refreshOrders()" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition">
                    <i class="fas fa-sync-alt"></i> Actualizar
                </button>
            </div>
        </div>
    </div>

    {{-- Paneles principales --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Panel de Cocina --}}
        <div class="bg-white rounded-xl shadow-lg">
            <div class="bg-gradient-to-r from-yellow-500 to-orange-500 text-white p-4 rounded-t-xl">
                <h2 class="text-xl font-bold flex items-center">
                    <i class="fas fa-fire mr-2"></i>
                    Órdenes en Cocina
                    <span class="ml-2 bg-white/20 px-2 py-1 rounded-full text-sm" id="kitchen-badge">{{ $kitchenOrders->count() }}</span>
                </h2>
            </div>
            <div class="p-4 max-h-96 overflow-y-auto" id="kitchen-orders">
                @forelse($kitchenOrders as $order)
                    @include('admin.order-control.partials.kitchen-order', ['order' => $order])
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-utensils text-4xl mb-4"></i>
                        <p>No hay órdenes en cocina</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Panel de Entregadas --}}
        <div class="bg-white rounded-xl shadow-lg">
            <div class="bg-gradient-to-r from-green-500 to-blue-500 text-white p-4 rounded-t-xl">
                <h2 class="text-xl font-bold flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    Órdenes Entregadas
                    <span class="ml-2 bg-white/20 px-2 py-1 rounded-full text-sm" id="delivered-badge">{{ $deliveredOrders->count() }}</span>
                </h2>
            </div>
            <div class="p-4 max-h-96 overflow-y-auto" id="delivered-orders">
                @forelse($deliveredOrders as $order)
                    @include('admin.order-control.partials.delivered-order', ['order' => $order])
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-box text-4xl mb-4"></i>
                        <p>No hay órdenes entregadas hoy</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Estadísticas rápidas --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Tiempo Promedio</p>
                    <p class="text-lg font-semibold" id="avg-time">
                        {{ $kitchenOrders->where('preparation_minutes', '!=', null)->avg('preparation_minutes') ? round($kitchenOrders->where('preparation_minutes', '!=', null)->avg('preparation_minutes')) : 0 }} min
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-chart-line text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Órdenes del Día</p>
                    <p class="text-lg font-semibold">{{ $kitchenOrders->count() + $deliveredOrders->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-dollar-sign text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Ventas del Día</p>
                    <p class="text-lg font-semibold">${{ number_format($deliveredOrders->sum('total'), 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <div class="flex items-center">
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-percentage text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Eficiencia</p>
                    <p class="text-lg font-semibold">
                        {{ $kitchenOrders->count() + $deliveredOrders->count() > 0 
                           ? round(($deliveredOrders->count() / ($kitchenOrders->count() + $deliveredOrders->count())) * 100) 
                           : 0 }}%
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal de detalles de orden --}}
<div id="orderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Detalles de la Orden</h3>
                    <button onclick="closeOrderModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="orderDetails">
                    <!-- Se llena con JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let autoRefresh = true;

// Actualizar órdenes automáticamente cada 10 segundos
setInterval(function() {
    if (autoRefresh) {
        refreshOrders();
    }
}, 10000);

// Función para actualizar órdenes
async function refreshOrders() {
    try {
        const response = await fetch('{{ route("admin.order-control.orders") }}');
        const data = await response.json();
        
        updateKitchenOrders(data.kitchen_orders);
        updateDeliveredOrders(data.delivered_orders);
        updateCounters(data);
        
    } catch (error) {
        console.error('Error refreshing orders:', error);
    }
}

// Actualizar órdenes de cocina
function updateKitchenOrders(orders) {
    const container = document.getElementById('kitchen-orders');
    
    if (orders.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-utensils text-4xl mb-4"></i>
                <p>No hay órdenes en cocina</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = orders.map(order => `
        <div class="border border-gray-200 rounded-lg p-4 mb-4 hover:shadow-md transition" data-order-id="${order.id}">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <h3 class="font-bold text-lg text-gray-900">Orden #${order.order_number}</h3>
                    <p class="text-sm text-gray-600">${order.created_at} - ${order.customer}</p>
                </div>
                <div class="text-right">
                    <span class="bg-${order.status_color}-100 text-${order.status_color}-800 px-2 py-1 rounded-full text-xs font-medium">
                        ${order.status_text}
                    </span>
                    <p class="text-sm font-medium mt-1">${order.kitchen_time} min</p>
                </div>
            </div>
            
            <div class="space-y-2 mb-4">
                ${order.items.map(item => `
                    <div class="flex justify-between text-sm">
                        <span>${item.quantity}x ${item.name}</span>
                        <span>$${item.price}</span>
                    </div>
                `).join('')}
            </div>
            
            <div class="flex justify-between items-center">
                <span class="font-bold">Total: $${order.total}</span>
                <div class="space-x-2">
                    ${order.status === 'pending' ? `
                        <button onclick="startKitchen(${order.id})" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-play mr-1"></i>Iniciar
                        </button>
                    ` : ''}
                    ${order.status === 'in_kitchen' ? `
                        <button onclick="markReady(${order.id})" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-check mr-1"></i>Listo
                        </button>
                    ` : ''}
                    ${order.status === 'ready' ? `
                        <button onclick="markDelivered(${order.id})" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                            <i class="fas fa-truck mr-1"></i>Entregar
                        </button>
                    ` : ''}
                </div>
            </div>
        </div>
    `).join('');
}

// Actualizar órdenes entregadas
function updateDeliveredOrders(orders) {
    const container = document.getElementById('delivered-orders');
    
    if (orders.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-box text-4xl mb-4"></i>
                <p>No hay órdenes entregadas hoy</p>
            </div>
        `;
        return;
    }
    
    container.innerHTML = orders.map(order => `
        <div class="border border-gray-200 rounded-lg p-4 mb-4 bg-gray-50">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="font-bold text-gray-900">Orden #${order.order_number}</h3>
                    <p class="text-sm text-gray-600">${order.customer} - ${order.items_count} platillos</p>
                    <p class="text-xs text-green-600">Entregada: ${order.delivered_at}</p>
                    ${order.preparation_minutes ? `<p class="text-xs text-gray-500">Tiempo: ${order.preparation_minutes} min</p>` : ''}
                </div>
                <div class="text-right">
                    <span class="font-bold">$${order.total}</span>
                    <div class="text-xs text-green-600 mt-1">
                        <i class="fas fa-check-circle"></i> Entregada
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

// Actualizar contadores
function updateCounters(data) {
    document.getElementById('kitchen-count').textContent = data.kitchen_orders.length;
    document.getElementById('delivered-count').textContent = data.delivered_orders.length;
    document.getElementById('kitchen-badge').textContent = data.kitchen_orders.length;
    document.getElementById('delivered-badge').textContent = data.delivered_orders.length;
}

// Funciones de control de órdenes
async function startKitchen(orderId) {
    try {
        const response = await fetch(`/admin/order-control/orders/${orderId}/start-kitchen`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification(data.message, 'success');
            refreshOrders();
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('Error al procesar la orden', 'error');
    }
}

async function markReady(orderId) {
    try {
        const response = await fetch(`/admin/order-control/orders/${orderId}/mark-ready`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification(data.message, 'success');
            refreshOrders();
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('Error al procesar la orden', 'error');
    }
}

async function markDelivered(orderId) {
    try {
        const response = await fetch(`/admin/order-control/orders/${orderId}/mark-delivered`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification(data.message, 'success');
            refreshOrders();
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('Error al procesar la orden', 'error');
    }
}

// Función para mostrar notificaciones
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Controlar auto-refresh
document.addEventListener('visibilitychange', function() {
    autoRefresh = !document.hidden;
});
</script>
@endpush