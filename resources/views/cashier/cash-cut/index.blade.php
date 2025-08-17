@extends('layouts.app')

@section('title', 'Corte de Caja - Sistema POS')
@section('page-title', 'Corte de Caja')

@section('content')
<div class="fade-in">
    @if(!$currentCashCut)
        <!-- Open Cash Cut Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-play-circle mr-2 text-green-600"></i>
                    Abrir Caja
                </h3>
                <p class="mt-1 text-sm text-gray-600">
                    Para comenzar a realizar ventas, necesitas abrir la caja registradora.
                </p>
            </div>
            
            <form method="POST" action="{{ route('cashier.cash-cut.open') }}" class="p-6">
                @csrf
                <div class="max-w-md">
                    <label for="initial_amount" class="block text-sm font-medium text-gray-700 mb-2">
                        Monto Inicial en Caja *
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" 
                               id="initial_amount" 
                               name="initial_amount" 
                               step="0.01" 
                               min="0"
                               value="{{ old('initial_amount', '0.00') }}"
                               class="block w-full pl-7 pr-12 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('initial_amount') border-red-500 @enderror"
                               placeholder="0.00"
                               required>
                    </div>
                    @error('initial_amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        Introduce el monto de dinero en efectivo con el que inicias tu turno.
                    </p>
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="btn-success">
                        <i class="fas fa-unlock mr-2"></i>
                        Abrir Caja
                    </button>
                </div>
            </form>
        </div>
    @else
        <!-- Current Cash Cut Info -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-cash-register mr-2 text-blue-600"></i>
                    Caja Abierta - Turno Actual
                </h3>
                <p class="mt-1 text-sm text-gray-600">
                    Información del corte de caja actual iniciado el {{ $currentCashCut->opened_at->format('d/m/Y') }} a las {{ $currentCashCut->opened_at->format('H:i') }}.
                </p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <p class="text-2xl font-bold text-blue-600">${{ number_format($currentCashCut->initial_amount, 2) }}</p>
                        <p class="text-sm text-gray-600">Monto Inicial</p>
                    </div>
                    
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-2xl font-bold text-green-600">${{ number_format($totalAmount, 2) }}</p>
                        <p class="text-sm text-gray-600">Ventas en Efectivo</p>
                        <p class="text-xs text-gray-500">{{ $cashSales->count() }} de {{ $totalSales }} ventas</p>
                    </div>
                    
                    <div class="text-center p-4 bg-emerald-50 rounded-lg">
                        <p class="text-2xl font-bold text-emerald-600">${{ number_format($totalIncome, 2) }}</p>
                        <p class="text-sm text-gray-600">Ingresos Extra</p>
                        <p class="text-xs text-gray-500">{{ $cashMovements->where('type', 'income')->count() }} registros</p>
                    </div>
                    
                    <div class="text-center p-4 bg-red-50 rounded-lg">
                        <p class="text-2xl font-bold text-red-600">${{ number_format($totalExpenses, 2) }}</p>
                        <p class="text-sm text-gray-600">Gastos</p>
                        <p class="text-xs text-gray-500">{{ $cashMovements->where('type', 'expense')->count() }} registros</p>
                    </div>
                    
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <p class="text-2xl font-bold text-purple-600">${{ number_format($currentCashCut->initial_amount + $totalAmount + $totalIncome - $totalExpenses, 2) }}</p>
                        <p class="text-sm text-gray-600">Total Esperado</p>
                    </div>
                    
                    <div class="text-center p-4 bg-orange-50 rounded-lg">
                        <p class="text-2xl font-bold text-orange-600">{{ $currentCashCut->opened_at->diffForHumans(null, true) }}</p>
                        <p class="text-sm text-gray-600">Tiempo Abierto</p>
                    </div>
                </div>

                <!-- Payment Methods Breakdown -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-3">
                        <i class="fas fa-credit-card mr-2 text-blue-600"></i>
                        Desglose por Método de Pago
                    </h4>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center p-3 bg-green-100 rounded-lg">
                            <p class="text-lg font-bold text-green-700">${{ number_format($totalAmount, 2) }}</p>
                            <p class="text-sm text-gray-600">Efectivo</p>
                            <p class="text-xs text-gray-500">{{ $cashSales->count() }} ventas</p>
                            <p class="text-xs text-gray-400">💰 Cuenta para caja</p>
                        </div>
                        
                        <div class="text-center p-3 bg-blue-100 rounded-lg">
                            <p class="text-lg font-bold text-blue-700">${{ number_format($cardAmount, 2) }}</p>
                            <p class="text-sm text-gray-600">Tarjeta</p>
                            <p class="text-xs text-gray-500">{{ $cardSales->count() }} ventas</p>
                            <p class="text-xs text-gray-400">💳 No cuenta para caja</p>
                        </div>
                        
                        <div class="text-center p-3 bg-purple-100 rounded-lg">
                            <p class="text-lg font-bold text-purple-700">${{ number_format($transferAmount, 2) }}</p>
                            <p class="text-sm text-gray-600">Transferencia</p>
                            <p class="text-xs text-gray-500">{{ $transferSales->count() }} ventas</p>
                            <p class="text-xs text-gray-400">🏦 No cuenta para caja</p>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <p class="text-sm text-gray-600">
                            <strong>Total general:</strong> ${{ number_format($totalAmount + $cardAmount + $transferAmount, 2) }} 
                            ({{ $totalSales }} ventas)
                        </p>
                        <p class="text-xs text-gray-500">Solo el efectivo se cuenta para el corte de caja físico</p>
                    </div>
                </div>

                <!-- Cash Movements Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Add Expense -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <h4 class="text-lg font-medium text-gray-900 mb-3">
                            <i class="fas fa-minus-circle mr-2 text-red-600"></i>
                            Registrar Gasto
                        </h4>
                        <form method="POST" action="{{ route('cashier.cash-cut.add-expense') }}" class="space-y-3">
                            @csrf
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="expense_amount" class="block text-sm font-medium text-gray-700 mb-1">
                                        Monto *
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-sm">$</span>
                                        </div>
                                        <input type="number" 
                                               id="expense_amount" 
                                               name="amount" 
                                               step="0.01" 
                                               min="0.01"
                                               class="block w-full pl-7 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                               placeholder="0.00"
                                               required>
                                    </div>
                                </div>
                                <div>
                                    <label for="expense_concept" class="block text-sm font-medium text-gray-700 mb-1">
                                        Concepto *
                                    </label>
                                    <input type="text" 
                                           id="expense_concept" 
                                           name="concept" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                           placeholder="Ej: Compra ingredientes"
                                           required>
                                </div>
                            </div>
                            <div>
                                <label for="expense_description" class="block text-sm font-medium text-gray-700 mb-1">
                                    Descripción (Opcional)
                                </label>
                                <textarea id="expense_description" 
                                          name="description" 
                                          rows="2"
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                          placeholder="Detalles adicionales..."></textarea>
                            </div>
                            <button type="submit" class="w-full bg-red-600 text-white py-2 px-4 rounded-md text-sm font-medium hover:bg-red-700 transition duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Agregar Gasto
                            </button>
                        </form>
                    </div>

                    <!-- Add Income -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h4 class="text-lg font-medium text-gray-900 mb-3">
                            <i class="fas fa-plus-circle mr-2 text-green-600"></i>
                            Registrar Ingreso
                        </h4>
                        <form method="POST" action="{{ route('cashier.cash-cut.add-income') }}" class="space-y-3">
                            @csrf
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="income_amount" class="block text-sm font-medium text-gray-700 mb-1">
                                        Monto *
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 text-sm">$</span>
                                        </div>
                                        <input type="number" 
                                               id="income_amount" 
                                               name="amount" 
                                               step="0.01" 
                                               min="0.01"
                                               class="block w-full pl-7 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                               placeholder="0.00"
                                               required>
                                    </div>
                                </div>
                                <div>
                                    <label for="income_concept" class="block text-sm font-medium text-gray-700 mb-1">
                                        Concepto *
                                    </label>
                                    <input type="text" 
                                           id="income_concept" 
                                           name="concept" 
                                           class="block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                           placeholder="Ej: Propinas, bonus"
                                           required>
                                </div>
                            </div>
                            <div>
                                <label for="income_description" class="block text-sm font-medium text-gray-700 mb-1">
                                    Descripción (Opcional)
                                </label>
                                <textarea id="income_description" 
                                          name="description" 
                                          rows="2"
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                          placeholder="Detalles adicionales..."></textarea>
                            </div>
                            <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md text-sm font-medium hover:bg-green-700 transition duration-200">
                                <i class="fas fa-plus mr-2"></i>
                                Agregar Ingreso
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Cash Movements History -->
                @if($cashMovements->count() > 0)
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-3">
                        <i class="fas fa-history mr-2 text-gray-600"></i>
                        Movimientos de Caja
                    </h4>
                    <div class="space-y-2">
                        @foreach($cashMovements as $movement)
                        <div class="flex items-center justify-between p-3 bg-white rounded-md border">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    @if($movement->type === 'expense')
                                        <i class="fas fa-minus-circle text-red-500"></i>
                                    @else
                                        <i class="fas fa-plus-circle text-green-500"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $movement->concept }}</p>
                                    @if($movement->description)
                                        <p class="text-xs text-gray-500">{{ $movement->description }}</p>
                                    @endif
                                    <p class="text-xs text-gray-400">{{ $movement->created_at->format('H:i') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold {{ $movement->type === 'expense' ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $movement->type === 'expense' ? '-' : '+' }}${{ number_format($movement->amount, 2) }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Close Cash Cut Form -->
                <div class="border-t pt-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-calculator mr-2 text-red-600"></i>
                        Cerrar Caja
                    </h4>
                    
                    <form method="POST" action="{{ route('cashier.cash-cut.close') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="final_amount" class="block text-sm font-medium text-gray-700 mb-2">
                                    Monto Final en Caja *
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" 
                                           id="final_amount" 
                                           name="final_amount" 
                                           step="0.01" 
                                           min="0"
                                           value="{{ old('final_amount') }}"
                                           class="block w-full pl-7 pr-16 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('final_amount') border-red-500 @enderror"
                                           placeholder="0.00"
                                           required>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <button type="button" 
                                                onclick="openBillCounter()"
                                                class="text-blue-600 hover:text-blue-800 focus:outline-none"
                                                title="Contador de Billetes">
                                            <i class="fas fa-calculator text-lg"></i>
                                        </button>
                                    </div>
                                </div>
                                @error('final_amount')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <div class="mt-1 flex items-center justify-between">
                                    <p class="text-sm text-gray-500">
                                        Cuenta el dinero físico en la caja.
                                    </p>
                                    <button type="button" 
                                            onclick="openBillCounter()"
                                            class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                        <i class="fas fa-calculator mr-1"></i>
                                        Usar contador
                                    </button>
                                </div>
                            </div>
                            
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Notas (Opcional)
                                </label>
                                <textarea id="notes" 
                                          name="notes" 
                                          rows="3"
                                          class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror"
                                          placeholder="Observaciones del turno...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">
                                        Importante
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Cuenta cuidadosamente todo el dinero en la caja</li>
                                            <li>Incluye billetes, monedas y cualquier otro efectivo</li>
                                            <li>El sistema calculará automáticamente si hay diferencias</li>
                                            <li>Una vez cerrada, no podrás realizar más ventas hasta abrir una nueva caja</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end space-x-4">
                            <button type="submit" 
                                    class="btn-danger"
                                    onclick="return confirm('¿Estás seguro de que deseas cerrar la caja? No podrás realizar más ventas hasta abrir una nueva caja.')">
                                <i class="fas fa-lock mr-2"></i>
                                Cerrar Caja
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Today's Sales -->
        @if($todaySales && $todaySales->count() > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-list mr-2 text-green-600"></i>
                        Ventas del Turno Actual
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
                                    Hora
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Artículos
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Método
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($todaySales->take(10) as $sale)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $sale->sale_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $sale->created_at->format('H:i:s') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $sale->saleDetails->sum('quantity') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($sale->payment_method === 'cash') bg-green-100 text-green-800
                                            @elseif($sale->payment_method === 'card') bg-blue-100 text-blue-800
                                            @else bg-purple-100 text-purple-800 @endif">
                                            {{ ucfirst($sale->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-green-600">${{ number_format($sale->total, 2) }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($todaySales->count() > 10)
                    <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 text-center">
                        <a href="{{ route('cashier.sale.history') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Ver todas las ventas del día ({{ $todaySales->count() }} total)
                        </a>
                    </div>
                @endif
            </div>
        @endif
    @endif

    <!-- Recent Cash Cuts -->
    @if($recentCashCuts->count() > 0)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-history mr-2 text-purple-600"></i>
                    Cortes Recientes
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Inicial
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ventas
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Esperado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Real
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Diferencia
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentCashCuts as $cashCut)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $cashCut->closed_at->format('d/m/Y') }}</div>
                                    <div class="text-sm text-gray-500">{{ $cashCut->closed_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">${{ number_format($cashCut->initial_amount, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">${{ number_format($cashCut->sales_amount, 2) }}</div>
                                    <div class="text-sm text-gray-500">{{ $cashCut->total_sales }} ventas</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">${{ number_format($cashCut->expected_amount, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">${{ number_format($cashCut->final_amount, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($cashCut->difference == 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>
                                            Exacto
                                        </span>
                                    @elseif($cashCut->difference > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-plus mr-1"></i>
                                            +${{ number_format($cashCut->difference, 2) }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-minus mr-1"></i>
                                            ${{ number_format($cashCut->difference, 2) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

@if($currentCashCut)
<!-- Bill Counter Modal -->
<div id="bill-counter-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between pb-4 mb-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-calculator mr-2 text-blue-600"></i>
                    Contador de Billetes y Monedas
                </h3>
                <button type="button" onclick="closeBillCounter()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Expected vs Counted Display -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <p class="text-lg font-bold text-blue-600" id="expected-amount">
                        ${{ number_format(($currentCashCut ? $currentCashCut->initial_amount : 0) + $totalAmount + $totalIncome - $totalExpenses, 2) }}
                    </p>
                    <p class="text-sm text-gray-600">Monto Esperado</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-lg font-bold text-green-600" id="counted-amount">$0.00</p>
                    <p class="text-sm text-gray-600">Monto Contado</p>
                </div>
                <div class="text-center p-4 rounded-lg" id="difference-display">
                    <p class="text-lg font-bold" id="difference-amount">$0.00</p>
                    <p class="text-sm text-gray-600">Diferencia</p>
                </div>
            </div>

            <!-- Bill Counter Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Billetes -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-money-bill-wave mr-2 text-green-600"></i>
                        Billetes
                    </h4>
                    <div class="space-y-3">
                        <!-- $1000 -->
                        <div class="flex items-center justify-between p-3 bg-white rounded-md border">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-bold text-red-600">$1000</span>
                                <span class="text-sm text-gray-500">×</span>
                                <input type="number" 
                                       id="bill-1000" 
                                       min="0" 
                                       value="0" 
                                       class="w-16 px-2 py-1 border border-gray-300 rounded text-center focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       onchange="calculateTotal()">
                            </div>
                            <span class="font-medium text-gray-900" id="total-1000">$0</span>
                        </div>

                        <!-- $500 -->
                        <div class="flex items-center justify-between p-3 bg-white rounded-md border">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-bold text-purple-600">$500</span>
                                <span class="text-sm text-gray-500">×</span>
                                <input type="number" 
                                       id="bill-500" 
                                       min="0" 
                                       value="0" 
                                       class="w-16 px-2 py-1 border border-gray-300 rounded text-center focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       onchange="calculateTotal()">
                            </div>
                            <span class="font-medium text-gray-900" id="total-500">$0</span>
                        </div>

                        <!-- $200 -->
                        <div class="flex items-center justify-between p-3 bg-white rounded-md border">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-bold text-yellow-600">$200</span>
                                <span class="text-sm text-gray-500">×</span>
                                <input type="number" 
                                       id="bill-200" 
                                       min="0" 
                                       value="0" 
                                       class="w-16 px-2 py-1 border border-gray-300 rounded text-center focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       onchange="calculateTotal()">
                            </div>
                            <span class="font-medium text-gray-900" id="total-200">$0</span>
                        </div>

                        <!-- $100 -->
                        <div class="flex items-center justify-between p-3 bg-white rounded-md border">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-bold text-red-500">$100</span>
                                <span class="text-sm text-gray-500">×</span>
                                <input type="number" 
                                       id="bill-100" 
                                       min="0" 
                                       value="0" 
                                       class="w-16 px-2 py-1 border border-gray-300 rounded text-center focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       onchange="calculateTotal()">
                            </div>
                            <span class="font-medium text-gray-900" id="total-100">$0</span>
                        </div>

                        <!-- $50 -->
                        <div class="flex items-center justify-between p-3 bg-white rounded-md border">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-bold text-pink-600">$50</span>
                                <span class="text-sm text-gray-500">×</span>
                                <input type="number" 
                                       id="bill-50" 
                                       min="0" 
                                       value="0" 
                                       class="w-16 px-2 py-1 border border-gray-300 rounded text-center focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       onchange="calculateTotal()">
                            </div>
                            <span class="font-medium text-gray-900" id="total-50">$0</span>
                        </div>

                        <!-- $20 -->
                        <div class="flex items-center justify-between p-3 bg-white rounded-md border">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-bold text-blue-600">$20</span>
                                <span class="text-sm text-gray-500">×</span>
                                <input type="number" 
                                       id="bill-20" 
                                       min="0" 
                                       value="0" 
                                       class="w-16 px-2 py-1 border border-gray-300 rounded text-center focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       onchange="calculateTotal()">
                            </div>
                            <span class="font-medium text-gray-900" id="total-20">$0</span>
                        </div>
                    </div>
                </div>

                <!-- Monedas -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-coins mr-2 text-yellow-600"></i>
                        Monedas
                    </h4>
                    <div class="space-y-3">
                        <!-- $10 -->
                        <div class="flex items-center justify-between p-3 bg-white rounded-md border">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-bold text-yellow-500">$10</span>
                                <span class="text-sm text-gray-500">×</span>
                                <input type="number" 
                                       id="coin-10" 
                                       min="0" 
                                       value="0" 
                                       class="w-16 px-2 py-1 border border-gray-300 rounded text-center focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       onchange="calculateTotal()">
                            </div>
                            <span class="font-medium text-gray-900" id="total-coin-10">$0</span>
                        </div>

                        <!-- $5 -->
                        <div class="flex items-center justify-between p-3 bg-white rounded-md border">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-bold text-gray-600">$5</span>
                                <span class="text-sm text-gray-500">×</span>
                                <input type="number" 
                                       id="coin-5" 
                                       min="0" 
                                       value="0" 
                                       class="w-16 px-2 py-1 border border-gray-300 rounded text-center focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       onchange="calculateTotal()">
                            </div>
                            <span class="font-medium text-gray-900" id="total-coin-5">$0</span>
                        </div>

                        <!-- $2 -->
                        <div class="flex items-center justify-between p-3 bg-white rounded-md border">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-bold text-gray-500">$2</span>
                                <span class="text-sm text-gray-500">×</span>
                                <input type="number" 
                                       id="coin-2" 
                                       min="0" 
                                       value="0" 
                                       class="w-16 px-2 py-1 border border-gray-300 rounded text-center focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       onchange="calculateTotal()">
                            </div>
                            <span class="font-medium text-gray-900" id="total-coin-2">$0</span>
                        </div>

                        <!-- $1 -->
                        <div class="flex items-center justify-between p-3 bg-white rounded-md border">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-bold text-gray-400">$1</span>
                                <span class="text-sm text-gray-500">×</span>
                                <input type="number" 
                                       id="coin-1" 
                                       min="0" 
                                       value="0" 
                                       class="w-16 px-2 py-1 border border-gray-300 rounded text-center focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       onchange="calculateTotal()">
                            </div>
                            <span class="font-medium text-gray-900" id="total-coin-1">$0</span>
                        </div>

                        <!-- $0.50 -->
                        <div class="flex items-center justify-between p-3 bg-white rounded-md border">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-bold text-gray-300">$0.50</span>
                                <span class="text-sm text-gray-500">×</span>
                                <input type="number" 
                                       id="coin-050" 
                                       min="0" 
                                       value="0" 
                                       class="w-16 px-2 py-1 border border-gray-300 rounded text-center focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       onchange="calculateTotal()">
                            </div>
                            <span class="font-medium text-gray-900" id="total-coin-050">$0</span>
                        </div>

                        <!-- Otros (centavos, etc.) -->
                        <div class="flex items-center justify-between p-3 bg-white rounded-md border">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-bold text-gray-300">Otros</span>
                                <span class="text-sm text-gray-500">$</span>
                                <input type="number" 
                                       id="other-amount" 
                                       min="0" 
                                       step="0.01"
                                       value="0" 
                                       class="w-20 px-2 py-1 border border-gray-300 rounded text-center focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       onchange="calculateTotal()">
                            </div>
                            <span class="font-medium text-gray-900" id="total-other">$0</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-between pt-6 mt-6 border-t">
                <button type="button" 
                        onclick="clearAllCounts()" 
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                    <i class="fas fa-trash mr-2"></i>
                    Limpiar Todo
                </button>
                <div class="space-x-3">
                    <button type="button" 
                            onclick="closeBillCounter()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-200">
                        Cancelar
                    </button>
                    <button type="button" 
                            onclick="applyCountedAmount()" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-check mr-2"></i>
                        Usar Este Monto
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($currentCashCut)
<script>
let totalCounted = 0;

function openBillCounter() {
    document.getElementById('bill-counter-modal').classList.remove('hidden');
    calculateTotal();
}

function closeBillCounter() {
    document.getElementById('bill-counter-modal').classList.add('hidden');
}

function calculateTotal() {
    // Billetes
    const bill1000 = parseInt(document.getElementById('bill-1000').value) || 0;
    const bill500 = parseInt(document.getElementById('bill-500').value) || 0;
    const bill200 = parseInt(document.getElementById('bill-200').value) || 0;
    const bill100 = parseInt(document.getElementById('bill-100').value) || 0;
    const bill50 = parseInt(document.getElementById('bill-50').value) || 0;
    const bill20 = parseInt(document.getElementById('bill-20').value) || 0;

    // Monedas
    const coin10 = parseInt(document.getElementById('coin-10').value) || 0;
    const coin5 = parseInt(document.getElementById('coin-5').value) || 0;
    const coin2 = parseInt(document.getElementById('coin-2').value) || 0;
    const coin1 = parseInt(document.getElementById('coin-1').value) || 0;
    const coin050 = parseInt(document.getElementById('coin-050').value) || 0;
    const otherAmount = parseFloat(document.getElementById('other-amount').value) || 0;

    // Calcular totales por denominación
    const total1000 = bill1000 * 1000;
    const total500 = bill500 * 500;
    const total200 = bill200 * 200;
    const total100 = bill100 * 100;
    const total50 = bill50 * 50;
    const total20 = bill20 * 20;
    const total_coin10 = coin10 * 10;
    const total_coin5 = coin5 * 5;
    const total_coin2 = coin2 * 2;
    const total_coin1 = coin1 * 1;
    const total_coin050 = coin050 * 0.50;

    // Actualizar display de totales individuales
    document.getElementById('total-1000').textContent = '$' + total1000.toLocaleString();
    document.getElementById('total-500').textContent = '$' + total500.toLocaleString();
    document.getElementById('total-200').textContent = '$' + total200.toLocaleString();
    document.getElementById('total-100').textContent = '$' + total100.toLocaleString();
    document.getElementById('total-50').textContent = '$' + total50.toLocaleString();
    document.getElementById('total-20').textContent = '$' + total20.toLocaleString();
    document.getElementById('total-coin-10').textContent = '$' + total_coin10.toLocaleString();
    document.getElementById('total-coin-5').textContent = '$' + total_coin5.toLocaleString();
    document.getElementById('total-coin-2').textContent = '$' + total_coin2.toLocaleString();
    document.getElementById('total-coin-1').textContent = '$' + total_coin1.toLocaleString();
    document.getElementById('total-coin-050').textContent = '$' + total_coin050.toFixed(2);
    document.getElementById('total-other').textContent = '$' + otherAmount.toFixed(2);

    // Calcular total general
    totalCounted = total1000 + total500 + total200 + total100 + total50 + total20 + 
                   total_coin10 + total_coin5 + total_coin2 + total_coin1 + total_coin050 + otherAmount;

    // Actualizar display del total contado
    document.getElementById('counted-amount').textContent = '$' + totalCounted.toFixed(2);

    // Calcular diferencia
    const expectedAmount = {{ ($currentCashCut ? $currentCashCut->initial_amount : 0) + $totalAmount + $totalIncome - $totalExpenses }};
    const difference = totalCounted - expectedAmount;

    document.getElementById('difference-amount').textContent = (difference >= 0 ? '+' : '') + '$' + difference.toFixed(2);

    // Cambiar color según la diferencia
    const differenceDisplay = document.getElementById('difference-display');
    if (difference === 0) {
        differenceDisplay.className = 'text-center p-4 bg-green-50 rounded-lg';
        document.getElementById('difference-amount').className = 'text-lg font-bold text-green-600';
    } else if (difference > 0) {
        differenceDisplay.className = 'text-center p-4 bg-blue-50 rounded-lg';
        document.getElementById('difference-amount').className = 'text-lg font-bold text-blue-600';
    } else {
        differenceDisplay.className = 'text-center p-4 bg-red-50 rounded-lg';
        document.getElementById('difference-amount').className = 'text-lg font-bold text-red-600';
    }
}

function clearAllCounts() {
    if (confirm('¿Estás seguro de que deseas limpiar todos los conteos?')) {
        // Limpiar todos los inputs
        document.getElementById('bill-1000').value = 0;
        document.getElementById('bill-500').value = 0;
        document.getElementById('bill-200').value = 0;
        document.getElementById('bill-100').value = 0;
        document.getElementById('bill-50').value = 0;
        document.getElementById('bill-20').value = 0;
        document.getElementById('coin-10').value = 0;
        document.getElementById('coin-5').value = 0;
        document.getElementById('coin-2').value = 0;
        document.getElementById('coin-1').value = 0;
        document.getElementById('coin-050').value = 0;
        document.getElementById('other-amount').value = 0;
        
        calculateTotal();
    }
}

function applyCountedAmount() {
    // Aplicar el monto contado al input del formulario principal
    document.getElementById('final_amount').value = totalCounted.toFixed(2);
    closeBillCounter();
}

// Cerrar modal al hacer click fuera de él
document.getElementById('bill-counter-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeBillCounter();
    }
});
</script>
@endif

<script>
// JavaScript que siempre debe estar disponible
</script>
@endsection
