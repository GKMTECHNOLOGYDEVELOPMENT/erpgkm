<?php

namespace App\Http\Controllers\almacen\ubicaciones;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UbicacionesVistaController extends Controller
{
public function vistaAlmacen()
{
    // Obtener las sedes disponibles desde la tabla sucursal
    $sedes = DB::table('sucursal')
        ->select('nombre')
        ->where('estado', 1) // Asumiendo que 1 = activo
        ->orderBy('nombre')
        ->pluck('nombre');

    return view('almacen.ubicaciones.vista-almacen', compact('sedes'));
}




public function getDatosRacks(Request $request)
{
    Log::debug('=== INICIO getDatosRacks ===', [
        'params' => $request->all()
    ]);

    $periodo = $request->input('periodo', 30);
    $sede = $request->input('sede', '');
    $buscar = $request->input('buscar', '');

    $fechaInicio = now()->subDays($periodo);
    Log::debug('Fecha inicio para movimientos', ['fechaInicio' => $fechaInicio]);

    // Query de ubicaciones (mantener igual)
    $query = DB::table('rack_ubicaciones as ru')
        ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
        ->leftJoin('articulos as a', 'ru.articulo_id', '=', 'a.idArticulos')
        ->select(
            'ru.idRackUbicacion',
            'ru.codigo',
            'ru.codigo_unico',
            'ru.nivel as piso',
            'ru.posicion',
            'ru.cantidad_actual',
            'ru.capacidad_maxima',
            'ru.estado_ocupacion',
            'r.nombre as rack_nombre',
            'r.sede',
            'r.idRack',
            'a.nombre as producto',
            'a.idArticulos as articulo_id'
        )
        ->where('r.estado', 'activo');

    if (!empty($sede)) {
        $query->where('r.sede', $sede);
    }

    if (!empty($buscar)) {
        $query->where('r.nombre', 'like', '%' . $buscar . '%');
    }

    $ubicaciones = $query->get();
    Log::debug('Ubicaciones obtenidas', ['total' => $ubicaciones->count()]);

    // Obtener movimientos de manera más simple primero
    $movimientos = DB::table('rack_movimientos')
        ->select(
            'rack_origen_id',
            'rack_destino_id',
            'cantidad',
            'created_at'
        )
        ->where('created_at', '>=', $fechaInicio)
        ->get();

    Log::debug('Movimientos brutos obtenidos', [
        'total_movimientos' => $movimientos->count(),
        'movimientos_sample' => $movimientos->take(3)
    ]);

    // Calcular actividad simple por rack
    $actividadPorRack = [];
    $detalleMovimientos = [];

    foreach ($movimientos as $mov) {
        $racksInvolucrados = [];
        if ($mov->rack_origen_id) $racksInvolucrados[] = $mov->rack_origen_id;
        if ($mov->rack_destino_id) $racksInvolucrados[] = $mov->rack_destino_id;
        
        foreach ($racksInvolucrados as $rackId) {
            // Puntos base por movimiento
            $puntos = 1; // Cada movimiento vale 1 punto base
            
            // Log detallado por movimiento
            $detalleMovimientos[$rackId][] = [
                'rack_id' => $rackId,
                'cantidad' => $mov->cantidad,
                'puntos' => $puntos,
                'fecha' => $mov->created_at
            ];
            
            $actividadPorRack[$rackId] = ($actividadPorRack[$rackId] ?? 0) + $puntos;
        }
    }

    Log::debug('Actividad POR RACK calculada', [
        'actividad_por_rack' => $actividadPorRack,
        'racks_con_movimientos' => array_keys($actividadPorRack)
    ]);

    // DEBUG: Mostrar detalle de movimientos para los primeros 3 racks
    if (!empty($detalleMovimientos)) {
        $sampleRacks = array_slice(array_keys($detalleMovimientos), 0, 3);
        foreach ($sampleRacks as $rackId) {
            Log::debug("Detalle movimientos RACK {$rackId}", [
                'total_movimientos' => count($detalleMovimientos[$rackId]),
                'movimientos' => $detalleMovimientos[$rackId],
                'puntos_totales' => $actividadPorRack[$rackId] ?? 0
            ]);
        }
    }

    // Encontrar el máximo de actividad para normalizar
    $maxActividad = !empty($actividadPorRack) ? max($actividadPorRack) : 1;
    
    Log::debug('MÁXIMA ACTIVIDAD ENCONTRADA', [
        'max_actividad' => $maxActividad,
        'min_actividad' => !empty($actividadPorRack) ? min($actividadPorRack) : 0,
        'promedio_actividad' => !empty($actividadPorRack) ? array_sum($actividadPorRack) / count($actividadPorRack) : 0
    ]);

    // Normalizar a porcentaje (0-100%)
    $actividadNormalizada = [];
    foreach ($actividadPorRack as $rackId => $actividad) {
        $porcentaje = min(round(($actividad / $maxActividad) * 100), 100);
        $actividadNormalizada[$rackId] = $porcentaje;
        
        // Log para debug de normalización
        if ($actividad <= 5) { // Solo log para actividades bajas para no saturar
            Log::debug("Normalización RACK {$rackId}", [
                'actividad_bruta' => $actividad,
                'max_actividad' => $maxActividad,
                'porcentaje_calculado' => $porcentaje,
                'formula' => "({$actividad} / {$maxActividad}) * 100 = {$porcentaje}%"
            ]);
        }
    }

    Log::debug('ACTIVIDAD NORMALIZADA', [
        'total_racks_con_actividad' => count($actividadNormalizada),
        'rango_actividad' => [
            'min' => !empty($actividadNormalizada) ? min($actividadNormalizada) : 0,
            'max' => !empty($actividadNormalizada) ? max($actividadNormalizada) : 0
        ]
    ]);

    // Preparar datos para el heatmap
    $data = [];
    $rackGroups = [];

    // Agrupar por rack
    foreach ($ubicaciones as $ub) {
        $rackGroups[$ub->rack_nombre][] = $ub;
    }

    // Generar datos para el heatmap
    $y = 0;
    foreach ($rackGroups as $rackNombre => $ubicacionesRack) {
        $x = 0;
        
        foreach ($ubicacionesRack as $ub) {
            $porcentajeOcupacion = 0;
            if ($ub->capacidad_maxima > 0) {
                $porcentajeOcupacion = round(($ub->cantidad_actual / $ub->capacidad_maxima) * 100);
            }

            // Usar actividad normalizada
            $valorActividad = $actividadNormalizada[$ub->idRack] ?? 0;

            // DEBUG: Log para ubicaciones específicas
            if ($valorActividad > 0 && $valorActividad < 10) {
                Log::debug("Ubicación con actividad baja", [
                    'rack_id' => $ub->idRack,
                    'rack_nombre' => $rackNombre,
                    'ubicacion' => $ub->codigo_unico ?? $ub->codigo,
                    'actividad_bruta' => $actividadPorRack[$ub->idRack] ?? 0,
                    'actividad_normalizada' => $valorActividad,
                    'max_actividad_sistema' => $maxActividad
                ]);
            }

            $categoria = 'Sin categoría';
            if ($ub->articulo_id) {
                $tipoArticulo = DB::table('articulos as a')
                    ->join('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')
                    ->where('a.idArticulos', $ub->articulo_id)
                    ->value('ta.nombre');
                $categoria = $tipoArticulo ?? 'Sin categoría';
            }

            // Extraer letra del rack
            $letraRack = substr($rackNombre, 0, 1);
            
            $data[] = [
                'x' => $x,
                'y' => $y,
                'value' => $valorActividad,
                'ocupacion' => $porcentajeOcupacion,
                'rack' => $rackNombre,
                'letra' => $letraRack,
                'ubicacion' => $ub->codigo_unico ?? $ub->codigo,
                'producto' => $ub->producto ?? 'Vacío',
                'cantidad' => $ub->cantidad_actual ?? 0,
                'capacidad' => $ub->capacidad_maxima,
                'categoria' => $categoria,
                'piso' => $ub->piso,
                'estado' => $ub->estado_ocupacion,
                'actividad_bruta' => $actividadPorRack[$ub->idRack] ?? 0, // Para debug
                'max_actividad' => $maxActividad // Para debug
            ];

            $x++;
        }
        $y++;
    }

    // Stats finales
    $stats = [
        'totalRacks' => count($rackGroups),
        'activeRacks' => count(array_filter($actividadNormalizada, fn($v) => $v > 0)),
        'avgActivity' => !empty($actividadNormalizada) ? round(array_sum($actividadNormalizada) / count($actividadNormalizada)) : 0,
        'totalUbicaciones' => count($ubicaciones),
        'ocupadas' => $ubicaciones->where('cantidad_actual', '>', 0)->count(),
        // Debug info
        'debug' => [
            'max_actividad_bruta' => $maxActividad,
            'total_movimientos' => $movimientos->count(),
            'racks_con_movimientos' => count($actividadPorRack)
        ]
    ];

    Log::debug('=== FINAL getDatosRacks ===', [
        'total_data_points' => count($data),
        'stats' => $stats,
        'sample_data' => array_slice($data, 0, 3) // Primeros 3 elementos para debug
    ]);

    return response()->json([
        'success' => true,
        'data' => $data,
        'stats' => $stats
    ]);
}


    // public function detalleRack($rack)
    // {
    //     // Obtener información del rack
    //     $rackInfo = DB::table('racks')
    //         ->where('nombre', $rack)
    //         ->where('estado', 'activo')
    //         ->first();

    //     if (!$rackInfo) {
    //         abort(404, 'Rack no encontrado');
    //     }

    //     // Obtener todas las ubicaciones del rack con sus artículos
    //     $ubicaciones = DB::table('rack_ubicaciones as ru')
    //         ->leftJoin('articulos as a', 'ru.articulo_id', '=', 'a.idArticulos')
    //         ->select(
    //             'ru.*',
    //             'a.nombre as producto',
    //             'a.codigo_barras',
    //             'a.stock_total',
    //             'a.foto'
    //         )
    //         ->where('ru.rack_id', $rackInfo->idRack)
    //         ->orderBy('ru.nivel')
    //         ->orderBy('ru.posicion')
    //         ->get();

    //     // Obtener movimientos recientes del rack (últimos 30 días)
    //     $movimientos = DB::table('rack_movimientos as rm')
    //         ->leftJoin('articulos as a', 'rm.articulo_id', '=', 'a.idArticulos')
    //         ->leftJoin('users as u', 'rm.usuario_id', '=', 'u.id')
    //         ->select(
    //             'rm.*',
    //             'a.nombre as producto',
    //             'u.name as usuario'
    //         )
    //         ->where(function($query) use ($rackInfo) {
    //             $query->where('rm.rack_origen_id', $rackInfo->idRack)
    //                   ->orWhere('rm.rack_destino_id', $rackInfo->idRack);
    //         })
    //         ->where('rm.created_at', '>=', now()->subDays(30))
    //         ->orderBy('rm.created_at', 'desc')
    //         ->limit(50)
    //         ->get();

    //     return view('almacen.ubicaciones.detalle-rack', compact('rackInfo', 'ubicaciones', 'movimientos'));
    // }


    
   public function detalleRack($rack)
{
    // Obtener datos del rack específico
    $rackData = DB::table('racks as r')
        ->join('rack_ubicaciones as ru', 'r.idRack', '=', 'ru.rack_id')
        ->leftJoin('articulos as a', 'ru.articulo_id', '=', 'a.idArticulos')
        ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')
        ->select(
            'r.idRack',
            'r.nombre as rack_nombre',
            'r.sede',
            'ru.idRackUbicacion',
            'ru.codigo',
            'ru.codigo_unico',
            'ru.nivel',
            'ru.posicion',
            'ru.cantidad_actual',
            'ru.capacidad_maxima',
            'ru.estado_ocupacion',
            'a.nombre as producto',
            'a.idArticulos',
            'ta.nombre as categoria',
            'ru.updated_at'
        )
        ->where('r.nombre', $rack)
        ->where('r.estado', 'activo')
        ->orderBy('ru.nivel', 'desc')
        ->orderBy('ru.posicion')
        ->get();

    // Si no existe el rack, redirigir
    if ($rackData->isEmpty()) {
        return redirect()->route('almacen.vista')->with('error', 'Rack no encontrado');
    }

    $rackId = $rackData->first()->idRack;
    $ubicacionesIds = $rackData->pluck('idRackUbicacion');

    // Obtener historial de movimientos para este rack - CORREGIDO
    $historialPorUbicacion = [];
    
    if ($ubicacionesIds->isNotEmpty()) {
        $movimientos = DB::table('rack_movimientos')
            ->where(function($query) use ($ubicacionesIds) {
                $query->whereIn('ubicacion_origen_id', $ubicacionesIds)
                      ->orWhereIn('ubicacion_destino_id', $ubicacionesIds);
            })
            ->orWhere('rack_origen_id', $rackId)
            ->orWhere('rack_destino_id', $rackId)
            ->select(
                'idMovimiento',
                'articulo_id',
                'ubicacion_origen_id',
                'ubicacion_destino_id',
                'rack_origen_id',
                'rack_destino_id',
                'cantidad',
                'tipo_movimiento',
                'observaciones',
                'created_at',
                'codigo_ubicacion_origen',
                'codigo_ubicacion_destino',
                'nombre_rack_origen',
                'nombre_rack_destino'
            )
            ->orderBy('created_at', 'desc')
            ->get();

        // Procesar movimientos para cada ubicación
        foreach ($movimientos as $mov) {
            // Movimientos donde esta ubicación es ORIGEN
            if ($mov->ubicacion_origen_id && in_array($mov->ubicacion_origen_id, $ubicacionesIds->toArray())) {
                $historialPorUbicacion[$mov->ubicacion_origen_id][] = [
                    'fecha' => $mov->created_at,
                    'producto' => 'Producto Movido', // Podrías hacer join con articulos si necesitas el nombre
                    'cantidad' => $mov->cantidad,
                    'tipo' => $mov->tipo_movimiento,
                    'desde' => $mov->codigo_ubicacion_origen,
                    'hacia' => $mov->codigo_ubicacion_destino,
                    'rack_origen' => $mov->nombre_rack_origen,
                    'rack_destino' => $mov->nombre_rack_destino,
                    'observaciones' => $mov->observaciones
                ];
            }
            
            // Movimientos donde esta ubicación es DESTINO
            if ($mov->ubicacion_destino_id && in_array($mov->ubicacion_destino_id, $ubicacionesIds->toArray())) {
                $historialPorUbicacion[$mov->ubicacion_destino_id][] = [
                    'fecha' => $mov->created_at,
                    'producto' => 'Producto Movido',
                    'cantidad' => $mov->cantidad,
                    'tipo' => $mov->tipo_movimiento,
                    'desde' => $mov->codigo_ubicacion_origen,
                    'hacia' => $mov->codigo_ubicacion_destino,
                    'rack_origen' => $mov->nombre_rack_origen,
                    'rack_destino' => $mov->nombre_rack_destino,
                    'observaciones' => $mov->observaciones
                ];
            }
            
            // Movimientos a nivel de rack (sin ubicación específica)
            if ((!$mov->ubicacion_origen_id && !$mov->ubicacion_destino_id) && 
                ($mov->rack_origen_id == $rackId || $mov->rack_destino_id == $rackId)) {
                // Asignar a todas las ubicaciones del rack o a una especial
                foreach ($ubicacionesIds as $ubicacionId) {
                    $historialPorUbicacion[$ubicacionId][] = [
                        'fecha' => $mov->created_at,
                        'producto' => 'Movimiento de Rack',
                        'cantidad' => $mov->cantidad,
                        'tipo' => $mov->tipo_movimiento,
                        'desde' => $mov->nombre_rack_origen,
                        'hacia' => $mov->nombre_rack_destino,
                        'rack_origen' => $mov->nombre_rack_origen,
                        'rack_destino' => $mov->nombre_rack_destino,
                        'observaciones' => $mov->observaciones
                    ];
                }
            }
        }
    }

    // Estructurar datos para la vista
    $rackEstructurado = [
        'nombre' => $rackData->first()->rack_nombre,
        'sede' => $rackData->first()->sede,
        'niveles' => []
    ];

    // Agrupar por niveles
    $niveles = $rackData->groupBy('nivel');
    
    foreach ($niveles as $nivelNum => $ubicacionesNivel) {
        $ubicacionesEstructuradas = [];
        
        foreach ($ubicacionesNivel as $ubicacion) {
            // Determinar estado basado en porcentaje de ocupación
            $porcentajeOcupacion = 0;
            if ($ubicacion->capacidad_maxima > 0) {
                $porcentajeOcupacion = ($ubicacion->cantidad_actual / $ubicacion->capacidad_maxima) * 100;
            }
            
            // Usar el estado de la base de datos o calcularlo
            $estado = $ubicacion->estado_ocupacion;
            if ($estado == 'vacio' && $porcentajeOcupacion > 0) {
                // Recalcular si hay inconsistencia
                if ($porcentajeOcupacion > 0 && $porcentajeOcupacion <= 24) $estado = 'bajo';
                elseif ($porcentajeOcupacion <= 49) $estado = 'medio';
                elseif ($porcentajeOcupacion <= 74) $estado = 'alto';
                elseif ($porcentajeOcupacion > 74) $estado = 'muy_alto';
            }
            
            $ubicacionesEstructuradas[] = [
                'id' => $ubicacion->idRackUbicacion,
                'codigo' => $ubicacion->codigo_unico ?? $ubicacion->codigo,
                'producto' => $ubicacion->producto,
                'cantidad' => $ubicacion->cantidad_actual,
                'capacidad' => $ubicacion->capacidad_maxima,
                'estado' => $estado,
                'categoria' => $ubicacion->categoria,
                'fecha' => $ubicacion->updated_at,
                'historial' => $historialPorUbicacion[$ubicacion->idRackUbicacion] ?? []
            ];
        }
        
        $rackEstructurado['niveles'][] = [
            'numero' => $nivelNum,
            'ubicaciones' => $ubicacionesEstructuradas
        ];
    }

    // Obtener lista de todos los racks para navegación
    $todosRacks = DB::table('racks')
        ->where('estado', 'activo')
        ->orderBy('nombre')
        ->pluck('nombre')
        ->toArray();

    return view('almacen.ubicaciones.detalle-rack', [
        'rack' => $rackEstructurado,
        'todosRacks' => $todosRacks,
        'rackActual' => $rack
    ]);
}



public function iniciarReubicacion(Request $request)
{
    try {
        Log::debug('Datos recibidos para iniciar reubicación:', $request->all());

        // Forzar casting a enteros ANTES de la validación
        $request->merge([
            'ubicacion_origen_id' => (int) $request->ubicacion_origen_id,
            'cantidad' => (int) $request->cantidad
        ]);

        Log::debug('Datos después del casting:', $request->all());

        $validator = Validator::make($request->all(), [
            'ubicacion_origen_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
            'producto' => 'required|string',
            'cantidad' => 'required|integer|min:1'
        ], [
            'cantidad.min' => 'La cantidad debe ser al menos 1 unidad.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'ubicacion_origen_id.exists' => 'La ubicación origen no existe.'
        ]);

        if ($validator->fails()) {
            Log::warning('Validación fallida en iniciarReubicacion:', [
                'errors' => $validator->errors()->toArray(),
                'input_data' => $request->all(),
                'input_types' => [
                    'ubicacion_origen_id' => gettype($request->ubicacion_origen_id),
                    'cantidad' => gettype($request->cantidad)
                ]
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validator->errors(),
                'debug_types' => [
                    'ubicacion_origen_id' => gettype($request->ubicacion_origen_id),
                    'cantidad' => gettype($request->cantidad)
                ]
            ], 422);
        }

        // El resto del código permanece igual...
        $ubicacionOrigen = DB::table('rack_ubicaciones as ru')
            ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
            ->select('ru.*', 'r.nombre as rack_nombre')
            ->where('ru.idRackUbicacion', $request->ubicacion_origen_id)
            ->first();

        if (!$ubicacionOrigen) {
            Log::warning('Ubicación origen no encontrada:', ['id' => $request->ubicacion_origen_id]);
            return response()->json([
                'success' => false,
                'message' => 'Ubicación origen no encontrada'
            ], 404);
        }

        // Verificar que la ubicación origen tenga producto
        if (!$ubicacionOrigen->articulo_id || $ubicacionOrigen->cantidad_actual <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'La ubicación origen no tiene productos para reubicar'
            ], 422);
        }

        Log::debug('Reubicación iniciada exitosamente:', [
            'ubicacion_origen' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
            'producto' => $request->producto,
            'cantidad' => $request->cantidad,
            'cantidad_type' => gettype($request->cantidad)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Modo reubicación activado',
            'data' => [
                'ubicacion_origen' => [
                    'id' => $ubicacionOrigen->idRackUbicacion,
                    'codigo' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                    'rack_nombre' => $ubicacionOrigen->rack_nombre,
                    'producto' => $request->producto,
                    'cantidad' => $request->cantidad
                ]
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Error al iniciar reubicación: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor'
        ], 500);
    }
}

    /**
     * Confirmar reubicación
     */
    public function confirmarReubicacion(Request $request)
    {
        DB::beginTransaction();
    
    try {
        Log::debug('Datos recibidos para confirmar reubicación:', $request->all());

        // Forzar casting a enteros ANTES de la validación
        $request->merge([
            'ubicacion_origen_id' => (int) $request->ubicacion_origen_id,
            'ubicacion_destino_id' => (int) $request->ubicacion_destino_id,
            'cantidad' => (int) $request->cantidad
        ]);

        $validator = Validator::make($request->all(), [
            'ubicacion_origen_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
            'ubicacion_destino_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
            'producto' => 'required|string',
            'cantidad' => 'required|integer|min:1',
            'tipo_reubicacion' => 'required|in:mismo_rack,otro_rack'
        ], [
            'cantidad.min' => 'La cantidad debe ser al menos 1 unidad.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.'
        ]);

        if ($validator->fails()) {
            Log::warning('Validación fallida en confirmarReubicacion:', [
                'errors' => $validator->errors()->toArray(),
                'input_data' => $request->all()
            ]);
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }


            // Verificar que las ubicaciones sean diferentes
            if ($request->ubicacion_origen_id == $request->ubicacion_destino_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes reubicar a la misma ubicación'
                ], 422);
            }

            // Obtener información de ambas ubicaciones
            $ubicacionOrigen = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->select('ru.*', 'r.nombre as rack_nombre', 'r.idRack as rack_id')
                ->where('ru.idRackUbicacion', $request->ubicacion_origen_id)
                ->first();

            $ubicacionDestino = DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->select('ru.*', 'r.nombre as rack_nombre', 'r.idRack as rack_id')
                ->where('ru.idRackUbicacion', $request->ubicacion_destino_id)
                ->first();

            // Verificar que la ubicación destino esté vacía
            if ($ubicacionDestino->articulo_id !== null || $ubicacionDestino->cantidad_actual > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'La ubicación destino ya está ocupada'
                ], 422);
            }

            // Verificar que la ubicación origen tenga suficiente cantidad
            if ($ubicacionOrigen->cantidad_actual < $request->cantidad) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cantidad insuficiente en la ubicación origen'
                ], 422);
            }

            // Realizar la reubicación en la base de datos
            // 1. Actualizar ubicación destino
            DB::table('rack_ubicaciones')
                ->where('idRackUbicacion', $request->ubicacion_destino_id)
                ->update([
                    'articulo_id' => $ubicacionOrigen->articulo_id,
                    'cantidad_actual' => $request->cantidad,
                    'estado_ocupacion' => $this->calcularEstadoOcupacion($request->cantidad, $ubicacionDestino->capacidad_maxima),
                    'updated_at' => now()
                ]);

            // 2. Actualizar ubicación origen
            $nuevaCantidadOrigen = $ubicacionOrigen->cantidad_actual - $request->cantidad;
            
            if ($nuevaCantidadOrigen > 0) {
                // Si queda producto, actualizar cantidad
                DB::table('rack_ubicaciones')
                    ->where('idRackUbicacion', $request->ubicacion_origen_id)
                    ->update([
                        'cantidad_actual' => $nuevaCantidadOrigen,
                        'estado_ocupacion' => $this->calcularEstadoOcupacion($nuevaCantidadOrigen, $ubicacionOrigen->capacidad_maxima),
                        'updated_at' => now()
                    ]);
            } else {
                // Si no queda producto, vaciar la ubicación
                DB::table('rack_ubicaciones')
                    ->where('idRackUbicacion', $request->ubicacion_origen_id)
                    ->update([
                        'articulo_id' => null,
                        'cantidad_actual' => 0,
                        'estado_ocupacion' => 'vacio',
                        'updated_at' => now()
                    ]);
            }

            // 3. Registrar el movimiento
            DB::table('rack_movimientos')->insert([
                'articulo_id' => $ubicacionOrigen->articulo_id,
                'ubicacion_origen_id' => $request->ubicacion_origen_id,
                'ubicacion_destino_id' => $request->ubicacion_destino_id,
                'rack_origen_id' => $ubicacionOrigen->rack_id,
                'rack_destino_id' => $ubicacionDestino->rack_id,
                'cantidad' => $request->cantidad,
                'tipo_movimiento' => 'reubicacion',
                'observaciones' => 'Reubicación de producto',
                'codigo_ubicacion_origen' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                'codigo_ubicacion_destino' => $ubicacionDestino->codigo_unico ?? $ubicacionDestino->codigo,
                'nombre_rack_origen' => $ubicacionOrigen->rack_nombre,
                'nombre_rack_destino' => $ubicacionDestino->rack_nombre,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Producto reubicado exitosamente',
                'data' => [
                    'origen' => $ubicacionOrigen->codigo_unico ?? $ubicacionOrigen->codigo,
                    'destino' => $ubicacionDestino->codigo_unico ?? $ubicacionDestino->codigo,
                    'cantidad' => $request->cantidad,
                    'tipo' => $request->tipo_reubicacion
                ]
            ]);

       } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al confirmar reubicación: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor: ' . $e->getMessage()
        ], 500);
    }
    }

    /**
     * Cancelar reubicación
     */
    public function cancelarReubicacion(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Reubicación cancelada'
        ]);
    }

    /**
     * Calcular estado de ocupación basado en porcentaje
     */
    private function calcularEstadoOcupacion($cantidad, $capacidadMaxima)
    {
        if ($capacidadMaxima <= 0) return 'vacio';
        
        $porcentaje = ($cantidad / $capacidadMaxima) * 100;
        
        if ($porcentaje == 0) return 'vacio';
        if ($porcentaje <= 24) return 'bajo';
        if ($porcentaje <= 49) return 'medio';
        if ($porcentaje <= 74) return 'alto';
        return 'muy_alto';
    }




public function listarProductos()
{
    try {
        $productos = DB::table('articulos')
            ->where('estado', 1)
            ->select('idArticulos as id', 'nombre')
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $productos
        ]);

    } catch (\Exception $e) {
        Log::error('Error al listar productos: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al cargar productos: ' . $e->getMessage()
        ], 500);
    }
}
public function agregarProducto(Request $request)
{
    DB::beginTransaction();
    
    try {
        $validator = Validator::make($request->all(), [
            'ubicacion_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion',
            'articulo_id' => 'required|integer|exists:articulos,idArticulos',
            'cantidad' => 'required|integer|min:1',
            'observaciones' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        // Obtener información de la ubicación
        $ubicacion = DB::table('rack_ubicaciones as ru')
            ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
            ->select('ru.*', 'r.nombre as rack_nombre', 'r.idRack as rack_id')
            ->where('ru.idRackUbicacion', $request->ubicacion_id)
            ->first();

        // Verificar que la ubicación esté vacía
        if ($ubicacion->articulo_id !== null || $ubicacion->cantidad_actual > 0) {
            return response()->json([
                'success' => false,
                'message' => 'La ubicación ya está ocupada'
            ], 422);
        }

        // Verificar que la cantidad no supere la capacidad
        if ($request->cantidad > $ubicacion->capacidad_maxima) {
            return response()->json([
                'success' => false,
                'message' => 'La cantidad supera la capacidad máxima de la ubicación'
            ], 422);
        }

        // Obtener información del producto
        $producto = DB::table('articulos')
            ->where('idArticulos', $request->articulo_id)
            ->first();

        // Actualizar la ubicación
        DB::table('rack_ubicaciones')
            ->where('idRackUbicacion', $request->ubicacion_id)
            ->update([
                'articulo_id' => $request->articulo_id,
                'cantidad_actual' => $request->cantidad,
                'estado_ocupacion' => $this->calcularEstadoOcupacion($request->cantidad, $ubicacion->capacidad_maxima),
                'updated_at' => now()
            ]);

        // Registrar el movimiento
        DB::table('rack_movimientos')->insert([
            'articulo_id' => $request->articulo_id,
            'ubicacion_destino_id' => $request->ubicacion_id,
            'rack_destino_id' => $ubicacion->rack_id,
            'cantidad' => $request->cantidad,
            'tipo_movimiento' => 'ajuste',
            'observaciones' => $request->observaciones ?: 'Ingreso de producto',
            'codigo_ubicacion_destino' => $ubicacion->codigo_unico ?? $ubicacion->codigo,
            'nombre_rack_destino' => $ubicacion->rack_nombre,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Producto agregado exitosamente',
            'data' => [
                'producto' => [
                    'nombre' => $producto->nombre,
                    'cantidad' => $request->cantidad
                ]
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al agregar producto: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor'
        ], 500);
    }
}

public function vaciarUbicacion(Request $request)
{
    DB::beginTransaction();
    
    try {
        $validator = Validator::make($request->all(), [
            'ubicacion_id' => 'required|integer|exists:rack_ubicaciones,idRackUbicacion'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        // Obtener información de la ubicación
        $ubicacion = DB::table('rack_ubicaciones as ru')
            ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
            ->select('ru.*', 'r.nombre as rack_nombre', 'r.idRack as rack_id')
            ->where('ru.idRackUbicacion', $request->ubicacion_id)
            ->first();

        // Verificar que la ubicación tenga producto
        if (!$ubicacion->articulo_id || $ubicacion->cantidad_actual <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'La ubicación ya está vacía'
            ], 422);
        }

        // Registrar movimiento de salida
        DB::table('rack_movimientos')->insert([
            'articulo_id' => $ubicacion->articulo_id,
            'ubicacion_origen_id' => $request->ubicacion_id,
            'rack_origen_id' => $ubicacion->rack_id,
            'cantidad' => $ubicacion->cantidad_actual,
            'tipo_movimiento' => 'salida',
            'observaciones' => 'Ubicación vaciada manualmente',
            'codigo_ubicacion_origen' => $ubicacion->codigo_unico ?? $ubicacion->codigo,
            'nombre_rack_origen' => $ubicacion->rack_nombre,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Vaciar la ubicación
        DB::table('rack_ubicaciones')
            ->where('idRackUbicacion', $request->ubicacion_id)
            ->update([
                'articulo_id' => null,
                'cantidad_actual' => 0,
                'estado_ocupacion' => 'vacio',
                'updated_at' => now()
            ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Ubicación vaciada exitosamente'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al vaciar ubicación: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor'
        ], 500);
    }
}


public function listarRacksDisponibles()
{
    try {
        // Excluir el rack actual y obtener racks activos
        $racks = DB::table('racks')
            ->where('estado', 'activo')
            ->where('idRack', '!=', request('rack_actual')) // Puedes enviar el rack actual desde el frontend si es necesario
            ->select('idRack as id', 'nombre', 'sede')
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $racks
        ]);

    } catch (\Exception $e) {
        Log::error('Error al listar racks disponibles: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al cargar racks disponibles'
        ], 500);
    }
}

public function listarUbicacionesVacias($rackId)
{
    try {
        $ubicaciones = DB::table('rack_ubicaciones as ru')
            ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
            ->where('ru.rack_id', $rackId)
            ->where('ru.estado_ocupacion', 'vacio')
            ->where(function($query) {
                $query->where('ru.articulo_id', null)
                      ->orWhere('ru.cantidad_actual', 0);
            })
            ->select(
                'ru.idRackUbicacion as id',
                'ru.codigo',
                'ru.codigo_unico',
                'ru.capacidad_maxima',
                'ru.nivel',
                'ru.posicion',
                'r.nombre as rack_nombre'
            )
            ->orderBy('ru.nivel')
            ->orderBy('ru.posicion')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $ubicaciones
        ]);

    } catch (\Exception $e) {
        Log::error('Error al listar ubicaciones vacías: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al cargar ubicaciones vacías'
        ], 500);
    }
}


public function crearRack(Request $request)
{
    DB::beginTransaction();
    
    try {
        $validator = Validator::make($request->all(), [
            'nombre' => [
                'required',
                'string',
                'max:10',
                function ($attribute, $value, $fail) use ($request) {
                    // Verificar que el nombre sea único en la misma sede
                    $existe = DB::table('racks')
                        ->where('nombre', $value)
                        ->where('sede', $request->sede) // Seguimos usando 'sede'
                        ->exists();
                    
                    if ($existe) {
                        $fail("El nombre '$value' ya está en uso en la sede {$request->sede}.");
                    }
                }
            ],
            'sede' => 'required|string|max:50|exists:sucursal,nombre', // Validamos que exista en sucursal
            'filas' => 'required|integer|min:1|max:12',
            'columnas' => 'required|integer|min:1|max:24',
            'capacidad_maxima' => 'required|integer|min:1|max:1000',
            'estado' => 'required|in:activo,inactivo'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        // Crear el rack
        $rackId = DB::table('racks')->insertGetId([
            'nombre' => $request->nombre,
            'sede' => $request->sede, // Guardamos el nombre de la sucursal
            'filas' => $request->filas,
            'columnas' => $request->columnas,
            'estado' => $request->estado,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Obtener el rack creado para generar el código único
        $rack = DB::table('racks')->where('idRack', $rackId)->first();

        // Generar ubicaciones automáticamente con capacidad personalizada
        $capacidadMaxima = $request->input('capacidad_maxima', 100);
        $this->generarUbicacionesAutomaticas($rack, $capacidadMaxima);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Rack creado exitosamente con ' . ($request->filas * $request->columnas) . ' ubicaciones generadas',
            'data' => [
                'id' => $rackId,
                'total_ubicaciones' => $request->filas * $request->columnas
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al crear rack: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Genera ubicaciones automáticamente para un rack
 */
private function generarUbicacionesAutomaticas($rack, $capacidadMaxima = 100)
{
    $ubicaciones = [];
    $now = now();

    // Generar ubicaciones basadas en filas y columnas
    for ($nivel = 1; $nivel <= $rack->filas; $nivel++) {
        for ($posicion = 1; $posicion <= $rack->columnas; $posicion++) {
            // Generar código automático (ej: A1-01, A1-02, etc.)
            $codigo = $this->generarCodigoUbicacion($rack->nombre, $nivel, $posicion);
            $codigoUnico = $rack->nombre . '-' . $codigo;

            $ubicaciones[] = [
                'rack_id' => $rack->idRack,
                'codigo' => $codigo,
                'codigo_unico' => $codigoUnico,
                'nivel' => $nivel,
                'posicion' => $posicion,
                'estado_ocupacion' => 'vacio',
                'capacidad_maxima' => $capacidadMaxima,
                'articulo_id' => null,
                'cantidad_actual' => 0,
                'created_at' => $now,
                'updated_at' => $now
            ];
        }
    }

    // Insertar todas las ubicaciones en lote
    if (!empty($ubicaciones)) {
        DB::table('rack_ubicaciones')->insert($ubicaciones);
    }

    Log::debug('Ubicaciones generadas automáticamente', [
        'rack_id' => $rack->idRack,
        'rack_nombre' => $rack->nombre,
        'total_ubicaciones' => count($ubicaciones),
        'filas' => $rack->filas,
        'columnas' => $rack->columnas,
        'capacidad_maxima' => $capacidadMaxima
    ]);
}

/**
 * Genera el código de ubicación basado en el formato existente
 */
private function generarCodigoUbicacion($nombreRack, $nivel, $posicion)
{
    // Formato: {LetraRack}{Nivel}-{Posición con 2 dígitos}
    // Ejemplo: A1-01, A1-02, B2-01, etc.
    return $nombreRack . $nivel . '-' . str_pad($posicion, 2, '0', STR_PAD_LEFT);
}

public function sugerirSiguienteLetra(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'sede' => 'required|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Sede requerida'
            ], 422);
        }

        $sede = $request->sede;

        // Obtener todas las letras usadas en esta sede específica
        $letrasUsadas = DB::table('racks')
            ->where('sede', $sede) // Filtramos por el nombre de la sede
            ->where('estado', 'activo')
            ->pluck('nombre')
            ->map(function($nombre) {
                // Extraer solo la letra (asumiendo que el nombre es una sola letra)
                $letra = strtoupper(trim($nombre));
                // Si es una sola letra A-Z, devolverla, sino null
                return preg_match('/^[A-Z]$/', $letra) ? $letra : null;
            })
            ->filter() // Remover nulls
            ->unique()
            ->sort()
            ->values();

        Log::debug('Letras usadas en sede ' . $sede, ['letras' => $letrasUsadas->toArray()]);

        // Definir el abecedario completo
        $abecedario = range('A', 'Z');
        
        // Encontrar la primera letra disponible
        $siguienteLetra = null;
        foreach ($abecedario as $letra) {
            if (!$letrasUsadas->contains($letra)) {
                $siguienteLetra = $letra;
                break;
            }
        }

        // Si todas las letras están usadas, sugerir patrón con números
        if (!$siguienteLetra) {
            // Buscar el último rack numérico en esta sede
            $racksConNumeros = DB::table('racks')
                ->where('sede', $sede)
                ->where('estado', 'activo')
                ->where('nombre', 'regexp', '^[A-Z][0-9]+$')
                ->pluck('nombre')
                ->sort()
                ->values();

            if ($racksConNumeros->isNotEmpty()) {
                $ultimoRack = $racksConNumeros->last();
                // Extraer número y aumentar
                preg_match('/([A-Z])(\d+)/', $ultimoRack, $matches);
                if (count($matches) === 3) {
                    $letraBase = $matches[1];
                    $numero = (int)$matches[2] + 1;
                    $siguienteLetra = $letraBase . $numero;
                } else {
                    $siguienteLetra = 'A1';
                }
            } else {
                // Si no hay racks con números, empezar con A1
                $siguienteLetra = 'A1';
            }
        }

        // Si aún no hay sugerencia, usar doble letra (AA, AB, etc.)
        if (!$siguienteLetra) {
            // Buscar si ya hay racks con doble letra en esta sede
            $racksDobleLetra = DB::table('racks')
                ->where('sede', $sede)
                ->where('estado', 'activo')
                ->where('nombre', 'regexp', '^[A-Z]{2}$')
                ->pluck('nombre')
                ->sort()
                ->values();

            if ($racksDobleLetra->isNotEmpty()) {
                $ultimaDobleLetra = $racksDobleLetra->last();
                // Incrementar la doble letra (AA -> AB, AB -> AC, etc.)
                $siguienteLetra = ++$ultimaDobleLetra;
            } else {
                $siguienteLetra = 'AA';
            }
        }

        Log::debug('Siguiente letra sugerida', [
            'sede' => $sede,
            'sugerencia' => $siguienteLetra,
            'letras_usadas' => $letrasUsadas->toArray()
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'sugerencia' => $siguienteLetra,
                'letras_usadas' => $letrasUsadas->toArray(),
                'abecedario_completo' => $abecedario
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Error al sugerir siguiente letra: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al generar sugerencia: ' . $e->getMessage()
        ], 500);
    }
}
public function listarRacks()
{
    try {
        $racks = DB::table('racks')
            ->where('estado', 'activo')
            ->select('idRack', 'nombre', 'sede', 'filas', 'columnas')
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $racks
        ]);

    } catch (\Exception $e) {
        Log::error('Error al listar racks: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al cargar racks'
        ], 500);
    }
}

public function obtenerInfoRack($id)
{
    try {
        $rack = DB::table('racks')
            ->where('idRack', $id)
            ->select('idRack', 'nombre', 'sede', 'filas', 'columnas')
            ->first();

        if (!$rack) {
            return response()->json([
                'success' => false,
                'message' => 'Rack no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $rack
        ]);

    } catch (\Exception $e) {
        Log::error('Error al obtener info del rack: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al cargar información del rack'
        ], 500);
    }
}

public function crearUbicacion(Request $request)
{
    DB::beginTransaction();
    
    try {
        $validator = Validator::make($request->all(), [
            'rack_id' => 'required|integer|exists:racks,idRack',
            'codigo' => 'required|string|max:20',
            'nivel' => 'required|integer|min:1',
            'posicion' => 'required|integer|min:1',
            'capacidad_maxima' => 'required|integer|min:1',
            'estado_ocupacion' => 'required|in:vacio,bajo,medio,alto,muy_alto'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verificar que no exista ya una ubicación con el mismo código en el mismo rack
        $ubicacionExistente = DB::table('rack_ubicaciones')
            ->where('rack_id', $request->rack_id)
            ->where('codigo', $request->codigo)
            ->exists();

        if ($ubicacionExistente) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe una ubicación con este código en el rack seleccionado'
            ], 422);
        }

        // Generar código único automáticamente
        $rack = DB::table('racks')->where('idRack', $request->rack_id)->first();
        $codigoUnico = $rack->nombre . '-' . $request->codigo;

        // Crear la ubicación
        $ubicacionId = DB::table('rack_ubicaciones')->insertGetId([
            'rack_id' => $request->rack_id,
            'codigo' => $request->codigo,
            'codigo_unico' => $codigoUnico,
            'nivel' => $request->nivel,
            'posicion' => $request->posicion,
            'capacidad_maxima' => $request->capacidad_maxima,
            'estado_ocupacion' => $request->estado_ocupacion,
            'articulo_id' => null, // Siempre null al crear
            'cantidad_actual' => 0, // Siempre 0 al crear
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Ubicación creada exitosamente',
            'data' => [
                'id' => $ubicacionId,
                'codigo_unico' => $codigoUnico
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al crear ubicación: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor: ' . $e->getMessage()
        ], 500);
    }
}
}