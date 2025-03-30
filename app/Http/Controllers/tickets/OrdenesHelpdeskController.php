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
use App\Models\TipoEnvio;
use App\Models\TipoRecojo;
use App\Models\TransicionStatusTicket;
use Spatie\Browsershot\Browsershot;

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




        return view("tickets.ordenes-trabajo.helpdesk.create", compact(
            'clientesGenerales',
            'clientes',
            'tiendas',
            'usuarios',
            'tiposServicio',
            'marcas',
            'modelos',
            'tiposEnvio',
            'tiposRecojo'
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
                'tipoServicio' => 'required|integer|exists:tiposervicio,idTipoServicio',
                'fallaReportada' => 'required|string|max:255',
                'esEnvio' => 'nullable|boolean',  // Validamos si el campo 'esEnvio' está presente
                'idTecnico' => 'nullable|integer|exists:usuarios,idUsuario',  // Validamos el ID del técnico
                'tipoRecojo' => 'nullable|integer|exists:tiporecojo,idtipoRecojo', // Validar tipo de recojo
                'tipoEnvio' => 'nullable|integer|exists:tipoenvio,idtipoenvio', // Validar tipo de envío
                'nombreTecnicoEnvio' => 'nullable|array',
                'dniTecnicoEnvio' => 'nullable|array'
            ]);

            Log::debug('Datos validados:', $validatedData);

            // Guardar la orden de trabajo
            $ticket = Ticket::create([
                'numero_ticket' => $validatedData['numero_ticket'],
                'idClienteGeneral' => $validatedData['idClienteGeneral'],
                'idCliente' => $validatedData['idCliente'],
                'idTienda' => $validatedData['idTienda'],
                'tipoServicio' => $validatedData['tipoServicio'],
                'idUsuario' => auth()->id(),
                'fallaReportada' => $validatedData['fallaReportada'],
                'fecha_creacion' => now(),
                'idTipotickets' => 2,
                'envio' => $validatedData['esEnvio'] ? 1 : 0,
                'idEncargadoEnvio_Provincia' => $validatedData['idTecnico'] ?? NULL
            ]);

            Log::debug('Orden de trabajo creada correctamente.');


            // Si es un envío, almacenar los técnicos de recojo
            if ($validatedData['esEnvio']) {
                foreach ($validatedData['nombreTecnicoEnvio'] as $index => $nombre) {
                    $dni = $validatedData['dniTecnicoEnvio'][$index];

                    // Guardar cada técnico de recojo
                    DB::table('ticket_receptor')->insert([
                        'idTickets' => $ticket->idTickets,
                        'nombre' => $nombre,
                        'dni' => $dni,
                    ]);
                }

                Log::info('Datos de técnicos de recojo guardados correctamente');
            }



            // Definir el idEstadflujo basado en el valor de esEnvio
            $idEstadflujo = $validatedData['esEnvio'] ? 28 : 1;

            // Crear el flujo de trabajo con el idEstadflujo correspondiente
            $ticketFlujoId = DB::table('ticketflujo')->insertGetId([
                'idTicket' => $ticket->idTickets,
                'idEstadflujo' => $idEstadflujo,  // Usar el valor de idEstadflujo basado en esEnvio
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

            // Verificar si es Envío y guardar en la tabla datos_envio
            if ($validatedData['esEnvio']) {
                // Insertar en la tabla datos_envio
                DB::table('datos_envio')->insert([
                    'idTickets' => $ticket->idTickets,
                    'tipoRecojo' => $validatedData['tipoRecojo'], // tipo de recojo seleccionado
                    'tipoEnvio' => $validatedData['tipoEnvio'], // tipo de envío seleccionado
                    'idUsuario' => $validatedData['idTecnico'], // ID del técnico seleccionado
                    'tipo' => 1
                ]);
                Log::info('Datos de envío guardados correctamente');
            }

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
            'idtipoServicio'

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

    public function generateLevantamientoPdf($idOt)
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

        // 🔹 Logo del cliente general
        $logoClienteGeneral = null;
        if ($orden->clienteGeneral && $orden->clienteGeneral->foto) {
            $logoClienteGeneral = 'data:image/png;base64,' . base64_encode($orden->clienteGeneral->foto);
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
            'firmaTecnico' => $firmaTecnico,
            'firmaCliente' => $firmaCliente,
            'imagenesAnexos' => $imagenesAnexos,
            'imagenesFotosTickets' => $imagenesFotosTickets,
            'emitente' => (object)['nome' => 'GKM TECHNOLOGY S.A.C.'],
            'logoClienteGeneral' => $logoClienteGeneral,
            'suministros' => $suministros,
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
            ->header('Content-Disposition', 'inline; filename="levantamiento_' . $idOt . '.pdf"');
    }



    public function obtenerClientes($idClienteGeneral)
    {
        $clientes = Cliente::where('cliente_general_id', $idClienteGeneral)->get();

        return response()->json($clientes);
    }
}
