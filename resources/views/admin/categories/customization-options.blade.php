@extends('layouts.app')

@section('title', 'Opciones de Personalización - ' . $category->name . ' - Sistema POS')
@section('page-title', 'Opciones de Personalización: ' . $category->name)

@section('header-actions')
    <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
        <i class="fas fa-arrow-left mr-2"></i>Volver a Categorías
    </a>
@endsection

@section('content')
<div class="fade-in">
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-6">
            <div class="flex items-center mb-4">
                <div class="w-6 h-6 rounded-full mr-3" style="background-color: {{ $category->color }}"></div>
                <h3 class="text-lg font-semibold">{{ $category->name }}</h3>
                @if($category->is_customizable)
                    <span class="ml-3 px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Personalizable</span>
                @else
                    <span class="ml-3 px-2 py-1 text-xs bg-red-100 text-red-800 rounded">No Personalizable</span>
                @endif
            </div>
            <p class="text-gray-600">{{ $category->description ?: 'Sin descripción' }}</p>
        </div>

        @if(!$category->is_customizable)
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-3 mt-1"></i>
                    <div>
                        <h4 class="text-yellow-800 font-medium">Categoría no personalizable</h4>
                        <p class="text-yellow-700 text-sm mt-1">
                            Esta categoría no está marcada como personalizable. 
                            <a href="{{ route('admin.categories.edit', $category) }}" class="underline">Edítala aquí</a> 
                            para habilitar las opciones de personalización.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.categories.update-customization-options', $category) }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Observaciones -->
                <div class="border rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-minus-circle text-red-500 mr-2"></i>
                        Observaciones (Quitar ingredientes)
                    </h4>
                    
                    @php
                        $observations = $allOptions->where('type', 'observation');
                    @endphp
                    
                    @if($observations->count() > 0)
                        <div class="space-y-3">
                            @foreach($observations as $option)
                                <label class="flex items-center p-3 rounded border hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" 
                                           name="customization_options[]" 
                                           value="{{ $option->id }}"
                                           {{ in_array($option->id, $categoryOptions) ? 'checked' : '' }}
                                           class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <div class="flex-1">
                                        <span class="font-medium text-gray-900">{{ $option->name }}</span>
                                        <div class="text-sm text-gray-500">
                                            Orden: {{ $option->sort_order }}
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No hay observaciones disponibles</p>
                        <div class="text-center">
                            <a href="{{ route('admin.customization-options.create') }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-plus mr-1"></i>Crear primera observación
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Especialidades -->
                <div class="border rounded-lg p-4">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-plus-circle text-green-500 mr-2"></i>
                        Especialidades (Agregar ingredientes)
                    </h4>
                    
                    @php
                        $specialties = $allOptions->where('type', 'specialty');
                    @endphp
                    
                    @if($specialties->count() > 0)
                        <div class="space-y-3">
                            @foreach($specialties as $option)
                                <label class="flex items-center p-3 rounded border hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" 
                                           name="customization_options[]" 
                                           value="{{ $option->id }}"
                                           {{ in_array($option->id, $categoryOptions) ? 'checked' : '' }}
                                           class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <div class="flex-1">
                                        <span class="font-medium text-gray-900">{{ $option->name }}</span>
                                        @if($option->price > 0)
                                            <span class="ml-2 px-2 py-1 text-xs bg-green-100 text-green-800 rounded">
                                                +${{ number_format($option->price, 2) }}
                                            </span>
                                        @endif
                                        <div class="text-sm text-gray-500">
                                            Orden: {{ $option->sort_order }}
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No hay especialidades disponibles</p>
                        <div class="text-center">
                            <a href="{{ route('admin.customization-options.create') }}" 
                               class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-plus mr-1"></i>Crear primera especialidad
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        Solo las opciones seleccionadas aparecerán en el modal de personalización para productos de esta categoría.
                    </div>
                    <div class="flex space-x-4">
                        <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
                            <i class="fas fa-times mr-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>Guardar Opciones
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Ayuda -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <i class="fas fa-lightbulb text-blue-600 mr-3 mt-1"></i>
            <div>
                <h4 class="text-blue-800 font-medium">¿Cómo funciona?</h4>
                <ul class="text-blue-700 text-sm mt-2 space-y-1">
                    <li>• Selecciona las opciones que quieres que aparezcan para productos de esta categoría</li>
                    <li>• Las <strong>observaciones</strong> permiten quitar ingredientes (sin costo adicional)</li>
                    <li>• Las <strong>especialidades</strong> permiten agregar ingredientes (pueden tener costo adicional)</li>
                    <li>• Solo productos de categorías marcadas como "personalizables" mostrarán el modal</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
