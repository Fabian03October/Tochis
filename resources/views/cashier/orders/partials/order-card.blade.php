{{-- 
    Tarjeta de orden para 'En Preparación' y 'Entregadas'.
    El fondo de color ($bgClass) ya no se aplica a la tarjeta, 
    sino al encabezado de la columna en la vista principal.
--}}
<div class="bg-white rounded-lg shadow border border-gray-200 m-2 p-3 transition-all hover:shadow-md">
    
    {{-- Encabezado: Nombre de Orden y Hora --}}
    <div class="flex justify-between items-center mb-2">
        <h6 class="font-semibold text-gray-900">{{ $order->order_display_name }}</h6>
        <small class="text-gray-500">{{ $order->created_at->format('H:i') }}</small>
    </div>
    
    {{-- Timer de Cocina (la lógica PHP y las clases CSS personalizadas se mantienen) --}}
    @if($order->kitchen_started_at)
        @php
            $kitchenMinutes = $order->kitchen_started_at->diffInMinutes(now());
            $timeClass = '';
            if ($kitchenMinutes > 15) $timeClass = 'danger';
            elseif ($kitchenMinutes > 10) $timeClass = 'warning';
        @endphp
        <div class="kitchen-time {{ $timeClass }} text-sm">
            ⏱️ {{ $kitchenMinutes }} min
        </div>
    @endif
    
    {{-- Lista de Productos --}}
    <ul class="list-none m-0 text-sm text-gray-700 mt-2 space-y-1">
        @foreach($order->saleDetails as $detail)
            <li class="truncate">{{ $detail->quantity }}x {{ $detail->product_name }}</li>
        @endforeach
    </ul>
    
    {{-- Total de la Orden --}}
    <div class="mt-3 pt-2 border-t border-gray-200">
        <strong class="text-gray-900">Total: ${{ number_format($order->total, 2) }}</strong>
    </div>
</div>