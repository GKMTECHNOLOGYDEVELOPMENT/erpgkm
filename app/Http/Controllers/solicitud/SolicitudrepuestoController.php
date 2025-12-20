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




    /**
     * Marcar un repuesto como usado
     */
    public function marcarUsado(Request $request, $solicitudId)
    {
        try {
            $request->validate([
                'articulo_id' => 'required|integer',
                'fecha_uso' => 'required|date',
                'observacion' => 'nullable|string|max:500',
                'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120' // 5MB máximo por foto
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

                // Procesar fotos si existen
                $rutaFotos = [];
                if ($request->hasFile('fotos')) {
                    foreach ($request->file('fotos') as $foto) {
                        $nombreArchivo = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                        $ruta = $foto->storeAs('evidencias_repuestos', $nombreArchivo, 'public');
                        $rutaFotos[] = $ruta;
                    }
                }

                // NO ACTUALIZAR KARDEX - ya se actualizó cuando se entregó el repuesto

                // Actualizar en la tabla ordenesarticulos
                DB::table('ordenesarticulos')
                    ->where('idsolicitudesordenes', $solicitudId)
                    ->where('idarticulos', $request->articulo_id)
                    ->update([
                        'fechaUsado' => $request->fecha_uso,
                        'fechaSinUsar' => null,
                        'observacion' => $request->observacion,
                        'fotos_evidencia' => !empty($rutaFotos) ? json_encode($rutaFotos) : null
                    ]);

                // Registrar en logs
                Log::info("Repuesto marcado como usado - Solicitud: {$solicitudId}, Repuesto: {$repuestoInfo->nombre}, Fecha: {$request->fecha_uso}, Fotos: " . count($rutaFotos));
            });

            return response()->json([
                'success' => true,
                'message' => 'Repuesto marcado como usado correctamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al marcar repuesto como usado: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al marcar el repuesto: ' . $e->getMessage()
            ], 500);
        }
    }



    /**
     * Marcar un repuesto como no usado (devolución al inventario)
     */
    public function marcarNoUsado(Request $request, $solicitudId)
    {
        try {
            $request->validate([
                'articulo_id' => 'required|integer',
                'fecha_devolucion' => 'required|date',
                'observacion' => 'nullable|string|max:500',
                'fotos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120' // 5MB máximo por foto
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

                // Procesar fotos si existen
                $rutaFotos = [];
                if ($request->hasFile('fotos')) {
                    foreach ($request->file('fotos') as $foto) {
                        $nombreArchivo = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                        $ruta = $foto->storeAs('evidencias_devoluciones', $nombreArchivo, 'public');
                        $rutaFotos[] = $ruta;
                    }
                }

                // Buscar la ubicación original donde estaba el repuesto
                $ubicacionOriginal = DB::table('rack_ubicaciones')
                    ->select('idRackUbicacion', 'codigo', 'rack_id')
                    ->where('codigo', $repuestoInfo->ubicacion_utilizada)
                    ->first();

                if (!$ubicacionOriginal) {
                    throw new \Exception('No se pudo encontrar la ubicación original del repuesto');
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
                // ESTO ES LO ÚNICO QUE NECESITAMOS HACER - NO CREAR NUEVO REGISTRO
                $registrosEliminados = DB::table('inventario_ingresos_clientes')
                    ->where('codigo_solicitud', $solicitud->codigo)
                    ->where('articulo_id', $request->articulo_id)
                    ->where('tipo_ingreso', 'salida')
                    ->delete();

                // 5. Actualizar KARDEX para la ENTRADA (devolución) - CORREGIR KARDEX
                $articuloInfo = DB::table('articulos')
                    ->select('precio_compra')
                    ->where('idArticulos', $request->articulo_id)
                    ->first();

                if ($articuloInfo) {
                    $this->actualizarKardexEntrada($request->articulo_id, $clienteGeneralId, $repuestoInfo->cantidad, $articuloInfo->precio_compra, "Devolución repuesto no usado - Solicitud: {$solicitud->codigo}");
                }

                // 6. Actualizar en la tabla ordenesarticulos
                DB::table('ordenesarticulos')
                    ->where('idsolicitudesordenes', $solicitudId)
                    ->where('idarticulos', $request->articulo_id)
                    ->update([
                        'fechaSinUsar' => $request->fecha_devolucion,
                        'fechaUsado' => null,
                        'observacion' => $request->observacion . " | Devolución completada: " . now()->format('d/m/Y H:i'),
                        'fotos_evidencia' => !empty($rutaFotos) ? json_encode($rutaFotos) : null
                    ]);

                // 7. Registrar en logs
                Log::info("Repuesto devuelto al inventario - Solicitud: {$solicitudId}, Repuesto: {$repuestoInfo->nombre}, Cantidad: {$repuestoInfo->cantidad}, Ubicación: {$ubicacionOriginal->codigo}, Cliente: {$clienteGeneralId}, Registros eliminados: {$registrosEliminados}");
            });

            return response()->json([
                'success' => true,
                'message' => 'Repuesto marcado como no usado y devuelto al inventario correctamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al marcar repuesto como no usado: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al marcar el repuesto: ' . $e->getMessage()
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
                    'modelo_id' => $articulo->modelo_id, // ← ESTE ES EL IMPORTANTE
                    'modelo_nombre' => $articulo->modelo_nombre, // ← ESTE ES EL IMPORTANTE
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
        // Obtener la solicitud con sus repuestos Y USUARIOS
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

        // Obtener información del solicitante desde la tabla usuarios
        $solicitante = DB::table('usuarios')
            ->select('idUsuario', 'Nombre', 'apellidoPaterno', 'apellidoMaterno', 'correo')
            ->where('idUsuario', $solicitud->idUsuario)
            ->where('estado', 1) // Solo usuarios activos
            ->first();

        // Obtener información del técnico (si existe)
        $tecnico = null;
        if ($solicitud->idTecnico) {
            $tecnico = DB::table('usuarios')
                ->select('idUsuario', 'Nombre', 'apellidoPaterno', 'apellidoMaterno', 'correo')
                ->where('idUsuario', $solicitud->idTecnico)
                ->where('estado', 1)
                ->first();
        }

        // Obtener lista de usuarios para la opción "otro"
        $usuarios = DB::table('usuarios')
            ->select('idUsuario', 'Nombre', 'apellidoPaterno', 'apellidoMaterno', 'correo')
            ->where('estado', 1) // Solo usuarios activos
            ->orderBy('Nombre')
            ->orderBy('apellidoPaterno')
            ->get();

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
                $entregaInfo = DB::table('repuestos_entregas as re')
                    ->select(
                        're.tipo_entrega',
                        're.usuario_destino_id',
                        'u.Nombre',
                        'u.apellidoPaterno',
                        'u.apellidoMaterno'
                    )
                    ->leftJoin('usuarios as u', 're.usuario_destino_id', '=', 'u.idUsuario')
                    ->where('re.solicitud_id', $id)
                    ->where('re.articulo_id', $repuesto->idArticulos)
                    ->first();

                $repuesto->entrega_info = $entregaInfo;
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

            if ($solicitud->estado == 'aprobada') {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta solicitud ya ha sido aprobada anteriormente'
                ], 400);
            }

            $ubicacionesSeleccionadas = $request->input('ubicaciones', []);
            if (empty($ubicacionesSeleccionadas)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se han seleccionado ubicaciones para los repuestos'
                ], 400);
            }

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

            foreach ($repuestosSolicitud as $repuesto) {
                $cantidadSolicitada = (int)$repuesto->cantidad;
                $ubicacionId = $ubicacionesSeleccionadas[$repuesto->idArticulos] ?? null;

                if (!$ubicacionId) {
                    return response()->json([
                        'success' => false,
                        'message' => "No se seleccionó ubicación para el repuesto: {$repuesto->nombre}"
                    ], 400);
                }

                $ticketInfo = DB::table('tickets')
                    ->select('numero_ticket')
                    ->where('idTickets', $repuesto->idticket)
                    ->first();

                if (!$ticketInfo) {
                    return response()->json([
                        'success' => false,
                        'message' => "Ticket no encontrado para el repuesto: {$repuesto->nombre}"
                    ], 404);
                }

                $numeroTicket = $ticketInfo->numero_ticket;

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

                if ((int)$stockUbicacion->cantidad < $cantidadSolicitada) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stock insuficiente en la ubicación seleccionada para: {$repuesto->nombre}. Ubicación: {$stockUbicacion->ubicacion_codigo}, Disponible: {$stockUbicacion->cantidad}, Solicitado: {$cantidadSolicitada}"
                    ], 400);
                }

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

                // 1) Descontar stock por PK
                DB::table('rack_ubicacion_articulos')
                    ->where('idRackUbicacionArticulo', $stockUbicacion->idRackUbicacionArticulo)
                    ->decrement('cantidad', $cantidadSolicitada);

                // ✅ Descontar de CAJAS si existen
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
                    'tipo_movimiento' => 'salida',
                    'usuario_id' => auth()->id(),
                    'observaciones' => "Solicitud repuesto aprobada (grupal): {$solicitud->codigo} - Ticket: {$numeroTicket}",
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
                DB::table('repuestos_entregas')->insert([
                    'solicitud_id' => $solicitud->idsolicitudesordenes,
                    'articulo_id' => $repuesto->idArticulos,
                    'usuario_destino_id' => $solicitud->idUsuario,
                    'tipo_entrega' => 'solicitante',
                    'cantidad' => $cantidadSolicitada,
                    'ubicacion_utilizada' => $stockUbicacion->ubicacion_codigo,
                    'usuario_entrego_id' => auth()->id(),
                    'observaciones' => "Repuesto entregado grupalmente - Ticket: {$numeroTicket}",
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

                // 7) Marcar procesado
                DB::table('ordenesarticulos')
                    ->where('idordenesarticulos', $repuesto->idordenesarticulos)
                    ->update([
                        'estado' => 1,
                        'observacion' => "Ubicación utilizada: {$stockUbicacion->ubicacion_codigo} - Procesado grupalmente - Ticket: {$numeroTicket} - Entregado al solicitante"
                    ]);

                Log::info("✅ Repuesto procesado grupalmente - Artículo: {$repuesto->idArticulos}, Cantidad: {$cantidadSolicitada}, Ubicación: {$stockUbicacion->ubicacion_codigo}, Ticket: {$numeroTicket}, Código Solicitud: {$solicitud->codigo}");
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
                'message' => 'Solicitud de repuestos aprobada correctamente. Stock descontado de las ubicaciones seleccionadas (y de cajas si existían).'
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
            $tipoDestinatario = $request->input('tipo_destinatario');
            $usuarioDestinoId = $request->input('usuario_destino_id');

            if (!$articuloId || !$ubicacionId || !$tipoDestinatario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos incompletos para procesar el repuesto'
                ], 400);
            }

            $usuarioFinalId = null;
            $tipoEntrega = '';
            $nombreDestinatario = '';

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
                    ], 400);
            }

            if (!$usuarioFinalId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo determinar el usuario destino'
                ], 400);
            }

            $destinatarioInfo = DB::table('usuarios')
                ->select('Nombre', 'apellidoPaterno', 'apellidoMaterno')
                ->where('idUsuario', $usuarioFinalId)
                ->first();

            $nombreDestinatario = $destinatarioInfo
                ? "{$destinatarioInfo->Nombre} {$destinatarioInfo->apellidoPaterno}"
                : 'Usuario no encontrado';

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

            $ticketInfo = DB::table('tickets')
                ->select('numero_ticket')
                ->where('idTickets', $repuesto->idticket)
                ->first();

            if (!$ticketInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ticket no encontrado'
                ], 404);
            }

            $numeroTicket = $ticketInfo->numero_ticket;

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

            $cantidadSolicitada = (int)$repuesto->cantidad;

            $stockUbicacion = DB::table('rack_ubicacion_articulos as rua')
                ->select(
                    'rua.cantidad',
                    'rua.idRackUbicacionArticulo', // ✅ NUEVO
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

            if ((int)$stockUbicacion->cantidad < $cantidadSolicitada) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock insuficiente en la ubicación seleccionada. Disponible: {$stockUbicacion->cantidad}, Solicitado: {$cantidadSolicitada}"
                ], 400);
            }

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

            // 1) Descontar stock por PK
            DB::table('rack_ubicacion_articulos')
                ->where('idRackUbicacionArticulo', $stockUbicacion->idRackUbicacionArticulo)
                ->decrement('cantidad', $cantidadSolicitada);

            // ✅ Descontar de CAJAS si existen
            $this->descontarDeCajasSiExisten(
                (int)$articuloId,
                (int)$ubicacionId,
                (int)$cantidadSolicitada,
                null
            );

            // 2) Movimiento
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
                'observaciones' => "Solicitud repuesto aprobada (individual): {$solicitud->codigo} - Ticket: {$numeroTicket} - Entregado a: {$nombreDestinatario} ({$tipoEntrega})",
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
            DB::table('repuestos_entregas')->insert([
                'solicitud_id' => $solicitud->idsolicitudesordenes,
                'articulo_id' => $articuloId,
                'usuario_destino_id' => $usuarioFinalId,
                'tipo_entrega' => $tipoEntrega,
                'cantidad' => $cantidadSolicitada,
                'ubicacion_utilizada' => $stockUbicacion->ubicacion_codigo,
                'usuario_entrego_id' => auth()->id(),
                'observaciones' => "Repuesto entregado individualmente - Ticket: {$numeroTicket}",
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 5) stock total
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

            // 7) marcar procesado
            DB::table('ordenesarticulos')
                ->where('idordenesarticulos', $repuesto->idordenesarticulos)
                ->update([
                    'estado' => 1,
                    'observacion' => "Ubicación utilizada: {$stockUbicacion->ubicacion_codigo} - Procesado individualmente - Ticket: {$numeroTicket} - Código Solicitud: {$solicitud->codigo} - Entregado a: {$nombreDestinatario} ({$tipoEntrega})"
                ]);

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

            Log::info("Repuesto procesado individualmente - Artículo: {$articuloId}, Cantidad: {$cantidadSolicitada}, Ticket: {$numeroTicket}, Código Solicitud: {$solicitud->codigo}, Destinatario: {$nombreDestinatario} ({$tipoEntrega})");

            return response()->json([
                'success' => true,
                'message' => "Repuesto procesado correctamente. Entregado a: {$nombreDestinatario}",
                'todos_procesados' => $todosProcesados,
                'numero_ticket' => $numeroTicket,
                'codigo_solicitud' => $solicitud->codigo,
                'destinatario' => $nombreDestinatario,
                'tipo_entrega' => $tipoEntrega
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
                ], 404);
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
                ], 400);
            }

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

            $numeroTicket = $solicitud->numeroTicket ?? 'N/A';

            $yaProcesado = DB::table('ordenesarticulos')
                ->where('idordenesarticulos', $repuesto->idordenesarticulos)
                ->where('estado', 1)
                ->exists();

            if ($yaProcesado) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este repuesto ya fue procesado para envío'
                ], 400);
            }

            $cantidadSolicitada = (int)$repuesto->cantidad;

            $stockUbicacion = DB::table('rack_ubicacion_articulos as rua')
                ->select(
                    'rua.cantidad',
                    'rua.idRackUbicacionArticulo', // ✅ NUEVO (para descontar por PK)
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

            if ((int)$stockUbicacion->cantidad < $cantidadSolicitada) {
                return response()->json([
                    'success' => false,
                    'message' => "Stock insuficiente en la ubicación seleccionada. Disponible: {$stockUbicacion->cantidad}, Solicitado: {$cantidadSolicitada}"
                ], 400);
            }

            $articuloInfo = DB::table('articulos')
                ->select('precio_compra', 'precio_venta')
                ->where('idArticulos', $articuloId)
                ->first();

            $fotoComprobantePath = null;
            if ($request->hasFile('foto_comprobante')) {
                $file = $request->file('foto_comprobante');
                $fileName = 'comprobante_' . time() . '_' . $solicitud->codigo . '_' . $articuloId . '.' . $file->getClientOriginalExtension();
                $fotoComprobantePath = $file->storeAs('comprobantes_envios', $fileName, 'public');
            }

            // 1) Descontar stock por PK
            DB::table('rack_ubicacion_articulos')
                ->where('idRackUbicacionArticulo', $stockUbicacion->idRackUbicacionArticulo)
                ->decrement('cantidad', $cantidadSolicitada);

            // ✅ Descontar de CAJAS si existen
            $this->descontarDeCajasSiExisten(
                (int)$articuloId,
                (int)$ubicacionId,
                (int)$cantidadSolicitada,
                null
            );

            // 2) Movimiento
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
                'observaciones' => "Envío a provincia: {$solicitud->codigo} - Ticket: {$numeroTicket} - Transportista: {$transportista} - Placa: {$placaVehiculo}",
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
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 5) stock total
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

            // 7) marcar procesado
            DB::table('ordenesarticulos')
                ->where('idordenesarticulos', $repuesto->idordenesarticulos)
                ->update([
                    'estado' => 1,
                    'observacion' => "Envío a provincia - Ubicación: {$stockUbicacion->ubicacion_codigo} - Ticket: {$numeroTicket} - Transportista: {$transportista} - Placa: {$placaVehiculo} - Fecha entrega: {$fechaEntregaTransporte}"
                ]);

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

            Log::info("Repuesto procesado para envío a provincia - Artículo: {$articuloId}, Cantidad: {$cantidadSolicitada}, Ticket: {$numeroTicket}, Transportista: {$transportista}");

            return response()->json([
                'success' => true,
                'message' => "Repuesto preparado para envío a provincia. Transportista: {$transportista}",
                'todos_procesados' => $todosProcesados,
                'numero_ticket' => $numeroTicket,
                'codigo_solicitud' => $solicitud->codigo,
                'transportista' => $transportista,
                'placa_vehiculo' => $placaVehiculo
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al procesar envío a provincia individual: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el envío: ' . $e->getMessage()
            ], 500);
        }
    }


    public function aceptarProvincia(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // Obtener la solicitud CON EL NÚMERO DE TICKET
            $solicitud = DB::table('solicitudesordenes')
                ->select('idsolicitudesordenes', 'codigo', 'estado', 'tipoorden', 'idUsuario', 'numeroTicket')
                ->where('idsolicitudesordenes', $id)
                ->where('tipoorden', 'solicitud_repuesto_provincia')
                ->first();

            if (!$solicitud) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solicitud no encontrada'
                ], 404);
            }

            // Verificar si ya está aprobada
            if ($solicitud->estado == 'aprobada') {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta solicitud ya ha sido aprobada anteriormente'
                ], 400);
            }

            $ubicacionesSeleccionadas = json_decode($request->input('ubicaciones'), true);
            $transportista = $request->input('transportista');
            $placaVehiculo = $request->input('placa_vehiculo');
            $fechaEntregaTransporte = $request->input('fecha_entrega_transporte');
            $observaciones = $request->input('observaciones');

            // Validaciones
            if (empty($ubicacionesSeleccionadas) || !$transportista || !$placaVehiculo || !$fechaEntregaTransporte) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos incompletos para procesar el envío grupal'
                ], 400);
            }

            // USAR EL NÚMERO DE TICKET DIRECTAMENTE
            $numeroTicket = $solicitud->numeroTicket ?? 'N/A';

            // Obtener repuestos de la solicitud - SIN JOIN CON TICKETS
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

            // Manejar la foto del comprobante (grupal)
            $fotoComprobantePath = null;
            if ($request->hasFile('foto_comprobante')) {
                $file = $request->file('foto_comprobante');
                $fileName = 'comprobante_grupal_' . time() . '_' . $solicitud->codigo . '.' . $file->getClientOriginalExtension();
                $fotoComprobantePath = $file->storeAs('comprobantes_envios', $fileName, 'public');
            }

            // Procesar cada repuesto
            foreach ($repuestosSolicitud as $repuesto) {
                $cantidadSolicitada = (int)$repuesto->cantidad;
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

                if ((int)$stockUbicacion->cantidad < $cantidadSolicitada) {
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

                // Obtener información del artículo para el kardex
                $articuloInfo = DB::table('articulos')
                    ->select('precio_compra', 'precio_venta')
                    ->where('idArticulos', $repuesto->idArticulos)
                    ->first();

                // 1. Descontar del stock (por PK)
                DB::table('rack_ubicacion_articulos')
                    ->where('idRackUbicacionArticulo', $stockUbicacion->idRackUbicacionArticulo)
                    ->decrement('cantidad', $cantidadSolicitada);

                // ✅ Descontar de CAJAS si existen (articulo + ubicacion)
                $this->descontarDeCajasSiExisten(
                    (int)$repuesto->idArticulos,
                    (int)$ubicacionId,
                    (int)$cantidadSolicitada,
                    null
                );

                // 2. Descontar stock total
                DB::table('articulos')
                    ->where('idArticulos', $repuesto->idArticulos)
                    ->decrement('stock_total', $cantidadSolicitada);

                // 3. Registrar movimiento
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

                // 4. Registrar en inventario_ingresos_clientes
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

                // 5. Registrar en la tabla de envíos a provincia
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

                // 6. Actualizar kardex
                $this->actualizarKardexSalida(
                    (int)$repuesto->idArticulos,
                    (int)$stockUbicacion->cliente_general_id,
                    (int)$cantidadSolicitada,
                    (float)$articuloInfo->precio_compra
                );

                // 7. Marcar como procesado
                DB::table('ordenesarticulos')
                    ->where('idordenesarticulos', $repuesto->idordenesarticulos)
                    ->update([
                        'estado' => 1,
                        'observacion' => "Envío a provincia (grupal) - Ubicación: {$stockUbicacion->ubicacion_codigo} - Ticket: {$numeroTicket} - Transportista: {$transportista} - Placa: {$placaVehiculo}"
                    ]);

                Log::info("✅ Repuesto procesado para envío grupal a provincia - Artículo: {$repuesto->idArticulos}, Cantidad: {$cantidadSolicitada}, Transportista: {$transportista}, Ticket: {$numeroTicket}");
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
                'message' => "Solicitud de repuestos para provincia aprobada correctamente. Todos los repuestos preparados para envío con transportista: {$transportista}. Ticket: {$numeroTicket}"
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al aceptar solicitud de repuestos para provincia (grupal): ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error al aceptar la solicitud: ' . $e->getMessage()
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
                    'cas' => 'CAS GKM',
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


    private function descontarDeCajasSiExisten(int $articuloId, int $ubicacionRackId, int $cantidad, ?int $tipoArticuloId = null): void
    {
        if ($cantidad <= 0) return;

        $q = DB::table('cajas')
            ->where('idArticulo', $articuloId)
            ->where('idubicaciones_rack', $ubicacionRackId);

        if (!is_null($tipoArticuloId)) {
            $q->where('idTipoArticulo', $tipoArticuloId);
        }

        // ✅ No todos los artículos tienen caja
        if (!(clone $q)->exists()) {
            return;
        }

        $totalEnCajas = (int)(clone $q)->sum('cantidad_actual');

        // Si hay cajas pero no alcanza: inconsistencia (mejor fallar)
        if ($totalEnCajas < $cantidad) {
            throw new \Exception(
                "Inconsistencia: cajas insuficientes para artículo {$articuloId} en ubicación {$ubicacionRackId}. " .
                    "En cajas: {$totalEnCajas}, requerido: {$cantidad}"
            );
        }

        $cajas = (clone $q)
            ->select('idCaja', 'cantidad_actual', 'estado', 'fecha_entrada')
            ->orderByRaw("CASE WHEN estado='abierta' THEN 0 ELSE 1 END")
            ->orderBy('fecha_entrada', 'asc')
            ->orderBy('idCaja', 'asc')
            ->lockForUpdate()
            ->get();

        $restante = $cantidad;

        foreach ($cajas as $caja) {
            if ($restante <= 0) break;

            $disp = (int)$caja->cantidad_actual;
            if ($disp <= 0) continue;

            $quita = min($disp, $restante);
            $nuevo = $disp - $quita;

            $upd = ['cantidad_actual' => $nuevo];

            // opcional: cerrar si queda en 0
            if ($nuevo <= 0) $upd['estado'] = 'cerrada';

            DB::table('cajas')
                ->where('idCaja', $caja->idCaja)
                ->update($upd);

            $restante -= $quita;
        }

        if ($restante > 0) {
            throw new \Exception(
                "No se pudo descontar completamente de cajas (faltan {$restante}) para artículo {$articuloId} en ubicación {$ubicacionRackId}."
            );
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
}
