<?php

namespace App\Http\Controllers\solicitud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SolicitudrepuestoController extends Controller
{
    public function index()
    {
        // Datos de ejemplo - luego los reemplazarás con tu modelo
        $estadisticas = [
            'pendientes' => 12,
            'aprobadas' => 8,
            'rechazadas' => 3,
            'total' => 23
        ];

        $solicitudes = [
            [
                'id' => 'SOL-001',
                'solicitante' => 'Juan Pérez',
                'departamento' => 'Taller Mecánico',
                'repuesto' => 'Filtro de Aceite',
                'cantidad' => 5,
                'fecha' => '15 Mar 2024',
                'estado' => 'pendiente'
            ],
            [
                'id' => 'SOL-002',
                'solicitante' => 'María García',
                'departamento' => 'Electricidad',
                'repuesto' => 'Bujías',
                'cantidad' => 12,
                'fecha' => '14 Mar 2024',
                'estado' => 'aprobado'
            ],
            [
                'id' => 'SOL-003',
                'solicitante' => 'Carlos López',
                'departamento' => 'Pintura',
                'repuesto' => 'Pastillas de Freno',
                'cantidad' => 4,
                'fecha' => '13 Mar 2024',
                'estado' => 'rechazado'
            ]
        ];

        return view("solicitud.solicitudrepuesto.index", compact('estadisticas', 'solicitudes'));
    }

    public function create()
{
    $userId = auth()->id();

    $tickets = DB::table('tickets as t')
        ->select(
            't.idTickets',
            't.numero_ticket',
            't.idModelo',
            'm.nombre as modelo_nombre',
            DB::raw('COUNT(v.idVisitas) as total_visitas') // Opcional: para contar visitas
        )
        ->leftJoin('modelo as m', 't.idModelo', '=', 'm.idModelo')
        ->leftJoin('visitas as v', 't.idTickets', '=', 'v.idTickets') // Join con visitas
        ->where('t.idTipotickets', 1)
        ->where(function ($query) use ($userId) {
            if ($userId == 1) {
                // Para admin: solo tickets con al menos una visita
                return $query;
            } else {
                return $query->whereExists(function ($subQuery) use ($userId) {
                    $subQuery->select(DB::raw(1))
                        ->from('visitas as v')
                        ->whereColumn('v.idTickets', 't.idTickets')
                        ->where('v.idUsuario', $userId)
                        ->where('v.estado', 1)
                        ->whereExists(function ($flujoQuery) {
                            $flujoQuery->select(DB::raw(1))
                                ->from('ticketflujo as tf')
                                ->whereColumn('tf.idTicket', 't.idTickets')
                                ->where('tf.idestadflujo', 2);
                        });
                });
            }
        })
        ->groupBy('t.idTickets', 't.numero_ticket', 't.idModelo', 'm.nombre') // Agrupar por ticket
        ->having('total_visitas', '>', 0) // Solo tickets con visitas
        ->orderBy('t.fecha_creacion', 'desc')
        ->get();

    // Obtener el último número de orden
    $lastOrder = DB::table('solicitudesordenes')
        ->orderBy('idsolicitudesordenes', 'desc')
        ->first();

    $nextOrderNumber = $lastOrder ? (intval(substr($lastOrder->codigo, 4)) + 1) : 1;

    return view("solicitud.solicitudrepuesto.create", compact('tickets', 'nextOrderNumber'));
}

    public function createProvincia()
    {
        $userId = auth()->id();

        // Ya no necesitamos cargar tickets para el select
        // Solo necesitamos obtener el próximo número de orden

        // Obtener el último número de orden
        $lastOrder = DB::table('solicitudesordenes')
            ->orderBy('idsolicitudesordenes', 'desc')
            ->first();

        $nextOrderNumber = $lastOrder ? (intval(substr($lastOrder->codigo, 4)) + 1) : 1;

        // Obtener lista de CAST activos
        $castList = DB::table('cast')
            ->where('estado', 1) // CAST activos
            ->orderBy('nombre', 'asc')
            ->get();

        // Obtener lista de modelos (para el select manual)
        $modelos = DB::table('modelo')
            ->where('estado', 1)
            ->orderBy('nombre', 'asc')
            ->get();

        // Pasar una bandera para identificar que es para provincia
        $esParaProvincia = true;

        return view("solicitud.solicitudrepuesto.create-provincia", compact(
            'nextOrderNumber',
            'esParaProvincia',
            'castList',
            'modelos'
        ));
    }
    // En el controlador
    public function getNextOrderNumber()
    {
        $lastOrder = DB::table('solicitudesordenes')
            ->orderBy('idsolicitudesordenes', 'desc')
            ->first();

        $nextOrderNumber = $lastOrder ? (intval(substr($lastOrder->codigo, 4)) + 1) : 1;

        return response()->json([
            'success' => true,
            'nextOrderNumber' => $nextOrderNumber
        ]);
    }

    public function gestionar($id)
    {
        // Obtener la solicitud con información básica
        $solicitud = DB::table('solicitudesordenes as so')
            ->select(
                'so.idsolicitudesordenes',
                'so.codigo',
                'so.estado',
                'so.tiposervicio',
                'so.niveldeurgencia',
                'so.fechacreacion',
                'so.fecharequerida',
                'so.observaciones',
                'so.cantidad',
                'so.totalcantidadproductos',
                'so.idticket',
                't.numero_ticket',
                'u.Nombre as nombre_solicitante'
            )
            ->leftJoin('usuarios as u', 'so.idTecnico', '=', 'u.idUsuario')
            ->leftJoin('tickets as t', 'so.idticket', '=', 't.idTickets')
            ->where('so.idsolicitudesordenes', $id)
            ->where('so.tipoorden', 'solicitud_repuesto')
            ->first();

        if (!$solicitud) {
            abort(404, 'Solicitud no encontrada');
        }

        // Obtener los repuestos de la solicitud que ya han sido procesados/aprobados
        // CONSULTA SIMPLIFICADA - sin joins de ubicaciones que causan duplicados
        $repuestos = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idordenesarticulos',
                'oa.cantidad as cantidad_solicitada',
                'oa.observacion',
                'oa.estado as estado_repuesto',
                'oa.fechaUsado',
                'oa.fechaSinUsar',
                'oa.idticket as idticket_repuesto',
                'a.idArticulos',
                'a.nombre',
                'a.codigo_barras',
                'a.codigo_repuesto',
                'sc.nombre as tipo_repuesto',
                // Número de ticket específico del repuesto
                't_repuesto.numero_ticket as numero_ticket_repuesto'
            )
            ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->leftJoin('tickets as t_repuesto', 'oa.idticket', '=', 't_repuesto.idTickets')
            ->where('oa.idsolicitudesordenes', $id)
            ->where('oa.estado', 1) // Solo repuestos ya procesados
            ->get();

        // Determinar el estado actual de cada repuesto basado en las fechas
        $estadosRepuestos = [];
        foreach ($repuestos as $repuesto) {
            if ($repuesto->fechaUsado) {
                $estadosRepuestos[$repuesto->idArticulos] = 'usado';
            } elseif ($repuesto->fechaSinUsar) {
                $estadosRepuestos[$repuesto->idArticulos] = 'no_usado';
            } else {
                $estadosRepuestos[$repuesto->idArticulos] = 'pendiente';
            }
        }

        // Contadores para el resumen
        $contadores = [
            'usados' => 0,
            'no_usados' => 0,
            'pendientes' => 0
        ];

        // Contar los estados
        foreach ($estadosRepuestos as $estado) {
            if ($estado === 'usado') {
                $contadores['usados']++;
            } elseif ($estado === 'no_usado') {
                $contadores['no_usados']++;
            } else {
                $contadores['pendientes']++;
            }
        }

        return view('solicitud.solicitudrepuesto.gestionar', compact(
            'solicitud',
            'repuestos',
            'estadosRepuestos',
            'contadores'
        ));
    }














 public function marcarUsado(Request $request, $solicitudId)
    {
        try {
            $request->validate([
                'articulo_id' => 'required|integer',
                'fecha_uso' => 'required|date',
                'observacion' => 'nullable|string|max:500',
                'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
            ]);

            DB::transaction(function () use ($request, $solicitudId) {
                // Obtener información de la solicitud
                $solicitud = DB::table('solicitudesordenes')
                    ->select('codigo')
                    ->where('idsolicitudesordenes', $solicitudId)
                    ->first();

                if (!$solicitud) {
                    throw new \Exception('Solicitud no encontrada');
                }

                // Obtener información del repuesto
                $repuestoInfo = DB::table('ordenesarticulos as oa')
                    ->select(
                        'oa.idordenesarticulos',
                        'oa.cantidad',
                        'oa.idticket',
                        'a.idArticulos',
                        'a.nombre',
                        't.numero_ticket',
                        't.idClienteGeneral'
                    )
                    ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
                    ->leftJoin('tickets as t', 'oa.idticket', '=', 't.idTickets')
                    ->where('oa.idsolicitudesordenes', $solicitudId)
                    ->where('oa.idarticulos', $request->articulo_id)
                    ->first();

                if (!$repuestoInfo) {
                    throw new \Exception('Repuesto no encontrado en la solicitud');
                }

                // 1. Primero, limpiar fotos anteriores de "usado" para este artículo
                DB::table('ordenes_articulos_fotos')
                    ->where('orden_articulo_id', $repuestoInfo->idordenesarticulos)
                    ->where('tipo_foto', 'usado')
                    ->delete();

                // 2. Procesar y guardar cada foto en la tabla separada
                if ($request->hasFile('fotos')) {
                    foreach ($request->file('fotos') as $index => $foto) {
                        try {
                            if (!$foto->isValid()) {
                                Log::warning("Foto no válida: " . $foto->getClientOriginalName());
                                continue;
                            }

                            // Leer como binario
                            $contenidoBinario = file_get_contents($foto->getRealPath());
                            
                            // Comprimir si es posible
                            if (function_exists('imagecreatefromstring')) {
                                $contenidoBinario = $this->comprimirImagenSimple($contenidoBinario);
                            }

                            // Insertar en la tabla de fotos
                            DB::table('ordenes_articulos_fotos')->insert([
                                'orden_articulo_id' => $repuestoInfo->idordenesarticulos,
                                'tipo_foto' => 'usado',
                                'nombre_archivo' => $foto->getClientOriginalName(),
                                'mime_type' => $foto->getMimeType(),
                                'datos' => $contenidoBinario,
                                'fecha_subida' => now()
                            ]);

                            Log::debug("Foto guardada en tabla separada: {$foto->getClientOriginalName()}, " .
                                     "Tamaño: " . strlen($contenidoBinario) . " bytes");
                            
                        } catch (\Exception $e) {
                            Log::error("Error procesando foto {$index}: " . $e->getMessage());
                            continue;
                        }
                    }
                }

                // 3. Actualizar el artículo con la fecha y observación
                // LIMPIAR el campo de "no usado" para evitar conflictos
                DB::table('ordenesarticulos')
                    ->where('idsolicitudesordenes', $solicitudId)
                    ->where('idarticulos', $request->articulo_id)
                    ->update([
                        'fechaUsado' => $request->fecha_uso,
                        'fechaSinUsar' => null, // Limpiar fecha de no usado
                        'observacion' => $request->observacion,
                        'foto_articulo_usado' => null, // Ya no guardamos aquí
                        'foto_articulo_no_usado' => null, // Limpiar
                        'updated_at' => now()
                    ]);

                Log::info("Repuesto marcado como usado - Solicitud: {$solicitudId}, " .
                         "Repuesto: {$repuestoInfo->nombre}, " .
                         "Fotos procesadas: " . ($request->hasFile('fotos') ? count($request->file('fotos')) : 0));
            });

            return response()->json([
                'success' => true,
                'message' => 'Repuesto marcado como usado correctamente',
                'fotos_guardadas' => $request->hasFile('fotos') ? count($request->file('fotos')) : 0
            ]);
        } catch (\Exception $e) {
            Log::error('Error al marcar repuesto como usado: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al marcar el repuesto: ' . $e->getMessage(),
                'fotos_guardadas' => 0
            ], 500);
        }
    }








/**
 * Comprimir imagen simple sin Intervention Image
 */
private function comprimirImagenSimple($contenidoBinario)
{
    // Si GD no está instalado, devolver original
    if (!function_exists('imagecreatefromstring')) {
        return $contenidoBinario;
    }

    try {
        // Intentar crear imagen desde string
        $imagen = @imagecreatefromstring($contenidoBinario);
        if ($imagen === false) {
            return $contenidoBinario;
        }

        // Obtener dimensiones
        $ancho = imagesx($imagen);
        $alto = imagesy($imagen);

        // Redimensionar solo si es mayor a 1200px
        if ($ancho > 1200) {
            $nuevoAncho = 1200;
            $nuevoAlto = intval($alto * ($nuevoAncho / $ancho));

            $nuevaImagen = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
            
            // Preservar transparencia para PNG
            if (imagecolortransparent($imagen) >= 0) {
                imagealphablending($nuevaImagen, false);
                imagesavealpha($nuevaImagen, true);
            }
            
            imagecopyresampled($nuevaImagen, $imagen, 0, 0, 0, 0, 
                              $nuevoAncho, $nuevoAlto, $ancho, $alto);
            
            imagedestroy($imagen);
            $imagen = $nuevaImagen;
        }

        // Exportar como JPEG con calidad 80%
        ob_start();
        imagejpeg($imagen, null, 80);
        $resultado = ob_get_clean();
        
        imagedestroy($imagen);
        
        return $resultado;

    } catch (\Exception $e) {
        Log::warning('Error al comprimir imagen: ' . $e->getMessage());
        return $contenidoBinario;
    }
}



/**
 * Marcar un repuesto como no usado (devolución al inventario) - VERSIÓN CORREGIDA
 */
public function marcarNoUsado(Request $request, $solicitudId)
{
    try {
        $request->validate([
            'articulo_id' => 'required|integer',
            'fecha_devolucion' => 'required|date',
            'observacion' => 'nullable|string|max:500',
            'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        // Declarar variables fuera del transaction
        $totalFotos = $request->hasFile('fotos') ? count($request->file('fotos')) : 0;
        $fotosGuardadas = 0;

        DB::transaction(function () use ($request, $solicitudId, $totalFotos, &$fotosGuardadas) {
            // Obtener información de la solicitud
            $solicitud = DB::table('solicitudesordenes')
                ->select('codigo')
                ->where('idsolicitudesordenes', $solicitudId)
                ->first();

            if (!$solicitud) {
                throw new \Exception('Solicitud no encontrada');
            }

            // Obtener información del repuesto y entrega
            $repuestoInfo = DB::table('ordenesarticulos as oa')
                ->select(
                    'oa.idordenesarticulos',
                    'oa.cantidad',
                    'oa.idticket',
                    'a.idArticulos',
                    'a.nombre',
                    're.ubicacion_utilizada',
                    're.usuario_destino_id',
                    're.tipo_entrega',
                    't.numero_ticket',
                    't.idClienteGeneral'
                )
                ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
                ->leftJoin('repuestos_entregas as re', function ($join) use ($solicitudId) {
                    $join->on('re.solicitud_id', '=', 'oa.idsolicitudesordenes')
                        ->on('re.articulo_id', '=', 'oa.idarticulos');
                })
                ->leftJoin('tickets as t', 'oa.idticket', '=', 't.idTickets')
                ->where('oa.idsolicitudesordenes', $solicitudId)
                ->where('oa.idarticulos', $request->articulo_id)
                ->first();

            if (!$repuestoInfo) {
                throw new \Exception('Repuesto no encontrado en la solicitud');
            }

            // ========================
            // 🆕 CORRECCIÓN: GUARDAR FOTOS EN TABLA SEPARADA
            // ========================
            
            // 1. Primero eliminar fotos anteriores de "no_usado" para este artículo
            DB::table('ordenes_articulos_fotos')
                ->where('orden_articulo_id', $repuestoInfo->idordenesarticulos)
                ->where('tipo_foto', 'no_usado')
                ->delete();

            Log::info("Fotos anteriores eliminadas para artículo (no usado): " . $repuestoInfo->idordenesarticulos);

            // 2. Procesar y guardar NUEVAS fotos en tabla separada
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $index => $foto) {
                    try {
                        if (!$foto->isValid()) {
                            Log::warning("Foto no válida (no usado): " . $foto->getClientOriginalName());
                            continue;
                        }

                        // Verificar límite de 5 fotos
                        if ($fotosGuardadas >= 5) {
                            Log::warning("Se alcanzó el límite de 5 fotos para la devolución: " . $repuestoInfo->nombre);
                            break;
                        }

                        // Leer como binario
                        $contenidoBinario = file_get_contents($foto->getRealPath());
                        
                        // Comprimir si es posible
                        if (function_exists('imagecreatefromstring')) {
                            $contenidoBinario = $this->comprimirImagenSimple($contenidoBinario);
                        }

                        // 🆕 Insertar en la tabla de fotos SEPARADA
                        DB::table('ordenes_articulos_fotos')->insert([
                            'orden_articulo_id' => $repuestoInfo->idordenesarticulos,
                            'tipo_foto' => 'no_usado',
                            'nombre_archivo' => $foto->getClientOriginalName(),
                            'mime_type' => $foto->getMimeType(),
                            'datos' => $contenidoBinario,
                            'fecha_subida' => now()
                        ]);

                        $fotosGuardadas++;
                        
                        Log::debug("✅ Foto guardada en tabla separada (no usado): {$foto->getClientOriginalName()}, " .
                                 "Tamaño: " . strlen($contenidoBinario) . " bytes, " .
                                 "ID Artículo: {$repuestoInfo->idordenesarticulos}");
                        
                    } catch (\Exception $e) {
                        Log::error("Error procesando foto {$index} (no usado): " . $e->getMessage());
                        continue;
                    }
                }
            }

            // ========================
            // RESTO DEL CÓDIGO (se mantiene igual)
            // ========================

            // Buscar la ubicación original donde estaba el repuesto
            $ubicacionOriginal = DB::table('rack_ubicaciones')
                ->select('idRackUbicacion', 'codigo', 'rack_id')
                ->where('codigo', $repuestoInfo->ubicacion_utilizada)
                ->first();

            if (!$ubicacionOriginal) {
                throw new \Exception('No se pudo encontrar la ubicación original del repuesto. Ubicación: ' . ($repuestoInfo->ubicacion_utilizada ?? 'NULL'));
            }

            // Obtener información del rack
            $rackInfo = DB::table('racks')
                ->select('nombre')
                ->where('idRack', $ubicacionOriginal->rack_id)
                ->first();

            // Obtener cliente_general_id del ticket
            $clienteGeneralId = $repuestoInfo->idClienteGeneral ?? 1;

            // 1. INCREMENTAR stock en rack_ubicacion_articulos (ubicación original)
            $rackUbicacionArticulo = DB::table('rack_ubicacion_articulos')
                ->where('rack_ubicacion_id', $ubicacionOriginal->idRackUbicacion)
                ->where('articulo_id', $request->articulo_id)
                ->first();

            if ($rackUbicacionArticulo) {
                // Si ya existe registro, incrementar
                DB::table('rack_ubicacion_articulos')
                    ->where('idRackUbicacionArticulo', $rackUbicacionArticulo->idRackUbicacionArticulo)
                    ->increment('cantidad', $repuestoInfo->cantidad);
            } else {
                // Si no existe, crear nuevo registro
                DB::table('rack_ubicacion_articulos')->insert([
                    'rack_ubicacion_id' => $ubicacionOriginal->idRackUbicacion,
                    'articulo_id' => $request->articulo_id,
                    'cantidad' => $repuestoInfo->cantidad,
                    'cliente_general_id' => $clienteGeneralId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // 2. INCREMENTAR stock total en tabla articulos
            DB::table('articulos')
                ->where('idArticulos', $request->articulo_id)
                ->increment('stock_total', $repuestoInfo->cantidad);

            // 3. Registrar movimiento en rack_movimientos (ENTRADA por devolución)
            DB::table('rack_movimientos')->insert([
                'articulo_id' => $request->articulo_id,
                'custodia_id' => null,
                'ubicacion_origen_id' => null,
                'ubicacion_destino_id' => $ubicacionOriginal->idRackUbicacion,
                'rack_origen_id' => null,
                'rack_destino_id' => $ubicacionOriginal->rack_id,
                'cantidad' => $repuestoInfo->cantidad,
                'tipo_movimiento' => 'entrada',
                'usuario_id' => auth()->id(),
                'observaciones' => "Devolución repuesto no usado - Solicitud: {$solicitud->codigo} - Ticket: {$repuestoInfo->numero_ticket} - Observación: {$request->observacion}",
                'codigo_ubicacion_origen' => null,
                'codigo_ubicacion_destino' => $ubicacionOriginal->codigo,
                'nombre_rack_origen' => null,
                'nombre_rack_destino' => $rackInfo->nombre ?? 'Desconocido',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 4. ELIMINAR registro en inventario_ingresos_clientes (donde se registró la salida)
            $registrosEliminados = DB::table('inventario_ingresos_clientes')
                ->where('codigo_solicitud', $solicitud->codigo)
                ->where('articulo_id', $request->articulo_id)
                ->where('tipo_ingreso', 'salida')
                ->delete();

            // 5. Actualizar KARDEX para la ENTRADA (devolución)
            $articuloInfo = DB::table('articulos')
                ->select('precio_compra')
                ->where('idArticulos', $request->articulo_id)
                ->first();

            if ($articuloInfo) {
                $this->actualizarKardexEntrada(
                    $request->articulo_id, 
                    $clienteGeneralId, 
                    $repuestoInfo->cantidad, 
                    $articuloInfo->precio_compra, 
                    "Devolución repuesto no usado - Solicitud: {$solicitud->codigo}"
                );
            }

            // 6. Crear array de datos para actualizar
            // 🆕 AHORA LIMPIAMOS los campos de fotos en ordenesarticulos
            $datosActualizar = [
                'fechaSinUsar' => $request->fecha_devolucion,
                'fechaUsado' => null,
                'observacion' => $request->observacion . " | Devolución completada: " . now()->format('d/m/Y H:i'),
                'foto_articulo_no_usado' => null, // 🆕 Limpiamos, ya no guardamos aquí
                'foto_articulo_usado' => null, // 🆕 Limpiamos por seguridad
                'fotos_evidencia' => null, // 🆕 Limpiamos campo antiguo
                'updated_at' => now()
            ];

            // Actualizar en la tabla ordenesarticulos
            DB::table('ordenesarticulos')
                ->where('idsolicitudesordenes', $solicitudId)
                ->where('idarticulos', $request->articulo_id)
                ->update($datosActualizar);

            // 7. Registrar en logs
            Log::info("✅ Repuesto devuelto al inventario - Solicitud: {$solicitudId}, " .
                     "Repuesto: {$repuestoInfo->nombre}, Cantidad: {$repuestoInfo->cantidad}, " .
                     "Ubicación: {$ubicacionOriginal->codigo}, " .
                     "Fotos subidas: {$totalFotos}, " .
                     "Fotos guardadas en tabla separada: {$fotosGuardadas}");
        });

        return response()->json([
            'success' => true,
            'message' => 'Repuesto marcado como no usado y devuelto al inventario correctamente',
            'fotos_subidas' => $totalFotos,
            'fotos_guardadas' => $fotosGuardadas,
            'limite_fotos' => 5
        ]);
    } catch (\Exception $e) {
        Log::error('❌ Error al marcar repuesto como no usado: ' . $e->getMessage());
        Log::error('File: ' . $e->getFile());
        Log::error('Line: ' . $e->getLine());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al marcar el repuesto: ' . $e->getMessage(),
            'fotos_subidas' => 0,
            'fotos_guardadas' => 0
        ], 500);
    }
}
    private function actualizarKardexEntrada($articuloId, $clienteGeneralId, $cantidad, $precioUnitario, $observaciones)
    {
        try {
            $fechaActual = now();
            $mesActual = $fechaActual->format('Y-m');

            // Buscar si existe registro de kardex para este mes
            $kardexActual = DB::table('kardex')
                ->where('idArticulo', $articuloId)
                ->where('cliente_general_id', $clienteGeneralId)
                ->whereYear('fecha', $fechaActual->year)
                ->whereMonth('fecha', $fechaActual->month)
                ->first();

            if ($kardexActual) {
                // ACTUALIZAR registro existente del mes
                $nuevoInventarioActual = $kardexActual->inventario_actual + $cantidad;
                $nuevoCostoInventario = $nuevoInventarioActual * $precioUnitario;

                DB::table('kardex')
                    ->where('id', $kardexActual->id)
                    ->update([
                        'unidades_entrada' => $kardexActual->unidades_entrada + $cantidad,
                        'costo_unitario_entrada' => $precioUnitario,
                        'inventario_actual' => $nuevoInventarioActual,
                        'costo_inventario' => $nuevoCostoInventario,
                        'updated_at' => now()
                    ]);
            } else {
                // CREAR nuevo registro mensual
                // Obtener último registro para calcular inventario inicial
                $ultimoKardex = DB::table('kardex')
                    ->where('idArticulo', $articuloId)
                    ->where('cliente_general_id', $clienteGeneralId)
                    ->orderBy('id', 'desc')
                    ->first();

                $inventarioInicial = $ultimoKardex ? $ultimoKardex->inventario_actual : 0;
                $inventarioActual = $inventarioInicial + $cantidad;
                $costoInventario = $inventarioActual * $precioUnitario;

                DB::table('kardex')->insert([
                    'fecha' => $fechaActual->format('Y-m-d'),
                    'idArticulo' => $articuloId,
                    'cliente_general_id' => $clienteGeneralId,
                    'unidades_entrada' => $cantidad,
                    'costo_unitario_entrada' => $precioUnitario,
                    'unidades_salida' => 0,
                    'costo_unitario_salida' => 0,
                    'inventario_inicial' => $inventarioInicial,
                    'inventario_actual' => $inventarioActual,
                    'costo_inventario' => $costoInventario,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error al actualizar kardex entrada: ' . $e->getMessage());
            throw $e;
        }
    }

    // Nuevo endpoint para buscar ticket por ID
    public function getTicketInfo($ticketId)
    {
        $ticket = DB::table('tickets as t')
            ->select(
                't.idTickets',
                't.numero_ticket',
                't.idClienteGeneral',
                't.idCliente',
                't.idMarca',
                't.idModelo',
                't.serie',
                't.fechaCompra',
                't.idTienda',
                't.fallaReportada',
                'cg.descripcion as cliente_general',
                'c.nombre as cliente_nombre',
                'c.documento as cliente_documento',
                'ti.nombre as tienda_nombre',
                'm.nombre as marca_nombre',
                'mo.nombre as modelo_nombre'
            )
            ->join('clientegeneral as cg', 't.idClienteGeneral', '=', 'cg.idClienteGeneral')
            ->leftJoin('cliente as c', 't.idCliente', '=', 'c.idCliente')
            ->leftJoin('tienda as ti', 't.idTienda', '=', 'ti.idTienda')
            ->leftJoin('marca as m', 't.idMarca', '=', 'm.idMarca')
            ->leftJoin('modelo as mo', 't.idModelo', '=', 'mo.idModelo')
            ->where('t.idTickets', $ticketId)
            ->first();

        return response()->json($ticket);
    }


    // Endpoint para obtener tipos de repuesto por modelo
    public function getTiposRepuesto($modeloId)
    {
        // Primero: Buscar artículos en articulo_modelo que tengan este modelo
        $articulosIds = DB::table('articulo_modelo')
            ->where('modelo_id', $modeloId)
            ->pluck('articulo_id');

        if ($articulosIds->isEmpty()) {
            return response()->json([]);
        }

        // Segundo: Buscar en articulos esos artículos y obtener sus subcategorías
        $tiposRepuesto = DB::table('articulos as a')
            ->select(
                'sc.id as idsubcategoria',
                'sc.nombre as tipo_repuesto'
            )
            ->join('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->whereIn('a.idArticulos', $articulosIds)
            ->where('a.estado', 1)
            ->groupBy('sc.id', 'sc.nombre')
            ->get();

        return response()->json($tiposRepuesto);
    }

    // Endpoint para obtener códigos por tipo de repuesto y modelo
    public function getCodigosRepuesto($modeloId, $subcategoriaId)
    {
        // Primero: Buscar artículos en articulo_modelo que tengan este modelo
        $articulosIds = DB::table('articulo_modelo')
            ->where('modelo_id', $modeloId)
            ->pluck('articulo_id');

        if ($articulosIds->isEmpty()) {
            return response()->json([]);
        }

        // Segundo: Buscar en articulos esos artículos con la subcategoría seleccionada
        $codigos = DB::table('articulos as a')
            ->select(
                'a.idArticulos',
                'a.codigo_repuesto',
                'a.nombre'
            )
            ->whereIn('a.idArticulos', $articulosIds)
            ->where('a.idsubcategoria', $subcategoriaId)
            ->where('a.estado', 1)
            ->whereNotNull('a.codigo_repuesto')
            ->where('a.codigo_repuesto', '!=', '')
            ->get();

        return response()->json($codigos);
    }



    public function store(Request $request)
    {
        $startTime = microtime(true);
        Log::info('Iniciando creación de orden', [
            'user_id' => auth()->id(),
            'ticket_id' => $request->input('ticketId'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        try {
            DB::beginTransaction();
            Log::info('Transacción de base de datos iniciada');

            // Validar los datos requeridos
            Log::debug('Iniciando validación de datos');
            $validated = $request->validate([
                'ticketId' => 'required|exists:tickets,idTickets',
                'orderInfo.tipoServicio' => 'required|string',
                'orderInfo.urgencia' => 'required|string|in:baja,media,alta',
                'orderInfo.fechaRequerida' => 'required|date',
                'orderInfo.observaciones' => 'nullable|string',
                'products' => 'required|array|min:1',
                'products.*.ticketId' => 'required|exists:tickets,idTickets',
                'products.*.modeloId' => 'required|exists:modelo,idModelo',
                'products.*.tipoId' => 'required|exists:subcategorias,id',
                'products.*.codigoId' => 'required',
                'products.*.cantidad' => 'required|integer|min:1|max:100'
            ]);
            Log::info('Validación exitosa', ['campos_validados' => array_keys($validated)]);

            // Obtener información del ticket
            Log::debug('Buscando ticket en base de datos', ['ticket_id' => $validated['ticketId']]);
            $ticket = DB::table('tickets')
                ->where('idTickets', $validated['ticketId'])
                ->first();

            if (!$ticket) {
                Log::error('Ticket no encontrado', ['ticket_id' => $validated['ticketId']]);
                throw new \Exception('Ticket no encontrado');
            }
            Log::info('Ticket encontrado', ['ticket_numero' => $ticket->numero_ticket]);

            // Calcular estadísticas de productos
            Log::debug('Calculando estadísticas de productos');
            $totalCantidad = collect($validated['products'])->sum('cantidad');
            $totalProductosUnicos = collect($validated['products'])->unique(function ($product) {
                return $product['modeloId'] . '-' . $product['tipoId'] . '-' . $product['codigoId'];
            })->count();

            Log::info('Estadísticas calculadas', [
                'total_cantidad' => $totalCantidad,
                'productos_unicos' => $totalProductosUnicos,
                'total_productos' => count($validated['products'])
            ]);

            // Generar código de orden
            $nextOrderNumber = DB::table('solicitudesordenes')->count() + 1;
            $codigoOrden = 'ORD-' . str_pad($nextOrderNumber, 3, '0', STR_PAD_LEFT);
            Log::info('Código de orden generado', ['codigo_orden' => $codigoOrden]);

            // 1. Insertar en solicitudesordenes con TODOS los campos
            Log::debug('Preparando inserción en solicitudesordenes');
            $solicitudData = [
                'fechacreacion' => now(),
                'estado' => 'pendiente',
                'tipoorden' => 'solicitud_repuesto',
                'fecharequerida' => $validated['orderInfo']['fechaRequerida'],
                'fechaentrega' => $validated['orderInfo']['fechaRequerida'],
                'codigo' => $codigoOrden,
                'niveldeurgencia' => $validated['orderInfo']['urgencia'],
                'tiposervicio' => $validated['orderInfo']['tipoServicio'],
                'observaciones' => $validated['orderInfo']['observaciones'] ?? null,
                'cantidad' => $totalProductosUnicos,
                'canproduuni' => $totalProductosUnicos,
                'totalcantidadproductos' => $totalCantidad,
                'idtipoServicio' => $this->getTipoServicioId($validated['orderInfo']['tipoServicio']),
                'idtecnico' => auth()->id(),
                'idusuario' => auth()->id(),
                'urgencia' => $validated['orderInfo']['urgencia']
            ];

            Log::debug('Datos para solicitudesordenes', $solicitudData);

            $solicitudId = DB::table('solicitudesordenes')->insertGetId($solicitudData);
            Log::info('Solicitud de orden creada exitosamente', ['solicitud_id' => $solicitudId]);

            // 2. Insertar los artículos en ordenesarticulos CON EL IDTICKET
            Log::debug('Iniciando procesamiento de productos', ['total_productos' => count($validated['products'])]);

            $productosProcesados = 0;
            $productosConError = 0;

            foreach ($validated['products'] as $index => $product) {
                Log::debug("Procesando producto {$index}", [
                    'producto_index' => $index,
                    'codigo' => $product['codigoId'],
                    'cantidad' => $product['cantidad']
                ]);

                // Buscar el idArticulos basado en el código
                $articulo = DB::table('articulos')
                    ->where('codigo_repuesto', $product['codigoId'])
                    ->first();

                if ($articulo) {
                    DB::table('ordenesarticulos')->insert([
                        'cantidad' => $product['cantidad'],
                        'estado' => 0, // 0 = pendiente
                        'observacion' => null,
                        'fotorepuesto' => null,
                        'fechausado' => null,
                        'fechasinusar' => null,
                        'idsolicitudesordenes' => $solicitudId,
                        'idticket' => $product['ticketId'], // Guardar el idticket en cada artículo
                        'idarticulos' => $articulo->idArticulos,
                        'idubicacion' => null
                    ]);
                    $productosProcesados++;
                    Log::debug("Producto {$index} insertado exitosamente", [
                        'articulo_id' => $articulo->idArticulos,
                        'codigo' => $product['codigoId']
                    ]);
                } else {
                    $productosConError++;
                    Log::warning("Artículo no encontrado", [
                        'producto_index' => $index,
                        'codigo' => $product['codigoId'],
                        'ticket_id' => $product['ticketId']
                    ]);
                }
            }

            Log::info('Procesamiento de productos completado', [
                'productos_procesados' => $productosProcesados,
                'productos_con_error' => $productosConError,
                'total_productos' => count($validated['products'])
            ]);

            DB::commit();
            Log::info('Transacción confirmada exitosamente');

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            Log::info('Orden creada exitosamente', [
                'solicitud_id' => $solicitudId,
                'codigo_orden' => $codigoOrden,
                'tiempo_ejecucion_ms' => $executionTime,
                'total_productos' => count($validated['products'])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Orden creada exitosamente',
                'solicitud_id' => $solicitudId,
                'codigo_orden' => $codigoOrden,
                'numeroticket' => $ticket->numero_ticket,
                'idticket' => $validated['ticketId'],
                'estadisticas' => [
                    'productos_unicos' => $totalProductosUnicos,
                    'total_cantidad' => $totalCantidad
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Error de validación al crear orden', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error de validación: ' . implode(', ', array_merge(...array_values($e->errors()))),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            Log::error('Error al crear orden', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ticket_id' => $request->input('ticketId'),
                'user_id' => auth()->id(),
                'tiempo_ejecucion_ms' => $executionTime
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la orden: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeProvincia(Request $request)
    {
        $startTime = microtime(true);
        Log::info('Iniciando creación de orden provincia', [
            'user_id' => auth()->id(),
            'ticket_number' => $request->input('ticketNumber'),
            'cast_id' => $request->input('castId'),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        try {
            DB::beginTransaction();
            Log::info('Transacción de base de datos iniciada');

            // Validar los datos requeridos para provincia (ticketNumber es texto, no necesita existir)
            Log::debug('Iniciando validación de datos para provincia');
            $validated = $request->validate([
                'ticketNumber' => 'required|string|min:1|max:50',
                'castId' => 'required|exists:cast,idCast',
                'orderInfo.tipoServicio' => 'required|string',
                'orderInfo.urgencia' => 'required|string|in:baja,media,alta',
                'orderInfo.fechaRequerida' => 'required|date',
                'orderInfo.observaciones' => 'nullable|string',
                'products' => 'required|array|min:1',
                'products.*.modeloId' => 'required|exists:modelo,idModelo',
                'products.*.tipoId' => 'required|exists:subcategorias,id',
                'products.*.codigoId' => 'required',
                'products.*.cantidad' => 'required|integer|min:1|max:100'
            ]);
            Log::info('Validación exitosa para provincia', ['campos_validados' => array_keys($validated)]);

            // Buscar información del CAST
            Log::debug('Buscando información del CAST', ['cast_id' => $validated['castId']]);
            $cast = DB::table('cast')
                ->where('idCast', $validated['castId'])
                ->first();

            if (!$cast) {
                Log::error('CAST no encontrado', ['cast_id' => $validated['castId']]);
                throw new \Exception('CAST no encontrado');
            }
            Log::info('CAST encontrado', ['cast_nombre' => $cast->nombre]);

            // Calcular estadísticas de productos
            Log::debug('Calculando estadísticas de productos');
            $totalCantidad = collect($validated['products'])->sum('cantidad');
            $totalProductosUnicos = collect($validated['products'])->unique(function ($product) {
                return $product['modeloId'] . '-' . $product['tipoId'] . '-' . $product['codigoId'];
            })->count();

            Log::info('Estadísticas calculadas', [
                'total_cantidad' => $totalCantidad,
                'productos_unicos' => $totalProductosUnicos,
                'total_productos' => count($validated['products'])
            ]);

            // Generar código de orden
            $nextOrderNumber = DB::table('solicitudesordenes')->count() + 1;
            $codigoOrden = 'ORD-' . str_pad($nextOrderNumber, 3, '0', STR_PAD_LEFT);
            Log::info('Código de orden generado', ['codigo_orden' => $codigoOrden]);

            // 1. Insertar en solicitudesordenes con los nuevos campos
            Log::debug('Preparando inserción en solicitudesordenes para provincia');
            $solicitudData = [
                'fechacreacion' => now(),
                'estado' => 'pendiente',
                'tipoorden' => 'solicitud_repuesto_provincia', // Nuevo tipo para provincia
                'idticket' => null, // Para provincia no tenemos idticket de la tabla tickets
                'idCast' => $validated['castId'], // Nuevo campo para CAST
                'fecharequerida' => $validated['orderInfo']['fechaRequerida'],
                'fechaentrega' => $validated['orderInfo']['fechaRequerida'],
                'codigo' => $codigoOrden,
                'niveldeurgencia' => $validated['orderInfo']['urgencia'],
                'tiposervicio' => $validated['orderInfo']['tipoServicio'],
                'observaciones' => $validated['orderInfo']['observaciones'] ?? null,
                'cantidad' => $totalProductosUnicos,
                'canproduuni' => $totalProductosUnicos,
                'totalcantidadproductos' => $totalCantidad,
                'urgencia' => $validated['orderInfo']['urgencia'],
                'numeroticket' => $validated['ticketNumber'], // Guardar el número de ticket como texto
                'idtiposervicio' => $this->getTipoServicioId($validated['orderInfo']['tipoServicio']),
                'idtecnico' => auth()->id(),
                'idusuario' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now()
            ];

            Log::debug('Datos para solicitudesordenes provincia', $solicitudData);

            $solicitudId = DB::table('solicitudesordenes')->insertGetId($solicitudData);
            Log::info('Solicitud de orden provincia creada exitosamente', ['solicitud_id' => $solicitudId]);

            // 2. Insertar los artículos en ordenesarticulos PARA PROVINCIA
            Log::debug('Iniciando procesamiento de productos para provincia', ['total_productos' => count($validated['products'])]);

            $productosProcesados = 0;
            $productosConError = 0;

            foreach ($validated['products'] as $index => $product) {
                Log::debug("Procesando producto {$index} para provincia", [
                    'producto_index' => $index,
                    'codigo' => $product['codigoId'],
                    'cantidad' => $product['cantidad'],
                    'ticket_number' => $validated['ticketNumber']
                ]);

                // Buscar el idArticulos basado en el código
                $articulo = DB::table('articulos')
                    ->where('codigo_repuesto', $product['codigoId'])
                    ->first();

                if ($articulo) {
                    // Para provincia, guardamos el número de ticket como texto en observacion
                    DB::table('ordenesarticulos')->insert([
                        'cantidad' => $product['cantidad'],
                        'estado' => 0, // 0 = pendiente
                        'observacion' => 'Ticket: ' . $validated['ticketNumber'] . ' | CAST: ' . $cast->nombre, // Guardar info en observación
                        'fotos_evidencia' => null,
                        'fotoRepuesto' => null,
                        'fechaUsado' => null,
                        'fechaSinUsar' => null,
                        'idSolicitudesOrdenes' => $solicitudId,
                        'idticket' => null, // Para provincia no tenemos idticket
                        'idArticulos' => $articulo->idArticulos,
                        'idUbicacion' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $productosProcesados++;
                    Log::debug("Producto {$index} insertado exitosamente para provincia", [
                        'articulo_id' => $articulo->idArticulos,
                        'codigo' => $product['codigoId']
                    ]);
                } else {
                    $productosConError++;
                    Log::warning("Artículo no encontrado para provincia", [
                        'producto_index' => $index,
                        'codigo' => $product['codigoId']
                    ]);
                }
            }

            Log::info('Procesamiento de productos para provincia completado', [
                'productos_procesados' => $productosProcesados,
                'productos_con_error' => $productosConError,
                'total_productos' => count($validated['products'])
            ]);

            DB::commit();
            Log::info('Transacción confirmada exitosamente para provincia');

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            Log::info('Orden provincia creada exitosamente', [
                'solicitud_id' => $solicitudId,
                'codigo_orden' => $codigoOrden,
                'ticket_number' => $validated['ticketNumber'],
                'cast_nombre' => $cast->nombre,
                'tiempo_ejecucion_ms' => $executionTime,
                'total_productos' => count($validated['products'])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Orden de provincia creada exitosamente',
                'solicitud_id' => $solicitudId,
                'codigo_orden' => $codigoOrden,
                'numeroticket' => $validated['ticketNumber'],
                'cast_nombre' => $cast->nombre,
                'estadisticas' => [
                    'productos_unicos' => $totalProductosUnicos,
                    'total_cantidad' => $totalCantidad
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Error de validación al crear orden provincia', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error de validación: ' . implode(', ', array_merge(...array_values($e->errors()))),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);
            Log::error('Error al crear orden provincia', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ticket_number' => $request->input('ticketNumber'),
                'cast_id' => $request->input('castId'),
                'user_id' => auth()->id(),
                'tiempo_ejecucion_ms' => $executionTime
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la orden de provincia: ' . $e->getMessage()
            ], 500);
        }
    }




    /**
     * Obtener el ID del tipo de servicio basado en el valor
     */
    private function getTipoServicioId($tipoServicio)
    {
        $tipos = [
            'mantenimiento' => 1,
            'reparacion' => 2,
            'instalacion' => 3,
            'garantia' => 4
        ];

        return $tipos[$tipoServicio] ?? 1; // Default a mantenimiento
    }

    public function show($id)
    {
        $solicitud = DB::table('solicitudesordenes as so')
            ->select(
                'so.idsolicitudesordenes',
                'so.codigo',
                'so.estado',
                'so.tiposervicio',
                'so.niveldeurgencia as urgencia',
                'so.fecharequerida',
                'so.observaciones',
                'so.idticket', // ? ESTE ES IMPORTANTE
                't.numero_ticket',
                't.serie',
                't.idModelo',
                'm.nombre as modelo_nombre',
                'mar.nombre as marca_nombre',
                DB::raw("CONCAT(u.Nombre, ' ', u.apellidoPaterno) as nombre_solicitante"),
                'ta.nombre as nombre_area'
            )
            ->leftJoin('usuarios as u', 'so.idusuario', '=', 'u.idUsuario')
            ->leftJoin('tipoarea as ta', 'u.idTipoArea', '=', 'ta.idTipoArea')
            ->leftJoin('tickets as t', 'so.idticket', '=', 't.idTickets')
            ->leftJoin('modelo as m', 't.idModelo', '=', 'm.idModelo')
            ->leftJoin('marca as mar', 't.idMarca', '=', 'mar.idMarca')
            ->where('so.idsolicitudesordenes', $id)
            ->where('so.tipoorden', 'solicitud_repuesto')
            ->first();

        if (!$solicitud) {
            abort(404, 'Solicitud no encontrada');
        }

        $articulos = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idordenesarticulos',
                'oa.cantidad',
                'oa.estado',
                'oa.idticket',
                'oa.idarticulos',
                'a.codigo_repuesto',
                'a.codigo_barras',
                'a.nombre as nombre_articulo',
                'a.precio_compra',
                'a.idsubcategoria',
                'sc.nombre as tipo_articulo',
                't.numero_ticket',
                'm.nombre as modelo_nombre'
            )
            ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->leftJoin('tickets as t', 'oa.idticket', '=', 't.idTickets')
            ->leftJoin('modelo as m', 't.idModelo', '=', 'm.idModelo')
            ->where('oa.idsolicitudesordenes', $id)
            ->get();

        return view('solicitud.solicitudrepuesto.show', compact('solicitud', 'articulos'));
    }

    public function showprovincia($id)
    {
        $solicitud = DB::table('solicitudesordenes as so')
            ->select(
                'so.idsolicitudesordenes',
                'so.codigo',
                'so.estado',
                'so.tiposervicio',
                'so.niveldeurgencia as urgencia',
                'so.fecharequerida',
                'so.observaciones',
                'so.numeroticket', // Ticket manual para provincia
                'so.idCast', // CAST para provincia
                'c.nombre as cast_nombre', // Nombre del CAST
                'c.direccion as cast_direccion', // Dirección del CAST
                'c.provincia as cast_provincia', // Provincia del CAST
                'c.distrito as cast_distrito', // Distrito del CAST
                'c.departamento as cast_departamento', // Departamento del CAST
                DB::raw("CONCAT(u.Nombre, ' ', u.apellidoPaterno) as nombre_solicitante"),
                'ta.nombre as nombre_area'
            )
            ->leftJoin('usuarios as u', 'so.idusuario', '=', 'u.idUsuario')
            ->leftJoin('tipoarea as ta', 'u.idTipoArea', '=', 'ta.idTipoArea')
            ->leftJoin('cast as c', 'so.idCast', '=', 'c.idCast') // JOIN con CAST
            ->where('so.idsolicitudesordenes', $id)
            ->where('so.tipoorden', 'solicitud_repuesto_provincia') // Tipo específico para provincia
            ->first();

        if (!$solicitud) {
            abort(404, 'Solicitud de provincia no encontrada');
        }

        $articulos = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idordenesarticulos',
                'oa.cantidad',
                'oa.estado',
                'oa.observacion', // En provincia, aquí está el ticket manual y CAST
                'oa.idArticulos',
                'a.codigo_repuesto',
                'a.codigo_barras',
                'a.nombre as nombre_articulo',
                'a.precio_compra',
                'a.idsubcategoria',
                'sc.nombre as tipo_articulo'
            )
            ->join('articulos as a', 'oa.idArticulos', '=', 'a.idArticulos')
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->where('oa.idSolicitudesOrdenes', $id)
            ->get();

        // Agregar información adicional a los artículos
        $articulos = $articulos->map(function ($articulo) {
            // Extraer información del ticket desde observacion
            if ($articulo->observacion) {
                if (strpos($articulo->observacion, 'Ticket:') !== false) {
                    $parts = explode(' | ', $articulo->observacion);
                    $ticketPart = str_replace('Ticket: ', '', $parts[0] ?? '');
                    $articulo->ticket_manual = trim($ticketPart);

                    if (isset($parts[1])) {
                        $castPart = str_replace('CAST: ', '', $parts[1] ?? '');
                        $articulo->cast_info = trim($castPart);
                    }
                }
            }
            return $articulo;
        });

        return view('solicitud.solicitudrepuesto.showprovincia', compact('solicitud', 'articulos'));
    }


    public function edit($id)
    {
        try {
            // Obtener la solicitud principal
            $solicitud = DB::table('solicitudesordenes')
                ->where('idsolicitudesordenes', $id)
                ->first();

            if (!$solicitud) {
                abort(404, 'Solicitud no encontrada');
            }

            // Obtener los artículos de la solicitud - CORREGIDO
            $articulos = DB::table('ordenesarticulos as oa')
                ->select(
                    'oa.idordenesarticulos',
                    'oa.cantidad',
                    'oa.idsolicitudesordenes',
                    'oa.idticket',
                    'oa.idarticulos',
                    'a.codigo_repuesto',
                    'a.nombre as articulo_nombre',
                    't.numero_ticket',
                    'm.nombre as modelo_nombre',
                    'm.idModelo',
                    'sc.id as subcategoria_id',
                    'sc.nombre as tipo_repuesto' // Cambiado de sc.tipo_repuesto a sc.nombre
                )
                ->leftJoin('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
                ->leftJoin('tickets as t', 'oa.idticket', '=', 't.idTickets')
                ->leftJoin('modelo as m', 't.idModelo', '=', 'm.idModelo')
                ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
                ->where('oa.idsolicitudesordenes', $id)
                ->get();

            // Obtener tickets disponibles (misma lógica que create)
            $userId = auth()->id();
            $tickets = DB::table('tickets as t')
                ->select(
                    't.idTickets',
                    't.numero_ticket',
                    't.idModelo',
                    'm.nombre as modelo_nombre'
                )
                ->leftJoin('modelo as m', 't.idModelo', '=', 'm.idModelo')
                ->where('t.idTipotickets', 1)
                ->where(function ($query) use ($userId) {
                    if ($userId == 1) {
                        return $query;
                    } else {
                        return $query->whereExists(function ($subQuery) use ($userId) {
                            $subQuery->select(DB::raw(1))
                                ->from('visitas as v')
                                ->whereColumn('v.idTickets', 't.idTickets')
                                ->where('v.idUsuario', $userId)
                                ->where('v.estado', 1)
                                ->whereExists(function ($flujoQuery) {
                                    $flujoQuery->select(DB::raw(1))
                                        ->from('ticketflujo as tf')
                                        ->whereColumn('tf.idTicket', 't.idTickets')
                                        ->where('tf.idestadflujo', 2);
                                });
                        });
                    }
                })
                ->orderBy('t.fecha_creacion', 'desc')
                ->get();

            return view('solicitud.solicitudrepuesto.edit', compact(
                'solicitud',
                'articulos',
                'tickets'
            ));
        } catch (\Exception $e) {
            Log::error('Error al cargar edición de solicitud', [
                'solicitud_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Error al cargar la solicitud para editar');
        }
    }

    public function update(Request $request, $id)
    {
        $startTime = microtime(true);
        Log::info('Iniciando actualización de orden', [
            'user_id' => auth()->id(),
            'solicitud_id' => $id,
            'ip' => $request->ip()
        ]);

        try {
            DB::beginTransaction();
            Log::info('Transacción de base de datos iniciada para actualización');

            // Validar los datos requeridos
            $validated = $request->validate([
                'ticketId' => 'required|exists:tickets,idTickets',
                'orderInfo.tipoServicio' => 'required|string',
                'orderInfo.urgencia' => 'required|string|in:baja,media,alta',
                'orderInfo.fechaRequerida' => 'required|date',
                'orderInfo.observaciones' => 'nullable|string',
                'products' => 'required|array|min:1',
                'products.*.ticketId' => 'required|exists:tickets,idTickets',
                'products.*.modeloId' => 'required|exists:modelo,idModelo',
                'products.*.tipoId' => 'required|exists:subcategorias,id',
                'products.*.codigoId' => 'required',
                'products.*.cantidad' => 'required|integer|min:1|max:100'
            ]);

            Log::info('Validación exitosa para actualización', ['solicitud_id' => $id]);

            // Verificar que la solicitud existe
            $solicitudExistente = DB::table('solicitudesordenes')
                ->where('idsolicitudesordenes', $id)
                ->first();

            if (!$solicitudExistente) {
                throw new \Exception('Solicitud no encontrada');
            }

            // Calcular nuevas estadísticas
            $totalCantidad = collect($validated['products'])->sum('cantidad');
            $totalProductosUnicos = collect($validated['products'])->unique(function ($product) {
                return $product['modeloId'] . '-' . $product['tipoId'] . '-' . $product['codigoId'];
            })->count();

            Log::info('Nuevas estadísticas calculadas', [
                'total_cantidad' => $totalCantidad,
                'productos_unicos' => $totalProductosUnicos
            ]);

            // 1. Actualizar la solicitud principal
            DB::table('solicitudesordenes')
                ->where('idsolicitudesordenes', $id)
                ->update([
                    'fecharequerida' => $validated['orderInfo']['fechaRequerida'],
                    'niveldeurgencia' => $validated['orderInfo']['urgencia'],
                    'tiposervicio' => $validated['orderInfo']['tipoServicio'],
                    'observaciones' => $validated['orderInfo']['observaciones'] ?? null,
                    'cantidad' => $totalProductosUnicos,
                    'canproduuni' => $totalProductosUnicos,
                    'totalcantidadproductos' => $totalCantidad,
                    'idtipoServicio' => $this->getTipoServicioId($validated['orderInfo']['tipoServicio']),
                    'urgencia' => $validated['orderInfo']['urgencia'],
                    'updated_at' => now()
                ]);

            // 2. Eliminar artículos existentes
            DB::table('ordenesarticulos')
                ->where('idsolicitudesordenes', $id)
                ->delete();

            Log::info('Artículos existentes eliminados', ['solicitud_id' => $id]);

            // 3. Insertar los nuevos artículos
            $productosProcesados = 0;
            $productosConError = 0;

            foreach ($validated['products'] as $index => $product) {
                $articulo = DB::table('articulos')
                    ->where('codigo_repuesto', $product['codigoId'])
                    ->first();

                if ($articulo) {
                    DB::table('ordenesarticulos')->insert([
                        'cantidad' => $product['cantidad'],
                        'estado' => 0,
                        'observacion' => null,
                        'fotorepuesto' => null,
                        'fechausado' => null,
                        'fechasinusar' => null,
                        'idsolicitudesordenes' => $id,
                        'idticket' => $product['ticketId'],
                        'idarticulos' => $articulo->idArticulos,
                        'idubicacion' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $productosProcesados++;
                } else {
                    $productosConError++;
                    Log::warning("Artículo no encontrado durante actualización", [
                        'codigo' => $product['codigoId']
                    ]);
                }
            }

            Log::info('Nuevos artículos insertados', [
                'procesados' => $productosProcesados,
                'con_error' => $productosConError
            ]);

            DB::commit();
            Log::info('Actualización completada exitosamente', ['solicitud_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Solicitud actualizada exitosamente',
                'solicitud_id' => $id,
                'codigo_orden' => $solicitudExistente->codigo
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Error de validación al actualizar', [
                'errors' => $e->errors(),
                'solicitud_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error de validación: ' . implode(', ', array_merge(...array_values($e->errors()))),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar solicitud', [
                'solicitud_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    public function editProvincia($id)
    {
        try {
            Log::info('=== INICIANDO editProvincia ===', ['solicitud_id' => $id]);

            // Obtener la solicitud principal para provincia
            $solicitud = DB::table('solicitudesordenes as so')
                ->select(
                    'so.idsolicitudesordenes',
                    'so.codigo',
                    'so.estado',
                    'so.tiposervicio',
                    'so.niveldeurgencia as urgencia',
                    'so.fecharequerida',
                    'so.observaciones',
                    'so.numeroticket',
                    'so.idCast',
                    'so.created_at as fechaCreacion',
                    'c.nombre as cast_nombre',
                    'c.direccion',
                    'c.provincia',
                    'c.distrito',
                    'c.departamento'
                )
                ->leftJoin('cast as c', 'so.idCast', '=', 'c.idCast')
                ->where('so.idsolicitudesordenes', $id)
                ->where('so.tipoorden', 'solicitud_repuesto_provincia')
                ->first();

            if (!$solicitud) {
                Log::error('Solicitud de provincia no encontrada', ['solicitud_id' => $id]);
                abort(404, 'Solicitud de provincia no encontrada');
            }

            Log::info('Solicitud encontrada:', [
                'id' => $solicitud->idsolicitudesordenes,
                'codigo' => $solicitud->codigo,
                'numeroticket' => $solicitud->numeroticket,
                'idCast' => $solicitud->idCast,
                'cast_nombre' => $solicitud->cast_nombre
            ]);

            // Obtener los artículos de la solicitud - VERSIÓN CORREGIDA
            Log::info('Consultando artículos para solicitud:', ['solicitud_id' => $id]);

            $articulos = DB::table('ordenesarticulos as oa')
                ->select(
                    'oa.idordenesarticulos',
                    'oa.cantidad',
                    'oa.observacion',
                    'oa.idArticulos',
                    'a.codigo_repuesto',
                    'a.nombre as nombre_articulo',
                    'a.idsubcategoria',
                    // MODELO - usar el de articulo_modelo si existe
                    DB::raw('COALESCE(am.modelo_id, a.idModelo) as modelo_id'),
                    'sc.id as subcategoria_id',
                    'sc.nombre as tipo_articulo',
                    // NOMBRE DEL MODELO - usar el de articulo_modelo si existe
                    DB::raw('COALESCE(m2.nombre, m.nombre) as modelo_nombre')
                )
                ->join('articulos as a', 'oa.idArticulos', '=', 'a.idArticulos')
                ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
                ->leftJoin('modelo as m', 'a.idModelo', '=', 'm.idModelo')
                ->leftJoin('articulo_modelo as am', 'a.idArticulos', '=', 'am.articulo_id')
                ->leftJoin('modelo as m2', 'am.modelo_id', '=', 'm2.idModelo')
                ->where('oa.idSolicitudesOrdenes', $id)
                ->get();

            // Log detallado de cada artículo CON ESTRUCTURA CORRECTA
            Log::info('Total de artículos encontrados:', ['count' => $articulos->count()]);

            $articulos->each(function ($articulo, $index) {
                Log::info("Artículo {$index} - CORREGIDO:", [
                    'idordenesarticulos' => $articulo->idordenesarticulos,
                    'idArticulos' => $articulo->idArticulos,
                    'codigo_repuesto' => $articulo->codigo_repuesto,
                    'modelo_id' => $articulo->modelo_id, // ? ESTE ES EL IMPORTANTE
                    'modelo_nombre' => $articulo->modelo_nombre, // ? ESTE ES EL IMPORTANTE
                    'subcategoria_id' => $articulo->subcategoria_id,
                    'tipo_articulo' => $articulo->tipo_articulo,
                    'cantidad' => $articulo->cantidad,
                    'observacion' => $articulo->observacion
                ]);
            });

            // Obtener lista de CAST activos
            $castList = DB::table('cast')
                ->where('estado', 1)
                ->orderBy('nombre', 'asc')
                ->get();

            // Obtener lista de modelos (para el select manual)
            $modelos = DB::table('modelo')
                ->where('estado', 1)
                ->orderBy('nombre', 'asc')
                ->get();

            // Log de resumen
            Log::info('Resumen datos CORREGIDOS para vista:', [
                'solicitud_id' => $solicitud->idsolicitudesordenes,
                'total_articulos' => $articulos->count(),
                'articulos_con_modelo' => $articulos->whereNotNull('modelo_id')->count(),
                'articulos_con_subcategoria' => $articulos->whereNotNull('subcategoria_id')->count()
            ]);

            Log::info('=== FINALIZANDO editProvincia ===');

            return view('solicitud.solicitudrepuesto.edit-provincia', compact(
                'solicitud',
                'articulos',
                'castList',
                'modelos'
            ));
        } catch (\Exception $e) {
            Log::error('Error al cargar edición de solicitud provincia', [
                'solicitud_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Error al cargar la solicitud para editar: ' . $e->getMessage());
        }
    }
    public function updateProvincia(Request $request, $id)
    {
        $startTime = microtime(true);
        Log::info('Iniciando actualización de orden provincia', [
            'user_id' => auth()->id(),
            'solicitud_id' => $id,
            'ip' => $request->ip()
        ]);

        try {
            DB::beginTransaction();
            Log::info('Transacción de base de datos iniciada para actualización provincia');

            // Validar los datos requeridos para provincia
            $validated = $request->validate([
                'ticketNumber' => 'required|string|min:1|max:50',
                'castId' => 'required|exists:cast,idCast',
                'orderInfo.tipoServicio' => 'required|string',
                'orderInfo.urgencia' => 'required|string|in:baja,media,alta',
                'orderInfo.fechaRequerida' => 'required|date',
                'orderInfo.observaciones' => 'nullable|string',
                'products' => 'required|array|min:1',
                'products.*.modeloId' => 'required|exists:modelo,idModelo',
                'products.*.tipoId' => 'required|exists:subcategorias,id',
                'products.*.codigoId' => 'required',
                'products.*.cantidad' => 'required|integer|min:1|max:100'
            ]);

            Log::info('Validación exitosa para actualización provincia', ['solicitud_id' => $id]);

            // Verificar que la solicitud existe y es de provincia
            $solicitudExistente = DB::table('solicitudesordenes')
                ->where('idsolicitudesordenes', $id)
                ->where('tipoorden', 'solicitud_repuesto_provincia')
                ->first();

            if (!$solicitudExistente) {
                throw new \Exception('Solicitud de provincia no encontrada');
            }

            // Buscar información del CAST
            $cast = DB::table('cast')
                ->where('idCast', $validated['castId'])
                ->first();

            if (!$cast) {
                throw new \Exception('CAST no encontrado');
            }

            // Calcular nuevas estadísticas
            $totalCantidad = collect($validated['products'])->sum('cantidad');
            $totalProductosUnicos = collect($validated['products'])->unique(function ($product) {
                return $product['modeloId'] . '-' . $product['tipoId'] . '-' . $product['codigoId'];
            })->count();

            Log::info('Nuevas estadísticas calculadas para provincia', [
                'total_cantidad' => $totalCantidad,
                'productos_unicos' => $totalProductosUnicos
            ]);

            // 1. Actualizar la solicitud principal
            DB::table('solicitudesordenes')
                ->where('idsolicitudesordenes', $id)
                ->update([
                    'idCast' => $validated['castId'],
                    'numeroticket' => $validated['ticketNumber'],
                    'fecharequerida' => $validated['orderInfo']['fechaRequerida'],
                    'niveldeurgencia' => $validated['orderInfo']['urgencia'],
                    'tiposervicio' => $validated['orderInfo']['tipoServicio'],
                    'observaciones' => $validated['orderInfo']['observaciones'] ?? null,
                    'cantidad' => $totalProductosUnicos,
                    'canproduuni' => $totalProductosUnicos,
                    'totalcantidadproductos' => $totalCantidad,
                    'idtipoServicio' => $this->getTipoServicioId($validated['orderInfo']['tipoServicio']),
                    'urgencia' => $validated['orderInfo']['urgencia'],
                    'updated_at' => now()
                ]);

            // 2. Eliminar artículos existentes
            DB::table('ordenesarticulos')
                ->where('idSolicitudesOrdenes', $id)
                ->delete();

            Log::info('Artículos existentes eliminados para provincia', ['solicitud_id' => $id]);

            // 3. Insertar los nuevos artículos PARA PROVINCIA
            $productosProcesados = 0;
            $productosConError = 0;

            foreach ($validated['products'] as $index => $product) {
                $articulo = DB::table('articulos')
                    ->where('codigo_repuesto', $product['codigoId'])
                    ->first();

                if ($articulo) {
                    // Para provincia, guardamos el número de ticket como texto en observacion
                    DB::table('ordenesarticulos')->insert([
                        'cantidad' => $product['cantidad'],
                        'estado' => 0, // 0 = pendiente
                        'observacion' => 'Ticket: ' . $validated['ticketNumber'] . ' | CAST: ' . $cast->nombre,
                        'fotos_evidencia' => null,
                        'fotoRepuesto' => null,
                        'fechaUsado' => null,
                        'fechaSinUsar' => null,
                        'idSolicitudesOrdenes' => $id,
                        'idticket' => null, // Para provincia no tenemos idticket
                        'idArticulos' => $articulo->idArticulos,
                        'idUbicacion' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    $productosProcesados++;
                } else {
                    $productosConError++;
                    Log::warning("Artículo no encontrado durante actualización provincia", [
                        'codigo' => $product['codigoId']
                    ]);
                }
            }

            Log::info('Nuevos artículos insertados para provincia', [
                'procesados' => $productosProcesados,
                'con_error' => $productosConError
            ]);

            DB::commit();
            Log::info('Actualización provincia completada exitosamente', ['solicitud_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Solicitud de provincia actualizada exitosamente',
                'solicitud_id' => $id,
                'codigo_orden' => $solicitudExistente->codigo,
                'numeroticket' => $validated['ticketNumber'],
                'cast_nombre' => $cast->nombre,
                'estadisticas' => [
                    'productos_unicos' => $totalProductosUnicos,
                    'total_cantidad' => $totalCantidad
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Error de validación al actualizar provincia', [
                'errors' => $e->errors(),
                'solicitud_id' => $id
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error de validación: ' . implode(', ', array_merge(...array_values($e->errors()))),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar solicitud provincia', [
                'solicitud_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la solicitud de provincia: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Verificar que la solicitud existe y es del tipo correcto
            $solicitud = DB::table('solicitudesordenes')
                ->where('idsolicitudesordenes', $id)
                ->where('tipoorden', 'solicitud_repuesto')
                ->first();

            if (!$solicitud) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solicitud no encontrada'
                ], 404);
            }

            // Eliminar artículos primero
            DB::table('ordenesarticulos')->where('idsolicitudesordenes', $id)->delete();

            // Eliminar la solicitud
            DB::table('solicitudesordenes')->where('idsolicitudesordenes', $id)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Solicitud eliminada exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }






  public function opciones($id)
    {
        // Obtener la solicitud
        $solicitud = DB::table('solicitudesordenes as so')
            ->select(
                'so.idsolicitudesordenes',
                'so.codigo',
                'so.estado',
                'so.tiposervicio',
                'so.niveldeurgencia',
                'so.fechacreacion',
                'so.fecharequerida',
                'so.observaciones',
                'so.cantidad',
                'so.totalcantidadproductos',
                'so.idticket',
                'so.idUsuario',
                'so.idTecnico',
                't.numero_ticket'
            )
            ->leftJoin('tickets as t', 'so.idticket', '=', 't.idTickets')
            ->where('so.idsolicitudesordenes', $id)
            ->where('so.tipoorden', 'solicitud_repuesto')
            ->first();

        if (!$solicitud) {
            abort(404, 'Solicitud no encontrada');
        }

        // Obtener información del solicitante
        $solicitante = DB::table('usuarios')
            ->select('idUsuario', 'Nombre', 'apellidoPaterno', 'apellidoMaterno', 'correo')
            ->where('idUsuario', $solicitud->idUsuario)
            ->where('estado', 1)
            ->first();

        // Obtener información del técnico
        $tecnico = null;
        if ($solicitud->idTecnico) {
            $tecnico = DB::table('usuarios')
                ->select('idUsuario', 'Nombre', 'apellidoPaterno', 'apellidoMaterno', 'correo')
                ->where('idUsuario', $solicitud->idTecnico)
                ->where('estado', 1)
                ->first();
        }

        // Obtener lista de usuarios
        $usuarios = DB::table('usuarios')
            ->select('idUsuario', 'Nombre', 'apellidoPaterno', 'apellidoMaterno', 'correo')
            ->where('estado', 1)
            ->orderBy('Nombre')
            ->orderBy('apellidoPaterno')
            ->get();

        // Obtener los repuestos de la solicitud
        $repuestos = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idordenesarticulos',
                'oa.cantidad as cantidad_solicitada',
                'oa.observacion',
                'oa.estado as estado_orden',
                'a.idArticulos',
                'a.nombre',
                'a.codigo_barras',
                'a.codigo_repuesto',
                'a.stock_total',
                'sc.nombre as tipo_repuesto'
            )
            ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->where('oa.idsolicitudesordenes', $id)
            ->get();

        // Para cada repuesto, obtener información completa
        foreach ($repuestos as $repuesto) {
            // Obtener ubicaciones con stock detallado
            $ubicaciones = DB::table('rack_ubicacion_articulos as rua')
                ->select(
                    'rua.idRackUbicacionArticulo',
                    'rua.rack_ubicacion_id',
                    'rua.cantidad as stock_ubicacion',
                    'ru.codigo as ubicacion_codigo',
                    'r.nombre as rack_nombre'
                )
                ->join('rack_ubicaciones as ru', 'rua.rack_ubicacion_id', '=', 'ru.idRackUbicacion')
                ->leftJoin('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->where('rua.articulo_id', $repuesto->idArticulos)
                ->where('rua.cantidad', '>', 0)
                ->orderBy('rua.cantidad', 'desc')
                ->get();

            // Calcular stock total disponible
            $stockDisponible = $ubicaciones->sum('stock_ubicacion');

            // Agregar información al repuesto
            $repuesto->stock_disponible = $stockDisponible;
            $repuesto->ubicaciones_detalle = $ubicaciones;
            $repuesto->suficiente_stock = $stockDisponible >= $repuesto->cantidad_solicitada;
            $repuesto->diferencia_stock = $stockDisponible - $repuesto->cantidad_solicitada;

            // Obtener información de entrega (sea normal o cedida)
            $entregaInfo = DB::table('repuestos_entregas as re')
                ->select(
                    're.id as entrega_id',
                    're.tipo_entrega',
                    're.usuario_destino_id',
                    're.estado as estado_entrega',
                    're.fecha_preparacion',
                    're.fecha_entrega',
                    're.entrega_origen_id', // Para saber si es cedido
                    'u.Nombre',
                    'u.apellidoPaterno',
                    'u.apellidoMaterno'
                )
                ->leftJoin('usuarios as u', 're.usuario_destino_id', '=', 'u.idUsuario')
                ->where('re.solicitud_id', $id)
                ->where('re.articulo_id', $repuesto->idArticulos)
                ->orderBy('re.id', 'desc')
                ->first();

            if ($entregaInfo) {
                $repuesto->entrega_info = $entregaInfo;
                $repuesto->ya_procesado = true;
                $repuesto->es_cedido = !empty($entregaInfo->entrega_origen_id);
                
                // Determinar estado actual
                if ($repuesto->es_cedido) {
                    // Estados específicos para repuestos cedidos
                    switch ($entregaInfo->estado_entrega) {
                        case 'listo_para_ceder':
                            $repuesto->estado_actual = 'listo_para_ceder';
                            break;
                        case 'entregado':
                            $repuesto->estado_actual = 'entregado_cedido';
                            break;
                        default:
                            $repuesto->estado_actual = $entregaInfo->estado_entrega ?? 'pendiente_entrega';
                    }
                } else {
                    // Estados para repuestos normales
                    $repuesto->estado_actual = $entregaInfo->estado_entrega ?? 'pendiente_entrega';
                }
            } else {
                $repuesto->entrega_info = null;
                $repuesto->ya_procesado = false;
                $repuesto->es_cedido = false;
                $repuesto->estado_actual = 'no_procesado';
            }
        }

        // Verificar si toda la solicitud puede ser atendida
        $puede_aceptar = $repuestos->every(function ($repuesto) {
            return $repuesto->suficiente_stock;
        });

        // Contar repuestos procesados y disponibles
        $repuestos_procesados = $repuestos->where('ya_procesado', true)->count();
        $repuestos_disponibles = $repuestos->where('suficiente_stock', true)->count();
        $total_repuestos = $repuestos->count();

        $puede_generar_pdf = ($repuestos_procesados == $total_repuestos) && ($total_repuestos > 0);

        return view('solicitud.solicitudrepuesto.opciones', compact(
            'solicitud',
            'repuestos',
            'puede_aceptar',
            'repuestos_procesados',
            'repuestos_disponibles',
            'total_repuestos',
            'solicitante',
            'tecnico',
            'usuarios',
            'puede_generar_pdf'
        ));
    }






public function aceptar(Request $request, $id)
{
    try {
        DB::beginTransaction();

        // Obtener la solicitud con todos los campos necesarios
        $solicitud = DB::table('solicitudesordenes')
            ->select('idsolicitudesordenes', 'codigo', 'estado', 'tipoorden', 'idUsuario', 'idTecnico')
            ->where('idsolicitudesordenes', $id)
            ->where('tipoorden', 'solicitud_repuesto')
            ->first();

        if (!$solicitud) {
            return response()->json([
                'success' => false,
                'message' => 'Solicitud no encontrada'
            ], 404, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        // Verificar si la solicitud ya está aprobada
        if ($solicitud->estado == 'aprobada') {
            return response()->json([
                'success' => false,
                'message' => 'Esta solicitud ya ha sido aprobada anteriormente'
            ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        // Obtener las ubicaciones seleccionadas del request
        $ubicacionesSeleccionadas = $request->input('ubicaciones', []);

        if (empty($ubicacionesSeleccionadas)) {
            return response()->json([
                'success' => false,
                'message' => 'No se han seleccionado ubicaciones para los repuestos'
            ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        // Obtener repuestos de la solicitud CON EL IDTICKET
        $repuestosSolicitud = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idordenesarticulos',
                'oa.cantidad',
                'oa.idticket',
                'a.idArticulos',
                'a.nombre',
                'a.stock_total',
                'a.precio_compra'
            )
            ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
            ->where('oa.idsolicitudesordenes', $id)
            ->get();

        // Verificar que todos los repuestos tengan stock suficiente
        foreach ($repuestosSolicitud as $repuesto) {
            $stockDisponible = DB::table('rack_ubicacion_articulos')
                ->where('articulo_id', $repuesto->idArticulos)
                ->sum('cantidad');

            if ($stockDisponible < $repuesto->cantidad) {
                $nombreRepuesto = mb_convert_encoding($repuesto->nombre, 'UTF-8', 'ISO-8859-1');
                return response()->json([
                    'success' => false,
                    'message' => "Stock insuficiente para el repuesto: {$nombreRepuesto}. Disponible: {$stockDisponible}, Solicitado: {$repuesto->cantidad}"
                ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }
        }

        // Obtener información del solicitante para todas las entregas
        $solicitanteInfo = DB::table('usuarios')
            ->select('Nombre', 'apellidoPaterno', 'apellidoMaterno')
            ->where('idUsuario', $solicitud->idUsuario)
            ->first();

        $nombreSolicitante = $solicitanteInfo
            ? mb_convert_encoding("{$solicitanteInfo->Nombre} {$solicitanteInfo->apellidoPaterno}", 'UTF-8', 'ISO-8859-1')
            : 'Solicitante no encontrado';

        // Procesar cada repuesto
        foreach ($repuestosSolicitud as $repuesto) {
            $cantidadSolicitada = (int)$repuesto->cantidad;
            $ubicacionId = $ubicacionesSeleccionadas[$repuesto->idArticulos] ?? null;

            if (!$ubicacionId) {
                $nombreRepuesto = mb_convert_encoding($repuesto->nombre, 'UTF-8', 'ISO-8859-1');
                return response()->json([
                    'success' => false,
                    'message' => "No se seleccionó ubicación para el repuesto: {$nombreRepuesto}"
                ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }

            // Obtener el número de ticket desde la tabla tickets
            $ticketInfo = DB::table('tickets')
                ->select('numero_ticket')
                ->where('idTickets', $repuesto->idticket)
                ->first();

            if (!$ticketInfo) {
                $nombreRepuesto = mb_convert_encoding($repuesto->nombre, 'UTF-8', 'ISO-8859-1');
                return response()->json([
                    'success' => false,
                    'message' => "Ticket no encontrado para el repuesto: {$nombreRepuesto}"
                ], 404, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }

            $numeroTicket = $ticketInfo->numero_ticket;

            // Verificar stock en la ubicación seleccionada
            $stockUbicacion = DB::table('rack_ubicacion_articulos as rua')
                ->select(
                    'rua.cantidad',
                    'rua.idRackUbicacionArticulo',
                    'rua.cliente_general_id',
                    'ru.codigo as ubicacion_codigo',
                    'ru.idRackUbicacion',
                    'r.idRack as rack_id',
                    'r.nombre as rack_nombre'
                )
                ->join('rack_ubicaciones as ru', 'rua.rack_ubicacion_id', '=', 'ru.idRackUbicacion')
                ->leftJoin('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->where('rua.articulo_id', $repuesto->idArticulos)
                ->where('rua.rack_ubicacion_id', $ubicacionId)
                ->first();

            if (!$stockUbicacion) {
                $nombreRepuesto = mb_convert_encoding($repuesto->nombre, 'UTF-8', 'ISO-8859-1');
                return response()->json([
                    'success' => false,
                    'message' => "Ubicación no encontrada para el repuesto: {$nombreRepuesto}"
                ], 404, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }

            if ((int)$stockUbicacion->cantidad < $cantidadSolicitada) {
                $nombreRepuesto = mb_convert_encoding($repuesto->nombre, 'UTF-8', 'ISO-8859-1');
                $ubicacionCodigo = mb_convert_encoding($stockUbicacion->ubicacion_codigo, 'UTF-8', 'ISO-8859-1');
                return response()->json([
                    'success' => false,
                    'message' => "Stock insuficiente en la ubicación seleccionada para: {$nombreRepuesto}. Ubicación: {$ubicacionCodigo}, Disponible: {$stockUbicacion->cantidad}, Solicitado: {$cantidadSolicitada}"
                ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }

            // Verificar si ya fue procesado
            $yaProcesado = DB::table('ordenesarticulos')
                ->where('idordenesarticulos', $repuesto->idordenesarticulos)
                ->where('estado', 1)
                ->exists();

            if ($yaProcesado) {
                $nombreRepuesto = mb_convert_encoding($repuesto->nombre, 'UTF-8', 'ISO-8859-1');
                return response()->json([
                    'success' => false,
                    'message' => "El repuesto {$nombreRepuesto} ya fue procesado anteriormente"
                ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }

            // 1) DESCONTAR de rack_ubicacion_articulos (por PK)
            DB::table('rack_ubicacion_articulos')
                ->where('idRackUbicacionArticulo', $stockUbicacion->idRackUbicacionArticulo)
                ->decrement('cantidad', $cantidadSolicitada);

            // ? 1.1) DESCONTAR de cajas si existen
            $this->descontarDeCajasSiExisten(
                (int)$repuesto->idArticulos,
                (int)$ubicacionId,
                (int)$cantidadSolicitada,
                null
            );

            // 2) DESCONTAR stock total en tabla articulos
            DB::table('articulos')
                ->where('idArticulos', $repuesto->idArticulos)
                ->decrement('stock_total', $cantidadSolicitada);

            // 3) Registrar movimiento en rack_movimientos
            $observacionesMovimiento = mb_convert_encoding(
                "Solicitud repuesto aprobada (grupal): {$solicitud->codigo} - Ticket: {$numeroTicket} - Entregado a: {$nombreSolicitante} (solicitante)",
                'UTF-8',
                'ISO-8859-1'
            );

            $ubicacionCodigo = mb_convert_encoding($stockUbicacion->ubicacion_codigo, 'UTF-8', 'ISO-8859-1');
            $rackNombre = mb_convert_encoding($stockUbicacion->rack_nombre, 'UTF-8', 'ISO-8859-1');

            DB::table('rack_movimientos')->insert([
                'articulo_id' => $repuesto->idArticulos,
                'custodia_id' => null,
                'ubicacion_origen_id' => $ubicacionId,
                'ubicacion_destino_id' => null,
                'rack_origen_id' => $stockUbicacion->rack_id,
                'rack_destino_id' => null,
                'cantidad' => $cantidadSolicitada,
                'tipo_movimiento' => 'salida',
                'usuario_id' => auth()->id(),
                'observaciones' => $observacionesMovimiento,
                'codigo_ubicacion_origen' => $ubicacionCodigo,
                'codigo_ubicacion_destino' => null,
                'nombre_rack_origen' => $rackNombre,
                'nombre_rack_destino' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 4) inventario_ingresos_clientes
            DB::table('inventario_ingresos_clientes')->insert([
                'compra_id' => null,
                'articulo_id' => $repuesto->idArticulos,
                'tipo_ingreso' => 'salida',
                'ingreso_id' => $solicitud->idsolicitudesordenes,
                'cliente_general_id' => $stockUbicacion->cliente_general_id,
                'numero_orden' => $numeroTicket,
                'codigo_solicitud' => $solicitud->codigo,
                'cantidad' => -$cantidadSolicitada,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 5) repuestos_entregas (grupal -> solicitante)
            $observacionesEntrega = mb_convert_encoding(
                "Repuesto entregado grupalmente - Ticket: {$numeroTicket} - Destinatario: {$nombreSolicitante}",
                'UTF-8',
                'ISO-8859-1'
            );

            DB::table('repuestos_entregas')->insert([
                'solicitud_id' => $solicitud->idsolicitudesordenes,
                'articulo_id' => $repuesto->idArticulos,
                'usuario_destino_id' => $solicitud->idUsuario,
                'tipo_entrega' => 'solicitante',
                'cantidad' => $cantidadSolicitada,
                'ubicacion_utilizada' => $ubicacionCodigo,
                'usuario_entrego_id' => auth()->id(),
                'observaciones' => $observacionesEntrega,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 6) Kardex
            $this->actualizarKardexSalida(
                (int)$repuesto->idArticulos,
                (int)$stockUbicacion->cliente_general_id,
                (int)$cantidadSolicitada,
                (float)$repuesto->precio_compra
            );

            // 7) Marcar como procesado
            $observacion = mb_convert_encoding(
                "Ubicación utilizada: {$ubicacionCodigo} - Procesado grupalmente - Ticket: {$numeroTicket} - Código Solicitud: {$solicitud->codigo} - Entregado a: {$nombreSolicitante} (solicitante)",
                'UTF-8',
                'ISO-8859-1'
            );

            DB::table('ordenesarticulos')
                ->where('idordenesarticulos', $repuesto->idordenesarticulos)
                ->update([
                    'estado' => 1,
                    'observacion' => $observacion
                ]);

            $nombreRepuesto = mb_convert_encoding($repuesto->nombre, 'UTF-8', 'ISO-8859-1');
            Log::info("? Repuesto procesado grupalmente - Artículo: {$repuesto->idArticulos} ({$nombreRepuesto}), Cantidad: {$cantidadSolicitada}, Ubicación: {$ubicacionCodigo}, Ticket: {$numeroTicket}, Solicitud: {$solicitud->codigo}, Destinatario: {$nombreSolicitante}");
        }

        // Actualizar estado de la solicitud
        DB::table('solicitudesordenes')
            ->where('idsolicitudesordenes', $id)
            ->update([
                'estado' => 'aprobada',
                'fechaaprobacion' => now(),
                'idaprobador' => auth()->id()
            ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Solicitud de repuestos aprobada correctamente. Stock descontado de las ubicaciones seleccionadas.'
        ], 200, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al aceptar solicitud de repuestos (grupal): ' . $e->getMessage());
        Log::error('File: ' . $e->getFile());
        Log::error('Line: ' . $e->getLine());
        Log::error('Trace: ' . $e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Error al aceptar la solicitud: ' . $e->getMessage()
        ], 500, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }
}
   public function aceptarIndividual(Request $request, $id)
{
    try {
        DB::beginTransaction();

        $solicitud = DB::table('solicitudesordenes')
            ->select('idsolicitudesordenes', 'codigo', 'estado', 'tipoorden', 'idUsuario', 'idTecnico')
            ->where('idsolicitudesordenes', $id)
            ->where('tipoorden', 'solicitud_repuesto')
            ->first();

        if (!$solicitud) {
            return response()->json([
                'success' => false,
                'message' => 'Solicitud no encontrada'
            ], 404, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        $articuloId = (int)$request->input('articulo_id');
        $ubicacionId = (int)$request->input('ubicacion_id');
        $tipoDestinatario = $request->input('tipo_destinatario');
        $usuarioDestinoId = $request->input('usuario_destino_id');

        if (!$articuloId || !$ubicacionId || !$tipoDestinatario) {
            return response()->json([
                'success' => false,
                'message' => 'Datos incompletos para procesar el repuesto'
            ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        // Determinar el usuario destino final
        $usuarioFinalId = null;
        $tipoEntrega = '';

        switch ($tipoDestinatario) {
            case 'solicitante':
                $usuarioFinalId = $solicitud->idUsuario;
                $tipoEntrega = 'solicitante';
                break;
            case 'tecnico':
                $usuarioFinalId = $solicitud->idTecnico;
                $tipoEntrega = 'tecnico';
                break;
            case 'otro':
                $usuarioFinalId = $usuarioDestinoId;
                $tipoEntrega = 'otro_usuario';
                break;
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Tipo de destinatario no válido'
                ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        if (!$usuarioFinalId) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo determinar el usuario destino'
            ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        // Nombre destinatario
        $destinatarioInfo = DB::table('usuarios')
            ->select('Nombre', 'apellidoPaterno', 'apellidoMaterno')
            ->where('idUsuario', $usuarioFinalId)
            ->first();

        $nombreDestinatario = $destinatarioInfo
            ? mb_convert_encoding("{$destinatarioInfo->Nombre} {$destinatarioInfo->apellidoPaterno}", 'UTF-8', 'ISO-8859-1')
            : 'Usuario no encontrado';

        // Repuesto con ticket
        $repuesto = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idordenesarticulos',
                'oa.cantidad',
                'oa.idticket',
                'a.idArticulos',
                'a.nombre',
                'a.stock_total'
            )
            ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
            ->where('oa.idsolicitudesordenes', $id)
            ->where('a.idArticulos', $articuloId)
            ->first();

        if (!$repuesto) {
            return response()->json([
                'success' => false,
                'message' => 'Repuesto no encontrado en la solicitud'
            ], 404, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        $ticketInfo = DB::table('tickets')
            ->select('numero_ticket')
            ->where('idTickets', $repuesto->idticket)
            ->first();

        if (!$ticketInfo) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket no encontrado'
            ], 404, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        $numeroTicket = $ticketInfo->numero_ticket;

        // Ya procesado
        $yaProcesado = DB::table('ordenesarticulos')
            ->where('idordenesarticulos', $repuesto->idordenesarticulos)
            ->where('estado', 1)
            ->exists();

        if ($yaProcesado) {
            return response()->json([
                'success' => false,
                'message' => 'Este repuesto ya fue procesado anteriormente'
            ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        $cantidadSolicitada = (int)$repuesto->cantidad;

        // Stock ubicación
        $stockUbicacion = DB::table('rack_ubicacion_articulos as rua')
            ->select(
                'rua.cantidad',
                'rua.idRackUbicacionArticulo',
                'ru.codigo as ubicacion_codigo',
                'ru.idRackUbicacion',
                'r.idRack as rack_id',
                'r.nombre as rack_nombre',
                'rua.cliente_general_id'
            )
            ->join('rack_ubicaciones as ru', 'rua.rack_ubicacion_id', '=', 'ru.idRackUbicacion')
            ->leftJoin('racks as r', 'ru.rack_id', '=', 'r.idRack')
            ->where('rua.articulo_id', $articuloId)
            ->where('rua.rack_ubicacion_id', $ubicacionId)
            ->first();

        if (!$stockUbicacion) {
            return response()->json([
                'success' => false,
                'message' => 'Ubicación no encontrada para este repuesto'
            ], 404, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        if ((int)$stockUbicacion->cantidad < $cantidadSolicitada) {
            return response()->json([
                'success' => false,
                'message' => "Stock insuficiente en la ubicación seleccionada. Disponible: {$stockUbicacion->cantidad}, Solicitado: {$cantidadSolicitada}"
            ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        // Articulo info (kardex)
        $articuloInfo = DB::table('articulos')
            ->select('precio_compra', 'precio_venta')
            ->where('idArticulos', $articuloId)
            ->first();

        if (!$articuloInfo) {
            return response()->json([
                'success' => false,
                'message' => 'Información del artículo no encontrada'
            ], 404, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        // 1) Descontar de rack_ubicacion_articulos (por PK)
        DB::table('rack_ubicacion_articulos')
            ->where('idRackUbicacionArticulo', $stockUbicacion->idRackUbicacionArticulo)
            ->decrement('cantidad', $cantidadSolicitada);

        // ? 1.1) Descontar de CAJAS si existen
        $this->descontarDeCajasSiExisten(
            (int)$articuloId,
            (int)$ubicacionId,
            (int)$cantidadSolicitada,
            null
        );

        // 2) Movimiento
        $observacionesMovimiento = mb_convert_encoding(
            "Solicitud repuesto aprobada (individual): {$solicitud->codigo} - Ticket: {$numeroTicket} - Entregado a: {$nombreDestinatario} ({$tipoEntrega})",
            'UTF-8',
            'ISO-8859-1'
        );

        DB::table('rack_movimientos')->insert([
            'articulo_id' => $articuloId,
            'custodia_id' => null,
            'ubicacion_origen_id' => $ubicacionId,
            'ubicacion_destino_id' => null,
            'rack_origen_id' => $stockUbicacion->rack_id,
            'rack_destino_id' => null,
            'cantidad' => $cantidadSolicitada,
            'tipo_movimiento' => 'salida',
            'usuario_id' => auth()->id(),
            'observaciones' => $observacionesMovimiento,
            'codigo_ubicacion_origen' => $stockUbicacion->ubicacion_codigo,
            'codigo_ubicacion_destino' => null,
            'nombre_rack_origen' => $stockUbicacion->rack_nombre,
            'nombre_rack_destino' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 3) inventario_ingresos_clientes
        DB::table('inventario_ingresos_clientes')->insert([
            'compra_id' => null,
            'articulo_id' => $articuloId,
            'tipo_ingreso' => 'salida',
            'ingreso_id' => $solicitud->idsolicitudesordenes,
            'cliente_general_id' => $stockUbicacion->cliente_general_id,
            'numero_orden' => $numeroTicket,
            'codigo_solicitud' => $solicitud->codigo,
            'cantidad' => -$cantidadSolicitada,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 4) repuestos_entregas
        $observacionesEntrega = mb_convert_encoding(
            "Repuesto entregado individualmente - Ticket: {$numeroTicket} - Destinatario: {$nombreDestinatario}",
            'UTF-8',
            'ISO-8859-1'
        );

        DB::table('repuestos_entregas')->insert([
            'solicitud_id' => $solicitud->idsolicitudesordenes,
            'articulo_id' => $articuloId,
            'usuario_destino_id' => $usuarioFinalId,
            'tipo_entrega' => $tipoEntrega,
            'cantidad' => $cantidadSolicitada,
            'ubicacion_utilizada' => $stockUbicacion->ubicacion_codigo,
            'usuario_entrego_id' => auth()->id(),
            'observaciones' => $observacionesEntrega,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 5) Stock total
        DB::table('articulos')
            ->where('idArticulos', $articuloId)
            ->decrement('stock_total', $cantidadSolicitada);

        // 6) Kardex
        $this->actualizarKardexSalida(
            (int)$articuloId,
            (int)$stockUbicacion->cliente_general_id,
            (int)$cantidadSolicitada,
            (float)$articuloInfo->precio_compra
        );

        // 7) Marcar procesado
        $observacion = mb_convert_encoding(
            "Ubicación utilizada: {$stockUbicacion->ubicacion_codigo} - Procesado individualmente - Ticket: {$numeroTicket} - Código Solicitud: {$solicitud->codigo} - Entregado a: {$nombreDestinatario} ({$tipoEntrega})",
            'UTF-8',
            'ISO-8859-1'
        );

        DB::table('ordenesarticulos')
            ->where('idordenesarticulos', $repuesto->idordenesarticulos)
            ->update([
                'estado' => 1,
                'observacion' => $observacion
            ]);

        // Completar solicitud si ya no quedan pendientes
        $repuestosPendientes = DB::table('ordenesarticulos')
            ->where('idsolicitudesordenes', $id)
            ->where('estado', 0)
            ->count();

        $todosProcesados = ($repuestosPendientes == 0);

        if ($todosProcesados) {
            DB::table('solicitudesordenes')
                ->where('idsolicitudesordenes', $id)
                ->update([
                    'estado' => 'aprobada',
                    'fechaaprobacion' => now(),
                    'idaprobador' => auth()->id()
                ]);
        }

        DB::commit();

        Log::info("? Repuesto procesado individualmente - Artículo: {$articuloId}, Cantidad: {$cantidadSolicitada}, Ticket: {$numeroTicket}, Solicitud: {$solicitud->codigo}, Destinatario: {$nombreDestinatario} ({$tipoEntrega})");

        return response()->json([
            'success' => true,
            'message' => "Repuesto procesado correctamente. Entregado a: {$nombreDestinatario}",
            'todos_procesados' => $todosProcesados,
            'numero_ticket' => $numeroTicket,
            'codigo_solicitud' => $solicitud->codigo,
            'destinatario' => $nombreDestinatario,
            'tipo_entrega' => $tipoEntrega
        ], 200, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al procesar repuesto individual: ' . $e->getMessage());
        Log::error('File: ' . $e->getFile());
        Log::error('Line: ' . $e->getLine());
        Log::error('Trace: ' . $e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Error al procesar el repuesto: ' . $e->getMessage()
        ], 500, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }
}


    public function opcionesProvincia($id)
    {
        // Obtener la solicitud con sus repuestos
        $solicitud = DB::table('solicitudesordenes as so')
            ->select(
                'so.idsolicitudesordenes',
                'so.codigo',
                'so.estado',
                'so.tiposervicio',
                'so.niveldeurgencia',
                'so.fechacreacion',
                'so.fecharequerida',
                'so.observaciones',
                'so.cantidad',
                'so.totalcantidadproductos',
                'so.idticket',
                'so.idUsuario',
                'so.idTecnico',
                't.numero_ticket'
            )
            ->leftJoin('tickets as t', 'so.idticket', '=', 't.idTickets')
            ->where('so.idsolicitudesordenes', $id)
            ->where('so.tipoorden', 'solicitud_repuesto_provincia') // Cambio importante aquí
            ->first();

        if (!$solicitud) {
            abort(404, 'Solicitud no encontrada');
        }

        // Obtener información del solicitante
        $solicitante = DB::table('usuarios')
            ->select('idUsuario', 'Nombre', 'apellidoPaterno', 'apellidoMaterno', 'correo')
            ->where('idUsuario', $solicitud->idUsuario)
            ->where('estado', 1)
            ->first();

        // Obtener los repuestos de la solicitud
        $repuestos = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idordenesarticulos',
                'oa.cantidad as cantidad_solicitada',
                'oa.observacion',
                'a.idArticulos',
                'a.nombre',
                'a.codigo_barras',
                'a.codigo_repuesto',
                'a.stock_total',
                'sc.nombre as tipo_repuesto'
            )
            ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->where('oa.idsolicitudesordenes', $id)
            ->get();

        // Para cada repuesto, obtener stock disponible y ubicaciones con detalle
        foreach ($repuestos as $repuesto) {
            // Obtener ubicaciones con stock detallado
            $ubicaciones = DB::table('rack_ubicacion_articulos as rua')
                ->select(
                    'rua.idRackUbicacionArticulo',
                    'rua.rack_ubicacion_id',
                    'rua.cantidad as stock_ubicacion',
                    'ru.codigo as ubicacion_codigo',
                    'r.nombre as rack_nombre'
                )
                ->join('rack_ubicaciones as ru', 'rua.rack_ubicacion_id', '=', 'ru.idRackUbicacion')
                ->leftJoin('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->where('rua.articulo_id', $repuesto->idArticulos)
                ->where('rua.cantidad', '>', 0)
                ->orderBy('rua.cantidad', 'desc')
                ->get();

            // Calcular stock total disponible
            $stockDisponible = $ubicaciones->sum('stock_ubicacion');

            // Agregar información al repuesto
            $repuesto->stock_disponible = $stockDisponible;
            $repuesto->ubicaciones_detalle = $ubicaciones;
            $repuesto->suficiente_stock = $stockDisponible >= $repuesto->cantidad_solicitada;
            $repuesto->diferencia_stock = $stockDisponible - $repuesto->cantidad_solicitada;

            // Verificar si ya fue procesado individualmente
            $repuesto->ya_procesado = DB::table('ordenesarticulos')
                ->where('idordenesarticulos', $repuesto->idordenesarticulos)
                ->where('estado', 1)
                ->exists();

            if ($repuesto->ya_procesado) {
                // Obtener información del envío a provincia
                $envioInfo = DB::table('repuestos_envios_provincia as re')
                    ->select(
                        're.transportista',
                        're.placa_vehiculo',
                        're.fecha_entrega_transporte',
                        're.foto_comprobante',
                        're.observaciones'
                    )
                    ->where('re.solicitud_id', $id)
                    ->where('re.articulo_id', $repuesto->idArticulos)
                    ->first();

                $repuesto->envio_info = $envioInfo;
            }
        }

        // Verificar si toda la solicitud puede ser atendida
        $puede_aceptar = $repuestos->every(function ($repuesto) {
            return $repuesto->suficiente_stock;
        });

        // Contar repuestos procesados y disponibles
        $repuestos_procesados = $repuestos->where('ya_procesado', true)->count();
        $repuestos_disponibles = $repuestos->where('suficiente_stock', true)->count();
        $total_repuestos = $repuestos->count();

        $puede_generar_pdf = ($repuestos_procesados == $total_repuestos) && ($total_repuestos > 0);

        return view('solicitud.solicitudrepuesto.opciones-provincia', compact(
            'solicitud',
            'repuestos',
            'puede_aceptar',
            'repuestos_procesados',
            'repuestos_disponibles',
            'total_repuestos',
            'solicitante',
            'puede_generar_pdf'
        ));
    }



   public function aceptarProvinciaIndividual(Request $request, $id)
{
    try {
        DB::beginTransaction();

        $solicitud = DB::table('solicitudesordenes')
            ->select('idsolicitudesordenes', 'codigo', 'estado', 'tipoorden', 'idUsuario', 'numeroTicket')
            ->where('idsolicitudesordenes', $id)
            ->where('tipoorden', 'solicitud_repuesto_provincia')
            ->first();

        if (!$solicitud) {
            return response()->json([
                'success' => false,
                'message' => 'Solicitud no encontrada'
            ], 404, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        $articuloId = (int)$request->input('articulo_id');
        $ubicacionId = (int)$request->input('ubicacion_id');
        $transportista = $request->input('transportista');
        $placaVehiculo = $request->input('placa_vehiculo');
        $fechaEntregaTransporte = $request->input('fecha_entrega_transporte');
        $observaciones = $request->input('observaciones');

        if (!$articuloId || !$ubicacionId || !$transportista || !$placaVehiculo || !$fechaEntregaTransporte) {
            return response()->json([
                'success' => false,
                'message' => 'Datos incompletos para procesar el envío'
            ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        // (si realmente lo necesitas por tu BD legacy)
        $transportista = mb_convert_encoding($transportista, 'UTF-8', 'ISO-8859-1');
        $placaVehiculo = mb_convert_encoding($placaVehiculo, 'UTF-8', 'ISO-8859-1');
        $observaciones = $observaciones ? mb_convert_encoding($observaciones, 'UTF-8', 'ISO-8859-1') : null;

        $repuesto = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idordenesarticulos',
                'oa.cantidad',
                'a.idArticulos',
                'a.nombre',
                'a.stock_total'
            )
            ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
            ->where('oa.idsolicitudesordenes', $id)
            ->where('a.idArticulos', $articuloId)
            ->first();

        if (!$repuesto) {
            return response()->json([
                'success' => false,
                'message' => 'Repuesto no encontrado en la solicitud'
            ], 404, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        $nombreRepuesto = mb_convert_encoding($repuesto->nombre, 'UTF-8', 'ISO-8859-1');

        $numeroTicket = $solicitud->numeroTicket ?? 'N/A';

        $yaProcesado = DB::table('ordenesarticulos')
            ->where('idordenesarticulos', $repuesto->idordenesarticulos)
            ->where('estado', 1)
            ->exists();

        if ($yaProcesado) {
            return response()->json([
                'success' => false,
                'message' => "Este repuesto ({$nombreRepuesto}) ya fue procesado para envío"
            ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        $cantidadSolicitada = (int)$repuesto->cantidad;

        $stockUbicacion = DB::table('rack_ubicacion_articulos as rua')
            ->select(
                'rua.cantidad',
                'rua.idRackUbicacionArticulo',
                'ru.codigo as ubicacion_codigo',
                'ru.idRackUbicacion',
                'r.idRack as rack_id',
                'r.nombre as rack_nombre',
                'rua.cliente_general_id'
            )
            ->join('rack_ubicaciones as ru', 'rua.rack_ubicacion_id', '=', 'ru.idRackUbicacion')
            ->leftJoin('racks as r', 'ru.rack_id', '=', 'r.idRack')
            ->where('rua.articulo_id', $articuloId)
            ->where('rua.rack_ubicacion_id', $ubicacionId)
            ->first();

        if (!$stockUbicacion) {
            return response()->json([
                'success' => false,
                'message' => "Ubicación no encontrada para el repuesto: {$nombreRepuesto}"
            ], 404, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        if ((int)$stockUbicacion->cantidad < $cantidadSolicitada) {
            $ubicacionCodigo = mb_convert_encoding($stockUbicacion->ubicacion_codigo, 'UTF-8', 'ISO-8859-1');
            return response()->json([
                'success' => false,
                'message' => "Stock insuficiente en la ubicación seleccionada para: {$nombreRepuesto}. Ubicación: {$ubicacionCodigo}, Disponible: {$stockUbicacion->cantidad}, Solicitado: {$cantidadSolicitada}"
            ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        $articuloInfo = DB::table('articulos')
            ->select('precio_compra', 'precio_venta')
            ->where('idArticulos', $articuloId)
            ->first();

        if (!$articuloInfo) {
            return response()->json([
                'success' => false,
                'message' => 'Información del artículo no encontrada'
            ], 404, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        $ubicacionCodigo = mb_convert_encoding($stockUbicacion->ubicacion_codigo, 'UTF-8', 'ISO-8859-1');
        $rackNombre = mb_convert_encoding($stockUbicacion->rack_nombre, 'UTF-8', 'ISO-8859-1');

        // Foto comprobante
        $fotoComprobantePath = null;
        if ($request->hasFile('foto_comprobante')) {
            $file = $request->file('foto_comprobante');
            $fileName = 'comprobante_' . time() . '_' . $solicitud->codigo . '_' . $articuloId . '.' . $file->getClientOriginalExtension();
            $fotoComprobantePath = $file->storeAs('comprobantes_envios', $fileName, 'public');
        }

        // 1) Descontar de rack_ubicacion_articulos (por PK)
        DB::table('rack_ubicacion_articulos')
            ->where('idRackUbicacionArticulo', $stockUbicacion->idRackUbicacionArticulo)
            ->decrement('cantidad', $cantidadSolicitada);

        // ? 1.1) Descontar de CAJAS si existen
        $this->descontarDeCajasSiExisten(
            (int)$articuloId,
            (int)$ubicacionId,
            (int)$cantidadSolicitada,
            null
        );

        // 2) Movimiento
        $observacionesMovimiento = mb_convert_encoding(
            "Envío a provincia: {$solicitud->codigo} - Ticket: {$numeroTicket} - Transportista: {$transportista} - Placa: {$placaVehiculo} - Artículo: {$nombreRepuesto}",
            'UTF-8',
            'ISO-8859-1'
        );

        DB::table('rack_movimientos')->insert([
            'articulo_id' => $articuloId,
            'custodia_id' => null,
            'ubicacion_origen_id' => $ubicacionId,
            'ubicacion_destino_id' => null,
            'rack_origen_id' => $stockUbicacion->rack_id,
            'rack_destino_id' => null,
            'cantidad' => $cantidadSolicitada,
            'tipo_movimiento' => 'salida_provincia',
            'usuario_id' => auth()->id(),
            'observaciones' => $observacionesMovimiento,
            'codigo_ubicacion_origen' => $ubicacionCodigo,
            'codigo_ubicacion_destino' => null,
            'nombre_rack_origen' => $rackNombre,
            'nombre_rack_destino' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 3) inventario_ingresos_clientes
        DB::table('inventario_ingresos_clientes')->insert([
            'compra_id' => null,
            'articulo_id' => $articuloId,
            'tipo_ingreso' => 'salida_provincia',
            'ingreso_id' => $solicitud->idsolicitudesordenes,
            'cliente_general_id' => $stockUbicacion->cliente_general_id,
            'numero_orden' => $numeroTicket,
            'codigo_solicitud' => $solicitud->codigo,
            'cantidad' => -$cantidadSolicitada,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 4) repuestos_envios_provincia
        DB::table('repuestos_envios_provincia')->insert([
            'solicitud_id' => $solicitud->idsolicitudesordenes,
            'articulo_id' => $articuloId,
            'transportista' => $transportista,
            'placa_vehiculo' => $placaVehiculo,
            'fecha_entrega_transporte' => $fechaEntregaTransporte,
            'foto_comprobante' => $fotoComprobantePath,
            'observaciones' => $observaciones,
            'usuario_entrego_id' => auth()->id(),
            'ubicacion_origen' => $ubicacionCodigo,
            'rack_origen' => $rackNombre,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 5) Stock total
        DB::table('articulos')
            ->where('idArticulos', $articuloId)
            ->decrement('stock_total', $cantidadSolicitada);

        // 6) Kardex
        $this->actualizarKardexSalida(
            (int)$articuloId,
            (int)$stockUbicacion->cliente_general_id,
            (int)$cantidadSolicitada,
            (float)$articuloInfo->precio_compra
        );

        // 7) Marcar procesado
        $observacion = mb_convert_encoding(
            "Envío a provincia - Ubicación: {$ubicacionCodigo} - Ticket: {$numeroTicket} - Transportista: {$transportista} - Placa: {$placaVehiculo} - Fecha entrega transporte: {$fechaEntregaTransporte} - Artículo: {$nombreRepuesto}",
            'UTF-8',
            'ISO-8859-1'
        );

        DB::table('ordenesarticulos')
            ->where('idordenesarticulos', $repuesto->idordenesarticulos)
            ->update([
                'estado' => 1,
                'observacion' => $observacion
            ]);

        // Completar solicitud si ya no quedan pendientes
        $repuestosPendientes = DB::table('ordenesarticulos')
            ->where('idsolicitudesordenes', $id)
            ->where('estado', 0)
            ->count();

        $todosProcesados = ($repuestosPendientes == 0);

        if ($todosProcesados) {
            DB::table('solicitudesordenes')
                ->where('idsolicitudesordenes', $id)
                ->update([
                    'estado' => 'aprobada',
                    'fechaaprobacion' => now(),
                    'idaprobador' => auth()->id()
                ]);
        }

        DB::commit();

        Log::info("? Repuesto procesado para envío a provincia - Artículo: {$articuloId} ({$nombreRepuesto}), Cantidad: {$cantidadSolicitada}, Ticket: {$numeroTicket}, Transportista: {$transportista}, Placa: {$placaVehiculo}, Ubicación: {$ubicacionCodigo}");

        return response()->json([
            'success' => true,
            'message' => "Repuesto preparado para envío a provincia. Transportista: {$transportista}, Artículo: {$nombreRepuesto}",
            'todos_procesados' => $todosProcesados,
            'numero_ticket' => $numeroTicket,
            'codigo_solicitud' => $solicitud->codigo,
            'transportista' => $transportista,
            'placa_vehiculo' => $placaVehiculo,
            'articulo' => $nombreRepuesto,
            'ubicacion' => $ubicacionCodigo,
            'cantidad' => $cantidadSolicitada
        ], 200, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al procesar envío a provincia individual: ' . $e->getMessage());
        Log::error('File: ' . $e->getFile());
        Log::error('Line: ' . $e->getLine());
        Log::error('Trace: ' . $e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Error al procesar el envío: ' . $e->getMessage()
        ], 500, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }
}











    private function actualizarKardexSalida($articuloId, $clienteGeneralId, $cantidadSalida, $costoUnitario)
    {
        try {
            // Obtener el mes y año actual
            $fechaActual = now();
            $mesActual = $fechaActual->format('m');
            $anioActual = $fechaActual->format('Y');

            Log::info("?? Procesando kardex para mes: {$mesActual}, año: {$anioActual}");

            // Buscar si existe un registro de kardex para este artículo, cliente y mes actual
            $kardexMesActual = DB::table('kardex')
                ->where('idArticulo', $articuloId)
                ->where('cliente_general_id', $clienteGeneralId)
                ->whereMonth('fecha', $mesActual)
                ->whereYear('fecha', $anioActual)
                ->first();

            if ($kardexMesActual) {
                Log::info("? Kardex del mes actual encontrado - ID: {$kardexMesActual->id}, actualizando...");

                // ACTUALIZAR registro existente del mes
                $nuevoInventarioActual = $kardexMesActual->inventario_actual - $cantidadSalida;
                $nuevoCostoInventario = max(0, $kardexMesActual->costo_inventario - ($cantidadSalida * $costoUnitario));

                DB::table('kardex')
                    ->where('id', $kardexMesActual->id)
                    ->update([
                        'unidades_salida' => $kardexMesActual->unidades_salida + $cantidadSalida,
                        'costo_unitario_salida' => $costoUnitario,
                        'inventario_actual' => $nuevoInventarioActual,
                        'costo_inventario' => $nuevoCostoInventario,
                        'updated_at' => now()
                    ]);

                Log::info("? Kardex actualizado - Salidas: " . ($kardexMesActual->unidades_salida + $cantidadSalida) .
                    ", Inventario: {$nuevoInventarioActual}, Costo: {$nuevoCostoInventario}");
            } else {
                Log::info("?? No hay kardex para este mes, creando nuevo registro...");

                // Obtener el último registro de kardex (de cualquier mes) para calcular inventario inicial
                $ultimoKardex = DB::table('kardex')
                    ->where('idArticulo', $articuloId)
                    ->where('cliente_general_id', $clienteGeneralId)
                    ->orderBy('fecha', 'desc')
                    ->orderBy('id', 'desc')
                    ->first();

                // Calcular valores iniciales para el nuevo mes
                $inventarioInicial = $ultimoKardex ? $ultimoKardex->inventario_actual : 0;
                $inventarioActual = $inventarioInicial - $cantidadSalida;

                // Calcular costo del inventario
                $costoInventarioAnterior = $ultimoKardex ? $ultimoKardex->costo_inventario : 0;
                $costoInventarioActual = max(0, $costoInventarioAnterior - ($cantidadSalida * $costoUnitario));

                Log::info("?? Valores calculados - Inicial: {$inventarioInicial}, Actual: {$inventarioActual}, " .
                    "Costo anterior: {$costoInventarioAnterior}, Costo actual: {$costoInventarioActual}");

                // CREAR nuevo registro de kardex para el nuevo mes
                DB::table('kardex')->insert([
                    'fecha' => $fechaActual->format('Y-m-d'),
                    'idArticulo' => $articuloId,
                    'cliente_general_id' => $clienteGeneralId,
                    'unidades_entrada' => 0,
                    'costo_unitario_entrada' => 0,
                    'unidades_salida' => $cantidadSalida,
                    'costo_unitario_salida' => $costoUnitario,
                    'inventario_inicial' => $inventarioInicial,
                    'inventario_actual' => $inventarioActual,
                    'costo_inventario' => $costoInventarioActual,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                Log::info("? Nuevo kardex creado para el mes - Artículo: {$articuloId}, Cliente: {$clienteGeneralId}");
            }

            Log::info("? Kardex procesado correctamente - Artículo: {$articuloId}, Salida: {$cantidadSalida}");
        } catch (\Exception $e) {
            Log::error('? Error al actualizar kardex para salida: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            throw $e;
        }
    }


    public function generarConformidadProvincia($id)
    {
        try {
            // Obtener la solicitud con información completa
            $solicitud = DB::table('solicitudesordenes as so')
                ->select(
                    'so.idsolicitudesordenes',
                    'so.codigo',
                    'so.tiposervicio',
                    'so.niveldeurgencia',
                    'so.fechacreacion',
                    'so.fecharequerida',
                    'so.fechaaprobacion',
                    'so.observaciones',
                    'so.cantidad',
                    'so.totalcantidadproductos',
                    'so.idUsuario',
                    'so.idTecnico',
                    'so.estado',
                    'so.numeroTicket',
                    'u_solicitante.Nombre as solicitante_nombre',
                    'u_solicitante.apellidoPaterno as solicitante_apellido',
                    'u_solicitante.documento as solicitante_documento',
                    'u_aprobador.Nombre as aprobador_nombre',
                    'u_aprobador.apellidoPaterno as aprobador_apellido'
                )
                ->leftJoin('usuarios as u_solicitante', 'so.idUsuario', '=', 'u_solicitante.idUsuario')
                ->leftJoin('usuarios as u_aprobador', 'so.idaprobador', '=', 'u_aprobador.idUsuario')
                ->where('so.idsolicitudesordenes', $id)
                ->where('so.tipoorden', 'solicitud_repuesto_provincia')
                ->first();

            if (!$solicitud) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solicitud de provincia no encontrada'
                ], 404);
            }

            // Verificar que todos los repuestos estén procesados
            $repuestosPendientes = DB::table('ordenesarticulos')
                ->where('idsolicitudesordenes', $id)
                ->where('estado', 0)
                ->count();

            if ($repuestosPendientes > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede generar la conformidad: aún hay repuestos pendientes de envío'
                ], 400);
            }

            // Verificar que la solicitud esté aprobada
            if ($solicitud->estado != 'aprobada') {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede generar la conformidad: la solicitud no ha sido aprobada'
                ], 400);
            }

            // Obtener información del envío a provincia
            $envioInfo = DB::table('repuestos_envios_provincia as re')
                ->select(
                    're.transportista',
                    're.placa_vehiculo',
                    're.fecha_entrega_transporte',
                    're.foto_comprobante',
                    're.observaciones',
                    'u_entrego.Nombre as usuario_entrego_nombre',
                    'u_entrego.apellidoPaterno as usuario_entrego_apellido'
                )
                ->leftJoin('usuarios as u_entrego', 're.usuario_entrego_id', '=', 'u_entrego.idUsuario')
                ->where('re.solicitud_id', $id)
                ->orderBy('re.created_at', 'desc')
                ->first();

            // AGREGAR información del envío al objeto de solicitud
            $solicitud->envio_info = $envioInfo;

            // Obtener repuestos enviados - CONSULTA SIMPLIFICADA
            $repuestos = DB::table('ordenesarticulos as oa')
                ->select(
                    'oa.cantidad',
                    'a.nombre as repuesto_nombre',
                    'a.codigo_barras',
                    'a.codigo_repuesto',
                    'sc.nombre as tipo_repuesto'
                )
                ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
                ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
                ->where('oa.idsolicitudesordenes', $id)
                ->where('oa.estado', 1)
                ->get();

            // Verificar que hay repuestos procesados
            if ($repuestos->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay repuestos procesados para generar la conformidad'
                ], 400);
            }

            // Para cada repuesto, buscar la ubicación de origen desde rack_movimientos
            foreach ($repuestos as $repuesto) {
                $ubicacion = DB::table('rack_movimientos')
                    ->select('codigo_ubicacion_origen')
                    ->where('articulo_id', function ($query) use ($repuesto, $id) {
                        $query->select('idArticulos')
                            ->from('articulos')
                            ->where('nombre', $repuesto->repuesto_nombre)
                            ->limit(1);
                    })
                    ->where('tipo_movimiento', 'salida_provincia')
                    ->where('observaciones', 'like', '%' . $solicitud->codigo . '%')
                    ->orderBy('created_at', 'desc')
                    ->first();

                $repuesto->ubicacion_utilizada = $ubicacion ? $ubicacion->codigo_ubicacion_origen : 'N/A';
            }

            // Datos estáticos de la empresa
            $empresa = (object) [
                'nombre_empresa' => 'GKM TECHNOLOGY',
                'direccion' => 'Av. Principal 123',
                'telefono' => '9999',
                'ruc' => '000000',
                'logo' => null
            ];

            // Generar PDF específico para provincia
            $pdf = \PDF::loadView('solicitud.solicitudrepuesto.pdf.conformidad_provincia', [
                'solicitud' => $solicitud,
                'repuestos' => $repuestos,
                'empresa' => $empresa,
                'fecha_generacion' => now()->format('d/m/Y H:i')
            ]);

            $nombreArchivo = 'conformidad_envio_provincia_' . $solicitud->codigo . '_' . now()->format('Ymd_His') . '.pdf';

            return $pdf->download($nombreArchivo);
        } catch (\Exception $e) {
            Log::error('Error al generar PDF de conformidad de envío a provincia: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error al generar el PDF: ' . $e->getMessage()
            ], 500);
        }
    }

/**
 * Descuenta unidades desde la tabla `cajas` si existen cajas para:
 *  - idArticulo = $articuloId
 *  - idubicaciones_rack = $ubicacionRackId (FK hacia rack_ubicaciones.idRackUbicacion)
 *
 * Reglas:
 *  - Si NO hay cajas, no hace nada (silencioso).
 *  - Solo considera cajas "abierta" (puedes incluir "cerrada" si tu negocio lo requiere).
 *  - Descuenta primero las cajas más antiguas (FIFO por fecha_entrada).
 *  - No permite dejar cantidad_actual negativa.
 *  - Si hay cajas pero no alcanza cantidad_actual total, lanza excepción (rollback).
 *
 * @throws \Exception
 */



private function descontarDeCajasSiExisten(int $articuloId, int $ubicacionRackId, int $cantidad, ?int $tipoArticuloId = null): void
{
    if ($cantidad <= 0) {
        return;
    }

    // Base query: cajas del artículo en esa ubicación
    $q = DB::table('cajas')
        ->where('idArticulo', $articuloId)
        ->where('idubicaciones_rack', $ubicacionRackId)
        ->where('cantidad_actual', '>', 0)
        ->where('estado', 'abierta'); // si quieres incluir cerradas: ->whereIn('estado',['abierta','cerrada'])

    // (Opcional) Si tu data mezcla tipos por artículo y quieres evitarlo
    if (!is_null($tipoArticuloId)) {
        $q->where('idTipoArticulo', $tipoArticuloId);
    }

    // Si no hay cajas: no hacemos nada (regla pedida)
    $existenCajas = (clone $q)->exists();
    if (!$existenCajas) {
        return;
    }

    // Traer cajas ordenadas (FIFO)
    // lockForUpdate para evitar carreras dentro de la misma transacción
    $cajas = (clone $q)
        ->select('idCaja', 'cantidad_actual')
        ->orderBy('fecha_entrada', 'asc')
        ->orderBy('idCaja', 'asc')
        ->lockForUpdate()
        ->get();

    $disponibleTotal = 0;
    foreach ($cajas as $c) {
        $disponibleTotal += (int)$c->cantidad_actual;
    }

    if ($disponibleTotal < $cantidad) {
        // OJO: aquí lanzo excepción para que el método que llama haga rollback
        throw new \Exception(
            "Stock en cajas insuficiente para descontar. Articulo={$articuloId}, UbicacionRack={$ubicacionRackId}, " .
            "DisponibleEnCajas={$disponibleTotal}, Solicitado={$cantidad}"
        );
    }

    // Descontar progresivamente
    $restante = $cantidad;

    foreach ($cajas as $caja) {
        if ($restante <= 0) break;

        $actual = (int)$caja->cantidad_actual;
        if ($actual <= 0) continue;

        $descuento = min($actual, $restante);
        $nuevo = $actual - $descuento;

        DB::table('cajas')
            ->where('idCaja', (int)$caja->idCaja)
            ->update([
                'cantidad_actual' => $nuevo,
            ]);

        $restante -= $descuento;
    }

    // Por seguridad (no debería pasar por la validación previa)
    if ($restante > 0) {
        throw new \Exception(
            "No se logró descontar completamente de cajas. Restante={$restante} (Articulo={$articuloId}, UbicacionRack={$ubicacionRackId})"
        );
    }
}




public function aceptarProvincia(Request $request, $id)
{
    try {
        DB::beginTransaction();

        $solicitud = DB::table('solicitudesordenes')
            ->select('idsolicitudesordenes', 'codigo', 'estado', 'tipoorden', 'idUsuario', 'numeroTicket')
            ->where('idsolicitudesordenes', $id)
            ->where('tipoorden', 'solicitud_repuesto_provincia')
            ->first();

        if (!$solicitud) {
            return response()->json([
                'success' => false,
                'message' => 'Solicitud no encontrada'
            ], 404, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        if ($solicitud->estado == 'aprobada') {
            return response()->json([
                'success' => false,
                'message' => 'Esta solicitud ya ha sido aprobada anteriormente'
            ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        $ubicacionesSeleccionadas = json_decode($request->input('ubicaciones'), true);
        $transportista = $request->input('transportista');
        $placaVehiculo = $request->input('placa_vehiculo');
        $fechaEntregaTransporte = $request->input('fecha_entrega_transporte');
        $observaciones = $request->input('observaciones');

        if (empty($ubicacionesSeleccionadas) || !$transportista || !$placaVehiculo || !$fechaEntregaTransporte) {
            return response()->json([
                'success' => false,
                'message' => 'Datos incompletos para procesar el envío grupal'
            ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
        }

        $numeroTicket = $solicitud->numeroTicket ?? 'N/A';

        $repuestosSolicitud = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idordenesarticulos',
                'oa.cantidad',
                'a.idArticulos',
                'a.nombre',
                'a.stock_total'
            )
            ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
            ->where('oa.idsolicitudesordenes', $id)
            ->get();

        foreach ($repuestosSolicitud as $repuesto) {
            $stockDisponible = DB::table('rack_ubicacion_articulos')
                ->where('articulo_id', $repuesto->idArticulos)
                ->sum('cantidad');

            if ($stockDisponible < $repuesto->cantidad) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock insuficiente para el repuesto: {$repuesto->nombre}. Disponible: {$stockDisponible}, Solicitado: {$repuesto->cantidad}"
                ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }
        }

        // Foto comprobante (una para todos)
        $fotoComprobantePath = null;
        if ($request->hasFile('foto_comprobante')) {
            $file = $request->file('foto_comprobante');
            $fileName = 'comprobante_grupal_' . time() . '_' . $solicitud->codigo . '.' . $file->getClientOriginalExtension();
            $fotoComprobantePath = $file->storeAs('comprobantes_envios', $fileName, 'public');
        }

        foreach ($repuestosSolicitud as $repuesto) {
            $cantidadSolicitada = (int)$repuesto->cantidad;
            $ubicacionId = $ubicacionesSeleccionadas[$repuesto->idArticulos] ?? null;

            if (!$ubicacionId) {
                return response()->json([
                    'success' => false,
                    'message' => "No se seleccionó ubicación para el repuesto: {$repuesto->nombre}"
                ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }

            $stockUbicacion = DB::table('rack_ubicacion_articulos as rua')
                ->select(
                    'rua.cantidad',
                    'rua.idRackUbicacionArticulo',
                    'rua.cliente_general_id',
                    'ru.codigo as ubicacion_codigo',
                    'ru.idRackUbicacion',
                    'r.idRack as rack_id',
                    'r.nombre as rack_nombre'
                )
                ->join('rack_ubicaciones as ru', 'rua.rack_ubicacion_id', '=', 'ru.idRackUbicacion')
                ->leftJoin('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->where('rua.articulo_id', $repuesto->idArticulos)
                ->where('rua.rack_ubicacion_id', $ubicacionId)
                ->first();

            if (!$stockUbicacion) {
                return response()->json([
                    'success' => false,
                    'message' => "Ubicación no encontrada para el repuesto: {$repuesto->nombre}"
                ], 404, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }

            if ((int)$stockUbicacion->cantidad < $cantidadSolicitada) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock insuficiente en la ubicación seleccionada para: {$repuesto->nombre}. Ubicación: {$stockUbicacion->ubicacion_codigo}, Disponible: {$stockUbicacion->cantidad}, Solicitado: {$cantidadSolicitada}"
                ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }

            $yaProcesado = DB::table('ordenesarticulos')
                ->where('idordenesarticulos', $repuesto->idordenesarticulos)
                ->where('estado', 1)
                ->exists();

            if ($yaProcesado) {
                return response()->json([
                    'success' => false,
                    'message' => "El repuesto {$repuesto->nombre} ya fue procesado anteriormente"
                ], 400, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
            }

            $articuloInfo = DB::table('articulos')
                ->select('precio_compra', 'precio_venta')
                ->where('idArticulos', $repuesto->idArticulos)
                ->first();

            // 1) Descontar stock en ubicación (por PK)
            DB::table('rack_ubicacion_articulos')
                ->where('idRackUbicacionArticulo', $stockUbicacion->idRackUbicacionArticulo)
                ->decrement('cantidad', $cantidadSolicitada);

            // ? 1.1) Descontar de CAJAS si existen
            $this->descontarDeCajasSiExisten(
                (int)$repuesto->idArticulos,
                (int)$ubicacionId,
                (int)$cantidadSolicitada,
                null
            );

            // 2) Stock total
            DB::table('articulos')
                ->where('idArticulos', $repuesto->idArticulos)
                ->decrement('stock_total', $cantidadSolicitada);

            // 3) Movimiento
            DB::table('rack_movimientos')->insert([
                'articulo_id' => $repuesto->idArticulos,
                'custodia_id' => null,
                'ubicacion_origen_id' => $ubicacionId,
                'ubicacion_destino_id' => null,
                'rack_origen_id' => $stockUbicacion->rack_id,
                'rack_destino_id' => null,
                'cantidad' => $cantidadSolicitada,
                'tipo_movimiento' => 'salida_provincia',
                'usuario_id' => auth()->id(),
                'observaciones' => "Envío a provincia (grupal): {$solicitud->codigo} - Ticket: {$numeroTicket} - Transportista: {$transportista}",
                'codigo_ubicacion_origen' => $stockUbicacion->ubicacion_codigo,
                'codigo_ubicacion_destino' => null,
                'nombre_rack_origen' => $stockUbicacion->rack_nombre,
                'nombre_rack_destino' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 4) inventario_ingresos_clientes
            DB::table('inventario_ingresos_clientes')->insert([
                'compra_id' => null,
                'articulo_id' => $repuesto->idArticulos,
                'tipo_ingreso' => 'salida_provincia',
                'ingreso_id' => $solicitud->idsolicitudesordenes,
                'cliente_general_id' => $stockUbicacion->cliente_general_id,
                'numero_orden' => $numeroTicket,
                'codigo_solicitud' => $solicitud->codigo,
                'cantidad' => -$cantidadSolicitada,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 5) repuestos_envios_provincia
            DB::table('repuestos_envios_provincia')->insert([
                'solicitud_id' => $solicitud->idsolicitudesordenes,
                'articulo_id' => $repuesto->idArticulos,
                'transportista' => $transportista,
                'placa_vehiculo' => $placaVehiculo,
                'fecha_entrega_transporte' => $fechaEntregaTransporte,
                'foto_comprobante' => $fotoComprobantePath,
                'observaciones' => $observaciones,
                'usuario_entrego_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 6) Kardex
            $this->actualizarKardexSalida(
                (int)$repuesto->idArticulos,
                (int)$stockUbicacion->cliente_general_id,
                (int)$cantidadSolicitada,
                (float)$articuloInfo->precio_compra
            );

            // 7) Marcar procesado
            DB::table('ordenesarticulos')
                ->where('idordenesarticulos', $repuesto->idordenesarticulos)
                ->update([
                    'estado' => 1,
                    'observacion' => "Envío a provincia (grupal) - Ubicación: {$stockUbicacion->ubicacion_codigo} - Ticket: {$numeroTicket} - Transportista: {$transportista} - Placa: {$placaVehiculo}"
                ]);

            Log::info("? Repuesto procesado para envío grupal a provincia - Artículo: {$repuesto->idArticulos}, Cantidad: {$cantidadSolicitada}, Transportista: {$transportista}, Ticket: {$numeroTicket}");
        }

        DB::table('solicitudesordenes')
            ->where('idsolicitudesordenes', $id)
            ->update([
                'estado' => 'aprobada',
                'fechaaprobacion' => now(),
                'idaprobador' => auth()->id()
            ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => "Solicitud de repuestos para provincia aprobada correctamente. Todos los repuestos preparados para envío con transportista: {$transportista}. Ticket: {$numeroTicket}"
        ], 200, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al aceptar solicitud de repuestos para provincia (grupal): ' . $e->getMessage());
        Log::error('Trace: ' . $e->getTraceAsString());

        return response()->json([
            'success' => false,
            'message' => 'Error al aceptar la solicitud: ' . $e->getMessage()
        ], 500, ['Content-Type' => 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }
}



public function marcarListoIndividual(Request $request, $id)
{
    try {
        DB::beginTransaction();

        $solicitud = DB::table('solicitudesordenes')
            ->select('idsolicitudesordenes', 'codigo', 'estado', 'tipoorden', 'idUsuario', 'idTecnico')
            ->where('idsolicitudesordenes', $id)
            ->where('tipoorden', 'solicitud_repuesto')
            ->first();

        if (!$solicitud) {
            return response()->json([
                'success' => false,
                'message' => 'Solicitud no encontrada'
            ], 404);
        }

        $articuloId = (int)$request->input('articulo_id');
        $ubicacionId = (int)$request->input('ubicacion_id');

        // Verificar si ya fue marcado como listo para entregar
        $yaMarcadoListo = DB::table('repuestos_entregas')
            ->where('solicitud_id', $id)
            ->where('articulo_id', $articuloId)
            ->where('estado', 'pendiente_entrega')
            ->exists();

        if ($yaMarcadoListo) {
            return response()->json([
                'success' => false,
                'message' => 'Este repuesto ya está marcado como listo para entregar'
            ], 400);
        }

        // Obtener información del repuesto
        $repuesto = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idordenesarticulos',
                'oa.cantidad',
                'oa.idticket',
                'a.idArticulos',
                'a.nombre',
                'a.stock_total'
            )
            ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
            ->where('oa.idsolicitudesordenes', $id)
            ->where('a.idArticulos', $articuloId)
            ->first();

        if (!$repuesto) {
            return response()->json([
                'success' => false,
                'message' => 'Repuesto no encontrado en la solicitud'
            ], 404);
        }

        // Verificar stock disponible en la ubicación
        $stockUbicacion = DB::table('rack_ubicacion_articulos as rua')
            ->select(
                'rua.cantidad',
                'ru.codigo as ubicacion_codigo',
                'r.nombre as rack_nombre',
                'rua.cliente_general_id'
            )
            ->join('rack_ubicaciones as ru', 'rua.rack_ubicacion_id', '=', 'ru.idRackUbicacion')
            ->leftJoin('racks as r', 'ru.rack_id', '=', 'r.idRack')
            ->where('rua.articulo_id', $articuloId)
            ->where('rua.rack_ubicacion_id', $ubicacionId)
            ->first();

        if (!$stockUbicacion) {
            return response()->json([
                'success' => false,
                'message' => 'Ubicación no encontrada para este repuesto'
            ], 404);
        }

        if ((int)$stockUbicacion->cantidad < (int)$repuesto->cantidad) {
            return response()->json([
                'success' => false,
                'message' => "Stock insuficiente en la ubicación seleccionada. Disponible: {$stockUbicacion->cantidad}, Solicitado: {$repuesto->cantidad}"
            ], 400);
        }

        // Obtener número de ticket
        $ticketInfo = DB::table('tickets')
            ->select('numero_ticket')
            ->where('idTickets', $repuesto->idticket)
            ->first();
        $numeroTicket = $ticketInfo->numero_ticket ?? 'N/A';

        // 1. Registrar en repuestos_entregas con estado 'pendiente_entrega'
        DB::table('repuestos_entregas')->insert([
            'solicitud_id' => $solicitud->idsolicitudesordenes,
            'articulo_id' => $articuloId,
            'usuario_destino_id' => $solicitud->idTecnico,
            'tipo_entrega' => 'tecnico',
            'cantidad' => $repuesto->cantidad,
            'ubicacion_utilizada' => $stockUbicacion->ubicacion_codigo,
            'ubicacion_id' => $ubicacionId,
            'numero_ticket' => $numeroTicket,
            'usuario_preparo_id' => auth()->id(),
            'estado' => 'pendiente_entrega',
            'observaciones' => "Marcado como listo para entregar al técnico",
            'fecha_preparacion' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 2. Marcar en ordenesarticulos con estado 2 (listo para entregar)
        DB::table('ordenesarticulos')
            ->where('idordenesarticulos', $repuesto->idordenesarticulos)
            ->update([
                'estado' => 2,
                'observacion' => "Listo para entregar al técnico - Ubicación: {$stockUbicacion->ubicacion_codigo}",
                'updated_at' => now()
            ]);

        // 3. ✅ ACTUALIZAR ESTADO DE LA SOLICITUD A "listo_para_entregar"
        // REGLA: Si hay al menos un repuesto listo, la solicitud está "listo_para_entregar"
        DB::table('solicitudesordenes')
            ->where('idsolicitudesordenes', $id)
            ->update([
                'estado' => 'listo_para_entregar',
                'fechaactualizacion' => now(),
                'updated_at' => now()
            ]);

        // 4. ✅ INSERTAR EN NOTIFICACIONES_SOLICITUD
        // Verificar si ya existe una notificación para esta solicitud
        $notificacionExistente = DB::table('notificaciones_solicitud')
            ->where('idSolicitudesOrdenes', $id)
            ->first();

        if ($notificacionExistente) {
            // Si ya existe, actualizar
            DB::table('notificaciones_solicitud')
                ->where('idNotificacionSolicitud', $notificacionExistente->idNotificacionSolicitud)
                ->update([
                    'estado_web' => 1,
                    'estado_app' => 0,
                    'fecha' => now(),
                    'updated_at' => now()
                ]);
        } else {
            // Si no existe, crear nueva
            DB::table('notificaciones_solicitud')->insert([
                'idSolicitudesOrdenes' => $id,
                'estado_web' => 1,
                'estado_app' => 0,
                'fecha' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 5. Registrar en logs
        Log::info("✅ Repuesto marcado como listo para entregar - Solicitud: {$solicitud->codigo}, Estado: listo_para_entregar, Artículo: {$articuloId}, Cantidad: {$repuesto->cantidad}, Ubicación: {$stockUbicacion->ubicacion_codigo}");

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => "Repuesto marcado como LISTO PARA ENTREGAR al técnico",
            'numero_ticket' => $numeroTicket,
            'codigo_solicitud' => $solicitud->codigo,
            'ubicacion' => $stockUbicacion->ubicacion_codigo,
            'estado_solicitud' => 'listo_para_entregar',
            'articulo_nombre' => $repuesto->nombre,
            'cantidad' => $repuesto->cantidad,
            'notificacion_creada' => true
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al marcar repuesto como listo para entregar: ' . $e->getMessage());
        Log::error('File: ' . $e->getFile());
        Log::error('Line: ' . $e->getLine());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al marcar el repuesto: ' . $e->getMessage()
        ], 500);
    }
}
public function confirmarEntregaFisicaConFoto(Request $request, $id)
{
    Log::info("════════════════════════════════════════════════════════");
    Log::info("🚀 INICIANDO confirmarEntregaFisicaConFoto (SIMPLIFICADO)");
    Log::info("════════════════════════════════════════════════════════");
    
    // Log de todos los datos recibidos
    Log::info("📦 DATOS RECIBIDOS EN REQUEST:");
    Log::info("- Solicitud ID: " . $id);
    Log::info("- Articulo ID: " . $request->input('articulo_id'));
    Log::info("- Observaciones: " . $request->input('observaciones'));
    Log::info("- Nombre Firmante: " . $request->input('nombre_firmante'));
    Log::info("- Fecha Firma: " . $request->input('fecha_firma'));
    Log::info("- Firma Confirmada: " . $request->input('firma_confirmada'));
    Log::info("- Tiene archivo foto: " . ($request->hasFile('foto') ? 'SÍ' : 'NO'));
    Log::info("User ID autenticado: " . (auth()->id() ?? 'No autenticado'));
    
    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        Log::info("📸 INFO ARCHIVO FOTO:");
        Log::info("  - Nombre: " . $file->getClientOriginalName());
        Log::info("  - Tamaño: " . $file->getSize() . " bytes");
        Log::info("  - MIME: " . $file->getMimeType());
        Log::info("  - Extensión: " . $file->getClientOriginalExtension());
    }

    try {
        Log::info("🔍 Buscando solicitud...");
        DB::beginTransaction();

        $solicitud = DB::table('solicitudesordenes')
            ->select('idsolicitudesordenes', 'codigo', 'estado', 'tipoorden', 'idUsuario', 'idTecnico')
            ->where('idsolicitudesordenes', $id)
            ->where('tipoorden', 'solicitud_repuesto')
            ->first();

        if (!$solicitud) {
            Log::error("❌ Solicitud no encontrada con ID: " . $id);
            return response()->json([
                'success' => false,
                'message' => 'Solicitud no encontrada'
            ], 404);
        }

        Log::info("✅ Solicitud encontrada: " . $solicitud->codigo);

        $articuloId = (int)$request->input('articulo_id');
        $observacionesEntrega = $request->input('observaciones');
        $nombreFirmante = $request->input('nombre_firmante');
        $fechaFirma = $request->input('fecha_firma');
        
        // Procesar firma_confirmada correctamente
        $firmaConfirmadaRaw = $request->input('firma_confirmada');
        $firmaConfirmada = 0;
        
        if ($firmaConfirmadaRaw === 'true' || $firmaConfirmadaRaw === true || $firmaConfirmadaRaw === '1' || $firmaConfirmadaRaw === 1) {
            $firmaConfirmada = 1;
        }

        Log::info("📊 DATOS PROCESADOS:");
        Log::info("- Articulo ID: " . $articuloId);
        Log::info("- Observaciones Entrega: " . $observacionesEntrega);
        Log::info("- Nombre Firmante: " . $nombreFirmante);
        Log::info("- Fecha Firma: " . $fechaFirma);
        Log::info("- Firma Confirmada (procesada): " . $firmaConfirmada);

        if (!$articuloId) {
            Log::error("❌ Articulo ID no proporcionado");
            return response()->json([
                'success' => false,
                'message' => 'ID de artículo no proporcionado'
            ], 400);
        }

        // Buscar en repuestos_entregas con estado 'pendiente_entrega'
        Log::info("🔍 Buscando entrega pendiente...");
        $entregaPendiente = DB::table('repuestos_entregas')
            ->where('solicitud_id', $id)
            ->where('articulo_id', $articuloId)
            ->where('estado', 'pendiente_entrega')
            ->first();

        if (!$entregaPendiente) {
            Log::error("❌ No se encontró entrega pendiente para solicitud: " . $id . ", artículo: " . $articuloId);
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el repuesto listo para entregar'
            ], 404);
        }

        Log::info("✅ Entrega pendiente encontrada ID: " . $entregaPendiente->id);
        Log::info("📋 Datos entrega pendiente:");
        Log::info("  - Estado actual: " . $entregaPendiente->estado);
        Log::info("  - Ubicación: " . $entregaPendiente->ubicacion_utilizada);
        Log::info("  - Cantidad: " . $entregaPendiente->cantidad);

        // ========================
        // 1. PROCESAR LA FOTO (en LONGBLOB)
        // ========================
        $fotoBlob = null;
        $tipoArchivo = null;
        $tamanoFoto = 0;
        
        Log::info("🖼️ Procesando foto...");
        if ($request->hasFile('foto')) {
            try {
                $file = $request->file('foto');
                
                // Validaciones
                $validMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp'];
                $mimeType = $file->getMimeType();
                
                Log::info("🔍 Validando tipo MIME: " . $mimeType);
                if (!in_array($mimeType, $validMimeTypes)) {
                    Log::error("❌ Tipo MIME no válido: " . $mimeType);
                    throw new \Exception('Formato de imagen no válido. Solo se permiten JPG, PNG, GIF, WEBP, BMP');
                }
                
                // Tamaño máximo: 10MB
                $maxSize = 10 * 1024 * 1024;
                $fileSize = $file->getSize();
                Log::info("📏 Tamaño archivo: " . $fileSize . " bytes, Máximo: " . $maxSize . " bytes");
                
                if ($fileSize > $maxSize) {
                    Log::error("❌ Tamaño excedido: " . $fileSize . " > " . $maxSize);
                    throw new \Exception("La imagen es demasiado grande. Máximo 10MB");
                }
                
                // Leer la imagen
                Log::info("📖 Leyendo archivo...");
                $fotoBlob = file_get_contents($file->getRealPath());
                $tipoArchivo = $mimeType;
                $tamanoFoto = strlen($fotoBlob);
                
                Log::info("✅ Foto procesada exitosamente:");
                Log::info("  - Tamaño BLOB: " . $tamanoFoto . " bytes");
                Log::info("  - Tipo Archivo: " . $tipoArchivo);
                
            } catch (\Exception $e) {
                Log::error("❌ Error al procesar foto: " . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error al procesar la foto: ' . $e->getMessage()
                ], 400);
            }
        } else {
            Log::warning("⚠️ No se recibió archivo de foto");
        }

        // ========================
        // 2. PREPARAR DATOS PARA ACTUALIZACIÓN
        // ========================
        Log::info("📝 Preparando datos para actualización...");
        
        // Datos base para actualizar
        $updateData = [
            'estado' => 'entregado',
            'usuario_entrego_id' => auth()->id(),
            'fecha_entrega' => now(),
            'firma_confirma' => $firmaConfirmada,
            'observaciones_entrega' => $observacionesEntrega,
            'updated_at' => now()
        ];

        // Agregar foto si existe
        if ($fotoBlob) {
            $updateData['foto_entrega'] = $fotoBlob;
            $updateData['tipo_archivo_foto'] = $tipoArchivo;
            Log::info("📸 Foto agregada al update");
        }

        // Actualizar observaciones generales
        $observacionesCompletas = $entregaPendiente->observaciones . 
            " | ✅ ENTREGA CONFIRMADA: " . now()->format('d/m/Y H:i:s') .
            " | Firmado por: {$nombreFirmante} ({$fechaFirma})" .
            " | Firma confirmada: " . ($firmaConfirmada ? 'SÍ' : 'NO') .
            ($observacionesEntrega ? " | Obs. entrega: {$observacionesEntrega}" : "") .
            ($fotoBlob ? " | Foto adjunta: {$tipoArchivo}" : "");

        $updateData['observaciones'] = $observacionesCompletas;

        // ========================
        // 3. EJECUTAR ACTUALIZACIÓN
        // ========================
        Log::info("⚡ Actualizando repuestos_entregas...");
        
        $affected = DB::table('repuestos_entregas')
            ->where('id', $entregaPendiente->id)
            ->update($updateData);

        Log::info("✅ Filas afectadas: " . $affected);

        if ($affected === 0) {
            Log::error("❌ No se actualizó ninguna fila");
            throw new \Exception("No se pudo actualizar el registro de entrega");
        }

        // ========================
        // 4. ACTUALIZAR ORDENESARTICULOS
        // ========================
        Log::info("📝 Actualizando ordenesarticulos...");
        DB::table('ordenesarticulos')
            ->where('idsolicitudesordenes', $id)
            ->where('idarticulos', $articuloId)
            ->update([
                'estado' => 1,
                'observacion' => "✅ ENTREGA CONFIRMADA: " . now()->format('d/m/Y H:i:s') .
                    " | Ubicación: {$entregaPendiente->ubicacion_utilizada}" .
                    " | Firmado por: {$nombreFirmante}" .
                    " | Firma: " . ($firmaConfirmada ? 'CONFIRMADA' : 'NO CONFIRMADA') .
                    ($fotoBlob ? " | Foto adjunta" : ""),
                'updated_at' => now()
            ]);

        // ========================
        // 5. ACTUALIZAR ESTADO DE LA SOLICITUD
        // ========================
        Log::info("🔄 Actualizando estado de solicitud...");

        // Verificar si existe al menos un repuesto entregado
        $existeEntrega = DB::table('repuestos_entregas')
            ->where('solicitud_id', $id)
            ->where('estado', 'entregado')
            ->exists();

        Log::info("📊 ¿Existe al menos un repuesto entregado?: " . ($existeEntrega ? 'SÍ' : 'NO'));

        if ($existeEntrega) {
            // Si existe al menos UN repuesto entregado, estado = "entregado"
            Log::info("✅ Al menos un repuesto entregado. Estado actualizado a 'entregado'");
            
            DB::table('solicitudesordenes')
                ->where('idsolicitudesordenes', $id)
                ->update([
                    'estado' => 'entregado',
                    'fechaactualizacion' => now(),
                    'updated_at' => now()
                ]);
        } else {
            // Ningún repuesto entregado, mantener estado actual
            Log::info("ℹ️ Ningún repuesto entregado. Estado no cambia.");
            
            DB::table('solicitudesordenes')
                ->where('idsolicitudesordenes', $id)
                ->update([
                    'fechaactualizacion' => now(),
                    'updated_at' => now()
                ]);
        }

        // ========================
        // 6. NOTIFICACIONES
        // ========================
        Log::info("🔔 Procesando notificaciones...");
        $notificacionExistente = DB::table('notificaciones_solicitud')
            ->where('idSolicitudesOrdenes', $id)
            ->first();

        if ($notificacionExistente) {
            Log::info("📝 Actualizando notificación existente...");
            DB::table('notificaciones_solicitud')
                ->where('idNotificacionSolicitud', $notificacionExistente->idNotificacionSolicitud)
                ->update([
                    'estado_web' => 1,
                    'estado_app' => 0,
                    'fecha' => now(),
                    'updated_at' => now()
                ]);
        } else {
            Log::info("🆕 Creando nueva notificación...");
            DB::table('notificaciones_solicitud')->insert([
                'idSolicitudesOrdenes' => $id,
                'estado_web' => 1,
                'estado_app' => 0,
                'fecha' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        DB::commit();
        
        Log::info("════════════════════════════════════════════════════════");
        Log::info("🎉 confirmarEntregaFisicaConFoto COMPLETADO EXITOSAMENTE");
        Log::info("════════════════════════════════════════════════════════");
        Log::info("📋 RESUMEN:");
        Log::info("  - Solicitud: " . $solicitud->codigo);
        Log::info("  - Artículo ID: " . $articuloId);
        Log::info("  - Firma confirmada: " . ($firmaConfirmada ? 'SÍ' : 'NO'));
        Log::info("  - Foto guardada: " . ($fotoBlob ? 'SÍ' : 'NO'));
        Log::info("  - Estado solicitud: " . ($existeEntrega ? 'entregado' : 'sin cambios'));
        Log::info("════════════════════════════════════════════════════════");

        return response()->json([
            'success' => true,
            'message' => 'Entrega confirmada exitosamente',
            'codigo_solicitud' => $solicitud->codigo,
            'articulo_id' => $articuloId,
            'foto_guardada' => $fotoBlob ? true : false,
            'firma_confirmada' => (bool)$firmaConfirmada,
            'observaciones_entrega' => $observacionesEntrega,
            'estado_solicitud' => $existeEntrega ? 'entregado' : 'sin cambios'
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("════════════════════════════════════════════════════════");
        Log::error("💥 ERROR en confirmarEntregaFisicaConFoto");
        Log::error("════════════════════════════════════════════════════════");
        Log::error('Mensaje: ' . $e->getMessage());
        Log::error('Archivo: ' . $e->getFile());
        Log::error('Línea: ' . $e->getLine());
        Log::error('Trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al confirmar la entrega: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Confirmar entrega de repuesto cedido con foto
 */
public function confirmarEntregaCedidaConFoto(Request $request, $id)
{
    Log::info("════════════════════════════════════════════════════════");
    Log::info("📦 INICIANDO confirmarEntregaCedidaConFoto");
    Log::info("════════════════════════════════════════════════════════");
    
    Log::info("📦 DATOS RECIBIDOS:");
    Log::info("- Solicitud ID: " . $id);
    Log::info("- Articulo ID: " . $request->input('articulo_id'));
    Log::info("- Entrega ID: " . $request->input('entrega_id'));
    Log::info("- Todos los datos: " . json_encode($request->all()));

    try {
        DB::beginTransaction();

        $solicitud = DB::table('solicitudesordenes')
            ->select('idsolicitudesordenes', 'codigo', 'estado', 'tipoorden', 'idUsuario', 'idTecnico')
            ->where('idsolicitudesordenes', $id)
            ->where('tipoorden', 'solicitud_repuesto')
            ->first();

        if (!$solicitud) {
            Log::error("❌ Solicitud no encontrada");
            return response()->json([
                'success' => false,
                'message' => 'Solicitud no encontrada'
            ], 404);
        }

        Log::info("✅ Solicitud encontrada: " . $solicitud->codigo);

        $articuloId = (int)$request->input('articulo_id');
        $entregaId = (int)$request->input('entrega_id');
        $observacionesEntrega = $request->input('observaciones');
        $nombreFirmante = $request->input('nombre_firmante');
        $fechaFirma = $request->input('fecha_firma');
        
        // Procesar firma_confirmada
        $firmaConfirmada = 0;
        $firmaConfirmadaRaw = $request->input('firma_confirmada');
        if ($firmaConfirmadaRaw === 'true' || $firmaConfirmadaRaw === true || $firmaConfirmadaRaw === '1' || $firmaConfirmadaRaw === 1) {
            $firmaConfirmada = 1;
        }

        // ==========================================
        // 1. VALIDAR ENTREGA CEDIDA
        // ==========================================
        Log::info("🔍 Validando entrega cedida...");
        $entregaCedida = DB::table('repuestos_entregas as re')
            ->select(
                're.id',
                're.solicitud_id',
                're.articulo_id',
                're.cantidad',
                're.ubicacion_utilizada',
                're.entrega_origen_id',
                're.estado',
                're.observaciones',
                're.numero_ticket',
                'so.codigo as codigo_solicitud',
                'a.nombre as articulo_nombre'
            )
            ->leftJoin('solicitudesordenes as so', 're.solicitud_id', '=', 'so.idsolicitudesordenes')
            ->leftJoin('articulos as a', 're.articulo_id', '=', 'a.idArticulos')
            ->where('re.id', $entregaId)
            ->where('re.solicitud_id', $id)
            ->where('re.articulo_id', $articuloId)
            ->where('re.estado', 'listo_para_ceder')
            ->first();

        if (!$entregaCedida) {
            Log::error("❌ Entrega cedida no encontrada o no está en 'listo_para_ceder'");
            return response()->json([
                'success' => false,
                'message' => 'Entrega cedida no encontrada o no está disponible'
            ], 404);
        }

        Log::info("✅ Entrega cedida validada:");
        Log::info("   - ID: " . $entregaCedida->id);
        Log::info("   - Solicitud: " . $entregaCedida->codigo_solicitud);
        Log::info("   - Artículo: " . $entregaCedida->articulo_nombre);

        // ==========================================
        // 2. PROCESAR FOTO
        // ==========================================
        $fotoBlob = null;
        $tipoArchivo = null;
        
        Log::info("🖼️ Procesando foto...");
        if ($request->hasFile('foto')) {
            try {
                $file = $request->file('foto');
                
                // Validaciones
                $validMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp'];
                $mimeType = $file->getMimeType();
                
                if (!in_array($mimeType, $validMimeTypes)) {
                    throw new \Exception('Formato de imagen no válido. Solo se permiten JPG, PNG, GIF, WEBP, BMP');
                }
                
                // Tamaño máximo: 10MB
                $maxSize = 10 * 1024 * 1024;
                $fileSize = $file->getSize();
                
                if ($fileSize > $maxSize) {
                    throw new \Exception("La imagen es demasiado grande. Máximo 10MB");
                }
                
                // Leer la imagen
                $fotoBlob = file_get_contents($file->getRealPath());
                $tipoArchivo = $mimeType;
                
                Log::info("✅ Foto procesada exitosamente");
                
            } catch (\Exception $e) {
                Log::error("❌ Error al procesar foto: " . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Error al procesar la foto: ' . $e->getMessage()
                ], 400);
            }
        }

        // ==========================================
        // 3. ACTUALIZAR ENTREGA CEDIDA
        // ==========================================
        Log::info("🔄 Actualizando entrega cedida...");
        
        // Preparar datos de actualización
        $updateData = [
            'estado' => 'entregado',
            'usuario_entrego_id' => auth()->id(),
            'fecha_entrega' => now(),
            'firma_confirma' => $firmaConfirmada,
            'observaciones_entrega' => $observacionesEntrega,
            'updated_at' => now()
        ];

        // Agregar foto si existe
        if ($fotoBlob) {
            $updateData['foto_entrega'] = $fotoBlob;
            $updateData['tipo_archivo_foto'] = $tipoArchivo;
        }

        // Actualizar observaciones generales
        $observacionesCompletas = $entregaCedida->observaciones . 
            " | ✅ ENTREGA CEDIDA CONFIRMADA: " . now()->format('d/m/Y H:i:s') .
            " | Firmado por: {$nombreFirmante} ({$fechaFirma})" .
            " | Firma confirmada: " . ($firmaConfirmada ? 'SÍ' : 'NO') .
            ($observacionesEntrega ? " | Obs. entrega: {$observacionesEntrega}" : "") .
            ($fotoBlob ? " | Foto adjunta: {$tipoArchivo}" : "");

        $updateData['observaciones'] = $observacionesCompletas;

        // Ejecutar actualización
        $affected = DB::table('repuestos_entregas')
            ->where('id', $entregaCedida->id)
            ->update($updateData);

        if ($affected === 0) {
            throw new \Exception("No se pudo actualizar el registro de entrega cedida");
        }

        Log::info("✅ Entrega cedida actualizada exitosamente");

        // ==========================================
        // 4. ACTUALIZAR ENTREGA ORIGEN (OPCIONAL)
        // ==========================================
        if ($entregaCedida->entrega_origen_id) {
            Log::info("🔄 Actualizando entrega origen...");
            DB::table('repuestos_entregas')
                ->where('id', $entregaCedida->entrega_origen_id)
                ->update([
                    'observaciones' => DB::raw("CONCAT(observaciones, ' | 📤 ENTREGA CEDIDA COMPLETADA: " . now()->format('d/m/Y H:i:s') . "')"),
                    'updated_at' => now()
                ]);
        }

        // ==========================================
        // 5. ACTUALIZAR ORDENESARTICULOS
        // ==========================================
        Log::info("📝 Actualizando ordenesarticulos...");
        DB::table('ordenesarticulos')
            ->where('idsolicitudesordenes', $id)
            ->where('idarticulos', $articuloId)
            ->update([
                'estado' => 1,
                'observacion' => "✅ ENTREGA CEDIDA CONFIRMADA: " . now()->format('d/m/Y H:i:s') .
                    " | Ubicación: {$entregaCedida->ubicacion_utilizada}" .
                    " | Firmado por: {$nombreFirmante}" .
                    " | Firma: " . ($firmaConfirmada ? 'CONFIRMADA' : 'NO CONFIRMADA') .
                    ($fotoBlob ? " | Foto adjunta" : ""),
                'updated_at' => now()
            ]);

        // ==========================================
        // 6. ACTUALIZAR ESTADO SOLICITUD
        // ==========================================
        Log::info("🔄 Actualizando estado de solicitud...");
        
        $totalRepuestos = DB::table('ordenesarticulos')
            ->where('idsolicitudesordenes', $id)
            ->count();
            
        $repuestosEntregados = DB::table('ordenesarticulos')
            ->where('idsolicitudesordenes', $id)
            ->where('estado', 1)
            ->count();

        Log::info("📊 Estado de repuestos:");
        Log::info("   - Total: " . $totalRepuestos);
        Log::info("   - Entregados: " . $repuestosEntregados);

        $nuevoEstado = 'parcial_listo';
        if ($repuestosEntregados == $totalRepuestos) {
            $nuevoEstado = 'entregado';
        }

        DB::table('solicitudesordenes')
            ->where('idsolicitudesordenes', $id)
            ->update([
                'estado' => $nuevoEstado,
                'fechaactualizacion' => now(),
                'updated_at' => now()
            ]);

        Log::info("✅ Estado de solicitud actualizado a: " . $nuevoEstado);

        // ==========================================
        // 7. NOTIFICACIONES
        // ==========================================
        Log::info("🔔 Procesando notificaciones...");
        $notificacionExistente = DB::table('notificaciones_solicitud')
            ->where('idsolicitudesordenes', $id)
            ->first();

        if ($notificacionExistente) {
            DB::table('notificaciones_solicitud')
                ->where('idNotificacionSolicitud', $notificacionExistente->idNotificacionSolicitud)
                ->update([
                    'estado_web' => 1,
                    'estado_app' => 0,
                    'fecha' => now(),
                    'updated_at' => now()
                ]);
        } else {
            DB::table('notificaciones_solicitud')->insert([
                'idsolicitudesordenes' => $id,
                'estado_web' => 1,
                'estado_app' => 0,
                'fecha' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        DB::commit();

        Log::info("════════════════════════════════════════════════════════");
        Log::info("✅ confirmarEntregaCedidaConFoto COMPLETADO EXITOSAMENTE");
        Log::info("════════════════════════════════════════════════════════");

        return response()->json([
            'success' => true,
            'message' => 'Entrega de repuesto cedido confirmada exitosamente',
            'codigo_solicitud' => $solicitud->codigo,
            'articulo_nombre' => $entregaCedida->articulo_nombre,
            'estado_solicitud' => $nuevoEstado,
            'repuestos_entregados' => $repuestosEntregados,
            'repuestos_totales' => $totalRepuestos,
            'foto_guardada' => $fotoBlob ? true : false,
            'firma_confirmada' => (bool)$firmaConfirmada
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("💥 ERROR en confirmarEntregaCedidaConFoto: " . $e->getMessage());
        Log::error("Trace: " . $e->getTraceAsString());
        return response()->json([
            'success' => false,
            'message' => 'Error al confirmar entrega cedida: ' . $e->getMessage()
        ], 500);
    }
}

public function obtenerInfoEntrega($solicitudId, $articuloId)
{
    try {
        Log::info("🔍 Obteniendo info de entrega", [
            'solicitud_id' => $solicitudId,
            'articulo_id' => $articuloId
        ]);

        // Buscar información de la entrega
        $entrega = DB::table('repuestos_entregas')
            ->select([
                'estado',
                'fecha_entrega',
                'usuario_entrego_id',
                'firma_confirma',
                'observaciones_entrega',
                'observaciones',
                'foto_entrega',
                'tipo_archivo_foto',
                'created_at',
                'updated_at'
            ])
            ->where('solicitud_id', $solicitudId)
            ->where('articulo_id', $articuloId)
            ->where('estado', 'entregado')
            ->first();

        if (!$entrega) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró información de entrega'
            ], 404);
        }

        // Obtener nombre del usuario que entregó
        $usuarioEntrego = null;
        if ($entrega->usuario_entrego_id) {
            $usuarioEntrego = DB::table('users')
                ->select('name')
                ->where('id', $entrega->usuario_entrego_id)
                ->first();
        }

        // Preparar datos de respuesta
        $data = [
            'estado' => $entrega->estado,
            'fecha_entrega' => $entrega->fecha_entrega 
                ? \Carbon\Carbon::parse($entrega->fecha_entrega)->format('d/m/Y H:i:s')
                : null,
            'usuario_entrego' => $usuarioEntrego ? $usuarioEntrego->name : null,
            'firma_confirma' => (bool)$entrega->firma_confirma,
            'observaciones_entrega' => $entrega->observaciones_entrega,
            'observaciones' => $entrega->observaciones,
            'fecha_creacion' => \Carbon\Carbon::parse($entrega->created_at)->format('d/m/Y H:i:s'),
            'fecha_actualizacion' => \Carbon\Carbon::parse($entrega->updated_at)->format('d/m/Y H:i:s'),
        ];

        // Incluir foto en base64 si existe
        if ($entrega->foto_entrega && $entrega->tipo_archivo_foto) {
            // Convertir BLOB a base64
            $fotoBase64 = base64_encode($entrega->foto_entrega);
            $data['foto_entrega'] = $fotoBase64;
            $data['tipo_archivo_foto'] = $entrega->tipo_archivo_foto;
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);

    } catch (\Exception $e) {
        Log::error('Error al obtener info de entrega:', [
            'error' => $e->getMessage(),
            'solicitud' => $solicitudId,
            'articulo' => $articuloId
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error al obtener información: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Método auxiliar para comprimir imágenes JPEG (si la necesitas)
 */
private function comprimirImagenJPEG($path, $calidad = 80)
{
    try {
        Log::info("🖼️ Comprimiendo imagen JPEG...");
        Log::info("  - Ruta: " . $path);
        Log::info("  - Calidad: " . $calidad);
        
        $image = imagecreatefromjpeg($path);
        if (!$image) {
            Log::warning("⚠️ No se pudo crear imagen desde JPEG, devolviendo original");
            return file_get_contents($path);
        }
        
        ob_start();
        imagejpeg($image, null, $calidad);
        $contenido = ob_get_clean();
        imagedestroy($image);
        
        $tamanoOriginal = filesize($path);
        $tamanoComprimido = strlen($contenido);
        $porcentaje = round(($tamanoComprimido / $tamanoOriginal) * 100, 2);
        
        Log::info("✅ Imagen comprimida:");
        Log::info("  - Original: " . $tamanoOriginal . " bytes");
        Log::info("  - Comprimida: " . $tamanoComprimido . " bytes");
        Log::info("  - Tamaño: " . $porcentaje . "% del original");
        
        return $contenido;
    } catch (\Exception $e) {
        Log::warning("⚠️ No se pudo comprimir la imagen: " . $e->getMessage());
        return file_get_contents($path);
    }
}

/**
 * Ceder un repuesto que está pendiente por retorno a la solicitud actual
 * FLUJO CORREGIDO:
 * 1. Verificar que el repuesto existe en la solicitud destino (ordenesarticulos)
 * 2. Verificar que hay una entrega pendiente por retorno de OTRA solicitud
 * 3. Comparar que el artículo y cantidad coincidan
 * 4. Verificar ubicación en repuestos_entregas
 * 5. Ceder a la nueva solicitud
 */
public function cederRepuesto(Request $request, $idSolicitudDestino)
    {
        Log::info("════════════════════════════════════════════════════════");
        Log::info("🔄 INICIANDO cederRepuesto");
        Log::info("════════════════════════════════════════════════════════");
        
        // Validar datos del request
        $articuloId = $request->input('articulo_id');
        $entregaIdOrigen = $request->input('entrega_id');
        
        Log::info("🔍 Datos recibidos:");
        Log::info("  - articulo_id: " . ($articuloId ?? 'NULL'));
        Log::info("  - entrega_id: " . ($entregaIdOrigen ?? 'NULL'));
        
        if (empty($articuloId) || empty($entregaIdOrigen)) {
            Log::error("❌ Datos requeridos faltantes");
            return response()->json([
                'success' => false,
                'message' => 'Faltan datos requeridos: artículo ID y entrega ID'
            ], 400);
        }
        
        $articuloId = (int)$articuloId;
        $entregaIdOrigen = (int)$entregaIdOrigen;
        
        try {
            DB::beginTransaction();

            // ==========================================
            // 1. VALIDAR SOLICITUD DESTINO
            // ==========================================
            Log::info("🔍 Validando solicitud destino...");
            $solicitudDestino = DB::table('solicitudesordenes')
                ->select('idSolicitudesOrdenes', 'codigo', 'estado', 'tipoorden', 'idUsuario', 'idTecnico')
                ->where('idSolicitudesOrdenes', $idSolicitudDestino)
                ->where('tipoorden', 'solicitud_repuesto')
                ->first();

            if (!$solicitudDestino) {
                Log::error("❌ Solicitud destino no encontrada");
                return response()->json([
                    'success' => false,
                    'message' => 'Solicitud destino no encontrada'
                ], 404);
            }

            Log::info("✅ Solicitud destino encontrada: " . $solicitudDestino->codigo);

            // ==========================================
            // 2. VERIFICAR ARTÍCULO EN SOLICITUD DESTINO
            // ==========================================
            Log::info("🔍 Verificando artículo en solicitud destino...");
            $repuestoSolicitudDestino = DB::table('ordenesarticulos as oa')
                ->select(
                    'oa.idOrdenesArticulos',
                    'oa.cantidad as cantidad_solicitada',
                    'oa.estado as estado_articulo',
                    'oa.idticket',
                    'a.idArticulos',
                    'a.nombre',
                    'a.codigo_repuesto'
                )
                ->join('articulos as a', 'oa.idArticulos', '=', 'a.idArticulos')
                ->where('oa.idSolicitudesOrdenes', $idSolicitudDestino)
                ->where('a.idArticulos', $articuloId)
                ->first();

            if (!$repuestoSolicitudDestino) {
                Log::error("❌ Artículo no encontrado en solicitud destino");
                return response()->json([
                    'success' => false,
                    'message' => 'El artículo no existe en la solicitud destino'
                ], 404);
            }

            if ($repuestoSolicitudDestino->estado_articulo != 0) {
                Log::error("❌ Artículo ya procesado en esta solicitud");
                return response()->json([
                    'success' => false,
                    'message' => 'Este artículo ya fue procesado en esta solicitud'
                ], 400);
            }

            // ==========================================
            // 3. VALIDAR ENTREGA ORIGEN
            // ==========================================
            Log::info("🔍 Validando entrega origen...");
            $entregaOrigen = DB::table('repuestos_entregas as re')
                ->select(
                    're.id',
                    're.solicitud_id',
                    're.articulo_id',
                    're.cantidad',
                    're.ubicacion_utilizada',
                    're.ubicacion_id',
                    're.estado',
                    're.numero_ticket',
                    're.usuario_destino_id',
                    're.tipo_entrega',
                    're.observaciones',
                    'so.codigo as codigo_solicitud_origen',
                    'a.nombre as articulo_nombre'
                )
                ->leftJoin('solicitudesordenes as so', 're.solicitud_id', '=', 'so.idSolicitudesOrdenes')
                ->leftJoin('articulos as a', 're.articulo_id', '=', 'a.idArticulos')
                ->where('re.id', $entregaIdOrigen)
                ->where('re.articulo_id', $articuloId)
                ->where('re.estado', 'pendiente_por_retorno')
                ->first();

            if (!$entregaOrigen) {
                Log::error("❌ Entrega origen no válida");
                return response()->json([
                    'success' => false,
                    'message' => 'La entrega seleccionada no está disponible para ceder'
                ], 404);
            }

            // ==========================================
            // 4. COMPARAR ARTÍCULO Y CANTIDAD
            // ==========================================
            if ($repuestoSolicitudDestino->idArticulos != $entregaOrigen->articulo_id) {
                Log::error("❌ Artículos no coinciden");
                return response()->json([
                    'success' => false,
                    'message' => 'Los artículos no coinciden'
                ], 400);
            }
            
            if ($entregaOrigen->cantidad < $repuestoSolicitudDestino->cantidad_solicitada) {
                Log::error("❌ Cantidad insuficiente");
                return response()->json([
                    'success' => false,
                    'message' => 'Cantidad insuficiente en la entrega origen. Disponible: ' . 
                               $entregaOrigen->cantidad . ', Solicitado: ' . $repuestoSolicitudDestino->cantidad_solicitada
                ], 400);
            }

            // ==========================================
            // 5. OBTENER NÚMERO DE TICKET
            // ==========================================
            $ticketInfo = DB::table('tickets')
                ->select('numero_ticket')
                ->where('idTickets', $repuestoSolicitudDestino->idticket)
                ->first();
            $numeroTicket = $ticketInfo->numero_ticket ?? 'N/A';

            // ==========================================
            // 6. ACTUALIZAR ENTREGA ORIGEN A "cedido"
            // ==========================================
            Log::info("🔄 Actualizando entrega origen...");
            DB::table('repuestos_entregas')
                ->where('id', $entregaOrigen->id)
                ->update([
                    'estado' => 'cedido',
                    'observaciones' => ($entregaOrigen->observaciones ?? '') . 
                        " | ✅ CEDIDO a solicitud: " . $solicitudDestino->codigo . 
                        " (" . now()->format('d/m/Y H:i:s') . ")",
                    'updated_at' => now()
                ]);

            // ==========================================
            // 7. CREAR NUEVA ENTREGA PARA SOLICITUD DESTINO
            // ==========================================
            Log::info("📝 Creando nueva entrega para solicitud destino...");
            $nuevaEntregaId = DB::table('repuestos_entregas')->insertGetId([
                'solicitud_id' => $solicitudDestino->idSolicitudesOrdenes,
                'articulo_id' => $articuloId,
                'usuario_destino_id' => $solicitudDestino->idTecnico,
                'tipo_entrega' => 'tecnico',
                'cantidad' => $repuestoSolicitudDestino->cantidad_solicitada,
                'ubicacion_utilizada' => $entregaOrigen->ubicacion_utilizada,
                'ubicacion_id' => $entregaOrigen->ubicacion_id,
                'numero_ticket' => $numeroTicket,
                'usuario_preparo_id' => auth()->id(),
                'estado' => 'listo_para_ceder',
                'entrega_origen_id' => $entregaOrigen->id,
                'observaciones' => "Repuesto CEDIDO desde solicitud: " . $entregaOrigen->codigo_solicitud_origen . 
                    " | Cantidad: " . $repuestoSolicitudDestino->cantidad_solicitada . 
                    " | Ubicación: " . $entregaOrigen->ubicacion_utilizada . 
                    " | Preparado por: " . (auth()->user()->name ?? 'Usuario') . 
                    " (" . now()->format('d/m/Y H:i:s') . ")",
                'fecha_preparacion' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // ==========================================
            // 8. ACTUALIZAR ORDENESARTICULOS DE LA SOLICITUD DESTINO
            // ==========================================
            Log::info("📝 Actualizando ordenesarticulos...");
            DB::table('ordenesarticulos')
                ->where('idOrdenesArticulos', $repuestoSolicitudDestino->idOrdenesArticulos)
                ->update([
                    'estado' => 2, // Listo para entregar
                    'observacion' => "Repuesto CEDIDO | Origen: " . $entregaOrigen->codigo_solicitud_origen . 
                        " | Ubicación: " . $entregaOrigen->ubicacion_utilizada . 
                        " | " . now()->format('d/m/Y H:i:s'),
                    'updated_at' => now()
                ]);

            // ==========================================
            // 9. ACTUALIZAR ESTADO DE LA SOLICITUD DESTINO
            // ==========================================
            Log::info("🔄 Actualizando estado de solicitud destino...");
            
            $totalRepuestos = DB::table('ordenesarticulos')
                ->where('idSolicitudesOrdenes', $idSolicitudDestino)
                ->count();
                
            $repuestosListos = DB::table('ordenesarticulos')
                ->where('idSolicitudesOrdenes', $idSolicitudDestino)
                ->whereIn('estado', [1, 2])
                ->count();

            $nuevoEstado = $solicitudDestino->estado;
            if ($repuestosListos == $totalRepuestos) {
                $nuevoEstado = 'listo_para_entregar';
            } elseif ($repuestosListos > 0) {
                $nuevoEstado = 'parcial_listo';
            }

            DB::table('solicitudesordenes')
                ->where('idSolicitudesOrdenes', $idSolicitudDestino)
                ->update([
                    'estado' => $nuevoEstado,
                    'fechaactualizacion' => now(),
                    'updated_at' => now()
                ]);

            // ==========================================
            // 10. NOTIFICACIONES
            // ==========================================
            DB::table('notificaciones_solicitud')->insert([
                'idSolicitudesOrdenes' => $idSolicitudDestino,
                'estado_web' => 1,
                'estado_app' => 0,
                'fecha' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            Log::info("✅ cederRepuesto COMPLETADO EXITOSAMENTE");
            Log::info("📋 RESUMEN:");
            Log::info("  - Solicitud destino: " . $solicitudDestino->codigo);
            Log::info("  - Solicitud origen: " . $entregaOrigen->codigo_solicitud_origen);
            Log::info("  - Artículo: " . $entregaOrigen->articulo_nombre);
            Log::info("  - Cantidad cedida: " . $repuestoSolicitudDestino->cantidad_solicitada);
            Log::info("  - Nueva entrega ID: " . $nuevaEntregaId);
            Log::info("  - Nuevo estado solicitud: " . $nuevoEstado);

            return response()->json([
                'success' => true,
                'message' => 'Repuesto cedido exitosamente a la solicitud ' . $solicitudDestino->codigo,
                'data' => [
                    'solicitud_destino' => $solicitudDestino->codigo,
                    'solicitud_origen' => $entregaOrigen->codigo_solicitud_origen,
                    'articulo' => $entregaOrigen->articulo_nombre,
                    'cantidad' => $repuestoSolicitudDestino->cantidad_solicitada,
                    'ubicacion' => $entregaOrigen->ubicacion_utilizada,
                    'nueva_entrega_id' => $nuevaEntregaId,
                    'estado_solicitud' => $nuevoEstado
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("💥 ERROR en cederRepuesto: " . $e->getMessage());
            Log::error("Trace: " . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al ceder el repuesto: ' . $e->getMessage()
            ], 500);
        }
    }

/**
 * Confirmar la entrega física de un repuesto cedido
 */
public function confirmarEntregaCedido(Request $request, $id)
{
    Log::info("════════════════════════════════════════════════════════");
    Log::info("📦 INICIANDO confirmarEntregaCedido");
    Log::info("════════════════════════════════════════════════════════");
    
    Log::info("📦 DATOS RECIBIDOS:");
    Log::info("- Solicitud ID: " . $id);
    Log::info("- Articulo ID: " . $request->input('articulo_id'));
    Log::info("- Entrega ID: " . $request->input('entrega_id'));
    Log::info("- Todos los datos: " . json_encode($request->all()));

    try {
        DB::beginTransaction();

        $solicitudId = $id;
        $articuloId = (int)$request->input('articulo_id');
        $entregaId = (int)$request->input('entrega_id');

        // ==========================================
        // 1. VALIDAR ENTREGA CEDIDA - CORREGIDO
        // ==========================================
        Log::info("🔍 Validando entrega cedida...");
        $entregaCedida = DB::table('repuestos_entregas as re')
            ->select(
                're.id',
                're.solicitud_id',
                're.articulo_id',
                're.cantidad',
                're.ubicacion_utilizada',
                're.entrega_origen_id',
                're.estado',
                're.observaciones',
                'so.codigo as codigo_solicitud',
                'a.nombre as articulo_nombre'
            )
            ->leftJoin('solicitudesordenes as so', 're.solicitud_id', '=', 'so.idSolicitudesOrdenes') // COLUMNA CORRECTA
            ->leftJoin('articulos as a', 're.articulo_id', '=', 'a.idArticulos')
            ->where('re.id', $entregaId)
            ->where('re.solicitud_id', $solicitudId)
            ->where('re.articulo_id', $articuloId)
            ->where('re.estado', 'listo_para_ceder')
            ->first();

        if (!$entregaCedida) {
            Log::error("❌ Entrega cedida no encontrada o no está en 'listo_para_ceder'");
            return response()->json([
                'success' => false,
                'message' => 'Entrega cedida no encontrada'
            ], 404);
        }

        Log::info("✅ Entrega cedida encontrada:");
        Log::info("   - ID: " . $entregaCedida->id);
        Log::info("   - Entrega origen ID: " . $entregaCedida->entrega_origen_id);
        Log::info("   - Solicitud: " . $entregaCedida->codigo_solicitud);
        Log::info("   - Artículo: " . $entregaCedida->articulo_nombre);

        // ==========================================
        // 2. ACTUALIZAR ENTREGA CEDIDA
        // ==========================================
        Log::info("🔄 Actualizando entrega cedida...");
        DB::table('repuestos_entregas')
            ->where('id', $entregaId)
            ->update([
                'estado' => 'entregado',
                'usuario_entrego_id' => auth()->id(),
                'fecha_entrega' => now(),
                'firma_confirma' => 1,
                'observaciones_entrega' => "Repuesto cedido entregado físicamente",
                'observaciones' => $entregaCedida->observaciones . 
                    " | ✅ ENTREGA CEDIDA CONFIRMADA: " . now()->format('d/m/Y H:i:s'),
                'updated_at' => now()
            ]);

        // ==========================================
        // 3. ACTUALIZAR ENTREGA ORIGEN (OPCIONAL)
        // ==========================================
        if ($entregaCedida->entrega_origen_id) {
            Log::info("🔄 Actualizando entrega origen...");
            DB::table('repuestos_entregas')
                ->where('id', $entregaCedida->entrega_origen_id)
                ->update([
                    'observaciones' => DB::raw("CONCAT(observaciones, ' | 📤 ENTREGA CEDIDA COMPLETADA: " . now()->format('d/m/Y H:i:s') . "')"),
                    'updated_at' => now()
                ]);
        }

        // ==========================================
        // 4. ACTUALIZAR ORDENESARTICULOS - CORREGIDO
        // ==========================================
        Log::info("📝 Actualizando ordenesarticulos...");
        DB::table('ordenesarticulos')
            ->where('idSolicitudesOrdenes', $solicitudId)  // COLUMNA CORRECTA
            ->where('idarticulos', $articuloId)
            ->update([
                'estado' => 1, // Entregado
                'observacion' => "Repuesto CEDIDO entregado físicamente | " . now()->format('d/m/Y H:i:s'),
                'updated_at' => now()
            ]);

        // ==========================================
        // 5. ACTUALIZAR ESTADO SOLICITUD - CORREGIDO
        // ==========================================
        Log::info("🔄 Actualizando estado de solicitud...");
        
        // Usar nombre correcto de columna
        $totalRepuestos = DB::table('ordenesarticulos')
            ->where('idSolicitudesOrdenes', $solicitudId)  // COLUMNA CORRECTA
            ->count();
            
        $repuestosEntregados = DB::table('ordenesarticulos')
            ->where('idSolicitudesOrdenes', $solicitudId)  // COLUMNA CORRECTA
            ->where('estado', 1)
            ->count();

        Log::info("📊 Estado de repuestos:");
        Log::info("   - Total: " . $totalRepuestos);
        Log::info("   - Entregados: " . $repuestosEntregados);

        $nuevoEstado = 'parcial_listo';
        if ($repuestosEntregados == $totalRepuestos) {
            $nuevoEstado = 'aprobada';
        }

        // Usar nombre correcto de columna
        DB::table('solicitudesordenes')
            ->where('idSolicitudesOrdenes', $solicitudId)  // COLUMNA CORRECTA
            ->update([
                'estado' => $nuevoEstado,
                'fechaactualizacion' => now(),
                'updated_at' => now()
            ]);

        Log::info("✅ Estado de solicitud actualizado a: " . $nuevoEstado);

        // ==========================================
        // 6. NOTIFICACIONES
        // ==========================================
        Log::info("🔔 Actualizando notificaciones...");
        DB::table('notificaciones_solicitud')
            ->where('idSolicitudesOrdenes', $solicitudId)
            ->update([
                'estado_web' => 1,
                'estado_app' => 0,
                'fecha' => now(),
                'updated_at' => now()
            ]);

        DB::commit();

        Log::info("════════════════════════════════════════════════════════");
        Log::info("✅ confirmarEntregaCedido COMPLETADO EXITOSAMENTE");
        Log::info("════════════════════════════════════════════════════════");

        return response()->json([
            'success' => true,
            'message' => 'Entrega de repuesto cedido confirmada',
            'codigo_solicitud' => $entregaCedida->codigo_solicitud,
            'articulo_nombre' => $entregaCedida->articulo_nombre,
            'estado_solicitud' => $nuevoEstado,
            'repuestos_entregados' => $repuestosEntregados,
            'repuestos_totales' => $totalRepuestos
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error("💥 ERROR en confirmarEntregaCedido: " . $e->getMessage());
        Log::error("Trace: " . $e->getTraceAsString());
        return response()->json([
            'success' => false,
            'message' => 'Error al confirmar entrega cedida: ' . $e->getMessage()
        ], 500);
    }
}
}
