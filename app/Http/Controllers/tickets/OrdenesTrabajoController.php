<?php

namespace App\Http\Controllers\tickets;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket; // Asegúrate de tener este modelo
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ClienteGeneral; // Reemplaza con el modelo correcto
use App\Models\TipoServicio; // Reemplaza con el modelo correcto
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
use App\Models\TipoDocumento; // Reemplaza con el modelo correcto
use App\Models\Visita;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketExport;
use App\Exports\HelpdeskTicketExport; // Importa el nuevo exportador
use App\Models\AnexosVisita;
use App\Models\CondicionesTicket;
use App\Models\Fotostickest;
use App\Models\SeleccionarVisita;
use App\Models\TicketFlujo;
use App\Models\TransicionStatusTicket;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; // Asegúrate de usar esta clase
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Spatie\Browsershot\Browsershot;
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
        $clientesGenerales = ClienteGeneral::all();
        $tiposServicio = TipoServicio::all();
        $usuarios = Usuario::where('idTipoUsuario', 4)->get();
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
        $tiposServicio = TipoServicio::all();
        $marcas = Marca::all();
        $modelos = Modelo::all();
        $tiposDocumento = TipoDocumento::all();




        return view("tickets.ordenes-trabajo.smart-tv.create", compact(
            'clientesGenerales',
            'clientes',
            'tiendas',
            'usuarios',
            'tiposServicio',
            'marcas',
            'modelos',
            'tiposDocumento',
            'departamentos'
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
                'idTienda' => 'required|integer|exists:tienda,idTienda',
                'direccion' => 'required|string|max:255',
                'idMarca' => 'required|integer|exists:marca,idMarca',
                'idModelo' => 'required|integer|exists:modelo,idModelo',
                'serie' => 'required|string|max:255',
                'fechaCompra' => 'required|date_format:Y-m-d',
                'fallaReportada' => 'required|string|max:255',
                'lat' => 'nullable|string|max:255',
                'lng' => 'nullable|string|max:255',
            ]);

            Log::info('Datos validados correctamente', ['validatedData' => $validatedData]);

            // Crear la nueva orden de trabajo
            $ticket = Ticket::create([
                'numero_ticket' => $validatedData['nroTicket'],
                'idClienteGeneral' => $validatedData['idClienteGeneral'],
                'idCliente' => $validatedData['idCliente'],
                'idTienda' => $validatedData['idTienda'],
                'direccion' => $validatedData['direccion'],
                'idMarca' => $validatedData['idMarca'],
                'idModelo' => $validatedData['idModelo'],
                'serie' => $validatedData['serie'],
                'fechaCompra' => $validatedData['fechaCompra'],
                'fallaReportada' => $validatedData['fallaReportada'],
                'lat' => $validatedData['lat'],
                'lng' => $validatedData['lng'],
                'idUsuario' => auth()->id(),
                'fecha_creacion' => now(),
                'idTipotickets' => 1,
            ]);

            Log::info('Orden de trabajo creada correctamente', ['ticket' => $ticket]);

            $ticketFlujoId = DB::table('ticketflujo')->insertGetId([
                'idTicket' => $ticket->idTickets, // ID del ticket recién creado
                'idEstadflujo' => 1,  // Estado inicial de flujo
                'idUsuario' => auth()->id(),  // Usuario autenticado
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

        // Obtener los estados desde la tabla estado_ots
        $estadosOTS = DB::table('estado_ots')->get();
        // Obtener el idTickets
        $ticketId = $ticket->idTickets;

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
        $encargado = Usuario::whereIn('idTipoUsuario', [4, 5])->get();
        $tecnicos_apoyo = Usuario::where('idTipoUsuario', 4)->get();
        $clientes = Cliente::all();
        $clientesGenerales = ClienteGeneral::all();
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

                // Si el idEstadflujo del ticketflujo es 3, solo mostrar los estados con idEstadflujo 4
                if ($idEstadflujo == 3) {
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 4)  // Solo obtener el estado con idEstadflujo 4
                        ->get();
                } elseif ($idEstadflujo == 1) {
                    // Si el ticket tiene un idTicketFlujo con idEstadflujo = 1, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->whereIn('idEstadflujo', [3])  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } elseif ($idEstadflujo == 9) {
                    // Si el idEstadflujo del ticketflujo es 9, solo mostrar los estados con idEstadflujo 3
                    $estadosFlujo = DB::table('estado_flujo')
                        ->where('idEstadflujo', 3)  // Solo obtener el estado con idEstadflujo 3
                        ->get();
                } else {
                    // Si no tiene idEstadflujo = 1, 3 o 9, verificar si es 6 o 7
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
            'visitaExistente'

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
        // $usuario = Auth::user();
        // $rol = $usuario->rol->nombre ?? 'Sin Rol';
        // Obtener el ticket
        $orden = Ticket::with(['marca', 'modelo', 'cliente', 'tecnico', 'tienda', 'ticketflujo', 'usuario'])->findOrFail($id);
        $ticket = Ticket::with(['marca', 'modelo', 'cliente', 'tecnico', 'tienda', 'ticketflujo.estadoFlujo', 'usuario'])->findOrFail($id);
        // Obtener los estados de OTS
        $estadosOTS = DB::table('estado_ots')->get();
        $ticketId = $ticket->idTickets;
        // Obtener la visita usando idVisitas
        // Verificar que la visita corresponda al ticket
        $visita = DB::table('visitas')
            ->where('idVisitas', $idVisitas)
            ->where('idTickets', $id) // Verificamos que el idTickets de la visita coincida con el id del ticket
            ->first();

        // Verificamos que la visita exista, si no, devolver algún mensaje de error
        if (!$visita) {
            return view("pages.error404");
        }
        // Pasamos todos los datos a la vista
        return view("tickets.ordenes-trabajo.smart-tv.firmas.firmacliente", compact(
            'ticket',
            'orden',

            'estadosOTS',
            'ticketId',
            'idVisitas', // Asegúrate de pasar idVisitas aquí
            'visita', // El objeto visita completo
            'id',
            'idVisitas' // Pasamos también el id del ticket
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
        $ordenesQuery = Ticket::with([
            'tecnico:idUsuario,Nombre',
            'usuario:idUsuario,Nombre',
            'cliente:idCliente,nombre',
            'clientegeneral:idClienteGeneral,descripcion',
            'tiposervicio:idTipoServicio,nombre',
            'estado_ot:idEstadoots,descripcion,color',
            'marca:idMarca,nombre',
            'modelo.categoria:idCategoria,nombre', // Cargar la categoría a través del modelo
            'ticketflujo.estadoflujo:idEstadflujo,descripcion,color' // Relacionamos ticketflujo con estadoflujo
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
        $marcas = Marca::all(); // O lo que sea necesario para recuperar las marcas
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
                'documento' => 'required|string|max:255|unique:cliente,documento',
                'telefono' => 'nullable|string|max:15',
                'email' => 'nullable|email|max:255',
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


    public function loadEstados($id)
    {
        // Obtener todos los flujos de estados relacionados con el ticket, incluyendo el usuario
        $ticketFlujos = TicketFlujo::with('estadoFlujo', 'usuario') // Traer la relación con 'usuario'
            ->where('idTicket', $id) // Filtrar por idTicket
            ->get(); // Obtener todos los registros

        // Devolver la respuesta con todos los estados de flujo, incluyendo el usuario
        return response()->json([
            'estadosFlujo' => $ticketFlujos,
        ]);
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

        // Crear la nueva visita
        $visita = new Visita();
        $visita->nombre = $request->nombre;
        $visita->fecha_programada = $request->fecha_visita;
        $visita->fecha_inicio_hora = $fechaInicio;  // Concatenar fecha y hora
        $visita->fecha_final_hora = $fechaFinal; // Concatenar fecha y hora
        $visita->idUsuario = $request->encargado;

        Log::info('Creando la visita', ['visita' => $visita]);

        // Asignar 0 o 1 a "necesita_apoyo"
        $visita->necesita_apoyo = $request->necesita_apoyo ?? 0;  // Si no se envió, asignar 0

        $visita->tipoServicio = 1; // O el valor correspondiente que quieras
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
        $idEstadflujo = ($encargado->idTipoUsuario == 4) ? 2 : 4;
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

        return response()->json(['success' => true, 'message' => 'Visita guardada exitosamente']);
    }









    public function obtenerVisitas($ticketId)
    {
        // Obtener todas las visitas del ticket, incluyendo el técnico y los anexos
        $visitas = Visita::with(['tecnico', 'anexos_visitas' => function ($query) {
            // Filtrar los anexos donde idTipovisita sea 2 o 3
            $query->whereIn('idTipovisita', [2, 3, 4]);
        }])->where('idTickets', $ticketId)->get();

        // Convertir las fechas a formato ISO 8601
        $visitas->each(function ($visita) {
            $visita->fecha_inicio_hora = $visita->fecha_inicio_hora->toIso8601String();
            $visita->fecha_final_hora = $visita->fecha_final_hora->toIso8601String();

            // Incluir el nombre del técnico
            $visita->nombre_tecnico = $visita->tecnico ? $visita->tecnico->Nombre : null;  // Aquí asumimos que el campo 'nombre' está en el modelo Usuario
            $visita->idTicket = $visita->idTickets;  // Este es el ID del ticket asociado a la visita
            $visita->idVisita = $visita->idVisitas;  // Este es el ID de la visita
            $visita->nombre_visita = $visita->nombre; // Este es el nombre de la visita

            // Iterar sobre los anexos y obtener los datos (solo los que tienen idTipovisita 2 o 3)
            $visita->anexos_visitas->each(function ($anexovisita) {
                // Asegurarse de obtener todos los datos de los anexos
                // Convertir la foto a base64 si existe
                if ($anexovisita->foto) {
                    $anexovisita->foto = base64_encode($anexovisita->foto);
                }
                // Si quieres incluir la descripción, lat, lng y ubicación
                $anexovisita->descripcion = $anexovisita->descripcion;
                $anexovisita->idTipovisita = $anexovisita->idTipovisita;
                $anexovisita->lat = $anexovisita->lat;
                $anexovisita->lng = $anexovisita->lng;
                $anexovisita->ubicacion = $anexovisita->ubicacion;
            });
        });

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



        // Si el servicio es igual a 1, creamos el ticketflujo y actualizamos el ticket
        if ($request->servicio == 1) {
            // Insertar en la tabla ticketflujo con idEstadflujo 14
            $ticketflujo = DB::table('ticketflujo')->insertGetId([
                'idTicket' => $request->idTickets,  // Usamos el idTickets de la solicitud
                'idEstadflujo' => 9,               // Siempre 14 como estado
                'idUsuario' => auth()->id(),        // ID del usuario autenticado
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
        // Validar la solicitud
        $request->validate([
            'imagen' => 'required|image|max:2048', // Validamos que la imagen sea válida y no mayor de 2MB
            'descripcion' => 'required|string|max:255',
            'ticket_id' => 'required|integer|exists:tickets,idTickets', // Validamos que el ID del ticket exista
        ]);

        // Obtener los datos de la solicitud
        $imagen = $request->file('imagen'); // Imagen en formato binario
        $descripcion = $request->input('descripcion');
        $ticket_id = $request->input('ticket_id');

        // Log para verificar los datos recibidos
        Log::info('Datos recibidos para guardar la imagen:');
        Log::info('Descripción: ' . $descripcion);
        Log::info('Ticket ID: ' . $ticket_id);
        Log::info('Imagen: ' . $imagen->getClientOriginalName()); // Nombre original del archivo

        // Buscar el idVisitas asociado con el ticket en la tabla seleccionarvisita
        $visita = DB::table('seleccionarvisita')
            ->where('idTickets', $ticket_id)
            ->first(); // Obtenemos la primera visita asociada con el ticket

        // Verificar si se encontró el visita_id
        if (!$visita) {
            Log::error('No se encontró una visita válida para este ticket.');
            return response()->json(['success' => false, 'message' => 'No se encontró una visita válida para este ticket.'], 400);
        }

        // Obtener el idVisitas
        $visita_id = $visita->idVisitas;

        // Log para verificar el idVisitas encontrado
        Log::info('Visita ID encontrado: ' . $visita_id);

        // Convertir la imagen a binario
        $imagen_binaria = file_get_contents($imagen->getRealPath());

        // Log para verificar si la conversión de la imagen fue exitosa
        Log::info('Tamaño de la imagen binaria: ' . strlen($imagen_binaria));

        // Crear la entrada en la base de datos
        $foto = new Fotostickest();
        $foto->idTickets = $ticket_id;
        $foto->idVisitas = $visita_id;
        $foto->foto = $imagen_binaria; // Guardamos la imagen en binario
        $foto->descripcion = $descripcion;

        try {
            $foto->save();
            // Log para verificar que la imagen se guardó correctamente
            Log::info('Imagen guardada con éxito en la base de datos.');
        } catch (\Exception $e) {
            // Log para capturar errores en el proceso de guardado
            Log::error('Error al guardar la imagen en la base de datos: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al guardar la imagen en la base de datos.'], 500);
        }

        // Devolver una respuesta exitosa
        return response()->json(['success' => true, 'message' => 'Imagen guardada correctamente.']);
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
        preg_match('#^data:image/(\w+);base64,#i', $base64String, $matches);
        $imageType = $matches[1] ?? 'jpeg'; // Si no se detecta tipo, usa JPEG

        // Decodificar la imagen base64
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64String));
        $image = imagecreatefromstring($imageData);
        if (!$image) return $base64String; // Si la imagen es inválida, devolver la original

        // Obtener dimensiones
        $width = imagesx($image);
        $height = imagesy($image);
        $newWidth = min($width, $maxWidth);
        $newHeight = ($height / $width) * $newWidth; // Mantener proporción

        // Redimensionar imagen
        $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Convertir a JPEG con calidad reducida
        ob_start();
        imagejpeg($resizedImage, null, $quality);
        $compressedImage = ob_get_clean();

        // Liberar memoria
        imagedestroy($image);
        imagedestroy($resizedImage);

        // Devolver imagen comprimida en base64
        return 'data:image/jpeg;base64,' . base64_encode($compressedImage);
    }
    public function generateInformePdfVisita($idOt, $idVisita)
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
            'fallaReportada' => $orden->fallaReportada ?? 'No especificado' // 🔹 Agregamos la falla reportada
        ];

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
                        'telefono' => ($visitaSeleccionada->tecnico->telefono ?? 'No registrado')
                    ]
                ]);
            }
        }


        // 🔹 OBTENER FIRMAS FILTRADAS POR idTickets Y idVisitas
        $firma = DB::table('firmas')->where('idTickets', $idOt)
            ->where('idVisitas', $idVisitasSeleccionada)  // Filtrar por la visita seleccionada
            ->first();

        $firmaTecnico = $firma && !empty($firma->firma_tecnico)
            ? $this->optimizeBase64Image('data:image/png;base64,' . base64_encode($firma->firma_tecnico))
            : null;

        $firmaCliente = $firma && !empty($firma->firma_cliente)
            ? $this->optimizeBase64Image('data:image/png;base64,' . base64_encode($firma->firma_cliente))
            : null;
        // 🔹 OBTENER IMÁGENES EN BASE64 (Filtrar los anexos de la visita seleccionada)
        $visitaSeleccionada = $orden->visitas->where('idVisitas', $idVisitasSeleccionada)->first();

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

        $fechaCreacion = $orden->fecha_creacion ? date('d/m/Y', strtotime($orden->fecha_creacion)) : 'N/A';

        // 🔹 Renderizar vista
        $html = View('tickets.ordenes-trabajo.smart-tv.informe.pdf.informe', [
            'orden' => $orden,
            'fechaCreacion' => $fechaCreacion,
            'producto' => $producto,
            'transicionesStatusOt' => $transicionesStatusOt,
            'visitas' => $visitas,
            'firmaTecnico' => $firmaTecnico,
            'firmaCliente' => $firmaCliente,
            'imagenesAnexos' => $imagenesAnexos,
            'imagenesFotosTickets' => $imagenesFotosTickets
        ])->render();

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
            ->header('Content-Disposition', 'inline; filename="informe_' . $idOt . '.pdf"');
    }




    public function generateInformePdf($idOt)
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
                        'telefono' => ($visitaSeleccionada->tecnico->telefono ?? 'No registrado')
                    ]
                ]);
            }
        }

        // 🔹 OBTENER FIRMAS FILTRADAS POR idTickets Y idVisitas
        $firma = DB::table('firmas')->where('idTickets', $idOt)
            ->where('idVisitas', $idVisitasSeleccionada)  // Filtrar por la visita seleccionada
            ->first();

        // Aplicar optimización de imágenes a las firmas
        $firmaTecnico = $firma && !empty($firma->firma_tecnico)
            ? $this->optimizeBase64Image('data:image/png;base64,' . base64_encode($firma->firma_tecnico))
            : null;

        $firmaCliente = $firma && !empty($firma->firma_cliente)
            ? $this->optimizeBase64Image('data:image/png;base64,' . base64_encode($firma->firma_cliente))
            : null;

        // 🔹 OBTENER IMÁGENES EN BASE64 (Filtrar los anexos de la visita seleccionada)
        $visitaSeleccionada = $orden->visitas->where('idVisitas', $idVisitasSeleccionada)->first();

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



        // 🔹 FORMATEAR FECHA DE CREACIÓN DEL TICKET
        $fechaCreacion = $orden->fecha_creacion ? date('d/m/Y', strtotime($orden->fecha_creacion)) : 'N/A';

        // 🔹 PASAR DATOS A LA VISTA
        $html = View('tickets.ordenes-trabajo.smart-tv.informe.pdf.informe', [
            'orden' => $orden,
            'fechaCreacion' => $fechaCreacion, // ✅ PASAMOS LA FECHA DE CREACIÓN A LA VISTA
            'producto' => $producto,
            'transicionesStatusOt' => $transicionesStatusOt,
            'visitas' => $visitas,
            'firmaTecnico' => $firmaTecnico,
            'firmaCliente' => $firmaCliente,
            'imagenesAnexos' => $imagenesAnexos,
            'imagenesFotosTickets' => $imagenesFotosTickets
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
            ->header('Content-Disposition', 'inline; filename="informe_' . $idOt . '.pdf"');
    }






    // Método para actualizar el estado del ticket
    public function actualizarEstado(Request $request, $idTicket)
    {
        // Validamos el estado recibido (un número entero)
        $validated = $request->validate([
            'estado' => 'required|integer',  // Aseguramos que el estado es un número entero
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

        // Verificar si la actualización fue exitosa
        if ($updated) {
            Log::info("Ticket actualizado correctamente con idTicketFlujo: " . $ticketflujo);
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
}
