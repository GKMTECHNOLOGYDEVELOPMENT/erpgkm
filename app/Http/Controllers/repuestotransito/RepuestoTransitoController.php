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
        $query = $this->buildQuery($filtros);
        
        $repuestos = $query->paginate(20);

        // CONTADORES ACTUALIZADOS BASADOS EN REPUESTOS_ENTREGAS
        $contadores = $this->getContadores($filtros);
        
        Log::info('Total repuestos encontrados: ' . $repuestos->total());
        Log::info('Contadores:', $contadores);
        
        return view('repuestos-transito.index', compact('repuestos', 'contadores', 'filtros'));
    }

    /**
     * Método AJAX para filtrar
     */
   public function filtrar(Request $request)
{
    $filtros = $request->all();
    
    Log::info('=== FILTRAR AJAX ===');
    Log::info('Filtros AJAX:', $filtros);
    
    // Construir consulta con filtros
    $query = $this->buildQuery($filtros);
    
    // Obtener repuestos paginados
    $repuestos = $query->paginate(20)->appends($filtros);
    
    // Obtener contadores actualizados
    $contadores = $this->getContadores($filtros);
    
    // Verificar si es solicitud AJAX
    if ($request->ajax()) {
        // Devolver vista parcial de tabla
        $tablaView = view('repuestos-transito.partials.tabla', compact('repuestos'))->render();
        $summaryView = view('repuestos-transito.partials.summary-cards', compact('contadores'))->render();
        
        return response()->json([
            'success' => true,
            'tabla' => $tablaView,
            'summary' => $summaryView,
            'total' => $repuestos->total(),
            'current_page' => $repuestos->currentPage(),
            'last_page' => $repuestos->lastPage()
        ]);
    }
    
    return back()->withInput();
}

  private function buildQuery($filtros = [])
{
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
            // CAMPOS QUE TU VISTA NECESITA
            DB::raw('COALESCE(mo.nombre, "N/A") as modelo'),
            DB::raw('COALESCE(ma.nombre, "N/A") as marca'),
            're.estado as estado_entrega',
            're.fecha_entrega',
            're.observaciones as obs_entrega',
            're.numero_ticket',
            // Estado dinámico basado en repuestos_entregas - AGREGADO CEDIDO
            DB::raw('CASE 
                WHEN re.estado = "entregado" THEN "en_transito"
                WHEN re.estado = "cedido" THEN "cedido"
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
        // RELACIÓN CON TICKET PARA OBTENER EL MODELO
        ->leftJoin('tickets as t', 't.idTickets', '=', 'so.idticket')
        ->leftJoin('modelo as mo', 'mo.idModelo', '=', 't.idModelo')
        ->leftJoin('marca as ma', 'ma.idMarca', '=', 'mo.idMarca')
        ->leftJoin('repuestos_entregas as re', function($join) {
            $join->on('oa.idSolicitudesOrdenes', '=', 're.solicitud_id')
                 ->on('oa.idArticulos', '=', 're.articulo_id');
        })
        ->where('so.tipoorden', 'solicitud_repuesto')
        ->where(function($q) {
            $q->whereNotNull('re.id')
              ->orWhereIn('oa.estado', [0, 1]);
        });

    // Aplicar filtros BASADOS EN REPUESTOS_ENTREGAS - AGREGADO CEDIDO
    if (!empty($filtros['estado'])) {
        $query->where(function($q) use ($filtros) {
            switch ($filtros['estado']) {
                case 'en_transito':
                    $q->where('re.estado', 'entregado');
                    break;
                case 'cedido':
                    $q->where('re.estado', 'cedido');
                    break;
                case 'usado':
                    $q->where('re.estado', 'usado');
                    break;
                case 'devuelto':
                    $q->where('re.estado', 'devuelto');
                    break;
                case 'pendiente':
                    $q->where('re.estado', 'pendiente');
                    break;
                default:
                    $q->where('re.estado', $filtros['estado']);
                    break;
            }
        });
    }

    // Mantener otros filtros existentes
    if (!empty($filtros['codigo_repuesto'])) {
        $query->where('a.codigo_repuesto', 'like', '%' . $filtros['codigo_repuesto'] . '%');
    }

    if (!empty($filtros['codigo_solicitud'])) {
        $query->where('so.codigo', 'like', '%' . $filtros['codigo_solicitud'] . '%');
    }

    if (!empty($filtros['solicitante'])) {
        $query->where('u.Nombre', 'like', '%' . $filtros['solicitante'] . '%');
    }

    // Filtro de modelo - buscar tanto en modelo como en marca
    if (!empty($filtros['modelo'])) {
        $query->where(function($q) use ($filtros) {
            $q->where('mo.nombre', 'like', '%' . $filtros['modelo'] . '%')
              ->orWhere('ma.nombre', 'like', '%' . $filtros['modelo'] . '%');
        });
    }

    // Filtro de marca específica
    if (!empty($filtros['marca'])) {
        $query->where('ma.nombre', 'like', '%' . $filtros['marca'] . '%');
    }

    if (!empty($filtros['subcategoria'])) {
        $query->where('sc.nombre', 'like', '%' . $filtros['subcategoria'] . '%');
    }

    if (!empty($filtros['fecha_desde'])) {
        $query->whereDate('re.fecha_entrega', '>=', $filtros['fecha_desde']);
    }

    if (!empty($filtros['fecha_hasta'])) {
        $query->whereDate('re.fecha_entrega', '<=', $filtros['fecha_hasta']);
    }

    if (!empty($filtros['numero_ticket'])) {
        $query->where('re.numero_ticket', 'like', '%' . $filtros['numero_ticket'] . '%');
    }

    // AGRUPAR PARA EVITAR DUPLICADOS POR MODELOS
    $query->groupBy(
        'oa.idOrdenesArticulos',
        'a.idArticulos',
        'so.idSolicitudesOrdenes',
        're.id'
    );

    return $query->orderBy('re.fecha_entrega', 'desc')
                 ->orderBy('oa.created_at', 'desc');
}
    /**
     * Obtiene contadores con filtros aplicados
     */
   private function getContadores($filtros = [])
{
    // Base query para contadores
    $baseQuery = DB::table('ordenesarticulos as oa')
        ->join('solicitudesordenes as so', 'oa.idSolicitudesOrdenes', '=', 'so.idSolicitudesOrdenes')
        ->leftJoin('repuestos_entregas as re', function($join) {
            $join->on('oa.idSolicitudesOrdenes', '=', 're.solicitud_id')
                 ->on('oa.idArticulos', '=', 're.articulo_id');
        })
        ->where('so.tipoorden', 'solicitud_repuesto')
        ->whereNotNull('re.id');

    // Aplicar mismos filtros a contadores
    if (!empty($filtros['codigo_repuesto'])) {
        $baseQuery->join('articulos as a', 'oa.idArticulos', '=', 'a.idArticulos')
                  ->where('a.codigo_repuesto', 'like', '%' . $filtros['codigo_repuesto'] . '%');
    }

    if (!empty($filtros['fecha_desde'])) {
        $baseQuery->whereDate('re.fecha_entrega', '>=', $filtros['fecha_desde']);
    }

    if (!empty($filtros['fecha_hasta'])) {
        $baseQuery->whereDate('re.fecha_entrega', '<=', $filtros['fecha_hasta']);
    }

    return [
        'total' => (clone $baseQuery)->count(),
        
        'en_transito' => (clone $baseQuery)
            ->where('re.estado', 'entregado')
            ->count(),
            
        'cedidos' => (clone $baseQuery)  // NUEVO CONTADOR PARA CEDIDOS
            ->where('re.estado', 'cedido')
            ->count(),
            
        'usados' => (clone $baseQuery)
            ->where('re.estado', 'usado')
            ->count(),
            
        'devueltos' => (clone $baseQuery)
            ->where('re.estado', 'devuelto')
            ->count(),
            
        'pendientes' => (clone $baseQuery)
            ->where('re.estado', 'pendiente')
            ->count(),
    ];
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
 * Obtener todas las fotos - VERSIÓN CORREGIDA (Desde repuestos_entregas)
 */
public function obtenerTodasFotos($id)
{
    Log::info('=== OBTENER TODAS LAS FOTOS - DESDE repuestos_entregas ===');
    Log::info('ID solicitado: ' . $id);
    
    try {
        // 1. Primero obtener el ID del artículo y solicitud para buscar en repuestos_entregas
        $infoBasica = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idOrdenesArticulos',
                'oa.idSolicitudesOrdenes',
                'oa.idArticulos',
                'oa.fotoRepuesto', // Mantenemos estas por si acaso
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
            'foto_entrega' => [],    // De repuestos_entregas
            'foto_retorno' => [],    // De repuestos_entregas
            'fotoRepuesto' => [],    // De ordenesarticulos (backup)
            'foto_articulo_usado' => [], // De ordenesarticulos (backup)
            'foto_articulo_no_usado' => [] // De ordenesarticulos (backup)
        ];
        
        $totalFotos = 0;
        
        // 3. BUSCAR PRIMERO EN repuestos_entregas (prioridad principal)
        if ($infoBasica->idSolicitudesOrdenes && $infoBasica->idArticulos) {
            Log::info('Buscando fotos en repuestos_entregas...', [
                'solicitud_id' => $infoBasica->idSolicitudesOrdenes,
                'articulo_id' => $infoBasica->idArticulos
            ]);
            
            $fotosEntrega = DB::table('repuestos_entregas')
                ->select(
                    'foto_entrega', 
                    'fotoRetorno', 
                    'tipo_archivo_foto',
                    'fotoEmisor' // También esta columna
                )
                ->where('solicitud_id', $infoBasica->idSolicitudesOrdenes)
                ->where('articulo_id', $infoBasica->idArticulos)
                ->first();
            
            if ($fotosEntrega) {
                Log::info('✅ Registro encontrado en repuestos_entregas');
                
                // Foto de entrega (columna: foto_entrega)
                if (!empty($fotosEntrega->foto_entrega)) {
                    try {
                        Log::info('Procesando foto_entrega de repuestos_entregas...');
                        $base64 = base64_encode($fotosEntrega->foto_entrega);
                        $mimeType = $fotosEntrega->tipo_archivo_foto ?? 'image/jpeg';
                        $fotosPorTipo['foto_entrega'][] = [
                            'id' => $totalFotos + 1,
                            'base64' => 'data:' . $mimeType . ';base64,' . $base64,
                            'mime' => $mimeType,
                            'nombre' => 'foto_entrega_' . ($totalFotos + 1),
                            'tipo_foto_db' => 'foto_entrega',
                            'fuente' => 'repuestos_entregas'
                        ];
                        $totalFotos++;
                        Log::info('✅ foto_entrega procesada correctamente');
                    } catch (\Exception $e) {
                        Log::error('Error procesando foto_entrega: ' . $e->getMessage());
                    }
                } else {
                    Log::info('❌ foto_entrega está vacía o es NULL');
                }
                
                // Foto de retorno (columna: fotoRetorno)
                if (!empty($fotosEntrega->fotoRetorno)) {
                    try {
                        Log::info('Procesando fotoRetorno de repuestos_entregas...');
                        $base64 = base64_encode($fotosEntrega->fotoRetorno);
                        $mimeType = $fotosEntrega->tipo_archivo_foto ?? 'image/jpeg';
                        $fotosPorTipo['foto_retorno'][] = [
                            'id' => $totalFotos + 1,
                            'base64' => 'data:' . $mimeType . ';base64,' . $base64,
                            'mime' => $mimeType,
                            'nombre' => 'foto_retorno_' . ($totalFotos + 1),
                            'tipo_foto_db' => 'fotoRetorno',
                            'fuente' => 'repuestos_entregas'
                        ];
                        $totalFotos++;
                        Log::info('✅ fotoRetorno procesada correctamente');
                    } catch (\Exception $e) {
                        Log::error('Error procesando fotoRetorno: ' . $e->getMessage());
                    }
                } else {
                    Log::info('❌ fotoRetorno está vacía o es NULL');
                }
                
                // Foto del emisor (columna: fotoEmisor)
                if (!empty($fotosEntrega->fotoEmisor)) {
                    try {
                        Log::info('Procesando fotoEmisor de repuestos_entregas...');
                        $base64 = base64_encode($fotosEntrega->fotoEmisor);
                        $mimeType = $fotosEntrega->tipo_archivo_foto ?? 'image/jpeg';
                        $fotosPorTipo['foto_entrega'][] = [
                            'id' => $totalFotos + 1,
                            'base64' => 'data:' . $mimeType . ';base64,' . $base64,
                            'mime' => $mimeType,
                            'nombre' => 'foto_emisor_' . ($totalFotos + 1),
                            'tipo_foto_db' => 'fotoEmisor',
                            'fuente' => 'repuestos_entregas'
                        ];
                        $totalFotos++;
                        Log::info('✅ fotoEmisor procesada correctamente');
                    } catch (\Exception $e) {
                        Log::error('Error procesando fotoEmisor: ' . $e->getMessage());
                    }
                }
            } else {
                Log::warning('No se encontró registro en repuestos_entregas para:', [
                    'solicitud_id' => $infoBasica->idSolicitudesOrdenes,
                    'articulo_id' => $infoBasica->idArticulos
                ]);
                
                // 4. COMO BACKUP: Buscar fotos en ordenesarticulos solo si no hay en repuestos_entregas
                Log::info('Buscando fotos de backup en ordenesarticulos...');
                
                // Foto del repuesto (backup)
                if (!empty($infoBasica->fotoRepuesto)) {
                    try {
                        Log::info('Procesando fotoRepuesto (backup)...');
                        $base64 = base64_encode($infoBasica->fotoRepuesto);
                        $fotosPorTipo['fotoRepuesto'][] = [
                            'id' => $totalFotos + 1,
                            'base64' => 'data:image/jpeg;base64,' . $base64,
                            'mime' => 'image/jpeg',
                            'nombre' => 'foto_repuesto_' . ($totalFotos + 1),
                            'tipo_foto_db' => 'fotoRepuesto',
                            'fuente' => 'ordenesarticulos'
                        ];
                        $totalFotos++;
                        Log::info('✅ fotoRepuesto procesada correctamente (backup)');
                    } catch (\Exception $e) {
                        Log::error('Error procesando fotoRepuesto: ' . $e->getMessage());
                    }
                }
                
                // Foto artículo usado (backup)
                if (!empty($infoBasica->foto_articulo_usado)) {
                    try {
                        Log::info('Procesando foto_articulo_usado (backup)...');
                        $base64 = base64_encode($infoBasica->foto_articulo_usado);
                        $fotosPorTipo['foto_articulo_usado'][] = [
                            'id' => $totalFotos + 1,
                            'base64' => 'data:image/jpeg;base64,' . $base64,
                            'mime' => 'image/jpeg',
                            'nombre' => 'foto_usado_' . ($totalFotos + 1),
                            'tipo_foto_db' => 'foto_articulo_usado',
                            'fuente' => 'ordenesarticulos'
                        ];
                        $totalFotos++;
                        Log::info('✅ foto_articulo_usado procesada correctamente (backup)');
                    } catch (\Exception $e) {
                        Log::error('Error procesando foto_articulo_usado: ' . $e->getMessage());
                    }
                }
                
                // Foto artículo no usado (backup)
                if (!empty($infoBasica->foto_articulo_no_usado)) {
                    try {
                        Log::info('Procesando foto_articulo_no_usado (backup)...');
                        $base64 = base64_encode($infoBasica->foto_articulo_no_usado);
                        $fotosPorTipo['foto_articulo_no_usado'][] = [
                            'id' => $totalFotos + 1,
                            'base64' => 'data:image/jpeg;base64,' . $base64,
                            'mime' => 'image/jpeg',
                            'nombre' => 'foto_no_usado_' . ($totalFotos + 1),
                            'tipo_foto_db' => 'foto_articulo_no_usado',
                            'fuente' => 'ordenesarticulos'
                        ];
                        $totalFotos++;
                        Log::info('✅ foto_articulo_no_usado procesada correctamente (backup)');
                    } catch (\Exception $e) {
                        Log::error('Error procesando foto_articulo_no_usado: ' . $e->getMessage());
                    }
                }
            }
        }
        
        // 5. Log de diagnóstico
        Log::info('📊 Resumen de fotos encontradas:');
        foreach ($fotosPorTipo as $tipo => $fotos) {
            Log::info("  - $tipo: " . count($fotos) . ' fotos');
            if (count($fotos) > 0) {
                $fuente = $fotos[0]['fuente'] ?? 'desconocida';
                Log::info("    Fuente: $fuente");
            }
        }
        
        // 6. Preparar respuesta estructurada
        $tiposConfig = [
            'foto_entrega' => [
                'titulo' => '📦 Fotos de Entrega',
                'descripcion' => 'Fotos del momento de entrega del repuesto'
            ],
            'foto_retorno' => [
                'titulo' => '🔄 Fotos de Retorno',
                'descripcion' => 'Fotos del retorno o devolución del repuesto'
            ],
            'fotoRepuesto' => [
                'titulo' => '📸 Fotos del Repuesto',
                'descripcion' => 'Fotos originales del repuesto (backup)'
            ],
            'foto_articulo_usado' => [
                'titulo' => '✅ Repuesto Usado',
                'descripcion' => 'Fotos del repuesto utilizado (backup)'
            ],
            'foto_articulo_no_usado' => [
                'titulo' => '❌ Repuesto Devuelto',
                'descripcion' => 'Fotos del repuesto devuelto sin usar (backup)'
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
                    'descripcion' => $config['descripcion'],
                    'fuente' => $fotosDelTipo[0]['fuente'] ?? 'desconocida'
                ];
                Log::info("✅ $tipoKey tiene " . count($fotosDelTipo) . " fotos");
            } else {
                $respuestaEstructurada[$tipoKey] = [
                    'tiene' => false,
                    'total' => 0,
                    'fotos' => [],
                    'titulo' => $config['titulo'],
                    'descripcion' => 'No hay fotos disponibles',
                    'fuente' => 'no_disponible'
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
            'fuente_principal' => 'repuestos_entregas',
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


public  function show ($id)
    {
        //
        return view('repuestos-transito.show', compact('id'));
    }
}