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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; // Asegúrate de usar esta clase
use Illuminate\Support\Facades\Validator;

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
            // Log de depuración: mostrar los datos de la solicitud
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

            // Log de depuración: mostrar los datos validados
            Log::debug('Datos validados:', $validatedData);

            // 🔹 Guardar el ticket en una variable
            $ticket = Ticket::create([
                'numero_ticket' => $validatedData['numero_ticket'],
                'idClienteGeneral' => $validatedData['idClienteGeneral'],
                'idCliente' => $validatedData['idCliente'],
                'idTienda' => $validatedData['idTienda'],
                'idTecnico' => $validatedData['idTecnico'],
                'tipoServicio' => $validatedData['tipoServicio'],
                'idUsuario' => auth()->id(), // ID del usuario autenticado
                'idEstadoots' => 17, // Estado inicial de la orden de trabajo
                'fallaReportada' => $validatedData['fallaReportada'],
                'fecha_creacion' => now(), // Establece la fecha y hora actuales
                'idTipotickets' => 2, // Asignar tipo de ticket
            ]);

            // Log de depuración: confirmar que se creó la orden de trabajo
            Log::debug('Orden de trabajo creada correctamente.');

            // 🔹 Redirigir a la vista de edición correcta
            return redirect()->route('ordenes.helpdesk.edit', ['id' => $ticket->idTickets])
                ->with('success', 'Orden de trabajo creada correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Errores de validación:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error al crear la orden de trabajo: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al crear la orden de trabajo.');
        }
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
                'fechaCompra' => 'required|date_format:Y-m-d', // Asegúrate de usar este formato de fecha
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
                // 'idEstadflujo' => 1, // Estado inicial de la orden de trabajo
                'idUsuario' => auth()->id(), // ID del usuario autenticado
                'fecha_creacion' => now(), // Fecha de creación
                'idTipotickets' => 1, // Fecha de creación

            ]);

            Log::info('Orden de trabajo creada correctamente', ['ticket' => $ticket]);

            // Guardar el flujo de la orden de trabajo en la tabla ticketflujo
            $ticketFlujo = DB::table('ticketflujo')->insertGetId([
                'idTicket' => $ticket->idTickets, // ID del ticket recién creado
                'idEstadflujo' => 1,  // Estado inicial de flujo
                'fecha_creacion' => now(), // Fecha y hora actual de la creación
            ]);

            Log::info('Flujo de trabajo guardado correctamente', [
                'idTicket' => $ticket->idTickets,
                'idEstadflujo' => 1,
                'fecha_creacion' => now(),
                'idTicketFlujo' => $ticketFlujo
            ]);

            // Ahora actualizamos el ticket con el idTicketFlujo recién generado
            $ticket->idTicketFlujo = $ticketFlujo;  // Asignamos el idTicketFlujo al ticket
            $ticket->save();  // Guardamos el ticket con el idTicketFlujo

            Log::info('Ticket actualizado con idTicketFlujo', ['ticket' => $ticket]);

            // Redirigir a la vista de edición del ticket con el ID del ticket recién creado
            return redirect()->route('edit', ['id' => $ticket->idTickets])
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

        // Aquí estás obteniendo el ticketId
        $ticketId = $ticket->idTickets;  // Es lo que te interesa

        // Acceder al idTicketFlujo desde la relación 'ticketflujo'
        $idTicketFlujo = $orden->ticketflujo ? $orden->ticketflujo->idTicketFlujo : null;

        // Acceder al idEstadflujo desde la relación 'ticketflujo'
        $idEstadflujo = $orden->ticketflujo ? $orden->ticketflujo->idEstadflujo : null;

        // Obtener la descripción del estado de flujo desde la relación 'estadoFlujo'
        $descripcionEstadoFlujo = $orden->ticketflujo && $orden->ticketflujo->estadoFlujo ? $orden->ticketflujo->estadoFlujo->descripcion : 'Sin estado de flujo';

        // Otros datos que ya estás obteniendo
        $usuario = $orden->usuario;
        $encargado = Usuario::whereIn('idTipoUsuario', [3, 5])->get();
        $tecnicos_apoyo = Usuario::where('idTipoUsuario', 3)->get();
        $clientes = Cliente::all();
        $clientesGenerales = ClienteGeneral::all();
        $estadosFlujo = EstadoFlujo::all();
        $modelos = Modelo::all();
        $tiendas = Tienda::all();
        $marcas = Marca::all();

        // Pasamos el ticketId a la vista
        return view("tickets.ordenes-trabajo.smart-tv.edit", compact(
            'ticket',
            'orden',
            'modelos',
            'usuario',
            'estadosFlujo',
            'clientes',
            'clientesGenerales',
            'tiendas',
            'marcas',
            'encargado',
            'tecnicos_apoyo',
            'idTicketFlujo',
            'idEstadflujo',
            'descripcionEstadoFlujo',
            'ticketId'
        ));
    }


    public function editHelpdesk($id)
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

        // 🔹 Aquí se añade la variable $usuarios para solucionar el error
        $usuarios = Usuario::all(); // Obtener todos los técnicos disponibles
        $tiposServicio = TipoServicio::all(); // Obtener tipos de servicio disponibles

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

        return redirect()->route('helpdesk.edit', ['id' => $id])->with('success', 'Orden actualizada correctamente.');
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

    public function exportHelpdeskToExcel()
    {
        return Excel::download(new HelpdeskTicketExport(), 'helpdesk_tickets.xlsx');
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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_visita' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'encargado' => 'required|integer|exists:usuarios,idUsuario', // Validar que el encargado existe
            'necesita_apoyo' => 'nullable|in:0,1',  // Ahora se valida si es 0 o 1
            'tecnicos_apoyo' => 'nullable|array', // Si seleccionaron técnicos de apoyo
        ]);

        $fechaInicio = $request->fecha_visita . ' ' . $request->hora_inicio;
        $fechaFinal = $request->fecha_visita . ' ' . $request->hora_fin;

        // Verificar si el técnico ya tiene una visita en ese rango de tiempo
        $visitasConflicto = DB::table('visitas')
            ->where('idUsuario', $request->encargado)
            ->where(function ($query) use ($fechaInicio, $fechaFinal) {
                $query->whereBetween('fecha_inicio', [$fechaInicio, $fechaFinal])
                    ->orWhereBetween('fecha_final', [$fechaInicio, $fechaFinal])
                    ->orWhere(function ($query) use ($fechaInicio, $fechaFinal) {
                        $query->where('fecha_inicio', '<=', $fechaFinal)
                            ->where('fecha_final', '>=', $fechaInicio);
                    });
            })
            ->exists(); // Retorna true si existe un conflicto de horario

        if ($visitasConflicto) {
            return response()->json(['success' => false, 'message' => 'El técnico ya tiene una visita asignada en este horario.'], 400);
        }

        // Crear la nueva visita
        $visita = new Visita();
        $visita->nombre = $request->nombre;
        $visita->fecha_programada = $request->fecha_visita;
        $visita->fecha_inicio = $fechaInicio;  // Concatenar fecha y hora
        $visita->fecha_final = $fechaFinal; // Concatenar fecha y hora
        $visita->idUsuario = $request->encargado;

        // Asignar 0 o 1 a "necesita_apoyo"
        $visita->necesita_apoyo = $request->necesita_apoyo ?? 0;  // Si no se envió, asignar 0

        $visita->tipoServicio = 1; // O el valor correspondiente que quieras
        $visita->idTickets = $request->idTickets; // Asegúrate de pasar este valor desde el frontend

        // Guardar la visita
        $visita->save();

        // Comprobar si el ID de la visita se generó correctamente
        Log::info('ID de la visita después de guardar:', ['idVisita' => $visita->idVisitas]);

        // Verificar si necesita apoyo y si se seleccionaron técnicos de apoyo
        if ($visita->necesita_apoyo == 1 && $request->has('tecnicos_apoyo')) {
            // Guardar técnicos de apoyo en la tabla ticketapoyo
            foreach ($request->tecnicos_apoyo as $tecnicoId) {
                DB::table('ticketapoyo')->insert([
                    'idTecnico' => $tecnicoId,
                    'idTicket' => $visita->idTickets, // Usar el idTickets de la visita
                    'idVisita' => $visita->idVisitas,
                ]);
            }
        }



        return response()->json(['success' => true, 'message' => 'Visita guardada exitosamente']);
    }








    public function obtenerVisitas($ticketId)
    {
        // Obtener todas las visitas del ticket
        $visitas = Visita::where('idTickets', $ticketId)->get();

        // Convertir las fechas a formato ISO 8601
        $visitas->each(function ($visita) {
            $visita->fecha_inicio = $visita->fecha_inicio->toIso8601String();
            $visita->fecha_final = $visita->fecha_final->toIso8601String();
        });

        return response()->json($visitas);
    }
}
