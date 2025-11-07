@extends('layouts.app')

@section('title', 'Detalles Configuración MercadoPago')

{{-- 1. Título de página estándar --}}
@section('page-title')
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Detalles: {{ $setting->name }}</h1>
        <p class="text-gray-400 text-sm">Información de la configuración de MercadoPago</p>
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
    /* Estilo para las listas de detalles */
    .detail-row {
        @apply flex justify-between items-center py-3 px-4 border-b border-gray-100;
    }
    .detail-label {
        @apply text-sm font-medium text-gray-500;
    }
    .detail-value {
        @apply text-sm font-semibold text-gray-900 text-right;
    }
    .detail-badge {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
    }
</style>
@endsection

@section('content')
<div class="fade-in">
    <div class="flex items-center justify-end space-x-3 mb-6">
        <a href="{{ route('admin.mercadopago.edit', $setting) }}" class="btn-primary">
            <i class="fas fa-edit mr-2"></i> Editar
        </a>
        <a href="{{ route('admin.mercadopago.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i> Volver
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3 text-green-500"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                        Información Básica
                    </h3>
                </div>
                <dl>
                    <div class="detail-row">
                        <dt class="detail-label">Nombre:</dt>
                        <dd class="detail-value">{{ $setting->name }}</dd>
                    </div>
                    <div class="detail-row">
                        <dt class="detail-label">Modo:</dt>
                        <dd class="detail-value">
                            @if($setting->is_sandbox)
                                <span class="detail-badge bg-yellow-100 text-yellow-800">Sandbox (Pruebas)</span>
                            @else
                                <span class="detail-badge bg-green-100 text-green-800">Producción</span>
                            @endif
                        </dd>
                    </div>
                    <div class="detail-row">
                        <dt class="detail-label">Estado:</dt>
                        <dd class="detail-value">
                            @if($setting->is_active)
                                <span class="detail-badge bg-green-100 text-green-800"><i class="fas fa-check mr-1"></i> Activo</span>
                            @else
                                <span class="detail-badge bg-gray-100 text-gray-800"><i class="fas fa-pause mr-1"></i> Inactivo</span>
                            @endif
                        </dd>
                    </div>
                    <div class="detail-row">
                        <dt class="detail-label">Creado:</dt>
                        <dd class="detail-value">{{ $setting->created_at->format('d/m/Y H:i:s') }}</dd>
                    </div>
                    <div class="detail-row">
                        <dt class="detail-label">Actualizado:</dt>
                        <dd class="detail-value">{{ $setting->updated_at->format('d/m/Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-key mr-2 text-yellow-500"></i>
                        Credenciales API
                    </h3>
                </div>
                <dl>
                    <div class="detail-row">
                        <dt class="detail-label">Public Key:</dt>
                        <dd class="detail-value font-mono">
                            {{ Str::mask($setting->public_key, '*', 8, -8) }}
                            <button class="ml-2 text-gray-400 hover:text-blue-600" onclick="copyToClipboard('{{ $setting->public_key }}')" title="Copiar">
                                <i class="fas fa-copy"></i>
                            </button>
                        </dd>
                    </div>
                    <div class="detail-row">
                        <dt class="detail-label">Access Token:</dt>
                        <dd class="detail-value font-mono">
                            {{ Str::mask($setting->access_token, '*', 8, -8) }}
                            <button class="ml-2 text-gray-400 hover:text-blue-600" onclick="copyToClipboard('{{ $setting->access_token }}')" title="Copiar">
                                <i class="fas fa-copy"></i>
                            </button>
                        </dd>
                    </div>
                    <div class="detail-row">
                        <dt class="detail-label">Conexión:</dt>
                        <dd class="detail-value">
                            <span class="detail-badge bg-gray-100 text-gray-800" id="connection-status">
                                <i class="fas fa-question mr-1"></i> Sin verificar
                            </span>
                        </dd>
                    </div>
                </dl>
                <div class="p-4 bg-gray-50 border-t border-gray-200 text-right">
                    <button class="btn-secondary" id="test-connection" data-id="{{ $setting->id }}">
                        <i class="fas fa-wifi"></i> Probar Conexión
                    </button>
                </div>
            </div>

            @if($setting->description)
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-comment mr-2 text-gray-500"></i>
                            Descripción
                        </h3>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-700">{{ $setting->description }}</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-terminal mr-2 text-gray-600"></i>
                        Configuración Terminal
                    </h3>
                </div>
                <dl>
                    <div class="detail-row">
                        <dt class="detail-label">Tipo:</dt>
                        <dd class="detail-value">
                            @if($setting->terminal_type)
                                <span class="detail-badge bg-blue-100 text-blue-800">{{ ucfirst($setting->terminal_type) }}</span>
                            @else
                                <span class="text-gray-500">No especificado</span>
                            @endif
                        </dd>
                    </div>
                    <div class="detail-row">
                        <dt class="detail-label">Terminal ID:</dt>
                        <dd class="detail-value font-mono">
                            {{ $setting->terminal_id ?: 'No especificado' }}
                        </dd>
                    </div>
                    <div class="detail-row">
                        <dt class="detail-label">POS ID:</dt>
                        <dd class="detail-value font-mono">
                            {{ $setting->pos_id ?: 'No especificado' }}
                        </dd>
                    </div>
                    <div class="detail-row">
                        <dt class="detail-label">Retorno Automático:</dt>
                        <dd class="detail-value">
                            @if($setting->auto_return)
                                <span class="detail-badge bg-green-100 text-green-800"><i class="fas fa-check mr-1"></i> Habilitado</span>
                            @else
                                <span class="detail-badge bg-gray-100 text-gray-800"><i class="fas fa-times mr-1"></i> Deshabilitado</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bell mr-2 text-purple-500"></i>
                        URLs de Notificación
                    </h3>
                </div>
                <dl>
                    <div class="detail-row">
                        <dt class="detail-label">Webhook:</dt>
                        <dd class="detail-value truncate" title="{{ $setting->webhook_url ?: 'No configurado' }}">
                            {{ $setting->webhook_url ? Str::limit($setting->webhook_url, 30) : 'No configurado' }}
                        </dd>
                    </div>
                    <div class="detail-row">
                        <dt class="detail-label">Éxito:</dt>
                        <dd class="detail-value truncate" title="{{ $setting->success_url ?: 'No configurado' }}">
                            {{ $setting->success_url ? Str::limit($setting->success_url, 30) : 'No configurado' }}
                        </dd>
                    </div>
                    <div class="detail-row">
                        <dt class="detail-label">Error:</dt>
                        <dd class="detail-value truncate" title="{{ $setting->failure_url ?: 'No configurado' }}">
                            {{ $setting->failure_url ? Str::limit($setting->failure_url, 30) : 'No configurado' }}
                        </dd>
                    </div>
                    <div class="detail-row">
                        <dt class="detail-label">Pendiente:</dt>
                        <dd class="detail-value truncate" title="{{ $setting->pending_url ?: 'No configurado' }}">
                            {{ $setting->pending_url ? Str::limit($setting->pending_url, 30) : 'No configurado' }}
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
{{-- 7. Script actualizado a Vanilla JS --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = '{{ csrf_token() }}';

    // 1. Probar conexión
    const testButton = document.getElementById('test-connection');
    if (testButton) {
        testButton.addEventListener('click', function() {
            const id = this.dataset.id;
            const statusBadge = document.getElementById('connection-status');
            
            testButton.disabled = true;
            statusBadge.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Probando...';
            statusBadge.className = 'detail-badge bg-yellow-100 text-yellow-800';

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
                    statusBadge.className = 'detail-badge bg-green-100 text-green-800';
                    alert('Conexión exitosa');
                } else {
                    statusBadge.innerHTML = '<i class="fas fa-times mr-1"></i> Error';
                    statusBadge.className = 'detail-badge bg-red-100 text-red-800';
                    alert(data.message || 'Error de conexión');
                }
            })
            .catch(() => {
                statusBadge.innerHTML = '<i class="fas fa-times mr-1"></i> Error';
                statusBadge.className = 'detail-badge bg-red-100 text-red-800';
                alert('Error al probar la conexión');
            })
            .finally(() => {
                testButton.disabled = false;
            });
        });
    }

    // 2. Copiar al portapapeles
    window.copyToClipboard = function(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Copiado al portapapeles');
        }, function() {
            alert('Error al copiar');
        });
    }
});
</script>
@endpush