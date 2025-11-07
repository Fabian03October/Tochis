@extends('layouts.admin')

@section('title', 'Configuración de Impresoras')

@section('content')
<div>
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">🖨️ Configuración de Impresoras Térmicas</h1>
            <p class="text-gray-600 text-sm">Administra las impresoras para tickets de venta</p>
        </div>
        <a href="{{ route('admin.printers.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Nueva Impresora
        </a>
    </div>

    @if($printers->count() > 0)
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-print mr-2 text-blue-600"></i>
                    Impresoras Configuradas
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Impresora
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Conexión
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Configuración
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($printers as $printer)
                            <tr class="hover:bg-gray-50 {{ $printer->is_active ? 'bg-green-50 border-l-4 border-green-500' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($printer->is_active)
                                            <div class="flex-shrink-0 w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                        @else
                                            <div class="flex-shrink-0 w-3 h-3 bg-gray-300 rounded-full mr-3"></div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $printer->name }}
                                                @if($printer->is_active)
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Activa
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ App\Models\PrinterSetting::getSupportedModels()[$printer->model] ?? $printer->model }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <i class="fas fa-{{ $printer->connection_type === 'usb' ? 'usb' : ($printer->connection_type === 'network' ? 'wifi' : 'bluetooth') }} mr-1"></i>
                                        {{ App\Models\PrinterSetting::getConnectionTypes()[$printer->connection_type] }}
                                    </div>
                                    @if($printer->connection_string)
                                        <div class="text-sm text-gray-500">{{ $printer->connection_string }}</div>
                                    @endif
                                    @if($printer->port)
                                        <div class="text-sm text-gray-500">Puerto: {{ $printer->port }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div>{{ $printer->paper_width }}</div>
                                    <div>{{ $printer->characters_per_line }} chars/línea</div>
                                    @if($printer->auto_cut)
                                        <div class="text-green-600"><i class="fas fa-check mr-1"></i>Corte automático</div>
                                    @endif
                                    @if($printer->cash_drawer)
                                        <div class="text-blue-600"><i class="fas fa-cash-register mr-1"></i>Cajón conectado</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'connected' => ['bg-green-100', 'text-green-800', 'Conectada'],
                                            'disconnected' => ['bg-red-100', 'text-red-800', 'Desconectada'],
                                            'error' => ['bg-red-100', 'text-red-800', 'Error'],
                                            'unknown' => ['bg-gray-100', 'text-gray-800', 'Desconocido']
                                        ];
                                        $status = $statusColors[$printer->status] ?? $statusColors['unknown'];
                                    @endphp
                                    
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $status[0] }} {{ $status[1] }}">
                                        {{ $status[2] }}
                                    </span>
                                    
                                    @if($printer->last_test)
                                        <div class="text-xs text-gray-500 mt-1">
                                            Última prueba: {{ $printer->last_test->diffForHumans() }}
                                        </div>
                                    @endif
                                    
                                    @if(!$printer->is_enabled)
                                        <div class="text-xs text-red-500 mt-1">
                                            <i class="fas fa-ban mr-1"></i>Deshabilitada
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <div class="flex space-x-2">
                                        <!-- Test de conexión -->
                                        <form method="POST" action="{{ route('admin.printers.test', $printer) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="btn-secondary btn-sm" title="Probar conexión">
                                                <i class="fas fa-plug"></i>
                                            </button>
                                        </form>

                                        <!-- Ticket de prueba -->
                                        <form method="POST" action="{{ route('admin.printers.print-test', $printer) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="btn-warning btn-sm" title="Imprimir prueba">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </form>

                                        <!-- Activar/Desactivar -->
                                        @if(!$printer->is_active && $printer->is_enabled)
                                            <form method="POST" action="{{ route('admin.printers.activate', $printer) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="btn-success btn-sm" title="Activar como predeterminada">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <!-- Editar -->
                                        <a href="{{ route('admin.printers.edit', $printer) }}" class="btn-primary btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Eliminar -->
                                        @if(!$printer->is_active)
                                            <form method="POST" action="{{ route('admin.printers.destroy', $printer) }}" class="inline" 
                                                  onsubmit="return confirm('¿Estás seguro de eliminar esta impresora?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger btn-sm" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-12 text-center">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-print text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay impresoras configuradas</h3>
            <p class="text-gray-500 mb-6">Configura tu primera impresora térmica para comenzar a imprimir tickets de venta.</p>
            <a href="{{ route('admin.printers.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Configurar Primera Impresora
            </a>
        </div>
    @endif

    <!-- Información adicional -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Información sobre Impresoras Térmicas</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Solo una impresora puede estar activa a la vez</li>
                        <li>Las impresoras deshabilitadas no aparecerán como opciones de impresión</li>
                        <li>Realiza pruebas de conexión regularmente para verificar el funcionamiento</li>
                        <li>Los modelos compatibles incluyen ESC/POS estándar</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection