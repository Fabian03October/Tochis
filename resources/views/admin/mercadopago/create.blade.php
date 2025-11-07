@extends('layouts.admin')

@section('title', 'Nueva Configuración MercadoPago')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-plus"></i>
                        Nueva Configuración MercadoPago
                    </h3>
                    <a href="{{ route('admin.mercadopago.index') }}" class="btn btn-secondary float-right">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <form action="{{ route('admin.mercadopago.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        
                        <!-- Información Básica -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nombre de la Configuración <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Ej: Terminal Principal" 
                                           required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_sandbox">Modo de Operación</label>
                                    <select class="form-control @error('is_sandbox') is-invalid @enderror" 
                                            id="is_sandbox" 
                                            name="is_sandbox">
                                        <option value="1" {{ old('is_sandbox', '1') == '1' ? 'selected' : '' }}>
                                            Sandbox (Pruebas)
                                        </option>
                                        <option value="0" {{ old('is_sandbox') == '0' ? 'selected' : '' }}>
                                            Producción
                                        </option>
                                    </select>
                                    @error('is_sandbox')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Credenciales API -->
                        <h5 class="mt-4 mb-3">
                            <i class="fas fa-key"></i> Credenciales de API
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="public_key">Public Key <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('public_key') is-invalid @enderror" 
                                           id="public_key" 
                                           name="public_key" 
                                           value="{{ old('public_key') }}" 
                                           placeholder="TEST-xxx o APP_USR-xxx"
                                           required>
                                    @error('public_key')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="access_token">Access Token <span class="text-danger">*</span></label>
                                    <input type="password" 
                                           class="form-control @error('access_token') is-invalid @enderror" 
                                           id="access_token" 
                                           name="access_token" 
                                           value="{{ old('access_token') }}" 
                                           placeholder="TEST-xxx o APP_USR-xxx"
                                           required>
                                    @error('access_token')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Configuración de Terminal Point -->
                        <h5 class="mt-4 mb-3">
                            <i class="fas fa-credit-card"></i> Configuración de Terminal Point
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="terminal_type">Tipo de Terminal</label>
                                    <select class="form-control @error('terminal_type') is-invalid @enderror" 
                                            id="terminal_type" 
                                            name="terminal_type">
                                        <option value="">Seleccionar...</option>
                                        <option value="point" {{ old('terminal_type') == 'point' ? 'selected' : '' }}>
                                            MercadoPago Point
                                        </option>
                                        <option value="smart" {{ old('terminal_type') == 'smart' ? 'selected' : '' }}>
                                            Point Smart
                                        </option>
                                        <option value="mini" {{ old('terminal_type') == 'mini' ? 'selected' : '' }}>
                                            Point Mini
                                        </option>
                                        <option value="pro" {{ old('terminal_type') == 'pro' ? 'selected' : '' }}>
                                            Point Pro
                                        </option>
                                    </select>
                                    @error('terminal_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="terminal_id">ID de Terminal</label>
                                    <input type="text" 
                                           class="form-control @error('terminal_id') is-invalid @enderror" 
                                           id="terminal_id" 
                                           name="terminal_id" 
                                           value="{{ old('terminal_id') }}" 
                                           placeholder="Opcional">
                                    @error('terminal_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="pos_id">POS ID</label>
                                    <input type="text" 
                                           class="form-control @error('pos_id') is-invalid @enderror" 
                                           id="pos_id" 
                                           name="pos_id" 
                                           value="{{ old('pos_id') }}" 
                                           placeholder="Opcional">
                                    @error('pos_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- URLs de Notificación -->
                        <h5 class="mt-4 mb-3">
                            <i class="fas fa-bell"></i> URLs de Notificación
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="webhook_url">URL de Webhook</label>
                                    <input type="url" 
                                           class="form-control @error('webhook_url') is-invalid @enderror" 
                                           id="webhook_url" 
                                           name="webhook_url" 
                                           value="{{ old('webhook_url', url('/webhook/mercadopago')) }}" 
                                           placeholder="https://tudominio.com/webhook/mercadopago">
                                    @error('webhook_url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="success_url">URL de Éxito</label>
                                    <input type="url" 
                                           class="form-control @error('success_url') is-invalid @enderror" 
                                           id="success_url" 
                                           name="success_url" 
                                           value="{{ old('success_url', url('/payment/success')) }}" 
                                           placeholder="https://tudominio.com/payment/success">
                                    @error('success_url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="failure_url">URL de Error</label>
                                    <input type="url" 
                                           class="form-control @error('failure_url') is-invalid @enderror" 
                                           id="failure_url" 
                                           name="failure_url" 
                                           value="{{ old('failure_url', url('/payment/failure')) }}" 
                                           placeholder="https://tudominio.com/payment/failure">
                                    @error('failure_url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pending_url">URL Pendiente</label>
                                    <input type="url" 
                                           class="form-control @error('pending_url') is-invalid @enderror" 
                                           id="pending_url" 
                                           name="pending_url" 
                                           value="{{ old('pending_url', url('/payment/pending')) }}" 
                                           placeholder="https://tudominio.com/payment/pending">
                                    @error('pending_url')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Configuración Adicional -->
                        <h5 class="mt-4 mb-3">
                            <i class="fas fa-cog"></i> Configuración Adicional
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1" 
                                               {{ old('is_active', '1') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">
                                            Activar esta configuración
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="auto_return" 
                                               name="auto_return" 
                                               value="1" 
                                               {{ old('auto_return', '1') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="auto_return">
                                            Retorno automático después del pago
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Descripción opcional de la configuración">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Guardar Configuración
                        </button>
                        <a href="{{ route('admin.mercadopago.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-generar webhook URL basada en el dominio actual
    const baseUrl = window.location.origin;
    
    $('#webhook_url').val(baseUrl + '/webhook/mercadopago');
    $('#success_url').val(baseUrl + '/payment/success');
    $('#failure_url').val(baseUrl + '/payment/failure');
    $('#pending_url').val(baseUrl + '/payment/pending');
    
    // Mostrar/ocultar campos según el modo
    $('#is_sandbox').change(function() {
        const isSandbox = $(this).val() == '1';
        const publicKeyInput = $('#public_key');
        const accessTokenInput = $('#access_token');
        
        if (isSandbox) {
            publicKeyInput.attr('placeholder', 'TEST-xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx');
            accessTokenInput.attr('placeholder', 'TEST-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
        } else {
            publicKeyInput.attr('placeholder', 'APP_USR-xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx');
            accessTokenInput.attr('placeholder', 'APP_USR-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
        }
    });
    
    // Validación del formulario
    $('form').submit(function(e) {
        const publicKey = $('#public_key').val();
        const accessToken = $('#access_token').val();
        const isSandbox = $('#is_sandbox').val() == '1';
        
        if (isSandbox) {
            if (!publicKey.startsWith('TEST-')) {
                e.preventDefault();
                toastr.error('El Public Key debe comenzar con TEST- en modo sandbox');
                return false;
            }
            if (!accessToken.startsWith('TEST-')) {
                e.preventDefault();
                toastr.error('El Access Token debe comenzar con TEST- en modo sandbox');
                return false;
            }
        } else {
            if (!publicKey.startsWith('APP_USR-')) {
                e.preventDefault();
                toastr.error('El Public Key debe comenzar con APP_USR- en modo producción');
                return false;
            }
            if (!accessToken.startsWith('APP_USR-')) {
                e.preventDefault();
                toastr.error('El Access Token debe comenzar con APP_USR- en modo producción');
                return false;
            }
        }
    });
});
</script>
@endpush