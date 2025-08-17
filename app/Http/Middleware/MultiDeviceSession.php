<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MultiDeviceSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Permitir múltiples sesiones para el mismo usuario
        if (auth()->check()) {
            // Configurar cookie de sesión para permitir múltiples dispositivos
            config([
                'session.same_site' => 'lax',
                'session.secure' => false, // true si usas HTTPS
                'session.http_only' => true,
                'session.cookie' => 'laravel_session_' . auth()->id(),
            ]);
        }

        return $next($request);
    }
}
