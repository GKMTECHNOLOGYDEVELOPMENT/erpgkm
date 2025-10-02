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





// public function guardarUbicacion(Request $request)
// {
//     try {
//         DB::beginTransaction();

//         $solicitud = SolicitudIngreso::findOrFail($request->solicitud_id);
        
//         // Validar que la suma de las cantidades sea igual a la cantidad total
//         $totalDistribuido = collect($request->ubicaciones)->sum('cantidad');
        
//         if ($totalDistribuido != $solicitud->cantidad) {
//             return response()->json([
//                 'success' => false,
//                 'message' => 'La suma de las cantidades distribuidas ('.$totalDistribuido.') debe ser igual a la cantidad total ('.$solicitud->cantidad.')'
//             ], 422);
//         }

//         // Verificar si el artículo requiere series
//         $articulo = \App\Models\Articulo::find($solicitud->articulo_id);
//         $requiereSeries = $articulo && $articulo->maneja_serie === 1;

//         Log::info("Artículo ID: {$solicitud->articulo_id}, Maneja serie: " . ($articulo ? $articulo->maneja_serie : 'N/A') . ", Requiere series: " . ($requiereSeries ? 'SÍ' : 'NO'));

//         // Validar series si es requerido
//         if ($requiereSeries) {
//             if (!$request->has('series') || empty($request->series)) {
//                 return response()->json([
//                     'success' => false,
//                     'message' => 'Este artículo requiere números de serie'
//                 ], 422);
//             }

//             $totalSeries = count($request->series);
//             if ($totalSeries != $solicitud->cantidad) {
//                 return response()->json([
//                     'success' => false,
//                     'message' => "Debe ingresar {$solicitud->cantidad} números de serie, recibidas: {$totalSeries}"
//                 ], 422);
//             }

//             // Validar que no haya series duplicadas
//             $seriesNumeros = array_column($request->series, 'numero_serie');
//             $seriesUnicas = array_unique($seriesNumeros);
//             if (count($seriesUnicas) != count($seriesNumeros)) {
//                 return response()->json([
//                     'success' => false,
//                     'message' => 'No puede haber números de serie duplicados'
//                 ], 422);
//             }

//             // Validar que las series no existan ya en la base de datos para este artículo
//             $seriesExistentes = ArticuloSerie::where('articulo_id', $solicitud->articulo_id)
//                 ->whereIn('numero_serie', $seriesNumeros)
//                 ->where(function($query) use ($solicitud) {
//                     $query->where('origen', '!=', $solicitud->origen)
//                           ->orWhere('origen_id', '!=', $solicitud->origen_id);
//                 })
//                 ->pluck('numero_serie')
//                 ->toArray();

//             if (!empty($seriesExistentes)) {
//                 return response()->json([
//                     'success' => false,
//                     'message' => 'Los siguientes números de serie ya existen para este artículo: ' . implode(', ', $seriesExistentes)
//                 ], 422);
//             }
//         }

//         $esPrimeraUbicacion = ($solicitud->estado !== 'ubicado');
        
//         if ($esPrimeraUbicacion) {
//             Log::info("Primera ubicación para solicitud ID: {$solicitud->idSolicitudIngreso}. Aumentando stock.");
//         } else {
//             Log::info("Re-ubicación para solicitud ID: {$solicitud->idSolicitudIngreso}. Stock ya fue aumentado anteriormente.");
//         }

//         // Eliminar ubicaciones y series existentes
//         ArticuloUbicacion::where('origen', $solicitud->origen)
//             ->where('articulo_id', $solicitud->articulo_id)
//             ->where('origen_id', $solicitud->origen_id)
//             ->delete();

//         ArticuloSerie::where('origen', $solicitud->origen)
//             ->where('articulo_id', $solicitud->articulo_id)
//             ->where('origen_id', $solicitud->origen_id)
//             ->delete();

//         $nombresUbicaciones = [];
        
//         // Guardar cada ubicación
//         foreach ($request->ubicaciones as $ubicacionData) {
//             $articuloUbicacion = ArticuloUbicacion::create([
//                 'origen' => $solicitud->origen,
//                 'articulo_id' => $solicitud->articulo_id,
//                 'origen_id' => $solicitud->origen_id,
//                 'ubicacion_id' => $ubicacionData['ubicacion_id'],
//                 'cantidad' => $ubicacionData['cantidad'],
//                 'created_at' => now(),
//                 'updated_at' => now()
//             ]);

//             $ubicacion = \App\Models\Ubicacion::find($ubicacionData['ubicacion_id']);
//             if ($ubicacion) {
//                 $nombresUbicaciones[] = $ubicacion->nombre . ' (' . $ubicacionData['cantidad'] . ')';
//             }
//         }

//         // Guardar series sin ubicación
//         $seriesGuardadas = [];
//         if ($requiereSeries && !empty($request->series)) {
//             Log::info("Guardando {$totalSeries} series para solicitud {$solicitud->idSolicitudIngreso}");
            
//             foreach ($request->series as $serieData) {
//                 $serieBD = ArticuloSerie::create([
//                     'origen' => $solicitud->origen,
//                     'origen_id' => $solicitud->origen_id,
//                     'articulo_id' => $solicitud->articulo_id,
//                     'numero_serie' => $serieData['numero_serie'],
//                     'estado' => 'activo'
//                 ]);

//                 $seriesGuardadas[] = [
//                     'numero_serie' => $serieBD->numero_serie
//                 ];

//                 Log::info("Serie guardada: {$serieBD->numero_serie}");
//             }
//         }

//         // Aumentar stock del artículo solo si es la primera vez
//         if ($esPrimeraUbicacion && $articulo) {
//             $stockAnterior = $articulo->stock_total;
//             $nuevoStock = $stockAnterior + $solicitud->cantidad;
            
//             $articulo->stock_total = $nuevoStock;
//             $articulo->save();
            
//             Log::info("Stock actualizado - Artículo ID: {$articulo->idArticulos}");
//             Log::info("Stock anterior: {$stockAnterior}, Cantidad añadida: {$solicitud->cantidad}, Nuevo stock: {$nuevoStock}");
//         }

//         // Actualizar solicitud
//         $ubicacionTexto = !empty($nombresUbicaciones) ? implode(', ', $nombresUbicaciones) : 'Sin ubicación';
        
//         $solicitud->ubicacion = $ubicacionTexto;
//         $solicitud->estado = 'ubicado';
//         $solicitud->save();

//         DB::commit();

//         // Retornar respuesta con las ubicaciones y series actualizadas
//         $ubicacionesActualizadas = ArticuloUbicacion::with('ubicacion:idUbicacion,nombre')
//             ->where('origen', $solicitud->origen)
//             ->where('articulo_id', $solicitud->articulo_id)
//             ->where('origen_id', $solicitud->origen_id)
//             ->get()
//             ->map(function($ubicacion) {
//                 return [
//                     'ubicacion_id' => $ubicacion->ubicacion_id,
//                     'cantidad' => $ubicacion->cantidad,
//                     'nombre_ubicacion' => $ubicacion->ubicacion->nombre
//                 ];
//             });

//         $mensaje = 'Artículo ubicado correctamente en '.count($request->ubicaciones).' ubicación(es)';
//         if ($requiereSeries) {
//             $mensaje .= ' con '.count($seriesGuardadas).' número(s) de serie';
//         }
//         if ($esPrimeraUbicacion) {
//             $mensaje .= ' y stock actualizado';
//         }

//         return response()->json([
//             'success' => true,
//             'message' => $mensaje,
//             'ubicaciones' => $ubicacionesActualizadas,
//             'series' => $seriesGuardadas,
//             'ubicacion_texto' => $ubicacionTexto,
//             'stock_actualizado' => $esPrimeraUbicacion
//         ]);

//     } catch (\Exception $e) {
//         DB::rollBack();
//         Log::error('Error al guardar ubicación: ' . $e->getMessage());
//         Log::error('Stack trace: ' . $e->getTraceAsString());
//         return response()->json([
//             'success' => false,
//             'message' => 'Error al guardar la ubicación: '.$e->getMessage()
//         ], 500);
//     }
// }



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