<?php
// app/Http/Controllers/CronogramaController.php
namespace App\Http\Controllers\areacomercial;
use App\Http\Controllers\Controller;
use App\Models\CronogramaTarea;
use App\Models\CronogramaDependencia;
use App\Models\CronogramaConfiguracion;
use App\Models\CronogramaHistorico;
use App\Models\Seguimiento;
use App\Models\SeleccionarSeguimiento;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CronogramaController extends Controller
{
    // Obtener todos los datos del cronograma para un seguimiento
   public function getData($idSeguimiento): JsonResponse
{
    try {
        // Verificar que el seguimiento existe
        $seguimiento = Seguimiento::findOrFail($idSeguimiento);

        // Obtener idpersona desde query
        $idPersona = request()->query('idpersona');

        if (!$idPersona) {
            throw new \Exception('idpersona es requerido');
        }

        // Obtener tareas por seguimiento y persona
        $tareas = CronogramaTarea::where('idSeguimiento', $idSeguimiento)
            ->where('idpersona', $idPersona)
            ->orderBy('orden')
            ->get();

        // Obtener dependencias por seguimiento y persona
        $dependencias = CronogramaDependencia::where('idSeguimiento', $idSeguimiento)
            ->where('idpersona', $idPersona)
            ->get();

        // Obtener configuración por seguimiento y persona
        $configuracion = CronogramaConfiguracion::where('idSeguimiento', $idSeguimiento)
            ->where('idpersona', $idPersona)
            ->first();

        // Formatear datos para Gantt
        $data = [
            'data' => $tareas->map(fn($tarea) => $tarea->toGanttFormat()),
            'links' => $dependencias->map(fn($dep) => $dep->toGanttFormat()),
            'config' => $configuracion ? [
                'vista' => $configuracion->vista_actual,
                'zoom_inicio' => optional($configuracion->zoom_inicio)->format('Y-m-d'),
                'zoom_fin' => optional($configuracion->zoom_fin)->format('Y-m-d'),
                'extras' => $configuracion->configuracion_json,
            ] : []
        ];

        return response()->json($data);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    // Dentro de CronogramaController o como un servicio externo
public function createCronogramaDesdeTask(Task $task, $idSeguimiento)
{
    $idPersona = SeleccionarSeguimiento::where('idseguimiento', $idSeguimiento)->value('idpersona');

    if (!$idPersona) {
        throw new \Exception('No se encontró idpersona para este seguimiento.');
    }

    $tarea = new CronogramaTarea();
    $tarea->idSeguimiento = $idSeguimiento;
    $tarea->idpersona = $idPersona;
    $tarea->task_id = $task->id; // Puedes usar el ID de task como identificador
    $tarea->orden = CronogramaTarea::porSeguimiento($idSeguimiento)->max('orden') + 1;
    $tarea->parent_task_id = $parentId ?? '0';

    $tarea->nombre = $task->title;
    $tarea->descripcion = $task->description ?? '';
    $tarea->fecha_inicio = now(); // O alguna fecha estimada si tienes
    $tarea->fecha_fin = now()->addDays(1); // Duración estimada de 1 día
    $tarea->duracion = 1;
    $tarea->progreso = 0;
    $tarea->tipo = 'task';
    $tarea->abierto = true;

    $tarea->save();

    return $tarea;
}

    // Guardar/Actualizar una tarea
public function saveTask(Request $request, $idSeguimiento): JsonResponse
{
    Log::info('Datos recibidos en saveTask:', $request->all()); // Debug

    try {
        $validated = $request->validate([
            'id' => 'required|string',
            'text' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'progress' => 'numeric|between:0,1',
            'parent' => 'nullable|string',
            'type' => 'in:project,task,milestone',
            'description' => 'nullable|string'
        ]);

        DB::beginTransaction();

        $tarea = CronogramaTarea::where('idSeguimiento', $idSeguimiento)
            ->where('task_id', $validated['id'])
            ->first();

        $esNueva = !$tarea;

      $idPersona = SeleccionarSeguimiento::where('idseguimiento', $idSeguimiento)->value('idpersona');

if (!$idPersona) {
    throw new \Exception('No se encontró idpersona para este seguimiento.');
}

if (!$tarea) {
    $tarea = new CronogramaTarea();
    $tarea->idSeguimiento = $idSeguimiento;
    $tarea->idpersona = $idPersona; // 👈 guardar idpersona
    $tarea->task_id = $validated['id'];
    $tarea->orden = CronogramaTarea::porSeguimiento($idSeguimiento)->max('orden') + 1;
}

        // Actualizar campos
        $tarea->nombre = $validated['text'];
        $tarea->descripcion = $validated['description'] ?? '';
        $tarea->fecha_inicio = $validated['start_date'];
        $tarea->fecha_fin = $validated['end_date'];
        $tarea->progreso = $validated['progress'] ?? 0;
        $tarea->parent_task_id = $validated['parent'] ?? null;
        $tarea->tipo = $validated['type'] ?? 'task';
        $tarea->abierto = true;
        
        // Calcular duración
        $inicio = Carbon::parse($validated['start_date']);
        $fin = Carbon::parse($validated['end_date']);
        $tarea->duracion = $inicio->diffInDays($fin) + 1;

        $tarea->save();


        // ✅ Si el task_id es numérico, intenta actualizar la tarea relacionada
        if (is_numeric($tarea->task_id)) {
            DB::table('tasks')
                ->where('id', $tarea->task_id)
                ->update(['title' => $tarea->nombre, 'updated_at' => now()]);
        }
        DB::commit();

        Log::info('Tarea guardada exitosamente:', $tarea->toArray()); // Debug

        return response()->json([
            'success' => true,
            'task' => $tarea->toGanttFormat()
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al guardar tarea:', ['error' => $e->getMessage()]); // Debug
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

    // Eliminar una tarea
    public function deleteTask($idSeguimiento, $taskId): JsonResponse
    {
        try {
            DB::beginTransaction();

            $tarea = CronogramaTarea::where('idSeguimiento', $idSeguimiento)
                ->where('task_id', $taskId)
                ->first();

            if (!$tarea) {
                return response()->json(['error' => 'Tarea no encontrada'], 404);
            }

            $datosAnteriores = $tarea->toArray();

            // Eliminar dependencias relacionadas
            CronogramaDependencia::where('idSeguimiento', $idSeguimiento)
                ->where(function($query) use ($taskId) {
                    $query->where('source_task_id', $taskId)
                          ->orWhere('target_task_id', $taskId);
                })
                ->delete();

            // Reasignar tareas hijas al padre de la tarea eliminada
            $tareasHijas = CronogramaTarea::where('idSeguimiento', $idSeguimiento)
                ->where('parent_task_id', $taskId)
                ->get();

            foreach ($tareasHijas as $hija) {
                $hija->parent_task_id = $tarea->parent_task_id;
                $hija->save();
            }

            // Eliminar la tarea
            $tarea->delete();

            // Registrar en histórico
            CronogramaHistorico::create([
                'idSeguimiento' => $idSeguimiento,
                'task_id' => $taskId,
                'accion' => 'delete',
                'datos_anteriores' => $datosAnteriores,
                'datos_nuevos' => null,
                'usuario_id' => Auth::id()
            ]);

            DB::commit();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Guardar/Actualizar dependencia (enlace)
    public function saveLink(Request $request, $idSeguimiento): JsonResponse
    {
        try {
            $request->validate([
                'id' => 'required|string',
                'source' => 'required|string',
                'target' => 'required|string',
                'type' => 'in:0,1,2,3'
            ]);

             // Obtener idpersona
        $idPersona = SeleccionarSeguimiento::where('idseguimiento', $idSeguimiento)->value('idpersona');
        if (!$idPersona) {
            throw new \Exception('No se encontró idpersona para este seguimiento.');
        }


            DB::beginTransaction();

            // Verificar que las tareas existen
            $tareaOrigen = CronogramaTarea::where('idSeguimiento', $idSeguimiento)
                ->where('task_id', $request->source)
                ->first();

            $tareaDestino = CronogramaTarea::where('idSeguimiento', $idSeguimiento)
                ->where('task_id', $request->target)
                ->first();

            if (!$tareaOrigen || !$tareaDestino) {
                return response()->json(['error' => 'Una de las tareas no existe'], 400);
            }

            // Verificar que no se crea dependencia circular
            if ($this->verificarDependenciaCircular($idSeguimiento, $request->source, $request->target)) {
                return response()->json(['error' => 'Esta dependencia crearía un ciclo'], 400);
            }

             $dependencia = CronogramaDependencia::updateOrCreate(
            [
                'idSeguimiento' => $idSeguimiento,
                'link_id' => $request->id
            ],
            [
                'idpersona' => $idPersona, // ✅ Aquí se guarda
                'source_task_id' => $request->source,
                'target_task_id' => $request->target,
                'tipo_dependencia' => $request->type ?? '0'
            ]
        );

            DB::commit();

            return response()->json([
                'success' => true,
                'link' => $dependencia->toGanttFormat()
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Eliminar dependencia
    public function deleteLink($idSeguimiento, $linkId): JsonResponse
    {
        try {
            $dependencia = CronogramaDependencia::where('idSeguimiento', $idSeguimiento)
                ->where('link_id', $linkId)
                ->first();

            if (!$dependencia) {
                return response()->json(['error' => 'Dependencia no encontrada'], 404);
            }

            $dependencia->delete();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Guardar configuración del cronograma
    public function saveConfig(Request $request, $idSeguimiento): JsonResponse
    {
        try {
            $request->validate([
                'vista_actual' => 'nullable|string|in:Day,Week,Month,Quarter Day,Half Day',
                'zoom_inicio' => 'nullable|date',
                'zoom_fin' => 'nullable|date',
                'configuracion_json' => 'nullable|array'
            ]);

            CronogramaConfiguracion::updateOrCreate(
                ['idSeguimiento' => $idSeguimiento],
                [
                    'vista_actual' => $request->vista_actual ?? 'Day',
                    'zoom_inicio' => $request->zoom_inicio,
                    'zoom_fin' => $request->zoom_fin,
                    'configuracion_json' => $request->configuracion_json
                ]
            );

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Obtener histórico de cambios
    public function getHistorico($idSeguimiento): JsonResponse
    {
        try {
            $historico = CronogramaHistorico::where('idSeguimiento', $idSeguimiento)
                ->with('usuario:id,name')
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            return response()->json($historico);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Método auxiliar para verificar dependencias circulares
    private function verificarDependenciaCircular($idSeguimiento, $source, $target): bool
{
    // Si source y target son iguales, es claramente un ciclo
    if ($source === $target) {
        return true;
    }

    $dependencias = CronogramaDependencia::porSeguimiento($idSeguimiento)
        ->get(['source_task_id', 'target_task_id'])
        ->groupBy('source_task_id');

    $visitados = [];
    $enProceso = [];

    function detectarCiclo($nodo, $objetivo, $dependencias, &$visitados, &$enProceso) {
        if ($nodo === $objetivo) {
            return true;
        }

        if (isset($enProceso[$nodo])) {
            return true;
        }

        if (isset($visitados[$nodo])) {
            return false;
        }

        $visitados[$nodo] = true;
        $enProceso[$nodo] = true;

        if (isset($dependencias[$nodo])) {
            foreach ($dependencias[$nodo] as $dep) {
                if (detectarCiclo($dep['target_task_id'], $objetivo, $dependencias, $visitados, $enProceso)) {
                    return true;
                }
            }
        }

        unset($enProceso[$nodo]);
        return false;
    }

    return detectarCiclo($target, $source, $dependencias->toArray(), $visitados, $enProceso);
}

    // Importar datos desde formato externo (Excel, MS Project, etc.)
    public function importData(Request $request, $idSeguimiento): JsonResponse
    {
        try {
            $request->validate([
                'tasks' => 'required|array',
                'tasks.*.id' => 'required|string',
                'tasks.*.name' => 'required|string',
                'tasks.*.start' => 'required|date',
                'tasks.*.end' => 'required|date',
                'overwrite' => 'boolean'
            ]);

            DB::beginTransaction();

            // Si overwrite es true, limpiar datos existentes
            if ($request->overwrite) {
                CronogramaDependencia::where('idSeguimiento', $idSeguimiento)->delete();
                CronogramaTarea::where('idSeguimiento', $idSeguimiento)->delete();
            }

            $orden = 1;
            foreach ($request->tasks as $taskData) {
                CronogramaTarea::updateOrCreate(
                    [
                        'idSeguimiento' => $idSeguimiento,
                        'task_id' => $taskData['id']
                    ],
                    [
                        'nombre' => $taskData['name'],
                        'fecha_inicio' => $taskData['start'],
                        'fecha_fin' => $taskData['end'],
                        'progreso' => ($taskData['progress'] ?? 0) / 100,
                        'parent_task_id' => $taskData['parent'] ?? null,
                        'tipo' => $taskData['type'] ?? 'task',
                        'orden' => $orden++,
                        'duracion' => Carbon::parse($taskData['start'])->diffInDays(Carbon::parse($taskData['end'])) + 1
                    ]
                );
            }

            // Procesar dependencias si las hay
            if ($request->has('links')) {
                foreach ($request->links as $linkData) {
                    CronogramaDependencia::updateOrCreate(
                        [
                            'idSeguimiento' => $idSeguimiento,
                            'link_id' => $linkData['id']
                        ],
                        [
                            'source_task_id' => $linkData['source'],
                            'target_task_id' => $linkData['target'],
                            'tipo_dependencia' => $linkData['type'] ?? '0'
                        ]
                    );
                }
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Datos importados correctamente']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}