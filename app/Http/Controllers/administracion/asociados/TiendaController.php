<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Models\Tienda;
use Illuminate\Http\Request;

class TiendaController extends Controller
{
    public function index()
    {
        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.asociados.tienda'); 
    }
    // Crear una nueva Tienda
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nombre' => 'required|string|max:255',
    //     ]);

    //     $tienda = new Tienda();
    //     $tienda->nombre = $request->nombre;
    //     $tienda->save();

    //     return response()->json(['message' => 'Tienda creada correctamente'], 201);
    // }

    // Actualizar una Tienda
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'nombre' => 'required|string|max:255',
    //     ]);

    //     $tienda = Tienda::findOrFail($id);
    //     $tienda->nombre = $request->nombre;
    //     $tienda->save();

    //     return response()->json(['message' => 'Tienda actualizada correctamente']);
    // }

    // Eliminar una Tienda
    // public function destroy($id)
    // {
    //     $tienda = Tienda::findOrFail($id);
    //     $tienda->delete();

    //     return response()->json(['message' => 'Tienda eliminada correctamente']);
    // }

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


}
