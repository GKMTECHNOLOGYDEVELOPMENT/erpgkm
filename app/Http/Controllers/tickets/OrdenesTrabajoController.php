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
use App\Models\ConstanciaEntrega;
use App\Models\ConstanciaFoto;
use App\Models\Custodia;
use App\Models\Fotostickest;
use App\Models\SeleccionarVisita;
use App\Models\SolicitudEntrega;
use App\Models\TicketFlujo;
use App\Models\TransicionStatusTicket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; // Asegúrate de usar esta clase
use Illuminate\Support\Facades\Validator;
use Spatie\Browsershot\Browsershot;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Facades\Image;



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
            Log::info('Inicio de la creaciÃ³n de orden de trabajo', ['data' => $request->all()]);
            // Manejar los checkboxes para que siempre estÃ©n presentes
            $request->merge([
                'evaluaciontienda' => $request->has('evaluaciontienda') ? 1 : 0,
            ]);

            // ValidaciÃ³n de los datos
            $validatedData = $request->validate([
                'nroTicket' => 'required|string|max:255|unique:tickets,numero_ticket',
                'idClienteGeneral' => 'required|integer|exists:clientegeneral,idClienteGeneral',
                'idCliente' => 'required|integer|exists:cliente,idCliente',
                'idTienda' => 'required|integer|exists:tienda,idTienda', // Permitir que idTienda sea nullable
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
                'evaluaciontienda' => 'required|in:0,1'
            ]);

            Log::info('Datos validados correctamente', ['validatedData' => $validatedData]);

            // Verificar si el cliente no es una tienda y se proporcionÃ³ un nombre de tienda
            $tiendaId = $validatedData['idTienda'];

            // Si no hay idTienda, significa que se estÃ¡ creando una tienda
            if (!$tiendaId && $request->has('nombreTienda') && $request->input('nombreTienda') !== "") {
                // Crear la tienda con los datos proporcionados
                $tienda = Tienda::create([
                    'nombre' => $request->input('nombreTienda'),
                ]);

                $tiendaId = $tienda->idTienda; // Guardar el ID de la tienda reciÃ©n creada
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
                'evaluaciontienda' => $validatedData['evaluaciontienda']
            ]);

            Log::info('Orden de trabajo creada correctamente', ['ticket' => $ticket]);

            // Verificar si el checkbox "Entrega a Lab" estÃ¡ marcado
            if ($request->has('entregaLab') && $request->input('entregaLab') === 'on') {
                // Si "Entrega a Lab" estÃ¡ marcado, solo se crea la visita y su flujo relacionado

                // Crear la visita
                $visita = Visita::create([
                    'nombre' => 'Laboratorio',  // El nombre de la visita
                    'fecha_programada' => now(),  // Fecha y hora programada: ahora
                    'fecha_asignada' => now(),    // Fecha de asignaciÃ³n: ahora
                    'fechas_desplazamiento' => null, // Asumimos que este campo puede ser null
                    'fecha_llegada' => null,  // Asumimos que este campo puede ser null
                    'fecha_inicio' => null,  // El campo 'fecha_inicio' debe ser null
                    'fecha_final' => null,  // El campo 'fecha_final' debe ser null
                    'estado' => 1,  // Estado inicial (puedes ajustarlo segÃºn sea necesario)
                    'idTickets' => $ticket->idTickets,  // El ID del ticket relacionado
                    'idUsuario' => $this->obtenerIdUsuario(),  // Llamar a la funciÃ³n para obtener el idUsuario correcto
                    'fecha_inicio_hora' => null,  // Este campo puede ser null
                    'fecha_final_hora' => null,  // Este campo puede ser null
                    'necesita_apoyo' => 0,  // Necesita apoyo (por defecto se establece en 0)
                    'tipoServicio' => 7,  // Tipo de servicio es 7, segÃºn lo solicitado
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
                // Si "Entrega a Lab" no estÃ¡ marcado, se maneja el flujo "es recojo"
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

            // Redirigir a la vista de ediciÃ³n del ticket con el ID del ticket reciÃ©n creado
            return redirect()->route('ordenes.edit', ['id' => $ticket->idTickets])
                ->with('success', 'Orden de trabajo creada correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // En caso de error en la validaciÃ³n
            Log::error('Errores de validaciÃ³n', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // En caso de cualquier otro error
            Log::error('Error al crear la orden de trabajo', ['exception' => $e->getMessage()]);
            return redirect()->back()->with('error', 'OcurriÃ³ un error al crear la orden de trabajo.');
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

        if ($cliente) {
            $esTiendacliente = (isset($cliente->esTienda) && $cliente->esTienda == 1) ? 1 : 0;
            Log::info("Cliente ID: {$cliente->id}, Tienda: {$cliente->esTienda}, esTiendacliente: {$esTiendacliente}");
        } else {
            $esTiendacliente = 0;
            Log::info("Cliente no encontrado. esTiendacliente: {$esTiendacliente}");
        }



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
        if ($colorEstado == '#B5FA37' || $colorEstado == '#FA4DF4') {
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
                        ->whereIn('idEstadflujo', [4, 3, 35])  // Solo obtener el estado con idEstadflujo 4
                        ->get();
                } elseif ($idEstadflujo == 2) {
                    // Si el idEstadflujo es 2, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [10, 7, 35])  // Obtener los estados con idEstadflujo 3 y 4
                        ->get();
                } elseif ($idEstadflujo == 11) {
                    // Si el idEstadflujo es 3, solo mostrar los estados con idEstadflujo 4
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [12, 13, 14, 15, 16, 17, 35])  // Obtener los estados con idEstadflujo 3 y 4
                        ->get();
                } elseif ($idEstadflujo == 12) {
                    // Si el id Estado flujo es 12 tiene que salir
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [35])
                        ->get();
                } elseif ($idEstadflujo == 18) {
                    // Si el id Estado flujo es 12 tiene que salir
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [3, 35])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 10) {
                    // Si el idEstadflujo es 3, solo mostrar los estados con idEstadflujo 4
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [11, 35])  // Solo obtener el estado con idEstadflujo 4
                        ->get();
                } elseif ($idEstadflujo == 1) {
                    // Si el ticket tiene un idTicketFlujo con idEstadflujo = 1, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [3, 8, 33, 35, 10])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 9) {
                    // Si el idEstadflujo del ticketflujo es 9, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [3, 33, 35, 2])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 14) {
                    // Si el idEstadflujo del ticketflujo es 9, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [19, 35])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 19) {
                    // Si el idEstadflujo del ticketflujo es 9, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [35])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 15) {
                    // Si el idEstadflujo del ticketflujo es 9, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [20, 35])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 20) {
                    // Si el idEstadflujo del ticketflujo es 9, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [35])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 16) {
                    // Si el idEstadflujo del ticketflujo es 9, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [21, 35])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 21) {
                    // Si el idEstadflujo del ticketflujo es 9, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [35])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 17) {
                    // Si el idEstadflujo del ticketflujo es 9, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [22, 35])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 22) {
                    // Si el idEstadflujo del ticketflujo es 9, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [35])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 8) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [3, 1, 33, 35])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 33) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [1, 5, 8, 9, 35])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 35) {
                    // Si el idEstadflujo es 8, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [1, 5, 8, 9, 35, 10])  // Solo obtener el estado con idEstadflujo 3
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
                ->whereIn('idEstadoots', [1, 3, 4])  // Filtrar solo por los estados 1 y 2
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






        // Verificar si se deben ocultar los estados de flujo
        if ($idEstadflujo == 2) {
            Log::info("EstadosFlujo ocultos: El idEstadflujo es 2. No se mostrarán estados.");
            $estadosFlujo = collect(); // Vaciar los estados si el estado flujo es 2
        } else {
            Log::info("EstadosFlujo visibles: idEstadflujo diferente de 2. Mostrando estados, incluso si ultimaVisitaConEstado1 es false.");
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
            'estadovisita',


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
            ->first();

        // Si ya existe una firma, redirigimos a la página de error 404
        if ($firmaExistente) {
            return view("pages.error404");
        }

        // Obtener la visita usando idVisitas
        $visita = DB::table('visitas')
            ->where('idVisitas', $idVisitas)
            ->where('idTickets', $id)
            ->first();

        // Verificamos que la visita exista
        if (!$visita) {
            return view("pages.error404");
        }

        // Obtener el cliente para verificar 'esTienda'
        $cliente = DB::table('cliente')
            ->where('idCliente', $ticket->idCliente)
            ->first();

        // Pasamos los datos a la vista, incluyendo el valor de 'esTienda' del cliente
        return view("tickets.ordenes-trabajo.smart-tv.firmas.firmacliente", compact(
            'ticket',
            'orden',
            'estadosOTS',
            'ticketId',
            'idVisitas',
            'visita',
            'id',
            'cliente' // Asegúrate de pasar la variable del cliente
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

    public function exportToExcel(Request $request)
    {
        return Excel::download(
            new TicketExport(
                $request->clienteGeneral,
                $request->startDate,
                $request->endDate
            ),
            'tickets.xlsx'
        );
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
        try {
            Log::info('🔍 Filtros recibidos en getAll', $request->all());

            $tipoTicket = $request->input('tipoTicket', 1);
            $perPage = $request->input('length', 10);
            $start = $request->input('start', 0);
            $page = ($start / $perPage) + 1;

            $query = Ticket::select([
                'idTickets',
                'numero_ticket',
                'fecha_creacion',
                'idModelo',
                'idMarca',
                'idCliente',
                'direccion',
                'serie',
                'idTicketFlujo',
                'idClienteGeneral' // ✅ AÑADIR

            ])
                ->with([
                    'cliente:idCliente,nombre',
                    'marca:idMarca,nombre',
                    'modelo:idModelo,nombre,idCategoria',
                    'modelo.categoria:idCategoria,nombre',
                    'ticketflujo:idTicketFlujo,idTicket,idEstadflujo',
                    'ticketflujo.estadoflujo:idEstadflujo,descripcion,color',
                    'seleccionarVisita:idselecionarvisita,idTickets,idVisitas,vistaseleccionada',
                    'seleccionarVisita.visita:idVisitas,nombre,fecha_programada,fecha_asignada,estado,idUsuario',
                    'seleccionarVisita.visita.tecnico:idUsuario,Nombre',
                    'clientegeneral:idClienteGeneral,descripcion', // ✅ AÑADIR
                    'visitas' => fn($q) => $q->select('idVisitas', 'idTickets', 'fecha_programada')
                        ->latest('fecha_programada')
                        ->limit(1),
                    'transicion_status_tickets' => fn($q) => $q->when(
                        $request->filled('idVisita'),
                        fn($q2) => $q2->whereHas(
                            'seleccionarVisita',
                            fn($q3) => $q3->where('idVisitas', $request->idVisita)
                        )->where('idEstadoots', 3)
                    )
                ])
                ->where('idTipotickets', $tipoTicket);

            // Aplicar filtros
            if ($request->filled('marca')) {
                $query->where('idMarca', $request->marca);
            }

            if ($request->filled('clienteGeneral')) {
                $query->where('idClienteGeneral', $request->clienteGeneral);
            }

            if ($request->filled('startDate') && $request->filled('endDate')) {
                $query->whereBetween('fecha_creacion', [
                    $request->startDate . ' 00:00:00',
                    $request->endDate . ' 23:59:59'
                ]);
            }

            // Búsqueda global
            if ($request->filled('search.value')) {
                $searchValue = trim($request->input('search.value'));
                $query->where(function ($q) use ($searchValue) {
                    $q->where('numero_ticket', 'LIKE', "%{$searchValue}%")
                        ->orWhere('serie', 'LIKE', "%{$searchValue}%")
                        ->orWhere('direccion', 'LIKE', "%{$searchValue}%")
                        ->orWhereHas('modelo', fn($q) => $q->where('nombre', 'LIKE', "%{$searchValue}%"))
                        ->orWhereHas('cliente', fn($q) => $q->where('nombre', 'LIKE', "%{$searchValue}%"))
                        ->orWhereHas('ticketflujo.estadoflujo', fn($q) => $q->where('descripcion', 'LIKE', "%{$searchValue}%"));
                });
            }


            // Ordenación
            $query->orderBy('fecha_creacion', 'desc');

            // Obtener total de registros
            $totalRecords = $query->count();

            // Paginación
            $tickets = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $tickets->total(),
                "data" => $tickets->items()
            ]);
        } catch (\Throwable $e) {
            Log::error('❌ Error en getAll()', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => "Error del servidor: " . $e->getMessage()
            ], 500);
        }
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





    public function clientesDatosCliente($ticketId) // Pasamos el ID del ticket
    {
        // Obtén el ticket usando el ID
        $orden = Ticket::find($ticketId);

        // Si no encuentras el ticket, puedes retornar una respuesta con error
        if (!$orden) {
            return response()->json(['error' => 'Orden no encontrada'], 404);
        }

        // Obtén el cliente seleccionado del ticket
        $clienteSeleccionado = optional($orden->cliente)->idCliente;

        // Obtén todos los clientes
        $clientes = Cliente::all();

        return response()->json([
            'clientes' => $clientes,
            'clienteSeleccionado' => $clienteSeleccionado, // Cliente seleccionado
        ]);
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
            'fechaCreacion' => 'required|date_format:Y-m-d H:i:s',
            'fallaReportada' => 'nullable|string',
            'erma' => 'nullable|string|max:255',
            'linkubicacion' => 'nullable|string|max:500',
            'lat' => 'nullable|string|max:50',
            'lng' => 'nullable|string|max:50',
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
        $orden->fecha_creacion = $request->fechaCreacion;
        $orden->fallaReportada = $request->fallaReportada;
        $orden->erma = $request->erma;
        $orden->linkubicacion = $request->linkubicacion;
        $orden->lat = $request->lat;
        $orden->lng = $request->lng;


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
        $request->validate([
            'field' => 'required|string',
            'oldValue' => 'nullable|string',
            'newValue' => 'nullable|string',
            'usuario' => 'required|string',
        ]);

        Modificacion::create([
            'idTickets'     => $id,
            'campo'         => $request->input('field'),
            'valor_antiguo' => $request->input('oldValue') ?? '',
            'valor_nuevo'   => $request->input('newValue') ?? '',
            'usuario'       => $request->input('usuario'),
        ]);

        return response()->json(['success' => true, 'message' => 'Modificación guardada correctamente']);
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

        // Obtener la primera palabra del nombre
        $primeraPalabra = explode(' ', trim($request->nombre))[0];

        // Validar si la primera palabra es 'Entrega' (sin importar mayúsculas/minúsculas)
        if (strcasecmp($primeraPalabra, 'Entrega') === 0) {
            $tipoServicio = 4;
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

        $visitas = Visita::with([
            'tecnico',
            'anexos_visitas' => function ($query) {
                $query->whereIn('idTipovisita', [2, 3, 4]);
            },
            'condicionesTickets'
        ])
            ->where('idTickets', $ticketId)
            ->get();

        Log::info('Visitas obtenidas para ticket ' . $ticketId, ['total_visitas' => $visitas->count()]);

        if ($visitas->isEmpty()) {
            Log::warning('No se encontraron visitas para el ticket: ' . $ticketId);
        }

        $visitas->each(function ($visita) {
            Log::info('Procesando visita ID: ' . $visita->idVisitas, [
                'nombre' => $visita->nombre,
                'fecha_inicio_hora' => $visita->fecha_inicio_hora,
                'fecha_final_hora' => $visita->fecha_final_hora
            ]);

            // Fechas en formato ISO
            $visita->fecha_inicio_hora = $visita->fecha_inicio_hora?->toIso8601String();
            $visita->fecha_final_hora = $visita->fecha_final_hora?->toIso8601String();

            // Técnico
            $visita->nombre_tecnico = $visita->tecnico ? $visita->tecnico->Nombre : null;
            $visita->idTipoUsuario = $visita->tecnico ? $visita->tecnico->idTipoUsuario : null;

            // Valores adicionales
            $visita->recojo = $visita->recojo ?? 0;
            $visita->tipoServicio = $visita->tipoServicio;
            $visita->idTicket = $visita->idTickets;
            $visita->idVisita = $visita->idVisitas;

            // ✅ Nombre de la visita (siempre del registro en la tabla visitas)
            $visita->nombre_visita = $visita->nombre;

            // ✅ Nombre del titular (si viene de condicionesTickets)
            $visita->nombre_titular = $visita->condicionesTickets->isNotEmpty()
                ? $visita->condicionesTickets[0]->nombre
                : null;

            Log::info('Visita procesada', [
                'nombre_visita' => $visita->nombre_visita,
                'nombre_titular' => $visita->nombre_titular,
                'nombre_tecnico' => $visita->nombre_tecnico,
                'idTicket' => $visita->idTicket
            ]);

            // Datos extra de condiciones
            $visita->servicio = $visita->condicionesTickets->isNotEmpty() ? $visita->condicionesTickets[0]->servicio : null;
            $visita->motivo = $visita->condicionesTickets->isNotEmpty() ? $visita->condicionesTickets[0]->motivo : null;
            $visita->titular = $visita->condicionesTickets->isNotEmpty() ? $visita->condicionesTickets[0]->titular : null;
            $visita->dni = $visita->condicionesTickets->isNotEmpty() ? $visita->condicionesTickets[0]->dni : null;
            $visita->telefono = $visita->condicionesTickets->isNotEmpty() ? $visita->condicionesTickets[0]->telefono : null;

            // Ocultar datos sensibles del técnico
            if ($visita->tecnico) {
                $visita->tecnico->makeHidden(['firma', 'avatar']);
            }

            // Anexos
            $visita->anexos_visitas->each(function ($anexovisita) {
                if ($anexovisita->foto) {
                    $anexovisita->foto = base64_encode($anexovisita->foto);
                }
            });

            // Condiciones
            if ($visita->condicionesTickets) {
                $visita->condicionesTickets->each(function ($condicion) {
                    if ($condicion->imagen) {
                        $condicion->imagen = base64_encode($condicion->imagen);
                    }
                });
            } else {
                $visita->condicionesTickets = [];
            }
        });

        return response()->json($visitas);
    }







    public function obtenerHistorialModificaciones($ticketId)
    {
        $page = request()->get('page', 1);
        $perPage = request()->get('per_page', 10);

        $historial = Modificacion::where('idTickets', $ticketId)
            ->orderBy('fecha_modificacion', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => $historial->items(),
            'current_page' => $historial->currentPage(),
            'last_page' => $historial->lastPage(),
            'per_page' => $historial->perPage(),
            'total' => $historial->total()
        ]);
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
        $visita->fecha_programada = $request->fecha_inicio_hora;
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
        $request->validate([
            'imagenes' => 'required|array',
            'imagenes.*' => 'image',
            'descripciones' => 'required|array',
            'descripciones.*' => 'string|max:255',
            'ticket_id' => 'required|integer|exists:tickets,idTickets',
        ]);

        if (!extension_loaded('gd') || !function_exists('imagewebp')) {
            return response()->json([
                'success' => false,
                'message' => 'El servidor no soporta WebP con GD.'
            ], 500);
        }

        $visita = DB::table('seleccionarvisita')
            ->where('idTickets', $request->ticket_id)
            ->first();

        if (!$visita) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró una visita válida para este ticket.'
            ], 400);
        }

        $visita_id = $visita->idVisitas;
        $imagenesGuardadas = [];

        // ✅ Usar el nuevo constructor con Driver explícito
        $manager = new ImageManager(new Driver());

        foreach ($request->file('imagenes') as $index => $imagen) {
            $descripcion = $request->descripciones[$index] ?? 'Sin descripción';

            $img = $manager->read($imagen->getRealPath()) // v3 usa `read` en vez de `make`
                ->scale(width: 1024)
                ->toWebp(quality: 90);

            $foto = new Fotostickest();
            $foto->idTickets = $request->ticket_id;
            $foto->idVisitas = $visita_id;
            $foto->foto = (string) $img;
            $foto->descripcion = $descripcion;
            $foto->save();

            $imagenesGuardadas[] = ['id' => $foto->id, 'descripcion' => $descripcion];
        }

        return response()->json([
            'success' => true,
            'message' => 'Imágenes procesadas y guardadas correctamente.',
            'imagenes' => $imagenesGuardadas
        ]);
    }





      public function obtenerImagenes($ticketId)
    {
        // Buscar el idVisitas en la tabla seleccionarvisita con base en el idTickets
        $seleccionada = DB::table('seleccionarvisita')
            ->where('idTickets', $ticketId)
            ->first();  // Solo buscamos por ticketId

        // Si se encontrÃ³ una visita asociada al ticket
        if ($seleccionada) {
            // Tomamos el idVisitas de la visita seleccionada
            $visitaId = $seleccionada->idVisitas;

            // Buscar las imÃ¡genes asociadas al ticket y la visita
            $imagenes = DB::table('fotostickest')
                ->where('idTickets', $ticketId)
                ->where('idVisitas', $visitaId)  // Usamos el idVisitas obtenido
                ->get();

            // Si no hay imÃ¡genes asociadas, retornar un mensaje
            if ($imagenes->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No se encontraron imÃ¡genes para este ticket y visita.']);
            }

            // Convertir las imÃ¡genes a base64 para poder mostrarlas en el frontend
            $imagenes = $imagenes->map(function ($imagen) {
                return [
                    'id' => $imagen->idfotostickest,
                    'src' => 'data:image/jpeg;base64,' . base64_encode($imagen->foto),
                    'description' => $imagen->descripcion
                ];
            });

            // Retornar la respuesta con las imÃ¡genes
            return response()->json(['imagenes' => $imagenes]);
        } else {
            // Si no se encontrÃ³ una visita para el ticket, devolver error
            return response()->json(['success' => false, 'message' => 'No se encontrÃ³ una visita seleccionada para este ticket.']);
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

    private function optimizeBase64Image($base64String, $calidad = 70, $destinoAncho = 600, $destinoAlto = 400)
    {
        if (!$base64String) return null;

        // Verificar que es una imagen base64 válida
        if (!preg_match('#^data:image/(\w+);base64,#i', $base64String, $matches)) {
            return $base64String;
        }

        $datosImagen = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64String));
        $origen = @imagecreatefromstring($datosImagen);
        if (!$origen) return $base64String;

        $anchoOriginal = imagesx($origen);
        $altoOriginal = imagesy($origen);

        // 🔁 Rotar si la imagen es vertical
        if ($altoOriginal > $anchoOriginal) {
            $origen = imagerotate($origen, -90, 0);
            $anchoOriginal = imagesx($origen);
            $altoOriginal = imagesy($origen);
        }

        // 📐 Calcular proporciones
        $ratioOriginal = $anchoOriginal / $altoOriginal;
        $ratioDestino = $destinoAncho / $destinoAlto;

        if ($ratioOriginal > $ratioDestino) {
            $nuevoAncho = $destinoAncho;
            $nuevoAlto = intval($destinoAncho / $ratioOriginal);
        } else {
            $nuevoAlto = $destinoAlto;
            $nuevoAncho = intval($destinoAlto * $ratioOriginal);
        }

        // 🎯 Crear imagen redimensionada con fondo transparente
        $resized = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        $transparente = imagecolorallocatealpha($resized, 0, 0, 0, 127);
        imagefilledrectangle($resized, 0, 0, $nuevoAncho, $nuevoAlto, $transparente);
        imagecopyresampled($resized, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $anchoOriginal, $altoOriginal);

        // 🖼️ Canvas final del tamaño deseado
        $canvas = imagecreatetruecolor($destinoAncho, $destinoAlto);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $transparente = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefilledrectangle($canvas, 0, 0, $destinoAncho, $destinoAlto, $transparente);

        $destX = intval(($destinoAncho - $nuevoAncho) / 2);
        $destY = intval(($destinoAlto - $nuevoAlto) / 2);
        imagecopy($canvas, $resized, $destX, $destY, 0, 0, $nuevoAncho, $nuevoAlto);

        // 🧭 Convertir a WebP optimizado
        ob_start();
        imagewebp($canvas, null, $calidad);
        $contenido = ob_get_clean();

        // 🧹 Liberar recursos
        imagedestroy($origen);
        imagedestroy($resized);
        imagedestroy($canvas);

        return 'data:image/webp;base64,' . base64_encode($contenido);
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

        $condicion = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisitasSeleccionada) // ✅
            ->first();

        $motivoCondicion = $condicion->motivo ?? null;

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
                    'telefono' => ($visitaSeleccionada->tecnico->telefono ?? 'No registrado'),
                    'vehiculo_placa' => $visitaSeleccionada->tecnico->vehiculo->numero_placa ?? 'Sin placa',

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

        // ✅ Solo cargar imágenes desde condicionesticket
        $imagenesAnexos = DB::table('condicionesticket')
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
            ? date('d/m/Y', strtotime($visitaSeleccionada->fecha_inicio))
            : 'N/A';

        // 🔹 Verificar si el técnico es de tipoUsuario = 4
        $tipoUsuario = null;
        if ($visitaSeleccionada && $visitaSeleccionada->tecnico) {
            $tipoUsuario = $visitaSeleccionada->tecnico->idTipoUsuario ?? null;
        }


        $nombreCliente = $orden->cliente->nombre ?? 'N/A';
        $tipoDocCliente = $orden->cliente->tipodocumento->nombre ?? 'Documento';
        $docCliente = $orden->cliente->documento ?? 'No disponible';

        if ($condicion && $condicion->titular == 0) {
            $nombreCliente = $condicion->nombre ?? $nombreCliente;
            $tipoDocCliente = 'DNI';
            $docCliente = $condicion->dni ?? $docCliente;
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
            'nombreCliente' => $nombreCliente,
            'tipoDocCliente' => $tipoDocCliente,
            'docCliente' => $docCliente,
            'condicion' => $condicion, // 👈 AGREGA ESTA LÍNEA
            'visitas' => $visitas,
            'firmaTecnico' => $firmaTecnico,
            'firmaCliente' => $firmaCliente,
            'imagenesAnexos' => $imagenesAnexos,
            'imagenesFotosTickets' => $imagenesFotosTickets,
            'marca' => $marca,
            'logoGKM' => $logoGKM,
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
            ->margins(3, 3, 3, 3)
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


    public function checkUpdates($idOt)
    {
        $ticket = Ticket::findOrFail($idOt);
        return response()->json([
            'ultimaActualizacion' => optional($ticket->updated_at)->toDateTimeString()
        ]);
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

        $condicion = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisitasSeleccionada) // ✅
            ->first();

        $motivoCondicion = $condicion->motivo ?? null;

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
        // 🔹 Agregar esto
        $condicion = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
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

            // Comprobar si el técnico tiene una firma en la tabla usuarios
            if ($tecnico && !empty($tecnico->firma)) {
                // Codificar la firma en base64 para ser mostrada en el PDF
                $firmaTecnico = 'data:image/png;base64,' . base64_encode($tecnico->firma);
            }
        }

        // ✅ Solo cargar imágenes desde condicionesticket
        $imagenesAnexos = DB::table('condicionesticket')
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


        // 🔹 Determinar vista según tipo de servicio
        $tipoServicio = $visitaSeleccionada->tipoServicio ?? null;
        $nombreVisita = Str::lower($visitaSeleccionada->nombre ?? '');
        // 🔹 Datos del cliente por defecto
        $nombreCliente = $orden->cliente->nombre ?? 'N/A';
        $tipoDocCliente = $orden->cliente->tipodocumento->nombre ?? 'Documento';
        $docCliente = $orden->cliente->documento ?? 'No disponible';

        // 🔹 Si el cliente no es titular, tomar los datos desde condicionesticket
        if ($condicion && $condicion->titular == 0) {
            $nombreCliente = $condicion->nombre ?? $nombreCliente;
            $tipoDocCliente = 'DNI';
            $docCliente = $condicion->dni ?? $docCliente;
        }


        if (Str::contains($nombreVisita, 'laboratorio')) {
            // 🔸 No se toca: laboratorio mantiene su vista
            $vistaPdf = 'tickets.ordenes-trabajo.smart-tv.informe.pdf.laboratorio';
        } elseif (in_array($tipoServicio, [3, 4])) {
            // 🔸 tipoServicio = 3 (recojo) o 4 (entrega)
            $vistaPdf = 'tickets.ordenes-trabajo.smart-tv.informe.pdf.informe_chofer';
        } elseif ($tipoServicio == 1) {
            // 🔸 tipoServicio = 1 (visita)
            $vistaPdf = 'tickets.ordenes-trabajo.smart-tv.informe.pdf.informe';
        } else {
            // 🔸 Cualquier otro tipo no contemplado
            $vistaPdf = 'tickets.ordenes-trabajo.smart-tv.informe.pdf.informe';
        }

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
            'motivoCondicion' => $motivoCondicion,
            'modoVistaPrevia' => false,
            'condicion' => $condicion, // 👈 AGREGA ESTO
            'firma' => $firma, // ✅ AÑADE ESTO
            'nombreCliente' => $nombreCliente,
            'tipoDocCliente' => $tipoDocCliente,
            'docCliente' => $docCliente,
        ])->render();

        // 1. Guardar PDF temporal
        $tempOriginal = tempnam(sys_get_temp_dir(), 'pdf_raw_') . '.pdf';
        Browsershot::html($html)
            ->format('A4')
            ->fullPage()
            ->noSandbox()
            ->setDelay(2000)
            ->margins(3, 3, 3, 3)
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

        $condicion = DB::table('condicionesticket')
            ->where('idTickets', $idOt)
            ->where('idVisitas', $idVisitasSeleccionada) // ✅
            ->first();

        $motivoCondicion = $condicion->motivo ?? null;

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
            'motivoCondicion' => $motivoCondicion,
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
        $estadosValidos = [7, 8, 6, 5, 18]; // Estos son los estados válidos: finalizar, coordinar recojo, fuera de garantía, pendiente repuestos

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



    // public function guardarEstadoflujo(Request $request)
    // {
    //     // Validar los datos
    //     $request->validate([
    //         'idTicket' => 'required|integer|exists:tickets,idTickets',
    //         'idEstadflujo' => 'required|integer|exists:estado_flujo,idEstadflujo',
    //     ]);

    //     // Obtener el ID del usuario autenticado
    //     $idUsuario = auth()->user()->idUsuario;

    //     // Insertar el registro en la tabla ticketflujo
    //     DB::table('ticketflujo')->insert([
    //         'idTicket' => $request->idTicket,
    //         'idEstadflujo' => $request->idEstadflujo,
    //         'idUsuario' => $idUsuario,
    //         'fecha_creacion' => now(),
    //         'comentarioflujo' => $request->comentarioflujo ?? '',
    //     ]);

    //     // Obtener el último idTicketFlujo insertado
    //     $idTicketFlujo = DB::getPdo()->lastInsertId();

    //     // Actualizar la tabla tickets con el idTicketFlujo recién creado
    //     DB::table('tickets')
    //         ->where('idTickets', $request->idTicket)
    //         ->update([
    //             'idTicketFlujo' => $idTicketFlujo,
    //         ]);

    //     // Si el estado de flujo es 10, crear una visita
    //     if ($request->idEstadflujo == 10) {
    //         $idUsuarioAleatorio = rand(0, 1) ? 139 : 140;

    //         DB::table('visitas')->insert([
    //             'nombre' => 'Laboratorio',
    //             'fecha_programada' => now(),
    //             'fecha_asignada' => now(),
    //             'fechas_desplazamiento' => null,
    //             'fecha_llegada' => null,
    //             'fecha_inicio' => null,
    //             'fecha_final' => null,
    //             'estado' => 1,
    //             'idTickets' => $request->idTicket,
    //             'idUsuario' => $idUsuarioAleatorio,
    //             'fecha_inicio_hora' => null,
    //             'fecha_final_hora' => null,
    //             'necesita_apoyo' => 0,
    //             'tipoServicio' => 7,
    //             'visto' => 0,
    //             'recojo' => null,
    //             'estadovisita' => null,
    //             'nombreclientetienda' => null,
    //             'celularclientetienda' => null,
    //             'dniclientetienda' => null,
    //         ]);
    //     }

    //     return response()->json(['success' => true]);
    // }



    public function guardarEstadoflujo(Request $request)
    {
        // Validar los datos
        $request->validate([
            'idTicket' => 'required|integer|exists:tickets,idTickets',
            'idEstadflujo' => 'required|integer|exists:estado_flujo,idEstadflujo',
        ]);

        // Obtener el ID del usuario autenticado
        $idUsuario = auth()->user()->idUsuario;

        // Insertar el registro en la tabla ticketflujo
        DB::table('ticketflujo')->insert([
            'idTicket' => $request->idTicket,
            'idEstadflujo' => $request->idEstadflujo,
            'idUsuario' => $idUsuario,
            'fecha_creacion' => now(),
            'comentarioflujo' => $request->comentarioflujo ?? '',
        ]);

        // Obtener el último idTicketFlujo insertado
        $idTicketFlujo = DB::getPdo()->lastInsertId();

        // Actualizar la tabla tickets con el idTicketFlujo recién creado
        DB::table('tickets')
            ->where('idTickets', $request->idTicket)
            ->update([
                'idTicketFlujo' => $idTicketFlujo,
            ]);

        // Si el estado de flujo es 10, crear una visita
        if ($request->idEstadflujo == 10) {
            $idUsuarioAleatorio = rand(0, 1) ? 139 : 140;

            DB::table('visitas')->insert([
                'nombre' => 'Laboratorio',
                'fecha_programada' => now(),
                'fecha_asignada' => now(),
                'fechas_desplazamiento' => null,
                'fecha_llegada' => null,
                'fecha_inicio' => null,
                'fecha_final' => null,
                'estado' => 1,
                'idTickets' => $request->idTicket,
                'idUsuario' => $idUsuarioAleatorio,
                'fecha_inicio_hora' => null,
                'fecha_final_hora' => null,
                'necesita_apoyo' => 0,
                'tipoServicio' => 7,
                'visto' => 0,
                'recojo' => null,
                'estadovisita' => null,
                'nombreclientetienda' => null,
                'celularclientetienda' => null,
                'dniclientetienda' => null,
            ]);
        }

        // Crear custodia automáticamente si el estado de flujo es 19 y el cliente tiene documento 20109072177
        if ($request->idEstadflujo == 19) {
            // Obtener el ticket con el cliente relacionado
            $ticket = Ticket::with('cliente')->find($request->idTicket);

            if ($ticket && $ticket->cliente && $ticket->cliente->documento == '20109072177') {
                // Verificar si ya existe una custodia para este ticket
                $custodiaExistente = Custodia::where('id_ticket', $ticket->idTickets)->first();

                if (!$custodiaExistente) {
                    // Crear la custodia automáticamente
                    $custodia = Custodia::create([
                        'codigocustodias'        => strtoupper(Str::random(10)),
                        'id_ticket'              => $ticket->idTickets,
                        'idcliente'              => $ticket->idCliente,
                        'numero_ticket'          => $ticket->numero_ticket,
                        'idMarca'                => $ticket->idMarca,
                        'idModelo'               => $ticket->idModelo,
                        'serie'                  => $ticket->serie,
                        'estado'                 => 'Pendiente',
                        'fecha_ingreso_custodia' => now()->toDateString(),
                        'ubicacion_actual'       => 'Almacén', // Valor por defecto
                        'responsable_entrega'    => null,
                        'id_responsable_recepcion'  => $idUsuario,
                        'observaciones'          => 'Custodia creada automáticamente por cambio de estado de flujo',
                        'fecha_devolucion'       => null,
                    ]);

                    // Opcional: Log para seguimiento
                    Log::info("Custodia creada automáticamente para el ticket {$ticket->idTickets} debido al estado de flujo 19");
                }
            }
        }

        return response()->json(['success' => true]);
    }


    public function eliminarflujo($id)
    {
        try {
            $flujo = DB::table('ticketflujo')->where('idTicketFlujo', $id)->first();

            if (!$flujo) {
                return response()->json(['success' => false, 'message' => 'Estado de flujo no encontrado'], 404);
            }

            DB::table('ticketflujo')->where('idTicketFlujo', $id)->delete();

            return response()->json(['success' => true, 'message' => 'Estado de flujo eliminado']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error eliminando: ' . $e->getMessage()], 500);
        }
    }


    public function relacionarFlujo(Request $request, $ticketId)
    {
        $request->validate([
            'flujoId' => 'required|integer|exists:ticketflujo,idTicketFlujo',
        ]);

        try {
            DB::table('tickets')
                ->where('idTickets', $ticketId)
                ->update([
                    'idTicketFlujo' => $request->flujoId
                ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
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
        $solicitud->idTipoServicio = 1;
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


    // public function obtenerSolicitudes()
    // {
    //     // Obtener solicitudes con estado = 0
    //     $solicitudes = Solicitudentrega::where('solicitudentrega.estado', 0)  // Especificar la tabla para 'estado'
    //         ->join('visitas', 'solicitudentrega.idVisitas', '=', 'visitas.idVisitas')
    //         ->join('tickets', 'solicitudentrega.idTickets', '=', 'tickets.idTickets')
    //         ->join('usuarios', 'visitas.idUsuario', '=', 'usuarios.idUsuario')
    //         ->select(
    //             'solicitudentrega.idSolicitudentrega',
    //             'solicitudentrega.comentario',
    //             'solicitudentrega.idTickets',
    //             'solicitudentrega.idVisitas',
    //             'tickets.numero_ticket',   // Agregar el número de ticket
    //             'usuarios.Nombre as nombre_usuario',  // Agregar el nombre del usuario
    //             'solicitudentrega.fechaHora'  // Agregar la fecha y hora de la solicitud
    //         )
    //         ->get();

    //     return response()->json($solicitudes);
    // }


    public function obtenerSolicitudes()
    {
        $usuario = auth()->user();
        Log::info('[NOTIFICACIONES] Inicio - Usuario ID: ' . $usuario->idUsuario . 
                ', Nombre: ' . $usuario->Nombre . 
                ', Rol: ' . $usuario->idRol);

        $solicitudesFiltradas = collect();
        $totalPorTipo = [];

        // 1. Solicitudes tipo 1 - Solicitud de entrega
        $permiso1 = 'VER_SOLICITUD_ENTREGA';
        if (\App\Helpers\PermisoHelper::tienePermiso($permiso1)) {
            Log::info('[NOTIFICACIONES] Usuario TIENE permiso: ' . $permiso1);
            $solicitudesTipo1 = Solicitudentrega::where('solicitudentrega.estado', 0)
                ->where('solicitudentrega.idTipoServicio', 1)
                ->leftJoin('visitas', 'solicitudentrega.idVisitas', '=', 'visitas.idVisitas')
                ->leftJoin('tickets', 'solicitudentrega.idTickets', '=', 'tickets.idTickets')
                ->leftJoin('usuarios', 'visitas.idUsuario', '=', 'usuarios.idUsuario')
                ->select(
                    'solicitudentrega.idSolicitudentrega',
                    'solicitudentrega.comentario',
                    'solicitudentrega.idTickets',
                    'solicitudentrega.idVisitas',
                    'solicitudentrega.idTipoServicio',
                    'tickets.numero_ticket',
                    'usuarios.Nombre as nombre_usuario',
                    'solicitudentrega.fechaHora'
                )
                ->get();
            
            $count1 = $solicitudesTipo1->count();
            $totalPorTipo[1] = $count1;
            Log::info('[NOTIFICACIONES] Tipo 1 encontradas: ' . $count1 . ' solicitudes');
            
            $solicitudesFiltradas = $solicitudesFiltradas->concat($solicitudesTipo1);
        } else {
            Log::info('[NOTIFICACIONES] Usuario NO tiene permiso: ' . $permiso1);
        }

        // 2. Solicitudes tipo 2 - Pendiente por programación
        $permiso2 = 'VER_PENDIENTE_PROGRAMACION';
        if (\App\Helpers\PermisoHelper::tienePermiso($permiso2)) {
            Log::info('[NOTIFICACIONES] Usuario TIENE permiso: ' . $permiso2);
            $solicitudesTipo2 = Solicitudentrega::where('solicitudentrega.estado', 0)
                ->where('solicitudentrega.idTipoServicio', 2)
                ->leftJoin('visitas', 'solicitudentrega.idVisitas', '=', 'visitas.idVisitas')
                ->leftJoin('tickets', 'solicitudentrega.idTickets', '=', 'tickets.idTickets')
                ->leftJoin('usuarios', 'visitas.idUsuario', '=', 'usuarios.idUsuario')
                ->select(
                    'solicitudentrega.idSolicitudentrega',
                    'solicitudentrega.comentario',
                    'solicitudentrega.idTickets',
                    'solicitudentrega.idVisitas',
                    'solicitudentrega.idTipoServicio',
                    'tickets.numero_ticket',
                    'usuarios.Nombre as nombre_usuario',
                    'solicitudentrega.fechaHora'
                )
                ->get();
            
            $count2 = $solicitudesTipo2->count();
            $totalPorTipo[2] = $count2;
            Log::info('[NOTIFICACIONES] Tipo 2 encontradas: ' . $count2 . ' solicitudes');
            
            $solicitudesFiltradas = $solicitudesFiltradas->concat($solicitudesTipo2);
        } else {
            Log::info('[NOTIFICACIONES] Usuario NO tiene permiso: ' . $permiso2);
        }

        // 3. Solicitudes tipo 3 - Ingreso a laboratorio
        $permiso3 = 'VER_INGRESO_LABORATORIO';
        if (\App\Helpers\PermisoHelper::tienePermiso($permiso3)) {
            Log::info('[NOTIFICACIONES] Usuario TIENE permiso: ' . $permiso3);
            $solicitudesTipo3 = Solicitudentrega::where('solicitudentrega.estado', 0)
                ->where('solicitudentrega.idTipoServicio', 3)
                ->leftJoin('visitas', 'solicitudentrega.idVisitas', '=', 'visitas.idVisitas')
                ->leftJoin('tickets', 'solicitudentrega.idTickets', '=', 'tickets.idTickets')
                ->leftJoin('usuarios', 'visitas.idUsuario', '=', 'usuarios.idUsuario')
                ->select(
                    'solicitudentrega.idSolicitudentrega',
                    'solicitudentrega.comentario',
                    'solicitudentrega.idTickets',
                    'solicitudentrega.idVisitas',
                    'solicitudentrega.idTipoServicio',
                    'tickets.numero_ticket',
                    'usuarios.Nombre as nombre_usuario',
                    'solicitudentrega.fechaHora'
                )
                ->get();
            
            $count3 = $solicitudesTipo3->count();
            $totalPorTipo[3] = $count3;
            Log::info('[NOTIFICACIONES] Tipo 3 encontradas: ' . $count3 . ' solicitudes');
            
            $solicitudesFiltradas = $solicitudesFiltradas->concat($solicitudesTipo3);
        } else {
            Log::info('[NOTIFICACIONES] Usuario NO tiene permiso: ' . $permiso3);
        }

        // 4. Solicitudes tipo 4 - Observación de asistencia
        $permiso4 = 'VER_OBSERVACION_ASISTENCIA';
        if (\App\Helpers\PermisoHelper::tienePermiso($permiso4)) {
            Log::info('[NOTIFICACIONES] Usuario TIENE permiso: ' . $permiso4);
            $solicitudesTipo4 = Solicitudentrega::where('solicitudentrega.estado', 0)
                ->where('solicitudentrega.idTipoServicio', 4)
                ->leftJoin('visitas', 'solicitudentrega.idVisitas', '=', 'visitas.idVisitas')
                ->leftJoin('tickets', 'solicitudentrega.idTickets', '=', 'tickets.idTickets')
                ->leftJoin('usuarios', 'visitas.idUsuario', '=', 'usuarios.idUsuario')
                ->select(
                    'solicitudentrega.idSolicitudentrega',
                    'solicitudentrega.comentario',
                    'solicitudentrega.idTickets',
                    'solicitudentrega.idVisitas',
                    'solicitudentrega.idTipoServicio',
                    'tickets.numero_ticket',
                    'usuarios.Nombre as nombre_usuario',
                    'solicitudentrega.fechaHora'
                )
                ->get();
            
            $count4 = $solicitudesTipo4->count();
            $totalPorTipo[4] = $count4;
            Log::info('[NOTIFICACIONES] Tipo 4 encontradas: ' . $count4 . ' solicitudes');
            
            $solicitudesFiltradas = $solicitudesFiltradas->concat($solicitudesTipo4);
        } else {
            Log::info('[NOTIFICACIONES] Usuario NO tiene permiso: ' . $permiso4);
        }

        // 5. Solicitudes tipo 5 - Solicitud de repuestos
        $permiso5 = 'VER_SOLICITUD_REPUESTOS';
        if (\App\Helpers\PermisoHelper::tienePermiso($permiso5)) {
            Log::info('[NOTIFICACIONES] Usuario TIENE permiso: ' . $permiso5);
            $solicitudesTipo5 = Solicitudentrega::where('solicitudentrega.estado', 0)
                ->where('solicitudentrega.idTipoServicio', 5)
                ->leftJoin('usuarios', 'solicitudentrega.idUsuario', '=', 'usuarios.idUsuario')
                ->select(
                    'solicitudentrega.idSolicitudentrega',
                    'solicitudentrega.comentario',
                    'solicitudentrega.idTipoServicio',
                    'usuarios.Nombre as nombre_usuario',
                    'solicitudentrega.fechaHora'
                )
                ->get()
                ->map(function ($item) {
                    $item->numero_ticket = null;
                    $item->idTickets = null;
                    $item->idVisitas = null;
                    return $item;
                });
            
            $count5 = $solicitudesTipo5->count();
            $totalPorTipo[5] = $count5;
            Log::info('[NOTIFICACIONES] Tipo 5 encontradas: ' . $count5 . ' solicitudes');
            
            $solicitudesFiltradas = $solicitudesFiltradas->concat($solicitudesTipo5);
        } else {
            Log::info('[NOTIFICACIONES] Usuario NO tiene permiso: ' . $permiso5);
        }

        // 6. Solicitudes tipo 6 - Solicitud de artículo
        $permiso6 = 'VER_SOLICITUD_ARTICULO';
        if (\App\Helpers\PermisoHelper::tienePermiso($permiso6)) {
            Log::info('[NOTIFICACIONES] Usuario TIENE permiso: ' . $permiso6);
            $solicitudesTipo6 = Solicitudentrega::where('solicitudentrega.estado', 0)
                ->where('solicitudentrega.idTipoServicio', 6)
                ->leftJoin('usuarios', 'solicitudentrega.idUsuario', '=', 'usuarios.idUsuario')
                ->select(
                    'solicitudentrega.idSolicitudentrega',
                    'solicitudentrega.comentario',
                    'solicitudentrega.idTipoServicio',
                    'usuarios.Nombre as nombre_usuario',
                    'solicitudentrega.fechaHora'
                )
                ->get()
                ->map(function ($item) {
                    $item->numero_ticket = null;
                    $item->idTickets = null;
                    $item->idVisitas = null;
                    return $item;
                });
            
            $count6 = $solicitudesTipo6->count();
            $totalPorTipo[6] = $count6;
            Log::info('[NOTIFICACIONES] Tipo 6 encontradas: ' . $count6 . ' solicitudes');
            
            $solicitudesFiltradas = $solicitudesFiltradas->concat($solicitudesTipo6);
        } else {
            Log::info('[NOTIFICACIONES] Usuario NO tiene permiso: ' . $permiso6);
        }

        // 7. Solicitudes tipo 7 - Solicitud de custodia
        $permiso7 = 'VER_SOLICITUD_CUSTODIA';
        if (\App\Helpers\PermisoHelper::tienePermiso($permiso7)) {
            Log::info('[NOTIFICACIONES] Usuario TIENE permiso: ' . $permiso7);
            $solicitudesTipo7 = Solicitudentrega::where('solicitudentrega.estado', 0)
                ->where('solicitudentrega.idTipoServicio', 7)
                ->leftJoin('usuarios', 'solicitudentrega.idUsuario', '=', 'usuarios.idUsuario')
                ->select(
                    'solicitudentrega.idSolicitudentrega',
                    'solicitudentrega.comentario',
                    'solicitudentrega.idTipoServicio',
                    'usuarios.Nombre as nombre_usuario',
                    'solicitudentrega.fechaHora'
                )
                ->get()
                ->map(function ($item) {
                    $item->numero_ticket = null;
                    $item->idTickets = null;
                    $item->idVisitas = null;
                    return $item;
                });
            
            $count7 = $solicitudesTipo7->count();
            $totalPorTipo[7] = $count7;
            Log::info('[NOTIFICACIONES] Tipo 7 encontradas: ' . $count7 . ' solicitudes');
            
            $solicitudesFiltradas = $solicitudesFiltradas->concat($solicitudesTipo7);
        } else {
            Log::info('[NOTIFICACIONES] Usuario NO tiene permiso: ' . $permiso7);
        }

        // 8. Solicitudes tipo 8 - Solicitud de abastecimiento
        $permiso8 = 'VER_SOLICITUD_ABASTECIMIENTO';
        if (\App\Helpers\PermisoHelper::tienePermiso($permiso8)) {
            Log::info('[NOTIFICACIONES] Usuario TIENE permiso: ' . $permiso8);
            $solicitudesTipo8 = Solicitudentrega::where('solicitudentrega.estado', 0)
                ->where('solicitudentrega.idTipoServicio', 8)
                ->leftJoin('usuarios', 'solicitudentrega.idUsuario', '=', 'usuarios.idUsuario')
                ->select(
                    'solicitudentrega.idSolicitudentrega',
                    'solicitudentrega.comentario',
                    'solicitudentrega.idTipoServicio',
                    'usuarios.Nombre as nombre_usuario',
                    'solicitudentrega.fechaHora'
                )
                ->get()
                ->map(function ($item) {
                    $item->numero_ticket = null;
                    $item->idTickets = null;
                    $item->idVisitas = null;
                    return $item;
                });
            
            $count8 = $solicitudesTipo8->count();
            $totalPorTipo[8] = $count8;
            Log::info('[NOTIFICACIONES] Tipo 8 encontradas: ' . $count8 . ' solicitudes');
            
            $solicitudesFiltradas = $solicitudesFiltradas->concat($solicitudesTipo8);
        } else {
            Log::info('[NOTIFICACIONES] Usuario NO tiene permiso: ' . $permiso8);
        }

        $totalFinal = $solicitudesFiltradas->count();
        
        // Log detallado del resumen
        Log::info('[NOTIFICACIONES] === RESUMEN ===');
        Log::info('[NOTIFICACIONES] Usuario: ' . $usuario->Nombre . ' (ID: ' . $usuario->idUsuario . ')');
        Log::info('[NOTIFICACIONES] Total de solicitudes encontradas: ' . $totalFinal);
        
        if ($totalFinal > 0) {
            Log::info('[NOTIFICACIONES] Distribución por tipo:');
            foreach ($totalPorTipo as $tipo => $cantidad) {
                $nombreTipo = $this->getNombreTipoServicio($tipo);
                Log::info('[NOTIFICACIONES]   - Tipo ' . $tipo . ' (' . $nombreTipo . '): ' . $cantidad);
            }
            
            // Log de las primeras 3 solicitudes para debugging
            Log::info('[NOTIFICACIONES] Primeras solicitudes:');
            foreach ($solicitudesFiltradas->take(3) as $index => $solicitud) {
                Log::info('[NOTIFICACIONES]   [' . ($index + 1) . '] ID: ' . $solicitud->idSolicitudentrega . 
                        ', Tipo: ' . $solicitud->idTipoServicio . 
                        ', Usuario: ' . ($solicitud->nombre_usuario ?? 'N/A') .
                        ', Ticket: ' . ($solicitud->numero_ticket ?? 'N/A'));
            }
        } else {
            Log::info('[NOTIFICACIONES] No se encontraron solicitudes para este usuario');
        }
        Log::info('[NOTIFICACIONES] === FIN RESUMEN ===');

        return response()->json($solicitudesFiltradas);
    }

    // Método auxiliar para obtener nombre del tipo de servicio
    private function getNombreTipoServicio($tipo)
    {
        $nombres = [
            1 => 'Solicitud de entrega',
            2 => 'Pendiente por programación',
            3 => 'Ingreso a laboratorio',
            4 => 'Observación de asistencia',
            5 => 'Solicitud de repuestos',
            6 => 'Solicitud de artículo',
            7 => 'Solicitud de custodia',
            8 => 'Solicitud de abastecimiento'
        ];
        
        return $nombres[$tipo] ?? 'Tipo desconocido';
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

            // Si el tipo de servicio es 2 o 3, solo cambiamos el estado y salimos
            if (in_array($solicitud->idTipoServicio, [2, 3])) {
                return response()->json(['message' => 'Estado de la solicitud actualizado.']);
            }

            // Registrar el ID del usuario autenticado
            Log::info('Usuario autenticado ID: ' . auth()->user()->idUsuario);

            // Crear una nueva visita
            $visita = new Visita();
            $visita->nombre = 'Laboratorio';
            $visita->fecha_programada = now();
            $visita->fecha_asignada = now();
            $visita->fecha_inicio_hora = now();
            $visita->fecha_final_hora = now();
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


    public function storeConstancia(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validar datos (quitamos unique validation)
            $validated = $request->validate([
                'nrticket' => 'required',
                'tipo' => 'required',
                'fechacompra' => 'required|date',
                'nombrecliente' => 'required',
                'emailcliente' => 'nullable|email',
                'direccioncliente' => 'nullable',
                'telefonocliente' => 'nullable',
                'observaciones' => 'nullable',
                'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB
                'idticket' => 'nullable|exists:tickets,idTickets',
                'fotos_existentes' => 'nullable|json' // Para manejar fotos existentes
            ]);

            // Buscar constancia existente
            $constancia = ConstanciaEntrega::where('numeroticket', $validated['nrticket'])->first();

            if ($constancia) {
                // Actualizar constancia existente
                $constancia->update([
                    'tipo' => $validated['tipo'],
                    'fechacompra' => $validated['fechacompra'],
                    'nombrecliente' => $validated['nombrecliente'],
                    'emailcliente' => $validated['emailcliente'],
                    'direccioncliente' => $validated['direccioncliente'],
                    'telefonocliente' => $validated['telefonocliente'],
                    'observaciones' => $validated['observaciones'],
                    'idticket' => $validated['idticket']
                ]);

                $message = 'Constancia actualizada correctamente';
            } else {
                // Crear nueva constancia
                $constancia = ConstanciaEntrega::create([
                    'numeroticket' => $validated['nrticket'],
                    'tipo' => $validated['tipo'],
                    'fechacompra' => $validated['fechacompra'],
                    'nombrecliente' => $validated['nombrecliente'],
                    'emailcliente' => $validated['emailcliente'],
                    'direccioncliente' => $validated['direccioncliente'],
                    'telefonocliente' => $validated['telefonocliente'],
                    'observaciones' => $validated['observaciones'],
                    'idticket' => $validated['idticket']
                ]);

                $message = 'Constancia creada correctamente';
            }

            // Manejar fotos existentes (eliminar las que no están en el array)
            if ($request->filled('fotos_existentes')) {
                $fotosAMantener = json_decode($request->fotos_existentes, true);

                ConstanciaFoto::where('idconstancia', $constancia->idconstancia)
                    ->whereNotIn('idfoto', $fotosAMantener)
                    ->delete();
            }

            // Guardar nuevas fotos
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $imageData = file_get_contents($photo->getRealPath());

                    ConstanciaFoto::create([
                        'idconstancia' => $constancia->idconstancia,
                        'imagen' => $imageData,
                        'descripcion' => 'Foto adjunta a constancia'
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'constancia_id' => $constancia->idconstancia
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar constancia: ' . $e->getMessage()
            ], 500);
        }
    }




    public function porTicket($ticketId)
    {
        $constancia = ConstanciaEntrega::with(['fotos' => function ($query) {
            $query->select('idfoto', 'idconstancia', 'descripcion');
        }])->where('idticket', $ticketId)->first();

        if (!$constancia) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró constancia para este ticket'
            ]);
        }

        // Transformar fotos para incluir URLs
        $constancia->fotos->transform(function ($foto) {
            $foto->imagen_url = route('constancias.fotos.mostrar', $foto->idfoto);
            return $foto;
        });

        return response()->json([
            'success' => true,
            'constancia' => $constancia
        ]);
    }

    public function eliminarFoto($fotoId)
    {
        $foto = ConstanciaFoto::findOrFail($fotoId);
        $foto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Foto eliminada correctamente'
        ]);
    }

    // En el controlador
    public function mostrarFoto($id)
    {
        $foto = ConstanciaFoto::findOrFail($id);
        return response($foto->imagen)
            ->header('Content-Type', 'image/jpeg');
    }

    public function descargarPDF($id)
    {
        try {
            Log::info("\ud83d\udcc5 Iniciando descarga de constancia para ticket ID: {$id}");

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
            ])->findOrFail($id);

            $seleccionada = SeleccionarVisita::where('idTickets', $id)->first();
            if (!$seleccionada) {
                abort(404, 'No se encontr\xf3 una visita seleccionada para este ticket.');
            }

            $idVisitasSeleccionada = $seleccionada->idVisitas;

            $transicionesStatusOt = TransicionStatusTicket::where('idTickets', $id)
                ->where('idVisitas', $idVisitasSeleccionada)
                ->whereNotNull('justificacion')
                ->where('justificacion', '!=', '')
                ->with('estado_ot')
                ->get();

            $equipo = DB::table('equipos')
                ->where('idTickets', $id)
                ->where('idVisitas', $idVisitasSeleccionada)
                ->first();

            // 🔹 Si encontramos el equipo, buscamos su modelo, marca y categoría
            $modelo = null;
            $marca = null;
            $categoria = null;

            if ($equipo) {
                $modelo = \App\Models\Modelo::find($equipo->idModelo);
                $marca = \App\Models\Marca::find($equipo->idMarca);
                $categoria = \App\Models\Categoria::find($equipo->idCategoria);
            }

            // 🔹 Armamos los datos del producto
            $producto = [
                'categoria' => $categoria->nombre ?? 'No especificado',
                'marca' => $marca->nombre ?? 'No especificado',
                'modelo' => $modelo->nombre ?? 'No especificado',
                'serie' => $equipo->nserie ?? 'No especificado',
                'fallaReportada' => $orden->fallaReportada ?? 'No especificado'
            ];

            $condicion = DB::table('condicionesticket')
                ->where('idTickets', $id)
                ->where('idVisitas', $idVisitasSeleccionada)
                ->first();

            $motivoCondicion = $condicion->motivo ?? null;
            $constancia = DB::table('constancia_entregas')
                ->where('idticket', $id)
                ->first();

            $constanciaFotos = DB::table('constancia_fotos')
                ->where('idconstancia', $constancia->idconstancia ?? 0)
                ->get()
                ->map(function ($foto) {
                    $mime = finfo_buffer(finfo_open(), $foto->imagen, FILEINFO_MIME_TYPE);
                    $base64 = $foto->imagen ? 'data:' . $mime . ';base64,' . base64_encode($foto->imagen) : null;
                    return [
                        'foto_base64' => $base64 ? $this->optimizeBase64Image($base64) : null,
                        'descripcion' => $foto->descripcion ?? 'Sin descripción'
                    ];
                });


            $logoGKM = $this->procesarLogoMarca(file_get_contents(public_path('assets/images/auth/logogkm2.png')));

            if ($marca) {
                $marca->logo_base64 = $marca->foto ? $this->procesarLogoMarca($marca->foto) : null;
            }
            $visitaSeleccionada = $orden->visitas->where('idVisitas', $idVisitasSeleccionada)->first();

            $visitas = collect();
            if ($visitaSeleccionada) {
                $visitas = collect([
                    [
                        'nombre' => $visitaSeleccionada->nombre ?? 'N/A',
                        'fecha_programada' => optional($visitaSeleccionada->fecha_programada)->format('d/m/Y') ?? 'N/A',
                        'hora_inicio' => optional($visitaSeleccionada->fecha_inicio)->format('H:i') ?? 'N/A',
                        'hora_final' => optional($visitaSeleccionada->fecha_final)->format('H:i') ?? 'N/A',
                        'fecha_llegada' => optional($visitaSeleccionada->fecha_llegada)->format('d/m/Y H:i') ?? 'N/A',
                        'tecnico' => ($visitaSeleccionada->tecnico->Nombre ?? 'N/A') . ' ' . ($visitaSeleccionada->tecnico->apellidoPaterno ?? ''),
                        'correo' => $visitaSeleccionada->tecnico->correo ?? 'No disponible',
                        'telefono' => $visitaSeleccionada->tecnico->telefono ?? 'No registrado',
                        'documento' => $visitaSeleccionada->tecnico->documento ?? 'No disponible',
                        'tipo_documento' => $visitaSeleccionada->tecnico->tipodocumento->nombre ?? 'Documento',
                        'vehiculo_placa' => $visitaSeleccionada->tecnico->vehiculo->numero_placa ?? 'Sin placa'
                    ]
                ]);
            }

            $firma = DB::table('firmas')->where('idTickets', $id)
                ->where('idVisitas', $idVisitasSeleccionada)
                ->first();

            $firmaCliente = $firma && $firma->firma_cliente ? $this->optimizeBase64Image('data:image/png;base64,' . base64_encode($firma->firma_cliente)) : null;
            $firmaTecnico = $visitaSeleccionada->tecnico->firma ?? null;
            $firmaTecnico = $firmaTecnico ? 'data:image/png;base64,' . base64_encode($firmaTecnico) : null;

            $imagenesAnexos = $visitaSeleccionada->anexos_visitas->map(function ($anexo) {
                return [
                    'foto_base64' => $anexo->foto ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($anexo->foto)) : null,
                    'descripcion' => $anexo->descripcion
                ];
            });

            $imagenesCondiciones = DB::table('condicionesticket')
                ->where('idTickets', $id)
                ->where('idVisitas', $idVisitasSeleccionada)
                ->whereNotNull('imagen')
                ->get()
                ->map(function ($condicion) {
                    return [
                        'foto_base64' => $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($condicion->imagen)),
                        'descripcion' => 'CONDICION: ' . ($condicion->motivo ?? 'Sin descripcion')
                    ];
                });

            $imagenesAnexos = $imagenesAnexos->merge($imagenesCondiciones);

            $imagenesFotosTickets = $visitaSeleccionada->fotostickest->map(function ($foto) {
                return [
                    'foto_base64' => $foto->foto ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($foto->foto)) : null,
                    'descripcion' => $foto->descripcion
                ];
            });

            $fechaCreacion = optional($visitaSeleccionada->fecha_inicio)->format('d/m/Y') ?? 'N/A';

            $html = view('tickets.ordenes-trabajo.smart-tv.informe.pdf.constancia_entrega', [
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
                'motivoCondicion' => $motivoCondicion,
                'constancia' => $constancia,
                'constanciaFotos' => $constanciaFotos,
                'modoVistaPrevia' => false,
                'firma' => $firma
            ])->render();

            $pdf = Browsershot::html($html)
                ->format('A4')
                ->fullPage()
                ->noSandbox()
                ->emulateMedia('screen')
                ->waitUntilNetworkIdle()
                ->showBackground()
                ->pdf();

            return response($pdf)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="ORDEN_DE_INGRESO ' . $orden->idTickets . '.pdf"');
        } catch (\Exception $e) {
            Log::error('Error generando constancia PDF: ' . $e->getMessage());
            abort(500, 'Error generando PDF');
        }
    }


    public function descargarPDF_App($id, $idVisitas)
    {
        try {
            Log::info("\ud83d\udcc5 Iniciando descarga de constancia para ticket ID: {$id}");

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
            ])->findOrFail($id);


            $idVisitasSeleccionada = $idVisitas;

            $transicionesStatusOt = TransicionStatusTicket::where('idTickets', $id)
                ->where('idVisitas', $idVisitasSeleccionada)
                ->whereNotNull('justificacion')
                ->where('justificacion', '!=', '')
                ->with('estado_ot')
                ->get();

            $equipo = DB::table('equipos')
                ->where('idTickets', $id)
                ->where('idVisitas', $idVisitasSeleccionada)
                ->first();

            // 🔹 Si encontramos el equipo, buscamos su modelo, marca y categoría
            $modelo = null;
            $marca = null;
            $categoria = null;

            if ($equipo) {
                $modelo = \App\Models\Modelo::find($equipo->idModelo);
                $marca = \App\Models\Marca::find($equipo->idMarca);
                $categoria = \App\Models\Categoria::find($equipo->idCategoria);
            }

            // 🔹 Armamos los datos del producto
            $producto = [
                'categoria' => $categoria->nombre ?? 'No especificado',
                'marca' => $marca->nombre ?? 'No especificado',
                'modelo' => $modelo->nombre ?? 'No especificado',
                'serie' => $equipo->nserie ?? 'No especificado',
                'fallaReportada' => $orden->fallaReportada ?? 'No especificado'
            ];

            $condicion = DB::table('condicionesticket')
                ->where('idTickets', $id)
                ->where('idVisitas', $idVisitasSeleccionada)
                ->first();

            $motivoCondicion = $condicion->motivo ?? null;
            $constancia = DB::table('constancia_entregas')
                ->where('idticket', $id)
                ->first();

            $constanciaFotos = DB::table('constancia_fotos')
                ->where('idconstancia', $constancia->idconstancia ?? 0)
                ->get()
                ->map(function ($foto) {
                    $mime = finfo_buffer(finfo_open(), $foto->imagen, FILEINFO_MIME_TYPE);
                    $base64 = $foto->imagen ? 'data:' . $mime . ';base64,' . base64_encode($foto->imagen) : null;
                    return [
                        'foto_base64' => $base64 ? $this->optimizeBase64Image($base64) : null,
                        'descripcion' => $foto->descripcion ?? 'Sin descripción'
                    ];
                });


            $logoGKM = $this->procesarLogoMarca(file_get_contents(public_path('assets/images/auth/logogkm2.png')));

            if ($marca) {
                $marca->logo_base64 = $marca->foto ? $this->procesarLogoMarca($marca->foto) : null;
            }
            $visitaSeleccionada = $orden->visitas->where('idVisitas', $idVisitasSeleccionada)->first();

            $visitas = collect();
            if ($visitaSeleccionada) {
                $visitas = collect([
                    [
                        'nombre' => $visitaSeleccionada->nombre ?? 'N/A',
                        'fecha_programada' => optional($visitaSeleccionada->fecha_programada)->format('d/m/Y') ?? 'N/A',
                        'hora_inicio' => optional($visitaSeleccionada->fecha_inicio)->format('H:i') ?? 'N/A',
                        'hora_final' => optional($visitaSeleccionada->fecha_final)->format('H:i') ?? 'N/A',
                        'fecha_llegada' => optional($visitaSeleccionada->fecha_llegada)->format('d/m/Y H:i') ?? 'N/A',
                        'tecnico' => ($visitaSeleccionada->tecnico->Nombre ?? 'N/A') . ' ' . ($visitaSeleccionada->tecnico->apellidoPaterno ?? ''),
                        'correo' => $visitaSeleccionada->tecnico->correo ?? 'No disponible',
                        'telefono' => $visitaSeleccionada->tecnico->telefono ?? 'No registrado',
                        'documento' => $visitaSeleccionada->tecnico->documento ?? 'No disponible',
                        'tipo_documento' => $visitaSeleccionada->tecnico->tipodocumento->nombre ?? 'Documento',
                        'vehiculo_placa' => $visitaSeleccionada->tecnico->vehiculo->numero_placa ?? 'Sin placa'
                    ]
                ]);
            }

            $firma = DB::table('firmas')->where('idTickets', $id)
                ->where('idVisitas', $idVisitasSeleccionada)
                ->first();

            $firmaCliente = $firma && $firma->firma_cliente ? $this->optimizeBase64Image('data:image/png;base64,' . base64_encode($firma->firma_cliente)) : null;
            $firmaTecnico = $visitaSeleccionada->tecnico->firma ?? null;
            $firmaTecnico = $firmaTecnico ? 'data:image/png;base64,' . base64_encode($firmaTecnico) : null;

            $imagenesAnexos = $visitaSeleccionada->anexos_visitas->map(function ($anexo) {
                return [
                    'foto_base64' => $anexo->foto ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($anexo->foto)) : null,
                    'descripcion' => $anexo->descripcion
                ];
            });

            $imagenesCondiciones = DB::table('condicionesticket')
                ->where('idTickets', $id)
                ->where('idVisitas', $idVisitasSeleccionada)
                ->whereNotNull('imagen')
                ->get()
                ->map(function ($condicion) {
                    return [
                        'foto_base64' => $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($condicion->imagen)),
                        'descripcion' => 'CONDICION: ' . ($condicion->motivo ?? 'Sin descripcion')
                    ];
                });

            $imagenesAnexos = $imagenesAnexos->merge($imagenesCondiciones);

            $imagenesFotosTickets = $visitaSeleccionada->fotostickest->map(function ($foto) {
                return [
                    'foto_base64' => $foto->foto ? $this->optimizeBase64Image('data:image/jpeg;base64,' . base64_encode($foto->foto)) : null,
                    'descripcion' => $foto->descripcion
                ];
            });

            $fechaCreacion = optional($visitaSeleccionada->fecha_inicio)->format('d/m/Y') ?? 'N/A';

            $html = view('tickets.ordenes-trabajo.smart-tv.informe.pdf.constancia_entrega', [
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
                'motivoCondicion' => $motivoCondicion,
                'constancia' => $constancia,
                'constanciaFotos' => $constanciaFotos,
                'modoVistaPrevia' => false,
                'firma' => $firma
            ])->render();

            $pdf = Browsershot::html($html)
                ->format('A4')
                ->fullPage()
                ->noSandbox()
                ->emulateMedia('screen')
                ->waitUntilNetworkIdle()
                ->showBackground()
                ->pdf();

            return response($pdf)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="ORDEN_DE_INGRESO ' . $orden->idTickets . '.pdf"');
        } catch (\Exception $e) {
            Log::error('Error generando constancia PDF: ' . $e->getMessage());
            abort(500, 'Error generando PDF');
        }
    }

    public function eliminarImagenesMasivo(Request $request)
    {
        $ticketId = $request->ticket_id;
        $visitaId = $request->visita_id;

        try {
            Log::info('Intentando eliminar imágenes', [
                'ticket_id' => $ticketId,
                'visita_id' => $visitaId,
            ]);

            $query = Fotostickest::where('idTickets', $ticketId);

            if ($visitaId === null || $visitaId === 'null') {
                $query->whereNull('idVisitas');
            } else {
                $query->where('idVisitas', $visitaId);
            }

            $deleted = $query->delete();

            Log::info('Resultado eliminación', ['deleted' => $deleted]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar imágenes', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error interno',
                'error' => $e->getMessage()
            ], 500);
        }
    }


























public function obtenerSolicitudesRepuestos($ticketId, $visitaId = null)
{
    Log::info('OrdenesTrabajoController@obtenerSolicitudesRepuestos - Inicio', [
        'ticketId' => $this->sanitizeUTF8($ticketId),
        'visitaId' => $this->sanitizeUTF8($visitaId),
        'multiple_solicitudes' => 'HABILITADO'
    ]);

    try {
        // 1️⃣ Obtener TODAS las solicitudes de este ticket/visita
        $querySolicitud = DB::table('solicitudesordenes as so')
            ->leftJoin('usuarios as u_tecnico', 'so.idTecnico', '=', 'u_tecnico.idUsuario')
            ->leftJoin('usuarios as u_destino', 'so.id_usuario_destino', '=', 'u_destino.idUsuario')
            ->where('so.idticket', $ticketId);

        // Filtro por visita si se proporciona
        if ($visitaId && $visitaId !== 'null') {
            Log::info('Aplicando filtro por visita', [
                'visitaId' => $this->sanitizeUTF8($visitaId)
            ]);
            $querySolicitud->where('so.idVisita', $visitaId);
        }

        $querySolicitud->select([
            'so.idSolicitudesOrdenes',
            'so.codigo',
            'so.estado as estado_solicitud',
            'so.fechaCreacion',
            'so.fechaEntrega',
            'so.observaciones',
            'so.idTecnico',
            'so.id_usuario_destino',
            'u_tecnico.Nombre as tecnico_nombre',
            'u_tecnico.apellidoPaterno as tecnico_apellido',
            'u_destino.Nombre as destino_nombre',
        ]);

        $solicitudes = $querySolicitud->get();

        // Sanitizar datos de las solicitudes
        $solicitudes = $solicitudes->map(function ($solicitud) {
            return (object) [
                'idSolicitudesOrdenes' => $solicitud->idSolicitudesOrdenes,
                'codigo' => $this->sanitizeUTF8($solicitud->codigo),
                'estado_solicitud' => $this->sanitizeUTF8($solicitud->estado_solicitud),
                'fechaCreacion' => $solicitud->fechaCreacion,
                'fechaEntrega' => $solicitud->fechaEntrega,
                'observaciones' => $this->sanitizeUTF8($solicitud->observaciones),
                'idTecnico' => $solicitud->idTecnico,
                'id_usuario_destino' => $solicitud->id_usuario_destino,
                'tecnico_nombre' => $this->sanitizeUTF8($solicitud->tecnico_nombre),
                'tecnico_apellido' => $this->sanitizeUTF8($solicitud->tecnico_apellido),
                'destino_nombre' => $this->sanitizeUTF8($solicitud->destino_nombre),
            ];
        });

        Log::info('Solicitudes encontradas', [
            'total_solicitudes' => $solicitudes->count(),
            'ids_solicitudes' => $solicitudes->pluck('idSolicitudesOrdenes')->toArray(),
            'codigos' => $solicitudes->pluck('codigo')->toArray()
        ]);

        if ($solicitudes->isEmpty()) {
            Log::info('No se encontraron solicitudes de repuestos para este ticket/visita');
            return response()->json([
                'success' => true,
                'solicitudes' => [],
                'tiene_entregas' => false
            ], 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        }

        $solicitudesData = [];
        $totalSolicitudesConEntregas = 0;

        // 2️⃣ Procesar CADA solicitud
        foreach ($solicitudes as $solicitud) {
            Log::info('Procesando solicitud individual', [
                'solicitud_id' => $solicitud->idSolicitudesOrdenes,
                'codigo' => $solicitud->codigo
            ]);

            // Verificar SI EXISTE en repuestos_entregas para ESTA solicitud
            $tieneEntregas = DB::table('repuestos_entregas')
                ->where('solicitud_id', $solicitud->idSolicitudesOrdenes)
                ->exists();

            // Usar texto simple en lugar de caracteres especiales para logs
            $tieneEntregasTexto = $tieneEntregas ? 'Si' : 'No';
            
            Log::info('Verificación de entregas para solicitud', [
                'solicitud_id' => $solicitud->idSolicitudesOrdenes,
                'tiene_entregas' => $tieneEntregasTexto
            ]);

            // Si NO tiene entregas, NO incluir esta solicitud
            if (!$tieneEntregas) {
                Log::info('La solicitud NO tiene entregas registradas, se omite', [
                    'solicitud_id' => $solicitud->idSolicitudesOrdenes
                ]);
                continue;
            }

            $totalSolicitudesConEntregas++;

            // 3️⃣ Obtener todas las entregas de ESTA solicitud específica
            $entregas = DB::table('repuestos_entregas as re')
                ->leftJoin('usuarios as u_entrego', 're.usuario_entrego_id', '=', 'u_entrego.idUsuario')
                ->leftJoin('usuarios as u_preparo', 're.usuario_preparo_id', '=', 'u_preparo.idUsuario')
                ->leftJoin('usuarios as u_destino_re', 're.usuario_destino_id', '=', 'u_destino_re.idUsuario')
                ->leftJoin('articulos as a', 're.articulo_id', '=', 'a.idArticulos')
                ->where('re.solicitud_id', $solicitud->idSolicitudesOrdenes)
                ->select([
                    're.id as entrega_id',
                    're.solicitud_id',
                    're.articulo_id',
                    're.cantidad as cantidad_entregada',
                    're.estado as estado_entrega',
                    're.tipo_entrega',
                    're.ubicacion_utilizada',
                    're.ubicacion_id',
                    're.numero_ticket',
                    're.fecha_entrega',
                    're.fecha_preparacion',
                    're.usuario_entrego_id',
                    're.usuario_preparo_id',
                    're.usuario_destino_id',
                    're.observaciones as observaciones_entrega',
                    're.observaciones_entrega as obs_entrega',
                    're.firma_confirma',
                    're.firmaReceptor',
                    're.firmaEmisor',
                    're.fotoRetorno',
                    're.obsEntrega',
                    're.foto_entrega',
                    're.tipo_archivo_foto',
                    
                    'u_entrego.Nombre as entrego_nombre',
                    'u_entrego.apellidoPaterno as entrego_apellido',
                    'u_preparo.Nombre as preparo_nombre',
                    'u_preparo.apellidoPaterno as preparo_apellido',
                    'u_destino_re.Nombre as destino_re_nombre',
                    'u_destino_re.apellidoPaterno as destino_re_apellido',
                    
                    'a.codigo_repuesto as codigo_articulo',
                    'a.codigo_barras as codigo_barras_articulo',
                    'a.nombre as nombre_articulo',
                    'a.sku as sku_articulo',
                    'a.stock_total as stock_articulo'
                ])
                ->orderBy('re.fecha_entrega', 'desc')
                ->get();

            // Sanitizar datos de entregas
            $entregas = $entregas->map(function ($entrega) {
                return (object) [
                    'entrega_id' => $entrega->entrega_id,
                    'solicitud_id' => $entrega->solicitud_id,
                    'articulo_id' => $entrega->articulo_id,
                    'cantidad_entregada' => $entrega->cantidad_entregada,
                    'estado_entrega' => $this->sanitizeUTF8($entrega->estado_entrega),
                    'tipo_entrega' => $this->sanitizeUTF8($entrega->tipo_entrega),
                    'ubicacion_utilizada' => $this->sanitizeUTF8($entrega->ubicacion_utilizada),
                    'ubicacion_id' => $entrega->ubicacion_id,
                    'numero_ticket' => $this->sanitizeUTF8($entrega->numero_ticket),
                    'fecha_entrega' => $entrega->fecha_entrega,
                    'fecha_preparacion' => $entrega->fecha_preparacion,
                    'usuario_entrego_id' => $entrega->usuario_entrego_id,
                    'usuario_preparo_id' => $entrega->usuario_preparo_id,
                    'usuario_destino_id' => $entrega->usuario_destino_id,
                    'observaciones_entrega' => $this->sanitizeUTF8($entrega->observaciones_entrega),
                    'obs_entrega' => $this->sanitizeUTF8($entrega->obs_entrega),
                    'firma_confirma' => $entrega->firma_confirma,
                    'firmaReceptor' => $entrega->firmaReceptor,
                    'firmaEmisor' => $entrega->firmaEmisor,
                    'fotoRetorno' => $entrega->fotoRetorno,
                    'obsEntrega' => $this->sanitizeUTF8($entrega->obsEntrega),
                    'foto_entrega' => $entrega->foto_entrega,
                    'tipo_archivo_foto' => $this->sanitizeUTF8($entrega->tipo_archivo_foto),
                    
                    'entrego_nombre' => $this->sanitizeUTF8($entrega->entrego_nombre),
                    'entrego_apellido' => $this->sanitizeUTF8($entrega->entrego_apellido),
                    'preparo_nombre' => $this->sanitizeUTF8($entrega->preparo_nombre),
                    'preparo_apellido' => $this->sanitizeUTF8($entrega->preparo_apellido),
                    'destino_re_nombre' => $this->sanitizeUTF8($entrega->destino_re_nombre),
                    'destino_re_apellido' => $this->sanitizeUTF8($entrega->destino_re_apellido),
                    
                    'codigo_articulo' => $this->sanitizeUTF8($entrega->codigo_articulo),
                    'codigo_barras_articulo' => $this->sanitizeUTF8($entrega->codigo_barras_articulo),
                    'nombre_articulo' => $this->sanitizeUTF8($entrega->nombre_articulo),
                    'sku_articulo' => $this->sanitizeUTF8($entrega->sku_articulo),
                    'stock_articulo' => $entrega->stock_articulo
                ];
            });

            Log::info('Entregas encontradas para solicitud', [
                'solicitud_id' => $solicitud->idSolicitudesOrdenes,
                'total_entregas' => $entregas->count(),
                'entregas_ids' => $entregas->pluck('entrega_id')->toArray()
            ]);

            // 4️⃣ Preparar datos de ESTA solicitud
            $ultimaEntrega = $entregas->first();
            
            $solicitudData = [
                'idSolicitudesOrdenes' => $solicitud->idSolicitudesOrdenes,
                'codigo' => $solicitud->codigo,
                'estado_solicitud' => $solicitud->estado_solicitud,
                'estado_entrega' => $ultimaEntrega->estado_entrega ?? 'pendiente',
                'fechaCreacion' => $solicitud->fechaCreacion,
                'fechaCreacion_format' => $solicitud->fechaCreacion 
                    ? Carbon::parse($solicitud->fechaCreacion)->format('d/m/Y H:i')
                    : null,
                'fechaEntrega' => $solicitud->fechaEntrega,
                'fechaEntrega_format' => $solicitud->fechaEntrega
                    ? Carbon::parse($solicitud->fechaEntrega)->format('d/m/Y H:i')
                    : null,
                'fecha_entrega' => $ultimaEntrega->fecha_entrega ?? null,
                'fecha_entrega_format' => $ultimaEntrega->fecha_entrega
                    ? Carbon::parse($ultimaEntrega->fecha_entrega)->format('d/m/Y H:i')
                    : null,
                'observaciones' => $solicitud->observaciones,
                'tecnico_completo' => trim(($solicitud->tecnico_nombre ?? '') . ' ' . ($solicitud->tecnico_apellido ?? '')),
                'destino_completo' => $solicitud->destino_nombre ?? 'No asignado',
                'entrego_completo' => trim(($ultimaEntrega->entrego_nombre ?? '') . ' ' . ($ultimaEntrega->entrego_apellido ?? '')) ?: 'No registrado',
                'preparo_completo' => trim(($ultimaEntrega->preparo_nombre ?? '') . ' ' . ($ultimaEntrega->preparo_apellido ?? '')) ?: 'No registrado',
                'tipo_entrega' => $ultimaEntrega->tipo_entrega ?? null,
                'ubicacion_utilizada' => $ultimaEntrega->ubicacion_utilizada ?? null,
                'numero_ticket' => $ultimaEntrega->numero_ticket ?? null,
                'ubicacion_id' => $ultimaEntrega->ubicacion_id ?? null,
                'observaciones_entrega' => $ultimaEntrega->observaciones_entrega ?? null,
                'entregas' => [],
                'articulos' => []
            ];

            // 5️⃣ Preparar el array de entregas y artículos únicos
            $articulosUnicos = [];
            foreach ($entregas as $entrega) {
                // OBTENER EL orden_articulo_id para esta entrega
                $ordenArticulo = DB::table('ordenesarticulos')
                    ->where('idSolicitudesOrdenes', $solicitud->idSolicitudesOrdenes)
                    ->where('idArticulos', $entrega->articulo_id)
                    ->first();
                
                // Si no existe, crearlo automáticamente
                if (!$ordenArticulo) {
                    Log::info('Creando registro en ordenesarticulos para entrega', [
                        'solicitud_id' => $solicitud->idSolicitudesOrdenes,
                        'articulo_id' => $entrega->articulo_id,
                        'ticket_id' => $ticketId
                    ]);
                    
                    $ordenArticuloId = DB::table('ordenesarticulos')->insertGetId([
                        'idSolicitudesOrdenes' => $solicitud->idSolicitudesOrdenes,
                        'idArticulos' => $entrega->articulo_id,
                        'idticket' => $ticketId,
                        'cantidad' => $entrega->cantidad_entregada ?? 1,
                        'estado' => 0,
                        'observacion' => $this->sanitizeUTF8('Creado automáticamente desde entrega'),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                    
                    $ordenArticulo = (object) ['idOrdenesArticulos' => $ordenArticuloId];
                }
                
                if (!$ordenArticulo) {
                    Log::warning('No se pudo obtener/crear orden_articulo_id para', [
                        'solicitud_id' => $solicitud->idSolicitudesOrdenes,
                        'articulo_id' => $entrega->articulo_id
                    ]);
                    continue;
                }

                // Agregar al array de entregas
                $solicitudData['entregas'][] = [
                    'entrega_id' => $entrega->entrega_id,
                    'fecha_entrega' => $entrega->fecha_entrega,
                    'fecha_entrega_format' => $entrega->fecha_entrega
                        ? Carbon::parse($entrega->fecha_entrega)->format('d/m/Y H:i')
                        : null,
                    'estado_entrega' => $entrega->estado_entrega,
                    'tipo_entrega' => $entrega->tipo_entrega,
                    'ubicacion_utilizada' => $entrega->ubicacion_utilizada,
                    'observaciones_entrega' => $entrega->observaciones_entrega,
                    'cantidad_entregada' => $entrega->cantidad_entregada,
                    'obsEntrega' => $entrega->obsEntrega,
                    'fotoRetorno' => $entrega->fotoRetorno ? 'Si' : 'No', // Cambiar a texto simple
                    'firma_confirma' => $entrega->firma_confirma ? 'Si' : 'No' // Cambiar a texto simple
                ];

                // Agrupar artículos únicos
                if (!isset($articulosUnicos[$entrega->articulo_id])) {
                    $articulosUnicos[$entrega->articulo_id] = [
                        'articulo_id' => $entrega->articulo_id,
                        'orden_articulo_id' => $ordenArticulo->idOrdenesArticulos,
                        'entrega_id' => $entrega->entrega_id,
                        'cantidad_entregada' => $entrega->cantidad_entregada,
                        'estado_entrega' => $entrega->estado_entrega,
                        'fecha_entrega' => $entrega->fecha_entrega,
                        'codigo_articulo' => $entrega->codigo_articulo,
                        'codigo_barras_articulo' => $entrega->codigo_barras_articulo,
                        'nombre_articulo' => $entrega->nombre_articulo,
                        'sku_articulo' => $entrega->sku_articulo,
                        'stock_articulo' => $entrega->stock_articulo,
                        'obsEntrega' => $entrega->obsEntrega,
                        'fotoRetorno' => $entrega->fotoRetorno
                    ];
                }
            }

            // 6️⃣ Preparar artículos para ESTA solicitud
            foreach ($articulosUnicos as $articuloId => $articuloData) {
                // Determinar estado de uso con texto simple
                $estadoUso = 'Pendiente';
                $estadoColor = 'secondary';
                
                if ($articuloData['obsEntrega']) {
                    $obs = strtolower($articuloData['obsEntrega']);
                    if (strpos($obs, 'usado') !== false || strpos($obs, 'utilizado') !== false) {
                        $estadoUso = 'Usado';
                        $estadoColor = 'success';
                    } elseif (strpos($obs, 'no usado') !== false || strpos($obs, 'sin usar') !== false) {
                        $estadoUso = 'No Usado';
                        $estadoColor = 'warning';
                    } elseif (strpos($obs, 'devuelto') !== false) {
                        $estadoUso = 'Devuelto';
                        $estadoColor = 'danger';
                    }
                } elseif ($articuloData['estado_entrega'] === 'usado') {
                    $estadoUso = 'Usado';
                    $estadoColor = 'success';
                } elseif ($articuloData['estado_entrega'] === 'pendiente_por_retorno') {
                    $estadoUso = 'No Usado';
                    $estadoColor = 'warning';
                } elseif ($articuloData['estado_entrega'] === 'devuelto') {
                    $estadoUso = 'Devuelto';
                    $estadoColor = 'danger';
                }
                
                $solicitudData['articulos'][] = [
                    'articulo_id' => $articuloData['articulo_id'],
                    'orden_articulo_id' => $articuloData['orden_articulo_id'],
                    'entrega_id' => $articuloData['entrega_id'],
                    'cantidad_entregada' => $articuloData['cantidad_entregada'],
                    'estado_entrega' => $articuloData['estado_entrega'],
                    'estado_uso' => $estadoUso,
                    'estado_uso_color' => $estadoColor,
                    'fecha_entrega' => $articuloData['fecha_entrega'],
                    'fecha_entrega_format' => $articuloData['fecha_entrega']
                        ? Carbon::parse($articuloData['fecha_entrega'])->format('d/m/Y H:i')
                        : null,
                    'codigo_articulo' => $articuloData['codigo_articulo'],
                    'codigo_barras_articulo' => $articuloData['codigo_barras_articulo'],
                    'nombre_articulo' => $articuloData['nombre_articulo'],
                    'sku_articulo' => $articuloData['sku_articulo'],
                    'stock_articulo' => $articuloData['stock_articulo'],
                    'obsEntrega' => $articuloData['obsEntrega'],
                    'tiene_foto_retorno' => $articuloData['fotoRetorno'] ? true : false
                ];
            }

            // Agregar esta solicitud al array final
            $solicitudesData[] = $solicitudData;

            Log::info('Solicitud procesada exitosamente', [
                'solicitud_id' => $solicitud->idSolicitudesOrdenes,
                'total_articulos' => count($solicitudData['articulos']),
                'total_entregas' => count($solicitudData['entregas'])
            ]);
        }

        Log::info('Respuesta final preparada', [
            'success' => true,
            'total_solicitudes_encontradas' => $solicitudes->count(),
            'total_solicitudes_con_entregas' => $totalSolicitudesConEntregas,
            'total_solicitudes_devueltas' => count($solicitudesData),
            'solicitudes_devueltas_ids' => collect($solicitudesData)->pluck('idSolicitudesOrdenes')->toArray()
        ]);

        // Sanitizar los datos finales antes de retornar
        $solicitudesData = $this->ensureJsonSafe($solicitudesData);

        $response = [
            'success' => true,
            'solicitudes' => $solicitudesData,
            'tiene_entregas' => count($solicitudesData) > 0,
            'metadata' => [
                'total_solicitudes' => count($solicitudesData),
                'filtro_ticket' => $this->sanitizeUTF8($ticketId),
                'filtro_visita' => $this->sanitizeUTF8($visitaId)
            ]
        ];

        // Sanitizar respuesta completa
        $response = $this->ensureJsonSafe($response);

        return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);

    } catch (\Exception $e) {
        Log::error('Error en obtenerSolicitudesRepuestos', [
            'message' => $this->sanitizeUTF8($e->getMessage()),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $this->sanitizeUTF8($e->getTraceAsString())
        ]);

        return response()->json([
            'success' => false,
            'message' => $this->sanitizeUTF8('Error al obtener solicitudes de repuestos')
        ], 500, [], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    }
}

/**
 * Sanitiza una cadena para asegurar que solo tenga caracteres UTF-8 válidos
 */
private function sanitizeUTF8($string)
{
    if (is_null($string) || is_numeric($string) || is_bool($string)) {
        return $string;
    }

    if (!is_string($string)) {
        $string = strval($string);
    }

    // Verificar si la cadena está vacía
    if (empty($string)) {
        return $string;
    }

    // Intentar detectar y convertir a UTF-8
    $detectedEncoding = mb_detect_encoding($string, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
    
    if ($detectedEncoding && $detectedEncoding !== 'UTF-8') {
        $string = mb_convert_encoding($string, 'UTF-8', $detectedEncoding);
    } elseif (!$detectedEncoding) {
        // Si no se puede detectar, intentar conversión forzada
        $string = mb_convert_encoding($string, 'UTF-8', 'auto');
    }

    // Eliminar caracteres de control excepto tab, newline, carriage return
    $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $string);
    
    // Eliminar caracteres UTF-8 no válidos
    $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
    
    return $string;
}

/**
 * Asegura que todos los valores en un array sean seguros para JSON
 */
private function ensureJsonSafe($data)
{
    if (is_array($data) || is_object($data)) {
        array_walk_recursive($data, function (&$value, $key) {
            if (is_string($value)) {
                $value = $this->sanitizeUTF8($value);
            }
        });
    } elseif (is_string($data)) {
        $data = $this->sanitizeUTF8($data);
    }
    
    return $data;
}

public function marcarComoUsado(Request $request)
{
    try {
        DB::beginTransaction();
        
        $idUsuario = auth()->id();
        $items = $request->input('items', []);
        
        if (empty($items)) {
            return response()->json([
                'success' => false,
                'message' => 'No hay items para procesar'
            ], 400);
        }
        
        $resultados = [
            'procesados' => [],
            'errores' => [],
            'solicitudes_finalizadas' => []
        ];
        
        // Agrupar por solicitud para optimizar consultas
        $solicitudesIds = [];
        
        foreach ($items as $item) {
            try {
                $repuestoEntregaId = $item['repuesto_entrega_id'] ?? null;
                $estado = $item['estado'] ?? null; // SOLO 'usado' o 'devuelto'
                $observacion = $item['observacion'] ?? null;
                $cantidad = $item['cantidad'] ?? null;
                $foto_usado = $request->hasFile('foto_usado') ? $request->file('foto_usado') : null;
                $foto_devuelto = $request->hasFile('foto_devuelto') ? $request->file('foto_devuelto') : null;
                
                Log::info('Procesando item:', [
                    'repuesto_entrega_id' => $repuestoEntregaId,
                    'estado' => $estado,
                    'observacion' => $observacion,
                    'cantidad' => $cantidad
                ]);
                
                if (!$repuestoEntregaId || !$estado) {
                    $resultados['errores'][] = [
                        'repuesto_entrega_id' => $repuestoEntregaId,
                        'error' => 'Datos incompletos: falta repuesto_entrega_id o estado'
                    ];
                    continue;
                }
                
               // En la consulta de $repuestoEntrega, corregir el JOIN con tickets:
$repuestoEntrega = DB::table('repuestos_entregas as re')
    ->select(
        're.id',
        're.solicitud_id',
        're.articulo_id',
        're.cantidad',
        're.ubicacion_utilizada',
        're.estado as estado_actual',
        're.fecha_entrega',
        're.usuario_entrego_id',
        're.fotoRetorno',
        're.obsEntrega',
        're.observaciones_entrega',
        're.firma_confirma',
        're.firmaReceptor',
        'so.codigo',
        'so.estado as estado_solicitud',
        'so.idusuario',
        'so.id_usuario_destino',
        'so.tipoorden',
        'so.idticket',
        'a.stock_total',
        'a.precio_compra',
        'a.nombre as articulo_nombre',
        't.idClienteGeneral',
        't.numero_ticket', // ← AGREGAR ESTE CAMPO
        'oa.idordenesarticulos',
        'oa.estado as estado_orden_articulo',
        'oa.fechaUsado',
        'oa.fechaSinUsar',
        'oa.observacion as observacion_orden',
        'oa.foto_articulo_usado',
        'oa.foto_articulo_no_usado'
    )
    ->join('solicitudesordenes as so', 're.solicitud_id', '=', 'so.idsolicitudesordenes')
    ->join('articulos as a', 're.articulo_id', '=', 'a.idArticulos')
    ->leftJoin('ordenesarticulos as oa', function($join) {
        $join->on('oa.idsolicitudesordenes', '=', 're.solicitud_id')
             ->on('oa.idarticulos', '=', 're.articulo_id');
    })
    ->leftJoin('tickets as t', 'so.idticket', '=', 't.idTickets') // ← CORREGIR ESTO
    ->where('re.id', $repuestoEntregaId)
    ->first();
                
                if (!$repuestoEntrega) {
                    $resultados['errores'][] = [
                        'repuesto_entrega_id' => $repuestoEntregaId,
                        'error' => 'Repuesto entrega no encontrado'
                    ];
                    continue;
                }
                
                // Verificar que no esté ya procesado
                $estadosFinales = ['usado', 'devuelto'];
                if (in_array($repuestoEntrega->estado_actual, $estadosFinales)) {
                    $resultados['errores'][] = [
                        'repuesto_entrega_id' => $repuestoEntregaId,
                        'error' => "Ya fue procesado anteriormente (estado: {$repuestoEntrega->estado_actual})"
                    ];
                    continue;
                }
                
                // Validar estado válido
                $estadosValidos = ['usado', 'devuelto'];
                if (!in_array($estado, $estadosValidos)) {
                    $resultados['errores'][] = [
                        'repuesto_entrega_id' => $repuestoEntregaId,
                        'error' => "Estado inválido: {$estado}. Válidos: " . implode(', ', $estadosValidos)
                    ];
                    continue;
                }
                
                // Verificar que haya ubicación si es 'usado'
                if ($estado === 'usado' && empty($repuestoEntrega->ubicacion_utilizada)) {
                    $resultados['errores'][] = [
                        'repuesto_entrega_id' => $repuestoEntregaId,
                        'error' => 'No hay ubicación registrada para este repuesto entrega'
                    ];
                    continue;
                }
                
                // Agregar a lista de solicitudes para verificación posterior
                if (!in_array($repuestoEntrega->solicitud_id, $solicitudesIds)) {
                    $solicitudesIds[] = $repuestoEntrega->solicitud_id;
                }
                
                // Preparar observación final
                $observacionFinal = '';
                if ($estado === 'usado') {
                    $observacionFinal = 'Marcado como USADO' . ($observacion ? ' - ' . $observacion : '');
                } else {
                    $observacionFinal = 'Marcado como NO USADO - Devuelto' . ($observacion ? ' - ' . $observacion : '');
                }
                
                // Manejar fotos
                $fotoUsadoPath = null;
                $fotoDevueltoPath = null;
                
                if ($estado === 'usado' && $foto_usado) {
                    $fotoUsadoPath = $this->guardarFotousado($foto_usado, 'usado_' . $repuestoEntregaId);
                } elseif ($estado === 'devuelto' && $foto_devuelto) {
                    $fotoDevueltoPath = $this->guardarFotousado($foto_devuelto, 'devuelto_' . $repuestoEntregaId);
                }
                
                // Datos para actualizar en repuestos_entregas
                $updateDataRepuesto = [
                    'estado' => $estado,
                    'obsEntrega' => $observacionFinal,
                    'updated_at' => now()
                ];
                
                // Si es devuelto, agregar firmas
                if ($estado === 'devuelto') {
                    $updateDataRepuesto['firma_confirma'] = 1;
                    $updateDataRepuesto['firmaReceptor'] = 1;
                }
                
                // Si hay foto, actualizar fotoRetorno
                if ($fotoUsadoPath || $fotoDevueltoPath) {
                    $fotoPath = $fotoUsadoPath ? $fotoUsadoPath : $fotoDevueltoPath;
                    $updateDataRepuesto['fotoRetorno'] = $fotoPath;
                }
                
                // 2. Actualizar estado del repuesto entrega
                DB::table('repuestos_entregas')
                    ->where('id', $repuestoEntregaId)
                    ->update($updateDataRepuesto);
                
                // 3. Actualizar o crear registro en ordenesarticulos
                $updateDataOrdenArticulo = [
                    'estado' => 1, // Marcado como procesado
                    'observacion' => $observacionFinal,
                    'updated_at' => now()
                ];
                
                if ($estado === 'usado') {
                    $updateDataOrdenArticulo['fechaUsado'] = now();
                    if ($fotoUsadoPath) {
                        $updateDataOrdenArticulo['foto_articulo_usado'] = $fotoUsadoPath;
                    }
                } else {
                    // Para devuelto
                    $updateDataOrdenArticulo['fechaSinUsar'] = now();
                    if ($fotoDevueltoPath) {
                        $updateDataOrdenArticulo['foto_articulo_no_usado'] = $fotoDevueltoPath;
                    }
                }
                
                // Verificar si existe el registro en ordenesarticulos
                if ($repuestoEntrega->idordenesarticulos) {
                    DB::table('ordenesarticulos')
                        ->where('idordenesarticulos', $repuestoEntrega->idordenesarticulos)
                        ->update($updateDataOrdenArticulo);
                } else {
                    // Crear nuevo registro
                    $updateDataOrdenArticulo['idSolicitudesOrdenes'] = $repuestoEntrega->solicitud_id;
                    $updateDataOrdenArticulo['idArticulos'] = $repuestoEntrega->articulo_id;
                    $updateDataOrdenArticulo['idticket'] = $repuestoEntrega->idticket ?? 0;
                    $updateDataOrdenArticulo['cantidad'] = $cantidad ?? $repuestoEntrega->cantidad;
                    $updateDataOrdenArticulo['created_at'] = now();
                    
                    DB::table('ordenesarticulos')->insert($updateDataOrdenArticulo);
                }
                
                // SOLO para estado 'usado' procesar el descuento de inventario
                if ($estado === 'usado') {
                    $cantidadUsada = $cantidad ?? $repuestoEntrega->cantidad;
                    
                    // Obtener número de ticket para pasarlo al método
                    $numeroTicketParaInventario = $repuestoEntrega->numero_ticket ?? null;
                    
                    $this->procesarSalidaInventario(
                        $repuestoEntrega->solicitud_id,
                        $repuestoEntrega->articulo_id,
                        $cantidadUsada,
                        $repuestoEntrega->ubicacion_utilizada,
                        $repuestoEntrega->codigo, // Código de solicitud
                        $repuestoEntrega->idClienteGeneral ?? 1,
                        $repuestoEntrega->precio_compra,
                        $numeroTicketParaInventario, // Número de ticket
                        $repuestoEntregaId
                    );
                }
                // Si es 'devuelto', NO se procesa inventario, solo se marcan las firmas y estado
                
                $resultados['procesados'][] = [
                    'repuesto_entrega_id' => $repuestoEntregaId,
                    'estado' => $estado,
                    'solicitud_id' => $repuestoEntrega->solicitud_id,
                    'articulo_id' => $repuestoEntrega->articulo_id,
                    'articulo_nombre' => $repuestoEntrega->articulo_nombre,
                    'cantidad' => $cantidad ?? $repuestoEntrega->cantidad,
                    'ubicacion' => $repuestoEntrega->ubicacion_utilizada,
                    'observacion' => $observacion,
                    'tiene_foto_usado' => $fotoUsadoPath ? true : false,
                    'tiene_foto_devuelto' => $fotoDevueltoPath ? true : false,
                    'codigo_solicitud' => $repuestoEntrega->codigo
                ];
                
                Log::info('Repuesto procesado exitosamente', [
                    'repuesto_entrega_id' => $repuestoEntregaId,
                    'estado' => $estado,
                    'articulo' => $repuestoEntrega->articulo_nombre
                ]);
                
            } catch (\Exception $e) {
                Log::error('Error procesando repuesto entrega ID ' . ($repuestoEntregaId ?? 'desconocido') . ': ' . $e->getMessage());
                $resultados['errores'][] = [
                    'repuesto_entrega_id' => $repuestoEntregaId,
                    'error' => $e->getMessage()
                ];
                continue;
            }
        }
        
        // 4. Verificar si las solicitudes deben finalizarse
        foreach ($solicitudesIds as $solicitudId) {
            if ($this->verificarFinalizacionSolicitud($solicitudId)) {
                $resultados['solicitudes_finalizadas'][] = $solicitudId;
            }
        }
        
        DB::commit();
        
        // Estadísticas
        $totalProcesados = count($resultados['procesados']);
        $totalErrores = count($resultados['errores']);
        $totalFinalizadas = count($resultados['solicitudes_finalizadas']);
        
        $message = "Procesamiento completado: {$totalProcesados} items procesados, {$totalErrores} errores";
        if ($totalFinalizadas > 0) {
            $message .= ", {$totalFinalizadas} solicitudes finalizadas";
        }
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $resultados
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error general en marcarComoUsado: ' . $e->getMessage());
        Log::error('Trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al procesar: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Guarda la foto de evidencia
 */
/**
 * Guarda la foto de evidencia (versión simplificada)
 */
private function guardarFotousado($foto, $nombreBase)
{
    try {
        // Crear nombre único para el archivo
        $nombreArchivo = $nombreBase . '_' . time() . '.' . $foto->getClientOriginalExtension();
        
        // Crear directorio si no existe
        $directorio = storage_path('app/public/repuestos_evidencias');
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }
        
        // Guardar la imagen
        $path = $foto->storeAs('public/repuestos_evidencias', $nombreArchivo);
        
        return 'repuestos_evidencias/' . $nombreArchivo;
        
    } catch (\Exception $e) {
        Log::error('Error al guardar foto: ' . $e->getMessage());
        return null;
    }
}
/**
 * Procesa la salida de inventario (versión actualizada con datos completos)
 */
private function procesarSalidaInventario(
    $solicitudId, 
    $articuloId, 
    $cantidad, 
    $ubicacionCodigo,
    $codigoSolicitud,
    $clienteGeneralId,
    $precioCompra,
    $numeroTicket = null,
    $repuestoEntregaId = null
) {
    Log::info("Procesando salida inventario - Solicitud: {$codigoSolicitud}, Artículo: {$articuloId}, Cantidad: {$cantidad}");
    
    // 1. Buscar la ubicación por código con más información
    $ubicacion = DB::table('rack_ubicaciones as ru')
        ->select(
            'ru.idRackUbicacion',
            'ru.codigo',
            'r.idRack as rack_id',
            'r.nombre as rack_nombre'
        )
        ->leftJoin('racks as r', 'ru.rack_id', '=', 'r.idRack')
        ->where('ru.codigo', $ubicacionCodigo)
        ->first();
    
    if (!$ubicacion) {
        throw new \Exception("Ubicación no encontrada: {$ubicacionCodigo}");
    }
    
    // 2. Verificar stock en rack_ubicacion_articulos
    $stockUbicacion = DB::table('rack_ubicacion_articulos as rua')
        ->select(
            'rua.idRackUbicacionArticulo',
            'rua.cantidad',
            'rua.articulo_id',
            'rua.cliente_general_id',
            'rua.created_at'
        )
        ->where('rua.rack_ubicacion_id', $ubicacion->idRackUbicacion)
        ->where('rua.articulo_id', $articuloId)
        ->where('rua.cliente_general_id', $clienteGeneralId)
        ->first();
    
    if (!$stockUbicacion) {
        // Intentar buscar sin cliente_general_id (fallback)
        $stockUbicacion = DB::table('rack_ubicacion_articulos as rua')
            ->where('rack_ubicacion_id', $ubicacion->idRackUbicacion)
            ->where('articulo_id', $articuloId)
            ->first();
        
        if (!$stockUbicacion) {
            throw new \Exception("No hay stock en la ubicación {$ubicacionCodigo} para el artículo ID {$articuloId}");
        }
    }
    
    if ($stockUbicacion->cantidad < $cantidad) {
        throw new \Exception("Stock insuficiente en {$ubicacionCodigo}. Disponible: {$stockUbicacion->cantidad}, Requerido: {$cantidad}");
    }
    
    // 3. Descontar de rack_ubicacion_articulos
    $nuevaCantidadRU = $stockUbicacion->cantidad - $cantidad;
    DB::table('rack_ubicacion_articulos')
        ->where('idRackUbicacionArticulo', $stockUbicacion->idRackUbicacionArticulo)
        ->update([
            'cantidad' => $nuevaCantidadRU,
            'updated_at' => now()
        ]);
    
    // 4. Descontar stock total en articulos
    $articulo = DB::table('articulos')
        ->where('idArticulos', $articuloId)
        ->first(['stock_total', 'nombre']);
    
    if (!$articulo) {
        throw new \Exception("Artículo ID {$articuloId} no encontrado");
    }
    
    if ($articulo->stock_total < $cantidad) {
        throw new \Exception("Stock total insuficiente. Disponible: {$articulo->stock_total}, Requerido: {$cantidad}");
    }
    
    $nuevoStockTotal = $articulo->stock_total - $cantidad;
    DB::table('articulos')
        ->where('idArticulos', $articuloId)
        ->update([
            'stock_total' => $nuevoStockTotal,
            'updated_at' => now()
        ]);
    
    // 5. Registrar movimiento en rack_movimientos
    $observaciones = "Salida por uso - Solicitud: {$codigoSolicitud}";
    if ($numeroTicket) {
        $observaciones .= " - Ticket: {$numeroTicket}";
    }
    if ($repuestoEntregaId) {
        $observaciones .= " - RepEntID: {$repuestoEntregaId}";
    }
    
    DB::table('rack_movimientos')->insert([
        'articulo_id' => $articuloId,
        'ubicacion_origen_id' => $ubicacion->idRackUbicacion,
        'ubicacion_destino_id' => null,
        'rack_origen_id' => $ubicacion->rack_id,
        'rack_destino_id' => null,
        'cantidad' => $cantidad,
        'tipo_movimiento' => 'salida',
        'usuario_id' => auth()->id(),
        'observaciones' => $observaciones,
        'codigo_ubicacion_origen' => $ubicacionCodigo,
        'codigo_ubicacion_destino' => null,
        'nombre_rack_origen' => $ubicacion->rack_nombre,
        'nombre_rack_destino' => null,
        'custodia_id' => null,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    // 6. Obtener el número de ticket desde la tabla tickets si no se proporcionó
    if (!$numeroTicket) {
        // Buscar el ticket relacionado con esta solicitud a través de ordenesarticulos
        $ticketData = DB::table('ordenesarticulos as oa')
            ->join('tickets as t', 'oa.idticket', '=', 't.idTickets')
            ->where('oa.idSolicitudesOrdenes', $solicitudId)
            ->select('t.numero_ticket')
            ->first();
        
        if ($ticketData) {
            $numeroTicket = $ticketData->numero_ticket;
            Log::info("✅ Número de ticket obtenido desde BD: {$numeroTicket}");
        } else {
            // Si no se encuentra en ordenesarticulos, buscar directo en solicitudesordenes
            $ticketData = DB::table('solicitudesordenes as so')
                ->join('tickets as t', 'so.idticket', '=', 't.idTickets')
                ->where('so.idsolicitudesordenes', $solicitudId)
                ->select('t.numero_ticket')
                ->first();
            
            if ($ticketData) {
                $numeroTicket = $ticketData->numero_ticket;
                Log::info("✅ Número de ticket obtenido desde solicitudesordenes: {$numeroTicket}");
            } else {
                $numeroTicket = 'SIN-TICKET';
                Log::warning("⚠️ No se encontró número de ticket para la solicitud {$solicitudId}");
            }
        }
    }
    
    // 7. Registrar en inventario_ingresos_clientes - CON TODOS LOS DATOS
    DB::table('inventario_ingresos_clientes')->insert([
        'compra_id' => null,
        'articulo_id' => $articuloId,
        'tipo_ingreso' => 'salida',
        'ingreso_id' => $solicitudId,
        'cliente_general_id' => $clienteGeneralId,
        'numero_orden' => $numeroTicket, // ✅ Número de ticket
        'codigo_solicitud' => $codigoSolicitud, // ✅ Código de la solicitud
        'cas' => 'CAS AUTORIZADO GKM TECHNOLOGY', // ✅ CAS fijo
        'cantidad' => -$cantidad, // Negativo para salida
        'created_at' => now(),
        'updated_at' => now()
    ]);
    
    Log::info("✅ Registro en inventario_ingresos_clientes - N° Ticket: {$numeroTicket}, Código: {$codigoSolicitud}, CAS: CAS AUTORIZADO GKM TECHNOLOGY");
    
    // 8. Actualizar kardex - Aquí SÍ se necesita precio_compra
    $this->actualizarKardexSalida($articuloId, $clienteGeneralId, $cantidad, $precioCompra);
    
    Log::info("✅ Salida inventario procesada - Artículo: {$articuloId}, Stock restante ubicación: {$nuevaCantidadRU}, Stock total: {$nuevoStockTotal}");
}
/**
 * Verifica si una solicitud debe finalizarse (versión mejorada)
 */
private function verificarFinalizacionSolicitud($solicitudId)
{
    // Estados que se consideran "procesados"
    $estadosProcesados = ['usado', 'devuelto'];
    
    // Obtener todos los repuestos de la solicitud
    $repuestos = DB::table('repuestos_entregas')
        ->where('solicitud_id', $solicitudId)
        ->get(['id', 'estado']);
    
    if ($repuestos->isEmpty()) {
        return false;
    }
    
    $todosProcesados = true;
    foreach ($repuestos as $repuesto) {
        // Si el estado es NULL o no está en la lista de procesados
        if (!$repuesto->estado || !in_array($repuesto->estado, $estadosProcesados)) {
            $todosProcesados = false;
            break;
        }
    }
    
    if ($todosProcesados) {
        // Todos los repuestos han sido procesados, finalizar solicitud
        $fechaFinalizacion = now();
        DB::table('solicitudesordenes')
            ->where('idsolicitudesordenes', $solicitudId)
            ->update([
                'estado' => 'Finalizado',
                'fechaEntrega' => $fechaFinalizacion,
                'updated_at' => $fechaFinalizacion
            ]);
        
        Log::info("Solicitud {$solicitudId} finalizada automáticamente");
        return true;
    }
    
    return false;
}

/**
 * Método para actualizar kardex (Mismo que para repuestos)
 */
private function actualizarKardexSalida($articuloId, $clienteGeneralId, $cantidadSalida, $costoUnitario)
{
    try {
        // Obtener el mes y año actual
        $fechaActual = now();
        $mesActual = $fechaActual->format('m');
        $anioActual = $fechaActual->format('Y');

        Log::info("📅 Procesando kardex para artículo - mes: {$mesActual}, año: {$anioActual}");

        // Buscar si existe un registro de kardex para este artículo, cliente y mes actual
        $kardexMesActual = DB::table('kardex')
            ->where('idArticulo', $articuloId)
            ->where('cliente_general_id', $clienteGeneralId)
            ->whereMonth('fecha', $mesActual)
            ->whereYear('fecha', $anioActual)
            ->first();

        if ($kardexMesActual) {
            Log::info("✅ Kardex del mes actual encontrado - ID: {$kardexMesActual->id}, actualizando...");

            // ACTUALIZAR registro existente del mes
            $nuevoInventarioActual = $kardexMesActual->inventario_actual - $cantidadSalida;
            $nuevoCostoInventario = max(0, $kardexMesActual->costo_inventario - ($cantidadSalida * $costoUnitario));

            DB::table('kardex')
                ->where('id', $kardexMesActual->id)
                ->update([
                    'unidades_salida' => $kardexMesActual->unidades_salida + $cantidadSalida,
                    'costo_unitario_salida' => $costoUnitario,
                    'inventario_actual' => $nuevoInventarioActual,
                    'costo_inventario' => $nuevoCostoInventario,
                    'updated_at' => now()
                ]);

            Log::info("✅ Kardex actualizado - Salidas: " . ($kardexMesActual->unidades_salida + $cantidadSalida) .
                ", Inventario: {$nuevoInventarioActual}, Costo: {$nuevoCostoInventario}");
        } else {
            Log::info("📝 No hay kardex para este mes, creando nuevo registro...");

            // Obtener el último registro de kardex (de cualquier mes) para calcular inventario inicial
            $ultimoKardex = DB::table('kardex')
                ->where('idArticulo', $articuloId)
                ->where('cliente_general_id', $clienteGeneralId)
                ->orderBy('fecha', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            // Calcular valores iniciales para el nuevo mes
            $inventarioInicial = $ultimoKardex ? $ultimoKardex->inventario_actual : 0;
            $inventarioActual = $inventarioInicial - $cantidadSalida;

            // Calcular costo del inventario
            $costoInventarioAnterior = $ultimoKardex ? $ultimoKardex->costo_inventario : 0;
            $costoInventarioActual = max(0, $costoInventarioAnterior - ($cantidadSalida * $costoUnitario));

            Log::info("📊 Valores calculados - Inicial: {$inventarioInicial}, Actual: {$inventarioActual}, " .
                "Costo anterior: {$costoInventarioAnterior}, Costo actual: {$costoInventarioActual}");

            // CREAR nuevo registro de kardex para el nuevo mes
            DB::table('kardex')->insert([
                'fecha' => $fechaActual->format('Y-m-d'),
                'idArticulo' => $articuloId,
                'cliente_general_id' => $clienteGeneralId,
                'unidades_entrada' => 0,
                'costo_unitario_entrada' => 0,
                'unidades_salida' => $cantidadSalida,
                'costo_unitario_salida' => $costoUnitario,
                'inventario_inicial' => $inventarioInicial,
                'inventario_actual' => $inventarioActual,
                'costo_inventario' => $costoInventarioActual,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            Log::info("✅ Nuevo kardex creado para el mes - Artículo: {$articuloId}, Cliente: {$clienteGeneralId}");
        }

        Log::info("✅ Kardex procesado correctamente - Artículo: {$articuloId}, Salida: {$cantidadSalida}");
    } catch (\Exception $e) {
        Log::error('❌ Error al actualizar kardex para salida: ' . $e->getMessage());
        Log::error('File: ' . $e->getFile());
        Log::error('Line: ' . $e->getLine());
        throw $e;
    }
}



}
