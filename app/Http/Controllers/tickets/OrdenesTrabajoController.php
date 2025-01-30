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
use App\Models\Tienda; // Reemplaza con el modelo correcto
use App\Models\Marca; // Reemplaza con el modelo correcto
use App\Models\Modelo; // Reemplaza con el modelo correcto
use Illuminate\Support\Facades\File; // Asegúrate de usar esta clase
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


    // Cargar la vista de creación según el rol del usuario
    public function create()
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

        $carpetaVista = match ($rol) {
            'COORDINACION SMART' => 'smart-tv',
            'COORDINACION HELP DESK' => 'helpdesk',
            default => '',
        };

        if ($carpetaVista) {
            return view("tickets.ordenes-trabajo.$carpetaVista.create", compact(
                'clientesGenerales',
                'clientes',
                'tiendas',
                'usuarios',
                'tiposServicio',
                'marcas',
                'modelos'
            ));
        } else {
            abort(403, 'No tienes permiso para acceder a esta vista.');
        }
    }


    // Guardar una nueva orden de trabajo
    public function store(Request $request)
    {
        try {
            // Validar los datos
            $validatedData = $request->validate([
                'idTipotickets' => 'required|integer|exists:tipotickets,idTipotickets',
                'nroTicket' => 'required|string|max:255|unique:tickets,numero_ticket',
                'idClienteGeneral' => 'required|integer|exists:clientegeneral,idClienteGeneral',
                'idCliente' => 'required|integer|exists:cliente,idCliente',
                'idTienda' => 'required|integer|exists:tienda,idTienda',
                'tecnico' => 'required|integer|exists:usuarios,idUsuario',
                'tipoServicio' => 'required|integer|exists:tiposervicio,idTipoServicio',
            ]);

            // Establecer la zona horaria explícitamente
            $fechaCreacion = now()->setTimezone('America/Lima');

            // Crear la nueva orden de trabajo
            Ticket::create([
                'idTipotickets' => $validatedData['idTipotickets'],
                'numero_ticket' => $validatedData['nroTicket'],
                'idClienteGeneral' => $validatedData['idClienteGeneral'],
                'idCliente' => $validatedData['idCliente'],
                'idTienda' => $validatedData['idTienda'],
                'idTecnico' => $validatedData['tecnico'],
                'tipoServicio' => $validatedData['tipoServicio'],
                'idUsuario' => auth()->id(), // ID del usuario autenticado
                'idEstadoots' => 17, // Estado inicial de la orden de trabajo
                'fecha_creacion' => $fechaCreacion, // Fecha actual en la zona horaria de Perú
            ]);

            return response()->json(['success' => true, 'message' => 'Orden de trabajo creada correctamente.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Errores de validación.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error al crear una orden de trabajo: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al crear la orden de trabajo.'], 500);
        }
    }





    // Editar orden de trabajo según el rol
    public function edit($id)
    {
        $usuario = Auth::user();
        $rol = $usuario->rol->nombre ?? 'Sin Rol';

        $orden = Ticket::with(['marca', 'modelo', 'cliente', 'tecnico', 'tienda'])->findOrFail($id);
        $modelos = Modelo::all(); // Obtén todos los modelos disponibles

        $carpetaVista = match ($rol) {
            'COORDINACION SMART' => 'smart-tv',
            'COORDINACION HELP DESK' => 'helpdesk',
            default => '',
        };

        if ($carpetaVista) {
            return view("tickets.ordenes-trabajo.$carpetaVista.edit", compact('orden', 'modelos'));
        } else {
            abort(403, 'No tienes permiso para acceder a esta vista.');
        }
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

    // Exportar todas las órdenes de trabajo a PDF
    public function exportAllPDF()
    {
        $ordenes = Ticket::all();

        $pdf = Pdf::loadView('tickets.ordenes-trabajo.pdf.ordenes', compact('ordenes'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('reporte-ordenes-trabajo.pdf');
    }

    // Obtener todas las órdenes de trabajo en formato JSON
    public function getAll()
    {
        $ordenes = Ticket::with([
            'tecnico:idUsuario,Nombre', // Relación para obtener el nombre del técnico
            'usuario:idUsuario,Nombre', // Relación para obtener el nombre del usuario
            'cliente:idCliente,nombre', // Relación para obtener el nombre del cliente
            'clientegeneral:idClienteGeneral,descripcion', // Relación para el cliente general
            'tiposervicio:idTipoServicio,nombre', // Relación para obtener el nombre del tipo de servicio
            'estado_ot:idEstadoots,descripcion,color', // Relación para obtener la descripción del estado
        ])->get();

        // Formatear el resultado
        $ordenes = $ordenes->map(function ($orden) {
            return [
                'idTickets' => $orden->idTickets,
                'numero_ticket' => $orden->numero_ticket,
                'tecnico' => $orden->tecnico->Nombre ?? 'N/A', // Nombre del técnico
                'usuario' => $orden->usuario->Nombre ?? 'N/A', // Nombre del usuario
                'cliente' => $orden->cliente->nombre ?? 'N/A', // Nombre del cliente
                'cliente_general' => $orden->clientegeneral->descripcion ?? 'N/A', // Nombre del cliente general
                'tipoServicio' => $orden->tiposervicio->nombre ?? 'N/A', // Nombre del tipo de servicio
                'estado' => $orden->estado_ot->descripcion ?? 'N/A', // Descripción del estado
                'fecha_creacion' => $orden->fecha_creacion ? $orden->fecha_creacion->format('d/m/Y H:i') : 'N/A', // Formato de fecha
                'color' => $orden->estado_ot->color ?? '#000000', // Color del estado
            ];
        });

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
}
