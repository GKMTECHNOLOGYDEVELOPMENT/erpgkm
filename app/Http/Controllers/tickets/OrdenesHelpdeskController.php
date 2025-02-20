<?php

namespace App\Http\Controllers\tickets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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


    public function exportHelpdeskToExcel()
    {
        return Excel::download(new HelpdeskTicketExport(), 'helpdesk_tickets.xlsx');
    }






}
