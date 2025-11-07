@extends('layouts.app')

@section('title', 'Control de Órdenes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Control de Órdenes - Cajero</h1>
                <div class="text-muted">
                    <i class="fas fa-clock"></i> Actualización automática cada 10 segundos
                </div>
            </div>
        </div>
    </div>

    <!-- Grid de Órdenes -->
    <div id="orders-grid" class="mb-4">
        @forelse($inPreparationOrders as $order)
            <div class="order-row mb-3" data-order-id="{{ $order->id }}">
                <!-- Orden Principal -->
                <div class="order-card clickable-card" 
                     data-order-id="{{ $order->id }}" 
                     data-type="order"
                     style="background: linear-gradient(135deg, #FF6B35, #F7931E); color: white;">
                    <div class="card-content">
                        <h6 class="mb-1">{{ $order->order_display_name }}</h6>
                        <small>{{ $order->created_at->format('H:i') }}</small>
                        <div class="mt-1">
                            <strong>${{ number_format($order->total, 2) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Platillos y Bebidas -->
                <div class="items-row">
                    @foreach($order->saleDetails as $detail)
                        @php
                            $isFood = $detail->product->category_id !== 5; // Todas excepto Bebidas (ID 5)
                            $cardClass = $isFood ? 'food-card' : 'drink-card';
                            $bgColor = $isFood ? 
                                'background: linear-gradient(135deg, #8B0000, #DC143C);' : 
                                'background: linear-gradient(135deg, #4169E1, #1E90FF);';
                        @endphp
                        
                        <div class="item-card clickable-card {{ $cardClass }}" 
                             data-order-id="{{ $order->id }}" 
                             data-detail-id="{{ $detail->id }}"
                             data-type="{{ $isFood ? 'food' : 'drink' }}"
                             style="{{ $bgColor }} color: white;">
                            <div class="card-content">
                                <div class="item-name">{{ $detail->product_name }}</div>
                                <div class="item-quantity">{{ $detail->quantity }}x</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center text-muted p-5">
                <i class="fas fa-utensils fa-3x mb-3"></i>
                <h5>No hay órdenes en preparación</h5>
                <p>Las nuevas órdenes aparecerán aquí automáticamente</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" style="z-index: 99999 !important;">
    <div class="modal-dialog modal-dialog-centered" role="document" style="z-index: 100001 !important;">
        <div class="modal-content" style="z-index: 100000 !important;">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <i class="fas fa-question-circle"></i> Confirmar Acción
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="fas fa-utensils fa-3x text-warning mb-3"></i>
                    <h5 id="modal-question">¿Ya se sirvió este platillo?</h5>
                    <p class="text-muted" id="modal-item-name">Nombre del platillo</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-lg" data-dismiss="modal" style="z-index: 100002 !important;">
                    <i class="fas fa-times"></i> No
                </button>
                <button type="button" class="btn btn-success btn-lg" id="confirm-served" style="z-index: 100002 !important;">
                    <i class="fas fa-check"></i> Sí, ya se sirvió
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos para el grid de órdenes */
.order-row {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.order-card {
    min-width: 120px;
    width: 120px;
    height: 120px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    cursor: pointer;
    position: relative;
}

.order-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
}

.items-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    flex: 1;
}

.item-card {
    min-width: 100px;
    width: 100px;
    height: 100px;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 6px rgba(0,0,0,0.15);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    cursor: pointer;
    position: relative;
}

.item-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.2);
}

.card-content {
    text-align: center;
    padding: 10px;
}

.item-name {
    font-size: 11px;
    font-weight: bold;
    margin-bottom: 5px;
    line-height: 1.2;
}

.item-quantity {
    font-size: 14px;
    font-weight: bold;
    background: rgba(255,255,255,0.2);
    border-radius: 15px;
    padding: 2px 8px;
    display: inline-block;
}

/* Efectos de click */
.clickable-card:active {
    transform: scale(0.95);
}

/* Responsive */
@media (max-width: 768px) {
    .order-row {
        flex-direction: column;
        gap: 10px;
    }
    
    .items-row {
        justify-content: center;
    }
    
    .order-card, .item-card {
        width: 80px;
        height: 80px;
        min-width: 80px;
    }
    
    .item-name {
        font-size: 10px;
    }
}

/* Animación de eliminación */
@keyframes fadeOut {
    0% { opacity: 1; transform: scale(1); }
    100% { opacity: 0; transform: scale(0.8); }
}

.removing {
    animation: fadeOut 0.3s ease-in-out forwards;
}

/* Estilos para el modal */
.modal {
    z-index: 99999 !important;
}

.modal-backdrop {
    z-index: 99998 !important;
}

.modal-content {
    z-index: 100000 !important;
    position: relative;
}

.modal-dialog {
    pointer-events: auto;
    z-index: 100001 !important;
}

/* Botones más grandes en el modal */
.modal-footer .btn {
    padding: 12px 24px;
    font-size: 16px;
    font-weight: bold;
    z-index: 100002 !important;
    position: relative;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentCard = null;
    let currentType = null;
    let currentOrderId = null;
    let currentDetailId = null;
    let refreshInterval = null;
    
    // Actualizar órdenes cada 10 segundos
    refreshInterval = setInterval(updateOrders, 10000);
    
    // Event listeners para las cards clickeables (usando delegación de eventos)
    document.addEventListener('click', function(e) {
        const card = e.target.closest('.clickable-card');
        if (card) {
            e.preventDefault();
            e.stopPropagation();
            
            currentCard = card;
            currentType = card.dataset.type;
            currentOrderId = card.dataset.orderId;
            currentDetailId = card.dataset.detailId || null;
            
            console.log('Card clicked:', currentType, currentOrderId, currentDetailId); // Debug
            
            showConfirmationModal();
        }
    });
    
    // Botón de confirmación en el modal
    document.getElementById('confirm-served').addEventListener('click', function() {
        console.log('Confirm button clicked'); // Debug
        
        // Procesar la acción inmediatamente
        if (currentType === 'order') {
            markOrderAsServed(currentOrderId);
        } else {
            markItemAsServed(currentOrderId, currentDetailId);
        }
    });
    
    function showConfirmationModal() {
        const modal = document.getElementById('confirmationModal');
        const question = document.getElementById('modal-question');
        const itemName = document.getElementById('modal-item-name');
        
        if (currentType === 'order') {
            question.textContent = '¿Ya se sirvió toda la orden?';
            itemName.textContent = `Orden #${currentCard.querySelector('h6').textContent.replace('Orden #', '')}`;
        } else {
            const isFood = currentType === 'food';
            question.textContent = `¿Ya se sirvió este ${isFood ? 'platillo' : 'bebida'}?`;
            itemName.textContent = currentCard.querySelector('.item-name').textContent;
        }
        
        // Detener el refresh automático mientras el modal está abierto
        if (refreshInterval) {
            clearInterval(refreshInterval);
            refreshInterval = null;
        }
        
        // Mostrar el modal de forma simple
        $(modal).modal({
            backdrop: true,
            keyboard: true,
            show: true
        });
    }
    
    function markOrderAsServed(orderId) {
        console.log('Marking order as served:', orderId); // Debug
        
        // Cerrar modal y limpiar
        $('#confirmationModal').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        
        fetch(`/cashier/orders/${orderId}/mark-complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Order response:', data); // Debug
            if (data.success) {
                removeOrderRow(orderId);
                showAlert('success', data.message);
                
                // Reiniciar refresh
                if (!refreshInterval) {
                    refreshInterval = setInterval(updateOrders, 10000);
                }
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Error al procesar la solicitud');
        });
    }
    
    function markItemAsServed(orderId, detailId) {
        console.log('Marking item as served:', orderId, detailId); // Debug
        
        // Cerrar modal y limpiar
        $('#confirmationModal').modal('hide');
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open');
        
        fetch(`/cashier/orders/${orderId}/mark-item-served/${detailId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('Item response:', data); // Debug
            if (data.success) {
                if (data.order_complete) {
                    removeOrderRow(orderId);
                } else {
                    removeItemCard(detailId);
                }
                showAlert('success', data.message);
                
                // Reiniciar refresh
                if (!refreshInterval) {
                    refreshInterval = setInterval(updateOrders, 10000);
                }
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Error al procesar la solicitud');
        });
    }
    
    function removeOrderRow(orderId) {
        const orderRow = document.querySelector(`[data-order-id="${orderId}"]`);
        if (orderRow) {
            orderRow.classList.add('removing');
            setTimeout(() => {
                orderRow.remove();
            }, 300);
        }
    }
    
    function removeItemCard(detailId) {
        const itemCard = document.querySelector(`[data-detail-id="${detailId}"]`);
        if (itemCard) {
            itemCard.classList.add('removing');
            setTimeout(() => {
                itemCard.remove();
            }, 300);
        }
    }
    
    function updateOrders() {
        fetch('{{ route("cashier.orders.api") }}')
            .then(response => response.json())
            .then(data => {
                location.reload(); // Recargar para actualizar las órdenes
            })
            .catch(error => {
                console.error('Error al actualizar órdenes:', error);
            });
    }
    
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <strong>${type === 'success' ? '¡Éxito!' : 'Error'}</strong> ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', alertHtml);
        
        setTimeout(() => {
            const alert = document.querySelector('.alert');
            if (alert) alert.remove();
        }, 4000);
    }
});
</script>

{{-- 
    Este archivo es una plantilla de SCRIPT, no se muestra directamente.
    Es usado por JavaScript para generar las tarjetas dinámicamente.
--}}
<script type="text/template" id="order-card-template">
    <div class="bg-white rounded-lg shadow border border-gray-200 m-2 p-3 transition-all hover:shadow-md">
        <div class="flex justify-between items-center mb-2">
            <h6 class="font-semibold text-gray-900">Orden #__ORDER_NUMBER__</h6>
            <div class="flex items-center space-x-2">
                __STATION_BADGE__
                <small class="text-gray-500">__TIME__</small>
            </div>
        </div>
        
        __KITCHEN_TIME__
        
        <ul class="list-none mb-0 text-sm text-gray-700 mt-2 space-y-1">
            __PRODUCTS_LIST__
        </ul>
        
        <div class="mt-3 pt-2 border-t border-gray-200">
            <strong class="text-gray-900">Total: $__TOTAL__</strong>
        </div>
        
        <div class="mt-2">
            __ACTION_BUTTONS__
        </div>
    </div>
</script>

<style>
/* Compatibilidad entre Bootstrap y clases Tailwind personalizadas */
.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Clases Tailwind emuladas para compatibilidad */
.bg-white { background-color: #ffffff; }
.rounded-lg { border-radius: 0.5rem; }
.shadow { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); }
.border { border-width: 1px; }
.border-gray-200 { border-color: #e5e7eb; }
.border-t { border-top-width: 1px; }
.transition-all { transition: all 0.3s ease; }
.hover\:shadow-md:hover { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }

.flex { display: flex; }
.justify-between { justify-content: space-between; }
.items-center { align-items: center; }
.space-x-2 > * + * { margin-left: 0.5rem; }
.space-y-1 > * + * { margin-top: 0.25rem; }

.font-semibold { font-weight: 600; }
.text-gray-900 { color: #111827; }
.text-gray-500 { color: #6b7280; }
.text-gray-600 { color: #4b5563; }
.text-gray-700 { color: #374151; }
.text-red-600 { color: #dc2626; }
.text-orange-500 { color: #f97316; }
.font-bold { font-weight: 700; }
.font-medium { font-weight: 500; }

.text-sm { font-size: 0.875rem; }
.text-xs { font-size: 0.75rem; }

.mb-2 { margin-bottom: 0.5rem; }
.mt-2 { margin-top: 0.5rem; }
.mt-3 { margin-top: 0.75rem; }
.pt-2 { padding-top: 0.5rem; }

.list-none { list-style: none; }

.inline-flex { display: inline-flex; }
.px-2 { padding-left: 0.5rem; padding-right: 0.5rem; }
.px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
.py-1 { padding-top: 0.25rem; padding-bottom: 0.25rem; }
.py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
.rounded-full { border-radius: 9999px; }
.rounded-md { border-radius: 0.375rem; }

.bg-blue-100 { background-color: #dbeafe; }
.text-blue-800 { color: #1e40af; }
.bg-gray-100 { background-color: #f3f4f6; }
.text-gray-800 { color: #1f2937; }

.bg-green-600 { background-color: #16a34a; }
.hover\:bg-green-700:hover { background-color: #15803d; }
.bg-blue-600 { background-color: #2563eb; }
.hover\:bg-blue-700:hover { background-color: #1d4ed8; }
.bg-orange-600 { background-color: #ea580c; }
.hover\:bg-orange-700:hover { background-color: #c2410c; }
.text-white { color: #ffffff; }

.border-transparent { border-color: transparent; }
.leading-4 { line-height: 1rem; }
.focus\:outline-none:focus { outline: none; }
.focus\:ring-2:focus { box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5); }
.focus\:ring-offset-2:focus { box-shadow: 0 0 0 2px #ffffff, 0 0 0 4px rgba(59, 130, 246, 0.5); }
.focus\:ring-green-500:focus { box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.5); }
.focus\:ring-blue-500:focus { box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5); }
.focus\:ring-orange-500:focus { box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.5); }
.transition-colors { transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out; }

.fixed { position: fixed; }
.top-4 { top: 1rem; }
.right-4 { right: 1rem; }
.z-50 { z-index: 50; }
.max-w-sm { max-width: 24rem; }
.w-full { width: 100%; }

.bg-green-100 { background-color: #dcfce7; }
.border-green-500 { border-color: #22c55e; }
.text-green-700 { color: #15803d; }
.bg-red-100 { background-color: #fee2e2; }
.border-red-500 { border-color: #ef4444; }
.text-red-700 { color: #b91c1c; }
.border-l-4 { border-left-width: 4px; }
.p-4 { padding: 1rem; }

.flex-shrink-0 { flex-shrink: 0; }
.ml-3 { margin-left: 0.75rem; }
.ml-auto { margin-left: auto; }
.pl-3 { padding-left: 0.75rem; }
.mr-1 { margin-right: 0.25rem; }

.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* Animaciones para tiempo de cocina */
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

.text-red-600.font-bold {
    animation: pulse 1s infinite;
}

.border-right:last-child {
    border-right: none !important;
}

/* Hover effects para las tarjetas */
.order-card {
    cursor: pointer;
    transition: all 0.3s ease;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Actualizar órdenes cada 10 segundos
    setInterval(updateOrders, 10000);
    
    function updateOrders() {
        fetch('{{ route("cashier.orders.api") }}')
            .then(response => response.json())
            .then(data => {
                updateOrderPanel('preparation', data.in_preparation, false, false, 'preparation');
                updateOrderPanel('ready', data.ready, true, false, 'ready');
                updateOrderPanel('received', data.received, false, true, 'received');
                
                // Actualizar contadores
                document.getElementById('preparation-count').textContent = data.in_preparation.length;
                document.getElementById('ready-count').textContent = data.ready.length;
                document.getElementById('received-count').textContent = data.received.length;
            })
            .catch(error => {
                console.error('Error al actualizar órdenes:', error);
            });
    }
    
    function updateOrderPanel(type, orders, showReceiveButton = false, showDeliverButton = false, panelType = null) {
        const container = document.getElementById(type + '-orders');
        
        if (orders.length === 0) {
            let emptyMessage = '';
            let icon = '';
            
            switch(type) {
                case 'preparation':
                    icon = 'fas fa-utensils';
                    emptyMessage = 'No hay órdenes en preparación';
                    break;
                case 'ready':
                    icon = 'fas fa-bell';
                    emptyMessage = 'No hay órdenes listas';
                    break;
                case 'received':
                    icon = 'fas fa-clipboard-check';
                    emptyMessage = 'No hay órdenes recibidas';
                    break;
            }
            
            container.innerHTML = `
                <div class="p-3 text-center text-muted">
                    <i class="${icon} fa-2x mb-2"></i>
                    <p>${emptyMessage}</p>
                </div>
            `;
            return;
        }
        
        // Obtener el template
        const template = document.getElementById('order-card-template').innerHTML;
        let html = '';
        
        orders.forEach(order => {
            // Procesar datos de la orden
            const orderDate = new Date(order.created_at);
            const timeString = orderDate.toLocaleTimeString('es-ES', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            
            // Calcular tiempo en cocina
            let kitchenTimeHtml = '';
            if (order.kitchen_started_at) {
                const startTime = new Date(order.kitchen_started_at);
                const now = new Date();
                const diffMinutes = Math.floor((now - startTime) / 60000);
                
                let timeClass = 'text-gray-600';
                if (diffMinutes > 15) timeClass = 'text-red-600 font-bold';
                else if (diffMinutes > 10) timeClass = 'text-orange-500 font-semibold';
                
                kitchenTimeHtml = `<div class="text-sm ${timeClass} mb-2">⏱️ ${diffMinutes} min en preparación</div>`;
            }
            
            // Generar lista de productos
            let productsList = '';
            order.sale_details.forEach(detail => {
                productsList += `<li class="flex justify-between">
                    <span>${detail.quantity}x ${detail.product_name}</span>
                </li>`;
            });
            
            // Badge de estación
            let stationBadge = '';
            if (order.station_type === 'bar') {
                stationBadge = '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Barra</span>';
            } else {
                stationBadge = '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Cocina</span>';
            }
            
            // Botones de acción
            let actionButtons = '';
            if (type === 'preparation') {
                // Botón para marcar como lista desde preparación
                actionButtons = `
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors" onclick="markReady(${order.id})">
                        <i class="fas fa-check-circle mr-1"></i> Marcar Lista
                    </button>
                `;
            } else if (showReceiveButton) {
                actionButtons = `
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors" onclick="markReceived(${order.id})">
                        <i class="fas fa-hand-holding mr-1"></i> Recibir
                    </button>
                `;
            } else if (showDeliverButton) {
                actionButtons = `
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors" onclick="markDelivered(${order.id})">
                        <i class="fas fa-check-double mr-1"></i> Entregar
                    </button>
                `;
            }
            
            // Reemplazar placeholders en el template
            let cardHtml = template
                .replace('__ORDER_NUMBER__', order.order_number)
                .replace('__TIME__', timeString)
                .replace('__STATION_BADGE__', stationBadge)
                .replace('__KITCHEN_TIME__', kitchenTimeHtml)
                .replace('__PRODUCTS_LIST__', productsList)
                .replace('__TOTAL__', parseFloat(order.total).toFixed(2))
                .replace('__ACTION_BUTTONS__', actionButtons);
            
            html += cardHtml;
        });
        
        container.innerHTML = html;
    }
    
    // Función global para marcar como lista
    window.markReady = function(saleId) {
        fetch(`/cashier/orders/${saleId}/mark-ready`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar inmediatamente
                updateOrders();
                showAlert('success', data.message);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Error al procesar la solicitud');
        });
    };
    
    // Función global para marcar como recibido
    window.markReceived = function(saleId) {
        fetch(`/cashier/orders/${saleId}/mark-received`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar inmediatamente
                updateOrders();
                showAlert('success', data.message);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Error al procesar la solicitud');
        });
    };
    
    // Función global para marcar como entregado
    window.markDelivered = function(saleId) {
        fetch(`/cashier/orders/${saleId}/mark-delivered`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar inmediatamente
                updateOrders();
                showAlert('success', data.message);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Error al procesar la solicitud');
        });
    };
    
    function showAlert(type, message) {
        // Mostrar alert temporal con Tailwind CSS
        const alertClass = type === 'success' 
            ? 'bg-green-100 border-green-500 text-green-700' 
            : 'bg-red-100 border-red-500 text-red-700';
        
        const alertHtml = `
            <div class="fixed top-4 right-4 z-50 max-w-sm w-full">
                <div class="${alertClass} border-l-4 p-4 rounded shadow-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'}"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium">${message}</p>
                        </div>
                        <div class="ml-auto pl-3">
                            <button type="button" class="inline-flex text-gray-400 hover:text-gray-600" onclick="this.closest('.fixed').remove()">
                                <span class="sr-only">Close</span>
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', alertHtml);
        
        // Auto-remove after 4 seconds
        setTimeout(() => {
            const alert = document.querySelector('.fixed.top-4.right-4');
            if (alert) alert.remove();
        }, 4000);
    }
});
</script>
@endsection