<?php

namespace App\Http\Controllers\administracion\areas;

use App\Http\Controllers\Controller;
use App\Models\Tipoarea;
use App\Models\Clientegeneral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AreasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('administracion.areas.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientesGenerales = Clientegeneral::where('estado', 1)->get();
        return view('administracion.areas.create', compact('clientesGenerales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255|unique:tipoarea,nombre',
                'clientes_generales' => 'nullable|array',
                'clientes_generales.*' => 'exists:clientegeneral,idClienteGeneral',
            ]);

            DB::transaction(function () use ($request) {
                // Crear el área
                $area = Tipoarea::create([
                    'nombre' => $request->nombre
                ]);

                // Asignar clientes generales si se seleccionaron
                if ($request->has('clientes_generales')) {
                    foreach ($request->clientes_generales as $idClienteGeneral) {
                        DB::table('clientegeneral_area')->insert([
                            'idClienteGeneral' => $idClienteGeneral,
                            'idTipoArea' => $area->idTipoArea
                        ]);
                    }
                }

                Log::info("Área creada: {$area->nombre} con " . count($request->clientes_generales ?? []) . " clientes");
            });

            // Si es una petición AJAX (desde el modal), responder con JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Área creada exitosamente.'
                ]);
            }

            // Si es una petición normal, redirigir
            return redirect()->route('areas.index')
                ->with('success', 'Área creada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error creando área: ' . $e->getMessage());

            // Si es una petición AJAX, responder con error JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear el área: ' . $e->getMessage(),
                    'errors' => $e->getMessage()
                ], 422);
            }

            // Si es una petición normal, redirigir con error
            return redirect()->back()
                ->with('error', 'Error al crear el área: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $area = Tipoarea::findOrFail($id);
        $clientesAsignados = DB::table('clientegeneral_area as cga')
            ->join('clientegeneral as cg', 'cga.idClienteGeneral', '=', 'cg.idClienteGeneral')
            ->where('cga.idTipoArea', $id)
            ->select('cg.idClienteGeneral', 'cg.descripcion')
            ->get();

        return view('administracion.areas.show', compact('area', 'clientesAsignados'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $area = Tipoarea::findOrFail($id);

        // Obtener IDs de clientes ya asignados a esta área
        $clientesAsignadosIds = DB::table('clientegeneral_area')
            ->where('idTipoArea', $id)
            ->pluck('idClienteGeneral')
            ->toArray();

        // Debug: verifica los datos
        Log::info('Clientes asignados IDs para área ' . $id . ':', $clientesAsignadosIds);

        return view('administracion.areas.edit', compact('area', 'clientesAsignadosIds'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255|unique:tipoarea,nombre,' . $id . ',idTipoArea',
                'clientes_generales' => 'nullable|array',
                'clientes_generales.*' => 'exists:clientegeneral,idClienteGeneral',
            ]);

            DB::transaction(function () use ($request, $id) {
                // Actualizar el área
                $area = Tipoarea::findOrFail($id);
                $area->update([
                    'nombre' => $request->nombre
                ]);

                // Sincronizar clientes generales
                DB::table('clientegeneral_area')
                    ->where('idTipoArea', $id)
                    ->delete();

                if ($request->has('clientes_generales')) {
                    foreach ($request->clientes_generales as $idClienteGeneral) {
                        DB::table('clientegeneral_area')->insert([
                            'idClienteGeneral' => $idClienteGeneral,
                            'idTipoArea' => $id
                        ]);
                    }
                }

                Log::info("Área actualizada: {$area->nombre} con " . count($request->clientes_generales ?? []) . " clientes");
            });

            // Si es una petición AJAX, responder con JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Área actualizada exitosamente.'
                ]);
            }

            return redirect()->route('areas.index')
                ->with('success', 'Área actualizada exitosamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si es una petición AJAX, responder con errores de validación en JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error actualizando área: ' . $e->getMessage());

            // Si es una petición AJAX, responder con error JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el área: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Error al actualizar el área: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                // Eliminar relaciones primero
                DB::table('clientegeneral_area')
                    ->where('idTipoArea', $id)
                    ->delete();

                // Eliminar el área
                Tipoarea::findOrFail($id)->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Área eliminada exitosamente.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error eliminando área: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el área.'
            ], 500);
        }
    }

    /**
     * Obtener áreas para API/Select2
     */
    public function getAreas()
    {
        try {
            $areas = Tipoarea::select('idTipoArea', 'nombre')->get();
            return response()->json($areas);
        } catch (\Exception $e) {
            Log::error('Error obteniendo áreas: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }


    public function getClientesGenerales()
    {
        try {
            $clientes = Clientegeneral::where('estado', 1)
                ->select('idClienteGeneral', 'descripcion')
                ->get();

            return response()->json($clientes);
        } catch (\Exception $e) {
            Log::error('Error obteniendo clientes generales: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }
    // En tu AreasController.php
    public function getClientesModal($id)
    {
        try {
            $clientesAsignados = DB::table('clientegeneral_area as cga')
                ->join('clientegeneral as cg', 'cga.idClienteGeneral', '=', 'cg.idClienteGeneral')
                ->where('cga.idTipoArea', $id)
                ->select('cg.idClienteGeneral', 'cg.descripcion')
                ->get();

            return response()->json([
                'success' => true,
                'clientes' => $clientesAsignados
            ]);
        } catch (\Exception $e) {
            Log::error('Error obteniendo clientes para modal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los clientes'
            ], 500);
        }
    }
    /**
     * Obtener datos para DataTable
     */
    public function getAll(Request $request)
    {
        $query = Tipoarea::query()
            ->select(
                'idTipoArea',
                'nombre',
                DB::raw('(SELECT COUNT(*) FROM clientegeneral_area WHERE idTipoArea = tipoarea.idTipoArea) as total_clientes')
            );

        $total = Tipoarea::count();

        // Filtro global de búsqueda
        if ($search = $request->input('search.value')) {
            $query->where('nombre', 'like', "%$search%");
        }

        $filtered = $query->count();

        $areas = $query
            ->skip($request->start)
            ->take($request->length)
            ->get();

        $data = $areas->map(function ($area) {
            return [
                'idTipoArea' => $area->idTipoArea,
                'nombre' => $area->nombre,
                'total_clientes' => $area->total_clientes,
                'acciones' => $area->idTipoArea // Se renderizará en JavaScript
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }
}
