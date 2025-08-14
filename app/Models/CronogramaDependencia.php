<?php
// app/Models/CronogramaDependencia.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CronogramaDependencia extends Model
{
    protected $table = 'cronograma_dependencias';

    protected $fillable = [
        'idSeguimiento',
        'link_id',
        'source_task_id',
        'target_task_id',
        'tipo_dependencia',
        'retraso'
    ];

    protected $casts = [
        'retraso' => 'integer'
    ];

    // Relación con seguimiento
    public function seguimiento(): BelongsTo
    {
        return $this->belongsTo(Seguimiento::class, 'idSeguimiento', 'idSeguimiento');
    }

    // Relación con tarea origen
    public function tareaOrigen(): BelongsTo
    {
        return $this->belongsTo(CronogramaTarea::class, 'source_task_id', 'task_id');
    }

    // Relación con tarea destino
    public function tareaDestino(): BelongsTo
    {
        return $this->belongsTo(CronogramaTarea::class, 'target_task_id', 'task_id');
    }

    // Scope para dependencias de un seguimiento específico
    public function scopePorSeguimiento($query, $idSeguimiento)
    {
        return $query->where('idSeguimiento', $idSeguimiento);
    }

    // Método para formatear para el frontend
    public function toGanttFormat()
    {
        return [
            'id' => $this->link_id,
            'source' => $this->source_task_id,
            'target' => $this->target_task_id,
            'type' => $this->tipo_dependencia
        ];
    }
}