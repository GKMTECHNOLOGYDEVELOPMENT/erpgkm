<?php

namespace App\Http\Controllers\areacomercial;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SeleccionarSeguimiento;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ScrumboarddController extends Controller
{
    // Obtener todos los proyectos con sus tareas
 // En ScrumboarddController.php

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
        'idpersona' => $seleccion->idpersona // 👈 Aquí se guarda el idpersona
    ];

    // Manejo de imagen (nueva o existente)
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('tasks', 'public');
        $taskData['image'] = Storage::url($path);
    } elseif ($request->filled('image_url')) {
        $taskData['image'] = $request->image_url;
    }

    $task = Task::create($taskData);

    return response()->json([
        'success' => true,
        'task' => $task
    ]);
}

    // Actualizar una tarea
 public function updateTask(Request $request, Task $task)
{
    Log::info('Solicitud recibida para actualizar tarea', [
        'datos_recibidos' => $request->all(),
        'headers' => $request->headers->all()
    ]);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'tags' => 'nullable|string'
    ]);

    Log::debug('Datos validados:', $validated);

    $taskData = [
        'title' => $validated['title'],
        'description' => $validated['description'] ?? null,
        'tags' => !empty($validated['tags']) ? json_encode(array_map('trim', explode(',', $validated['tags']))) : null
    ];

    $task->update($taskData);

    Log::info('Tarea actualizada exitosamente', $task->fresh()->toArray());

    return response()->json([
        'success' => true,
        'task' => $task->fresh()
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
        
        // Verificar si la tarea ya está en el proyecto destino
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
}