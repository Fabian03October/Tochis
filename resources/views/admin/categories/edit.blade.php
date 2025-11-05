@extends('layouts.admin')

@section('title', 'Editar Categoría')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Editar Categoría</h1>
                <p class="text-gray-600 text-sm mt-1">Modifica la información de "{{ $category->name }}"</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-lg p-2 mr-3">
                        <i class="fas fa-edit text-blue-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Información de la Categoría</h3>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.categories.update', $category) }}" id="category-form" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div>
                    <label for="name" class="flex items-center text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-tag mr-2 text-blue-500"></i>
                        Nombre de la Categoría *
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $category->name) }}"
                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('name') border-red-500 @enderror"
                           placeholder="Ej: Bebidas, Snacks, Lácteos..."
                           required>
                    @error('name')
                        <p class="flex items-center mt-2 text-sm text-red-600">
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
                              class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 @error('description') border-red-500 @enderror"
                              placeholder="Describe brevemente esta categoría (opcional)">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="flex items-center mt-2 text-sm text-red-600">
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
                               value="{{ old('color', $category->color) }}"
                               class="h-12 w-20 border-2 border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer @error('color') border-red-500 @enderror">
                        <span class="text-sm text-gray-600">Selecciona un color distintivo</span>
                    </div>
                    @error('color')
                        <p class="flex items-center mt-2 text-sm text-red-600">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            {{ $message }}
                        </p>
                    @enderror

                    <!-- Color Presets -->
                    <div class="mt-3">
                        <p class="text-sm text-gray-600 mb-2">Colores predefinidos:</p>
                        <div class="grid grid-cols-8 gap-2">
                            @php
                                $presetColors = ['#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#06B6D4', '#84CC16'];
                            @endphp
                            @foreach($presetColors as $preset)
                                <button type="button" 
                                        class="w-8 h-8 rounded-lg border-2 {{ $preset === $category->color ? 'border-gray-800' : 'border-gray-300' }} hover:border-gray-400 transition duration-200 transform hover:scale-110"
                                        style="background-color: {{ $preset }}"
                                        onclick="document.getElementById('color').value = '{{ $preset }}'; updatePreview();">
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Checkboxes -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Estado -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" 
                                   id="is_active"
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="is_active" class="ml-3 text-sm font-medium text-gray-900 cursor-pointer">
                                <i class="fas fa-power-off mr-2 text-green-500"></i>
                                Categoría activa
                            </label>
                        </div>
                        <p class="text-xs text-gray-600 mt-2 ml-7">
                            Las categorías desactivadas no aparecerán en el punto de venta.
                        </p>
                    </div>

                    <!-- Personalizable -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <input type="hidden" name="is_customizable" value="0">
                            <input type="checkbox" 
                                   id="is_customizable" 
                                   name="is_customizable" 
                                   value="1"
                                   {{ old('is_customizable', $category->is_customizable) ? 'checked' : '' }}
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="is_customizable" class="ml-3 text-sm font-medium text-gray-900 cursor-pointer">
                                <i class="fas fa-cog mr-2 text-gray-500"></i>
                                Permitir personalización
                            </label>
                        </div>
                        <p class="text-xs text-gray-600 mt-2 ml-7">
                            Los productos mostrarán opciones de personalización.
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.categories.index') }}" class="btn-secondary">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        Actualizar Categoría
                    </button>
                </div>
            </form>
        </div>

        <!-- Preview Section -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-lg p-2 mr-3">
                        <i class="fas fa-eye text-green-600"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Vista Previa</h3>
                </div>
            </div>

            <div class="p-6">
                <!-- Preview Card -->
                <div id="category-preview" class="bg-gradient-to-br from-gray-50 to-gray-100 border-2 border-gray-200 rounded-xl p-4 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div id="preview-color-indicator" class="w-4 h-4 rounded-full mr-3 border border-white shadow-sm" style="background-color: {{ $category->color }};"></div>
                            <div>
                                <h4 id="preview-name" class="text-lg font-bold text-gray-800">{{ $category->name }}</h4>
                                <p id="preview-description" class="text-sm text-gray-600">{{ $category->description ?: 'Sin descripción' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($category->is_active)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Activa
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Inactiva
                                </span>
                            @endif
                            @if($category->is_customizable)
                                <span id="preview-customizable" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-cog mr-1"></i>
                                    Personalizable
                                </span>
                            @else
                                <span id="preview-customizable" class="hidden inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-cog mr-1"></i>
                                    Personalizable
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="mt-6 space-y-4">
                    <h5 class="flex items-center text-sm font-semibold text-gray-700">
                        <i class="fas fa-chart-bar mr-2 text-blue-500"></i>
                        Estadísticas Actuales
                    </h5>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-2xl font-bold text-blue-700">{{ $category->products()->count() }}</p>
                                    <p class="text-sm text-blue-600 font-medium">Productos</p>
                                </div>
                                <div class="bg-blue-200 rounded-lg p-2">
                                    <i class="fas fa-box text-blue-700"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-green-50 to-green-100 p-4 rounded-xl border border-green-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-2xl font-bold text-green-700">{{ $category->products()->where('is_active', true)->count() }}</p>
                                    <p class="text-sm text-green-600 font-medium">Activos</p>
                                </div>
                                <div class="bg-green-200 rounded-lg p-2">
                                    <i class="fas fa-check-circle text-green-700"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h6 class="flex items-center text-xs font-semibold text-gray-700 mb-3">
                            <i class="fas fa-info-circle mr-2"></i>
                            Información de la Categoría
                        </h6>
                        <div class="text-xs text-gray-600 space-y-1">
                            <div class="flex justify-between">
                                <span>Fecha de creación:</span>
                                <span>{{ $category->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Última actualización:</span>
                                <span>{{ $category->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updatePreview() {
    const nameInput = document.getElementById('name');
    const colorInput = document.getElementById('color');
    const descriptionInput = document.getElementById('description');
    const customizableInput = document.getElementById('is_customizable');
    
    // Update preview name
    const previewName = document.getElementById('preview-name');
    if (nameInput && previewName) {
        previewName.textContent = nameInput.value || '{{ $category->name }}';
    }
    
    // Update preview color
    const previewColor = document.getElementById('preview-color-indicator');
    if (colorInput && previewColor) {
        previewColor.style.backgroundColor = colorInput.value;
    }
    
    // Update preview description
    const previewDescription = document.getElementById('preview-description');
    if (descriptionInput && previewDescription) {
        previewDescription.textContent = descriptionInput.value || 'Sin descripción';
    }
    
    // Update customizable badge
    const previewCustomizable = document.getElementById('preview-customizable');
    if (customizableInput && previewCustomizable) {
        if (customizableInput.checked) {
            previewCustomizable.classList.remove('hidden');
        } else {
            previewCustomizable.classList.add('hidden');
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const colorInput = document.getElementById('color');
    const descriptionInput = document.getElementById('description');
    const customizableInput = document.getElementById('is_customizable');
    
    if (nameInput) {
        nameInput.addEventListener('input', updatePreview);
    }
    
    if (colorInput) {
        colorInput.addEventListener('input', updatePreview);
    }
    
    if (descriptionInput) {
        descriptionInput.addEventListener('input', updatePreview);
    }
    
    if (customizableInput) {
        customizableInput.addEventListener('change', updatePreview);
    }
});
</script>
@endpush
