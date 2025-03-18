<?php

namespace App\Http\Controllers\tickets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Ticket;
use App\Models\ClienteGeneral;
use App\Models\TipoServicio;
use App\Models\Usuario;
use App\Models\Tipoticket;
use App\Models\Cliente;
use App\Models\Tienda;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\EstadoFlujo;
use App\Models\Visita;
use App\Models\ClienteClientegeneral;
use App\Exports\HelpdeskTicketExport;

class OrdenesHelpdeskController extends Controller
{
    // Mostrar la vista principal según el rol del usuario
    public function helpdesk()
    {
        // Obtener usuario autenticado y su rol
        $usuario = Auth::user();
        $rol = $usuario->rol->nombre ?? 'Sin Rol';

        // Obtener los datos necesarios
        $clientesGenerales = ClienteGeneral::all();
        $tiposServicio = TipoServicio::all();
        $usuarios = Usuario::where('idTipoUsuario', 4)->get();
        $tiposTickets = Tipoticket::all();
        $clientes = Cliente::all();
        $tiendas = Tienda::all();
        $marcas = Marca::all();
        $modelos = Modelo::all();

        // Determinar la carpeta de vistas según el rol
        $carpetaVista = match ($rol) {
            'COORDINACION SMART' => 'smart-tv',
            'COORDINACION HELP DESK' => 'helpdesk',
            default => '',
        };

        if ($carpetaVista) {
            return view("tickets.ordenes-trabajo.helpdesk.index", compact(
                'clientesGenerales',
                'tiposServicio',
                'usuarios',
                'tiposTickets',
                'clientes',
                'tiendas',
                'marcas',
                'modelos'
            ));
        } else {
            abort(403, 'No tienes permiso para acceder a esta vista.');
        }
    }

    // Cargar la vista de creación según el rol del usuario
    public function createhelpdesk()
    {
        $usuario = Auth::user();
        $rol = $usuario->rol->nombre ?? 'Sin Rol';

        $clientesGenerales = ClienteGeneral::where('estado', 1)->get();
        $clientes = Cliente::where('estado', 1)->get();
        $tiendas = Tienda::all();
        $usuarios = Usuario::where('idTipoUsuario', 4)->get();
        $tiposServicio = TipoServicio::all();
        $marcas = Marca::all();
        $modelos = Modelo::all();




        return view("tickets.ordenes-trabajo.helpdesk.create", compact(
            'clientesGenerales',
            'clientes',
            'tiendas',
            'usuarios',
            'tiposServicio',
            'marcas',
            'modelos'
        ));
    }

    public function storehelpdesk(Request $request)
    {
        try {
            Log::debug('Datos recibidos en storehelpdesk:', $request->all());

            // Validar los datos
            $validatedData = $request->validate([
                'numero_ticket' => 'required|string|max:255|unique:tickets,numero_ticket',
                'idClienteGeneral' => 'required|integer|exists:clientegeneral,idClienteGeneral',
                'idCliente' => 'required|integer|exists:cliente,idCliente',
                'idTienda' => 'required|integer|exists:tienda,idTienda',
                'idTecnico' => 'required|integer|exists:usuarios,idUsuario',
                'tipoServicio' => 'required|integer|exists:tiposervicio,idTipoServicio',
                'fallaReportada' => 'required|string|max:255',
            ]);

            Log::debug('Datos validados:', $validatedData);

            // Guardar la orden de trabajo
            $ticket = Ticket::create([
                'numero_ticket' => $validatedData['numero_ticket'],
                'idClienteGeneral' => $validatedData['idClienteGeneral'],
                'idCliente' => $validatedData['idCliente'],
                'idTienda' => $validatedData['idTienda'],
                'idTecnico' => $validatedData['idTecnico'],
                'tipoServicio' => $validatedData['tipoServicio'],
                'idUsuario' => auth()->id(),

                'fallaReportada' => $validatedData['fallaReportada'],
                'fecha_creacion' => now(),
                'idTipotickets' => 2,
            ]);

            Log::debug('Orden de trabajo creada correctamente.');

            // 🔹 Redirigir según el tipo de servicio seleccionado
            if ($validatedData['tipoServicio'] == 2) {
                return redirect()->route('ordenes.helpdesk.levantamiento.edit', ['id' => $ticket->idTickets])
                    ->with('success', 'Orden de trabajo creada correctamente (Levantamiento de Información).');
            } elseif ($validatedData['tipoServicio'] == 1) {
                return redirect()->route('ordenes.helpdesk.soporte.edit', ['id' => $ticket->idTickets])
                    ->with('success', 'Orden de trabajo creada correctamente (Soporte On Site).');
            } else {
                return redirect()->route('ordenes.helpdesk.index')->with('success', 'Orden de trabajo creada correctamente.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Errores de validación:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error al crear la orden de trabajo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al crear la orden de trabajo.');
        }
    }


    public function editHelpdesk($id)
    {
        $usuario = Auth::user();
        $rol = $usuario->rol->nombre ?? 'Sin Rol';

        $estadosOTS = DB::table('estado_ots')
        ->whereIn('idEstadoots', [2, 4, 5])
        ->get();


        // Obtener la orden con relaciones
        $orden = Ticket::with(['marca', 'modelo', 'cliente', 'tecnico', 'tienda', 'estadoflujo', 'usuario'])
            ->findOrFail($id);
            $ticket = Ticket::with(['marca', 'modelo', 'cliente', 'tecnico', 'tienda', 'ticketflujo.estadoFlujo', 'usuario'])->findOrFail($id);

            
    // Obtener el idTickets
    $ticketId = $ticket->idTickets;
            $colorEstado = $orden->ticketflujo && $orden->ticketflujo->estadoFlujo ? $orden->ticketflujo->estadoFlujo->color : '#FFFFFF';  // color por defecto si no se encuentra

            $encargado = Usuario::whereIn('idTipoUsuario', [1])->get();
            $tecnicos_apoyo = Usuario::where('idTipoUsuario', 1)->get();

        // Obtener listas necesarias para el formulario
        $clientes = Cliente::all();
        $clientesGenerales = ClienteGeneral::all();
        $estadosFlujo = EstadoFlujo::all();
        $modelos = Modelo::all();
        $tiendas = Tienda::all();
        $marcas = Marca::all();

        // 🔹 Aquí se añade la variable $usuarios para solucionar el error
        $usuarios = Usuario::all(); // Obtener todos los técnicos disponibles
        $tiposServicio = TipoServicio::all(); // Obtener tipos de servicio disponibles

          // Buscar en la tabla tickets el idTicketFlujo correspondiente al ticket
          $ticket = DB::table('tickets')->where('idTickets', $id)->first();

        return view("tickets.ordenes-trabajo.helpdesk.edit", compact(
            'orden',
            'usuarios',
            'tiposServicio',
            'modelos',
            'clientes',
            'clientesGenerales',
            'tiendas',
            'estadosOTS',
            'marcas',
            'estadosFlujo',
            'colorEstado',
            'encargado',
            'tecnicos_apoyo',
            'ticketId',
            'ticket',
            'id'

        ));
    }



    public function editSoporte($id)
    {
        $usuario = Auth::user();
        $rol = $usuario->rol->nombre ?? 'Sin Rol';

        // Obtener la orden con relaciones
        $orden = Ticket::with(['marca', 'modelo', 'cliente', 'tecnico', 'tienda', 'estadoflujo', 'usuario'])
            ->findOrFail($id);

        // Obtener listas necesarias para el formulario
        $clientes = Cliente::all();
        $clientesGenerales = ClienteGeneral::all();
        $estadosFlujo = EstadoFlujo::all();
        $modelos = Modelo::all();
        $tiendas = Tienda::all();
        $marcas = Marca::all();
        $usuarios = Usuario::all();
        $tiposServicio = TipoServicio::all();

        return view("tickets.ordenes-trabajo.helpdesk.edit", compact(
            'orden',
            'usuarios',
            'tiposServicio',
            'modelos',
            'clientes',
            'clientesGenerales',
            'tiendas',
            'marcas',
            'estadosFlujo'
        ));
    }



    public function updateHelpdesk(Request $request, $id)
    {
        $validatedData = $request->validate([
            'numero_ticket' => 'required|string|max:255',
            'idClienteGeneral' => 'required|integer|exists:clientegeneral,idClienteGeneral',
            'idCliente' => 'required|integer|exists:cliente,idCliente',
            'idTienda' => 'required|integer|exists:tienda,idTienda',
            'idTecnico' => 'required|integer|exists:usuarios,idUsuario',
            'tipoServicio' => 'required|integer|exists:tiposervicio,idTipoServicio',
            'fallaReportada' => 'required|string|max:255',
        ]);
    
        $orden = Ticket::findOrFail($id);
        $orden->update($validatedData);
    
        // 🔹 Determinar a qué vista redirigir según el tipo de servicio
        $rutaEdicion = ($orden->tipoServicio == 1) 
            ? 'helpdesk.levantamiento.edit' 
            : 'helpdesk.soporte.edit';
    
        return redirect()->route($rutaEdicion, ['id' => $id])
            ->with('success', 'Orden actualizada correctamente.');
    }
    

    public function exportHelpdeskToExcel()
    {
        return Excel::download(new HelpdeskTicketExport(), 'helpdesk_tickets.xlsx');
    }

    public function getAll(Request $request)
    {
        $ordenesQuery = Ticket::with([
            'tecnico:idUsuario,Nombre',
            'usuario:idUsuario,Nombre',
            'cliente:idCliente,nombre',
            'clientegeneral:idClienteGeneral,descripcion',
            'tiposervicio:idTipoServicio,nombre',
            'estado_ot:idEstadoots,descripcion,color',
            'marca:idMarca,nombre',
            'modelo.categoria:idCategoria,nombre', // Cargar la categoría a través del modelo
            'estadoflujo:idEstadflujo,descripcion,color' // Cargar toda la relación estadoflujo
        ]);

        // 🔹 Filtrar por tipo de ticket (1 o 2), si no se proporciona, por defecto muestra ambos
        if ($request->has('tipoTicket') && in_array($request->tipoTicket, [1, 2])) {
            $ordenesQuery->where('idTipotickets', $request->tipoTicket);
        }

        // 🔹 Filtro por marca (si es proporcionado)
        if ($request->has('marca') && $request->marca != '') {
            $ordenesQuery->where('idMarca', $request->marca);
        }

        // 🔹 Filtro por cliente general (si es proporcionado)
        if ($request->has('clienteGeneral') && $request->clienteGeneral != '') {
            $ordenesQuery->where('idClienteGeneral', $request->clienteGeneral);
        }

        $ordenes = $ordenesQuery->paginate(10);
        return response()->json($ordenes);
    }

    public function validarTicket($nroTicket)
    {
        $ticketExistente = Ticket::where('numero_ticket', $nroTicket)->exists();

        return response()->json([
            'existe' => $ticketExistente
        ]);
    }
    public function getClientesGenerales($idCliente)
    {
        $clientesGenerales = ClienteClientegeneral::where('idCliente', $idCliente)
            ->with('clienteGeneral')
            ->get();

        return response()->json($clientesGenerales);
    }

    public function getClientes()
    {
        $clientes = Cliente::all();

        return response()->json($clientes);
    }

    public function checkNumeroTicket(Request $request)
    {
        $numero_ticket = $request->input('numero_ticket');
        $exists = Ticket::where('numero_ticket', $numero_ticket)->exists();

        return response()->json(['unique' => !$exists]);
    }

    public function guardarCliente(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'documento' => 'required|string|max:255|unique:cliente,documento',
                'telefono' => 'nullable|string|max:15',
                'email' => 'nullable|email|max:255',
                'direccion' => 'required|string|max:255',
                'departamento' => 'required|string|max:255',
                'provincia' => 'required|string|max:255',
                'distrito' => 'required|string|max:255',
                'esTienda' => 'nullable|boolean',
                'idTipoDocumento' => 'required|exists:tipodocumento,idTipoDocumento',
                'idClienteGeneraloption' => 'required|array',
                'idClienteGeneraloption.*' => 'integer|exists:clientegeneral,idClienteGeneral',
            ]);

            $validatedData['estado'] = 1;
            $validatedData['fecha_registro'] = now();
            $validatedData['esTienda'] = isset($validatedData['esTienda']) && $validatedData['esTienda'] == 1 ? true : false;

            $idClienteGenerales = $validatedData['idClienteGeneraloption'];
            unset($validatedData['idClienteGeneraloption']);

            $cliente = Cliente::create($validatedData);

            if (!empty($idClienteGenerales)) {
                $clienteGenerales = collect($idClienteGenerales)->map(function ($idClienteGeneral) use ($cliente) {
                    return [
                        'idCliente' => $cliente->idCliente,
                        'idClienteGeneral' => $idClienteGeneral,
                    ];
                });

                DB::table('cliente_clientegeneral')->insert($clienteGenerales->toArray());
            }

            return response()->json([
                'success' => true,
                'message' => 'Cliente agregado correctamente',
                'data' => $cliente,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Errores de validación:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al guardar el cliente: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar el cliente.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function obtenerVisitas($ticketId)
    {
        $visitas = Visita::where('idTickets', $ticketId)->get();

        $visitas->each(function ($visita) {
            $visita->fecha_inicio = $visita->fecha_inicio->toIso8601String();
            $visita->fecha_final = $visita->fecha_final->toIso8601String();
        });

        return response()->json($visitas);
    }



    public function actualizarHelpdesk(Request $request, $id)
    {
        // Log para ver los datos que se están recibiendo
        Log::info('Datos recibidos para actualizar la orden:', $request->all());
    
        // Validar los datos del formulario
        $validated = $request->validate([
            'idCliente' => 'required|exists:cliente,idCliente',
            'idClienteGeneral' => 'required|exists:clientegeneral,idClienteGeneral',
            'idTienda' => 'required|exists:tienda,idTienda',
            'idTecnico' => 'required|exists:usuarios,idUsuario',
            'fallaReportada' => 'nullable|string',
        ]);
    
        // Encontrar la orden y actualizarla
        $orden = Ticket::findOrFail($id); // Usamos findOrFail para asegurarnos que la orden existe
    
        // Log para verificar que se encontró la orden
        Log::info('Orden encontrada con ID:', ['id' => $orden->id]);
    
        // Actualizar los campos de la orden
        $orden->idCliente = $request->idCliente;
        $orden->idClienteGeneral = $request->idClienteGeneral;
        $orden->idTienda = $request->idTienda;
        $orden->idTecnico = $request->idTecnico;
        $orden->fallaReportada = $request->fallaReportada;
    
        // Guardar los cambios
        $orden->save();
    
        // Log para confirmar que los cambios se guardaron
        Log::info('Orden actualizada:', ['id' => $orden->id, 'nuevos_datos' => $orden->toArray()]);
    
        // Responder con éxito
        return response()->json(['success' => true]);
    }
    
}
