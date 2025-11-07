@extends('layouts.app') {{-- 1. Layout actualizado --}}

@section('title', 'Configuración MercadoPago')

{{-- 2. Título de página estándar --}}
@section('page-title')
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Configuración MercadoPago</h1>
        <p class="text-gray-400 text-sm">Gestiona tus terminales de pago</p>
    </div>
@endsection

{{-- 3. Animación estándar --}}
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
        <a href="{{ route('admin.mercadopago.create') }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i> Nueva Configuración
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3 text-green-500"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error')) {{-- Alerta de error para la conexión --}}
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    @if($settings->isEmpty())
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-12 text-center">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-credit-card text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay configuraciones de MercadoPago</h3>
            <p class="text-gray-500 mb-6">Configure su terminal de pagos para comenzar a procesar transacciones.</p>
            <a href="{{ route('admin.mercadopago.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i> Crear Primera Configuración
            </a>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Nombre</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Modo</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Terminal</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Conexión</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($settings as $setting)
                            <tr class="hover:bg-gray-50 group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $setting->name }}</div>
                                    @if($setting->is_active)
                                        <div class="text-xs text-green-600">Activo</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($setting->is_sandbox)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Sandbox</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Producción</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($setting->terminal_type)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">{{ ucfirst($setting->terminal_type) }}</span>
                                    @else
                                        <span class="text-sm text-gray-500">No especificado</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($setting->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i> Activo</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"><i class="fas fa-pause mr-1"></i> Inactivo</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800" id="status-{{ $setting->id }}">
                                        <i class="fas fa-question mr-1"></i> Sin verificar
                                    </span>
                                </td>
                                
                                {{-- 8. Botones de acción estándar --}}
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        
                                        <a href="{{ route('admin.mercadopago.show', $setting) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200" title="Ver">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        
                                        <a href="{{ route('admin.mercadopago.edit', $setting) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 hover:bg-indigo-200" title="Editar">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        
                                        <button type="button" 
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 test-connection"
                                                data-id="{{ $setting->id }}" 
                                                title="Probar Conexión">
                                            <i class="fas fa-wifi text-sm"></i>
                                        </button>
                                        
                                        @if(!$setting->is_active)
                                            <button type="button" 
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 text-green-600 hover:bg-green-200 activate-setting"
                                                    data-id="{{ $setting->id }}" 
                                                    title="Activar">
                                                <i class="fas fa-play text-sm"></i>
                                            </button>
                                        @endif
                                        
                                        <button type="button" 
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 delete-setting"
                                                data-id="{{ $setting->id }}" 
                                                data-name="{{ $setting->name }}"
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
        </div>
    @endif
</div>
@endsection

{{-- 9. Script actualizado a Vanilla JS --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = '{{ csrf_token() }}';

    // 1. Probar conexión
    document.querySelectorAll('.test-connection').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const statusBadge = document.getElementById(`status-${id}`);
            
            button.disabled = true;
            statusBadge.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Probando...';
            statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800';

            fetch(`/admin/mercadopago/${id}/test`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    statusBadge.innerHTML = '<i class="fas fa-check mr-1"></i> Conectado';
                    statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
                    alert('Conexión exitosa');
                } else {
                    statusBadge.innerHTML = '<i class="fas fa-times mr-1"></i> Error';
                    statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
                    alert(data.message || 'Error de conexión');
                }
            })
            .catch(() => {
                statusBadge.innerHTML = '<i class="fas fa-times mr-1"></i> Error';
                statusBadge.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
                alert('Error al probar la conexión');
            })
            .finally(() => {
                button.disabled = false;
            });
        });
    });

    // 2. Activar configuración
    document.querySelectorAll('.activate-setting').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            button.disabled = true;

            fetch(`/admin/mercadopago/${id}/activate`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Configuración activada');
                    location.reload();
                } else {
                    alert(data.message || 'Error al activar');
                    button.disabled = false;
                }
            })
            .catch(() => {
                alert('Error al activar la configuración');
                button.disabled = false;
            });
        });
    });

    // 3. Eliminar configuración (usando confirm, como en las otras vistas)
    document.querySelectorAll('.delete-setting').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            
            if (confirm(`¿Estás seguro de que deseas eliminar la configuración "${name}"?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/mercadopago/${id}`;
                
                form.innerHTML = `
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
});
</script>
@endpush