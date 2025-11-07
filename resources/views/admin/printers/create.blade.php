@extends('layouts.app')

@section('title', 'Nueva Impresora')

@section('content')
<div>
    <div class="mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.printers.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">🖨️ Configurar Nueva Impresora</h1>
                <p class="text-gray-600 text-sm">Agrega una nueva impresora térmica al sistema</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-cog mr-2 text-blue-600"></i>
                Configuración de Impresora
            </h3>
        </div>

        <form method="POST" action="{{ route('admin.printers.store') }}" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Información Básica -->
                <div class="space-y-4">
                    <h4 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">
                        Información Básica
                    </h4>
                    
                    <!-- Nombre -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre de la Impresora *
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name"
                               value="{{ old('name') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ej: Impresora Principal"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Modelo -->
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-2">
                            Modelo de Impresora *
                        </label>
                        <select name="model" 
                                id="model"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                required>
                            <option value="">Seleccionar modelo...</option>
                            @foreach(App\Models\PrinterSetting::getSupportedModels() as $key => $model)
                                <option value="{{ $key }}" {{ old('model') === $key ? 'selected' : '' }}>
                                    {{ $model }}
                                </option>
                            @endforeach
                        </select>
                        @error('model')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ancho del Papel -->
                    <div>
                        <label for="paper_width" class="block text-sm font-medium text-gray-700 mb-2">
                            Ancho del Papel *
                        </label>
                        <select name="paper_width" 
                                id="paper_width"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                required>
                            @foreach(App\Models\PrinterSetting::getPaperWidths() as $key => $width)
                                <option value="{{ $key }}" {{ old('paper_width', '80mm') === $key ? 'selected' : '' }}>
                                    {{ $width }}
                                </option>
                            @endforeach
                        </select>
                        @error('paper_width')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Caracteres por Línea -->
                    <div>
                        <label for="characters_per_line" class="block text-sm font-medium text-gray-700 mb-2">
                            Caracteres por Línea *
                        </label>
                        <input type="number" 
                               name="characters_per_line" 
                               id="characters_per_line"
                               value="{{ old('characters_per_line', 48) }}"
                               min="20"
                               max="80"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               required>
                        <p class="mt-1 text-sm text-gray-500">Normalmente 32 para 58mm, 48 para 80mm</p>
                        @error('characters_per_line')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Configuración de Conexión -->
                <div class="space-y-4">
                    <h4 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">
                        Configuración de Conexión
                    </h4>
                    
                    <!-- Tipo de Conexión -->
                    <div>
                        <label for="connection_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Conexión *
                        </label>
                        <select name="connection_type" 
                                id="connection_type"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                required>
                            @foreach(App\Models\PrinterSetting::getConnectionTypes() as $key => $type)
                                <option value="{{ $key }}" {{ old('connection_type', 'usb') === $key ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('connection_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- String de Conexión -->
                    <div id="connection_string_field">
                        <label for="connection_string" class="block text-sm font-medium text-gray-700 mb-2">
                            <span id="connection_string_label">Puerto/Dirección</span>
                        </label>
                        <input type="text" 
                               name="connection_string" 
                               id="connection_string"
                               value="{{ old('connection_string') }}"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Ej: COM1, /dev/usb/lp0, 192.168.1.100">
                        <p class="mt-1 text-sm text-gray-500" id="connection_string_help">
                            <!-- Se actualizará con JavaScript -->
                        </p>
                        @error('connection_string')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Puerto (solo para red) -->
                    <div id="port_field" style="display: none;">
                        <label for="port" class="block text-sm font-medium text-gray-700 mb-2">
                            Puerto de Red
                        </label>
                        <input type="number" 
                               name="port" 
                               id="port"
                               value="{{ old('port', 9100) }}"
                               min="1"
                               max="65535"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <p class="mt-1 text-sm text-gray-500">Puerto estándar para impresoras térmicas: 9100</p>
                        @error('port')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Opciones Adicionales -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-lg font-medium text-gray-900 mb-4">
                    Opciones Adicionales
                </h4>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Corte Automático -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="auto_cut" 
                               id="auto_cut"
                               value="1"
                               {{ old('auto_cut', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="auto_cut" class="ml-2 block text-sm text-gray-700">
                            <i class="fas fa-cut mr-1 text-blue-600"></i>
                            Corte automático de papel
                        </label>
                    </div>

                    <!-- Cajón de Dinero -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="cash_drawer" 
                               id="cash_drawer"
                               value="1"
                               {{ old('cash_drawer') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="cash_drawer" class="ml-2 block text-sm text-gray-700">
                            <i class="fas fa-cash-register mr-1 text-green-600"></i>
                            Cajón de dinero conectado
                        </label>
                    </div>

                    <!-- Habilitada -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="is_enabled" 
                               id="is_enabled"
                               value="1"
                               {{ old('is_enabled', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_enabled" class="ml-2 block text-sm text-gray-700">
                            <i class="fas fa-power-off mr-1 text-green-600"></i>
                            Impresora habilitada
                        </label>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.printers.index') }}" class="btn-secondary">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Guardar Configuración
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const connectionType = document.getElementById('connection_type');
    const connectionStringField = document.getElementById('connection_string_field');
    const connectionStringLabel = document.getElementById('connection_string_label');
    const connectionStringHelp = document.getElementById('connection_string_help');
    const connectionStringInput = document.getElementById('connection_string');
    const portField = document.getElementById('port_field');
    const charactersPerLine = document.getElementById('characters_per_line');
    const paperWidth = document.getElementById('paper_width');

    function updateConnectionFields() {
        const type = connectionType.value;
        
        // Mostrar/ocultar campo de puerto
        if (type === 'network') {
            portField.style.display = 'block';
        } else {
            portField.style.display = 'none';
        }

        // Actualizar etiquetas y ayuda según el tipo de conexión
        switch (type) {
            case 'usb':
                connectionStringLabel.textContent = 'Puerto USB';
                connectionStringHelp.textContent = 'Ej: COM1 (Windows), /dev/usb/lp0 (Linux)';
                connectionStringInput.placeholder = 'COM1';
                break;
            case 'network':
                connectionStringLabel.textContent = 'Dirección IP';
                connectionStringHelp.textContent = 'Dirección IP de la impresora en la red';
                connectionStringInput.placeholder = '192.168.1.100';
                break;
            case 'bluetooth':
                connectionStringLabel.textContent = 'Dirección MAC';
                connectionStringHelp.textContent = 'Dirección MAC del dispositivo Bluetooth';
                connectionStringInput.placeholder = '00:11:22:33:44:55';
                break;
            case 'serial':
                connectionStringLabel.textContent = 'Puerto Serial';
                connectionStringHelp.textContent = 'Ej: COM1 (Windows), /dev/ttyS0 (Linux)';
                connectionStringInput.placeholder = 'COM1';
                break;
        }
    }

    function updateCharactersPerLine() {
        const width = paperWidth.value;
        if (width === '58mm') {
            charactersPerLine.value = 32;
        } else if (width === '80mm') {
            charactersPerLine.value = 48;
        }
    }

    connectionType.addEventListener('change', updateConnectionFields);
    paperWidth.addEventListener('change', updateCharactersPerLine);

    // Inicializar
    updateConnectionFields();
});
</script>
@endsection