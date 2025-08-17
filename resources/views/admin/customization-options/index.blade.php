@extends('layouts.app')

@section('title', 'Opciones de Personalización - Sistema POS')
@section('page-title', 'Opciones de Personalización')

@section('header-actions')
    <a href="{{ route('admin.customization-options.create') }}" class="btn-primary">
        <i class="fas fa-plus mr-2"></i>Nueva Opción
    </a>
@endsection

@section('content')
<div class="fade-in">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Observaciones (Quitar ingredientes) -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-minus-circle text-red-500 mr-2"></i>
                Observaciones (Quitar)
            </h2>
            
            @if($observations->count() > 0)
                <div class="space-y-2">
                    @foreach($observations as $observation)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded border">
                            <div class="flex items-center">
                                <span class="font-medium text-gray-800">{{ $observation->name }}</span>
                                @if(!$observation->is_active)
                                    <span class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Inactivo</span>
                                @endif
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600">Orden: {{ $observation->sort_order }}</span>
                                <a href="{{ route('admin.customization-options.edit', $observation) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.customization-options.destroy', $observation) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800"
                                            onclick="return confirm('¿Estás seguro de que quieres eliminar esta opción?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No hay observaciones configuradas</p>
            @endif
        </div>

        <!-- Especialidades (Agregar ingredientes) -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-plus-circle text-green-500 mr-2"></i>
                Especialidades (Agregar)
            </h2>
            
            @if($specialties->count() > 0)
                <div class="space-y-2">
                    @foreach($specialties as $specialty)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded border">
                            <div class="flex items-center">
                                <span class="font-medium text-gray-800">{{ $specialty->name }}</span>
                                @if($specialty->price > 0)
                                    <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-800 rounded">
                                        +${{ number_format($specialty->price, 2) }}
                                    </span>
                                @endif
                                @if(!$specialty->is_active)
                                    <span class="ml-2 px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Inactivo</span>
                                @endif
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600">Orden: {{ $specialty->sort_order }}</span>
                                <a href="{{ route('admin.customization-options.edit', $specialty) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.customization-options.destroy', $specialty) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800"
                                            onclick="return confirm('¿Estás seguro de que quieres eliminar esta opción?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">No hay especialidades configuradas</p>
            @endif
        </div>
    </div>
</div>
@endsection
