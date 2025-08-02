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
use App\Models\Categoria;
use App\Models\CondicionesTicket;
use App\Models\Equipo;
use App\Models\SeleccionarVisita;
use App\Models\Suministro;
use App\Models\TicketFlujo;
use App\Models\Tipodocumento;
use App\Models\TipoEnvio;
use App\Models\TipoRecojo;
use App\Models\TransicionStatusTicket;
use Illuminate\Support\Facades\Validator;
use Spatie\Browsershot\Browsershot;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;
use App\Exports\OrdenesHelpdeskExport;
use App\Models\Fotostickest;

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
        $usuarios = Usuario::where('idTipoUsuario', 1)->get();
        $tiposTickets = Tipoticket::all();
        $clientes = Cliente::all();
        $tiendas = Tienda::all();
        $marcas = Marca::all();
        $modelos = Modelo::all();

        // Determinar la carpeta de vistas según el rol
        $carpetaVista = match ($rol) {
            'COORDINACION SMART' => 'smart-tv',
            'COORDINACION HELP DESK' => 'helpdesk',
            'INVITADO' => 'invitado',
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
        $usuarios = Usuario::where('idTipoUsuario', 1)->get();
        $tiposServicio = TipoServicio::all();
        $marcas = Marca::all();
        $modelos = Modelo::all();
        $tiposEnvio = TipoEnvio::all();
        $tiposRecojo = TipoRecojo::all();  // Recuperar todos los registros de la tabla
        $departamentos = json_decode(file_get_contents(public_path('ubigeos/departamentos.json')), true);
        $categorias = Categoria::all();

        $tiposDocumento = Tipodocumento::all();




        return view("tickets.ordenes-trabajo.helpdesk.create", compact(
            'clientesGenerales',
            'clientes',
            'tiendas',
            'usuarios',
            'tiposServicio',
            'departamentos',
            'marcas',
            'categorias',
            'modelos',
            'tiposEnvio',
            'tiposRecojo',
            'tiposDocumento'
        ));
    }






    public function storehelpdesk(Request $request)
    {
        try {
            Log::debug('Datos recibidos en storehelpdesk:', $request->all());

            // Validación
            $validatedData = $request->validate([
                'numero_ticket' => 'required|string|max:255|unique:tickets,numero_ticket',
                'idClienteGeneral' => 'required|integer|exists:clientegeneral,idClienteGeneral',
                'idCliente' => 'required|integer|exists:cliente,idCliente',
                'idTienda' => 'required|integer|exists:tienda,idTienda',
                'tipoServicio' => 'required|integer|exists:tiposervicio,idTipoServicio',
                'fallaReportada' => 'required|string|max:255',
                'esEnvio' => 'nullable|boolean',
                'idTecnico' => 'nullable|integer|exists:usuarios,idUsuario',
                'tipoRecojo' => 'nullable|integer|exists:tiporecojo,idtipoRecojo',
                'tipoEnvio' => 'nullable|integer|exists:tipoenvio,idtipoenvio',
                'nombreTecnicoEnvio' => 'nullable|array',
                'dniTecnicoEnvio' => 'nullable|array',
                'agencia' => 'nullable|string|max:255',

                // Validación solo si el tipoServicio es 6
                'tipoProducto' => 'required_if:tipoServicio,6|integer|exists:categoria,idCategoria',
                'marca' => 'required_if:tipoServicio,6|integer|exists:marca,idMarca',
                'modelo' => 'required_if:tipoServicio,6|integer|exists:modelo,idModelo',
                'serieRetirar' => 'nullable|string|max:255',
                'observaciones' => 'nullable|string|max:1000',
                'nrmcotizacion' => 'nullable|string|max:1000',

            ]);

            Log::debug('Datos validados:', $validatedData);

            // Crear el ticket
            $ticket = Ticket::create([
                'numero_ticket' => $validatedData['numero_ticket'],
                'idClienteGeneral' => $validatedData['idClienteGeneral'],
                'idCliente' => $validatedData['idCliente'],
                'idTienda' => $validatedData['idTienda'],
                'tipoServicio' => $validatedData['tipoServicio'],
                'idUsuario' => auth()->id(),
                'fallaReportada' => $validatedData['fallaReportada'],
                'nrmcotizacion' => $validatedData['nrmcotizacion'],
                'fecha_creacion' => now(),
                'idTipotickets' => 2,
                'envio' => $validatedData['esEnvio'] ? 1 : 0,
            ]);

            Log::debug('Orden de trabajo creada correctamente.');

            // Guardar técnicos si es envío
            if ($validatedData['esEnvio']) {
                foreach ($validatedData['nombreTecnicoEnvio'] as $index => $nombre) {
                    $dni = $validatedData['dniTecnicoEnvio'][$index];

                    DB::table('ticket_receptor')->insert([
                        'idTickets' => $ticket->idTickets,
                        'nombre' => $nombre,
                        'dni' => $dni,
                    ]);
                }

                Log::info('Datos de técnicos de recojo guardados correctamente');
            }

            // Solo crear el flujo normal si tipoServicio NO es 6
            if ($validatedData['tipoServicio'] != 6) {
                $idEstadflujo = $validatedData['esEnvio'] ? 30 : 1;

                $ticketFlujoId = DB::table('ticketflujo')->insertGetId([
                    'idTicket' => $ticket->idTickets,
                    'idEstadflujo' => $idEstadflujo,
                    'idUsuario' => auth()->id(),
                    'fecha_creacion' => now(),
                ]);

                $ticket->idTicketFlujo = $ticketFlujoId;
                $ticket->save();

                Log::info('Ticket actualizado con idTicketFlujo', ['ticket' => $ticket]);
            }

            // Guardar datos de envío si es necesario
            if ($validatedData['esEnvio']) {
                DB::table('datos_envio')->insert([
                    'idTickets' => $ticket->idTickets,
                    'tipoRecojo' => $validatedData['tipoRecojo'],
                    'tipoEnvio' => $validatedData['tipoEnvio'],
                    'idUsuario' => $validatedData['idTecnico'],
                    'agencia' => $validatedData['agencia'],
                    'tipo' => 1
                ]);
                Log::info('Datos de envío guardados correctamente');
            }

            // Crear visita y flujo adicional si tipoServicio es 6 (Laboratorio)
            if ($validatedData['tipoServicio'] == 6) {
                // Crear la visita
                $idVisita = DB::table('visitas')->insertGetId([
                    'nombre' => 'LABORATORIO',
                    'fecha_programada' => now(),
                    'fecha_asignada' => now(),
                    'fechas_desplazamiento' => now(),
                    'fecha_llegada' => now(),
                    'fecha_inicio' => now(),
                    'fecha_final' => now(),
                    'fecha_inicio_hora' => now(),
                    'fecha_final_hora' => now(),
                    'estado' => 1,
                    'idTickets' => $ticket->idTickets,
                    'idUsuario' => auth()->id(),
                    'necesita_apoyo' => 0,
                    'tipoServicio' => 6,
                    'visto' => 0,
                    'recojo' => 0,
                    'estadovisita' => 0,
                    'celularclientetienda' => null,
                    'dniclientetienda' => null,
                    'nombreclientetienda' => null
                ]);

                Log::info('Visita de laboratorio creada automáticamente.');

                // Crear flujo adicional con estado 2
                $ticketFlujoEstado2 = DB::table('ticketflujo')->insertGetId([
                    'idTicket' => $ticket->idTickets,
                    'idEstadflujo' => 10,
                    'idUsuario' => auth()->id(),
                    'fecha_creacion' => now(),
                ]);

                // Actualizar el ticket con el nuevo flujo (estado 2)
                $ticket->idTicketFlujo = $ticketFlujoEstado2;
                $ticket->save();

                Log::info('Ticket actualizado con segundo idTicketFlujo (estado 2)', [
                    'ticket' => $ticket->idTickets,
                    'nuevo_flujo' => $ticketFlujoEstado2
                ]);

                // Ahora, guardamos los datos en la tabla equipos
                DB::table('equipos')->insert([
                    'nserie' => $validatedData['serieRetirar'],
                    'modalidad' => 'Instalación',  // Aquí puedes poner la modalidad que necesites, por ejemplo 'Laboratorio'
                    'idTickets' => $ticket->idTickets,
                    'idModelo' => $validatedData['modelo'],
                    'idMarca' => $validatedData['marca'],
                    'idCategoria' => $validatedData['tipoProducto'],
                    'idVisitas' => $idVisita,  // Usamos el idVisita generado
                    'observaciones' => $validatedData['observaciones'],
                ]);

                Log::info('Datos del equipo guardados correctamente');
            }

            // Redirección según tipoServicio
            if ($validatedData['tipoServicio'] == 2) {
                return redirect()->route('ordenes.helpdesk.levantamiento.edit', ['id' => $ticket->idTickets])
                    ->with('success', 'Orden de trabajo creada correctamente (Levantamiento de Información).');
            } elseif ($validatedData['tipoServicio'] == 1) {
                return redirect()->route('ordenes.helpdesk.soporte.edit', ['id' => $ticket->idTickets])
                    ->with('success', 'Orden de trabajo creada correctamente (Soporte On Site).');
            } elseif ($validatedData['tipoServicio'] == 5) {
                return redirect()->route('ordenes.helpdesk.ejecucion.edit', ['id' => $ticket->idTickets])
                    ->with('success', 'Orden de trabajo creada correctamente (Ejecucion).');
            } elseif ($validatedData['tipoServicio'] == 6) {
                return redirect()->route('ordenes.helpdesk.laboratorio.edit', ['id' => $ticket->idTickets])
                    ->with('success', 'Orden de trabajo creada correctamente (Laboratorio).');
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

        $estadosOTS = DB::table('estado_ots')
            ->whereIn('idEstadoots', [2, 3, 4, 6])
            ->get();

        $tipoUsuario = null;  // Inicializamos la variable para el tipo de usuario
        $categorias = Categoria::all();

        // Buscar en la tabla tickets el idTicketFlujo correspondiente al ticket
        $ticket = DB::table('tickets')->where('idTickets', $id)->first();
        // Obtener listas necesarias para el formulario
        $clientes = Cliente::all();
        $clientesGenerales = ClienteGeneral::all();
        $estadosFlujo = EstadoFlujo::all();
        $modelos = Modelo::all();
        $tiendas = Tienda::all();
        $marcas = Marca::all();
        $usuarios = Usuario::all();
        $tiposServicio = TipoServicio::all();

        $ejecutor = Usuario::find($orden->ejecutor);



        $tipoUsuario = null;  // Inicializamos la variable para el tipo de usuario

        // Consulta para obtener el idUsuario de la visita seleccionada para ese ticket
        $idVisitaSeleccionada = DB::table('seleccionarvisita')
            ->where('idTickets', $ticketId)  // Filtro por ticketId
            ->value('idVisitas');  // Obtenemos el idVisitas de la visita seleccionada para ese ticket

        Log::info('Visita seleccionada, idVisita: ' . $idVisitaSeleccionada); // Log de la visita seleccionada

        // Si se encuentra una visita seleccionada
        if ($idVisitaSeleccionada) {

            // Obtener el idUsuario de la visita seleccionada
            $idUsuario = DB::table('visitas')
                ->where('idTickets', $ticketId)  // Filtro por ticketId
                ->where('idVisitas', $idVisitaSeleccionada)  // Filtro por idVisita seleccionada
                ->value('idUsuario');  // Obtenemos el idUsuario de esa visita

            Log::info('idUsuario obtenido de la visita seleccionada: ' . $idUsuario); // Log de idUsuario

            // Si encontramos un idUsuario, obtener el idTipoUsuario del usuario
            if ($idUsuario) {
                $tipoUsuario = DB::table('usuarios')
                    ->where('idUsuario', $idUsuario)  // Filtro por idUsuario
                    ->value('idTipoUsuario');  // Obtenemos el idTipoUsuario

                Log::info('Tipo de usuario obtenido: ' . $tipoUsuario);  // Log del tipo de usuario
            } else {
                Log::warning('No se encontró un idUsuario para la visita seleccionada.'); // Log si no se encuentra el idUsuario
            }
        } else {
            Log::warning('No se encontró una visita seleccionada para el ticket: ' . $ticketId); // Log si no se encuentra una visita seleccionada
        }

        // Puedes agregar un log final para revisar el valor de $tipoUsuario
        Log::info('Valor final de tipoUsuario: ' . $tipoUsuario);

        $encargado = Usuario::where('idTipoUsuario', 1)->get();
        // Verificar si existe un flujo con idEstadflujo = 4
        $flujo = TicketFlujo::where('idTicket', $ticketId)
            ->where('idEstadflujo', 4)
            ->first();
        $existeFlujo4 = $flujo ? true : false;  // Si existe flujo con idEstadflujo 4, establecer como verdadero


        // Verificar que encontramos el ticket y que tiene un idTicketFlujo
        if ($ticket) {
            $idTicketFlujo = $ticket->idTicketFlujo;  // Obtener el idTicketFlujo del ticket

            // Buscar en ticketflujo el idEstadflujo correspondiente al idTicketFlujo
            $ticketFlujo = DB::table('ticketflujo')->where('idTicketFlujo', $idTicketFlujo)->first();

            // Verifica que existe el ticketFlujo y su idEstadflujo
            if ($ticketFlujo) {
                $idEstadflujo = $ticketFlujo->idEstadflujo;  // Obtener el idEstadflujo del ticketflujo

                // Si el idEstadflujo es 4, no mostrar ningún estado de flujo
                if ($idEstadflujo == 4) {
                    $estadosFlujo = collect();  // Asignar una colección vacía si es 4 (no mostrar estados)
                } elseif ($idEstadflujo == 28) {
                    // Si el idEstadflujo es 3, solo mostrar los estados con idEstadflujo 4
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 25)  // Solo obtener el estado con idEstadflujo 4
                        ->get();
                } elseif ($idEstadflujo == 25) {
                    // Si el idEstadflujo es 2, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [27, 26])  // Obtener los estados con idEstadflujo 3 y 4
                        ->get();
                } elseif ($idEstadflujo == 26) {
                    // Si el idEstadflujo es 3, solo mostrar los estados con idEstadflujo 4
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 27)  // Solo obtener el estado con idEstadflujo 4
                        ->get();
                } elseif ($idEstadflujo == 12) {
                    // Si el id Estado flujo es 12 tiene que salir
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 18)
                        ->get();
                } elseif ($idEstadflujo == 18) {
                    // Si el id Estado flujo es 12 tiene que salir
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 3)
                        ->get();
                } elseif ($idEstadflujo == 10) {
                    // Si el idEstadflujo es 3, solo mostrar los estados con idEstadflujo 4
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 11)  // Solo obtener el estado con idEstadflujo 4
                        ->get();
                } elseif ($idEstadflujo == 1) {
                    // Si el ticket tiene un idTicketFlujo con idEstadflujo = 1, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [3, 8])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 9) {
                    // Si el idEstadflujo del ticketflujo es 9, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 3)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 8) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [3, 1])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 31) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 24)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 24) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 29)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 29) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 32)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 32) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 27)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 27) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 4)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 30) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 28)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } else {
                    // Si no tiene idEstadflujo = 1, 3, 8 o 9, verificar si es 6 o 7
                    if (in_array($idEstadflujo, [6, 7])) {
                        // Si tiene idEstadflujo 6 o 7, solo traer los estados con idEstadflujo 4
                        $estadosFlujo = DB::table('estado_flujo')
                            ->whereIn('idEstadflujo', [4, 31])  // Solo obtener el estado con idEstadflujo 3
                            ->get();
                    } else {
                        // Si no cumple ninguna de las condiciones anteriores, mostrar todos los estados
                        $estadosFlujo = DB::table('estado_flujo')->get();
                    }
                }
            }
        }

        $tecnicos_apoyo = Usuario::where('idTipoUsuario', 1)->get();

        $visita = DB::table('visitas')
            ->join('usuarios', 'visitas.idUsuario', '=', 'usuarios.idUsuario')
            ->join('tickets', 'visitas.idTickets', '=', 'tickets.idTickets') // Unimos con la tabla tickets
            ->where('visitas.idTickets', $ticketId)
            ->select(
                'usuarios.Nombre as usuarios_nombre',
                'usuarios.apellidoPaterno as usuarios_apellidoPaterno',
                'visitas.*',
                'tickets.numero_ticket' // Seleccionamos el numero_ticket de la tabla tickets
            )
            ->first();

        $visitaId = $visita ? $visita->idVisitas : null; // Si no hay visita, será null


        // Verificar si la visita seleccionada está registrada en la tabla seleccionarvisita
        $visitaSeleccionada = DB::table('seleccionarvisita')
            ->where('idTickets', $ticketId)
            ->where('idVisitas', $visitaId)
            ->exists(); // Devuelve true si la visita está seleccionada, false si no



        // Verificar si existe una transición en transicion_status_ticket con idEstadoots = 4
        $transicionExistente = DB::table('transicion_status_ticket')
            ->where('idTickets', $ticketId)
            ->where('idVisitas', $visitaId)
            ->where('idEstadoots', 4)
            ->exists(); // Devuelve true si existe, false si no


        // Verificar si existe un flujo con idEstadflujo = 25
        $flujo = TicketFlujo::where('idTicket', $ticketId)
            ->where('idEstadflujo', 25)
            ->first();

        // dd($flujo); // Verifica si devuelve el registro correcto

        $existeFlujo25 = $flujo ? true : false;  // Si existe flujo con idEstadflujo 4, establecer como verdadero


        // Verificar si existe un flujo con idEstadflujo = 31
        $flujo = TicketFlujo::where('idTicket', $ticketId)
            ->where('idEstadflujo', 31)
            ->first();

        // dd($flujo); // Verifica si devuelve el registro correcto

        $existeFlujo31 = $flujo ? true : false;  // Si existe flujo con idEstadflujo 4, establecer como verdadero

        $tiposEnvio = TipoEnvio::all();
        $tiposRecojo = TipoRecojo::all();  // Recuperar todos los registros de la tabla


        // Obtener la última visita para un ticket
        $ultimaVisita = DB::table('visitas')
            ->where('idTickets', $ticketId)  // Filtrar por el id del ticket
            ->orderBy('idVisitas', 'desc')  // Ordenar por idVisitas (asumido como incremental)
            ->first();  // Obtener solo la última visita

        // Verificar si la última visita tiene 'estadovisita' igual a 1 o null/0
        if ($ultimaVisita) {
            if ($ultimaVisita->estadovisita == 1) {
                // La última visita tiene 'estadovisita' igual a 1
                $ultimaVisitaConEstado1 = true;
            } elseif ($ultimaVisita->estadovisita === null || $ultimaVisita->estadovisita == 0) {
                // La última visita tiene 'estadovisita' igual a null o 0
                $ultimaVisitaConEstado1 = false;
            }
        } else {
            // No se encontraron visitas para este ticket
            $ultimaVisitaConEstado1 = true;  // Aquí cambiamos a true para que el botón se muestre si no hay visitas.
        }


        // Obtener el valor de estadovisita para la visita seleccionada
        $estadovisita = DB::table('visitas')
            ->where('idTickets', $ticketId)  // Filtro por ticketId
            ->where('idVisitas', $idVisitaSeleccionada)  // Filtro por idVisitas seleccionada
            ->value('estadovisita');  // Obtenemos el valor de estadovisita
        Log::info('Estado de la visita: ' . $estadovisita);  // Log del valor de estadovisita

        return view("tickets.ordenes-trabajo.helpdesk.edit", compact(
            'orden',
            'usuarios',
            'tiposServicio',
            'modelos',
            'clientes',
            'clientesGenerales',
            'tiendas',
            'ticketId',
            'marcas',
            'ticket',
            'estadosFlujo',
            'colorEstado',
            'existeFlujo4',
            'encargado',
            'tecnicos_apoyo',
            'visitaId',
            'transicionExistente',
            'estadosOTS',
            'tipoUsuario',
            'id',
            'idVisitaSeleccionada',
            'categorias',
            'existeFlujo25',
            'ejecutor', // Asegúrate de pasar la variable ejecutor
            'existeFlujo31',
            'tiposEnvio',
            'tiposRecojo',
            'ultimaVisitaConEstado1',
            'estadovisita'


        ));
    }




    //EJECUCION
    public function editejecucion($id)
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

        $estadosOTS = DB::table('estado_ots')
            ->whereIn('idEstadoots', [2, 3, 4, 6])
            ->get();

        $tipoUsuario = null;  // Inicializamos la variable para el tipo de usuario
        $categorias = Categoria::all();

        // Buscar en la tabla tickets el idTicketFlujo correspondiente al ticket
        $ticket = DB::table('tickets')->where('idTickets', $id)->first();
        // Obtener listas necesarias para el formulario
        $clientes = Cliente::all();
        $clientesGenerales = ClienteGeneral::all();
        $estadosFlujo = EstadoFlujo::all();
        $modelos = Modelo::all();
        $tiendas = Tienda::all();
        $marcas = Marca::all();
        $usuarios = Usuario::all();
        $tiposServicio = TipoServicio::all();

        $ejecutor = Usuario::find($orden->ejecutor);



        $tipoUsuario = null;  // Inicializamos la variable para el tipo de usuario

        // Consulta para obtener el idUsuario de la visita seleccionada para ese ticket
        $idVisitaSeleccionada = DB::table('seleccionarvisita')
            ->where('idTickets', $ticketId)  // Filtro por ticketId
            ->value('idVisitas');  // Obtenemos el idVisitas de la visita seleccionada para ese ticket

        Log::info('Visita seleccionada, idVisita: ' . $idVisitaSeleccionada); // Log de la visita seleccionada

        // Si se encuentra una visita seleccionada
        if ($idVisitaSeleccionada) {

            // Obtener el idUsuario de la visita seleccionada
            $idUsuario = DB::table('visitas')
                ->where('idTickets', $ticketId)  // Filtro por ticketId
                ->where('idVisitas', $idVisitaSeleccionada)  // Filtro por idVisita seleccionada
                ->value('idUsuario');  // Obtenemos el idUsuario de esa visita

            Log::info('idUsuario obtenido de la visita seleccionada: ' . $idUsuario); // Log de idUsuario

            // Si encontramos un idUsuario, obtener el idTipoUsuario del usuario
            if ($idUsuario) {
                $tipoUsuario = DB::table('usuarios')
                    ->where('idUsuario', $idUsuario)  // Filtro por idUsuario
                    ->value('idTipoUsuario');  // Obtenemos el idTipoUsuario

                Log::info('Tipo de usuario obtenido: ' . $tipoUsuario);  // Log del tipo de usuario
            } else {
                Log::warning('No se encontró un idUsuario para la visita seleccionada.'); // Log si no se encuentra el idUsuario
            }
        } else {
            Log::warning('No se encontró una visita seleccionada para el ticket: ' . $ticketId); // Log si no se encuentra una visita seleccionada
        }

        // Puedes agregar un log final para revisar el valor de $tipoUsuario
        Log::info('Valor final de tipoUsuario: ' . $tipoUsuario);

        $encargado = Usuario::where('idTipoUsuario', 1)->get();
        // Verificar si existe un flujo con idEstadflujo = 4
        $flujo = TicketFlujo::where('idTicket', $ticketId)
            ->where('idEstadflujo', 4)
            ->first();
        $existeFlujo4 = $flujo ? true : false;  // Si existe flujo con idEstadflujo 4, establecer como verdadero


        // Verificar que encontramos el ticket y que tiene un idTicketFlujo
        if ($ticket) {
            $idTicketFlujo = $ticket->idTicketFlujo;  // Obtener el idTicketFlujo del ticket

            // Buscar en ticketflujo el idEstadflujo correspondiente al idTicketFlujo
            $ticketFlujo = DB::table('ticketflujo')->where('idTicketFlujo', $idTicketFlujo)->first();

            // Verifica que existe el ticketFlujo y su idEstadflujo
            if ($ticketFlujo) {
                $idEstadflujo = $ticketFlujo->idEstadflujo;  // Obtener el idEstadflujo del ticketflujo

                // Si el idEstadflujo es 4, no mostrar ningún estado de flujo
                if ($idEstadflujo == 4) {
                    $estadosFlujo = collect();  // Asignar una colección vacía si es 4 (no mostrar estados)
                } elseif ($idEstadflujo == 28) {
                    // Si el idEstadflujo es 3, solo mostrar los estados con idEstadflujo 4
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 25)  // Solo obtener el estado con idEstadflujo 4
                        ->get();
                } elseif ($idEstadflujo == 25) {
                    // Si el idEstadflujo es 2, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [27, 26])  // Obtener los estados con idEstadflujo 3 y 4
                        ->get();
                } elseif ($idEstadflujo == 26) {
                    // Si el idEstadflujo es 3, solo mostrar los estados con idEstadflujo 4
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 27)  // Solo obtener el estado con idEstadflujo 4
                        ->get();
                } elseif ($idEstadflujo == 12) {
                    // Si el id Estado flujo es 12 tiene que salir
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 18)
                        ->get();
                } elseif ($idEstadflujo == 18) {
                    // Si el id Estado flujo es 12 tiene que salir
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 3)
                        ->get();
                } elseif ($idEstadflujo == 10) {
                    // Si el idEstadflujo es 3, solo mostrar los estados con idEstadflujo 4
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 11)  // Solo obtener el estado con idEstadflujo 4
                        ->get();
                } elseif ($idEstadflujo == 1) {
                    // Si el ticket tiene un idTicketFlujo con idEstadflujo = 1, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [3, 8])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 9) {
                    // Si el idEstadflujo del ticketflujo es 9, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 3)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 8) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [3, 1])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 31) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 24)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 24) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 29)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 29) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 32)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 32) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 27)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 27) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 4)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 30) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 28)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } else {
                    // Si no tiene idEstadflujo = 1, 3, 8 o 9, verificar si es 6 o 7
                    if (in_array($idEstadflujo, [6, 7])) {
                        // Si tiene idEstadflujo 6 o 7, solo traer los estados con idEstadflujo 4
                        $estadosFlujo = DB::table('estado_flujo')
                            ->whereIn('idEstadflujo', [4, 31])  // Solo obtener el estado con idEstadflujo 3
                            ->get();
                    } else {
                        // Si no cumple ninguna de las condiciones anteriores, mostrar todos los estados
                        $estadosFlujo = DB::table('estado_flujo')->get();
                    }
                }
            }
        }

        $tecnicos_apoyo = Usuario::where('idTipoUsuario', 1)->get();

        $visita = DB::table('visitas')
            ->join('usuarios', 'visitas.idUsuario', '=', 'usuarios.idUsuario')
            ->join('tickets', 'visitas.idTickets', '=', 'tickets.idTickets') // Unimos con la tabla tickets
            ->where('visitas.idTickets', $ticketId)
            ->select(
                'usuarios.Nombre as usuarios_nombre',
                'usuarios.apellidoPaterno as usuarios_apellidoPaterno',
                'visitas.*',
                'tickets.numero_ticket' // Seleccionamos el numero_ticket de la tabla tickets
            )
            ->first();

        $visitaId = $visita ? $visita->idVisitas : null; // Si no hay visita, será null


        // Verificar si la visita seleccionada está registrada en la tabla seleccionarvisita
        $visitaSeleccionada = DB::table('seleccionarvisita')
            ->where('idTickets', $ticketId)
            ->where('idVisitas', $visitaId)
            ->exists(); // Devuelve true si la visita está seleccionada, false si no



        // Verificar si existe una transición en transicion_status_ticket con idEstadoots = 4
        $transicionExistente = DB::table('transicion_status_ticket')
            ->where('idTickets', $ticketId)
            ->where('idVisitas', $visitaId)
            ->where('idEstadoots', 4)
            ->exists(); // Devuelve true si existe, false si no


        // Verificar si existe un flujo con idEstadflujo = 25
        $flujo = TicketFlujo::where('idTicket', $ticketId)
            ->where('idEstadflujo', 25)
            ->first();

        // dd($flujo); // Verifica si devuelve el registro correcto

        $existeFlujo25 = $flujo ? true : false;  // Si existe flujo con idEstadflujo 4, establecer como verdadero


        // Verificar si existe un flujo con idEstadflujo = 31
        $flujo = TicketFlujo::where('idTicket', $ticketId)
            ->where('idEstadflujo', 31)
            ->first();

        // dd($flujo); // Verifica si devuelve el registro correcto

        $existeFlujo31 = $flujo ? true : false;  // Si existe flujo con idEstadflujo 4, establecer como verdadero

        $tiposEnvio = TipoEnvio::all();
        $tiposRecojo = TipoRecojo::all();  // Recuperar todos los registros de la tabla


        // Obtener la última visita para un ticket
        $ultimaVisita = DB::table('visitas')
            ->where('idTickets', $ticketId)  // Filtrar por el id del ticket
            ->orderBy('idVisitas', 'desc')  // Ordenar por idVisitas (asumido como incremental)
            ->first();  // Obtener solo la última visita

        // Verificar si la última visita tiene 'estadovisita' igual a 1 o null/0
        if ($ultimaVisita) {
            if ($ultimaVisita->estadovisita == 1) {
                // La última visita tiene 'estadovisita' igual a 1
                $ultimaVisitaConEstado1 = true;
            } elseif ($ultimaVisita->estadovisita === null || $ultimaVisita->estadovisita == 0) {
                // La última visita tiene 'estadovisita' igual a null o 0
                $ultimaVisitaConEstado1 = false;
            }
        } else {
            // No se encontraron visitas para este ticket
            $ultimaVisitaConEstado1 = true;  // Aquí cambiamos a true para que el botón se muestre si no hay visitas.
        }


        // Obtener el valor de estadovisita para la visita seleccionada
        $estadovisita = DB::table('visitas')
            ->where('idTickets', $ticketId)  // Filtro por ticketId
            ->where('idVisitas', $idVisitaSeleccionada)  // Filtro por idVisitas seleccionada
            ->value('estadovisita');  // Obtenemos el valor de estadovisita
        Log::info('Estado de la visita: ' . $estadovisita);  // Log del valor de estadovisita

        return view("tickets.ordenes-trabajo.helpdesk.edit", compact(
            'orden',
            'usuarios',
            'tiposServicio',
            'modelos',
            'clientes',
            'clientesGenerales',
            'tiendas',
            'ticketId',
            'marcas',
            'ticket',
            'estadosFlujo',
            'colorEstado',
            'existeFlujo4',
            'encargado',
            'tecnicos_apoyo',
            'visitaId',
            'transicionExistente',
            'estadosOTS',
            'tipoUsuario',
            'id',
            'idVisitaSeleccionada',
            'categorias',
            'existeFlujo25',
            'ejecutor', // Asegúrate de pasar la variable ejecutor
            'existeFlujo31',
            'tiposEnvio',
            'tiposRecojo',
            'ultimaVisitaConEstado1',
            'estadovisita'


        ));
    }






    public function ediLaboratorio($id)
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

        $estadosOTS = DB::table('estado_ots')
            ->whereIn('idEstadoots', [2, 4, 5, 3])
            ->get();

        $tipoUsuario = null;  // Inicializamos la variable para el tipo de usuario
        $categorias = Categoria::all();

        // Buscar en la tabla tickets el idTicketFlujo correspondiente al ticket
        $ticket = DB::table('tickets')->where('idTickets', $id)->first();
        // Obtener listas necesarias para el formulario
        $clientes = Cliente::all();
        $clientesGenerales = ClienteGeneral::all();
        $estadosFlujo = EstadoFlujo::all();
        $modelos = Modelo::all();
        $tiendas = Tienda::all();
        $marcas = Marca::all();
        $usuarios = Usuario::all();
        $tiposServicio = TipoServicio::all();

        $ejecutor = Usuario::find($orden->ejecutor);



        $tipoUsuario = null;  // Inicializamos la variable para el tipo de usuario

        // Consulta para obtener el idUsuario de la visita seleccionada para ese ticket
        $idVisitaSeleccionada = DB::table('seleccionarvisita')
            ->where('idTickets', $ticketId)  // Filtro por ticketId
            ->value('idVisitas');  // Obtenemos el idVisitas de la visita seleccionada para ese ticket

        Log::info('Visita seleccionada, idVisita: ' . $idVisitaSeleccionada); // Log de la visita seleccionada

        // Si se encuentra una visita seleccionada
        if ($idVisitaSeleccionada) {

            // Obtener el idUsuario de la visita seleccionada
            $idUsuario = DB::table('visitas')
                ->where('idTickets', $ticketId)  // Filtro por ticketId
                ->where('idVisitas', $idVisitaSeleccionada)  // Filtro por idVisita seleccionada
                ->value('idUsuario');  // Obtenemos el idUsuario de esa visita

            Log::info('idUsuario obtenido de la visita seleccionada: ' . $idUsuario); // Log de idUsuario

            // Si encontramos un idUsuario, obtener el idTipoUsuario del usuario
            if ($idUsuario) {
                $tipoUsuario = DB::table('usuarios')
                    ->where('idUsuario', $idUsuario)  // Filtro por idUsuario
                    ->value('idTipoUsuario');  // Obtenemos el idTipoUsuario

                Log::info('Tipo de usuario obtenido: ' . $tipoUsuario);  // Log del tipo de usuario
            } else {
                Log::warning('No se encontró un idUsuario para la visita seleccionada.'); // Log si no se encuentra el idUsuario
            }
        } else {
            Log::warning('No se encontró una visita seleccionada para el ticket: ' . $ticketId); // Log si no se encuentra una visita seleccionada
        }

        // Puedes agregar un log final para revisar el valor de $tipoUsuario
        Log::info('Valor final de tipoUsuario: ' . $tipoUsuario);

        $encargado = Usuario::where('idTipoUsuario', 1)->get();
        // Verificar si existe un flujo con idEstadflujo = 4
        $flujo = TicketFlujo::where('idTicket', $ticketId)
            ->where('idEstadflujo', 4)
            ->first();
        $existeFlujo4 = $flujo ? true : false;  // Si existe flujo con idEstadflujo 4, establecer como verdadero


        // Verificar que encontramos el ticket y que tiene un idTicketFlujo
        if ($ticket) {
            $idTicketFlujo = $ticket->idTicketFlujo;  // Obtener el idTicketFlujo del ticket

            // Buscar en ticketflujo el idEstadflujo correspondiente al idTicketFlujo
            $ticketFlujo = DB::table('ticketflujo')->where('idTicketFlujo', $idTicketFlujo)->first();

            // Verifica que existe el ticketFlujo y su idEstadflujo
            if ($ticketFlujo) {
                $idEstadflujo = $ticketFlujo->idEstadflujo;  // Obtener el idEstadflujo del ticketflujo

                // Si el idEstadflujo es 4, no mostrar ningún estado de flujo
                if ($idEstadflujo == 4) {
                    $estadosFlujo = collect();  // Asignar una colección vacía si es 4 (no mostrar estados)
                } elseif ($idEstadflujo == 28) {
                    // Si el idEstadflujo es 3, solo mostrar los estados con idEstadflujo 4
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 25)  // Solo obtener el estado con idEstadflujo 4
                        ->get();
                } elseif ($idEstadflujo == 25) {
                    // Si el idEstadflujo es 2, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [27, 26])  // Obtener los estados con idEstadflujo 3 y 4
                        ->get();
                } elseif ($idEstadflujo == 26) {
                    // Si el idEstadflujo es 3, solo mostrar los estados con idEstadflujo 4
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 27)  // Solo obtener el estado con idEstadflujo 4
                        ->get();
                } elseif ($idEstadflujo == 12) {
                    // Si el id Estado flujo es 12 tiene que salir
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 18)
                        ->get();
                } elseif ($idEstadflujo == 18) {
                    // Si el id Estado flujo es 12 tiene que salir
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 3)
                        ->get();
                } elseif ($idEstadflujo == 10) {
                    // Si el idEstadflujo es 3, solo mostrar los estados con idEstadflujo 4
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 11)  // Solo obtener el estado con idEstadflujo 4
                        ->get();
                } elseif ($idEstadflujo == 1) {
                    // Si el ticket tiene un idTicketFlujo con idEstadflujo = 1, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [3, 8])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 9) {
                    // Si el idEstadflujo del ticketflujo es 9, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 3)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 8) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [3, 1])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 31) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 24)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 24) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 29)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 29) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 32)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 32) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 27)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 27) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 4)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 30) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 28)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } else {
                    // Si no tiene idEstadflujo = 1, 3, 8 o 9, verificar si es 6 o 7
                    if (in_array($idEstadflujo, [6, 7])) {
                        // Si tiene idEstadflujo 6 o 7, solo traer los estados con idEstadflujo 4
                        $estadosFlujo = DB::table('estado_flujo')
                            ->whereIn('idEstadflujo', [4, 31])  // Solo obtener el estado con idEstadflujo 3
                            ->get();
                    } else {
                        // Si no cumple ninguna de las condiciones anteriores, mostrar todos los estados
                        $estadosFlujo = DB::table('estado_flujo')->get();
                    }
                }
            }
        }

        $tecnicos_apoyo = Usuario::where('idTipoUsuario', 1)->get();

        $visita = DB::table('visitas')
            ->join('usuarios', 'visitas.idUsuario', '=', 'usuarios.idUsuario')
            ->join('tickets', 'visitas.idTickets', '=', 'tickets.idTickets') // Unimos con la tabla tickets
            ->where('visitas.idTickets', $ticketId)
            ->select(
                'usuarios.Nombre as usuarios_nombre',
                'usuarios.apellidoPaterno as usuarios_apellidoPaterno',
                'visitas.*',
                'tickets.numero_ticket' // Seleccionamos el numero_ticket de la tabla tickets
            )
            ->first();

        $visitaId = $visita ? $visita->idVisitas : null; // Si no hay visita, será null


        // Verificar si la visita seleccionada está registrada en la tabla seleccionarvisita
        $visitaSeleccionada = DB::table('seleccionarvisita')
            ->where('idTickets', $ticketId)
            ->where('idVisitas', $visitaId)
            ->exists(); // Devuelve true si la visita está seleccionada, false si no



        // Verificar si existe una transición en transicion_status_ticket con idEstadoots = 4
        $transicionExistente = DB::table('transicion_status_ticket')
            ->where('idTickets', $ticketId)
            ->where('idVisitas', $visitaId)
            ->where('idEstadoots', 4)
            ->exists(); // Devuelve true si existe, false si no


        // Verificar si existe un flujo con idEstadflujo = 25
        $flujo = TicketFlujo::where('idTicket', $ticketId)
            ->where('idEstadflujo', 25)
            ->first();

        // dd($flujo); // Verifica si devuelve el registro correcto

        $existeFlujo25 = $flujo ? true : false;  // Si existe flujo con idEstadflujo 4, establecer como verdadero


        // Verificar si existe un flujo con idEstadflujo = 31
        $flujo = TicketFlujo::where('idTicket', $ticketId)
            ->where('idEstadflujo', 31)
            ->first();

        // dd($flujo); // Verifica si devuelve el registro correcto

        $existeFlujo31 = $flujo ? true : false;  // Si existe flujo con idEstadflujo 4, establecer como verdadero

        $tiposEnvio = TipoEnvio::all();
        $tiposRecojo = TipoRecojo::all();  // Recuperar todos los registros de la tabla


        // Obtener la última visita para un ticket
        $ultimaVisita = DB::table('visitas')
            ->where('idTickets', $ticketId)  // Filtrar por el id del ticket
            ->orderBy('idVisitas', 'desc')  // Ordenar por idVisitas (asumido como incremental)
            ->first();  // Obtener solo la última visita

        // Verificar si la última visita tiene 'estadovisita' igual a 1 o null/0
        if ($ultimaVisita) {
            if ($ultimaVisita->estadovisita == 1) {
                // La última visita tiene 'estadovisita' igual a 1
                $ultimaVisitaConEstado1 = true;
            } elseif ($ultimaVisita->estadovisita === null || $ultimaVisita->estadovisita == 0) {
                // La última visita tiene 'estadovisita' igual a null o 0
                $ultimaVisitaConEstado1 = false;
            }
        } else {
            // No se encontraron visitas para este ticket
            $ultimaVisitaConEstado1 = true;  // Aquí cambiamos a true para que el botón se muestre si no hay visitas.
        }


        // Obtener el valor de estadovisita para la visita seleccionada
        $estadovisita = DB::table('visitas')
            ->where('idTickets', $ticketId)  // Filtro por ticketId
            ->where('idVisitas', $idVisitaSeleccionada)  // Filtro por idVisitas seleccionada
            ->value('estadovisita');  // Obtenemos el valor de estadovisita
        Log::info('Estado de la visita: ' . $estadovisita);  // Log del valor de estadovisita

        return view("tickets.ordenes-trabajo.helpdesk.edit", compact(
            'orden',
            'usuarios',
            'tiposServicio',
            'modelos',
            'clientes',
            'clientesGenerales',
            'tiendas',
            'ticketId',
            'marcas',
            'ticket',
            'estadosFlujo',
            'colorEstado',
            'existeFlujo4',
            'encargado',
            'tecnicos_apoyo',
            'visitaId',
            'transicionExistente',
            'estadosOTS',
            'tipoUsuario',
            'id',
            'idVisitaSeleccionada',
            'categorias',
            'existeFlujo25',
            'ejecutor', // Asegúrate de pasar la variable ejecutor
            'existeFlujo31',
            'tiposEnvio',
            'tiposRecojo',
            'ultimaVisitaConEstado1',
            'estadovisita'


        ));
    }



    public function guardardatosenviosoporte(Request $request)
    {
        // Validar los datos recibidos
        $validator = Validator::make($request->all(), [
            'idTecnico' => 'required|exists:usuarios,idUsuario',
            'tipoRecojo' => 'required|exists:tiporecojo,idtipoRecojo',
            'tipoEnvio' => 'required|exists:tipoenvio,idtipoenvio',
            'ticketId' => 'required|exists:tickets,idTickets',
            'agencia' => 'required|string|max:255', // Validación para agencia
        ]);

        // Si la validación falla, devolver respuesta con errores
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Errores de validación', 'errors' => $validator->errors()]);
        }

        // Intentar insertar los datos en la tabla datos_envio
        try {
            // Usamos DB::insert() para insertar los datos directamente
            DB::insert('
                INSERT INTO datos_envio (idTickets, tipoRecojo, tipoEnvio, tipo, idUsuario, agencia)
                VALUES (?, ?, ?, ?, ?, ?)
            ', [
                $request->ticketId,      // idTickets
                $request->tipoRecojo,    // tipoRecojo
                $request->tipoEnvio,     // tipoEnvio
                2,                       // tipo (siempre es 2 según lo indicado)
                $request->idTecnico,     // idUsuario (el técnico de envío)
                $request->agencia        // agencia
            ]);

            return response()->json(['success' => true, 'message' => 'Datos de envío guardados correctamente.']);
        } catch (\Exception $e) {
            // En caso de error al insertar
            return response()->json(['success' => false, 'message' => 'Hubo un error al guardar los datos de envío.']);
        }
    }

    public function guardarEquipo(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'tipoProducto' => 'required|integer',
            'marca' => 'required|integer',
            'modelo' => 'required|integer',
            'numeroSerie' => 'required|string|max:255',
            'idTicket' => 'required|integer',
            'idVisita' => 'required|integer',
            'observaciones' => 'nullable|string', // Validar observaciones (opcional)
        ]);

        // Crear un nuevo registro en la tabla equipos
        $equipo = new Equipo();
        $equipo->nserie = $request->numeroSerie;
        $equipo->modalidad = 'Instalación'; // Si quieres asignar una modalidad específica
        $equipo->idTickets = $request->idTicket;
        $equipo->idModelo = $request->modelo;
        $equipo->idMarca = $request->marca;
        $equipo->idCategoria = $request->tipoProducto;
        $equipo->idVisitas = $request->idVisita;
        $equipo->observaciones = $request->observaciones; // Guardar observaciones
        $equipo->save();

        // Obtener la marca y ocultar la foto
        $marca = Marca::find($request->marca);
        $marca->makeHidden(['foto']); // Excluir la foto de la respuesta

        // Devolver la respuesta
        return response()->json([
            'success' => true,
            'producto' => $equipo,
            'marca' => $marca, // Devolver la marca sin la foto
            'modelo' => Modelo::find($request->modelo),
            'numeroSerie' => $request->numeroSerie,
            'observaciones' => $equipo->observaciones // Incluir observaciones en la respuesta
        ]);
    }






    public function guardarEquipoRetirar(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'tipoProducto' => 'required|integer',
            'marca' => 'required|integer',
            'modelo' => 'required|integer',
            'numeroSerie' => 'required|string|max:255',
            'observaciones' => 'nullable|string|max:255', // Añadir validación para observaciones
            'idTicket' => 'required|integer',
            'idVisita' => 'required|integer',
        ]);

        // Crear un nuevo registro en la tabla equipos
        $equipo = new Equipo();
        $equipo->nserie = $request->numeroSerie;
        $equipo->modalidad = 'Retirar'; // Asignar modalidad de "Retirar"
        $equipo->idTickets = $request->idTicket;
        $equipo->idModelo = $request->modelo;
        $equipo->idMarca = $request->marca;
        $equipo->idCategoria = $request->tipoProducto;
        $equipo->idVisitas = $request->idVisita;
        $equipo->observaciones = $request->observaciones; // Guardar las observaciones
        $equipo->save();

        // Obtener la marca y ocultar la foto
        $marca = Marca::find($request->marca);
        $marca->makeHidden(['foto']); // Excluir la foto de la respuesta

        // Devolver la respuesta
        return response()->json([
            'success' => true,
            'producto' => $equipo,
            'marca' => $marca, // Devolver la marca sin la foto
            'modelo' => Modelo::find($request->modelo),
            'numeroSerie' => $request->numeroSerie,
            'observaciones' => $equipo->observaciones, // Incluir observaciones en la respuesta
        ]);
    }

    public function obtenerProductosInstalados(Request $request)
    {
        $productos = Equipo::select(
            'equipos.idEquipos',
            'equipos.nserie',
            'equipos.idCategoria',  // Añadir este campo
            'equipos.idMarca',      // Añadir este campo
            'equipos.idModelo',     // Añadir este campo
            'categoria.nombre as categoria_nombre',
            'marca.nombre as marca_nombre',
            'modelo.nombre as modelo_nombre',
            'equipos.observaciones'
        )
            ->join('categoria', 'equipos.idCategoria', '=', 'categoria.idCategoria')
            ->join('marca', 'equipos.idMarca', '=', 'marca.idMarca')
            ->join('modelo', 'equipos.idModelo', '=', 'modelo.idModelo')
            ->where('equipos.idTickets', $request->idTicket)
            ->where('equipos.idVisitas', $request->idVisita)
            ->where('equipos.modalidad', 'Instalación')
            ->get();

        return response()->json($productos);
    }

    public function obtenerProductosRetirados(Request $request)
    {
        $productos = Equipo::select(
            'equipos.idEquipos',
            'equipos.nserie',
            'equipos.idCategoria',  // Añadir este campo
            'equipos.idMarca',      // Añadir este campo
            'equipos.idModelo',     // Añadir este campo
            'categoria.nombre as categoria_nombre',
            'marca.nombre as marca_nombre',
            'modelo.nombre as modelo_nombre',
            'equipos.observaciones'
        )
            ->join('categoria', 'equipos.idCategoria', '=', 'categoria.idCategoria')
            ->join('marca', 'equipos.idMarca', '=', 'marca.idMarca')
            ->join('modelo', 'equipos.idModelo', '=', 'modelo.idModelo')
            ->where('equipos.idTickets', $request->idTicket)
            ->where('equipos.idVisitas', $request->idVisita)
            ->where('equipos.modalidad', 'Retirar')
            ->get();

        return response()->json($productos);
    }



    public function obtenerMarcasPorCategoria($idCategoria)
    {
        // Obtener las marcas que pertenecen a la categoría seleccionada
        $marcas = Marca::where('estado', 1) // Aseguramos que solo las marcas activas sean seleccionables
            ->whereHas('modelos', function ($query) use ($idCategoria) {
                $query->where('idCategoria', $idCategoria);
            })
            ->get(); // Trae todas las columnas

        // Excluir el campo 'foto' de la respuesta utilizando makeHidden
        $marcas->makeHidden(['foto']);

        // Retornar las marcas como respuesta JSON
        return response()->json($marcas);
    }




    public function obtenerModelosPorMarca($idMarca)
    {
        // Obtener los modelos que pertenecen a la marca seleccionada
        $modelos = Modelo::where('idMarca', $idMarca)->get();

        // Retornar los modelos como respuesta JSON
        return response()->json($modelos);
    }

    public function obtenerModelosPorCategoria($idCategoria)
    {
        // Obtener los modelos que pertenecen a la categoría seleccionada
        $modelos = Modelo::where('idCategoria', $idCategoria)->get();

        // Devolver los modelos como respuesta JSON
        return response()->json($modelos);
    }




    public function obtenerModelosPorMarcaYCategoria($idMarca, $idCategoria)
    {
        $modelos = Modelo::where('idMarca', $idMarca)
            ->where('idCategoria', $idCategoria)
            ->get();

        return response()->json($modelos);
    }


    public function obtenerModelosPorMarcaYCategoriaobtener($idMarca, $idCategoria)
    {
        try {
            // Validar que los IDs sean numéricos
            if (!is_numeric($idMarca) || !is_numeric($idCategoria)) {
                return response()->json([
                    'success' => false,
                    'message' => 'IDs de marca y categoría deben ser numéricos'
                ], 400);
            }

            // Obtener modelos activos que pertenezcan a la marca y categoría
            $modelos = Modelo::where('idMarca', $idMarca)
                ->where('idCategoria', $idCategoria)
                ->where('estado', 1) // Solo modelos activos
                ->select('idModelo', 'nombre')
                ->orderBy('nombre', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'modelos' => $modelos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener modelos: ' . $e->getMessage()
            ], 500);
        }
    }








    public function editHelpdesk($id)
    {
        $usuario = Auth::user();
        $rol = $usuario->rol->nombre ?? 'Sin Rol';

        $estadosOTS = DB::table('estado_ots')
            ->whereIn('idEstadoots', [2, 4, 5, 6])
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
        $articulos = DB::table('articulos')
            ->join('tipoarticulos', 'articulos.idTipoArticulo', '=', 'tipoarticulos.idTipoArticulo')
            ->select('articulos.idArticulos', 'articulos.nombre', 'articulos.idTipoArticulo', 'tipoarticulos.nombre as tipo_nombre')
            ->get();



        // Inicializamos la variable para idVisitaSeleccionada
        $idVisitaSeleccionada = null;

        // Consulta para obtener el idVisita de la visita seleccionada para ese ticket
        $idVisitaSeleccionada = DB::table('seleccionarvisita')
            ->where('idTickets', $ticketId)  // Filtro por ticketId
            ->value('idVisitas');  // Obtenemos el idVisitas de la visita seleccionada para ese ticket

        // Log de la visita seleccionada
        Log::info('Visita seleccionada, idVisita: ' . $idVisitaSeleccionada);

        // Si se encuentra una visita seleccionada
        if ($idVisitaSeleccionada) {
            Log::info('Se encontró una visita seleccionada para el ticket: ' . $ticketId);
        } else {
            Log::warning('No se encontró una visita seleccionada para el ticket: ' . $ticketId); // Log si no se encuentra una visita seleccionada
        }

        // Puedes agregar un log final para revisar el valor de idVisita
        Log::info('Valor final de idVisita: ' . $idVisitaSeleccionada);

        // Verificar si existe un flujo con idEstadflujo = 31
        $flujo = TicketFlujo::where('idTicket', $ticketId)
            ->where('idEstadflujo', 31)
            ->first();

        // dd($flujo); // Verifica si devuelve el registro correcto

        $existeFlujo31 = $flujo ? true : false;  // Si existe flujo con idEstadflujo 4, establecer como verdadero





        // Obtener la última visita para un ticket
        $ultimaVisita = DB::table('visitas')
            ->where('idTickets', $ticketId)  // Filtrar por el id del ticket
            ->orderBy('idVisitas', 'desc')  // Ordenar por idVisitas (asumido como incremental)
            ->first();  // Obtener solo la última visita

        // Verificar si la última visita tiene 'estadovisita' igual a 1 o null/0
        if ($ultimaVisita) {
            if ($ultimaVisita->estadovisita == 1) {
                // La última visita tiene 'estadovisita' igual a 1
                $ultimaVisitaConEstado1 = true;
            } elseif ($ultimaVisita->estadovisita === null || $ultimaVisita->estadovisita == 0) {
                // La última visita tiene 'estadovisita' igual a null o 0
                $ultimaVisitaConEstado1 = false;
            }
        } else {
            // No se encontraron visitas para este ticket
            $ultimaVisitaConEstado1 = true;  // Aquí cambiamos a true para que el botón se muestre si no hay visitas.
        }

        // Verificar si existe un flujo con idEstadflujo = 25
        $flujo = TicketFlujo::where('idTicket', $ticketId)
            ->where('idEstadflujo', 25)
            ->first();

        // dd($flujo); // Verifica si devuelve el registro correcto

        $existeFlujo25 = $flujo ? true : false;  // Si existe flujo con idEstadflujo 4, establecer como verdadero

        // Obtener el valor de estadovisita para la visita seleccionada
        $estadovisita = DB::table('visitas')
            ->where('idTickets', $ticketId)  // Filtro por ticketId
            ->where('idVisitas', $idVisitaSeleccionada)  // Filtro por idVisitas seleccionada
            ->value('estadovisita');  // Obtenemos el valor de estadovisita
        Log::info('Estado de la visita: ' . $estadovisita);  // Log del valor de estadovisita





        // Verificar si existe un flujo con idEstadflujo = 31
        $flujo = TicketFlujo::where('idTicket', $ticketId)
            ->where('idEstadflujo', 25)
            ->first();

        // dd($flujo); // Verifica si devuelve el registro correcto

        $existeFlujo25 = $flujo ? true : false;  // Si existe flujo con idEstadflujo 4, establecer como verdadero



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
            'articulos',
            'idVisitaSeleccionada',
            'idtipoServicio',
            'existeFlujo31',
            'ultimaVisitaConEstado1',
            'existeFlujo25',
            'estadovisita'

        ));
    }




    public function guardarSuministros(Request $request)
    {
        $articulos = $request->articulos;
        $ticketId = $request->ticketId;
        $visitaId = $request->visitaId;

        foreach ($articulos as $articulo) {
            // Verificar si el artículo ya está guardado para este ticket y visita
            $existe = DB::table('suministros')
                ->where('idTickets', $ticketId)
                ->where('idVisitas', $visitaId)
                ->where('idArticulos', $articulo['id'])
                ->exists();

            if ($existe) {
                // Si existe, actualizar la cantidad del artículo
                DB::table('suministros')
                    ->where('idTickets', $ticketId)
                    ->where('idVisitas', $visitaId)
                    ->where('idArticulos', $articulo['id'])
                    ->update(['cantidad' => DB::raw('cantidad + ' . $articulo['cantidad'])]);
            } else {
                // Si no existe, insertar el artículo nuevo
                DB::table('suministros')->insert([
                    'idTickets' => $ticketId,
                    'idVisitas' => $visitaId,
                    'idArticulos' => $articulo['id'],
                    'cantidad' => $articulo['cantidad'],
                ]);
            }
        }

        return response()->json(['message' => 'Suministros guardados correctamente.']);
    }








    // public function getSuministros($ticketId, $visitaId)
    // {
    //     // Obtener los suministros asociados con el ticketId y visitaId
    //     $suministros = DB::table('suministros')
    //         ->join('articulos', 'suministros.idArticulos', '=', 'articulos.idArticulos')
    //         ->join('tipoarticulos', 'articulos.idTipoArticulo', '=', 'tipoarticulos.idTipoArticulo') // Hacemos el join con tipoarticulos
    //         ->where('suministros.idTickets', $ticketId)
    //         ->where('suministros.idVisitas', $visitaId)
    //         ->select('articulos.idArticulos', 'articulos.nombre', 'tipoarticulos.nombre as tipo_nombre', 'suministros.cantidad') // Seleccionamos también el tipo_nombre
    //         ->get();

    //     return response()->json($suministros);
    // }


    public function getSuministros($ticketId, $visitaId)
    {
        // Obtener los suministros asociados con el ticketId y visitaId
        $suministros = DB::table('suministros')
            ->join('articulos', 'suministros.idArticulos', '=', 'articulos.idArticulos')
            ->join('tipoarticulos', 'articulos.idTipoArticulo', '=', 'tipoarticulos.idTipoArticulo') // Hacemos el join con tipoarticulos
            ->where('suministros.idTickets', $ticketId)
            ->where('suministros.idVisitas', $visitaId)
            ->select('suministros.idSuministros', 'articulos.idArticulos', 'articulos.nombre', 'tipoarticulos.nombre as tipo_nombre', 'suministros.cantidad') // Añadir idSuministros aquí
            ->get();

        // Verificar los datos obtenidos
        Log::info('Suministros obtenidos:', ['suministros' => $suministros]);

        return response()->json($suministros);
    }

    public function actualizarCantidad(Request $request, $id)
    {
        // Validar los datos
        $request->validate([
            'cantidad' => 'required|integer|min:1',  // Asegúrate de que la cantidad sea válida
        ]);

        // Obtener el suministro
        $suministro = Suministro::find($id);

        // Si no se encuentra el suministro
        if (!$suministro) {
            return response()->json(['message' => 'Suministro no encontrado.'], 404);
        }

        // Actualizar la cantidad
        $suministro->cantidad = $request->input('cantidad');
        $suministro->save();

        // Devolver respuesta
        return response()->json(['message' => 'Cantidad actualizada correctamente.']);
    }






    public function eliminarSuministros($idSuministro)
    {
        try {
            // Log para ver qué ID estamos intentando eliminar
            Log::info('Eliminando suministro con ID: ' . $idSuministro);

            // Eliminar el suministro por ID
            $deleted = DB::table('suministros')->where('idSuministros', $idSuministro)->delete();

            // Verificar si se eliminó algo
            if ($deleted) {
                // Log para confirmar que el artículo fue eliminado
                Log::info('Suministro con ID ' . $idSuministro . ' eliminado correctamente.');

                // Respuesta exitosa
                return response()->json(['message' => 'Artículo eliminado correctamente.']);
            } else {
                // Log en caso de que no se haya encontrado el suministro para eliminar
                Log::warning('No se encontró el suministro con ID: ' . $idSuministro);

                return response()->json(['message' => 'No se encontró el artículo para eliminar.'], 404);
            }
        } catch (\Exception $e) {
            // Log para capturar el error
            Log::error('Error al eliminar el suministro con ID ' . $idSuministro . ': ' . $e->getMessage());

            // En caso de error
            return response()->json(['message' => 'Error al eliminar el artículo.'], 500);
        }
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


    // public function getSuministros(Request $request)
    // {
    //     // Obtener los datos de la visita seleccionada usando el ID de la visita seleccionada
    //     $seleccionadaVisita = SeleccionarVisita::where('idselecionarvisita', $request->idseleccionvisita)->first();

    //     // Verificar si no se encontró la visita
    //     // if (!$seleccionadaVisita) {
    //     //     Log::error('Visita no encontrada', ['idseleccionvisita' => $request->idseleccionvisita]);
    //     //     return response()->json(['error' => 'Visita no encontrada'], 404);
    //     // }

    //     // Log para ver si encontramos la visita
    //     Log::info('Visita encontrada', ['idseleccionvisita' => $request->idseleccionvisita, 'visita' => $seleccionadaVisita]);

    //     $idTickets = $seleccionadaVisita->idTickets;
    //     $idVisitas = $seleccionadaVisita->idVisitas;

    //     // Obtener suministros para el ticket y la visita actuales
    //     $suministros = Suministro::where('idTickets', $idTickets)
    //         ->where('idVisitas', $idVisitas)
    //         ->get();

    //     // Log para verificar los suministros obtenidos
    //     Log::info('Suministros encontrados', ['suministros' => $suministros]);

    //     // Devolver los suministros existentes al frontend
    //     return response()->json($suministros);
    // }






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


    public function getAll(Request $request)
    {
        Log::info("📥 Entrando al método getAll (HELPDESK)");

        $tipoTicket = 2; // Forzar tipo Helpdesk

        // Base para contar registros totales reales del tipo Helpdesk
        $baseQuery = Ticket::query()->where('idTipotickets', $tipoTicket);
        $recordsTotal = $baseQuery->count();

        // Base extendida con relaciones
        $query = $baseQuery->with([
            'tecnico:idUsuario,Nombre',
            'usuario:idUsuario,Nombre',
            'cliente:idCliente,nombre',
            'tiposervicio:idTipoServicio,nombre',
            'estado_ot:idEstadoots,descripcion,color',
            'tienda:idTienda,nombre',
            'ticketflujo.estadoflujo:idEstadflujo,descripcion,color',
            'manejoEnvio:idmanejo_envio,idTickets,tipo',
            'visitas' => function ($q) {
                $q->select('idVisitas', 'idTickets', 'fecha_programada')
                    ->latest('fecha_programada')->limit(1);
            },
            'seleccionarVisita:idselecionarvisita,idTickets,idVisitas,vistaseleccionada',
            'seleccionarVisita.visita:idVisitas,nombre,fecha_programada,fecha_asignada,estado,idUsuario',
            'seleccionarVisita.visita.tecnico:idUsuario,Nombre',
            'visitas:idVisitas,nombre,fecha_programada,fecha_asignada,estado,idUsuario',
            'visitas.tecnico:idUsuario,Nombre',
            'transicion_status_tickets' => function ($q) use ($request) {
                if ($request->has('idVisita')) {
                    $q->whereHas('seleccionarVisita', function ($subquery) use ($request) {
                        $subquery->where('idVisitas', $request->idVisita);
                    })->where('idEstadoots', 3);
                }
            }
        ]);


        if ($request->has('clienteGeneral') && $request->clienteGeneral != '') {
            $query->where('idClienteGeneral', $request->clienteGeneral);
        }

        if ($request->filled('startDate') && $request->filled('endDate')) {
            $query->whereBetween('fecha_creacion', [
                $request->startDate . ' 00:00:00',
                $request->endDate . ' 23:59:59'
            ]);
        } elseif ($request->filled('startDate')) {
            $query->where('fecha_creacion', '>=', $request->startDate . ' 00:00:00');
        } elseif ($request->filled('endDate')) {
            $query->where('fecha_creacion', '<=', $request->endDate . ' 23:59:59');
        }

        if ($request->has('search') && !empty($request->input('search.value'))) {
            $searchValue = trim($request->input('search.value'));
            $normalized = Str::lower(Str::ascii($searchValue));
        
            $query->where(function ($q) use ($searchValue, $normalized) {
                $q->orWhere('idTickets', $searchValue)
                    ->orWhere('numero_ticket', $searchValue)
                    ->orWhere('numero_ticket', 'LIKE', "%{$searchValue}%")
                    ->orWhereHas('cliente', fn($q) => $q->where('nombre', 'LIKE', "%{$searchValue}%"))
                    ->orWhereHas('tienda', fn($q) => $q->where('nombre', 'LIKE', "%{$searchValue}%"))
                    ->orWhereHas('tecnico', fn($q) => $q->where('Nombre', 'LIKE', "%{$searchValue}%"))
                    ->orWhereHas('visitas.tecnico', fn($q) => $q->where('Nombre', 'LIKE', "%{$searchValue}%"))
                    ->orWhereHas('ticketflujo.estadoFlujo', fn($q) =>
                        $q->whereRaw("LOWER(CONVERT(descripcion USING utf8)) LIKE ?", ["%{$normalized}%"]));
            });
        }
        

        $query->orderBy('idTickets', 'desc');
        $recordsFiltered = (clone $query)->count();

        $ordenes = $query->skip($request->input('start', 0))
            ->take($request->input('length', 10))
            ->get()
            ->map(function ($item) {
                $arr = json_decode(json_encode($item), true);
                array_walk_recursive($arr, fn(&$v) => $v = is_string($v) ? mb_convert_encoding($v, 'UTF-8', 'UTF-8') : $v);
                return $arr;
            });

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $ordenes
        ]);
    }


    public function verEnvio($id)
    {
        $envioTipo1 = DB::table('datos_envio')
            ->where('idTickets', $id)
            ->where('tipo', 1)
            ->first();

        $envioTipo2 = DB::table('datos_envio')
            ->where('idTickets', $id)
            ->where('tipo', 2)
            ->first();

        // Datos de tipo 1 (Envío)
        $tipoRecojo1 = $envioTipo1
            ? DB::table('tiporecojo')->where('idtipoRecojo', $envioTipo1->tipoRecojo)->first()
            : null;

        $tipoEnvio1 = $envioTipo1
            ? DB::table('tipoenvio')->where('idtipoenvio', $envioTipo1->tipoEnvio)->first()
            : null;

        $manejoEnvio1 = DB::table('manejo_envio')
            ->where('idTickets', $id)
            ->where('tipo', 1)
            ->first();

        // Datos de manejo_envio tipo = 2
        $manejoEnvio2 = DB::table('manejo_envio')
            ->where('idTickets', $id)
            ->where('tipo', 2)
            ->first();

        $usuarioEnvio1 = $manejoEnvio1
            ? DB::table('usuarios')->where('idUsuario', $manejoEnvio1->idUsuario)->first()
            : null;

        // Usuario que registró el manejo_envio (recojo)
        $usuarioEnvio2 = $manejoEnvio2
            ? DB::table('usuarios')->where('idUsuario', $manejoEnvio2->idUsuario)->first()
            : null;

        $usuario1 = $envioTipo1
            ? DB::table('usuarios')->where('idUsuario', $envioTipo1->idUsuario)->first()
            : null;

        // Datos de tipo 2 (Recojo)
        $tipoRecojo2 = $envioTipo2
            ? DB::table('tiporecojo')->where('idtipoRecojo', $envioTipo2->tipoRecojo)->first()
            : null;

        $tipoEnvio2 = $envioTipo2
            ? DB::table('tipoenvio')->where('idtipoenvio', $envioTipo2->tipoEnvio)->first()
            : null;

        $usuario2 = $envioTipo2
            ? DB::table('usuarios')->where('idUsuario', $envioTipo2->idUsuario)->first()
            : null;

        $receptor = DB::table('ticket_receptor')
            ->where('idTickets', $id)
            ->first();

        $anexos1 = DB::table('anexo_retiro')
            ->where('idTickets', $id)
            ->where('tipo', 1)
            ->get();

        $anexos2 = DB::table('anexo_retiro')
            ->where('idTickets', $id)
            ->where('tipo', 2)
            ->get();


        $ticket = DB::table('tickets')->where('idTickets', $id)->first();

        $ejecutor = $ticket
            ? DB::table('usuarios')->where('idUsuario', $ticket->ejecutor)->first()
            : null;

        return view('apps.invoice.preview', [
            'ticketId' => $id,
            'numero_ticket' => $ticket->numero_ticket ?? 'N/A',

            // Envío (tipo = 1)
            'tipoRecojo1' => $tipoRecojo1->nombre ?? 'N/A',
            'tipoEnvio1'  => $tipoEnvio1->nombre ?? 'N/A',
            'tecnico1'    => $usuario1 ? "{$usuario1->Nombre} {$usuario1->apellidoPaterno}" : 'N/A',
            'correo1'     => $usuario1->correo ?? 'N/A',
            'telefono1'   => $usuario1->telefono ?? 'N/A',

            // Recojo (tipo = 2)
            'tipoRecojo2' => $tipoRecojo2->nombre ?? 'N/A',
            'tipoEnvio2'  => $tipoEnvio2->nombre ?? 'N/A',
            'tecnico2'    => $usuario2 ? "{$usuario2->Nombre} {$usuario2->apellidoPaterno}" : 'N/A',
            'correo2'     => $usuario2->correo ?? 'N/A',
            'telefono2'   => $usuario2->telefono ?? 'N/A',

            'receptorNombre' => $receptor->nombre ?? 'N/A',
            'receptorDni'    => $receptor->dni ?? 'N/A',
            'anexos1' => $anexos1,
            'anexos2' => $anexos2,

            'ejecutor'       => $ejecutor ? "{$ejecutor->Nombre} {$ejecutor->apellidoPaterno}" : 'N/A',

            // Tipo control
            'tipo1' => $envioTipo1 ? 1 : ($envioTipo2 ? 2 : null),
            'tipo2' => $envioTipo1 && $envioTipo2 ? 2 : null,
            'manejoEnvio1' => $manejoEnvio1,
            'manejoEnvio2' => $manejoEnvio2,
            'usuarioEnvio1' => $usuarioEnvio1,
            'usuarioEnvio2' => $usuarioEnvio2,

        ]);
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
            'tipoServicio' => 'required|integer|exists:tiposervicio,idTipoServicio',
            'idTienda' => 'required|exists:tienda,idTienda',

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
        $orden->tipoServicio = $request->tipoServicio;

        $orden->fallaReportada = $request->fallaReportada;

        // Guardar los cambios
        $orden->save();

        // Log para confirmar que los cambios se guardaron
        Log::info('Orden actualizada:', ['id' => $orden->id, 'nuevos_datos' => $orden->toArray()]);

        // Responder con éxito
        return response()->json(['success' => true]);
    }




    public function actualizarSoporte(Request $request, $id)
    {
        // Log para ver los datos que se están recibiendo
        Log::info('Datos recibidos para actualizar la orden:', $request->all());

        // Validar los datos del formulario
        $validated = $request->validate([
            'idCliente' => 'required|exists:cliente,idCliente',
            'idClienteGeneral' => 'required|exists:clientegeneral,idClienteGeneral',
            'idTienda' => 'required|exists:tienda,idTienda',
            'fallaReportada' => 'nullable|string',
            'ejecutor' => 'nullable|exists:usuarios,idUsuario', // Validar que el ejecutor sea un usuario válido

        ]);

        // Encontrar la orden y actualizarla
        $orden = Ticket::findOrFail($id); // Usamos findOrFail para asegurarnos que la orden existe

        // Log para verificar que se encontró la orden
        Log::info('Orden encontrada con ID:', ['id' => $orden->id]);

        // Actualizar los campos de la orden
        $orden->idCliente = $request->idCliente;
        $orden->idClienteGeneral = $request->idClienteGeneral;
        $orden->idTienda = $request->idTienda;
        $orden->fallaReportada = $request->fallaReportada;
        $orden->ejecutor = $request->ejecutor; // Actualizamos el ejecutor


        // Guardar los cambios
        $orden->save();

        // Log para confirmar que los cambios se guardaron
        Log::info('Orden actualizada:', ['id' => $orden->id, 'nuevos_datos' => $orden->toArray()]);

        // Responder con éxito
        return response()->json(['success' => true]);
    }





    public function actualizarEjecucion(Request $request, $id)
    {
        // Log para ver los datos que se están recibiendo
        Log::info('Datos recibidos para actualizar la orden:', $request->all());

        // Validar los datos del formulario
        $validated = $request->validate([
            'idCliente' => 'required|exists:cliente,idCliente',
            'idClienteGeneral' => 'required|exists:clientegeneral,idClienteGeneral',
            'idTienda' => 'required|exists:tienda,idTienda',
            'fallaReportada' => 'nullable|string',
            'ejecutor' => 'nullable|exists:usuarios,idUsuario', // Validar que el ejecutor sea un usuario válido
            'nrmcotizacion' => 'nullable|string',

        ]);

        // Encontrar la orden y actualizarla
        $orden = Ticket::findOrFail($id); // Usamos findOrFail para asegurarnos que la orden existe

        // Log para verificar que se encontró la orden
        Log::info('Orden encontrada con ID:', ['id' => $orden->id]);

        // Actualizar los campos de la orden
        $orden->idCliente = $request->idCliente;
        $orden->idClienteGeneral = $request->idClienteGeneral;
        $orden->idTienda = $request->idTienda;
        $orden->fallaReportada = $request->fallaReportada;
        $orden->ejecutor = $request->ejecutor; // Actualizamos el ejecutor
        $orden->nrmcotizacion = $request->nrmcotizacion;

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


    private function optimizeBase64Image($base64String, $calidad = 70, $destinoAncho = 700, $destinoAlto = 450)
    {
        if (!$base64String) return null;

        if (!preg_match('#^data:image/(\w+);base64,#i', $base64String, $matches)) {
            return $base64String;
        }

        $datosImagen = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64String));
        $origen = @imagecreatefromstring($datosImagen);
        if (!$origen) return $base64String;

        $anchoOriginal = imagesx($origen);
        $altoOriginal = imagesy($origen);

        // 🔁 Rotar si es vertical
        if ($altoOriginal > $anchoOriginal) {
            $origen = imagerotate($origen, -90, 0);
            $anchoOriginal = imagesx($origen);
            $altoOriginal = imagesy($origen);
        }

        // 🖼️ Redimensionar directamente al tamaño deseado (aunque deforme un poco si es necesario)
        $resized = imagecreatetruecolor($destinoAncho, $destinoAlto);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        $transparente = imagecolorallocatealpha($resized, 0, 0, 0, 127);
        imagefilledrectangle($resized, 0, 0, $destinoAncho, $destinoAlto, $transparente);
        imagecopyresampled($resized, $origen, 0, 0, 0, 0, $destinoAncho, $destinoAlto, $anchoOriginal, $altoOriginal);

        ob_start();
        imagewebp($resized, null, $calidad);
        $contenido = ob_get_clean();

        imagedestroy($origen);
        imagedestroy($resized);

        return 'data:image/webp;base64,' . base64_encode($contenido);
    }


    public function procesarLogoMarca($imagenRaw)
    {
        $manager = new ImageManager(new Driver());

        $img = $manager->read($imagenRaw); // 👈 CAMBIO IMPORTANT

        $img = $img->scaleDown(width: 256, height: 160); // Este método respeta proporciones

        $canvas = $manager->create(256, 160, 'ffffff'); // 👈 nuevo método en v3

        $canvas->place($img, 'center');

        return 'data:image/png;base64,' . base64_encode($canvas->toPng());
    }

    public function generateLevantamientoPdf($idOt)
    {
        $orden = Ticket::with([
            'cliente.tipodocumento',
            'clienteGeneral',
            'tecnico.tipodocumento',
            'tienda',
            'marca',
            'modelo.categoria',
            'transicion_status_tickets.estado_ot',
            'visitas.tecnico.tipodocumento',
            'visitas.anexos_visitas',
            'visitas.fotostickest',
        ])->findOrFail($idOt);

        $logoGKM = $this->procesarLogoMarca(file_get_contents(public_path('assets/images/auth/logogkm2.png')));

        $logoClienteGeneral = null;
        if ($orden->clienteGeneral && !empty($orden->clienteGeneral->foto)) {
            $logoClienteGeneral = $this->procesarLogoMarca($orden->clienteGeneral->foto);
        }

        $seleccionada = SeleccionarVisita::where('idTickets', $idOt)->first();
        if (!$seleccionada) {
            return response()->json(['success' => false, 'message' => 'No se encontró una visita seleccionada.']);
        }

        $idVisitasSeleccionada = $seleccionada->idVisitas;

        $transicionesStatusOt = TransicionStatusTicket::where('idTickets', $idOt)
            ->where('idVisitas', $idVisitasSeleccionada)
            ->whereNotNull('justificacion')
            ->where('justificacion', '!=', '')
            ->with('estado_ot')
            ->get();

        $producto = [
            'categoria' => $orden->modelo->categoria->nombre ?? 'No especificado',
            'marca' => $orden->modelo->marca->nombre ?? 'No especificado',
            'modelo' => $orden->modelo->nombre ?? 'No especificado',
            'serie' => $orden->serie ?? 'No especificado',
            'fallaReportada' => $orden->fallaReportada ?? 'No especificado'
        ];

        $condicion = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisitasSeleccionada) // ✅
            ->first();

        $motivoCondicion = $condicion->motivo ?? null;

        $suministros = Suministro::with('articulo.tipoArticulo', 'articulo.modelo.marca')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisitasSeleccionada)
            ->get();



        $visitas = collect();
        $visitaSeleccionada = $orden->visitas->where('idVisitas', $idVisitasSeleccionada)->first();

        if ($visitaSeleccionada) {
            $visitas = collect([
                [
                    'nombre' => $visitaSeleccionada->nombre ?? 'N/A',
                    'fecha_programada' => $visitaSeleccionada->fecha_programada ? date('d/m/Y', strtotime($visitaSeleccionada->fecha_programada)) : 'N/A',
                    'hora_inicio' => $visitaSeleccionada->fecha_inicio ? date('H:i', strtotime($visitaSeleccionada->fecha_inicio)) : 'N/A',
                    'hora_final' => $visitaSeleccionada->fecha_final ? date('H:i', strtotime($visitaSeleccionada->fecha_final)) : 'N/A',
                    'fecha_llegada' => $visitaSeleccionada->fecha_llegada ? date('d/m/Y H:i', strtotime($visitaSeleccionada->fecha_llegada)) : 'N/A',
                    'tecnico' => ($visitaSeleccionada->tecnico->Nombre ?? 'N/A') . ' ' . ($visitaSeleccionada->tecnico->apellidoPaterno ?? ''),
                    'correo' => ($visitaSeleccionada->tecnico->correo ?? 'No disponible'),
                    'telefono' => ($visitaSeleccionada->tecnico->telefono ?? 'No registrado'),
                    'documento' => $visitaSeleccionada->tecnico->documento ?? 'No disponible',
                    'tipo_documento' => $visitaSeleccionada->tecnico->tipodocumento->nombre ?? 'Documento',
                    'vehiculo_placa' => $visitaSeleccionada->tecnico->vehiculo->numero_placa ?? 'Sin placa',
                ]
            ]);
        }

        $firma = DB::table('firmas')->where('idTickets', $idOt)
            ->where('idVisitas', $idVisitasSeleccionada)
            ->first();

        $firmaCliente = $firma && !empty($firma->firma_cliente)
            ? $this->optimizeBase64Image('data:image/png;base64,' . base64_encode($firma->firma_cliente))
            : null;

        $firmaTecnico = null;
        if ($visitaSeleccionada && $visitaSeleccionada->tecnico && !empty($visitaSeleccionada->tecnico->firma)) {
            $firmaTecnico = 'data:image/png;base64,' . base64_encode($visitaSeleccionada->tecnico->firma);
        }

        $imagenesAnexos = [];
        if ($visitaSeleccionada && $visitaSeleccionada->anexos_visitas) {
            $imagenesAnexos = $visitaSeleccionada->anexos_visitas->map(function ($anexo) {
                return [
                    'foto_base64' => !empty($anexo->foto)
                        ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($anexo->foto))
                        : null,
                    'descripcion' => $anexo->descripcion
                ];
            });
        }

        $imagenesCondiciones = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisitasSeleccionada)
            ->whereNotNull('imagen')
            ->get()
            ->map(function ($condicion) {
                return [
                    'foto_base64' => $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($condicion->imagen)),
                    'descripcion' => 'CONDICIÓN: ' . ($condicion->motivo ?? 'Sin descripción')
                ];
            });

        $imagenesAnexos = $imagenesAnexos->merge($imagenesCondiciones);

        $imagenesFotosTickets = $visitaSeleccionada->fotostickest->map(function ($foto) {
            return [
                'foto_base64' => !empty($foto->foto)
                    ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($foto->foto))
                    : null,
                'descripcion' => $foto->descripcion
            ];
        });

        $fechaCreacion = $visitaSeleccionada && $visitaSeleccionada->fecha_inicio
            ? date('d/m/Y', strtotime($visitaSeleccionada->fecha_inicio))
            : 'N/A';

        $vistaPdf = 'tickets.ordenes-trabajo.helpdesk.levantamiento.informe.pdf.index';

        $html = View($vistaPdf, [
            'orden' => $orden,
            'fechaCreacion' => $fechaCreacion,
            'producto' => $producto,
            'transicionesStatusOt' => $transicionesStatusOt,
            'visitas' => $visitas,
            'firma' => $firma,
            'firmaTecnico' => $firmaTecnico,
            'firmaCliente' => $firmaCliente,
            'imagenesAnexos' => $imagenesAnexos,
            'imagenesFotosTickets' => $imagenesFotosTickets,
            'emitente' => (object)['nome' => 'GKM TECHNOLOGY S.A.C.'],
            'logoClienteGeneral' => $logoClienteGeneral,
            'logoGKM' => $logoGKM,
            'suministros' => $suministros,
            'motivoCondicion' => $motivoCondicion,
            'modoVistaPrevia' => false
        ])->render();

        // 1. Guardar PDF temporal
        $tempOriginal = tempnam(sys_get_temp_dir(), 'pdf_raw_') . '.pdf';
        Browsershot::html($html)
            ->format('A4')
            ->fullPage()
            ->noSandbox()
            ->setDelay(2000)
            ->margins(2.5, 2.5, 2.5, 2.5)
            ->emulateMedia('screen')
            ->waitUntilNetworkIdle()
            ->showBackground()
            ->save($tempOriginal);

        // 2. Comprimir con Ghostscript
        $gs = 'C:\Program Files\gs\gs10.05.1\bin\gswin64c.exe'; // ruta exacta de tu instalación Ghostscript
        $tempCompressed = tempnam(sys_get_temp_dir(), 'pdf_compressed_') . '.pdf';

        $cmd = "\"{$gs}\" -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile=\"{$tempCompressed}\" \"{$tempOriginal}\"";
        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0 || !file_exists($tempCompressed) || filesize($tempCompressed) === 0) {
            @unlink($tempOriginal);
            return response()->json([
                'success' => false,
                'message' => 'Ghostscript no logró comprimir el PDF.',
                'command' => $cmd,
                'output' => $output
            ], 500);
        }

        // 3. Enviar el PDF comprimido
        $pdfOutput = file_get_contents($tempCompressed);
        @unlink($tempOriginal);
        @unlink($tempCompressed);

        return response($pdfOutput)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="LEVANTAMIENTO TECNICO ' . $orden->numero_ticket . '.pdf"');
    }


    public function generateLevantamientoPdfVisita($idOt, $idVisita)
    {
        $orden = Ticket::with([
            'cliente.tipodocumento',
            'clienteGeneral',
            'tecnico.tipodocumento',
            'tienda',
            'marca',
            'modelo.categoria',
            'transicion_status_tickets.estado_ot',
            'visitas.tecnico.tipodocumento',
            'visitas.anexos_visitas',
            'visitas.fotostickest',
        ])->findOrFail($idOt);

        $logoGKM = $this->procesarLogoMarca(file_get_contents(public_path('assets/images/auth/logogkm2.png')));

        $logoClienteGeneral = null;
        if ($orden->clienteGeneral && !empty($orden->clienteGeneral->foto)) {
            $logoClienteGeneral = $this->procesarLogoMarca($orden->clienteGeneral->foto);
        }

        $idVisitasSeleccionada = $idVisita;

        $transicionesStatusOt = TransicionStatusTicket::where('idTickets', $idOt)
            ->where('idVisitas', $idVisitasSeleccionada)
            ->whereNotNull('justificacion')
            ->where('justificacion', '!=', '')
            ->with('estado_ot')
            ->get();

        $producto = [
            'categoria' => $orden->modelo->categoria->nombre ?? 'No especificado',
            'marca' => $orden->modelo->marca->nombre ?? 'No especificado',
            'modelo' => $orden->modelo->nombre ?? 'No especificado',
            'serie' => $orden->serie ?? 'No especificado',
            'fallaReportada' => $orden->fallaReportada ?? 'No especificado'
        ];

        $condicion = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisitasSeleccionada) // ✅
            ->first();

        $motivoCondicion = $condicion->motivo ?? null;

        $suministros = Suministro::with('articulo.tipoArticulo', 'articulo.modelo.marca')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisitasSeleccionada)
            ->get();



        $visitas = collect();
        $visitaSeleccionada = $orden->visitas->where('idVisitas', $idVisitasSeleccionada)->first();

        if ($visitaSeleccionada) {
            $visitas = collect([
                [
                    'nombre' => $visitaSeleccionada->nombre ?? 'N/A',
                    'fecha_programada' => $visitaSeleccionada->fecha_programada ? date('d/m/Y', strtotime($visitaSeleccionada->fecha_programada)) : 'N/A',
                    'hora_inicio' => $visitaSeleccionada->fecha_inicio ? date('H:i', strtotime($visitaSeleccionada->fecha_inicio)) : 'N/A',
                    'hora_final' => $visitaSeleccionada->fecha_final ? date('H:i', strtotime($visitaSeleccionada->fecha_final)) : 'N/A',
                    'fecha_llegada' => $visitaSeleccionada->fecha_llegada ? date('d/m/Y H:i', strtotime($visitaSeleccionada->fecha_llegada)) : 'N/A',
                    'tecnico' => ($visitaSeleccionada->tecnico->Nombre ?? 'N/A') . ' ' . ($visitaSeleccionada->tecnico->apellidoPaterno ?? ''),
                    'correo' => ($visitaSeleccionada->tecnico->correo ?? 'No disponible'),
                    'telefono' => ($visitaSeleccionada->tecnico->telefono ?? 'No registrado'),
                    'documento' => $visitaSeleccionada->tecnico->documento ?? 'No disponible',
                    'tipo_documento' => $visitaSeleccionada->tecnico->tipodocumento->nombre ?? 'Documento',
                    'vehiculo_placa' => $visitaSeleccionada->tecnico->vehiculo->numero_placa ?? 'Sin placa',
                ]
            ]);
        }

        $firma = DB::table('firmas')->where('idTickets', $idOt)
            ->where('idVisitas', $idVisitasSeleccionada)
            ->first();

        $firmaCliente = $firma && !empty($firma->firma_cliente)
            ? $this->optimizeBase64Image('data:image/png;base64,' . base64_encode($firma->firma_cliente))
            : null;

        $firmaTecnico = null;
        if ($visitaSeleccionada && $visitaSeleccionada->tecnico && !empty($visitaSeleccionada->tecnico->firma)) {
            $firmaTecnico = 'data:image/png;base64,' . base64_encode($visitaSeleccionada->tecnico->firma);
        }

        $imagenesAnexos = [];
        if ($visitaSeleccionada && $visitaSeleccionada->anexos_visitas) {
            $imagenesAnexos = $visitaSeleccionada->anexos_visitas->map(function ($anexo) {
                return [
                    'foto_base64' => !empty($anexo->foto)
                        ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($anexo->foto))
                        : null,
                    'descripcion' => $anexo->descripcion
                ];
            });
        }

        $imagenesCondiciones = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisitasSeleccionada)
            ->whereNotNull('imagen')
            ->get()
            ->map(function ($condicion) {
                return [
                    'foto_base64' => $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($condicion->imagen)),
                    'descripcion' => 'CONDICIÓN: ' . ($condicion->motivo ?? 'Sin descripción')
                ];
            });

        $imagenesAnexos = $imagenesAnexos->merge($imagenesCondiciones);

        $imagenesFotosTickets = $visitaSeleccionada->fotostickest->map(function ($foto) {
            return [
                'foto_base64' => !empty($foto->foto)
                    ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($foto->foto))
                    : null,
                'descripcion' => $foto->descripcion
            ];
        });

        $fechaCreacion = $visitaSeleccionada && $visitaSeleccionada->fecha_inicio
            ? date('d/m/Y', strtotime($visitaSeleccionada->fecha_inicio))
            : 'N/A';

        $vistaPdf = 'tickets.ordenes-trabajo.helpdesk.levantamiento.informe.pdf.index';

        $html = View($vistaPdf, [
            'orden' => $orden,
            'fechaCreacion' => $fechaCreacion,
            'producto' => $producto,
            'transicionesStatusOt' => $transicionesStatusOt,
            'visitas' => $visitas,
            'firma' => $firma,
            'firmaTecnico' => $firmaTecnico,
            'firmaCliente' => $firmaCliente,
            'imagenesAnexos' => $imagenesAnexos,
            'imagenesFotosTickets' => $imagenesFotosTickets,
            'emitente' => (object)['nome' => 'GKM TECHNOLOGY S.A.C.'],
            'logoClienteGeneral' => $logoClienteGeneral,
            'logoGKM' => $logoGKM,
            'suministros' => $suministros,
            'motivoCondicion' => $motivoCondicion,
            'modoVistaPrevia' => false
        ])->render();

        // 1. Guardar PDF temporal
        $tempOriginal = tempnam(sys_get_temp_dir(), 'pdf_raw_') . '.pdf';
        Browsershot::html($html)
            ->format('A4')
            ->fullPage()
            ->noSandbox()
            ->setDelay(2000)
            ->margins(2.5, 2.5, 2.5, 2.5)
            ->emulateMedia('screen')
            ->waitUntilNetworkIdle()
            ->showBackground()
            ->save($tempOriginal);

        // 2. Comprimir con Ghostscript
        $gs = 'C:\Program Files\gs\gs10.05.1\bin\gswin64c.exe'; // ruta exacta de tu instalación Ghostscript
        $tempCompressed = tempnam(sys_get_temp_dir(), 'pdf_compressed_') . '.pdf';

        $cmd = "\"{$gs}\" -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile=\"{$tempCompressed}\" \"{$tempOriginal}\"";
        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0 || !file_exists($tempCompressed) || filesize($tempCompressed) === 0) {
            @unlink($tempOriginal);
            return response()->json([
                'success' => false,
                'message' => 'Ghostscript no logró comprimir el PDF.',
                'command' => $cmd,
                'output' => $output
            ], 500);
        }

        // 3. Enviar el PDF comprimido
        $pdfOutput = file_get_contents($tempCompressed);
        @unlink($tempOriginal);
        @unlink($tempCompressed);

        return response($pdfOutput)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="LEVANTAMIENTO TECNICO ' . $orden->numero_ticket . '.pdf"');
    }


    public function generateLabPdfVisita($idOt)
    {
        $orden = Ticket::with([
            'cliente.tipodocumento',
            'clienteGeneral',
            'tecnico.tipodocumento',
            'tienda',
            'marca',
            'modelo.categoria',
            'transicion_status_tickets.estado_ot',
            'visitas.tecnico.tipodocumento',
            'visitas.anexos_visitas',
            'visitas.fotostickest',
        ])->findOrFail($idOt);

        $logoGKM = $this->procesarLogoMarca(file_get_contents(public_path('assets/images/auth/logogkm2.png')));

        $logoClienteGeneral = null;
        if ($orden->clienteGeneral && !empty($orden->clienteGeneral->foto)) {
            $logoClienteGeneral = $this->procesarLogoMarca($orden->clienteGeneral->foto);
        }


        $seleccionada = SeleccionarVisita::where('idTickets', $idOt)->first();
        if (!$seleccionada) {
            return response()->json(['success' => false, 'message' => 'No se encontró una visita seleccionada.']);
        }

        $idVisita = $seleccionada->idVisitas;
        $visitaSeleccionada = $orden->visitas->where('idVisitas', $idVisita)->first();

        $transicionesStatusOt = TransicionStatusTicket::where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->whereNotNull('justificacion')
            ->where('justificacion', '!=', '')
            ->with('estado_ot')
            ->get();

        $producto = [
            'categoria' => $orden->modelo->categoria->nombre ?? 'No especificado',
            'marca' => $orden->modelo->marca->nombre ?? 'No especificado',
            'modelo' => $orden->modelo->nombre ?? 'No especificado',
            'serie' => $orden->serie ?? 'No especificado',
            'fallaReportada' => $orden->fallaReportada ?? 'No especificado'
        ];

        $suministros = Suministro::with('articulo.tipoArticulo', 'articulo.modelo.marca')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->get();

        $equipos = Equipo::with(['modelo', 'marca', 'categoria'])
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->get();

        Log::debug('Equipos cargados:', ['equipos' => $equipos]);

        $equiposInstalados = $equipos->where('modalidad', 'Instalación')->map(function ($equipo) {
            return [
                'tipoProducto' => $equipo->categoria->nombre ?? 'Sin categoría',
                'modelo' => $equipo->modelo->nombre ?? 'Sin modelo',
                'marca' => $equipo->marca->nombre ?? 'Sin marca',
                'nserie' => $equipo->nserie ?? 'Sin serie',
                'observacion'  => $equipo->observaciones ?? 'Sin observación', // ✅ aquí
            ];
        });

        $equiposRetirados = $equipos->where('modalidad', 'Retirar')->map(function ($equipo) {
            return [
                'tipoProducto' => $equipo->categoria->nombre ?? 'Sin categoría',
                'modelo' => $equipo->modelo->nombre ?? 'Sin modelo',
                'marca' => $equipo->marca->nombre ?? 'Sin marca',
                'nserie' => $equipo->nserie ?? 'Sin serie',
                'observacion'  => $equipo->observaciones ?? 'Sin observación', // ✅ aquí
            ];
        });

        $condicion = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->first();

        $motivoCondicion = $condicion->motivo ?? null;




        $visitas = collect();
        if ($visitaSeleccionada) {
            $visitas = collect([[
                'nombre' => $visitaSeleccionada->nombre ?? 'N/A',
                'fecha_programada' => $visitaSeleccionada->fecha_programada ? date('d/m/Y', strtotime($visitaSeleccionada->fecha_programada)) : 'N/A',
                'hora_inicio' => $visitaSeleccionada->fecha_inicio ? date('H:i', strtotime($visitaSeleccionada->fecha_inicio)) : 'N/A',
                'hora_final' => $visitaSeleccionada->fecha_final ? date('H:i', strtotime($visitaSeleccionada->fecha_final)) : 'N/A',
                'fecha_llegada' => $visitaSeleccionada->fecha_llegada ? date('d/m/Y H:i', strtotime($visitaSeleccionada->fecha_llegada)) : 'N/A',
                'tecnico' => ($visitaSeleccionada->tecnico->Nombre ?? 'N/A') . ' ' . ($visitaSeleccionada->tecnico->apellidoPaterno ?? ''),
                'correo' => $visitaSeleccionada->tecnico->correo ?? 'No disponible',
                'telefono' => $visitaSeleccionada->tecnico->telefono ?? 'No registrado',
                'documento' => $visitaSeleccionada->tecnico->documento ?? 'No disponible',
                'tipo_documento' => $visitaSeleccionada->tecnico->tipodocumento->nombre ?? 'Documento',
                'vehiculo_placa' => $visitaSeleccionada->tecnico->vehiculo->numero_placa ?? 'Sin placa',
            ]]);
        }

        $firma = DB::table('firmas')->where('idTickets', $idOt)->where('idVisitas', $idVisita)->first();

        $firmaCliente = $firma && $firma->firma_cliente
            ? $this->optimizeBase64Image('data:image/png;base64,' . base64_encode($firma->firma_cliente))
            : null;

        $firmaTecnico = $visitaSeleccionada && $visitaSeleccionada->tecnico && $visitaSeleccionada->tecnico->firma
            ? 'data:image/png;base64,' . base64_encode($visitaSeleccionada->tecnico->firma)
            : null;

        $imagenesAnexos = $visitaSeleccionada->anexos_visitas->map(function ($anexo) {
            return [
                'foto_base64' => $anexo->foto
                    ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($anexo->foto))
                    : null,
                'descripcion' => $anexo->descripcion
            ];
        });

        $imagenesCondiciones = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->whereNotNull('imagen')
            ->get()
            ->map(function ($condicion) {
                return [
                    'foto_base64' => $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($condicion->imagen)),
                    'descripcion' => 'CONDICIÓN: ' . ($condicion->motivo ?? 'Sin descripción')
                ];
            });

        $imagenesAnexos = $imagenesAnexos->merge($imagenesCondiciones);


        $imagenesFotosTickets = $visitaSeleccionada->fotostickest->map(function ($foto) {
            return [
                'foto_base64' => $foto->foto
                    ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($foto->foto))
                    : null,
                'descripcion' => $foto->descripcion
            ];
        });

        $fechaCreacion = $visitaSeleccionada && $visitaSeleccionada->fecha_inicio
            ? date('d/m/Y', strtotime($visitaSeleccionada->fecha_inicio))
            : 'N/A';

        $html = view('tickets.ordenes-trabajo.helpdesk.laboratorio.informe.pdf.index', [
            'orden' => $orden,
            'fechaCreacion' => $fechaCreacion,
            'producto' => $producto,
            'transicionesStatusOt' => $transicionesStatusOt,
            'visitas' => $visitas,
            'firma' => $firma,
            'firmaTecnico' => $firmaTecnico,
            'firmaCliente' => $firmaCliente,
            'imagenesAnexos' => $imagenesAnexos,
            'imagenesFotosTickets' => $imagenesFotosTickets,
            'emitente' => (object)['nome' => 'GKM TECHNOLOGY S.A.C.'],
            'logoClienteGeneral' => $logoClienteGeneral,
            'logoGKM' => $logoGKM,
            'suministros' => $suministros,
            'motivoCondicion' => $motivoCondicion,
            'equiposInstalados' => $equiposInstalados,
            'equiposRetirados' => $equiposRetirados,
            'modoVistaPrevia' => false


        ])->render();

        // 1. Guardar PDF temporal
        $tempOriginal = tempnam(sys_get_temp_dir(), 'pdf_raw_') . '.pdf';
        Browsershot::html($html)
            ->format('A4')
            ->fullPage()
            ->noSandbox()
            ->setDelay(2000)
            ->margins(2.5, 2.5, 2.5, 2.5)
            ->emulateMedia('screen')
            ->waitUntilNetworkIdle()
            ->showBackground()
            ->save($tempOriginal);

        // 2. Comprimir con Ghostscript
        $gs = 'C:\Program Files\gs\gs10.05.1\bin\gswin64c.exe'; // ruta exacta de tu instalación Ghostscript
        $tempCompressed = tempnam(sys_get_temp_dir(), 'pdf_compressed_') . '.pdf';

        $cmd = "\"{$gs}\" -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile=\"{$tempCompressed}\" \"{$tempOriginal}\"";
        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0 || !file_exists($tempCompressed) || filesize($tempCompressed) === 0) {
            @unlink($tempOriginal);
            return response()->json([
                'success' => false,
                'message' => 'Ghostscript no logró comprimir el PDF.',
                'command' => $cmd,
                'output' => $output
            ], 500);
        }

        // 3. Enviar el PDF comprimido
        $pdfOutput = file_get_contents($tempCompressed);
        @unlink($tempOriginal);
        @unlink($tempCompressed);

        return response($pdfOutput)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="INFORME TECNICO ' . $orden->numero_ticket . '.pdf"');
    }

    public function generateLabPdfVisitaApp($idOt, $idVisita)
    {
        $orden = Ticket::with([
            'cliente.tipodocumento',
            'clienteGeneral',
            'tecnico.tipodocumento',
            'tienda',
            'marca',
            'modelo.categoria',
            'transicion_status_tickets.estado_ot',
            'visitas.tecnico.tipodocumento',
            'visitas.anexos_visitas',
            'visitas.fotostickest',
        ])->findOrFail($idOt);

        $logoGKM = $this->procesarLogoMarca(file_get_contents(public_path('assets/images/auth/logogkm2.png')));

        $logoClienteGeneral = null;
        if ($orden->clienteGeneral && !empty($orden->clienteGeneral->foto)) {
            $logoClienteGeneral = $this->procesarLogoMarca($orden->clienteGeneral->foto);
        }


        $idVisita = $idVisita;
        $visitaSeleccionada = $orden->visitas->where('idVisitas', $idVisita)->first();

        $transicionesStatusOt = TransicionStatusTicket::where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->whereNotNull('justificacion')
            ->where('justificacion', '!=', '')
            ->with('estado_ot')
            ->get();

        $producto = [
            'categoria' => $orden->modelo->categoria->nombre ?? 'No especificado',
            'marca' => $orden->modelo->marca->nombre ?? 'No especificado',
            'modelo' => $orden->modelo->nombre ?? 'No especificado',
            'serie' => $orden->serie ?? 'No especificado',
            'fallaReportada' => $orden->fallaReportada ?? 'No especificado'
        ];

        $suministros = Suministro::with('articulo.tipoArticulo', 'articulo.modelo.marca')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->get();

        $equipos = Equipo::with(['modelo', 'marca', 'categoria'])
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->get();

        Log::debug('Equipos cargados:', ['equipos' => $equipos]);

        $equiposInstalados = $equipos->where('modalidad', 'Instalación')->map(function ($equipo) {
            return [
                'tipoProducto' => $equipo->categoria->nombre ?? 'Sin categoría',
                'modelo' => $equipo->modelo->nombre ?? 'Sin modelo',
                'marca' => $equipo->marca->nombre ?? 'Sin marca',
                'nserie' => $equipo->nserie ?? 'Sin serie',
                'observacion'  => $equipo->observaciones ?? 'Sin observación', // ✅ aquí
            ];
        });

        $equiposRetirados = $equipos->where('modalidad', 'Retirar')->map(function ($equipo) {
            return [
                'tipoProducto' => $equipo->categoria->nombre ?? 'Sin categoría',
                'modelo' => $equipo->modelo->nombre ?? 'Sin modelo',
                'marca' => $equipo->marca->nombre ?? 'Sin marca',
                'nserie' => $equipo->nserie ?? 'Sin serie',
                'observacion'  => $equipo->observaciones ?? 'Sin observación', // ✅ aquí
            ];
        });

        $condicion = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->first();

        $motivoCondicion = $condicion->motivo ?? null;




        $visitas = collect();
        if ($visitaSeleccionada) {
            $visitas = collect([[
                'nombre' => $visitaSeleccionada->nombre ?? 'N/A',
                'fecha_programada' => $visitaSeleccionada->fecha_programada ? date('d/m/Y', strtotime($visitaSeleccionada->fecha_programada)) : 'N/A',
                'hora_inicio' => $visitaSeleccionada->fecha_inicio ? date('H:i', strtotime($visitaSeleccionada->fecha_inicio)) : 'N/A',
                'hora_final' => $visitaSeleccionada->fecha_final ? date('H:i', strtotime($visitaSeleccionada->fecha_final)) : 'N/A',
                'fecha_llegada' => $visitaSeleccionada->fecha_llegada ? date('d/m/Y H:i', strtotime($visitaSeleccionada->fecha_llegada)) : 'N/A',
                'tecnico' => ($visitaSeleccionada->tecnico->Nombre ?? 'N/A') . ' ' . ($visitaSeleccionada->tecnico->apellidoPaterno ?? ''),
                'correo' => $visitaSeleccionada->tecnico->correo ?? 'No disponible',
                'telefono' => $visitaSeleccionada->tecnico->telefono ?? 'No registrado',
                'documento' => $visitaSeleccionada->tecnico->documento ?? 'No disponible',
                'tipo_documento' => $visitaSeleccionada->tecnico->tipodocumento->nombre ?? 'Documento',
                'vehiculo_placa' => $visitaSeleccionada->tecnico->vehiculo->numero_placa ?? 'Sin placa',
            ]]);
        }

        $firma = DB::table('firmas')->where('idTickets', $idOt)->where('idVisitas', $idVisita)->first();

        $firmaCliente = $firma && $firma->firma_cliente
            ? $this->optimizeBase64Image('data:image/png;base64,' . base64_encode($firma->firma_cliente))
            : null;

        $firmaTecnico = $visitaSeleccionada && $visitaSeleccionada->tecnico && $visitaSeleccionada->tecnico->firma
            ? 'data:image/png;base64,' . base64_encode($visitaSeleccionada->tecnico->firma)
            : null;

        $imagenesAnexos = $visitaSeleccionada->anexos_visitas->map(function ($anexo) {
            return [
                'foto_base64' => $anexo->foto
                    ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($anexo->foto))
                    : null,
                'descripcion' => $anexo->descripcion
            ];
        });

        $imagenesCondiciones = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->whereNotNull('imagen')
            ->get()
            ->map(function ($condicion) {
                return [
                    'foto_base64' => $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($condicion->imagen)),
                    'descripcion' => 'CONDICIÓN: ' . ($condicion->motivo ?? 'Sin descripción')
                ];
            });

        $imagenesAnexos = $imagenesAnexos->merge($imagenesCondiciones);


        $imagenesFotosTickets = $visitaSeleccionada->fotostickest->map(function ($foto) {
            return [
                'foto_base64' => $foto->foto
                    ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($foto->foto))
                    : null,
                'descripcion' => $foto->descripcion
            ];
        });

        $fechaCreacion = $visitaSeleccionada && $visitaSeleccionada->fecha_inicio
            ? date('d/m/Y', strtotime($visitaSeleccionada->fecha_inicio))
            : 'N/A';

        $html = view('tickets.ordenes-trabajo.helpdesk.laboratorio.informe.pdf.index', [
            'orden' => $orden,
            'fechaCreacion' => $fechaCreacion,
            'producto' => $producto,
            'transicionesStatusOt' => $transicionesStatusOt,
            'visitas' => $visitas,
            'firma' => $firma,
            'firmaTecnico' => $firmaTecnico,
            'firmaCliente' => $firmaCliente,
            'imagenesAnexos' => $imagenesAnexos,
            'imagenesFotosTickets' => $imagenesFotosTickets,
            'emitente' => (object)['nome' => 'GKM TECHNOLOGY S.A.C.'],
            'logoClienteGeneral' => $logoClienteGeneral,
            'logoGKM' => $logoGKM,
            'suministros' => $suministros,
            'motivoCondicion' => $motivoCondicion,
            'equiposInstalados' => $equiposInstalados,
            'equiposRetirados' => $equiposRetirados,
            'modoVistaPrevia' => false


        ])->render();

        // 1. Guardar PDF temporal
        $tempOriginal = tempnam(sys_get_temp_dir(), 'pdf_raw_') . '.pdf';
        Browsershot::html($html)
            ->format('A4')
            ->fullPage()
            ->noSandbox()
            ->setDelay(2000)
            ->margins(2.5, 2.5, 2.5, 2.5)
            ->emulateMedia('screen')
            ->waitUntilNetworkIdle()
            ->showBackground()
            ->save($tempOriginal);

        // 2. Comprimir con Ghostscript
        $gs = 'C:\Program Files\gs\gs10.05.1\bin\gswin64c.exe'; // ruta exacta de tu instalación Ghostscript
        $tempCompressed = tempnam(sys_get_temp_dir(), 'pdf_compressed_') . '.pdf';

        $cmd = "\"{$gs}\" -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile=\"{$tempCompressed}\" \"{$tempOriginal}\"";
        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0 || !file_exists($tempCompressed) || filesize($tempCompressed) === 0) {
            @unlink($tempOriginal);
            return response()->json([
                'success' => false,
                'message' => 'Ghostscript no logró comprimir el PDF.',
                'command' => $cmd,
                'output' => $output
            ], 500);
        }

        // 3. Enviar el PDF comprimido
        $pdfOutput = file_get_contents($tempCompressed);
        @unlink($tempOriginal);
        @unlink($tempCompressed);

        return response($pdfOutput)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="INFORME TECNICO ' . $orden->numero_ticket . '.pdf"');
    }



    public function generateSoportePdf($idOt)
    {
        $orden = Ticket::with([
            'cliente.tipodocumento',
            'clienteGeneral',
            'tecnico.tipodocumento',
            'tienda',
            'marca',
            'modelo.categoria',
            'transicion_status_tickets.estado_ot',
            'visitas.tecnico.tipodocumento',
            'visitas.anexos_visitas',
            'visitas.fotostickest',
        ])->findOrFail($idOt);

        $logoGKM = $this->procesarLogoMarca(file_get_contents(public_path('assets/images/auth/logogkm2.png')));

        $logoClienteGeneral = null;
        if ($orden->clienteGeneral && !empty($orden->clienteGeneral->foto)) {
            $logoClienteGeneral = $this->procesarLogoMarca($orden->clienteGeneral->foto);
        }


        $seleccionada = SeleccionarVisita::where('idTickets', $idOt)->first();
        if (!$seleccionada) {
            return response()->json(['success' => false, 'message' => 'No se encontró una visita seleccionada.']);
        }

        $idVisita = $seleccionada->idVisitas;
        $visitaSeleccionada = $orden->visitas->where('idVisitas', $idVisita)->first();

        $transicionesStatusOt = TransicionStatusTicket::where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->whereNotNull('justificacion')
            ->where('justificacion', '!=', '')
            ->with('estado_ot')
            ->get();

        $producto = [
            'categoria' => $orden->modelo->categoria->nombre ?? 'No especificado',
            'marca' => $orden->modelo->marca->nombre ?? 'No especificado',
            'modelo' => $orden->modelo->nombre ?? 'No especificado',
            'serie' => $orden->serie ?? 'No especificado',
            'fallaReportada' => $orden->fallaReportada ?? 'No especificado'
        ];

        $suministros = Suministro::with('articulo.tipoArticulo', 'articulo.modelo.marca')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->get();

        $equipos = Equipo::with(['modelo', 'marca', 'categoria'])
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->get();

        Log::debug('Equipos cargados:', ['equipos' => $equipos]);

        $equiposInstalados = $equipos->where('modalidad', 'Instalación')->map(function ($equipo) {
            return [
                'tipoProducto' => $equipo->categoria->nombre ?? 'Sin categoría',
                'modelo' => $equipo->modelo->nombre ?? 'Sin modelo',
                'marca' => $equipo->marca->nombre ?? 'Sin marca',
                'nserie' => $equipo->nserie ?? 'Sin serie',
                'observacion'  => $equipo->observaciones ?? 'Sin observación', // ✅ aquí
            ];
        });

        $equiposRetirados = $equipos->where('modalidad', 'Retirar')->map(function ($equipo) {
            return [
                'tipoProducto' => $equipo->categoria->nombre ?? 'Sin categoría',
                'modelo' => $equipo->modelo->nombre ?? 'Sin modelo',
                'marca' => $equipo->marca->nombre ?? 'Sin marca',
                'nserie' => $equipo->nserie ?? 'Sin serie',
                'observacion'  => $equipo->observaciones ?? 'Sin observación', // ✅ aquí
            ];
        });

        $condicion = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->first();

        $motivoCondicion = $condicion->motivo ?? null;




        $visitas = collect();
        if ($visitaSeleccionada) {
            $visitas = collect([[
                'nombre' => $visitaSeleccionada->nombre ?? 'N/A',
                'fecha_programada' => $visitaSeleccionada->fecha_programada ? date('d/m/Y', strtotime($visitaSeleccionada->fecha_programada)) : 'N/A',
                'hora_inicio' => $visitaSeleccionada->fecha_inicio ? date('H:i', strtotime($visitaSeleccionada->fecha_inicio)) : 'N/A',
                'hora_final' => $visitaSeleccionada->fecha_final ? date('H:i', strtotime($visitaSeleccionada->fecha_final)) : 'N/A',
                'fecha_llegada' => $visitaSeleccionada->fecha_llegada ? date('d/m/Y H:i', strtotime($visitaSeleccionada->fecha_llegada)) : 'N/A',
                'tecnico' => ($visitaSeleccionada->tecnico->Nombre ?? 'N/A') . ' ' . ($visitaSeleccionada->tecnico->apellidoPaterno ?? ''),
                'correo' => $visitaSeleccionada->tecnico->correo ?? 'No disponible',
                'telefono' => $visitaSeleccionada->tecnico->telefono ?? 'No registrado',
                'documento' => $visitaSeleccionada->tecnico->documento ?? 'No disponible',
                'tipo_documento' => $visitaSeleccionada->tecnico->tipodocumento->nombre ?? 'Documento',
                'vehiculo_placa' => $visitaSeleccionada->tecnico->vehiculo->numero_placa ?? 'Sin placa',
            ]]);
        }

        $firma = DB::table('firmas')->where('idTickets', $idOt)->where('idVisitas', $idVisita)->first();

        $firmaCliente = $firma && $firma->firma_cliente
            ? $this->optimizeBase64Image('data:image/png;base64,' . base64_encode($firma->firma_cliente))
            : null;

        $firmaTecnico = $visitaSeleccionada && $visitaSeleccionada->tecnico && $visitaSeleccionada->tecnico->firma
            ? 'data:image/png;base64,' . base64_encode($visitaSeleccionada->tecnico->firma)
            : null;

        $imagenesAnexos = $visitaSeleccionada->anexos_visitas->map(function ($anexo) {
            return [
                'foto_base64' => $anexo->foto
                    ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($anexo->foto))
                    : null,
                'descripcion' => $anexo->descripcion
            ];
        });

        $imagenesCondiciones = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->whereNotNull('imagen')
            ->get()
            ->map(function ($condicion) {
                return [
                    'foto_base64' => $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($condicion->imagen)),
                    'descripcion' => 'CONDICIÓN: ' . ($condicion->motivo ?? 'Sin descripción')
                ];
            });

        $imagenesAnexos = $imagenesAnexos->merge($imagenesCondiciones);


        $imagenesFotosTickets = $visitaSeleccionada->fotostickest->map(function ($foto) {
            return [
                'foto_base64' => $foto->foto
                    ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($foto->foto))
                    : null,
                'descripcion' => $foto->descripcion
            ];
        });

        $fechaCreacion = $visitaSeleccionada && $visitaSeleccionada->fecha_inicio
            ? date('d/m/Y', strtotime($visitaSeleccionada->fecha_inicio))
            : 'N/A';

        $html = view('tickets.ordenes-trabajo.helpdesk.soporte.informe.pdf.index', [
            'orden' => $orden,
            'fechaCreacion' => $fechaCreacion,
            'producto' => $producto,
            'transicionesStatusOt' => $transicionesStatusOt,
            'visitas' => $visitas,
            'firma' => $firma,
            'firmaTecnico' => $firmaTecnico,
            'firmaCliente' => $firmaCliente,
            'imagenesAnexos' => $imagenesAnexos,
            'imagenesFotosTickets' => $imagenesFotosTickets,
            'emitente' => (object)['nome' => 'GKM TECHNOLOGY S.A.C.'],
            'logoClienteGeneral' => $logoClienteGeneral,
            'logoGKM' => $logoGKM,
            'suministros' => $suministros,
            'motivoCondicion' => $motivoCondicion,
            'equiposInstalados' => $equiposInstalados,
            'equiposRetirados' => $equiposRetirados,
            'modoVistaPrevia' => false


        ])->render();

        // 1. Guardar PDF temporal
        $tempOriginal = tempnam(sys_get_temp_dir(), 'pdf_raw_') . '.pdf';
        Browsershot::html($html)
            ->format('A4')
            ->fullPage()
            ->noSandbox()
            ->setDelay(2000)
            ->margins(2.5, 2.5, 2.5, 2.5)
            ->emulateMedia('screen')
            ->waitUntilNetworkIdle()
            ->showBackground()
            ->save($tempOriginal);

        // 2. Comprimir con Ghostscript
        $gs = 'C:\Program Files\gs\gs10.05.1\bin\gswin64c.exe'; // ruta exacta de tu instalación Ghostscript
        $tempCompressed = tempnam(sys_get_temp_dir(), 'pdf_compressed_') . '.pdf';

        $cmd = "\"{$gs}\" -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile=\"{$tempCompressed}\" \"{$tempOriginal}\"";
        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0 || !file_exists($tempCompressed) || filesize($tempCompressed) === 0) {
            @unlink($tempOriginal);
            return response()->json([
                'success' => false,
                'message' => 'Ghostscript no logró comprimir el PDF.',
                'command' => $cmd,
                'output' => $output
            ], 500);
        }

        // 3. Enviar el PDF comprimido
        $pdfOutput = file_get_contents($tempCompressed);
        @unlink($tempOriginal);
        @unlink($tempCompressed);

        return response($pdfOutput)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="INFORME TECNICO ' . $orden->numero_ticket . '.pdf"');
    }




    public function generateEjecucionPdf($idOt)
    {
        $orden = Ticket::with([
            'cliente.tipodocumento',
            'clienteGeneral',
            'tecnico.tipodocumento',
            'tienda',
            'marca',
            'modelo.categoria',
            'transicion_status_tickets.estado_ot',
            'visitas.tecnico.tipodocumento',
            'visitas.anexos_visitas',
            'visitas.fotostickest',
        ])->findOrFail($idOt);

        $logoGKM = $this->procesarLogoMarca(file_get_contents(public_path('assets/images/auth/logogkm2.png')));

        $logoClienteGeneral = null;
        if ($orden->clienteGeneral && !empty($orden->clienteGeneral->foto)) {
            $logoClienteGeneral = $this->procesarLogoMarca($orden->clienteGeneral->foto);
        }


        $seleccionada = SeleccionarVisita::where('idTickets', $idOt)->first();
        if (!$seleccionada) {
            return response()->json(['success' => false, 'message' => 'No se encontró una visita seleccionada.']);
        }

        $idVisita = $seleccionada->idVisitas;
        $visitaSeleccionada = $orden->visitas->where('idVisitas', $idVisita)->first();

        $transicionesStatusOt = TransicionStatusTicket::where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->whereNotNull('justificacion')
            ->where('justificacion', '!=', '')
            ->with('estado_ot')
            ->get();

        $producto = [
            'categoria' => $orden->modelo->categoria->nombre ?? 'No especificado',
            'marca' => $orden->modelo->marca->nombre ?? 'No especificado',
            'modelo' => $orden->modelo->nombre ?? 'No especificado',
            'serie' => $orden->serie ?? 'No especificado',
            'fallaReportada' => $orden->fallaReportada ?? 'No especificado'
        ];

        $suministros = Suministro::with('articulo.tipoArticulo', 'articulo.modelo.marca')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->get();

        $equipos = Equipo::with(['modelo', 'marca', 'categoria'])
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->get();

        Log::debug('Equipos cargados:', ['equipos' => $equipos]);

        $equiposInstalados = $equipos->where('modalidad', 'Instalación')->map(function ($equipo) {
            return [
                'tipoProducto' => $equipo->categoria->nombre ?? 'Sin categoría',
                'modelo' => $equipo->modelo->nombre ?? 'Sin modelo',
                'marca' => $equipo->marca->nombre ?? 'Sin marca',
                'nserie' => $equipo->nserie ?? 'Sin serie',
                'observacion'  => $equipo->observaciones ?? 'Sin observación', // ✅ aquí
            ];
        });

        $equiposRetirados = $equipos->where('modalidad', 'Retirar')->map(function ($equipo) {
            return [
                'tipoProducto' => $equipo->categoria->nombre ?? 'Sin categoría',
                'modelo' => $equipo->modelo->nombre ?? 'Sin modelo',
                'marca' => $equipo->marca->nombre ?? 'Sin marca',
                'nserie' => $equipo->nserie ?? 'Sin serie',
                'observacion'  => $equipo->observaciones ?? 'Sin observación', // ✅ aquí
            ];
        });

        $condicion = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->first();

        $motivoCondicion = $condicion->motivo ?? null;




        $visitas = collect();
        if ($visitaSeleccionada) {
            $visitas = collect([[
                'nombre' => $visitaSeleccionada->nombre ?? 'N/A',
                'fecha_programada' => $visitaSeleccionada->fecha_programada ? date('d/m/Y', strtotime($visitaSeleccionada->fecha_programada)) : 'N/A',
                'hora_inicio' => $visitaSeleccionada->fecha_inicio ? date('H:i', strtotime($visitaSeleccionada->fecha_inicio)) : 'N/A',
                'hora_final' => $visitaSeleccionada->fecha_final ? date('H:i', strtotime($visitaSeleccionada->fecha_final)) : 'N/A',
                'fecha_llegada' => $visitaSeleccionada->fecha_llegada ? date('d/m/Y H:i', strtotime($visitaSeleccionada->fecha_llegada)) : 'N/A',
                'tecnico' => ($visitaSeleccionada->tecnico->Nombre ?? 'N/A') . ' ' . ($visitaSeleccionada->tecnico->apellidoPaterno ?? ''),
                'correo' => $visitaSeleccionada->tecnico->correo ?? 'No disponible',
                'telefono' => $visitaSeleccionada->tecnico->telefono ?? 'No registrado',
                'documento' => $visitaSeleccionada->tecnico->documento ?? 'No disponible',
                'tipo_documento' => $visitaSeleccionada->tecnico->tipodocumento->nombre ?? 'Documento',
                'vehiculo_placa' => $visitaSeleccionada->tecnico->vehiculo->numero_placa ?? 'Sin placa',
            ]]);
        }

        $firma = DB::table('firmas')->where('idTickets', $idOt)->where('idVisitas', $idVisita)->first();

        $firmaCliente = $firma && $firma->firma_cliente
            ? $this->optimizeBase64Image('data:image/png;base64,' . base64_encode($firma->firma_cliente))
            : null;

        $firmaTecnico = $visitaSeleccionada && $visitaSeleccionada->tecnico && $visitaSeleccionada->tecnico->firma
            ? 'data:image/png;base64,' . base64_encode($visitaSeleccionada->tecnico->firma)
            : null;

        $imagenesAnexos = $visitaSeleccionada->anexos_visitas->map(function ($anexo) {
            return [
                'foto_base64' => $anexo->foto
                    ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($anexo->foto))
                    : null,
                'descripcion' => $anexo->descripcion
            ];
        });

        $imagenesCondiciones = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->whereNotNull('imagen')
            ->get()
            ->map(function ($condicion) {
                return [
                    'foto_base64' => $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($condicion->imagen)),
                    'descripcion' => 'CONDICIÓN: ' . ($condicion->motivo ?? 'Sin descripción')
                ];
            });

        $imagenesAnexos = $imagenesAnexos->merge($imagenesCondiciones);


        $imagenesFotosTickets = $visitaSeleccionada->fotostickest->map(function ($foto) {
            return [
                'foto_base64' => $foto->foto
                    ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($foto->foto))
                    : null,
                'descripcion' => $foto->descripcion
            ];
        });

        $fechaCreacion = $visitaSeleccionada && $visitaSeleccionada->fecha_inicio
            ? date('d/m/Y', strtotime($visitaSeleccionada->fecha_inicio))
            : 'N/A';

        $html = view('tickets.ordenes-trabajo.helpdesk.soporte.informe.pdf.index', [
            'orden' => $orden,
            'fechaCreacion' => $fechaCreacion,
            'producto' => $producto,
            'transicionesStatusOt' => $transicionesStatusOt,
            'visitas' => $visitas,
            'firma' => $firma,
            'firmaTecnico' => $firmaTecnico,
            'firmaCliente' => $firmaCliente,
            'imagenesAnexos' => $imagenesAnexos,
            'imagenesFotosTickets' => $imagenesFotosTickets,
            'emitente' => (object)['nome' => 'GKM TECHNOLOGY S.A.C.'],
            'logoClienteGeneral' => $logoClienteGeneral,
            'logoGKM' => $logoGKM,
            'suministros' => $suministros,
            'motivoCondicion' => $motivoCondicion,
            'equiposInstalados' => $equiposInstalados,
            'equiposRetirados' => $equiposRetirados,
            'modoVistaPrevia' => false


        ])->render();

        // 1. Guardar PDF temporal
        $tempOriginal = tempnam(sys_get_temp_dir(), 'pdf_raw_') . '.pdf';
        Browsershot::html($html)
            ->format('A4')
            ->fullPage()
            ->noSandbox()
            ->setDelay(2000)
            ->margins(2.5, 2.5, 2.5, 2.5)
            ->emulateMedia('screen')
            ->waitUntilNetworkIdle()
            ->showBackground()
            ->save($tempOriginal);

        // 2. Comprimir con Ghostscript
        $gs = 'C:\Program Files\gs\gs10.05.1\bin\gswin64c.exe'; // ruta exacta de tu instalación Ghostscript
        $tempCompressed = tempnam(sys_get_temp_dir(), 'pdf_compressed_') . '.pdf';

        $cmd = "\"{$gs}\" -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile=\"{$tempCompressed}\" \"{$tempOriginal}\"";
        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0 || !file_exists($tempCompressed) || filesize($tempCompressed) === 0) {
            @unlink($tempOriginal);
            return response()->json([
                'success' => false,
                'message' => 'Ghostscript no logró comprimir el PDF.',
                'command' => $cmd,
                'output' => $output
            ], 500);
        }

        // 3. Enviar el PDF comprimido
        $pdfOutput = file_get_contents($tempCompressed);
        @unlink($tempOriginal);
        @unlink($tempCompressed);

        return response($pdfOutput)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="INFORME TECNICO ' . $orden->numero_ticket . '.pdf"');
    }


    public function generateSoportePdfVisita($idOt, $idVisita)
    {
        $orden = Ticket::with([
            'cliente.tipodocumento',
            'clienteGeneral',
            'tecnico.tipodocumento',
            'tienda',
            'marca',
            'modelo.categoria',
            'transicion_status_tickets.estado_ot',
            'visitas.tecnico.tipodocumento',
            'visitas.anexos_visitas',
            'visitas.fotostickest',
        ])->findOrFail($idOt);

        $logoGKM = $this->procesarLogoMarca(file_get_contents(public_path('assets/images/auth/logogkm2.png')));

        $logoClienteGeneral = null;
        if ($orden->clienteGeneral && !empty($orden->clienteGeneral->foto)) {
            $logoClienteGeneral = $this->procesarLogoMarca($orden->clienteGeneral->foto);
        }

        $idVisita = $idVisita;
        $visitaSeleccionada = $orden->visitas->where('idVisitas', $idVisita)->first();

        $transicionesStatusOt = TransicionStatusTicket::where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->whereNotNull('justificacion')
            ->where('justificacion', '!=', '')
            ->with('estado_ot')
            ->get();

        $producto = [
            'categoria' => $orden->modelo->categoria->nombre ?? 'No especificado',
            'marca' => $orden->modelo->marca->nombre ?? 'No especificado',
            'modelo' => $orden->modelo->nombre ?? 'No especificado',
            'serie' => $orden->serie ?? 'No especificado',
            'fallaReportada' => $orden->fallaReportada ?? 'No especificado'
        ];

        $suministros = Suministro::with('articulo.tipoArticulo', 'articulo.modelo.marca')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->get();

        $equipos = Equipo::with(['modelo', 'marca', 'categoria'])
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->get();

        Log::debug('Equipos cargados:', ['equipos' => $equipos]);

        $equiposInstalados = $equipos->where('modalidad', 'Instalación')->map(function ($equipo) {
            return [
                'tipoProducto' => $equipo->categoria->nombre ?? 'Sin categoría',
                'modelo' => $equipo->modelo->nombre ?? 'Sin modelo',
                'marca' => $equipo->marca->nombre ?? 'Sin marca',
                'nserie' => $equipo->nserie ?? 'Sin serie',
            ];
        });

        $equiposRetirados = $equipos->where('modalidad', 'Retirar')->map(function ($equipo) {
            return [
                'tipoProducto' => $equipo->categoria->nombre ?? 'Sin categoría',
                'modelo' => $equipo->modelo->nombre ?? 'Sin modelo',
                'marca' => $equipo->marca->nombre ?? 'Sin marca',
                'nserie' => $equipo->nserie ?? 'Sin serie',
            ];
        });

        $condicion = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->first();

        $motivoCondicion = $condicion->motivo ?? null;



        $visitas = collect();
        if ($visitaSeleccionada) {
            $visitas = collect([[
                'nombre' => $visitaSeleccionada->nombre ?? 'N/A',
                'fecha_programada' => $visitaSeleccionada->fecha_programada ? date('d/m/Y', strtotime($visitaSeleccionada->fecha_programada)) : 'N/A',
                'hora_inicio' => $visitaSeleccionada->fecha_inicio ? date('H:i', strtotime($visitaSeleccionada->fecha_inicio)) : 'N/A',
                'hora_final' => $visitaSeleccionada->fecha_final ? date('H:i', strtotime($visitaSeleccionada->fecha_final)) : 'N/A',
                'fecha_llegada' => $visitaSeleccionada->fecha_llegada ? date('d/m/Y H:i', strtotime($visitaSeleccionada->fecha_llegada)) : 'N/A',
                'tecnico' => ($visitaSeleccionada->tecnico->Nombre ?? 'N/A') . ' ' . ($visitaSeleccionada->tecnico->apellidoPaterno ?? ''),
                'correo' => $visitaSeleccionada->tecnico->correo ?? 'No disponible',
                'telefono' => $visitaSeleccionada->tecnico->telefono ?? 'No registrado',
                'documento' => $visitaSeleccionada->tecnico->documento ?? 'No disponible',
                'tipo_documento' => $visitaSeleccionada->tecnico->tipodocumento->nombre ?? 'Documento',
                'vehiculo_placa' => $visitaSeleccionada->tecnico->vehiculo->numero_placa ?? 'Sin placa',
            ]]);
        }

        $firma = DB::table('firmas')->where('idTickets', $idOt)->where('idVisitas', $idVisita)->first();

        $firmaCliente = $firma && $firma->firma_cliente
            ? $this->optimizeBase64Image('data:image/png;base64,' . base64_encode($firma->firma_cliente))
            : null;

        $firmaTecnico = $visitaSeleccionada && $visitaSeleccionada->tecnico && $visitaSeleccionada->tecnico->firma
            ? 'data:image/png;base64,' . base64_encode($visitaSeleccionada->tecnico->firma)
            : null;

        $imagenesAnexos = $visitaSeleccionada->anexos_visitas->map(function ($anexo) {
            return [
                'foto_base64' => $anexo->foto
                    ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($anexo->foto))
                    : null,
                'descripcion' => $anexo->descripcion
            ];
        });

        $imagenesCondiciones = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->whereNotNull('imagen')
            ->get()
            ->map(function ($condicion) {
                return [
                    'foto_base64' => $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($condicion->imagen)),
                    'descripcion' => 'CONDICIÓN: ' . ($condicion->motivo ?? 'Sin descripción')
                ];
            });

        $imagenesAnexos = $imagenesAnexos->merge($imagenesCondiciones);

        $imagenesFotosTickets = $visitaSeleccionada->fotostickest->map(function ($foto) {
            return [
                'foto_base64' => $foto->foto
                    ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($foto->foto))
                    : null,
                'descripcion' => $foto->descripcion
            ];
        });

        $fechaCreacion = $visitaSeleccionada && $visitaSeleccionada->fecha_inicio
            ? date('d/m/Y', strtotime($visitaSeleccionada->fecha_inicio))
            : 'N/A';

        $html = view('tickets.ordenes-trabajo.helpdesk.soporte.informe.pdf.index', [
            'orden' => $orden,
            'fechaCreacion' => $fechaCreacion,
            'producto' => $producto,
            'transicionesStatusOt' => $transicionesStatusOt,
            'visitas' => $visitas,
            'firma' => $firma,
            'firmaTecnico' => $firmaTecnico,
            'firmaCliente' => $firmaCliente,
            'imagenesAnexos' => $imagenesAnexos,
            'imagenesFotosTickets' => $imagenesFotosTickets,
            'emitente' => (object)['nome' => 'GKM TECHNOLOGY S.A.C.'],
            'logoClienteGeneral' => $logoClienteGeneral,
            'logoGKM' => $logoGKM,
            'suministros' => $suministros,
            'motivoCondicion' => $motivoCondicion,
            'equiposInstalados' => $equiposInstalados,
            'equiposRetirados' => $equiposRetirados,
            'modoVistaPrevia' => false


        ])->render();

        // 1. Guardar PDF temporal
        $tempOriginal = tempnam(sys_get_temp_dir(), 'pdf_raw_') . '.pdf';
        Browsershot::html($html)
            ->format('A4')
            ->fullPage()
            ->noSandbox()
            ->setDelay(2000)
            ->margins(2.5, 2.5, 2.5, 2.5)
            ->emulateMedia('screen')
            ->waitUntilNetworkIdle()
            ->showBackground()
            ->save($tempOriginal);

        // 2. Comprimir con Ghostscript
        $gs = 'C:\Program Files\gs\gs10.05.1\bin\gswin64c.exe'; // ruta exacta de tu instalación Ghostscript
        $tempCompressed = tempnam(sys_get_temp_dir(), 'pdf_compressed_') . '.pdf';

        $cmd = "\"{$gs}\" -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile=\"{$tempCompressed}\" \"{$tempOriginal}\"";
        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0 || !file_exists($tempCompressed) || filesize($tempCompressed) === 0) {
            @unlink($tempOriginal);
            return response()->json([
                'success' => false,
                'message' => 'Ghostscript no logró comprimir el PDF.',
                'command' => $cmd,
                'output' => $output
            ], 500);
        }

        // 3. Enviar el PDF comprimido
        $pdfOutput = file_get_contents($tempCompressed);
        @unlink($tempOriginal);
        @unlink($tempCompressed);

        return response($pdfOutput)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="INFORME TECNICO ' . $orden->numero_ticket . '.pdf"');
    }








    private function buildInformeHelpdeskHtml($idOt, $idVisita, $modoVistaPrevia = false, $tipo = 'levantamiento')
    {
        $orden = Ticket::with([
            'cliente',
            'clienteGeneral',
            'tienda',
            'tecnico',
            'marca',
            'modelo.categoria',
            'transicion_status_tickets.estado_ot',
            'visitas.tecnico',
            'visitas.anexos_visitas',
            'visitas.fotostickest'
        ])->findOrFail($idOt);

        $logoGKM = $this->procesarLogoMarca(file_get_contents(public_path('assets/images/auth/logogkm2.png')));

        $logoClienteGeneral = null;
        if ($orden->clienteGeneral && !empty($orden->clienteGeneral->foto)) {
            $logoClienteGeneral = $this->procesarLogoMarca($orden->clienteGeneral->foto);
        }

        $visitaSeleccionada = $orden->visitas->firstWhere('idVisitas', $idVisita);

        $transicionesStatusOt = TransicionStatusTicket::where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->whereNotNull('justificacion')
            ->where('justificacion', '!=', '')
            ->with('estado_ot')
            ->get();

        $producto = [
            'categoria' => $orden->modelo->categoria->nombre ?? 'No especificado',
            'marca' => $orden->modelo->marca->nombre ?? 'No especificado',
            'modelo' => $orden->modelo->nombre ?? 'No especificado',
            'serie' => $orden->serie ?? 'No especificado',
            'fallaReportada' => $orden->fallaReportada ?? 'No especificado'
        ];

        $suministros = Suministro::with('articulo.tipoArticulo', 'articulo.modelo.marca')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->get();

        // 🔥 Equipos necesarios para SOPORTE
        $equipos = Equipo::with(['modelo', 'marca', 'categoria'])
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->get();

        $equiposInstalados = collect($equipos->where('modalidad', 'Instalación')->map(function ($equipo) {
            return [
                'tipoProducto' => $equipo->categoria->nombre ?? 'Sin categoría',
                'modelo' => $equipo->modelo->nombre ?? 'Sin modelo',
                'marca' => $equipo->marca->nombre ?? 'Sin marca',
                'nserie' => $equipo->nserie ?? 'Sin serie',
            ];
        }));

        $equiposRetirados = collect($equipos->where('modalidad', 'Retirar')->map(function ($equipo) {
            return [
                'tipoProducto' => $equipo->categoria->nombre ?? 'Sin categoría',
                'modelo' => $equipo->modelo->nombre ?? 'Sin modelo',
                'marca' => $equipo->marca->nombre ?? 'Sin marca',
                'nserie' => $equipo->nserie ?? 'Sin serie',
            ];
        }));

        $condicion = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->first();

        $motivoCondicion = $condicion->motivo ?? null;


        $visitas = collect();
        if ($visitaSeleccionada) {
            $visitas = collect([[
                'nombre' => $visitaSeleccionada->nombre ?? 'N/A',
                'hora_inicio' => optional($visitaSeleccionada->fecha_inicio)->format('H:i'),
                'hora_final' => optional($visitaSeleccionada->fecha_final)->format('H:i'),
                'fecha_llegada' => optional($visitaSeleccionada->fecha_llegada)->format('d/m/Y H:i'),
                'tecnico' => ($visitaSeleccionada->tecnico->Nombre ?? '') . ' ' . ($visitaSeleccionada->tecnico->apellidoPaterno ?? ''),
                'correo' => $visitaSeleccionada->tecnico->correo ?? 'No disponible',
                'telefono' => $visitaSeleccionada->tecnico->telefono ?? 'No registrado',
                'documento' => $visitaSeleccionada->tecnico->documento ?? 'No disponible',
                'vehiculo_placa' => $visitaSeleccionada->tecnico->vehiculo->numero_placa ?? 'Sin placa',
            ]]);
        }

        $firma = DB::table('firmas')->where('idTickets', $idOt)->where('idVisitas', $idVisita)->first();

        $firmaCliente = $firma && $firma->firma_cliente
            ? $this->optimizeBase64Image('data:image/png;base64,' . base64_encode($firma->firma_cliente))
            : null;

        $firmaTecnico = $visitaSeleccionada && $visitaSeleccionada->tecnico && $visitaSeleccionada->tecnico->firma
            ? 'data:image/png;base64,' . base64_encode($visitaSeleccionada->tecnico->firma)
            : null;

        $imagenesAnexos = $visitaSeleccionada->anexos_visitas->map(function ($anexo) {
            return [
                'foto_base64' => $anexo->foto ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($anexo->foto)) : null,
                'descripcion' => $anexo->descripcion
            ];
        });

        $imagenesCondiciones = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisita)
            ->whereNotNull('imagen')
            ->get()
            ->map(function ($condicion) {
                return [
                    'foto_base64' => $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($condicion->imagen)),
                    'descripcion' => 'CONDICIÓN: ' . ($condicion->motivo ?? 'Sin descripción')
                ];
            });

        $imagenesAnexos = $imagenesAnexos->merge($imagenesCondiciones);

        $imagenesFotosTickets = $visitaSeleccionada->fotostickest->map(function ($foto) {
            return [
                'foto_base64' => $foto->foto ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($foto->foto)) : null,
                'descripcion' => $foto->descripcion
            ];
        });

        $fechaCreacion = optional($visitaSeleccionada->fecha_inicio)->format('d/m/Y');

        $vista = $tipo === 'soporte'
            ? 'tickets.ordenes-trabajo.helpdesk.soporte.informe.pdf.index'
            : 'tickets.ordenes-trabajo.helpdesk.levantamiento.informe.pdf.index';

        return view($vista, [
            'orden' => $orden,
            'fechaCreacion' => $fechaCreacion,
            'producto' => $producto,
            'transicionesStatusOt' => $transicionesStatusOt,
            'visitas' => $visitas,
            'firma' => $firma,
            'firmaTecnico' => $firmaTecnico,
            'firmaCliente' => $firmaCliente,
            'imagenesAnexos' => $imagenesAnexos,
            'imagenesFotosTickets' => $imagenesFotosTickets,
            'emitente' => (object)['nome' => 'GKM TECHNOLOGY S.A.C.'],
            'logoClienteGeneral' => $logoClienteGeneral,
            'logoGKM' => $logoGKM,
            'suministros' => $suministros,
            'equiposInstalados' => $equiposInstalados,
            'equiposRetirados' => $equiposRetirados,
            'motivoCondicion' => $motivoCondicion,
            'modoVistaPrevia' => $modoVistaPrevia
        ])->render();
    }


    public function vistaPreviaImagen($idOt, $idVisita, $tipo = 'levantamiento')
    {
        try {
            Log::debug("Generando vista previa", ['idOt' => $idOt, 'idVisita' => $idVisita, 'tipo' => $tipo]);

            $html = $this->buildInformeHelpdeskHtml($idOt, $idVisita, true, $tipo);
        } catch (\Exception $e) {
            Log::error('Error al generar vista previa: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

        return response(
            Browsershot::html($html)
                ->windowSize(800, 1000)
                ->noSandbox()
                ->emulateMedia('screen')
                ->waitUntilNetworkIdle()
                ->screenshot(),
            200
        )->header('Content-Type', 'image/png');
    }


    public function firmaclienteLeva($id, $idVisitas)
    {
        // Obtener el ticket
        $ticket = Ticket::with(['marca', 'modelo', 'cliente', 'tecnico', 'tienda', 'ticketflujo.estadoFlujo', 'usuario'])->findOrFail($id);
        $orden = $ticket;
        $estadosOTS = DB::table('estado_ots')->get();
        $ticketId = $ticket->idTickets;

        // Verificar si ya existe una firma para el ticket y la visita
        $firmaExistente = DB::table('firmas')
            ->where('idTickets', $id)
            ->where('idVisitas', $idVisitas)
            ->first(); // Verificamos si ya existe una firma para esa visita y ticket

        // Si ya existe una firma, redirigimos a la página de error 404
        if ($firmaExistente) {
            return view("pages.error404"); // Mostrar error 404 si ya existe la firma
        }

        // Obtener la visita usando idVisitas
        $visita = DB::table('visitas')
            ->where('idVisitas', $idVisitas)
            ->where('idTickets', $id) // Verificamos que el idTickets de la visita coincida con el id del ticket
            ->first();

        // Verificamos que la visita exista, si no, devolver algún mensaje de error
        if (!$visita) {
            return view("pages.error404"); // Redirigimos a error 404 si la visita no existe
        }

        // Pasamos todos los datos a la vista
        return view("tickets.ordenes-trabajo.helpdesk.levantamiento.firmas.firmaClienteLeva", compact(
            'ticket',
            'orden',
            'estadosOTS',
            'ticketId',
            'idVisitas',
            'visita',
            'id'
        ));
    }








    public function firmaclienteSopo($id, $idVisitas)
    {
        // Obtener el ticket
        $ticket = Ticket::with(['marca', 'modelo', 'cliente', 'tecnico', 'tienda', 'ticketflujo.estadoFlujo', 'usuario'])->findOrFail($id);
        $orden = $ticket;
        $estadosOTS = DB::table('estado_ots')->get();
        $ticketId = $ticket->idTickets;

        // Verificar si ya existe una firma para el ticket y la visita
        $firmaExistente = DB::table('firmas')
            ->where('idTickets', $id)
            ->where('idVisitas', $idVisitas)
            ->first(); // Verificamos si ya existe una firma para esa visita y ticket

        // Si ya existe una firma, redirigimos a la página de error 404
        if ($firmaExistente) {
            return view("pages.error404"); // Mostrar error 404 si ya existe la firma
        }

        // Obtener la visita usando idVisitas
        $visita = DB::table('visitas')
            ->where('idVisitas', $idVisitas)
            ->where('idTickets', $id) // Verificamos que el idTickets de la visita coincida con el id del ticket
            ->first();

        // Verificamos que la visita exista, si no, devolver algún mensaje de error
        if (!$visita) {
            return view("pages.error404"); // Redirigimos a error 404 si la visita no existe
        }

        // Pasamos todos los datos a la vista
        return view("tickets.ordenes-trabajo.helpdesk.soporte.firmas.firmaClienteSopo", compact(
            'ticket',
            'orden',
            'estadosOTS',
            'ticketId',
            'idVisitas',
            'visita',
            'id'
        ));
    }




    public function firmaclienteEjecucion($id, $idVisitas)
    {
        // Obtener el ticket
        $ticket = Ticket::with(['marca', 'modelo', 'cliente', 'tecnico', 'tienda', 'ticketflujo.estadoFlujo', 'usuario'])->findOrFail($id);
        $orden = $ticket;
        $estadosOTS = DB::table('estado_ots')->get();
        $ticketId = $ticket->idTickets;

        // Verificar si ya existe una firma para el ticket y la visita
        $firmaExistente = DB::table('firmas')
            ->where('idTickets', $id)
            ->where('idVisitas', $idVisitas)
            ->first(); // Verificamos si ya existe una firma para esa visita y ticket

        // Si ya existe una firma, redirigimos a la página de error 404
        if ($firmaExistente) {
            return view("pages.error404"); // Mostrar error 404 si ya existe la firma
        }

        // Obtener la visita usando idVisitas
        $visita = DB::table('visitas')
            ->where('idVisitas', $idVisitas)
            ->where('idTickets', $id) // Verificamos que el idTickets de la visita coincida con el id del ticket
            ->first();

        // Verificamos que la visita exista, si no, devolver algún mensaje de error
        if (!$visita) {
            return view("pages.error404"); // Redirigimos a error 404 si la visita no existe
        }

        // Pasamos todos los datos a la vista
        return view("tickets.ordenes-trabajo.helpdesk.ejecucion.firmas.firmaClienteEjecu", compact(
            'ticket',
            'orden',
            'estadosOTS',
            'ticketId',
            'idVisitas',
            'visita',
            'id'
        ));
    }



    public function firmaclienteLab($id, $idVisitas)
    {
        // Obtener el ticket
        $ticket = Ticket::with(['marca', 'modelo', 'cliente', 'tecnico', 'tienda', 'ticketflujo.estadoFlujo', 'usuario'])->findOrFail($id);
        $orden = $ticket;
        $estadosOTS = DB::table('estado_ots')->get();
        $ticketId = $ticket->idTickets;

        // Verificar si ya existe una firma para el ticket y la visita
        $firmaExistente = DB::table('firmas')
            ->where('idTickets', $id)
            ->where('idVisitas', $idVisitas)
            ->first(); // Verificamos si ya existe una firma para esa visita y ticket

        // Si ya existe una firma, redirigimos a la página de error 404
        if ($firmaExistente) {
            return view("pages.error404"); // Mostrar error 404 si ya existe la firma
        }

        // Obtener la visita usando idVisitas
        $visita = DB::table('visitas')
            ->where('idVisitas', $idVisitas)
            ->where('idTickets', $id) // Verificamos que el idTickets de la visita coincida con el id del ticket
            ->first();

        // Verificamos que la visita exista, si no, devolver algún mensaje de error
        if (!$visita) {
            return view("pages.error404"); // Redirigimos a error 404 si la visita no existe
        }

        // Pasamos todos los datos a la vista
        return view("tickets.ordenes-trabajo.helpdesk.laboratorio.firmas.firmaLab", compact(
            'ticket',
            'orden',
            'estadosOTS',
            'ticketId',
            'idVisitas',
            'visita',
            'id'
        ));
    }


    public function guardarFirmaCliente(Request $request, $id, $idVisitas)
    {
        $request->validate([
            'firma' => 'required|string',
            'nombreEncargado' => 'nullable|string|max:255',
            'tipoDocumento' => 'nullable|string|max:255',
            'documento' => 'nullable|string|max:255',
        ]);

        $ticket = Ticket::findOrFail($id);

        $visitaExistente = DB::table('visitas')
            ->where('idVisitas', $idVisitas)
            ->where('idTickets', $ticket->idTickets)
            ->first();

        if (!$visitaExistente) {
            return response()->json(['message' => 'La combinación de idVisitas y idTickets no es válida.'], 400);
        }

        $firmaCliente = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->firma));

        $firmaExistente = DB::table('firmas')
            ->where('idTickets', $ticket->idTickets)
            ->where('idCliente', $ticket->idCliente)
            ->where('idVisitas', $idVisitas)
            ->first();

        if ($firmaExistente) {
            return response()->json(['message' => 'Ya existe una firma para este ticket, cliente y visita.'], 400);
        }

        DB::table('firmas')->insert([
            'firma_cliente' => $firmaCliente,
            'idTickets' => $ticket->idTickets,
            'idCliente' => $ticket->idCliente,
            'idVisitas' => $idVisitas,
            'nombreencargado' => $request->nombreEncargado,
            'tipodocumento' => $request->tipoDocumento,
            'documento' => $request->documento,
        ]);

        return response()->json(['message' => 'Firma creada correctamente'], 201);
    }






    public function obtenerClientes($idClienteGeneral)
    {
        $clientes = Cliente::where('cliente_general_id', $idClienteGeneral)->get();

        return response()->json($clientes);
    }



    public function guardarEstadoSoporte(Request $request)
    {

        try {
            // Validar los datos
            $request->validate([
                'idTickets' => 'required|integer|exists:tickets,idTickets',
                'idEstadoots' => 'required|integer|exists:estado_ots,idEstadoots',
                'justificacion' => 'nullable|string'
            ]);

            // Log de la entrada de datos
            Log::info('Datos recibidos en guardarEstado: ', $request->all());

            // Buscar el idVisitas en la tabla seleccionarvisita con base en el idTickets
            $seleccionarVisita = DB::table('seleccionarvisita')
                ->where('idTickets', $request->idTickets)
                ->first();

            // Verificar si encontramos un idVisitas
            if ($seleccionarVisita) {
                $idVisitas = $seleccionarVisita->idVisitas;
                Log::info("Visita encontrada: idVisitas = $idVisitas");
            } else {
                // Si no se encuentra, podría devolver un error o enviar `null`
                return response()->json(['success' => false, 'message' => 'No se encontró una visita asociada a este ticket.']);
            }

            // Buscar si ya existe un registro para este ticket, visita y estado
            $transicion = DB::table('transicion_status_ticket')
                ->where('idTickets', $request->idTickets)
                ->where('idVisitas', $idVisitas)
                ->where('idEstadoots', $request->idEstadoots)
                ->first();

            if ($transicion) {
                // Si existe, actualizar la justificación
                Log::info('Actualizando justificación para transicion: ', (array) $transicion);
                DB::table('transicion_status_ticket')
                    ->where('idTransicionStatus', $transicion->idTransicionStatus)
                    ->update([
                        'justificacion' => $request->justificacion,
                        'fechaRegistro' => now()
                    ]);
            } else {
                // Si no existe, crear un nuevo registro
                Log::info('Creando nuevo registro para la transicion de ticket');
                DB::table('transicion_status_ticket')->insert([
                    'idTickets' => $request->idTickets,
                    'idVisitas' => $idVisitas,
                    'idEstadoots' => $request->idEstadoots,
                    'justificacion' => $request->justificacion,
                    'fechaRegistro' => now(),
                    'estado' => 1 // Estado activo
                ]);
            }

            Log::info('Estado guardado correctamente.');

            return response()->json(['success' => true, 'message' => 'Estado guardado correctamente.']);
        } catch (\Exception $e) {
            Log::error('Error en guardarEstadoSoporte: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Hubo un error al guardar el estado.'], 500);
        }
    }

    public function exportHelpdeskToExcel(Request $request)
    {
        return Excel::download(
            new HelpdeskTicketExport(
                $request->startDate,
                $request->endDate
            ),
            'ordenes_helpdesk.xlsx'
        );
    }




    public function obtenerJustificacionSoporte(Request $request)
    {
        // Validar los parámetros
        $request->validate([
            'ticketId' => 'required|integer|exists:tickets,idTickets',
            'estadoId' => 'required|integer|exists:estado_ots,idEstadoots'
        ]);

        // Log de la entrada de datos
        Log::info('Datos recibidos en obtenerJustificacion: ', $request->all());

        // Buscar el idVisitas en la tabla seleccionarvisita con base en el idTickets
        $seleccionada = DB::table('seleccionarvisita')
            ->where('idTickets', $request->ticketId)
            ->first();  // Solo buscamos por ticketId

        if ($seleccionada) {
            // Log de la visita encontrada
            Log::info('Visita seleccionada encontrada, idVisitas = ' . $seleccionada->idVisitas);

            // Obtener los estados asociados a la visita
            $estados = DB::table('estado_ots')
                ->join('transicion_status_ticket', 'estado_ots.idEstadoots', '=', 'transicion_status_ticket.idEstadoots')
                ->where('transicion_status_ticket.idTickets', $request->ticketId)
                ->where('transicion_status_ticket.idVisitas', $seleccionada->idVisitas)
                ->select('estado_ots.idEstadoots', 'estado_ots.descripcion') // Cambié 'nombre' por 'descripcion'
                ->distinct()
                ->get();

            // Log de los estados obtenidos
            Log::info('Estados obtenidos para la visita: ', $estados->toArray());

            // Obtener la justificación de la transicion_status_ticket
            $transicion = DB::table('transicion_status_ticket')
                ->where('idTickets', $request->ticketId)
                ->where('idVisitas', $seleccionada->idVisitas)
                ->where('idEstadoots', $request->estadoId)
                ->first();

            if ($transicion) {
                Log::info('Justificación encontrada: ' . $transicion->justificacion);
                return response()->json([
                    'success' => true,
                    'justificacion' => $transicion->justificacion,
                    'estados' => $estados
                ]);
            } else {
                Log::info('No hay justificación guardada para este estado');
                return response()->json([
                    'success' => true,
                    'justificacion' => null,
                    'estados' => $estados
                ]);
            }
        } else {
            Log::info('No se encontró una visita seleccionada para este ticket.');
            return response()->json([
                'success' => false,
                'message' => 'No se encontró una visita seleccionada para este ticket.'
            ]);
        }
    }




    public function obtenerFirmaTecnico($id)
    {
        // Obtener el idVisitas directamente con una consulta más eficiente
        $idVisitas = DB::table('seleccionarvisita')
            ->where('idTickets', $id) // Relacionamos el idTickets que hemos recibido
            ->value('idVisitas'); // Obtenemos el idVisitas correspondiente

        if (!$idVisitas) {
            return response()->json(['error' => 'No se encontró la visita relacionada con este ticket'], 404);
        }

        // Obtener el idUsuario (técnico) de la tabla visitas
        $idUsuario = DB::table('visitas')
            ->where('idVisitas', $idVisitas)
            ->value('idUsuario'); // Obtenemos el idUsuario (técnico) asociado con esta visita

        if (!$idUsuario) {
            return response()->json(['error' => 'No se encontró el técnico asociado a esta visita'], 404);
        }

        // Obtener la firma del técnico desde la tabla usuarios
        $firmaTecnico = DB::table('usuarios')
            ->where('idUsuario', $idUsuario)
            ->value('firma'); // Obtenemos la firma en formato binario

        // Verificar si la firma existe
        if ($firmaTecnico) {
            // Si la firma es un string base64, la usamos directamente
            if (strpos($firmaTecnico, 'data:image/') !== false) {
                return response()->json(['firma' => $firmaTecnico]);
            }

            // Si la firma no está en base64, la convertimos
            $firmaTecnicoBase64 = base64_encode($firmaTecnico);
            return response()->json(['firma' => "data:image/png;base64,$firmaTecnicoBase64"]);
        }

        return response()->json(['firma' => null], 404);
    }


    public function eliminarProducto($id)
    {
        // Buscar el producto por id
        $producto = Equipo::find($id);

        // Verificar si el producto existe
        if ($producto) {
            // Eliminar el producto
            $producto->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }




    public function guardarVisitaSoporte(Request $request)
    {
        // Validación de los datos
        Log::info('Validando los datos de la visita', $request->all());
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_visita' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'encargado' => 'required|integer|exists:usuarios,idUsuario', // Validar que el encargado existe
            'necesita_apoyo' => 'nullable|in:0,1',  // Ahora se valida si es 0 o 1
            'recojo' => 'nullable|in:0,1',  // Validar el valor de recojo
            'tecnicos_apoyo' => 'nullable|array', // Si seleccionaron técnicos de apoyo
            'imagenVisita' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validar la imagen
        ]);

        Log::info('Datos validados correctamente');

        $fechaInicio = $request->fecha_visita . ' ' . $request->hora_inicio;
        $fechaFinal = $request->fecha_visita . ' ' . $request->hora_fin;

        Log::info('Fechas de inicio y fin: ', ['inicio' => $fechaInicio, 'fin' => $fechaFinal]);

        // Verificar si el técnico ya tiene una visita en ese rango de tiempo
        $visitasConflicto = DB::table('visitas')
            ->where('idUsuario', $request->encargado)
            ->where(function ($query) use ($fechaInicio, $fechaFinal) {
                $query->whereBetween('fecha_inicio_hora', [$fechaInicio, $fechaFinal])
                    ->orWhereBetween('fecha_final_hora', [$fechaInicio, $fechaFinal])
                    ->orWhere(function ($query) use ($fechaInicio, $fechaFinal) {
                        $query->where('fecha_inicio_hora', '<=', $fechaFinal)
                            ->where('fecha_final_hora', '>=', $fechaInicio);
                    });
            })
            ->exists(); // Retorna true si existe un conflicto de horario

        if ($visitasConflicto) {
            Log::warning('Conflicto de horario encontrado para el técnico', [
                'encargado' => $request->encargado,
                'fecha_inicio' => $fechaInicio,
                'fecha_fin' => $fechaFinal
            ]);
            return response()->json(['success' => false, 'message' => 'El técnico ya tiene una visita asignada en este horario.'], 400);
        }

        // Obtener el tipo de usuario del encargado
        $encargado = DB::table('usuarios')->where('idUsuario', $request->encargado)->first();
        $tipoServicio = 1; // Default para técnico (idTipoUsuario 1)

        // Asignar tipoServicio basado en el tipo de usuario
        if ($encargado->idTipoUsuario == 4) {
            $tipoServicio = 3; // Si el tipo de usuario es 4, asignamos 3 (por ejemplo, Chofer)
        }

        // Crear la nueva visita
        $visita = new Visita();
        $visita->nombre = $request->nombre;
        $visita->fecha_programada = $request->fecha_visita;
        $visita->fecha_inicio_hora = $fechaInicio;  // Concatenar fecha y hora
        $visita->fecha_final_hora = $fechaFinal; // Concatenar fecha y hora
        $visita->tipoServicio = $tipoServicio; // O el valor correspondiente que quieras

        $visita->idUsuario = $request->encargado;

        Log::info('Creando la visita', ['visita' => $visita]);

        // Asignar 0 o 1 a "necesita_apoyo"
        $visita->necesita_apoyo = $request->necesita_apoyo ?? 0;  // Si no se envió, asignar 0
        $visita->recojo = $request->recojo ?? 0;  // Asignar 0 si no se envió

        $visita->idTickets = $request->idTickets; // Asegúrate de pasar este valor desde el frontend

        // Guardar la visita
        $visita->save();

        Log::info('Visita guardada con éxito', ['visita_id' => $visita->idVisitas]);



        // Actualizar el campo 'ejecutor' en la tabla 'tickets' con el valor de 'encargado'
        // DB::table('tickets')
        //     ->where('idTickets', $visita->idTickets) // Aseguramos de que estamos actualizando el ticket correcto
        //     ->update([
        //         'ejecutor' => $request->encargado, // Asignamos el id del encargado al campo 'ejecutor'
        //     ]);

        // Log::info('Campo ejecutor actualizado en la tabla tickets', ['idTickets' => $visita->idTickets, 'ejecutor' => $request->encargado]);

        // Manejar la imagen (si se subió)
        if ($request->hasFile('imagenVisita')) {
            Log::info('Imagen recibida', ['imagen' => $request->file('imagenVisita')->getClientOriginalName()]);
            Log::info('Tipo de archivo de la imagen:', ['tipo' => $request->file('imagenVisita')->getMimeType()]);

            $imagen = file_get_contents($request->file('imagenVisita')->getRealPath());  // Leer archivo como binario

            // Guardar la imagen en la tabla 'imagenapoyosmart'
            DB::table('imagenapoyosmart')->insert([
                'imagen' => $imagen,
                'idVisitas' => $visita->idVisitas,
                'descripcion' => $request->nombre,  // Usar el nombre de la visita como descripción
            ]);

            Log::info('Imagen guardada correctamente en imagenapoyosmart');
        }

        // Guardar en la tabla anexos_visitas
        DB::table('anexos_visitas')->insert([
            'idVisitas' => $visita->idVisitas,  // Usamos el id de la visita recién creada
            'idTipovisita' => 1,  // El tipo de visita siempre es 1
            'foto' => null,  // Puedes pasar la foto aquí si tienes una, o dejarla como null
            'descripcion' => null,  // Puedes pasar la descripción aquí si tienes alguna, o dejarla como null
            'lat' => null,  // Puedes pasar la latitud aquí si la tienes, o dejarla como null
            'lng' => null,  // Puedes pasar la longitud aquí si la tienes, o dejarla como null
            'ubicacion' => null,  // Puedes pasar la ubicación aquí si la tienes, o dejarla como null
        ]);

        Log::info('Anexo de visita guardado correctamente');

        $encargado = DB::table('usuarios')->where('idUsuario', $request->encargado)->first();

        // Asignar el idEstadflujo basado en el tipo de encargado
        $idEstadflujo = ($encargado->idTipoUsuario == 1) ? 2 : 2;
        Log::info('Asignando idEstadflujo', ['idEstadflujo' => $idEstadflujo]);

        // Insertar en la tabla ticketflujo
        $ticketflujo = DB::table('ticketflujo')->insertGetId([  // Usamos insertGetId para obtener el ID
            'idTicket' => $visita->idTickets,
            'idEstadflujo' => $idEstadflujo,
            'idUsuario' => auth()->id(),  // Usuario autenticado
            'fecha_creacion' => now(),
        ]);

        Log::info('Flujo de ticket insertado con éxito', ['ticketflujo_id' => $ticketflujo]);

        // Actualizar el campo idTicketFlujo en la tabla tickets con el nuevo idTicketFlujo
        DB::table('tickets')
            ->where('idTickets', $visita->idTickets)
            ->update(['idTicketFlujo' => $ticketflujo]);

        Log::info('Actualización de idTicketFlujo completada');

        // Comprobar si necesita apoyo y si se seleccionaron técnicos de apoyo
        if ($visita->necesita_apoyo == 1 && $request->has('tecnicos_apoyo')) {
            Log::info('Guardando técnicos de apoyo', ['tecnicos_apoyo' => $request->tecnicos_apoyo]);

            // Guardar técnicos de apoyo en la tabla ticketapoyo
            foreach ($request->tecnicos_apoyo as $tecnicoId) {
                DB::table('ticketapoyo')->insert([
                    'idTecnico' => $tecnicoId,
                    'idTicket' => $visita->idTickets, // Usar el idTickets de la visita
                    'idVisita' => $visita->idVisitas,
                ]);
            }

            Log::info('Técnicos de apoyo guardados exitosamente');
        }


        // Aquí agregamos la parte que guarda la modificación en la tabla 'modificaciones'
        $usuarioAutenticado = auth()->user()->Nombre; // Obtener el nombre del usuario autenticado

        DB::table('modificaciones')->insert([
            'idTickets' => $visita->idTickets,
            'campo' => 'Crear Visita',
            'valor_antiguo' => 'Visita nueva',
            'valor_nuevo' => 'Visita nueva creada',
            'usuario' => $usuarioAutenticado,
            'fecha_modificacion' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info('Modificación registrada en la tabla modificaciones');



        return response()->json(['success' => true, 'message' => 'Visita guardada exitosamente']);
    }





    public function guardarSoporte(Request $request)
    {
        // Validar los datos
        $validator = Validator::make($request->all(), [
            'idTickets' => 'required|integer',
            'idVisitas' => 'required|integer',
            'titular' => 'required|integer',
            'nombre' => 'nullable|string|max:255',
            'dni' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:255',
            'servicio' => 'required|integer',
            'motivo' => 'nullable|string',
            'fecha_condicion' => 'required|date',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'ubicacion' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validación para la imagen
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Obtener el nombre del usuario autenticado
        $usuario = auth()->user();
        $nombreUsuario = $usuario ? $usuario->Nombre : null;  // Accede al nombre del usuario desde la tabla 'usuarios'

        // Guardar los datos en la base de datos
        $data = $request->all();
        $data['nombre_usuario'] = $nombreUsuario; // Añadir el nombre del usuario a los datos

        // Si se ha cargado una imagen, la convertimos a binario
        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $imagenBinaria = file_get_contents($imagen->getRealPath());
            $data['imagen'] = $imagenBinaria; // Asignamos la imagen binaria
        }

        // Guardar los datos de la condición
        $condicion = CondicionesTicket::create($data);

        // Insertar los datos en la tabla anexos_visitas
        $anexoData = [
            'idVisitas' => $request->idVisitas,
            'lat' => $request->lat,          // Latitud obtenida
            'lng' => $request->lng,          // Longitud obtenida
            'ubicacion' => $request->ubicacion,  // Dirección obtenida
            'foto' => null,                  // Foto (nula)
            'descripcion' => 'Inicio de servicio', // Descripción predeterminada
            'idTipovisita' => 4,             // idTipovisita siempre 4
        ];

        // Insertar en la tabla anexos_visitas
        DB::table('anexos_visitas')->insert($anexoData);

        // Actualizar la fecha_inicio en la tabla visitas
        $fechaInicio = now();  // Fecha y hora actuales

        // Actualizamos la tabla visitas con la fecha de inicio
        DB::table('visitas')
            ->where('idVisitas', $request->idVisitas)
            ->update(['fecha_inicio' => $fechaInicio]);



        // Si el servicio es igual a 1, creamos el ticketflujo y actualizamos el ticket
        if ($request->servicio == 1) {
            // Insertar en la tabla ticketflujo con idEstadflujo 9
            $ticketflujo = DB::table('ticketflujo')->insertGetId([
                'idTicket' => $request->idTickets,  // Usamos el idTickets de la solicitud
                'idEstadflujo' => 9,               // Siempre 9 como estado (o el estado que corresponda)
                'idUsuario' => auth()->id(),
                'fecha_creacion' => now(),          // Fecha actual
            ]);

            // Verificamos si la inserción fue exitosa
            if (!$ticketflujo) {
                return response()->json(['error' => 'Error al crear ticketflujo.'], 500);
            }

            // Actualizar el campo idTicketFlujo en la tabla tickets con el nuevo idTicketFlujo
            DB::table('tickets')
                ->where('idTickets', $request->idTickets)
                ->update(['idTicketFlujo' => $ticketflujo]);

            // Verificamos si la actualización fue exitosa
            if ($ticketflujo) {
                Log::info('ticketflujo creado correctamente con idTicketFlujo: ' . $ticketflujo);
            } else {
                Log::error('Error al actualizar ticketflujo en tickets.');
                return response()->json(['error' => 'Error al actualizar el ticketflujo.'], 500);
            }

            // Ahora actualizamos el campo estadovisita a 1 en la tabla visitas
            $actualizarVisita = DB::table('visitas')
                ->where('idVisitas', $request->idVisitas)
                ->update(['estadovisita' => 1]);  // Esto actualiza la variable estadovisita a 1

            // Verificamos si la actualización de visita fue exitosa
            if (!$actualizarVisita) {
                return response()->json(['error' => 'Error al actualizar estadovisita.'], 500);
            } else {
                Log::info('estado visita actualizado correctamente a 1 para idVisitas: ' . $request->idVisitas);
            }
        }

        if ($condicion) {
            return response()->json(['success' => true, 'message' => 'Condiciones guardadas correctamente.']);
        } else {
            return response()->json(['error' => 'Error al guardar las condiciones.'], 500);
        }
    }
}
