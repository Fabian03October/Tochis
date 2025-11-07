@extends('layouts.app')

@section('title', 'Ver Promoción - Sistema POS')
@section('page-title', 'Detalle de Promoción')

@section('content')
<div class="fade-in">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $promotion->name }}</h1>
            <p class="text-gray-600">Detalles de la promoción</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn-primary">
                <i class="fas fa-edit mr-2"></i>
                Editar
            </a>
            <a href="{{ route('admin.promotions.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver
            </a>
        </div>
    </div>

    <!-- Promotion Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Información General</h3>
                
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nombre</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $promotion->name }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Tipo de Descuento</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($promotion->type === 'percentage')
                                Porcentaje ({{ $promotion->discount_value }}%)
                            @else
                                Monto Fijo (${{ number_format($promotion->discount_value, 2) }})
                            @endif
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Se Aplica a</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($promotion->apply_to === 'all')
                                Todos los Platillos
                            @elseif($promotion->apply_to === 'category')
                                Categorías específicas
                            @else
                                Platillos específicos
                            @endif
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Monto Mínimo</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($promotion->minimum_amount > 0)
                                ${{ number_format($promotion->minimum_amount, 2) }}
                            @else
                                Sin mínimo
                            @endif
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Máximo de Usos</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            @if($promotion->max_uses)
                                {{ $promotion->max_uses }} usos
                            @else
                                Ilimitado
                            @endif
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Usos Actuales</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $promotion->uses_count }}</dd>
                    </div>
                    
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500">Descripción</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $promotion->description ?: 'Sin descripción' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Status & Timing -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Estado</h3>
                
                @php
                    $now = now();
                    $isActive = $promotion->is_active;
                    $isStarted = $promotion->start_date <= $now;
                    $isExpired = $promotion->end_date < $now;
                @endphp
                
                <div class="text-center">
                    @if(!$isActive)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-pause mr-2"></i>Pausada
                        </span>
                    @elseif($isExpired)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times mr-2"></i>Expirada
                        </span>
                    @elseif(!$isStarted)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-clock mr-2"></i>Programada
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check mr-2"></i>Activa
                        </span>
                    @endif
                </div>
            </div>

            <!-- Timing Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Programación</h3>
                
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Fecha de Inicio</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $promotion->start_date->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Fecha de Fin</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $promotion->end_date->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Duración</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $promotion->start_date->diffInDays($promotion->end_date) }} días
                        </dd>
                    </div>
                    
                    @if(!$isExpired && $isStarted)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Tiempo Restante</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $promotion->end_date->diffForHumans() }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

            <!-- Creator Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Información de Creación</h3>
                
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Creado por</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $promotion->creator->name }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Fecha de Creación</dt>
                        <dd class="mt-1 text-sm text-gray-900">
                            {{ $promotion->created_at->format('d/m/Y H:i') }}
                        </dd>
                    </div>
                    
                    @if($promotion->updated_at != $promotion->created_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $promotion->updated_at->format('d/m/Y H:i') }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
