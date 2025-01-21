<?php

namespace App\Http\Controllers\tickets;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrdenTrabajo; // Asegúrate de tener este modelo
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
                'nombre' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'estado' => 'nullable|boolean',
            ]);

            // Crear una nueva orden de trabajo
            OrdenTrabajo::create([
                'nombre' => $validatedData['nombre'],
                'descripcion' => $validatedData['descripcion'] ?? '',
                'estado' => $validatedData['estado'] ?? 1,
            ]);

            return response()->json(['success' => true, 'message' => 'Orden de trabajo creada correctamente.']);
        } catch (\Exception $e) {
            Log::error('Error al crear una orden de trabajo: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Ocurrió un error al crear la orden de trabajo.'], 500);
        }
    }

    // Editar una orden de trabajo
    public function edit($id)
    {
        $orden = OrdenTrabajo::findOrFail($id);
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
            $orden = OrdenTrabajo::findOrFail($id);
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
            $orden = OrdenTrabajo::findOrFail($id);
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
        $ordenes = OrdenTrabajo::all();

        $pdf = Pdf::loadView('tickets.ordenes-trabajo.pdf.ordenes', compact('ordenes'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('reporte-ordenes-trabajo.pdf');
    }

    // Obtener todas las órdenes de trabajo en formato JSON
    public function getAll()
    {
        $ordenes = OrdenTrabajo::all();

        return response()->json($ordenes);
    }

    // Validar si un nombre ya existe
    public function checkNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $exists = OrdenTrabajo::where('nombre', $nombre)->exists();

        return response()->json(['unique' => !$exists]);
    }
}
