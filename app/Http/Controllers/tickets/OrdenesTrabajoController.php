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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketExport;
use App\Models\Visita;
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
            Log::info('Inicio de creación de orden de trabajo Helpdesk', ['data' => $request->all()]);
    
            // Validación de los datos
            $validatedData = $request->validate([
                'numero_ticket' => 'required|string|max:255|unique:tickets,numero_ticket',
                'idClienteGeneral' => 'required|integer|exists:clientegeneral,idClienteGeneral',
                'idCliente' => 'required|integer|exists:cliente,idCliente',
                'idTienda' => 'required|integer|exists:tienda,idTienda',
                'idTecnico' => 'required|integer|exists:usuarios,idUsuario',
                'tipoServicio' => 'required|integer|exists:tiposervicio,idTipoServicio',
                'fallaReportada' => 'required|string|max:255',
            ]);
    
            Log::info('Datos validados correctamente', ['validatedData' => $validatedData]);
    
            // Crear la orden de trabajo
            $ticket = Ticket::create([
                'numero_ticket' => $validatedData['numero_ticket'],
                'idClienteGeneral' => $validatedData['idClienteGeneral'],
                'idCliente' => $validatedData['idCliente'],
                'idTienda' => $validatedData['idTienda'],
                'idTecnico' => $validatedData['idTecnico'],
                'tipoServicio' => $validatedData['tipoServicio'],
                'idUsuario' => auth()->id(),
                'idEstadoots' => 17, // Estado inicial
                'fallaReportada' => $validatedData['fallaReportada'],
                'fecha_creacion' => now(),
            ]);
    
            Log::info('Orden de trabajo Helpdesk creada correctamente', ['ticket' => $ticket]);
    
            // Redirigir a la edición de la orden recién creada
            return redirect()->route('ordenes.helpdesk.edit', ['id' => $ticket->idTickets])
            ->with('success', 'Orden de trabajo creada correctamente.');            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Errores de validación en storehelpdesk', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error al crear la orden de trabajo Helpdesk', ['exception' => $e->getMessage()]);
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
                'idEstadflujo' => 1, // Estado inicial de la orden de trabajo
                'idUsuario' => auth()->id(), // ID del usuario autenticado
                'fecha_creacion' => now(), // Fecha de creación
            ]);

            Log::info('Orden de trabajo creada correctamente', ['ticket' => $ticket]);

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


    // Editar orden de trabajo según el rol
    public function edit($id)
    {
        $usuario = Auth::user();
        $rol = $usuario->rol->nombre ?? 'Sin Rol';

        $orden = Ticket::with(['marca', 'modelo', 'cliente', 'tecnico', 'tienda', 'estadoflujo', 'usuario'])->findOrFail($id);
        $ticket = Ticket::with(['marca', 'modelo', 'cliente', 'tecnico', 'tienda', 'estadoflujo', 'usuario'])->findOrFail($id);
        // Obtener el estado de flujo si es necesario
        $estadoflujo = $orden->estadoflujo;

        $usuario = $orden->usuario;

        $tecnico = Usuario::where('idRol', 3)->get();
        $tecnicos_apoyo = Usuario::where('idRol', 3)->get();

        // Obtener todos los clientes disponibles
        $clientes = Cliente::all();
        // Obtener todos los clientes generales disponibles
        $clientesGenerales = ClienteGeneral::all();
        $estadosFlujo = EstadoFlujo::all();
        $modelos = Modelo::all(); // Obtén todos los modelos disponibles
        // Obtener todas las tiendas disponibles
        $tiendas = Tienda::all();
        // Obtener todas las marcas disponibles
        $marcas = Marca::all();
        
        return view("tickets.ordenes-trabajo.smart-tv.edit", compact('ticket', 'orden', 'modelos', 'usuario', 'estadosFlujo', 'clientes', 'clientesGenerales', 'tiendas', 'marcas', 'tecnico', 'tecnicos_apoyo'));
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
            'orden', 'usuarios', 'tiposServicio', 'modelos', 'clientes', 
            'clientesGenerales', 'tiendas', 'marcas', 'estadosFlujo'
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

    // Exportar todas las órdenes de trabajo a PDF
    public function exportAllPDF()
    {
        $ordenes = Ticket::all();

        $pdf = Pdf::loadView('tickets.ordenes-trabajo.pdf.ordenes', compact('ordenes'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('reporte-ordenes-trabajo.pdf');
    }

    public function exportToExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $marca     = $request->input('marca');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        return Excel::download(
            new TicketExport($marca, $startDate, $endDate, 10000),
            'tickets.xlsx'
        );
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
            'estadoflujo:idEstadflujo,descripcion,color', // Cargar toda la relación estadoflujo


        ]);

        // Filtro por marca si es proporcionado
        if ($request->has('marca') && $request->marca != '') {
            $ordenesQuery->where('idMarca', $request->marca);
        }

        // Filtro por cliente general si es proporcionado
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
        try {
            // Validar los datos del request
            $request->validate([
                'nombre' => 'required|string|max:255',
                'fecha_programada' => 'required|date',
                'hora_inicio' => 'required|date_format:H:i',
                'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
                'tecnico_id' => 'required|exists:usuarios,idUsuario',
                'necesita_apoyo' => 'nullable|boolean',
                'tecnico_apoyo' => 'nullable|array',
                'ticket_id' => 'required|exists:tickets,idTickets',
            ]);

            // Log de los datos recibidos
            Log::info('Datos recibidos en guardarVisita:', $request->all());

            // Obtener el ticket por su id
            $ticket = Ticket::findOrFail($request->ticket_id);
            Log::info('Ticket encontrado:', ['ticket_id' => $ticket->idTickets]);

            // Crear la visita en la base de datos
            $visita = new Visita();
            $visita->nombre = $request->nombre;
            $visita->fecha_programada = $request->fecha_programada;
            $visita->fecha_inicio = $request->hora_inicio;
            $visita->fecha_final = $request->hora_fin;
            $visita->fecha_inicio_hora = date('H:i', strtotime($request->hora_inicio));
            $visita->fecha_final_hora = date('H:i', strtotime($request->hora_fin));
            $visita->estado = 1; // Estado activo o "pendiente"
            $visita->idTickets = $ticket->idTickets;
            Log::info('Datos de visita antes de guardar:', [
                'nombre' => $visita->nombre,
                'fecha_programada' => $visita->fecha_programada,
                'hora_inicio' => $visita->fecha_inicio,
                'hora_fin' => $visita->fecha_final,
            ]);
            $visita->save();
            Log::info('Visita guardada con éxito, ID Visita:', ['idVisita' => $visita->idVisitas]);

            // Relacionar el técnico con la visita
            $tecnico = Usuario::find($request->tecnico_id);
            Log::info('Técnico asignado:', ['tecnico_id' => $tecnico->idUsuario, 'Nombre' => $tecnico->Nombre]);
            $visita->idTecnico = $tecnico->idUsuario;
            $visita->save();

            // Si se necesita apoyo, guardar los técnicos de apoyo en la tabla ticketapoyo
            if ($request->necesita_apoyo && !empty($request->tecnico_apoyo)) {
                Log::info('Técnicos de apoyo a asignar:', $request->tecnico_apoyo);
                $tecnicosDeApoyo = $request->tecnico_apoyo;

                // Guardar técnicos de apoyo en la tabla ticketapoyo
                foreach ($tecnicosDeApoyo as $idTecnico) {
                    // Asegúrate de que el 'idVisita' es el correcto y que la visita ya está guardada
                    TicketApoyo::create([
                        'idTecnico' => $idTecnico, // El ID del técnico de apoyo
                        'idTicket' => $ticket->idTicket, // ID del ticket
                        'idVisita' => $visita->idVisita, // ID de la visita recién creada
                    ]);
                    Log::info('Técnico de apoyo asignado:', ['idTecnico' => $idTecnico, 'idVisita' => $visita->idVisitas]);
                }
            }

            // Respuesta de éxito
            Log::info('Visita guardada con éxito:', ['visita_id' => $visita->idVisitas]);
            return response()->json(['success' => true, 'message' => 'Visita guardada con éxito'], 200);
        } catch (\Exception $e) {
            // Capturar cualquier error y registrar un log
            Log::error('Error al guardar la visita:', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            // Respuesta de error
            return response()->json(['success' => false, 'message' => 'Error al guardar la visita'], 500);
        }
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
