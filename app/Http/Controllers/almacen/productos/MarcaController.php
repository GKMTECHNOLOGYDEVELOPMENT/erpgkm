<?php

namespace App\Http\Controllers\almacen\productos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Marca;
use App\Models\MarcaClienteGeneral;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

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
                'nombre' => 'required|string|max:255|unique:marca,nombre',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación para la foto
            ]);

            // Datos básicos de la marca con estado siempre en 1
            $dataMarca = [
                'nombre' => $validatedData['nombre'],
                'estado' => 1, // Estado siempre será 1
            ];

            // Guardar la marca en la base de datos (sin la foto por ahora)
            Log::info('Insertando marca:', $dataMarca);
            $marca = Marca::create($dataMarca);

            // Si se subió una foto
            if ($request->hasFile('foto')) {
                // Obtener el contenido binario de la imagen
                $binaryImage = file_get_contents($request->file('foto')->getRealPath());

                // Actualizar la marca con la imagen en formato binario
                DB::table('marca')
                    ->where('idMarca', $marca->idMarca)
                    ->update(['foto' => $binaryImage]);

                Log::info('Imagen guardada como longblob en la base de datos.');
            }

            // Responder con JSON
            return response()->json([
                'success' => true,
                'message' => 'Marca agregada correctamente',
                'data' => $marca,
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
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'estado' => 'nullable|boolean',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // validar imagen
            ]);

            $marca = Marca::findOrFail($id);
            Log::info("Actualizando marca con ID: $id");

            $marca->update([
                'nombre' => $validatedData['nombre'],
                'estado' => $request->has('estado') ? 1 : 0,
            ]);

            if ($request->hasFile('foto')) {
                $imagenBinaria = file_get_contents($request->file('foto')->getRealPath());

                \DB::table('marca')
                    ->where('idMarca', $marca->idMarca)
                    ->update(['foto' => $imagenBinaria]);
            }

            return redirect()->route('marcas.index')->with('success', 'Marca actualizada exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar la marca: ' . $e->getMessage());
            return redirect()->route('marcas.index')->with('error', 'Ocurrió un error al actualizar la marca.');
        }
    }
    public function getMarcasPorCliente($idClienteGeneral)
    {
        $marcas = \App\Models\Marca::select('marca.idMarca', 'marca.nombre')
            ->join('marca_clientegeneral as mcg', 'marca.idMarca', '=', 'mcg.idMarca')
            ->where('mcg.idClienteGeneral', $idClienteGeneral)
            ->groupBy('marca.idMarca', 'marca.nombre')
            ->orderBy('marca.nombre')
            ->get();

        return response()->json($marcas);
    }


    public function destroy($id)
    {
        try {
            $marca = Marca::findOrFail($id);
    
            $marca->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Marca eliminada con éxito',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar la marca: ' . $e->getMessage());
    
            // Aquí detectamos si es error de clave foránea
            if (str_contains($e->getMessage(), 'Integrity constraint violation')) {
                return response()->json([
                    'success' => false,
                    'error' => 'Marca asociada a uno o más registros.',
                ], 500);
            }
    
            return response()->json([
                'success' => false,
                'error' => 'Ocurrió un error al eliminar la marca.',
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

    public function getAll(Request $request)
    {
        $query = Marca::query();
    
        $total = $query->count();
    
        // Filtro global
        if ($search = $request->input('search.value')) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%$search%");
            });
        }
    
        $filtered = $query->count();
    
        $marcas = $query
            ->skip($request->start ?? 0)
            ->take($request->length ?? 10)
            ->get();
    
        $data = $marcas->map(function ($marca) {
            return [
                'idMarca' => $marca->idMarca,
                'nombre' => $marca->nombre,
                'estado' => $marca->estado ? 'Activo' : 'Inactivo',
                'foto' => $marca->foto ? 'data:image/jpeg;base64,' . base64_encode($marca->foto) : null,
            ];
        });
    
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $data,
        ]);
    }
    

    public function checkNombre(Request $request)
    {
        $nombre = $request->input('nombre');
        $exists = Marca::where('nombre', $nombre)->exists();

        return response()->json(['unique' => !$exists]);
    }


    public function checkMarcas(Request $request)
    {
        // Obtenemos las marcas activas
        $marcas = Marca::where('estado', 1)->get();

        // Ocultamos el campo 'foto'
        $marcas->makeHidden('foto');

        return response()->json($marcas); // Devolvemos los datos en formato JSON
    }


    public function getMarcasByClienteGeneral($idClienteGeneral)
    {
        // Obtener las marcas relacionadas con el cliente general
        $marcas = MarcaClienteGeneral::where('idClienteGeneral', $idClienteGeneral)
            ->with(['marca'])  // Cargar la relación 'marca' normalmente
            ->get()
            ->map(function ($marcaCliente) {
                // Aquí accedemos al modelo 'marca' y ocultamos el campo 'foto'
                $marcaCliente->marca->makeHidden('foto');
                return $marcaCliente->marca;
            });

        return response()->json($marcas);
    }
}
