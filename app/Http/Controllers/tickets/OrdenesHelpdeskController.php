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
use App\Models\Articulo;
use App\Models\SeleccionarVisita;
use App\Models\Suministro;

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

            // Crear el flujo de trabajo con idEstadflujo = 10 (estado para "entrega a laboratorio")
            $ticketFlujoId = DB::table('ticketflujo')->insertGetId([
                'idTicket' => $ticket->idTickets,
                'idEstadflujo' => 1,  // Estado de flujo "entrega a laboratorio"
                'idUsuario' => auth()->id(),
                'fecha_creacion' => now(),
            ]);

            Log::info('Flujo de trabajo guardado correctamente', [
                'idTicket' => $ticket->idTickets,
                'idTicketFlujo' => $ticketFlujoId
            ]);

            // Actualizar el ticket con el idTicketFlujo generado
            $ticket->idTicketFlujo = $ticketFlujoId;
            $ticket->save();


            Log::info('Ticket actualizado con idTicketFlujo', ['ticket' => $ticket]);

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



        // Buscar la visita seleccionada para el ticket
        $VisitaIdd = DB::table('seleccionarvisita')
            ->where('idTickets', $ticketId)
            ->value('idVisitas');  // Obtenemos el idVisitas de la visita seleccionada para ese ticket

        Log::info('Visita seleccionada, VisitaIdd: ' . $VisitaIdd);  // Log de la visita seleccionada

        // Si se encuentra una visita seleccionada
        if ($VisitaIdd) {
            // Obtener más detalles de la visita seleccionada (si lo necesitas)
            $visita = DB::table('visitas')
                ->where('idVisitas', $VisitaIdd)
                ->first();

            Log::info('Detalles de la visita seleccionada:', (array) $visita);  // Log de los detalles de la visita
        } else {
            Log::warning('No se encontró una visita seleccionada para el ticket: ' . $ticketId);  // Log si no se encuentra una visita seleccionada
        }




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
        $tipoUsuario = Auth::user()->idTipoUsuario; // ✅
        $idtipoServicio = $orden->tipoServicio ?? null;
        $tiposServicio = TipoServicio::all();

        // Buscar en la tabla tickets el idTicketFlujo correspondiente al ticket
        $ticket = DB::table('tickets')->where('idTickets', $id)->first();

        // Obtener los articulos según el idTipoArticulo
        $articulosTipo1 = Articulo::where('idTipoArticulo', 1)->get();  // Artículos con idTipoArticulo = 1
        $articulosTipo2 = Articulo::where('idTipoArticulo', 2)->get();  // Artículos con idTipoArticulo = 2
        $articulosTipo3 = Articulo::where('idTipoArticulo', 3)->get();  // Artículos con idTipoArticulo = 3
        $articulosTipo4 = Articulo::where('idTipoArticulo', 4)->get();  // Artículos con idTipoArticulo = 4

        $idVisitaSeleccionada = null;

        return view("tickets.ordenes-trabajo.helpdesk.edit", compact(
            'orden',
            'usuarios',
            'tipoUsuario',
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
            'VisitaIdd',
            'id',
            'articulosTipo1',
            'articulosTipo2',
            'articulosTipo3',
            'articulosTipo4',
            'idVisitaSeleccionada',
            'idtipoServicio'

        ));
    }

    // public function store(Request $request)
    // {
    //     // Validar los datos recibidos
    //     $request->validate([
    //         'herramientas' => 'array',
    //         'productos' => 'array',
    //         'repuestos' => 'array',
    //         'insumos' => 'array',
    //         'herramientas.*.idArticulos' => 'required|exists:articulos,idArticulos',
    //         'productos.*.idArticulos' => 'required|exists:articulos,idArticulos',
    //         'repuestos.*.idArticulos' => 'required|exists:articulos,idArticulos',
    //         'insumos.*.idArticulos' => 'required|exists:articulos,idArticulos',
    //         'herramientas.*.cantidad' => 'required|integer|min:1',
    //         'productos.*.cantidad' => 'required|integer|min:1',
    //         'repuestos.*.cantidad' => 'required|integer|min:1',
    //         'insumos.*.cantidad' => 'required|integer|min:1',
    //     ]);

    //     // Insertar suministros de herramientas
    //     foreach ($request->herramientas as $herramienta) {
    //         Suministro::create([
    //             'idTickets' => $herramienta['idTickets'],
    //             'idVisitas' => $herramienta['idVisitas'],
    //             'idArticulos' => $herramienta['idArticulos'],
    //             'cantidad' => $herramienta['cantidad'],
    //             'tipo' => 'herramienta',  // Si quieres almacenar el tipo de suministro
    //         ]);
    //     }

    //     // Insertar suministros de productos
    //     foreach ($request->productos as $producto) {
    //         Suministro::create([
    //             'idTickets' => $producto['idTickets'],
    //             'idVisitas' => $producto['idVisitas'],
    //             'idArticulos' => $producto['idArticulos'],
    //             'cantidad' => $producto['cantidad'],
    //             'tipo' => 'producto',  // Similar para productos
    //         ]);
    //     }

    //     // Insertar suministros de repuestos
    //     foreach ($request->repuestos as $repuesto) {
    //         Suministro::create([
    //             'idTickets' => $repuesto['idTickets'],
    //             'idVisitas' => $repuesto['idVisitas'],
    //             'idArticulos' => $repuesto['idArticulos'],
    //             'cantidad' => $repuesto['cantidad'],
    //             'tipo' => 'repuesto',
    //         ]);
    //     }

    //     // Insertar suministros de insumos
    //     foreach ($request->insumos as $insumo) {
    //         Suministro::create([
    //             'idTickets' => $insumo['idTickets'],
    //             'idVisitas' => $insumo['idVisitas'],
    //             'idArticulos' => $insumo['idArticulos'],
    //             'cantidad' => $insumo['cantidad'],
    //             'tipo' => 'insumo',
    //         ]);
    //     }

    //     // Responder con éxito
    //     return response()->json(['success' => true]);
    // }

    public function store(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'herramientas' => 'array',
            'productos' => 'array',
            'repuestos' => 'array',
            'insumos' => 'array',
            'herramientas.*.idArticulos' => 'required|exists:articulos,idArticulos',
            'productos.*.idArticulos' => 'required|exists:articulos,idArticulos',
            'repuestos.*.idArticulos' => 'required|exists:articulos,idArticulos',
            'insumos.*.idArticulos' => 'required|exists:articulos,idArticulos',
            'herramientas.*.cantidad' => 'required|integer|min:1',
            'productos.*.cantidad' => 'required|integer|min:1',
            'repuestos.*.cantidad' => 'required|integer|min:1',
            'insumos.*.cantidad' => 'required|integer|min:1',
        ]);

        $errores = [];

        // Insertar suministros de herramientas
        foreach ($request->herramientas as $herramienta) {
            $existeSuministro = Suministro::where('idTickets', $herramienta['idTickets'])
                ->where('idVisitas', $herramienta['idVisitas'])
                ->where('idArticulos', $herramienta['idArticulos'])
                ->exists();

            $nombreArticulo = Articulo::find($herramienta['idArticulos'])->nombre;  // Obtener el nombre del artículo

            if ($existeSuministro) {
                $errores[] = "El suministro de la herramienta '{$nombreArticulo}' ya existe para este ticket y visita.";
            } else {
                Suministro::create([
                    'idTickets' => $herramienta['idTickets'],
                    'idVisitas' => $herramienta['idVisitas'],
                    'idArticulos' => $herramienta['idArticulos'],
                    'cantidad' => $herramienta['cantidad'],
                    'tipo' => 'herramienta',
                ]);
            }
        }

        // Insertar suministros de productos
        foreach ($request->productos as $producto) {
            $existeSuministro = Suministro::where('idTickets', $producto['idTickets'])
                ->where('idVisitas', $producto['idVisitas'])
                ->where('idArticulos', $producto['idArticulos'])
                ->exists();

            $nombreArticulo = Articulo::find($producto['idArticulos'])->nombre;  // Obtener el nombre del artículo

            if ($existeSuministro) {
                $errores[] = "El suministro del producto '{$nombreArticulo}' ya existe para este ticket y visita.";
            } else {
                Suministro::create([
                    'idTickets' => $producto['idTickets'],
                    'idVisitas' => $producto['idVisitas'],
                    'idArticulos' => $producto['idArticulos'],
                    'cantidad' => $producto['cantidad'],
                    'tipo' => 'producto',
                ]);
            }
        }

        // Insertar suministros de repuestos
        foreach ($request->repuestos as $repuesto) {
            $existeSuministro = Suministro::where('idTickets', $repuesto['idTickets'])
                ->where('idVisitas', $repuesto['idVisitas'])
                ->where('idArticulos', $repuesto['idArticulos'])
                ->exists();

            $nombreArticulo = Articulo::find($repuesto['idArticulos'])->nombre;  // Obtener el nombre del artículo

            if ($existeSuministro) {
                $errores[] = "El suministro del repuesto '{$nombreArticulo}' ya existe para este ticket y visita.";
            } else {
                Suministro::create([
                    'idTickets' => $repuesto['idTickets'],
                    'idVisitas' => $repuesto['idVisitas'],
                    'idArticulos' => $repuesto['idArticulos'],
                    'cantidad' => $repuesto['cantidad'],
                    'tipo' => 'repuesto',
                ]);
            }
        }

        // Insertar suministros de insumos
        foreach ($request->insumos as $insumo) {
            $existeSuministro = Suministro::where('idTickets', $insumo['idTickets'])
                ->where('idVisitas', $insumo['idVisitas'])
                ->where('idArticulos', $insumo['idArticulos'])
                ->exists();

            $nombreArticulo = Articulo::find($insumo['idArticulos'])->nombre;  // Obtener el nombre del artículo

            if ($existeSuministro) {
                $errores[] = "El suministro del insumo '{$nombreArticulo}' ya existe para este ticket y visita.";
            } else {
                Suministro::create([
                    'idTickets' => $insumo['idTickets'],
                    'idVisitas' => $insumo['idVisitas'],
                    'idArticulos' => $insumo['idArticulos'],
                    'cantidad' => $insumo['cantidad'],
                    'tipo' => 'insumo',
                ]);
            }
        }

        // Si hay errores, devolverlos
        if (!empty($errores)) {
            return response()->json(['success' => false, 'errores' => $errores]);
        }

        // Si no hay errores, retornar éxito
        return response()->json(['success' => true]);
    }


    public function getSuministros(Request $request)
    {
        // Obtener los datos de la visita seleccionada usando el ID de la visita seleccionada
        $seleccionadaVisita = SeleccionarVisita::where('idselecionarvisita', $request->idseleccionvisita)->first();

        // Verificar si no se encontró la visita
        // if (!$seleccionadaVisita) {
        //     Log::error('Visita no encontrada', ['idseleccionvisita' => $request->idseleccionvisita]);
        //     return response()->json(['error' => 'Visita no encontrada'], 404);
        // }

        // Log para ver si encontramos la visita
        Log::info('Visita encontrada', ['idseleccionvisita' => $request->idseleccionvisita, 'visita' => $seleccionadaVisita]);

        $idTickets = $seleccionadaVisita->idTickets;
        $idVisitas = $seleccionadaVisita->idVisitas;

        // Obtener suministros para el ticket y la visita actuales
        $suministros = Suministro::where('idTickets', $idTickets)
            ->where('idVisitas', $idVisitas)
            ->get();

        // Log para verificar los suministros obtenidos
        Log::info('Suministros encontrados', ['suministros' => $suministros]);

        // Devolver los suministros existentes al frontend
        return response()->json($suministros);
    }




    public function editSoporte($id)
    {
        $usuario = Auth::user();
        $rol = $usuario->rol->nombre ?? 'Sin Rol';

        // Obtener la orden con relaciones
        $orden = Ticket::with(['marca', 'modelo', 'cliente', 'tecnico', 'tienda', 'estadoflujo', 'usuario'])
            ->findOrFail($id);
        $ticket = Ticket::with(['marca', 'modelo', 'cliente', 'tecnico', 'tienda', 'ticketflujo.estadoFlujo', 'usuario'])->findOrFail($id);

        // Obtener el idTickets
        $ticketId = $ticket->idTickets;
        $colorEstado = $orden->ticketflujo && $orden->ticketflujo->estadoFlujo ? $orden->ticketflujo->estadoFlujo->color : '#FFFFFF';  // color por defecto si no se encuentra

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
            'estadosFlujo',
            'colorEstado'
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
            ? 'helpdesk.soporte.edit'
            : 'helpdesk.levantamiento.edit';

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




    public function seleccionarVisitaLevantamiento(Request $request)
    {
        // Validar los datos que vienen del frontend
        $validated = $request->validate([
            'idTickets' => 'required|integer',
            'idVisitas' => 'required|integer',
            'vistaseleccionada' => 'required|string|max:255',
        ]);

        // Log de los datos validados
        Log::info('Datos validados en seleccionarVisitaLevantamiento:', $validated);

        // Buscar si ya existe un registro con el mismo idTickets
        $seleccionarVisita = SeleccionarVisita::where('idTickets', $validated['idTickets'])->first();

        // Log del resultado de la búsqueda
        if ($seleccionarVisita) {
            Log::info('Visita encontrada para actualizar:', ['idTickets' => $validated['idTickets'], 'idVisitas' => $validated['idVisitas']]);
        } else {
            Log::info('No se encontró visita, se creará un nuevo registro:', ['idTickets' => $validated['idTickets']]);
        }

        // Si el registro ya existe, actualizamos los campos
        if ($seleccionarVisita) {
            $seleccionarVisita->idVisitas = $validated['idVisitas'];
            $seleccionarVisita->vistaseleccionada = $validated['vistaseleccionada'];

            // Log antes de guardar
            Log::info('Actualizando visita:', ['idVisitas' => $seleccionarVisita->idVisitas, 'vistaseleccionada' => $seleccionarVisita->vistaseleccionada]);

            $seleccionarVisita->save();

            return response()->json(['success' => true, 'message' => 'Visita actualizada correctamente.']);
        }

        // Si no existe, creamos un nuevo registro
        $seleccionarVisita = new SeleccionarVisita();
        $seleccionarVisita->idTickets = $validated['idTickets'];
        $seleccionarVisita->idVisitas = $validated['idVisitas'];
        $seleccionarVisita->vistaseleccionada = $validated['vistaseleccionada'];

        // Log antes de guardar el nuevo registro
        Log::info('Guardando nueva visita:', ['idTickets' => $seleccionarVisita->idTickets, 'idVisitas' => $seleccionarVisita->idVisitas, 'vistaseleccionada' => $seleccionarVisita->vistaseleccionada]);

        // Guardar en la base de datos
        if ($seleccionarVisita->save()) {
            Log::info('Visita seleccionada guardada correctamente.');
            return response()->json(['success' => true, 'message' => 'Visita seleccionada guardada correctamente.']);
        } else {
            Log::error('Hubo un error al guardar la visita seleccionada.');
            return response()->json(['success' => false, 'message' => 'Hubo un error al guardar la visita seleccionada.']);
        }
    }



    public function verificarVisitaSeleccionadaLevantamiento($idVisita)
    {
        // Verifica si la visita está en la tabla 'seleccionarvisita'
        $visita = DB::table('seleccionarvisita')
            ->where('idVisitas', $idVisita)
            ->first();

        // Si la visita está seleccionada, devuelve true; de lo contrario, false
        return response()->json([
            'seleccionada' => $visita ? true : false
        ]);
    }
}
