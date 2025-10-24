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
}
