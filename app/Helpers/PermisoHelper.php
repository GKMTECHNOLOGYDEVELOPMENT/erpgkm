<?php

namespace App\Helpers;

use App\Models\CombinacionPermiso;
use App\Models\Permiso;
use Illuminate\Support\Facades\Log;

class PermisoHelper
{
    public static function tienePermiso($nombrePermiso)
    {
        // Obtener el usuario autenticado
        $usuario = auth()->user();
        
        if (!$usuario) {
            Log::debug("PermisoHelper: No hay usuario autenticado para permiso: {$nombrePermiso}");
            return false;
        }

        Log::debug("PermisoHelper: Verificando permiso '{$nombrePermiso}' para usuario ID: {$usuario->idUsuario}");

        // Obtener la combinación del usuario (rol + tipoUsuario + tipoArea)
        $combinacion = CombinacionPermiso::where('idRol', $usuario->idRol)
            ->where('idTipoUsuario', $usuario->idTipoUsuario)
            ->where('idTipoArea', $usuario->idTipoArea)
            ->first();

        if (!$combinacion) {
            Log::debug("PermisoHelper: No se encontró combinación para usuario ID: {$usuario->idUsuario} con Rol:{$usuario->idRol}, TipoUsuario:{$usuario->idTipoUsuario}, TipoArea:{$usuario->idTipoArea}");
            return false;
        }

        Log::debug("PermisoHelper: Combinación encontrada ID: {$combinacion->idCombinacion}");

        // Verificar si la combinación tiene el permiso
        $tienePermiso = $combinacion->permisos()
            ->where('nombre', $nombrePermiso)
            ->exists();

        Log::debug("PermisoHelper: Usuario ID: {$usuario->idUsuario} " . ($tienePermiso ? "SÍ" : "NO") . " tiene permiso '{$nombrePermiso}'");

        return $tienePermiso;
    }

    public static function tieneAlgunPermiso($permisos)
    {
        foreach ($permisos as $permiso) {
            if (self::tienePermiso($permiso)) {
                return true;
            }
        }
        return false;
    }
}