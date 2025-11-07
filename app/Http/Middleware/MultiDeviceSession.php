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
            // Crear un identificador único por dispositivo/pestaña
            $deviceId = $request->header('X-Device-ID') ?? 
                       $request->cookie('device_id') ?? 
                       uniqid('device_', true);
            
            // Configurar cookie de sesión única por dispositivo y usuario
            $sessionName = 'laravel_session_' . auth()->id() . '_' . md5($deviceId);
            
            config([
                'session.cookie' => $sessionName,
                'session.same_site' => 'lax',
                'session.secure' => $request->isSecure(),
                'session.http_only' => true,
                'session.lifetime' => 480, // 8 horas para POS
            ]);
            
            // Establecer cookie de dispositivo si no existe
            if (!$request->cookie('device_id')) {
                cookie('device_id', $deviceId, 525600); // 1 año
            }
        }

        return $next($request);
    }
}
