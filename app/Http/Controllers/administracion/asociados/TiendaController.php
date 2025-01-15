<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Http\Requests\TiendasRequest;
use App\Models\Cliente;
use App\Models\Tienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TiendaController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.asociados.tienda.index', compact('clientes')); 
    }
    

    // En TiendaController.php
public function store(Request $request)
{
    try {
        // Log para ver si se recibe la solicitud
        Log::info('Solicitud de Tienda recibida', ['data' => $request->all()]);

        // Validar y obtener los datos de la tienda
        $dataTienda = [
            'nombre' => $request->nombre, // Solo 'nombre' se almacenará
        ];

        // Guardar la tienda en la base de datos
        Log::info('Insertando tienda:', $dataTienda);
        Tienda::insert($dataTienda);

        // Obtener la última tienda insertada para obtener su ID
        $data = Tienda::latest('idTienda')->first();
        $idTienda = $data->idTienda;
        Log::info('Tienda insertada con ID: ' . $idTienda);

        // Responder con JSON
        return response()->json([
            'success' => true,
            'message' => 'Tienda agregada correctamente',
            'data' => $dataTienda,
        ]);
    } catch (\Exception $e) {
        // Log para capturar el error
        Log::error('Error al guardar la tienda: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Ocurrió un error al guardar la tienda.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function edit($id)
{
    // Busca la tienda por su id
    $tienda = Tienda::findOrFail($id); 

    // Retorna la vista de edición, pasando la tienda encontrada
    return view('administracion.asociados.tienda.edit', compact('tienda'));
}



 // Método para actualizar una tienda
 public function update(Request $request, $id)
 {
     // Validar que el nombre de la tienda sea una cadena no vacía
     $request->validate([
         'nombre' => 'required|string|max:255',
     ]);

     // Buscar la tienda por su ID
     $tienda = Tienda::find($id);

     if (!$tienda) {
         // Si no se encuentra la tienda, retornar un error 404
         return response()->json(['message' => 'Tienda no encontrada'], 404);
     }

     // Actualizar el nombre de la tienda
     $tienda->nombre = $request->nombre;
     $tienda->save();

     // Retornar la tienda actualizada en formato JSON
     return response()->json($tienda);
 }



public function destroy($id)
{
    $tienda = Tienda::find($id);

    if (!$tienda) {
        return response()->json(['error' => 'Cliente no encontrado'], 404);
    }
    // Eliminar el cliente
    $tienda->delete();

    // Responder con el estado de la eliminación
    return response()->json([
        'message' => 'Tienda eliminada con éxito'
       
    ], 200);
}


    public function getAll()
    {
        // Obtén todos los datos de la tabla tienda
        $tiendas = Tienda::all();

        // Procesa los datos
        $tiendasData = $tiendas->map(function ($tienda) {
            return [
                'idTienda' => $tienda->idTienda,
                'nombre' => $tienda->nombre,
            ];
        });

        // Retorna los datos en formato JSON
        return response()->json($tiendasData);
    }

    public function checkNombreTienda (Request $request)
    {
        $nombre = $request->input('nombre');
        $exists = Tienda::where('nombre', $nombre)->exists();

        return response()->json(['unique' => !$exists]);
    }


}