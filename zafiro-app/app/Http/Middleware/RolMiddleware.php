<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = $request->user();

        $permitido = $usuario
            && $usuario->activo
            && $usuario->empleado?->activo
            && in_array($usuario->rolSlug(), $roles, true);

        if (! $permitido) {
            abort(403, 'No tienes permiso para entrar a esta sección.');
        }

        return $next($request);
    }
}