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
                return $solicitud->compra && $solicitud->compra->estado === 'enviado_almacen';
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
            
            // Validar que la suma de las cantidades sea igual a la cantidad total
            $totalDistribuido = collect($request->ubicaciones)->sum('cantidad');
            
            if ($totalDistribuido != $solicitud->cantidad) {
                return response()->json([
                    'success' => false,
                    'message' => 'La suma de las cantidades distribuidas ('.$totalDistribuido.') debe ser igual a la cantidad total ('.$solicitud->cantidad.')'
                ], 422);
            }

            // Verificar si el artículo requiere series
            $articulo = \App\Models\Articulo::find($solicitud->articulo_id);
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
                        // Excluir las series de esta misma solicitud (para permitir re-ubicaciones)
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

                // Validar que todas las series tengan ubicación
                foreach ($request->series as $serie) {
                    if (empty($serie['ubicacion_id'])) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Todas las series deben tener una ubicación asignada'
                        ], 422);
                    }
                }
            }

            $esPrimeraUbicacion = ($solicitud->estado !== 'ubicado');
            
            if ($esPrimeraUbicacion) {
                Log::info("Primera ubicación para solicitud ID: {$solicitud->idSolicitudIngreso}. Aumentando stock.");
            } else {
                Log::info("Re-ubicación para solicitud ID: {$solicitud->idSolicitudIngreso}. Stock ya fue aumentado anteriormente.");
            }

            // Eliminar ubicaciones y series existentes
            ArticuloUbicacion::where('origen', $solicitud->origen)
                ->where('articulo_id', $solicitud->articulo_id)
                ->where('origen_id', $solicitud->origen_id)
                ->delete();

            ArticuloSerie::where('origen', $solicitud->origen)
                ->where('articulo_id', $solicitud->articulo_id)
                ->where('origen_id', $solicitud->origen_id)
                ->delete();

            $nombresUbicaciones = [];
            
            // Guardar cada ubicación
            foreach ($request->ubicaciones as $ubicacionData) {
                $articuloUbicacion = ArticuloUbicacion::create([
                    'origen' => $solicitud->origen,
                    'articulo_id' => $solicitud->articulo_id,
                    'origen_id' => $solicitud->origen_id,
                    'ubicacion_id' => $ubicacionData['ubicacion_id'],
                    'cantidad' => $ubicacionData['cantidad'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $ubicacion = \App\Models\Ubicacion::find($ubicacionData['ubicacion_id']);
                if ($ubicacion) {
                    $nombresUbicaciones[] = $ubicacion->nombre . ' (' . $ubicacionData['cantidad'] . ')';
                }
            }

            // Guardar series si es requerido
            $seriesGuardadas = [];
            if ($requiereSeries && !empty($request->series)) {
                Log::info("Guardando {$totalSeries} series para solicitud {$solicitud->idSolicitudIngreso}");
                
                foreach ($request->series as $serieData) {
                    $serieBD = ArticuloSerie::create([
                        'origen' => $solicitud->origen,
                        'origen_id' => $solicitud->origen_id,
                        'articulo_id' => $solicitud->articulo_id,
                        'ubicacion_id' => $serieData['ubicacion_id'],
                        'numero_serie' => $serieData['numero_serie'],
                        'estado' => 'activo'
                    ]);

                    $seriesGuardadas[] = [
                        'numero_serie' => $serieBD->numero_serie,
                        'ubicacion_id' => $serieBD->ubicacion_id
                    ];

                    Log::info("Serie guardada: {$serieBD->numero_serie} en ubicación {$serieBD->ubicacion_id}");
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

            // Actualizar solicitud
            $ubicacionTexto = !empty($nombresUbicaciones) ? implode(', ', $nombresUbicaciones) : 'Sin ubicación';
            
            $solicitud->ubicacion = $ubicacionTexto;
            $solicitud->estado = 'ubicado';
            $solicitud->save();

            DB::commit();

            // Retornar respuesta con las ubicaciones y series actualizadas
            $ubicacionesActualizadas = ArticuloUbicacion::with('ubicacion:idUbicacion,nombre')
                ->where('origen', $solicitud->origen)
                ->where('articulo_id', $solicitud->articulo_id)
                ->where('origen_id', $solicitud->origen_id)
                ->get()
                ->map(function($ubicacion) {
                    return [
                        'ubicacion_id' => $ubicacion->ubicacion_id,
                        'cantidad' => $ubicacion->cantidad,
                        'nombre_ubicacion' => $ubicacion->ubicacion->nombre
                    ];
                });

            $mensaje = 'Artículo ubicado correctamente en '.count($request->ubicaciones).' ubicación(es)';
            if ($requiereSeries) {
                $mensaje .= ' con '.count($seriesGuardadas).' número(s) de serie';
            }
            if ($esPrimeraUbicacion) {
                $mensaje .= ' y stock actualizado';
            }

            return response()->json([
                'success' => true,
                'message' => $mensaje,
                'ubicaciones' => $ubicacionesActualizadas,
                'series' => $seriesGuardadas,
                'ubicacion_texto' => $ubicacionTexto,
                'stock_actualizado' => $esPrimeraUbicacion
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
}