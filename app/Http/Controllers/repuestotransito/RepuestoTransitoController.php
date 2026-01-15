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
        
        // Obtener los repuestos en tránsito basados en repuestos_entregas
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
                're.estado as estado_entrega',
                're.fecha_entrega',
                're.observaciones as obs_entrega',
                're.numero_ticket',
                // Estado dinámico basado en repuestos_entregas
                DB::raw('CASE 
                    WHEN re.estado = "entregado" THEN "en_transito"
                    WHEN re.estado = "usado" THEN "usado"
                    WHEN re.estado = "devuelto" THEN "devuelto"
                    WHEN re.estado = "pendiente" THEN "pendiente"
                    ELSE COALESCE(re.estado, "sin_entrega")
                END as estado_repuesto')
            )
            ->join('solicitudesordenes as so', 'oa.idSolicitudesOrdenes', '=', 'so.idSolicitudesOrdenes')
            ->join('articulos as a', 'oa.idArticulos', '=', 'a.idArticulos')
            ->leftJoin('usuarios as u', 'so.idUsuario', '=', 'u.idUsuario')
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->leftJoin('articulo_modelo as am', function($join) {
                $join->on('a.idArticulos', '=', 'am.articulo_id')
                     ->where('a.idTipoArticulo', 2); // Solo para tipo 2 si aplica
            })
            ->leftJoin('modelo as m', 'am.modelo_id', '=', 'm.idModelo')
            ->leftJoin('marca as mar', 'm.idMarca', '=', 'mar.idMarca')
            // UNIR CON REPUESTOS_ENTREGAS (esto es clave)
            ->leftJoin('repuestos_entregas as re', function($join) {
                $join->on('oa.idSolicitudesOrdenes', '=', 're.solicitud_id')
                     ->on('oa.idArticulos', '=', 're.articulo_id');
            })
            ->where('so.tipoorden', 'solicitud_repuesto')
            // Considerar repuestos que tengan entrega o estén en proceso
            ->where(function($q) {
                $q->whereNotNull('re.id')
                  ->orWhereIn('oa.estado', [0, 1]); // Mantener lógica anterior si es necesario
            });

        // Aplicar filtros BASADOS EN REPUESTOS_ENTREGAS
        if (!empty($filtros['estado'])) {
            switch ($filtros['estado']) {
                case 'en_transito':
                    $query->where('re.estado', 'entregado');
                    break;
                case 'usado':
                    $query->where('re.estado', 'usado');
                    break;
                case 'devuelto':
                    $query->where('re.estado', 'devuelto');
                    break;
                case 'pendiente':
                    $query->where('re.estado', 'pendiente');
                    break;
                default:
                    // Si el filtro es otro estado específico
                    $query->where('re.estado', $filtros['estado']);
                    break;
            }
        }

        // Mantener otros filtros existentes
        if (!empty($filtros['codigo_repuesto'])) {
            $query->where('a.codigo_repuesto', 'like', '%' . $filtros['codigo_repuesto'] . '%');
        }

        if (!empty($filtros['codigo_solicitud'])) {
            $query->where('so.codigo', 'like', '%' . $filtros['codigo_solicitud'] . '%');
        }

        if (!empty($filtros['fecha_desde'])) {
            $query->whereDate('re.fecha_entrega', '>=', $filtros['fecha_desde']);
        }

        if (!empty($filtros['fecha_hasta'])) {
            $query->whereDate('re.fecha_entrega', '<=', $filtros['fecha_hasta']);
        }

        $repuestos = $query->orderBy('re.fecha_entrega', 'desc')
                          ->orderBy('oa.created_at', 'desc')
                          ->paginate(20);

        // CONTADORES ACTUALIZADOS BASADOS EN REPUESTOS_ENTREGAS
        $contadores = [
            'total' => DB::table('ordenesarticulos as oa')
                ->join('solicitudesordenes as so', 'oa.idSolicitudesOrdenes', '=', 'so.idSolicitudesOrdenes')
                ->leftJoin('repuestos_entregas as re', function($join) {
                    $join->on('oa.idSolicitudesOrdenes', '=', 're.solicitud_id')
                         ->on('oa.idArticulos', '=', 're.articulo_id');
                })
                ->where('so.tipoorden', 'solicitud_repuesto')
                ->whereNotNull('re.id')
                ->count(),
            
            'en_transito' => DB::table('repuestos_entregas as re')
                ->where('re.estado', 'entregado')
                ->count(),
                
            'usados' => DB::table('repuestos_entregas as re')
                ->where('re.estado', 'usado')
                ->count(),
                
            'devueltos' => DB::table('repuestos_entregas as re')
                ->where('re.estado', 'devuelto')
                ->count(),
                
            'pendientes' => DB::table('repuestos_entregas as re')
                ->where('re.estado', 'pendiente')
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
        // 1. Primero obtenemos solo los campos más básicos para probar
        $repuesto = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idOrdenesArticulos',
                'oa.cantidad',
                'oa.observacion', // Este podría tener caracteres raros
                'oa.estado',
                'oa.fechaUsado',
                'oa.fechaSinUsar',
                'oa.created_at',
                'oa.updated_at',
                'oa.idSolicitudesOrdenes',
                'oa.idArticulos',
                'oa.idUbicacion'
            )
            ->where('oa.idOrdenesArticulos', $id)
            ->first();

        if (!$repuesto) {
            Log::warning('Repuesto no encontrado: ID=' . $id);
            return response()->json([
                'success' => false,
                'message' => 'Repuesto no encontrado'
            ], 404);
        }
        
        Log::info('Repuesto base encontrado: ' . $id);
        
        // 2. Obtener datos adicionales por separado
        $datosAdicionales = DB::table('ordenesarticulos as oa')
            ->join('solicitudesordenes as so', 'oa.idSolicitudesOrdenes', '=', 'so.idSolicitudesOrdenes')
            ->join('articulos as a', 'oa.idArticulos', '=', 'a.idArticulos')
            ->leftJoin('usuarios as u', 'so.idUsuario', '=', 'u.idUsuario')
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->leftJoin('repuestos_entregas as re', function($join) {
                $join->on('oa.idSolicitudesOrdenes', '=', 're.solicitud_id')
                     ->on('oa.idArticulos', '=', 're.articulo_id');
            })
            ->where('oa.idOrdenesArticulos', $id)
            ->select(
                // Campos de articulos
                'a.codigo_repuesto as nombre_repuesto',
                'a.codigo_repuesto',
                'a.sku',
                'a.codigo_barras',
                
                // Campos de solicitudesordenes
                'so.codigo as codigo_solicitud',
                'so.fechaCreacion',
                'so.fecharequerida',
                'so.estado as estado_solicitud',
                DB::raw("CASE 
                    WHEN so.observaciones IS NOT NULL 
                    THEN CONVERT(so.observaciones USING utf8) 
                    ELSE NULL 
                END as observaciones_solicitud"),
                
                // Campos de usuarios
                'u.Nombre as solicitante',
                
                // Campos de subcategorias
                'sc.nombre as subcategoria',
                
                // Campos de repuestos_entregas
                're.estado as estado_entrega',
                're.fecha_entrega',
                DB::raw("CASE 
                    WHEN re.observaciones IS NOT NULL 
                    THEN CONVERT(re.observaciones USING utf8) 
                    ELSE NULL 
                END as obs_entrega"),
                're.numero_ticket',
                're.ubicacion_utilizada',
                're.tipo_entrega',
                DB::raw("CASE 
                    WHEN re.obsEntrega IS NOT NULL 
                    THEN CONVERT(re.obsEntrega USING utf8) 
                    ELSE NULL 
                END as obs_entrega_detalle"),
                
                // Indicadores de fotos
                DB::raw('CASE WHEN oa.fotoRepuesto IS NOT NULL THEN 1 ELSE 0 END as tiene_foto_repuesto'),
                DB::raw('CASE WHEN oa.foto_articulo_usado IS NOT NULL THEN 1 ELSE 0 END as tiene_foto_usado'),
                DB::raw('CASE WHEN oa.foto_articulo_no_usado IS NOT NULL THEN 1 ELSE 0 END as tiene_foto_no_usado'),
                DB::raw('CASE WHEN re.foto_entrega IS NOT NULL THEN 1 ELSE 0 END as tiene_foto_entrega'),
                DB::raw('CASE WHEN re.fotoRetorno IS NOT NULL THEN 1 ELSE 0 END as tiene_foto_retorno'),
                
                // Estado unificado
                DB::raw('CASE 
                    WHEN re.estado = "entregado" THEN "en_transito"
                    WHEN re.estado = "usado" THEN "usado"
                    WHEN re.estado = "devuelto" THEN "devuelto"
                    WHEN re.estado = "pendiente" THEN "pendiente"
                    ELSE COALESCE(re.estado, "sin_entrega")
                END as estado')
            )
            ->first();
        
        if (!$datosAdicionales) {
            Log::warning('No se encontraron datos adicionales para ID=' . $id);
            $datosAdicionales = (object) [];
        }
        
        // 3. Combinar los datos
        $repuestoCompleto = array_merge(
            (array) $repuesto,
            (array) $datosAdicionales
        );
        
        // 4. Limpiar campos potencialmente problemáticos
        $repuestoCompleto = $this->limpiarCaracteresInvalidos($repuestoCompleto);
        
        // 5. Verificar fotos disponibles
        $fotosDisponibles = [];
        $totalFotos = 0;
        
        $indicadores = [
            'tiene_foto_repuesto' => 'fotoRepuesto',
            'tiene_foto_usado' => 'foto_articulo_usado',
            'tiene_foto_no_usado' => 'foto_articulo_no_usado',
            'tiene_foto_entrega' => 'foto_entrega',
            'tiene_foto_retorno' => 'fotoRetorno'
        ];
        
        foreach ($indicadores as $campo => $tipo) {
            if (isset($repuestoCompleto[$campo]) && $repuestoCompleto[$campo] == 1) {
                $fotosDisponibles[$tipo] = true;
                $totalFotos++;
            }
        }
        
        // 6. Agregar información de fotos
        $repuestoCompleto['tiene_fotos'] = ($totalFotos > 0);
        $repuestoCompleto['total_fotos'] = $totalFotos;
        
        // 7. Eliminar campos temporales
        foreach (array_keys($indicadores) as $campoTemporal) {
            unset($repuestoCompleto[$campoTemporal]);
        }
        
        Log::info('¿Tiene fotos?: ' . ($repuestoCompleto['tiene_fotos'] ? 'SI' : 'NO'));
        Log::info('Fotos disponibles:', $fotosDisponibles);
        
        return response()->json([
            'success' => true,
            'data' => $repuestoCompleto,
            'info_fotos' => [
                'total' => $totalFotos,
                'disponibles' => $fotosDisponibles,
                'tiene_fotos' => $totalFotos > 0
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Error al obtener detalles del repuesto: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        // Intentar un debug más específico
        try {
            $debug = DB::select('SELECT VERSION() as mysql_version');
            Log::info('MySQL Version: ' . json_encode($debug));
        } catch (\Exception $debugE) {
            Log::error('Error obteniendo versión MySQL: ' . $debugE->getMessage());
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener detalles: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Método auxiliar para limpiar caracteres inválidos
 */
private function limpiarCaracteresInvalidos(array $data)
{
    foreach ($data as $key => &$value) {
        if (is_string($value)) {
            // Eliminar caracteres no UTF-8
            $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
            
            // Reemplazar caracteres de control
            $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value);
            
            // Si aún hay problemas, usar iconv
            if (!mb_check_encoding($value, 'UTF-8')) {
                $value = iconv('UTF-8', 'UTF-8//IGNORE', $value);
            }
            
            // Si está vacío después de limpiar, poner null
            if (trim($value) === '') {
                $value = null;
            }
        }
    }
    
    return $data;
}
/**
 * Obtener todas las fotos - VERSIÓN CORREGIDA
 */
public function obtenerTodasFotos($id)
{
    Log::info('=== OBTENER TODAS LAS FOTOS ===');
    Log::info('ID solicitado: ' . $id);
    
    try {
        // 1. Primero obtener el ID del artículo y solicitud para poder buscar en repuestos_entregas
        $infoBasica = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idOrdenesArticulos',
                'oa.idSolicitudesOrdenes',
                'oa.idArticulos',
                'oa.fotoRepuesto',
                'oa.foto_articulo_usado',
                'oa.foto_articulo_no_usado'
            )
            ->where('oa.idOrdenesArticulos', $id)
            ->first();
            
        if (!$infoBasica) {
            Log::warning('Repuesto no encontrado: ID=' . $id);
            return response()->json([
                'success' => false,
                'message' => 'Repuesto no encontrado'
            ], 404);
        }
        
        Log::info('Info básica obtenida:', [
            'solicitud_id' => $infoBasica->idSolicitudesOrdenes,
            'articulo_id' => $infoBasica->idArticulos
        ]);
        
        // 2. Inicializar arrays para fotos
        $fotosPorTipo = [
            'fotoRepuesto' => [],
            'foto_articulo_usado' => [],
            'foto_articulo_no_usado' => [],
            'foto_entrega' => [],
            'fotoRetorno' => []
        ];
        
        $totalFotos = 0;
        
        // 3. Procesar fotos de ordenesarticulos
        // Foto del repuesto
        if (!empty($infoBasica->fotoRepuesto)) {
            try {
                Log::info('Procesando fotoRepuesto...');
                $base64 = base64_encode($infoBasica->fotoRepuesto);
                $fotosPorTipo['fotoRepuesto'][] = [
                    'id' => $totalFotos + 1,
                    'base64' => 'data:image/jpeg;base64,' . $base64,
                    'mime' => 'image/jpeg',
                    'nombre' => 'foto_repuesto_' . ($totalFotos + 1),
                    'tipo_foto_db' => 'fotoRepuesto'
                ];
                $totalFotos++;
                Log::info('✅ fotoRepuesto procesada correctamente');
            } catch (\Exception $e) {
                Log::error('Error procesando fotoRepuesto: ' . $e->getMessage());
            }
        }
        
        // Foto artículo usado
        if (!empty($infoBasica->foto_articulo_usado)) {
            try {
                Log::info('Procesando foto_articulo_usado...');
                $base64 = base64_encode($infoBasica->foto_articulo_usado);
                $fotosPorTipo['foto_articulo_usado'][] = [
                    'id' => $totalFotos + 1,
                    'base64' => 'data:image/jpeg;base64,' . $base64,
                    'mime' => 'image/jpeg',
                    'nombre' => 'foto_usado_' . ($totalFotos + 1),
                    'tipo_foto_db' => 'foto_articulo_usado'
                ];
                $totalFotos++;
                Log::info('✅ foto_articulo_usado procesada correctamente');
            } catch (\Exception $e) {
                Log::error('Error procesando foto_articulo_usado: ' . $e->getMessage());
            }
        }
        
        // Foto artículo no usado
        if (!empty($infoBasica->foto_articulo_no_usado)) {
            try {
                Log::info('Procesando foto_articulo_no_usado...');
                $base64 = base64_encode($infoBasica->foto_articulo_no_usado);
                $fotosPorTipo['foto_articulo_no_usado'][] = [
                    'id' => $totalFotos + 1,
                    'base64' => 'data:image/jpeg;base64,' . $base64,
                    'mime' => 'image/jpeg',
                    'nombre' => 'foto_no_usado_' . ($totalFotos + 1),
                    'tipo_foto_db' => 'foto_articulo_no_usado'
                ];
                $totalFotos++;
                Log::info('✅ foto_articulo_no_usado procesada correctamente');
            } catch (\Exception $e) {
                Log::error('Error procesando foto_articulo_no_usado: ' . $e->getMessage());
            }
        }
        
        // 4. Buscar fotos en repuestos_entregas usando los IDs correctos
        if ($infoBasica->idSolicitudesOrdenes && $infoBasica->idArticulos) {
            Log::info('Buscando fotos en repuestos_entregas...', [
                'solicitud_id' => $infoBasica->idSolicitudesOrdenes,
                'articulo_id' => $infoBasica->idArticulos
            ]);
            
            $fotosEntrega = DB::table('repuestos_entregas')
                ->select('foto_entrega', 'fotoRetorno', 'tipo_archivo_foto')
                ->where('solicitud_id', $infoBasica->idSolicitudesOrdenes)
                ->where('articulo_id', $infoBasica->idArticulos)
                ->first();
            
            if ($fotosEntrega) {
                Log::info('✅ Registro encontrado en repuestos_entregas');
                
                // Foto de entrega
                if (!empty($fotosEntrega->foto_entrega)) {
                    try {
                        Log::info('Procesando foto_entrega...');
                        $base64 = base64_encode($fotosEntrega->foto_entrega);
                        $mimeType = $fotosEntrega->tipo_archivo_foto ?? 'image/jpeg';
                        $fotosPorTipo['foto_entrega'][] = [
                            'id' => $totalFotos + 1,
                            'base64' => 'data:' . $mimeType . ';base64,' . $base64,
                            'mime' => $mimeType,
                            'nombre' => 'foto_entrega_' . ($totalFotos + 1),
                            'tipo_foto_db' => 'foto_entrega'
                        ];
                        $totalFotos++;
                        Log::info('✅ foto_entrega procesada correctamente');
                    } catch (\Exception $e) {
                        Log::error('Error procesando foto_entrega: ' . $e->getMessage());
                    }
                } else {
                    Log::info('❌ foto_entrega está vacía o es NULL');
                }
                
                // Foto de retorno
                if (!empty($fotosEntrega->fotoRetorno)) {
                    try {
                        Log::info('Procesando fotoRetorno...');
                        $base64 = base64_encode($fotosEntrega->fotoRetorno);
                        $mimeType = $fotosEntrega->tipo_archivo_foto ?? 'image/jpeg';
                        $fotosPorTipo['fotoRetorno'][] = [
                            'id' => $totalFotos + 1,
                            'base64' => 'data:' . $mimeType . ';base64,' . $base64,
                            'mime' => $mimeType,
                            'nombre' => 'foto_retorno_' . ($totalFotos + 1),
                            'tipo_foto_db' => 'fotoRetorno'
                        ];
                        $totalFotos++;
                        Log::info('✅ fotoRetorno procesada correctamente');
                    } catch (\Exception $e) {
                        Log::error('Error procesando fotoRetorno: ' . $e->getMessage());
                    }
                } else {
                    Log::info('❌ fotoRetorno está vacía o es NULL');
                }
            } else {
                Log::warning('No se encontró registro en repuestos_entregas para:', [
                    'solicitud_id' => $infoBasica->idSolicitudesOrdenes,
                    'articulo_id' => $infoBasica->idArticulos
                ]);
            }
        }
        
        // 5. Log de diagnóstico
        Log::info('📊 Resumen de fotos encontradas:');
        foreach ($fotosPorTipo as $tipo => $fotos) {
            Log::info("  - $tipo: " . count($fotos) . ' fotos');
            if (count($fotos) > 0) {
                Log::info("    Ejemplo Base64 (primeros 50 chars): " . substr($fotos[0]['base64'], 0, 50) . '...');
            }
        }
        
        // 6. Preparar respuesta estructurada
        $tiposConfig = [
            'fotoRepuesto' => [
                'titulo' => '📸 Fotos del Repuesto',
                'descripcion' => 'Fotos originales del repuesto'
            ],
            'foto_articulo_usado' => [
                'titulo' => '✅ Repuesto Usado',
                'descripcion' => 'Fotos del repuesto utilizado en el equipo'
            ],
            'foto_articulo_no_usado' => [
                'titulo' => '❌ Repuesto Devuelto',
                'descripcion' => 'Fotos del repuesto devuelto sin usar'
            ],
            'foto_entrega' => [
                'titulo' => '📦 Entrega',
                'descripcion' => 'Fotos de la entrega del repuesto'
            ],
            'fotoRetorno' => [
                'titulo' => '🔄 Retorno',
                'descripcion' => 'Fotos del retorno del repuesto'
            ]
        ];
        
        $respuestaEstructurada = [];
        
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
                Log::info("✅ $tipoKey tiene " . count($fotosDelTipo) . " fotos");
            } else {
                $respuestaEstructurada[$tipoKey] = [
                    'tiene' => false,
                    'total' => 0,
                    'fotos' => [],
                    'titulo' => $config['titulo'],
                    'descripcion' => 'No hay fotos disponibles'
                ];
                Log::info("❌ $tipoKey no tiene fotos");
            }
        }
        
        Log::info('Total fotos procesadas: ' . $totalFotos);
        
        return response()->json([
            'success' => true,
            'fotos' => $respuestaEstructurada,
            'total_fotos' => $totalFotos,
            'info' => 'Fotos obtenidas correctamente',
            'debug' => [
                'ordenesarticulos_id' => $infoBasica->idOrdenesArticulos,
                'solicitud_id' => $infoBasica->idSolicitudesOrdenes,
                'articulo_id' => $infoBasica->idArticulos
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('❌ Error al obtener fotos: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener las fotos: ' . $e->getMessage()
        ], 500);
    }
}
}