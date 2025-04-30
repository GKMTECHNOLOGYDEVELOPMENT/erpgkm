<?php

namespace App\Http\Controllers\almacen\productos;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use App\Models\Modelo;
use App\Models\Marca; // Add this line
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class ModelosController extends Controller
{
    public function index()
    {
        // Obtener todas las marcas y categorías activas
        $marcas = Marca::where('estado', 1)->get();
        $categorias = Categoria::where('estado', 1)->get();

        // Retornar la vista con los datos
        return view('almacen.productos.modelos.index', compact('marcas', 'categorias'));
    }


    public function store(Request $request)
    {
        try {
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'idMarca' => 'required|integer|exists:marca,idMarca',
                'idCategoria' => 'required|integer|exists:categoria,idCategoria',
            ]);

            // Datos básicos del modelo
            $dataModelo = [
                'nombre' => $validatedData['nombre'],
                'idMarca' => $validatedData['idMarca'],
                'idCategoria' => $validatedData['idCategoria'],
                'estado' => 1,
            ];

            // Guardar el modelo en la base de datos
            Log::info('Insertando modelo:', $dataModelo);
            Modelo::create($dataModelo);

            return response()->json([
                'success' => true,
                'message' => 'Modelo agregado correctamente',
                'data' => $dataModelo,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al guardar el modelo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar el modelo.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $modelo = Modelo::findOrFail($id);
        $marcas = Marca::all();
        $categorias = Categoria::all();
        return view('almacen.productos.modelos.edit', compact('modelo', 'marcas', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'idMarca' => 'required|integer|exists:marca,idMarca',
                'idCategoria' => 'required|integer|exists:categoria,idCategoria',
                'estado' => 'nullable|boolean',
            ]);

            // Obtener el modelo
            $modelo = Modelo::findOrFail($id);
            Log::info("Actualizando modelo con ID: $id");

            // Actualizar los datos del modelo
            $modelo->update($validatedData);

            return redirect()->route('modelos.index')
                ->with('success', 'Modelo actualizado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar el modelo: ' . $e->getMessage());
            return redirect()->route('modelos.index')
                ->with('error', 'Ocurrió un error al actualizar el modelo.');
        }
    }

    public function destroy($id)
    {
        try {
            $modelo = Modelo::findOrFail($id);

            // Eliminar el modelo
            $modelo->delete();

            return response()->json([
                'success' => true,
                'message' => 'Modelo eliminado con éxito',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar el modelo: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar el modelo.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function exportAllPDF()
    {
        // Obtener todos los modelos
        $modelos = Modelo::with(['marca', 'categoria'])->get();
        Log::info( 'Modelos obtenidos' .$modelos );

        // Generar el PDF
        $pdf = Pdf::loadView('almacen.productos.modelos.pdf.reporte-modelos', compact('modelos'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('reporte-modelos.pdf');
    }

    public function getAll()
    {
        $modelos = Modelo::with(['marca', 'categoria'])->get();
        $modelosData = $modelos->map(function ($modelo) {
            return [
                'idModelo' => $modelo->idModelo,
                'nombre' => $modelo->nombre,
                'marca' => $modelo->marca->nombre ?? 'Sin Marca',
                'categoria' => $modelo->categoria->nombre ?? 'Sin Categoría',
                'estado' => $modelo->estado ? 'Activo' : 'Inactivo',
            ];
        });

        return response()->json($modelosData);
    }

    public function checkNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $idMarca = $request->input('idMarca');
        $idCategoria = $request->input('idCategoria');
    
        $exists = Modelo::where('nombre', $nombre)
            ->where('idMarca', $idMarca)
            ->where('idCategoria', $idCategoria)
            ->exists();
    
        return response()->json(['unique' => !$exists]);
    }
    


    
}
