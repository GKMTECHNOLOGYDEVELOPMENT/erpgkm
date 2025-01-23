<?php

namespace App\Http\Controllers\almacen\kits;

use App\Http\Controllers\Controller;
use App\Models\Kit;
use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class KitsController extends Controller
{
    public function index()
    {
        // Obtener todos los kits
        $kits = Kit::with('articulos')->get();

        // Cargar la vista index
        return view('almacen.kits-articulos.index', compact('kits'));
    }

    public function create()
    {
        // Obtener los artículos activos para el select
        $articulos = Articulo::where('estado', 1)->get();

        // Cargar la vista de creación
        return view('almacen.kits-articulos.create', compact('articulos'));
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'codigo' => 'nullable|string|max:255',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:255',
            'precio_compra' => 'nullable|numeric|min:0',
            'precio_venta' => 'nullable|numeric|min:0', // Este será el campo que corresponde a 'precio' en la BD
            'fecha' => 'nullable|date',
            'monedaCompra' => 'nullable|integer|exists:monedas,idMonedas', // Validar contra la tabla 'monedas'
            'monedaVenta' => 'nullable|integer|exists:monedas,idMonedas',  // Validar contra la tabla 'monedas'

        ]);

        Log::info('Datos validados para el kit:', $validatedData);

        try {
            // Crear el kit
            $kit = Kit::create([
                'codigo' => $validatedData['codigo'] ?? null,
                'nombre' => $validatedData['nombre'],
                'descripcion' => $validatedData['descripcion'] ?? null,
                'precio_compra' => $validatedData['precio_compra'] ?? null,
                'precio' => $validatedData['precio_venta'] ?? null, // Mapear precio_venta al campo precio
                'fecha' => $validatedData['fecha'] ?? now(), // Usar la fecha actual si no se proporciona
                'monedaCompra' => $validatedData['monedaCompra'] ?? null,
                'monedaVenta' => $validatedData['monedaVenta'] ?? null,
            ]);



            // Redirigir con un mensaje de éxito
            return redirect()->route('almacen.kits.index')->with('success', 'Kit creado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear el kit: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al crear el kit. Por favor, intente de nuevo.');
        }
    }

    public function edit($id)
    {
        // Buscar el kit por ID
        $kit = Kit::with('articulos')->findOrFail($id);

        // Obtener los artículos activos para el select
        $articulos = Articulo::where('estado', 1)->get();

        // Cargar la vista de edición
        return view('almacen.kits-articulos.edit', compact('kit', 'articulos'));
    }

    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->all();

        try {
            // Buscar el kit y actualizarlo
            $kit = Kit::findOrFail($id);
            $kit->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'estado' => $request->has('estado') ? 1 : 0,
            ]);

            // Sincronizar los artículos
            $kit->articulos()->sync($request->articulos);

            return redirect()->route('almacen.kits.index')->with('success', 'Kit actualizado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar el kit: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al actualizar el kit.');
        }
    }

    public function destroy($id)
    {
        try {
            $kit = Kit::findOrFail($id);
            $kit->delete();

            return response()->json(['message' => 'Kit eliminado con éxito'], 200);
        } catch (\Exception $e) {
            Log::error('Error al eliminar el kit: ' . $e->getMessage());
            return response()->json(['error' => 'Hubo un problema al eliminar el kit.'], 500);
        }
    }

    public function exportAllPDF()
    {
        try {
            $kits = Kit::with('articulos')->get();

            $pdf = PDF::loadView('almacen.kits-articulos.pdf.kits', compact('kits'))
                ->setPaper('a4', 'landscape');

            return $pdf->download('reporte-kits.pdf');
        } catch (\Exception $e) {
            Log::error('Error al generar el PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al generar el PDF.');
        }
    }

    public function getAll()
    {
        $kits = Kit::with('articulos')->get();

        return response()->json($kits);
    }
}
