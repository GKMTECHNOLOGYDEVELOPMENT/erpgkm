<?php

namespace App\Http\Controllers\repuestotransito;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class RepuestoTransitoController extends Controller
{
    public function index(Request $request)
    {
        $filtros = $request->all();
        
        Log::info('=== REPUESTOS TRANSITO INDEX ===');
        Log::info('Filtros recibidos:', $filtros);
        
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
                'a.codigo_repuesto as nombre_repuesto',
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
        
        Log::info('Total repuestos encontrados: ' . $repuestos->total());
        Log::info('Contadores:', $contadores);
        
        return view('repuestos-transito.index', compact('repuestos', 'contadores', 'filtros'));
    }

    public function obtenerDetalles($id)
    {
        Log::info('=== OBTENER DETALLES ===');
        Log::info('ID solicitado: ' . $id);
        
        try {
            // 1. Obtener información básica del repuesto
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
                Log::warning('Repuesto no encontrado: ID=' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Repuesto no encontrado'
                ], 404);
            }
            
            Log::info('Repuesto encontrado: ' . $repuesto->nombre_repuesto);
            
            // 2. Verificar si tiene fotos en la nueva tabla
            Log::info('=== VERIFICANDO FOTOS EN TABLA SEPARADA ===');
            
            // Obtener conteo de fotos por tipo
            $fotosPorTipo = DB::table('ordenes_articulos_fotos')
                ->select('tipo_foto', DB::raw('COUNT(*) as cantidad'))
                ->where('orden_articulo_id', $id)
                ->groupBy('tipo_foto')
                ->get()
                ->pluck('cantidad', 'tipo_foto')
                ->toArray();
            
            $totalFotos = array_sum(array_values($fotosPorTipo));
            
            Log::info('Total fotos: ' . $totalFotos);
            Log::info('Fotos por tipo:', $fotosPorTipo);
            
            // Agregar información de fotos al resultado
            $repuesto->tiene_fotos = ($totalFotos > 0);
            $repuesto->total_fotos = $totalFotos;
            $repuesto->fotos_disponibles = [
                'fotoRepuesto' => isset($fotosPorTipo['original']) && $fotosPorTipo['original'] > 0,
                'foto_articulo_usado' => isset($fotosPorTipo['usado']) && $fotosPorTipo['usado'] > 0,
                'foto_articulo_no_usado' => isset($fotosPorTipo['no_usado']) && $fotosPorTipo['no_usado'] > 0
            ];
            
            // También incluir conteos por tipo
            $repuesto->fotos_por_tipo = [
                'original' => $fotosPorTipo['original'] ?? 0,
                'usado' => $fotosPorTipo['usado'] ?? 0,
                'no_usado' => $fotosPorTipo['no_usado'] ?? 0
            ];
            
            Log::info('¿Tiene fotos?: ' . ($repuesto->tiene_fotos ? 'SI' : 'NO'));
            Log::info('Fotos disponibles:', $repuesto->fotos_disponibles);
            
            return response()->json([
                'success' => true,
                'data' => $repuesto,
                'info_fotos' => [
                    'total' => $totalFotos,
                    'por_tipo' => $fotosPorTipo,
                    'tiene_fotos' => $totalFotos > 0
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener detalles del repuesto: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener detalles'
            ], 500);
        }
    }

    /**
     * Obtener todas las fotos desde la tabla separada - VERSIÓN MEJORADA
     */
    public function obtenerTodasFotos($id)
    {
        Log::info('=== OBTENER TODAS LAS FOTOS DESDE TABLA SEPARADA ===');
        Log::info('ID solicitado: ' . $id);
        
        try {
            // 1. Verificar si el repuesto existe
            $existeRepuesto = DB::table('ordenesarticulos')
                ->where('idOrdenesArticulos', $id)
                ->exists();
                
            if (!$existeRepuesto) {
                Log::warning('Repuesto no encontrado: ID=' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Repuesto no encontrado'
                ], 404);
            }
            
            // 2. Obtener todas las fotos de la tabla separada
            Log::info('Buscando fotos en ordenes_articulos_fotos...');
            
            $fotos = DB::table('ordenes_articulos_fotos')
                ->select('id', 'tipo_foto', 'nombre_archivo', 'mime_type', 'datos', 'fecha_subida')
                ->where('orden_articulo_id', $id)
                ->orderBy('tipo_foto')
                ->orderBy('fecha_subida', 'asc')
                ->get();
            
            Log::info('Total fotos encontradas: ' . $fotos->count());
            
            // 3. Organizar fotos por tipo
            $fotosPorTipo = [
                'fotoRepuesto' => [], // Para fotos de tipo "original" si las tienes
                'foto_articulo_usado' => [],
                'foto_articulo_no_usado' => []
            ];
            
            // Contador total de fotos
            $totalFotos = 0;
            
            // Mapear tipos de la base de datos a tipos del frontend
            $tipoMapping = [
                'original' => 'fotoRepuesto',
                'usado' => 'foto_articulo_usado',
                'no_usado' => 'foto_articulo_no_usado'
            ];
            
            foreach ($fotos as $foto) {
                try {
                    if (empty($foto->datos)) {
                        continue;
                    }
                    
                    $mimeType = $foto->mime_type ?: 'image/jpeg';
                    $base64 = base64_encode($foto->datos);
                    
                    $fotoArray = [
                        'id' => $foto->id,
                        'base64' => 'data:' . $mimeType . ';base64,' . $base64,
                        'mime' => $mimeType,
                        'nombre' => $foto->nombre_archivo ?? 'foto_' . $foto->id,
                        'fecha' => $foto->fecha_subida,
                        'tipo_foto_db' => $foto->tipo_foto
                    ];
                    
                    // Determinar el tipo para el frontend
                    $tipoFrontend = $tipoMapping[$foto->tipo_foto] ?? 'fotoRepuesto';
                    
                    // Agregar a la lista correspondiente
                    if (!isset($fotosPorTipo[$tipoFrontend])) {
                        $fotosPorTipo[$tipoFrontend] = [];
                    }
                    
                    $fotosPorTipo[$tipoFrontend][] = $fotoArray;
                    $totalFotos++;
                    
                } catch (\Exception $e) {
                    Log::error('Error procesando foto ' . $foto->id . ': ' . $e->getMessage());
                }
            }
            
            // 4. Preparar respuesta estructurada para el frontend
            $respuestaEstructurada = [];
            
            // Definir tipos con sus títulos y descripciones
            $tiposConfig = [
                'fotoRepuesto' => [
                    'titulo' => '📸 Fotos del Repuesto',
                    'descripcion' => 'Fotos originales del repuesto',
                    'tipo_db' => 'original'
                ],
                'foto_articulo_usado' => [
                    'titulo' => '✅ Repuesto Usado',
                    'descripcion' => 'Fotos del repuesto utilizado en el equipo',
                    'tipo_db' => 'usado'
                ],
                'foto_articulo_no_usado' => [
                    'titulo' => '❌ Repuesto Devuelto',
                    'descripcion' => 'Fotos del repuesto devuelto sin usar',
                    'tipo_db' => 'no_usado'
                ]
            ];
            
            foreach ($tiposConfig as $tipoKey => $config) {
                $fotosDelTipo = $fotosPorTipo[$tipoKey] ?? [];
                
                if (count($fotosDelTipo) > 0) {
                    $respuestaEstructurada[$tipoKey] = [
                        'tiene' => true,
                        'total' => count($fotosDelTipo),
                        'fotos' => $fotosDelTipo,
                        'titulo' => $config['titulo'],
                        'descripcion' => $config['descripcion']
                    ];
                } else {
                    $respuestaEstructurada[$tipoKey] = [
                        'tiene' => false,
                        'total' => 0,
                        'fotos' => [],
                        'titulo' => $config['titulo'],
                        'descripcion' => 'No hay fotos disponibles'
                    ];
                }
            }
            
            Log::info('Total fotos procesadas: ' . $totalFotos);
            Log::info('Fotos por tipo en respuesta:', array_map(function($item) {
                return $item['total'] ?? 0;
            }, $respuestaEstructurada));
            
            return response()->json([
                'success' => true,
                'fotos' => $respuestaEstructurada,
                'total_fotos' => $totalFotos,
                'info' => 'Fotos obtenidas correctamente desde la base de datos'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener fotos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las fotos: ' . $e->getMessage()
            ], 500);
        }
    }
}