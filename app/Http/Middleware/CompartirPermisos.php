<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\PermisoHelper;

class CompartirPermisos
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            // Compartir permisos con todas las vistas
            view()->share('tienePermiso', function($permiso) {
                return PermisoHelper::tienePermiso($permiso);
            });
            
            view()->share('tieneAlgunPermiso', function($permisos) {
                return PermisoHelper::tieneAlgunPermiso($permisos);
            });
        }

        return $next($request);
    }
}