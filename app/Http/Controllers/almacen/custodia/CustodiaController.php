<?php

namespace App\Http\Controllers\almacen\custodia;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Models\Cliente;
use App\Models\Custodia;
use App\Models\CustodiaFoto;
use App\Models\CustodiaUbicacion;
use App\Models\HarvestRetiro;
use App\Models\Marca;
use App\Models\Modelo;
use Illuminate\Support\Facades\DB;
use App\Models\Ticket;
use App\Models\Ubicacion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class CustodiaController extends Controller
{

    public function index(Request $request)
    {
        $query = Custodia::with([
            'ticket',
            'ticket.cliente',
            'ticket.cliente.tipoDocumento',
            'ticket.marca',
            'ticket.modelo'
        ])
            ->where('estado', '!=', 'Rechazado');

        // Filtro de búsqueda
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('codigocustodias', 'like', "%{$search}%")
                    ->orWhereHas('ticket', function ($q2) use ($search) {
                        $q2->where('serie', 'like', "%{$search}%")
                            ->orWhere('numero_ticket', 'like', "%{$search}%")
                            ->orWhereHas('cliente', function ($q3) use ($search) {
                                $q3->where('nombre', 'like', "%{$search}%")
                                    ->orWhere('documento', 'like', "%{$search}%");
                            })
                            ->orWhereHas('marca', function ($q3) use ($search) {
                                $q3->where('nombre', 'like', "%{$search}%");
                            })
                            ->orWhereHas('modelo', function ($q3) use ($search) {
                                $q3->where('nombre', 'like', "%{$search}%");
                            });
                    });
            });
        }

        // Filtro por estado
        if ($request->has('estado') && $request->estado != 'Todos los estados') {
            $query->where('estado', $request->estado);
        }

        $custodias = $query->orderBy('created_at', 'desc')->paginate(9);

        // Estadísticas (siempre sin incluir rechazados)
        $totalSolicitudes = Custodia::where('estado', '!=', 'Rechazado')->count();
        $pendientes = Custodia::where('estado', 'Pendiente')->count();
        $enCustodia = Custodia::where('estado', 'Aprobado')->count();
        $devueltos = Custodia::where('estado', 'Devuelto')->count();

        // Mantener los filtros en la paginación
        if ($request->has('search')) {
            $custodias->appends(['search' => $request->search]);
        }
        if ($request->has('estado')) {
            $custodias->appends(['estado' => $request->estado]);
        }

        return view('solicitud.solicitudcustodia.index', compact(
            'custodias',
            'totalSolicitudes',
            'pendientes',
            'enCustodia',
            'devueltos'
        ));
    }


public function harvest($id)
{
    $custodia = Custodia::with([
        'ticket',
        'ticket.cliente',
        'ticket.marca',
        'ticket.modelo'
    ])->findOrFail($id);

    // Obtener repuestos compatibles con modelos y subcategoria
    $repuestos = Articulo::where('idTipoArticulo', 2) // Tipo repuesto
        ->where('estado', 1) // Activos
        ->with(['modelos', 'subcategoria']) // Cargar modelos (relación muchos a muchos)
        ->select('idArticulos', 'codigo_repuesto', 'idsubcategoria')
        ->get();

    // Obtener retiros existentes
    $retiros = HarvestRetiro::where('id_custodia', $id)
        ->where('estado', 'Activo')
        ->with(['responsable', 'articulo.modelos', 'articulo.subcategoria'])
        ->get();

    return view('solicitud.solicitudcustodia.harvest', compact(
        'custodia', 
        'repuestos',
        'retiros'
    ));
}


public function retirarRepuesto(Request $request, $id)
{
    $request->validate([
        'codigo_repuesto' => 'required|string|max:255',
        'cantidad' => 'required|integer|min:1',
        'observaciones' => 'nullable|string|max:500'
    ]);

    try {
        DB::beginTransaction();

        $custodia = Custodia::findOrFail($id);

        // Verificar que el código de repuesto existe y obtener el id_articulo
        $articulo = Articulo::where('codigo_repuesto', $request->codigo_repuesto)
            ->where('idTipoArticulo', 2)
            ->first();

        if (!$articulo) {
            return response()->json([
                'success' => false,
                'message' => 'El código de repuesto no existe o no es un repuesto válido'
            ], 400);
        }

        // Crear registro de retiro (incluyendo id_articulo)
        $retiro = HarvestRetiro::create([
            'id_custodia' => $id,
            'id_articulo' => $articulo->idArticulos, // ✅ Agregamos el id_articulo
            'codigo_repuesto' => $request->codigo_repuesto,
            'cantidad_retirada' => $request->cantidad,
            'observaciones' => $request->observaciones,
            'id_responsable' => auth()->id(),
            'estado' => 'Activo'
        ]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Repuesto retirado correctamente',
            'retiro' => $retiro
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error al retirar repuesto: ' . $e->getMessage()
        ], 500);
    }
}


public function anularRetiro($idRetiro)
{
    try {
        $retiro = HarvestRetiro::where('id', $idRetiro)
            ->where('estado', 'Activo')
            ->firstOrFail();

        // Solo cambiar el estado (no restaurar stock)
        $retiro->estado = 'Anulado';
        $retiro->save();

        return response()->json([
            'success' => true,
            'message' => 'Retiro anulado correctamente'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al anular retiro: ' . $e->getMessage()
        ], 500);
    }
}
public function getRepuestosCompatibles($idModelo)
{
    $repuestos = Articulo::where('idTipoArticulo', 2)
        ->where(function($query) use ($idModelo) {
            $query->where('idModelo', $idModelo)
                  ->orWhereNull('idModelo');
        })
        ->where('estado', 1)
        ->select('idArticulos as id', 'nombre', 'codigo_repuesto', 'stock_total')
        ->get();

    return response()->json($repuestos);
}



    public function actualizarCustodia(Request $request, $id)
    {
        $request->validate([
            'es_custodia' => 'required|boolean',
            'ubicacion_actual' => 'required_if:es_custodia,1|string|max:100',
            'erma' => 'nullable|string|max:50',
        ]);

        $ticket = Ticket::find($id);
        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Ticket no encontrado'], 404);
        }

        $nuevoEstado = (int) $request->input('es_custodia');
        $custodia = Custodia::where('id_ticket', $ticket->idTickets)->first();
        $erma = trim($request->input('erma'));

        // 🚨 Si no hay ERMA, automáticamente desactivar custodia
        if ($nuevoEstado === 1 && empty($erma)) {
            if ($custodia) {
                $custodia->estado = 'Rechazado';
                $custodia->save();
            }

            $ticket->es_custodia = 0;
            $ticket->save();

            return response()->json([
                'success' => false,
                'message' => '❌ No se puede activar custodia sin N. erma. Custodia desactivada automáticamente.',
                'es_custodia' => 0
            ], 400);
        }

        // ✅ PONER EN CUSTODIA
        if ($nuevoEstado === 1) {
            $ubicacion = trim($request->input('ubicacion_actual'));
            $fechaIngreso = $request->input('fecha_ingreso_custodia');

                    if (!$custodia) {
            $custodia = Custodia::create([
                'codigocustodias'        => strtoupper(Str::random(10)),
                'id_ticket'              => $ticket->idTickets,
                'idcliente'              => $ticket->idCliente,
                'numero_ticket'          => $ticket->numero_ticket,
                'idMarca'                => $ticket->idMarca,
                'idModelo'               => $ticket->idModelo,
                'serie'                  => $ticket->serie,
                'estado'                 => 'Pendiente',
                'fecha_ingreso_custodia' => $fechaIngreso ?? now()->toDateString(),
                'ubicacion_actual'       => $ubicacion,
                'responsable_entrega'    => null,
                'id_responsable_recepcion'  => auth()->id(),
                'observaciones'          => null,
                'fecha_devolucion'       => null,
            ]);
        } else {
                $custodia->ubicacion_actual = $ubicacion;
                $custodia->fecha_ingreso_custodia = $fechaIngreso ?? $custodia->fecha_ingreso_custodia;

                if ($custodia->estado === 'Rechazado') {
                    $custodia->estado = 'Pendiente';
                }

                $custodia->save();
            }

            $ticket->es_custodia = 1;
            $ticket->erma = $erma; // ✅ Guardar erma si existe
            $ticket->save();

            return response()->json([
                'success' => true,
                'message' => '✅ Equipo puesto en custodia correctamente.',
                'es_custodia' => 1,
                'ubicacion_actual' => $custodia->ubicacion_actual,
                'fecha_ingreso_custodia' => $custodia->fecha_ingreso_custodia,
                'erma' => $ticket->erma
            ]);
        }

        // ✅ QUITAR DE CUSTODIA
        if ($nuevoEstado === 0) {
            if ($custodia) {
                if ($custodia->estado === 'Pendiente') {
                    $custodia->estado = 'Rechazado';
                    $custodia->save();
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede desmarcar la custodia porque está en estado: ' . $custodia->estado,
                    ], 403);
                }
            }

            $ticket->es_custodia = 0;
            $ticket->save();

            return response()->json([
                'success' => true,
                'message' => '✅ Custodia desactivada correctamente.',
                'es_custodia' => 0
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Acción no válida.'], 400);
    }





    public function verificarCustodia($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['success' => false, 'message' => 'Ticket no encontrado'], 404);
        }

        return response()->json(['success' => true, 'es_custodia' => $ticket->es_custodia]);
    }

    public function opciones($id)
    {
        $custodia = Custodia::with([
            'ticket',
            'ticket.cliente',
            'ticket.cliente.tipoDocumento',
            'ticket.marca',
            'ticket.modelo',
            'responsableRecepcion'
        ])->findOrFail($id);

        $ubicaciones = Ubicacion::with('sucursal')->get();


        return view('solicitud.solicitudcustodia.opciones', compact('custodia', 'ubicaciones'));
    }

 public function update(Request $request, $id)
{
    $custodia = Custodia::findOrFail($id);

    // Validar los datos - CAMBIAR idubicacion por rack_ubicacion_id
    $validated = $request->validate([
        'estado' => 'required|in:Pendiente,En revisión,Aprobado,Rechazado,Devuelto',
        'ubicacion_actual' => 'required_if:estado,Aprobado|max:100',
        'observaciones' => 'nullable|string',
        'rack_ubicacion_id' => 'required_if:estado,Aprobado|exists:rack_ubicaciones,idRackUbicacion', // ✅ CAMBIADO
        'observacion_almacen' => 'nullable|string',
        'cantidad' => 'sometimes|integer|min:1'
    ]);

   DB::beginTransaction();

    try {
        $estadoAnterior = $custodia->estado;
        $custodia->update($validated);
        $custodia->observacion_almacen = $validated['observacion_almacen'] ?? null; // ✅ GUARDAR EN 

        if ($request->estado === 'Aprobado') {
            $this->guardarCustodiaEnRack($custodia, $request);
        } else if ($estadoAnterior === 'Aprobado' && $request->estado !== 'Aprobado') {
            $this->eliminarCustodiaDeRack($custodia);
            CustodiaUbicacion::where('idCustodia', $custodia->id)->delete();
        }

        DB::commit();

        // ✅ OBTENER UBICACIÓN ACTUAL DESPUÉS DE GUARDAR
        $ubicacionActual = $this->obtenerUbicacionActualData($custodia->id);

        return response()->json([
            'success' => true,
            'message' => 'Custodia actualizada correctamente',
            'estado_actualizado' => $custodia->estado,
            'ubicacion_actual' => $ubicacionActual // ✅ Agregar ubicación actual
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al actualizar custodia: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar la custodia: ' . $e->getMessage()
        ], 500);
    }
}

// ✅ Método auxiliar para obtener datos de ubicación
private function obtenerUbicacionActualData($custodiaId)
{
    return DB::table('rack_ubicacion_articulos as rua')
        ->join('rack_ubicaciones as ru', 'rua.rack_ubicacion_id', '=', 'ru.idRackUbicacion')
        ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
        ->where('rua.custodia_id', $custodiaId)
        ->select(
            'ru.idRackUbicacion',
            'ru.codigo',
            'rua.cantidad',
            'r.nombre as rack_nombre',
            'r.sede'
        )
        ->first();
}

/**
 * Guardar custodia en rack_ubicacion_articulos - CORREGIDO
 */
private function guardarCustodiaEnRack($custodia, $request)
{
    try {
        $rackUbicacionId = $request->rack_ubicacion_id; // ✅ Usar el nuevo campo
        
        // Verificar que la ubicación del rack existe
        $rackUbicacion = DB::table('rack_ubicaciones')
            ->where('idRackUbicacion', $rackUbicacionId)
            ->first();

        if (!$rackUbicacion) {
            throw new Exception('Ubicación de rack no encontrada');
        }

        // Verificar capacidad disponible
        $cantidadActualEnUbicacion = DB::table('rack_ubicacion_articulos')
            ->where('rack_ubicacion_id', $rackUbicacionId)
            ->sum('cantidad');

        $capacidadDisponible = $rackUbicacion->capacidad_maxima - $cantidadActualEnUbicacion;
        $cantidadRequerida = $request->cantidad ?? 1;

        if ($cantidadRequerida > $capacidadDisponible) {
            throw new Exception("La ubicación {$rackUbicacion->codigo} no tiene suficiente capacidad. Disponible: {$capacidadDisponible}, Requerido: {$cantidadRequerida}");
        }

        // Verificar si ya existe esta custodia en algún rack
        $custodiaExistente = DB::table('rack_ubicacion_articulos')
            ->where('custodia_id', $custodia->id)
            ->first();

        if ($custodiaExistente) {
            // Actualizar ubicación existente
            DB::table('rack_ubicacion_articulos')
                ->where('custodia_id', $custodia->id)
                ->update([
                    'rack_ubicacion_id' => $rackUbicacionId,
                    'cantidad' => $cantidadRequerida,
                    'updated_at' => now()
                ]);
                
            Log::info("Custodia {$custodia->id} actualizada en rack {$rackUbicacionId}");
        } else {
            // Crear nuevo registro en rack_ubicacion_articulos
            DB::table('rack_ubicacion_articulos')->insert([
                'rack_ubicacion_id' => $rackUbicacionId,
                'articulo_id' => null, // No es un artículo del inventario
                'custodia_id' => $custodia->id, // Nuevo campo
                'cantidad' => $cantidadRequerida,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            Log::info("Custodia {$custodia->id} creada en rack {$rackUbicacionId}");
        }

        // Actualizar estado de ocupación del rack
        $this->actualizarEstadoRack($rackUbicacionId);

        // ✅ ELIMINAR registro antiguo de custodia_ubicacion si existe
        CustodiaUbicacion::where('idCustodia', $custodia->id)->delete();

    } catch (\Exception $e) {
        Log::error('Error al guardar custodia en rack: ' . $e->getMessage());
        throw $e;
    }
}


/**
 * Obtener ubicación actual de la custodia en racks
 */
/**
 * Obtener ubicación actual de la custodia en racks
 */
public function obtenerUbicacionActual($custodiaId)
{
    try {
        $custodia = Custodia::findOrFail($custodiaId);
        
        $ubicacionActual = DB::table('rack_ubicacion_articulos as rua')
            ->join('rack_ubicaciones as ru', 'rua.rack_ubicacion_id', '=', 'ru.idRackUbicacion')
            ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
            ->where('rua.custodia_id', $custodiaId)
            ->select(
                'ru.idRackUbicacion',
                'ru.codigo',
                'rua.cantidad',
                'r.nombre as rack_nombre',
                'r.sede'
            )
            ->first();

        return response()->json([
            'success' => true,
            'ubicacion_actual' => $ubicacionActual
        ]);

    } catch (\Exception $e) {
        Log::error('Error al obtener ubicación actual: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener ubicación actual'
        ], 500);
    }
}

/**
 * Buscar rack disponible para custodia
 */
private function buscarRackDisponibleParaCustodia($ubicacion)
{
    // Buscar racks en la misma sede/sucursal que tengan espacio
    return DB::table('rack_ubicaciones as ru')
        ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
        ->leftJoin('rack_ubicacion_articulos as rua', 'ru.idRackUbicacion', '=', 'rua.rack_ubicacion_id')
        ->where('r.estado', 'activo')
        ->where('r.sede', 'LIKE', '%' . $ubicacion->sucursal->nombre . '%') // Ajustar según tu lógica de sedes
        ->select(
            'ru.idRackUbicacion',
            'ru.codigo',
            'ru.capacidad_maxima',
            'r.nombre as rack_nombre',
            'r.sede',
            DB::raw('COALESCE(SUM(rua.cantidad), 0) as cantidad_ocupada')
        )
        ->groupBy('ru.idRackUbicacion', 'ru.codigo', 'ru.capacidad_maxima', 'r.nombre', 'r.sede')
        ->havingRaw('ru.capacidad_maxima - COALESCE(SUM(rua.cantidad), 0) >= 1') // Mínimo 1 unidad de espacio
        ->orderBy('cantidad_ocupada', 'asc') // Priorizar racks con menos ocupación
        ->first();
}

/**
 * Eliminar custodia del sistema de racks
 */
private function eliminarCustodiaDeRack($custodia)
{
    try {
        // Eliminar registro de rack_ubicacion_articulos
        $eliminados = DB::table('rack_ubicacion_articulos')
            ->where('custodia_id', $custodia->id)
            ->delete();

        if ($eliminados > 0) {
            Log::info("Custodia {$custodia->id} eliminada del sistema de racks");
        }

    } catch (\Exception $e) {
        Log::error('Error al eliminar custodia de rack: ' . $e->getMessage());
        throw $e;
    }
}

/**
 * Actualizar estado de ocupación del rack
 */
private function actualizarEstadoRack($rackUbicacionId)
{
    try {
        // Calcular ocupación actual
        $ocupacion = DB::table('rack_ubicacion_articulos')
            ->where('rack_ubicacion_id', $rackUbicacionId)
            ->sum('cantidad');

        $rackUbicacion = DB::table('rack_ubicaciones')
            ->where('idRackUbicacion', $rackUbicacionId)
            ->first();

        if ($rackUbicacion) {
            $estado = $this->calcularEstadoOcupacion($ocupacion, $rackUbicacion->capacidad_maxima);
            
            DB::table('rack_ubicaciones')
                ->where('idRackUbicacion', $rackUbicacionId)
                ->update([
                    'estado_ocupacion' => $estado,
                    'updated_at' => now()
                ]);
        }
    } catch (\Exception $e) {
        Log::error('Error al actualizar estado del rack: ' . $e->getMessage());
    }
}

/**
 * Calcular estado de ocupación (mismo método que en UbicacionesVistaController)
 */
private function calcularEstadoOcupacion($cantidad, $capacidadMaxima)
{
    if ($capacidadMaxima <= 0) return 'vacio';
    
    $porcentaje = ($cantidad / $capacidadMaxima) * 100;
    
    if ($porcentaje == 0) return 'vacio';
    if ($porcentaje <= 24) return 'bajo';
    if ($porcentaje <= 49) return 'medio';
    if ($porcentaje <= 74) return 'alto';
    return 'muy_alto';
}


/**
 * Obtener sugerencias de ubicación para custodia
 */
public function sugerirUbicacionesCustodia($custodiaId)
{
    try {
        $custodia = Custodia::findOrFail($custodiaId);
        
        // Usar el mismo sistema de sugerencias que para artículos
        $sugerencias = $this->obtenerSugerenciasUbicaciones(1); // Cantidad siempre 1 para custodias

        return response()->json([
            'success' => true,
            'sugerencias' => $sugerencias,
            'mensaje' => 'Sugerencias de ubicación para custodia'
        ]);

    } catch (\Exception $e) {
        Log::error('Error al obtener sugerencias para custodia: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener sugerencias de ubicación'
        ], 500);
    }
}

/**
 * Método reutilizable para obtener sugerencias (compartido con solicitud ingreso)
 */
private function obtenerSugerenciasUbicaciones($cantidad)
{
    // Aquí puedes reutilizar la lógica del método sugerirUbicacionesMejorado
    
    return DB::table('rack_ubicaciones as ru')
        ->join('racks as r', 'ru.rack_id', '=', 'r.idRack')
        ->leftJoin('rack_ubicacion_articulos as rua', 'ru.idRackUbicacion', '=', 'rua.rack_ubicacion_id')
        ->where('r.estado', 'activo')
        ->select(
            'ru.idRackUbicacion as id',
            'ru.codigo',
            'ru.capacidad_maxima',
            'r.nombre as rack_nombre',
            'r.sede',
            DB::raw('COALESCE(SUM(rua.cantidad), 0) as cantidad_ocupada')
        )
        ->groupBy('ru.idRackUbicacion', 'ru.codigo', 'ru.capacidad_maxima', 'r.nombre', 'r.sede')
        ->havingRaw('ru.capacidad_maxima - COALESCE(SUM(rua.cantidad), 0) >= ?', [$cantidad])
        ->orderBy('cantidad_ocupada', 'asc')
        ->get()
        ->map(function($ubicacion) use ($cantidad) {
            $espacioDisponible = $ubicacion->capacidad_maxima - $ubicacion->cantidad_ocupada;
            return [
                'id' => $ubicacion->id,
                'codigo' => $ubicacion->codigo,
                'rack_nombre' => $ubicacion->rack_nombre,
                'sede' => $ubicacion->sede,
                'cantidad_actual' => $ubicacion->cantidad_ocupada,
                'capacidad_maxima' => $ubicacion->capacidad_maxima,
                'espacio_disponible' => $espacioDisponible,
                'tipo' => 'rack',
                'prioridad' => 1
            ];
        });
}


   public function create()
    {
        // Ya no necesitamos pasar los datos aquí, se cargarán via AJAX
        return view('solicitud.solicitudcustodia.create');
    }

    public function store(Request $request)
{
    // Validación
    $validated = $request->validate([
        'idcliente' => 'required|exists:cliente,idCliente',
        'numero_ticket' => 'nullable|string|max:100',
        'idMarca' => 'nullable|exists:marca,idMarca',
        'idModelo' => 'nullable|exists:modelo,idModelo',
        'serie' => 'nullable|string|max:100',
        'fecha_ingreso_custodia' => 'required|date',
        'ubicacion_actual' => 'required|string|max:100',
        'estado' => 'required|in:Pendiente,En revisión,Aprobado',
        'observaciones' => 'nullable|string',
    ]);

    try {
        // Iniciar transacción
        DB::beginTransaction();

        // Generar código de custodia
        $codigoCustodia = 'CUST-' . date('YmdHis') . '-' . rand(100, 999);

        // Obtener información del cliente
        $cliente = DB::table('cliente')
            ->where('idCliente', $validated['idcliente'])
            ->first();
        
        // Obtener información de marca si existe
        $marcaNombre = null;
        if ($validated['idMarca']) {
            $marca = DB::table('marca')
                ->where('idMarca', $validated['idMarca'])
                ->first();
            $marcaNombre = $marca ? $marca->nombre : null;
        }
        
        // Obtener información de modelo si existe
        $modeloNombre = null;
        if ($validated['idModelo']) {
            $modelo = DB::table('modelo')
                ->where('idModelo', $validated['idModelo'])
                ->first();
            $modeloNombre = $modelo ? $modelo->nombre : null;
        }

        // Crear la custodia
        $custodia = Custodia::create([
            'codigocustodias' => $codigoCustodia,
            'id_ticket' => null,
            'idcliente' => $validated['idcliente'],
            'numero_ticket' => $validated['numero_ticket'],
            'idMarca' => $validated['idMarca'],
            'idModelo' => $validated['idModelo'],
            'serie' => $validated['serie'],
            'estado' => $validated['estado'],
            'fecha_ingreso_custodia' => $validated['fecha_ingreso_custodia'],
            'ubicacion_actual' => $validated['ubicacion_actual'],
            'id_responsable_recepcion' => auth()->id(),
            'observaciones' => $validated['observaciones'],
        ]);

        // Insertar en la tabla solicitudentrega para notificaciones de custodia
        $comentarioCustodia = "Equipo ingresado a custodia. ";
        $comentarioCustodia .= "Código: {$codigoCustodia}. ";
        $comentarioCustodia .= "Cliente: " . ($cliente ? $cliente->nombre : 'N/A') . ". ";
        
        if ($marcaNombre) {
            $comentarioCustodia .= "Marca: {$marcaNombre}. ";
        }
        
        if ($modeloNombre) {
            $comentarioCustodia .= "Modelo: {$modeloNombre}. ";
        }
        
        if ($validated['serie']) {
            $comentarioCustodia .= "Serie: {$validated['serie']}. ";
        }
        
        $comentarioCustodia .= "Ubicación: {$validated['ubicacion_actual']}. ";
        $comentarioCustodia .= $validated['observaciones'] ? "Observaciones: " . $validated['observaciones'] : "";

        DB::table('solicitudentrega')->insert([
            'idTickets' => null, // No hay ticket en custodia
            'numero_ticket' => $validated['numero_ticket'], // Número de ticket si existe
            'idVisitas' => null, // No hay visita
            'idUsuario' => auth()->id(), // Usuario autenticado
            'comentario' => trim($comentarioCustodia),
            'estado' => 1, // Estado 1
            'fechaHora' => now(), // Fecha y hora actual del registro
            'idTipoServicio' => 7 // idTipoServicio 7 para custodia (usa un número diferente)
        ]);

        Log::info('Registro en solicitudentrega para custodia creado', [
            'custodia_id' => $custodia->id,
            'codigo_custodia' => $codigoCustodia,
            'usuario_id' => auth()->id(),
            'idTipoServicio' => 7
        ]);

        // Confirmar transacción
        DB::commit();

        // Si es una petición AJAX, retornar JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Custodia creada exitosamente.',
                'data' => $custodia
            ]);
        }

        return redirect()->route('solicitudcustodia.index')
            ->with('success', 'Custodia creada exitosamente.');

    } catch (\Exception $e) {
        // Revertir transacción en caso de error
        DB::rollBack();
        
        // Si es una petición AJAX, retornar error en JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la custodia: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()
            ->with('error', 'Error al crear la custodia: ' . $e->getMessage())
            ->withInput();
    }
}

    

    // Métodos actualizados para soportar búsqueda con Select2
       public function getClientes(Request $request): JsonResponse
    {
        $search = $request->get('search');
        
        $clientes = Cliente::where('estado', 1)
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                      ->orWhere('documento', 'like', "%{$search}%");
                });
            })
            ->orderBy('nombre')
            ->paginate(30);

        // Transformar los datos para Select2
        $transformed = $clientes->getCollection()->map(function($cliente) {
            return [
                'id' => $cliente->idCliente,
                'nombre' => $cliente->nombre,
                'documento' => $cliente->documento,
                'text' => $cliente->nombre . ($cliente->documento ? ' - ' . $cliente->documento : '')
            ];
        });

        return response()->json([
            'results' => $transformed,
            'pagination' => [
                'more' => $clientes->hasMorePages()
            ]
        ]);
    }

        public function getMarcas(Request $request): JsonResponse
    {
        $search = $request->get('search');
        
        $marcas = Marca::where('estado', 1)
            ->when($search, function($query) use ($search) {
                $query->where('nombre', 'like', "%{$search}%");
            })
            ->orderBy('nombre')
            ->paginate(30);

        $transformed = $marcas->getCollection()->map(function($marca) {
            return [
                'id' => $marca->idMarca,
                'nombre' => $marca->nombre,
                'text' => $marca->nombre
            ];
        });

        return response()->json([
            'results' => $transformed,
            'pagination' => [
                'more' => $marcas->hasMorePages()
            ]
        ]);
    }
    public function getModelos(Request $request): JsonResponse
    {
        $search = $request->get('search');
        
        $modelos = Modelo::where('estado', 1)
            ->when($search, function($query) use ($search) {
                $query->where('nombre', 'like', "%{$search}%");
            })
            ->orderBy('nombre')
            ->paginate(30);

        $transformed = $modelos->getCollection()->map(function($modelo) {
            return [
                'id' => $modelo->idModelo,
                'nombre' => $modelo->nombre,
                'text' => $modelo->nombre
            ];
        });

        return response()->json([
            'results' => $transformed,
            'pagination' => [
                'more' => $modelos->hasMorePages()
            ]
        ]);
    }

   public function guardarFotos(Request $request, $idCustodia)
    {
        // Validar que la custodia exista
        $custodia = Custodia::find($idCustodia);
        
        if (!$custodia) {
            return response()->json([
                'success' => false,
                'message' => 'Custodia no encontrada'
            ], 404);
        }

        $request->validate([
            'fotos.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        // Verificar que hay fotos
        if (!$request->hasFile('fotos')) {
            return response()->json([
                'success' => false,
                'message' => 'No se han seleccionado fotos'
            ], 400);
        }

        $fotosSubidas = [];

        try {
            foreach ($request->file('fotos') as $foto) {
                // Validar cada archivo individualmente
                if (!$foto->isValid()) {
                    continue;
                }

                // Generar nombre único con hash
                $nombreOriginal = $foto->getClientOriginalName();
                $extension = $foto->getClientOriginalExtension();
                $tipoMime = $foto->getMimeType();
                $tamaño = $foto->getSize();
                
                // Leer el contenido del archivo
                $contenidoImagen = file_get_contents($foto->getRealPath());
                
                // Generar hash único para el nombre
                $nombreHash = $this->generarNombreHash($contenidoImagen, $custodia->id);
                
                // Generar hash para verificar integridad
                $hashArchivo = hash('sha256', $contenidoImagen);

                // Guardar en base de datos (se encripta automáticamente por el mutator)
                $fotoGuardada = CustodiaFoto::create([
                    'id_custodia' => $custodia->id,
                    'nombre_archivo' => $nombreOriginal,
                    'nombre_hash' => $nombreHash,
                    'tipo_archivo' => $tipoMime,
                    'tamaño_archivo' => $tamaño,
                    'datos_imagen' => $contenidoImagen, // Se encripta automáticamente
                    'hash_archivo' => $hashArchivo,
                    'uploaded_by' => auth()->id()
                ]);

                $fotosSubidas[] = $fotoGuardada;
            }

            return response()->json([
                'success' => true,
                'message' => count($fotosSubidas) . ' foto(s) subida(s) correctamente',
                'fotos_subidas' => count($fotosSubidas)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar las fotos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar nombre hash único para el archivo
     */
    private function generarNombreHash($contenidoImagen, $idCustodia)
    {
        $timestamp = microtime(true);
        $stringUnico = $contenidoImagen . $timestamp . $idCustodia . Str::random(10);
        
        return hash('sha256', $stringUnico);
    }

    /**
     * Obtener fotos de una custodia
     */
    public function obtenerFotos($idCustodia)
    {
        $fotos = CustodiaFoto::where('id_custodia', $idCustodia)
                            ->select([
                                'id',
                                'nombre_archivo',
                                'nombre_hash',
                                'tipo_archivo',
                                'tamaño_archivo',
                                'hash_archivo',
                                'descripcion',
                                'uploaded_by',
                                'created_at'
                            ])
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        return response()->json([
            'fotos' => $fotos
        ]);
    }

    /**
     * Obtener imagen individual (para mostrar en img src)
     */
    public function obtenerImagen($idFoto)
    {
        $foto = CustodiaFoto::find($idFoto);
        
        if (!$foto) {
            abort(404);
        }

        // Verificar permisos (opcional)
        // if (!auth()->check()) {
        //     abort(403);
        // }

        // Obtener la imagen desencriptada a través del accesor
        $imagenDesencriptada = $foto->imagen;
        
        if (!$imagenDesencriptada) {
            abort(404);
        }

        // Devolver como respuesta de imagen
        return response($imagenDesencriptada)
            ->header('Content-Type', $foto->tipo_archivo)
            ->header('Content-Length', strlen($imagenDesencriptada))
            ->header('Cache-Control', 'private, max-age=3600')
            ->header('Content-Disposition', 'inline; filename="' . $foto->nombre_archivo . '"');
    }

    /**
     * Descargar foto
     */
    public function descargarFoto($idFoto)
    {
        $foto = CustodiaFoto::find($idFoto);
        
        if (!$foto) {
            abort(404);
        }

        $imagenDesencriptada = $foto->imagen;
        
        if (!$imagenDesencriptada) {
            abort(404);
        }

        return response($imagenDesencriptada)
            ->header('Content-Type', $foto->tipo_archivo)
            ->header('Content-Length', strlen($imagenDesencriptada))
            ->header('Content-Disposition', 'attachment; filename="' . $foto->nombre_archivo . '"');
    }

    /**
     * Eliminar foto
     */
    public function eliminarFoto($idFoto)
    {
        $foto = CustodiaFoto::find($idFoto);
        
        if (!$foto) {
            return response()->json(['success' => false, 'message' => 'Foto no encontrada'], 404);
        }
        
        // Verificar permisos (opcional)
        if (auth()->id() !== $foto->uploaded_by && !auth()->user()->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'No tienes permisos para eliminar esta foto'], 403);
        }
        
        // Eliminar registro (la imagen se elimina automáticamente de la BD)
        $foto->delete();
        
        return response()->json(['success' => true, 'message' => 'Foto eliminada correctamente']);
    }

    /**
     * Verificar integridad de una foto
     */
    public function verificarIntegridad($idFoto)
    {
        $foto = CustodiaFoto::find($idFoto);
        
        if (!$foto) {
            return response()->json(['success' => false, 'message' => 'Foto no encontrada'], 404);
        }

        $imagenDesencriptada = $foto->imagen;
        $hashActual = hash('sha256', $imagenDesencriptada);
        $integro = ($hashActual === $foto->hash_archivo);

        return response()->json([
            'success' => true,
            'integro' => $integro,
            'hash_original' => $foto->hash_archivo,
            'hash_actual' => $hashActual
        ]);
    }






    






}
