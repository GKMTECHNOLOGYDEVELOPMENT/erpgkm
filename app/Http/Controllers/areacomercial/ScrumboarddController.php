<?php

namespace App\Http\Controllers\areacomercial;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SeleccionarSeguimiento;
use App\Models\Task;
// Agregar los nuevos modelos
use App\Models\TaskCotizacion;
use App\Models\TaskReunion;
use App\Models\TaskLevantamiento;
use App\Models\TaskGanado;
use App\Models\TaskObservado;
use App\Models\TaskRechazado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ScrumboarddController extends Controller
{
    // Obtener todos los proyectos con sus tareas y datos específicos
    public function index(Request $request)
    {
        $idSeguimiento = $request->query('seguimiento');
        $idPersona = $request->query('idpersona');

        $query = Project::with(['tasks' => function($query) use ($idSeguimiento, $idPersona) {
            if ($idSeguimiento) {
                $query->where('idseguimiento', $idSeguimiento);
            }
            if ($idPersona) {
                $query->where('idpersona', $idPersona);
            }
            
            // Cargar relaciones específicas
            $query->with(['cotizaciones', 'reuniones', 'levantamientos', 'ganados', 'observados', 'rechazados']);
        }]);

        // Filtrar proyectos también por ambos IDs
        if ($idSeguimiento) {
            $query->where('idseguimiento', $idSeguimiento);
        }

        if ($idPersona) {
            $query->where('idpersona', $idPersona);
        }

        $projects = $query->get();

        // Decodificar tags en tareas
        $projects->each(function ($project) {
            $project->tasks->each(function ($task) {
                $task->tags = $task->tags ? json_decode($task->tags, true) : [];
            });
        });

        return response()->json($projects);
    }

    // Guardar un nuevo proyecto
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'idseguimiento' => 'required|integer'
        ]);

        // Buscar el registro en seleccionarseguimiento
        $seleccion = SeleccionarSeguimiento::where('idseguimiento', $request->idseguimiento)->first();

        // Verificamos si existe
        if (!$seleccion) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró una selección para el seguimiento proporcionado.'
            ], 404);
        }

        // Crear el proyecto incluyendo el idpersona
        $project = Project::create([
            'title' => $request->title,
            'idseguimiento' => $request->idseguimiento,
            'idpersona' => $seleccion->idpersona
        ]);

        return response()->json([
            'success' => true,
            'project' => $project->load('tasks')
        ]);
    }

    // Guardar una cotización individual
public function storeCotizacion(Request $request)
{
    $request->validate([
        'task_id' => 'required|exists:tasks,id',
        'codigo_cotizacion' => 'nullable|string|max:255',
        'fecha_cotizacion' => 'nullable|date',
        'detalle_producto' => 'nullable|string',
        'total_cotizacion' => 'nullable|numeric',
        // ... validar otros campos ...
    ]);

    $cotizacion = TaskCotizacion::create([
        'task_id' => $request->task_id,
        'codigo_cotizacion' => $request->codigo_cotizacion,
        'fecha_cotizacion' => $request->fecha_cotizacion,
        'detalle_producto' => $request->detalle_producto,
        'condiciones_comerciales' => $request->condiciones_comerciales,
        'total_cotizacion' => $request->total_cotizacion,
        'validez_cotizacion' => $request->validez_cotizacion,
        'responsable_cotizacion' => $request->responsable_cotizacion,
        'observaciones' => $request->observaciones
    ]);

    return response()->json([
        'success' => true,
        'cotizacion' => $cotizacion
    ]);
}

// Eliminar una cotización
public function deleteCotizacion($id)
{
    try {
        $cotizacion = TaskCotizacion::findOrFail($id);
        $cotizacion->delete();
        
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar la cotización'
        ], 500);
    }
}

    // Actualizar un proyecto
    public function update(Request $request, Project $project)
    {
        $request->validate(['title' => 'required|string|max:255']);
        
        $project->update($request->only('title'));
        
        return response()->json([
            'success' => true,
            'project' => $project->load('tasks')
        ]);
    }

    // Eliminar un proyecto
    public function destroy(Project $project)
    {
        $project->tasks()->delete();
        $project->delete();
        
        return response()->json(['success' => true]);
    }

    // Limpiar todas las tareas de un proyecto
    public function clearTasks(Project $project)
    {
        $project->tasks()->delete();
        
        return response()->json(['success' => true]);
    }

    // Guardar una nueva tarea con datos específicos
    public function storeTask(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'image_url' => 'nullable|string',
            'idseguimiento' => 'required|integer'
        ]);

        // Buscar idpersona desde seleccionarseguimiento
        $seleccion = SeleccionarSeguimiento::where('idseguimiento', $request->idseguimiento)->first();

        if (!$seleccion) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró una selección para el seguimiento proporcionado.'
            ], 404);
        }

        $taskData = [
            'project_id' => $request->project_id,
            'title' => $request->title,
            'description' => $request->description,
            'tags' => $request->tags ? json_encode(explode(',', $request->tags)) : null,
            'date' => now(),
            'idseguimiento' => $request->idseguimiento,
            'idpersona' => $seleccion->idpersona
        ];

        // Manejo de imagen
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tasks', 'public');
            $taskData['image'] = Storage::url($path);
        } elseif ($request->filled('image_url')) {
            $taskData['image'] = $request->image_url;
        }

        // Crear la tarea principal
        $task = Task::create($taskData);

        // Guardar datos específicos según el tipo de proyecto
        $this->saveSpecificTaskData($request, $task);

        return response()->json([
            'success' => true,
            'task' => $task->load(['cotizaciones', 'reuniones', 'levantamientos', 'ganados', 'observados', 'rechazados'])
        ]);
    }

    // Actualizar una tarea con datos específicos
    public function updateTask(Request $request, Task $task)
    {
        Log::info('Solicitud recibida para actualizar tarea', [
            'datos_recibidos' => $request->all()
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tags' => 'nullable|string'
        ]);

        $taskData = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'tags' => !empty($validated['tags']) ? json_encode(array_map('trim', explode(',', $validated['tags']))) : null
        ];

        $task->update($taskData);

        // Actualizar datos específicos
        $this->saveSpecificTaskData($request, $task);

        Log::info('Tarea actualizada exitosamente');

        return response()->json([
            'success' => true,
            'task' => $task->fresh()->load(['cotizaciones', 'reuniones', 'levantamientos', 'ganados', 'observados', 'rechazados'])
        ]);
    }

    // Eliminar una tarea
    public function destroyTask(Task $task)
    {
        // Eliminar imagen si existe
        if ($task->image) {
            $oldImage = str_replace('/storage', '', parse_url($task->image, PHP_URL_PATH));
            Storage::disk('public')->delete($oldImage);
        }
        
        $task->delete();
        
        return response()->json(['success' => true]);
    }

    // Mover tarea entre proyectos
    public function moveTask(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'task_id' => 'required|exists:tasks,id',
                'new_project_id' => 'required|exists:projects,id|different:current_project_id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $task = Task::find($request->task_id);
            
            if ($task->project_id == $request->new_project_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'La tarea ya está en este proyecto'
                ], 400);
            }

            $oldProjectId = $task->project_id;
            $task->project_id = $request->new_project_id;
            $task->save();

            return response()->json([
                'success' => true,
                'message' => 'Task moved successfully',
                'data' => [
                    'task_id' => $task->id,
                    'old_project_id' => $oldProjectId,
                    'new_project_id' => $task->project_id
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error moving task',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Método auxiliar para guardar datos específicos de cada tipo de tarea
    private function saveSpecificTaskData(Request $request, Task $task)
    {
        $projectTitle = $task->project->title ?? '';

        // Cotización
        if (stripos($projectTitle, 'cotizaciones') !== false) {
            $this->saveCotizacionData($request, $task);
        }
        // Reunión
        elseif (stripos($projectTitle, 'reuniones') !== false) {
            $this->saveReunionData($request, $task);
        }
        // Levantamiento
        elseif (stripos($projectTitle, 'levantamientos') !== false) {
            $this->saveLevantamientoData($request, $task);
        }
        // Ganado
        elseif (stripos($projectTitle, 'ganados') !== false) {
            $this->saveGanadoData($request, $task);
        }
        // Observado
        elseif (stripos($projectTitle, 'observados') !== false) {
            $this->saveObservadoData($request, $task);
        }
        // Rechazado
        elseif (stripos($projectTitle, 'rechazados') !== false) {
            $this->saveRechazadoData($request, $task);
        }
    }

    // Métodos específicos para cada tipo de dato
private function saveCotizacionData(Request $request, Task $task)
{
    // Verificar que haya al menos un campo lleno para crear la cotización
    $hasData = $request->filled('codigoCotizacion') || 
               $request->filled('fechaCotizacion') ||
               $request->filled('detalleproducto') ||
               $request->filled('totalcotizacion');
    
    if ($hasData) {
        $data = [
            'task_id' => $task->id,
            'codigo_cotizacion' => $request->codigoCotizacion,
            'fecha_cotizacion' => $request->fechaCotizacion,
            'detalle_producto' => $request->detalleproducto,
            'condiciones_comerciales' => $request->condicionescomerciales,
            'total_cotizacion' => $request->totalcotizacion,
            'validez_cotizacion' => $request->validezcotizacion,
            'responsable_cotizacion' => $request->responsablecotizacion,
            'observaciones' => $request->observacionescotizacion
        ];

        // SIEMPRE crear nuevo registro (múltiples cotizaciones)
        TaskCotizacion::create($data);
    }
}

// Obtener cotizaciones de una tarea (si no lo tienes)
public function getCotizaciones($taskId)
{
    try {
        $task = Task::findOrFail($taskId);
        $cotizaciones = $task->cotizaciones;
        
        return response()->json([
            'success' => true,
            'cotizaciones' => $cotizaciones
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener cotizaciones'
        ], 500);
    }
}

// Actualizar una cotización existente
public function updateCotizacion(Request $request, $id)
{
    try {
        $cotizacion = TaskCotizacion::findOrFail($id);
        
        $request->validate([
            'codigo_cotizacion' => 'nullable|string|max:255',
            'fecha_cotizacion' => 'nullable|date',
            'detalle_producto' => 'nullable|string',
            'total_cotizacion' => 'nullable|numeric',
            'validez_cotizacion' => 'nullable|string|max:100',
            'responsable_cotizacion' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string'
        ]);

        $cotizacion->update([
            'codigo_cotizacion' => $request->codigo_cotizacion,
            'fecha_cotizacion' => $request->fecha_cotizacion,
            'detalle_producto' => $request->detalle_producto,
            'condiciones_comerciales' => $request->condiciones_comerciales,
            'total_cotizacion' => $request->total_cotizacion,
            'validez_cotizacion' => $request->validez_cotizacion,
            'responsable_cotizacion' => $request->responsable_cotizacion,
            'observaciones' => $request->observaciones
        ]);

        return response()->json([
            'success' => true,
            'cotizacion' => $cotizacion
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar la cotización: ' . $e->getMessage()
        ], 500);
    }
}


// En ScrumboarddController.php
public function handleCotizacion(Request $request)
{
    try {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'codigo_cotizacion' => 'nullable|string|max:255',
            'fecha_cotizacion' => 'nullable|date',
            'detalle_producto' => 'nullable|string',
            'condiciones_comerciales' => 'nullable|string',
            'total_cotizacion' => 'nullable|numeric',
            'validez_cotizacion' => 'nullable|string|max:100',
            'responsable_cotizacion' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'cotizacion_id' => 'nullable|exists:task_cotizaciones,id' // Para actualización
        ]);

        if ($request->filled('cotizacion_id')) {
            // Actualizar cotización existente
            $cotizacion = TaskCotizacion::findOrFail($request->cotizacion_id);
            $cotizacion->update([
                'codigo_cotizacion' => $request->codigo_cotizacion,
                'fecha_cotizacion' => $request->fecha_cotizacion,
                'detalle_producto' => $request->detalle_producto,
                'condiciones_comerciales' => $request->condiciones_comerciales,
                'total_cotizacion' => $request->total_cotizacion,
                'validez_cotizacion' => $request->validez_cotizacion,
                'responsable_cotizacion' => $request->responsable_cotizacion,
                'observaciones' => $request->observaciones
            ]);
            
            $message = 'Cotización actualizada exitosamente';
        } else {
            // Crear nueva cotización
            $cotizacion = TaskCotizacion::create([
                'task_id' => $request->task_id,
                'codigo_cotizacion' => $request->codigo_cotizacion,
                'fecha_cotizacion' => $request->fecha_cotizacion,
                'detalle_producto' => $request->detalle_producto,
                'condiciones_comerciales' => $request->condiciones_comerciales,
                'total_cotizacion' => $request->total_cotizacion,
                'validez_cotizacion' => $request->validez_cotizacion,
                'responsable_cotizacion' => $request->responsable_cotizacion,
                'observaciones' => $request->observaciones
            ]);
            
            $message = 'Cotización creada exitosamente';
        }

        return response()->json([
            'success' => true,
            'cotizacion' => $cotizacion,
            'message' => $message
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}

    private function saveReunionData(Request $request, Task $task)
    {
        $data = [
            'task_id' => $task->id,
            'fecha_reunion' => $request->fechareunion,
            'tipo_reunion' => $request->tiporeunion,
            'motivo_reunion' => $request->motivoreunion,
            'participantes' => $request->participantesreunion,
            'responsable_reunion' => $request->responsablereunion,
            'link_reunion' => $request->linkreunion,
            'direccion_fisica' => $request->direccionfisica,
            'minuta' => $request->minutareunion,
            'actividades' => $request->actividadesReunion
        ];

        if ($task->reunion) {
            $task->reunion->update($data);
        } else {
            TaskReunion::create($data);
        }
    }

    private function saveLevantamientoData(Request $request, Task $task)
    {
        $data = [
            'task_id' => $task->id,
            'fecha_requerimiento' => $request->fecharequerimiento,
            'participantes' => $request->participanteslevantamiento,
            'ubicacion' => $request->ubicacionlevantamiento,
            'descripcion_requerimiento' => $request->descripcionrequerimiento,
            'observaciones' => $request->observacioneslevantamiento
        ];

        if ($task->levantamiento) {
            $task->levantamiento->update($data);
        } else {
            TaskLevantamiento::create($data);
        }
    }

    private function saveGanadoData(Request $request, Task $task)
    {
        $data = [
            'task_id' => $task->id,
            'fecha_ganado' => $request->fechaganado,
            'codigo_cotizacion' => $request->codigoCotizacion,
            'tipo_relacion' => $request->tiporelacion,
            'tipo_servicio' => $request->tiposervicio,
            'valor_ganado' => $request->valorganado,
            'forma_cierre' => $request->formacierre,
            'duracion_acuerdo' => $request->duraciondelacuerdo,
            'observaciones' => $request->observacionesganado
        ];

        if ($task->ganado) {
            $task->ganado->update($data);
        } else {
            TaskGanado::create($data);
        }
    }

    private function saveObservadoData(Request $request, Task $task)
    {
        $data = [
            'task_id' => $task->id,
            'fecha_observado' => $request->fechaobservado,
            'estado_actual' => $request->estadoactual,
            'detalles' => $request->detallesobservado,
            'comentarios' => $request->comentariosobservado,
            'acciones_pendientes' => $request->accionespendientes,
            'detalle_observado' => $request->detalleobservado
        ];

        if ($task->observado) {
            $task->observado->update($data);
        } else {
            TaskObservado::create($data);
        }
    }

    private function saveRechazadoData(Request $request, Task $task)
    {
        $data = [
            'task_id' => $task->id,
            'fecha_rechazo' => $request->fecharechazo,
            'motivo_rechazo' => $request->motivorechazo,
            'comentarios_cliente' => $request->comentarioscliente
        ];

        if ($task->rechazado) {
            $task->rechazado->update($data);
        } else {
            TaskRechazado::create($data);
        }
    }


    // Manejar reuniones (crear y actualizar)
public function handleReunion(Request $request)
{
    try {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'fecha_reunion' => 'nullable|date',
            'tipo_reunion' => 'nullable|string|max:255',
            'motivo_reunion' => 'nullable|string|max:255',
            'participantes' => 'nullable|string',
            'responsable_reunion' => 'nullable|string|max:255',
            'link_reunion' => 'nullable|url|max:255',
            'direccion_fisica' => 'nullable|string|max:255',
            'minuta' => 'nullable|string',
            'actividades' => 'nullable|string',
            'reunion_id' => 'nullable|exists:task_reuniones,id' // Para actualización
        ]);

        if ($request->filled('reunion_id')) {
            // Actualizar reunión existente
            $reunion = TaskReunion::findOrFail($request->reunion_id);
            $reunion->update([
                'fecha_reunion' => $request->fecha_reunion,
                'tipo_reunion' => $request->tipo_reunion,
                'motivo_reunion' => $request->motivo_reunion,
                'participantes' => $request->participantes,
                'responsable_reunion' => $request->responsable_reunion,
                'link_reunion' => $request->link_reunion,
                'direccion_fisica' => $request->direccion_fisica,
                'minuta' => $request->minuta,
                'actividades' => $request->actividades
            ]);
            
            $message = 'Reunión actualizada exitosamente';
        } else {
            // Crear nueva reunión
            $reunion = TaskReunion::create([
                'task_id' => $request->task_id,
                'fecha_reunion' => $request->fecha_reunion,
                'tipo_reunion' => $request->tipo_reunion,
                'motivo_reunion' => $request->motivo_reunion,
                'participantes' => $request->participantes,
                'responsable_reunion' => $request->responsable_reunion,
                'link_reunion' => $request->link_reunion,
                'direccion_fisica' => $request->direccion_fisica,
                'minuta' => $request->minuta,
                'actividades' => $request->actividades
            ]);
            
            $message = 'Reunión creada exitosamente';
        }

        return response()->json([
            'success' => true,
            'reunion' => $reunion,
            'message' => $message
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}

// Obtener reuniones de una tarea
public function getReuniones($taskId)
{
    try {
        $task = Task::findOrFail($taskId);
        $reuniones = $task->reuniones;
        
        return response()->json([
            'success' => true,
            'reuniones' => $reuniones
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener reuniones'
        ], 500);
    }
}

// Eliminar reunión
public function deleteReunion($id)
{
    try {
        $reunion = TaskReunion::findOrFail($id);
        $reunion->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Reunión eliminada exitosamente'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar la reunión: ' . $e->getMessage()
        ], 500);
    }
}

// Manejar levantamientos (crear y actualizar)
public function handleLevantamiento(Request $request)
{
    try {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'fecha_requerimiento' => 'nullable|date',
            'participantes' => 'nullable|string',
            'ubicacion' => 'nullable|string|max:255',
            'descripcion_requerimiento' => 'nullable|string',
            'observaciones' => 'nullable|string',
            'levantamiento_id' => 'nullable|exists:task_levantamientos,id' // Para actualización
        ]);

        if ($request->filled('levantamiento_id')) {
            // Actualizar levantamiento existente
            $levantamiento = TaskLevantamiento::findOrFail($request->levantamiento_id);
            $levantamiento->update([
                'fecha_requerimiento' => $request->fecha_requerimiento,
                'participantes' => $request->participantes,
                'ubicacion' => $request->ubicacion,
                'descripcion_requerimiento' => $request->descripcion_requerimiento,
                'observaciones' => $request->observaciones
            ]);
            
            $message = 'Levantamiento actualizado exitosamente';
        } else {
            // Crear nuevo levantamiento
            $levantamiento = TaskLevantamiento::create([
                'task_id' => $request->task_id,
                'fecha_requerimiento' => $request->fecha_requerimiento,
                'participantes' => $request->participantes,
                'ubicacion' => $request->ubicacion,
                'descripcion_requerimiento' => $request->descripcion_requerimiento,
                'observaciones' => $request->observaciones
            ]);
            
            $message = 'Levantamiento creado exitosamente';
        }

        return response()->json([
            'success' => true,
            'levantamiento' => $levantamiento,
            'message' => $message
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}

// Obtener levantamientos de una tarea
public function getLevantamientos($taskId)
{
    try {
        $task = Task::findOrFail($taskId);
        $levantamientos = $task->levantamientos;
        
        return response()->json([
            'success' => true,
            'levantamientos' => $levantamientos
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener levantamientos'
        ], 500);
    }
}

// Eliminar levantamiento
public function deleteLevantamiento($id)
{
    try {
        $levantamiento = TaskLevantamiento::findOrFail($id);
        $levantamiento->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Levantamiento eliminado exitosamente'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar el levantamiento: ' . $e->getMessage()
        ], 500);
    }
}




// Manejar proyectos ganados (crear y actualizar)
public function handleGanado(Request $request)
{
    try {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'fecha_ganado' => 'nullable|date',
            'codigo_cotizacion' => 'nullable|string|max:255',
            'tipo_relacion' => 'nullable|string|max:255',
            'tipo_servicio' => 'nullable|string|max:255',
            'valor_ganado' => 'nullable|numeric',
            'forma_cierre' => 'nullable|string|max:255',
            'duracion_acuerdo' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
            'ganado_id' => 'nullable|exists:task_ganados,id' // Para actualización
        ]);

        if ($request->filled('ganado_id')) {
            // Actualizar ganado existente
            $ganado = TaskGanado::findOrFail($request->ganado_id);
            $ganado->update([
                'fecha_ganado' => $request->fecha_ganado,
                'codigo_cotizacion' => $request->codigo_cotizacion,
                'tipo_relacion' => $request->tipo_relacion,
                'tipo_servicio' => $request->tipo_servicio,
                'valor_ganado' => $request->valor_ganado,
                'forma_cierre' => $request->forma_cierre,
                'duracion_acuerdo' => $request->duracion_acuerdo,
                'observaciones' => $request->observaciones
            ]);
            
            $message = 'Proyecto ganado actualizado exitosamente';
        } else {
            // Crear nuevo proyecto ganado
            $ganado = TaskGanado::create([
                'task_id' => $request->task_id,
                'fecha_ganado' => $request->fecha_ganado,
                'codigo_cotizacion' => $request->codigo_cotizacion,
                'tipo_relacion' => $request->tipo_relacion,
                'tipo_servicio' => $request->tipo_servicio,
                'valor_ganado' => $request->valor_ganado,
                'forma_cierre' => $request->forma_cierre,
                'duracion_acuerdo' => $request->duracion_acuerdo,
                'observaciones' => $request->observaciones
            ]);
            
            $message = 'Proyecto ganado creado exitosamente';
        }

        return response()->json([
            'success' => true,
            'ganado' => $ganado,
            'message' => $message
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}

// Obtener proyectos ganados de una tarea
public function getGanados($taskId)
{
    try {
        $task = Task::findOrFail($taskId);
        $ganados = $task->ganados;
        
        return response()->json([
            'success' => true,
            'ganados' => $ganados
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener proyectos ganados'
        ], 500);
    }
}

// Eliminar proyecto ganado
public function deleteGanado($id)
{
    try {
        $ganado = TaskGanado::findOrFail($id);
        $ganado->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Proyecto ganado eliminado exitosamente'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar el proyecto ganado: ' . $e->getMessage()
        ], 500);
    }
}


// Manejar proyectos observados (crear y actualizar)
public function handleObservado(Request $request)
{
    try {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'fecha_observado' => 'nullable|date',
            'estado_actual' => 'nullable|string|max:255',
            'detalles' => 'nullable|string',
            'comentarios' => 'nullable|string',
            'acciones_pendientes' => 'nullable|string',
            'detalle_observado' => 'nullable|string',
            'observado_id' => 'nullable|exists:task_observados,id' // Para actualización
        ]);

        if ($request->filled('observado_id')) {
            // Actualizar observado existente
            $observado = TaskObservado::findOrFail($request->observado_id);
            $observado->update([
                'fecha_observado' => $request->fecha_observado,
                'estado_actual' => $request->estado_actual,
                'detalles' => $request->detalles,
                'comentarios' => $request->comentarios,
                'acciones_pendientes' => $request->acciones_pendientes,
                'detalle_observado' => $request->detalle_observado
            ]);
            
            $message = 'Proyecto observado actualizado exitosamente';
        } else {
            // Crear nuevo proyecto observado
            $observado = TaskObservado::create([
                'task_id' => $request->task_id,
                'fecha_observado' => $request->fecha_observado,
                'estado_actual' => $request->estado_actual,
                'detalles' => $request->detalles,
                'comentarios' => $request->comentarios,
                'acciones_pendientes' => $request->acciones_pendientes,
                'detalle_observado' => $request->detalle_observado
            ]);
            
            $message = 'Proyecto observado creado exitosamente';
        }

        return response()->json([
            'success' => true,
            'observado' => $observado,
            'message' => $message
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}

// Obtener proyectos observados de una tarea
public function getObservados($taskId)
{
    try {
        $task = Task::findOrFail($taskId);
        $observados = $task->observados;
        
        return response()->json([
            'success' => true,
            'observados' => $observados
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener proyectos observados'
        ], 500);
    }
}

// Eliminar proyecto observado
public function deleteObservado($id)
{
    try {
        $observado = TaskObservado::findOrFail($id);
        $observado->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Proyecto observado eliminado exitosamente'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar el proyecto observado: ' . $e->getMessage()
        ], 500);
    }
}

// Manejar proyectos rechazados (crear y actualizar)
public function handleRechazado(Request $request)
{
    try {
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'fecha_rechazo' => 'nullable|date',
            'motivo_rechazo' => 'nullable|string',
            'comentarios_cliente' => 'nullable|string',
            'rechazado_id' => 'nullable|exists:task_rechazados,id' // Para actualización
        ]);

        if ($request->filled('rechazado_id')) {
            // Actualizar rechazado existente
            $rechazado = TaskRechazado::findOrFail($request->rechazado_id);
            $rechazado->update([
                'fecha_rechazo' => $request->fecha_rechazo,
                'motivo_rechazo' => $request->motivo_rechazo,
                'comentarios_cliente' => $request->comentarios_cliente
            ]);
            
            $message = 'Proyecto rechazado actualizado exitosamente';
        } else {
            // Crear nuevo proyecto rechazado
            $rechazado = TaskRechazado::create([
                'task_id' => $request->task_id,
                'fecha_rechazo' => $request->fecha_rechazo,
                'motivo_rechazo' => $request->motivo_rechazo,
                'comentarios_cliente' => $request->comentarios_cliente
            ]);
            
            $message = 'Proyecto rechazado creado exitosamente';
        }

        return response()->json([
            'success' => true,
            'rechazado' => $rechazado,
            'message' => $message
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}

// Obtener proyectos rechazados de una tarea
public function getRechazados($taskId)
{
    try {
        $task = Task::findOrFail($taskId);
        $rechazados = $task->rechazados;
        
        return response()->json([
            'success' => true,
            'rechazados' => $rechazados
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al obtener proyectos rechazados'
        ], 500);
    }
}

// Eliminar proyecto rechazado
public function deleteRechazado($id)
{
    try {
        $rechazado = TaskRechazado::findOrFail($id);
        $rechazado->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Proyecto rechazado eliminado exitosamente'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar el proyecto rechazado: ' . $e->getMessage()
        ], 500);
    }
}
}