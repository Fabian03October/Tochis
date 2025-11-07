@extends('layouts.app')

@section('title', 'Opciones de Personalización - Sistema POS')
@section('page-title')
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Opciones de Personalización</h1>
        <p class="text-gray-400 text-sm">Gestiona las opciones de personalización para Platillos</p>
    </div>
@endsection

{{-- 1. Animación (Añadida) --}}
@section('styles')
<style>
    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@section('content')
<div class="fade-in">
    <div class="flex justify-end items-center mb-6">
        <a href="{{ route('admin.customization-options.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Nueva Opción
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3 text-green-500"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <i class="fas fa-cogs text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Opciones</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $observations->count() + $specialties->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg">
                    <i class="fas fa-minus-circle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Observaciones</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $observations->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <i class="fas fa-plus-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Especialidades</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $specialties->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <i class="fas fa-toggle-on text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Activas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $observations->where('is_active', true)->count() + $specialties->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg mr-3">
                        <i class="fas fa-minus-circle text-red-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Observaciones (Quitar ingredientes)</h3>
                </div>
            </div>
            
            @if($observations->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Opción
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Orden
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($observations as $observation)
                                <tr class="hover:bg-gray-50 group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-minus text-red-600 text-sm"></i>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $observation->name }}</div>
                                                <div class="text-sm text-gray-500">Quitar ingrediente</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900">{{ $observation->sort_order }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($observation->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Activa
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Inactiva
                                            </span>
                                        @endif
                                    </td>
                                    
                                    {{-- 4. BOTONES DE ACCIÓN ACTUALIZADOS --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            
                                            <a href="{{ route('admin.customization-options.edit', $observation) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 hover:bg-indigo-200 hover:text-indigo-700 transition duration-200 transform hover:scale-110"
                                               title="Editar">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                            
                                            <button type="button" 
                                                    onclick="confirmDelete('{{ $observation->id }}', '{{ $observation->name }}')"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-700 transition duration-200 transform hover:scale-110"
                                                    title="Eliminar">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-minus-circle text-3xl text-red-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay observaciones</h3>
                    <p class="text-gray-500 mb-6">Aún no has creado ninguna opción para quitar ingredientes.</p>
                    <a href="{{ route('admin.customization-options.create') }}" class="btn-primary">
                        <i class="fas fa-plus mr-2"></i>
                        Nueva Observación
                    </a>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg mr-3">
                        <i class="fas fa-plus-circle text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Especialidades (Agregar ingredientes)</h3>
                </div>
            </div>
            
            @if($specialties->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Opción
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Precio
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Orden
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($specialties as $specialty)
                                <tr class="hover:bg-gray-50 group">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-plus text-green-600 text-sm"></i>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $specialty->name }}</div>
                                                <div class="text-sm text-gray-500">Agregar ingrediente</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($specialty->price > 0)
                                            <span class="text-sm font-medium text-green-600">
                                                +${{ number_format($specialty->price, 2) }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500">Gratis</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900">{{ $specialty->sort_order }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($specialty->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Activa
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Inactiva
                                            </span>
                                        @endif
                                    </td>
                                    
                                    {{-- 6. BOTONES DE ACCIÓN ACTUALIZADOS --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            
                                            <a href="{{ route('admin.customization-options.edit', $specialty) }}" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 hover:bg-indigo-200 hover:text-indigo-700 transition duration-200 transform hover:scale-110"
                                               title="Editar">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                            
                                            <button type="button" 
                                                    onclick="confirmDelete('{{ $specialty->id }}', '{{ $specialty->name }}')"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-700 transition duration-200 transform hover:scale-110"
                                                    title="Eliminar">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-plus-circle text-3xl text-green-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay especialidades</h3>
                    <p class="text-gray-500 mb-6">Aún no has creado ninguna opción para agregar ingredientes.</p>
                    <a href="{{ route('admin.customization-options.create') }}" class="btn-primary">
                        <i class="fas fa-plus mr-2"></i>
                        Nueva Especialidad
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function confirmDelete(optionId, optionName) {
    if (confirm(`¿Estás seguro de que deseas eliminar la opción "${optionName}"?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/customization-options/${optionId}`;
        
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