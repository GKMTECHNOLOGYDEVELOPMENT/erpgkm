<?php
// app/Http/Controllers/ActividadController.php
namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Actividad;
use App\Models\Etiqueta;
use App\Models\Invitado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActividadController extends Controller
{
public function index()
{
    $actividades = Actividad::with(['invitados'])->get();
    
    return response()->json($actividades->map(function ($actividad) {
        return [
            'actividad_id' => $actividad->actividad_id,
            'titulo' => $actividad->titulo,
            'etiqueta' => $actividad->etiqueta,
            'fechainicio' => $actividad->fechainicio,
            'fechafin' => $actividad->fechafin,
            'enlaceevento' => $actividad->enlaceevento,
            'ubicacion' => $actividad->ubicacion,
            'descripcion' => $actividad->descripcion,
            'invitados' => $actividad->invitados->map(function ($invitado) {
                return [
                    'id_usuarios' => $invitado->id_usuarios,
                    // Agrega otros campos si los necesitas
                ];
            })->toArray()
        ];
    }));
}



   public function store(Request $request)
{
    $request->validate([
        'titulo' => 'required',
        'fechainicio' => 'required|date',
        'fechafin' => 'required|date|after_or_equal:fechainicio',
        'etiqueta' => 'required',
    ]);

    $actividad = Actividad::create([
        'titulo' => $request->titulo,
        'etiqueta' => $request->etiqueta,
        'fechainicio' => $request->fechainicio,
        'fechafin' => $request->fechafin,
        'enlaceevento' => $request->enlaceevento,
        'ubicacion' => $request->ubicacion,
        'descripcion' => $request->descripcion,
        'user_id' => Auth::id(),
    ]);

    // Guardar invitados
    if ($request->has('invitados')) {
        foreach ($request->invitados as $invitadoId) {
            Invitado::create([
                'actividad_id' => $actividad->actividad_id,
                'id_usuarios' => $invitadoId
            ]);
        }
    }

    return response()->json([
        'actividad_id' => $actividad->actividad_id,
        'titulo' => $actividad->titulo,
        'etiqueta' => $actividad->etiqueta,
        'fechainicio' => $actividad->fechainicio,
        'fechafin' => $actividad->fechafin,
        'enlaceevento' => $actividad->enlaceevento,
        'ubicacion' => $actividad->ubicacion,
        'descripcion' => $actividad->descripcion,
    ], 201);
}

   public function update(Request $request, $id)
{
    $actividad = Actividad::where('actividad_id', $id)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    $request->validate([
        'titulo' => 'required',
        'fechainicio' => 'required|date',
        'fechafin' => 'required|date|after_or_equal:fechainicio',
        'etiqueta' => 'required',
    ]);

    $actividad->update([
        'titulo' => $request->titulo,
        'etiqueta' => $request->etiqueta,
        'fechainicio' => $request->fechainicio,
        'fechafin' => $request->fechafin,
        'enlaceevento' => $request->enlaceevento,
        'ubicacion' => $request->ubicacion,
        'descripcion' => $request->descripcion,
    ]);

    // Actualizar invitados
    if ($request->has('invitados')) {
        // Eliminar invitados existentes
        Invitado::where('actividad_id', $actividad->actividad_id)->delete();
        
        // Agregar nuevos invitados
        foreach ($request->invitados as $invitadoId) {
            Invitado::create([
                'actividad_id' => $actividad->actividad_id,
                'id_usuarios' => $invitadoId
            ]);
        }
    }

    return response()->json([
        'actividad_id' => $actividad->actividad_id,
        'titulo' => $actividad->titulo,
        'etiqueta' => $actividad->etiqueta,
        'fechainicio' => $actividad->fechainicio,
        'fechafin' => $actividad->fechafin,
        'enlaceevento' => $actividad->enlaceevento,
        'ubicacion' => $actividad->ubicacion,
        'descripcion' => $actividad->descripcion,
    ]);
}
    public function destroy($id)
    {
        $actividad = Actividad::where('actividad_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Eliminar invitados primero
        Invitado::where('actividad_id', $actividad->actividad_id)->delete();
        
        $actividad->delete();

        return response()->json(null, 204);
    }
}