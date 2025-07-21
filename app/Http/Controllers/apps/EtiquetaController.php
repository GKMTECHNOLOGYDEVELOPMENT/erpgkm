<?php

// app/Http/Controllers/EtiquetaController.php
namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;

use App\Models\Etiqueta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EtiquetaController extends Controller
{
    public function index()
    {
        $etiquetas = Etiqueta::all();
        return response()->json($etiquetas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:etiquetas,nombre',
            'color' => 'required|string|max:255',
            'icono' => 'nullable|string|max:255',
        ]);

        $etiqueta = Etiqueta::create([
            'nombre' => $request->nombre,
            'color' => $request->color,
            'icono' => $request->icono,
            'user_id' => Auth::id(),
        ]);

        return response()->json($etiqueta, 201);
    }

    public function update(Request $request, $id)
    {
        $etiqueta = Etiqueta::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'nombre' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'icono' => 'nullable|string|max:255',
        ]);

        $etiqueta->update([
            'nombre' => $request->nombre,
            'color' => $request->color,
            'icono' => $request->icono,
        ]);

        return response()->json($etiqueta);
    }

    public function destroy($id)
    {
      $etiqueta = Etiqueta::find($id);

if (!$etiqueta) {
    return response()->json(['message' => 'Etiqueta no encontrada.'], 404);
}

if ($etiqueta->user_id !== Auth::id()) {
    return response()->json(['message' => 'No tienes permiso para eliminar esta etiqueta.'], 403);
}


        $etiqueta->delete();

        return response()->json(null, 204);
    }
}