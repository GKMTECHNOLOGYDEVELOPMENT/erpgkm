<?php

namespace App\Http\Controllers\solicitud;

use App\Http\Controllers\Controller;
use App\Models\ArticuloUbicacion;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SolicitudingresoController extends Controller
{
    public function index()
    {
        // Obtener las compras que TIENEN solicitudes de ingreso
        $comprasConSolicitudes = DB::table('compra as c')
            ->select(
                'c.idCompra',
                'c.codigocompra',
                'c.fechaEmision as fecha_compra',
                'c.total',
                'p.nombre as proveedor',
                DB::raw('COUNT(si.idSolicitudIngreso) as total_solicitudes'),
                DB::raw('SUM(CASE WHEN si.estado = "pendiente" THEN 1 ELSE 0 END) as pendientes'),
                DB::raw('SUM(CASE WHEN si.estado = "recibido" THEN 1 ELSE 0 END) as recibidos'),
                DB::raw('SUM(CASE WHEN si.estado = "ubicado" THEN 1 ELSE 0 END) as ubicados')
            )
            ->join('solicitud_ingreso as si', 'c.idCompra', '=', 'si.compra_id')
            ->leftJoin('proveedores as p', 'c.proveedor_id', '=', 'p.idProveedor')
            ->groupBy('c.idCompra', 'c.codigocompra', 'c.fechaEmision', 'c.total', 'p.nombre')
            ->having('total_solicitudes', '>', 0)
            ->orderBy('c.fechaEmision', 'desc')
            ->get();

        // Obtener las ubicaciones
        $ubicaciones = DB::table('ubicacion')
            ->select('idUbicacion', 'nombre')
            ->orderBy('nombre')
            ->get();

        return view('solicitud.solicitudingreso.index', compact('comprasConSolicitudes', 'ubicaciones'));
    }

 public function porCompra($compraId)
{
    $solicitudes = DB::table('solicitud_ingreso as si')
        ->select(
            'si.idSolicitudIngreso as id',
            'si.compra_id',
            'si.codigo_solicitud',
            'si.estado',
            'si.cantidad',
            'si.observaciones',
            'a.nombre as articulo',
            'a.idArticulos as articulo_id',
            DB::raw('COALESCE(a.maneja_serie, 0) as maneja_serie')
        )
        ->leftJoin('articulos as a', 'si.articulo_id', '=', 'a.idArticulos')
        ->where('si.compra_id', $compraId)
        ->orderBy('si.idSolicitudIngreso')
        ->get();

    // Obtener las ubicaciones de cada artículo PARA ESTA COMPRA
    foreach ($solicitudes as $solicitud) {
        $solicitud->ubicaciones = DB::table('articulo_ubicaciones as au')
            ->select(
                'au.cantidad',
                'u.nombre as ubicacion_nombre',
                'u.idUbicacion'
            )
            ->leftJoin('ubicacion as u', 'au.ubicacion_id', '=', 'u.idUbicacion')
            ->where('au.articulo_id', $solicitud->articulo_id)
            ->where('au.compra_id', $compraId) // FILTRAR POR COMPRA
            ->get();
        
        if (!isset($solicitud->maneja_serie)) {
            $solicitud->maneja_serie = 0;
        }
    }

    return response()->json(['solicitudes' => $solicitudes]);
}

public function procesar(Request $request)
{
    try {
        $data = $request->validate([
            'id' => 'required|integer|exists:solicitud_ingreso,idSolicitudIngreso',
            'articulo_id' => 'required|integer|exists:articulos,idArticulos',
            'compra_id' => 'required|integer|exists:compra,idCompra',
            'estado' => 'required|in:pendiente,recibido,ubicado',
            'ubicaciones' => 'required|array|min:1',
            'ubicaciones.*.idUbicacion' => 'required|integer|exists:ubicacion,idUbicacion',
            'ubicaciones.*.cantidad' => 'required|integer|min:1',
            'series' => 'nullable|array',
            'series.*.serie' => 'required|string|max:255',
            'series.*.estado' => 'required|in:disponible,vendido,defectuoso,garantia',
            'observaciones' => 'nullable|string'
        ]);

        DB::beginTransaction();

        // 1. Obtener información de la solicitud y artículo
        $solicitud = DB::table('solicitud_ingreso as si')
            ->select('si.*', DB::raw('COALESCE(a.maneja_serie, 0) as maneja_serie'))
            ->leftJoin('articulos as a', 'si.articulo_id', '=', 'a.idArticulos')
            ->where('si.idSolicitudIngreso', $data['id'])
            ->first();

        if (!$solicitud) {
            throw new Exception('Solicitud no encontrada');
        }

        $articulo = DB::table('articulos')
            ->where('idArticulos', $data['articulo_id'])
            ->first();

        if (!$articulo) {
            throw new Exception('Artículo no encontrado');
        }

        $stockAnterior = $articulo->stock_total;

        // 2. Obtener las ubicaciones actuales del artículo PARA ESTA COMPRA
        $ubicacionesActuales = DB::table('articulo_ubicaciones')
            ->where('articulo_id', $data['articulo_id'])
            ->where('compra_id', $data['compra_id']) // FILTRAR POR COMPRA
            ->get();

        // 3. Actualizar el estado de la solicitud
        $updateSolicitud = [
            'estado' => $data['estado'],
            'observaciones' => $data['observaciones'],
            'updated_at' => now()
        ];

        if ($data['estado'] === 'ubicado') {
            $updateSolicitud['fecha_ubicado'] = now();
        } elseif ($data['estado'] === 'recibido') {
            $updateSolicitud['fecha_recibido'] = now();
        }

        DB::table('solicitud_ingreso')
            ->where('idSolicitudIngreso', $data['id'])
            ->update($updateSolicitud);

        // 4. Manejar ubicaciones según el estado
        if ($data['estado'] === 'ubicado') {
            // ELIMINAR ubicaciones existentes para esta compra
            DB::table('articulo_ubicaciones')
                ->where('articulo_id', $data['articulo_id'])
                ->where('compra_id', $data['compra_id'])
                ->delete();

            // INSERTAR nuevas ubicaciones para esta compra
            $ubicacionesInsertar = [];
            foreach ($data['ubicaciones'] as $ubicacionData) {
                $ubicacionesInsertar[] = [
                    'articulo_id' => $data['articulo_id'],
                    'compra_id' => $data['compra_id'], // IMPORTANTE: agregar compra_id
                    'ubicacion_id' => $ubicacionData['idUbicacion'],
                    'cantidad' => $ubicacionData['cantidad'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            DB::table('articulo_ubicaciones')->insert($ubicacionesInsertar);

            // 5. ACTUALIZAR STOCK CORRECTAMENTE - SUMAR todas las ubicaciones de TODAS las compras
            $stockTotalActualizado = DB::table('articulo_ubicaciones')
                ->where('articulo_id', $data['articulo_id'])
                ->sum('cantidad');
            
            DB::table('articulos')
                ->where('idArticulos', $data['articulo_id'])
                ->update(['stock_total' => $stockTotalActualizado]);

            // 6. Manejar series si el artículo maneja series
            $seriesProcesadas = 0;
            if ($solicitud->maneja_serie && isset($data['series'])) {
                $detalleCompra = DB::table('detalle_compra')
                    ->where('idCompra', $data['compra_id'])
                    ->where('idProducto', $data['articulo_id'])
                    ->first();

                $detalleCompraId = $detalleCompra ? $detalleCompra->idDetalleCompra : null;

                $seriesProcesadas = $this->procesarSeriesArticulo(
                    $data['compra_id'],
                    $detalleCompraId,
                    $data['articulo_id'],
                    $data['series']
                );
            }

        } else {
            // Si no está ubicado, ELIMINAR las ubicaciones de esta compra
            DB::table('articulo_ubicaciones')
                ->where('articulo_id', $data['articulo_id'])
                ->where('compra_id', $data['compra_id'])
                ->delete();

            // Recalcular el stock total
            $stockTotalActualizado = DB::table('articulo_ubicaciones')
                ->where('articulo_id', $data['articulo_id'])
                ->sum('cantidad');
            
            DB::table('articulos')
                ->where('idArticulos', $data['articulo_id'])
                ->update(['stock_total' => $stockTotalActualizado]);

            // Eliminar series si ya existían
            if ($solicitud->maneja_serie) {
                DB::table('compra_serie_articulos')
                    ->where('compra_id', $data['compra_id'])
                    ->where('articulo_id', $data['articulo_id'])
                    ->delete();
            }
        }

        DB::commit();

        // Obtener el stock actualizado
        $stockActualizado = DB::table('articulos')
            ->where('idArticulos', $data['articulo_id'])
            ->value('stock_total');

        return response()->json([
            'success' => true, 
            'message' => 'Solicitud actualizada correctamente',
            'stock_anterior' => $stockAnterior,
            'stock_actual' => $stockActualizado,
            'diferencia' => $stockActualizado - $stockAnterior,
            'series_procesadas' => $seriesProcesadas ?? 0
        ]);

    } catch (Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false, 
            'message' => 'Error al procesar la solicitud: ' . $e->getMessage()
        ]);
    }
}

private function procesarSeriesArticulo($compraId, $detalleCompraId, $articuloId, $seriesData)
{
    // Eliminar series existentes para esta compra y artículo
    DB::table('compra_serie_articulos')
        ->where('compra_id', $compraId)
        ->where('articulo_id', $articuloId)
        ->delete();

    // Insertar las nuevas series
    $seriesInsertar = [];
    $fechaIngreso = now();

    foreach ($seriesData as $serieData) {
        // Verificar si la serie ya existe para otro artículo
        $serieExistente = DB::table('compra_serie_articulos')
            ->where('serie', $serieData['serie'])
            ->where('articulo_id', '!=', $articuloId)
            ->first();

        if ($serieExistente) {
            throw new Exception("La serie {$serieData['serie']} ya existe para otro artículo");
        }

        $seriesInsertar[] = [
            'compra_id' => $compraId,
            'detalle_compra_id' => $detalleCompraId,
            'articulo_id' => $articuloId,
            'serie' => $serieData['serie'],
            'estado' => $serieData['estado'],
            'fecha_ingreso' => $fechaIngreso,
            'fecha_actualizacion' => $fechaIngreso,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    if (!empty($seriesInsertar)) {
        DB::table('compra_serie_articulos')->insert($seriesInsertar);
    }

    return count($seriesInsertar);
}

    /**
     * Genera series automáticamente para un artículo
     */
    private function generarSeriesParaArticulo($compraId, $detalleCompraId, $articuloId, $cantidad)
    {
        // Verificar si ya existen series para esta compra y artículo
        $seriesExistentes = DB::table('compra_serie_articulos')
            ->where('compra_id', $compraId)
            ->where('articulo_id', $articuloId)
            ->count();

        // Si ya existen series, no generar nuevas
        if ($seriesExistentes > 0) {
            return;
        }

        // Obtener información del artículo para el formato de serie
        $articulo = DB::table('articulos')
            ->where('idArticulos', $articuloId)
            ->first();

        if (!$articulo) {
            throw new Exception('Artículo no encontrado al generar series');
        }

        $series = [];
        $fechaIngreso = now();

        // Generar series según la cantidad
        for ($i = 1; $i <= $cantidad; $i++) {
            // Formato de serie: [CODIGO_ARTICULO]-[COMPRA_ID]-[NUMERO_SECUENCIAL]
            $serie = $articulo->codigo . '-' . $compraId . '-' . str_pad($i, 4, '0', STR_PAD_LEFT);
            
            $series[] = [
                'compra_id' => $compraId,
                'detalle_compra_id' => $detalleCompraId,
                'articulo_id' => $articuloId,
                'serie' => $serie,
                'estado' => 'disponible',
                'fecha_ingreso' => $fechaIngreso,
                'fecha_actualizacion' => $fechaIngreso,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        // Insertar todas las series
        DB::table('compra_serie_articulos')->insert($series);
    }

    /**
     * Método para obtener las series de un artículo específico
     */
    public function obtenerSeriesArticulo($compraId, $articuloId)
    {
        $series = DB::table('compra_serie_articulos as csa')
            ->select('csa.*', 'a.nombre as articulo_nombre', 'a.codigo as articulo_codigo')
            ->leftJoin('articulos as a', 'csa.articulo_id', '=', 'a.idArticulos')
            ->where('csa.compra_id', $compraId)
            ->where('csa.articulo_id', $articuloId)
            ->orderBy('csa.serie')
            ->get();

        return response()->json(['series' => $series]);
    }

    /**
     * Método para actualizar una serie específica
     */
    public function actualizarSerie(Request $request, $serieId)
    {
        try {
            $data = $request->validate([
                'serie' => 'required|string|max:255',
                'estado' => 'required|in:disponible,vendido,defectuoso,garantia'
            ]);

            DB::table('compra_serie_articulos')
                ->where('id', $serieId)
                ->update([
                    'serie' => $data['serie'],
                    'estado' => $data['estado'],
                    'fecha_actualizacion' => now(),
                    'updated_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Serie actualizada correctamente'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la serie: ' . $e->getMessage()
            ]);
        }
    }

  public function obtenerUbicacionesArticulo($articuloId, $compraId = null)
{
    $query = DB::table('articulo_ubicaciones as au')
        ->select(
            'au.idArticuloUbicacion',
            'au.cantidad',
            'au.compra_id',
            'u.idUbicacion',
            'u.nombre as ubicacion_nombre'
        )
        ->leftJoin('ubicacion as u', 'au.ubicacion_id', '=', 'u.idUbicacion')
        ->where('au.articulo_id', $articuloId);

    if ($compraId) {
        $query->where('au.compra_id', $compraId);
    }

    $ubicaciones = $query->get();

    return response()->json(['ubicaciones' => $ubicaciones]);
}


    /**
 * Obtener las series de un artículo específico
 */
public function obtenerSeries($compraId, $articuloId)
{
    try {
        $series = DB::table('compra_serie_articulos as csa')
            ->select('csa.*', 'a.nombre as articulo_nombre', 'a.codigo as articulo_codigo')
            ->leftJoin('articulos as a', 'csa.articulo_id', '=', 'a.idArticulos')
            ->where('csa.compra_id', $compraId)
            ->where('csa.articulo_id', $articuloId)
            ->orderBy('csa.serie')
            ->get();

        return response()->json(['series' => $series]);
    } catch (Exception $e) {
        return response()->json(['series' => []]);
    }
}
}