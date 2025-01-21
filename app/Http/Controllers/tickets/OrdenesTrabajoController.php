<?php

namespace App\Http\Controllers\tickets;

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


class OrdenesTrabajoController extends Controller
{
    // Mostrar la vista principal
    public function index()
    {
        // Obtén los datos necesarios para el formulario
        $clientesGenerales = ClienteGeneral::all(); // Reemplaza con el modelo correcto
        $tiposServicio = TipoServicio::all(); // Reemplaza con el modelo correcto
        $usuarios = Usuario::where('idTipoUsuario', 1)->get();
        $tiposTickets = Tipoticket::all(); // Obtiene los tipos de tickets
        $clientes = Cliente::all(); // Obtiene todos los clientes
        $tiendas = Tienda::all(); // Obtiene todas las tiendas

        // Retorna la vista y pasa las variables
        return view('tickets.ordenes-trabajo.index', compact('clientesGenerales', 'tiposServicio', 'usuarios', 'tiposTickets', 'clientes', 'tiendas')); // Asegúrate de que esta vista exista
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

            // Crear la nueva orden de trabajo
            Ticket::create([
                'idTipotickets' => $validatedData['idTipotickets'],
                'numero_ticket' => $validatedData['nroTicket'],
                'idClienteGeneral' => $validatedData['idClienteGeneral'],
                'idCliente' => $validatedData['idCliente'],
                'IdTienda' => $validatedData['idTienda'],
                'idTecnico' => $validatedData['tecnico'],
                'tipoServicio' => $validatedData['tipoServicio'],
                'idUsuario' => auth()->id(), // ID del usuario autenticado
                'idEstadoots' => 1, // Estado inicial de la orden de trabajo
                'fecha_creacion' => now(), // Fecha actual para la creación
            ]);

            return response()->json(['success' => true, 'message' => 'Orden de trabajo creada correctamente.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Errores de validación.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error al crear una orden de trabajo: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al crear la orden de trabajo.'], 500);
        }
    }




    // Editar una orden de trabajo
    public function edit($id)
    {
        $orden = Ticket::findOrFail($id);
        return view('tickets.ordenes-trabajo.edit', compact('orden'));
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
    // Obtener todas las órdenes de trabajo en formato JSON
    public function getAll()
    {
        $ordenes = Ticket::with([
            'tecnico:idUsuario,Nombre', // Relación para obtener el nombre del técnico
            'usuario:idUsuario,Nombre', // Relación para obtener el nombre del usuario
            'cliente:idCliente,nombre', // Relación para obtener el nombre del cliente
            'clientegeneral:idClienteGeneral,descripcion', // Relación para el cliente general
            'tiposervicio:idTipoServicio,nombre', // Relación para obtener el nombre del tipo de servicio
            'estado_ot:idEstadoots,descripcion', // Relación para obtener la descripción del estado
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
            ];
        });

        return response()->json($ordenes);
    }



    // Validar si un nombre ya existe
    public function checkNumeroTicket(Request $request)
    {
        $numero_ticket = $request->input('numero_ticket');
        $exists = Ticket::where('numero_ticket', $numero_ticket)->exists();

        return response()->json(['unique' => !$exists]);
    }
}
