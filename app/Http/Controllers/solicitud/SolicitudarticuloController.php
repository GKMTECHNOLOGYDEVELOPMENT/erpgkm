<?php

namespace App\Http\Controllers\solicitud;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Models\CentroCosto;
use App\Models\Ordenesarticulo;
use App\Models\PrioridadSolicitud;
use App\Models\SolicitudAlmacen;
use App\Models\SolicitudAlmacenDetalle;
use App\Models\Solicitudesordene;
use App\Models\Tipoarea;
use App\Models\TipoSolicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Browsershot\Browsershot;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SolicitudarticuloController extends Controller
{
    public function index()
    {
        // ================================
        // LOG: Entrada al método
        // ================================
        Log::info('📌 [Solicitudes] Entrando a index()', [
            'params' => request()->all()
        ]);

        $query = DB::table('solicitudesordenes as so')
            ->select(
                'so.idsolicitudesordenes',
                'so.codigo',
                'so.codigo_cotizacion',
                'so.estado',
                'so.fechacreacion',
                'so.fecharequerida',
                'so.niveldeurgencia',
                'so.tiposervicio',
                'so.tipoorden',
                'so.cantidad as total_productos',
                'so.totalcantidadproductos',
                'so.observaciones',
                't.numero_ticket as numero_ticket',
                DB::raw("CONCAT(u.Nombre, ' ', u.apellidoPaterno) as nombre_solicitante")
            )
            ->leftJoin('usuarios as u', 'so.idusuario', '=', 'u.idUsuario')
            ->leftJoin('tickets as t', 'so.idticket', '=', 't.idTickets')
            ->whereIn('so.tipoorden', [
                'solicitud_articulo',
                'solicitud_repuesto',
                'solicitud_repuesto_provincia'
            ]);

        // ================================
        // Filtro por tipo
        // ================================
        if (request()->filled('tipo')) {
            Log::info('🔎 Filtro tipo', ['tipo' => request('tipo')]);
            $query->where('so.tipoorden', request('tipo'));
        }

        // ================================
        // Filtro por estado
        // ================================
        if (request()->filled('estado')) {
            Log::info('🔎 Filtro estado', ['estado' => request('estado')]);
            $query->where('so.estado', request('estado'));
        }

        // ================================
        // Filtro por rango de fechas (Fecha Creación)
        // ================================
        if (request()->filled('fecha_inicio') || request()->filled('fecha_fin')) {

            $fechaInicio = request('fecha_inicio')
                ? \Carbon\Carbon::parse(request('fecha_inicio'))->startOfDay()
                : null;

            $fechaFin = request('fecha_fin')
                ? \Carbon\Carbon::parse(request('fecha_fin'))->endOfDay()
                : null;

            Log::info('📅 Filtro fechas', [
                'inicio' => $fechaInicio,
                'fin' => $fechaFin
            ]);

            if ($fechaInicio && $fechaFin) {
                $query->whereBetween('so.fechacreacion', [$fechaInicio, $fechaFin]);
            } elseif ($fechaInicio) {
                $query->whereDate('so.fechacreacion', '>=', $fechaInicio);
            } elseif ($fechaFin) {
                $query->whereDate('so.fechacreacion', '<=', $fechaFin);
            }
        }


        // ================================
        // Filtro por urgencia
        // ================================
        if (request()->filled('urgencia')) {
            Log::info('🔎 Filtro urgencia', ['urgencia' => request('urgencia')]);
            $query->where('so.niveldeurgencia', request('urgencia'));
        }

        // ================================
        // Filtro por búsqueda (CORREGIDO)
        // ================================
        if (request()->filled('search')) {
            $search = request('search');

            Log::info('🔍 Búsqueda global', ['search' => $search]);

            $query->where(function ($q) use ($search) {

                // Código de solicitud
                $q->where('so.codigo', 'LIKE', "%{$search}%")

                    // Ticket (optimizado si empieza como ticket)
                    ->orWhere('t.numero_ticket', 'LIKE', "{$search}%")

                    // Nombre del solicitante
                    ->orWhere(DB::raw("CONCAT(u.Nombre, ' ', u.apellidoPaterno)"), 'LIKE', "%{$search}%");
            });
        }


        // ================================
        // LOG: SQL FINAL
        // ================================
        Log::info('🧠 SQL solicitudes', [
            'sql' => $query->toSql(),
            'bindings' => $query->getBindings()
        ]);

        // ================================
        // Ejecutar query
        // ================================
        $solicitudes = $query
            ->orderBy('so.fechacreacion', 'desc')
            ->paginate(10);

        // ================================
        // LOG: Resultados
        // ================================
        Log::info('📦 Resultado solicitudes', [
            'total' => $solicitudes->total(),
            'items' => $solicitudes->getCollection()->map(function ($s) {
                return [
                    'id' => $s->idsolicitudesordenes,
                    'codigo' => $s->codigo,
                    'ticket' => $s->numero_ticket,
                    'estado' => $s->estado,
                ];
            })
        ]);

        // ================================
        // CONTADORES
        // ================================
        $contadoresQuery = DB::table('solicitudesordenes as so')
            ->leftJoin('tickets as t', 'so.idticket', '=', 't.idTickets')
            ->leftJoin('usuarios as u', 'so.idusuario', '=', 'u.idUsuario')
            ->whereIn('so.tipoorden', [
                'solicitud_articulo',
                'solicitud_repuesto',
                'solicitud_repuesto_provincia'
            ])
            ->where('so.estado', 'pendiente');

        if (request()->filled('tipo')) {
            $contadoresQuery->where('so.tipoorden', request('tipo'));
        }

        if (request()->filled('urgencia')) {
            $contadoresQuery->where('so.niveldeurgencia', request('urgencia'));
        }

        if (request()->filled('fecha_inicio') || request()->filled('fecha_fin')) {

            $fechaInicio = request('fecha_inicio')
                ? \Carbon\Carbon::parse(request('fecha_inicio'))->startOfDay()
                : null;

            $fechaFin = request('fecha_fin')
                ? \Carbon\Carbon::parse(request('fecha_fin'))->endOfDay()
                : null;

            if ($fechaInicio && $fechaFin) {
                $contadoresQuery->whereBetween('so.fechacreacion', [$fechaInicio, $fechaFin]);
            } elseif ($fechaInicio) {
                $contadoresQuery->whereDate('so.fechacreacion', '>=', $fechaInicio);
            } elseif ($fechaFin) {
                $contadoresQuery->whereDate('so.fechacreacion', '<=', $fechaFin);
            }
        }

        if (request()->filled('search')) {
            $search = request('search');
            $contadoresQuery->where(function ($q) use ($search) {
                $q->where('so.codigo', 'LIKE', "%{$search}%")
                    ->orWhere('t.numero_ticket', 'LIKE', "{$search}%")
                    ->orWhere(DB::raw("CONCAT(u.Nombre, ' ', u.apellidoPaterno)"), 'LIKE', "%{$search}%");
            });
        }
        // LOG contadores
        Log::info('📊 SQL contadores', [
            'sql' => $contadoresQuery->toSql(),
            'bindings' => $contadoresQuery->getBindings()
        ]);

        $contadores = $contadoresQuery
            ->selectRaw("
            SUM(CASE WHEN tipoorden = 'solicitud_repuesto' THEN 1 ELSE 0 END) as repuesto_lima,
            SUM(CASE WHEN tipoorden = 'solicitud_repuesto_provincia' THEN 1 ELSE 0 END) as repuesto_provincia,
            SUM(CASE WHEN tipoorden = 'solicitud_articulo' THEN 1 ELSE 0 END) as solicitud_articulo,
            COUNT(*) as total
        ")
            ->first();

        $contadoresArray = [
            'repuesto_lima' => $contadores->repuesto_lima ?? 0,
            'repuesto_provincia' => $contadores->repuesto_provincia ?? 0,
            'solicitud_articulo' => $contadores->solicitud_articulo ?? 0,
            'total' => $contadores->total ?? 0
        ];

        Log::info('📈 Contadores calculados', $contadoresArray);

        return view('solicitud.solicitudarticulo.index', compact('solicitudes', 'contadoresArray'));
    }


    public function create()
    {
        $usuario = auth()->user()->load('tipoArea');

        // SOLO ARTÍCULOS (tipos 1, 3, 4) - SIN REPUESTOS
        $articulos = DB::table('articulos as a')
            ->select(
                'a.idArticulos',
                'a.nombre',
                'a.codigo_barras',
                'a.codigo_repuesto',
                'a.precio_compra',
                'a.stock_total',
                'a.idTipoArticulo',
                'a.idModelo',
                'a.idsubcategoria',
                'ta.nombre as tipo_articulo_nombre',
                'm.nombre as nombre_modelo',
                'mar.nombre as nombre_marca',
                'sc.nombre as nombre_subcategoria'
            )
            ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')
            ->leftJoin('modelo as m', 'a.idModelo', '=', 'm.idModelo')
            ->leftJoin('marca as mar', 'm.idMarca', '=', 'mar.idMarca')
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->where('a.estado', 1)
            ->whereIn('a.idTipoArticulo', [1, 3, 4]) // SOLO: Productos, suministros, herramientas
            ->orderBy('a.nombre')
            ->get();

        // Obtener todas las áreas
        $areas = DB::table('tipoarea')
            ->orderBy('nombre')
            ->get();

        // Obtener todos los usuarios activos
        $usuarios = DB::table('usuarios')
            ->select('idUsuario', 'Nombre', 'apellidoPaterno', 'apellidoMaterno', 'idTipoArea')
            ->where('estado', 1)
            ->orderBy('Nombre')
            ->orderBy('apellidoPaterno')
            ->get();

        // Formatear los datos de manera más simple
        $articulosFormateados = $articulos->map(function ($articulo) {
            $infoAdicional = [];

            // Información del tipo de artículo
            if ($articulo->tipo_articulo_nombre) {
                $infoAdicional[] = $articulo->tipo_articulo_nombre;
            }

            // Información del modelo y marca (si existe)
            if ($articulo->nombre_modelo) {
                $infoAdicional[] = $articulo->nombre_modelo;
            }
            if ($articulo->nombre_marca) {
                $infoAdicional[] = $articulo->nombre_marca;
            }

            // Información de subcategoría
            if ($articulo->nombre_subcategoria) {
                $infoAdicional[] = $articulo->nombre_subcategoria;
            }

            $infoTexto = $infoAdicional ? ' (' . implode(' - ', $infoAdicional) . ')' : '';
            $codigo = $articulo->codigo_barras ?: $articulo->codigo_repuesto;

            return [
                'idArticulos' => $articulo->idArticulos,
                'nombre' => $articulo->nombre,
                'codigo_barras' => $articulo->codigo_barras,
                'codigo_repuesto' => $articulo->codigo_repuesto,
                'tipo_articulo' => $articulo->tipo_articulo_nombre,
                'modelo' => $articulo->nombre_modelo,
                'marca' => $articulo->nombre_marca,
                'subcategoria' => $articulo->nombre_subcategoria,
                'nombre_completo' => $articulo->nombre . $infoTexto . ' (' . $codigo . ')'
            ];
        });

        // Obtener solo cotizaciones aprobadas que NO tengan solicitudes
        $cotizacionesAprobadas = DB::table('cotizaciones as c')
            ->select(
                'c.idCotizaciones',
                'c.numero_cotizacion',
                'c.fecha_emision',
                'c.estado_cotizacion',
                'cl.nombre as cliente_nombre'
            )
            ->leftJoin('cliente as cl', 'c.idCliente', '=', 'cl.idCliente')
            ->where('c.estado_cotizacion', 'aprobada')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('solicitudesordenes as so')
                    ->whereRaw('so.codigo_cotizacion = c.numero_cotizacion')
                    ->where('so.tipoorden', 'solicitud_articulo');
            })
            ->orderBy('c.fecha_emision', 'desc')
            ->get();

        // Obtener el próximo número de orden
        $lastOrder = DB::table('solicitudesordenes')
            ->where('tipoorden', 'solicitud_articulo')
            ->orderBy('idsolicitudesordenes', 'desc')
            ->first();

        $nextOrderNumber = $lastOrder ? (intval(substr($lastOrder->codigo, 4)) + 1) : 1;

        return view("solicitud.solicitudarticulo.create", [
            'usuario' => $usuario,
            'articulos' => $articulosFormateados,
            'cotizacionesAprobadas' => $cotizacionesAprobadas,
            'nextOrderNumber' => $nextOrderNumber,
            'areas' => $areas,
            'usuarios' => $usuarios
        ]);
    }




    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validación corregida
            $validated = $request->validate([
                'orderInfo.tipoServicio' => 'required|string',
                'orderInfo.urgencia' => 'required|string|in:baja,media,alta',
                'orderInfo.fechaRequerida' => 'required|date',
                'orderInfo.observaciones' => 'nullable|string',
                'orderInfo.areaDestino' => 'required|exists:tipoarea,idTipoArea',
                'orderInfo.usuarioDestino' => 'required|exists:usuarios,idUsuario',
                'orderInfo.esUsoDiario' => 'nullable',
                'orderInfo.observacionDevolucion' => 'nullable|string',
                'products' => 'required|array|min:1',
                'products.*.articuloId' => 'required|exists:articulos,idArticulos',
                'products.*.cantidad' => 'required|integer|min:1|max:1000',
                'products.*.descripcion' => 'nullable|string',
                'products.*.requiereDevolucion' => 'nullable',
                'products.*.fechaDevolucion' => 'nullable|date',
                'selectedCotizacion' => 'nullable|exists:cotizaciones,idCotizaciones'
            ]);

            // VALIDACIÓN CRÍTICA: Fecha de devolución no puede ser menor que fecha de requerimiento
            $fechaRequerida = $validated['orderInfo']['fechaRequerida'];

            foreach ($validated['products'] as $index => $product) {
                if (
                    isset($product['fechaDevolucion']) && $product['fechaDevolucion'] &&
                    $product['fechaDevolucion'] < $fechaRequerida
                ) {

                    $articuloNombre = DB::table('articulos')
                        ->where('idArticulos', $product['articuloId'])
                        ->value('nombre') ?? 'Artículo #' . $product['articuloId'];

                    return response()->json([
                        'success' => false,
                        'message' => "La fecha de devolución del artículo '{$articuloNombre}' " .
                            "({$product['fechaDevolucion']}) no puede ser anterior a la " .
                            "fecha de requerimiento ({$fechaRequerida})."
                    ], 422);
                }
            }

            // Convertir esUsoDiario a booleano
            $esUsoDiario = filter_var($validated['orderInfo']['esUsoDiario'] ?? false, FILTER_VALIDATE_BOOLEAN);

            // Convertir requiereDevolucion a booleano en cada producto
            foreach ($validated['products'] as &$product) {
                $product['requiereDevolucion'] = filter_var($product['requiereDevolucion'] ?? false, FILTER_VALIDATE_BOOLEAN);
            }

            // Verificar si ya existe una solicitud para esta cotización
            if (!empty($validated['selectedCotizacion'])) {
                $solicitudExistente = DB::table('solicitudesordenes')
                    ->where('codigo_cotizacion', $validated['selectedCotizacion'])
                    ->where('tipoorden', 'solicitud_articulo')
                    ->first();

                if ($solicitudExistente) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Ya existe una solicitud para esta cotización. No se puede crear otra.'
                    ], 400);
                }
            }

            // Calcular estadísticas de productos
            $totalCantidad = collect($validated['products'])->sum('cantidad');
            $totalProductosUnicos = count($validated['products']);

            // Generar código de orden
            $lastOrder = DB::table('solicitudesordenes')
                ->where('tipoorden', 'solicitud_articulo')
                ->orderBy('idsolicitudesordenes', 'desc')
                ->first();

            $nextOrderNumber = $lastOrder ? (intval(substr($lastOrder->codigo, 4)) + 1) : 1;
            $codigoOrden = 'SOL-' . str_pad($nextOrderNumber, 3, '0', STR_PAD_LEFT);

            // Obtener información de la cotización
            $codigoCotizacion = null;
            if (!empty($validated['selectedCotizacion'])) {
                $cotizacion = DB::table('cotizaciones')
                    ->where('idCotizaciones', $validated['selectedCotizacion'])
                    ->first();
                $codigoCotizacion = $cotizacion->numero_cotizacion;
            }

            // Obtener nombre completo del usuario destino
            $usuarioDestino = DB::table('usuarios')
                ->where('idUsuario', $validated['orderInfo']['usuarioDestino'])
                ->first();

            $nombreUsuarioDestino = 'Usuario #' . $validated['orderInfo']['usuarioDestino'];
            if ($usuarioDestino) {
                $nombreCompleto = trim($usuarioDestino->Nombre ?? '');
                $nombreCompleto .= ' ' . trim($usuarioDestino->apellidoPaterno ?? '');
                $nombreCompleto .= ' ' . trim($usuarioDestino->apellidoMaterno ?? '');
                $nombreCompleto = trim($nombreCompleto);

                if (!empty($nombreCompleto)) {
                    $nombreUsuarioDestino = $nombreCompleto;
                }
            }

            // Insertar en solicitudesordenes
            $solicitudId = DB::table('solicitudesordenes')->insertGetId([
                'fechacreacion' => now(),
                'estado' => 'pendiente',
                'tipoorden' => 'solicitud_articulo',
                'idticket' => null,
                'fecharequerida' => $validated['orderInfo']['fechaRequerida'],
                'fechaentrega' => $validated['orderInfo']['fechaRequerida'],
                'numeroticket' => null,
                'codigo' => $codigoOrden,
                'codigo_cotizacion' => $codigoCotizacion,
                'niveldeurgencia' => $validated['orderInfo']['urgencia'],
                'tiposervicio' => $validated['orderInfo']['tipoServicio'],
                'observaciones' => $validated['orderInfo']['observaciones'] ?? null,
                'cantidad' => $totalProductosUnicos,
                'canproduuni' => $totalProductosUnicos,
                'totalcantidadproductos' => $totalCantidad,
                'idtipoServicio' => $this->getTipoServicioId($validated['orderInfo']['tipoServicio']),
                'idtecnico' => null,
                'idusuario' => Auth::id(),
                'id_area_destino' => $validated['orderInfo']['areaDestino'],
                'id_usuario_destino' => $validated['orderInfo']['usuarioDestino'],
                'urgencia' => $validated['orderInfo']['urgencia'],
                'es_uso_diario' => $esUsoDiario ? 1 : 0,
                'observacion_devolucion' => $validated['orderInfo']['observacionDevolucion'] ?? null
            ]);
            // ============================================
            // CREAR ASIGNACIÓN PRINCIPAL
            // ============================================

            // Generar código de asignación
            $lastAsignacion = DB::table('asignaciones')->orderBy('id', 'desc')->first();
            $nextAsignacionNumber = $lastAsignacion ? ($lastAsignacion->id + 1) : 1;
            $codigoAsignacion = 'ASG-' . str_pad($nextAsignacionNumber, 3, '0', STR_PAD_LEFT);

            // Calcular estadísticas para la asignación
            $productosConDevolucion = array_filter($validated['products'], function ($p) {
                return filter_var($p['requiereDevolucion'] ?? false, FILTER_VALIDATE_BOOLEAN);
            });

            $productosSinDevolucion = array_filter($validated['products'], function ($p) {
                return !filter_var($p['requiereDevolucion'] ?? false, FILTER_VALIDATE_BOOLEAN);
            });

            // ============================================
            // DETERMINAR TIPO DE ASIGNACIÓN (CON 4 TIPOS)
            // ============================================
            $tipoAsignacion = 'trabajo_a_realizar'; // Por defecto: para trabajos/servicios

            if ($esUsoDiario) {
                $tipoAsignacion = 'uso_diario';
            } else {
                $hayArticulosConDevolucion = count($productosConDevolucion) > 0;

                if ($hayArticulosConDevolucion) {
                    $tipoAsignacion = 'prestamo';
                }
                // Si no hay artículos con devolución, se mantiene 'trabajo_a_realizar'
            }

            // Crear asignación principal
            $asignacionId = DB::table('asignaciones')->insertGetId([
                'codigo_asignacion' => $codigoAsignacion,
                'idUsuario' => $validated['orderInfo']['usuarioDestino'],
                'idSolicitud' => $solicitudId,
                'codigo_solicitud' => $codigoOrden,
                'id_area_destino' => $validated['orderInfo']['areaDestino'],
                'fecha_asignacion' => $validated['orderInfo']['fechaRequerida'],
                'fecha_devolucion' => null,
                'fecha_entrega_real' => null,
                'observaciones' => "Asignación creada desde solicitud: {$codigoOrden}. " .
                    "Usuario destino: {$nombreUsuarioDestino}. " .
                    "Área destino: " . $this->getNombreArea($validated['orderInfo']['areaDestino']) . ". " .
                    ($validated['orderInfo']['observaciones'] ?? 'Sin observaciones adicionales') .
                    ($codigoCotizacion ? " | Cotización: {$codigoCotizacion}" : '') .
                    " | Tipo: {$tipoAsignacion}" .
                    ($tipoAsignacion === 'trabajo_a_realizar' ? ' (Trabajos/Servicios)' : ($tipoAsignacion === 'prestamo' ? ' (Préstamo con devolución)' : ' (Uso diario)')),
                'estado' => 'pendiente',
                'tipo_asignacion' => $tipoAsignacion,
                'total_articulos' => $totalProductosUnicos,
                'total_cantidad' => $totalCantidad,
                'con_devolucion' => count($productosConDevolucion),
                'sin_devolucion' => count($productosSinDevolucion),
                'id_usuario_creador' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // ============================================
            // INSERTAR ARTÍCULOS EN DETALLE_ASIGNACIONES
            // ============================================
            $detallesAsignacion = [];

            foreach ($validated['products'] as $index => $product) {
                // Obtener información del artículo
                $articulo = DB::table('articulos')
                    ->where('idArticulos', $product['articuloId'])
                    ->first();

                // ============================================
                // DETERMINAR TIPO DE ARTÍCULO (CON 4 TIPOS)
                // ============================================
                $tipoArticulo = $tipoAsignacion; // Por defecto usa el mismo tipo que la asignación

                // Solo override si el artículo individual requiere devolución
                // pero la asignación general es de trabajo
                if ($tipoAsignacion === 'trabajo_a_realizar' && $product['requiereDevolucion']) {
                    $tipoArticulo = 'prestamo'; // Artículo individual con devolución en asignación de trabajo
                }

                // Calcular fecha de devolución esperada (solo para préstamos)
                $fechaDevolucionEsperada = null;
                if ($product['requiereDevolucion'] && !empty($product['fechaDevolucion'])) {
                    try {
                        $fechaDevolucionEsperada = Carbon::parse($product['fechaDevolucion'])->format('Y-m-d');
                    } catch (\Exception $e) {
                        Log::warning('Error parsing fecha_devolucion para detalle', [
                            'fecha' => $product['fechaDevolucion'],
                            'error' => $e->getMessage()
                        ]);
                    }
                }

                $detallesAsignacion[] = [
                    'asignacion_id' => $asignacionId,
                    'articulo_id' => $product['articuloId'],
                    'codigo_articulo' => $articulo->codigo ?? null,
                    'nombre_articulo' => $articulo->nombre ?? null,
                    'cantidad' => $product['cantidad'],
                    'numero_serie' => null,
                    'tipo' => $tipoArticulo,
                    'estado_articulo' => 'pendiente',
                    'fecha_entrega_esperada' => $validated['orderInfo']['fechaRequerida'],
                    'fecha_entrega_real' => null,
                    'fecha_devolucion_esperada' => $fechaDevolucionEsperada,
                    'fecha_devolucion_real' => null,
                    'requiere_devolucion' => $product['requiereDevolucion'] ? 1 : 0,
                    'observaciones' => $product['descripcion'] ?? null,
                    'id_solicitud_detalle' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
            // Insertar todos los detalles de una vez
            if (!empty($detallesAsignacion)) {
                DB::table('detalle_asignaciones')->insert($detallesAsignacion);
            }

            // ============================================
            // INSERTAR ARTÍCULOS EN ORDENESARTICULOS
            // ============================================
            foreach ($validated['products'] as $product) {
                $fechaDevolucion = null;
                if (!empty($product['fechaDevolucion'])) {
                    try {
                        $fechaDevolucion = Carbon::parse($product['fechaDevolucion'])->format('Y-m-d H:i:s');
                    } catch (\Exception $e) {
                        Log::warning('Error parsing fecha_devolucion', [
                            'fecha' => $product['fechaDevolucion'],
                            'error' => $e->getMessage()
                        ]);
                    }
                }

                DB::table('ordenesarticulos')->insert([
                    'cantidad' => $product['cantidad'],
                    'estado' => 0,
                    'observacion' => $product['descripcion'] ?? null,
                    'fotorepuesto' => null,
                    'fechausado' => null,
                    'fechasinusar' => null,
                    'idsolicitudesordenes' => $solicitudId,
                    'idticket' => null,
                    'idarticulos' => $product['articuloId'],
                    'idubicacion' => null,
                    'codigo_cotizacion' => $codigoCotizacion,
                    'requiere_devolucion' => $product['requiereDevolucion'] ? 1 : 0,
                    'fecha_devolucion_programada' => $fechaDevolucion,
                    'id_asignacion' => $asignacionId
                ]);
            }

            // ============================================
            // INSERTAR EN SOLICITUDENTREGA PARA NOTIFICACIONES
            // ============================================
            $comentarioArticulos = "Solicitud de artículos. Orden: {$codigoOrden}. ";
            $comentarioArticulos .= "Usuario destino: {$nombreUsuarioDestino}. ";
            $comentarioArticulos .= "Total productos: {$totalProductosUnicos}, Cantidad total: {$totalCantidad}. ";
            $comentarioArticulos .= "Asignación creada: {$codigoAsignacion}. ";

            if ($validated['orderInfo']['observaciones'] ?? false) {
                $comentarioArticulos .= "Observaciones: " . $validated['orderInfo']['observaciones'];
            }

            if ($codigoCotizacion) {
                $comentarioArticulos .= " | Cotización: {$codigoCotizacion}";
            }

            if (count($productosConDevolucion) > 0) {
                $comentarioArticulos .= " | Artículos con devolución: " . count($productosConDevolucion);
            }

            DB::table('solicitudentrega')->insert([
                'idTickets' => null,
                'numero_ticket' => null,
                'idVisitas' => null,
                'idUsuario' => Auth::id(),
                'comentario' => trim($comentarioArticulos),
                'estado' => 0,
                'fechaHora' => now(),
                'idTipoServicio' => 6,
                'id_asignacion' => $asignacionId
            ]);

            // ============================================
            // ACTUALIZAR ESTADO DE LA COTIZACIÓN
            // ============================================
            if (!empty($validated['selectedCotizacion'])) {
                DB::table('cotizaciones')
                    ->where('idCotizaciones', $validated['selectedCotizacion'])
                    ->update([
                        'estado_cotizacion' => 'solicitado',
                        'updated_at' => now()
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Solicitud de artículos creada exitosamente',
                'solicitud_id' => $solicitudId,
                'codigo_orden' => $codigoOrden,
                'codigo_cotizacion' => $codigoCotizacion,
                'es_uso_diario' => $esUsoDiario,
                'usuario_destino' => [
                    'id' => $validated['orderInfo']['usuarioDestino'],
                    'nombre_completo' => $nombreUsuarioDestino
                ],
                'asignacion' => [
                    'id' => $asignacionId,
                    'codigo_asignacion' => $codigoAsignacion,
                    'estado' => 'pendiente',
                    'total_articulos' => $totalProductosUnicos,
                    'total_cantidad' => $totalCantidad
                ],
                'estadisticas' => [
                    'productos_unicos' => $totalProductosUnicos,
                    'total_cantidad' => $totalCantidad,
                    'con_devolucion' => count($productosConDevolucion),
                    'sin_devolucion' => count($productosSinDevolucion)
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear solicitud de artículos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método auxiliar para obtener nombre del área
    private function getNombreArea($idArea)
    {
        try {
            $area = DB::table('tipoarea')
                ->where('idTipoArea', $idArea)
                ->first();

            return $area ? $area->descripcion : 'Área #' . $idArea;
        } catch (\Exception $e) {
            Log::warning('Error al obtener nombre de área: ' . $e->getMessage());
            return 'Área #' . $idArea;
        }
    }





    public function show($id)
    {
        $usuario = auth()->user()->load('tipoArea');

        // Obtener la solicitud existente con más información - INCLUYENDO NUEVOS CAMPOS
        $solicitud = DB::table('solicitudesordenes as so')
            ->select(
                'so.idsolicitudesordenes',
                'so.codigo',
                'so.codigo_cotizacion',
                'so.tiposervicio',
                'so.niveldeurgencia as urgencia',
                'so.fecharequerida',
                'so.observaciones',
                'so.estado',
                'so.fechacreacion',
                'so.totalcantidadproductos',
                'so.cantidad as productos_unicos',
                'so.id_area_destino',           // Nuevo campo
                'so.id_usuario_destino',        // Nuevo campo
                DB::raw("CONCAT(u.Nombre, ' ', u.apellidoPaterno) as nombre_solicitante"),
                'ta.nombre as nombre_area',
                // Información del área destino
                'ta_destino.nombre as nombre_area_destino',
                // Información del usuario destino
                'u_destino.Nombre as usuario_destino_nombre',
                'u_destino.apellidoPaterno as usuario_destino_apellido',
                'u_destino.correo as usuario_destino_correo'
            )
            ->leftJoin('usuarios as u', 'so.idusuario', '=', 'u.idUsuario')
            ->leftJoin('tipoarea as ta', 'u.idTipoArea', '=', 'ta.idTipoArea')
            ->leftJoin('tipoarea as ta_destino', 'so.id_area_destino', '=', 'ta_destino.idTipoArea') // Nuevo join
            ->leftJoin('usuarios as u_destino', 'so.id_usuario_destino', '=', 'u_destino.idUsuario') // Nuevo join
            ->where('so.idsolicitudesordenes', $id)
            ->where('so.tipoorden', 'solicitud_articulo')
            ->first();

        if (!$solicitud) {
            abort(404, 'Solicitud no encontrada');
        }

        // Obtener los artículos de la solicitud con información completa
        $articulos = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idordenesarticulos',
                'oa.cantidad',
                'oa.estado',
                'oa.observacion as descripcion',
                'oa.idarticulos',
                'a.nombre as nombre_articulo',
                'a.codigo_barras',
                'a.codigo_repuesto',
                'a.precio_compra',
                'a.idTipoArticulo',
                'a.idModelo',
                'a.idsubcategoria',
                'ta.nombre as tipo_articulo',
                'm.nombre as modelo',
                'mar.nombre as marca',
                'sc.nombre as subcategoria'
            )
            ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
            ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')
            ->leftJoin('modelo as m', 'a.idModelo', '=', 'm.idModelo')
            ->leftJoin('marca as mar', 'm.idMarca', '=', 'mar.idMarca')
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->where('oa.idsolicitudesordenes', $id)
            ->get();

        // Para los repuestos (tipo 2), obtener información adicional desde articulo_modelo
        $repuestosIds = $articulos->where('idTipoArticulo', 2)->pluck('idarticulos');

        if ($repuestosIds->count() > 0) {
            $repuestosCompletos = DB::table('articulo_modelo as am')
                ->select(
                    'am.articulo_id',
                    'm.nombre as nombre_modelo_repuesto',
                    'mar.nombre as nombre_marca_repuesto'
                )
                ->join('modelo as m', 'am.modelo_id', '=', 'm.idModelo')
                ->leftJoin('marca as mar', 'm.idMarca', '=', 'mar.idMarca')
                ->whereIn('am.articulo_id', $repuestosIds)
                ->get();

            // Actualizar la información de los repuestos
            foreach ($articulos as $articulo) {
                if ($articulo->idTipoArticulo == 2) {
                    $repuestoInfo = $repuestosCompletos->firstWhere('articulo_id', $articulo->idarticulos);
                    if ($repuestoInfo) {
                        $articulo->modelo = $repuestoInfo->nombre_modelo_repuesto;
                        $articulo->marca = $repuestoInfo->nombre_marca_repuesto;
                    }
                }
            }
        }

        return view('solicitud.solicitudarticulo.show', [
            'usuario' => $usuario,
            'solicitud' => $solicitud,
            'articulos' => $articulos // Mantenemos como colección de objetos
        ]);
    }


    public function edit($id)
    {
        $usuario = auth()->user()->load('tipoArea');

        // Obtener la solicitud existente con información de cotización
        $solicitud = DB::table('solicitudesordenes as so')
            ->select(
                'so.idsolicitudesordenes',
                'so.codigo',
                'so.codigo_cotizacion', // Este campo guarda el numero_cotizacion
                'so.tiposervicio',
                'so.niveldeurgencia as urgencia',
                'so.fecharequerida',
                'so.observaciones',
                'so.estado',
                'so.id_area_destino',           // Nuevo campo
                'so.id_usuario_destino',        // Nuevo campo
                'so.es_uso_diario',             // NUEVO: campo para uso diario
                'so.observacion_devolucion'     // NUEVO: campo para observación de devolución
            )
            ->where('so.idsolicitudesordenes', $id)
            ->where('so.tipoorden', 'solicitud_articulo')
            ->first();

        if (!$solicitud) {
            abort(404, 'Solicitud no encontrada');
        }

        // Obtener información de la cotización si existe
        $cotizacionActual = null;
        $productosCotizacion = [];

        if ($solicitud->codigo_cotizacion) {
            $cotizacionActual = DB::table('cotizaciones as c')
                ->select(
                    'c.idCotizaciones',
                    'c.numero_cotizacion',
                    'c.fecha_emision',
                    'c.estado_cotizacion',
                    'cl.nombre as cliente_nombre'
                )
                ->leftJoin('cliente as cl', 'c.idCliente', '=', 'cl.idCliente')
                ->where('c.numero_cotizacion', $solicitud->codigo_cotizacion)
                ->first();

            // Obtener productos de la cotización actual
            if ($cotizacionActual) {
                $productosCotizacion = DB::table('cotizacion_productos as cp')
                    ->select(
                        'cp.id',
                        'cp.articulo_id',
                        'cp.cantidad',
                        'cp.descripcion',
                        'cp.precio_unitario',
                        'cp.subtotal'
                    )
                    ->where('cp.cotizacion_id', $cotizacionActual->idCotizaciones)
                    ->get();
            }
        }

        // Obtener los artículos actuales de la solicitud con información completa
        $productosActuales = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idordenesarticulos',
                'oa.cantidad',
                'oa.observacion as descripcion',
                'oa.idarticulos',
                'oa.requiere_devolucion',       // NUEVO
                'oa.fecha_devolucion_programada', // NUEVO
                'a.nombre',
                'a.codigo_barras',
                'a.codigo_repuesto',
                'a.idTipoArticulo',
                'a.idModelo',
                'a.idsubcategoria',
                'ta.nombre as tipo_articulo_nombre',
                'm.nombre as nombre_modelo',
                'mar.nombre as nombre_marca',
                'sc.nombre as nombre_subcategoria'
            )
            ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
            ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')
            ->leftJoin('modelo as m', 'a.idModelo', '=', 'm.idModelo')
            ->leftJoin('marca as mar', 'm.idMarca', '=', 'mar.idMarca')
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->where('oa.idsolicitudesordenes', $id)
            ->where('oa.estado', 0) // Solo artículos pendientes
            ->get();

        // FILTRAR SOLO ARTÍCULOS (tipos 1, 3, 4) - EXCLUIR REPUESTOS (tipo 2)
        $productosActuales = $productosActuales->filter(function ($producto) {
            return in_array($producto->idTipoArticulo, [1, 3, 4]);
        })->values(); // Resetear índices

        // Obtener todos los artículos disponibles (SOLO tipos 1, 3, 4 - SIN REPUESTOS)
        $articulos = DB::table('articulos as a')
            ->select(
                'a.idArticulos',
                'a.nombre',
                'a.codigo_barras',
                'a.codigo_repuesto',
                'a.precio_compra',
                'a.stock_total',
                'a.idTipoArticulo',
                'a.idModelo',
                'a.idsubcategoria',
                'ta.nombre as tipo_articulo_nombre',
                'm.nombre as nombre_modelo',
                'mar.nombre as nombre_marca',
                'sc.nombre as nombre_subcategoria'
            )
            ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')
            ->leftJoin('modelo as m', 'a.idModelo', '=', 'm.idModelo')
            ->leftJoin('marca as mar', 'm.idMarca', '=', 'mar.idMarca')
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->where('a.estado', 1)
            ->whereIn('a.idTipoArticulo', [1, 3, 4]) // SOLO: Productos, suministros, herramientas
            ->orderBy('a.nombre')
            ->get();

        // Formatear los datos
        $articulosFormateados = $articulos->map(function ($articulo) {
            $infoAdicional = [];

            if ($articulo->tipo_articulo_nombre) {
                $infoAdicional[] = $articulo->tipo_articulo_nombre;
            }

            if ($articulo->nombre_modelo) {
                $infoAdicional[] = $articulo->nombre_modelo;
            }
            if ($articulo->nombre_marca) {
                $infoAdicional[] = $articulo->nombre_marca;
            }

            if ($articulo->nombre_subcategoria) {
                $infoAdicional[] = $articulo->nombre_subcategoria;
            }

            $infoTexto = $infoAdicional ? ' (' . implode(' - ', $infoAdicional) . ')' : '';
            $codigo = $articulo->codigo_barras ?: $articulo->codigo_repuesto;

            return [
                'idArticulos' => $articulo->idArticulos,
                'nombre' => $articulo->nombre,
                'codigo_barras' => $articulo->codigo_barras,
                'codigo_repuesto' => $articulo->codigo_repuesto,
                'tipo_articulo' => $articulo->tipo_articulo_nombre,
                'modelo' => $articulo->nombre_modelo,
                'marca' => $articulo->nombre_marca,
                'subcategoria' => $articulo->nombre_subcategoria,
                'nombre_completo' => $articulo->nombre . $infoTexto . ' (' . $codigo . ')'
            ];
        });

        // Obtener cotizaciones aprobadas que NO tengan solicitudes (excluyendo la actual)
        $cotizacionesAprobadas = DB::table('cotizaciones as c')
            ->select(
                'c.idCotizaciones',
                'c.numero_cotizacion',
                'c.fecha_emision',
                'c.estado_cotizacion',
                'cl.nombre as cliente_nombre'
            )
            ->leftJoin('cliente as cl', 'c.idCliente', '=', 'cl.idCliente')
            ->where('c.estado_cotizacion', 'aprobada')
            ->whereNotExists(function ($query) use ($solicitud) {
                $query->select(DB::raw(1))
                    ->from('solicitudesordenes as so')
                    ->whereRaw('so.codigo_cotizacion = c.numero_cotizacion')
                    ->where('so.tipoorden', 'solicitud_articulo')
                    ->where('so.idsolicitudesordenes', '!=', $solicitud->idsolicitudesordenes); // Excluir la actual
            })
            ->orderBy('c.fecha_emision', 'desc')
            ->get();

        // Obtener todas las áreas
        $areas = DB::table('tipoarea')
            ->orderBy('nombre')
            ->get();

        // Obtener todos los usuarios activos
        $usuarios = DB::table('usuarios')
            ->select('idUsuario', 'Nombre', 'apellidoPaterno', 'apellidoMaterno', 'idTipoArea')
            ->where('estado', 1)
            ->orderBy('Nombre')
            ->orderBy('apellidoPaterno')
            ->get();

        return view('solicitud.solicitudarticulo.edit', [
            'usuario' => $usuario,
            'solicitud' => $solicitud,
            'productosActuales' => $productosActuales,
            'articulos' => $articulosFormateados,
            'cotizacionesAprobadas' => $cotizacionesAprobadas,
            'cotizacionActual' => $cotizacionActual,
            'productosCotizacion' => $productosCotizacion,
            'areas' => $areas,
            'usuarios' => $usuarios
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // Validar que la solicitud existe
            $solicitud = DB::table('solicitudesordenes')
                ->where('idsolicitudesordenes', $id)
                ->where('tipoorden', 'solicitud_articulo')
                ->first();

            if (!$solicitud) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solicitud no encontrada'
                ], 404);
            }

            // Validar los datos
            $validated = $request->validate([
                'orderInfo.tipoServicio' => 'required|string',
                'orderInfo.urgencia' => 'required|string|in:baja,media,alta',
                'orderInfo.fechaRequerida' => 'required|date',
                'orderInfo.observaciones' => 'nullable|string',
                'orderInfo.areaDestino' => 'required|exists:tipoarea,idTipoArea',
                'orderInfo.usuarioDestino' => 'required|exists:usuarios,idUsuario',
                'orderInfo.esUsoDiario' => 'nullable|boolean',           // NUEVO
                'orderInfo.observacionDevolucion' => 'nullable|string',  // NUEVO
                'products' => 'required|array|min:1',
                'products.*.articuloId' => 'required|exists:articulos,idArticulos',
                'products.*.cantidad' => 'required|integer|min:1|max:1000',
                'products.*.descripcion' => 'nullable|string',
                'products.*.requiereDevolucion' => 'nullable|boolean',   // NUEVO
                'products.*.fechaDevolucion' => 'nullable|date',         // NUEVO
                'selectedCotizacion' => 'nullable|exists:cotizaciones,idCotizaciones'
            ]);

            // Si se está agregando una cotización, verificar que no tenga ya una
            if (!empty($validated['selectedCotizacion']) && !$solicitud->codigo_cotizacion) {
                // Obtener información de la cotización
                $cotizacion = DB::table('cotizaciones')
                    ->where('idCotizaciones', $validated['selectedCotizacion'])
                    ->first();

                if ($cotizacion) {
                    $codigoCotizacion = $cotizacion->numero_cotizacion;

                    // Actualizar el código de cotización en la solicitud
                    DB::table('solicitudesordenes')
                        ->where('idsolicitudesordenes', $id)
                        ->update([
                            'codigo_cotizacion' => $codigoCotizacion
                        ]);

                    // Actualizar estado de la cotización
                    DB::table('cotizaciones')
                        ->where('idCotizaciones', $validated['selectedCotizacion'])
                        ->update([
                            'estado_cotizacion' => 'solicitado',
                            'updated_at' => now()
                        ]);
                }
            }

            // Resto del código de actualización (calcular estadísticas, etc.)
            $totalCantidad = collect($validated['products'])->sum('cantidad');
            $totalProductosUnicos = count($validated['products']);

            // 1. Actualizar la solicitud principal
            DB::table('solicitudesordenes')
                ->where('idsolicitudesordenes', $id)
                ->update([
                    'fecharequerida' => $validated['orderInfo']['fechaRequerida'],
                    'fechaentrega' => $validated['orderInfo']['fechaRequerida'],
                    'niveldeurgencia' => $validated['orderInfo']['urgencia'],
                    'tiposervicio' => $validated['orderInfo']['tipoServicio'],
                    'observaciones' => $validated['orderInfo']['observaciones'] ?? null,
                    'id_area_destino' => $validated['orderInfo']['areaDestino'],
                    'id_usuario_destino' => $validated['orderInfo']['usuarioDestino'],
                    'cantidad' => $totalProductosUnicos,
                    'canproduuni' => $totalProductosUnicos,
                    'totalcantidadproductos' => $totalCantidad,
                    'idtipoServicio' => $this->getTipoServicioId($validated['orderInfo']['tipoServicio']),
                    'urgencia' => $validated['orderInfo']['urgencia'],
                    'fechaactualizacion' => now(),
                    // NUEVOS CAMPOS
                    'es_uso_diario' => $validated['orderInfo']['esUsoDiario'] ?? 0,
                    'observacion_devolucion' => $validated['orderInfo']['observacionDevolucion'] ?? null
                ]);

            // 2. Eliminar los artículos actuales (solo los pendientes)
            DB::table('ordenesarticulos')
                ->where('idsolicitudesordenes', $id)
                ->where('estado', 0) // Solo eliminar los pendientes
                ->delete();

            // 3. Insertar los nuevos artículos
            foreach ($validated['products'] as $product) {
                DB::table('ordenesarticulos')->insert([
                    'cantidad' => $product['cantidad'],
                    'estado' => 0, // 0 = pendiente
                    'observacion' => $product['descripcion'] ?? null,
                    'fotorepuesto' => null,
                    'fechausado' => null,
                    'fechasinusar' => null,
                    'idsolicitudesordenes' => $id,
                    'idticket' => null,
                    'idarticulos' => $product['articuloId'],
                    'idubicacion' => null,
                    'codigo_cotizacion' => $solicitud->codigo_cotizacion,
                    // NUEVOS CAMPOS
                    'requiere_devolucion' => $product['requiereDevolucion'] ?? 0,
                    'fecha_devolucion_programada' => isset($product['fechaDevolucion']) ?
                        Carbon::parse($product['fechaDevolucion'])->format('Y-m-d H:i:s') : null
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Solicitud de artículos actualizada exitosamente',
                'solicitud_id' => $id,
                'codigo_orden' => $solicitud->codigo,
                'estadisticas' => [
                    'productos_unicos' => $totalProductosUnicos,
                    'total_cantidad' => $totalCantidad
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al actualizar solicitud de artículos: ' . $e->getMessage());

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
                ->where('tipoorden', 'solicitud_articulo')
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

    // API para obtener próximo número de orden
    public function getNextOrderNumber()
    {
        $lastOrder = DB::table('solicitudesordenes')
            ->where('tipoorden', 'solicitud_articulo')
            ->orderBy('idsolicitudesordenes', 'desc')
            ->first();

        $nextOrderNumber = $lastOrder ? (intval(substr($lastOrder->codigo, 4)) + 1) : 1;

        return response()->json([
            'success' => true,
            'nextOrderNumber' => $nextOrderNumber
        ]);
    }

    /**
     * Obtener el ID del tipo de servicio basado en el valor
     */
    private function getTipoServicioId($tipoServicio)
    {
        $tipos = [
            'solicitud_articulo' => 5,
            'mantenimiento' => 1,
            'reparacion' => 2,
            'instalacion' => 3,
            'garantia' => 4
        ];

        return $tipos[$tipoServicio] ?? 5;
    }

    public function opciones($id)
    {
        // Obtener la solicitud con el campo idusuario incluido
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
                'so.idusuario',
                'so.id_area_destino',           // Nuevo campo
                'so.id_usuario_destino',        // Nuevo campo
                't.numero_ticket',
                'u.Nombre as nombre_solicitante',
                // Información del área destino
                'ta_destino.nombre as nombre_area_destino',
                // Información del usuario destino
                'u_destino.Nombre as usuario_destino_nombre',
                'u_destino.apellidoPaterno as usuario_destino_apellido',
                'u_destino.correo as usuario_destino_correo'
            )
            ->leftJoin('usuarios as u', 'so.idusuario', '=', 'u.idUsuario')
            ->leftJoin('tipoarea as ta_destino', 'so.id_area_destino', '=', 'ta_destino.idTipoArea') // Nuevo join
            ->leftJoin('usuarios as u_destino', 'so.id_usuario_destino', '=', 'u_destino.idUsuario') // Nuevo join
            ->leftJoin('tickets as t', 'so.idticket', '=', 't.idTickets')
            ->where('so.idsolicitudesordenes', $id)
            ->where('so.tipoorden', 'solicitud_articulo')
            ->first();

        if (!$solicitud) {
            abort(404, 'Solicitud no encontrada');
        }
        // Obtener información del solicitante
        $solicitante = DB::table('usuarios')
            ->select('idUsuario', 'Nombre', 'apellidoPaterno', 'apellidoMaterno', 'correo')
            ->where('idUsuario', $solicitud->idusuario) // ← Usar idusuario de la solicitud
            ->where('estado', 1)
            ->first();

        // Obtener lista de usuarios para la opción "otro"
        $usuarios = DB::table('usuarios')
            ->select('idUsuario', 'Nombre', 'apellidoPaterno', 'apellidoMaterno', 'correo')
            ->where('estado', 1)
            ->orderBy('Nombre')
            ->orderBy('apellidoPaterno')
            ->get();

        // Obtener los artículos de la solicitud
        $articulos = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idordenesarticulos',
                'oa.cantidad as cantidad_solicitada',
                'oa.observacion',
                'a.idArticulos',
                'a.nombre',
                'a.codigo_barras',
                'a.codigo_repuesto',
                'a.stock_total',
                'sc.nombre as tipo_articulo'
            )
            ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->where('oa.idsolicitudesordenes', $id)
            ->get();

        // Para cada artículo, obtener stock disponible y ubicaciones con detalle
        foreach ($articulos as $articulo) {
            // Obtener ubicaciones con stock detallado
            $ubicaciones = DB::table('rack_ubicacion_articulos as rua')
                ->select(
                    'rua.idRackUbicacionArticulo',
                    'rua.rack_ubicacion_id',
                    'rua.cantidad as stock_ubicacion',
                    'rua.cliente_general_id',
                    'ru.codigo as ubicacion_codigo',
                    'r.nombre as rack_nombre'
                )
                ->join('rack_ubicaciones as ru', 'rua.rack_ubicacion_id', '=', 'ru.idRackUbicacion')
                ->leftJoin('racks as r', 'ru.rack_id', '=', 'r.idRack')
                ->where('rua.articulo_id', $articulo->idArticulos)
                ->where('rua.cantidad', '>', 0)
                ->orderBy('rua.cantidad', 'desc')
                ->get();

            // Calcular stock total disponible
            $stockDisponible = $ubicaciones->sum('stock_ubicacion');

            // Agregar información al artículo
            $articulo->stock_disponible = $stockDisponible;
            $articulo->ubicaciones_detalle = $ubicaciones;
            $articulo->suficiente_stock = $stockDisponible >= $articulo->cantidad_solicitada;
            $articulo->diferencia_stock = $stockDisponible - $articulo->cantidad_solicitada;

            // Verificar si ya fue procesado individualmente
            $articulo->ya_procesado = DB::table('ordenesarticulos')
                ->where('idordenesarticulos', $articulo->idordenesarticulos)
                ->where('estado', 1)
                ->exists();

            // Si ya fue procesado, obtener información de entrega
            if ($articulo->ya_procesado) {
                $entregaInfo = DB::table('articulos_entregas as ae')
                    ->select(
                        'ae.tipo_entrega',
                        'ae.usuario_destino_id',
                        'u.Nombre',
                        'u.apellidoPaterno',
                        'u.apellidoMaterno'
                    )
                    ->leftJoin('usuarios as u', 'ae.usuario_destino_id', '=', 'u.idUsuario')
                    ->where('ae.solicitud_id', $solicitud->idsolicitudesordenes)
                    ->where('ae.articulo_id', $articulo->idArticulos)
                    ->first();

                $articulo->entrega_info = $entregaInfo;
            }
        }

        // Verificar si toda la solicitud puede ser atendida
        $puede_aceptar = $articulos->every(function ($articulo) {
            return $articulo->suficiente_stock;
        });

        // Contar artículos procesados y disponibles
        $articulos_procesados = $articulos->where('ya_procesado', true)->count();
        $articulos_disponibles = $articulos->where('suficiente_stock', true)->count();
        $total_articulos = $articulos->count();

        // Verificar si se puede generar PDF (todos los artículos procesados)
        $puede_generar_pdf = ($articulos_procesados == $total_articulos) && ($total_articulos > 0);

        return view('solicitud.solicitudarticulo.opciones', compact(
            'solicitud',
            'articulos',
            'puede_aceptar',
            'articulos_procesados',
            'articulos_disponibles',
            'total_articulos',
            'solicitante',
            'usuarios',
            'puede_generar_pdf' // ← Nueva variable

        ));
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
                'u.Nombre as nombre_solicitante'
            )
            ->leftJoin('usuarios as u', 'so.idusuario', '=', 'u.idUsuario')
            ->where('so.idsolicitudesordenes', $id)
            ->where('so.tipoorden', 'solicitud_articulo')
            ->first();

        if (!$solicitud) {
            abort(404, 'Solicitud no encontrada');
        }

        // Obtener los artículos de la solicitud que ya han sido procesados/aprobados
        $articulos = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idordenesarticulos',
                'oa.cantidad as cantidad_solicitada',
                'oa.observacion',
                'oa.estado as estado_articulo',
                'oa.fechaUsado',
                'oa.fechaSinUsar',
                'a.idArticulos',
                'a.nombre',
                'a.codigo_barras',
                'a.codigo_repuesto',
                'sc.nombre as tipo_articulo'
            )
            ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->where('oa.idsolicitudesordenes', $id)
            ->where('oa.estado', 1) // Solo artículos ya procesados
            ->get();

        // Determinar el estado actual de cada artículo basado en las fechas
        $estadosArticulos = [];
        foreach ($articulos as $articulo) {
            if ($articulo->fechaUsado) {
                $estadosArticulos[$articulo->idArticulos] = 'usado';
            } elseif ($articulo->fechaSinUsar) {
                $estadosArticulos[$articulo->idArticulos] = 'no_usado';
            } else {
                $estadosArticulos[$articulo->idArticulos] = 'pendiente';
            }
        }

        // Contadores para el resumen
        $contadores = [
            'usados' => 0,
            'no_usados' => 0,
            'pendientes' => 0
        ];

        // Contar los estados
        foreach ($estadosArticulos as $estado) {
            if ($estado === 'usado') {
                $contadores['usados']++;
            } elseif ($estado === 'no_usado') {
                $contadores['no_usados']++;
            } else {
                $contadores['pendientes']++;
            }
        }

        return view('solicitud.solicitudarticulo.gestionar', compact(
            'solicitud',
            'articulos',
            'estadosArticulos',
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

                // Obtener información del artículo
                $articuloInfo = DB::table('ordenesarticulos as oa')
                    ->select(
                        'oa.idordenesarticulos',
                        'oa.cantidad',
                        'a.idArticulos',
                        'a.nombre'
                    )
                    ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
                    ->where('oa.idsolicitudesordenes', $solicitudId)
                    ->where('oa.idarticulos', $request->articulo_id)
                    ->first();

                if (!$articuloInfo) {
                    throw new \Exception('Artículo no encontrado en la solicitud');
                }

                // 1. Primero, limpiar fotos anteriores de "usado" para este artículo
                DB::table('ordenes_articulos_fotos')
                    ->where('orden_articulo_id', $articuloInfo->idordenesarticulos)
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
                                'orden_articulo_id' => $articuloInfo->idordenesarticulos,
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
                        // Mantener compatibilidad si necesitas
                        'fotos_evidencia' => null,
                        'updated_at' => now()
                    ]);

                Log::info("Artículo marcado como usado - Solicitud: {$solicitudId}, " .
                    "Artículo: {$articuloInfo->nombre}, " .
                    "Fotos procesadas: " . ($request->hasFile('fotos') ? count($request->file('fotos')) : 0));
            });

            return response()->json([
                'success' => true,
                'message' => 'Artículo marcado como usado correctamente',
                'fotos_guardadas' => $request->hasFile('fotos') ? count($request->file('fotos')) : 0
            ]);
        } catch (\Exception $e) {
            Log::error('Error al marcar artículo como usado: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al marcar el artículo: ' . $e->getMessage(),
                'fotos_guardadas' => 0
            ], 500);
        }
    }














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

                // Obtener información del artículo y entrega
                $articuloInfo = DB::table('ordenesarticulos as oa')
                    ->select(
                        'oa.idordenesarticulos',
                        'oa.cantidad',
                        'a.idArticulos',
                        'a.nombre',
                        'ae.ubicacion_utilizada'
                    )
                    ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
                    ->leftJoin('articulos_entregas as ae', function ($join) use ($solicitudId) {
                        $join->on('ae.solicitud_id', '=', 'oa.idsolicitudesordenes')
                            ->on('ae.articulo_id', '=', 'oa.idarticulos');
                    })
                    ->where('oa.idsolicitudesordenes', $solicitudId)
                    ->where('oa.idarticulos', $request->articulo_id)
                    ->first();

                if (!$articuloInfo) {
                    throw new \Exception('Artículo no encontrado en la solicitud');
                }

                // ========================
                // 🆕 CORRECCIÓN: GUARDAR FOTOS EN TABLA SEPARADA
                // ========================

                // 1. Primero eliminar fotos anteriores de "no_usado" para este artículo
                DB::table('ordenes_articulos_fotos')
                    ->where('orden_articulo_id', $articuloInfo->idordenesarticulos)
                    ->where('tipo_foto', 'no_usado')
                    ->delete();

                Log::info("Fotos anteriores eliminadas para artículo (no usado): " . $articuloInfo->idordenesarticulos);

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
                                Log::warning("Se alcanzó el límite de 5 fotos para la devolución: " . $articuloInfo->nombre);
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
                                'orden_articulo_id' => $articuloInfo->idordenesarticulos,
                                'tipo_foto' => 'no_usado',
                                'nombre_archivo' => $foto->getClientOriginalName(),
                                'mime_type' => $foto->getMimeType(),
                                'datos' => $contenidoBinario,
                                'fecha_subida' => now()
                            ]);

                            $fotosGuardadas++;

                            Log::debug("✅ Foto guardada en tabla separada (no usado): {$foto->getClientOriginalName()}, " .
                                "Tamaño: " . strlen($contenidoBinario) . " bytes, " .
                                "ID Artículo: {$articuloInfo->idordenesarticulos}");
                        } catch (\Exception $e) {
                            Log::error("Error procesando foto {$index} (no usado): " . $e->getMessage());
                            continue;
                        }
                    }
                }

                // ========================
                // RESTO DEL CÓDIGO PARA DEVOLUCIÓN DE ARTÍCULOS
                // ========================

                // Buscar la ubicación original donde estaba el artículo
                $ubicacionOriginal = DB::table('rack_ubicaciones')
                    ->select('idRackUbicacion', 'codigo', 'rack_id')
                    ->where('codigo', $articuloInfo->ubicacion_utilizada)
                    ->first();

                if (!$ubicacionOriginal) {
                    throw new \Exception('No se pudo encontrar la ubicación original del artículo. Ubicación: ' . ($articuloInfo->ubicacion_utilizada ?? 'NULL'));
                }

                // Obtener información del rack
                $rackInfo = DB::table('racks')
                    ->select('nombre')
                    ->where('idRack', $ubicacionOriginal->rack_id)
                    ->first();

                // 1. INCREMENTAR stock en rack_ubicacion_articulos (ubicación original)
                $rackUbicacionArticulo = DB::table('rack_ubicacion_articulos')
                    ->where('rack_ubicacion_id', $ubicacionOriginal->idRackUbicacion)
                    ->where('articulo_id', $request->articulo_id)
                    ->first();

                if ($rackUbicacionArticulo) {
                    // Si ya existe registro, incrementar
                    DB::table('rack_ubicacion_articulos')
                        ->where('idRackUbicacionArticulo', $rackUbicacionArticulo->idRackUbicacionArticulo)
                        ->increment('cantidad', $articuloInfo->cantidad);
                } else {
                    // Si no existe, crear nuevo registro
                    DB::table('rack_ubicacion_articulos')->insert([
                        'rack_ubicacion_id' => $ubicacionOriginal->idRackUbicacion,
                        'articulo_id' => $request->articulo_id,
                        'cantidad' => $articuloInfo->cantidad,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                // 2. INCREMENTAR stock total en tabla articulos
                DB::table('articulos')
                    ->where('idArticulos', $request->articulo_id)
                    ->increment('stock_total', $articuloInfo->cantidad);

                // 3. Registrar movimiento en rack_movimientos (ENTRADA por devolución)
                DB::table('rack_movimientos')->insert([
                    'articulo_id' => $request->articulo_id,
                    'custodia_id' => null,
                    'ubicacion_origen_id' => null,
                    'ubicacion_destino_id' => $ubicacionOriginal->idRackUbicacion,
                    'rack_origen_id' => null,
                    'rack_destino_id' => $ubicacionOriginal->rack_id,
                    'cantidad' => $articuloInfo->cantidad,
                    'tipo_movimiento' => 'entrada',
                    'usuario_id' => auth()->id(),
                    'observaciones' => "Devolución artículo no usado - Solicitud: {$solicitud->codigo} - Observación: {$request->observacion}",
                    'codigo_ubicacion_origen' => null,
                    'codigo_ubicacion_destino' => $ubicacionOriginal->codigo,
                    'nombre_rack_origen' => null,
                    'nombre_rack_destino' => $rackInfo->nombre ?? 'Desconocido',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // 4. ELIMINAR registro en inventario_ingresos_clientes (donde se registró la salida)
                $registrosEliminados = DB::table('inventario_ingresos_clientes')
                    ->where('ingreso_id', $solicitudId)
                    ->where('articulo_id', $request->articulo_id)
                    ->where('tipo_ingreso', 'salida')
                    ->delete();

                // 5. Crear array de datos para actualizar
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

                // 6. Registrar en logs
                Log::info("✅ Artículo devuelto al inventario - Solicitud: {$solicitudId}, " .
                    "Artículo: {$articuloInfo->nombre}, Cantidad: {$articuloInfo->cantidad}, " .
                    "Ubicación: {$ubicacionOriginal->codigo}, " .
                    "Fotos subidas: {$totalFotos}, " .
                    "Fotos guardadas en tabla separada: {$fotosGuardadas}");
            });

            return response()->json([
                'success' => true,
                'message' => 'Artículo marcado como no usado y devuelto al inventario correctamente',
                'fotos_subidas' => $totalFotos,
                'fotos_guardadas' => $fotosGuardadas,
                'limite_fotos' => 5
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Error al marcar artículo como no usado: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());

            return response()->json([
                'success' => false,
                'message' => 'Error al marcar el artículo: ' . $e->getMessage(),
                'fotos_subidas' => 0,
                'fotos_guardadas' => 0
            ], 500);
        }
    }




    private function comprimirImagenSimple($contenidoBinario)
    {
        try {
            // Crear imagen desde el binario
            $imagen = imagecreatefromstring($contenidoBinario);
            if (!$imagen) {
                return $contenidoBinario; // Retornar original si no se pudo procesar
            }

            // Capturar contenido comprimido en buffer
            ob_start();

            // Guardar como JPEG con calidad 80% (puedes ajustar)
            imagejpeg($imagen, null, 80);
            $contenidoComprimido = ob_get_clean();

            // Liberar memoria
            imagedestroy($imagen);

            // Si la compresión es exitosa, retornarla
            if ($contenidoComprimido && strlen($contenidoComprimido) > 0) {
                return $contenidoComprimido;
            }

            return $contenidoBinario;
        } catch (\Exception $e) {
            Log::warning("Error en compresión de imagen: " . $e->getMessage());
            return $contenidoBinario;
        }
    }



    public function aceptarIndividual(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // Obtener la solicitud con todos los campos necesarios (INCLUYENDO id_usuario_destino)
            $solicitud = DB::table('solicitudesordenes')
                ->select('idsolicitudesordenes', 'codigo', 'estado', 'tipoorden', 'idusuario', 'id_usuario_destino')
                ->where('idsolicitudesordenes', $id)
                ->where('tipoorden', 'solicitud_articulo')
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
                    'message' => 'Datos incompletos para procesar el artículo'
                ], 400);
            }

            // Obtener información del artículo
            $articulo = DB::table('ordenesarticulos as oa')
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

            if (!$articulo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Artículo no encontrado en la solicitud'
                ], 404);
            }

            // Verificar si ya fue procesado
            $yaProcesado = DB::table('ordenesarticulos')
                ->where('idordenesarticulos', $articulo->idordenesarticulos)
                ->where('estado', 1)
                ->exists();

            if ($yaProcesado) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este artículo ya fue procesado anteriormente'
                ], 400);
            }

            $cantidadSolicitada = $articulo->cantidad;

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
                ->where('rua.articulo_id', $articuloId)
                ->where('rua.rack_ubicacion_id', $ubicacionId)
                ->first();

            if (!$stockUbicacion) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ubicación no encontrada para este artículo'
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
                ->select('precio_compra', 'precio_venta', 'stock_total')
                ->where('idArticulos', $articuloId)
                ->first();

            if (!$articuloInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Información del artículo no encontrada'
                ], 404);
            }

            // ✅ 1. DESCONTAR de rack_ubicacion_articulos (ubicación específica)
            DB::table('rack_ubicacion_articulos')
                ->where('idRackUbicacionArticulo', $stockUbicacion->idRackUbicacionArticulo)
                ->decrement('cantidad', $cantidadSolicitada);

            // ✅ 2. DESCONTAR stock total en tabla articulos
            DB::table('articulos')
                ->where('idArticulos', $articuloId)
                ->decrement('stock_total', $cantidadSolicitada);

            // ✅ 3. Registrar el movimiento en rack_movimientos (SALIDA)
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
                'observaciones' => "Solicitud artículo aprobada (individual): {$solicitud->codigo}",
                'codigo_ubicacion_origen' => $stockUbicacion->ubicacion_codigo,
                'codigo_ubicacion_destino' => null,
                'nombre_rack_origen' => $stockUbicacion->rack_nombre,
                'nombre_rack_destino' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // ✅ 4. Registrar en inventario_ingresos_clientes como SALIDA
            DB::table('inventario_ingresos_clientes')->insert([
                'compra_id' => null,
                'articulo_id' => $articuloId,
                'tipo_ingreso' => 'salida',
                'ingreso_id' => $solicitud->idsolicitudesordenes,
                'cliente_general_id' => $stockUbicacion->cliente_general_id,
                'cantidad' => -$cantidadSolicitada,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // ✅ 5. Actualizar KARDEX para la SALIDA
            $this->actualizarKardexSalida($articuloId, $stockUbicacion->cliente_general_id, $cantidadSolicitada, $articuloInfo->precio_compra);

            // Determinar el usuario destino final
            $usuarioFinalId = null;
            $tipoEntrega = '';
            $nombreDestinatario = '';

            switch ($request->tipo_destinatario) {
                case 'destino':
                    // Usuario Destino Original
                    if (!$solicitud->id_usuario_destino) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No se ha definido un usuario destino en la solicitud'
                        ], 400);
                    }
                    $usuarioFinalId = $solicitud->id_usuario_destino;
                    $tipoEntrega = 'destino';
                    break;

                case 'solicitante':
                    $usuarioFinalId = $solicitud->idusuario;
                    $tipoEntrega = 'solicitante';
                    break;

                case 'otro':
                    $usuarioFinalId = $request->usuario_destino_id;
                    $tipoEntrega = 'otro_usuario';
                    break;

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Tipo de destinatario no válido'
                    ], 400);
            }

            // Obtener nombre del destinatario
            $destinatarioInfo = DB::table('usuarios')
                ->select('Nombre', 'apellidoPaterno', 'apellidoMaterno')
                ->where('idUsuario', $usuarioFinalId)
                ->first();

            if (!$destinatarioInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario destinatario no encontrado'
                ], 404);
            }

            $nombreDestinatario = "{$destinatarioInfo->Nombre} {$destinatarioInfo->apellidoPaterno}";

            // ✅ 6. Registrar en articulos_entregas (NUEVA TABLA)
            DB::table('articulos_entregas')->insert([
                'solicitud_id' => $solicitud->idsolicitudesordenes,
                'articulo_id' => $articuloId,
                'usuario_destino_id' => $usuarioFinalId,
                'tipo_entrega' => $tipoEntrega,
                'cantidad' => $cantidadSolicitada,
                'ubicacion_utilizada' => $stockUbicacion->ubicacion_codigo,
                'fecha_entrega' => now(),
                'usuario_entrego_id' => auth()->id(),
                'observaciones' => "Artículo entregado individualmente",
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // ✅ 7. Marcar el artículo como procesado
            DB::table('ordenesarticulos')
                ->where('idordenesarticulos', $articulo->idordenesarticulos)
                ->update([
                    'estado' => 1,
                    'observacion' => "Ubicación utilizada: {$stockUbicacion->ubicacion_codigo} - Procesado individualmente - Entregado a: {$nombreDestinatario}"
                ]);

            // Verificar si todos los artículos han sido procesados
            $articulosPendientes = DB::table('ordenesarticulos')
                ->where('idsolicitudesordenes', $id)
                ->where('estado', 0)
                ->count();

            $todosProcesados = $articulosPendientes == 0;

            // Si todos los artículos han sido procesados, marcar la solicitud como aprobada
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
                'message' => "Artículo procesado correctamente. Entregado a: {$nombreDestinatario}",
                'todos_procesados' => $todosProcesados,
                'destinatario' => $nombreDestinatario,
                'tipo_entrega' => $tipoEntrega,
                'puede_generar_pdf' => $todosProcesados
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al procesar artículo individual: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el artículo: ' . $e->getMessage()
            ], 500);
        }
    }




    public function aceptar(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // Obtener la solicitud con todos los campos necesarios (INCLUYENDO id_usuario_destino)
            $solicitud = DB::table('solicitudesordenes')
                ->select('idsolicitudesordenes', 'codigo', 'estado', 'tipoorden', 'idusuario', 'id_usuario_destino')
                ->where('idsolicitudesordenes', $id)
                ->where('tipoorden', 'solicitud_articulo')
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

            // Obtener la asignación relacionada
            $asignacion = DB::table('asignaciones')
                ->where('idSolicitud', $solicitud->idsolicitudesordenes)
                ->where('codigo_solicitud', $solicitud->codigo)
                ->first();

            if (!$asignacion) {
                Log::warning("No se encontró asignación para la solicitud: {$solicitud->codigo}");
            }

            // Obtener las ubicaciones seleccionadas del request
            $ubicacionesSeleccionadas = $request->input('ubicaciones', []);

            if (empty($ubicacionesSeleccionadas)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se han seleccionado ubicaciones para los artículos'
                ], 400);
            }

            // Determinar el usuario destino para el procesamiento grupal
            $usuarioFinalId = null;
            $tipoEntrega = '';
            $nombreDestinatario = '';

            switch ($request->tipo_destinatario) {
                case 'destino':
                    // Usuario Destino Original
                    if (!$solicitud->id_usuario_destino) {
                        return response()->json([
                            'success' => false,
                            'message' => 'No se ha definido un usuario destino en la solicitud'
                        ], 400);
                    }
                    $usuarioFinalId = $solicitud->id_usuario_destino;
                    $tipoEntrega = 'destino';
                    break;

                case 'solicitante':
                    $usuarioFinalId = $solicitud->idusuario;
                    $tipoEntrega = 'solicitante';
                    break;

                case 'otro':
                    $usuarioFinalId = $request->usuario_destino_id;
                    $tipoEntrega = 'otro_usuario';
                    break;

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Tipo de destinatario no válido'
                    ], 400);
            }

            // Obtener nombre del destinatario
            $destinatarioInfo = DB::table('usuarios')
                ->select('Nombre', 'apellidoPaterno', 'apellidoMaterno')
                ->where('idUsuario', $usuarioFinalId)
                ->first();

            if (!$destinatarioInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario destinatario no encontrado'
                ], 404);
            }

            $nombreDestinatario = "{$destinatarioInfo->Nombre} {$destinatarioInfo->apellidoPaterno}";

            // Obtener artículos de la solicitud
            $articulosSolicitud = DB::table('ordenesarticulos as oa')
                ->select(
                    'oa.idordenesarticulos',
                    'oa.cantidad',
                    'oa.id_asignacion', // ← Nuevo campo
                    'a.idArticulos',
                    'a.nombre',
                    'a.stock_total',
                    'a.precio_compra'
                )
                ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
                ->where('oa.idsolicitudesordenes', $id)
                ->get();

            // Verificar que todos los artículos tengan stock suficiente
            foreach ($articulosSolicitud as $articulo) {
                $stockDisponible = DB::table('rack_ubicacion_articulos')
                    ->where('articulo_id', $articulo->idArticulos)
                    ->sum('cantidad');

                if ($stockDisponible < $articulo->cantidad) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stock insuficiente para el artículo: {$articulo->nombre}. Disponible: {$stockDisponible}, Solicitado: {$articulo->cantidad}"
                    ], 400);
                }
            }

            // ============================================
            // PROCESAR CADA ARTÍCULO
            // ============================================
            foreach ($articulosSolicitud as $articulo) {
                $cantidadSolicitada = $articulo->cantidad;
                $ubicacionId = $ubicacionesSeleccionadas[$articulo->idArticulos] ?? null;

                if (!$ubicacionId) {
                    return response()->json([
                        'success' => false,
                        'message' => "No se seleccionó ubicación para el artículo: {$articulo->nombre}"
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
                    ->where('rua.articulo_id', $articulo->idArticulos)
                    ->where('rua.rack_ubicacion_id', $ubicacionId)
                    ->first();

                if (!$stockUbicacion) {
                    return response()->json([
                        'success' => false,
                        'message' => "Ubicación no encontrada para el artículo: {$articulo->nombre}"
                    ], 404);
                }

                if ($stockUbicacion->cantidad < $cantidadSolicitada) {
                    return response()->json([
                        'success' => false,
                        'message' => "Stock insuficiente en la ubicación seleccionada para: {$articulo->nombre}. Ubicación: {$stockUbicacion->ubicacion_codigo}, Disponible: {$stockUbicacion->cantidad}, Solicitado: {$cantidadSolicitada}"
                    ], 400);
                }

                // Verificar si ya fue procesado
                $yaProcesado = DB::table('ordenesarticulos')
                    ->where('idordenesarticulos', $articulo->idordenesarticulos)
                    ->where('estado', 1)
                    ->exists();

                if ($yaProcesado) {
                    return response()->json([
                        'success' => false,
                        'message' => "El artículo {$articulo->nombre} ya fue procesado anteriormente"
                    ], 400);
                }

                // ✅ 1. DESCONTAR de rack_ubicacion_articulos
                DB::table('rack_ubicacion_articulos')
                    ->where('idRackUbicacionArticulo', $stockUbicacion->idRackUbicacionArticulo)
                    ->decrement('cantidad', $cantidadSolicitada);

                // ✅ 2. DESCONTAR stock total en tabla articulos
                DB::table('articulos')
                    ->where('idArticulos', $articulo->idArticulos)
                    ->decrement('stock_total', $cantidadSolicitada);

                // ✅ 3. Registrar movimiento en rack_movimientos
                DB::table('rack_movimientos')->insert([
                    'articulo_id' => $articulo->idArticulos,
                    'custodia_id' => null,
                    'ubicacion_origen_id' => $ubicacionId,
                    'ubicacion_destino_id' => null,
                    'rack_origen_id' => $stockUbicacion->rack_id,
                    'rack_destino_id' => null,
                    'cantidad' => $cantidadSolicitada,
                    'tipo_movimiento' => 'salida',
                    'usuario_id' => auth()->id(),
                    'observaciones' => "Solicitud artículo aprobada (grupal): {$solicitud->codigo}",
                    'codigo_ubicacion_origen' => $stockUbicacion->ubicacion_codigo,
                    'codigo_ubicacion_destino' => null,
                    'nombre_rack_origen' => $stockUbicacion->rack_nombre,
                    'nombre_rack_destino' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // ✅ 4. Registrar en articulos_entregas
                DB::table('articulos_entregas')->insert([
                    'solicitud_id' => $solicitud->idsolicitudesordenes,
                    'articulo_id' => $articulo->idArticulos,
                    'usuario_destino_id' => $usuarioFinalId,
                    'tipo_entrega' => $tipoEntrega,
                    'cantidad' => $cantidadSolicitada,
                    'ubicacion_utilizada' => $stockUbicacion->ubicacion_codigo,
                    'fecha_entrega' => now(),
                    'usuario_entrego_id' => auth()->id(),
                    'observaciones' => "Artículo entregado grupalmente",
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // ✅ 5. Registrar en inventario_ingresos_clientes
                DB::table('inventario_ingresos_clientes')->insert([
                    'compra_id' => null,
                    'articulo_id' => $articulo->idArticulos,
                    'tipo_ingreso' => 'salida',
                    'ingreso_id' => $solicitud->idsolicitudesordenes,
                    'cliente_general_id' => $stockUbicacion->cliente_general_id,
                    'cantidad' => -$cantidadSolicitada,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // ✅ 6. Actualizar kardex
                $this->actualizarKardexSalida($articulo->idArticulos, $stockUbicacion->cliente_general_id, $cantidadSolicitada, $articulo->precio_compra);

                // ✅ 7. Marcar como procesado
                DB::table('ordenesarticulos')
                    ->where('idordenesarticulos', $articulo->idordenesarticulos)
                    ->update([
                        'estado' => 1,
                        'observacion' => "Ubicación utilizada: {$stockUbicacion->ubicacion_codigo} - Procesado grupalmente - Entregado a: {$nombreDestinatario}"
                    ]);

                // ============================================
                // ✅ ACTUALIZAR DETALLE_ASIGNACIONES (CORREGIDO)
                // ============================================
                if ($asignacion) {
                    // Obtener el tipo de la asignación principal
                    $tipoAsignacion = $asignacion->tipo_asignacion ?? 'trabajo_a_realizar';

                    // El tipo del artículo generalmente sigue el tipo de la asignación
                    $tipoArticulo = $tipoAsignacion;

                    // Consultar si el artículo requiere devolución
                    $articuloDetalle = DB::table('ordenesarticulos')
                        ->select('requiere_devolucion', 'fecha_devolucion_programada')
                        ->where('idordenesarticulos', $articulo->idordenesarticulos)
                        ->first();

                    // Solo override si el artículo individual requiere devolución 
                    // pero la asignación es de trabajo
                    if ($tipoAsignacion === 'trabajo_a_realizar' && $articuloDetalle && $articuloDetalle->requiere_devolucion) {
                        $tipoArticulo = 'prestamo';
                    } elseif ($tipoAsignacion === 'prestamo' && (!$articuloDetalle || !$articuloDetalle->requiere_devolucion)) {
                        // Si la asignación es préstamo pero el artículo no requiere devolución,
                        // dejarlo como préstamo (porque la asignación ya tiene otros artículos con devolución)
                        $tipoArticulo = 'prestamo';
                    }

                    DB::table('detalle_asignaciones')
                        ->where('asignacion_id', $asignacion->id)
                        ->where('articulo_id', $articulo->idArticulos)
                        ->update([
                            'estado_articulo' => 'activo',
                            'fecha_entrega_real' => now()->format('Y-m-d'),
                            'tipo' => $tipoArticulo,
                            'requiere_devolucion' => $articuloDetalle ? $articuloDetalle->requiere_devolucion : 0,
                            'fecha_devolucion_esperada' => $articuloDetalle ? $articuloDetalle->fecha_devolucion_programada : null,
                            'updated_at' => now()
                        ]);
                }

                Log::info("✅ Artículo procesado grupalmente - Artículo: {$articulo->idArticulos}, Cantidad: {$cantidadSolicitada}, Ubicación: {$stockUbicacion->ubicacion_codigo}");
            }

            // ============================================
            // ✅ ACTUALIZAR ASIGNACIÓN PRINCIPAL (NUEVO)
            // ============================================
            if ($asignacion) {
                DB::table('asignaciones')
                    ->where('id', $asignacion->id)
                    ->update([
                        'estado' => 'activo',
                        'fecha_entrega_real' => now()->format('Y-m-d'),
                        'updated_at' => now()
                    ]);

                Log::info("✅ Asignación actualizada: {$asignacion->codigo_asignacion} - Estado: activo");
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
                'message' => "Solicitud de artículos aprobada correctamente. Entregado a: {$nombreDestinatario}",
                'destinatario' => $nombreDestinatario,
                'tipo_entrega' => $tipoEntrega,
                'asignacion_actualizada' => $asignacion ? true : false,
                'codigo_asignacion' => $asignacion ? $asignacion->codigo_asignacion : null
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al aceptar solicitud de artículos (grupal): ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al aceptar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método para actualizar kardex (Mismo que para repuestos)
    private function actualizarKardexSalida($articuloId, $clienteGeneralId, $cantidadSalida, $costoUnitario)
    {
        try {
            // Obtener el mes y año actual
            $fechaActual = now();
            $mesActual = $fechaActual->format('m');
            $anioActual = $fechaActual->format('Y');

            Log::info("📅 Procesando kardex para artículo - mes: {$mesActual}, año: {$anioActual}");

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


    public function generarConformidad($id)
    {
        try {
            $solicitud = DB::table('solicitudesordenes as so')
                ->select(
                    'so.idsolicitudesordenes',
                    'so.codigo',
                    'so.codigo_cotizacion',
                    'so.tiposervicio',
                    'so.niveldeurgencia',
                    'so.fechacreacion',
                    'so.fecharequerida',
                    'so.fechaaprobacion',
                    'so.observaciones',
                    'so.cantidad',
                    'so.totalcantidadproductos',
                    'so.idusuario',
                    'so.estado',
                    'u_solicitante.Nombre as solicitante_nombre',
                    'u_solicitante.apellidoPaterno as solicitante_apellido',
                    'u_solicitante.documento as solicitante_documento',
                    'u_aprobador.Nombre as aprobador_nombre',
                    'u_aprobador.apellidoPaterno as aprobador_apellido'
                )
                ->leftJoin('usuarios as u_solicitante', 'so.idusuario', '=', 'u_solicitante.idUsuario')
                ->leftJoin('usuarios as u_aprobador', 'so.idaprobador', '=', 'u_aprobador.idUsuario')
                ->where('so.idsolicitudesordenes', $id)
                ->where('so.tipoorden', 'solicitud_articulo')
                ->first();

            if (!$solicitud) {
                return response()->json(['success' => false, 'message' => 'Solicitud no encontrada'], 404);
            }

            $articulosPendientes = DB::table('ordenesarticulos')
                ->where('idsolicitudesordenes', $id)
                ->where('estado', 0)
                ->count();

            if ($articulosPendientes > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede generar la conformidad: aún hay artículos pendientes de entrega'
                ], 400);
            }

            $articulos = DB::table('ordenesarticulos as oa')
                ->select(
                    'oa.cantidad',
                    'a.nombre as articulo_nombre',
                    'a.codigo_barras',
                    'a.codigo_repuesto',
                    'ae.ubicacion_utilizada',
                    'ae.fecha_entrega',
                    'ae.tipo_entrega',
                    'u_destino.Nombre as destinatario_nombre',
                    'u_destino.apellidoPaterno as destinatario_apellido',
                    'u_entrego.Nombre as entregador_nombre',
                    'u_entrego.apellidoPaterno as entregador_apellido'
                )
                ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
                ->leftJoin('articulos_entregas as ae', function ($join) use ($id) {
                    $join->on('ae.articulo_id', '=', 'a.idArticulos')
                        ->where('ae.solicitud_id', $id);
                })
                ->leftJoin('usuarios as u_destino', 'ae.usuario_destino_id', '=', 'u_destino.idUsuario')
                ->leftJoin('usuarios as u_entrego', 'ae.usuario_entrego_id', '=', 'u_entrego.idUsuario')
                ->where('oa.idsolicitudesordenes', $id)
                ->where('oa.estado', 1)
                ->get();

            if ($articulos->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No hay artículos procesados para generar la conformidad'], 400);
            }

            $empresa = (object)[
                'nombre_empresa' => 'GKM TECHNOLOGY',
                'direccion'      => 'Av. Principal 123',
                'telefono'       => '9999',
                'ruc'            => '000000',
                'logo'           => null,
            ];

            $nombreArchivo = 'conformidad_entrega_' . $solicitud->codigo . '_' . now()->format('Ymd_His') . '.pdf';

            $bgPath = public_path('assets/images/hojamembretada.jpg');

            if (!file_exists($bgPath)) {
                throw new \Exception("No se encontró la hoja membretada en: " . $bgPath);
            }

            $bgBase64 = 'data:image/jpeg;base64,' . base64_encode(file_get_contents($bgPath));


            // 1) Render HTML desde tu blade
            $html = view('solicitud.solicitudarticulo.pdf.conformidad', [
                'solicitud'        => $solicitud,
                'articulos'        => $articulos,
                'empresa'          => $empresa,
                'fecha_generacion' => now()->format('d/m/Y H:i'),
                'bgBase64'         => $bgBase64, // ✅

            ])->render();

            // 2) Generar PDF con Browsershot a archivo temporal
            $tempPath = storage_path('app/tmp_' . uniqid() . '.pdf');

            Browsershot::html($html)
                ->format('A4')
                ->margins(0, 0, 0, 0) // si usas hoja membretada, mejor 0
                ->showBackground()
                ->waitUntilNetworkIdle()
                ->savePdf($tempPath);

            // 3) Mostrar en el navegador (inline) y borrar luego manualmente
            return response()->file($tempPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $nombreArchivo . '"',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al generar conformidad de entrega: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al generar la conformidad: ' . $e->getMessage()
            ], 500);
        }
    }



    public function enviarASolicitudAlmacen(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $solicitud = DB::table('solicitudesordenes')
                ->select('idsolicitudesordenes', 'codigo', 'idusuario')
                ->where('idsolicitudesordenes', $id)
                ->first();

            if (!$solicitud) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solicitud no encontrada'
                ], 404);
            }

            $articulosSinStock = $request->input('articulos', []);

            if (empty($articulosSinStock)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se han seleccionado artículos sin stock'
                ], 400);
            }

            // Obtener información del usuario solicitante
            $usuarioSolicitante = DB::table('usuarios')
                ->select('Nombre', 'apellidoPaterno', 'apellidoMaterno')
                ->where('idUsuario', $solicitud->idusuario)
                ->first();

            $nombreSolicitante = $usuarioSolicitante ?
                trim($usuarioSolicitante->Nombre . ' ' . $usuarioSolicitante->apellidoPaterno) :
                'Solicitante';

            // Generar código para la nueva solicitud de abastecimiento
            $codigoAbastecimiento = 'SA-' . date('ymd') . '-' . str_pad(DB::table('solicitud_almacen')->count() + 1, 3, '0', STR_PAD_LEFT);

            // Crear la solicitud de abastecimiento
            $solicitudAlmacenId = DB::table('solicitud_almacen')->insertGetId([
                'codigo_solicitud' => $codigoAbastecimiento,
                'titulo' => 'Solicitud de Abastecimiento - ' . $solicitud->codigo,
                'idTipoSolicitud' => 1,
                'solicitante' => $nombreSolicitante,
                'idPrioridad' => 2,
                'fecha_requerida' => now()->addDays(7)->format('Y-m-d'),
                'idCentroCosto' => null,
                'idTipoArea' => 1,
                'descripcion' => 'Solicitud generada automáticamente desde: ' . $solicitud->codigo . '. Artículos sin stock disponible.',
                'justificacion' => 'Los siguientes artículos no contaban con stock suficiente en la solicitud original y requieren abastecimiento.',
                'observaciones' => 'Generado automáticamente del sistema',
                'total_unidades' => array_sum(array_column($articulosSinStock, 'cantidad_solicitada')),
                'estado' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Agregar los artículos a la solicitud de abastecimiento
            foreach ($articulosSinStock as $articulo) {
                $articuloInfo = DB::table('articulos as a')
                    ->select(
                        'a.idArticulos',
                        'a.nombre',
                        'a.codigo_barras',
                        'a.codigo_repuesto',
                        'sc.nombre as categoria_nombre',
                        'u.nombre as unidad_nombre',
                        'm.nombre as modelo_nombre',
                        'mar.nombre as marca_nombre'
                    )
                    ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
                    ->leftJoin('unidades as u', 'a.idUnidad', '=', 'u.idUnidad')
                    ->leftJoin('modelo as m', 'a.idModelo', '=', 'm.idModelo')
                    ->leftJoin('marca as mar', 'm.idMarca', '=', 'mar.idMarca')
                    ->where('a.idArticulos', $articulo['idArticulos'])
                    ->first();

                if ($articuloInfo) {
                    DB::table('solicitud_almacen_detalle')->insert([
                        'idSolicitudAlmacen' => $solicitudAlmacenId,
                        'idArticulo' => $articuloInfo->idArticulos,
                        'descripcion_producto' => $articuloInfo->nombre,
                        'cantidad' => $articulo['cantidad_solicitada'],
                        'unidad' => $articuloInfo->unidad_nombre ?? 'unidad',
                        'categoria' => $articuloInfo->categoria_nombre ?? '',
                        'codigo_producto' => $articuloInfo->codigo_barras ?? $articuloInfo->codigo_repuesto,
                        'marca' => $articuloInfo->marca_nombre ?? '',
                        'especificaciones_tecnicas' => 'Solicitado desde: ' . $solicitud->codigo,
                        'justificacion_producto' => 'Stock insuficiente en solicitud original',
                        'estado' => 'pendiente',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }

            // Marcar los artículos como "enviados a abastecimiento" en la solicitud original
            foreach ($articulosSinStock as $articulo) {
                DB::table('ordenesarticulos')
                    ->where('idsolicitudesordenes', $id)
                    ->where('idarticulos', $articulo['idArticulos'])
                    ->update([
                        'observacion' => DB::raw("CONCAT(COALESCE(observacion, ''), ' | Enviado a abastecimiento: $codigoAbastecimiento')"),
                        'estado' => 2
                    ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Artículos enviados a solicitud de abastecimiento exitosamente',
                'codigo_abastecimiento' => $codigoAbastecimiento,
                'id_solicitud_almacen' => $solicitudAlmacenId,
                'total_articulos' => count($articulosSinStock)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al enviar a solicitud de abastecimiento: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar a solicitud de abastecimiento: ' . $e->getMessage()
            ], 500);
        }
    }



    public function enviarAlmacen(Request $request, $solicitudId)
    {
        try {
            DB::beginTransaction();

            // Obtener la solicitud original
            $solicitudOriginal = Solicitudesordene::findOrFail($solicitudId);

            // Obtener artículos seleccionados y datos del formulario
            $articulosSeleccionados = $request->articulos;
            $datosSolicitud = $request->datos_solicitud;

            // Obtener usuario autenticado
            $usuario = Auth::user();

            // Obtener información completa del usuario desde la tabla usuarios
            $usuarioInfo = \App\Models\Usuario::find($usuario->idUsuario ?? $usuario->id);

            // Construir nombre completo del solicitante
            $nombreSolicitante = 'Usuario Almacén'; // Valor por defecto

            if ($usuarioInfo) {
                $nombreSolicitante = trim(
                    ($usuarioInfo->Nombre ?? $usuarioInfo->name ?? '') . ' ' .
                        ($usuarioInfo->apellidoPaterno ?? $usuarioInfo->apellido_paterno ?? '') . ' ' .
                        ($usuarioInfo->apellidoMaterno ?? $usuarioInfo->apellido_materno ?? '')
                );

                // Si está vacío, usar el email o nombre de usuario
                if (empty(trim($nombreSolicitante))) {
                    $nombreSolicitante = $usuarioInfo->correo ?? $usuarioInfo->usuario ?? 'Usuario Almacén';
                }
            }

            // Generar código único para la solicitud de abastecimiento
            $codigoSolicitud = 'SA-' . date('ymd') . '-' . str_pad(SolicitudAlmacen::count() + 1, 3, '0', STR_PAD_LEFT);

            // Crear la solicitud de abastecimiento CON EL NOMBRE DEL USUARIO AUTENTICADO
            $solicitudAlmacen = SolicitudAlmacen::create([
                'codigo_solicitud' => $codigoSolicitud,
                'titulo' => $datosSolicitud['titulo'],
                'idsolicitudArticulo' => $solicitudOriginal->id,
                'idTipoSolicitud' => $datosSolicitud['idTipoSolicitud'],
                'solicitante' => $nombreSolicitante, // CAMBIADO: Usar nombre del usuario autenticado
                'idPrioridad' => $datosSolicitud['idPrioridad'],
                'fecha_requerida' => $datosSolicitud['fecha_requerida'],
                'idCentroCosto' => $datosSolicitud['idCentroCosto'],
                'idTipoArea' => $datosSolicitud['idTipoArea'],
                'descripcion' => $datosSolicitud['descripcion'],
                'justificacion' => $datosSolicitud['justificacion'],
                'observaciones' => $datosSolicitud['observaciones'],
                'total_unidades' => $this->calcularTotalUnidades($articulosSeleccionados),
                'estado' => 'pendiente'
            ]);

            // Procesar cada artículo seleccionado
            foreach ($articulosSeleccionados as $articuloData) {
                $this->crearDetalleSolicitud($solicitudAlmacen->idSolicitudAlmacen, $articuloData);
            }

            // Marcar los artículos como enviados a abastecimiento
            $this->marcarArticulosEnviados($solicitudId, $articulosSeleccionados);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Solicitud de abastecimiento creada exitosamente',
                'codigo_abastecimiento' => $codigoSolicitud,
                'id_solicitud_almacen' => $solicitudAlmacen->idSolicitudAlmacen,
                'total_articulos' => count($articulosSeleccionados),
                'solicitante' => $nombreSolicitante // Opcional: devolver el nombre para debug
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear solicitud de abastecimiento: ' . $e->getMessage()
            ], 500);
        }
    }
    private function obtenerTipoSolicitudAbastecimiento()
    {
        // Buscar tipo "Abastecimiento" o usar el primero disponible
        $tipo = TipoSolicitud::where('nombre', 'like', '%abastecimiento%')
            ->orWhere('nombre', 'like', '%reposición%')
            ->first();

        return $tipo ? $tipo->idTipoSolicitud : TipoSolicitud::first()->idTipoSolicitud;
    }

    private function obtenerPrioridadUrgente()
    {
        // Buscar prioridad "Urgente" o nivel 3-4
        $prioridad = PrioridadSolicitud::where('nivel', '>=', 3)
            ->where('estado', 1)
            ->first();

        return $prioridad ? $prioridad->idPrioridad : PrioridadSolicitud::where('estado', 1)->first()->idPrioridad;
    }

    private function obtenerCentroCostoDefault()
    {
        $centroCosto = CentroCosto::where('estado', 1)->first();
        return $centroCosto ? $centroCosto->idCentroCosto : null;
    }

    private function calcularTotalUnidades($articulos)
    {
        return array_sum(array_column($articulos, 'cantidad_solicitada'));
    }

    private function crearDetalleSolicitud($solicitudAlmacenId, $articuloData)
    {
        // Obtener información completa del artículo
        $articulo = Articulo::with(['unidad', 'modelo.marca', 'modelo.categoria'])
            ->find($articuloData['idArticulos']);

        if (!$articulo) {
            return;
        }

        // Calcular cantidad faltante
        $cantidadFaltante = $articuloData['cantidad_solicitada'] - $articuloData['stock_disponible'];

        // Asegurar que la cantidad sea al menos 1
        $cantidadSolicitar = max(1, $cantidadFaltante);

        SolicitudAlmacenDetalle::create([
            'idSolicitudAlmacen' => $solicitudAlmacenId,
            'idArticulo' => $articulo->idArticulos,
            'descripcion_producto' => $articulo->nombre,
            'cantidad' => $cantidadSolicitar,
            'unidad' => $articulo->unidad ? $articulo->unidad->nombre : 'Unidad',
            'categoria' => $articulo->modelo && $articulo->modelo->categoria ? $articulo->modelo->categoria->nombre : '',
            'codigo_producto' => $articulo->codigo_barras ?: $articulo->codigo_repuesto,
            'marca' => $articulo->modelo && $articulo->modelo->marca ? $articulo->modelo->marca->nombre : '',
            'especificaciones_tecnicas' => $articulo->ficha_tecnica,
            'justificacion_producto' => 'Stock insuficiente. Solicitado: ' . $articuloData['cantidad_solicitada'] . ', Disponible: ' . $articuloData['stock_disponible'],
            'estado' => 'pendiente',
            'observaciones_detalle' => 'Generado automáticamente por sistema'
        ]);
    }

    private function marcarArticulosEnviados($solicitudId, $articulosSeleccionados)
    {
        // Aquí puedes implementar la lógica para marcar los artículos como enviados a abastecimiento
        // en tu tabla ordenesarticulos o donde corresponda

        foreach ($articulosSeleccionados as $articuloData) {
            // Ejemplo: Actualizar el estado en ordenesarticulos
            Ordenesarticulo::where('idSolicitudesOrdenes', $solicitudId)
                ->where('idArticulos', $articuloData['idArticulos'])
                ->update([
                    'estado' => 2, // O el estado que indique "enviado a abastecimiento"
                    'observacion' => 'Enviado a solicitud de abastecimiento - ' . now()->format('d/m/Y H:i')
                ]);
        }
    }

    // Método para obtener datos del modal de creación
    public function getModalData()
    {
        $tiposSolicitud = TipoSolicitud::where('estado', 1)->get();
        $prioridades = PrioridadSolicitud::where('estado', 1)->get();
        $centrosCosto = CentroCosto::where('estado', 1)->get();
        $areas = Tipoarea::all();

        return response()->json([
            'tiposSolicitud' => $tiposSolicitud,
            'prioridades' => $prioridades,
            'centrosCosto' => $centrosCosto,
            'areas' => $areas
        ]);
    }


    // En tu SolicitudarticuloController.php, agrega este método:

    public function contadoresFiltrados()
    {
        $query = DB::table('solicitudesordenes as so')
            ->leftJoin('tickets as t', 'so.idticket', '=', 't.idTickets')
            ->whereIn('so.tipoorden', [
                'solicitud_articulo',
                'solicitud_repuesto',
                'solicitud_repuesto_provincia'
            ])
            ->where('so.estado', 'pendiente');

        if (request()->filled('tipo')) {
            $query->where('so.tipoorden', request('tipo'));
        }

        if (request()->filled('urgencia')) {
            $query->where('so.niveldeurgencia', request('urgencia'));
        }

        if (request()->filled('search')) {
            $search = request('search');

            $query->where(function ($q) use ($search) {
                $q->where('so.codigo', 'LIKE', "%{$search}%")
                    ->orWhere('t.numero_ticket', 'LIKE', "{$search}%");
            });
        }

        $contadores = $query->selectRaw("
        SUM(CASE WHEN so.tipoorden = 'solicitud_repuesto' THEN 1 ELSE 0 END) as repuesto_lima,
        SUM(CASE WHEN so.tipoorden = 'solicitud_repuesto_provincia' THEN 1 ELSE 0 END) as repuesto_provincia,
        SUM(CASE WHEN so.tipoorden = 'solicitud_articulo' THEN 1 ELSE 0 END) as solicitud_articulo,
        COUNT(*) as total
    ")->first();

        return response()->json([
            'repuesto_lima' => (int) $contadores->repuesto_lima,
            'repuesto_provincia' => (int) $contadores->repuesto_provincia,
            'solicitud_articulo' => (int) $contadores->solicitud_articulo,
            'total' => (int) $contadores->total,
        ]);
    }


    // Mantén tu método contadores() original si lo necesitas para otra cosa:
    public function contadores()
    {
        // Este sigue siendo el contador general sin filtros
        $contadores = DB::table('solicitudesordenes')
            ->selectRaw("
            SUM(CASE WHEN tipoorden = 'solicitud_repuesto' THEN 1 ELSE 0 END) as repuesto_lima,
            SUM(CASE WHEN tipoorden = 'solicitud_repuesto_provincia' THEN 1 ELSE 0 END) as repuesto_provincia,
            SUM(CASE WHEN tipoorden = 'solicitud_articulo' THEN 1 ELSE 0 END) as solicitud_articulo,
            COUNT(*) as total
        ")
            ->whereIn('tipoorden', ['solicitud_articulo', 'solicitud_repuesto', 'solicitud_repuesto_provincia'])
            ->where('estado', 'pendiente') // Añade esto si quieres solo pendientes
            ->first();

        return response()->json([
            'repuesto_lima' => $contadores->repuesto_lima ?? 0,
            'repuesto_provincia' => $contadores->repuesto_provincia ?? 0,
            'solicitud_articulo' => $contadores->solicitud_articulo ?? 0,
            'total' => $contadores->total ?? 0
        ]);
    }
}
