<?php

namespace App\Http\Controllers\administracion\permisos;

use App\Http\Controllers\Controller;
use App\Models\Permiso;
use App\Models\CombinacionPermiso;
use App\Models\Rol;
use App\Models\TipoUsuario;
use App\Models\TipoArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermisosController extends Controller
{
    // Vista principal con Alpine.js
    public function index()
    {
        $permisos = Permiso::all();
        $combinaciones = CombinacionPermiso::with(['rol', 'tipoUsuario', 'tipoArea', 'permisos'])->get();
        $roles = Rol::all();
        $tiposUsuario = TipoUsuario::all();
        $tiposArea = TipoArea::all();

        return view('permisos.index', compact('permisos', 'combinaciones', 'roles', 'tiposUsuario', 'tiposArea'));
    }

    // API: Obtener todos los datos
    public function getData()
    {
        $data = [
            'permisos' => Permiso::all(),
            'combinaciones' => CombinacionPermiso::with(['rol', 'tipoUsuario', 'tipoArea', 'permisos'])
                ->orderBy('created_at', 'desc')  // ← AÑADE ESTO TAMBIÉN
                ->get()
                ->map(function ($combinacion) {
                    return [
                        'idCombinacion' => $combinacion->idCombinacion,
                        'nombre_completo' => $combinacion->nombre_completo,
                        'rol' => $combinacion->rol ? $combinacion->rol->nombre : 'N/A',
                        'tipo_usuario' => $combinacion->tipoUsuario ? $combinacion->tipoUsuario->nombre : 'N/A',
                        'tipo_area' => $combinacion->tipoArea ? $combinacion->tipoArea->nombre : 'N/A',
                        'permisos_count' => $combinacion->permisos->count(),
                        'permisos' => $combinacion->permisos->pluck('idPermiso')->toArray()
                    ];
                }),
            'roles' => Rol::all(),
            'tiposUsuario' => TipoUsuario::all(),
            'tiposArea' => TipoArea::all()
        ];

        return response()->json($data);
    }

    // API: Crear permiso
    public function storePermiso(Request $request)
    {
        try {
            $permiso = Permiso::create($request->all());
            return response()->json(['success' => true, 'permiso' => $permiso]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // API: Actualizar permiso
    public function updatePermiso(Request $request, $id)
    {
        try {
            $permiso = Permiso::findOrFail($id);
            $permiso->update($request->all());
            return response()->json(['success' => true, 'permiso' => $permiso]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // API: Eliminar permiso
    public function destroyPermiso($id)
    {
        try {
            DB::transaction(function () use ($id) {
                // Eliminar relaciones primero
                DB::table('combinacion_permisos')->where('idPermiso', $id)->delete();
                // Eliminar permiso
                Permiso::findOrFail($id)->delete();
            });

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // API: Crear combinación (CORREGIDA)
    public function storeCombinacion(Request $request)
    {
        try {
            // Verificar si ya existe la combinación
            $existe = CombinacionPermiso::where('idRol', $request->idRol)
                ->where('idTipoUsuario', $request->idTipoUsuario)
                ->where('idTipoArea', $request->idTipoArea)
                ->exists();

            if ($existe) {
                return response()->json(['success' => false, 'error' => 'Esta combinación ya existe.']);
            }

            $combinacion = CombinacionPermiso::create($request->all());

            // Cargar las relaciones manualmente
            $combinacionConRelaciones = CombinacionPermiso::with(['rol', 'tipoUsuario', 'tipoArea'])
                ->where('idCombinacion', $combinacion->idCombinacion)
                ->first();

            // Construir la respuesta manualmente
            $combinacionResponse = [
                'idCombinacion' => $combinacionConRelaciones->idCombinacion,
                'nombre_completo' => $combinacionConRelaciones->nombre_completo,
                'rol' => $combinacionConRelaciones->rol ? $combinacionConRelaciones->rol->nombre : 'N/A',
                'tipo_usuario' => $combinacionConRelaciones->tipoUsuario ? $combinacionConRelaciones->tipoUsuario->nombre : 'N/A',
                'tipo_area' => $combinacionConRelaciones->tipoArea ? $combinacionConRelaciones->tipoArea->nombre : 'N/A',
                'permisos_count' => 0,
                'permisos' => []
            ];

            return response()->json([
                'success' => true,
                'combinacion' => $combinacionResponse
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // API: Eliminar combinación
    public function destroyCombinacion($id)
    {
        try {
            CombinacionPermiso::findOrFail($id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // API: Obtener permisos de combinación
    public function getPermisosCombinacion($idCombinacion)
    {
        try {
            $combinacion = CombinacionPermiso::with('permisos')->findOrFail($idCombinacion);
            return response()->json(['success' => true, 'permisos' => $combinacion->permisos->pluck('idPermiso')->toArray()]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    // API: Guardar permisos de combinación
    public function guardarPermisosCombinacion(Request $request, $idCombinacion)
    {
        try {
            DB::transaction(function () use ($idCombinacion, $request) {
                // Eliminar permisos actuales
                DB::table('combinacion_permisos')->where('idCombinacion', $idCombinacion)->delete();

                // Asignar nuevos permisos
                if ($request->has('permisos')) {
                    foreach ($request->permisos as $permisoId) {
                        DB::table('combinacion_permisos')->insert([
                            'idCombinacion' => $idCombinacion,
                            'idPermiso' => $permisoId,
                            'created_at' => now()
                        ]);
                    }
                }
            });

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
