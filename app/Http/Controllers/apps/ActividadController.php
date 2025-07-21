<?php
// app/Http/Controllers/ActividadController.php
namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Mail\ActividadNotification;
use App\Models\Actividad;
use App\Models\Etiqueta;
use App\Models\Invitado;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

        $usuarioId = Auth::id();

    // 1️⃣ VALIDAR CRUCE DE HORARIO DEL USUARIO AUTENTICADO
    $conflictoUsuario = DB::table('actividades')
        ->where('user_id', $usuarioId)
        ->where(function ($query) use ($request) {
            $query->whereBetween('fechainicio', [$request->fechainicio, $request->fechafin])
                  ->orWhereBetween('fechafin', [$request->fechainicio, $request->fechafin])
                  ->orWhere(function ($q) use ($request) {
                      $q->where('fechainicio', '<=', $request->fechainicio)
                        ->where('fechafin', '>=', $request->fechafin);
                  });
        })
        ->exists();

    if ($conflictoUsuario) {
        return response()->json([
            'message' => 'Ya tienes una actividad en ese rango de fechas y horas.'
        ], 422);
    }

    // 2️⃣ VALIDAR CRUCE DE HORARIO PARA INVITADOS (si existen)
    if ($request->has('invitados') && is_array($request->invitados)) {
        $invitadoEnConflicto = DB::table('invitados')
            ->join('actividades', 'invitados.actividad_id', '=', 'actividades.actividad_id')
            ->whereIn('invitados.id_usuarios', $request->invitados)
            ->where(function ($query) use ($request) {
                $query->whereBetween('actividades.fechainicio', [$request->fechainicio, $request->fechafin])
                      ->orWhereBetween('actividades.fechafin', [$request->fechainicio, $request->fechafin])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('actividades.fechainicio', '<=', $request->fechainicio)
                            ->where('actividades.fechafin', '>=', $request->fechafin);
                      });
            })
            ->select('invitados.id_usuarios')
            ->first();

        if ($invitadoEnConflicto) {
            $usuario = \App\Models\Usuario::find($invitadoEnConflicto->id_usuarios);
            return response()->json([
                'message' => 'El usuario ' . ($usuario->Nombre ?? 'invitado') . ' ya tiene una actividad en ese horario.'
            ], 422);
        }
    }



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

        // Notificar al creador
        $creador = Usuario::find(Auth::id());
        Mail::to($creador->correo)->send(
            new ActividadNotification($actividad, 'creacion', $creador)
        );

        // Guardar invitados y notificarles
        if ($request->has('invitados')) {
            foreach ($request->invitados as $invitadoId) {
                Invitado::create([
                    'actividad_id' => $actividad->actividad_id,
                    'id_usuarios' => $invitadoId
                ]);

                $invitado = Usuario::find($invitadoId);
                if ($invitado) {
                    Mail::to($invitado->correo)->send(
                        new ActividadNotification($actividad, 'creacion', $invitado)
                    );
                }
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
     $actividad = Actividad::find($id);

if (!$actividad) {
    return response()->json(['message' => 'Actividad no encontrada.'], 404);
}

if ($actividad->user_id !== Auth::id()) {
    return response()->json(['message' => 'No tienes permiso para actualizar esta actividad.'], 403);
}

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

        // Notificar al creador
        $creador = Usuario::find(Auth::id());
        Mail::to($creador->correo)->send(
            new ActividadNotification($actividad, 'actualizacion', $creador)
        );

        // Obtener invitados anteriores para comparar
        $invitadosAnteriores = Invitado::where('actividad_id', $actividad->actividad_id)
            ->pluck('id_usuarios')
            ->toArray();

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

                // Notificar solo a los nuevos invitados
                if (!in_array($invitadoId, $invitadosAnteriores)) {
                    $invitado = Usuario::find($invitadoId);
                    if ($invitado) {
                        Mail::to($invitado->correo)->send(
                            new ActividadNotification($actividad, 'creacion', $invitado)
                        );
                    }
                }
            }

            // Notificar a todos los invitados sobre la actualización
            foreach ($request->invitados as $invitadoId) {
                $invitado = Usuario::find($invitadoId);
                if ($invitado) {
                    Mail::to($invitado->correo)->send(
                        new ActividadNotification($actividad, 'actualizacion', $invitado)
                    );
                }
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
    $actividad = Actividad::where('actividad_id', $id)->first();

    if (!$actividad) {
        return response()->json(['message' => 'Actividad no encontrada.'], 404);
    }

    if ($actividad->user_id !== Auth::id()) {
        return response()->json(['message' => 'No tienes permiso para eliminar esta actividad.'], 403);
    }

    // Obtener invitados antes de eliminar
    $invitados = Invitado::where('actividad_id', $actividad->actividad_id)
        ->with('usuario')
        ->get();

    // Notificar al creador
    $creador = Usuario::find(Auth::id());
    Mail::to($creador->correo)->send(
        new ActividadNotification($actividad, 'eliminacion', $creador)
    );

    // Notificar a los invitados
    foreach ($invitados as $invitado) {
        if ($invitado->usuario) {
            Mail::to($invitado->usuario->correo)->send(
                new ActividadNotification($actividad, 'eliminacion', $invitado->usuario)
            );
        }
    }

    // Eliminar invitados primero
    Invitado::where('actividad_id', $actividad->actividad_id)->delete();

    $actividad->delete();

    return response()->json(null, 204);
}

}