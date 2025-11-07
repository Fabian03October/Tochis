@extends('layouts.app')

@section('title', 'Gestión de Combos - TOCHIS')
@section('page-title', 'Gestión de Combos')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="hover:text-orange-500">Dashboard</a>
    <span class="mx-2">/</span>
    <span class="text-orange-500">Combos</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.combos.create') }}" 
       class="btn-primary">
        <i class="fas fa-plus mr-2"></i>Crear Combo
    </a>
@endsection

@push('styles')
<style>
    .combo-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(249, 115, 22, 0.1);
        transition: all 0.3s ease;
    }
    
    .combo-card:hover {
        box-shadow: 0 8px 30px rgba(249, 115, 22, 0.15);
        transform: translateY(-2px);
    }
    
    .combo-header {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        border-radius: 16px 16px 0 0;
    }
    
    .product-badge {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border-radius: 20px;
        padding: 4px 12px;
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
        margin: 2px;
        display: inline-block;
    }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .status-active {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .status-inactive {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    .auto-suggest-badge {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.65rem;
        font-weight: 600;
    }
    
    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }
    
    .action-btn:hover {
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')
<div class="fade-in">
    @if($combos->count() > 0)
        <!-- Grid de Combos -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($combos as $combo)
                <div class="combo-card">
                    <!-- Header del Combo -->
                    <div class="combo-header p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-box-open text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-white font-bold text-lg">{{ $combo->name }}</h3>
                                    <p class="text-orange-100 text-sm">{{ Str::limit($combo->description, 30) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="status-badge {{ $combo->is_active ? 'status-active' : 'status-inactive' }}">
                                    {{ $combo->is_active ? 'Activo' : 'Inactivo' }}
                                </div>
                                @if($combo->auto_suggest)
                                    <div class="auto-suggest-badge mt-1">
                                        Auto-Sugerir
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contenido del Combo -->
                    <div class="p-6">
                        <!-- Platillos del Combo -->
                        <div class="mb-4">
                            <h4 class="text-sm font-bold text-gray-700 mb-2 flex items-center">
                                <i class="fas fa-utensils mr-2 text-orange-500"></i>
                                Platillos Incluidos
                            </h4>
                            <div class="flex flex-wrap">
                                @foreach($combo->products as $product)
                                    <span class="product-badge">
                                        {{ $product->pivot->quantity }}x {{ Str::limit($product->name, 15) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Precios y Ahorro -->
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="bg-green-50 rounded-lg p-3 text-center">
                                <div class="text-2xl font-bold text-green-600">${{ number_format($combo->price, 2) }}</div>
                                <div class="text-xs text-green-700 font-medium">Precio Combo</div>
                                <div class="text-xs text-gray-500 line-through">${{ number_format($combo->original_price, 2) }}</div>
                            </div>
                            <div class="bg-orange-50 rounded-lg p-3 text-center">
                                <div class="text-2xl font-bold text-orange-600">${{ number_format($combo->savings, 2) }}</div>
                                <div class="text-xs text-orange-700 font-medium">Ahorro Total</div>
                                <div class="text-xs text-gray-600">({{ $combo->discount_percentage }}% descuento)</div>
                            </div>
                        </div>
                        
                        <!-- Descripción Completa -->
                        @if(strlen($combo->description) > 30)
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 bg-gray-50 rounded-lg p-3">
                                    {{ $combo->description }}
                                </p>
                            </div>
                        @endif
                        
                        <!-- Acciones -->
                        <div class="flex justify-center space-x-2 pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.combos.show', $combo) }}" 
                               class="action-btn bg-blue-500 hover:bg-blue-600 text-white" 
                               title="Ver Detalles">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            <a href="{{ route('admin.combos.edit', $combo) }}" 
                               class="action-btn bg-orange-500 hover:bg-orange-600 text-white" 
                               title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <form action="{{ route('admin.combos.toggle-status', $combo) }}" 
                                  method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="action-btn bg-green-500 hover:bg-green-600 text-white" 
                                        title="{{ $combo->is_active ? 'Desactivar' : 'Activar' }}">
                                    <i class="fas fa-toggle-{{ $combo->is_active ? 'on' : 'off' }}"></i>
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.combos.destroy', $combo) }}" 
                                  method="POST" class="inline" 
                                  onsubmit="return confirm('¿Estás seguro de eliminar este combo?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="action-btn bg-red-500 hover:bg-red-600 text-white" 
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Paginación -->
        @if($combos->hasPages())
            <div class="mt-8 flex justify-center">
                <div class="bg-white rounded-lg shadow-md p-4">
                    {{ $combos->links() }}
                </div>
            </div>
        @endif
    @else
        <!-- Estado Vacío -->
        <div class="text-center py-16">
            <div class="bg-white rounded-xl shadow-lg p-12 max-w-md mx-auto">
                <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-box-open text-orange-500 text-4xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">No hay combos creados</h3>
                <p class="text-gray-600 mb-8">Crea tu primer combo para empezar a ofrecer promociones atractivas a tus clientes.</p>
                <a href="{{ route('admin.combos.create') }}" 
                   class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>Crear Primer Combo
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
