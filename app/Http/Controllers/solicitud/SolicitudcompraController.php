<?php

namespace App\Http\Controllers\solicitud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SolicitudCompra;
use App\Models\SolicitudCompraDetalle;
use App\Models\SolicitudCompraArchivo;
use App\Models\SolicitudAlmacen;
use App\Models\SolicitudAlmacenDetalle;
use App\Models\TipoArea;
use App\Models\PrioridadSolicitud;
use App\Models\CentroCosto;
use App\Models\Moneda;
use App\Models\Proveedore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SolicitudcompraController extends Controller
{
    public function index()
    {
        // Obtener todas las solicitudes de compra con relaciones
        $solicitudes = SolicitudCompra::with([
            'tipoArea',
            'prioridad',
            'solicitudAlmacen', // Relación con solicitud de almacén
            'detalles.moneda'   // Relación con detalles y moneda
        ])->orderBy('created_at', 'desc')->get();

        // Obtener áreas y prioridades para los filtros
        $areas = TipoArea::all();
        $prioridades = PrioridadSolicitud::where('estado', 1)->get();

        return view('solicitud.solicitudcompra.index', compact('solicitudes', 'areas', 'prioridades'));
    }
    public function create()
    {
        // Obtener datos para los selects
        $tipoAreas = TipoArea::all();
        $prioridades = PrioridadSolicitud::where('estado', 1)->get();
        $centrosCosto = CentroCosto::where('estado', 1)->get();

        // Obtener proveedores activos
        $proveedores = Proveedore::where('estado', 1)
            ->orderBy('nombre')
            ->get(['idProveedor', 'nombre', 'telefono', 'email']);

        // **OPCIÓN 2: Filtrar manualmente las solicitudes que ya están en compras**

        // Primero obtenemos los IDs de solicitudes de almacén que YA están en compras
        $solicitudesAlmacenEnCompras = SolicitudCompra::pluck('idSolicitudAlmacen')->toArray();

        // Luego obtenemos solo las solicitudes de almacén que NO están en esa lista
        $solicitudesAlmacen = SolicitudAlmacen::where('estado', 'aprobada')
            ->whereHas('detalles', function ($query) {
                $query->where('estado', 'aprobado');
            })
            ->whereNotIn('idSolicitudAlmacen', $solicitudesAlmacenEnCompras) // ✅ Excluir las que ya tienen compra
            ->with(['detalles' => function ($query) {
                $query->where('estado', 'aprobado');
            }])
            ->get();

        // **DEBUG: Verificar cuántas solicitudes se obtienen**
        \Log::info('Solicitudes de almacén disponibles para compra:', [
            'total' => $solicitudesAlmacen->count(),
            'excluidas' => count($solicitudesAlmacenEnCompras),
            'ids_disponibles' => $solicitudesAlmacen->pluck('idSolicitudAlmacen')->toArray(),
            'ids_excluidas' => $solicitudesAlmacenEnCompras
        ]);


        $monedas = Moneda::all(); // O si tienes un estado: ->where('estado', 1)->get()

        // Obtener usuario autenticado
        $user = Auth::user();
        $solicitanteCompra = 'Usuario Sistema';

        if ($user) {
            if (isset($user->Nombre)) {
                $solicitanteCompra = trim(
                    ($user->Nombre ?? '') . ' ' .
                        ($user->apellidoPaterno ?? '') . ' ' .
                        ($user->apellidoMaterno ?? '')
                );

                if (empty(trim($solicitanteCompra))) {
                    $solicitanteCompra = $user->correo ?? $user->usuario ?? 'Usuario Sistema';
                }
            } else {
                $usuario = \App\Models\Usuario::where('idUsuario', $user->id ?? $user->idUsuario)->first();
                if ($usuario) {
                    $solicitanteCompra = trim(
                        ($usuario->Nombre ?? '') . ' ' .
                            ($usuario->apellidoPaterno ?? '') . ' ' .
                            ($usuario->apellidoMaterno ?? '')
                    );

                    if (empty(trim($solicitanteCompra))) {
                        $solicitanteCompra = $usuario->correo ?? $usuario->usuario ?? 'Usuario Sistema';
                    }
                }
            }
        }

        return view('solicitud.solicitudcompra.create', compact(
            'tipoAreas',
            'prioridades',
            'centrosCosto',
            'solicitudesAlmacen',
            'proveedores',
            'solicitanteCompra',
            'monedas'
        ));
    }



public function store(Request $request)
{
    // Log de entrada
    Log::info('=== INICIANDO store() SOLICITUD COMPRA ===');
    Log::info('IP del cliente: ' . $request->ip());
    Log::info('User-Agent: ' . $request->header('User-Agent'));
    Log::info('ID Usuario: ' . (auth()->check() ? auth()->id() : 'No autenticado'));

    // DEBUG: Verificar datos recibidos ANTES de validar
    Log::debug('📥 Datos recibidos ANTES de validar:', [
        'todos_los_campos' => array_keys($request->all()),
        'items_count' => count($request->items ?? []),
        'idSolicitudAlmacen' => $request->idSolicitudAlmacen,
        'solicitante_compra' => $request->solicitante_compra,
        'solicitante_almacen' => $request->solicitante_almacen
    ]);

    // Validación
    try {
        $validated = $request->validate([
            'solicitante_compra' => 'required|string|max:255',
            'solicitante_almacen' => 'required|string|max:255',
            'idTipoArea' => 'required|exists:tipoarea,idTipoArea',
            'idPrioridad' => 'required|exists:prioridad_solicitud,idPrioridad',
            'fecha_requerida' => 'required|date',
            'justificacion' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.descripcion_producto' => 'required|string',
            'items.*.idMonedas' => 'required|exists:monedas,idMonedas',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio_unitario_estimado' => 'required|numeric|min:0',
            'idSolicitudAlmacen' => 'required|exists:solicitud_almacen,idSolicitudAlmacen',
        ]);
        
        Log::info('✅ Validación exitosa');
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('❌ ERROR DE VALIDACIÓN:', [
            'errores' => $e->errors(),
            'datos_recibidos' => $request->except(['items', 'archivos']),
            'items_recibidos' => $request->items ? 'Sí (' . count($request->items) . ' items)' : 'No',
            'campos_faltantes' => $this->getMissingFields($request)
        ]);
        
        return back()
            ->withErrors($e->validator)
            ->withInput()
            ->with('error', 'Por favor complete todos los campos requeridos.');
    }

    // Log de datos recibidos después de validar
    Log::debug('📋 Datos validados:', [
        'except_items' => $request->except(['items', 'archivos']),
        'items_count' => count($request->items ?? []),
        'archivos_count' => $request->hasFile('archivos') ? count($request->file('archivos')) : 0,
    ]);

    // Log detallado de items
    if ($request->has('items')) {
        Log::info('📦 Detalles de items recibidos:');
        foreach ($request->items as $index => $item) {
            Log::debug("Item {$index}:", [
                'descripcion' => $item['descripcion_producto'] ?? 'N/A',
                'cantidad' => $item['cantidad'] ?? 0,
                'precio_unitario' => $item['precio_unitario_estimado'] ?? 0,
                'moneda' => $item['idMonedas'] ?? 'N/A',
                'idSolicitudAlmacenDetalle' => $item['idSolicitudAlmacenDetalle'] ?? 'No especificado',
                'todos_los_campos_item' => array_keys($item)
            ]);
        }
    }

    // Log de archivos
    if ($request->hasFile('archivos')) {
        Log::info('📎 Archivos adjuntos:');
        foreach ($request->file('archivos') as $index => $archivo) {
            Log::debug("Archivo {$index}:", [
                'nombre' => $archivo->getClientOriginalName(),
                'tamaño' => $archivo->getSize(),
                'mime_type' => $archivo->getMimeType()
            ]);
        }
    }

    try {
        Log::info('🔄 Iniciando transacción de base de datos');
        DB::beginTransaction();
        
        $transactionId = uniqid('trans_', true);
        Log::info("ID de transacción: {$transactionId}");

        // **ACTUALIZAR ESTADO DE LA SOLICITUD DE ALMACÉN**
        Log::info('🔍 Buscando solicitud de almacén:', [
            'idSolicitudAlmacen' => $request->idSolicitudAlmacen
        ]);

        $solicitudAlmacen = \App\Models\SolicitudAlmacen::with('detalles')->find($request->idSolicitudAlmacen);

        if (!$solicitudAlmacen) {
            Log::error('❌ No se encontró la solicitud de almacén', [
                'idSolicitudAlmacen' => $request->idSolicitudAlmacen,
                'solicitudes_existentes' => \App\Models\SolicitudAlmacen::pluck('idSolicitudAlmacen')->toArray()
            ]);
            throw new \Exception('No se encontró la solicitud de almacén con ID: ' . $request->idSolicitudAlmacen);
        }

        Log::info('✅ Solicitud de almacén encontrada:', [
            'id' => $solicitudAlmacen->idSolicitudAlmacen,
            'estado_actual' => $solicitudAlmacen->estado,
            'detalles_count' => $solicitudAlmacen->detalles->count()
        ]);

        // Actualizar estado de la solicitud de almacén
        $estadoAnterior = $solicitudAlmacen->estado;
        $solicitudAlmacen->update([
            'estado' => 'Solicitud Enviada administración',
            'updated_at' => now()
        ]);

        Log::info('📝 Estado de solicitud almacén actualizado:', [
            'idSolicitudAlmacen' => $solicitudAlmacen->idSolicitudAlmacen,
            'estado_anterior' => $estadoAnterior,
            'nuevo_estado' => 'Solicitud Enviada administración',
            'updated_at' => $solicitudAlmacen->updated_at
        ]);

        // Generar código de solicitud
        $countSolicitudes = SolicitudCompra::count();
        $codigoSolicitud = 'SC-' . date('Ymd') . '-' . str_pad($countSolicitudes + 1, 4, '0', STR_PAD_LEFT);
        
        Log::info('🔢 Generando código de solicitud:', [
            'base_count' => $countSolicitudes,
            'codigo_generado' => $codigoSolicitud
        ]);

        // Calcular totales
        $subtotal = 0;
        $totalUnidades = 0;
        $itemsProcesados = [];

        Log::info('🧮 Calculando totales de items...');
        foreach ($request->items as $index => $item) {
            $itemSubtotal = $item['cantidad'] * $item['precio_unitario_estimado'];
            $subtotal += $itemSubtotal;
            $totalUnidades += $item['cantidad'];
            
            $itemsProcesados[$index] = [
                'descripcion' => $item['descripcion_producto'],
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio_unitario_estimado'],
                'subtotal_item' => $itemSubtotal
            ];
        }

        $iva = $subtotal * 0.18; // 18% IGV
        $total = $subtotal + $iva;

        Log::info('💰 Totales calculados:', [
            'subtotal' => number_format($subtotal, 2),
            'iva_18%' => number_format($iva, 2),
            'total' => number_format($total, 2),
            'total_unidades' => $totalUnidades,
            'items_procesados' => count($itemsProcesados)
        ]);

        // **CREAR SOLICITUD DE COMPRA CON AMBOS SOLICITANTES Y RELACIÓN CON ALMACÉN**
        Log::info('📄 Creando solicitud de compra principal...');
        
        // Verificar campos opcionales
        $solicitudCompraData = [
            'codigo_solicitud' => $codigoSolicitud,
            'idSolicitudAlmacen' => $request->idSolicitudAlmacen,
            'solicitante_compra' => $request->solicitante_compra,
            'solicitante_almacen' => $request->solicitante_almacen,
            'idTipoArea' => $request->idTipoArea,
            'idPrioridad' => $request->idPrioridad,
            'fecha_requerida' => $request->fecha_requerida,
            'idCentroCosto' => $request->idCentroCosto ?? null,
            'proyecto_asociado' => $request->proyecto_asociado ?? null,
            'justificacion' => $request->justificacion,
            'observaciones' => $request->observaciones ?? null,
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $total,
            'total_unidades' => $totalUnidades,
            'estado' => 'pendiente',
            'created_at' => now(),
            'updated_at' => now()
        ];

        Log::debug('📝 Datos para crear solicitud compra:', $solicitudCompraData);

        $solicitudCompra = SolicitudCompra::create($solicitudCompraData);

        Log::info('✅ Solicitud de compra principal creada:', [
            'idSolicitudCompra' => $solicitudCompra->idSolicitudCompra,
            'codigo_solicitud' => $codigoSolicitud,
            'estado' => 'pendiente'
        ]);

        // Crear detalles manteniendo la relación con almacén
        Log::info('📝 Creando detalles de la solicitud de compra...');
        $detallesCreados = 0;
        
        foreach ($request->items as $index => $item) {
            $detalleData = [
                'idSolicitudCompra' => $solicitudCompra->idSolicitudCompra,
                'idSolicitudAlmacenDetalle' => $item['idSolicitudAlmacenDetalle'] ?? null,
                'idArticulo' => $item['idArticulo'] ?? null,
                'descripcion_producto' => $item['descripcion_producto'],
                'categoria' => $item['categoria'] ?? '',
                'cantidad' => $item['cantidad'],
                'unidad' => $item['unidad'] ?? 'unidad',
                'idMonedas' => $item['idMonedas'],
                'precio_unitario_estimado' => $item['precio_unitario_estimado'],
                'total_producto' => $item['cantidad'] * $item['precio_unitario_estimado'],
                'codigo_producto' => $item['codigo_producto'] ?? '',
                'marca' => $item['marca'] ?? '',
                'especificaciones_tecnicas' => $item['especificaciones_tecnicas'] ?? '',
                'proveedor_sugerido' => $item['proveedor_sugerido'] ?? $this->getProveedorSugerido($item),
                'justificacion_producto' => $item['justificacion_producto'] ?? '',
                'observaciones_detalle' => $item['observaciones_detalle'] ?? '',
                'estado' => 'pendiente',
                'created_at' => now(),
                'updated_at' => now()
            ];

            $detalle = SolicitudCompraDetalle::create($detalleData);
            $detallesCreados++;
            
            Log::debug("✅ Detalle {$index} creado:", [
                'idDetalle' => $detalle->idSolicitudCompraDetalle,
                'descripcion' => substr($item['descripcion_producto'], 0, 50) . '...',
                'relacion_almacen' => $item['idSolicitudAlmacenDetalle'] ? 'Sí' : 'No'
            ]);
        }

        Log::info("✅ {$detallesCreados} detalles creados exitosamente");

        // Guardar archivos si existen
        if ($request->hasFile('archivos')) {
            Log::info('💾 Procesando archivos adjuntos...');
            $archivosGuardados = 0;
            
            foreach ($request->file('archivos') as $archivo) {
                $nombreOriginal = $archivo->getClientOriginalName();
                $nombreArchivo = time() . '_' . $nombreOriginal;
                $ruta = $archivo->storeAs(
                    'solicitudes_compra/' . $solicitudCompra->idSolicitudCompra, 
                    $nombreArchivo, 
                    'public'
                );

                SolicitudCompraArchivo::create([
                    'idSolicitudCompra' => $solicitudCompra->idSolicitudCompra,
                    'nombre_archivo' => $nombreOriginal,
                    'ruta_archivo' => $ruta,
                    'tipo_archivo' => $archivo->getMimeType(),
                    'tamaño' => $archivo->getSize(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $archivosGuardados++;
                Log::debug("💾 Archivo guardado: {$nombreOriginal}");
            }
            
            Log::info("✅ {$archivosGuardados} archivos guardados exitosamente");
        }

        DB::commit();
        Log::info('🎉 Transacción completada exitosamente');

        // Log de resumen
        Log::info('📊 RESUMEN DE OPERACIÓN:', [
            'solicitud_compra_id' => $solicitudCompra->idSolicitudCompra,
            'codigo_solicitud' => $codigoSolicitud,
            'solicitud_almacen_id' => $request->idSolicitudAlmacen,
            'estado_almacen_actualizado' => 'Sí',
            'items_procesados' => $detallesCreados,
            'archivos_adjuntos' => $archivosGuardados ?? 0,
            'subtotal' => number_format($subtotal, 2),
            'total' => number_format($total, 2),
            'duracion_transaccion' => microtime(true) - LARAVEL_START . ' segundos'
        ]);

        Log::info('=== FINALIZANDO store() SOLICITUD COMPRA ===');

        return redirect()->route('solicitudcompra.index')
            ->with('success', 'Solicitud de compra ' . $codigoSolicitud . ' creada exitosamente y solicitud de almacén enviada a administración.')
            ->with('solicitud_id', $solicitudCompra->idSolicitudCompra)
            ->with('solicitud_codigo', $codigoSolicitud);

    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('❌ ERROR CRÍTICO en store():', [
            'mensaje' => $e->getMessage(),
            'archivo' => $e->getFile(),
            'linea' => $e->getLine(),
            'codigo_error' => $e->getCode(),
            'trace_completo' => $e->getTraceAsString(),
            'datos_request' => $request->except(['items', 'archivos', 'password', 'token']),
            'transaction_id' => $transactionId ?? 'No iniciada'
        ]);

        // Crear notificación de error para administradores
        if (class_exists(\App\Models\ErrorLog::class)) {
            \App\Models\ErrorLog::create([
                'usuario_id' => auth()->id(),
                'modulo' => 'SolicitudCompraController',
                'accion' => 'store',
                'error' => $e->getMessage(),
                'datos' => json_encode($request->except(['items', 'archivos', 'password', 'token'])),
                'ip' => $request->ip()
            ]);
        }

        return back()
            ->with('error', 'Error al crear la solicitud: ' . $e->getMessage())
            ->with('error_detalle', 'Contacte al administrador. Código de error: ' . ($transactionId ?? 'N/A'))
            ->withInput();
    }
}

/**
 * Método auxiliar para identificar campos faltantes
 */
private function getMissingFields(Request $request)
{
    $requiredFields = [
        'solicitante_compra',
        'solicitante_almacen', 
        'idTipoArea',
        'idPrioridad',
        'fecha_requerida',
        'justificacion',
        'items',
        'idSolicitudAlmacen'
    ];
    
    $missing = [];
    
    foreach ($requiredFields as $field) {
        if (empty($request->$field)) {
            $missing[] = $field;
        }
    }
    
    return $missing;
}




    // Método auxiliar para obtener proveedor sugerido
    private function getProveedorSugerido($item)
    {
        if (isset($item['proveedor_sugerido']) && !empty($item['proveedor_sugerido'])) {
            return $item['proveedor_sugerido'];
        }

        if (isset($item['idProveedor']) && $item['idProveedor'] === 'otro' && isset($item['proveedor_otro'])) {
            return $item['proveedor_otro'];
        }

        if (isset($item['idProveedor']) && $item['idProveedor'] !== 'otro' && !empty($item['idProveedor'])) {
            $proveedor = Proveedore::find($item['idProveedor']);
            return $proveedor ? $proveedor->nombre : '';
        }

        return '';
    }

    public function getSolicitudAlmacenDetalles($idSolicitudAlmacen)
    {
        try {
            // Cargar la solicitud de almacén completa con sus relaciones CORREGIDAS
            $solicitudAlmacen = SolicitudAlmacen::with([
                'area', // CORREGIDO: era 'tipoArea'
                'prioridad',
                'centroCosto',
                'detalles' => function ($query) {
                    $query->where('estado', 'aprobado');
                },
                'detalles.articulo' // AGREGAR ESTA RELACIÓN
            ])->findOrFail($idSolicitudAlmacen);

            if ($solicitudAlmacen->detalles->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay productos aprobados en esta solicitud de almacén',
                    'solicitud' => null,
                    'detalles' => []
                ]);
            }

            // **CORRECCIÓN: Formatear correctamente la fecha**
            $fechaRequerida = $solicitudAlmacen->fecha_requerida;
            if ($fechaRequerida instanceof \Carbon\Carbon) {
                $fechaRequerida = $fechaRequerida->format('Y-m-d');
            }

            // Preparar datos de la solicitud para autocompletar
            $solicitudData = [
                'idSolicitudAlmacen' => $solicitudAlmacen->idSolicitudAlmacen,
                'codigo_solicitud' => $solicitudAlmacen->codigo_solicitud,
                'titulo' => $solicitudAlmacen->titulo,
                'solicitante_almacen' => $solicitudAlmacen->solicitante,
                'idTipoArea' => $solicitudAlmacen->idTipoArea,
                'tipo_area_nombre' => $solicitudAlmacen->area->nombre ?? '',
                'idPrioridad' => $solicitudAlmacen->idPrioridad,
                'prioridad_nombre' => $solicitudAlmacen->prioridad->nombre ?? '',
                'fecha_requerida' => $fechaRequerida, // **USAR LA FECHA FORMATEADA**
                'idCentroCosto' => $solicitudAlmacen->idCentroCosto,
                'centro_costo_nombre' => $solicitudAlmacen->centroCosto ?
                    $solicitudAlmacen->centroCosto->codigo . ' - ' . $solicitudAlmacen->centroCosto->nombre : '',
                'justificacion' => $solicitudAlmacen->justificacion,
                'observaciones' => $solicitudAlmacen->observaciones
            ];

            // ... el resto del código igual
            $detallesData = [];
            foreach ($solicitudAlmacen->detalles as $detalle) {
                $detallesData[] = [
                    'idSolicitudAlmacenDetalle' => $detalle->idSolicitudAlmacenDetalle,
                    'idArticulo' => $detalle->idArticulo,
                    'descripcion_producto' => $detalle->descripcion_producto,
                    'categoria' => $detalle->categoria,
                    'idMonedas' => $detalle->idMonedas ?? null,
                    'cantidad' => $detalle->cantidad,
                    'cantidad_aprobada' => $detalle->cantidad,
                    'unidad' => $detalle->unidad,
                    'precio_unitario_estimado' => $detalle->precio_unitario_estimado ?? 0,
                    'total_producto' => $detalle->total_producto ?? 0,
                    'codigo_producto' => $detalle->codigo_producto,
                    'codigo_barras' => $detalle->articulo->codigo_barras ?? '', // AGREGAR ESTA LÍNEA
                    'marca' => $detalle->marca,
                    'especificaciones_tecnicas' => $detalle->especificaciones_tecnicas,
                    'proveedor_sugerido' => $detalle->proveedor_sugerido ?? '',
                    'justificacion_producto' => $detalle->justificacion_producto,
                    'observaciones_detalle' => $detalle->observaciones_detalle,
                    'fromAlmacen' => true
                ];
            }


            return response()->json([
                'success' => true,
                'solicitud' => $solicitudData,
                'detalles' => $detallesData,
                'message' => 'Datos cargados exitosamente'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en getSolicitudAlmacenDetalles: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los detalles: ' . $e->getMessage(),
                'solicitud' => null,
                'detalles' => []
            ], 500);
        }
    }
    public function show($id)
    {
        // Cargar la solicitud con todas las relaciones necesarias
        $solicitud = SolicitudCompra::with([
            'tipoArea',
            'prioridad',
            'centroCosto',
            'detalles.moneda', // Agregar relación con moneda en detalles
            'archivos',
            'solicitudAlmacen',
            'solicitudAlmacen.detalles' => function ($query) {
                $query->where('estado', 'aprobado');
            }
        ])->findOrFail($id);

        // Calcular estadísticas adicionales si es necesario
        $estadisticas = [
            'total_productos' => $solicitud->detalles->count(),
            'cantidad_total' => $solicitud->detalles->sum('cantidad'),
            'subtotal_detalles' => $solicitud->detalles->sum('total_producto')
        ];

        return view('solicitud.solicitudcompra.show', compact('solicitud', 'estadisticas'));
    }


    public function evaluacion($id)
    {
        // Cargar la solicitud con todas las relaciones necesarias incluyendo monedas
        $solicitud = SolicitudCompra::with([
            'tipoArea',
            'prioridad',
            'centroCosto',
            'detalles.moneda', // Agregar relación con moneda
            'archivos',
            'solicitudAlmacen',
            'solicitudAlmacen.detalles' => function ($query) {
                $query->where('estado', 'aprobado');
            }
        ])->findOrFail($id);

        // Calcular estadísticas de los detalles
        $totalDetalles = $solicitud->detalles->count();
        $aprobados = $solicitud->detalles->where('estado', 'Aprobado por administración')->count();
        $rechazados = $solicitud->detalles->where('estado', 'Rechazado por administración')->count();
        $pendientes = $solicitud->detalles->where('estado', 'pendiente')->count();

        // Determinar el estado general basado en los detalles
        $estadoGeneral = $this->determinarEstadoGeneral($solicitud);

        // Calcular información de monedas
        $solicitud->moneda_simbolo = $this->getResumenMoneda($solicitud);
        $solicitud->multiple_monedas = $this->hasMultipleCurrencies($solicitud);
        $solicitud->monedas_utilizadas = $this->getMonedasUtilizadas($solicitud);

        $estadisticas = [
            'total_productos' => $totalDetalles,
            'cantidad_total' => $solicitud->detalles->sum('cantidad'),
            'subtotal_detalles' => $solicitud->detalles->sum('total_producto'),
            'detalles_aprobados' => $aprobados,
            'detalles_rechazados' => $rechazados,
            'detalles_pendientes' => $pendientes,
            'estado_general' => $estadoGeneral,
            'puede_avanzar_estado' => $this->puedeAvanzarEstado($solicitud),
            'estados_siguientes' => $this->obtenerEstadosSiguientes($solicitud)
        ];

        return view('solicitud.solicitudcompra.evaluacioncompras', compact('solicitud', 'estadisticas'));
    }

    private function determinarEstadoGeneral($solicitud)
    {
        $detalles = $solicitud->detalles;
        $total = $detalles->count();
        $aprobados = $detalles->where('estado', 'Aprobado por administración')->count();
        $rechazados = $detalles->where('estado', 'Rechazado por administración')->count();
        $pendientes = $detalles->where('estado', 'pendiente')->count();

        if ($pendientes > 0) {
            return 'en_proceso'; // Todavía hay artículos pendientes
        } elseif ($aprobados == $total) {
            return 'completada'; // Todos los artículos aprobados por administración
        } elseif ($rechazados == $total) {
            return 'rechazada'; // Todos los artículos rechazados por administración
        } else {
            return 'en_proceso'; // Mezcla de aprobados y rechazados por administración
        }
    }


    private function puedeAvanzarEstado($solicitud)
    {
        // Solo se puede avanzar estado cuando todos los artículos están evaluados
        $detallesPendientes = $solicitud->detalles->where('estado', 'pendiente')->count();
        return $detallesPendientes === 0;
    }

    private function obtenerEstadosSiguientes($solicitud)
    {
        $estadosSiguientes = [];

        switch ($solicitud->estado) {
            case 'completada':
                $estadosSiguientes = [
                    'cancelada' => 'Cancelar Solicitud',
                    'presupuesto_aprobado' => 'Aprobar Presupuesto'
                ];
                break;

            case 'presupuesto_aprobado':
                $estadosSiguientes = [
                    'cancelada' => 'Cancelar Solicitud',
                    'pagado' => 'Marcar como Pagado'
                ];
                break;

            case 'pagado':
                $estadosSiguientes = [
                    'cancelada' => 'Cancelar Solicitud',
                    'finalizado' => 'Finalizar Proceso'
                ];
                break;

            case 'en_proceso':
                if ($this->puedeAvanzarEstado($solicitud)) {
                    $estadosSiguientes = [
                        'cancelada' => 'Cancelar Solicitud',
                        'completada' => 'Completar Evaluación'
                    ];
                } else {
                    $estadosSiguientes = [
                        'cancelada' => 'Cancelar Solicitud'
                    ];
                }
                break;

            case 'pendiente':
                $estadosSiguientes = [
                    'cancelada' => 'Cancelar Solicitud'
                ];
                break;
        }

        return $estadosSiguientes;
    }
    // Método para cambiar el estado de la solicitud
    public function cambiarEstado(Request $request, $id)
    {
        try {
            $solicitud = SolicitudCompra::findOrFail($id);
            $nuevoEstado = $request->estado;

            // Validar transición de estado
            $estadosPermitidos = $this->obtenerEstadosSiguientes($solicitud);

            if (!array_key_exists($nuevoEstado, $estadosPermitidos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transición de estado no permitida'
                ], 400);
            }

            \DB::beginTransaction();

            // Actualizar estado de solicitud compra
            $solicitud->update([
                'estado' => $nuevoEstado,
                'fecha_aprobacion' => in_array($nuevoEstado, ['completada', 'presupuesto_aprobado', 'pagado', 'finalizado']) ? now() : $solicitud->fecha_aprobacion
            ]);

            // Si el estado es 'finalizado', actualizar también la solicitud de almacén
            if ($nuevoEstado === 'finalizado' && $solicitud->idSolicitudAlmacen) {
                $solicitudAlmacen = SolicitudAlmacen::find($solicitud->idSolicitudAlmacen);
                if ($solicitudAlmacen) {
                    $solicitudAlmacen->update([
                        'estado' => 'finalizado',
                        'fecha_aprobacion' => now(),
                        'updated_at' => now()
                    ]);

                    \Log::info('Estado de solicitud almacén actualizado a finalizado:', [
                        'idSolicitudAlmacen' => $solicitudAlmacen->idSolicitudAlmacen,
                        'idSolicitudCompra' => $solicitud->idSolicitudCompra
                    ]);
                }
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado correctamente',
                'nuevo_estado' => $nuevoEstado
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método para cancelar la solicitud
    public function cancelarSolicitud(Request $request, $id)
    {
        try {
            $solicitud = SolicitudCompra::findOrFail($id);
            $motivo = $request->motivo;

            if (!$motivo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe proporcionar un motivo para cancelar'
                ], 400);
            }

            \DB::beginTransaction();

            $solicitud->update([
                'estado' => 'cancelada',
                'motivo_rechazo' => $motivo,
                'fecha_aprobacion' => now()
            ]);

            // Actualizar también la solicitud de almacén a cancelada
            if ($solicitud->idSolicitudAlmacen) {
                $solicitudAlmacen = SolicitudAlmacen::find($solicitud->idSolicitudAlmacen);
                if ($solicitudAlmacen) {
                    $solicitudAlmacen->update([
                        'estado' => 'cancelada',
                        'motivo_rechazo' => $motivo,
                        'fecha_aprobacion' => now(),
                        'updated_at' => now()
                    ]);

                    \Log::info('Estado de solicitud almacén actualizado a cancelada:', [
                        'idSolicitudAlmacen' => $solicitudAlmacen->idSolicitudAlmacen,
                        'idSolicitudCompra' => $solicitud->idSolicitudCompra,
                        'motivo' => $motivo
                    ]);
                }
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Solicitud cancelada correctamente'
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al cancelar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }



    public function aprobarArticulo(Request $request, $idSolicitud, $idDetalle)
    {
        try {
            \DB::beginTransaction();

            $detalle = SolicitudCompraDetalle::where('idSolicitudCompra', $idSolicitud)
                ->where('idSolicitudCompraDetalle', $idDetalle)
                ->firstOrFail();

            // Actualizar detalle de compra (REEMPLAZA estado)
            $detalle->update([
                'estado' => 'Aprobado por administración',
                'cantidad_aprobada' => $request->cantidad_aprobada ?? $detalle->cantidad,
                'observaciones_detalle' => $request->observaciones
            ]);

            // Si tiene relación con almacén, actualizar también el detalle de almacén (AGREGA estado)
            if ($detalle->idSolicitudAlmacenDetalle) {
                $detalleAlmacen = SolicitudAlmacenDetalle::find($detalle->idSolicitudAlmacenDetalle);
                if ($detalleAlmacen) {
                    $detalleAlmacen->update([
                        'estado' => 'Aprobado por administración', // NUEVO ESTADO
                        'cantidad_aprobada' => $request->cantidad_aprobada ?? $detalleAlmacen->cantidad,
                        'observaciones_detalle' => $request->observaciones
                    ]);
                }
            }

            // Recalcular el estado general de la solicitud
            $this->actualizarEstadoSolicitud($idSolicitud);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Artículo aprobado por administración correctamente'
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al aprobar el artículo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function rechazarArticulo(Request $request, $idSolicitud, $idDetalle)
    {
        try {
            \DB::beginTransaction();

            $detalle = SolicitudCompraDetalle::where('idSolicitudCompra', $idSolicitud)
                ->where('idSolicitudCompraDetalle', $idDetalle)
                ->firstOrFail();

            // Actualizar detalle de compra (REEMPLAZA estado)
            $detalle->update([
                'estado' => 'Rechazado por administración',
                'cantidad_aprobada' => 0,
                'observaciones_detalle' => $request->observaciones
            ]);

            // Si tiene relación con almacén, actualizar también el detalle de almacén (AGREGA estado)
            if ($detalle->idSolicitudAlmacenDetalle) {
                $detalleAlmacen = SolicitudAlmacenDetalle::find($detalle->idSolicitudAlmacenDetalle);
                if ($detalleAlmacen) {
                    $detalleAlmacen->update([
                        'estado' => 'Rechazado por administración', // NUEVO ESTADO
                        'cantidad_aprobada' => 0,
                        'observaciones_detalle' => $request->observaciones
                    ]);
                }
            }

            // Recalcular el estado general de la solicitud
            $this->actualizarEstadoSolicitud($idSolicitud);

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Artículo rechazado por administración correctamente'
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al rechazar el artículo: ' . $e->getMessage()
            ], 500);
        }
    }
    private function actualizarEstadoSolicitud($idSolicitud)
    {
        $solicitud = SolicitudCompra::with('detalles')->find($idSolicitud);
        $estadoGeneral = $this->determinarEstadoGeneral($solicitud);

        $solicitud->update([
            'estado' => $estadoGeneral
        ]);

        return $estadoGeneral;
    }




    public function edit($id)
    {
        $solicitud = SolicitudCompra::with([
            'tipoArea',
            'prioridad',
            'centroCosto',
            'detalles.moneda', // Agregar relación con moneda
            'archivos',
            'solicitudAlmacen',
            'solicitudAlmacen.detalles' => function ($query) {
                $query->where('estado', 'aprobado');
            }
        ])->findOrFail($id);

        // Verificar si se puede editar (solo pendiente)
        if ($solicitud->estado != 'pendiente') {
            return redirect()->route('solicitudcompra.show', $id)
                ->with('error', 'No se puede editar una solicitud que no está en estado pendiente.');
        }

        // Obtener datos para los selects
        $tipoAreas = TipoArea::all();
        $prioridades = PrioridadSolicitud::where('estado', 1)->get();
        $centrosCosto = CentroCosto::where('estado', 1)->get();
        $monedas = Moneda::all(); // Agregar monedas
        $proveedores = Proveedore::where('estado', 1)->get(); // Agregar proveedores

        return view('solicitud.solicitudcompra.edit', compact(
            'solicitud',
            'tipoAreas',
            'prioridades',
            'centrosCosto',
            'monedas', // Agregar monedas
            'proveedores' // Agregar proveedores
        ));
    }

    public function update(Request $request, $id)
    {
        \Log::info('=== UPDATE METHOD STARTED ===');
        \Log::info('Request ID:', ['id' => $id]);
        \Log::info('Request Data:', $request->all());

        try {
            $solicitud = SolicitudCompra::findOrFail($id);
            \Log::info('Solicitud found:', ['id' => $solicitud->idSolicitudCompra, 'estado' => $solicitud->estado]);

            // Verificar si se puede editar (solo pendiente)
            if ($solicitud->estado != 'pendiente') {
                \Log::warning('Solicitud not editable - not pendiente');
                return redirect()->route('solicitudcompra.show', $id)
                    ->with('error', 'No se puede editar una solicitud que no está en estado pendiente.');
            }

            // Validación actualizada - CORREGIR EL CAMPO idArticulo
            $validated = $request->validate([
                'idTipoArea' => 'required|exists:tipoarea,idTipoArea',
                'idPrioridad' => 'required|exists:prioridad_solicitud,idPrioridad',
                'fecha_requerida' => 'required|date',
                'justificacion' => 'required|string',
                'idCentroCosto' => 'nullable|exists:centro_costo,idCentroCosto',
                'proyecto_asociado' => 'nullable|string|max:255',
                'observaciones' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.descripcion_producto' => 'required|string',
                'items.*.cantidad' => 'required|integer|min:1',
                'items.*.precio_unitario_estimado' => 'required|numeric|min:0',
                'items.*.total_producto' => 'required|numeric|min:0',
                'items.*.idMonedas' => 'required|exists:monedas,idMonedas',
                'items.*.idSolicitudAlmacenDetalle' => 'nullable|exists:solicitud_almacen_detalle,idSolicitudAlmacenDetalle',
                // CORREGIR: Cambiar idArticulo por el nombre correcto de la columna
                'items.*.idArticulo' => 'nullable', // Remover la validación exists temporalmente
                'items.*.categoria' => 'nullable|string|max:100',
                'items.*.unidad' => 'nullable|string|max:50',
                'items.*.codigo_producto' => 'nullable|string|max:255',
                'items.*.marca' => 'nullable|string|max:100',
                'items.*.especificaciones_tecnicas' => 'nullable|string',
                'items.*.proveedor_sugerido' => 'nullable|string|max:255',
                'items.*.justificacion_producto' => 'nullable|string',
                'items.*.observaciones_detalle' => 'nullable|string',
                'archivos' => 'nullable|array',
                'archivos.*' => 'file|max:10240', // 10MB máximo
            ]);

            \Log::info('Validation passed');

            \DB::beginTransaction();

            // Calcular totales con IGV del 18% (como en el create)
            $subtotal = 0;
            $totalUnidades = 0;

            foreach ($request->items as $item) {
                $subtotal += $item['cantidad'] * $item['precio_unitario_estimado'];
                $totalUnidades += $item['cantidad'];
            }

            $igv = $subtotal * 0.18; // 18% IGV (como en el create)
            $total = $subtotal + $igv;

            \Log::info('Totals calculated:', [
                'subtotal' => $subtotal,
                'igv' => $igv,
                'total' => $total,
                'total_unidades' => $totalUnidades
            ]);

            // Actualizar solicitud de compra - mantener solicitantes originales
            $solicitud->update([
                'idTipoArea' => $request->idTipoArea,
                'idPrioridad' => $request->idPrioridad,
                'fecha_requerida' => $request->fecha_requerida,
                'idCentroCosto' => $request->idCentroCosto,
                'proyecto_asociado' => $request->proyecto_asociado,
                'justificacion' => $request->justificacion,
                'observaciones' => $request->observaciones,
                'subtotal' => $subtotal,
                'iva' => $igv,
                'total' => $total,
                'total_unidades' => $totalUnidades,
                // Mantener los solicitantes originales, no se actualizan
            ]);

            \Log::info('Solicitud updated successfully');

            // Eliminar detalles existentes y crear nuevos
            $solicitud->detalles()->delete();
            \Log::info('Old details deleted');

            // Crear nuevos detalles
            foreach ($request->items as $item) {
                // Determinar el proveedor sugerido
                $proveedorSugerido = $item['proveedor_sugerido'] ?? null;
                if (!$proveedorSugerido && isset($item['idProveedor'])) {
                    if ($item['idProveedor'] === 'otro') {
                        $proveedorSugerido = $item['proveedor_otro'] ?? null;
                    } else {
                        $proveedor = Proveedore::find($item['idProveedor']);
                        $proveedorSugerido = $proveedor ? $proveedor->nombre : null;
                    }
                }

                SolicitudCompraDetalle::create([
                    'idSolicitudCompra' => $solicitud->idSolicitudCompra,
                    'idSolicitudAlmacenDetalle' => $item['idSolicitudAlmacenDetalle'] ?? null,
                    'idArticulo' => $item['idArticulo'] ?? null, // VERIFICAR SI ESTE CAMPO EXISTE EN LA BD
                    'descripcion_producto' => $item['descripcion_producto'],
                    'categoria' => $item['categoria'] ?? null,
                    'cantidad' => $item['cantidad'],
                    'unidad' => $item['unidad'] ?? 'unidad',
                    'precio_unitario_estimado' => $item['precio_unitario_estimado'],
                    'total_producto' => $item['total_producto'],
                    'idMonedas' => $item['idMonedas'],
                    'codigo_producto' => $item['codigo_producto'] ?? null,
                    'marca' => $item['marca'] ?? null,
                    'especificaciones_tecnicas' => $item['especificaciones_tecnicas'] ?? null,
                    'proveedor_sugerido' => $proveedorSugerido,
                    'justificacion_producto' => $item['justificacion_producto'] ?? null,
                    'observaciones_detalle' => $item['observaciones_detalle'] ?? null,
                    'estado' => 'pendiente'
                ]);
            }

            \Log::info('New details created');

            // Guardar nuevos archivos si existen
            if ($request->hasFile('archivos')) {
                foreach ($request->file('archivos') as $archivo) {
                    $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                    $ruta = $archivo->storeAs('solicitudes_compra/' . $solicitud->idSolicitudCompra, $nombreArchivo, 'public');

                    SolicitudCompraArchivo::create([
                        'idSolicitudCompra' => $solicitud->idSolicitudCompra,
                        'nombre_archivo' => $archivo->getClientOriginalName(),
                        'ruta_archivo' => $ruta,
                        'tipo_archivo' => $archivo->getClientMimeType(),
                        'tamaño' => $archivo->getSize(),
                    ]);
                }
                \Log::info('New files uploaded');
            }

            \DB::commit();

            \Log::info('=== UPDATE COMPLETED SUCCESSFULLY ===');

            return redirect()->route('solicitudcompra.show', $solicitud->idSolicitudCompra)
                ->with('success', 'Solicitud de compra ' . $solicitud->codigo_solicitud . ' actualizada exitosamente.');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error in update method:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with('error', 'Error al actualizar la solicitud: ' . $e->getMessage())
                ->withInput();
        }
    }
    public function destroy($id)
    {
        $solicitud = SolicitudCompra::findOrFail($id);

        if ($solicitud->estado != 'pendiente') {
            return redirect()->route('solicitudcompra.index')
                ->with('error', 'No se puede eliminar una solicitud que no está pendiente.');
        }

        $solicitud->delete();

        return redirect()->route('solicitudcompra.index')
            ->with('success', 'Solicitud eliminada exitosamente.');
    }

    public function opciones($id)
    {
        // Método para acciones específicas (aprobar, rechazar, etc.)
    }




    public function gestionadministracion(Request $request)
    {
        try {
            Log::info('====== FILTROS FECHAS ======');
            Log::info('Fecha desde:', ['value' => $request->fecha_desde, 'filled' => $request->filled('fecha_desde')]);
            Log::info('Fecha hasta:', ['value' => $request->fecha_hasta, 'filled' => $request->filled('fecha_hasta')]);

            $query = SolicitudCompra::with([
                'detalles.moneda',
                'tipoArea',
                'prioridad',
                'detalles' => function ($query) {
                    $query->select(
                        'idSolicitudCompraDetalle',
                        'idSolicitudCompra',
                        'descripcion_producto',
                        'cantidad',
                        'unidad',
                        'precio_unitario_estimado',
                        'idMonedas'
                    );
                }
            ]);

            // Filtrar por estado si se proporciona
            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }

            // Filtrar por prioridad si se proporciona
            if ($request->filled('prioridad')) {
                $query->where('idPrioridad', $request->prioridad);
            }

            // Filtrar por fecha desde - DEBUG DETALLADO
            if ($request->filled('fecha_desde')) {
                Log::info('Aplicando filtro fecha_desde:', [
                    'valor' => $request->fecha_desde,
                    'tipo' => gettype($request->fecha_desde)
                ]);

                // Asegúrate de usar el campo correcto (puede ser 'fecha_creacion', 'created_at', etc.)
                $query->whereDate('created_at', '>=', $request->fecha_desde);
            }

            // Filtrar por fecha hasta - DEBUG DETALLADO
            if ($request->filled('fecha_hasta')) {
                Log::info('Aplicando filtro fecha_hasta:', [
                    'valor' => $request->fecha_hasta,
                    'tipo' => gettype($request->fecha_hasta)
                ]);

                $query->whereDate('created_at', '<=', $request->fecha_hasta);
            }

            Log::info('Query SQL:', ['sql' => $query->toSql()]);
            Log::info('Bindings:', ['bindings' => $query->getBindings()]);

            $solicitudes = $query->orderBy('created_at', 'desc')->paginate(12);


            // Procesar los datos aquí con manejo de errores
            $solicitudes->getCollection()->transform(function ($solicitud) {
                try {
                    $solicitud->resumen_moneda = $this->getResumenMoneda($solicitud);
                    $solicitud->multiple_currencies = $this->hasMultipleCurrencies($solicitud);
                    $solicitud->monedas_utilizadas = $this->getMonedasUtilizadas($solicitud);
                } catch (\Exception $e) {
                    Log::error('Error procesando solicitud ID: ' . ($solicitud->id ?? 'N/A'), [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);

                    // Valores por defecto en caso de error
                    $solicitud->resumen_moneda = 'S/';
                    $solicitud->multiple_currencies = false;
                    $solicitud->monedas_utilizadas = '';
                }
                return $solicitud;
            });

            Log::info('Solicitudes encontradas:', ['count' => $solicitudes->count()]);

            // Si es petición AJAX, devolver JSON con el HTML
            if ($request->ajax() || $request->has('ajax')) {
                try {
                    $html = view('solicitud.solicitudcompra.gestionadministracion-cards', compact('solicitudes'))->render();

                    return response()->json([
                        'success' => true,
                        'html' => $html,
                        'total' => $solicitudes->total(),
                        'count' => $solicitudes->count()
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error generando HTML:', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Error al generar la vista: ' . $e->getMessage(),
                        'html' => '<div class="alert alert-danger">Error al cargar los datos</div>'
                    ], 500);
                }
            }

            return view('solicitud.solicitudcompra.gestionadministracion', compact('solicitudes'));
        } catch (\Exception $e) {
            Log::error('Error en gestión administración:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            if ($request->ajax() || $request->has('ajax')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error interno del servidor: ' . $e->getMessage()
                ], 500);
            }

            return back()->withErrors(['error' => 'Ocurrió un error al procesar la solicitud']);
        }
    }

    private function getResumenMoneda($solicitud)
    {
        if ($solicitud->detalles->isEmpty()) return 'S/';

        $currencyCount = [];
        foreach ($solicitud->detalles as $detalle) {
            if ($detalle->moneda) {
                $currencyId = $detalle->moneda->idMonedas;
                $currencyCount[$currencyId] = ($currencyCount[$currencyId] ?? 0) + 1;
            }
        }

        if (empty($currencyCount)) return 'S/';

        $mostCommonCurrency = array_keys($currencyCount)[0];
        foreach ($solicitud->detalles as $detalle) {
            if ($detalle->moneda && $detalle->moneda->idMonedas == $mostCommonCurrency) {
                return $detalle->moneda->simbolo ?? 'S/';
            }
        }

        return 'S/';
    }

    private function hasMultipleCurrencies($solicitud)
    {
        $currencies = [];
        foreach ($solicitud->detalles as $detalle) {
            if ($detalle->moneda) {
                $currencyId = $detalle->moneda->idMonedas;
                if (!in_array($currencyId, $currencies)) {
                    $currencies[] = $currencyId;
                }
            }
        }
        return count($currencies) > 1;
    }

    private function getMonedasUtilizadas($solicitud)
    {
        $currencies = [];
        foreach ($solicitud->detalles as $detalle) {
            if ($detalle->moneda && !in_array($detalle->moneda->nombre, $currencies)) {
                $currencies[] = $detalle->moneda->nombre;
            }
        }
        return implode(', ', $currencies);
    }
}
