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

        // Obtener el último número de orden
        $lastOrder = DB::table('solicitudesordenes')
            ->orderBy('idsolicitudesordenes', 'desc')
            ->first();

        $nextOrderNumber = $lastOrder ? (intval(substr($lastOrder->codigo, 4)) + 1) : 1;

        return view("solicitud.solicitudrepuesto.create", compact('tickets', 'nextOrderNumber'));
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
        try {
            DB::beginTransaction();

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

            // Obtener información del ticket
            $ticket = DB::table('tickets')
                ->where('idTickets', $validated['ticketId'])
                ->first();

            if (!$ticket) {
                throw new \Exception('Ticket no encontrado');
            }

            // Calcular estadísticas de productos
            $totalCantidad = collect($validated['products'])->sum('cantidad');
            $totalProductosUnicos = collect($validated['products'])->unique(function ($product) {
                return $product['modeloId'] . '-' . $product['tipoId'] . '-' . $product['codigoId'];
            })->count();

            // Generar código de orden
            $nextOrderNumber = DB::table('solicitudesordenes')->count() + 1;
            $codigoOrden = 'ORD-' . str_pad($nextOrderNumber, 3, '0', STR_PAD_LEFT);

            // 1. Insertar en solicitudesordenes con TODOS los campos
            $solicitudId = DB::table('solicitudesordenes')->insertGetId([
                'fechacreacion' => now(),
                'estado' => 'pendiente',
                'tipoorden' => 'solicitud_repuesto',
                // 'idticket' => $validated['ticketId'], // ID del ticket
                'fecharequerida' => $validated['orderInfo']['fechaRequerida'],
                'fechaentrega' => $validated['orderInfo']['fechaRequerida'],
                // 'numeroticket' => $ticket->numero_ticket, // Número de ticket
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
            ]);

            // 2. Insertar los artículos en ordenesarticulos CON EL IDTICKET
            foreach ($validated['products'] as $product) {
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
                } else {
                    // Log de artículo no encontrado pero continuar con los demás
                    Log::warning("Artículo no encontrado con código: " . $product['codigoId']);
                }
            }

            DB::commit();

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
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al crear orden: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la orden: ' . $e->getMessage()
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
                'so.idticket', // ← ESTE ES IMPORTANTE
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

    public function edit($id)
    {
        $solicitud = DB::table('solicitudesordenes as so')
            ->where('so.idsolicitudesordenes', $id)
            ->where('so.tipoorden', 'solicitud_repuesto')
            ->first();

        if (!$solicitud) {
            abort(404, 'Solicitud no encontrada');
        }

        // Obtener tickets para el select
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

        $articulosSolicitud = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.*',
                'a.nombre as nombre_articulo',
                'a.codigo_barras',
                'a.codigo_repuesto',
                'a.precio_compra',
                'sc.nombre as tipo_articulo'
            )
            ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->where('oa.idsolicitudesordenes', $id)
            ->get();

        return view('solicitud.solicitudrepuesto.edit', compact('solicitud', 'tickets', 'articulosSolicitud'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'ticketId' => 'required|exists:tickets,idTickets',
                'orderInfo.tipoServicio' => 'required|string',
                'orderInfo.urgencia' => 'required|string|in:baja,media,alta',
                'orderInfo.fechaRequerida' => 'required|date',
                'orderInfo.observaciones' => 'nullable|string',
                'products' => 'required|array|min:1',
                'products.*.articuloId' => 'required|exists:articulos,idArticulos',
                'products.*.cantidad' => 'required|integer|min:1|max:1000'
            ]);

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

            // Obtener información del ticket
            $ticket = DB::table('tickets')
                ->where('idTickets', $validated['ticketId'])
                ->first();

            // Calcular nuevas estadísticas
            $totalCantidad = collect($validated['products'])->sum('cantidad');
            $totalProductosUnicos = count($validated['products']);

            // Actualizar la solicitud
            DB::table('solicitudesordenes')
                ->where('idsolicitudesordenes', $id)
                ->update([
                    'idticket' => $validated['ticketId'],
                    'numeroticket' => $ticket->numero_ticket,
                    'fecharequerida' => $validated['orderInfo']['fechaRequerida'],
                    'niveldeurgencia' => $validated['orderInfo']['urgencia'],
                    'tiposervicio' => $validated['orderInfo']['tipoServicio'],
                    'observaciones' => $validated['orderInfo']['observaciones'] ?? null,
                    'cantidad' => $totalProductosUnicos,
                    'canproduuni' => $totalProductosUnicos,
                    'totalcantidadproductos' => $totalCantidad,
                    'urgencia' => $validated['orderInfo']['urgencia']
                ]);

            // Eliminar artículos existentes y agregar nuevos
            DB::table('ordenesarticulos')->where('idsolicitudesordenes', $id)->delete();

            foreach ($validated['products'] as $product) {
                DB::table('ordenesarticulos')->insert([
                    'cantidad' => $product['cantidad'],
                    'estado' => 0,
                    'observacion' => null,
                    'fotorepuesto' => null,
                    'fechausado' => null,
                    'fechasinusar' => null,
                    'idsolicitudesordenes' => $id,
                    'idticket' => $validated['ticketId'],
                    'idarticulos' => $product['articuloId'],
                    'idubicacion' => null
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Solicitud de repuesto actualizada exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la solicitud: ' . $e->getMessage()
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
            't.numero_ticket',
            'u.name as nombre_solicitante'
        )
        ->leftJoin('users as u', 'so.idusuario', '=', 'u.id')
        ->leftJoin('tickets as t', 'so.idticket', '=', 't.idTickets')
        ->where('so.idsolicitudesordenes', $id)
        ->where('so.tipoorden', 'solicitud_repuesto')
        ->first();

    if (!$solicitud) {
        abort(404, 'Solicitud no encontrada');
    }

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
    }

    // Verificar si toda la solicitud puede ser atendida
    $puede_aceptar = $repuestos->every(function ($repuesto) {
        return $repuesto->suficiente_stock;
    });

    // Contar repuestos procesados y disponibles
    $repuestos_procesados = $repuestos->where('ya_procesado', true)->count();
    $repuestos_disponibles = $repuestos->where('suficiente_stock', true)->count();
    $total_repuestos = $repuestos->count();

    return view('solicitud.solicitudrepuesto.opciones', compact(
        'solicitud', 
        'repuestos', 
        'puede_aceptar',
        'repuestos_procesados',
        'repuestos_disponibles',
        'total_repuestos'
    ));
}


public function aceptar(Request $request, $id)
{
    try {
        DB::beginTransaction();

        // Obtener la solicitud con todos los campos necesarios
        $solicitud = DB::table('solicitudesordenes')
            ->select('idsolicitudesordenes', 'codigo', 'estado', 'tipoorden')
            ->where('idsolicitudesordenes', $id)
            ->where('tipoorden', 'solicitud_repuesto')
            ->first();

        if (!$solicitud) {
            return response()->json([
                'success' => false,
                'message' => 'Solicitud no encontrada'
            ], 404);
        }

        // Verificar si la solicitud ya está aprobada
        if ($solicitud->estado == 'aprobada') {
            return response()->json([
                'success' => false,
                'message' => 'Esta solicitud ya ha sido aprobada anteriormente'
            ], 400);
        }

        // Obtener las ubicaciones seleccionadas del request
        $ubicacionesSeleccionadas = $request->input('ubicaciones', []);

        if (empty($ubicacionesSeleccionadas)) {
            return response()->json([
                'success' => false,
                'message' => 'No se han seleccionado ubicaciones para los repuestos'
            ], 400);
        }

        // Obtener repuestos de la solicitud
        $repuestosSolicitud = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idordenesarticulos',
                'oa.cantidad',
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
                return response()->json([
                    'success' => false,
                    'message' => "Stock insuficiente para el repuesto: {$repuesto->nombre}. Disponible: {$stockDisponible}, Solicitado: {$repuesto->cantidad}"
                ], 400);
            }
        }

        // Procesar cada repuesto
        foreach ($repuestosSolicitud as $repuesto) {
            $cantidadSolicitada = $repuesto->cantidad;
            $ubicacionId = $ubicacionesSeleccionadas[$repuesto->idArticulos] ?? null;

            if (!$ubicacionId) {
                return response()->json([
                    'success' => false,
                    'message' => "No se seleccionó ubicación para el repuesto: {$repuesto->nombre}"
                ], 400);
            }

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
                return response()->json([
                    'success' => false,
                    'message' => "Ubicación no encontrada para el repuesto: {$repuesto->nombre}"
                ], 404);
            }

            if ($stockUbicacion->cantidad < $cantidadSolicitada) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock insuficiente en la ubicación seleccionada para: {$repuesto->nombre}. Ubicación: {$stockUbicacion->ubicacion_codigo}, Disponible: {$stockUbicacion->cantidad}, Solicitado: {$cantidadSolicitada}"
                ], 400);
            }

            // Verificar si ya fue procesado
            $yaProcesado = DB::table('ordenesarticulos')
                ->where('idordenesarticulos', $repuesto->idordenesarticulos)
                ->where('estado', 1)
                ->exists();

            if ($yaProcesado) {
                return response()->json([
                    'success' => false,
                    'message' => "El repuesto {$repuesto->nombre} ya fue procesado anteriormente"
                ], 400);
            }

            // ✅ 1. DESCONTAR de rack_ubicacion_articulos (ubicación específica)
            DB::table('rack_ubicacion_articulos')
                ->where('idRackUbicacionArticulo', $stockUbicacion->idRackUbicacionArticulo)
                ->decrement('cantidad', $cantidadSolicitada);

            // ✅ 2. DESCONTAR stock total en tabla articulos
            DB::table('articulos')
                ->where('idArticulos', $repuesto->idArticulos)
                ->decrement('stock_total', $cantidadSolicitada);

            // ✅ 3. Registrar movimiento en rack_movimientos
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
                'observaciones' => "Solicitud repuesto aprobada (grupal): {$solicitud->codigo}",
                'codigo_ubicacion_origen' => $stockUbicacion->ubicacion_codigo,
                'codigo_ubicacion_destino' => null,
                'nombre_rack_origen' => $stockUbicacion->rack_nombre,
                'nombre_rack_destino' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // ✅ 4. Registrar en inventario_ingresos_clientes
            DB::table('inventario_ingresos_clientes')->insert([
                'compra_id' => null,
                'articulo_id' => $repuesto->idArticulos,
                'tipo_ingreso' => 'salida',
                'ingreso_id' => $solicitud->idsolicitudesordenes,
                'cliente_general_id' => $stockUbicacion->cliente_general_id,
                'cantidad' => -$cantidadSolicitada,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // ✅ 5. Actualizar kardex
            $this->actualizarKardexSalida($repuesto->idArticulos, $stockUbicacion->cliente_general_id, $cantidadSolicitada, $repuesto->precio_compra);

            // ✅ 6. Marcar como procesado
            DB::table('ordenesarticulos')
                ->where('idordenesarticulos', $repuesto->idordenesarticulos)
                ->update([
                    'estado' => 1,
                    'observacion' => "Ubicación utilizada: {$stockUbicacion->ubicacion_codigo} - Procesado grupalmente"
                ]);

            Log::info("✅ Repuesto procesado grupalmente - Artículo: {$repuesto->idArticulos}, Cantidad: {$cantidadSolicitada}, Ubicación: {$stockUbicacion->ubicacion_codigo}");
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
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al aceptar solicitud de repuestos (grupal): ' . $e->getMessage());
        Log::error('File: ' . $e->getFile());
        Log::error('Line: ' . $e->getLine());
        Log::error('Trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al aceptar la solicitud: ' . $e->getMessage()
        ], 500);
    }
}

public function aceptarIndividual(Request $request, $id)
{
    try {
        DB::beginTransaction();

        // Obtener la solicitud con todos los campos necesarios
        $solicitud = DB::table('solicitudesordenes')
            ->select('idsolicitudesordenes', 'codigo', 'estado', 'tipoorden')
            ->where('idsolicitudesordenes', $id)
            ->where('tipoorden', 'solicitud_repuesto')
            ->first();

        if (!$solicitud) {
            return response()->json([
                'success' => false,
                'message' => 'Solicitud no encontrada'
            ], 404);
        }

        $articuloId = $request->input('articulo_id');
        $ubicacionId = $request->input('ubicacion_id');

        if (!$articuloId || !$ubicacionId) {
            return response()->json([
                'success' => false,
                'message' => 'Datos incompletos para procesar el repuesto'
            ], 400);
        }

        // Obtener información del repuesto
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
            ], 404);
        }

        // Verificar si ya fue procesado
        $yaProcesado = DB::table('ordenesarticulos')
            ->where('idordenesarticulos', $repuesto->idordenesarticulos)
            ->where('estado', 1)
            ->exists();

        if ($yaProcesado) {
            return response()->json([
                'success' => false,
                'message' => 'Este repuesto ya fue procesado anteriormente'
            ], 400);
        }

        $cantidadSolicitada = $repuesto->cantidad;

        // Verificar stock en la ubicación seleccionada
        $stockUbicacion = DB::table('rack_ubicacion_articulos as rua')
            ->select(
                'rua.cantidad', 
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
            ], 404);
        }

        if ($stockUbicacion->cantidad < $cantidadSolicitada) {
            return response()->json([
                'success' => false,
                'message' => "Stock insuficiente en la ubicación seleccionada. Disponible: {$stockUbicacion->cantidad}, Solicitado: {$cantidadSolicitada}"
            ], 400);
        }

        // Obtener información del artículo para el kardex
        $articuloInfo = DB::table('articulos')
            ->select('precio_compra', 'precio_venta')
            ->where('idArticulos', $articuloId)
            ->first();

        if (!$articuloInfo) {
            return response()->json([
                'success' => false,
                'message' => 'Información del artículo no encontrada'
            ], 404);
        }

        // 1. Descontar del stock en la ubicación específica seleccionada
        DB::table('rack_ubicacion_articulos')
            ->where('articulo_id', $articuloId)
            ->where('rack_ubicacion_id', $ubicacionId)
            ->decrement('cantidad', $cantidadSolicitada);

        // 2. Registrar el movimiento en rack_movimientos (SALIDA)
        DB::table('rack_movimientos')->insert([
            'articulo_id' => $articuloId,
            'custodia_id' => null, // No aplica para salida
            'ubicacion_origen_id' => $ubicacionId,
            'ubicacion_destino_id' => null, // Es una salida, no hay destino
            'rack_origen_id' => $stockUbicacion->rack_id,
            'rack_destino_id' => null, // Es una salida
            'cantidad' => $cantidadSolicitada,
            'tipo_movimiento' => 'salida',
            'usuario_id' => auth()->id(),
            'observaciones' => "Solicitud repuesto aprobada (individual): {$solicitud->codigo}",
            'codigo_ubicacion_origen' => $stockUbicacion->ubicacion_codigo,
            'codigo_ubicacion_destino' => null, // Es una salida
            'nombre_rack_origen' => $stockUbicacion->rack_nombre,
            'nombre_rack_destino' => null, // Es una salida
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 3. Registrar en inventario_ingresos_clientes como SALIDA
        DB::table('inventario_ingresos_clientes')->insert([
            'compra_id' => null, // No es compra
            'articulo_id' => $articuloId,
            'tipo_ingreso' => 'salida',
            'ingreso_id' => $solicitud->idsolicitudesordenes, // ID de la solicitud como referencia
            'cliente_general_id' => $stockUbicacion->cliente_general_id, // Cliente general de la ubicación
            'cantidad' => -$cantidadSolicitada, // Negativo porque es salida
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // 4. Actualizar stock total del artículo
        DB::table('articulos')
            ->where('idArticulos', $articuloId)
            ->decrement('stock_total', $cantidadSolicitada);

        // 5. Actualizar KARDEX para la SALIDA
        $this->actualizarKardexSalida($articuloId, $stockUbicacion->cliente_general_id, $cantidadSolicitada, $articuloInfo->precio_compra);

        // 6. Marcar el repuesto como procesado
        DB::table('ordenesarticulos')
            ->where('idordenesarticulos', $repuesto->idordenesarticulos)
            ->update([
                'estado' => 1,
                'observacion' => "Ubicación utilizada: {$stockUbicacion->ubicacion_codigo} - Procesado individualmente"
            ]);

        // Verificar si todos los repuestos han sido procesados
        $repuestosPendientes = DB::table('ordenesarticulos')
            ->where('idsolicitudesordenes', $id)
            ->where('estado', 0)
            ->count();

        $todosProcesados = $repuestosPendientes == 0;

        // Si todos los repuestos han sido procesados, marcar la solicitud como aprobada
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

        return response()->json([
            'success' => true,
            'message' => 'Repuesto procesado correctamente',
            'todos_procesados' => $todosProcesados
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al procesar repuesto individual: ' . $e->getMessage());
        Log::error('File: ' . $e->getFile());
        Log::error('Line: ' . $e->getLine());
        Log::error('Trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al procesar el repuesto: ' . $e->getMessage()
        ], 500);
    }
}


private function actualizarKardexSalida($articuloId, $clienteGeneralId, $cantidadSalida, $costoUnitario)
{
    try {
        // Obtener el mes y año actual
        $fechaActual = now();
        $mesActual = $fechaActual->format('m');
        $anioActual = $fechaActual->format('Y');
        
        Log::info("📅 Procesando kardex para mes: {$mesActual}, año: {$anioActual}");

        // Buscar si existe un registro de kardex para este artículo, cliente y mes actual
        $kardexMesActual = DB::table('kardex')
            ->where('idArticulo', $articuloId)
            ->where('cliente_general_id', $clienteGeneralId)
            ->whereMonth('fecha', $mesActual)
            ->whereYear('fecha', $anioActual)
            ->first();

        if ($kardexMesActual) {
            Log::info("✅ Kardex del mes actual encontrado - ID: {$kardexMesActual->id}, actualizando...");
            
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

            Log::info("✅ Kardex actualizado - Salidas: " . ($kardexMesActual->unidades_salida + $cantidadSalida) . 
                     ", Inventario: {$nuevoInventarioActual}, Costo: {$nuevoCostoInventario}");

        } else {
            Log::info("📝 No hay kardex para este mes, creando nuevo registro...");
            
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

            Log::info("📊 Valores calculados - Inicial: {$inventarioInicial}, Actual: {$inventarioActual}, " .
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

            Log::info("✅ Nuevo kardex creado para el mes - Artículo: {$articuloId}, Cliente: {$clienteGeneralId}");
        }

        Log::info("✅ Kardex procesado correctamente - Artículo: {$articuloId}, Salida: {$cantidadSalida}");

    } catch (\Exception $e) {
        Log::error('❌ Error al actualizar kardex para salida: ' . $e->getMessage());
        Log::error('File: ' . $e->getFile());
        Log::error('Line: ' . $e->getLine());
        throw $e;
    }
}
}
