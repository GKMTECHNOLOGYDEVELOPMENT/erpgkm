<?php

namespace App\Observers;

use App\Models\Task;
use App\Models\CronogramaTarea;
use Illuminate\Support\Facades\Log;

class TaskObserver
{
    public function deleted(Task $task)
    {
        Log::info("🧹 Eliminando cronograma relacionado para task_id: {$task->id}");

        $tareas = CronogramaTarea::where('task_id', $task->id)
            ->orWhere('parent_task_id', $task->id)
            ->get();

        foreach ($tareas as $tarea) {
            Log::info("🗑️ Eliminando CronogramaTarea ID: {$tarea->id}");
            $tarea->delete();
        }
    }
}
