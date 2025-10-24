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
        $solicitudes = DB::table('solicitudesordenes as so')
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
            ->whereIn('so.tipoorden', ['solicitud_articulo', 'solicitud_repuesto'])
            ->orderBy('so.fechacreacion', 'desc')
            ->paginate(10);

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
}
