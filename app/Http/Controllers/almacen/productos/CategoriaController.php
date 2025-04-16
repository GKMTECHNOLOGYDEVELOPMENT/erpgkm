<?php

namespace App\Http\Controllers\almacen\productos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class CategoriaController extends Controller
{
    public function index()
    {   

        return view('almacen.productos.categoria.index');
    }

    public function store(Request $request)
    {
        try {
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'estado' => 'nullable|boolean',
            ]);

            // Datos básicos de la categoría
            $dataCategoria = [
                'nombre' => $validatedData['nombre'],
                'estado' => $validatedData['estado'] ?? 1,
            ];

            // Guardar la categoría en la base de datos
            Log::info('Insertando categoría:', $dataCategoria);
            Categoria::create($dataCategoria);

            return response()->json([
                'success' => true,
                'message' => 'Categoría agregada correctamente',
                'data' => $dataCategoria,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al guardar la categoría: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al guardar la categoría.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $categoria = Categoria::findOrFail($id);
        return view('almacen.productos.categoria.edit', compact('categoria'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Validar los datos del formulario
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'estado' => 'nullable|boolean',
            ]);

            // Obtener la categoría
            $categoria = Categoria::findOrFail($id);
            Log::info("Actualizando categoría con ID: $id");

            // Actualizar los datos de la categoría
            $categoria->update($validatedData);

            return redirect()->route('categorias.index')
                ->with('success', 'Categoría actualizada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar la categoría: ' . $e->getMessage());
            return redirect()->route('categorias.index')
                ->with('error', 'Ocurrió un error al actualizar la categoría.');
        }
    }

    public function destroy($id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
    
            // Intentar eliminar
            $categoria->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Categoría eliminada con éxito',
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                // Código de error para violación de integridad referencial
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar la categoría porque está asociada a uno o más equipos.',
                ], 409); // 409 Conflict
            }
    
            // Otros errores de base de datos
            Log::error('Error al eliminar la categoría: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar la categoría.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            Log::error('Error inesperado al eliminar la categoría: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar la categoría.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    

    public function exportAllPDF()
    {
        // Obtener todas las categorías
        $categorias = Categoria::all();

        // Generar el PDF
        $pdf = Pdf::loadView('almacen.productos.categoria.pdf.reporte-categorias', compact('categorias'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('reporte-categorias.pdf');
    }

    public function getAll()
    {
        $categorias = Categoria::all();

        $categoriasData = $categorias->map(function ($categoria) {
            return [
                'idCategoria' => $categoria->idCategoria,
                'nombre' => $categoria->nombre,
                'estado' => $categoria->estado ? 'Activo' : 'Inactivo',
            ];
        });

        return response()->json($categoriasData);
    }

    public function checkNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $exists = Categoria::where('nombre', $nombre)->exists();

        return response()->json(['unique' => !$exists]);
    }
}
