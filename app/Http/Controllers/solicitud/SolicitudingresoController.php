<?php

namespace App\Http\Controllers\solicitud;

use App\Http\Controllers\Controller;
use App\Models\ArticuloUbicacion;
use App\Models\ArticuloSerie;
use App\Models\SolicitudIngreso;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SolicitudingresoController extends Controller
{
    public function index()
    {
        Log::info('=== INICIANDO INDEX DE SOLICITUDES INGRESO ===');
    
        $solicitudes = SolicitudIngreso::with([
            'articulo:idArticulos,nombre,codigo_barras,codigo_repuesto,idTipoArticulo,maneja_serie',
            'proveedor:idProveedor,nombre,numeroDocumento',
            'clienteGeneral:idClienteGeneral,descripcion,estado',
            'compra:idCompra,codigocompra,estado,fechaEmision,proveedor_id',
            'entradaProveedor:id,codigo_entrada,estado,fecha_ingreso,tipo_entrada,cliente_general_id',
            'ubicaciones.ubicacion:idUbicacion,nombre'
        ])->orderBy('created_at', 'desc')->get();

        Log::info('Total de solicitudes encontradas: ' . $solicitudes->count());

        // Filtrar: Solo compras con estado 'enviado_almacen' y todas las entradas proveedor
        $solicitudesFiltradas = $solicitudes->filter(function($solicitud) {
            if ($solicitud->origen === 'compra') {
                return $solicitud->compra && 
                    ($solicitud->compra->estado === 'enviado_almacen' || 
                        $solicitud->compra->estado === 'actualizado_almacen');
            } else {
                return true;
            }
        });

        Log::info('Solicitudes después del filtro: ' . $solicitudesFiltradas->count());

        // Agrupar solicitudes filtradas por origen y origen_id
        $solicitudesAgrupadas = $solicitudesFiltradas->groupBy(function($solicitud) {
            return $solicitud->origen . '_' . $solicitud->origen_id;
        })->map(function($grupo) {
            $primeraSolicitud = $grupo->first();
            
            $mostrarCliente = $primeraSolicitud->origen === 'entrada_proveedor' && $primeraSolicitud->cliente_general_id;
            
            return [
                'origen' => $primeraSolicitud->origen,
                'origen_id' => $primeraSolicitud->origen_id,
                'origen_especifico' => $primeraSolicitud->origen === 'compra' ? $primeraSolicitud->compra : $primeraSolicitud->entradaProveedor,
                'proveedor' => $primeraSolicitud->proveedor,
                'cliente_general' => $primeraSolicitud->clienteGeneral,
                'mostrar_cliente' => $mostrarCliente,
                'fecha_origen' => $primeraSolicitud->fecha_origen,
                'estado_general' => $this->calcularEstadoGeneral($grupo),
                'solicitudes' => $grupo->map(function($solicitud) {
                    Log::info("Solicitud ID: {$solicitud->idSolicitudIngreso}, Ubicaciones count: " . $solicitud->ubicaciones->count());
                    
                    if ($solicitud->ubicaciones->count() > 0) {
                        Log::info("Ubicaciones para solicitud {$solicitud->idSolicitudIngreso}:", 
                            $solicitud->ubicaciones->toArray());
                    }

                    // Cargar series existentes para esta solicitud
                    $series = ArticuloSerie::where('origen', $solicitud->origen)
                        ->where('origen_id', $solicitud->origen_id)
                        ->where('articulo_id', $solicitud->articulo_id)
                        ->get();
                    
                    return [
                        'idSolicitudIngreso' => $solicitud->idSolicitudIngreso,
                        'origen' => $solicitud->origen,
                        'origen_id' => $solicitud->origen_id,
                        'articulo_id' => $solicitud->articulo_id,
                        'articulo' => $solicitud->articulo,
                        'cantidad' => $solicitud->cantidad,
                        'estado' => $solicitud->estado,
                        'ubicacion' => $solicitud->ubicacion,
                        'ubicaciones' => $solicitud->ubicaciones->map(function($ubicacion) {
                            return [
                                'idArticuloUbicacion' => $ubicacion->idArticuloUbicacion,
                                'ubicacion_id' => $ubicacion->ubicacion_id,
                                'cantidad' => $ubicacion->cantidad,
                                'nombre_ubicacion' => $ubicacion->ubicacion ? $ubicacion->ubicacion->nombre : 'Ubicación no encontrada'
                            ];
                        }),
                        'series' => $series->map(function($serie) {
                            return [
                                'idArticuloSerie' => $serie->idArticuloSerie,
                                'numero_serie' => $serie->numero_serie,
                                'ubicacion_id' => $serie->ubicacion_id
                            ];
                        })
                    ];
                }),
                'total_articulos' => $grupo->count(),
                'total_cantidad' => $grupo->sum('cantidad'),
                'created_at' => $grupo->max('created_at')
            ];
        })->values();

        Log::info('Grupos de solicitudes creados: ' . $solicitudesAgrupadas->count());

        // Obtener todas las ubicaciones activas
        $ubicaciones = \App\Models\Ubicacion::whereHas('sucursal', function($query) {
            $query->where('estado', true);
        })->get();

        Log::info('Ubicaciones finales a enviar a la vista: ' . $ubicaciones->count());

        return view('solicitud.solicitudingreso.index', compact('solicitudesAgrupadas', 'ubicaciones'));
    }



public function guardarUbicacion(Request $request)
{
    try {
        DB::beginTransaction();

        $solicitud = SolicitudIngreso::findOrFail($request->solicitud_id);
        $articulo = \App\Models\Articulo::find($solicitud->articulo_id);
        
        // Validar que la suma de las cantidades sea igual a la cantidad total
        $totalDistribuido = collect($request->ubicaciones)->sum('cantidad');
        
        if ($totalDistribuido != $solicitud->cantidad) {
            return response()->json([
                'success' => false,
                'message' => 'La suma de las cantidades distribuidas ('.$totalDistribuido.') debe ser igual a la cantidad total ('.$solicitud->cantidad.')'
            ], 422);
        }

        // Verificar si el artículo requiere series
        $requiereSeries = $articulo && $articulo->maneja_serie === 1;

        Log::info("Artículo ID: {$solicitud->articulo_id}, Maneja serie: " . ($articulo ? $articulo->maneja_serie : 'N/A') . ", Requiere series: " . ($requiereSeries ? 'SÍ' : 'NO'));

        // Validar series si es requerido
        if ($requiereSeries) {
            if (!$request->has('series') || empty($request->series)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este artículo requiere números de serie'
                ], 422);
            }

            $totalSeries = count($request->series);
            if ($totalSeries != $solicitud->cantidad) {
                return response()->json([
                    'success' => false,
                    'message' => "Debe ingresar {$solicitud->cantidad} números de serie, recibidas: {$totalSeries}"
                ], 422);
            }

            // Validar que no haya series duplicadas
            $seriesNumeros = array_column($request->series, 'numero_serie');
            $seriesUnicas = array_unique($seriesNumeros);
            if (count($seriesUnicas) != count($seriesNumeros)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puede haber números de serie duplicados'
                ], 422);
            }

            // Validar que las series no existan ya en la base de datos para este artículo
            $seriesExistentes = ArticuloSerie::where('articulo_id', $solicitud->articulo_id)
                ->whereIn('numero_serie', $seriesNumeros)
                ->where(function($query) use ($solicitud) {
                    $query->where('origen', '!=', $solicitud->origen)
                          ->orWhere('origen_id', '!=', $solicitud->origen_id);
                })
                ->pluck('numero_serie')
                ->toArray();

            if (!empty($seriesExistentes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Los siguientes números de serie ya existen para este artículo: ' . implode(', ', $seriesExistentes)
                ], 422);
            }
        }

        $esPrimeraUbicacion = ($solicitud->estado !== 'ubicado');
        
        if ($esPrimeraUbicacion) {
            Log::info("Primera ubicación para solicitud ID: {$solicitud->idSolicitudIngreso}. Aumentando stock.");
        } else {
            Log::info("Re-ubicación para solicitud ID: {$solicitud->idSolicitudIngreso}. Stock ya fue aumentado anteriormente.");
        }

        // Eliminar ubicaciones existentes en rack_ubicacion_articulos para esta solicitud
        DB::table('rack_ubicacion_articulos')
            ->where('articulo_id', $solicitud->articulo_id)
            ->whereIn('rack_ubicacion_id', function($query) use ($solicitud) {
                $query->select('rack_ubicacion_id')
                      ->from('rack_ubicacion_articulos')
                      ->where('articulo_id', $solicitud->articulo_id);
            })
            ->delete();

        // Eliminar series existentes
        ArticuloSerie::where('origen', $solicitud->origen)
            ->where('articulo_id', $solicitud->articulo_id)
            ->where('origen_id', $solicitud->origen_id)
            ->delete();

        $nombresUbicaciones = [];
        
        // Guardar cada ubicación en rack_ubicacion_articulos
        foreach ($request->ubicaciones as $ubicacionData) {
            $rackUbicacionId = $ubicacionData['ubicacion_id'];
            
            // Verificar si la ubicación del rack existe
            $rackUbicacion = DB::table('rack_ubicaciones')
                ->where('idRackUbicacion', $rackUbicacionId)
                ->first();

            if (!$rackUbicacion) {
                throw new Exception("La ubicación del rack no existe");
            }

            // Verificar capacidad usando rack_ubicacion_articulos
            $cantidadActualEnUbicacion = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $rackUbicacionId)
                ->sum('cantidad');

            $capacidadDisponible = $rackUbicacion->capacidad_maxima - $cantidadActualEnUbicacion;

            if ($ubicacionData['cantidad'] > $capacidadDisponible) {
                throw new Exception("La cantidad excede la capacidad disponible de la ubicación {$rackUbicacion->codigo}. Capacidad disponible: {$capacidadDisponible}");
            }

            // Verificar si ya existe este artículo en esta ubicación (en rack_ubicacion_articulos)
            $articuloExistente = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $rackUbicacionId)
                ->where('articulo_id', $solicitud->articulo_id)
                ->first();

            if ($articuloExistente) {
                // Actualizar cantidad existente en rack_ubicacion_articulos
                DB::table('rack_ubicacion_articulos')
                    ->where('rack_ubicacion_id', $rackUbicacionId)
                    ->where('articulo_id', $solicitud->articulo_id)
                    ->update([
                        'cantidad' => $articuloExistente->cantidad + $ubicacionData['cantidad'],
                        'updated_at' => now()
                    ]);
            } else {
                // Insertar nuevo registro en rack_ubicacion_articulos
                DB::table('rack_ubicacion_articulos')->insert([
                    'rack_ubicacion_id' => $rackUbicacionId,
                    'articulo_id' => $solicitud->articulo_id,
                    'cantidad' => $ubicacionData['cantidad'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Actualizar estado de ocupación de la ubicación (en rack_ubicaciones)
            $nuevaCantidadTotalUbicacion = $cantidadActualEnUbicacion + $ubicacionData['cantidad'];
            $nuevoEstado = $this->calcularEstadoOcupacion($nuevaCantidadTotalUbicacion, $rackUbicacion->capacidad_maxima);

            DB::table('rack_ubicaciones')
                ->where('idRackUbicacion', $rackUbicacionId)
                ->update([
                    'estado_ocupacion' => $nuevoEstado,
                    'updated_at' => now()
                ]);

            $nombresUbicaciones[] = $rackUbicacion->codigo . ' (' . $ubicacionData['cantidad'] . ')';
        }

        // Guardar series sin ubicación
        $seriesGuardadas = [];
        if ($requiereSeries && !empty($request->series)) {
            Log::info("Guardando {$totalSeries} series para solicitud {$solicitud->idSolicitudIngreso}");
            
            foreach ($request->series as $serieData) {
                $serieBD = ArticuloSerie::create([
                    'origen' => $solicitud->origen,
                    'origen_id' => $solicitud->origen_id,
                    'articulo_id' => $solicitud->articulo_id,
                    'numero_serie' => $serieData['numero_serie'],
                    'estado' => 'activo'
                ]);

                $seriesGuardadas[] = [
                    'numero_serie' => $serieBD->numero_serie
                ];

                Log::info("Serie guardada: {$serieBD->numero_serie}");
            }
        }

        // Aumentar stock del artículo solo si es la primera vez
        if ($esPrimeraUbicacion && $articulo) {
            $stockAnterior = $articulo->stock_total;
            $nuevoStock = $stockAnterior + $solicitud->cantidad;
            
            $articulo->stock_total = $nuevoStock;
            $articulo->save();
            
            Log::info("Stock actualizado - Artículo ID: {$articulo->idArticulos}");
            Log::info("Stock anterior: {$stockAnterior}, Cantidad añadida: {$solicitud->cantidad}, Nuevo stock: {$nuevoStock}");
        }

        // Registrar movimiento en rack_movimientos
        $rackInfo = DB::table('rack_ubicaciones')
            ->where('idRackUbicacion', $request->ubicaciones[0]['ubicacion_id'])
            ->join('racks', 'rack_ubicaciones.rack_id', '=', 'racks.idRack')
            ->first();

        if ($rackInfo) {
            DB::table('rack_movimientos')->insert([
                'articulo_id' => $solicitud->articulo_id,
                'ubicacion_destino_id' => $request->ubicaciones[0]['ubicacion_id'],
                'rack_destino_id' => $rackInfo->rack_id,
                'cantidad' => $solicitud->cantidad,
                'tipo_movimiento' => 'entrada',
                'observaciones' => 'Ingreso desde solicitud: ' . ($solicitud->origen === 'compra' ? 
                    $solicitud->compra->codigocompra : $solicitud->entradaProveedor->codigo_entrada),
                'codigo_ubicacion_destino' => $rackInfo->codigo,
                'nombre_rack_destino' => $rackInfo->nombre,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Actualizar solicitud
        $ubicacionTexto = !empty($nombresUbicaciones) ? implode(', ', $nombresUbicaciones) : 'Sin ubicación';
        
        $solicitud->ubicacion = $ubicacionTexto;
        $solicitud->estado = 'ubicado';
        $solicitud->save();

        // VERIFICAR SI TODOS LOS ARTÍCULOS DE LA COMPRA/ENTRADA ESTÁN UBICADOS
        $todosUbicados = $this->verificarTodosArticulosUbicados($solicitud);

        if ($todosUbicados) {
            // Actualizar el estado de la compra o entrada proveedor a 'aprobado'
            if ($solicitud->origen === 'compra') {
                DB::table('compra')
                    ->where('idCompra', $solicitud->origen_id)
                    ->update([
                        'estado' => 'aprobado',
                        'updated_at' => now()
                    ]);
                Log::info("✅ COMPRA APROBADA - Todos los artículos ubicados. Compra ID: {$solicitud->origen_id}");
            } elseif ($solicitud->origen === 'entrada_proveedor') {
                DB::table('entradas_proveedores')
                    ->where('id', $solicitud->origen_id)
                    ->update([
                        'estado' => 'aprobado',
                        'updated_at' => now()
                    ]);
                Log::info("✅ ENTRADA PROVEEDOR APROBADA - Todos los artículos ubicados. Entrada ID: {$solicitud->origen_id}");
            }
        }

        DB::commit();

        // Retornar respuesta con las ubicaciones actualizadas desde rack_ubicacion_articulos
        $ubicacionesActualizadas = DB::table('rack_ubicacion_articulos as rua')
            ->join('rack_ubicaciones as ru', 'rua.rack_ubicacion_id', '=', 'ru.idRackUbicacion')
            ->where('rua.articulo_id', $solicitud->articulo_id)
            ->select(
                'rua.rack_ubicacion_id as ubicacion_id',
                'rua.cantidad',
                'ru.codigo as nombre_ubicacion'
            )
            ->get()
            ->map(function($ubicacion) {
                return [
                    'ubicacion_id' => $ubicacion->ubicacion_id,
                    'cantidad' => $ubicacion->cantidad,
                    'nombre_ubicacion' => $ubicacion->nombre_ubicacion
                ];
            });

        $mensaje = 'Artículo ubicado correctamente en '.count($request->ubicaciones).' ubicación(es)';
        if ($requiereSeries) {
            $mensaje .= ' con '.count($seriesGuardadas).' número(s) de serie';
        }
        if ($esPrimeraUbicacion) {
            $mensaje .= ' y stock actualizado';
        }
        if ($todosUbicados) {
            $mensaje .= '. ¡Todos los artículos han sido ubicados!';
        }

        return response()->json([
            'success' => true,
            'message' => $mensaje,
            'ubicaciones' => $ubicacionesActualizadas,
            'series' => $seriesGuardadas,
            'ubicacion_texto' => $ubicacionTexto,
            'stock_actualizado' => $esPrimeraUbicacion,
            'todos_ubicados' => $todosUbicados
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al guardar ubicación: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json([
            'success' => false,
            'message' => 'Error al guardar la ubicación: '.$e->getMessage()
        ], 500);
    }
}
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



private function verificarTodosArticulosUbicados($solicitud)
{
    try {
        // Obtener todas las solicitudes del mismo origen y origen_id
        $solicitudesDelMismoOrigen = SolicitudIngreso::where('origen', $solicitud->origen)
            ->where('origen_id', $solicitud->origen_id)
            ->get();

        $totalSolicitudes = $solicitudesDelMismoOrigen->count();
        $solicitudesUbicadas = $solicitudesDelMismoOrigen->where('estado', 'ubicado')->count();

        $todosUbicados = ($totalSolicitudes > 0) && ($solicitudesUbicadas === $totalSolicitudes);

        Log::info("Verificación ubicación - Origen: {$solicitud->origen}, ID: {$solicitud->origen_id}");
        Log::info("Total solicitudes: {$totalSolicitudes}, Ubicadas: {$solicitudesUbicadas}, Todos ubicados: " . ($todosUbicados ? 'SÍ' : 'NO'));

        return $todosUbicados;

    } catch (\Exception $e) {
        Log::error('Error al verificar artículos ubicados: ' . $e->getMessage());
        return false;
    }
}







    private function calcularEstadoGeneral($solicitudes)
    {
        $estados = $solicitudes->pluck('estado')->unique();
        
        if ($estados->count() === 1) {
            return $estados->first();
        }
        
        if ($estados->contains('pendiente')) {
            return 'pendiente';
        }
        
        if ($estados->contains('recibido')) {
            return 'recibido';
        }
        
        return 'ubicado';
    }

    public function cambiarEstado(Request $request, $id)
    {
        try {
            $solicitud = SolicitudIngreso::findOrFail($id);
            $solicitud->estado = $request->estado;
            $solicitud->save();

            return response()->json([
                'success' => true, 
                'message' => 'Estado actualizado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el estado'
            ], 500);
        }
    }


public function sugerirUbicacionesMejorado($articuloId, $cantidad)
{
    try {
        Log::info("=== SUGERENCIAS MEJORADAS DE UBICACIÓN ===");
        Log::info("Artículo ID: {$articuloId}, Cantidad requerida: {$cantidad}");

        // PRIMERO: Buscar ubicaciones existentes del artículo EN rack_ubicacion_articulos
        $ubicacionesExistentes = DB::table('rack_ubicacion_articulos as rua')
            ->join('rack_ubicaciones as ru', 'rua.rack_ubicacion_id', '=', 'ru.idRackUbicacion')
            ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
            ->where('rua.articulo_id', $articuloId)
            ->where('r.estado', 'activo')
            ->select(
                'ru.idRackUbicacion',
                'ru.codigo',
                'rua.cantidad as cantidad_actual',
                'ru.capacidad_maxima',
                'r.nombre as rack_nombre',
                'r.sede'
            )
            ->get()
            ->map(function($ubicacion) use ($cantidad) {
                $espacioDisponible = $ubicacion->capacidad_maxima - $ubicacion->cantidad_actual;
                return [
                    'id' => $ubicacion->idRackUbicacion,
                    'codigo' => $ubicacion->codigo,
                    'rack_nombre' => $ubicacion->rack_nombre,
                    'sede' => $ubicacion->sede,
                    'cantidad_actual' => $ubicacion->cantidad_actual,
                    'capacidad_maxima' => $ubicacion->capacidad_maxima,
                    'espacio_disponible' => $espacioDisponible,
                    'tipo' => 'existente',
                    'prioridad' => 1,
                    'puede_almacenar_completo' => $espacioDisponible >= $cantidad
                ];
            });

        Log::info("Ubicaciones existentes: " . $ubicacionesExistentes->count());

        // CASO 1: Ubicaciones existentes pueden almacenar TODO
        $puedenAlmacenarCompleto = $ubicacionesExistentes->where('puede_almacenar_completo', true);
        
        if ($puedenAlmacenarCompleto->isNotEmpty()) {
            Log::info("CASO 1: Ubicaciones existentes pueden almacenar cantidad completa");
            return $this->formatearRespuesta($puedenAlmacenarCompleto->values(), 'existentes_completas', 
                "Ubicaciones existentes con espacio suficiente");
        }

        // CASO 2: Ubicaciones existentes tienen espacio INSUFICIENTE
        $tienenEspacioInsuficiente = $ubicacionesExistentes->where('espacio_disponible', '>', 0)
            ->where('puede_almacenar_completo', false);

        if ($tienenEspacioInsuficiente->isNotEmpty()) {
            Log::info("CASO 2: Ubicaciones existentes tienen espacio insuficiente");
            
            // Calcular cantidad faltante
            $espacioTotalExistente = $tienenEspacioInsuficiente->sum('espacio_disponible');
            $cantidadFaltante = $cantidad - $espacioTotalExistente;
            
            Log::info("Espacio total en existentes: {$espacioTotalExistente}, Cantidad faltante: {$cantidadFaltante}");

            if ($cantidadFaltante > 0) {
                // Buscar ubicaciones vacías para la cantidad faltante
                $ubicacionesVacias = $this->obtenerUbicacionesVacias($cantidadFaltante);
                
                $sugerenciasCombinadas = $tienenEspacioInsuficiente
                    ->merge($ubicacionesVacias)
                    ->sortBy('prioridad')
                    ->values();

                return $this->formatearRespuesta($sugerenciasCombinadas, 'combinada_existentes_vacias',
                    "Ubicaciones existentes (espacio insuficiente) + ubicaciones vacías para completar");
            }
        }

        // CASO 3: No hay ubicaciones existentes - Buscar múltiples ubicaciones vacías
        Log::info("CASO 3: Buscando ubicaciones vacías para cantidad completa: {$cantidad}");
        $ubicacionesVaciasCompletas = $this->obtenerUbicacionesVacias($cantidad);

        if ($ubicacionesVaciasCompletas->isNotEmpty()) {
            return $this->formatearRespuesta($ubicacionesVaciasCompletas, 'solo_vacias_completas',
                "Ubicaciones vacías disponibles para cantidad completa");
        }

        // CASO 4: Buscar múltiples ubicaciones vacías que sumen la cantidad requerida
        Log::info("CASO 4: Buscando combinación de ubicaciones vacías");
        $combinacionUbicaciones = $this->buscarCombinacionUbicaciones($cantidad);
        
        if ($combinacionUbicaciones->isNotEmpty()) {
            return $this->formatearRespuesta($combinacionUbicaciones, 'combinada_vacias',
                "Combinación de ubicaciones vacías que suman la cantidad requerida");
        }

        // CASO 5: Sin sugerencias
        Log::info("CASO 5: No hay ubicaciones disponibles");
        return $this->formatearRespuesta(collect([]), 'sin_sugerencias',
            "No hay ubicaciones disponibles para la cantidad requerida");

    } catch (\Exception $e) {
        Log::error('Error en sugerirUbicacionesMejorado: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener sugerencias de ubicación: ' . $e->getMessage()
        ], 500);
    }
}

// Método para buscar combinación de ubicaciones vacías
private function buscarCombinacionUbicaciones($cantidadRequerida)
{
    try {
        // Buscar ubicaciones con espacio disponible usando rack_ubicacion_articulos
        $ubicacionesConEspacio = DB::table('rack_ubicaciones as ru')
            ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
            ->leftJoin('rack_ubicacion_articulos as rua', 'ru.idRackUbicacion', '=', 'rua.rack_ubicacion_id')
            ->where('r.estado', 'activo')
            ->select(
                'ru.idRackUbicacion',
                'ru.codigo',
                'ru.capacidad_maxima',
                'r.nombre as rack_nombre',
                'r.sede',
                DB::raw('COALESCE(SUM(rua.cantidad), 0) as cantidad_ocupada')
            )
            ->groupBy('ru.idRackUbicacion', 'ru.codigo', 'ru.capacidad_maxima', 'r.nombre', 'r.sede')
            ->get()
            ->map(function($ubicacion) {
                $espacioDisponible = $ubicacion->capacidad_maxima - $ubicacion->cantidad_ocupada;
                return [
                    'id' => $ubicacion->idRackUbicacion,
                    'codigo' => $ubicacion->codigo,
                    'rack_nombre' => $ubicacion->rack_nombre,
                    'sede' => $ubicacion->sede,
                    'cantidad_actual' => $ubicacion->cantidad_ocupada,
                    'capacidad_maxima' => $ubicacion->capacidad_maxima,
                    'espacio_disponible' => $espacioDisponible,
                    'tipo' => 'nueva',
                    'prioridad' => 2
                ];
            })
            ->where('espacio_disponible', '>', 0)
            ->sortByDesc('espacio_disponible')
            ->values();

        Log::info("Ubicaciones con espacio disponible para combinación: " . $ubicacionesConEspacio->count());

        // Algoritmo simple: tomar las ubicaciones con más espacio hasta cubrir la cantidad
        $combinacion = collect([]);
        $totalAcumulado = 0;
        
        foreach ($ubicacionesConEspacio as $ubicacion) {
            if ($totalAcumulado >= $cantidadRequerida) break;
            
            $combinacion->push($ubicacion);
            $totalAcumulado += $ubicacion['espacio_disponible'];
        }

        Log::info("Combinación encontrada - Total acumulado: {$totalAcumulado}, Requerido: {$cantidadRequerida}");

        // Solo retornar si la combinación cubre al menos el 80% de lo requerido
        if ($totalAcumulado >= $cantidadRequerida * 0.8) {
            return $combinacion;
        }

        return collect([]);

    } catch (\Exception $e) {
        Log::error('Error en buscarCombinacionUbicaciones: ' . $e->getMessage());
        return collect([]);
    }
}

private function obtenerUbicacionesVacias($cantidad)
{
    try {
        Log::info("Buscando ubicaciones vacías con capacidad >= {$cantidad}");
        
        // Verificar disponibilidad usando rack_ubicacion_articulos
        $ubicaciones = DB::table('rack_ubicaciones as ru')
            ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
            ->leftJoin('rack_ubicacion_articulos as rua', 'ru.idRackUbicacion', '=', 'rua.rack_ubicacion_id')
            ->where('r.estado', 'activo')
            ->where('ru.capacidad_maxima', '>=', $cantidad)
            ->select(
                'ru.idRackUbicacion',
                'ru.codigo',
                'ru.capacidad_maxima',
                'r.nombre as rack_nombre',
                'r.sede',
                DB::raw('COALESCE(SUM(rua.cantidad), 0) as cantidad_ocupada')
            )
            ->groupBy('ru.idRackUbicacion', 'ru.codigo', 'ru.capacidad_maxima', 'r.nombre', 'r.sede')
            ->havingRaw('ru.capacidad_maxima - COALESCE(SUM(rua.cantidad), 0) >= ?', [$cantidad])
            ->get();

        Log::info("Ubicaciones vacías encontradas: " . $ubicaciones->count());

        return $ubicaciones->map(function($ubicacion) {
            $espacioDisponible = $ubicacion->capacidad_maxima - $ubicacion->cantidad_ocupada;
            return [
                'id' => $ubicacion->idRackUbicacion,
                'codigo' => $ubicacion->codigo,
                'rack_nombre' => $ubicacion->rack_nombre,
                'sede' => $ubicacion->sede,
                'cantidad_actual' => $ubicacion->cantidad_ocupada,
                'capacidad_maxima' => $ubicacion->capacidad_maxima,
                'espacio_disponible' => $espacioDisponible,
                'tipo' => 'nueva',
                'prioridad' => 2
            ];
        });

    } catch (\Exception $e) {
        Log::error('ERROR en obtenerUbicacionesVacias: ' . $e->getMessage());
        return collect([]);
    }
}

// Método auxiliar para formatear respuesta
private function formatearRespuesta($sugerencias, $tipo, $mensaje)
{
    return response()->json([
        'success' => true,
        'sugerencias' => $sugerencias,
        'total_sugerencias' => $sugerencias->count(),
        'tipo_sugerencia' => $tipo,
        'mensaje' => $mensaje,
        'debug' => [
            'tipo_caso' => $tipo,
            'cantidad_sugerencias' => $sugerencias->count()
        ]
    ]);
}

public function actualizarSolicitud(Request $request, $id)
{
    try {
        DB::beginTransaction();

        $solicitud = SolicitudIngreso::findOrFail($id);
        
        // Validar que no esté ubicado
        if ($solicitud->estado === 'ubicado') {
            return response()->json([
                'success' => false,
                'message' => 'No se puede actualizar una solicitud ya ubicada'
            ], 422);
        }

        // Validar solo los campos editables
        $request->validate([
            'cantidad' => 'required|integer|min:1',
            'observaciones' => 'nullable|string'
        ]);

        // Guardar la cantidad anterior para cálculos
        $cantidadAnterior = $solicitud->cantidad;
        $nuevaCantidad = $request->cantidad;

        // Actualizar la solicitud
        $solicitud->update([
            'cantidad' => $nuevaCantidad,
            'observaciones' => $request->observaciones,
            'estado' => 'actualizar'
        ]);

        // Actualizar según el origen (compra o entrada_proveedor)
        if ($solicitud->origen === 'compra') {
            $detalleId = $this->actualizarDetalleCompra($solicitud);
            $this->actualizarInventarioIngresosClientes($solicitud, 'compra', $detalleId);
        } elseif ($solicitud->origen === 'entrada_proveedor') {
            $detalleId = $this->actualizarDetalleEntradaProveedor($solicitud);
            $this->actualizarInventarioIngresosClientes($solicitud, 'entrada_proveedor', $detalleId);
        }

        DB::commit();

        Log::info("Solicitud ID: {$id} actualizada correctamente. Nueva cantidad: {$nuevaCantidad}");

        return response()->json([
            'success' => true,
            'message' => 'Solicitud actualizada correctamente y precios recalculados',
            'solicitud' => $solicitud
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al actualizar solicitud: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar la solicitud: '.$e->getMessage()
        ], 500);
    }
}

private function actualizarDetalleCompra($solicitud)
{
    try {
        // Buscar el detalle de compra usando DB
        $detalleCompra = DB::table('detalle_compra')
            ->where('idCompra', $solicitud->origen_id)
            ->where('idProducto', $solicitud->articulo_id)
            ->first();

        if ($detalleCompra) {
            // Recalcular subtotal con el mismo precio unitario
            $nuevoSubtotal = $solicitud->cantidad * $detalleCompra->precio;

            // Actualizar detalle_compra usando DB
            DB::table('detalle_compra')
                ->where('idDetalleCompra', $detalleCompra->idDetalleCompra)
                ->update([
                    'cantidad' => $solicitud->cantidad,
                    'subtotal' => $nuevoSubtotal,
                    'updated_at' => now()
                ]);

            // Actualizar el estado de la compra a 'actualizado_almacen'
            DB::table('compra')
                ->where('idCompra', $solicitud->origen_id)
                ->update([
                    'estado' => 'actualizado_almacen',
                    'updated_at' => now()
                ]);

            Log::info("Detalle compra actualizado - Compra ID: {$solicitud->origen_id}, Artículo ID: {$solicitud->articulo_id}");
            Log::info("Nueva cantidad: {$solicitud->cantidad}, Nuevo subtotal: {$nuevoSubtotal}");

            return $detalleCompra->idDetalleCompra;
        } else {
            Log::warning("No se encontró detalle de compra para origen_id: {$solicitud->origen_id}, articulo_id: {$solicitud->articulo_id}");
            return null;
        }

    } catch (\Exception $e) {
        Log::error('Error al actualizar detalle compra: ' . $e->getMessage());
        throw $e;
    }
}

private function actualizarDetalleEntradaProveedor($solicitud)
{
    try {
        // Buscar el detalle de entrada proveedor usando DB
        $detalleEntrada = DB::table('entradas_proveedores_detalle')
            ->where('entrada_id', $solicitud->origen_id)
            ->where('articulo_id', $solicitud->articulo_id)
            ->first();

        if ($detalleEntrada) {
            // Recalcular subtotal con el mismo precio unitario
            $nuevoSubtotal = $solicitud->cantidad * $detalleEntrada->precio_unitario;

            // Actualizar entradas_proveedores_detalle usando DB
            DB::table('entradas_proveedores_detalle')
                ->where('id', $detalleEntrada->id)
                ->update([
                    'cantidad' => $solicitud->cantidad,
                    'subtotal' => $nuevoSubtotal,
                    'updated_at' => now()
                ]);

            // Actualizar el estado de la entrada proveedor a 'actualizado_almacen'
            DB::table('entradas_proveedores')
                ->where('id', $solicitud->origen_id)
                ->update([
                    'estado' => 'actualizado_almacen',
                    'updated_at' => now()
                ]);

            Log::info("Detalle entrada proveedor actualizado - Entrada ID: {$solicitud->origen_id}, Artículo ID: {$solicitud->articulo_id}");
            Log::info("Nueva cantidad: {$solicitud->cantidad}, Nuevo subtotal: {$nuevoSubtotal}");

            return $detalleEntrada->id;
        } else {
            Log::warning("No se encontró detalle de entrada proveedor para origen_id: {$solicitud->origen_id}, articulo_id: {$solicitud->articulo_id}");
            return null;
        }

    } catch (\Exception $e) {
        Log::error('Error al actualizar detalle entrada proveedor: ' . $e->getMessage());
        throw $e;
    }
}

private function actualizarInventarioIngresosClientes($solicitud, $tipoIngreso, $ingresoId)
{
    try {
        if (!$ingresoId) {
            Log::warning("No se puede actualizar inventario_ingresos_clientes sin ingresoId");
            return;
        }

        // Determinar compra_id según el tipo de ingreso
        $compraId = null;
        if ($tipoIngreso === 'compra') {
            $compraId = $solicitud->origen_id;
        } else {
            // Para entrada_proveedor, el compra_id sería el id de la entrada_proveedor
            $compraId = $solicitud->origen_id;
        }

        // Buscar si ya existe un registro en inventario_ingresos_clientes
        $inventarioExistente = DB::table('inventario_ingresos_clientes')
            ->where('ingreso_id', $ingresoId)
            ->where('articulo_id', $solicitud->articulo_id)
            ->where('tipo_ingreso', $tipoIngreso)
            ->first();

        if ($inventarioExistente) {
            // Actualizar registro existente
            DB::table('inventario_ingresos_clientes')
                ->where('id', $inventarioExistente->id)
                ->update([
                    'cantidad' => $solicitud->cantidad,
                    'compra_id' => $compraId,
                    'cliente_general_id' => $solicitud->cliente_general_id,
                    'updated_at' => now()
                ]);

            Log::info("Inventario ingresos clientes ACTUALIZADO - ID: {$inventarioExistente->id}, Nueva cantidad: {$solicitud->cantidad}");
        } else {
            // Crear nuevo registro
            $nuevoId = DB::table('inventario_ingresos_clientes')->insertGetId([
                'compra_id' => $compraId,
                'articulo_id' => $solicitud->articulo_id,
                'tipo_ingreso' => $tipoIngreso,
                'ingreso_id' => $ingresoId,
                'cliente_general_id' => $solicitud->cliente_general_id,
                'cantidad' => $solicitud->cantidad,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            Log::info("Inventario ingresos clientes CREADO - ID: {$nuevoId}, Cantidad: {$solicitud->cantidad}");
        }

        Log::info("Inventario ingresos clientes actualizado - Tipo: {$tipoIngreso}, Ingreso ID: {$ingresoId}, Artículo: {$solicitud->articulo_id}");

    } catch (\Exception $e) {
        Log::error('Error al actualizar inventario_ingresos_clientes: ' . $e->getMessage());
        throw $e;
    }
}




}