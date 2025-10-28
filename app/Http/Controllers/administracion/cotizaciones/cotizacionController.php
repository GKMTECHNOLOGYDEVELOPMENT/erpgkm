<?php

namespace App\Http\Controllers\administracion\cotizaciones;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\CotizacionProducto;
use App\Models\Credito;
use App\Models\Equipo;
use App\Models\Moneda;
use App\Models\Ticket;
use App\Models\Visita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class cotizacionController extends Controller
{
    public function index () {


        return view('cotizaciones.index');
    }

    public function create () {
        return view('cotizaciones.create');
    }


 public function search(Request $request)
    {
        $search = $request->get('search');
        
        $clientes = Cliente::where('estado', 1) // Solo clientes activos
            ->where(function($query) use ($search) {
                $query->where('nombre', 'LIKE', "%{$search}%")
                      ->orWhere('documento', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%");
            })
            ->orderBy('nombre')
            ->paginate(10);

        return response()->json([
            'data' => $clientes->items(),
            'pagination' => [
                'more' => $clientes->hasMorePages()
            ]
        ]);
    }

     public function getConfiguracion()
    {
        try {
            $monedas = Moneda::all();
            $terminosPago = Credito::all();

            return response()->json([
                'success' => true,
                'monedas' => $monedas,
                'terminosPago' => $terminosPago
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar configuración'
            ], 500);
        }
    }
// 🔥 MÉTODO CORREGIDO: Obtener todos los tickets disponibles SOLO con idTipotickets = 2
public function getTicketsDisponibles()
{
    try {
        $tickets = Ticket::where('idTipotickets', 2) // SOLO tickets con idTipotickets = 2
            ->with(['tienda']) // Cargar relación con tienda
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'tickets' => $tickets
        ]);
    } catch (\Exception $e) {
        Log::error("Error al cargar tickets disponibles: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al cargar tickets disponibles'
        ], 500);
    }
}

// 🔥 NUEVO MÉTODO: Obtener detalle completo de un ticket
public function getTicketDetalle($ticketId)
{
    try {
        $ticket = Ticket::where('idTickets', $ticketId)
            ->with(['tienda', 'cliente']) // Cargar relaciones
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'ticket' => $ticket
        ]);
    } catch (\Exception $e) {
        Log::error("Error al cargar detalle del ticket: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al cargar detalle del ticket'
        ], 500);
    }
}



   public function getByCliente($clienteId)
{
    try {
        Log::info("Buscando tickets para cliente: " . $clienteId);
        
        $tickets = Ticket::where('idCliente', $clienteId)
            ->where('idTipotickets', 2)
            ->where('tipoServicio', 6)
            ->with(['tienda', 'visitas'])
            ->get();

        Log::info("Tickets encontrados: " . $tickets->count());

        return response()->json([
            'success' => true,
            'tickets' => $tickets
        ]);
    } catch (\Exception $e) {
        Log::error("Error al cargar tickets: " . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error al cargar tickets'
        ], 500);
    }
}

public function getVisitas($ticketId)
{
    try {
        Log::info('Iniciando consulta de visitas', ['ticketId' => $ticketId]);

        $visitas = Visita::where('idTickets', $ticketId)
            ->with('tecnico')
            ->get();

        Log::info('Consulta de visitas completada', [
            'ticketId' => $ticketId,
            'total_visitas' => $visitas->count()
        ]);

        // 🔥 LIMPIAR LOS DATOS Y USAR LOS NOMBRES CORRECTOS DE CAMPOS
        $visitasLimpias = $visitas->map(function ($visita) {
            return [
                'idVisitas' => $visita->idVisitas,
                'Nombre' => $this->limpiarString($visita->Nombre), // 🔥 CON MAYÚSCULA
                'fecha_programada' => $visita->fecha_programada,
                'fecha_asignada' => $visita->fecha_asignada,
                'fechas_desplazamiento' => $visita->fechas_desplazamiento,
                'fecha_llegada' => $visita->fecha_llegada,
                'fecha_inicio' => $visita->fecha_inicio,
                'fecha_final' => $visita->fecha_final,
                'estado' => $visita->estado,
                'idTickets' => $visita->idTickets,
                'idUsuario' => $visita->idUsuario,
                'tecnico' => $visita->tecnico ? [
                    'idUsuario' => $visita->tecnico->idUsuario,
                    'Nombre' => $this->limpiarString($visita->tecnico->Nombre), // 🔥 CON MAYÚSCULA
                    // Agrega otros campos del técnico si los necesitas
                ] : null
            ];
        });

        return response()->json([
            'success' => true,
            'visitas' => $visitasLimpias
        ]);
    } catch (\Exception $e) {
        Log::error('Error al cargar visitas', [
            'ticketId' => $ticketId,
            'error_message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error al cargar visitas'
        ], 500);
    }
}

// 🔥 MÉTODO PARA LIMPIAR STRINGS
private function limpiarString($string)
{
    if (is_null($string)) {
        return '';
    }
    
    // Limpiar caracteres UTF-8 problemáticos
    $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
    $string = iconv('UTF-8', 'UTF-8//IGNORE', $string);
    
    return $string;
}
    public function getEquipo($ticketId, $visitaId = null)
    {
        try {
            $query = Equipo::where('idTickets', $ticketId);
            
            if ($visitaId) {
                $query->where('idVisitas', $visitaId);
            }

            $equipo = $query->first();

            return response()->json([
                'success' => true,
                'equipo' => $equipo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar equipo'
            ], 500);
        }
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            Log::info('Iniciando guardado de cotización', $request->all());

            // Validar datos requeridos
            $validator = Validator::make($request->all(), [
                'numero_cotizacion' => 'required|string|max:255|unique:cotizaciones,numero_cotizacion',
                'fecha_emision' => 'required|date',
                'valida_hasta' => 'required|date',
                'idCliente' => 'required|exists:cliente,idCliente',
                'idMonedas' => 'required|exists:monedas,idMonedas',
                'subtotal' => 'required|numeric|min:0',
                'igv' => 'required|numeric|min:0',
                'total' => 'required|numeric|min:0',
                'items' => 'required|array|min:1',
                'items.*.articulo_id' => 'required|exists:articulos,idArticulos',
                'items.*.cantidad' => 'required|integer|min:1',
                'items.*.precio_unitario' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validar NGR si viene con ticket
            if ($request->idTickets) {
                $ticket = Ticket::find($request->idTickets);
                if (!$ticket) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El ticket seleccionado no existe'
                    ], 422);
                }
            }

            // Validar visita si se proporciona
            if ($request->visita_id) {
                $visita = Visita::find($request->visita_id);
                if (!$visita) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La visita seleccionada no existe'
                    ], 422);
                }
            }

            // Crear la cotización
            $cotizacion = Cotizacion::create([
                'numero_cotizacion' => $request->numero_cotizacion,
                'fecha_emision' => $request->fecha_emision,
                'valida_hasta' => $request->valida_hasta,
                'subtotal' => $request->subtotal,
                'igv' => $request->igv,
                'total' => $request->total,
                'incluir_igv' => $request->incluir_igv ?? true,
                'terminos_condiciones' => $request->terminos_condiciones,
                'dias_validez' => $request->dias_validez ?? 30,
                'terminos_pago' => $request->terminos_pago,
                'estado_cotizacion' => 'pendiente',
                
                // Campos NGR
                'ot' => $request->ot,
                'serie' => $request->serie,
                'visita_id' => $request->visita_id,
                
                // Relaciones
                'idCliente' => $request->idCliente,
                'idMonedas' => $request->idMonedas,
                'idTickets' => $request->idTickets,
                'idTienda' => $request->idTienda,
            ]);

            // Guardar los productos de la cotización
            foreach ($request->items as $item) {
                CotizacionProducto::create([
                    'cotizacion_id' => $cotizacion->idCotizaciones,
                    'articulo_id' => $item['articulo_id'],
                    'descripcion' => $item['descripcion'],
                    'codigo_repuesto' => $item['codigo_repuesto'] ?? null,
                    'precio_unitario' => $item['precio_unitario'],
                    'cantidad' => $item['cantidad'],
                    'subtotal' => $item['subtotal'],
                ]);

                Log::info('Producto guardado para cotización', [
                    'cotizacion_id' => $cotizacion->idCotizaciones,
                    'articulo_id' => $item['articulo_id'],
                    'descripcion' => $item['descripcion']
                ]);
            }

            DB::commit();

            Log::info('Cotización guardada exitosamente', [
                'cotizacion_id' => $cotizacion->idCotizaciones,
                'numero_cotizacion' => $cotizacion->numero_cotizacion,
                'total_items' => count($request->items)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cotización guardada correctamente',
                'cotizacion_id' => $cotizacion->idCotizaciones,
                'numero_cotizacion' => $cotizacion->numero_cotizacion,
                'total' => $cotizacion->total
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error al guardar cotización: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la cotización: ' . $e->getMessage()
            ], 500);
        }
    }

}
