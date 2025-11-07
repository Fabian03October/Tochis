@extends('layouts.admin')

@section('title', 'Configuración MercadoPago')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-credit-card"></i>
                        Configuración MercadoPago
                    </h3>
                    <a href="{{ route('admin.mercadopago.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Nueva Configuración
                    </a>
                </div>

                <div class="card-body">
                    @if($settings->isEmpty())
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                            <h5>No hay configuraciones de MercadoPago</h5>
                            <p>Configure su terminal de pagos para comenzar a procesar transacciones.</p>
                            <a href="{{ route('admin.mercadopago.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Primera Configuración
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Modo</th>
                                        <th>Terminal</th>
                                        <th>Estado</th>
                                        <th>Conexión</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($settings as $setting)
                                        <tr>
                                            <td>
                                                <strong>{{ $setting->name }}</strong>
                                                @if($setting->is_active)
                                                    <span class="badge badge-success ml-2">Activo</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($setting->is_sandbox)
                                                    <span class="badge badge-warning">Sandbox</span>
                                                @else
                                                    <span class="badge badge-success">Producción</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($setting->terminal_type)
                                                    <span class="badge badge-info">{{ ucfirst($setting->terminal_type) }}</span>
                                                @else
                                                    <span class="text-muted">No especificado</span>
                                                @endif
                                            </td>
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
                                            <td>
                                                <span class="badge badge-secondary" id="status-{{ $setting->id }}">
                                                    <i class="fas fa-question"></i> Sin verificar
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.mercadopago.show', $setting) }}" 
                                                       class="btn btn-sm btn-info" title="Ver">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.mercadopago.edit', $setting) }}" 
                                                       class="btn btn-sm btn-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-primary test-connection"
                                                            data-id="{{ $setting->id }}" 
                                                            title="Probar Conexión">
                                                        <i class="fas fa-wifi"></i>
                                                    </button>
                                                    @if(!$setting->is_active)
                                                        <button type="button" 
                                                                class="btn btn-sm btn-success activate-setting"
                                                                data-id="{{ $setting->id }}" 
                                                                title="Activar">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    @endif
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger delete-setting"
                                                            data-id="{{ $setting->id }}" 
                                                            title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para confirmación de eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿Está seguro de que desea eliminar esta configuración de MercadoPago?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let deleteId = null;

    // Probar conexión
    $('.test-connection').click(function() {
        const id = $(this).data('id');
        const statusBadge = $(`#status-${id}`);
        const button = $(this);
        
        button.prop('disabled', true);
        statusBadge.html('<i class="fas fa-spinner fa-spin"></i> Probando...');
        statusBadge.removeClass().addClass('badge badge-warning');
        
        $.post(`/admin/mercadopago/${id}/test`)
            .done(function(response) {
                if(response.success) {
                    statusBadge.html('<i class="fas fa-check"></i> Conectado');
                    statusBadge.removeClass().addClass('badge badge-success');
                    toastr.success('Conexión exitosa');
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

    // Activar configuración
    $('.activate-setting').click(function() {
        const id = $(this).data('id');
        const button = $(this);
        
        button.prop('disabled', true);
        
        $.post(`/admin/mercadopago/${id}/activate`)
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

    // Eliminar configuración
    $('.delete-setting').click(function() {
        deleteId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirmDelete').click(function() {
        if(deleteId) {
            $.ajax({
                url: `/admin/mercadopago/${deleteId}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                }
            })
            .done(function(response) {
                toastr.success('Configuración eliminada');
                location.reload();
            })
            .fail(function() {
                toastr.error('Error al eliminar la configuración');
            })
            .always(function() {
                $('#deleteModal').modal('hide');
                deleteId = null;
            });
        }
    });
});
</script>
@endpush