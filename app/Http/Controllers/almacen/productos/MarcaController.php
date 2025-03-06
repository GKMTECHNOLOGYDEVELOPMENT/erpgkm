<?php

namespace App\Http\Controllers\almacen\productos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marca;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class MarcaController extends Controller
{
    public function index()
    {
        return view('almacen.productos.marcas.index');
    }

    public function store(Request $request)
    {
        try {
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
            ]);
    
            // Datos básicos de la marca con estado siempre en 1
            $dataMarca = [
                'nombre' => $validatedData['nombre'],
                'estado' => 1, // Estado siempre será 1
            ];
    
            // Guardar la marca en la base de datos
            Log::info('Insertando marca:', $dataMarca);
            Marca::create($dataMarca);
    
            return response()->json([
                'success' => true,
                'message' => 'Marca agregada correctamente',
                'data' => $dataMarca,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al guardar la marca: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar la marca.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function guardarMarcaSmart(Request $request)
    {
        try {
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
            ]);
    
            // Datos básicos de la marca con estado siempre en 1
            $dataMarca = [
                'nombre' => $validatedData['nombre'],
                'estado' => 1, // Estado siempre será 1
            ];
    
            // Guardar la marca en la base de datos
            Log::info('Insertando marca:', $dataMarca);
            Marca::create($dataMarca);
    
            return response()->json([
                'success' => true,
                'message' => 'Marca agregada correctamente',
                'data' => $dataMarca,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al guardar la marca: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar la marca.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    

    public function edit($id)
    {
        $marca = Marca::findOrFail($id);
        return view('almacen.productos.marcas.edit', compact('marca'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'estado' => 'nullable|boolean',
            ]);

            // Obtener la marca
            $marca = Marca::findOrFail($id);
            Log::info("Actualizando marca con ID: $id");

            // Actualizar los datos de la marca
            $marca->update($validatedData);

            return redirect()->route('marcas.index')
                ->with('success', 'Marca actualizada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar la marca: ' . $e->getMessage());
            return redirect()->route('marcas.index')
                ->with('error', 'Ocurrió un error al actualizar la marca.');
        }
    }

    public function destroy($id)
    {
        try {
            $marca = Marca::findOrFail($id);

            // Eliminar la marca
            $marca->delete();

            return response()->json([
                'success' => true,
                'message' => 'Marca eliminada con éxito',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar la marca: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar la marca.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function exportAllPDF()
    {
        // Obtener todas las marcas
        $marcas = Marca::all();

        // Generar el PDF
        $pdf = Pdf::loadView('almacen.productos.marcas.pdf.reporte-marcas', compact('marcas'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('reporte-marcas.pdf');
    }

    public function getAll()
    {
        $marcas = Marca::all();

        $marcasData = $marcas->map(function ($marca) {
            return [
                'idMarca' => $marca->idMarca,
                'nombre' => $marca->nombre,
                'estado' => $marca->estado ? 'Activo' : 'Inactivo',
            ];
        });

        return response()->json($marcasData);
    }

    public function checkNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $exists = Marca::where('nombre', $nombre)->exists();

        return response()->json(['unique' => !$exists]);
    }


    public function checkMarcas(Request $request)
    {
        // Aquí hacemos una consulta para obtener las marcas activas (o según el estado que necesites)
        $marcas = Marca::where('estado', 1)->get(); // Filtramos por 'estado' igual a 1 (activas)

        return response()->json($marcas); // Devolvemos los datos en formato JSON
    }
}
