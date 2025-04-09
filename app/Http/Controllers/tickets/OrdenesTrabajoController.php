<?php

namespace App\Http\Controllers\tickets;

use App\Events\NotificacionNueva;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket; // Asegúrate de tener este modelo
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Clientegeneral; // Reemplaza con el modelo correcto
use App\Models\Tiposervicio; // Reemplaza con el modelo correcto
use App\Models\Usuario; // Reemplaza con el modelo correcto
use App\Models\Tipoticket; // Reemplaza con el modelo correcto
use App\Models\Cliente; // Reemplaza con el modelo correcto
use App\Models\ClienteClientegeneral;
use App\Models\EstadoFlujo;
use App\Models\Tienda; // Reemplaza con el modelo correcto
use App\Models\Marca; // Reemplaza con el modelo correcto
use App\Models\Modelo; // Reemplaza con el modelo correcto
use App\Models\Modificacion;
use App\Models\Ticketapoyo;
use App\Models\Tipodocumento; // Reemplaza con el modelo correcto
use App\Models\Visita;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketExport;
use App\Exports\HelpdeskTicketExport; // Importa el nuevo exportador
use App\Models\AnexosVisita;
use App\Models\Categoria;
use App\Models\CondicionesTicket;
use App\Models\Fotostickest;
use App\Models\SeleccionarVisita;
use App\Models\SolicitudEntrega;
use App\Models\TicketFlujo;
use App\Models\TransicionStatusTicket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; // Asegúrate de usar esta clase
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;

// use Barryvdh\DomPDF\Facade as PDF;

class OrdenesTrabajoController extends Controller
{
    // Mostrar la vista principal según el rol del usuario
    public function index()
    {
        // Obtener usuario autenticado y su rol
        $usuario = Auth::user();
        $rol = $usuario->rol->nombre ?? 'Sin Rol';

        // Obtener los datos necesarios
        $clientesGenerales = Clientegeneral::all();
        $tiposServicio = Tiposervicio::all();
        $usuarios = Usuario::where('idTipoUsuario', 4)->get();
        $tiposTickets = Tipoticket::all();
        $clientes = Cliente::all();
        $tiendas = Tienda::all();
        $marcas = Marca::all();
        $modelos = Modelo::all();

        // Determinar la carpeta de vistas según el rol
        $carpetaVista = match ($rol) {
            'COORDINACION SMART' => 'smart-tv',
            'ADMIN PRINCIPAL' => 'smart-tv',
            'helpdesk',
            'COORDINACION HELP DESK' => 'helpdesk',
            default => '',
        };

        if ($carpetaVista) {
            return view("tickets.ordenes-trabajo.$carpetaVista.index", compact(
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


    // Mostrar la vista principal según el rol del usuario
    public function smarttable()
    {
        // Obtener usuario autenticado y su rol
        $usuario = Auth::user();
        $rol = $usuario->rol->nombre ?? 'Sin Rol';
        // Obtener los datos necesarios
        $clientesGenerales = Clientegeneral::all();
        $tiposServicio = Tiposervicio::all();
        $usuarios = Usuario::where('idTipoUsuario', 1)->get();
        $tiposTickets = Tipoticket::all();
        $clientes = Cliente::all();
        $tiendas = Tienda::all();
        $marcas = Marca::all();
        $modelos = Modelo::with('categoria')->get();

        return view("tickets.ordenes-trabajo.smart-tv.index", compact(
            'clientesGenerales',
            'tiposServicio',
            'usuarios',
            'tiposTickets',
            'clientes',
            'tiendas',
            'marcas',
            'modelos'
        ));
    }


    // Cargar la vista de creación según el rol del usuario
    public function createsmart()
    {
        $usuario = Auth::user();
        $rol = $usuario->rol->nombre ?? 'Sin Rol';
        $departamentos = json_decode(file_get_contents(public_path('ubigeos/departamentos.json')), true);
        $clientesGenerales = ClienteGeneral::where('estado', 1)->get();
        $clientes = Cliente::where('estado', 1)->get();
        $tiendas = Tienda::all();
        $usuarios = Usuario::where('idTipoUsuario', 1)->get();
        $tiposServicio = Tiposervicio::all();
        $marcas = Marca::all();
        $modelos = Modelo::all();
        $tiposDocumento = Tipodocumento::all();
        $categorias = Categoria::all();







        return view("tickets.ordenes-trabajo.smart-tv.create", compact(
            'clientesGenerales',
            'clientes',
            'tiendas',
            'usuarios',
            'tiposServicio',
            'marcas',
            'modelos',
            'tiposDocumento',
            'departamentos',
            'categorias'
        ));
    }


    public function storesmart(Request $request)
    {
        try {
            Log::info('Inicio de la creación de orden de trabajo', ['data' => $request->all()]);

            // Validación de los datos
            $validatedData = $request->validate([
                'nroTicket' => 'required|string|max:255|unique:tickets,numero_ticket',
                'idClienteGeneral' => 'required|integer|exists:clientegeneral,idClienteGeneral',
                'idCliente' => 'required|integer|exists:cliente,idCliente',
                'idTienda' => 'nullable|integer|exists:tienda,idTienda', // Permitir que idTienda sea nullable
                'direccion' => 'required|string|max:255',
                'idMarca' => 'required|integer|exists:marca,idMarca',
                'idModelo' => 'required|integer|exists:modelo,idModelo',
                'serie' => 'required|string|max:255',
                'fechaCompra' => 'required|date_format:Y-m-d',
                'fecha_creacion' => 'required|date_format:Y-m-d H:i',
                'fallaReportada' => 'required|string',
                'linkubicacion' => 'required|string',
                'lat' => 'nullable|string|max:255',
                'lng' => 'nullable|string|max:255',
            ]);

            Log::info('Datos validados correctamente', ['validatedData' => $validatedData]);

            // Verificar si el cliente no es una tienda y se proporcionó un nombre de tienda
            $tiendaId = $validatedData['idTienda'];

            // Si no hay idTienda, significa que se está creando una tienda
            if (!$tiendaId && $request->has('nombreTienda') && $request->input('nombreTienda') !== "") {
                // Crear la tienda con los datos proporcionados
                $tienda = Tienda::create([
                    'nombre' => $request->input('nombreTienda'),
                ]);

                $tiendaId = $tienda->idTienda; // Guardar el ID de la tienda recién creada
                Log::info('Tienda creada', ['tienda' => $tienda]);
            }

            // Obtener la fecha y hora como una instancia de Carbon
            $fechaCreacion = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $validatedData['fecha_creacion']);


            // Crear la nueva orden de trabajo
            $ticket = Ticket::create([
                'numero_ticket' => $validatedData['nroTicket'],
                'idClienteGeneral' => $validatedData['idClienteGeneral'],
                'idCliente' => $validatedData['idCliente'],
                'idTienda' => $tiendaId,  // Guardar el idTienda (ya sea el existente o el creado)
                'direccion' => $validatedData['direccion'],
                'idMarca' => $validatedData['idMarca'],
                'idModelo' => $validatedData['idModelo'],
                'serie' => $validatedData['serie'],
                'fechaCompra' => $validatedData['fechaCompra'],
                'linkubicacion' => $validatedData['linkubicacion'],
                'fallaReportada' => $validatedData['fallaReportada'],
                'lat' => $validatedData['lat'],
                'lng' => $validatedData['lng'],
                'idUsuario' => auth()->id(),
                'fecha_creacion' => $fechaCreacion,  // Aseguramos que se guarda correctamente
                'idTipotickets' => 1,
                'tipoServicio' => 1,
            ]);

            Log::info('Orden de trabajo creada correctamente', ['ticket' => $ticket]);

            // Verificar si el checkbox "Entrega a Lab" está marcado
            if ($request->has('entregaLab') && $request->input('entregaLab') === 'on') {
                // Si "Entrega a Lab" está marcado, solo se crea la visita y su flujo relacionado

                // Crear la visita
                $visita = Visita::create([
                    'nombre' => 'Laboratorio',  // El nombre de la visita
                    'fecha_programada' => now(),  // Fecha y hora programada: ahora
                    'fecha_asignada' => now(),    // Fecha de asignación: ahora
                    'fechas_desplazamiento' => null, // Asumimos que este campo puede ser null
                    'fecha_llegada' => null,  // Asumimos que este campo puede ser null
                    'fecha_inicio' => null,  // El campo 'fecha_inicio' debe ser null
                    'fecha_final' => null,  // El campo 'fecha_final' debe ser null
                    'estado' => 1,  // Estado inicial (puedes ajustarlo según sea necesario)
                    'idTickets' => $ticket->idTickets,  // El ID del ticket relacionado
                    'idUsuario' => $this->obtenerIdUsuario(),  // Llamar a la función para obtener el idUsuario correcto
                    'fecha_inicio_hora' => null,  // Este campo puede ser null
                    'fecha_final_hora' => null,  // Este campo puede ser null
                    'necesita_apoyo' => 0,  // Necesita apoyo (por defecto se establece en 0)
                    'tipoServicio' => 7,  // Tipo de servicio es 7, según lo solicitado
                ]);

                Log::info('Visita creada correctamente para laboratorio', ['visita' => $visita]);

                // Crear el flujo de trabajo con idEstadflujo = 10 (estado para "entrega a laboratorio")
                $ticketFlujoId = DB::table('ticketflujo')->insertGetId([
                    'idTicket' => $ticket->idTickets,
                    'idEstadflujo' => 10,  // Estado de flujo "entrega a laboratorio"
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
            } else {
                // Si "Entrega a Lab" no está marcado, se maneja el flujo "es recojo"
                $estadoFlujo = $request->has('esRecojo') && $request->input('esRecojo') === 'on' ? 8 : 1;

                $ticketFlujoId = DB::table('ticketflujo')->insertGetId([
                    'idTicket' => $ticket->idTickets,
                    'idEstadflujo' => $estadoFlujo,  // Estado inicial de flujo
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
            }

            // Redirigir a la vista de edición del ticket con el ID del ticket recién creado
            return redirect()->route('ordenes.edit', ['id' => $ticket->idTickets])
                ->with('success', 'Orden de trabajo creada correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // En caso de error en la validación
            Log::error('Errores de validación', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // En caso de cualquier otro error
            Log::error('Error al crear la orden de trabajo', ['exception' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Ocurrió un error al crear la orden de trabajo.');
        }
    }


    /**
     * Función para obtener el idUsuario correcto para la visita
     */
    private function obtenerIdUsuario()
    {
        $idUsuarioAutenticado = auth()->id(); // Obtener el ID del usuario autenticado

        // Si el idUsuario autenticado es 6 o 7, se asigna ese ID
        if (in_array($idUsuarioAutenticado, [6, 7])) {
            return $idUsuarioAutenticado;
        }

        // Si el idUsuario autenticado no es ni 6 ni 7, seleccionamos aleatoriamente entre 6 y 7
        return rand(6, 7); // Seleccionar aleatoriamente entre 6 y 7
    }




    public function validarTicket($nroTicket)
    {
        // Verifica si el número de ticket ya existe
        $ticketExistente = Ticket::where('numero_ticket', $nroTicket)->exists();

        // Devuelve la respuesta en formato JSON
        return response()->json([
            'existe' => $ticketExistente
        ]);
    }






    public function edit($id)
    {
        $usuario = Auth::user();
        $rol = $usuario->rol->nombre ?? 'Sin Rol';

        $orden = Ticket::with(['marca', 'modelo', 'cliente', 'tecnico', 'tienda', 'ticketflujo', 'usuario'])->findOrFail($id);
        $ticket = Ticket::with(['marca', 'modelo', 'cliente', 'tecnico', 'tienda', 'ticketflujo.estadoFlujo', 'usuario'])->findOrFail($id);

        // Obtener el estado de flujo y el color del ticket
        $colorEstado = $orden->ticketflujo && $orden->ticketflujo->estadoFlujo ? $orden->ticketflujo->estadoFlujo->color : '#FFFFFF';  // color por defecto si no se encuentra


        // Obtener el cliente correspondiente
$cliente = $orden->cliente;  // Asumimos que 'cliente' está relacionado con el Ticket.

// Verificar si el cliente tiene la propiedad 'tienda' y si es igual a 1 o 0
$esTiendacliente = ($cliente && isset($cliente->esTienda) && $cliente->esTienda == 1) ? 1 : 0;
Log::info("Cliente ID: {$cliente->id}, Tienda: {$cliente->esTienda}, esTiendacliente: {$esTiendacliente}");



        // Obtener los estados desde la tabla estado_ots
        // $estadosOTS = DB::table('estado_ots')->get();
        // Verificar si la visita seleccionada tiene un tipo de usuario


        // Obtener el idTickets
        $ticketId = $ticket->idTickets;

        // Verificar si existe un flujo con idEstadflujo = 4
        $flujo = TicketFlujo::where('idTicket', $ticketId)
            ->where('idEstadflujo', 4)
            ->first();

        $existeFlujo4 = $flujo ? true : false;  // Si existe flujo con idEstadflujo 4, establecer como verdadero

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


        $visitaExistente = $visita ? true : false;

        $visitaId = $visita ? $visita->idVisitas : null; // Si no hay visita, será null

        $tipoServicio = $visita ? $visita->tipoServicio : null;



        // Verificar si existe una transición en transicion_status_ticket con idEstadoots = 4
        $transicionExistente = DB::table('transicion_status_ticket')
            ->where('idTickets', $ticketId)
            ->where('idVisitas', $visitaId)
            ->where('idEstadoots', 4)
            ->exists(); // Devuelve true si existe, false si no

        // Verificar si la visita seleccionada está registrada en la tabla seleccionarvisita
        $visitaSeleccionada = DB::table('seleccionarvisita')
            ->where('idTickets', $ticketId)
            ->where('idVisitas', $visitaId)
            ->exists(); // Devuelve true si la visita está seleccionada, false si no


        // Acceder al idTicketFlujo desde la relación 'ticketflujo'
        $idTicketFlujo = $orden->ticketflujo ? $orden->ticketflujo->idTicketFlujo : null;

        // Acceder al idEstadflujo desde la relación 'ticketflujo'
        $idEstadflujo = $orden->ticketflujo ? $orden->ticketflujo->idEstadflujo : null;

        // Obtener la descripción del estado de flujo desde la relación 'estadoFlujo'
        $descripcionEstadoFlujo = $orden->ticketflujo && $orden->ticketflujo->estadoFlujo ? $orden->ticketflujo->estadoFlujo->descripcion : 'Sin estado de flujo';

        // Obtener todos los estados de flujo para este ticket
        $estadosFlujo = DB::table('ticketflujo')
            ->join('estado_flujo', 'ticketflujo.idEstadflujo', '=', 'estado_flujo.idEstadflujo')
            ->where('ticketflujo.idTicket', $ticketId)  // Solo estados para este ticket
            ->select('estado_flujo.descripcion', 'ticketflujo.fecha_creacion', 'estado_flujo.color')
            ->orderBy('ticketflujo.fecha_creacion', 'asc')  // Opcional: Ordenar por fecha de creación
            ->get();

        // Otros datos que ya estás obteniendo
        // $encargado = Usuario::whereIn('idTipoUsuario', [1, 5])->get();

        // Asumiendo que tienes el colorEstado ya disponible en tu controlador
        // $colorEstado = '#B5FA37'; // Este color lo puedes obtener dinámicamente según lo que estés trabajando

        // Filtrar los encargados según el color del estado
        if ($colorEstado == '#B5FA37') {
            // Si el colorEstado es #B5FA37, solo obtener los usuarios de tipo 1 (TÉCNICO)
            $encargado = Usuario::where('idTipoUsuario', 4)->get();
        } elseif ($colorEstado == '#FBCACD') {
            // Si el colorEstado es #FBCACD, solo obtener los usuarios de tipo 5 (CHOFER)
            $encargado = Usuario::where('idTipoUsuario', 1)->get();
        } else {
            // Si no es ninguno de esos colores, traer ambos tipos (1 y 5)
            $encargado = Usuario::whereIn('idTipoUsuario', [1, 4])->get();
        }




        $tecnicos_apoyo = Usuario::where('idTipoUsuario', 1)->get();
        $clientes = Cliente::all();
        $clientesGenerales = Clientegeneral::all();
        $modelos = Modelo::all();
        $tiendas = Tienda::all();
        $marcas = Marca::all();

        // Buscar en la tabla tickets el idTicketFlujo correspondiente al ticket
        $ticket = DB::table('tickets')->where('idTickets', $id)->first();

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
                } elseif ($idEstadflujo == 3) {
                    // Si el idEstadflujo es 3, solo mostrar los estados con idEstadflujo 4
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 4)  // Solo obtener el estado con idEstadflujo 4
                        ->get();
                } elseif ($idEstadflujo == 2) {
                    // Si el idEstadflujo es 2, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [3, 10])  // Obtener los estados con idEstadflujo 3 y 4
                        ->get();
                } elseif ($idEstadflujo == 11) {
                    // Si el idEstadflujo es 3, solo mostrar los estados con idEstadflujo 4
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [12, 13, 14, 15, 16, 17])  // Obtener los estados con idEstadflujo 3 y 4
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

                    } elseif ($idEstadflujo == 14) {
                        // Si el idEstadflujo del ticketflujo es 9, solo mostrar los estados con idEstadflujo 3
                        $estadosFlujo = DB::table('estado_flujo')
                            ->where('idEstadflujo', 3)  // Solo obtener el estado con idEstadflujo 3
                            ->get();

                } elseif ($idEstadflujo == 8) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [3, 1])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } else {
                    // Si no tiene idEstadflujo = 1, 3, 8 o 9, verificar si es 6 o 7
                    if (in_array($idEstadflujo, [6, 7])) {
                        // Si tiene idEstadflujo 6 o 7, solo traer los estados con idEstadflujo 4
                        $estadosFlujo = DB::table('estado_flujo')
                            ->where('idEstadflujo', 4)  // Solo obtener el estado 4
                            ->get();
                    } else {
                        // Si no cumple ninguna de las condiciones anteriores, mostrar todos los estados
                        $estadosFlujo = DB::table('estado_flujo')->get();
                    }
                }
            }
        }

        // Verificar si existe una condición para este ticket y visita
        $condicion = DB::table('condicionesticket')
            ->where('idTickets', $ticketId)
            ->where('idVisitas', $visitaId)
            ->exists(); // Devuelve true si existe, false si no

        // Obtener todas las visitas asociadas con el ticket
        $visitas = DB::table('visitas')
            ->where('idTickets', $ticketId)  // Asumiendo que $ticketId es el id del ticket actual
            ->get();

        // Verificar si alguna visita tiene una condición
        $condicionExistente = DB::table('condicionesticket')
            ->whereIn('idVisitas', $visitas->pluck('idVisitas'))  // Obtener las visitas asociadas al ticket
            ->exists();

        // Obtener todas las visitas asociadas a un ticket, ordenadas por el nombre
        $visitas = DB::table('visitas')
            ->where('idTickets', $ticketId)
            ->orderBy('nombre', 'desc')  // Ordenar por el nombre de la visita (para obtener la última visita con el nombre más alto)
            ->get();

        // Verificar si hay visitas
        $ultimaVisita = $visitas->first();  // Obtener la última visita (la más alta en el orden alfabético)

        $ultimaVisitaCondicion = null;
        if ($ultimaVisita) {
            // Verificar si existe una condición para esta última visita
            $ultimaVisitaCondicion = DB::table('condicionesticket')
                ->where('idTickets', $ticketId)
                ->where('idVisitas', $ultimaVisita->idVisitas)
                ->exists();  // Devuelve true si existe una condición
        }


        $condicionServicio = false;  // Valor por defecto

        if ($visitaSeleccionada) {
            // Verificar si la visita seleccionada tiene una condición con servicio = 1
            $condicionServicio = DB::table('condicionesticket')
                ->where('idTickets', $ticketId)
                ->where('idVisitas', $visitaId)
                ->where('servicio', 1)
                ->exists();
        }



        // Inicializamos la variable con un valor predeterminado
        $condicionexistevisita = false;  // Valor por defecto

        // Si la visita está seleccionada, proceder a verificar la condición
        if ($visitaSeleccionada) {
            // Verificar si existe una condición para este ticket y visita (sin considerar el servicio)
            $condicionexistevisita = DB::table('condicionesticket')
                ->where('idTickets', $ticketId)  // Filtrar por idTickets
                ->where('idVisitas', $visitaId)  // Filtrar por idVisitas (la visita seleccionada)
                ->exists();  // Devuelve true si existe una fila que cumple con los criterios
        }


        // Inicializamos la variable con un valor predeterminado
        $titular = null;  // Valor por defecto, puede ser 0 o 1

        // Si la visita está seleccionada, proceder a verificar la condición
        if ($visitaSeleccionada) {
            // Verificar si existe una condición para este ticket y visita
            $titular = DB::table('condicionesticket')
                ->where('idTickets', $ticketId)  // Filtrar por idTickets
                ->where('idVisitas', $visitaId)  // Filtrar por idVisitas (la visita seleccionada)
                ->value('titular');  // Obtener el valor de la columna 'titular'
        }

        // Si titular tiene un valor, puedes usarlo para comprobar si es 0 o 1
        if ($titular === 0) {
            // Acción para cuando titular es 0
        } elseif ($titular === 1) {
            // Acción para cuando titular es 1
        } else {
            // Acción si titular no es 0 ni 1 (en caso de que no tenga un valor definido)
        }



        $tieneTresOMasFotos = false;    // Valor por defecto


        // Si la visita está seleccionada, proceder a verificar las condiciones
        if ($visitaSeleccionada) {

            // Verificar si hay 3 o más fotos para este ticket y visita
            $tieneTresOMasFotos = DB::table('fotostickest')
                ->where('idTickets', $ticketId)  // Filtrar por idTickets
                ->where('idVisitas', $visitaId)  // Filtrar por idVisitas (la visita seleccionada)
                ->count() >= 3;  // Devuelve true si hay 3 o más fotos
        }



        $numeroVisitas = DB::table('visitas')
            ->where('idTickets', $ticketId)  // Filtramos por el idTickets del ticket actual
            ->count();  // Contamos las filas que cumplen la condición



        $visitasConCondiciones = DB::table('condicionesticket')
            ->whereIn('idVisitas', $visitas->pluck('idVisitas'))  // Filtramos por las visitas del ticket
            ->exists();  // Devuelve true si al menos una visita tiene una condición



        $visitaConCondicion = false;    // Valor por defecto
        // Si la visita está seleccionada, proceder a verificar las condiciones
        if ($visitaSeleccionada) {

            // Verificar si la visita tiene una condición
            $visitaConCondicion = DB::table('condicionesticket')
                ->where('idTickets', $ticketId)
                ->where('idVisitas', $visitaId)
                ->exists(); // Devuelve true si la visita tiene una condición, false si no
        }




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


        if ($tipoUsuario == 1) {
            // Si el tipo de usuario es 4, obtener solo los estados con idEstadoots 1, 2, o 3
            $estadosOTS = DB::table('estado_ots')
                ->whereIn('idEstadoots', [1, 2, 3, 4])  // Filtrar solo por los estados 1, 2, 3
                ->get();
        } elseif ($tipoUsuario == 4) {
            // Si el tipo de usuario es 5, obtener solo los estados con idEstadoots 1 o 2
            $estadosOTS = DB::table('estado_ots')
                ->whereIn('idEstadoots', [1, 2, 3, 4, 7])  // Filtrar solo por los estados 1 y 2
                ->get();
        } else {
            // Si el tipo de usuario no es ni 4 ni 5, obtener todos los estados (o un filtro predeterminado)
            $estadosOTS = DB::table('estado_ots')->get();  // Obtener todos los estados si no es tipo 4 ni 5
        }

        // dd($tipoUsuario);  // Esto debería mostrar el valor de tipoUsuario



        $idtipoServicio = null;  // Inicializamos la variable para el tipo de servicio

        // Consulta para obtener el idVisita de la visita seleccionada para ese ticket
        $idVisitaSeleccionada = DB::table('seleccionarvisita')
            ->where('idTickets', $ticketId)  // Filtro por ticketId
            ->value('idVisitas');  // Obtenemos el idVisitas de la visita seleccionada para ese ticket

        Log::info('Visita seleccionada, idVisita: ' . $idVisitaSeleccionada); // Log de la visita seleccionada

        // Si se encuentra una visita seleccionada
        if ($idVisitaSeleccionada) {

            // Obtener el tipoServicio de la visita seleccionada
            $idtipoServicio = DB::table('visitas')
                ->where('idTickets', $ticketId)  // Filtro por ticketId
                ->where('idVisitas', $idVisitaSeleccionada)  // Filtro por idVisita seleccionada
                ->value('tipoServicio');  // Obtenemos el tipoServicio

            Log::info('Tipo de servicio obtenido: ' . $idtipoServicio);  // Log del tipo de servicio

        } else {
            Log::warning('No se encontró una visita seleccionada para el ticket: ' . $ticketId); // Log si no se encuentra una visita seleccionada
        }

        // Puedes agregar un log final para revisar el valor de tipoServicio
        Log::info('Valor final de tipoServicio: ' . $idtipoServicio);


        // Obtener el valor de estadovisita para la visita seleccionada
$estadovisita = DB::table('visitas')
->where('idTickets', $ticketId)  // Filtro por ticketId
->where('idVisitas', $idVisitaSeleccionada)  // Filtro por idVisitas seleccionada
->value('estadovisita');  // Obtenemos el valor de estadovisita
Log::info('Estado de la visita: ' . $estadovisita);  // Log del valor de estadovisita




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





        // Pasamos los datos a la vista
        return view("tickets.ordenes-trabajo.smart-tv.edit", compact(
            'ticket',
            'orden',
            'modelos',
            'usuario',
            'clientes',
            'clientesGenerales',
            'tiendas',
            'marcas',
            'encargado',
            'tecnicos_apoyo',
            'idTicketFlujo',
            'idEstadflujo',
            'descripcionEstadoFlujo',
            'ticketId',
            'visitaId',
            'estadosOTS',
            'visita',
            'id',
            'estadosFlujo',
            'colorEstado',
            'visitaExistente',
            'existeFlujo4',
            'transicionExistente', // Pasamos la variable que indica si la transición existe
            'visitaSeleccionada',  // Pasamos la variable que indica si la visita está seleccionada  // Pasamos la variable que indica si existe flujo 4
            'condicion',  // Pasamos la variable $condicion a la vista
            'condicionExistente',
            'ultimaVisitaCondicion',
            // 'condicionexistenteservicio',
            'condicionexistevisita',
            'titular',
            'tieneTresOMasFotos',  // Pasamos la nueva variable a la vista
            'numeroVisitas',  // Pasamos el número de visitas a la vista
            'visitasConCondiciones',  // Pasamos la variable que indica si hay condiciones para las visitas
            'condicionServicio',
            'visitaConCondicion', // Pasamos la variable que indica si la visita tiene una condición
            'tipoUsuario',
            'idVisitaSeleccionada',
            'tipoServicio',  // Pasamos el tipoServicio a la vista
            'idtipoServicio',
            'ultimaVisitaConEstado1',
            'esTiendacliente',
            'estadovisita'


        ));
    }



    public function actualizarComentario($ticketId, $flujoId, Request $request)
    {
        // Obtener el comentario del cuerpo de la solicitud
        $comentario = $request->input('comentario');

        // Buscar el registro en la tabla ticketflujo con el idTicketFlujo
        $ticketFlujo = TicketFlujo::where('idTicketFlujo', $flujoId)->first();  // Cambié 'id' por 'idTicketFlujo'

        if ($ticketFlujo) {
            // Actualizar el comentario
            $ticketFlujo->comentarioflujo = $comentario;
            $ticketFlujo->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Ticket Flujo no encontrado'], 404);
        }
    }









    // Generar el enlace con expiración en el controlador
    public function generarEnlace($id, $idVisitas)
    {
        // El enlace expirará en 30 minutos desde el momento en que se genere
        $expiresAt = Carbon::now()->addMinutes(30); // 30 minutos de expiración
        $expiresAtTimestamp = $expiresAt->timestamp; // Convertir a timestamp

        // Generar la URL con el parámetro expires_at
        $url = route('firmacliente', [
            'id' => $id,
            'idVisitas' => $idVisitas,
            'expires_at' => $expiresAtTimestamp
        ]);

        // Retornar la URL generada
        return response()->json(['url' => $url]);
    }


    public function firmacliente($id, $idVisitas)
    {
        // Obtener el ticket
        $orden = Ticket::with(['marca', 'modelo', 'cliente', 'tecnico', 'tienda', 'ticketflujo', 'usuario'])->findOrFail($id);
        $ticket = Ticket::with(['marca', 'modelo', 'cliente', 'tecnico', 'tienda', 'ticketflujo.estadoFlujo', 'usuario'])->findOrFail($id);
        
        // Obtener los estados de OTS
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
        return view("tickets.ordenes-trabajo.smart-tv.firmas.firmacliente", compact(
            'ticket',
            'orden',
            'estadosOTS',
            'ticketId',
            'idVisitas', // Asegúrate de pasar idVisitas aquí
            'visita', // El objeto visita completo
            'id'
        ));
    }
    


    public function guardarFirmaCliente(Request $request, $id, $idVisitas)
    {
        // Validar que la firma esté presente
        $request->validate([
            'firma' => 'required|string',
        ]);

        // Obtener el ticket
        $ticket = Ticket::findOrFail($id);

        // Verificar si la combinación idVisitas y idTickets existe en la tabla visitas
        $visitaExistente = DB::table('visitas')
            ->where('idVisitas', $idVisitas)
            ->where('idTickets', $ticket->idTickets)
            ->first();

        // Si no existe una visita válida con esa combinación, retornar un error
        if (!$visitaExistente) {
            return response()->json(['message' => 'La combinación de idVisitas y idTickets no es válida.'], 400);
        }

        // Convertir la firma de base64 a binario
        $firmaCliente = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->firma));

        // Verificar si ya existe una firma para este ticket y cliente
        $firmaExistente = DB::table('firmas')
            ->where('idTickets', $ticket->idTickets)
            ->where('idCliente', $ticket->idCliente)
            ->where('idVisitas', $idVisitas) // Verificamos si ya existe con el idVisitas actual
            ->first();

        // Si no existe una firma para este ticket y cliente con el idVisitas actual
        if (!$firmaExistente) {
            // Si el idVisitas es diferente, creamos una nueva firma
            DB::table('firmas')->insert([
                'firma_cliente' => $firmaCliente,
                'idTickets' => $ticket->idTickets,
                'idCliente' => $ticket->idCliente, // Asumiendo que el ticket tiene un idCliente
                'idVisitas' => $idVisitas, // Guardamos el idVisitas
            ]);

            // Retornar una respuesta de éxito con mensaje de creación
            return response()->json(['message' => 'Firma creada correctamente'], 201);
        } else {
            // Si ya existe una firma con el mismo idVisitas, actualizamos la firma
            DB::table('firmas')
                ->where('idFirmas', $firmaExistente->idFirmas) // Encontramos la firma existente
                ->update([
                    'firma_cliente' => $firmaCliente,
                ]);

            // Retornar una respuesta de éxito con mensaje de actualización
            return response()->json(['message' => 'Firma actualizada correctamente'], 200);
        }
    }




    public function mostrarDetalles($ticketId)
    {
        // Obtener los detalles de la visita junto con el usuario y el número de ticket
        $visita = DB::table('visitas')
            ->join('usuarios', 'visitas.idUsuario', '=', 'usuarios.idUsuario')
            ->join('tickets', 'visitas.idTickets', '=', 'tickets.idTickets')
            ->where('visitas.idTickets', $ticketId)
            ->select(
                'usuarios.Nombre as usuarios_nombre',
                'usuarios.apellidoPaterno as usuarios_apellidoPaterno',
                'visitas.*',
                'tickets.numero_ticket',  // Número de ticket
                'tickets.idClienteGeneral',
                'tickets.idCliente',
                'tickets.tipoServicio',
                'tickets.fecha_creacion',
                'tickets.idTipotickets',
                'tickets.idEstadoots',
                'tickets.idTecnico',
                'tickets.idUsuario as ticket_idUsuario',  // Usuario que creó el ticket
                'tickets.idTienda',
                'tickets.fallaReportada',
                'tickets.esRecojo',
                'tickets.direccion',
                'tickets.idMarca',
                'tickets.idModelo',
                'tickets.serie',
                'tickets.fechaCompra',
                'tickets.lat',
                'tickets.lng',
                'tickets.idTicketFlujo'
            )
            ->first();

        // Verificamos si se encontraron los detalles de la visita
        if ($visita) {
            return response()->json($visita);
        }

        return response()->json(['error' => 'Visita no encontrada'], 404);
    }







    public function getClientesGenerales($idCliente)
    {
        // Obtener los clientes generales asociados al cliente
        $clientesGenerales = ClienteClientegeneral::where('idCliente', $idCliente)
            ->with('clienteGeneral') // Asegúrate de definir la relación en tu modelo
            ->get();

        return response()->json($clientesGenerales);
    }

    public function getClientes()
    {
        // Obtener todos los clientes
        $clientes = Cliente::all(); // Asegúrate de que esto devuelva los campos necesarios

        return response()->json($clientes);
    }



    // Actualizar una orden de trabajo
    public function update(Request $request, $id)
    {
        try {
            // Validar los datos
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'estado' => 'nullable|boolean',
            ]);

            // Encontrar y actualizar la orden de trabajo
            $orden = Ticket::findOrFail($id);
            $orden->update($validatedData);

            return redirect()->route('ordenes-trabajo.index')->with('success', 'Orden de trabajo actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar una orden de trabajo: ' . $e->getMessage());
            return redirect()->route('ordenes-trabajo.index')->with('error', 'Ocurrió un error al actualizar la orden de trabajo.');
        }
    }



    // Eliminar una orden de trabajo
    public function destroy($id)
    {
        try {
            $orden = Ticket::findOrFail($id);
            $orden->delete();

            return response()->json(['success' => true, 'message' => 'Orden de trabajo eliminada correctamente.']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar una orden de trabajo: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al eliminar la orden de trabajo.'], 500);
        }
    }

    public function exportToExcel()
    {
        return Excel::download(new TicketExport(), 'tickets.xlsx');
    }


    // Exportar todas las órdenes de trabajo a PDF
    public function exportAllPDF()
    {
        $ordenes = Ticket::all();

        $pdf = Pdf::loadView('tickets.ordenes-trabajo.pdf.ordenes', compact('ordenes'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('reporte-ordenes-trabajo.pdf');
    }


    public function getAll(Request $request)
    {
        $query = Ticket::with([
            'tecnico:idUsuario,Nombre',
            'usuario:idUsuario,Nombre',
            'cliente:idCliente,nombre',
            'clientegeneral:idClienteGeneral,descripcion',
            'tiposervicio:idTipoServicio,nombre',
            'estado_ot:idEstadoots,descripcion,color',
            'marca:idMarca,nombre',
            'modelo.categoria:idCategoria,nombre',
            'ticketflujo.estadoflujo:idEstadflujo,descripcion,color',
            'visitas' => function ($query) {
                $query->select('idVisitas', 'idTickets', 'fecha_programada')
                    ->latest('fecha_programada')
                    ->limit(1);
            },
            'seleccionarVisita:idselecionarvisita,idTickets,idVisitas,vistaseleccionada',
            'seleccionarVisita.visita:idVisitas,nombre,fecha_programada,fecha_asignada,estado,idUsuario',
            'seleccionarVisita.visita.tecnico:idUsuario,Nombre',
            'visitas:idVisitas,nombre,fecha_programada,fecha_asignada,estado,idUsuario',
            'visitas.tecnico:idUsuario,Nombre',
            'transicion_status_tickets' => function ($query) use ($request) {
                if ($request->has('idVisita')) {
                    $query->whereHas('seleccionarVisita', function ($subquery) use ($request) {
                        $subquery->where('idVisitas', $request->idVisita);
                    })->where('idEstadoots', 3);
                }
            }
        ]);

        if ($request->has('tipoTicket') && in_array($request->tipoTicket, [1, 2])) {
            $query->where('idTipotickets', $request->tipoTicket);
        }

        if ($request->has('marca') && $request->marca != '') {
            $query->where('idMarca', $request->marca);
        }

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
                $q->where('serie', $searchValue)
                    ->orWhere('numero_ticket', $searchValue)
                    ->orWhere('serie', 'LIKE', "%{$searchValue}%")
                    ->orWhere('numero_ticket', 'LIKE', "%{$searchValue}%")
                    ->orWhereRaw("DATE_FORMAT(fecha_creacion, '%d/%m/%Y') LIKE ?", ["%{$searchValue}%"])
                    ->orWhereHas(
                        'visitas',
                        fn($q) =>
                        $q->whereRaw("DATE_FORMAT(fecha_programada, '%d/%m/%Y') LIKE ?", ["%{$searchValue}%"])
                    )
                    ->orWhereHas('modelo', fn($q) => $q->where('nombre', 'LIKE', "%{$searchValue}%"))
                    ->orWhereHas('modelo.categoria', fn($q) => $q->where('nombre', 'LIKE', "%{$searchValue}%"))
                    ->orWhereHas('clientegeneral', fn($q) => $q->where('descripcion', 'LIKE', "%{$searchValue}%"))
                    ->orWhereHas('cliente', fn($q) => $q->where('nombre', 'LIKE', "%{$searchValue}%"))
                    ->orWhereHas('marca', fn($q) => $q->where('nombre', 'LIKE', "%{$searchValue}%"))
                    ->orWhere('direccion', 'LIKE', "%{$searchValue}%")
                    ->orWhereHas('tienda', fn($q) => $q->where('nombre', 'LIKE', "%{$searchValue}%"))
                    ->orWhereHas(
                        'tecnico',
                        fn($q) =>
                        $q->where('Nombre', 'LIKE', "%{$searchValue}%")
                    )
                    // Buscar por técnico de la visita más reciente
                    ->orWhereHas(
                        'visitas.tecnico',
                        fn($q) =>
                        $q->where('Nombre', 'LIKE', "%{$searchValue}%")
                    )
                    ->orWhereHas('ticketflujo.estadoFlujo', function ($q) use ($normalized) {
                        $q->whereRaw("LOWER(CONVERT(descripcion USING utf8)) LIKE ?", ["%{$normalized}%"]);
                    })
                    ->orWhere(function ($q) use ($searchValue) {
                        if (stripos('soporte', $searchValue) !== false || strtolower($searchValue) === 's') {
                            $q->orWhere('tipoServicio', 1);
                        }
                        if (stripos('levantamiento', $searchValue) !== false || strtolower($searchValue) === 'l') {
                            $q->orWhere('tipoServicio', 2);
                        }
                    });
            });

            $query->orderByRaw("
            CASE
                WHEN serie = ? THEN 1
                WHEN numero_ticket = ? THEN 2
                ELSE 3
            END
        ", [$searchValue, $searchValue]);
        }

        $recordsTotal = Ticket::count();
        $query->orderBy('idTickets', 'desc');
        $recordsFiltered = (clone $query)->count();

        $ordenes = $query->skip($request->input('start', 0))
            ->take($request->input('length', 10))
            ->get();

        $ordenes = $ordenes->map(function ($item) {
            $arr = json_decode(json_encode($item), true);
            array_walk_recursive($arr, function (&$v) {
                if (is_string($v)) {
                    $v = mb_convert_encoding($v, 'UTF-8', 'UTF-8');
                }
            });
            return $arr;
        });

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $ordenes
        ]);
    }





    public function getClientesGeneralesss($idCliente)
    {
        // Obtener los clientes generales relacionados con el cliente seleccionado
        $clientesGenerales = DB::table('cliente_clientegeneral')
            ->join('clientegeneral', 'cliente_clientegeneral.idClienteGeneral', '=', 'clientegeneral.idClienteGeneral')
            ->where('cliente_clientegeneral.idCliente', $idCliente)
            ->get(['clientegeneral.idClienteGeneral', 'clientegeneral.descripcion']);

        // Verificar si los clientes generales fueron encontrados
        if ($clientesGenerales->isEmpty()) {
            return response()->json([]); // Si no hay clientes generales, retornamos un array vacío
        }

        return response()->json($clientesGenerales); // Retornamos los clientes generales encontrados
    }





    public function marcaapi()
    {
        $marcas = Marca::all()->makeHidden(['foto']);
        return response()->json($marcas);
    }

    public function clienteGeneralApi()
    {
        // Obtener los datos completos, no solo la descripción
        $clientesGenerales = ClienteGeneral::select('idClienteGeneral', 'descripcion')->get();

        // Registrar los datos en el log para inspección
        Log::info('Datos recuperados de ClienteGeneral:', ['clientesGenerales' => $clientesGenerales]);

        // Enviar la respuesta JSON con el Content-Type adecuado
        return response()->json($clientesGenerales)->header('Content-Type', 'application/json; charset=utf-8');
    }

    public function getOrdenes(Request $request)
    {
        $query = marca::query();

        // Filtrar por marca si es necesario
        if ($request->has('marca') && $request->marca) {
            $query->where('marca_id', $request->marca); // Ajusta 'marca_id' al nombre real de tu columna
        }

        // Obtener los resultados paginados
        $ordenes = $query->paginate(10); // Ajusta el número de registros por página si es necesario

        return response()->json($ordenes);
    }



    public function generarInformePdf($idTickets)
    {
        // Obtener la información de la orden con el idTickets
        $orden = Ticket::where('idTickets', $idTickets)->firstOrFail();

        // Cargar una vista de Blade con los datos
        $pdf = PDF::loadView('tickets.ordenes-trabajo.pdf.informe', compact('orden'));

        // Mostrar el PDF en el navegador
        return $pdf->stream('informe_orden_' . $idTickets . '.pdf');
    }

    public function verInforme($idTickets)
    {
        // Obtener la información de la orden con el idTickets
        $orden = Ticket::where('idTickets', $idTickets)->firstOrFail();

        // Ruta donde se guardará el PDF
        $pdfDirectory = storage_path('app/public/pdfs');

        // Crear el directorio si no existe
        if (!File::exists($pdfDirectory)) {
            File::makeDirectory($pdfDirectory, 0777, true);
        }

        // Generar el PDF
        $pdf = PDF::loadView('tickets.ordenes-trabajo.pdf.informe', compact('orden'));
        // Ruta final donde se guardará el PDF
        $pdfPath = 'pdfs/informe_orden_' . $idTickets . '.pdf';

        // Guardar el PDF en el directorio especificado
        $pdf->save(storage_path('app/public/' . $pdfPath));

        // Retornar la URL del PDF
        return response()->json([
            'pdfUrl' => url('storage/' . $pdfPath)
        ]);
    }


    public function verHojaEntrega($idTickets)
    {
        // Obtener la información de la orden con el idTickets
        $orden = Ticket::where('idTickets', $idTickets)->firstOrFail();

        // Ruta donde se guardará el PDF
        $pdfDirectory = storage_path('app/public/pdfs');

        // Crear el directorio si no existe
        if (!File::exists($pdfDirectory)) {
            File::makeDirectory($pdfDirectory, 0777, true);
        }

        // Generar el PDF de la Hoja de Entrega
        $pdf = PDF::loadView('tickets.ordenes-trabajo.pdf.hoja_entrega', compact('orden'));

        // Ruta final donde se guardará el PDF
        $pdfPath = 'pdfs/hoja_entrega_orden_' . $idTickets . '.pdf';

        // Guardar el PDF en el directorio especificado
        $pdf->save(storage_path('app/public/' . $pdfPath));

        // Retornar la URL del PDF
        return response()->json([
            'pdfUrl' => url('storage/' . $pdfPath)
        ]);
    }


    // Validar si un nombre ya existe
    public function checkNumeroTicket(Request $request)
    {
        $numero_ticket = $request->input('numero_ticket');
        $exists = Ticket::where('numero_ticket', $numero_ticket)->exists();

        return response()->json(['unique' => !$exists]);
    }


    public function obtenerModelosPorMarca($idMarca)
    {
        //Obtener los modelos relacionados con la marca
        $modelos = Modelo::where('idMarca', $idMarca)->get();

        //Retornamos los modelos en formato JSON
        return response()->json($modelos);
    }


    public function guardarCliente(Request $request)
    {
        try {
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'documento' => 'required|string|max:16|unique:cliente,documento',
                'telefono' => 'nullable|string|max:255|unique:cliente,telefono',
                'email' => 'nullable|email|max:255|unique:cliente,email',
                'direccion' => 'required|string|max:255',
                'departamento' => 'required|string|max:255',
                'provincia' => 'required|string|max:255',
                'distrito' => 'required|string|max:255',
                'esTienda' => 'nullable|boolean',
                'idTipoDocumento' => 'required|exists:tipodocumento,idTipoDocumento',
                'idClienteGeneraloption' => 'required|array', // Asegurarse de que es un array
                'idClienteGeneraloption.*' => 'integer|exists:clientegeneral,idClienteGeneral', // Validar cada elemento
            ]);

            // Establecer valores predeterminados
            $validatedData['estado'] = 1; // Valor predeterminado para 'estado'
            $validatedData['fecha_registro'] = now(); // Fecha de registro

            // Convertir el valor de esTienda a booleano si está presente
            $validatedData['esTienda'] = isset($validatedData['esTienda']) && $validatedData['esTienda'] == 1 ? true : false;

            // Extraer y eliminar 'idClienteGeneral' del array validado
            $idClienteGenerales = $validatedData['idClienteGeneraloption'];
            unset($validatedData['idClienteGeneraloption']); // Remover el campo para que no se pase a la creación del cliente

            // Crear el cliente en la base de datos
            $cliente = Cliente::create($validatedData);

            // Asociar los idClienteGeneral en la tabla pivote
            if (!empty($idClienteGenerales)) {
                // Preparar los datos para la inserción en la tabla pivote
                $clienteGenerales = collect($idClienteGenerales)->map(function ($idClienteGeneral) use ($cliente) {
                    return [
                        'idCliente' => $cliente->idCliente,
                        'idClienteGeneral' => $idClienteGeneral,
                    ];
                });

                // Insertar los datos en la tabla pivote
                DB::table('cliente_clientegeneral')->insert($clienteGenerales->toArray());
            }

            // Responder con éxito
            return response()->json([
                'success' => true,
                'message' => 'Cliente agregado correctamente',
                'data' => $cliente,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Capturar errores de validación y devolverlos
            Log::error('Errores de validación:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Errores de validación.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Capturar errores generales
            Log::error('Error al guardar el cliente: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar el cliente.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function getClientesdatosclientes()
    {
        $clientes = Cliente::all(); // Obtiene todos los clientes

        return response()->json($clientes);
    }


    public function getClientesGeneraless($idCliente)
    {
        // Obtener los clientes generales asociados al cliente usando la tabla intermedia cliente_clientegeneral
        $clientesGenerales = ClienteGeneral::join('cliente_clientegeneral', 'cliente_clientegeneral.idClienteGeneral', '=', 'clientegeneral.idClienteGeneral')
            ->where('cliente_clientegeneral.idCliente', $idCliente)  // Filtro por el cliente seleccionado
            ->get(['clientegeneral.idClienteGeneral', 'clientegeneral.descripcion']);  // Seleccionar los campos necesarios

        return response()->json($clientesGenerales);
    }



    public function getTicketsPorSerie($serie)
    {
        // Buscar los tickets donde el campo 'serie' coincida con el valor recibido
        $tickets = Ticket::where('serie', 'like', '%' . $serie . '%') // Filtrar por la serie
            ->get(['idTickets', 'numero_ticket', 'fecha_creacion']);  // Seleccionar los campos necesarios

        return response()->json($tickets);  // Devolver los tickets como JSON
    }

    public function actualizarOrden(Request $request, $id)
    {
        // Log para ver los datos que se están recibiendo
        Log::info('Datos recibidos para actualizar la orden:', $request->all());

        // Validar los datos del formulario
        $validated = $request->validate([
            'idCliente' => 'required|exists:cliente,idCliente',
            'idClienteGeneral' => 'required|exists:clientegeneral,idClienteGeneral',
            'idTienda' => 'required|exists:tienda,idTienda',
            'direccion' => 'required|string|max:255',
            'idMarca' => 'required|exists:marca,idMarca',
            'idModelo' => 'required|exists:modelo,idModelo',
            'serie' => 'required|string|max:255',
            'fechaCompra' => 'required|date',
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
        $orden->direccion = $request->direccion;
        $orden->idMarca = $request->idMarca;
        $orden->idModelo = $request->idModelo;
        $orden->serie = $request->serie;
        $orden->fechaCompra = $request->fechaCompra;
        $orden->fallaReportada = $request->fallaReportada;

        // Guardar los cambios
        $orden->save();

        // Log para confirmar que los cambios se guardaron
        Log::info('Orden actualizada:', ['id' => $orden->id, 'nuevos_datos' => $orden->toArray()]);

        // Responder con éxito
        return response()->json(['success' => true]);
    }


    public function loadEstados($ticketId)
    {
        // Consulta usando DB para obtener los estados de flujo, los usuarios y los estados de flujo
        $ticketFlujos = DB::table('ticketflujo')
            ->join('usuarios', 'ticketflujo.idUsuario', '=', 'usuarios.idUsuario')
            ->join('estado_flujo', 'ticketflujo.idEstadflujo', '=', 'estado_flujo.idEstadflujo')
            ->select(
                'ticketflujo.idTicketFlujo',
                'ticketflujo.idTicket',
                'ticketflujo.idEstadflujo',
                'ticketflujo.fecha_creacion',
                'ticketflujo.comentarioflujo',
                'usuarios.idUsuario',
                'usuarios.Nombre as usuario_nombre', // Solo nombre del usuario, sin avatar
                'estado_flujo.descripcion as estado_descripcion', // Descripción del estado
                'estado_flujo.color as estado_color' // Color del estado
            )
            ->where('ticketflujo.idTicket', $ticketId)
            ->get(); // Obtiene los resultados de la consulta

        // Si es necesario, asegúrate de que las cadenas de texto estén correctamente codificadas en UTF-8
        $ticketFlujos->each(function ($flujo) {
            $flujo->usuario_nombre = mb_convert_encoding($flujo->usuario_nombre, 'UTF-8', 'auto');
            $flujo->comentarioflujo = mb_convert_encoding($flujo->comentarioflujo, 'UTF-8', 'auto');
            $flujo->estado_descripcion = mb_convert_encoding($flujo->estado_descripcion, 'UTF-8', 'auto');
        });

        // Devuelve la respuesta en formato JSON con la codificación adecuada
        return response()->json([
            'estadosFlujo' => $ticketFlujos
        ], 200, ['Content-Type' => 'application/json; charset=UTF-8']);
    }





    public function guardarModificacion(Request $request, $id)
    {
        // Validar los datos recibidos
        $request->validate([
            'field' => 'required|string',
            'oldValue' => 'required|string',
            'newValue' => 'required|string',
            'usuario' => 'required|string',
        ]);

        // Obtener el valor de idTickets desde el parámetro $id
        $idTickets = $id;  // El valor de $id proviene de la URL del controlador

        // Crear la modificación en la base de datos
        Modificacion::create([
            'idTickets' => $idTickets,  // Usamos el idTickets que proviene de la URL
            'campo' => $request->input('field'),
            'valor_antiguo' => $request->input('oldValue'),
            'valor_nuevo' => $request->input('newValue'),
            'usuario' => $request->input('usuario'),
        ]);

        return response()->json(['success' => 'Modificación guardada correctamente']);
    }


    public function obtenerUltimaModificacion($idTickets)
    {
        $ultimaModificacion = Modificacion::where('idTickets', $idTickets)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($ultimaModificacion) {
            return response()->json([
                'success' => true,
                'ultima_modificacion' => $ultimaModificacion,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No hay modificaciones previas.',
            ]);
        }
    }



    public function guardarVisita(Request $request)
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
            'tecnicos_apoyo' => 'nullable|array', // Si seleccionaron técnicos de apoyo
            'imagenVisita' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validar la imagen
            'nombreclientetienda' => 'nullable|string|max:255', // Validación para el nombre del cliente
            'celularclientetienda' => 'nullable|string|max:255', // Validación para el celular del cliente
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

    // Aquí guardamos los nuevos campos
    $visita->nombreclientetienda = $request->nombreclientetienda;
    $visita->celularclientetienda = $request->celularclientetienda;

    Log::info('Creando la visita', ['visita' => $visita]);

    // Asignar 0 o 1 a "necesita_apoyo"
    $visita->necesita_apoyo = $request->necesita_apoyo ?? 0;  // Si no se envió, asignar 0
    $visita->idTickets = $request->idTickets; // Asegúrate de pasar este valor desde el frontend

    // Guardar la visita
    $visita->save();
        Log::info('Visita guardada con éxito', ['visita_id' => $visita->idVisitas]);

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







    public function obtenerVisitas($ticketId)
    {
        Log::info('Obteniendo visitas para el ticket: ' . $ticketId);

        // Obtener todas las visitas del ticket, incluyendo el técnico, los anexos y las condiciones
        $visitas = Visita::with(['tecnico', 'anexos_visitas' => function ($query) {
            $query->whereIn('idTipovisita', [2, 3, 4]);
        }, 'condicionesTickets'])
            ->where('idTickets', $ticketId)
            ->get();

        Log::info('Visitas obtenidas para ticket ' . $ticketId, ['total_visitas' => $visitas->count()]);

        // Verificar si se han obtenido visitas
        if ($visitas->isEmpty()) {
            Log::warning('No se encontraron visitas para el ticket: ' . $ticketId);
        }

        // Convertir las fechas a formato ISO 8601
        $visitas->each(function ($visita) {
            Log::info('Procesando visita ID: ' . $visita->idVisitas, [
                'nombre' => $visita->nombre,
                'fecha_inicio_hora' => $visita->fecha_inicio_hora,
                'fecha_final_hora' => $visita->fecha_final_hora
            ]);

            $visita->fecha_inicio_hora = $visita->fecha_inicio_hora?->toIso8601String();
            $visita->fecha_final_hora = $visita->fecha_final_hora?->toIso8601String();
            // Incluir el nombre del técnico
            $visita->nombre_tecnico = $visita->tecnico ? $visita->tecnico->Nombre : null;

            $visita->idTipoUsuario = $visita->tecnico ? $visita->tecnico->idTipoUsuario : null;


            // Incluir tipoServicio
            $visita->tipoServicio = $visita->tipoServicio;  // Trae el campo tipoServi


            $visita->idTicket = $visita->idTickets;
            $visita->idVisita = $visita->idVisitas;
            $visita->nombre_visita = $visita->nombre;

            Log::info('Visita procesada', ['nombre_tecnico' => $visita->nombre_tecnico, 'idTicket' => $visita->idTicket]);

            $visita->servicio = $visita->condicionesTickets->isNotEmpty() ? $visita->condicionesTickets[0]->servicio : null;
            $visita->motivo = $visita->condicionesTickets->isNotEmpty() ? $visita->condicionesTickets[0]->motivo : null;
            $visita->titular = $visita->condicionesTickets->isNotEmpty() ? $visita->condicionesTickets[0]->titular : null;

            $visita->nombre = $visita->condicionesTickets->isNotEmpty() ? $visita->condicionesTickets[0]->nombre : null;
            $visita->dni = $visita->condicionesTickets->isNotEmpty() ? $visita->condicionesTickets[0]->dni : null;
            $visita->telefono = $visita->condicionesTickets->isNotEmpty() ? $visita->condicionesTickets[0]->telefono : null;

            // Excluir la firma y avatar del técnico si existe
            if ($visita->tecnico) {
                $visita->tecnico->makeHidden(['firma', 'avatar']);
            }

            $visita->anexos_visitas->each(function ($anexovisita) {
                Log::info('Procesando anexo de visita', [
                    'idTipovisita' => $anexovisita->idTipovisita,
                    'ubicacion' => $anexovisita->ubicacion
                ]);

                if ($anexovisita->foto) {
                    $anexovisita->foto = base64_encode($anexovisita->foto);
                    Log::info('Foto de anexo convertida a base64');
                }

                $anexovisita->descripcion = $anexovisita->descripcion;
                $anexovisita->lat = $anexovisita->lat;
                $anexovisita->lng = $anexovisita->lng;
                $anexovisita->ubicacion = $anexovisita->ubicacion;
            });

            if ($visita->condicionesTickets) {
                Log::info('Condiciones encontradas para la visita ID: ' . $visita->idVisitas);

                $visita->condicionesTickets->each(function ($condicion) {
                    Log::info('Procesando condición', [
                        'servicio' => $condicion->servicio,
                        'motivo' => $condicion->motivo
                    ]);

                    if ($condicion->imagen) {
                        $condicion->imagen = base64_encode($condicion->imagen);
                        Log::info('Imagen de condición convertida a base64');
                    }
                });
            } else {
                Log::warning('No se encontraron condiciones para la visita ID: ' . $visita->idVisitas);
                $visita->condicionesTickets = [];
            }
        });

        // Devolver las visitas como respuesta JSON
        return response()->json($visitas);
    }







    public function obtenerHistorialModificaciones($ticketId)
    {
        // Obtener todas las modificaciones relacionadas con el ticket
        $modificaciones = Modificacion::where('idTickets', $ticketId)
            ->orderBy('fecha_modificacion', 'desc')  // Ordenar por la fecha de modificación
            ->get();

        return response()->json($modificaciones);
    }






    public function actualizarVisita(Request $request, $id)
    {
        try {
            // Validación
            $validated = $request->validate([
                'fechas_desplazamiento' => 'required|date_format:Y-m-d H:i:s',
            ]);

            // Buscar la visita
            $visita = Visita::findOrFail($id);

            // Actualizar la fecha de desplazamiento
            $visita->fechas_desplazamiento = $validated['fechas_desplazamiento'];
            $visita->save();

            // Retornar la respuesta
            return response()->json($visita, 200);  // Enviar la visita actualizada
        } catch (\Exception $e) {
            // Manejo de errores
            return response()->json(['error' => 'Error al actualizar visita', 'message' => $e->getMessage()], 500);
        }
    }





    // use Illuminate\Support\Facades\Log; // Asegúrate de importar la clase Log al inicio del archivo

    public function guardarAnexoVisita(Request $request)
    {
        try {
            // Validación de los datos
            Log::info('Iniciando validación de datos', ['request' => $request->all()]);

            $validated = $request->validate([
                'idVisitas' => 'required|integer|exists:visitas,idVisitas',
                'idTipovisita' => 'required|integer',
                'lat' => 'required|numeric',
                'lng' => 'required|numeric',
                'ubicacion' => 'required|string|max:255',
            ]);

            Log::info('Datos validados correctamente', ['validated_data' => $validated]);

            // Verificar si ya existe un anexo para la visita y tipo de visita (sin importar lat y lng)
            Log::info('Verificando existencia de anexo para visita y tipo de visita', [
                'idVisitas' => $validated['idVisitas'],
                'idTipovisita' => $validated['idTipovisita']
            ]);

            $existingAnexo = DB::table('anexos_visitas')
                ->where('idVisitas', $validated['idVisitas'])
                ->where('idTipovisita', $validated['idTipovisita'])
                ->first();

            if ($existingAnexo) {
                Log::warning('Anexo ya existe para la visita y tipo de visita', [
                    'idVisitas' => $validated['idVisitas'],
                    'idTipovisita' => $validated['idTipovisita'],
                    'existing_anexo' => $existingAnexo
                ]);
                return response()->json(['error' => 'El técnico ya se encuentra en desplazamiento para esta visita.'], 400);
            }

            // Insertar los datos en la tabla anexos_visitas si no existe
            Log::info('Insertando nuevo anexo en la base de datos', [
                'idVisitas' => $validated['idVisitas'],
                'idTipovisita' => $validated['idTipovisita'],
                'lat' => $validated['lat'],
                'lng' => $validated['lng'],
                'ubicacion' => $validated['ubicacion'],
            ]);

            DB::table('anexos_visitas')->insert([
                'idVisitas' => $validated['idVisitas'],
                'idTipovisita' => $validated['idTipovisita'],
                'lat' => $validated['lat'],
                'lng' => $validated['lng'],
                'ubicacion' => $validated['ubicacion'],
            ]);

            Log::info('Anexo guardado correctamente', [
                'idVisitas' => $validated['idVisitas'],
                'idTipovisita' => $validated['idTipovisita']
            ]);

            return response()->json(['success' => true, 'message' => 'Anexo guardado correctamente.'], 200);
        } catch (\Exception $e) {
            // Manejo de errores
            Log::error('Error al guardar el anexo', [
                'exception' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['error' => 'Error al guardar el anexo', 'message' => $e->getMessage()], 500);
        }
    }






    public function guardarFoto(Request $request)
    {
        try {
            // Validación del archivo y el id de visita
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'visitaId' => 'required|integer',
                'lat' => 'nullable|numeric', // Validación para la latitud
                'lng' => 'nullable|numeric', // Validación para la longitud
                'ubicacion' => 'nullable|string', // Validación para la ubicación
            ]);

            // Obtener el archivo
            $file = $request->file('photo');

            // Convertir la imagen a binario
            $foto = file_get_contents($file);

            // Obtener el ID de la visita
            $visitaId = $request->input('visitaId');
            $lat = $request->input('lat'); // Obtener latitud
            $lng = $request->input('lng'); // Obtener longitud
            $ubicacion = $request->input('ubicacion'); // Obtener la ubicación (dirección)

            // Asegurarse de que la visita existe antes de guardar el anexo
            $visita = Visita::find($visitaId);
            if (!$visita) {
                return response()->json(['success' => false, 'message' => 'La visita no existe.'], 404);
            }

            // Guardar la foto en la tabla anexos_visitas
            $anexo = new AnexosVisita();
            $anexo->foto = $foto;
            $anexo->descripcion = 'Inicio de servicio'; // Descripción
            $anexo->idTipovisita = 3; // Tipo de visita 3
            $anexo->idVisitas = $visitaId; // ID de la visita
            $anexo->lat = $lat; // Latitud
            $anexo->lng = $lng; // Longitud
            $anexo->ubicacion = $ubicacion; // Ubicación (dirección)

            $anexo->save();

            return response()->json(['success' => true, 'message' => 'Foto subida con éxito.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al guardar la foto: ' . $e->getMessage()], 500);
        }
    }








    public function verificarFotoExistente($idVisitas)
    {
        try {
            // Verificar si existe una foto para la visita en la tabla anexos_visitas
            $anexo = AnexosVisita::where('idVisitas', $idVisitas)->whereNotNull('foto')->first();

            if ($anexo) {
                // Si hay foto asociada, devolver éxito
                return response()->json(['success' => true, 'message' => 'Foto encontrada.']);
            } else {
                // Si no hay foto asociada
                return response()->json(['success' => false, 'message' => 'No hay foto para esta visita.']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al verificar la foto: ' . $e->getMessage()], 500);
        }
    }

    public function updatevisita(Request $request, $id)
    {
        // Validar los datos obligatorios
        $validated = $request->validate([
            'fecha_inicio_hora' => 'required|date',
            'fecha_final_hora' => 'required|date',
            'idUsuario' => 'required|exists:usuarios,idUsuario',
        ]);
    
        // Buscar la visita
        $visita = Visita::find($id);
    
        if (!$visita) {
            return response()->json(['success' => false, 'message' => 'Visita no encontrada'], 404);
        }
    
        // Verificar si ya existe un anexo con idTipovisita = 2 (en ejecución)
        $anexoEnEjecucion = DB::table('anexos_visitas')
            ->where('idVisitas', $id)
            ->where('idTipovisita', 2)
            ->exists();
    
        if ($anexoEnEjecucion) {
            // Si existe un anexo con idTipovisita = 2, devolver un error
            return response()->json(['success' => false, 'message' => 'Esta visita está en ejecución, no se puede actualizar'], 400);
        }
    
        // Verificar si la hora final es menor que la hora de inicio
        $fechaInicio = strtotime($request->fecha_inicio_hora);
        $fechaFinal = strtotime($request->fecha_final_hora);
    
        if ($fechaFinal < $fechaInicio) {
            return response()->json(['success' => false, 'message' => 'La hora final no puede ser menor a la hora de inicio.'], 400);
        }
    
        // Actualizar los datos de la visita
        $visita->fecha_inicio_hora = $request->fecha_inicio_hora;
        $visita->fecha_final_hora = $request->fecha_final_hora;
        $visita->idUsuario = $request->idUsuario;
    
        // Actualizar los campos opcionales si existen
        if ($request->has('nombreclientetienda')) {
            $visita->nombreclientetienda = $request->nombreclientetienda;
        }
    
        if ($request->has('celularclientetienda')) {
            $visita->celularclientetienda = $request->celularclientetienda;
        }
    
        $visita->save();
    
        return response()->json(['success' => true, 'message' => 'Visita actualizada exitosamente']);
    }
    


    public function agregarTecnicoApoyo(Request $request)
    {
        // Verificar si ya existe un anexo con idTipovisita = 2 (en ejecución)
        $anexoEnEjecucion = DB::table('anexos_visitas')
            ->where('idVisitas', $request->idVisita)  // Usamos $request->idVisita para verificar el anexo
            ->where('idTipovisita', 2)  // idTipovisita = 2 indica que está en ejecución
            ->exists();
    
        if ($anexoEnEjecucion) {
            // Si existe un anexo con idTipovisita = 2, devolver un error
            return response()->json(['success' => false, 'message' => 'Esta visita está en ejecución, no se puede agregar un técnico de apoyo'], 400);
        }
    
        // Validar los datos
        $validated = $request->validate([
            'idVisita' => 'required|exists:visitas,idVisitas',
            'idTecnico' => 'required|exists:usuarios,idUsuario',
            'idTickets' => 'required|exists:visitas,idTickets',  // Validamos que idTickets exista
        ]);
    
        // Verificar si el técnico ya está asignado a esa visita y ticket
        $tecnicoExistente = DB::table('ticketapoyo')
            ->where('idVisita', $request->idVisita)
            ->where('idTicket', $request->idTickets)
            ->where('idTecnico', $request->idTecnico)
            ->exists();
    
        if ($tecnicoExistente) {
            // Si ya existe el mismo técnico para esa visita y ticket, devolver un error
            return response()->json(['success' => false, 'message' => 'Este técnico ya está asignado a esta visita y ticket'], 400);
        }
    
        // Insertar el técnico de apoyo
        DB::table('ticketapoyo')->insert([
            'idVisita' => $request->idVisita,
            'idTecnico' => $request->idTecnico,
            'idTicket' => $request->idTickets, // si tienes el idTicket disponible
        ]);
    
        return response()->json(['success' => true, 'message' => 'Técnico de apoyo agregado correctamente']);
    }
    

    




    // public function verificarRegistroAnexo($idVisitas)
    // {
    //     // Verificar si existe un registro en la tabla anexos_visitas con el idVisitas y los campos requeridos
    //     $registro = AnexosVisita::where('idVisitas', $idVisitas)
    //         ->whereNotNull('lat')
    //         ->whereNotNull('lng')
    //         ->whereNotNull('ubicacion')
    //         ->where('idTipovisita', 2) // Asumiendo que el tipo de visita 2 es "Inicio de Servicio"
    //         ->first();

    //     if ($registro) {
    //         // Si ya existe el registro, devolverlo
    //         return response()->json($registro);
    //     } else {
    //         // Si no existe el registro, devolver una respuesta vacía o un estado 404
    //         return response()->json([], 404);  // O simplemente return response()->json(null);
    //     }
    // }


    // use Illuminate\Support\Facades\Log; // Asegúrate de importar Log si no lo has hecho

    public function verificarRegistroAnexo($idVisitas)
    {
        // Log para verificar el valor recibido de idVisitas
        Log::info('Verificando registro de anexo para la visita ID: ' . $idVisitas);

        // Verificar si existe un registro en la tabla anexos_visitas con el idVisitas y los campos requeridos
        $registro = AnexosVisita::where('idVisitas', $idVisitas)
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->whereNotNull('ubicacion')
            ->where('idTipovisita', 2) // Asumiendo que el tipo de visita 2 es "Inicio de Servicio"
            ->first();

        // Log para verificar si se encontró el registro
        if ($registro) {
            Log::info('Registro encontrado para la visita ID: ' . $idVisitas);

            $registro->makeHidden(['foto']);

            Log::info('Datos del registro: ', (array) $registro); // Loguea los datos del registro como array
            // Si ya existe el registro, devolverlo
            return response()->json($registro);
        } else {
            // Log si no se encontró ningún registro
            Log::warning('No se encontró un registro para la visita ID: ' . $idVisitas);
            // Si no existe el registro, devolver una respuesta vacía o un estado 404
            return response()->json([], 404);  // O simplemente return response()->json(null);
        }
    }



    public function guardar(Request $request)
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

            // Actualizar el campo estadovisita a 1 en la tabla visitas
    // DB::table('visitas')
    // ->where('idVisitas', $request->idVisitas)
    // ->update(['estadovisita' => 1]); // Esto actualiza la variable estadovisita a 1



        // Si el servicio es igual a 1, creamos el ticketflujo y actualizamos el ticket
        if ($request->servicio == 1) {
            // Insertar en la tabla ticketflujo con idEstadflujo 14
            $ticketflujo = DB::table('ticketflujo')->insertGetId([
                'idTicket' => $request->idTickets,  // Usamos el idTickets de la solicitud
                'idEstadflujo' => 9,               // Siempre 14 como estado
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
        }

        if ($condicion) {
            return response()->json(['success' => true, 'message' => 'Condiciones guardadas correctamente.']);
        } else {
            return response()->json(['error' => 'Error al guardar las condiciones.'], 500);
        }
    }





    public function guardarEstado(Request $request)
    {
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
    }

    public function obtenerJustificacion(Request $request)
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


    public function guardarImagen(Request $request)
    {
        // Validar que se envíen imágenes y descripciones en array
        $request->validate([
            'imagenes' => 'required|array', // Asegura que es un array
            'imagenes.*' => 'image|max:2048', // Cada imagen no debe exceder 2MB
            'descripciones' => 'required|array',
            'descripciones.*' => 'string|max:255',
            'ticket_id' => 'required|integer|exists:tickets,idTickets',
        ]);

        // Obtener el idVisitas del ticket
        $visita = DB::table('seleccionarvisita')
            ->where('idTickets', $request->ticket_id)
            ->first();

        if (!$visita) {
            return response()->json(['success' => false, 'message' => 'No se encontró una visita válida para este ticket.'], 400);
        }

        $visita_id = $visita->idVisitas;

        $imagenesGuardadas = [];

        // Recorrer las imágenes y guardarlas
        foreach ($request->file('imagenes') as $index => $imagen) {
            $descripcion = $request->descripciones[$index] ?? 'Sin descripción';
            $imagen_binaria = file_get_contents($imagen->getRealPath());

            $foto = new Fotostickest();
            $foto->idTickets = $request->ticket_id;
            $foto->idVisitas = $visita_id;
            $foto->foto = $imagen_binaria;
            $foto->descripcion = $descripcion;
            $foto->save();

            $imagenesGuardadas[] = ['id' => $foto->id, 'descripcion' => $descripcion];
        }

        return response()->json(['success' => true, 'message' => 'Imágenes guardadas correctamente.', 'imagenes' => $imagenesGuardadas]);
    }




    public function obtenerImagenes($ticketId)
    {
        // Buscar el idVisitas en la tabla seleccionarvisita con base en el idTickets
        $seleccionada = DB::table('seleccionarvisita')
            ->where('idTickets', $ticketId)
            ->first();  // Solo buscamos por ticketId

        // Si se encontró una visita asociada al ticket
        if ($seleccionada) {
            // Tomamos el idVisitas de la visita seleccionada
            $visitaId = $seleccionada->idVisitas;

            // Buscar las imágenes asociadas al ticket y la visita
            $imagenes = DB::table('fotostickest')
                ->where('idTickets', $ticketId)
                ->where('idVisitas', $visitaId)  // Usamos el idVisitas obtenido
                ->get();

            // Si no hay imágenes asociadas, retornar un mensaje
            if ($imagenes->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No se encontraron imágenes para este ticket y visita.']);
            }

            // Convertir las imágenes a base64 para poder mostrarlas en el frontend
            $imagenes = $imagenes->map(function ($imagen) {
                return [
                    'id' => $imagen->idfotostickest,
                    'src' => 'data:image/jpeg;base64,' . base64_encode($imagen->foto),
                    'description' => $imagen->descripcion
                ];
            });

            // Retornar la respuesta con las imágenes
            return response()->json(['imagenes' => $imagenes]);
        } else {
            // Si no se encontró una visita para el ticket, devolver error
            return response()->json(['success' => false, 'message' => 'No se encontró una visita seleccionada para este ticket.']);
        }
    }






    public function eliminarImagen($id)
    {
        // Buscar la imagen en la base de datos
        $imagen = DB::table('fotostickest')->where('idfotostickest', $id)->first();

        if ($imagen) {
            // Eliminar la imagen
            DB::table('fotostickest')->where('idfotostickest', $id)->delete();
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'error' => 'La imagen no existe.']);
        }
    }







    public function verificarFechaExistente($idVisita)
    {
        // Buscar la visita por su ID
        $visita = Visita::find($idVisita);

        if (!$visita) {
            return response()->json([
                'existe' => false,
                'message' => 'Visita no encontrada.'
            ], 404);
        }

        // Verificar si ya existe una fecha de llegada
        $existeFecha = !is_null($visita->fecha_llegada);

        return response()->json([
            'existe' => $existeFecha,
            'message' => $existeFecha ? 'Ya existe una fecha de llegada.' : 'No existe una fecha de llegada.'
        ]);
    }

    // En el controlador VisitasController.php
    public function actualizarFechaLlegada(Request $request, $idVisitas)
    {
        // Validar los datos recibidos
        $request->validate([
            'fecha_llegada' => 'required|date',
        ]);

        try {
            // Actualizar la fecha de llegada en la base de datos
            $visita = Visita::findOrFail($idVisitas);
            $visita->fecha_llegada = $request->fecha_llegada;
            $visita->save();

            // Responder con éxito
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // Manejar error si no se puede encontrar la visita o actualizar
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }


    // use Illuminate\Support\Facades\Log; // Asegúrate de importar Log

    public function verificarFechaLlegada($idVisitas)
    {
        // Log para verificar el ID de visita recibido
        Log::info("Verificando fecha de llegada para la visita con ID: $idVisitas");

        // Buscar la visita por su ID
        $visita = Visita::find($idVisitas);

        // Verificar si la visita existe
        if ($visita) {
            Log::info("Visita encontrada con ID: $idVisitas");

            // Verificar si tiene una fecha de llegada
            if ($visita->fecha_llegada) {
                Log::info("La visita tiene una fecha de llegada: " . $visita->fecha_llegada);
                return response()->json([
                    'fecha_llegada' => $visita->fecha_llegada
                ]);
            } else {
                Log::info("La visita con ID: $idVisitas no tiene una fecha de llegada.");
            }
        } else {
            Log::warning("No se encontró la visita con ID: $idVisitas");
        }

        // Si no existe la visita o no tiene fecha de llegada, retornar null
        return response()->json([
            'fecha_llegada' => null
        ]);
    }


    public function obtenerFirmaCliente($id)
    {
        // Obtener el idVisitas directamente con una consulta más eficiente
        $idVisitas = DB::table('seleccionarvisita')
            ->where('idTickets', $id) // Relacionamos el idTickets que hemos recibido
            ->value('idVisitas'); // Obtenemos el idVisitas correspondiente

        if (!$idVisitas) {
            return response()->json(['error' => 'No se encontró la visita relacionada con este ticket'], 404);
        }

        // Obtener la firma del cliente usando un JOIN directo con la tabla 'firmas'
        $firma = DB::table('firmas')
            ->where('idTickets', $id)
            ->where('idVisitas', $idVisitas)
            ->value('firma_cliente');

        // Verificar si la firma existe
        if ($firma) {
            // Si la firma ya es un string base64, puedes usarla directamente
            if (strpos($firma, 'data:image/') !== false) {
                return response()->json(['firma' => $firma]);
            }

            // Si no es base64, convertirla a base64
            $firmaBase64 = base64_encode($firma);
            return response()->json(['firma' => "data:image/png;base64,$firmaBase64"]);
        }

        return response()->json(['firma' => null], 404);
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








    public function seleccionarVisita(Request $request)
    {
        // Validar los datos que vienen del frontend
        $validated = $request->validate([
            'idTickets' => 'required|integer',
            'idVisitas' => 'required|integer',
            'vistaseleccionada' => 'required|string|max:255',
        ]);

        // Buscar si ya existe un registro con el mismo idTickets
        $seleccionarVisita = SeleccionarVisita::where('idTickets', $validated['idTickets'])->first();

        // Si el registro ya existe, actualizamos los campos
        if ($seleccionarVisita) {
            $seleccionarVisita->idVisitas = $validated['idVisitas'];
            $seleccionarVisita->vistaseleccionada = $validated['vistaseleccionada'];
            $seleccionarVisita->save();

            return response()->json(['success' => true, 'message' => 'Visita actualizada correctamente.']);
        }

        // Si no existe, creamos un nuevo registro
        $seleccionarVisita = new SeleccionarVisita();
        $seleccionarVisita->idTickets = $validated['idTickets'];
        $seleccionarVisita->idVisitas = $validated['idVisitas'];
        $seleccionarVisita->vistaseleccionada = $validated['vistaseleccionada'];

        // Guardar en la base de datos
        if ($seleccionarVisita->save()) {
            return response()->json(['success' => true, 'message' => 'Visita seleccionada guardada correctamente.']);
        } else {
            return response()->json(['success' => false, 'message' => 'Hubo un error al guardar la visita seleccionada.']);
        }
    }


    public function verificarVisitaSeleccionada($idVisita)
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

    private function optimizeBase64Image($base64String, $quality = 60, $maxWidth = 800)
    {
        if (!$base64String) return null;

        // Extraer el tipo de imagen
        if (!preg_match('#^data:image/(\w+);base64,#i', $base64String, $matches)) {
            return $base64String; // Si no es imagen base64 válida, devolverla sin cambios
        }

        $imageType = strtolower($matches[1]); // Convertir a minúsculas (jpeg, png, webp)

        // Decodificar la imagen base64
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64String));

        // Evitar errores con imágenes corruptas
        if (!$imageData) {
            \Log::error("Error al decodificar imagen base64.");
            return $base64String;
        }

        // Crear la imagen desde la cadena binaria
        $image = @imagecreatefromstring($imageData);
        if (!$image) {
            \Log::error("Error al procesar la imagen con imagecreatefromstring.");
            return $base64String; // Retornar imagen original si no se puede procesar
        }

        // Obtener dimensiones
        $width = imagesx($image);
        $height = imagesy($image);
        $newWidth = min($width, $maxWidth);
        $newHeight = ($height / $width) * $newWidth; // Mantener proporción

        // Crear nueva imagen con transparencia si es PNG
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        if ($imageType === 'png') {
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);
            $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
            imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $transparent);
        } else {
            $background = imagecolorallocate($resizedImage, 255, 255, 255); // Blanco para JPG
            imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $background);
        }

        // Redimensionar imagen
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Convertir y optimizar
        ob_start();
        if ($imageType === 'png') {
            imagepng($resizedImage, null, 9); // Mantener transparencia y alta compresión
            $optimizedType = 'png';
        } elseif ($imageType === 'jpeg' || $imageType === 'jpg') {
            imagejpeg($resizedImage, null, $quality);
            $optimizedType = 'jpeg';
        } else {
            imagewebp($resizedImage, null, $quality); // WebP para imágenes no compatibles
            $optimizedType = 'webp';
        }
        $compressedImage = ob_get_clean();

        // Liberar memoria
        imagedestroy($image);
        imagedestroy($resizedImage);

        // Retornar imagen optimizada en base64
        return "data:image/{$optimizedType};base64," . base64_encode($compressedImage);
    }


    public function generateInformePdfVisita($idOt, $idVisita)
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
            'visitas.fotostickest'
        ])->findOrFail($idOt);

        // ✅ Validar que la visita pertenezca al ticket
        $visitaValida = $orden->visitas->where('idVisitas', $idVisita)->first();

        if (!$visitaValida) {
            return response()->json([
                'success' => false,
                'message' => 'La visita indicada no pertenece a este ticket.'
            ]);
        }

        $idVisitasSeleccionada = $idVisita;

        // 🔹 Obtener transiciones de estado filtradas por idTickets y idVisitas
        $transicionesStatusOt = TransicionStatusTicket::where('idTickets', $idOt)
            ->where('idVisitas', $idVisitasSeleccionada)
            ->whereNotNull('justificacion')
            ->where('justificacion', '!=', '')
            ->with('estado_ot')
            ->get();

        // 🔹 Obtener datos del producto
        $producto = [
            'categoria' => $orden->modelo->categoria->nombre ?? 'No especificado',
            'marca' => $orden->modelo->marca->nombre ?? 'No especificado',
            'modelo' => $orden->modelo->nombre ?? 'No especificado',
            'serie' => $orden->serie ?? 'No especificado',
            'fallaReportada' => $orden->fallaReportada ?? 'No especificado'
        ];

        $logoGKM = $this->procesarLogoMarca(file_get_contents(public_path('assets/images/auth/logogkm2.png')));
        $marca = $orden->modelo->marca ?? null;
        $marca->logo_base64 = null;

        if ($marca && !empty($marca->foto)) {
            $marca->logo_base64 = $this->procesarLogoMarca($marca->foto);
        }

        // 🔹 FORMATEAR VISITAS PARA LA VISTA
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
                    'documento' => $visitaSeleccionada->tecnico->documento ?? 'No disponible',
                    'tipo_documento' => $visitaSeleccionada->tecnico->tipodocumento->nombre ?? 'Documento',
                    'telefono' => ($visitaSeleccionada->tecnico->telefono ?? 'No registrado')
                ]
            ]);
        }

        // 🔹 OBTENER FIRMAS FILTRADAS POR idTickets Y idVisitas
        $firma = DB::table('firmas')->where('idTickets', $idOt)
            ->where('idVisitas', $idVisitasSeleccionada)
            ->first();

        $firmaCliente = $firma && !empty($firma->firma_cliente)
            ? $this->optimizeBase64Image('data:image/png;base64,' . base64_encode($firma->firma_cliente))
            : null;

        // 🔹 Obtener la firma del técnico (usuario) desde la tabla `usuarios`
        $firmaTecnico = null;
        if ($visitaSeleccionada && $visitaSeleccionada->tecnico) {
            $tecnico = $visitaSeleccionada->tecnico;
            if ($tecnico && !empty($tecnico->firma)) {
                $firmaTecnico = 'data:image/png;base64,' . base64_encode($tecnico->firma);
            }
        }

        // 🔹 Obtener imágenes de anexos y optimizarlas
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

        // 🔹 Obtener imágenes de los tickets y optimizarlas
        $imagenesFotosTickets = $visitaSeleccionada->fotostickest->map(function ($foto) {
            return [
                'foto_base64' => !empty($foto->foto)
                    ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($foto->foto))
                    : null,
                'descripcion' => $foto->descripcion
            ];
        });

        // 🔹 Obtener la fecha_inicio de la visita seleccionada
        $fechaCreacion = $visitaSeleccionada && $visitaSeleccionada->fecha_inicio
            ? date('d/m/Y', strtotime($visitaSeleccionada->fecha_inicio))
            : 'N/A';

        // 🔹 Verificar si el técnico es de tipoUsuario = 4
        $tipoUsuario = null;
        if ($visitaSeleccionada && $visitaSeleccionada->tecnico) {
            $tipoUsuario = $visitaSeleccionada->tecnico->idTipoUsuario ?? null;
        }

        // 🔹 Determinar la vista del PDF según el tipo de usuario
        $vistaPdf = ($tipoUsuario == 4)
            ? 'tickets.ordenes-trabajo.smart-tv.informe.pdf.informe_chofer'
            : 'tickets.ordenes-trabajo.smart-tv.informe.pdf.informe';
        $marca = $orden->modelo->marca ?? null;

        $html = View($vistaPdf, [
            'orden' => $orden,
            'fechaCreacion' => $fechaCreacion,
            'producto' => $producto,
            'transicionesStatusOt' => $transicionesStatusOt,
            'visitas' => $visitas,
            'firmaTecnico' => $firmaTecnico,
            'firmaCliente' => $firmaCliente,
            'imagenesAnexos' => $imagenesAnexos,
            'imagenesFotosTickets' => $imagenesFotosTickets,
            'marca' => $marca,
            'logoGKM' => $logoGKM,
            'modoVistaPrevia' => false
        ])->render();

        // 🔹 Generar PDF con Browsershot
        $pdfContent = Browsershot::html($html)
            ->format('A4')
            ->fullPage()
            ->noSandbox()
            ->setDelay(2000)
            ->emulateMedia('screen')
            ->waitUntilNetworkIdle()
            ->showBackground()
            ->pdf();

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $orden->numero_ticket . '.pdf"');
    }


    public function checkUpdates($idOt)
    {
        $ticket = Ticket::findOrFail($idOt);
        return response()->json([
            'ultimaActualizacion' => optional($ticket->updated_at)->toDateTimeString()
        ]);
    }

    public function procesarLogoMarca($imagenRaw)
    {
        $manager = new ImageManager(); // crear instancia
    
        $img = $manager->make($imagenRaw);
    
        // Redimensionar manteniendo proporciones, sin deformar
        $img->resize(256, 160, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        $canvas = $manager->canvas(256, 160, '#FFFFFF');
        
        $canvas->insert($img, 'center');
    
        return 'data:image/png;base64,' . base64_encode($canvas->encode('png'));
    }
    
    

    public function generateInformePdf($idOt)
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
            'visitas.fotostickest'
        ])->findOrFail($idOt);


        // 🔹 Obtener el idVisitas de la tabla seleccionarvisita según el idTickets
        $seleccionada = SeleccionarVisita::where('idTickets', $idOt)->first();

        // Si no se ha encontrado una visita seleccionada, manejar el caso adecuadamente
        if (!$seleccionada) {
            return response()->json(['success' => false, 'message' => 'No se encontró una visita seleccionada para este ticket.']);
        }

        // Obtener el idVisitas de la visita seleccionada
        $idVisitasSeleccionada = $seleccionada->idVisitas;

        // 🔹 Obtener transiciones de estado filtradas por idTickets y idVisitas
        $transicionesStatusOt = TransicionStatusTicket::where('idTickets', $idOt)
            ->where('idVisitas', $idVisitasSeleccionada)  // Filtrar por la visita seleccionada
            ->whereNotNull('justificacion')
            ->where('justificacion', '!=', '')
            ->with('estado_ot')
            ->get();

        // 🔹 OBTENER DATOS DEL PRODUCTO
        $producto = [
            'categoria' => $orden->modelo->categoria->nombre ?? 'No especificado',
            'marca' => $orden->modelo->marca->nombre ?? 'No especificado',
            'modelo' => $orden->modelo->nombre ?? 'No especificado',
            'serie' => $orden->serie ?? 'No especificado',
            'fallaReportada' => $orden->fallaReportada ?? 'No especificado' // 🔹 Agregamos la falla reportada
        ];
        $logoGKM = $this->procesarLogoMarca(file_get_contents(public_path('assets/images/auth/logogkm2.png')));
        $marca = $orden->modelo->marca ?? null;
        $marca->logo_base64 = null;
        
        if ($marca && !empty($marca->foto)) {
            $marca->logo_base64 = $this->procesarLogoMarca($marca->foto);
        }
        


        // 🔹 FORMATEAR VISITAS PARA LA VISTA
        $visitas = collect();
        if ($orden->visitas) {
            // Filtrar la visita seleccionada utilizando el idVisitas obtenido anteriormente
            $visitaSeleccionada = $orden->visitas->where('idVisitas', $idVisitasSeleccionada)->first();

            if ($visitaSeleccionada) {
                // Formatear la visita seleccionada para el informe
                $visitas = collect([ // Creamos un array solo con la visita seleccionada
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
        }

        // 🔹 OBTENER FIRMAS FILTRADAS POR idTickets Y idVisitas
        $firma = DB::table('firmas')->where('idTickets', $idOt)
            ->where('idVisitas', $idVisitasSeleccionada)  // Filtrar por la visita seleccionada
            ->first();

        // Aplicar optimización de imágenes a las firmas
        // $firmaTecnico = $firma && !empty($firma->firma_tecnico)
        //     ? $this->optimizeBase64Image('data:image/png;base64,' . base64_encode($firma->firma_tecnico))
        //     : null;

        $firmaCliente = $firma && !empty($firma->firma_cliente)
            ? $this->optimizeBase64Image('data:image/png;base64,' . base64_encode($firma->firma_cliente))
            : null;

        // 🔹 OBTENER IMÁGENES EN BASE64 (Filtrar los anexos de la visita seleccionada)
        $visitaSeleccionada = $orden->visitas->where('idVisitas', $idVisitasSeleccionada)->first();

        // 🔹 Obtener la firma del técnico (usuario)
        $firmaTecnico = null;
        if ($visitaSeleccionada && $visitaSeleccionada->tecnico) {
            // Obtener el técnico (usuario) asociado a la visita
            $tecnico = $visitaSeleccionada->tecnico;

            // Comprobar si el técnico tiene una firma en la tabla `usuarios`
            if ($tecnico && !empty($tecnico->firma)) {
                // Codificar la firma en base64 para ser mostrada en el PDF
                $firmaTecnico = 'data:image/png;base64,' . base64_encode($tecnico->firma);
            }
        }

        // Obtener los anexos de la visita seleccionada y optimizarlos
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

        // Obtener las imágenes de los tickets y optimizarlas
        $imagenesFotosTickets = $visitaSeleccionada->fotostickest->map(function ($foto) {
            return [
                'foto_base64' => !empty($foto->foto)
                    ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($foto->foto))
                    : null,
                'descripcion' => $foto->descripcion
            ];
        });



        // 🔹 Obtener la fecha_inicio de la visita seleccionada
        $fechaCreacion = $visitaSeleccionada && $visitaSeleccionada->fecha_inicio
            ? date('d/m/Y', strtotime($visitaSeleccionada->fecha_inicio)) // Formato dd/mm/yyyy
            : 'N/A';


        // 🔹 Verificar si el técnico es de tipoUsuario = 4
        $tipoUsuario = null;
        if ($visitaSeleccionada && $visitaSeleccionada->tecnico) {
            $tipoUsuario = $visitaSeleccionada->tecnico->idTipoUsuario ?? null;
        }

        // 🔹 Determinar la vista del PDF según el tipo de usuario
        $vistaPdf = ($tipoUsuario == 4)
            ? 'tickets.ordenes-trabajo.smart-tv.informe.pdf.informe_chofer'
            : 'tickets.ordenes-trabajo.smart-tv.informe.pdf.informe';

        $html = View($vistaPdf, [
            'orden' => $orden,
            'fechaCreacion' => $fechaCreacion,
            'producto' => $producto,
            'transicionesStatusOt' => $transicionesStatusOt,
            'visitas' => $visitas,
            'firmaTecnico' => $firmaTecnico,
            'firmaCliente' => $firmaCliente,
            'imagenesAnexos' => $imagenesAnexos,
            'imagenesFotosTickets' => $imagenesFotosTickets,
            'marca' => $marca,
            'logoGKM' => $logoGKM,
            'modoVistaPrevia' => false
        ])->render();

        // 🔹 GENERAR PDF EN MEMORIA CON BROWSERSHOT
        $pdfContent = Browsershot::html($html)
            ->format('A4')
            ->fullPage()
            ->noSandbox()
            ->setDelay(2000)
            ->emulateMedia('screen')
            ->waitUntilNetworkIdle()
            ->showBackground()
            ->pdf();

        // 🔹 RETORNAR PDF SIN GUARDARLO EN STORAGE
        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $orden->numero_ticket . '.pdf"');
    }


    private function buildInformeHtml($idOt, $idVisita, $modoVistaPrevia = false)
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
        $logoGKM = $this->procesarLogoMarca(file_get_contents(public_path('assets/images/auth/logogkm2.png')));
        $marca = $orden->modelo->marca ?? null;
        $marca->logo_base64 = null;

        if ($marca && !empty($marca->foto)) {
            $marca->logo_base64 = $this->procesarLogoMarca($marca->foto);
        }

        $visitas = collect();
        $visitaSeleccionada = $orden->visitas->where('idVisitas', $idVisitasSeleccionada)->first();
        if ($visitaSeleccionada) {
            $visitas = collect([[
                'nombre' => $visitaSeleccionada->nombre ?? 'N/A',
                'fecha_programada' => optional($visitaSeleccionada->fecha_programada)->format('d/m/Y'),
                'hora_inicio' => optional($visitaSeleccionada->fecha_inicio)->format('H:i'),
                'hora_final' => optional($visitaSeleccionada->fecha_final)->format('H:i'),
                'fecha_llegada' => optional($visitaSeleccionada->fecha_llegada)->format('d/m/Y H:i'),
                'tecnico' => ($visitaSeleccionada->tecnico->Nombre ?? 'N/A') . ' ' . ($visitaSeleccionada->tecnico->apellidoPaterno ?? ''),
                'correo' => $visitaSeleccionada->tecnico->correo ?? 'No disponible',
                'telefono' => $visitaSeleccionada->tecnico->telefono ?? 'No registrado',
                'documento' => $visitaSeleccionada->tecnico->documento ?? 'No disponible',
                'vehiculo_placa' => $visitaSeleccionada->tecnico->vehiculo->numero_placa ?? 'Sin placa',
            ]]);
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

        $imagenesAnexos = $visitaSeleccionada->anexos_visitas->map(function ($anexo) {
            return [
                'foto_base64' => !empty($anexo->foto)
                    ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($anexo->foto))
                    : null,
                'descripcion' => $anexo->descripcion
            ];
        });

        $imagenesFotosTickets = $visitaSeleccionada->fotostickest->map(function ($foto) {
            return [
                'foto_base64' => !empty($foto->foto)
                    ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($foto->foto))
                    : null,
                'descripcion' => $foto->descripcion
            ];
        });

        $fechaCreacion = optional($visitaSeleccionada->fecha_inicio)->format('d/m/Y') ?? 'N/A';
        $tipoUsuario = $visitaSeleccionada->tecnico->idTipoUsuario ?? null;

        $vistaPdf = ($tipoUsuario == 4)
            ? 'tickets.ordenes-trabajo.smart-tv.informe.pdf.informe_chofer'
            : 'tickets.ordenes-trabajo.smart-tv.informe.pdf.informe';

        return view($vistaPdf, [
            'orden' => $orden,
            'fechaCreacion' => $fechaCreacion,
            'producto' => $producto,
            'transicionesStatusOt' => $transicionesStatusOt,
            'visitas' => $visitas,
            'firmaTecnico' => $firmaTecnico,
            'firmaCliente' => $firmaCliente,
            'imagenesAnexos' => $imagenesAnexos,
            'imagenesFotosTickets' => $imagenesFotosTickets,
            'marca' => $marca,
            'logoGKM' => $logoGKM,
            'modoVistaPrevia' => $modoVistaPrevia
        ])->render();
    }

    public function vistaPreviaImagen($idOt, $idVisita)
    {
        $html = $this->buildInformeHtml($idOt, $idVisita, true);

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




    public function actualizarEstado(Request $request, $idTicket)
    {
        // Validamos el estado recibido (un número entero)
        $validated = $request->validate([
            'estado' => 'required|integer',
            'idVisita' => 'required|integer' // Aseguramos que el idVisita es un número entero
            // Aseguramos que el estado es un número entero
        ]);
    
        Log::info("Estado recibido: " . $validated['estado']);
    
        // Verificamos si el estado es uno de los valores válidos
        $estadosValidos = [7, 8, 6, 5]; // Estos son los estados válidos: finalizar, coordinar recojo, fuera de garantía, pendiente repuestos
    
        if (!in_array($validated['estado'], $estadosValidos)) {
            Log::error("Estado inválido recibido: " . $validated['estado']);
            return response()->json(['success' => false, 'message' => 'Estado inválido'], 400);
        }
    
        // Verificamos si el ticket existe
        $ticket = Ticket::find($idTicket);
        if (!$ticket) {
            Log::error("Ticket no encontrado con ID: " . $idTicket);
            return response()->json(['success' => false, 'message' => 'Ticket no encontrado'], 404);
        }
    
        Log::info("Ticket encontrado: " . $ticket->idTickets);
    
        // Insertar en la tabla ticketflujo usando insertGetId
        $ticketflujo = DB::table('ticketflujo')->insertGetId([
            'idTicket' => $idTicket,            // Asociamos el ticket
            'idEstadflujo' => $validated['estado'],  // El estado del flujo
            'idUsuario' => auth()->id(),        // ID del usuario que realiza la acción (por ejemplo, el usuario autenticado)
            'fecha_creacion' => now(),          // Fecha actual
        ]);
    
        // Verificar si el ID se generó correctamente
        if (!$ticketflujo) {
            Log::error('El idTicketFlujo no se generó correctamente');
            return response()->json(['success' => false, 'message' => 'El idTicketFlujo no se generó correctamente'], 500);
        }
    
        Log::info('TicketFlujo creado con ID: ' . $ticketflujo);
    
        // Actualizar el campo idTicketFlujo en la tabla tickets con el nuevo idTicketFlujo
        $updated = DB::table('tickets')
            ->where('idTickets', $idTicket)
            ->update(['idTicketFlujo' => $ticketflujo]);
    
            if ($updated) {
                Log::info("Ticket actualizado correctamente con idTicketFlujo: " . $ticketflujo);
        
                // Actualizar el campo estadovisita en la tabla visitas a 1 usando el idVisita recibido
                $updateVisita = DB::table('visitas')
                    ->where('idVisitas', $validated['idVisita'])  // Usamos el idVisita recibido
                    ->update(['estadovisita' => 1]);
        
                if ($updateVisita) {
                    Log::info("Visita actualizada correctamente con estadovisita = 1");
                } else {
                    Log::error("No se pudo actualizar el estadovisita para la visita con idVisitas: " . $validated['idVisita']);
                }
        
                return response()->json(['success' => true, 'message' => 'Estado actualizado correctamente']);
        } else {
            Log::error("Hubo un error al actualizar el idTicketFlujo en la tabla tickets.");
            return response()->json(['success' => false, 'message' => 'Hubo un error al actualizar el ticket'], 500);
        }
    }
    


    public function guardarEstadoflujo(Request $request)
    {
        // Validar los datos
        $request->validate([
            'idTicket' => 'required|integer|exists:tickets,idTickets',
            'idEstadflujo' => 'required|integer|exists:estado_flujo,idEstadflujo',
            // No necesitas validar el idUsuario aquí, ya que lo vamos a obtener del usuario autenticado
        ]);

        // Obtener el ID del usuario autenticado
        $idUsuario = auth()->user()->idUsuario;  // Asegúrate de usar auth()->user()->id

        // Insertar el registro en la tabla ticketflujo
        DB::table('ticketflujo')->insert([
            'idTicket' => $request->idTicket,
            'idEstadflujo' => $request->idEstadflujo,
            'idUsuario' => $idUsuario,  // Usamos el ID del usuario autenticado
            'fecha_creacion' => now(),
            'comentarioflujo' => $request->comentarioflujo ?? '',
        ]);

        // Obtener el último idTicketFlujo insertado
        $idTicketFlujo = DB::getPdo()->lastInsertId();

        // Actualizar la tabla tickets con el idTicketFlujo recién creado
        DB::table('tickets')
            ->where('idTickets', $request->idTicket)
            ->update([
                'idTicketFlujo' => $idTicketFlujo,  // Asignamos el idTicketFlujo al ticket
            ]);

        return response()->json(['success' => true]);
    }




    // Método en el controlador ImagenApoyoController.php
    public function getImagen($idVisita)
    {
        // Obtener la imagen y la descripción de la tabla 'imagenapoyosmart'
        $imagen = DB::table('imagenapoyosmart')->where('idVisitas', $idVisita)->first();

        // Verificar si se encontró la imagen
        if (!$imagen) {
            return response()->json(['error' => 'Imagen no encontrada'], 404);
        }

        // Retornar la imagen como base64
        return response()->json(['imagen' => base64_encode($imagen->imagen)]);
    }

    // Método en el controlador AnexosVisitasController.php
    public function getImagenInicioServicio($idVisita)
    {
        // Obtener la imagen y la descripción de la tabla 'anexos_visitas' con idTipovisita = 3
        $imagen = DB::table('anexos_visitas')
            ->where('idVisitas', $idVisita)
            ->where('idTipovisita', 3)
            ->first();

        // Verificar si se encontró la imagen
        if (!$imagen) {
            return response()->json(['error' => 'Imagen no encontrada'], 404);
        }

        // Retornar la imagen como base64
        return response()->json(['imagen' => base64_encode($imagen->foto)]);
    }


    public function getImagenTipo2($idVisita)
    {
        $imagen = DB::table('anexos_visitas')
            ->where('idVisitas', $idVisita)
            ->where('idTipovisita', 2)
            ->first();

        if (!$imagen) {
            return response()->json(['error' => 'Imagen no encontrada'], 404);
        }

        return response()->json(['imagen' => base64_encode($imagen->foto)]);
    }

    // Método en el controlador CondicionesTicketController.php
    public function getImagenFinalServicio($idVisita)
    {
        // Obtener la imagen de la tabla 'condicionesticket' según el idVisita
        $imagen = DB::table('condicionesticket')
            ->where('idVisitas', $idVisita)
            ->first();

        // Verificar si se encontró la imagen
        if (!$imagen || !$imagen->imagen) {
            return response()->json(['error' => 'Imagen no encontrada'], 404);
        }

        // Retornar la imagen como base64
        return response()->json(['imagen' => base64_encode($imagen->imagen)]);
    }





    public function guardarSolicitud(Request $request)
    {
        // Validar la solicitud
        $validated = $request->validate([
            'idTickets' => 'required|integer',
            'idVisitas' => 'required|integer',
        ]);

        // Verificar si ya existe una solicitud de entrega con el mismo idTickets, idVisitas y estado 0
        $existeSolicitud = SolicitudEntrega::where('idTickets', $validated['idTickets'])
            ->where('idVisitas', $validated['idVisitas'])
            ->where('estado', 0)
            ->exists();

        // Si ya existe una solicitud con esos parámetros, devolver un error
        if ($existeSolicitud) {
            return response()->json(['success' => false, 'message' => 'Ya existe una solicitud de entrega para esta visita.'], 400);
        }

        // Crear un nuevo registro en la tabla solicitudentrega
        $solicitud = new SolicitudEntrega();
        $solicitud->idTickets = $validated['idTickets'];
        $solicitud->idVisitas = $validated['idVisitas'];
        $solicitud->idUsuario = Auth::id(); // Obtener el id del usuario autenticado
        $solicitud->comentario = ''; // No asignamos ningún comentario
        $solicitud->estado = 0; // Estado inicial es 0
        $solicitud->fechaHora = now(); // Almacenar la fecha y hora actual

        $solicitud->save();

        // Crear la notificación
        $notification = [
            'id' => $solicitud->id,
            'message' => 'Nueva solicitud de entrega pendiente para el ticket #' . $solicitud->idTickets,
            'time' => now()->diffForHumans(),
            'profile' => 'default-profile.jpeg', // Asegúrate de tener una imagen predeterminada
        ];

        // Emitir el evento de notificación
        broadcast(new NotificacionNueva($notification));

        return response()->json(['success' => true, 'message' => 'Solicitud de entrega guardada correctamente.']);
    }






    // public function obtenerSolicitudes()
    // {
    //     // Obtener solicitudes con estado = 0
    //     $solicitudes = Solicitudentrega::where('estado', 0)->get(['idSolicitudentrega', 'comentario', 'idTickets', 'idVisitas']);

    //     return response()->json($solicitudes);
    // }


    public function obtenerSolicitudes()
    {
        // Obtener solicitudes con estado = 0
        $solicitudes = Solicitudentrega::where('solicitudentrega.estado', 0)  // Especificar la tabla para 'estado'
            ->join('visitas', 'solicitudentrega.idVisitas', '=', 'visitas.idVisitas')
            ->join('tickets', 'solicitudentrega.idTickets', '=', 'tickets.idTickets')
            ->join('usuarios', 'visitas.idUsuario', '=', 'usuarios.idUsuario')
            ->select(
                'solicitudentrega.idSolicitudentrega',
                'solicitudentrega.comentario',
                'solicitudentrega.idTickets',
                'solicitudentrega.idVisitas',
                'tickets.numero_ticket',   // Agregar el número de ticket
                'usuarios.Nombre as nombre_usuario',  // Agregar el nombre del usuario
                'solicitudentrega.fechaHora'  // Agregar la fecha y hora de la solicitud
            )
            ->get();

        return response()->json($solicitudes);
    }






    public function aceptarSolicitud($id)
    {
        // Verificar si el usuario está autenticado
        if (!auth()->check()) {
            return response()->json(['message' => 'Usuario no autenticado.'], 401);
        }

        // Buscar la solicitud por el ID
        $solicitud = Solicitudentrega::where('idSolicitudentrega', $id)->first();

        if ($solicitud) {
            // Cambiar el estado de la solicitud a "Aceptada"
            $solicitud->estado = 1; // Estado "Aceptada"
            $solicitud->save();

            // Registrar el ID del usuario autenticado
            Log::info('Usuario autenticado ID: ' . auth()->user()->idUsuario);

            // Crear una nueva visita
            $visita = new Visita();
            $visita->nombre = 'Laboratorio';
            $visita->fecha_programada = now();
            $visita->fecha_asignada = now();
            $visita->estado = 0;
            $visita->tipoServicio = 7;
            $visita->idTickets = $solicitud->idTickets;
            // $visita->idUsuario = auth()->user()->idUsuario;

            // Obtener el idUsuario del usuario autenticado
            $idUsuarioAutenticado = auth()->user()->idUsuario;

            // Lógica para asignar el idUsuario según las condiciones
            if (in_array($idUsuarioAutenticado, [10, 9])) {
                // Si el idUsuario autenticado es 6 o 7, asignarlo a la visita
                $visita->idUsuario = $idUsuarioAutenticado;
            } else {
                // Si el usuario no es 6 ni 7, asignamos aleatoriamente entre 6 y 7
                $visita->idUsuario = rand(10, 9);
            }

            $visita->save();

            // Registrar el flujo en ticketflujo
            $ticketFlujo = new TicketFlujo();
            $ticketFlujo->idTicket = $solicitud->idTickets;
            $ticketFlujo->idEstadflujo = 10; // Estado de flujo
            $ticketFlujo->idUsuario = auth()->user()->idUsuario;
            $ticketFlujo->fecha_creacion = now();
            $ticketFlujo->comentarioflujo = 'Ingresare comentario'; // Comentario
            $ticketFlujo->save();

            // Obtener el idTicketFlujo recién creado
            $idTicketFlujo = $ticketFlujo->idTicketFlujo;

            // Actualizar el registro en la tabla 'tickets' con el idTicketFlujo
            $ticket = Ticket::where('idTickets', $solicitud->idTickets)->first();
            if ($ticket) {
                $ticket->idTicketFlujo = $idTicketFlujo; // Actualizar con el idTicketFlujo
                $ticket->save();
            }

            return response()->json(['message' => 'Solicitud aceptada con éxito.']);
        }

        return response()->json(['message' => 'Solicitud no encontrada.'], 404);
    }



    public function denegarSolicitud($id)
    {
        // Aquí cambiamos 'find()' por 'where' y usamos 'idSolicitudentrega'
        $solicitud = Solicitudentrega::where('idSolicitudentrega', $id)->first();

        if ($solicitud) {
            $solicitud->estado = 2;  // Estado 2 significa "Rechazada"
            $solicitud->save();
            return response()->json(['message' => 'Solicitud rechazada con éxito.']);
        }

        return response()->json(['message' => 'Solicitud no encontrada.'], 404);
    }



    // Método para calcular el tiempo transcurrido usando Carbon
    private function calcularTiempoTranscurrido($fechaHora)
    {
        $now = Carbon::now();
        $fechaSolicitud = Carbon::parse($fechaHora);

        // Calculamos la diferencia en minutos
        $diffInMinutes = $now->diffInMinutes($fechaSolicitud);

        // Dependiendo de la diferencia, formateamos el tiempo
        if ($diffInMinutes < 1) {
            return 'Hace un momento';
        } elseif ($diffInMinutes < 5) {
            return 'Hace un momento';
        } elseif ($diffInMinutes < 30) {
            return "Hace $diffInMinutes minutos";
        } elseif ($diffInMinutes < 60) {
            return 'Hace una hora';
        } elseif ($diffInMinutes < 1440) {  // 1440 minutos = 1 día
            $diffInHours = $now->diffInHours($fechaSolicitud);
            return "Hace $diffInHours horas";
        } elseif ($diffInMinutes < 2880) {  // 2880 minutos = 2 días
            return 'Hace un día';
        } else {
            $diffInDays = $now->diffInDays($fechaSolicitud);
            return "Hace $diffInDays días";
        }
    }

    public function obtenerTecnicosDeApoyo($idVisitas, $idTicket)
    {
        $tecnicos = DB::table('ticketapoyo')
            ->join('usuarios', 'ticketapoyo.idTecnico', '=', 'usuarios.idUsuario')
            ->where('ticketapoyo.idVisita', $idVisitas)
            ->where('ticketapoyo.idTicket', $idTicket)
            ->select('ticketapoyo.idTicketApoyo', 'usuarios.idUsuario', 'usuarios.Nombre', 'usuarios.apellidoPaterno') // Selecciona idTicketApoyo
            ->get();
    
        return response()->json($tecnicos);
    }
    
    

    public function eliminar($idTicketApoyo)
    {
        $tecnicoApoyo = TicketApoyo::find($idTicketApoyo);
    
        if (!$tecnicoApoyo) {
            return response()->json(['success' => false, 'message' => 'Técnico de apoyo no encontrado.'], 404);
        }
    
        // Eliminar técnico de apoyo
        $tecnicoApoyo->delete();
    
        return response()->json(['success' => true, 'message' => 'Técnico de apoyo eliminado correctamente.']);
    }
    

}
