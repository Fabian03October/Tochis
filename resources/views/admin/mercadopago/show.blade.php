@extends('layouts.admin')

@section('title', 'Detalles Configuración MercadoPago')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-credit-card"></i>
                        Detalles: {{ $setting->name }}
                        @if($setting->is_active)
                            <span class="badge badge-success ml-2">Activo</span>
                        @else
                            <span class="badge badge-secondary ml-2">Inactivo</span>
                        @endif
                    </h3>
                    <div class="float-right">
                        <a href="{{ route('admin.mercadopago.edit', $setting) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('admin.mercadopago.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Información Básica -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-info-circle"></i> Información Básica</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Nombre:</th>
                                            <td>{{ $setting->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Modo:</th>
                                            <td>
                                                @if($setting->is_sandbox)
                                                    <span class="badge badge-warning">Sandbox (Pruebas)</span>
                                                @else
                                                    <span class="badge badge-success">Producción</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Estado:</th>
                                            <td>
                                                @if($setting->is_active)
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check"></i> Activo
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">
                                                        <i class="fas fa-pause"></i> Inactivo
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Creado:</th>
                                            <td>{{ $setting->created_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Actualizado:</th>
                                            <td>{{ $setting->updated_at->format('d/m/Y H:i:s') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Credenciales API -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-key"></i> Credenciales API</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Public Key:</th>
                                            <td>
                                                <code class="small">{{ Str::mask($setting->public_key, '*', 8, -8) }}</code>
                                                <button class="btn btn-sm btn-outline-secondary ml-2" 
                                                        onclick="copyToClipboard('{{ $setting->public_key }}')">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Access Token:</th>
                                            <td>
                                                <code class="small">{{ Str::mask($setting->access_token, '*', 8, -8) }}</code>
                                                <button class="btn btn-sm btn-outline-secondary ml-2" 
                                                        onclick="copyToClipboard('{{ $setting->access_token }}')">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Conexión:</th>
                                            <td>
                                                <span class="badge badge-secondary" id="connection-status">
                                                    <i class="fas fa-question"></i> Sin verificar
                                                </span>
                                                <button class="btn btn-sm btn-primary ml-2" id="test-connection">
                                                    <i class="fas fa-wifi"></i> Probar
                                                </button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- Configuración Terminal -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-terminal"></i> Configuración Terminal</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Tipo:</th>
                                            <td>
                                                @if($setting->terminal_type)
                                                    <span class="badge badge-info">{{ ucfirst($setting->terminal_type) }}</span>
                                                @else
                                                    <span class="text-muted">No especificado</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Terminal ID:</th>
                                            <td>
                                                @if($setting->terminal_id)
                                                    <code>{{ $setting->terminal_id }}</code>
                                                @else
                                                    <span class="text-muted">No especificado</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>POS ID:</th>
                                            <td>
                                                @if($setting->pos_id)
                                                    <code>{{ $setting->pos_id }}</code>
                                                @else
                                                    <span class="text-muted">No especificado</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Retorno Auto:</th>
                                            <td>
                                                @if($setting->auto_return)
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check"></i> Habilitado
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">
                                                        <i class="fas fa-times"></i> Deshabilitado
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- URLs de Notificación -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-bell"></i> URLs de Notificación</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Webhook:</th>
                                            <td>
                                                @if($setting->webhook_url)
                                                    <a href="{{ $setting->webhook_url }}" target="_blank" class="small">
                                                        {{ Str::limit($setting->webhook_url, 30) }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">No configurado</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Éxito:</th>
                                            <td>
                                                @if($setting->success_url)
                                                    <a href="{{ $setting->success_url }}" target="_blank" class="small">
                                                        {{ Str::limit($setting->success_url, 30) }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">No configurado</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Error:</th>
                                            <td>
                                                @if($setting->failure_url)
                                                    <a href="{{ $setting->failure_url }}" target="_blank" class="small">
                                                        {{ Str::limit($setting->failure_url, 30) }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">No configurado</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Pendiente:</th>
                                            <td>
                                                @if($setting->pending_url)
                                                    <a href="{{ $setting->pending_url }}" target="_blank" class="small">
                                                        {{ Str::limit($setting->pending_url, 30) }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">No configurado</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($setting->description)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-comment"></i> Descripción</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $setting->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Acciones -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-tools"></i> Acciones de Prueba</h5>
                                </div>
                                <div class="card-body">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-primary" id="test-qr">
                                            <i class="fas fa-qrcode"></i> Generar QR de Prueba
                                        </button>
                                        <button type="button" class="btn btn-info" id="test-payment">
                                            <i class="fas fa-credit-card"></i> Crear Pago de Prueba
                                        </button>
                                        @if(!$setting->is_active)
                                            <button type="button" class="btn btn-success" id="activate-setting">
                                                <i class="fas fa-play"></i> Activar Configuración
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para QR Code -->
<div class="modal fade" id="qrModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Código QR de Pago</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div id="qr-content">
                    <!-- QR code will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Probar conexión
    $('#test-connection').click(function() {
        const button = $(this);
        const statusBadge = $('#connection-status');
        
        button.prop('disabled', true);
        statusBadge.html('<i class="fas fa-spinner fa-spin"></i> Probando...');
        statusBadge.removeClass().addClass('badge badge-warning');
        
        $.post(`/admin/mercadopago/{{ $setting->id }}/test`)
            .done(function(response) {
                if(response.success) {
                    statusBadge.html('<i class="fas fa-check"></i> Conectado');
                    statusBadge.removeClass().addClass('badge badge-success');
                    toastr.success('Conexión exitosa');
                    
                    if(response.account_info) {
                        toastr.info(`Cuenta: ${response.account_info.email || 'N/A'}`);
                    }
                } else {
                    statusBadge.html('<i class="fas fa-times"></i> Error');
                    statusBadge.removeClass().addClass('badge badge-danger');
                    toastr.error(response.message || 'Error de conexión');
                }
            })
            .fail(function() {
                statusBadge.html('<i class="fas fa-times"></i> Error');
                statusBadge.removeClass().addClass('badge badge-danger');
                toastr.error('Error al probar la conexión');
            })
            .always(function() {
                button.prop('disabled', false);
            });
    });

    // Generar QR de prueba
    $('#test-qr').click(function() {
        const button = $(this);
        button.prop('disabled', true);
        
        $.post(`/admin/mercadopago/{{ $setting->id }}/generate-test-qr`)
            .done(function(response) {
                if(response.success) {
                    $('#qr-content').html(`
                        <h6>Pago de Prueba - $${response.amount}</h6>
                        <div class="mb-3">
                            <img src="${response.qr_code}" alt="QR Code" class="img-fluid" style="max-width: 300px;">
                        </div>
                        <p class="small text-muted">ID: ${response.payment_id}</p>
                        <p class="small">Escanea este código QR con la app de MercadoPago para probar el pago</p>
                    `);
                    $('#qrModal').modal('show');
                } else {
                    toastr.error(response.message || 'Error al generar QR');
                }
            })
            .fail(function() {
                toastr.error('Error al generar código QR');
            })
            .always(function() {
                button.prop('disabled', false);
            });
    });

    // Crear pago de prueba
    $('#test-payment').click(function() {
        const button = $(this);
        button.prop('disabled', true);
        
        $.post(`/admin/mercadopago/{{ $setting->id }}/create-test-payment`)
            .done(function(response) {
                if(response.success) {
                    toastr.success('Pago de prueba creado exitosamente');
                    if(response.init_point) {
                        if(confirm('¿Desea abrir el enlace de pago en una nueva ventana?')) {
                            window.open(response.init_point, '_blank');
                        }
                    }
                } else {
                    toastr.error(response.message || 'Error al crear pago de prueba');
                }
            })
            .fail(function() {
                toastr.error('Error al crear pago de prueba');
            })
            .always(function() {
                button.prop('disabled', false);
            });
    });

    // Activar configuración
    $('#activate-setting').click(function() {
        const button = $(this);
        button.prop('disabled', true);
        
        $.post(`/admin/mercadopago/{{ $setting->id }}/activate`)
            .done(function(response) {
                if(response.success) {
                    toastr.success('Configuración activada');
                    location.reload();
                } else {
                    toastr.error(response.message || 'Error al activar');
                    button.prop('disabled', false);
                }
            })
            .fail(function() {
                toastr.error('Error al activar la configuración');
                button.prop('disabled', false);
            });
    });
});

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        toastr.success('Copiado al portapapeles');
    }, function() {
        toastr.error('Error al copiar');
    });
}
</script>
@endpush