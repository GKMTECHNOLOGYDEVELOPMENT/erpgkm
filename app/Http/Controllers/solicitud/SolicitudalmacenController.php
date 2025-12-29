<?php

namespace App\Http\Controllers\solicitud;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Models\CentroCosto;
use App\Models\PrioridadSolicitud;
use App\Models\Proveedore;
use App\Models\SolicitudAlmacen;
use App\Models\SolicitudAlmacenDetalle;
use App\Models\SolicitudAlmacenHistorial;
use App\Models\Tipoarea;
use App\Models\TipoSolicitud;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SolicitudalmacenController extends Controller
{
    public function index()
    {
        // Obtener las solicitudes de la base de datos con relaciones
        $solicitudes = SolicitudAlmacen::with(['tipoSolicitud', 'prioridad', 'centroCosto', 'detalles'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Transformar los datos para el frontend
        $requests = $solicitudes->map(function ($solicitud) {
            return [
                'id' => $solicitud->idSolicitudAlmacen,
                'code' => $solicitud->codigo_solicitud,
                'title' => $solicitud->titulo,
                'description' => $solicitud->descripcion,
                'type' => $solicitud->tipoSolicitud->nombre ?? 'Sin tipo',
                'type_id' => $solicitud->idTipoSolicitud,
                'status' => $solicitud->estado,
                'priority' => $this->getPriorityLevel($solicitud->prioridad->nivel ?? 2),
                'priority_id' => $solicitud->idPrioridad,
                'requested_by' => $solicitud->solicitante,
                'required_date' => $solicitud->fecha_requerida,
                'created_at' => $solicitud->created_at,
                // QUITAR: 'total_value' => $solicitud->total,
                'products' => $solicitud->detalles->map(function ($detalle) {
                    return [
                        'id' => $detalle->idSolicitudAlmacenDetalle,
                        'name' => $detalle->descripcion_producto,
                        'code' => $detalle->codigo_producto, // <-- AQUÍ AGREGAS EL CÓDIGO
                        'quantity' => $detalle->cantidad,
                        'unit' => $detalle->unidad
                    ];
                })->toArray()
            ];
        });

        return view('solicitud.solicitudalmacen.index', compact('requests'));
    }

    private function getPriorityLevel($nivel)
    {
        $map = [
            1 => 'low',
            2 => 'medium',
            3 => 'high',
            4 => 'urgent'
        ];

        return $map[$nivel] ?? 'medium';
    }


    public function create()
    {
        $tiposSolicitud = TipoSolicitud::where('estado', 1)->get();
        $prioridades = PrioridadSolicitud::where('estado', 1)->get();
        $centrosCosto = CentroCosto::where('estado', 1)->get();
        $areas = Tipoarea::all(); // Añadir where('estado', 1) si aplica
        $articulos = Articulo::whereIn('idTipoArticulo', [1, 3, 4])
            ->where('estado', 1)
            ->get();

        // Obtener usuario autenticado - CORREGIDO
        $user = Auth::user();

        // DEBUG: Ver qué información tiene el usuario
        // dd($user); // Descomenta temporalmente para ver la estructura

        // Opción 1: Si usas la tabla 'usuarios' con idUsuario
        $usuario = Usuario::where('idUsuario', $user->idUsuario ?? $user->id)->first();

        // Opción 2: Si el modelo User está mapeado a la tabla usuarios
        // $usuario = $user; // Usar directamente el usuario autenticado

        // Construir el nombre del solicitante
        $nombreSolicitante = 'Usuario Almacén'; // Valor por defecto

        if ($usuario) {
            $nombreSolicitante = trim(
                ($usuario->Nombre ?? $usuario->name ?? '') . ' ' .
                    ($usuario->apellidoPaterno ?? $usuario->apellido_paterno ?? '') . ' ' .
                    ($usuario->apellidoMaterno ?? $usuario->apellido_materno ?? '')
            );

            // Si está vacío, usar el email o nombre de usuario
            if (empty(trim($nombreSolicitante))) {
                $nombreSolicitante = $usuario->email ?? $usuario->username ?? 'Usuario Almacén';
            }
        }

        return view('solicitud.solicitudalmacen.create', compact(
            'tiposSolicitud',
            'prioridades',
            'centrosCosto',
            'areas',
            'articulos',
            'nombreSolicitante'
        ));
    }



    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Generar código único para la solicitud
            $codigo = 'SA-' . date('ymd') . '-' . str_pad(SolicitudAlmacen::count() + 1, 3, '0', STR_PAD_LEFT);

            // Crear la solicitud principal SIN PRECIOS
            $solicitud = SolicitudAlmacen::create([
                'codigo_solicitud' => $codigo,
                'titulo' => $request->titulo,
                'idTipoSolicitud' => $request->idTipoSolicitud,
                'solicitante' => $request->solicitante,
                'idPrioridad' => $request->idPrioridad,
                'fecha_requerida' => $request->fecha_requerida,
                'idCentroCosto' => $request->idCentroCosto,
                'idTipoArea' => $request->idTipoArea, // Añadir esta línea
                'descripcion' => $request->descripcion,
                'justificacion' => $request->justificacion,
                'observaciones' => $request->observaciones,
                'total_unidades' => $request->total_unidades,
                'estado' => 'pendiente'
            ]);

              // Guardar en solicitudentrega
        DB::table('solicitudentrega')->insert([
            'idUsuario' => auth()->id(), // O el ID del usuario que registra
            'comentario' => 'Solicitud de Almacén: ' . $request->descripcion . ' - ' . $request->justificacion,
            'estado' => 0,
            'fechaHora' => now(), // Fecha y hora actual
            'idTipoServicio' => 8, // Ajustar si es necesario
            'numero_ticket' => $codigo, // Usar el código de la solicitud como número de ticket
            'idTickets' => null, // Ajustar si es necesario
            'idVisitas' => null, // Ajustar si es necesario
        ]);

            // ... el resto del código permanece igual
            foreach ($request->productos as $producto) {
                SolicitudAlmacenDetalle::create([
                    'idSolicitudAlmacen' => $solicitud->idSolicitudAlmacen,
                    'idArticulo' => $producto['idArticulo'] ?? null,
                    'descripcion_producto' => $producto['descripcion'],
                    'cantidad' => $producto['cantidad'],
                    'unidad' => $producto['unidad_nombre'],
                    'categoria' => $producto['categoria_nombre'],
                    'codigo_producto' => $producto['codigo_barras'],
                    'marca' => $producto['marca_nombre'],
                    'especificaciones_tecnicas' => $producto['especificaciones'],
                    'justificacion_producto' => $producto['justificacion_producto']
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Solicitud creada exitosamente',
                'codigo' => $codigo,
                'id' => $solicitud->idSolicitudAlmacen
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }


    // En el método getSelectData del controlador
    public function getSelectData()
    {
        $tiposSolicitud = TipoSolicitud::where('estado', 1)->get();
        $prioridades = PrioridadSolicitud::where('estado', 1)->get();
        $centrosCosto = CentroCosto::where('estado', 1)->get();
        $areas = TipoArea::all(); // Añadir esta línea
        $articulos = Articulo::whereIn('idTipoArticulo', [1, 3, 4])
            ->where('estado', 1)
            ->get(['idArticulos', 'nombre', 'codigo_barras', 'sku']);

        return response()->json([
            'tiposSolicitud' => $tiposSolicitud,
            'prioridades' => $prioridades,
            'centrosCosto' => $centrosCosto,
            'areas' => $areas, // Añadir esta línea
            'articulos' => $articulos
        ]);
    }

    // En tu controlador, agrega este método
    public function buscarArticulos(Request $request)
    {
        try {
            $searchTerm = $request->get('search', '');

            Log::info('Buscando con término: ' . $searchTerm);

            $articulos = Articulo::where(function ($query) use ($searchTerm) {
                $query->where('nombre', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('codigo_repuesto', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('codigo_barras', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('sku', 'LIKE', "%{$searchTerm}%");
            })
                ->with(['unidad', 'modelo.marca', 'modelo.categoria'])
                ->orderByRaw("
                CASE 
                    WHEN nombre IS NOT NULL AND nombre != '' THEN 1
                    ELSE 2
                END
            ")
                ->orderBy('codigo_repuesto')
                ->paginate(10);

            $formattedArticles = $articulos->map(function ($articulo) {
                // Si no tiene nombre, usar el código repuesto como texto
                $texto = !empty($articulo->nombre) ? $articulo->nombre : (!empty($articulo->codigo_repuesto) ? $articulo->codigo_repuesto : (!empty($articulo->codigo_barras) ? $articulo->codigo_barras : (!empty($articulo->sku) ? $articulo->sku : 'Sin nombre')));

                return [
                    'id' => $articulo->idArticulos,
                    'text' => $texto,
                    'nombre' => $articulo->nombre,
                    'codigo_barras' => $articulo->codigo_barras,
                    'codigo_repuesto' => $articulo->codigo_repuesto,
                    'sku' => $articulo->sku,
                    'idArticulos' => $articulo->idArticulos,
                    'marca' => $articulo->modelo && $articulo->modelo->marca ? $articulo->modelo->marca->nombre : null,
                    'categoria' => $articulo->modelo && $articulo->modelo->categoria ? $articulo->modelo->categoria->nombre : null,
                    'unidad' => $articulo->unidad ? $articulo->unidad->nombre : null,
                    'precio_compra' => $articulo->precio_compra,
                    'stock_total' => $articulo->stock_total
                ];
            });

            Log::info('Resultados formateados: ' . count($formattedArticles));
            if (count($formattedArticles) > 0) {
                Log::info('Primer resultado: ' . json_encode($formattedArticles[0]));
            }

            return response()->json([
                'success' => true,
                'data' => $formattedArticles,
                'next_page_url' => $articulos->nextPageUrl(),
                'debug' => [
                    'search' => $searchTerm,
                    'found' => count($formattedArticles)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error en buscarArticulos: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'data' => [],
                'next_page_url' => null,
                'message' => 'Error en la búsqueda'
            ]);
        }
    }

    // Método para mostrar la vista de detalles
    public function show($id)
    {
        return view('solicitud.solicitudalmacen.detalles', compact('id'));
    }

    public function getDetailData($id)
    {
        try {
            $solicitud = SolicitudAlmacen::with([
                'tipoSolicitud',
                'prioridad',
                'centroCosto',
                'area',
                'detalles',
                'archivos',
                'historial.usuario'
            ])->findOrFail($id);

            // DEBUG: Verificar relaciones
            \Log::info('Solicitud cargada:', [
                'id' => $solicitud->idSolicitudAlmacen,
                'tipoSolicitud' => $solicitud->tipoSolicitud,
                'tipoSolicitud_id' => $solicitud->idTipoSolicitud,
                'tipoSolicitud_nombre' => $solicitud->tipoSolicitud ? $solicitud->tipoSolicitud->nombre : 'NO CARGADO'
            ]);

            return response()->json([
                'success' => true,
                'solicitud' => $solicitud
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en getDetailData: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los detalles: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        // Obtener la solicitud con relaciones
        $solicitud = SolicitudAlmacen::with([
            'tipoSolicitud',
            'prioridad',
            'centroCosto',
            'area', // Añadir esta relación
            'detalles'
        ])->findOrFail($id);

        // Solo permitir edición si está pendiente
        if ($solicitud->estado !== 'pendiente') {
            return redirect()->route('solicitudalmacen.index')
                ->with('error', 'Solo se pueden editar solicitudes pendientes');
        }

        $tiposSolicitud = TipoSolicitud::where('estado', 1)->get();
        $prioridades = PrioridadSolicitud::where('estado', 1)->get();
        $centrosCosto = CentroCosto::where('estado', 1)->get();
        $areas = Tipoarea::all(); // Añadir esta línea
        // QUITAR: $proveedores = Proveedore::where('estado', 1)->get();

        return view('solicitud.solicitudalmacen.edit', compact(
            'solicitud',
            'tiposSolicitud',
            'prioridades',
            'centrosCosto',
            'areas', // Añadir esta variable
            'id'
        ));
    }

    // Método para cargar datos de edición (API)
    public function getEditData($id)
    {
        try {
            $solicitud = SolicitudAlmacen::with([
                'tipoSolicitud',
                'prioridad',
                'centroCosto',
                'area',
                'detalles.articulo.modelo.marca',
                'detalles.articulo.modelo.categoria',
                'detalles.articulo.unidad'
            ])->findOrFail($id);

            // Función para limpiar caracteres UTF-8
            $cleanData = function ($data) use (&$cleanData) {
                if (is_array($data)) {
                    return array_map($cleanData, $data);
                } elseif (is_object($data)) {
                    $data = (array) $data;
                    return array_map($cleanData, $data);
                } elseif (is_string($data)) {
                    // Limpiar caracteres malformados
                    return mb_convert_encoding($data, 'UTF-8', 'UTF-8');
                }
                return $data;
            };

            // Preparar datos de la solicitud manualmente
            $solicitudData = [
                'idSolicitudAlmacen' => $solicitud->idSolicitudAlmacen,
                'codigo_solicitud' => $solicitud->codigo_solicitud,
                'titulo' => $solicitud->titulo,
                'idTipoSolicitud' => $solicitud->idTipoSolicitud,
                'solicitante' => $solicitud->solicitante,
                'idPrioridad' => $solicitud->idPrioridad,
                'fecha_requerida' => $solicitud->fecha_requerida ? $solicitud->fecha_requerida->format('Y-m-d') : null,
                'idCentroCosto' => $solicitud->idCentroCosto,
                'idTipoArea' => $solicitud->idTipoArea,
                'descripcion' => $solicitud->descripcion,
                'justificacion' => $solicitud->justificacion,
                'observaciones' => $solicitud->observaciones,
                'total_unidades' => $solicitud->total_unidades,
                'estado' => $solicitud->estado,
                'created_at' => $solicitud->created_at,
                'updated_at' => $solicitud->updated_at,
            ];

            // Procesar los detalles
            $detallesData = [];
            foreach ($solicitud->detalles as $detalle) {
                $detalleData = [
                    'idSolicitudAlmacenDetalle' => $detalle->idSolicitudAlmacenDetalle,
                    'idSolicitudAlmacen' => $detalle->idSolicitudAlmacen,
                    'idArticulo' => $detalle->idArticulo,
                    'descripcion_producto' => $detalle->descripcion_producto,
                    'cantidad' => $detalle->cantidad,
                    'unidad' => $detalle->unidad,
                    'categoria' => $detalle->categoria,
                    'codigo_producto' => $detalle->codigo_producto,
                    'marca' => $detalle->marca,
                    'especificaciones_tecnicas' => $detalle->especificaciones_tecnicas,
                    'justificacion_producto' => $detalle->justificacion_producto,
                    'created_at' => $detalle->created_at,
                    'updated_at' => $detalle->updated_at,
                ];

                // Si hay un artículo relacionado, obtener información del modelo
                if ($detalle->articulo && $detalle->articulo->modelo) {
                    $detalleData['modelo_nombre'] = $detalle->articulo->modelo->nombre ?? '';
                    $detalleData['marca_nombre'] = $detalle->articulo->modelo->marca ? ($detalle->articulo->modelo->marca->nombre ?? '') : ($detalle->marca ?? '');
                    $detalleData['categoria_nombre'] = $detalle->articulo->modelo->categoria ? ($detalle->articulo->modelo->categoria->nombre ?? '') : ($detalle->categoria ?? '');
                    $detalleData['unidad_nombre'] = $detalle->articulo->unidad ? ($detalle->articulo->unidad->nombre ?? '') : ($detalle->unidad ?? '');
                } else {
                    // Si no hay artículo relacionado, usar los valores guardados en el detalle
                    $detalleData['modelo_nombre'] = '';
                    $detalleData['marca_nombre'] = $detalle->marca ?? '';
                    $detalleData['categoria_nombre'] = $detalle->categoria ?? '';
                    $detalleData['unidad_nombre'] = $detalle->unidad ?? '';
                }

                $detallesData[] = $detalleData;
            }

            $solicitudData['detalles'] = $detallesData;

            // Limpiar los datos antes de enviar
            $cleanedData = $cleanData($solicitudData);

            return response()->json([
                'success' => true,
                'solicitud' => $cleanedData
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en getEditData: ' . $e->getMessage());
            \Log::error('File: ' . $e->getFile());
            \Log::error('Line: ' . $e->getLine());

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los datos de edición'
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $solicitud = SolicitudAlmacen::findOrFail($id);

            // Validar que solo se puedan editar solicitudes pendientes
            if ($solicitud->estado !== 'pendiente') {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo se pueden editar solicitudes pendientes'
                ], 400);
            }

            // Actualizar la solicitud principal SIN PRECIOS
            $solicitud->update([
                'titulo' => $request->titulo,
                'idTipoSolicitud' => $request->idTipoSolicitud,
                'solicitante' => $request->solicitante,
                'idPrioridad' => $request->idPrioridad,
                'fecha_requerida' => $request->fecha_requerida,
                'idCentroCosto' => $request->idCentroCosto,
                'idTipoArea' => $request->idTipoArea, // Añadir esta línea
                'descripcion' => $request->descripcion,
                'justificacion' => $request->justificacion,
                'observaciones' => $request->observaciones,
                'total_unidades' => $request->total_unidades,
            ]);

            // Eliminar detalles existentes y crear nuevos SIN PRECIOS NI PROVEEDOR
            $solicitud->detalles()->delete();

            foreach ($request->productos as $producto) {
                SolicitudAlmacenDetalle::create([
                    'idSolicitudAlmacen' => $solicitud->idSolicitudAlmacen,
                    'idArticulo' => $producto['idArticulo'] ?? null,
                    'descripcion_producto' => $producto['descripcion'],
                    'cantidad' => $producto['cantidad'],
                    'unidad' => $producto['unidad_nombre'],
                    'categoria' => $producto['categoria_nombre'],
                    'codigo_producto' => $producto['codigo_barras'],
                    'marca' => $producto['marca_nombre'],
                    'especificaciones_tecnicas' => $producto['especificaciones'],
                    'justificacion_producto' => $producto['justificacion_producto']
                ]);
            }

            // Registrar en el historial
            if (method_exists($solicitud, 'registrarHistorial')) {
                $solicitud->registrarHistorial(
                    'pendiente', // Mantiene el mismo estado
                    'pendiente',
                    'Solicitud actualizada por el usuario',
                    auth()->id()
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Solicitud actualizada exitosamente',
                'id' => $solicitud->idSolicitudAlmacen
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }

    // Método para cambiar estado (aprobación/rechazo)
    // public function changeStatus(Request $request, $id)
    // {
    //     try {
    //         $solicitud = SolicitudAlmacen::findOrFail($id);
    //         $estadoAnterior = $solicitud->estado;

    //         $solicitud->update([
    //             'estado' => $request->estado,
    //             'motivo_rechazo' => $request->motivo_rechazo,
    //             'fecha_aprobacion' => $request->estado === 'aprobada' ? now() : null,
    //             'aprobado_por' => $request->estado === 'aprobada' ? auth()->id() : null
    //         ]);

    //         // Registrar en el historial
    //         SolicitudAlmacenHistorial::create([
    //             'idSolicitudAlmacen' => $solicitud->idSolicitudAlmacen,
    //             'estado_anterior' => $estadoAnterior,
    //             'estado_nuevo' => $request->estado,
    //             'observaciones' => $request->observaciones,
    //             'usuario_id' => auth()->id()
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Estado actualizado exitosamente'
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error al cambiar el estado: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function destroy($id)
    {
        //
    }

    public function opciones($id)
    {
        //
    }


    // En el controlador SolicitudalmacenController

    // Método para cambiar estado de la solicitud
    public function changeStatus(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $solicitud = SolicitudAlmacen::with('detalles')->findOrFail($id);
            $estadoAnterior = $solicitud->estado;
            $nuevoEstado = $request->estado;

            // Validar transiciones de estado permitidas
            $transicionesPermitidas = [
                'pendiente' => ['aprobada', 'rechazada', 'en_proceso'],
                'en_proceso' => ['completada'],
                'completada' => ['aprobada', 'rechazada']
            ];

            if (
                !isset($transicionesPermitidas[$estadoAnterior]) ||
                !in_array($nuevoEstado, $transicionesPermitidas[$estadoAnterior])
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transición de estado no permitida'
                ], 400);
            }

            // Actualizar estado de la solicitud
            $solicitud->update([
                'estado' => $nuevoEstado,
                'motivo_rechazo' => $request->motivo_rechazo,
                'fecha_aprobacion' => $nuevoEstado === 'aprobada' ? now() : null,
                'aprobado_por' => $nuevoEstado === 'aprobada' ? auth()->id() : null
            ]);

            // Registrar en el historial
            SolicitudAlmacenHistorial::create([
                'idSolicitudAlmacen' => $solicitud->idSolicitudAlmacen,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $nuevoEstado,
                'observaciones' => $request->observaciones,
                'usuario_id' => auth()->id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado: ' . $e->getMessage()
            ], 500);
        }
    }

    // MEJORA el método changeDetailStatus:

    public function changeDetailStatus(Request $request, $id)
    {
        Log::info('=== CHANGE DETAIL STATUS INICIADO ===');
        Log::info('ID detalle: ' . $id);
        Log::info('Nuevo estado: ' . $request->estado);

        try {
            DB::beginTransaction();

            // Buscar el detalle con sus relaciones
            $detalle = SolicitudAlmacenDetalle::with(['solicitud', 'solicitud.detalles'])->findOrFail($id);
            $solicitud = $detalle->solicitud;

            Log::info('Detalle encontrado - ID: ' . $detalle->idSolicitudAlmacenDetalle);
            Log::info('Solicitud ID: ' . $solicitud->idSolicitudAlmacen);
            Log::info('Estado actual solicitud: ' . $solicitud->estado);
            Log::info('Estado actual detalle: ' . $detalle->estado);

            $estadoAnteriorDetalle = $detalle->estado;

            // Validar que la solicitud permita cambios
            if (!in_array($solicitud->estado, ['pendiente', 'en_proceso', 'completada'])) {
                Log::warning('Solicitud no permite cambios - Estado: ' . $solicitud->estado);
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede modificar productos en solicitudes ' . $solicitud->estado
                ], 400);
            }

            // Actualizar estado del detalle
            $detalle->update([
                'estado' => $request->estado,
                'observaciones_detalle' => $request->observaciones_detalle,
                'updated_at' => now()
            ]);

            Log::info('Detalle actualizado - Nuevo estado: ' . $request->estado);

            // Recalcular estado de la solicitud basado en los detalles
            $this->recalcularEstadoSolicitud($solicitud);

            // Recargar la solicitud para obtener el estado actualizado
            $solicitud->refresh();

            // Registrar en el historial del detalle
            SolicitudAlmacenHistorial::create([
                'idSolicitudAlmacen' => $solicitud->idSolicitudAlmacen,
                'estado_anterior' => $estadoAnteriorDetalle,
                'estado_nuevo' => $request->estado,
                'observaciones' => "Producto actualizado: " . $detalle->descripcion_producto .
                    " - Estado: " . $request->estado .
                    ($request->observaciones_detalle ? " - Observaciones: " . $request->observaciones_detalle : ""),
                'usuario_id' => auth()->id(),
                'tipo_cambio' => 'detalle'
            ]);

            DB::commit();

            Log::info('=== CHANGE DETAIL STATUS COMPLETADO ===');
            Log::info('Nuevo estado solicitud: ' . $solicitud->estado);

            return response()->json([
                'success' => true,
                'message' => 'Estado del producto actualizado exitosamente',
                'solicitud_estado' => $solicitud->estado,
                'detalles_estado' => [
                    'aprobados' => $solicitud->detalles->where('estado', 'aprobado')->count(),
                    'rechazados' => $solicitud->detalles->where('estado', 'rechazado')->count(),
                    'pendientes' => $solicitud->detalles->where('estado', 'pendiente')->count()
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error en changeDetailStatus: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado del producto: ' . $e->getMessage()
            ], 500);
        }
    }

    // REEMPLAZA completamente la función recalcularEstadoSolicitud:

    private function recalcularEstadoSolicitud($solicitud)
    {
        // Recargar los detalles para tener la información más actualizada
        $solicitud->load('detalles');
        $detalles = $solicitud->detalles;

        if ($detalles->isEmpty()) {
            return;
        }

        $totalDetalles = $detalles->count();
        $aprobados = $detalles->where('estado', 'aprobado')->count();
        $rechazados = $detalles->where('estado', 'rechazado')->count();
        $pendientes = $detalles->where('estado', 'pendiente')->count();

        $nuevoEstado = $solicitud->estado;

        \Log::info("=== RECALCULANDO ESTADO ===");
        \Log::info("Solicitud ID: " . $solicitud->idSolicitudAlmacen);
        \Log::info("Estado actual: " . $solicitud->estado);
        \Log::info("Total detalles: " . $totalDetalles);
        \Log::info("Aprobados: " . $aprobados);
        \Log::info("Rechazados: " . $rechazados);
        \Log::info("Pendientes: " . $pendientes);

        // LÓGICA PRINCIPAL CORREGIDA BRO
        if ($solicitud->estado === 'pendiente') {
            // Si al menos un producto fue movido de pendiente a rechazado o aprobado
            if ($aprobados > 0 || $rechazados > 0) {
                $nuevoEstado = 'en_proceso';
                \Log::info("Cambiando a EN_PROCESO - productos evaluados: " . ($aprobados + $rechazados));
            }
        }

        // Si todos los artículos tienen un estado diferente a pendiente
        if ($pendientes === 0 && $totalDetalles > 0) {
            \Log::info("Todos los productos evaluados - Pendientes: 0");

            // PRIMERO va a COMPLETADA cuando todos tienen estado
            $nuevoEstado = 'completada';
            \Log::info("Cambiando a COMPLETADA - todos los productos evaluados");

            // DESPUÉS de completada, el sistema determina el estado final automáticamente
            // Si hay al menos un artículo aprobado, el estado final debe ser "aprobada"
            if ($aprobados > 0) {
                $nuevoEstado = 'aprobada';
                \Log::info("Cambiando a APROBADA - hay " . $aprobados . " productos aprobados");
            }
            // Si todos están rechazados, el estado final debe ser "rechazada"
            elseif ($rechazados === $totalDetalles) {
                $nuevoEstado = 'rechazada';
                \Log::info("Cambiando a RECHAZADA - todos los productos rechazados");
            }
        }

        \Log::info("Nuevo estado calculado: " . $nuevoEstado);

        // Actualizar estado de la solicitud si cambió
        if ($nuevoEstado !== $solicitud->estado) {
            $estadoAnteriorSolicitud = $solicitud->estado;

            \Log::info("ACTUALIZANDO ESTADO de solicitud: " . $estadoAnteriorSolicitud . " -> " . $nuevoEstado);

            $solicitud->update([
                'estado' => $nuevoEstado,
                'fecha_aprobacion' => $nuevoEstado === 'aprobada' ? now() : null,
                'aprobado_por' => $nuevoEstado === 'aprobada' ? auth()->id() : null,
                'updated_at' => now()
            ]);

            // Registrar cambio de estado de la solicitud en el historial
            SolicitudAlmacenHistorial::create([
                'idSolicitudAlmacen' => $solicitud->idSolicitudAlmacen,
                'estado_anterior' => $estadoAnteriorSolicitud,
                'estado_nuevo' => $nuevoEstado,
                'observaciones' => 'Cambio automático por sistema. Productos: ' . $aprobados . ' aprobados, ' . $rechazados . ' rechazados, ' . $pendientes . ' pendientes',
                'usuario_id' => auth()->id(),
                'tipo_cambio' => 'solicitud'
            ]);

            \Log::info("Estado de solicitud actualizado exitosamente");
        } else {
            \Log::info("No hay cambios en el estado de la solicitud");
        }
    }


    // Método para cambiar estado final de la solicitud (cuando está en completada)
    public function changeFinalStatus(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $solicitud = SolicitudAlmacen::with('detalles')->findOrFail($id);

            // Solo permitir cambiar estado si está en "completada"
            if ($solicitud->estado !== 'completada') {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo se puede cambiar el estado final en solicitudes completadas'
                ], 400);
            }

            $detalles = $solicitud->detalles;
            $aprobados = $detalles->where('estado', 'aprobado')->count();
            $rechazados = $detalles->where('estado', 'rechazado')->count();

            // Determinar el estado final permitido según la lógica BRO
            $estadoPermitido = 'aprobada'; // Por defecto siempre aprobada

            // Solo si TODOS los artículos están rechazados, puede ser rechazada
            if ($rechazados === $detalles->count() && $aprobados === 0) {
                $estadoPermitido = 'rechazada';
            }

            // Validar que el estado solicitado sea el permitido
            if ($request->estado !== $estadoPermitido) {
                return response()->json([
                    'success' => false,
                    'message' => 'Estado no permitido. Según los productos, el estado debe ser: ' . $estadoPermitido
                ], 400);
            }

            $estadoAnterior = $solicitud->estado;
            $solicitud->update([
                'estado' => $request->estado,
                'fecha_aprobacion' => $request->estado === 'aprobada' ? now() : null,
                'aprobado_por' => $request->estado === 'aprobada' ? auth()->id() : null,
                'motivo_rechazo' => $request->estado === 'rechazada' ? $request->motivo_rechazo : null
            ]);

            // Registrar en el historial
            SolicitudAlmacenHistorial::create([
                'idSolicitudAlmacen' => $solicitud->idSolicitudAlmacen,
                'estado_anterior' => $estadoAnterior,
                'estado_nuevo' => $request->estado,
                'observaciones' => $request->observaciones ?? 'Estado final definido por usuario',
                'usuario_id' => auth()->id(),
                'tipo_cambio' => 'final'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Estado final actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado final: ' . $e->getMessage()
            ], 500);
        }
    }
}
