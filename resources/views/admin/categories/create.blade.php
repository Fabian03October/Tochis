@extends('layouts.admin')

@section('title', 'Crear Categoría')

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
    <div class="max-w-6xl mx-auto">
        <!-- Header Card -->
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 mb-6">
            <div class="flex items-center">
                <div class="bg-blue-50 rounded-lg p-3 mr-4">
                    <i class="fas fa-plus text-xl text-blue-500"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-800">Crear Nueva Categoría</h1>
                    <p class="text-gray-500 text-sm">Organiza tus productos creando una nueva categoría</p>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Form Section -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="bg-blue-100 rounded-lg p-2 mr-3">
                            <i class="fas fa-info-circle text-blue-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Información de la Categoría</h3>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.categories.store') }}" class="p-4 space-y-4">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-blue-500"></i>
                            Nombre de la Categoría *
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
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

                    <!-- Description -->
                    <div>
                        <label for="description" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-2 text-green-500"></i>
                            Descripción
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('description') border-red-500 @enderror"
                                  placeholder="Describe brevemente esta categoría (opcional)">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="flex items-center mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Color -->
                    <div>
                        <label for="color" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-palette mr-2 text-purple-500"></i>
                            Color de la Categoría *
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="color" 
                                   id="color" 
                                   name="color" 
                                   value="{{ old('color', '#3B82F6') }}"
                                   class="h-10 w-16 border-2 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer @error('color') border-red-500 @enderror">
                            <span class="text-sm text-gray-600">Selecciona un color distintivo</span>
                        </div>
                        @error('color')
                            <p class="flex items-center mt-1 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror

                        <!-- Color Presets -->
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

                    <!-- Personalizable -->
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
                                       {{ old('is_customizable') ? 'checked' : '' }}
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

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save mr-2"></i>
                            Crear Categoría
                        </button>
                    </div>
                </form>
            </div>

            <!-- Preview Section -->
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
                    <!-- Category Preview Card -->
                    <div id="category-preview" class="bg-gradient-to-br from-blue-100 to-indigo-100 border-2 border-blue-200 rounded-xl p-4 transition-all duration-300">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div id="preview-color-indicator" class="w-4 h-4 rounded-full mr-3 border-2 border-white shadow-sm" style="background-color: #3B82F6;"></div>
                                <div>
                                    <h4 id="preview-name" class="text-lg font-bold text-gray-800">Nombre de la Categoría</h4>
                                    <p id="preview-description" class="text-sm text-gray-600">Descripción de la categoría</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div id="preview-customizable" class="hidden bg-orange-100 px-2 py-1 rounded-full">
                                    <span class="text-xs font-semibold text-orange-800">Personalizable</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tips -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <h5 class="flex items-center text-sm font-semibold text-blue-800 mb-2">
                            <i class="fas fa-lightbulb mr-2"></i>
                            Consejos
                        </h5>
                        <ul class="text-xs text-blue-700 space-y-1">
                            <li>• Usa nombres descriptivos y cortos</li>
                            <li>• Elige colores que distingan las categorías</li>
                            <li>• La personalización permite opciones extras</li>
                        </ul>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-gray-50 rounded-lg p-3">
                        <h5 class="flex items-center text-sm font-semibold text-gray-700 mb-3">
                            <i class="fas fa-chart-bar mr-2"></i>
                            Estadísticas Rápidas
                        </h5>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-600">Total Categorías:</span>
                                <span class="text-xs font-semibold text-gray-800">{{ \App\Models\Category::count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-600">Con Personalización:</span>
                                <span class="text-xs font-semibold text-gray-800">{{ \App\Models\Category::where('is_customizable', true)->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-600">Productos Totales:</span>
                                <span class="text-xs font-semibold text-gray-800">{{ \App\Models\Product::count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updatePreview() {
        const name = document.getElementById('name').value || 'Nombre de la Categoría';
        const description = document.getElementById('description').value || 'Descripción de la categoría';
        const color = document.getElementById('color').value || '#3B82F6';
        const isCustomizable = document.getElementById('is_customizable').checked;

        // Update preview elements
        document.getElementById('preview-name').textContent = name;
        document.getElementById('preview-description').textContent = description;
        document.getElementById('preview-color-indicator').style.backgroundColor = color;
        
        // Update category preview background gradient
        const preview = document.getElementById('category-preview');
        const lightColor = color + '20'; // Add transparency
        const borderColor = color + '40';
        preview.style.background = `linear-gradient(135deg, ${lightColor}, ${borderColor})`;
        preview.style.borderColor = color + '60';

        // Update customizable badge
        const customizableBadge = document.getElementById('preview-customizable');
        if (isCustomizable) {
            customizableBadge.classList.remove('hidden');
        } else {
            customizableBadge.classList.add('hidden');
        }
    }

    // Add event listeners
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('name').addEventListener('input', updatePreview);
        document.getElementById('description').addEventListener('input', updatePreview);
        document.getElementById('color').addEventListener('input', updatePreview);
        document.getElementById('is_customizable').addEventListener('change', updatePreview);
        
        // Initial preview update
        updatePreview();
    });
</script>
@endsection
