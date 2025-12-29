<?php

namespace App\Http\Controllers\almacen\asignarArticulo;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Models\Asignacion;
use App\Models\Usuario;
use App\Models\DetalleAsignacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AsignarArticuloController extends Controller
{
 public function index(Request $request)
{
    $query = Asignacion::with(['usuario', 'detalles.articulo']);
    
    // Filtros
    if ($request->filled('idUsuario')) {
        $query->where('idUsuario', $request->idUsuario);
    }
    
    if ($request->filled('estado')) {
        $query->where('estado', $request->estado);
    }
    
    if ($request->filled('articulo_id')) {
        $query->whereHas('detalles', function ($q) use ($request) {
            $q->where('articulo_id', $request->articulo_id);
        });
    }
    
    // Paginar los resultados (por ejemplo, 9 por página para 3 columnas)
    $asignaciones = $query->latest()->paginate(9);
    
    $usuarios = Usuario::where('estado', 1)->get();
    $articulos = Articulo::where('estado', 1)->get();
    
    return view('almacen.asignar-articulos.index', compact('asignaciones', 'usuarios', 'articulos'));
}

    public function create()
    {
        $usuarios = Usuario::select('idUsuario', 'Nombre', 'apellidoPaterno', 'apellidoMaterno', 'correo')
            ->where('estado', 1)
            ->get()
            ->map(function($usuario) {
                $usuario->nombre_completo = $usuario->Nombre . ' ' . 
                                           $usuario->apellidoPaterno . ' ' . 
                                           $usuario->apellidoMaterno;
                return $usuario;
            });
            
        $articulos = Articulo::select('idArticulos', 'nombre', 'stock_total', 'maneja_serie', 'precio_venta')
            ->where('estado', 1)
            ->where('stock_total', '>', 0)
            ->get()
            ->map(function($articulo) {
                $articulo->stock_disponible = $articulo->stock_disponible;
                return $articulo;
            });
        
        return view('almacen.asignar-articulos.create', compact('usuarios', 'articulos'));
    }
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'idUsuario' => 'required|exists:usuarios,idUsuario',
        'fecha_asignacion' => 'required|date',
        'fecha_devolucion' => 'nullable|date|after_or_equal:fecha_asignacion',
        'observaciones' => 'nullable|string|max:500',
        'articulos' => 'required|array|min:1',
        'articulos.*.articulo_id' => 'required|exists:articulos,idArticulos', // Cambiado también aquí
        'articulos.*.cantidad' => 'required|integer|min:1',
        'articulos.*.numero_serie' => 'nullable|string|max:255'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Error de validación',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        DB::beginTransaction();

        // Crear asignación
        $asignacion = Asignacion::create([
            'idUsuario' => $request->idUsuario,
            'fecha_asignacion' => $request->fecha_asignacion,
            'fecha_devolucion' => $request->fecha_devolucion,
            'observaciones' => $request->observaciones,
            'estado' => 'activo'
        ]);

        // Procesar artículos
        foreach ($request->articulos as $articuloData) {
            $articulo = Articulo::find($articuloData['articulo_id']); // Cambiado aquí
            
            // Validar stock disponible
            if ($articulo->stock_disponible < $articuloData['cantidad']) {
                throw new \Exception("Stock insuficiente para: {$articulo->nombre}. Stock disponible: {$articulo->stock_disponible}");
            }

            // Validar número de serie si el artículo lo requiere
            if ($articulo->maneja_serie && empty($articuloData['numero_serie'])) {
                throw new \Exception("El artículo '{$articulo->nombre}' requiere número de serie");
            }

            // Crear detalle
            $asignacion->detalles()->create([
                'articulo_id' => $articuloData['articulo_id'], // Cambiado aquí
                'cantidad' => $articuloData['cantidad'],
                'numero_serie' => $articuloData['numero_serie'] ?? null,
                'estado_articulo' => 'activo'
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Asignación creada exitosamente',
            'redirect' => route('asignar-articulos.index')
        ]);
            
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error al crear la asignación: ' . $e->getMessage()
        ], 500);
    }
}

   public function edit($id)
{
    $asignacion = Asignacion::with(['usuario', 'detalles.articulo'])->findOrFail($id);
    
    $usuarios = Usuario::select('idUsuario', 'Nombre', 'apellidoPaterno', 'apellidoMaterno', 'correo')
        ->where('estado', 1)
        ->get()
        ->map(function($usuario) {
            $usuario->nombre_completo = trim("{$usuario->Nombre} {$usuario->apellidoPaterno} {$usuario->apellidoMaterno}");
            return $usuario;
        });
        
    // Obtener todos los artículos activos para poder cambiar o agregar más
    $articulos = Articulo::select([
            'articulos.idArticulos',
            'articulos.nombre',
            'articulos.stock_total',
            'articulos.maneja_serie',
            'articulos.precio_venta',
            DB::raw('(articulos.stock_total - IFNULL(SUM(CASE WHEN asignaciones.estado IN ("activo", "vencido") THEN detalle_asignaciones.cantidad ELSE 0 END), 0)) as stock_disponible')
        ])
        ->leftJoin('detalle_asignaciones', 'articulos.idArticulos', '=', 'detalle_asignaciones.articulo_id')
        ->leftJoin('asignaciones', 'detalle_asignaciones.asignacion_id', '=', 'asignaciones.id')
        ->where('articulos.estado', 1)
        ->where('articulos.stock_total', '>', 0)
        ->groupBy('articulos.idArticulos', 'articulos.nombre', 'articulos.stock_total', 'articulos.maneja_serie', 'articulos.precio_venta')
        ->get()
        ->map(function($articulo) {
            // Asegurar que stock_disponible sea al menos 0
            $articulo->stock_disponible = max(0, $articulo->stock_disponible);
            return $articulo;
        });
    
    return view('almacen.asignar-articulos.edit', compact('asignacion', 'usuarios', 'articulos'));
}

   public function update(Request $request, $id)
{
    $asignacion = Asignacion::with('detalles')->findOrFail($id);
    
    $validator = Validator::make($request->all(), [
        'idUsuario' => 'required|exists:usuarios,idUsuario',
        'fecha_asignacion' => 'required|date',
        'fecha_devolucion' => 'nullable|date|after_or_equal:fecha_asignacion',
        'observaciones' => 'nullable|string|max:500',
        'estado' => 'required|in:activo,devuelto,vencido',
        'articulos' => 'required|array|min:1',
        'articulos.*.articulo_id' => 'required|exists:articulos,idArticulos', // Cambiado de 'id' a 'articulo_id'
        'articulos.*.cantidad' => 'required|integer|min:1',
        'articulos.*.numero_serie' => 'nullable|string|max:255',
        'articulos.*.estado_articulo' => 'required|in:activo,dañado,perdido,devuelto'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Error de validación',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        DB::beginTransaction();

        // Actualizar datos básicos de la asignación
        $asignacion->update([
            'idUsuario' => $request->idUsuario,
            'fecha_asignacion' => $request->fecha_asignacion,
            'fecha_devolucion' => $request->fecha_devolucion,
            'observaciones' => $request->observaciones,
            'estado' => $request->estado,
            'updated_at' => now()
        ]);

        // Eliminar detalles existentes
        $asignacion->detalles()->delete();

        // Agregar nuevos detalles
        foreach ($request->articulos as $articuloData) {
            $articulo = Articulo::find($articuloData['articulo_id']); // Cambiado de 'id' a 'articulo_id'
            
            // Validar stock disponible (solo si el estado no es devuelto)
            if ($request->estado !== 'devuelto' && $articulo->stock_disponible < $articuloData['cantidad']) {
                // Sumar los artículos que ya estaban asignados a esta asignación
                $yaAsignado = $asignacion->detalles()
                    ->where('articulo_id', $articuloData['articulo_id'])
                    ->sum('cantidad');
                
                $stockRealmenteDisponible = $articulo->stock_disponible + $yaAsignado;
                
                if ($stockRealmenteDisponible < $articuloData['cantidad']) {
                    throw new \Exception("Stock insuficiente para: {$articulo->nombre}. Stock disponible: {$stockRealmenteDisponible}");
                }
            }

            // Validar número de serie si el artículo lo requiere
            if ($articulo->maneja_serie && empty($articuloData['numero_serie'])) {
                throw new \Exception("El artículo '{$articulo->nombre}' requiere número de serie");
            }

            // Crear nuevo detalle
            $asignacion->detalles()->create([
                'articulo_id' => $articuloData['articulo_id'], // Cambiado de 'id' a 'articulo_id'
                'cantidad' => $articuloData['cantidad'],
                'numero_serie' => $articuloData['numero_serie'] ?? null,
                'estado_articulo' => $articuloData['estado_articulo']
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Asignación actualizada exitosamente',
            'redirect' => route('asignar-articulos.index')
        ]);
            
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar la asignación: ' . $e->getMessage()
        ], 500);
    }
}

    public function devolver($id)
    {
        try {
            DB::beginTransaction();
            
            $asignacion = Asignacion::findOrFail($id);
            $asignacion->update(['estado' => 'devuelto']);
            
            // Actualizar estado de los artículos
            $asignacion->detalles()->update(['estado_articulo' => 'devuelto']);
            
            DB::commit();
            
            return back()->with('success', 'Artículos devueltos exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al devolver los artículos: ' . $e->getMessage());
        }
    }

    public function reportarDanado(Request $request, $id)
    {
        try {
            $detalle = DetalleAsignacion::findOrFail($id);
            $detalle->update([
                'estado_articulo' => 'dañado',
                'observaciones' => $request->observaciones
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Artículo reportado como dañado'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al reportar el artículo'
            ], 500);
        }
    }
}