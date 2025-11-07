@extends('layouts.app')

@section('title', 'Editar Configuración MercadoPago')

{{-- 1. Título de página estándar --}}
@section('page-title')
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Editar Configuración</h1>
        <p class="text-gray-400 text-sm">Modificando "{{ $setting->name }}"</p>
    </div>
@endsection

{{-- 2. Animación estándar --}}
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
    <!-- 3. Header (Botón Volver) -->
    <div class="flex items-center justify-end mb-6">
        <a href="{{ route('admin.mercadopago.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver a Configuración
        </a>
    </div>

    <!-- 4. Alerta de Errores (Añadida para consistencia) -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        Por favor corrige los siguientes errores:
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 5. Formulario con layout de 2 columnas -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100">
        <form action="{{ route('admin.mercadopago.update', $setting) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Columna Izquierda - Información Básica y Credenciales -->
                <div class="space-y-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Información Básica</h3>
                    
                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre de la Configuración <span class="text-red-500">*</span></label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $setting->name) }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                               placeholder="Ej: Terminal Principal" 
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Modo de Operación -->
                    <div>
                        <label for="is_sandbox" class="block text-sm font-medium text-gray-700 mb-2">Modo de Operación</label>
                        <select id="is_sandbox" 
                                name="is_sandbox"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('is_sandbox') border-red-500 @enderror">
                            <option value="1" {{ old('is_sandbox', $setting->is_sandbox) == '1' ? 'selected' : '' }}>
                                Sandbox (Pruebas)
                            </option>
                            <option value="0" {{ old('is_sandbox', $setting->is_sandbox) == '0' ? 'selected' : '' }}>
                                Producción
                            </option>
                        </select>
                        @error('is_sandbox')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 pt-4">Credenciales de API</h3>

                    <!-- Public Key -->
                    <div>
                        <label for="public_key" class="block text-sm font-medium text-gray-700 mb-2">Public Key <span class="text-red-500">*</span></label>
                        <input type="text" 
                               id="public_key" 
                               name="public_key" 
                               value="{{ old('public_key', $setting->public_key) }}" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('public_key') border-red-500 @enderror"
                               placeholder="TEST-xxx o APP_USR-xxx"
                               required>
                        @error('public_key')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Access Token -->
                    <div>
                        <label for="access_token" class="block text-sm font-medium text-gray-700 mb-2">Access Token <span class="text-danger">*</span></label>
                        <input type="password" 
                               id="access_token" 
                               name="access_token" 
                               value="••••••••" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('access_token') border-red-500 @enderror"
                               placeholder="TEST-xxx o APP_USR-xxx">
                        <small class="text-xs text-gray-500">Deje en blanco o con '••••••••' para mantener el token actual.</small>
                        @error('access_token')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Descripción -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3"
                                  class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                  placeholder="Descripción opcional de la configuración">{{ old('description', $setting->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Columna Derecha - Configuración Terminal y URLs -->
                <div class="space-y-6">
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2">Configuración Terminal (Opcional)</h3>

                    <!-- Tipo de Terminal -->
                    <div>
                        <label for="terminal_type" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Terminal</label>
                        <select id="terminal_type" 
                                name="terminal_type"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('terminal_type') border-red-500 @enderror">
                            <option value="">Seleccionar...</option>
                            <option value="point" {{ old('terminal_type', $setting->terminal_type) == 'point' ? 'selected' : '' }}>MercadoPago Point</option>
                            <option value="smart" {{ old('terminal_type', $setting->terminal_type) == 'smart' ? 'selected' : '' }}>Point Smart</option>
                            <option value="mini" {{ old('terminal_type', $setting->terminal_type) == 'mini' ? 'selected' : '' }}>Point Mini</option>
                            <option value="pro" {{ old('terminal_type', $setting->terminal_type) == 'pro' ? 'selected' : '' }}>Point Pro</option>
                        </select>
                        @error('terminal_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Terminal ID -->
                    <div>
                        <label for="terminal_id" class="block text-sm font-medium text-gray-700 mb-2">ID de Terminal</label>
                        <input type="text" 
                               id="terminal_id" 
                               name="terminal_id" 
                               value="{{ old('terminal_id', $setting->terminal_id) }}" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('terminal_id') border-red-500 @enderror"
                               placeholder="Opcional">
                        @error('terminal_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- POS ID -->
                    <div>
                        <label for="pos_id" class="block text-sm font-medium text-gray-700 mb-2">POS ID</label>
                        <input type="text" 
                               id="pos_id" 
                               name="pos_id" 
                               value="{{ old('pos_id', $setting->pos_id) }}" 
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('pos_id') border-red-500 @enderror"
                               placeholder="Opcional">
                        @error('pos_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 pt-4">Configuración Adicional</h3>
                    
                    <!-- Checkboxes -->
                    <div class="space-y-4">
                        <label class="flex items-center">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" 
                                   id="is_active" 
                                   name="is_active" 
                                   value="1" 
                                   {{ old('is_active', $setting->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm font-medium text-gray-700">Activar esta configuración</span>
                        </label>
                        
                        <label class="flex items-center">
                            <input type="hidden" name="auto_return" value="0">
                            <input type="checkbox" 
                                   id="auto_return" 
                                   name="auto_return" 
                                   value="1" 
                                   {{ old('auto_return', $setting->auto_return) ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm font-medium text-gray-700">Retorno automático después del pago</span>
                        </label>
                    </div>

                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="mt-8 flex items-center justify-end space-x-4 pt-6 border-t">
                <a href="{{ route('admin.mercadopago.index') }}" class="btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Actualizar Configuración
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
{{-- 6. Script actualizado a Vanilla JS --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isSandboxSelect = document.getElementById('is_sandbox');
    const publicKeyInput = document.getElementById('public_key');
    const accessTokenInput = document.getElementById('access_token');
    const form = document.querySelector('form');

    function updatePlaceholders() {
        const isSandbox = isSandboxSelect.value == '1';
        if (isSandbox) {
            publicKeyInput.placeholder = 'TEST-xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
            if (accessTokenInput.value === '••••••••') {
                accessTokenInput.placeholder = 'TEST-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
            }
        } else {
            publicKeyInput.placeholder = 'APP_USR-xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
            if (accessTokenInput.value === '••••••••') {
                accessTokenInput.placeholder = 'APP_USR-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
            }
        }
    }

    // Limpiar campo de token si se enfoca
    accessTokenInput.addEventListener('focus', function() {
        if (this.value === '••••••••') {
            this.value = '';
        }
    });
    
    // Restaurar placeholder si se deja vacío
    accessTokenInput.addEventListener('blur', function() {
        if (this.value === '') {
            this.value = '••••••••';
        }
    });

    // Actualizar placeholders al cambiar el select
    isSandboxSelect.addEventListener('change', updatePlaceholders);
    
    // Actualizar placeholders al cargar la página
    updatePlaceholders();

    // Validación del formulario
    form.addEventListener('submit', function(e) {
        const publicKey = publicKeyInput.value;
        const accessToken = accessTokenInput.value;
        const isSandbox = isSandboxSelect.value == '1';
        let error = false;

        // Solo validar si el token se ha modificado
        if (accessToken && accessToken !== '••••••••') {
            if (isSandbox) {
                if (!publicKey.startsWith('TEST-')) {
                    alert('El Public Key debe comenzar con TEST- en modo sandbox');
                    error = true;
                }
                if (!accessToken.startsWith('TEST-')) {
                    alert('El Access Token debe comenzar con TEST- en modo sandbox');
                    error = true;
                }
            } else {
                if (!publicKey.startsWith('APP_USR-')) {
                    alert('El Public Key debe comenzar con APP_USR- en modo producción');
                    error = true;
                }
                if (!accessToken.startsWith('APP_USR-')) {
                    alert('El Access Token debe comenzar con APP_USR- en modo producción');
                    error = true;
                }
            }
        } else {
            // Si el token es '••••••••' o está vacío, deshabilitarlo para que no se envíe
            accessTokenInput.disabled = true;
        }

        if (error) {
            e.preventDefault(); // Detener el envío del formulario
            return false;
        }
    });
});
</script>
@endpush