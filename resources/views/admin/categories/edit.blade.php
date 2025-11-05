@extends('layouts.admin')

{{-- 1. Título de la página (estilo de 'create') --}}
@section('title')
<div>
    <h1 class="text-2xl font-bold text-gray-900">Editar Categoría</h1>
    <p class="text-gray-400 text-sm">Modifica la información de "{{ $category->name }}"</p>
</div>
@endsection

{{-- 2. Estilos de animación (copiados de 'create') --}}
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
{{-- 3. Contenedor principal (estilo de 'create') --}}
<div class="fade-in">
    <div class="max-w-6xl mx-auto">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-blue-100 rounded-lg p-2 mr-3">
                            <i class="fas fa-edit text-blue-600"></i> {{-- Icono de editar --}}
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Información de la Categoría</h3>
                    </div>
                </div>

                {{-- 4. Formulario con padding y espaciado de 'create' --}}
                <form method="POST" action="{{ route('admin.categories.update', $category) }}" class="p-4 space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-blue-500"></i>
                            Nombre de la Categoría *
                        </label>
                        <input type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name', $category->name) }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('name') border-red-500 @enderror"
                                placeholder="Ej: Bebidas, Snacks, Lácteos..."
                                required>
                        @error('name')
                            <p class="flex items-center mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-2 text-green-500"></i>
                            Descripción
                        </label>
                        <textarea id="description" 
                                    name="description" 
                                    rows="3"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('description') border-red-500 @enderror"
                                    placeholder="Describe brevemente esta categoría (opcional)">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <p class="flex items-center mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="color" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-palette mr-2 text-purple-500"></i>
                            Color de la Categoría *
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="color" 
                                    id="color" 
                                    name="color" 
                                    value="{{ old('color', $category->color) }}"
                                    class="h-10 w-16 border-2 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer @error('color') border-red-500 @enderror">
                            <span class="text-sm text-gray-600">Selecciona un color distintivo</span>
                        </div>
                        @error('color')
                            <p class="flex items-center mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror

                        <div class="mt-3">
                            <div class="grid grid-cols-8 gap-2">
                                @php
                                    $presetColors = ['#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#06B6D4', '#84CC16'];
                                @endphp
                                @foreach($presetColors as $preset)
                                    <button type="button" 
                                            class="w-6 h-6 rounded-lg border-2 border-gray-300 hover:border-gray-400 transition duration-200"
                                            style="background-color: {{ $preset }}"
                                            onclick="document.getElementById('color').value = '{{ $preset }}'; updatePreview();">
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- 5. Checkboxes (ambos con el estilo de 'create') --}}
                    <div>
                        <label class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-cog mr-2 text-orange-500"></i>
                            Opciones de Personalización
                        </label>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="flex items-center">
                                <input type="hidden" name="is_customizable" value="0">
                                <input type="checkbox" 
                                        id="is_customizable" 
                                        name="is_customizable" 
                                        value="1"
                                        {{ old('is_customizable', $category->is_customizable) ? 'checked' : '' }}
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label for="is_customizable" class="ml-2 text-sm font-medium text-gray-900 cursor-pointer">
                                    Permitir personalización de productos
                                </label>
                            </div>
                            <p class="text-xs text-gray-600 mt-1">
                                Los productos mostrarán opciones de personalización en el punto de venta.
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-power-off mr-2 text-green-500"></i>
                            Estado
                        </label>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="flex items-center">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" 
                                        id="is_active" 
                                        name="is_active" 
                                        value="1"
                                        {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                        class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 text-sm font-medium text-gray-900 cursor-pointer">
                                    Categoría Activa
                                </label>
                            </div>
                            <p class="text-xs text-gray-600 mt-1">
                                Las categorías desactivadas no aparecerán en el punto de venta.
                            </p>
                        </div>
                    </div>


                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Actualizar Categoría
                        </button>
                    </div>
                </form>
            </div>

            {{-- 6. Preview Section (Mezcla de 'create' y 'edit') --}}
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="bg-green-100 rounded-lg p-2 mr-3">
                                <i class="fas fa-eye text-green-600"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Vista Previa</h3>
                        </div>
                        <div class="bg-blue-100 px-2 py-1 rounded-full">
                            <span class="text-xs font-semibold text-blue-800">Tiempo Real</span>
                        </div>
                    </div>
                </div>

                <div class="p-4 space-y-4">
                    <div id="category-preview" class="bg-gradient-to-br from-blue-100 to-indigo-100 border-2 border-blue-200 rounded-xl p-4 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div id="preview-color-indicator" class="w-4 h-4 rounded-full mr-3 border-2 border-white shadow-sm"></div>
                                <div>
                                    <h4 id="preview-name" class="text-lg font-bold text-gray-800">Nombre</h4>
                                    <p id="preview-description" class="text-sm text-gray-600">Descripción</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                {{-- Badges para el script --}}
                                <div id="preview-customizable" class="hidden bg-orange-100 px-2 py-1 rounded-full">
                                    <span class="text-xs font-semibold text-orange-800">Personalizable</span>
                                </div>
                                <div id="preview-active" class="hidden bg-green-100 px-2 py-1 rounded-full">
                                    <span class="text-xs font-semibold text-green-800">Activa</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-3">
                        <h5 class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Estadísticas de esta Categoría
                        </h5>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-center">
                                <p class="text-xl font-bold text-blue-700">{{ $category->products()->count() }}</p>
                                <p class="text-xs text-blue-600 font-medium">Productos</p>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-center">
                                <p class="text-xl font-bold text-green-700">{{ $category->products()->where('is_active', true)->count() }}</p>
                                <p class="text-xs text-green-600 font-medium">Activos</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-3">
                        <h5 class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-info-circle mr-2"></i>
                            Información
                        </h5>
                        <ul class="text-xs text-gray-600 space-y-1">
                            <li class="flex justify-between"><span>Creación:</span> <span>{{ $category->created_at->format('d/m/Y H:i') }}</span></li>
                            <li class="flex justify-between"><span>Actualización:</span> <span>{{ $category->updated_at->format('d/m/Y H:i') }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 7. SCRIPT (Copiado de 'create' y mejorado para 'edit') --}}
<script>
    function updatePreview() {
        // Obtener valores de los inputs. Usamos los datos de $category como fallback.
        const name = document.getElementById('name').value || '{{ $category->name }}';
        const description = document.getElementById('description').value || '{{ $category->description ?: 'Sin descripción' }}';
        const color = document.getElementById('color').value || '{{ $category->color }}';
        const isCustomizable = document.getElementById('is_customizable').checked;
        const isActive = document.getElementById('is_active').checked; // Añadido campo de 'edit'

        // Actualizar elementos de la vista previa
        document.getElementById('preview-name').textContent = name;
        document.getElementById('preview-description').textContent = description;
        document.getElementById('preview-color-indicator').style.backgroundColor = color;
        
        // Actualizar fondo de la tarjeta de vista previa (la gran función de 'create')
        const preview = document.getElementById('category-preview');
        const lightColor = color + '20'; // Añadir transparencia
        const borderColor = color + '40';
        preview.style.background = `linear-gradient(135deg, ${lightColor}, ${borderColor})`;
        preview.style.borderColor = color + '60';

        // Actualizar badge 'Personalizable'
        const customizableBadge = document.getElementById('preview-customizable');
        if (isCustomizable) {
            customizableBadge.classList.remove('hidden');
        } else {
            customizableBadge.classList.add('hidden');
        }
        
        // Actualizar badge 'Activa' (nuevo)
        const activeBadge = document.getElementById('preview-active');
        if (isActive) {
            activeBadge.classList.remove('hidden');
        } else {
            activeBadge.classList.add('hidden');
        }
    }

    // Añadir event listeners
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('name').addEventListener('input', updatePreview);
        document.getElementById('description').addEventListener('input', updatePreview);
        document.getElementById('color').addEventListener('input', updatePreview);
        document.getElementById('is_customizable').addEventListener('change', updatePreview);
        document.getElementById('is_active').addEventListener('change', updatePreview); // Añadido listener
        
        // Actualización inicial al cargar la página para mostrar los datos de $category
        updatePreview();
    });
</script>
@endsection