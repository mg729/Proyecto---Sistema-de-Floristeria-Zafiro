<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClienteMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuario = $request->user();

        if (! $usuario || ! $usuario->activo || ! $usuario->esCliente()) {
            abort(403, 'Esta sección es solo para clientes.');
        }

        return $next($request);
    }
}