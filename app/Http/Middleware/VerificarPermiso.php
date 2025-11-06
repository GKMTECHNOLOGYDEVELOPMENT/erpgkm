<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\PermisoHelper;

class VerificarPermiso
{
    public function handle(Request $request, Closure $next, $permiso)
    {
        if (!PermisoHelper::tienePermiso($permiso)) {
            // Redirigir a la vista de error 403
            return response()->view('pages.error403', [], 403);
        }

        return $next($request);
    }
}