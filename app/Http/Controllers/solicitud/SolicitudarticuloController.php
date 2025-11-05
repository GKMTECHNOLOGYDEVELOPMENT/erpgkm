<?php

namespace App\Http\Controllers\solicitud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SolicitudarticuloController extends Controller
{
    public function index()
    {
        $query = DB::table('solicitudesordenes as so')
            ->select(
                'so.idsolicitudesordenes',
                'so.codigo',
                'so.estado',
                'so.fechacreacion',
                'so.fecharequerida',
                'so.niveldeurgencia',
                'so.tiposervicio',
                'so.tipoorden',
                'so.cantidad as total_productos',
                'so.totalcantidadproductos',
                'so.observaciones',
                DB::raw("CONCAT(u.Nombre, ' ', u.apellidoPaterno) as nombre_solicitante")
            )
            ->leftJoin('usuarios as u', 'so.idusuario', '=', 'u.idUsuario')
            ->whereIn('so.tipoorden', ['solicitud_articulo', 'solicitud_repuesto']);

        // Aplicar filtro por tipo
        if (request()->has('tipo') && !empty(request('tipo'))) {
            $query->where('so.tipoorden', request('tipo'));
        }

        // Aplicar filtro por estado
        if (request()->has('estado') && !empty(request('estado'))) {
            $query->where('so.estado', request('estado'));
        }

        // Aplicar filtro por urgencia
        if (request()->has('urgencia') && !empty(request('urgencia'))) {
            $query->where('so.niveldeurgencia', request('urgencia'));
        }

        // Aplicar filtro por búsqueda
        if (request()->has('search') && !empty(request('search'))) {
            $search = request('search');
            $query->where('so.codigo', 'LIKE', "%{$search}%");
        }

        $solicitudes = $query->orderBy('so.fechacreacion', 'desc')->paginate(10);

        return view("solicitud.solicitudarticulo.index", compact('solicitudes'));
    }

    public function create()
    {
        $usuario = auth()->user()->load('tipoArea');

        $articulos = DB::table('articulos as a')
            ->select(
                'a.idArticulos',
                'a.nombre',
                'a.codigo_barras',
                'a.codigo_repuesto',
                'a.precio_compra',
                'a.stock_total',
                'sc.nombre as tipo_articulo'
            )
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->where('a.estado', 1)
            ->get();

        // Obtener el próximo número de orden
        $lastOrder = DB::table('solicitudesordenes')
            ->where('tipoorden', 'solicitud_articulo')
            ->orderBy('idsolicitudesordenes', 'desc')
            ->first();

        $nextOrderNumber = $lastOrder ? (intval(substr($lastOrder->codigo, 4)) + 1) : 1;

        return view("solicitud.solicitudarticulo.create", [
            'usuario' => $usuario,
            'articulos' => $articulos,
            'nextOrderNumber' => $nextOrderNumber
        ]);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validar los datos del formulario
            $validated = $request->validate([
                'orderInfo.tipoServicio' => 'required|string',
                'orderInfo.urgencia' => 'required|string|in:baja,media,alta',
                'orderInfo.fechaRequerida' => 'required|date',
                'orderInfo.observaciones' => 'nullable|string',
                'products' => 'required|array|min:1',
                'products.*.articuloId' => 'required|exists:articulos,idArticulos',
                'products.*.cantidad' => 'required|integer|min:1|max:1000',
                'products.*.descripcion' => 'nullable|string'
            ]);

            // Calcular estadísticas de productos
            $totalCantidad = collect($validated['products'])->sum('cantidad');
            $totalProductosUnicos = count($validated['products']);

            // Generar código de orden para solicitud de artículo
            $lastOrder = DB::table('solicitudesordenes')
                ->where('tipoorden', 'solicitud_articulo')
                ->orderBy('idsolicitudesordenes', 'desc')
                ->first();

            $nextOrderNumber = $lastOrder ? (intval(substr($lastOrder->codigo, 4)) + 1) : 1;
            $codigoOrden = 'SOL-' . str_pad($nextOrderNumber, 3, '0', STR_PAD_LEFT);

            // 1. Insertar en solicitudesordenes
            $solicitudId = DB::table('solicitudesordenes')->insertGetId([
                'fechacreacion' => now(),
                'estado' => 'pendiente',
                'tipoorden' => 'solicitud_articulo', // Tipo específico para artículos
                'idticket' => null, // No hay ticket en solicitud de artículos
                'fecharequerida' => $validated['orderInfo']['fechaRequerida'],
                'fechaentrega' => $validated['orderInfo']['fechaRequerida'],
                'numeroticket' => null, // No hay ticket
                'codigo' => $codigoOrden,
                'niveldeurgencia' => $validated['orderInfo']['urgencia'],
                'tiposervicio' => $validated['orderInfo']['tipoServicio'],
                'observaciones' => $validated['orderInfo']['observaciones'] ?? null,
                'cantidad' => $totalProductosUnicos,
                'canproduuni' => $totalProductosUnicos,
                'totalcantidadproductos' => $totalCantidad,
                'idtipoServicio' => $this->getTipoServicioId($validated['orderInfo']['tipoServicio']),
                'idtecnico' => null, // No hay técnico asignado inicialmente
                'idusuario' => Auth::id(),
                'urgencia' => $validated['orderInfo']['urgencia']
            ]);

            // 2. Insertar los artículos en ordenesarticulos
            foreach ($validated['products'] as $product) {
                DB::table('ordenesarticulos')->insert([
                    'cantidad' => $product['cantidad'],
                    'estado' => 0, // 0 = pendiente
                    'observacion' => $product['descripcion'] ?? null,
                    'fotorepuesto' => null,
                    'fechausado' => null,
                    'fechasinusar' => null,
                    'idsolicitudesordenes' => $solicitudId,
                    'idticket' => null, // No hay ticket en solicitud de artículos
                    'idarticulos' => $product['articuloId'],
                    'idubicacion' => null
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Solicitud de artículos creada exitosamente',
                'solicitud_id' => $solicitudId,
                'codigo_orden' => $codigoOrden,
                'estadisticas' => [
                    'productos_unicos' => $totalProductosUnicos,
                    'total_cantidad' => $totalCantidad
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al crear solicitud de artículos: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al crear la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $usuario = auth()->user()->load('tipoArea');

        // Obtener la solicitud existente con más información
        $solicitud = DB::table('solicitudesordenes as so')
            ->select(
                'so.idsolicitudesordenes',
                'so.codigo',
                'so.tiposervicio',
                'so.niveldeurgencia as urgencia',
                'so.fecharequerida',
                'so.observaciones',
                'so.estado',
                'so.fechacreacion',
                'so.totalcantidadproductos',
                'so.cantidad as productos_unicos',
                DB::raw("CONCAT(u.Nombre, ' ', u.apellidoPaterno) as nombre_solicitante"),
                'ta.nombre as nombre_area'
            )
            ->leftJoin('usuarios as u', 'so.idusuario', '=', 'u.idUsuario')
            ->leftJoin('tipoarea as ta', 'u.idTipoArea', '=', 'ta.idTipoArea')
            ->where('so.idsolicitudesordenes', $id)
            ->where('so.tipoorden', 'solicitud_articulo')
            ->first();

        if (!$solicitud) {
            abort(404, 'Solicitud no encontrada');
        }

        // Obtener los artículos de la solicitud
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
                'sc.nombre as tipo_articulo'
            )
            ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->where('oa.idsolicitudesordenes', $id)
            ->get();

        return view('solicitud.solicitudarticulo.show', [
            'usuario' => $usuario,
            'solicitud' => $solicitud,
            'articulos' => $articulos
        ]);
    }

    public function edit($id)
    {
        $usuario = auth()->user()->load('tipoArea');

        // Obtener la solicitud existente
        $solicitud = DB::table('solicitudesordenes as so')
            ->select(
                'so.idsolicitudesordenes',
                'so.codigo',
                'so.tiposervicio',
                'so.niveldeurgencia as urgencia',
                'so.fecharequerida',
                'so.observaciones',
                'so.estado'
            )
            ->where('so.idsolicitudesordenes', $id)
            ->where('so.tipoorden', 'solicitud_articulo')
            ->first();

        if (!$solicitud) {
            abort(404, 'Solicitud no encontrada');
        }

        // Obtener los artículos actuales de la solicitud
        $productosActuales = DB::table('ordenesarticulos as oa')
            ->select(
                'oa.idordenesarticulos',
                'oa.cantidad',
                'oa.observacion as descripcion',
                'oa.idarticulos',
                'a.nombre',
                'a.codigo_barras',
                'a.codigo_repuesto',
                'sc.nombre as tipo_articulo'
            )
            ->join('articulos as a', 'oa.idarticulos', '=', 'a.idArticulos')
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->where('oa.idsolicitudesordenes', $id)
            ->where('oa.estado', 0) // Solo artículos pendientes
            ->get();

        // Obtener todos los artículos disponibles
        $articulos = DB::table('articulos as a')
            ->select(
                'a.idArticulos',
                'a.nombre',
                'a.codigo_barras',
                'a.codigo_repuesto',
                'a.precio_compra',
                'a.stock_total',
                'sc.nombre as tipo_articulo'
            )
            ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
            ->where('a.estado', 1)
            ->get();

        return view('solicitud.solicitudarticulo.edit', [
            'usuario' => $usuario,
            'solicitud' => $solicitud,
            'productosActuales' => $productosActuales,
            'articulos' => $articulos
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
                'products' => 'required|array|min:1',
                'products.*.articuloId' => 'required|exists:articulos,idArticulos',
                'products.*.cantidad' => 'required|integer|min:1|max:1000',
                'products.*.descripcion' => 'nullable|string'
            ]);

            // Calcular nuevas estadísticas de productos
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
                    'cantidad' => $totalProductosUnicos,
                    'canproduuni' => $totalProductosUnicos,
                    'totalcantidadproductos' => $totalCantidad,
                    'idtipoServicio' => $this->getTipoServicioId($validated['orderInfo']['tipoServicio']),
                    'urgencia' => $validated['orderInfo']['urgencia'],
                    'fechaactualizacion' => now()
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
                    'idticket' => null, // No hay ticket en solicitud de artículos
                    'idarticulos' => $product['articuloId'],
                    'idubicacion' => null
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
        // Obtener la solicitud con sus artículos
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
        ->leftJoin('usuarios as u', 'so.idusuario', '=', 'u.idUsuario')
        ->leftJoin('tickets as t', 'so.idticket', '=', 't.idTickets')
        ->where('so.idsolicitudesordenes', $id)
        ->where('so.tipoorden', 'solicitud_articulo')
        ->first();

        if (!$solicitud) {
            abort(404, 'Solicitud no encontrada');
        }

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
        }

        // Verificar si toda la solicitud puede ser atendida
        $puede_aceptar = $articulos->every(function ($articulo) {
            return $articulo->suficiente_stock;
        });

        // Contar artículos procesados y disponibles
        $articulos_procesados = $articulos->where('ya_procesado', true)->count();
        $articulos_disponibles = $articulos->where('suficiente_stock', true)->count();
        $total_articulos = $articulos->count();

        return view('solicitud.solicitudarticulo.opciones', compact(
            'solicitud',
            'articulos',
            'puede_aceptar',
            'articulos_procesados',
            'articulos_disponibles',
            'total_articulos'
        ));
    }

    public function aceptarIndividual(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // Obtener la solicitud con todos los campos necesarios
            $solicitud = DB::table('solicitudesordenes')
                ->select('idsolicitudesordenes', 'codigo', 'estado', 'tipoorden')
                ->where('idsolicitudesordenes', $id)
                ->where('tipoorden', 'solicitud_articulo') // Cambiado a solicitud_articulo
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

            Log::info("✅ Stock descontado en rack_ubicacion_articulos - Artículo ID: {$stockUbicacion->idRackUbicacionArticulo}, Cantidad: -{$cantidadSolicitada}");

            // ✅ 2. DESCONTAR stock total en tabla articulos
            DB::table('articulos')
                ->where('idArticulos', $articuloId)
                ->decrement('stock_total', $cantidadSolicitada);

            // Verificar stock actualizado
            $articuloActualizado = DB::table('articulos')
                ->select('stock_total')
                ->where('idArticulos', $articuloId)
                ->first();

            Log::info("✅ Stock total actualizado en articulos - ID: {$articuloId}, Stock anterior: {$articuloInfo->stock_total}, Stock actual: {$articuloActualizado->stock_total}");

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

            Log::info("✅ Movimiento registrado en rack_movimientos - Artículo: {$articuloId}, Cantidad: {$cantidadSolicitada}");

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

            Log::info("✅ Registro creado en inventario_ingresos_clientes - Cliente: {$stockUbicacion->cliente_general_id}, Cantidad: -{$cantidadSolicitada}");

            // ✅ 5. Actualizar KARDEX para la SALIDA
            $this->actualizarKardexSalida($articuloId, $stockUbicacion->cliente_general_id, $cantidadSolicitada, $articuloInfo->precio_compra);

            // ✅ 6. Marcar el artículo como procesado
            DB::table('ordenesarticulos')
                ->where('idordenesarticulos', $articulo->idordenesarticulos)
                ->update([
                    'estado' => 1,
                    'observacion' => "Ubicación utilizada: {$stockUbicacion->ubicacion_codigo} - Procesado individualmente"
                ]);

            Log::info("✅ Artículo marcado como procesado - ID: {$articulo->idordenesarticulos}");

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

                Log::info("✅ TODOS los artículos procesados - Solicitud marcada como APROBADA");
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Artículo procesado correctamente',
                'todos_procesados' => $todosProcesados
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al procesar artículo individual: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());

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

            // Obtener la solicitud con todos los campos necesarios
            $solicitud = DB::table('solicitudesordenes')
                ->select('idsolicitudesordenes', 'codigo', 'estado', 'tipoorden')
                ->where('idsolicitudesordenes', $id)
                ->where('tipoorden', 'solicitud_articulo') // Cambiado a solicitud_articulo
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
                    'message' => 'No se han seleccionado ubicaciones para los artículos'
                ], 400);
            }

            // Obtener artículos de la solicitud
            $articulosSolicitud = DB::table('ordenesarticulos as oa')
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

            // Procesar cada artículo
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

                // ✅ 1. DESCONTAR de rack_ubicacion_articulos (ubicación específica)
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

                // ✅ 4. Registrar en inventario_ingresos_clientes
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

                // ✅ 5. Actualizar kardex
                $this->actualizarKardexSalida($articulo->idArticulos, $stockUbicacion->cliente_general_id, $cantidadSolicitada, $articulo->precio_compra);

                // ✅ 6. Marcar como procesado
                DB::table('ordenesarticulos')
                    ->where('idordenesarticulos', $articulo->idordenesarticulos)
                    ->update([
                        'estado' => 1,
                        'observacion' => "Ubicación utilizada: {$stockUbicacion->ubicacion_codigo} - Procesado grupalmente"
                    ]);

                Log::info("✅ Artículo procesado grupalmente - Artículo: {$articulo->idArticulos}, Cantidad: {$cantidadSolicitada}, Ubicación: {$stockUbicacion->ubicacion_codigo}");
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
                'message' => 'Solicitud de artículos aprobada correctamente. Stock descontado de las ubicaciones seleccionadas.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al aceptar solicitud de artículos (grupal): ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());

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
}
