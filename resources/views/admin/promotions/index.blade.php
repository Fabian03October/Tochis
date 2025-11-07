@extends('layouts.app')

@section('title', 'Promociones - Sistema POS')
@section('page-title', 'Gestión de Promociones')

@section('content')
<div class="fade-in">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Promociones</h1>
            <p class="text-gray-600">Gestiona descuentos y ofertas especiales</p>
        </div>
        <a href="{{ route('admin.promotions.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Nueva Promoción
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-percentage text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Promociones Activas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $promotions->where('is_active', true)->where('end_date', '>', now())->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-clock text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Programadas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $promotions->where('start_date', '>', now())->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <i class="fas fa-history text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Expiradas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $promotions->where('end_date', '<', now())->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Promociones</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $promotions->total() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Promotions Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Lista de Promociones</h3>
        </div>
        
        @if($promotions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Promoción
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Descuento
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aplicación
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Período
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Usos
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($promotions as $promotion)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $promotion->name }}</div>
                                        @if($promotion->description)
                                            <div class="text-sm text-gray-500">{{ Str::limit($promotion->description, 50) }}</div>
                                        @endif
                                        <div class="text-xs text-gray-400">
                                            Creado por: {{ $promotion->creator->name }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($promotion->type === 'percentage')
                                            {{ $promotion->discount_value }}%
                                        @else
                                            ${{ number_format($promotion->discount_value, 2) }}
                                        @endif
                                    </div>
                                    @if($promotion->minimum_amount > 0)
                                        <div class="text-xs text-gray-500">
                                            Mín: ${{ number_format($promotion->minimum_amount, 2) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        @if($promotion->apply_to === 'all')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Todos los Platillos
                                            </span>
                                        @elseif($promotion->apply_to === 'category')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Por categoría
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                Platillos específicos
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ $promotion->start_date->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        hasta {{ $promotion->end_date->format('d/m/Y H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $now = now();
                                        $isActive = $promotion->is_active;
                                        $isStarted = $promotion->start_date <= $now;
                                        $isExpired = $promotion->end_date < $now;
                                    @endphp
                                    
                                    @if(!$isActive)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <i class="fas fa-pause mr-1"></i>Pausada
                                        </span>
                                    @elseif($isExpired)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>Expirada
                                        </span>
                                    @elseif(!$isStarted)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Programada
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Activa
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $promotion->uses_count }}</div>
                                    @if($promotion->max_uses)
                                        <div class="text-xs text-gray-500">de {{ $promotion->max_uses }}</div>
                                    @else
                                        <div class="text-xs text-gray-500">ilimitado</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.promotions.show', $promotion) }}" 
                                           class="text-blue-600 hover:text-blue-900" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <a href="{{ route('admin.promotions.edit', $promotion) }}" 
                                           class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        @if(!$isExpired)
                                            <form method="POST" action="{{ route('admin.promotions.toggle-status', $promotion) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" 
                                                        class="text-{{ $promotion->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $promotion->is_active ? 'yellow' : 'green' }}-900" 
                                                        title="{{ $promotion->is_active ? 'Pausar' : 'Activar' }}">
                                                    <i class="fas fa-{{ $promotion->is_active ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form method="POST" action="{{ route('admin.promotions.destroy', $promotion) }}" 
                                              class="inline" 
                                              onsubmit="return confirm('¿Estás seguro de eliminar esta promoción?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900" 
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $promotions->links() }}
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <i class="fas fa-percentage text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay promociones</h3>
                <p class="text-gray-500 mb-4">Comienza creando tu primera promoción para ofrecer descuentos a tus clientes.</p>
                <a href="{{ route('admin.promotions.create') }}" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    Crear Primera Promoción
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
