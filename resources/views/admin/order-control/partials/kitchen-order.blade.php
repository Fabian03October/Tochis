{{-- Partial para orden en cocina --}}
<div class="border border-gray-200 rounded-lg p-4 mb-4 hover:shadow-md transition" data-order-id="{{ $order->id }}">
    <div class="flex justify-between items-start mb-3">
        <div>
            <h3 class="font-bold text-lg text-gray-900">Orden #{{ $order->order_number }}</h3>
            <p class="text-sm text-gray-600">{{ $order->created_at->format('H:i') }} - {{ $order->user->name ?? 'Cliente' }}</p>
        </div>
        <div class="text-right">
            <span class="bg-{{ $order->kitchen_status_color }}-100 text-{{ $order->kitchen_status_color }}-800 px-2 py-1 rounded-full text-xs font-medium">
                {{ $order->kitchen_status_text }}
            </span>
            <p class="text-sm font-medium mt-1 {{ $order->kitchen_time > 30 ? 'text-red-600' : 'text-gray-600' }}">
                {{ $order->kitchen_time }} min
            </p>
        </div>
    </div>
    
    <div class="space-y-2 mb-4">
        @foreach($order->saleDetails as $detail)
            <div class="flex justify-between text-sm">
                <span>{{ $detail->quantity }}x {{ $detail->product->name }}</span>
                <span>${{ number_format($detail->price, 2) }}</span>
            </div>
            @if($detail->options->count() > 0)
                <div class="ml-4 text-xs text-gray-500">
                    @foreach($detail->options as $option)
                        <div>• {{ $option->name }} {{ $option->price > 0 ? '(+$'.number_format($option->price, 2).')' : '' }}</div>
                    @endforeach
                </div>
            @endif
        @endforeach
    </div>
    
    <div class="flex justify-between items-center">
        <span class="font-bold">Total: ${{ number_format($order->total, 2) }}</span>
        <div class="space-x-2">
            @if($order->kitchen_status === 'pending')
                <button onclick="startKitchen({{ $order->id }})" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm transition">
                    <i class="fas fa-play mr-1"></i>Iniciar
                </button>
            @elseif($order->kitchen_status === 'in_kitchen')
                <button onclick="markReady({{ $order->id }})" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm transition">
                    <i class="fas fa-check mr-1"></i>Listo
                </button>
            @elseif($order->kitchen_status === 'ready')
                <button onclick="markDelivered({{ $order->id }})" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition">
                    <i class="fas fa-truck mr-1"></i>Entregar
                </button>
            @endif
        </div>
    </div>
    
    @if($order->notes)
        <div class="mt-3 p-2 bg-yellow-50 border-l-4 border-yellow-400">
            <p class="text-sm text-yellow-800">
                <i class="fas fa-sticky-note mr-1"></i>
                {{ $order->notes }}
            </p>
        </div>
    @endif
</div>