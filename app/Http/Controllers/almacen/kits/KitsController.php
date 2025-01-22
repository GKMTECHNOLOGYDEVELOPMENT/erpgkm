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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'articulos' => 'required|array', // Validar que se seleccionen artículos
            'articulos.*' => 'exists:articulos,idArticulos', // Validar que los artículos existan
        ]);

        try {
            // Crear el kit
            $kit = Kit::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'estado' => $request->has('estado') ? 1 : 0,
            ]);

            // Asociar artículos al kit
            $kit->articulos()->attach($request->articulos);

            return redirect()->route('almacen.kits.index')->with('success', 'Kit creado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al crear el kit: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al crear el kit.');
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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
            'articulos' => 'required|array',
            'articulos.*' => 'exists:articulos,idArticulos',
        ]);

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
