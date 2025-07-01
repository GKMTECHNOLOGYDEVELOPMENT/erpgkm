<?php

namespace App\Http\Controllers\almacen\subcategoria;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Models\Modelo;
use App\Models\Moneda;
use App\Models\Subcategoria;
use Illuminate\Support\Str;
use App\Models\Tipoarea;
use App\Models\Tipoarticulo;
use App\Models\Unidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Picqer\Barcode\BarcodeGeneratorPNG;

class SubcategoriaController extends Controller
{

    public function index(){

        return view('almacen.subcategoria.index');
    }


    public function create(){

        return view('almacen.subcategoria.create');
    }

    public function edit($id)
{
    $subcategoria = Subcategoria::findOrFail($id);
    return view('almacen.subcategoria.edit', compact('subcategoria'));
}




 public function store(Request $request)
{
    try {
        // Validar los datos
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:100|unique:subcategorias,nombre',
            'descripcion' => 'nullable|string',
        ]);

        // Crear la subcategoría
        Subcategoria::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Subcategoría guardada correctamente',
        ]);
    } catch (ValidationException $e) {
        // Captura de errores de validación
        return response()->json([
            'success' => false,
            'message' => 'Error de validación',
            'errors' => $e->errors(), // Devuelve array con errores por campo
        ], 422);
    } catch (\Exception $e) {
        // Otro tipo de errores
        return response()->json([
            'success' => false,
            'message' => 'Ocurrió un error al guardar la subcategoría.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


public function getAll(Request $request)
{
    $draw = (int)$request->input('draw');
    $start = (int)$request->input('start', 0);
    $length = (int)$request->input('length', 10);
    $search = $request->input('search.value', '');

    $query = Subcategoria::query();

    $total = $query->count();

    if ($search) {
        $query->where('nombre', 'like', "%{$search}%")
              ->orWhere('descripcion', 'like', "%{$search}%");
    }

    $filtered = $query->count();

    $subcats = $query->skip($start)->take($length)->get();

    $data = $subcats->map(fn($s) => [
        'id' => $s->id,
        'nombre' => $s->nombre,
        'descripcion' => Str::limit($s->descripcion, 50),
    ]);

    return response()->json([
        'draw' => $draw,
        'recordsTotal' => $total,
        'recordsFiltered' => $filtered,
        'data' => $data,
    ]);
}


public function update(Request $request, $id)
{
    try {
        $validatedData = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('subcategorias')->ignore($id),
            ],
            'descripcion' => 'nullable|string',
        ]);

        $subcategoria = Subcategoria::findOrFail($id);
        $subcategoria->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Subcategoría actualizada correctamente',
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Ocurrió un error al actualizar la subcategoría.',
            'error' => $e->getMessage(),
        ], 500);
    }
}



// En SubcategoriaController o una ruta API aparte
public function validarNombre(Request $request)
{
    $nombre = $request->query('nombre');
    $id = $request->query('id');

    $query = \App\Models\Subcategoria::where('nombre', $nombre);

    // Si viene un ID (modo edición), lo excluimos
    if ($id) {
        $query->where('id', '!=', $id);
    }

    $exists = $query->exists();

    return response()->json(['exists' => $exists]);
}




    public function destroy($id)
    {
        try {
            $subcategoria = Subcategoria::findOrFail($id);
            $subcategoria->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sub Categoria eliminado con éxito',
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar el Sub Categoria: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al eliminar el Sub Categoria.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }





}