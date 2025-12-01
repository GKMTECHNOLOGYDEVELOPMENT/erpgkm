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
                'so.codigo_cotizacion', // Asegúrate de incluir este campo
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

    // Consulta principal para artículos (tipos 1, 3, 4)
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
        ->whereIn('a.idTipoArticulo', [1, 3, 4]) // Productos, suministros, herramientas
        ->get();

    // Para los repuestos (tipo 2)
    $repuestos = DB::table('articulos as a')
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
        ->leftJoin('articulo_modelo as am', 'a.idArticulos', '=', 'am.articulo_id')
        ->leftJoin('modelo as m', 'am.modelo_id', '=', 'm.idModelo')
        ->leftJoin('marca as mar', 'm.idMarca', '=', 'mar.idMarca')
        ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
        ->where('a.estado', 1)
        ->where('a.idTipoArticulo', 2) // Solo repuestos
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


    // Combinar ambos resultados
    $articulosCompletos = $articulos->merge($repuestos);

    // Formatear los datos de manera más simple
    $articulosFormateados = $articulosCompletos->map(function ($articulo) {
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
        
        // Información de subcategoría (especialmente para repuestos)
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
        'areas' => $areas,           // Nuevo
        'usuarios' => $usuarios      // Nuevo
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
            'orderInfo.areaDestino' => 'required|exists:tipoarea,idTipoArea',      // Nuevo
            'orderInfo.usuarioDestino' => 'required|exists:usuarios,idUsuario',    // Nuevo
            'products' => 'required|array|min:1',
            'products.*.articuloId' => 'required|exists:articulos,idArticulos',
            'products.*.cantidad' => 'required|integer|min:1|max:1000',
            'products.*.descripcion' => 'nullable|string',
            'selectedCotizacion' => 'nullable|exists:cotizaciones,idCotizaciones'
        ]);

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

        // Generar código de orden para solicitud de artículo
        $lastOrder = DB::table('solicitudesordenes')
            ->where('tipoorden', 'solicitud_articulo')
            ->orderBy('idsolicitudesordenes', 'desc')
            ->first();

        $nextOrderNumber = $lastOrder ? (intval(substr($lastOrder->codigo, 4)) + 1) : 1;
        $codigoOrden = 'SOL-' . str_pad($nextOrderNumber, 3, '0', STR_PAD_LEFT);

        // Obtener información de la cotización si existe
        $codigoCotizacion = null;
        if (!empty($validated['selectedCotizacion'])) {
            $cotizacion = DB::table('cotizaciones')
                ->where('idCotizaciones', $validated['selectedCotizacion'])
                ->first();
            $codigoCotizacion = $cotizacion->numero_cotizacion;
        }

        // 1. Insertar en solicitudesordenes
        $solicitudId = DB::table('solicitudesordenes')->insertGetId([
            'fechacreacion' => now(),
            'estado' => 'pendiente',
            'tipoorden' => 'solicitud_articulo',
            'idticket' => null,
            'fecharequerida' => $validated['orderInfo']['fechaRequerida'],
            'fechaentrega' => $validated['orderInfo']['fechaRequerida'],
            'numeroticket' => null,
            'codigo' => $codigoOrden,
            'codigo_cotizacion' => $codigoCotizacion, // Nuevo campo
            'niveldeurgencia' => $validated['orderInfo']['urgencia'],
            'tiposervicio' => $validated['orderInfo']['tipoServicio'],
            'observaciones' => $validated['orderInfo']['observaciones'] ?? null,
            'cantidad' => $totalProductosUnicos,
            'canproduuni' => $totalProductosUnicos,
            'totalcantidadproductos' => $totalCantidad,
            'idtipoServicio' => $this->getTipoServicioId($validated['orderInfo']['tipoServicio']),
            'idtecnico' => null,
            'idusuario' => Auth::id(),
             'id_area_destino' => $validated['orderInfo']['areaDestino'],        // Nuevo
            'id_usuario_destino' => $validated['orderInfo']['usuarioDestino'],  // Nuevo
            'urgencia' => $validated['orderInfo']['urgencia']
        ]);

        // 2. Insertar los artículos en ordenesarticulos
        foreach ($validated['products'] as $product) {
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
                'codigo_cotizacion' => $codigoCotizacion // Nuevo campo
            ]);
        }

        // 3. Actualizar estado de la cotización a "solicitado"
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
            'so.id_usuario_destino'         // Nuevo campo
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

    // Para los repuestos (tipo 2), obtener información adicional
    $repuestosIds = $productosActuales->where('idTipoArticulo', 2)->pluck('idarticulos');
    
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
        foreach ($productosActuales as $producto) {
            if ($producto->idTipoArticulo == 2) {
                $repuestoInfo = $repuestosCompletos->firstWhere('articulo_id', $producto->idarticulos);
                if ($repuestoInfo) {
                    $producto->nombre_modelo = $repuestoInfo->nombre_modelo_repuesto;
                    $producto->nombre_marca = $repuestoInfo->nombre_marca_repuesto;
                }
            }
        }
    }

    // Obtener todos los artículos disponibles (igual que en create)
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
        ->whereIn('a.idTipoArticulo', [1, 3, 4]) // Productos, suministros, herramientas
        ->get();

    // Para los repuestos (tipo 2)
    $repuestos = DB::table('articulos as a')
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
        ->leftJoin('articulo_modelo as am', 'a.idArticulos', '=', 'am.articulo_id')
        ->leftJoin('modelo as m', 'am.modelo_id', '=', 'm.idModelo')
        ->leftJoin('marca as mar', 'm.idMarca', '=', 'mar.idMarca')
        ->leftJoin('subcategorias as sc', 'a.idsubcategoria', '=', 'sc.id')
        ->leftJoin('tipoarticulos as ta', 'a.idTipoArticulo', '=', 'ta.idTipoArticulo')
        ->where('a.estado', 1)
        ->where('a.idTipoArticulo', 2) // Solo repuestos
        ->get();

    // Combinar ambos resultados
    $articulosCompletos = $articulos->merge($repuestos);

    // Formatear los datos
    $articulosFormateados = $articulosCompletos->map(function ($articulo) {
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
        'areas' => $areas,           // Nuevo
        'usuarios' => $usuarios      // Nuevo
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
            'orderInfo.areaDestino' => 'required|exists:tipoarea,idTipoArea',      // Nuevo
            'orderInfo.usuarioDestino' => 'required|exists:usuarios,idUsuario',    // Nuevo
            'products' => 'required|array|min:1',
            'products.*.articuloId' => 'required|exists:articulos,idArticulos',
            'products.*.cantidad' => 'required|integer|min:1|max:1000',
            'products.*.descripcion' => 'nullable|string',
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
                'id_area_destino' => $validated['orderInfo']['areaDestino'],        // Nuevo
                'id_usuario_destino' => $validated['orderInfo']['usuarioDestino'],  // Nuevo
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
                'idticket' => null,
                'idarticulos' => $product['articuloId'],
                'idubicacion' => null,
                'codigo_cotizacion' => $solicitud->codigo_cotizacion // Mantener el código de cotización si existe
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

// Agregar estos métodos al controlador para manejar las acciones

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

            // Procesar fotos si existen
            $rutaFotos = [];
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    $nombreArchivo = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                    $ruta = $foto->storeAs('evidencias_articulos', $nombreArchivo, 'public');
                    $rutaFotos[] = $ruta;
                }
            }

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
            Log::info("Artículo marcado como usado - Solicitud: {$solicitudId}, Artículo: {$articuloInfo->nombre}, Fecha: {$request->fecha_uso}");
        });

        return response()->json([
            'success' => true,
            'message' => 'Artículo marcado como usado correctamente'
        ]);

    } catch (\Exception $e) {
        Log::error('Error al marcar artículo como usado: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al marcar el artículo: ' . $e->getMessage()
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

        DB::transaction(function () use ($request, $solicitudId) {
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
                ->leftJoin('articulos_entregas as ae', function($join) use ($solicitudId) {
                    $join->on('ae.solicitud_id', '=', 'oa.idsolicitudesordenes')
                         ->on('ae.articulo_id', '=', 'oa.idarticulos');
                })
                ->where('oa.idsolicitudesordenes', $solicitudId)
                ->where('oa.idarticulos', $request->articulo_id)
                ->first();

            if (!$articuloInfo) {
                throw new \Exception('Artículo no encontrado en la solicitud');
            }

            // Procesar fotos si existen
            $rutaFotos = [];
            if ($request->hasFile('fotos')) {
                foreach ($request->file('fotos') as $foto) {
                    $nombreArchivo = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                    $ruta = $foto->storeAs('evidencias_devoluciones_articulos', $nombreArchivo, 'public');
                    $rutaFotos[] = $ruta;
                }
            }

            // Buscar la ubicación original donde estaba el artículo
            $ubicacionOriginal = DB::table('rack_ubicaciones')
                ->select('idRackUbicacion', 'codigo', 'rack_id')
                ->where('codigo', $articuloInfo->ubicacion_utilizada)
                ->first();

            if (!$ubicacionOriginal) {
                throw new \Exception('No se pudo encontrar la ubicación original del artículo');
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

            // 5. Actualizar en la tabla ordenesarticulos
            DB::table('ordenesarticulos')
                ->where('idsolicitudesordenes', $solicitudId)
                ->where('idarticulos', $request->articulo_id)
                ->update([
                    'fechaSinUsar' => $request->fecha_devolucion,
                    'fechaUsado' => null,
                    'observacion' => $request->observacion . " | Devolución completada: " . now()->format('d/m/Y H:i'),
                    'fotos_evidencia' => !empty($rutaFotos) ? json_encode($rutaFotos) : null
                ]);

            // 6. Registrar en logs
            Log::info("Artículo devuelto al inventario - Solicitud: {$solicitudId}, Artículo: {$articuloInfo->nombre}, Cantidad: {$articuloInfo->cantidad}, Ubicación: {$ubicacionOriginal->codigo}, Registros eliminados: {$registrosEliminados}");
        });

        return response()->json([
            'success' => true,
            'message' => 'Artículo marcado como no usado y devuelto al inventario correctamente'
        ]);

    } catch (\Exception $e) {
        Log::error('Error al marcar artículo como no usado: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al marcar el artículo: ' . $e->getMessage()
        ], 500);
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

            // ✅ 4. Registrar en articulos_entregas con el destinatario seleccionado
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
            'message' => "Solicitud de artículos aprobada correctamente. Stock descontado de las ubicaciones seleccionadas. Entregado a: {$nombreDestinatario}",
            'destinatario' => $nombreDestinatario,
            'tipo_entrega' => $tipoEntrega
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
        // Obtener la solicitud con información completa - AGREGAR CAMPO estado
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
                'so.estado', // ← ESTE ES EL CAMPO QUE FALTABA
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
            return response()->json([
                'success' => false,
                'message' => 'Solicitud no encontrada'
            ], 404);
        }

        // Verificar que todos los artículos estén procesados
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

        // Obtener artículos entregados con información CORREGIDA
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
            ->leftJoin('articulos_entregas as ae', function($join) use ($id) {
                $join->on('ae.articulo_id', '=', 'a.idArticulos')
                     ->where('ae.solicitud_id', $id);
            })
            ->leftJoin('usuarios as u_destino', 'ae.usuario_destino_id', '=', 'u_destino.idUsuario')
            ->leftJoin('usuarios as u_entrego', 'ae.usuario_entrego_id', '=', 'u_entrego.idUsuario')
            ->where('oa.idsolicitudesordenes', $id)
            ->where('oa.estado', 1)
            ->get();

        // Verificar que hay artículos procesados
        if ($articulos->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay artículos procesados para generar la conformidad'
            ], 400);
        }

        // Datos estáticos de la empresa
        $empresa = (object) [
            'nombre_empresa' => 'GKM TECHNOLOGY',
            'direccion' => 'Av. Principal 123',
            'telefono' => '9999',
            'ruc' => '000000',
            'logo' => null
        ];

        // Generar PDF
        $pdf = \PDF::loadView('solicitud.solicitudarticulo.pdf.conformidad', [
            'solicitud' => $solicitud,
            'articulos' => $articulos,
            'empresa' => $empresa,
            'fecha_generacion' => now()->format('d/m/Y H:i')
        ]);

        $nombreArchivo = 'conformidad_entrega_' . $solicitud->codigo . '_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($nombreArchivo);

    } catch (\Exception $e) {
        Log::error('Error al generar PDF de conformidad: ' . $e->getMessage());
        Log::error('File: ' . $e->getFile());
        Log::error('Line: ' . $e->getLine());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al generar el PDF: ' . $e->getMessage()
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




    
}
