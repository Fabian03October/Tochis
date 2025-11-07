@extends('layouts.app')

@section('title', 'Historial de Ventas - Sistema POS')
@section('page-title', 'Historial de Ventas')

@section('header-actions')
    <a href="{{ route('cashier.sale.index') }}" class="btn-primary">
        <i class="fas fa-shopping-cart mr-2"></i>
        Nueva Venta
    </a>
@endsection

@section('content')
<div class="fade-in">
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-filter mr-2 text-blue-600"></i>
                Filtros
            </h3>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('cashier.sale.history') }}" class="flex items-end space-x-4">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Fecha</label>
                    <input type="date" 
                           id="date" 
                           name="date" 
                           value="{{ request('date') }}"
                           class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-search mr-2"></i>
                        Filtrar
                    </button>
                </div>
                <div>
                    <a href="{{ route('cashier.sale.history') }}" class="btn-secondary">
                        <i class="fas fa-times mr-2"></i>
                        Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Sales Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-receipt text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Ventas</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $sales->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-dollar-sign text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Recaudado</p>
                    <p class="text-2xl font-semibold text-gray-900">${{ number_format($sales->sum('total'), 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-shopping-bag text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Artículos Vendidos</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $sales->sum(function($sale) { return $sale->saleDetails->sum('quantity'); }) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <i class="fas fa-chart-line text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Venta Promedio</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        ${{ $sales->count() > 0 ? number_format($sales->avg('total'), 2) : '0.00' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-list mr-2 text-green-600"></i>
                Lista de Ventas
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Venta
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha y Hora
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Artículos
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Método de Pago
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($sales as $sale)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $sale->sale_number }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $sale->created_at->format('d/m/Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $sale->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $sale->saleDetails->count() }} Platillos</div>
                                <div class="text-sm text-gray-500">{{ $sale->saleDetails->sum('quantity') }} unidades</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($sale->payment_method === 'cash') bg-green-100 text-green-800
                                    @elseif($sale->payment_method === 'card') bg-blue-100 text-blue-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                    <i class="fas 
                                        @if($sale->payment_method === 'cash') fa-money-bill-wave
                                        @elseif($sale->payment_method === 'card') fa-credit-card
                                        @else fa-exchange-alt @endif mr-1"></i>
                                    {{ ucfirst($sale->payment_method) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-semibold text-green-600">${{ number_format($sale->total, 2) }}</div>
                                @if($sale->payment_method === 'cash' && $sale->change_amount > 0)
                                    <div class="text-sm text-gray-500">Cambio: ${{ number_format($sale->change_amount, 2) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($sale->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Completada
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Cancelada
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="showSaleDetails({{ $sale->id }})" 
                                        class="text-blue-600 hover:text-blue-900 transition duration-200"
                                        title="Ver detalles">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <i class="fas fa-receipt text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-lg font-medium">No hay ventas registradas</p>
                                    <p class="text-sm">Los registros de ventas aparecerán aquí</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $sales->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Sale Details Modal -->
<div id="sale-details-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modal-title">Detalles de la Venta</h3>
                <button onclick="closeSaleDetails()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="modal-content">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showSaleDetails(saleId) {
    // Find sale data from the current page
    const sales = @json($sales->items());
    const sale = sales.find(s => s.id === saleId);
    
    if (!sale) return;
    
    const modal = document.getElementById('sale-details-modal');
    const content = document.getElementById('modal-content');
    
    let detailsHTML = `
        <div class="space-y-4">
            <div class="border-b pb-4">
                <p class="text-sm text-gray-600">Número de Venta</p>
                <p class="font-semibold">${sale.sale_number}</p>
            </div>
            
            <div class="border-b pb-4">
                <p class="text-sm text-gray-600">Fecha y Hora</p>
                <p class="font-semibold">${new Date(sale.created_at).toLocaleString('es-ES')}</p>
            </div>
            
            <div class="border-b pb-4">
                <p class="text-sm text-gray-600">Método de Pago</p>
                <p class="font-semibold">${sale.payment_method.charAt(0).toUpperCase() + sale.payment_method.slice(1)}</p>
            </div>
            
            <div class="border-b pb-4">
                <p class="text-sm text-gray-600">Platillos</p>
                <div class="mt-2 space-y-2">
    `;
    
    sale.sale_details.forEach(detail => {
        detailsHTML += `
            <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                <div>
                    <p class="font-medium text-sm">${detail.product_name}</p>
                    <p class="text-xs text-gray-600">$${parseFloat(detail.product_price).toFixed(2)} x ${detail.quantity}</p>
                </div>
                <p class="font-semibold">$${parseFloat(detail.subtotal).toFixed(2)}</p>
            </div>
        `;
    });
    
    detailsHTML += `
                </div>
            </div>
            
            <div>
                <div class="flex justify-between">
                    <span>Subtotal:</span>
                    <span>$${parseFloat(sale.subtotal).toFixed(2)}</span>
                </div>
                <div class="flex justify-between">
                    <span>Impuesto:</span>
                    <span>$${parseFloat(sale.tax).toFixed(2)}</span>
                </div>
                <div class="flex justify-between font-bold text-lg border-t pt-2">
                    <span>Total:</span>
                    <span>$${parseFloat(sale.total).toFixed(2)}</span>
                </div>
    `;
    
    if (sale.payment_method === 'cash') {
        detailsHTML += `
            <div class="flex justify-between text-sm text-gray-600">
                <span>Pagado:</span>
                <span>$${parseFloat(sale.paid_amount).toFixed(2)}</span>
            </div>
            <div class="flex justify-between text-sm text-gray-600">
                <span>Cambio:</span>
                <span>$${parseFloat(sale.change_amount).toFixed(2)}</span>
            </div>
        `;
    }
    
    detailsHTML += `</div></div>`;
    
    content.innerHTML = detailsHTML;
    modal.classList.remove('hidden');
}

function closeSaleDetails() {
    document.getElementById('sale-details-modal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('sale-details-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSaleDetails();
    }
});
</script>
@endpush
