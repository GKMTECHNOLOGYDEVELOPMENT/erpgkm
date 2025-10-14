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

    // Query de ubicaciones base
    $queryUbicaciones = DB::table('rack_ubicaciones as ru')
        ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
        ->select(
            'ru.idRackUbicacion',
            'ru.codigo',
            'ru.codigo_unico',
            'ru.nivel as piso',
            'ru.posicion',
            'ru.capacidad_maxima',
            'ru.estado_ocupacion',
            'r.nombre as rack_nombre',
            'r.sede',
            'r.idRack'
        )
        ->where('r.estado', 'activo');

    if (!empty($sede)) {
        $queryUbicaciones->where('r.sede', $sede);
    }
    if (!empty($buscar)) {
        $queryUbicaciones->where('r.nombre', 'like', '%' . $buscar . '%');
    }

    $ubicaciones = $queryUbicaciones->get();

    // ✅ Obtener productos por ubicación y AGRUPAR categorías y tipos
    $ubicacionIds = $ubicaciones->pluck('idRackUbicacion')->toArray();
    
    $productosPorUbicacion = DB::table('rack_ubicacion_articulos as rua')
        ->join('articulos as a', 'rua.articulo_id', '=', 'a.idArticulos')
        ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')
        ->leftJoin('modelo as m', 'a.idModelo', '=', 'm.idModelo')
        ->leftJoin('categoria as c', 'm.idCategoria', '=', 'c.idCategoria')
        ->whereIn('rua.rack_ubicacion_id', $ubicacionIds)
        ->select(
            'rua.rack_ubicacion_id',
            'rua.cantidad',
            'a.nombre as producto',
            'ta.nombre as tipo_articulo',
            'c.nombre as categoria'
        )
        ->get()
        ->groupBy('rack_ubicacion_id');

    // Calcular actividad (mantener igual)
    $movimientos = DB::table('rack_movimientos')
        ->select('rack_origen_id', 'rack_destino_id', 'cantidad', 'created_at')
        ->where('created_at', '>=', $fechaInicio)
        ->get();

    $actividadPorRack = [];
    foreach ($movimientos as $mov) {
        $racksInvolucrados = [];
        if ($mov->rack_origen_id) $racksInvolucrados[] = $mov->rack_origen_id;
        if ($mov->rack_destino_id) $racksInvolucrados[] = $mov->rack_destino_id;
        
        foreach ($racksInvolucrados as $rackId) {
            $actividadPorRack[$rackId] = ($actividadPorRack[$rackId] ?? 0) + 1;
        }
    }

    $maxActividad = !empty($actividadPorRack) ? max($actividadPorRack) : 1;
    $actividadNormalizada = [];
    foreach ($actividadPorRack as $rackId => $actividad) {
        $porcentaje = min(round(($actividad / $maxActividad) * 100), 100);
        $actividadNormalizada[$rackId] = $porcentaje;
    }

    // Preparar datos para el heatmap - SIMPLIFICADO
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
            // ✅ Obtener productos para esta ubicación
            $productosUbicacion = $productosPorUbicacion[$ub->idRackUbicacion] ?? collect();
            
            // Calcular cantidad total
            $cantidadTotal = $productosUbicacion->sum('cantidad');
            
            $porcentajeOcupacion = 0;
            if ($ub->capacidad_maxima > 0) {
                $porcentajeOcupacion = round(($cantidadTotal / $ub->capacidad_maxima) * 100);
            }

            // ✅ Obtener información AGRUPADA de productos
            $producto = 'Vacío';
            $categorias = 'Sin categoría';
            $tiposArticulo = 'Sin tipo';

            if ($productosUbicacion->isNotEmpty()) {
                // Si hay un solo producto
                if ($productosUbicacion->count() === 1) {
                    $primerProducto = $productosUbicacion->first();
                    $producto = $primerProducto->producto;
                    $categorias = $primerProducto->categoria ?? 'Sin categoría';
                    $tiposArticulo = $primerProducto->tipo_articulo ?? 'Sin tipo';
                } else {
                    // ✅ Si hay múltiples productos, mostrar el primero y agregar "+X más"
                    $primerProducto = $productosUbicacion->first();
                    $producto = $primerProducto->producto . ' +' . ($productosUbicacion->count() - 1) . ' más';
                    
                    // ✅ AGRUPAR categorías y tipos únicos separados por comas
                    $categoriasUnicas = $productosUbicacion->pluck('categoria')
                        ->filter()
                        ->unique()
                        ->values();
                    $categorias = $categoriasUnicas->isNotEmpty() ? 
                        $categoriasUnicas->join(', ') : 'Sin categoría';
                    
                    $tiposUnicos = $productosUbicacion->pluck('tipo_articulo')
                        ->filter()
                        ->unique()
                        ->values();
                    $tiposArticulo = $tiposUnicos->isNotEmpty() ? 
                        $tiposUnicos->join(', ') : 'Sin tipo';
                }
            }

            // Usar actividad normalizada
            $valorActividad = $actividadNormalizada[$ub->idRack] ?? 0;

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
                'producto' => $producto, // ✅ Producto principal o resumen
                'cantidad' => $cantidadTotal, // ✅ Cantidad total
                'capacidad' => $ub->capacidad_maxima,
                'categoria' => $categorias, // ✅ Categorías separadas por comas
                'tipo_articulo' => $tiposArticulo, // ✅ Tipos separados por comas
                'piso' => $ub->piso,
                'estado' => $ub->estado_ocupacion,
                'sede' => $ub->sede,
                'actividad_bruta' => $actividadPorRack[$ub->idRack] ?? 0,
                'max_actividad' => $maxActividad,
                'total_productos' => $productosUbicacion->count() // ✅ Para debug
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
        'ocupadas' => $ubicaciones->where('estado_ocupacion', '!=', 'vacio')->count(),
    ];

    Log::debug('=== FINAL getDatosRacks ===', [
        'total_data_points' => count($data),
        'stats' => $stats
    ]);

    return response()->json([
        'success' => true,
        'data' => $data,
        'stats' => $stats
    ]);
}



    public function detalleRack($rack)
    {
        // Obtener la sede desde el query parameter
        $sede = request('sede');

        if (!$sede) {
            $rackInfo = DB::table('racks')
                ->where('nombre', $rack)
                ->where('estado', 'activo')
                ->first();

            if (!$rackInfo) {
                return redirect()->route('almacen.vista')->with('error', 'Rack no encontrado');
            }

            $sede = $rackInfo->sede;
        }

        // Obtener datos del rack específico CON MÁS INFORMACIÓN DE ARTÍCULOS
        $rackData = DB::table('racks as r')
            ->join('rack_ubicaciones as ru', 'r.idRack', '=', 'ru.rack_id')
            ->leftJoin('rack_ubicacion_articulos as rua', 'ru.idRackUbicacion', '=', 'rua.rack_ubicacion_id')
            ->leftJoin('articulos as a', 'rua.articulo_id', '=', 'a.idArticulos')
            ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')
            ->leftJoin('modelo as m', 'a.idModelo', '=', 'm.idModelo') // JOIN con modelo
            ->leftJoin('categoria as c', 'm.idCategoria', '=', 'c.idCategoria') // JOIN con categoría
            ->select(
                'r.idRack',
                'r.nombre as rack_nombre',
                'r.sede',
                'ru.idRackUbicacion',
                'ru.codigo',
                'ru.codigo_unico',
                'ru.nivel',
                'ru.posicion',
                'ru.capacidad_maxima',
                'ru.estado_ocupacion',
                'a.idArticulos',
                'a.nombre as producto',
                'a.stock_total',
                'ta.nombre as tipo_articulo', // Tipo de artículo
                'c.nombre as categoria', // Categoría desde modelo->categoria
                'rua.cantidad',
                'ru.updated_at'
            )
            ->where('r.nombre', $rack)
            ->where('r.sede', $sede)
            ->where('r.estado', 'activo')
            ->orderBy('ru.nivel', 'desc')
            ->orderBy('ru.posicion')
            ->get();

        // Si no existe el rack, redirigir
        if ($rackData->isEmpty()) {
            return redirect()->route('almacen.vista')->with('error', "Rack '{$rack}' no encontrado en la sede '{$sede}'");
        }

        $rackId = $rackData->first()->idRack;
        $ubicacionesIds = $rackData->pluck('idRackUbicacion')->unique();

        // Obtener historial de movimientos
        $historialPorUbicacion = [];
        if ($ubicacionesIds->isNotEmpty()) {
            $movimientos = DB::table('rack_movimientos')
                ->where(function ($query) use ($ubicacionesIds) {
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
                if ($mov->ubicacion_origen_id && in_array($mov->ubicacion_origen_id, $ubicacionesIds->toArray())) {
                    $historialPorUbicacion[$mov->ubicacion_origen_id][] = [
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

                if ((!$mov->ubicacion_origen_id && !$mov->ubicacion_destino_id) &&
                    ($mov->rack_origen_id == $rackId || $mov->rack_destino_id == $rackId)
                ) {
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

        // Estructurar datos para la vista CON MÁS INFORMACIÓN
        $rackEstructurado = [
            'nombre' => $rackData->first()->rack_nombre,
            'sede' => $rackData->first()->sede,
            'niveles' => []
        ];

        // Agrupar por niveles y ubicaciones
        $niveles = $rackData->groupBy('nivel');

        foreach ($niveles as $nivelNum => $ubicacionesNivel) {
            $ubicacionesEstructuradas = [];

            // Agrupar por ubicación
            $ubicacionesAgrupadas = $ubicacionesNivel->groupBy('idRackUbicacion');

            foreach ($ubicacionesAgrupadas as $ubicacionId => $articulos) {
                $primerArticulo = $articulos->first();

                // Calcular cantidad total y productos
                $cantidadTotal = $articulos->sum('cantidad');
                $productos = $articulos->where('producto')->map(function ($art) {
                    return [
                        'id' => $art->idArticulos,
                        'nombre' => $art->producto,
                        'cantidad' => $art->cantidad,
                        'stock_total' => $art->stock_total,
                        'tipo_articulo' => $art->tipo_articulo,
                        'categoria' => $art->categoria
                    ];
                })->values();

                // Determinar estado basado en porcentaje de ocupación
                $porcentajeOcupacion = 0;
                if ($primerArticulo->capacidad_maxima > 0) {
                    $porcentajeOcupacion = ($cantidadTotal / $primerArticulo->capacidad_maxima) * 100;
                }

                // Calcular estado
                $estado = $primerArticulo->estado_ocupacion;
                if ($estado == 'vacio' && $cantidadTotal > 0) {
                    if ($porcentajeOcupacion > 0 && $porcentajeOcupacion <= 24) $estado = 'bajo';
                    elseif ($porcentajeOcupacion <= 49) $estado = 'medio';
                    elseif ($porcentajeOcupacion <= 74) $estado = 'alto';
                    elseif ($porcentajeOcupacion > 74) $estado = 'muy_alto';
                } elseif ($cantidadTotal == 0) {
                    $estado = 'vacio';
                }

                $ubicacionesEstructuradas[] = [
                    'id' => $primerArticulo->idRackUbicacion,
                    'codigo' => $primerArticulo->codigo_unico ?? $primerArticulo->codigo,
                    'productos' => $productos->toArray(),
                    'producto' => $productos->isNotEmpty() ? $productos->first()['nombre'] : null,
                    'cantidad' => $cantidadTotal,
                    'stock_total' => $productos->isNotEmpty() ? $productos->first()['stock_total'] : null,
                    'tipo_articulo' => $productos->isNotEmpty() ? $productos->first()['tipo_articulo'] : null,
                    'categoria' => $productos->isNotEmpty() ? $productos->first()['categoria'] : null,
                    'capacidad' => $primerArticulo->capacidad_maxima,
                    'estado' => $estado,
                    'nivel' => $primerArticulo->nivel,
                    'fecha' => $primerArticulo->updated_at,
                    'historial' => $historialPorUbicacion[$primerArticulo->idRackUbicacion] ?? []
                ];
            }

            $rackEstructurado['niveles'][] = [
                'numero' => $nivelNum,
                'ubicaciones' => $ubicacionesEstructuradas
            ];
        }

        // Obtener lista de todos los racks para navegación
        $todosRacks = DB::table('racks')
            ->where('sede', $sede)
            ->where('estado', 'activo')
            ->orderBy('nombre')
            ->pluck('nombre')
            ->toArray();

        return view('almacen.ubicaciones.detalle-rack', [
            'rack' => $rackEstructurado,
            'todosRacks' => $todosRacks,
            'rackActual' => $rack,
            'sedeActual' => $sede
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

            // Calcular cantidad total actual en la ubicación (SOLO de rack_ubicacion_articulos)
            $cantidadTotalActual = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $request->ubicacion_id)
                ->sum('cantidad');

            // Verificar que la nueva cantidad no supere la capacidad
            if (($cantidadTotalActual + $request->cantidad) > $ubicacion->capacidad_maxima) {
                return response()->json([
                    'success' => false,
                    'message' => 'La cantidad supera la capacidad máxima de la ubicación. Espacio disponible: ' . ($ubicacion->capacidad_maxima - $cantidadTotalActual) . ' unidades'
                ], 422);
            }

            // Obtener información del producto
            $producto = DB::table('articulos')
                ->where('idArticulos', $request->articulo_id)
                ->first();

            // Verificar si ya existe el artículo en la ubicación (SOLO en rack_ubicacion_articulos)
            $articuloExistente = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $request->ubicacion_id)
                ->where('articulo_id', $request->articulo_id)
                ->first();

            if ($articuloExistente) {
                // Actualizar cantidad existente en rack_ubicacion_articulos
                DB::table('rack_ubicacion_articulos')
                    ->where('rack_ubicacion_id', $request->ubicacion_id)
                    ->where('articulo_id', $request->articulo_id)
                    ->update([
                        'cantidad' => $articuloExistente->cantidad + $request->cantidad,
                        'updated_at' => now()
                    ]);
            } else {
                // Insertar nuevo registro SOLO en rack_ubicacion_articulos
                DB::table('rack_ubicacion_articulos')->insert([
                    'rack_ubicacion_id' => $request->ubicacion_id,
                    'articulo_id' => $request->articulo_id,
                    'cantidad' => $request->cantidad,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Actualizar estado de ocupación de la ubicación (SOLO en rack_ubicaciones)
            $nuevaCantidadTotal = $cantidadTotalActual + $request->cantidad;
            $nuevoEstado = $this->calcularEstadoOcupacion($nuevaCantidadTotal, $ubicacion->capacidad_maxima);

            DB::table('rack_ubicaciones')
                ->where('idRackUbicacion', $request->ubicacion_id)
                ->update([
                    'estado_ocupacion' => $nuevoEstado,
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
                        'id' => $producto->idArticulos,
                        'nombre' => $producto->nombre,
                        'cantidad' => $request->cantidad
                    ],
                    'espacio_disponible' => $ubicacion->capacidad_maxima - $nuevaCantidadTotal,
                    'cantidad_total_ubicacion' => $nuevaCantidadTotal
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al agregar producto: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor: ' . $e->getMessage()
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

            // Obtener todos los productos de esta ubicación
            $productosEnUbicacion = DB::table('rack_ubicacion_articulos as rua')
                ->join('articulos as a', 'rua.articulo_id', '=', 'a.idArticulos')
                ->where('rua.rack_ubicacion_id', $request->ubicacion_id)
                ->select('a.nombre as producto', 'rua.cantidad', 'a.idArticulos')
                ->get();

            // Verificar que la ubicación tenga productos
            if ($productosEnUbicacion->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'La ubicación ya está vacía'
                ], 422);
            }

            // Registrar movimientos de salida para cada producto
            foreach ($productosEnUbicacion as $producto) {
                DB::table('rack_movimientos')->insert([
                    'articulo_id' => $producto->idArticulos,
                    'ubicacion_origen_id' => $request->ubicacion_id,
                    'rack_origen_id' => $ubicacion->rack_id,
                    'cantidad' => $producto->cantidad,
                    'tipo_movimiento' => 'salida',
                    'observaciones' => 'Ubicación vaciada manualmente - ' . $producto->producto,
                    'codigo_ubicacion_origen' => $ubicacion->codigo_unico ?? $ubicacion->codigo,
                    'nombre_rack_origen' => $ubicacion->rack_nombre,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Vaciar TODOS los productos de la ubicación en rack_ubicacion_articulos
            DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $request->ubicacion_id)
                ->delete();

            // Actualizar la ubicación a estado vacío
            DB::table('rack_ubicaciones')
                ->where('idRackUbicacion', $request->ubicacion_id)
                ->update([
                    'estado_ocupacion' => 'vacio',
                    'updated_at' => now()
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ubicación vaciada exitosamente. Se removieron ' . $productosEnUbicacion->count() . ' productos.',
                'data' => [
                    'productos_removidos' => $productosEnUbicacion->count(),
                    'productos' => $productosEnUbicacion->pluck('producto')
                ]
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
                ->where(function ($query) {
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
                    $existe = DB::table('racks')
                        ->where('nombre', $value)
                        ->where('sede', $request->sede)
                        ->exists();

                    if ($existe) {
                        $fail("El nombre '$value' ya está en uso en la sede {$request->sede}.");
                    }
                }
            ],
            'sede' => 'required|string|max:50|exists:sucursal,nombre',
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
            'sede' => $request->sede,
            'filas' => $request->filas,
            'columnas' => $request->columnas,
            'estado' => $request->estado,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $rack = DB::table('racks')->where('idRack', $rackId)->first();
        $capacidadMaxima = $request->input('capacidad_maxima', 100);
        
        // Generar ubicaciones automáticamente
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


public function actualizarDimensionesRack(Request $request, $rackId)
{
    DB::beginTransaction();

    try {
        $validator = Validator::make($request->all(), [
            'filas' => 'required|integer|min:1|max:12',
            'columnas' => 'required|integer|min:1|max:24',
            'capacidad_maxima' => 'required|integer|min:1|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        // Obtener el rack actual
        $rack = DB::table('racks')->where('idRack', $rackId)->first();
        
        if (!$rack) {
            return response()->json([
                'success' => false,
                'message' => 'Rack no encontrado'
            ], 404);
        }

        // Obtener las ubicaciones existentes
        $ubicacionesExistentes = DB::table('rack_ubicaciones')
            ->where('rack_id', $rackId)
            ->get();

        // Actualizar las dimensiones del rack
        DB::table('racks')
            ->where('idRack', $rackId)
            ->update([
                'filas' => $request->filas,
                'columnas' => $request->columnas,
                'updated_at' => now()
            ]);

        $rackActualizado = DB::table('racks')->where('idRack', $rackId)->first();
        $nuevasUbicacionesGeneradas = $this->sincronizarUbicaciones($rackActualizado, $ubicacionesExistentes, $request->capacidad_maxima);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Dimensiones actualizadas exitosamente. ' . $nuevasUbicacionesGeneradas . ' nuevas ubicaciones generadas.',
            'data' => [
                'rack_id' => $rackId,
                'nuevas_ubicaciones' => $nuevasUbicacionesGeneradas,
                'total_filas' => $request->filas,
                'total_columnas' => $request->columnas
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al actualizar dimensiones del rack: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error interno del servidor: ' . $e->getMessage()
        ], 500);
    }
}

private function sincronizarUbicaciones($rack, $ubicacionesExistentes, $capacidadMaxima = 100)
{
    $nuevasUbicaciones = [];
    $now = now();
    $ubicacionesCreadas = 0;

    // Crear un mapa de ubicaciones existentes para verificación rápida
    $mapaExistente = [];
    foreach ($ubicacionesExistentes as $ubicacion) {
        $clave = "{$ubicacion->nivel}-{$ubicacion->posicion}";
        $mapaExistente[$clave] = $ubicacion;
    }

    // Generar todas las ubicaciones según las nuevas dimensiones
    for ($nivel = 1; $nivel <= $rack->filas; $nivel++) {
        for ($posicion = 1; $posicion <= $rack->columnas; $posicion++) {
            $clave = "{$nivel}-{$posicion}";
            
            // Si la ubicación no existe, crearla
            if (!isset($mapaExistente[$clave])) {
                $codigo = $this->generarCodigoUbicacion($rack->nombre, $nivel, $posicion);
                $codigoUnico = $rack->nombre . '-' . $codigo;

                $nuevasUbicaciones[] = [
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
                
                $ubicacionesCreadas++;
            }
        }
    }

    // Insertar las nuevas ubicaciones en lote
    if (!empty($nuevasUbicaciones)) {
        DB::table('rack_ubicaciones')->insert($nuevasUbicaciones);
        
        Log::debug('Nuevas ubicaciones generadas', [
            'rack_id' => $rack->idRack,
            'rack_nombre' => $rack->nombre,
            'nuevas_ubicaciones' => count($nuevasUbicaciones),
            'filas' => $rack->filas,
            'columnas' => $rack->columnas
        ]);
    }

    return $ubicacionesCreadas;
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
                ->map(function ($nombre) {
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
