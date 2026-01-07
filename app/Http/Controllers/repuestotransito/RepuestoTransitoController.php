<?php

namespace App\Http\Controllers\repuestotransito;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RepuestoTransitoController extends Controller
{
    public function index(Request $request)
    {
        $filtros = $request->all();
        
        // Obtener los repuestos en tránsito (pendientes)
        $query = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idOrdenesArticulos',
                'oa.cantidad',
                'oa.observacion',
                'oa.fechaUsado',
                'oa.fechaSinUsar',
                'oa.created_at as fecha_solicitud',
                'a.idArticulos',
                'a.nombre as nombre_repuesto',
                'a.codigo_barras',
                'a.codigo_repuesto',
                'a.sku',
                'so.idSolicitudesOrdenes',
                'so.codigo as codigo_solicitud',
                'so.fechaCreacion',
                'so.fecharequerida',
                'so.estado as estado_solicitud',
                'u.Nombre as solicitante',
                'sc.nombre as subcategoria',
                'm.nombre as modelo',
                'mar.nombre as marca',
                DB::raw('CASE 
                    WHEN oa.fechaUsado IS NOT NULL THEN "usado"
                    WHEN oa.fechaSinUsar IS NOT NULL THEN "no_usado"
                    ELSE "pendiente"
                END as estado_repuesto')
            )
            ->join('solicitudesordenes as so', 'oa.idSolicitudesOrdenes', '=', 'so.idSolicitudesOrdenes')
            ->join('articulos as a', 'oa.idArticulos', '=', 'a.idArticulos')
            ->leftJoin('usuarios as u', 'so.idUsuario', '=', 'u.idUsuario')
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->leftJoin('articulo_modelo as am', function($join) {
                $join->on('a.idArticulos', '=', 'am.articulo_id')
                     ->where('a.idTipoArticulo', 2);
            })
            ->leftJoin('modelo as m', 'am.modelo_id', '=', 'm.idModelo')
            ->leftJoin('marca as mar', 'm.idMarca', '=', 'mar.idMarca')
            ->where('so.tipoorden', 'solicitud_repuesto')
            ->whereIn('oa.estado', [0, 1]);

        // Aplicar filtros
        if (!empty($filtros['estado'])) {
            switch ($filtros['estado']) {
                case 'usado':
                    $query->whereNotNull('oa.fechaUsado');
                    break;
                case 'no_usado':
                    $query->whereNotNull('oa.fechaSinUsar');
                    break;
                case 'pendiente':
                    $query->whereNull('oa.fechaUsado')
                          ->whereNull('oa.fechaSinUsar');
                    break;
            }
        }

        if (!empty($filtros['codigo_repuesto'])) {
            $query->where('a.codigo_repuesto', 'like', '%' . $filtros['codigo_repuesto'] . '%');
        }

        if (!empty($filtros['codigo_solicitud'])) {
            $query->where('so.codigo', 'like', '%' . $filtros['codigo_solicitud'] . '%');
        }

        if (!empty($filtros['fecha_desde'])) {
            $query->whereDate('oa.created_at', '>=', $filtros['fecha_desde']);
        }

        if (!empty($filtros['fecha_hasta'])) {
            $query->whereDate('oa.created_at', '<=', $filtros['fecha_hasta']);
        }

        $repuestos = $query->orderBy('oa.created_at', 'desc')->paginate(20);

        // Contadores por estado
        $contadores = [
            'total' => DB::table('ordenesarticulos as oa')
                ->join('solicitudesordenes as so', 'oa.idSolicitudesOrdenes', '=', 'so.idSolicitudesOrdenes')
                ->where('so.tipoorden', 'solicitud_repuesto')
                ->count(),
            'usados' => DB::table('ordenesarticulos as oa')
                ->join('solicitudesordenes as so', 'oa.idSolicitudesOrdenes', '=', 'so.idSolicitudesOrdenes')
                ->where('so.tipoorden', 'solicitud_repuesto')
                ->whereNotNull('oa.fechaUsado')
                ->count(),
            'no_usados' => DB::table('ordenesarticulos as oa')
                ->join('solicitudesordenes as so', 'oa.idSolicitudesOrdenes', '=', 'so.idSolicitudesOrdenes')
                ->where('so.tipoorden', 'solicitud_repuesto')
                ->whereNotNull('oa.fechaSinUsar')
                ->count(),
            'pendientes' => DB::table('ordenesarticulos as oa')
                ->join('solicitudesordenes as so', 'oa.idSolicitudesOrdenes', '=', 'so.idSolicitudesOrdenes')
                ->where('so.tipoorden', 'solicitud_repuesto')
                ->whereNull('oa.fechaUsado')
                ->whereNull('oa.fechaSinUsar')
                ->count(),
        ];
        
        return view('repuestos-transito.index', compact('repuestos', 'contadores', 'filtros'));
    }

    public function obtenerDetalles($id)
    {
        try {
            // IMPORTANTE: NO seleccionar las columnas BLOB (longblob)
            $repuesto = DB::table('ordenesarticulos as oa')
                ->select(
                    'oa.idOrdenesArticulos',
                    'oa.cantidad',
                    'oa.observacion',
                    'oa.estado',
                    'oa.fechaUsado',
                    'oa.fechaSinUsar',
                    'oa.created_at',
                    'oa.updated_at',
                    'oa.idSolicitudesOrdenes',
                    'oa.idArticulos',
                    'oa.idUbicacion',
                    'oa.id_area_destino',
                    'oa.id_usuario_destino',
                    'oa.codigo_cotizacion',
                    'a.codigo_repuesto as nombre_repuesto',
                    'a.codigo_repuesto',
                    'a.sku',
                    'a.codigo_barras',
                    'sc.nombre as subcategoria',
                    'so.codigo as codigo_solicitud',
                    'so.fechaCreacion',
                    'so.fecharequerida',
                    'so.estado as estado_solicitud',
                    'so.observaciones',
                    'u.Nombre as solicitante',
                    'm.nombre as modelo',
                    'mar.nombre as marca',
                    DB::raw('CASE 
                        WHEN oa.fechaUsado IS NOT NULL THEN "usado"
                        WHEN oa.fechaSinUsar IS NOT NULL THEN "no_usado"
                        ELSE "pendiente"
                    END as estado')
                )
                ->join('solicitudesordenes as so', 'oa.idSolicitudesOrdenes', '=', 'so.idSolicitudesOrdenes')
                ->join('articulos as a', 'oa.idArticulos', '=', 'a.idArticulos')
                ->leftJoin('usuarios as u', 'so.idUsuario', '=', 'u.idUsuario')
                ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
                ->leftJoin('articulo_modelo as am', function($join) {
                    $join->on('a.idArticulos', '=', 'am.articulo_id')
                         ->where('a.idTipoArticulo', 2);
                })
                ->leftJoin('modelo as m', 'am.modelo_id', '=', 'm.idModelo')
                ->leftJoin('marca as mar', 'm.idMarca', '=', 'mar.idMarca')
                ->where('oa.idOrdenesArticulos', $id)
                ->first();

            if (!$repuesto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Repuesto no encontrado'
                ], 404);
            }
            
            // Verificar si tiene fotos (sin traer el contenido BLOB)
            $tieneFotos = DB::table('ordenesarticulos')
                ->select(
                    DB::raw('CASE 
                        WHEN fotoRepuesto IS NOT NULL AND LENGTH(fotoRepuesto) > 0 THEN 1
                        WHEN foto_articulo_usado IS NOT NULL AND LENGTH(foto_articulo_usado) > 0 THEN 1
                        WHEN foto_articulo_no_usado IS NOT NULL AND LENGTH(foto_articulo_no_usado) > 0 THEN 1
                        ELSE 0
                    END as tiene_fotos')
                )
                ->where('idOrdenesArticulos', $id)
                ->value('tiene_fotos');
            
            // Agregar bandera de fotos al resultado
            $repuesto->tiene_fotos = (bool)$tieneFotos;
            
            return response()->json([
                'success' => true,
                'data' => $repuesto
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener detalles del repuesto: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener detalles'
            ], 500);
        }
    }
}