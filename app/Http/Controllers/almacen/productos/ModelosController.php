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

        public function storeMODELOSMART(Request $request)
    {
        try {
            // Validar datos básicos
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'idMarca' => 'required|integer|exists:marca,idMarca',
                'idCategoria' => 'required|integer|exists:categoria,idCategoria',
            ]);

            // Definir valores de checkboxes (si no están marcados, son 0)
            $repuesto = $request->has('repuesto') ? 1 : 0;
            $producto = $request->has('producto') ? 1 : 0;
            $heramientas = $request->has('heramientas') ? 1 : 0;
            $suministros = $request->has('suministros') ? 1 : 0;

            $dataModelo = [
                'nombre' => $validatedData['nombre'],
                'idMarca' => $validatedData['idMarca'],
                'idCategoria' => $validatedData['idCategoria'],
                'estado' => 1,
                'repuesto' => $repuesto,
                'producto' => $producto,
                'heramientas' => $heramientas,
                'suministros' => $suministros,
            ];

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

    public function store(Request $request)
    {
        try {
            // Validar datos básicos
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'idMarca' => 'required|integer|exists:marca,idMarca',
                'idCategoria' => 'required|integer|exists:categoria,idCategoria',
                'pulgadas' => 'nullable|string|max:255',
            ]);

            // Definir valores de checkboxes (si no están marcados, son 0)
            $repuesto = $request->has('repuesto') ? 1 : 0;
            $producto = $request->has('producto') ? 1 : 0;
            $heramientas = $request->has('heramientas') ? 1 : 0;
            $suministros = $request->has('suministros') ? 1 : 0;

            $dataModelo = [
                'nombre' => $validatedData['nombre'],
                'idMarca' => $validatedData['idMarca'],
                'idCategoria' => $validatedData['idCategoria'],
                'estado' => 1,
                'repuesto' => $repuesto,
                'producto' => $producto,
                'heramientas' => $heramientas,
                'suministros' => $suministros,
                'pulgadas' => $validatedData['pulgadas'],
            ];

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
                'pulgadas' => 'nullable|string|max:255',

            ]);

            // Obtener el modelo
            $modelo = Modelo::findOrFail($id);
            Log::info("Actualizando modelo con ID: $id");

            // Determinar qué checkboxes están marcados
            $validatedData['repuesto'] = $request->has('repuesto') ? 1 : 0;
            $validatedData['producto'] = $request->has('producto') ? 1 : 0;
            $validatedData['heramientas'] = $request->has('heramientas') ? 1 : 0;
            $validatedData['suministros'] = $request->has('suministros') ? 1 : 0;

            // Si no se marca el estado, se asegura que sea 0
            $validatedData['estado'] = $request->has('estado') ? 1 : 0;

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
        Log::info('Modelos obtenidos' . $modelos);

        // Generar el PDF
        $pdf = Pdf::loadView('almacen.productos.modelos.pdf.reporte-modelos', compact('modelos'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('reporte-modelos.pdf');
    }

    public function getAll(Request $request)
    {
        $query = Modelo::with(['marca', 'categoria']);

        $total = $query->count();

        // Filtro de búsqueda
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%$search%")
                    ->orWhereHas('marca', fn($m) => $m->where('nombre', 'like', "%$search%"))
                    ->orWhereHas('categoria', fn($c) => $c->where('nombre', 'like', "%$search%"));
            });
        }

        $filtered = $query->count();

        // Aplicar ordenamiento
        $columnIndex = $request->input('order.0.column');
        $columnDir = $request->input('order.0.dir');
        $columns = ['idModelo', 'nombre', 'marca', 'categoria', 'estado'];

        if (isset($columns[$columnIndex])) {
            $orderColumn = $columns[$columnIndex];

            if ($orderColumn === 'marca') {
                $query->whereHas('marca', function ($q) use ($columnDir) {
                    $q->orderBy('nombre', $columnDir);
                });
            } elseif ($orderColumn === 'categoria') {
                $query->whereHas('categoria', function ($q) use ($columnDir) {
                    $q->orderBy('nombre', $columnDir);
                });
            } else {
                $query->orderBy($orderColumn, $columnDir);
            }
        }

        // Paginación
        $modelos = $query
            ->skip($request->start)
            ->take($request->length)
            ->get();

        // Formato de respuesta
        $data = $modelos->map(function ($m) {
            return [
                'idModelo' => $m->idModelo,
                'nombre' => $m->nombre,
                'marca' => $m->marca->nombre ?? 'Sin Marca',
                'categoria' => $m->categoria->nombre ?? 'Sin Categoría',
                'estado' => $m->estado ? 'Activo' : 'Inactivo',
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
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
