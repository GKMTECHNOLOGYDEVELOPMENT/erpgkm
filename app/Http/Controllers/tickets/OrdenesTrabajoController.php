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
use App\Models\TicketFlujo;
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
    
        // dd($visita);

    
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
        $encargado = Usuario::whereIn('idTipoUsuario', [3, 5])->get();
        $tecnicos_apoyo = Usuario::where('idTipoUsuario', 3)->get();
        $clientes = Cliente::all();
        $clientesGenerales = ClienteGeneral::all();
        $modelos = Modelo::all();
        $tiendas = Tienda::all();
        $marcas = Marca::all();
    
        // Pasamos los datos a la vista
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
            'ticketId',
            'visitaId',
            'estadosOTS',
            'visita' // Pasamos el idVisitas a la vista
        ));
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
    
  
    
        $encargado = DB::table('usuarios')->where('idUsuario', $request->encargado)->first();

        // Asignar el idEstadflujo basado en el tipo de encargado
        $idEstadflujo = ($encargado->idTipoUsuario == 3) ? 2 : 4;
    
        // Insertar en la tabla ticketflujo
        DB::table('ticketflujo')->insert([
            'idTicket' => $visita->idTickets,
            'idEstadflujo' => $idEstadflujo,
            'idUsuario' => auth()->id(),  // Usuario autenticado

            'fecha_creacion' => now(),
        ]);
    
        // Comprobar si necesita apoyo y si se seleccionaron técnicos de apoyo
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
        // Obtener todas las visitas del ticket, incluyendo el técnico
        $visitas = Visita::with('tecnico')->where('idTickets', $ticketId)->get();
    
        // Convertir las fechas a formato ISO 8601
        $visitas->each(function ($visita) {
            $visita->fecha_inicio = $visita->fecha_inicio->toIso8601String();
            $visita->fecha_final = $visita->fecha_final->toIso8601String();
            // Incluir el nombre del técnico
            $visita->nombre_tecnico = $visita->tecnico ? $visita->tecnico->Nombre : null;  // Aquí asumimos que el campo 'nombre' está en el modelo Usuario
        });
    
        return response()->json($visitas);
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


public function guardarAnexoVisita(Request $request)
{
    try {
        // Validación de los datos
        $validated = $request->validate([
            'idVisitas' => 'required|integer|exists:visitas,idVisitas',
            'idTipovisita' => 'required|integer',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'ubicacion' => 'required|string|max:255',
        ]);

        // Verificar si ya existe un anexo para la visita y tipo de visita (sin importar lat y lng)
        $existingAnexo = DB::table('anexos_visitas')
            ->where('idVisitas', $validated['idVisitas'])
            ->where('idTipovisita', $validated['idTipovisita'])
            ->first();

        if ($existingAnexo) {
            return response()->json(['error' => 'El técnico ya se encuentra en desplazamiento para esta visita.'], 400);
        }

        // Insertar los datos en la tabla anexos_visitas si no existe
        DB::table('anexos_visitas')->insert([
            'idVisitas' => $validated['idVisitas'],
            'idTipovisita' => $validated['idTipovisita'],
            'lat' => $validated['lat'],
            'lng' => $validated['lng'],
            'ubicacion' => $validated['ubicacion'],
        ]);

        return response()->json(['success' => true, 'message' => 'Anexo guardado correctamente.'], 200);
    } catch (\Exception $e) {
        // Manejo de errores
        return response()->json(['error' => 'Error al guardar el anexo', 'message' => $e->getMessage()], 500);
    }
}



public function guardarFoto(Request $request)
{
    try {
        // Validación del archivo y el id de visita
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'visitaId' => 'required|integer'
        ]);

        // Obtener el archivo
        $file = $request->file('photo');

        // Registrar en el log el nombre del archivo
        Log::info('Archivo recibido: ' . $file->getClientOriginalName());

        // Convertir la imagen a binario
        $foto = file_get_contents($file);

        // Registrar el tamaño de la foto
        Log::info('Tamaño de la foto: ' . $file->getSize() . ' bytes');

        // Obtener el ID de la visita
        $visitaId = $request->input('visitaId');

        // Registrar el ID de la visita
        Log::info('ID de la visita: ' . $visitaId);

        // Asegurarse de que la visita existe antes de guardar el anexo
        $visita = Visita::find($visitaId);
        if (!$visita) {
            Log::error('La visita con ID ' . $visitaId . ' no existe.');
            return response()->json(['success' => false, 'message' => 'La visita no existe.'], 404);
        }

        // Guardar la foto en la tabla anexos_visitas
        $anexo = new AnexosVisita();
        $anexo->foto = $foto;
        $anexo->descripcion = 'Inicio de servicio'; // Descripción
        $anexo->idTipovisita = 3; // Tipo de visita 3
        $anexo->idVisitas = $visitaId; // ID de la visita
        $anexo->lat = null; // Latitud (null)
        $anexo->lng = null; // Longitud (null)
        $anexo->ubicacion = null; // Ubicación (null)

        $anexo->save();

        Log::info('Foto subida correctamente para la visita con ID ' . $visitaId);

        return response()->json(['success' => true, 'message' => 'Foto subida con éxito.']);
    } catch (\Exception $e) {
        // Manejo de error y log de la excepción
        Log::error('Error al guardar la foto: ' . $e->getMessage());
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



public function verificarRegistroAnexo($idVisitas)
{
    // Verificar si existe un registro en la tabla anexos_visitas con el idVisitas y los campos requeridos
    $registro = AnexosVisita::where('idVisitas', $idVisitas)
        ->whereNotNull('lat')
        ->whereNotNull('lng')
        ->whereNotNull('ubicacion')
        ->where('idTipovisita', 2) // Asumiendo que el tipo de visita 2 es "Inicio de Servicio"
        ->first();

    if ($registro) {
        // Si ya existe el registro, devolverlo
        return response()->json($registro);
    } else {
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
        'fecha_condicion' => 'required|date'
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 400);
    }

    // Guardar los datos en la base de datos
    $condicion = CondicionesTicket::create($request->all());

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
        'idVisitas' => 'nullable|integer|exists:visitas,idVisitas',
        'idEstadoots' => 'required|integer|exists:estado_ots,idEstadoots',
        'justificacion' => 'nullable|string'
    ]);

    // Buscar si ya existe un registro para este ticket, visita y estado
    $transicion = DB::table('transicion_status_ticket')
        ->where('idTickets', $request->idTickets)
        ->where('idVisitas', $request->idVisitas)
        ->where('idEstadoots', $request->idEstadoots)
        ->first();

    if ($transicion) {
        // Si existe, actualizar la justificación
        DB::table('transicion_status_ticket')
            ->where('idTransicionStatus', $transicion->idTransicionStatus)
            ->update([
                'justificacion' => $request->justificacion,
                'fechaRegistro' => now()
            ]);
    } else {
        // Si no existe, crear un nuevo registro
        DB::table('transicion_status_ticket')->insert([
            'idTickets' => $request->idTickets,
            'idVisitas' => $request->idVisitas,
            'idEstadoots' => $request->idEstadoots,
            'justificacion' => $request->justificacion,
            'fechaRegistro' => now(),
            'estado' => 1 // Estado activo
        ]);
    }

    return response()->json(['success' => true, 'message' => 'Estado guardado correctamente.']);
}


public function obtenerJustificacion(Request $request)
{
    // Validar los parámetros
    $request->validate([
        'ticketId' => 'required|integer|exists:tickets,idTickets',
        'visitaId' => 'nullable|integer|exists:visitas,idVisitas',
        'estadoId' => 'required|integer|exists:estado_ots,idEstadoots'
    ]);

    // Buscar la justificación en la base de datos
    $transicion = DB::table('transicion_status_ticket')
        ->where('idTickets', $request->ticketId)
        ->where('idVisitas', $request->visitaId)
        ->where('idEstadoots', $request->estadoId)
        ->first();

    if ($transicion) {
        return response()->json([
            'success' => true,
            'justificacion' => $transicion->justificacion
        ]);
    } else {
        return response()->json([
            'success' => true,
            'justificacion' => null // No hay justificación guardada
        ]);
    }
}




public function guardarImagen(Request $request)
{
    // Validar la solicitud
    $request->validate([
        'imagen' => 'required|image|max:2048', // Validamos que la imagen sea válida y no sea mayor de 2MB
        'descripcion' => 'required|string|max:255',
        'ticket_id' => 'required|integer|exists:tickets,idTickets', // Asegúrate de validar el ID del ticket
        'visita_id' => 'nullable|integer|exists:visitas,idVisitas', // Si tienes visita_id
    ]);

    // Obtener los datos de la solicitud
    $imagen = $request->file('imagen'); // Imagen en formato binario
    $descripcion = $request->input('descripcion');
    $ticket_id = $request->input('ticket_id');
    $visita_id = $request->input('visita_id') ?? null;

    // Log para ver los datos recibidos
    Log::info('Datos recibidos:');
    Log::info('Descripción: ' . $descripcion);
    Log::info('Ticket ID: ' . $ticket_id);
    Log::info('Visita ID: ' . $visita_id);
    Log::info('Imagen: ' . $imagen->getClientOriginalName()); // Nombre original del archivo

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
    $foto->save();

    // Log para verificar que la imagen se guardó correctamente
    Log::info('Imagen guardada con éxito en la base de datos.');

    // Devolver una respuesta exitosa
    return response()->json(['success' => true, 'message' => 'Imagen guardada correctamente.']);
}


public function obtenerImagenes($ticketId, $visitaId)
    {
        $imagenes = DB::table('fotostickest')
            ->where('idTickets', $ticketId)
            ->where('idVisitas', $visitaId)
            ->get();

        // Convertir las imágenes a base64 para poder mostrarlas en el frontend
        $imagenes = $imagenes->map(function ($imagen) {
            return [
                'id' => $imagen->idfotostickest,
                'src' => 'data:image/jpeg;base64,' . base64_encode($imagen->foto),
                'description' => $imagen->descripcion
            ];
        });

        return response()->json(['imagenes' => $imagenes]);
    }


    // app/Http/Controllers/ImagenController.php
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


}
