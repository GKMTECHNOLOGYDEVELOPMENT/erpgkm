<?php

namespace App\Helpers;

use App\Models\Task;
use App\Models\CronogramaTarea;
use App\Models\SeleccionarSeguimiento;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CronogramaHelper
{
    public static function crearSubtareaCronograma(array $params): ?CronogramaTarea
    {
        try {
            Log::info("📌 Iniciando creación de subtarea para Task ID: {$params['task_id']}");

            $task = Task::findOrFail($params['task_id']);
            $idSeguimiento = $task->idseguimiento;
            $idPersona = SeleccionarSeguimiento::where('idseguimiento', $idSeguimiento)->value('idpersona');
            if (!$idPersona) throw new \Exception('No se encontró idpersona para este seguimiento.');

            // 📅 Fechas
            $fecha_inicio = isset($params['fecha_inicio']) ? Carbon::parse($params['fecha_inicio']) : Carbon::now();
            $fecha_fin = $fecha_inicio->copy()->addDay(); // duración fija de 1 día

            Log::info("🗓️ Fecha inicio subtarea: {$fecha_inicio} - Fecha fin: {$fecha_fin}");

            // 🔑 ID único para subtarea
            $uniqueSubTaskId = $params['tipo'] . '-' . ($params['sub_id'] ?? uniqid());

            // Crear subtarea
            $tarea = new CronogramaTarea();
            $tarea->fill([
                'idSeguimiento'   => $idSeguimiento,
                'idpersona'       => $idPersona,
                'task_id'         => $uniqueSubTaskId,
                'parent_task_id'  => $task->id,
                'nombre'          => $params['nombre'] ?? ucfirst($params['tipo']),
                'descripcion'     => $params['descripcion'] ?? '',
                'fecha_inicio'    => $fecha_inicio,
                'fecha_fin'       => $fecha_fin,
                'duracion'        => 1,
                'progreso'        => $params['progreso'] ?? 0, // ✅ Progreso dinámico
                'tipo'            => 'task',
                'abierto'         => true,
                'orden'           => CronogramaTarea::porSeguimiento($idSeguimiento)->max('orden') + 1,
            ]);
            $tarea->save();

            Log::info("✅ Subtarea creada correctamente con ID: {$tarea->id}");

            // 🔁 ACTUALIZAR TAREA PADRE
            $subtareas = CronogramaTarea::where('parent_task_id', $task->id)->get();

            $ultimaFechaFin = Carbon::parse($subtareas->max('fecha_fin'));

            $fechaInicioPadre = $task->fecha_inicio
                ? Carbon::parse($task->fecha_inicio)
                : Carbon::parse($subtareas->min('fecha_inicio'));

            $nuevaDuracion = $fechaInicioPadre->diffInDays($ultimaFechaFin) + 1;

            Log::info("🧠 Actualizando tarea padre (ID: {$task->id})");
            Log::info("➡️ Fecha inicio padre: {$fechaInicioPadre}");
            Log::info("➡️ Última fecha fin de subtareas: {$ultimaFechaFin}");
            Log::info("➡️ Nueva duración: {$nuevaDuracion} días");

            // 🔄 Actualizar en tabla `tasks`
            $task->fecha_fin = $ultimaFechaFin;
            $task->duracion = $nuevaDuracion;
            $task->save();

            Log::info("✅ Tarea padre actualizada correctamente.");

            // 🔄 Actualizar en tabla `cronograma_tareas` (si existe)
            $tareaPadre = CronogramaTarea::where('task_id', $task->id)
                ->where(function ($q) {
                    $q->whereNull('parent_task_id')
                      ->orWhere('parent_task_id', 0);
                })
                ->first();

            if ($tareaPadre) {
                $tareaPadre->fecha_inicio = $fechaInicioPadre;
                $tareaPadre->fecha_fin = $ultimaFechaFin;
                $tareaPadre->duracion = $nuevaDuracion;
                $tareaPadre->save();

                Log::info("✅ Cronograma tarea padre actualizada (ID: {$tareaPadre->id})");
            } else {
                Log::warning("⚠️ No se encontró la tarea padre en cronograma para task_id: {$task->id}");
            }

            return $tarea;

        } catch (\Exception $e) {
            Log::error('❌ Error al crear subtarea en cronograma: ' . $e->getMessage());
            return null;
        }
    }

public static function actualizarSubtareaCronograma(array $params): ?CronogramaTarea
{
    try {
        $uniqueSubTaskId = $params['tipo'] . '-' . $params['sub_id'];
        $subtarea = CronogramaTarea::where('task_id', $uniqueSubTaskId)->first();

        if (!$subtarea) {
            Log::warning("⚠️ No se encontró la subtarea para actualizar con ID: {$uniqueSubTaskId}");
            return null;
        }

        // 🗓️ Fecha de inicio (usa ahora si no viene)
        $fecha_inicio = isset($params['fecha_inicio']) 
            ? Carbon::parse($params['fecha_inicio'])->startOfDay() 
            : Carbon::now()->startOfDay();

        // 📆 Fecha fin = inicio + 1 día (conservar lógica uniforme)
        $fecha_fin = $fecha_inicio->copy()->addDay();

        $subtarea->update([
            'nombre'        => $params['nombre'] ?? $subtarea->nombre,
            'descripcion'   => $params['descripcion'] ?? $subtarea->descripcion,
            'fecha_inicio'  => $fecha_inicio,
            'fecha_fin'     => $fecha_fin,
            'progreso'      => $params['progreso'] ?? $subtarea->progreso, // 👈 Aquí se actualiza si se envía
            'duracion'      => 1,
        ]);

        Log::info("✅ Subtarea actualizada correctamente con ID: {$subtarea->id}");

        // 🔁 Buscar todas las subtareas hijas de esta tarea padre
        $task = Task::findOrFail($params['task_id']);
        $subtareas = CronogramaTarea::where('parent_task_id', $task->id)->get();

        if ($subtareas->isEmpty()) {
            Log::warning("⚠️ No hay subtareas para el task padre ID: {$task->id}");
            return $subtarea;
        }

        // Calcular fecha de inicio mínima y fecha fin máxima
        $fechaInicioPadre = $subtareas->min('fecha_inicio') ? Carbon::parse($subtareas->min('fecha_inicio')) : $fecha_inicio;
        $fechaFinPadre    = $subtareas->max('fecha_fin')    ? Carbon::parse($subtareas->max('fecha_fin'))    : $fecha_fin;

        // 🧮 Duración total de la tarea padre
        $duracionPadre = max(1, $fechaInicioPadre->diffInDays($fechaFinPadre) + 1);

        // ✏️ Actualizar tarea padre (tabla tasks)
        $task->update([
            'fecha_inicio' => $fechaInicioPadre,
            'fecha_fin'    => $fechaFinPadre,
            'duracion'     => $duracionPadre,
        ]);

        // ✏️ Actualizar cronograma_tareas si la tarea padre existe ahí también
        $tareaPadre = CronogramaTarea::where('task_id', $task->id)
            ->where(function ($q) {
                $q->whereNull('parent_task_id')
                  ->orWhere('parent_task_id', 0);
            })->first();

        if ($tareaPadre) {
            $tareaPadre->update([
                'fecha_inicio' => $fechaInicioPadre,
                'fecha_fin'    => $fechaFinPadre,
                'duracion'     => $duracionPadre,
            ]);
            Log::info("✅ Cronograma tarea padre actualizada correctamente.");
        } else {
            Log::warning("⚠️ No se encontró la tarea padre en cronograma para task_id: {$task->id}");
        }

        return $subtarea;

    } catch (\Exception $e) {
        Log::error('❌ Error al actualizar subtarea en cronograma: ' . $e->getMessage());
        return null;
    }
}



}
