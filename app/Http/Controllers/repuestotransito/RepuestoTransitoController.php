<?php

namespace App\Http\Controllers\repuestotransito;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Ordenesarticulo;
use Illuminate\Support\Facades\Log;

class RepuestoTransitoController extends Controller
{
    public function index(Request $request)
    {
        $filtros = $request->all();
        
        Log::info('=== INICIO INDEX REPUESTOS TRANSITO ===');
        Log::info('Filtros aplicados:', $filtros);
        
        // Obtener los repuestos en tránsito (pendientes)
        $query = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idOrdenesArticulos',
                'oa.cantidad',
                'oa.observacion',
                'oa.fotos_evidencia',
                'oa.foto_articulo_usado',
                'oa.foto_articulo_no_usado',
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

        // Log para verificar datos de fotos
        Log::info('Total repuestos obtenidos: ' . $repuestos->count());
        
        foreach ($repuestos as $index => $repuesto) {
            Log::info("Repuesto {$index} - ID: {$repuesto->idOrdenesArticulos}, Nombre: {$repuesto->nombre_repuesto}");
            Log::info("  - fotos_evidencia: " . ($repuesto->fotos_evidencia ? 'SI (' . strlen($repuesto->fotos_evidencia) . ' chars)' : 'NO'));
            Log::info("  - foto_articulo_usado: " . ($repuesto->foto_articulo_usado ? 'SI (' . strlen($repuesto->foto_articulo_usado) . ' chars)' : 'NO'));
            Log::info("  - foto_articulo_no_usado: " . ($repuesto->foto_articulo_no_usado ? 'SI (' . strlen($repuesto->foto_articulo_no_usado) . ' chars)' : 'NO'));
            Log::info("  - Estado: " . ($repuesto->fechaUsado ? 'usado' : ($repuesto->fechaSinUsar ? 'no_usado' : 'pendiente')));
            
            // Verificar contenido de cada campo
            if ($repuesto->fotos_evidencia) {
                $jsonFotos = json_decode($repuesto->fotos_evidencia, true);
                if (is_array($jsonFotos)) {
                    Log::info("  - fotos_evidencia JSON válido, contiene " . count($jsonFotos) . " fotos");
                } else {
                    Log::info("  - fotos_evidencia NO es JSON válido");
                }
            }
            
            if ($repuesto->foto_articulo_usado) {
                $jsonUsado = json_decode($repuesto->foto_articulo_usado, true);
                if (is_array($jsonUsado)) {
                    Log::info("  - foto_articulo_usado JSON válido, contiene " . count($jsonUsado) . " fotos");
                } else {
                    Log::info("  - foto_articulo_usado NO es JSON válido");
                }
            }
            
            if ($repuesto->foto_articulo_no_usado) {
                $jsonNoUsado = json_decode($repuesto->foto_articulo_no_usado, true);
                if (is_array($jsonNoUsado)) {
                    Log::info("  - foto_articulo_no_usado JSON válido, contiene " . count($jsonNoUsado) . " fotos");
                } else {
                    Log::info("  - foto_articulo_no_usado NO es JSON válido");
                }
            }
        }

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

        Log::info('=== FIN INDEX REPUESTOS TRANSITO ===');
        
        return view('repuestos-transito.index', compact('repuestos', 'contadores', 'filtros'));
    }

    public function obtenerDetalles($id)
    {
        Log::info("=== INICIO OBTENER DETALLES PARA ID: {$id} ===");
        
        try {
            $repuesto = DB::table('ordenesarticulos as oa')
                ->select(
                    'oa.*',
                    'a.nombre as nombre_repuesto',
                    'a.codigo_repuesto',
                    'a.sku',
                    'sc.nombre as subcategoria',
                    'so.codigo as codigo_solicitud',
                    'so.fechaCreacion',
                    'so.fecharequerida',
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
                Log::warning("Repuesto {$id} no encontrado");
                return response()->json([
                    'success' => false,
                    'message' => 'Repuesto no encontrado'
                ], 404);
            }

            Log::info("Repuesto encontrado: {$repuesto->nombre_repuesto}");
            Log::info("  - fechaUsado: " . ($repuesto->fechaUsado ? 'SI' : 'NO'));
            Log::info("  - fechaSinUsar: " . ($repuesto->fechaSinUsar ? 'SI' : 'NO'));
            Log::info("  - foto_articulo_usado: " . ($repuesto->foto_articulo_usado ? 'SI (' . strlen($repuesto->foto_articulo_usado) . ' chars)' : 'NO'));
            Log::info("  - foto_articulo_no_usado: " . ($repuesto->foto_articulo_no_usado ? 'SI (' . strlen($repuesto->foto_articulo_no_usado) . ' chars)' : 'NO'));
            Log::info("  - fotos_evidencia: " . ($repuesto->fotos_evidencia ? 'SI (' . strlen($repuesto->fotos_evidencia) . ' chars)' : 'NO'));

            // Obtener URLs de evidencias desde las NUEVAS columnas
            $evidencias = [];
            
            // 1. Si tiene fechaUsado, buscar en foto_articulo_usado
            if ($repuesto->fechaUsado && $repuesto->foto_articulo_usado) {
                Log::info("Buscando en foto_articulo_usado...");
                $fotos = json_decode($repuesto->foto_articulo_usado, true);
                if (is_array($fotos) && count($fotos) > 0) {
                    Log::info("Encontradas " . count($fotos) . " fotos en foto_articulo_usado");
                    $evidencias[] = route('repuesto-transito.imagen', [
                        'id' => $id, 
                        'tipo' => 'usado',
                        'index' => 0
                    ]);
                } else {
                    Log::info("foto_articulo_usado vacío o no es JSON válido");
                }
            }
            
            // 2. Si tiene fechaSinUsar, buscar en foto_articulo_no_usado
            if ($repuesto->fechaSinUsar && $repuesto->foto_articulo_no_usado) {
                Log::info("Buscando en foto_articulo_no_usado...");
                $fotos = json_decode($repuesto->foto_articulo_no_usado, true);
                if (is_array($fotos) && count($fotos) > 0) {
                    Log::info("Encontradas " . count($fotos) . " fotos en foto_articulo_no_usado");
                    $evidencias[] = route('repuesto-transito.imagen', [
                        'id' => $id, 
                        'tipo' => 'no_usado',
                        'index' => 0
                    ]);
                } else {
                    Log::info("foto_articulo_no_usado vacío o no es JSON válido");
                }
            }
            
            // 3. Si no hay en las nuevas columnas, usar fotos_evidencia (compatibilidad)
            if (empty($evidencias) && $repuesto->fotos_evidencia) {
                Log::info("Buscando en fotos_evidencia (compatibilidad)...");
                $fotos = json_decode($repuesto->fotos_evidencia, true);
                if (is_array($fotos)) {
                    Log::info("Encontradas " . count($fotos) . " fotos en fotos_evidencia");
                    foreach ($fotos as $index => $foto) {
                        $evidencias[] = route('repuesto-transito.imagen', [
                            'id' => $id, 
                            'tipo' => 'evidencia', 
                            'index' => $index
                        ]);
                    }
                } else {
                    Log::info("fotos_evidencia vacío o no es JSON válido");
                }
            }

            Log::info("Total evidencias encontradas: " . count($evidencias));
            Log::info("Evidencias: " . json_encode($evidencias));

            return response()->json([
                'success' => true,
                'data' => $repuesto,
                'evidencias' => $evidencias
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener detalles del repuesto: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener detalles'
            ], 500);
        } finally {
            Log::info("=== FIN OBTENER DETALLES PARA ID: {$id} ===");
        }
    }

    public function mostrarImagen($id, $tipo = 'usado', $index = 0)
    {
        Log::info("=== INICIO MOSTRAR IMAGEN ===");
        Log::info("Parámetros - ID: {$id}, Tipo: {$tipo}, Índice: {$index}");
        
        try {
            $repuesto = DB::table('ordenesarticulos')
                ->select('fotos_evidencia', 'foto_articulo_usado', 'foto_articulo_no_usado')
                ->where('idOrdenesArticulos', $id)
                ->first();

            if (!$repuesto) {
                Log::warning("Repuesto {$id} no encontrado");
                return $this->getDefaultImage();
            }

            Log::info("Repuesto encontrado, analizando campos de fotos:");
            Log::info("  - foto_articulo_usado: " . ($repuesto->foto_articulo_usado ? 'SI' : 'NO'));
            Log::info("  - foto_articulo_no_usado: " . ($repuesto->foto_articulo_no_usado ? 'SI' : 'NO'));
            Log::info("  - fotos_evidencia: " . ($repuesto->fotos_evidencia ? 'SI' : 'NO'));

            $imagenBlob = null;
            $logMessage = "";
            
            switch ($tipo) {
                case 'usado':
                    Log::info("Buscando foto_articulo_usado para repuesto {$id}");
                    if ($repuesto->foto_articulo_usado) {
                        $fotos = json_decode($repuesto->foto_articulo_usado, true);
                        Log::info("JSON decodificado de foto_articulo_usado: " . (is_array($fotos) ? 'SI (array)' : 'NO'));
                        if (is_array($fotos)) {
                            Log::info("Total fotos en array: " . count($fotos));
                            Log::info("Buscando índice {$index}");
                        }
                        
                        if (is_array($fotos) && isset($fotos[$index])) {
                            $imagenBlob = $fotos[$index];
                            $logMessage = "Encontrada foto_articulo_usado (binario), índice {$index}";
                            Log::info("Imagen encontrada en índice {$index}, tamaño blob: " . strlen($imagenBlob));
                        } else {
                            $logMessage = "foto_articulo_usado vacío o índice inválido";
                            Log::warning($logMessage);
                        }
                    } else {
                        $logMessage = "No tiene foto_articulo_usado";
                        Log::info($logMessage);
                    }
                    break;
                    
                case 'no_usado':
                    Log::info("Buscando foto_articulo_no_usado para repuesto {$id}");
                    if ($repuesto->foto_articulo_no_usado) {
                        $fotos = json_decode($repuesto->foto_articulo_no_usado, true);
                        Log::info("JSON decodificado de foto_articulo_no_usado: " . (is_array($fotos) ? 'SI (array)' : 'NO'));
                        if (is_array($fotos)) {
                            Log::info("Total fotos en array: " . count($fotos));
                        }
                        
                        if (is_array($fotos) && isset($fotos[$index])) {
                            $imagenBlob = $fotos[$index];
                            $logMessage = "Encontrada foto_articulo_no_usado (binario), índice {$index}";
                            Log::info("Imagen encontrada en índice {$index}, tamaño blob: " . strlen($imagenBlob));
                        } else {
                            $logMessage = "foto_articulo_no_usado vacío o índice inválido";
                            Log::warning($logMessage);
                        }
                    } else {
                        $logMessage = "No tiene foto_articulo_no_usado";
                        Log::info($logMessage);
                    }
                    break;
                    
                case 'evidencia':
                default:
                    Log::info("Buscando en fotos_evidencia para repuesto {$id}");
                    if ($repuesto->fotos_evidencia) {
                        $fotos = json_decode($repuesto->fotos_evidencia, true);
                        Log::info("JSON decodificado de fotos_evidencia: " . (is_array($fotos) ? 'SI (array)' : 'NO'));
                        if (is_array($fotos)) {
                            Log::info("Total fotos en array: " . count($fotos));
                        }
                        
                        $logMessage = "Usando fotos_evidencia (compatibilidad), " . (is_array($fotos) ? count($fotos) : '0') . " fotos";
                        
                        if (is_array($fotos) && isset($fotos[$index])) {
                            $foto = $fotos[$index];
                            
                            // Verificar si es binario (nuevo) o ruta (antiguo)
                            if (is_string($foto)) {
                                if (str_starts_with($foto, 'data:image')) {
                                    // Es base64
                                    Log::info("Imagen en formato base64");
                                    $parts = explode(',', $foto);
                                    if (count($parts) > 1) {
                                        $imagenBlob = base64_decode($parts[1]);
                                        $logMessage .= " (base64)";
                                    } else {
                                        $imagenBlob = base64_decode($foto);
                                        $logMessage .= " (base64 simple)";
                                    }
                                } elseif (str_starts_with($foto, '/') || str_starts_with($foto, 'http')) {
                                    // Es una ruta o URL
                                    Log::info("Imagen es ruta/URL: {$foto}");
                                    $logMessage .= " (ruta: {$foto})";
                                    return redirect($foto);
                                } else {
                                    Log::info("Imagen string no reconocido, longitud: " . strlen($foto));
                                }
                            } else {
                                // Es binario directo
                                $imagenBlob = $foto;
                                $logMessage .= " (binario directo)";
                                Log::info("Imagen en binario directo, tamaño: " . strlen($imagenBlob));
                            }
                        } else {
                            Log::info("Índice {$index} no encontrado en fotos_evidencia");
                        }
                    } else {
                        $logMessage = "No tiene fotos_evidencia";
                        Log::info($logMessage);
                    }
                    break;
            }

            Log::info("{$logMessage}, tamaño blob: " . ($imagenBlob ? strlen($imagenBlob) : 0));
            
            if (empty($imagenBlob)) {
                Log::warning("Imagen BLOB vacía para id={$id}, tipo={$tipo}, índice={$index}");
                return $this->getDefaultImage();
            }

            // Detectar tipo MIME del binario
            $mimeType = $this->detectarMimeType($imagenBlob);
            Log::info("Tipo MIME detectado: {$mimeType}");
            
            if (!$mimeType || !str_starts_with($mimeType, 'image/')) {
                $mimeType = 'image/jpeg';
                Log::warning("No se pudo detectar tipo MIME válido, usando JPEG por defecto");
            }

            Log::info("=== FIN MOSTRAR IMAGEN (éxito) ===");
            
            return response($imagenBlob, 200)
                ->header('Content-Type', $mimeType)
                ->header('Cache-Control', 'public, max-age=86400')
                ->header('Content-Length', strlen($imagenBlob))
                ->header('Content-Disposition', 'inline; filename="evidencia_' . $id . '_' . $tipo . '_' . $index . '.jpg"');
                
        } catch (\Exception $e) {
            Log::error('Error al mostrar imagen: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            Log::info("=== FIN MOSTRAR IMAGEN (error) ===");
            return $this->getDefaultImage();
        }
    }

    /**
     * Detectar tipo MIME de un blob binario
     */
    private function detectarMimeType($blob)
    {
        Log::info("Detectando MIME type de blob, tamaño: " . strlen($blob));
        
        // Usar finfo si está disponible
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_buffer($finfo, $blob);
            finfo_close($finfo);
            Log::info("finfo detectó: {$mimeType}");
            return $mimeType;
        }
        
        // Usar getimagesizefromstring como alternativa
        if (function_exists('getimagesizefromstring')) {
            $imageInfo = @getimagesizefromstring($blob);
            if ($imageInfo && isset($imageInfo['mime'])) {
                Log::info("getimagesizefromstring detectó: {$imageInfo['mime']}");
                return $imageInfo['mime'];
            }
            Log::info("getimagesizefromstring no pudo detectar");
        }
        
        // Detectar por los primeros bytes (magic numbers)
        $firstBytes = substr($blob, 0, 4);
        $hex = bin2hex($firstBytes);
        Log::info("Primeros bytes (hex): {$hex}");
        
        if (strpos($hex, 'ffd8ff') === 0) {
            Log::info("Detectado JPEG por magic number");
            return 'image/jpeg';
        } elseif ($firstBytes === "\x89PNG") {
            Log::info("Detectado PNG por magic number");
            return 'image/png';
        } elseif (strpos($hex, '47494638') === 0) {
            Log::info("Detectado GIF por magic number");
            return 'image/gif';
        }
        
        Log::info("No se pudo detectar tipo MIME por magic numbers");
        return null;
    }

    private function getDefaultImage()
    {
        Log::info("Retornando imagen por defecto");
        // Crear una imagen SVG más simple
        $svg = '<?xml version="1.0" encoding="UTF-8"?>
        <svg xmlns="http://www.w3.org/2000/svg" width="600" height="400" viewBox="0 0 600 400">
            <rect width="600" height="400" fill="#f8f9fa"/>
            <rect x="225" y="150" width="150" height="100" fill="#e9ecef" rx="6"/>
            <circle cx="300" cy="100" r="50" fill="#e9ecef"/>
            <text x="300" y="250" text-anchor="middle" fill="#6c757d" font-family="Arial" font-size="16" font-weight="500">
                Imagen no disponible
            </text>
        </svg>';
        
        return response($svg, 200)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    public function obtenerEvidencias($id)
    {
        Log::info("=== INICIO OBTENER EVIDENCIAS PARA ID: {$id} ===");
        
        try {
            $repuesto = DB::table('ordenesarticulos')
                ->select('fotos_evidencia', 'foto_articulo_usado', 'foto_articulo_no_usado')
                ->where('idOrdenesArticulos', $id)
                ->first();

            if (!$repuesto) {
                Log::warning("Repuesto {$id} no encontrado");
                return response()->json([
                    'success' => false,
                    'message' => 'Repuesto no encontrado'
                ], 404);
            }

            Log::info("Campos de fotos encontrados:");
            Log::info("  - foto_articulo_usado: " . ($repuesto->foto_articulo_usado ? 'SI (' . strlen($repuesto->foto_articulo_usado) . ' chars)' : 'NO'));
            Log::info("  - foto_articulo_no_usado: " . ($repuesto->foto_articulo_no_usado ? 'SI (' . strlen($repuesto->foto_articulo_no_usado) . ' chars)' : 'NO'));
            Log::info("  - fotos_evidencia: " . ($repuesto->fotos_evidencia ? 'SI (' . strlen($repuesto->fotos_evidencia) . ' chars)' : 'NO'));

            $evidencias = [];
            
            // PRIMERO: Buscar en foto_articulo_usado (BINARIO)
            if ($repuesto->foto_articulo_usado) {
                Log::info("Repuesto {$id}: Tiene foto_articulo_usado");
                $fotosUsado = json_decode($repuesto->foto_articulo_usado, true);
                Log::info("JSON decodificado foto_articulo_usado: " . (is_array($fotosUsado) ? 'SI, ' . count($fotosUsado) . ' fotos' : 'NO'));
                
                if (is_array($fotosUsado) && count($fotosUsado) > 0) {
                    $evidencias[] = route('repuesto-transito.imagen', [
                        'id' => $id, 
                        'tipo' => 'usado',
                        'index' => 0
                    ]);
                    Log::info("Agregada evidencia de foto_articulo_usado");
                }
            }
            
            // SEGUNDO: Buscar en foto_articulo_no_usado (BINARIO)
            if ($repuesto->foto_articulo_no_usado) {
                Log::info("Repuesto {$id}: Tiene foto_articulo_no_usado");
                $fotosNoUsado = json_decode($repuesto->foto_articulo_no_usado, true);
                Log::info("JSON decodificado foto_articulo_no_usado: " . (is_array($fotosNoUsado) ? 'SI, ' . count($fotosNoUsado) . ' fotos' : 'NO'));
                
                if (is_array($fotosNoUsado) && count($fotosNoUsado) > 0) {
                    $evidencias[] = route('repuesto-transito.imagen', [
                        'id' => $id, 
                        'tipo' => 'no_usado',
                        'index' => 0
                    ]);
                    Log::info("Agregada evidencia de foto_articulo_no_usado");
                }
            }
            
            // TERCERO: Si no hay en las nuevas columnas, usar fotos_evidencia (COMPATIBILIDAD)
            if (empty($evidencias) && $repuesto->fotos_evidencia) {
                Log::info("Repuesto {$id}: Usando fotos_evidencia (compatibilidad)");
                $fotosEvidencia = json_decode($repuesto->fotos_evidencia, true);
                Log::info("JSON decodificado fotos_evidencia: " . (is_array($fotosEvidencia) ? 'SI, ' . count($fotosEvidencia) . ' fotos' : 'NO'));
                
                if (is_array($fotosEvidencia)) {
                    foreach ($fotosEvidencia as $index => $foto) {
                        $evidencias[] = route('repuesto-transito.imagen', [
                            'id' => $id, 
                            'tipo' => 'evidencia', 
                            'index' => $index
                        ]);
                    }
                    Log::info("Agregadas " . count($fotosEvidencia) . " evidencias de fotos_evidencia");
                }
            }

            Log::info("Total evidencias encontradas para repuesto {$id}: " . count($evidencias));
            Log::info("Evidencias: " . json_encode($evidencias));
            
            return response()->json([
                'success' => true,
                'evidencias' => $evidencias
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener evidencias: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener evidencias'
            ], 500);
        } finally {
            Log::info("=== FIN OBTENER EVIDENCIAS PARA ID: {$id} ===");
        }
    }

    /**
     * Función helper para verificar si un repuesto tiene fotos
     * (Para usar en Blade si es necesario)
     */
    public function tieneFotos($repuesto)
    {
        Log::info("=== VERIFICANDO SI REPUESTO TIENE FOTOS ===");
        Log::info("ID Repuesto: " . ($repuesto->idOrdenesArticulos ?? 'N/A'));
        
        // Verificar cada campo que puede contener fotos
        $campos = ['fotos_evidencia', 'foto_articulo_usado', 'foto_articulo_no_usado'];
        
        foreach ($campos as $campo) {
            Log::info("Verificando campo: {$campo}");
            if (!empty($repuesto->$campo)) {
                Log::info("Campo {$campo} NO está vacío, longitud: " . strlen($repuesto->$campo));
                try {
                    $fotos = json_decode($repuesto->$campo, true);
                    if (is_array($fotos) && count($fotos) > 0) {
                        Log::info("Campo {$campo} tiene " . count($fotos) . " fotos válidas");
                        // Verificar que al menos una foto tenga contenido
                        foreach ($fotos as $index => $foto) {
                            if (!empty($foto)) {
                                Log::info("Foto en índice {$index} tiene contenido, tamaño: " . strlen($foto));
                                Log::info("=== FIN VERIFICACIÓN: TIENE FOTOS ===");
                                return true;
                            } else {
                                Log::info("Foto en índice {$index} está vacía");
                            }
                        }
                        Log::info("Campo {$campo} tiene array pero todas las fotos están vacías");
                    } else {
                        Log::info("Campo {$campo} no es JSON válido o array vacío");
                        // Si hay error en el JSON o no es array, el campo podría tener datos binarios
                        Log::info("Retornando true porque el campo tiene contenido aunque no sea JSON válido");
                        Log::info("=== FIN VERIFICACIÓN: TIENE FOTOS (contenido no JSON) ===");
                        return true;
                    }
                } catch (\Exception $e) {
                    Log::error("Error decodificando JSON del campo {$campo}: " . $e->getMessage());
                    // Si hay error en el JSON, el campo podría tener datos binarios
                    Log::info("Retornando true porque el campo tiene contenido (error en JSON)");
                    Log::info("=== FIN VERIFICACIÓN: TIENE FOTOS (error JSON) ===");
                    return true;
                }
            } else {
                Log::info("Campo {$campo} está vacío o es null");
            }
        }
        
        Log::info("=== FIN VERIFICACIÓN: NO TIENE FOTOS ===");
        return false;
    }

    /**
     * Método para debug: muestra información detallada de un repuesto
     */
    public function debugRepuesto($id)
    {
        Log::info("=== DEBUG REPUESTO ID: {$id} ===");
        
        $repuesto = DB::table('ordenesarticulos')
            ->where('idOrdenesArticulos', $id)
            ->first();
            
        if (!$repuesto) {
            Log::warning("Repuesto {$id} no encontrado");
            return response()->json(['error' => 'Repuesto no encontrado'], 404);
        }
        
        Log::info("=== DATOS COMPLETOS DEL REPUESTO ===");
        foreach ($repuesto as $key => $value) {
            if (in_array($key, ['fotos_evidencia', 'foto_articulo_usado', 'foto_articulo_no_usado'])) {
                Log::info("Campo {$key}: " . ($value ? 'TIENE DATOS (' . strlen($value) . ' chars)' : 'VACÍO'));
                if ($value) {
                    // Mostrar primeros 100 caracteres para ver el formato
                    $preview = substr($value, 0, 100);
                    Log::info("  Preview: " . $preview);
                    
                    // Intentar decodificar JSON
                    $decoded = json_decode($value, true);
                    if (is_array($decoded)) {
                        Log::info("  Es JSON válido, array con " . count($decoded) . " elementos");
                    } else {
                        Log::info("  NO es JSON válido");
                    }
                }
            } else {
                Log::info("Campo {$key}: " . ($value ?? 'NULL'));
            }
        }
        
        Log::info("=== FIN DEBUG REPUESTO ===");
        
        return response()->json([
            'repuesto' => $repuesto,
            'campos_fotos' => [
                'fotos_evidencia' => $repuesto->fotos_evidencia ? 'SI (' . strlen($repuesto->fotos_evidencia) . ' chars)' : 'NO',
                'foto_articulo_usado' => $repuesto->foto_articulo_usado ? 'SI (' . strlen($repuesto->foto_articulo_usado) . ' chars)' : 'NO',
                'foto_articulo_no_usado' => $repuesto->foto_articulo_no_usado ? 'SI (' . strlen($repuesto->foto_articulo_no_usado) . ' chars)' : 'NO',
            ]
        ]);
    }
}