<?php

namespace App\Http\Controllers\solicitud;

use App\Http\Controllers\Controller;
use App\Models\ArticuloUbicacion;
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
        'articulo:idArticulos,nombre,codigo_barras,codigo_repuesto,idTipoArticulo',
        'proveedor:idProveedor,nombre,numeroDocumento',
        'clienteGeneral:idClienteGeneral,descripcion,estado',
        'compra:idCompra,codigocompra,estado,fechaEmision,proveedor_id',
        'entradaProveedor:id,codigo_entrada,estado,fecha_ingreso,tipo_entrada,cliente_general_id',
        // ✅ CARGAR UBICACIONES CON LA UBICACIÓN RELACIONADA
        'ubicaciones.ubicacion:idUbicacion,nombre'
    ])->orderBy('created_at', 'desc')->get();

        Log::info('Total de solicitudes encontradas: ' . $solicitudes->count());

        // ✅ FILTRAR: Solo compras con estado 'enviado_almacen' y todas las entradas proveedor
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
                // ✅ DEBUG: Verificar qué ubicaciones se están cargando
                Log::info("Solicitud ID: {$solicitud->idSolicitudIngreso}, Ubicaciones count: " . $solicitud->ubicaciones->count());
                
                if ($solicitud->ubicaciones->count() > 0) {
                    Log::info("Ubicaciones para solicitud {$solicitud->idSolicitudIngreso}:", 
                        $solicitud->ubicaciones->toArray());
                }
                
                return [
                    'idSolicitudIngreso' => $solicitud->idSolicitudIngreso,
                    'origen' => $solicitud->origen,
                    'origen_id' => $solicitud->origen_id,
                    'articulo_id' => $solicitud->articulo_id,
                    'articulo' => $solicitud->articulo,
                    'cantidad' => $solicitud->cantidad,
                    'estado' => $solicitud->estado,
                    'ubicacion' => $solicitud->ubicacion, // ✅ AGREGAR ESTA LÍNEA
                    // ✅ INCLUIR LAS UBICACIONES CON SU NOMBRE
                    'ubicaciones' => $solicitud->ubicaciones->map(function($ubicacion) {
                        return [
                            'idArticuloUbicacion' => $ubicacion->idArticuloUbicacion,
                            'ubicacion_id' => $ubicacion->ubicacion_id,
                            'cantidad' => $ubicacion->cantidad,
                            'nombre_ubicacion' => $ubicacion->ubicacion ? $ubicacion->ubicacion->nombre : 'Ubicación no encontrada'
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

        // ✅ VERIFICAR SI ES LA PRIMERA VEZ QUE SE UBICA (para evitar duplicar stock)
        $esPrimeraUbicacion = ($solicitud->estado !== 'ubicado');
        
        if ($esPrimeraUbicacion) {
            Log::info("📍 Primera ubicación para solicitud ID: {$solicitud->idSolicitudIngreso}. Aumentando stock.");
        } else {
            Log::info("🔄 Re-ubicación para solicitud ID: {$solicitud->idSolicitudIngreso}. Stock ya fue aumentado anteriormente.");
        }

        // ✅ ELIMINAR UBICACIONES EXISTENTES ANTES DE CREAR NUEVAS
        ArticuloUbicacion::where('origen', $solicitud->origen)
            ->where('articulo_id', $solicitud->articulo_id)
            ->where('origen_id', $solicitud->origen_id)
            ->delete();

        // ✅ OBTENER NOMBRES DE UBICACIONES PARA GUARDAR EN SOLICITUD_INGRESO
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

            // ✅ OBTENER SOLO EL NOMBRE DE LA UBICACIÓN
            $ubicacion = \App\Models\Ubicacion::find($ubicacionData['ubicacion_id']);
            if ($ubicacion) {
                $nombresUbicaciones[] = $ubicacion->nombre;
            }
        }

        // ✅ AUMENTAR STOCK DEL ARTÍCULO SOLO SI ES LA PRIMERA VEZ QUE SE UBICA
        if ($esPrimeraUbicacion) {
            $articulo = \App\Models\Articulo::find($solicitud->articulo_id);
            
            if ($articulo) {
                $stockAnterior = $articulo->stock_total;
                $nuevoStock = $stockAnterior + $solicitud->cantidad;
                
                // Actualizar el stock
                $articulo->stock_total = $nuevoStock;
                $articulo->save();
                
                Log::info("📦 Stock actualizado - Artículo ID: {$articulo->idArticulos}");
                Log::info("📦 Stock anterior: {$stockAnterior}, Cantidad añadida: {$solicitud->cantidad}, Nuevo stock: {$nuevoStock}");
            } else {
                Log::error("❌ Artículo con ID {$solicitud->articulo_id} no encontrado");
            }
        }

        // ✅ ACTUALIZAR EL CAMPO 'ubicacion' EN SOLICITUD_INGRESO
        $ubicacionTexto = !empty($nombresUbicaciones) ? implode(', ', $nombresUbicaciones) : 'Sin ubicación';
        
        $solicitud->ubicacion = $ubicacionTexto;
        $solicitud->estado = 'ubicado';
        $solicitud->save();

        DB::commit();

        // ✅ RETORNAR LAS UBICACIONES ACTUALIZADAS
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

        return response()->json([
            'success' => true,
            'message' => 'Artículo ubicado correctamente en '.count($request->ubicaciones).' ubicación(es)' . 
                        ($esPrimeraUbicacion ? ' y stock actualizado' : ''),
            'ubicaciones' => $ubicacionesActualizadas,
            'ubicacion_texto' => $ubicacionTexto,
            'stock_actualizado' => $esPrimeraUbicacion
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al guardar ubicación: ' . $e->getMessage());
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