@extends('layouts.admin')

@section('title', 'Combos')

@section('content')
<div class="container mx-auto p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 bg-orange-100 p-6 rounded-lg">
        <div>
            <h1 class="text-3xl font-bold text-orange-800">Combos</h1>
            <p class="text-orange-600 mt-1">Gestiona los combos y paquetes especiales</p>
        </div>
        <a href="{{ route('admin.combos.create') }}" class="mt-4 sm:mt-0 bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center">
            <i class="fas fa-plus mr-2"></i>
            Nuevo Combo
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-orange-600">{{ $combos->count() }}</p>
                    <p class="text-gray-600">Total Combos</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-layer-group text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-green-600">{{ $combos->where('is_active', true)->count() }}</p>
                    <p class="text-gray-600">Activos</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-purple-600">{{ $combos->where('auto_suggest', true)->count() }}</p>
                    <p class="text-gray-600">Auto-Sugeridos</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-magic text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-bold text-orange-600">${{ number_format($combos->sum('price'), 2) }}</p>
                    <p class="text-gray-600">Valor Total</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Combos Grid -->
    @if($combos->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($combos as $combo)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <!-- Header del Combo -->
                    <div class="bg-orange-600 p-4 text-white">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-layer-group text-2xl mr-3"></i>
                                <div>
                                    <h3 class="font-bold text-lg">{{ $combo->name }}</h3>
                                    <p class="text-orange-100 text-sm">{{ $combo->description ?? 'Sin descripción' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex flex-col items-end space-y-1">
                                @if($combo->is_active)
                                    <span class="bg-green-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                        Activo
                                    </span>
                                @else
                                    <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                        Inactivo
                                    </span>
                                @endif
                                
                                @if($combo->auto_suggest)
                                    <span class="bg-purple-500 text-white px-2 py-1 rounded-full text-xs font-medium">
                                        Auto-Sugerir
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Productos Incluidos -->
                    <div class="p-4">
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-800 mb-2 flex items-center">
                                <i class="fas fa-box text-orange-600 mr-2"></i>
                                Productos Incluidos
                            </h4>
                            <div class="flex flex-wrap gap-1">
                                @foreach($combo->products as $product)
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                        {{ $product->pivot->quantity }}x {{ $product->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Precios -->
                        <div class="border-t pt-4">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <p class="text-2xl font-bold text-green-600">${{ number_format($combo->price, 2) }}</p>
                                    <p class="text-sm text-gray-500">Precio Combo</p>
                                </div>
                                <div class="text-right">
                                    @php
                                        $totalIndividual = $combo->products->sum(function($product) {
                                            return $product->price * $product->pivot->quantity;
                                        });
                                        $savings = $totalIndividual - $combo->price;
                                        $savingsPercent = $totalIndividual > 0 ? ($savings / $totalIndividual) * 100 : 0;
                                    @endphp
                                    <p class="text-lg font-semibold text-orange-600">${{ number_format($savings, 2) }}</p>
                                    <p class="text-sm text-gray-500">Ahorro ({{ number_format($savingsPercent, 1) }}%)</p>
                                </div>
                            </div>
                            
                            <div class="text-xs text-gray-400 text-center">
                                <p>Precio individual: ${{ number_format($totalIndividual, 2) }}</p>
                            </div>
                        </div>

                        @if($combo->description)
                            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">{{ $combo->description }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Acciones -->
                    <div class="p-4 bg-gray-50 flex items-center justify-center space-x-2">
                        <a href="{{ route('admin.combos.show', $combo) }}" 
                           class="bg-orange-600 hover:bg-orange-700 text-white p-2 rounded-lg transition-colors" 
                           title="Ver">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.combos.edit', $combo) }}" 
                           class="bg-green-600 hover:bg-green-700 text-white p-2 rounded-lg transition-colors" 
                           title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="confirmDelete({{ $combo->id }}, '{{ $combo->name }}')" 
                                class="bg-red-600 hover:bg-red-700 text-white p-2 rounded-lg transition-colors" 
                                title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($combos->hasPages())
            <div class="mt-6">
                {{ $combos->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-layer-group text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay combos registrados</h3>
            <p class="text-gray-500 mb-6">Comienza creando tu primer combo para ofrecer paquetes especiales a tus clientes.</p>
            <a href="{{ route('admin.combos.create') }}" class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Crear mi primer combo
            </a>
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal Script -->
<script>
function confirmDelete(comboId, comboName) {
    if (confirm(`¿Estás seguro de que deseas eliminar el combo "${comboName}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/combos/${comboId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection