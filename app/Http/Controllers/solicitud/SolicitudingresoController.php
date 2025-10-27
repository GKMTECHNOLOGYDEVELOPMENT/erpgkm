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
            // ⬇️ Aseguramos subcategoría y el idsubcategoria
            'articulo:idArticulos,nombre,codigo_barras,codigo_repuesto,idTipoArticulo,maneja_serie,idsubcategoria',
            'articulo.subcategoria:id,nombre',

            'proveedor:idProveedor,nombre,numeroDocumento',
            'clienteGeneral:idClienteGeneral,descripcion,estado',
            'compra:idCompra,codigocompra,estado,fechaEmision,proveedor_id',
            'entradaProveedor:id,codigo_entrada,estado,fecha_ingreso,tipo_entrada,cliente_general_id',
            'ubicaciones.ubicacion:idUbicacion,nombre'
        ])->orderBy('created_at', 'desc')->get();

        Log::info('Total de solicitudes encontradas: ' . $solicitudes->count());

        // Filtrar: Solo compras con estado 'enviado_almacen' / 'actualizado_almacen' y todas las entradas proveedor
        $solicitudesFiltradas = $solicitudes->filter(function ($solicitud) {
            if ($solicitud->origen === 'compra') {
                return $solicitud->compra &&
                    ($solicitud->compra->estado === 'enviado_almacen' ||
                        $solicitud->compra->estado === 'actualizado_almacen');
            }
            return true;
        });

        Log::info('Solicitudes después del filtro: ' . $solicitudesFiltradas->count());

        // Agrupar solicitudes filtradas por origen y origen_id
        $solicitudesAgrupadas = $solicitudesFiltradas->groupBy(function ($solicitud) {
            return $solicitud->origen . '_' . $solicitud->origen_id;
        })->map(function ($grupo) {

            $primeraSolicitud = $grupo->first();
            $mostrarCliente   = $primeraSolicitud->origen === 'entrada_proveedor' && $primeraSolicitud->cliente_general_id;

            // Mapear solicitudes y marcar si son "panel"
            $solicitudesMap = $grupo->map(function ($solicitud) {

                Log::info("Solicitud ID: {$solicitud->idSolicitudIngreso}, Ubicaciones count: " . $solicitud->ubicaciones->count());
                if ($solicitud->ubicaciones->count() > 0) {
                    Log::info("Ubicaciones para solicitud {$solicitud->idSolicitudIngreso}:", $solicitud->ubicaciones->toArray());
                }

                // Series existentes
                $series = ArticuloSerie::where('origen', $solicitud->origen)
                    ->where('origen_id', $solicitud->origen_id)
                    ->where('articulo_id', $solicitud->articulo_id)
                    ->get();

                // ========= NUEVO: detectar si el artículo es PANEL por subcategoría =========
                $subNombre = optional(optional($solicitud->articulo)->subcategoria)->nombre;
                $esPanel   = is_string($subNombre) && trim(mb_strtoupper($subNombre)) === 'PANEL';

                return [
                    'idSolicitudIngreso' => $solicitud->idSolicitudIngreso,
                    'origen'             => $solicitud->origen,
                    'origen_id'          => $solicitud->origen_id,
                    'articulo_id'        => $solicitud->articulo_id,
                    'articulo'           => $solicitud->articulo,          // incluye subcategoria si viene cargada
                    'subcategoria_nombre' => $subNombre,                     // ✅ expuesto directo
                    'es_panel'           => $esPanel,                       // ✅ flag listo para el front
                    'cantidad'           => $solicitud->cantidad,
                    'estado'             => $solicitud->estado,
                    'ubicacion'          => $solicitud->ubicacion,
                    'ubicaciones'        => $solicitud->ubicaciones->map(function ($ubicacion) {
                        return [
                            'idArticuloUbicacion' => $ubicacion->idArticuloUbicacion,
                            'ubicacion_id'        => $ubicacion->ubicacion_id,
                            'cantidad'            => $ubicacion->cantidad,
                            'nombre_ubicacion'    => $ubicacion->ubicacion ? $ubicacion->ubicacion->nombre : 'Ubicación no encontrada',
                        ];
                    }),
                    'series' => $series->map(function ($serie) {
                        return [
                            'idArticuloSerie' => $serie->idArticuloSerie,
                            'numero_serie'    => $serie->numero_serie,
                            'ubicacion_id'    => $serie->ubicacion_id,
                        ];
                    }),
                ];
            });

            // ========= NUEVO: resumen de paneles por grupo =========
            $tienePanel         = $solicitudesMap->contains(function ($s) {
                return $s['es_panel'] === true;
            });
            $panelesPendientes  = $solicitudesMap->filter(function ($s) {
                return $s['es_panel'] === true && $s['estado'] !== 'ubicado';
            })->count();

            return [
                'origen'            => $primeraSolicitud->origen,
                'origen_id'         => $primeraSolicitud->origen_id,
                'origen_especifico' => $primeraSolicitud->origen === 'compra' ? $primeraSolicitud->compra : $primeraSolicitud->entradaProveedor,
                'proveedor'         => $primeraSolicitud->proveedor,
                'cliente_general'   => $primeraSolicitud->clienteGeneral,
                'mostrar_cliente'   => $mostrarCliente,
                'fecha_origen'      => $primeraSolicitud->fecha_origen,
                'estado_general'    => $this->calcularEstadoGeneral($grupo),
                'solicitudes'       => $solicitudesMap,
                'total_articulos'   => $grupo->count(),
                'total_cantidad'    => $grupo->sum('cantidad'),
                'created_at'        => $grupo->max('created_at'),

                // ✅ Campos extra para el front
                'tiene_panel'       => $tienePanel,
                'paneles_pendientes' => $panelesPendientes,
            ];
        })->values();

        Log::info('Grupos de solicitudes creados: ' . $solicitudesAgrupadas->count());

        // Ubicaciones activas
        $ubicaciones = \App\Models\Ubicacion::whereHas('sucursal', function ($query) {
            $query->where('estado', true);
        })->get();

        Log::info('Ubicaciones finales a enviar a la vista: ' . $ubicaciones->count());

        return view('solicitud.solicitudingreso.index', compact('solicitudesAgrupadas', 'ubicaciones'));
    }



public function guardarUbicacion(Request $request)
{
    try {
        Log::info("=== 🚀 INICIANDO guardarUbicacion ===");
        DB::beginTransaction();

        $solicitud = SolicitudIngreso::findOrFail($request->solicitud_id);
        $articulo = \App\Models\Articulo::find($solicitud->articulo_id);

        Log::info("Solicitud ID: {$solicitud->idSolicitudIngreso}, Artículo ID: {$solicitud->articulo_id}, Cliente General ID: {$solicitud->cliente_general_id}");

        // ✅ Validar que la suma de las cantidades sea igual a la cantidad total
        $totalDistribuido = collect($request->ubicaciones)->sum('cantidad');
        if ($totalDistribuido != $solicitud->cantidad) {
            return response()->json([
                'success' => false,
                'message' => "La suma de las cantidades distribuidas ($totalDistribuido) debe ser igual a la cantidad total ({$solicitud->cantidad})"
            ], 422);
        }

        // ✅ Verificar si el artículo requiere series
        $requiereSeries = $articulo && $articulo->maneja_serie === 1;
        Log::info("Requiere series: " . ($requiereSeries ? 'SÍ' : 'NO'));

        // 🔍 Validar series si se requiere
        if ($requiereSeries) {
            if (!$request->has('series') || empty($request->series)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La suma de las cantidades distribuidas (' . $totalDistribuido . ') debe ser igual a la cantidad total (' . $solicitud->cantidad . ')'
                ], 422);
            }

            // Verificar si el artículo requiere seriesobtenerSugerenciasUbicaciones
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
                    ->where(function ($query) use ($solicitud) {
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

            // Validar duplicados
            $seriesNumeros = array_column($request->series, 'numero_serie');
            $seriesUnicas = array_unique($seriesNumeros);
            if (count($seriesUnicas) != count($seriesNumeros)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puede haber números de serie duplicados'
                ], 422);
            }

            // Validar si ya existen
            $seriesExistentes = ArticuloSerie::where('articulo_id', $solicitud->articulo_id)
                ->whereIn('numero_serie', $seriesNumeros)
                ->pluck('numero_serie')
                ->toArray();

            if (!empty($seriesExistentes)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Los siguientes números de serie ya existen: ' . implode(', ', $seriesExistentes)
                ], 422);
            }
        }

        $esPrimeraUbicacion = ($solicitud->estado !== 'ubicado');
        Log::info("¿Primera ubicación?: " . ($esPrimeraUbicacion ? 'Sí' : 'No'));

        // ⚙️ Eliminar solo ubicaciones con cantidad = 0 para este artículo y cliente
        Log::info("🔍 Buscando ubicaciones con cantidad 0 para eliminar...");
        $ubicacionesCero = DB::table('rack_ubicacion_articulos')
            ->where('articulo_id', $solicitud->articulo_id)
            ->where('cliente_general_id', $solicitud->cliente_general_id)
            ->where('cantidad', '<=', 0)
            ->pluck('idRackUbicacionArticulo')
            ->toArray();

        if (!empty($ubicacionesCero)) {
            Log::info("🗑️ Eliminando ubicaciones vacías: " . implode(', ', $ubicacionesCero));
            DB::table('rack_ubicacion_articulos')
                ->whereIn('idRackUbicacionArticulo', $ubicacionesCero)
                ->delete();
        } else {
            Log::info("✅ No hay ubicaciones con cantidad 0 para eliminar.");
        }

        // 🧾 Eliminar series existentes de esta solicitud
        ArticuloSerie::where('origen', $solicitud->origen)
            ->where('articulo_id', $solicitud->articulo_id)
            ->where('origen_id', $solicitud->origen_id)
            ->delete();

        $nombresUbicaciones = [];

        // 🔁 Recorrer ubicaciones enviadas
        foreach ($request->ubicaciones as $ubicacionData) {
            $rackUbicacionId = $ubicacionData['ubicacion_id'];
            Log::info("➡️ Procesando ubicación {$rackUbicacionId} para artículo {$solicitud->articulo_id}");

            $rackUbicacion = DB::table('rack_ubicaciones')
                ->where('idRackUbicacion', $rackUbicacionId)
                ->first();

            if (!$rackUbicacion) {
                throw new Exception("La ubicación del rack no existe");
            }

            // Verificar capacidad
            $cantidadActualEnUbicacion = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $rackUbicacionId)
                ->sum('cantidad');

            $capacidadDisponible = $rackUbicacion->capacidad_maxima - $cantidadActualEnUbicacion;
            Log::info("Capacidad disponible en {$rackUbicacion->codigo}: {$capacidadDisponible}");

            if ($ubicacionData['cantidad'] > $capacidadDisponible) {
                throw new Exception("La cantidad excede la capacidad disponible de la ubicación {$rackUbicacion->codigo}. Capacidad disponible: {$capacidadDisponible}");
            }

            // 🧩 Verificar si ya existe combinación (ubicación + artículo + cliente)
            $articuloExistente = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $rackUbicacionId)
                ->where('articulo_id', $solicitud->articulo_id)
                ->where('cliente_general_id', $solicitud->cliente_general_id)
                ->first();

            if ($articuloExistente) {
                Log::info("🔄 Actualizando cantidad existente en ubicación {$rackUbicacionId}");
                DB::table('rack_ubicacion_articulos')
                    ->where('idRackUbicacionArticulo', $articuloExistente->idRackUbicacionArticulo)
                    ->update([
                        'cantidad' => $articuloExistente->cantidad + $ubicacionData['cantidad'],
                        'updated_at' => now()
                    ]);
            } else {
                Log::info("🆕 Insertando nuevo registro (Ubicación {$rackUbicacionId}, Artículo {$solicitud->articulo_id}, Cliente {$solicitud->cliente_general_id})");
                DB::table('rack_ubicacion_articulos')->insert([
                    'rack_ubicacion_id' => $rackUbicacionId,
                    'articulo_id' => $solicitud->articulo_id,
                    'cliente_general_id' => $solicitud->cliente_general_id,
                    'cantidad' => $ubicacionData['cantidad'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // 🧮 Actualizar estado de ocupación
            $nuevaCantidadTotalUbicacion = $cantidadActualEnUbicacion + $ubicacionData['cantidad'];
            $nuevoEstado = $this->calcularEstadoOcupacion($nuevaCantidadTotalUbicacion, $rackUbicacion->capacidad_maxima);

            $solicitud->ubicacion = $ubicacionTexto;
            $solicitud->estado = 'ubicado';
            $solicitud->save();

            $nombresUbicaciones[] = $rackUbicacion->codigo . " ({$ubicacionData['cantidad']})";
        }

        // 🧾 Guardar series si aplica
        $seriesGuardadas = [];
        if ($requiereSeries && !empty($request->series)) {
            foreach ($request->series as $serieData) {
                $serieBD = ArticuloSerie::create([
                    'origen' => $solicitud->origen,
                    'origen_id' => $solicitud->origen_id,
                    'articulo_id' => $solicitud->articulo_id,
                    'numero_serie' => $serieData['numero_serie'],
                    'estado' => 'activo'
                ]);
                $seriesGuardadas[] = ['numero_serie' => $serieBD->numero_serie];
            }

        // 📈 Actualizar stock si es primera ubicación
        if ($esPrimeraUbicacion && $articulo) {
            $stockAnterior = $articulo->stock_total;
            $nuevoStock = $stockAnterior + $solicitud->cantidad;
            $articulo->stock_total = $nuevoStock;
            $articulo->save();
            Log::info("📈 Stock actualizado: de {$stockAnterior} a {$nuevoStock}");
        }

        // 🧾 Actualizar solicitud
        $solicitud->ubicacion = implode(', ', $nombresUbicaciones);
        $solicitud->estado = 'ubicado';
        $solicitud->save();

        DB::commit();
        Log::info("✅ Finalizado correctamente guardarUbicacion");

        return response()->json([
            'success' => true,
            'message' => 'Artículo ubicado correctamente',
            'ubicacion_texto' => $solicitud->ubicacion,
        ]);
    }
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('❌ Error en guardarUbicacion: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al guardar la ubicación: ' . $e->getMessage()
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


        $porcentaje = ($cantidad / $capacidadMaxima) * 100;

        if ($porcentaje == 0) return 'vacio';
        if ($porcentaje <= 24) return 'bajo';
        if ($porcentaje <= 49) return 'medio';
        if ($porcentaje <= 74) return 'alto';
        return 'muy_alto';
    }
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
            Log::info("=== NUEVA LÓGICA DE SUGERENCIA DE UBICACIÓN ===");
            Log::info("Artículo ID: {$articuloId}, Cantidad requerida: {$cantidad}");

            $todasUbicaciones = $this->obtenerTodasLasUbicacionesConEspacio($cantidad);

            Log::info("Total ubicaciones con espacio disponible: " . $todasUbicaciones->count());

            // CASO 1: Una sola ubicación puede almacenar TODO
            $completas = $todasUbicaciones->filter(function ($u) use ($cantidad) {
                return $u['espacio_disponible'] >= $cantidad;
            });

            if ($completas->isNotEmpty()) {
                Log::info("CASO 1: Ubicaciones con capacidad completa encontradas.");
                return $this->formatearRespuesta($completas->values(), 'completas', 'Ubicaciones con capacidad suficiente');
            }

            // CASO 2: Combinación de ubicaciones que sumen la cantidad
            $combinacion = collect([]);
            $acumulado = 0;

            foreach ($todasUbicaciones as $ubicacion) {
                if ($acumulado >= $cantidad) break;
                $combinacion->push($ubicacion);
                $acumulado += $ubicacion['espacio_disponible'];
            }

            Log::info("CASO 2: Combinación acumulada: $acumulado / $cantidad");

            if ($acumulado >= $cantidad) {
                return $this->formatearRespuesta($combinacion, 'combinada', 'Combinación de ubicaciones que suman la cantidad');
            }

            // CASO 3: Sin sugerencias
            Log::info("CASO 3: No hay suficiente espacio disponible en ninguna combinación.");
            return $this->formatearRespuesta(collect([]), 'sin_sugerencias', 'No hay ubicaciones disponibles suficientes');
        } catch (\Exception $e) {
            Log::error('Error en sugerirUbicacionesMejorado: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener sugerencias de ubicación: ' . $e->getMessage()
            ], 500);
        }
    }


    private function obtenerTodasLasUbicacionesConEspacio($cantidad)
    {
        try {
            return DB::table('rack_ubicaciones as ru')
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
                ->map(function ($ubicacion) {
                    $espacioDisponible = $ubicacion->capacidad_maxima - $ubicacion->cantidad_ocupada;
                    return [
                        'id' => $ubicacion->idRackUbicacion,
                        'codigo' => $ubicacion->codigo,
                        'rack_nombre' => $ubicacion->rack_nombre,
                        'sede' => $ubicacion->sede,
                        'cantidad_actual' => $ubicacion->cantidad_ocupada,
                        'capacidad_maxima' => $ubicacion->capacidad_maxima,
                        'espacio_disponible' => $espacioDisponible,
                        'tipo' => $ubicacion->cantidad_ocupada > 0 ? 'parcial' : 'vacía',
                        'prioridad' => $ubicacion->cantidad_ocupada > 0 ? 1 : 2
                    ];
                })
                ->where('espacio_disponible', '>', 0)
                ->sortBy('prioridad')
                ->values();
        } catch (\Exception $e) {
            Log::error('ERROR en obtenerTodasLasUbicacionesConEspacio: ' . $e->getMessage());
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

            return $ubicaciones->map(function ($ubicacion) {
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
                ->map(function ($ubicacion) {
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


    private function obtenerUbicacionesGeneralesConEspacio($cantidad, $articuloId)
    {
        try {
            Log::info("Buscando ubicaciones (sin importar si tienen el artículo) con capacidad >= {$cantidad}");

            return DB::table('rack_ubicaciones as ru')
                ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->leftJoin('rack_ubicacion_articulos as rua', function ($join) {
                    $join->on('ru.idRackUbicacion', '=', 'rua.rack_ubicacion_id');
                })
                ->where('r.estado', 'activo')
                ->whereNotIn('ru.idRackUbicacion', function ($query) use ($articuloId) {
                    $query->select('rack_ubicacion_id')
                        ->from('rack_ubicacion_articulos')
                        ->where('articulo_id', $articuloId);
                })
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
                ->get()
                ->map(function ($ubicacion) {
                    $espacioDisponible = $ubicacion->capacidad_maxima - $ubicacion->cantidad_ocupada;
                    return [
                        'id' => $ubicacion->idRackUbicacion,
                        'codigo' => $ubicacion->codigo,
                        'rack_nombre' => $ubicacion->rack_nombre,
                        'sede' => $ubicacion->sede,
                        'cantidad_actual' => $ubicacion->cantidad_ocupada,
                        'capacidad_maxima' => $ubicacion->capacidad_maxima,
                        'espacio_disponible' => $espacioDisponible,
                        'tipo' => 'disponible_sin_articulo',
                        'prioridad' => 2
                    ];
                });
        } catch (\Exception $e) {
            Log::error('ERROR en obtenerUbicacionesGeneralesConEspacio: ' . $e->getMessage());
            return collect([]);
        }
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
                'message' => 'Error al actualizar la solicitud: ' . $e->getMessage()
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
