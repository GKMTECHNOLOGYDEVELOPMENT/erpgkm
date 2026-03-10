<?php
// app/Http/Controllers/evaluarticket/EvaluarTicketController.php

namespace App\Http\Controllers\evaluarticket;

use App\Http\Controllers\Controller;
use App\Models\TicketClienteGeneral;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Modelo;
use App\Models\Ticket;
use App\Models\Tienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Traits\GoogleMapsTrait; // IMPORTAR EL TRAIT


class EvaluarTicketController extends Controller
{
      use GoogleMapsTrait; // USAR EL TRAIT

    /**
     * Display a listing of the tickets for evaluation.
     */
    public function index()
    {
        // Renderizar la vista
        return view('evaluarticket.index');
    }

    /**
     * API endpoint para obtener los tickets (para el JS)
     */
   public function getTickets()
{
    try {
        // Obtener todos los tickets con sus relaciones
        $tickets = TicketClienteGeneral::with([
            'categoria',
            'modelo.marca',
            'tipoDocumento',
            'clienteGeneral' // Agregar relación con cliente general
        ])
        ->orderBy('fechaCreacion', 'desc')
        ->get();

        // Transformar los datos para el frontend
        $ticketsTransformados = $tickets->map(function ($ticket) {
            // Mapear los estados directamente
            $estadoTexto = 'evaluando'; // valor por defecto para estado=1
            
            if ($ticket->estado == 2) {
                $estadoTexto = 'gestionando';
            } elseif ($ticket->estado == 3) {
                $estadoTexto = 'finalizado';
            }

            // Procesar foto del cliente general si existe (BLOB)
            $fotoClienteBase64 = null;
            if ($ticket->clienteGeneral && $ticket->clienteGeneral->foto) {
                // Convertir BLOB a base64
                $fotoClienteBase64 = 'data:image/jpeg;base64,' . base64_encode($ticket->clienteGeneral->foto);
            }

            return [
                'id' => $ticket->idTicket,
                'numeroTicket' => $ticket->numero_ticket,
                
                // Datos cliente general
                'idClienteGeneral' => $ticket->idClienteGeneral,
                'clienteGeneral' => $ticket->clienteGeneral ? [
                    'id' => $ticket->clienteGeneral->idClienteGeneral,
                    'descripcion' => $ticket->clienteGeneral->descripcion,
                    'foto' => $fotoClienteBase64
                ] : null,
                
                // Datos cliente (persona que reporta)
                'nombreCompleto' => $ticket->nombreCompleto,
                'correoElectronico' => $ticket->correoElectronico,
                'telefonoCelular' => $ticket->telefonoCelular,
                'telefonoFijo' => $ticket->telefonoFijo,
                'tipoDocumento' => $ticket->tipoDocumento ? $ticket->tipoDocumento->nombre : 'N/A',
                'dni_ruc_ce' => $ticket->dni_ruc_ce,
                
                // DATOS DE DIRECCIÓN
                'direccionCompleta' => $ticket->direccionCompleta,
                'referenciaDomicilio' => $ticket->referenciaDomicilio,
                'departamento' => $ticket->departamento,
                'provincia' => $ticket->provincia,
                'distrito' => $ticket->distrito,
                'ubicacionGoogleMaps' => $ticket->ubicacionGoogleMaps,
                
                // Datos producto
                'tipoProducto' => $ticket->categoria ? $ticket->categoria->nombre : 'N/A',
                'marca' => $ticket->modelo && $ticket->modelo->marca ? $ticket->modelo->marca->nombre : 'N/A',
                'modelo' => $ticket->modelo ? $ticket->modelo->nombre : 'N/A',
                'serie' => $ticket->serieProducto,
                
                // Falla
                'detallesFalla' => $ticket->detallesFalla,
                
                // Fechas
                'fechaCreacion' => date('Y-m-d H:i', strtotime($ticket->fechaCreacion)),
                'fechaCompra' => $ticket->fechaCompra,
                'tiendaSedeCompra' => $ticket->tiendaSedeCompra,
                
                // EVIDENCIAS - FOTOS
                'fotoVideoFalla' => $ticket->fotoVideoFalla,
                'fotoBoletaFactura' => $ticket->fotoBoletaFactura,
                'fotoNumeroSerie' => $ticket->fotoNumeroSerie,
                
                // Estado
                'estado' => $estadoTexto,
                'estado_valor' => $ticket->estado
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $ticketsTransformados
        ], 200);

    } catch (\Exception $e) {
        Log::error('Error en EvaluarTicketController@getTickets: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener tickets para evaluación',
            'error' => $e->getMessage()
        ], 500);
    }
}




public function crearOrdenTrabajo($idTicket)
    {
        try {
            DB::beginTransaction();
            
            Log::info('========== INICIO crearOrdenTrabajo ==========');
            Log::info('Creando orden de trabajo para ticket ID: ' . $idTicket);
            
            // Obtener el ticket con todas sus relaciones
            $ticket = TicketClienteGeneral::with([
                'categoria',
                'modelo.marca',
                'tipoDocumento',
                'clienteGeneral'
            ])->find($idTicket);
            
            if (!$ticket) {
                Log::error('Ticket no encontrado ID: ' . $idTicket);
                return response()->json([
                    'success' => false,
                    'message' => 'Ticket no encontrado'
                ], 404);
            }
            
            Log::info('Ticket encontrado:', [
                'id' => $ticket->idTicket,
                'numero' => $ticket->numero_ticket,
                'nombre' => $ticket->nombreCompleto,
                'idClienteGeneral' => $ticket->idClienteGeneral,
                'estado_actual' => $ticket->estado,
                'ubicacionGoogleMaps' => $ticket->ubicacionGoogleMaps
            ]);
            
            // PASO 0: Extraer coordenadas del link de Google Maps si existe
            $lat = null;
            $lng = null;
            $ubicacionGoogleMaps = $ticket->ubicacionGoogleMaps;
            
            if (!empty($ticket->ubicacionGoogleMaps)) {
                Log::info('Procesando link de ubicación:', ['link' => $ticket->ubicacionGoogleMaps]);
                
                $coordenadas = $this->extraerCoordenadasDeGoogleMaps($ticket->ubicacionGoogleMaps);
                $lat = $coordenadas['lat'];
                $lng = $coordenadas['lng'];
                
                // Si se resolvió una URL, actualizar
                if ($coordenadas['url_procesada'] !== $ticket->ubicacionGoogleMaps) {
                    $ubicacionGoogleMaps = $coordenadas['url_procesada'];
                    Log::info('URL actualizada después de resolución:', ['nueva_url' => $ubicacionGoogleMaps]);
                }
                
                Log::info('Coordenadas extraídas:', [
                    'lat' => $lat,
                    'lng' => $lng,
                    'url_final' => $ubicacionGoogleMaps
                ]);
            } else {
                Log::info('El ticket no tiene link de ubicación');
            }
            
            // PASO 1: Verificar si ya existe un cliente con este documento
            $clienteExistente = Cliente::where('documento', $ticket->dni_ruc_ce)->first();
            
            if ($clienteExistente) {
                Log::info('Cliente existente encontrado:', [
                    'idCliente' => $clienteExistente->idCliente,
                    'nombre' => $clienteExistente->nombre,
                    'documento' => $clienteExistente->documento
                ]);
                $cliente = $clienteExistente;
            } else {
                // Crear nuevo cliente
                Log::info('Creando nuevo cliente...');
                
                $clienteData = [
                    'nombre' => $ticket->nombreCompleto,
                    'idTipoDocumento' => $ticket->idTipoDocumento,
                    'documento' => $ticket->dni_ruc_ce,
                    'telefono' => $ticket->telefonoCelular,
                    'email' => $ticket->correoElectronico,
                    'departamento' => $ticket->departamento,
                    'provincia' => $ticket->provincia,
                    'distrito' => $ticket->distrito,
                    'direccion' => $ticket->direccionCompleta,
                    'idClienteGeneral' => [$ticket->idClienteGeneral],
                    'esTienda' => false,
                    'estado' => 1,
                    'fecha_registro' => now()
                ];
                
                Log::info('Datos para nuevo cliente:', $clienteData);
                
                // Validar los datos del cliente
                $validator = Validator::make($clienteData, [
                    'nombre' => 'required|string|max:255',
                    'idTipoDocumento' => 'required|integer|exists:tipodocumento,idTipoDocumento',
                    'documento' => 'required|string|max:255|unique:cliente,documento',
                    'telefono' => 'nullable|string|max:15',
                    'email' => 'nullable|email|max:255',
                    'departamento' => 'required|string|max:255',
                    'provincia' => 'required|string|max:255',
                    'distrito' => 'required|string|max:255',
                    'direccion' => 'required|string|max:255',
                    'idClienteGeneral' => 'required|array',
                    'idClienteGeneral.*' => 'integer|exists:clientegeneral,idClienteGeneral',
                ]);
                
                if ($validator->fails()) {
                    Log::error('Error validación cliente:', $validator->errors()->toArray());
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Error de validación al crear cliente',
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                // Crear el cliente
                $idClienteGenerales = $clienteData['idClienteGeneral'];
                unset($clienteData['idClienteGeneral']);
                
                $cliente = Cliente::create($clienteData);
                
                // Asociar los idClienteGeneral en la tabla pivote
                if (!empty($idClienteGenerales)) {
                    $clienteGenerales = collect($idClienteGenerales)->map(function ($idClienteGeneral) use ($cliente) {
                        return [
                            'idCliente' => $cliente->idCliente,
                            'idClienteGeneral' => $idClienteGeneral,
                        ];
                    });
                    
                    DB::table('cliente_clientegeneral')->insert($clienteGenerales->toArray());
                }
                
                Log::info('Cliente creado exitosamente:', [
                    'idCliente' => $cliente->idCliente,
                    'nombre' => $cliente->nombre
                ]);
            }
            
            // PASO 2: Crear la tienda
            Log::info('Creando tienda para el cliente...');
            
            // Usar la tienda del ticket o un nombre por defecto
            $nombreTienda = $ticket->tiendaSedeCompra ?: 'Tienda ' . $ticket->nombreCompleto;
            
            $tiendaExistente = Tienda::where('nombre', $nombreTienda)
                ->where('idCliente', $cliente->idCliente)
                ->first();
            
            if ($tiendaExistente) {
                Log::info('Tienda existente encontrada:', [
                    'idTienda' => $tiendaExistente->idTienda,
                    'nombre' => $tiendaExistente->nombre
                ]);
                $tienda = $tiendaExistente;
            } else {
                // Crear nueva tienda
                $tiendaData = [
                    'ruc' => $ticket->dni_ruc_ce,
                    'nombre' => $nombreTienda,
                    'celular' => $ticket->telefonoCelular,
                    'email' => $ticket->correoElectronico,
                    'direccion' => $ticket->direccionCompleta,
                    'referencia' => $ticket->referenciaDomicilio,
                    'lat' => $lat, // Asignar latitud extraída
                    'lng' => $lng, // Asignar longitud extraída
                    'idCliente' => $cliente->idCliente,
                    'departamento' => $ticket->departamento,
                    'provincia' => $ticket->provincia,
                    'distrito' => $ticket->distrito,
                ];
                
                Log::info('Datos para nueva tienda:', $tiendaData);
                
                $tienda = Tienda::create($tiendaData);
                
                Log::info('Tienda creada exitosamente:', [
                    'idTienda' => $tienda->idTienda,
                    'nombre' => $tienda->nombre,
                    'lat' => $tienda->lat,
                    'lng' => $tienda->lng
                ]);
            }
            
            // PASO 3: Crear la orden de trabajo
            Log::info('Creando orden de trabajo...');
            
            // Usar el mismo número de ticket del ticket original
            $nroTicket = $ticket->numero_ticket;
            
            // Formatear fecha de compra correctamente (solo Y-m-d)
            $fechaCompraFormateada = date('Y-m-d', strtotime($ticket->fechaCompra));
            
            // Formatear fecha de creación (Y-m-d H:i)
            $fechaCreacion = now();
            $fechaCreacionFormateada = $fechaCreacion->format('Y-m-d H:i');
            
            $ordenData = [
                'nroTicket' => $nroTicket,
                'idClienteGeneral' => $ticket->idClienteGeneral,
                'idCliente' => $cliente->idCliente,
                'idTienda' => $tienda->idTienda,
                'direccion' => $ticket->direccionCompleta,
                'idMarca' => $ticket->modelo->idMarca ?? null,
                'idModelo' => $ticket->idModelo,
                'serie' => $ticket->serieProducto,
                'fechaCompra' => $fechaCompraFormateada,
                'fecha_creacion' => $fechaCreacionFormateada,
                'fallaReportada' => $ticket->detallesFalla,
                'linkubicacion' => $ubicacionGoogleMaps ?? '', // Guardar el link procesado
                'lat' => $lat, // Asignar latitud extraída
                'lng' => $lng, // Asignar longitud extraída
                'evaluaciontienda' => 0
            ];
            
            Log::info('Datos para orden de trabajo:', $ordenData);
            
            // Validar que el número de ticket no exista ya en la tabla tickets
            $ticketExistente = Ticket::where('numero_ticket', $nroTicket)->first();
            if ($ticketExistente) {
                Log::warning('El número de ticket ya existe en la tabla tickets, se generará uno nuevo');
                // Si ya existe, generar uno nuevo
                $ultimoTicket = Ticket::orderBy('idTickets', 'desc')->first();
                $numero = $ultimoTicket ? intval(substr($ultimoTicket->numero_ticket, -6)) + 1 : 1;
                $nroTicket = 'OT-' . str_pad($numero, 6, '0', STR_PAD_LEFT);
                $ordenData['nroTicket'] = $nroTicket;
            }
            
            // Validar los datos de la orden
            $validator = Validator::make($ordenData, [
                'nroTicket' => 'required|string|max:255|unique:tickets,numero_ticket',
                'idClienteGeneral' => 'required|integer|exists:clientegeneral,idClienteGeneral',
                'idCliente' => 'required|integer|exists:cliente,idCliente',
                'idTienda' => 'required|integer|exists:tienda,idTienda',
                'direccion' => 'required|string|max:255',
                'idMarca' => 'nullable|integer|exists:marca,idMarca',
                'idModelo' => 'required|integer|exists:modelo,idModelo',
                'serie' => 'required|string|max:255',
                'fechaCompra' => 'required|date_format:Y-m-d',
                'fecha_creacion' => 'required|date_format:Y-m-d H:i',
                'fallaReportada' => 'required|string',
                'linkubicacion' => 'nullable|string',
                'lat' => 'nullable|numeric',
                'lng' => 'nullable|numeric',
                'evaluaciontienda' => 'required|in:0,1'
            ]);
            
            if ($validator->fails()) {
                Log::error('Error validación orden:', $validator->errors()->toArray());
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación al crear orden',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            // Crear la orden de trabajo
            $orden = Ticket::create([
                'numero_ticket' => $ordenData['nroTicket'],
                'idClienteGeneral' => $ordenData['idClienteGeneral'],
                'idCliente' => $ordenData['idCliente'],
                'idTienda' => $ordenData['idTienda'],
                'direccion' => $ordenData['direccion'],
                'idMarca' => $ordenData['idMarca'],
                'idModelo' => $ordenData['idModelo'],
                'serie' => $ordenData['serie'],
                'fechaCompra' => $ordenData['fechaCompra'],
                'linkubicacion' => $ordenData['linkubicacion'],
                'fallaReportada' => $ordenData['fallaReportada'],
                'lat' => $ordenData['lat'],
                'lng' => $ordenData['lng'],
                'idUsuario' => auth()->id(),
                'fecha_creacion' => $fechaCreacion,
                'idTipotickets' => 1,
                'tipoServicio' => 1,
                'evaluaciontienda' => $ordenData['evaluaciontienda']
            ]);
            
            Log::info('Orden de trabajo creada:', [
                'idTickets' => $orden->idTickets,
                'numero_ticket' => $orden->numero_ticket,
                'lat' => $orden->lat,
                'lng' => $orden->lng,
                'linkubicacion' => $orden->linkubicacion
            ]);
            
            // PASO 4: ACTUALIZAR EL ESTADO DEL TICKET ORIGINAL A GESTIONANDO (estado = 2)
            $ticket->estado = 2; // Cambiar a gestionando
            $ticket->save();
            
            Log::info('Estado del ticket original actualizado:', [
                'idTicket' => $ticket->idTicket,
                'numero_ticket' => $ticket->numero_ticket,
                'nuevo_estado' => $ticket->estado
            ]);
            
            // PASO 5: Crear el flujo de trabajo
            $ticketFlujoId = DB::table('ticketflujo')->insertGetId([
                'idTicket' => $orden->idTickets,
                'idEstadflujo' => 1, // Estado inicial
                'idUsuario' => auth()->id(),
                'fecha_creacion' => now(),
            ]);
            
            // Actualizar el ticket con el idTicketFlujo
            $orden->idTicketFlujo = $ticketFlujoId;
            $orden->save();
            
            Log::info('Flujo de trabajo creado:', [
                'idTicketFlujo' => $ticketFlujoId
            ]);
            
            DB::commit();
            
            Log::info('========== FIN crearOrdenTrabajo - ÉXITO ==========');
            
            return response()->json([
                'success' => true,
                'message' => 'Orden de trabajo creada exitosamente',
                'data' => [
                    'idOrden' => $orden->idTickets,
                    'numero_ticket' => $orden->numero_ticket,
                    'idCliente' => $cliente->idCliente,
                    'idTienda' => $tienda->idTienda,
                    'lat' => $orden->lat,
                    'lng' => $orden->lng,
                    'linkubicacion' => $orden->linkubicacion
                ]
            ], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('========== ERROR en crearOrdenTrabajo ==========');
            Log::error('Mensaje: ' . $e->getMessage());
            Log::error('Archivo: ' . $e->getFile() . ':' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());
            Log::error('================================================');
            
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la orden de trabajo',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}