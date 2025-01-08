<?php

namespace App\Http\Controllers\administracion\asociados;

use App\Http\Controllers\Controller;
use App\Models\Cast;
use Illuminate\Http\Request;

class CastController extends Controller
{
    public function index()
    {
        // Llamar la vista ubicada en administracion/usuarios.blade.php
        return view('administracion.asociados.cast'); 
    }

    // Crear un nuevo Cast
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'direccion' => 'nullable|string',
            'ruc' => 'required|string|max:50',
            'provincia' => 'nullable|string|max:50',
        ]);

        $cast = new Cast();
        $cast->nombre = $request->nombre;
        $cast->telefono = $request->telefono;
        $cast->email = $request->email;
        $cast->direccion = $request->direccion;
        $cast->ruc = $request->ruc;
        $cast->provincia = $request->provincia;
        $cast->save();

        return response()->json(['message' => 'Cast creado correctamente'], 201);
    }

      // Actualizar un Cast
      public function update(Request $request, $id)
      {
          $request->validate([
              'nombre' => 'required|string|max:255',
              'telefono' => 'required|string|max:20',
              'email' => 'required|email|max:255',
              'direccion' => 'nullable|string',
              'ruc' => 'required|string|max:50',
              'provincia' => 'nullable|string|max:50',
          ]);
  
          $cast = Cast::findOrFail($id);
          $cast->nombre = $request->nombre;
          $cast->telefono = $request->telefono;
          $cast->email = $request->email;
          $cast->direccion = $request->direccion;
          $cast->ruc = $request->ruc;
          $cast->provincia = $request->provincia;
          $cast->save();
  
          return response()->json(['message' => 'Cast actualizado correctamente']);
      }
  
      // Eliminar un Cast
      public function destroy($id)
      {
          $cast = Cast::findOrFail($id);
          $cast->delete();
  
          return response()->json(['message' => 'Cast eliminado correctamente']);
      }

      public function getAll()
    {
        // Obtén todos los datos de la tabla cast
        $casts = Cast::all();

        // Retorna los datos en formato JSON
        return response()->json($casts);
    }
}
