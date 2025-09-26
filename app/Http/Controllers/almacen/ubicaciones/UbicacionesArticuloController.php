<?php

namespace App\Http\Controllers\almacen\ubicaciones;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Models\Modelo;
use App\Models\Moneda;
use App\Models\Sucursal;
use App\Models\Tipoarea;
use App\Models\Tipoarticulo;
use App\Models\Ubicacion;
use App\Models\Ubicacione;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Picqer\Barcode\BarcodeGeneratorPNG;

class UbicacionesArticuloController extends Controller
{
    public function index()
    {
     
        // Retorna la vista para artículos
        return view('almacen.ubicacionarticulo.index');
    }
    

   public function create()
    {
        // Obtener datos para los selects
        $unidades = Unidad::all();
        $tiposArticulo = Tipoarticulo::all();
        $modelos = Modelo::with(['marca', 'categoria'])->where('estado', 1)->get();
        $monedas = Moneda::all();
        $sucursales = Sucursal::all();
        // Retornar la vista con los datos necesarios
        return view('almacen.ubicaciones.create', compact('unidades', 'tiposArticulo', 'modelos', 'monedas', 'sucursales'));
    }


    public function edit($id)
{
    $ubicacion = Ubicacion::findOrFail($id);
    $sucursales = Sucursal::all();
    return view('almacen.ubicaciones.edit', compact('ubicacion', 'sucursales'));
}
public function getUbicacionesArticulo(Request $request)
{
    $query = DB::table('articulo_ubicaciones as au')
        ->join('articulos as a', 'au.articulo_id', '=', 'a.idArticulos')
        ->join('ubicacion as u', 'au.ubicacion_id', '=', 'u.idUbicacion')
        ->select(
            'au.idArticuloUbicacion',
            'au.articulo_id',
            'au.ubicacion_id',
            'au.cantidad',
            'au.origen',
            'au.created_at',
            'a.nombre as nombre_articulo',
            'a.codigo_repuesto',
            'a.idTipoArticulo',
            'u.nombre as nombre_ubicacion'
        );

    $total = $query->count();

    if ($search = $request->input('search.value')) {
        $query->where(function ($q) use ($search) {
            $q->where('a.nombre', 'like', "%$search%")
              ->orWhere('a.codigo_repuesto', 'like', "%$search%")
              ->orWhere('u.nombre', 'like', "%$search%");
        });
    }

    $filtered = $query->count();

    $articuloUbicaciones = $query
        ->skip($request->start)
        ->take($request->length)
        ->get();

    $data = $articuloUbicaciones->map(function ($au) {
        // Determinar qué mostrar según el tipo de artículo
        $nombreArticulo = $au->nombre_articulo;
        if ($au->idTipoArticulo == 2) {
            $nombreArticulo = $au->codigo_repuesto ?: 'Sin código repuesto';
        }

        return [
            'idArticuloUbicacion' => $au->idArticuloUbicacion,
            'articulo_id' => $au->articulo_id,
            'ubicacion_id' => $au->ubicacion_id,
            'nombre_articulo' => $nombreArticulo,
            'nombre_ubicacion' => $au->nombre_ubicacion,
            'cantidad' => $au->cantidad,
            'origen' => $au->origen,
            'created_at' => $au->created_at
        ];
    });

    return response()->json([
        'draw' => intval($request->draw),
        'recordsTotal' => $total,
        'recordsFiltered' => $filtered,
        'data' => $data,
    ]);
}
public function update(Request $request, $id)
{
    Log::info('Inicio update ubicacion', ['id' => $id, 'request' => $request->all()]);

    try {
        $validated = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ubicacion', 'nombre')->ignore($id, 'idUbicacion'),
            ],
            'idSucursal' => 'required|integer|exists:sucursal,idSucursal',
        ]);

        $ubicacion = Ubicacion::findOrFail($id);
        $ubicacion->update($validated);

        Log::info('Ubicación actualizada correctamente', ['id' => $id]);

        return response()->json(['success' => true, 'message' => 'Ubicación actualizada correctamente']);

    } catch (\Illuminate\Validation\ValidationException $e) {
        $errors = $e->errors();
        Log::warning('Error de validación al actualizar ubicación', ['errors' => $errors]);

        if (isset($errors['nombre'])) {
            return response()->json([
                'success' => false,
                'duplicado' => true,
                'message' => $errors['nombre'][0],
            ], 422);
        }

        return response()->json(['success' => false, 'errors' => $errors], 422);

    } catch (\Exception $e) {
        Log::error('Error inesperado al actualizar ubicación', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error inesperado al actualizar la ubicación.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function store(Request $request)
{
    Log::info('Inicio store ubicacion', ['request' => $request->all()]);

    try {
        // Validar los datos de entrada
        $validatedData = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ubicacion', 'nombre'),
            ],
            'idSucursal' => 'required|integer|exists:sucursal,idSucursal',
        ]);

        Log::info('Validación exitosa', ['validatedData' => $validatedData]);

        // Crear y guardar la ubicación
        $ubicacion = new Ubicacion();
        $ubicacion->nombre = $validatedData['nombre'];
        $ubicacion->idSucursal = $validatedData['idSucursal'];
        $ubicacion->save();

        Log::info('Ubicación guardada', ['idUbicacion' => $ubicacion->idUbicacion]);

        return response()->json([
            'success' => true,
            'message' => 'Ubicación agregada correctamente',
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        $errors = $e->errors();

        Log::warning('Error de validación al guardar ubicación', ['errors' => $errors]);

        if (isset($errors['nombre'])) {
            return response()->json([
                'success' => false,
                'duplicado' => true,
                'message' => $errors['nombre'][0], // "El campo nombre ya ha sido tomado."
            ], 422);
        }

        return response()->json([
            'success' => false,
            'errors' => $errors,
        ], 422);

    } catch (\Exception $e) {
        Log::error('Error inesperado al guardar ubicación', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Ocurrió un error al guardar la ubicación.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


   public function destroy($id)
{
    try {
        $ubicacion = Ubicacion::findOrFail($id);

        // Verificar si el artículo tiene estado = 1
        // if ($ubicacion->estado == 1) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Este suministro no puede ser eliminado porque está activo.',
        //     ], 403); // 403 Forbidden
        // }


        $ubicacion->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ubicacion eliminado con éxito',
        ]);
    } catch (\Exception $e) {
        Log::error('Error al eliminar el artículo: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Ocurrió un error al eliminar el artículo.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


    
}
