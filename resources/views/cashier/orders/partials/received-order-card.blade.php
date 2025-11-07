<div class="order-card m-2 p-3 border rounded bg-primary text-white">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="mb-0">{{ $order->order_display_name }}</h6>
        <div>
            @if($order->station_type === 'bar')
                <span class="badge badge-info station-badge">Barra</span>
            @else
                <span class="badge badge-light station-badge">Cocina</span>
            @endif
            <small class="ml-1">{{ $order->created_at->format('H:i') }}</small>
        </div>
    </div>
    
    <ul class="list-unstyled mb-0 small">
        @foreach($order->saleDetails as $detail)
            <li>{{ $detail->quantity }}x {{ $detail->product_name }}</li>
        @endforeach
    </ul>
    
    <div class="mt-2 small">
        <strong>Total: ${{ number_format($order->total, 2) }}</strong>
    </div>
    
    <div class="mt-2 small text-light">
        <i class="fas fa-clock"></i> Recibida: {{ $order->updated_at->format('H:i') }}
    </div>
    
    <button class="btn btn-sm btn-light btn-action mt-2" onclick="markDelivered({{ $order->id }})">
        <i class="fas fa-check-double"></i> Entregar
    </button>
</div>