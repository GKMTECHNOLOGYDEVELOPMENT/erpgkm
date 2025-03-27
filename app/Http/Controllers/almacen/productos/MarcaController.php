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
        // Obtener la foto de la marca como una cadena base64
        $foto = $marca->foto ? 'data:image/jpeg;base64,' . base64_encode($marca->foto) : null;

        return [
            'idMarca' => $marca->idMarca,
            'nombre' => $marca->nombre,
            'estado' => $marca->estado ? 'Activo' : 'Inactivo',
            'foto' => $foto, // Añadir la foto en base64
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
        // Obtenemos las marcas activas
        $marcas = Marca::where('estado', 1)->get();
    
        // Ocultamos el campo 'foto'
        $marcas->makeHidden('foto');
    
        return response()->json($marcas); // Devolvemos los datos en formato JSON
    }


    // Dentro del controlador

public function getMarcasByClienteGeneral($idClienteGeneral)
{
    // Obtener las marcas relacionadas con el cliente general
    $marcas = MarcaClienteGeneral::where('idClienteGeneral', $idClienteGeneral)
                                  ->with('marca') // Asumiendo que tienes una relación en el modelo
                                  ->get()
                                  ->pluck('marca'); // Opción para obtener solo las marcas, dependiendo de tu estructura

    return response()->json($marcas);
}

    
}
