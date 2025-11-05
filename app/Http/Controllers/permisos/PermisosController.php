<?php

namespace App\Http\Controllers\permisos;

use App\Http\Controllers\Controller;
use App\Models\CombinacionPermiso;
use App\Models\CombinacionPermisoDetalle;
use App\Models\Permiso;
use App\Models\PermisoAsignado;
use App\Models\Rol;
use App\Models\TipoUsuario;
use App\Models\TipoArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermisosController extends Controller 
{
   // Index - Lista de permisos
    public function index()
    {
        $permisos = Permiso::all();
        return view('permisos.index', compact('permisos'));
    }

    // Vista crear permiso
    public function create()
    {
        return view('permisos.create');
    }

    // Guardar permiso
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'modulo' => 'required|string|max:100'
        ]);

        try {
            Permiso::create($request->all());
            return redirect()->route('permisos.index')->with('success', 'Permiso creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear el permiso: ' . $e->getMessage());
        }
    }

    // Vista editar permiso
    public function edit($id)
    {
        $permiso = Permiso::findOrFail($id);
        return view('permisos.edit', compact('permiso'));
    }

    // Actualizar permiso
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'modulo' => 'required|string|max:100'
        ]);

        try {
            $permiso = Permiso::findOrFail($id);
            $permiso->update($request->all());
            
            return redirect()->route('permisos.index')->with('success', 'Permiso actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar el permiso: ' . $e->getMessage());
        }
    }

    // Eliminar permiso
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                // Eliminar relaciones primero
                CombinacionPermisoDetalle::where('idPermiso', $id)->delete();
                // Eliminar permiso
                Permiso::findOrFail($id)->delete();
            });
            
            return redirect()->route('permisos.index')->with('success', 'Permiso eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el permiso: ' . $e->getMessage());
        }
    }

    // Vista para gestionar combinaciones
    public function combinaciones()
    {
        $combinaciones = CombinacionPermiso::with(['rol', 'tipoUsuario', 'tipoArea'])->get();
        $roles = Rol::all();
        $tiposUsuario = TipoUsuario::all();
        $tiposArea = TipoArea::all();
        
        return view('permisos.combinaciones', compact('combinaciones', 'roles', 'tiposUsuario', 'tiposArea'));
    }

    // Crear nueva combinación
    public function storeCombinacion(Request $request)
    {
        $request->validate([
            'idRol' => 'required|exists:rol,idRol',
            'idTipoUsuario' => 'required|exists:tipousuario,idTipoUsuario',
            'idTipoArea' => 'required|exists:tipoarea,idTipoArea',
            'nombre_combinacion' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string'
        ]);

        try {
            // Verificar si ya existe la combinación
            $existe = CombinacionPermiso::where('idRol', $request->idRol)
                ->where('idTipoUsuario', $request->idTipoUsuario)
                ->where('idTipoArea', $request->idTipoArea)
                ->exists();

            if ($existe) {
                return redirect()->back()->with('error', 'Esta combinación ya existe.');
            }

            CombinacionPermiso::create($request->all());
            return redirect()->route('permisos.combinaciones')->with('success', 'Combinación creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al crear la combinación: ' . $e->getMessage());
        }
    }

    // Vista para asignar permisos a combinación
    public function asignarPermisos($idCombinacion)
    {
        $combinacion = CombinacionPermiso::with(['rol', 'tipoUsuario', 'tipoArea', 'permisos'])->findOrFail($idCombinacion);
        $permisos = Permiso::all();
        $permisosAsignados = $combinacion->permisos->pluck('idPermiso')->toArray();
        
        return view('permisos.asignar-permisos', compact('combinacion', 'permisos', 'permisosAsignados'));
    }

    // Guardar permisos de combinación
    public function guardarPermisosCombinacion(Request $request, $idCombinacion)
    {
        $request->validate([
            'permisos' => 'array',
            'permisos.*' => 'exists:permisos,idPermiso'
        ]);

        try {
            DB::transaction(function () use ($idCombinacion, $request) {
                // Eliminar permisos actuales
                CombinacionPermisoDetalle::where('idCombinacion', $idCombinacion)->delete();
                
                // Asignar nuevos permisos
                if ($request->has('permisos')) {
                    foreach ($request->permisos as $permisoId) {
                        CombinacionPermisoDetalle::create([
                            'idCombinacion' => $idCombinacion,
                            'idPermiso' => $permisoId
                        ]);
                    }
                }
            });
            
            return redirect()->route('permisos.combinaciones')->with('success', 'Permisos asignados exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al asignar permisos: ' . $e->getMessage());
        }
    }

    // Eliminar combinación
    public function destroyCombinacion($id)
    {
        try {
            CombinacionPermiso::findOrFail($id)->delete();
            return redirect()->route('permisos.combinaciones')->with('success', 'Combinación eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar la combinación: ' . $e->getMessage());
        }
    }
}