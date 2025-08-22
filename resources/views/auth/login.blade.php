@extends('layouts.app')

@section('title', 'Iniciar Sesión - TOCHIS')

@push('styles')
<style>
    .tochis-bg {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
        position: relative;
        overflow: hidden;
    }
    
    .tochis-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: 
            radial-gradient(circle at 20% 50%, rgba(249, 115, 22, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(249, 115, 22, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 40% 80%, rgba(249, 115, 22, 0.1) 0%, transparent 50%);
    }
    
    .login-card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(249, 115, 22, 0.2);
    }
    
    .input-group {
        position: relative;
    }
    
    .input-group input {
        transition: all 0.3s ease;
    }
    
    .input-group input:focus {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(249, 115, 22, 0.2);
    }
    
    .logo-animation {
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
</style>
@endpush

@section('content')
<div class="min-h-screen flex items-center justify-center tochis-bg relative">
    <div class="max-w-md w-full mx-4 relative z-10">
        <!-- Logo y Título -->
        <div class="text-center mb-8">
            <div class="mx-auto h-24 w-24 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-2xl logo-animation">
                <i class="fas fa-utensils text-white text-3xl"></i>
            </div>
            <h1 class="mt-6 text-4xl font-bold text-white tracking-wider">
                TOCHIS
            </h1>
            <p class="mt-2 text-orange-300 font-medium">
                Sistema de Punto de Venta
            </p>
            <div class="w-20 h-1 bg-gradient-to-r from-orange-500 to-orange-600 mx-auto mt-4 rounded-full"></div>
        </div>
        
        <!-- Card de Login -->
        <div class="login-card rounded-2xl shadow-2xl p-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Bienvenido</h2>
                <p class="text-gray-600 mt-2">Inicia sesión para continuar</p>
            </div>
            
            <form class="space-y-6" method="POST" action="{{ route('login.post') }}">
                @csrf
                
                <!-- Email Input -->
                <div class="input-group">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-orange-500"></i>
                        Correo electrónico
                    </label>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           autocomplete="email" 
                           required 
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-orange-500 transition-all duration-300 @error('email') border-red-500 @enderror"
                           placeholder="ejemplo@tochis.com"
                           value="{{ old('email') }}">
                </div>

                <!-- Password Input -->
                <div class="input-group">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-orange-500"></i>
                        Contraseña
                    </label>
                    <input id="password" 
                           name="password" 
                           type="password" 
                           autocomplete="current-password" 
                           required 
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-orange-500 transition-all duration-300 @error('password') border-red-500 @enderror"
                           placeholder="••••••••">
                </div>

                <!-- Errores -->
                @if($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-400 mr-2"></i>
                            <div>
                                @foreach($errors->all() as $error)
                                    <p class="text-sm text-red-700">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Remember me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" 
                               name="remember" 
                               type="checkbox" 
                               class="h-4 w-4 text-orange-500 focus:ring-orange-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 text-sm text-gray-700 font-medium">
                            Recordarme
                        </label>
                    </div>
                </div>

                <!-- Botón de Login -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold py-3 px-6 rounded-lg hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-4 focus:ring-orange-300 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Iniciar Sesión
                </button>
            </form>
            
            <!-- Credenciales de prueba -->
            <div class="mt-8 p-4 bg-gray-50 rounded-lg border-l-4 border-orange-500">
                <h3 class="text-sm font-bold text-gray-800 mb-3 flex items-center">
                    <i class="fas fa-info-circle text-orange-500 mr-2"></i>
                    Credenciales de prueba
                </h3>
                <div class="text-xs text-gray-600 space-y-2">
                    <div class="flex justify-between items-center p-2 bg-white rounded border">
                        <span><strong>Administrador:</strong></span>
                        <span class="text-orange-600 font-mono">admin@pos.com / admin123</span>
                    </div>
                    <div class="flex justify-between items-center p-2 bg-white rounded border">
                        <span><strong>Cajero:</strong></span>
                        <span class="text-orange-600 font-mono">cajero@pos.com / cajero123</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-gray-400 text-sm">
                © 2025 TOCHIS. Todos los derechos reservados.
            </p>
        </div>
    </div>
</div>
@endsection
