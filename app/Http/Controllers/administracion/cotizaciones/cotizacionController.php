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
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\Validator;

class cotizacionController extends Controller
{
    public function index()
    {
        return view('cotizaciones.index');
    }

    /**
     * Obtener todas las cotizaciones para el index (API)
     */

    public function edit($id)
    {
        try {
            // Buscar la cotización con todas sus relaciones
            $cotizacion = Cotizacion::with([
                'cliente',
                'moneda',
                'ticket',
                'productos.articulo'
            ])->findOrFail($id);

            // Obtener datos adicionales para el formulario
            $clientes = Cliente::where('estado', 1)->get();
            $monedas = Moneda::all();
            $terminosPago = Credito::all();

            return view('cotizaciones.edit', compact(
                'cotizacion',
                'clientes',
                'monedas',
                'terminosPago'
            ));
        } catch (\Exception $e) {
            Log::error('Error al cargar formulario de edición: ' . $e->getMessage());
            return redirect()->route('cotizaciones.index')
                ->with('error', 'Cotización no encontrada');
        }
    }

    /**
     * Actualizar cotización
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            Log::info('Iniciando actualización de cotización', $request->all());

            $cotizacion = Cotizacion::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'numero_cotizacion' => 'required|string|max:255|unique:cotizaciones,numero_cotizacion,' . $id . ',idCotizaciones',
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

            // Actualizar la cotización
            $cotizacion->update([
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
                'estado_cotizacion' => $request->estado_cotizacion ?? 'pendiente',
                'ot' => $request->ot,
                'serie' => $request->serie,
                'visita_id' => $request->visita_id,
                'idCliente' => $request->idCliente,
                'idMonedas' => $request->idMonedas,
                'idTickets' => $request->idTickets,
                'idTienda' => $request->idTienda,
            ]);

            // Eliminar productos existentes
            CotizacionProducto::where('cotizacion_id', $cotizacion->idCotizaciones)->delete();

            // Agregar nuevos productos
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
            }

            DB::commit();

            Log::info('Cotización actualizada exitosamente', [
                'cotizacion_id' => $cotizacion->idCotizaciones,
                'numero_cotizacion' => $cotizacion->numero_cotizacion
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cotización actualizada correctamente',
                'cotizacion_id' => $cotizacion->idCotizaciones
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al actualizar cotización: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la cotización: ' . $e->getMessage()
            ], 500);
        }
    }

    public function detalle($id)
    {
        try {
            $cotizacion = Cotizacion::with([
                'cliente',
                'moneda',
                'ticket.tienda',
                'productos.articulo'
            ])->findOrFail($id);

            return view('cotizaciones.detalle', compact('cotizacion'));
        } catch (\Exception $e) {
            Log::error('Error al cargar detalle de cotización: ' . $e->getMessage());
            return redirect()->route('cotizaciones.index')
                ->with('error', 'Cotización no encontrada');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $cotizacion = Cotizacion::findOrFail($id);

            // Eliminar productos relacionados
            CotizacionProducto::where('cotizacion_id', $id)->delete();

            // Eliminar la cotización
            $cotizacion->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cotización eliminada correctamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al eliminar cotización: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la cotización: ' . $e->getMessage()
            ], 500);
        }
    }


    public function getCotizaciones(Request $request)
    {
        try {
            $query = Cotizacion::with(['cliente', 'moneda'])
                ->select('*')
                ->orderBy('fecha_emision', 'desc');

            // Filtro por estado
            if ($request->has('estado') && $request->estado != '') {
                $query->where('estado_cotizacion', $request->estado);
            }

            // Filtro por mes
            if ($request->has('mes') && $request->mes != '') {
                $query->whereMonth('fecha_emision', $request->mes);
            }

            // Filtro por búsqueda - CORREGIDO: sin columna empresa
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('numero_cotizacion', 'LIKE', "%{$search}%")
                        ->orWhereHas('cliente', function ($q2) use ($search) {
                            $q2->where('nombre', 'LIKE', "%{$search}%")
                                ->orWhere('documento', 'LIKE', "%{$search}%") // Buscar por documento también
                                ->orWhere('email', 'LIKE', "%{$search}%");
                        });
                });
            }

            $cotizaciones = $query->get()->map(function ($cotizacion) {
                return [
                    'id' => $cotizacion->idCotizaciones,
                    'cotizacionNo' => $cotizacion->numero_cotizacion,
                    'cliente' => [
                        'nombre' => $cotizacion->cliente->nombre ?? 'N/A',
                        'documento' => $cotizacion->cliente->documento ?? 'N/A', // Usar documento en lugar de empresa
                        'email' => $cotizacion->cliente->email ?? 'N/A'
                    ],
                    'fechaEmision' => $cotizacion->fecha_emision,
                    'validaHasta' => $cotizacion->valida_hasta,
                    'total' => (float) $cotizacion->total,
                    'moneda' => $cotizacion->moneda->simbolo ?? 'PEN',
                    'incluirIGV' => (bool) $cotizacion->incluir_igv,
                    'estado' => $cotizacion->estado_cotizacion
                ];
            });

            return response()->json([
                'success' => true,
                'cotizaciones' => $cotizaciones
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener cotizaciones: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar las cotizaciones'
            ], 500);
        }
    }

    /**
     * Obtener estadísticas para el dashboard
     */
    public function getEstadisticas()
    {
        try {
            $total = Cotizacion::count();
            $aprobadas = Cotizacion::where('estado_cotizacion', 'aprobada')->count();
            $pendientes = Cotizacion::where('estado_cotizacion', 'pendiente')->count();

            // Cotizaciones vencidas (valida_hasta menor a hoy)
            $vencidas = Cotizacion::where('valida_hasta', '<', now()->format('Y-m-d'))
                ->where('estado_cotizacion', '!=', 'aprobada')
                ->count();

            return response()->json([
                'success' => true,
                'stats' => [
                    'total' => $total,
                    'aprobadas' => $aprobadas,
                    'pendientes' => $pendientes,
                    'vencidas' => $vencidas
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener estadísticas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar estadísticas'
            ], 500);
        }
    }



    public function create(Request $request)
    {
        $ticketId = $request->get('ticket_id');
        $ticketIds = $request->get('ticket_ids');
        $clienteId = $request->get('cliente_id');

        $suministros = collect();
        $ticketsInfo = collect();

        // Si hay un ticket individual
        if ($ticketId) {
            $suministros = $this->obtenerSuministros($ticketId);
            $ticketsInfo = $this->obtenerInfoTickets([$ticketId]);
        }

        // Si hay múltiples tickets
        if ($ticketIds) {
            $ticketArray = explode(',', $ticketIds);
            $suministros = $this->obtenerSuministrosMultiples($ticketArray);
            $ticketsInfo = $this->obtenerInfoTickets($ticketArray);
        }

        return view('cotizaciones.create', compact(
            'suministros',
            'ticketsInfo',
            'ticketId',
            'ticketIds',
            'clienteId'
        ));
    }

    // 🔥 MÉTODO CORREGIDO: Obtener suministros por ticket y visita
    public function getSuministrosPorVisita($ticketId, $visitaId = null)
    {
        try {
            Log::info('Buscando suministros', [
                'ticketId' => $ticketId,
                'visitaId' => $visitaId
            ]);

            $query = DB::table('suministros')
                ->join('articulos', 'suministros.idArticulos', '=', 'articulos.idArticulos')
                ->where('suministros.idTickets', $ticketId);

            // Si se proporciona una visita, filtrar por visita
            if ($visitaId && $visitaId !== 'null' && $visitaId !== '') {
                $query->where('suministros.idVisitas', $visitaId);
            }

            $suministros = $query->select(
                'articulos.idArticulos',
                'articulos.nombre', // 🔥 CAMBIADO: descripcion → nombre
                'articulos.codigo_repuesto',
                'articulos.precio_venta',
                'articulos.idTipoArticulo',
                'suministros.cantidad as cantidad_suministro',
                'suministros.idVisitas'
            )
                ->get();

            Log::info('Suministros encontrados', [
                'total' => $suministros->count(),
                'ticketId' => $ticketId,
                'visitaId' => $visitaId
            ]);

            return response()->json([
                'success' => true,
                'suministros' => $suministros
            ]);
        } catch (\Exception $e) {
            Log::error('Error al cargar suministros: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar suministros'
            ], 500);
        }
    }

    // También corrige los métodos auxiliares:
    private function obtenerSuministros($ticketId)
    {
        return DB::table('suministros')
            ->join('articulos', 'suministros.idArticulos', '=', 'articulos.idArticulos')
            ->where('suministros.idTickets', $ticketId)
            ->select(
                'articulos.idArticulos',
                'articulos.nombre', // 🔥 CAMBIADO
                'articulos.codigo_repuesto',
                'suministros.cantidad',
                'articulos.precio_venta as precio_unitario'
            )
            ->get();
    }

    private function obtenerSuministrosMultiples($ticketIds)
    {
        return DB::table('suministros')
            ->join('articulos', 'suministros.idArticulos', '=', 'articulos.idArticulos')
            ->whereIn('suministros.idTickets', $ticketIds)
            ->select(
                'articulos.idArticulos',
                'articulos.nombre', // 🔥 CAMBIADO
                'articulos.codigo_repuesto',
                'suministros.cantidad',
                'articulos.precio_venta as precio_unitario',
                'suministros.idTickets'
            )
            ->get();
    }

    private function obtenerInfoTickets($ticketIds)
    {
        return DB::table('tickets')
            ->whereIn('idTickets', $ticketIds)
            ->select('idTickets', 'numero_ticket', 'idCliente')
            ->get();
    }

    public function search(Request $request)
    {
        $search = $request->get('search');

        $clientes = Cliente::where('estado', 1)
            ->where(function ($query) use ($search) {
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

    public function getTicketsDisponibles()
    {
        try {
            $tickets = Ticket::where('idTipotickets', 2)
                ->with(['tienda'])
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

    public function getTicketDetalle($ticketId)
    {
        try {
            $ticket = Ticket::where('idTickets', $ticketId)
                ->with(['tienda', 'cliente'])
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

            $visitasLimpias = $visitas->map(function ($visita) {
                return [
                    'idVisitas' => $visita->idVisitas,
                    'Nombre' => $this->limpiarString($visita->Nombre),
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
                        'Nombre' => $this->limpiarString($visita->tecnico->Nombre),
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


    // 🔥 NUEVO MÉTODO: Vista previa temporal (sin guardar)
    public function vistaPreviaTemporal(Request $request)
    {
        try {
            $datos = $request->all();

            // Renderizar vista HTML para la vista previa
            $html = view('cotizaciones.pdf-temporal', compact('datos'))->render();

            return response($html);
        } catch (\Exception $e) {
            Log::error('Error al generar vista previa temporal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al generar vista previa: ' . $e->getMessage()
            ], 500);
        }
    }



    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            Log::info('Iniciando guardado de cotización', $request->all());

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

            if ($request->idTickets) {
                $ticket = Ticket::find($request->idTickets);
                if (!$ticket) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El ticket seleccionado no existe'
                    ], 422);
                }
            }

            if ($request->visita_id) {
                $visita = Visita::find($request->visita_id);
                if (!$visita) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La visita seleccionada no existe'
                    ], 422);
                }
            }

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

                'ot' => $request->ot,
                'serie' => $request->serie,
                'visita_id' => $request->visita_id,

                'idCliente' => $request->idCliente,
                'idMonedas' => $request->idMonedas,
                'idTickets' => $request->idTickets,
                'idTienda' => $request->idTienda,
            ]);

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

    private function limpiarString($string)
    {
        if (is_null($string)) {
            return '';
        }

        $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
        $string = iconv('UTF-8', 'UTF-8//IGNORE', $string);

        return $string;
    }




    public function generarPDF($id)
    {
        try {
            $cotizacion = Cotizacion::with([
                'cliente',
                'moneda',
                'ticket.tienda',
                'productos.articulo'
            ])->findOrFail($id);

            $pdf = PDF::loadView('cotizaciones.pdf', compact('cotizacion'));

            return $pdf->download("cotizacion-{$cotizacion->numero_cotizacion}.pdf");
        } catch (\Exception $e) {
            Log::error('Error al generar PDF: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al generar PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vista previa de la cotización
     */
    public function vistaPrevia($id)
    {
        try {
            $cotizacion = Cotizacion::with([
                'cliente',
                'moneda',
                'ticket.tienda',
                'productos.articulo'
            ])->findOrFail($id);

            return view('cotizaciones.vista-previa', compact('cotizacion'));
        } catch (\Exception $e) {
            Log::error('Error al cargar vista previa: ' . $e->getMessage());
            abort(404, 'Cotización no encontrada');
        }
    }

    /**
     * Enviar cotización por email
     */
    public function enviarEmail(Request $request, $id)
    {
        try {
            $cotizacion = Cotizacion::with([
                'cliente',
                'moneda',
                'ticket.tienda',
                'productos.articulo'
            ])->findOrFail($id);

            $destinatario = $request->input('email', $cotizacion->cliente->email);

            if (!$destinatario) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró email del cliente'
                ], 400);
            }

            // Generar PDF para adjuntar
            $pdf = PDF::loadView('cotizaciones.pdf', compact('cotizacion'));

            // Enviar email
            Mail::send('cotizaciones.email', compact('cotizacion'), function ($message) use ($cotizacion, $destinatario, $pdf) {
                $message->to($destinatario)
                    ->subject("Cotización {$cotizacion->numero_cotizacion} - GKM Technology")
                    ->attachData($pdf->output(), "cotizacion-{$cotizacion->numero_cotizacion}.pdf");
            });

            // Actualizar estado de la cotización
            $cotizacion->update(['estado_cotizacion' => 'enviada']);

            return response()->json([
                'success' => true,
                'message' => 'Cotización enviada por email correctamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al enviar email: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar PDF desde datos en tiempo real (sin guardar)
     */
    public function generarPDFTemporal(Request $request)
    {
        try {
            $datos = $request->all();

            $pdf = PDF::loadView('cotizaciones.pdf-temporal', compact('datos'));

            return $pdf->download("cotizacion-preview-{$datos['numero_cotizacion']}.pdf");
        } catch (\Exception $e) {
            Log::error('Error al generar PDF temporal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al generar PDF: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enviar email desde datos en tiempo real (sin guardar)
     */
    public function enviarEmailTemporal(Request $request)
    {
        try {
            $datos = $request->all();
            $destinatario = $request->input('email');

            if (!$destinatario) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debe especificar un email destinatario'
                ], 400);
            }

            // Generar PDF para adjuntar
            $pdf = PDF::loadView('cotizaciones.pdf-temporal', compact('datos'));

            // Enviar email
            Mail::send('cotizaciones.email-temporal', compact('datos'), function ($message) use ($datos, $destinatario, $pdf) {
                $message->to($destinatario)
                    ->subject("Cotización {$datos['numero_cotizacion']} - GKM Technology")
                    ->attachData($pdf->output(), "cotizacion-{$datos['numero_cotizacion']}.pdf");
            });

            return response()->json([
                'success' => true,
                'message' => 'Cotización enviada por email correctamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al enviar email temporal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar email: ' . $e->getMessage()
            ], 500);
        }
    }
}
