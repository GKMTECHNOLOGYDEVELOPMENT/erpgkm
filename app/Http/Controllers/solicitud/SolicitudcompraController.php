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
use App\Models\Proveedore;
use Illuminate\Support\Facades\Auth;

class SolicitudcompraController extends Controller
{
     public function index()
    {
        // Obtener todas las solicitudes de compra con relaciones
        $solicitudes = SolicitudCompra::with(['tipoArea', 'prioridad'])
            ->orderBy('created_at', 'desc')
            ->get();

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
    
    // Solo solicitudes de almacén aprobadas que tengan productos aprobados
    $solicitudesAlmacen = SolicitudAlmacen::where('estado', 'aprobada')
        ->whereHas('detalles', function($query) {
            $query->where('estado', 'aprobado');
        })
        ->with(['detalles' => function($query) {
            $query->where('estado', 'aprobado');
        }])
        ->get();

    // Obtener usuario autenticado como solicitante de compra
    $user = Auth::user();
    $solicitanteCompra = 'Usuario Sistema';
    
    if ($user) {
        // Construir nombre completo del usuario
        $solicitanteCompra = trim(
            ($user->name ?? '') . ' ' . 
            ($user->apellido_paterno ?? '') . ' ' . 
            ($user->apellido_materno ?? '')
        );
        
        if (empty(trim($solicitanteCompra))) {
            $solicitanteCompra = $user->email ?? 'Usuario Sistema';
        }
    }

    return view('solicitud.solicitudcompra.create', compact(
        'tipoAreas', 
        'prioridades', 
        'centrosCosto',
        'solicitudesAlmacen',
        'proveedores',
        'solicitanteCompra' // Pasar el solicitante a la vista
    ));
}
    public function store(Request $request)
    {
        $request->validate([
            'solicitante' => 'required|string|max:255',
            'idTipoArea' => 'required|exists:tipoarea,idTipoArea',
            'idPrioridad' => 'required|exists:prioridad_solicitud,idPrioridad',
            'fecha_requerida' => 'required|date',
            'justificacion' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.descripcion_producto' => 'required|string',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio_unitario_estimado' => 'required|numeric|min:0',
        ]);

        try {
            \DB::beginTransaction();

            // Generar código de solicitud
            $codigoSolicitud = 'SC-' . date('Ymd') . '-' . str_pad(SolicitudCompra::count() + 1, 4, '0', STR_PAD_LEFT);

            // Calcular totales
            $subtotal = 0;
            $totalUnidades = 0;

            foreach ($request->items as $item) {
                $subtotal += $item['cantidad'] * $item['precio_unitario_estimado'];
                $totalUnidades += $item['cantidad'];
            }

            $iva = $subtotal * 0.19; // 19% IVA
            $total = $subtotal + $iva;

            // Crear solicitud de compra
            $solicitudCompra = SolicitudCompra::create([
                'codigo_solicitud' => $codigoSolicitud,
                'idSolicitudAlmacen' => $request->idSolicitudAlmacen,
                'solicitante' => $request->solicitante,
                'idTipoArea' => $request->idTipoArea,
                'idPrioridad' => $request->idPrioridad,
                'fecha_requerida' => $request->fecha_requerida,
                'idCentroCosto' => $request->idCentroCosto,
                'proyecto_asociado' => $request->proyecto_asociado,
                'justificacion' => $request->justificacion,
                'observaciones' => $request->observaciones,
                'subtotal' => $subtotal,
                'iva' => $iva,
                'total' => $total,
                'total_unidades' => $totalUnidades,
                'estado' => 'pendiente'
            ]);

            // Crear detalles
            foreach ($request->items as $item) {
                SolicitudCompraDetalle::create([
                    'idSolicitudCompra' => $solicitudCompra->idSolicitudCompra,
                    'idSolicitudAlmacenDetalle' => $item['idSolicitudAlmacenDetalle'] ?? null,
                    'descripcion_producto' => $item['descripcion_producto'],
                    'categoria' => $item['categoria'],
                    'cantidad' => $item['cantidad'],
                    'unidad' => $item['unidad'],
                    'precio_unitario_estimado' => $item['precio_unitario_estimado'],
                    'total_producto' => $item['cantidad'] * $item['precio_unitario_estimado'],
                    'codigo_producto' => $item['codigo_producto'],
                    'marca' => $item['marca'],
                    'especificaciones_tecnicas' => $item['especificaciones_tecnicas'],
                    'proveedor_sugerido' => $item['proveedor_sugerido'],
                    'justificacion_producto' => $item['justificacion_producto'],
                    'estado' => 'pendiente'
                ]);
            }

            // Guardar archivos si existen
            if ($request->hasFile('archivos')) {
                foreach ($request->file('archivos') as $archivo) {
                    $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                    $ruta = $archivo->storeAs('solicitudes_compra/' . $solicitudCompra->idSolicitudCompra, $nombreArchivo, 'public');

                    SolicitudCompraArchivo::create([
                        'idSolicitudCompra' => $solicitudCompra->idSolicitudCompra,
                        'nombre_archivo' => $archivo->getClientOriginalName(),
                        'ruta_archivo' => $ruta,
                        'tipo_archivo' => $archivo->getClientMimeType(),
                        'tamaño' => $archivo->getSize(),
                    ]);
                }
            }

            \DB::commit();

            return redirect()->route('solicitudcompra.index')
                ->with('success', 'Solicitud de compra ' . $codigoSolicitud . ' creada exitosamente.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Error al crear la solicitud: ' . $e->getMessage());
        }
    }

// En SolicitudcompraController - CORRIGE el método:

public function getSolicitudAlmacenDetalles($idSolicitudAlmacen)
{
    try {
        // Cargar la solicitud de almacén completa con sus relaciones
        $solicitudAlmacen = SolicitudAlmacen::with([
            'tipoArea', // Cambiar 'area' por 'tipoArea' si esa es la relación
            'prioridad', 
            'centroCosto',
            'detalles' => function($query) {
                $query->where('estado', 'aprobado');
            }
        ])->findOrFail($idSolicitudAlmacen);

        if ($solicitudAlmacen->detalles->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No hay productos aprobados en esta solicitud de almacén',
                'solicitud' => null,
                'detalles' => []
            ]);
        }

        // Obtener usuario autenticado para solicitante de compra
        $user = Auth::user();
        $solicitanteCompra = 'Usuario Sistema';
        
        if ($user) {
            $solicitanteCompra = trim(
                ($user->name ?? '') . ' ' . 
                ($user->apellido_paterno ?? '') . ' ' . 
                ($user->apellido_materno ?? '')
            );
            
            if (empty(trim($solicitanteCompra))) {
                $solicitanteCompra = $user->email ?? 'Usuario Sistema';
            }
        }

        // **DEBUG: Ver qué datos tenemos**
        \Log::info('Solicitud Almacen Data:', [
            'idTipoArea' => $solicitudAlmacen->idTipoArea,
            'tipoArea_relation' => $solicitudAlmacen->tipoArea ? $solicitudAlmacen->tipoArea->toArray() : 'No relation',
            'area_relation' => $solicitudAlmacen->area ? $solicitudAlmacen->area->toArray() : 'No area relation'
        ]);

        // Preparar datos de la solicitud para autocompletar
        $solicitudData = [
            'idSolicitudAlmacen' => $solicitudAlmacen->idSolicitudAlmacen,
            'codigo_solicitud' => $solicitudAlmacen->codigo_solicitud,
            'titulo' => $solicitudAlmacen->titulo,
            'solicitante_almacen' => $solicitudAlmacen->solicitante,
            'solicitante_compra' => $solicitanteCompra,
            'idTipoArea' => $solicitudAlmacen->idTipoArea,
            'tipo_area_nombre' => $solicitudAlmacen->tipoArea->nombre ?? ($solicitudAlmacen->area->nombre ?? ''),
            'idPrioridad' => $solicitudAlmacen->idPrioridad,
            'prioridad_nombre' => $solicitudAlmacen->prioridad->nombre ?? '',
            'fecha_requerida' => $solicitudAlmacen->fecha_requerida,
            'idCentroCosto' => $solicitudAlmacen->idCentroCosto,
            'centro_costo_nombre' => $solicitudAlmacen->centroCosto ? 
                $solicitudAlmacen->centroCosto->codigo . ' - ' . $solicitudAlmacen->centroCosto->nombre : '',
            'justificacion' => $solicitudAlmacen->justificacion,
            'observaciones' => $solicitudAlmacen->observaciones
        ];

        // Preparar detalles
        $detallesData = [];
        foreach ($solicitudAlmacen->detalles as $detalle) {
            $detallesData[] = [
                'idSolicitudAlmacenDetalle' => $detalle->idSolicitudAlmacenDetalle,
                'idArticulo' => $detalle->idArticulo,
                'descripcion_producto' => $detalle->descripcion_producto,
                'categoria' => $detalle->categoria,
                'cantidad' => $detalle->cantidad,
                'cantidad_aprobada' => $detalle->cantidad,
                'unidad' => $detalle->unidad,
                'precio_unitario_estimado' => $detalle->precio_unitario_estimado ?? 0,
                'total_producto' => $detalle->total_producto ?? 0,
                'codigo_producto' => $detalle->codigo_producto,
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
        \Log::error('File: ' . $e->getFile());
        \Log::error('Line: ' . $e->getLine());
        \Log::error('Trace: ' . $e->getTraceAsString());
        
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
        'detalles',
        'archivos',
        'solicitudAlmacen',
        'solicitudAlmacen.detalles' => function($query) {
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
    // Cargar la solicitud con todas las relaciones necesarias
    $solicitud = SolicitudCompra::with([
        'tipoArea', 
        'prioridad', 
        'centroCosto', 
        'detalles',
        'archivos',
        'solicitudAlmacen',
        'solicitudAlmacen.detalles' => function($query) {
            $query->where('estado', 'aprobado');
        }
    ])->findOrFail($id);

    // Calcular estadísticas de los detalles
    $totalDetalles = $solicitud->detalles->count();
    $aprobados = $solicitud->detalles->where('estado', 'aprobado')->count();
    $rechazados = $solicitud->detalles->where('estado', 'rechazado')->count();
    $pendientes = $solicitud->detalles->where('estado', 'pendiente')->count();

    // Determinar el estado general basado en los detalles
    $estadoGeneral = $this->determinarEstadoGeneral($solicitud);

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
    $aprobados = $detalles->where('estado', 'aprobado')->count();
    $rechazados = $detalles->where('estado', 'rechazado')->count();
    $pendientes = $detalles->where('estado', 'pendiente')->count();

    if ($pendientes > 0) {
        return 'en_proceso'; // Todavía hay artículos pendientes
    } elseif ($aprobados == $total) {
        return 'completada'; // Todos los artículos aprobados
    } elseif ($rechazados == $total) {
        return 'rechazada'; // Todos los artículos rechazados
    } else {
        return 'en_proceso'; // Mezcla de aprobados y rechazados
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
                'pagado' => 'Marcar como Pagado'
            ];
            break;
            
        case 'pagado':
            $estadosSiguientes = [
                'finalizado' => 'Finalizar Proceso'
            ];
            break;
            
        case 'en_proceso':
            if ($this->puedeAvanzarEstado($solicitud)) {
                $estadosSiguientes = [
                    'completada' => 'Completar Evaluación'
                ];
            }
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
        
        // Actualizar estado
        $solicitud->update([
            'estado' => $nuevoEstado,
            'fecha_aprobacion' => in_array($nuevoEstado, ['completada', 'presupuesto_aprobado', 'pagado', 'finalizado']) ? now() : $solicitud->fecha_aprobacion
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente',
            'nuevo_estado' => $nuevoEstado
        ]);
        
    } catch (\Exception $e) {
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
        
        $solicitud->update([
            'estado' => 'cancelada',
            'motivo_rechazo' => $motivo,
            'fecha_aprobacion' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Solicitud cancelada correctamente'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al cancelar la solicitud: ' . $e->getMessage()
        ], 500);
    }
}



// En tu SolicitudCompraController
public function aprobarArticulo(Request $request, $idSolicitud, $idDetalle)
{
    try {
        $detalle = SolicitudCompraDetalle::where('idSolicitudCompra', $idSolicitud)
            ->where('idSolicitudCompraDetalle', $idDetalle)
            ->firstOrFail();

        $detalle->update([
            'estado' => 'aprobado',
            'cantidad_aprobada' => $request->cantidad_aprobada ?? $detalle->cantidad,
            'observaciones_detalle' => $request->observaciones
        ]);

        // Recalcular el estado general de la solicitud
        $this->actualizarEstadoSolicitud($idSolicitud);

        return response()->json([
            'success' => true,
            'message' => 'Artículo aprobado correctamente'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al aprobar el artículo: ' . $e->getMessage()
        ], 500);
    }
}

public function rechazarArticulo(Request $request, $idSolicitud, $idDetalle)
{
    try {
        $detalle = SolicitudCompraDetalle::where('idSolicitudCompra', $idSolicitud)
            ->where('idSolicitudCompraDetalle', $idDetalle)
            ->firstOrFail();

        $detalle->update([
            'estado' => 'rechazado',
            'cantidad_aprobada' => 0,
            'observaciones_detalle' => $request->observaciones
        ]);

        // Recalcular el estado general de la solicitud
        $this->actualizarEstadoSolicitud($idSolicitud);

        return response()->json([
            'success' => true,
            'message' => 'Artículo rechazado correctamente'
        ]);

    } catch (\Exception $e) {
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
        'detalles',
        'archivos',
        'solicitudAlmacen',
        'solicitudAlmacen.detalles' => function($query) {
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
    
    // Solo solicitudes de almacén aprobadas que tengan productos aprobados
    $solicitudesAlmacen = SolicitudAlmacen::where('estado', 'aprobada')
        ->whereHas('detalles', function($query) {
            $query->where('estado', 'aprobado');
        })
        ->with(['detalles' => function($query) {
            $query->where('estado', 'aprobado');
        }])
        ->get();

    return view('solicitud.solicitudcompra.edit', compact(
        'solicitud',
        'tipoAreas', 
        'prioridades', 
        'centrosCosto',
        'solicitudesAlmacen'
    ));
}public function update(Request $request, $id)
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

        // Validación
        $validated = $request->validate([
            'solicitante' => 'required|string|max:255',
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
            'archivos' => 'nullable|array',
            'archivos.*' => 'file|max:10240', // 10MB máximo
        ]);

        \Log::info('Validation passed');

        \DB::beginTransaction();

        // Calcular totales
        $subtotal = 0;
        $totalUnidades = 0;

        foreach ($request->items as $item) {
            $subtotal += $item['cantidad'] * $item['precio_unitario_estimado'];
            $totalUnidades += $item['cantidad'];
        }

        $iva = $subtotal * 0.19; // 19% IVA
        $total = $subtotal + $iva;

        \Log::info('Totals calculated:', [
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $total,
            'total_unidades' => $totalUnidades
        ]);

        // Actualizar solicitud de compra
        $solicitud->update([
            'solicitante' => $request->solicitante,
            'idTipoArea' => $request->idTipoArea,
            'idPrioridad' => $request->idPrioridad,
            'fecha_requerida' => $request->fecha_requerida,
            'idCentroCosto' => $request->idCentroCosto,
            'proyecto_asociado' => $request->proyecto_asociado,
            'justificacion' => $request->justificacion,
            'observaciones' => $request->observaciones,
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $total,
            'total_unidades' => $totalUnidades,
        ]);

        \Log::info('Solicitud updated successfully');

        // Eliminar detalles existentes y crear nuevos
        $solicitud->detalles()->delete();
        \Log::info('Old details deleted');

        // Crear nuevos detalles
        foreach ($request->items as $item) {
            SolicitudCompraDetalle::create([
                'idSolicitudCompra' => $solicitud->idSolicitudCompra,
                'idSolicitudAlmacenDetalle' => $item['idSolicitudAlmacenDetalle'] ?? null,
                'idArticulo' => $item['idArticulo'] ?? null,
                'descripcion_producto' => $item['descripcion_producto'],
                'categoria' => $item['categoria'] ?? null,
                'cantidad' => $item['cantidad'],
                'unidad' => $item['unidad'] ?? null,
                'precio_unitario_estimado' => $item['precio_unitario_estimado'],
                'total_producto' => $item['total_producto'],
                'codigo_producto' => $item['codigo_producto'] ?? null,
                'marca' => $item['marca'] ?? null,
                'especificaciones_tecnicas' => $item['especificaciones_tecnicas'] ?? null,
                'proveedor_sugerido' => $item['proveedor_sugerido'] ?? null,
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


   

public function gestionadministracion()
{
    $solicitudes = SolicitudCompra::with(['detalles' => function($query) {
            $query->select('idSolicitudCompraDetalle', 'idSolicitudCompra', 'descripcion_producto', 
                          'cantidad', 'unidad', 'precio_unitario_estimado');
        }])
        ->orderBy('created_at', 'desc')
        ->paginate(12); // o el número que prefieras
    
    return view('solicitud.solicitudcompra.gestionadministracion', compact('solicitudes'));
}
}