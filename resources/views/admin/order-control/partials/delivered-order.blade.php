{{-- Partial para orden entregada --}}
<div class="border border-gray-200 rounded-lg p-4 mb-4 bg-gray-50">
    <div class="flex justify-between items-start">
        <div>
            <h3 class="font-bold text-gray-900">Orden #{{ $order->order_number }}</h3>
            <p class="text-sm text-gray-600">{{ $order->user->name ?? 'Cliente' }} - {{ $order->saleDetails->count() }} platillos</p>
            <p class="text-xs text-green-600">
                <i class="fas fa-clock mr-1"></i>
                Entregada: {{ $order->delivered_at ? $order->delivered_at->format('H:i') : 'N/A' }}
            </p>
            @if($order->preparation_minutes)
                <p class="text-xs text-gray-500">
                    <i class="fas fa-stopwatch mr-1"></i>
                    Tiempo de preparación: {{ $order->preparation_minutes }} min
                </p>
            @endif
        </div>
        <div class="text-right">
            <span class="font-bold text-lg">${{ number_format($order->total, 2) }}</span>
            <div class="text-xs text-green-600 mt-1">
                <i class="fas fa-check-circle mr-1"></i> Entregada
            </div>
            @if($order->payment_method === 'card')
                <div class="text-xs text-blue-600 mt-1">
                    <i class="fas fa-credit-card mr-1"></i> Tarjeta
                </div>
            @else
                <div class="text-xs text-green-600 mt-1">
                    <i class="fas fa-money-bill mr-1"></i> Efectivo
                </div>
            @endif
        </div>
    </div>
    
    {{-- Mostrar resumen de productos --}}
    <div class="mt-3 text-sm text-gray-600">
        @php
            $itemSummary = $order->saleDetails->map(function($detail) {
                return $detail->quantity . 'x ' . $detail->product->name;
            })->take(3)->implode(', ');
        @endphp
        {{ $itemSummary }}
        @if($order->saleDetails->count() > 3)
            <span class="text-gray-400">... y {{ $order->saleDetails->count() - 3 }} más</span>
        @endif
    </div>
</div>