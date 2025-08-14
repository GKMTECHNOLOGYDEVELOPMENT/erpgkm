<?php
// app/Models/CronogramaTarea.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CronogramaTarea extends Model
{
    protected $table = 'cronograma_tareas';

    protected $fillable = [
        'idSeguimiento',
        'task_id',
        'parent_task_id',
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'duracion',
        'progreso',
        'tipo',
        'abierto',
        'orden',
        'color'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'duracion' => 'decimal:2',
        'progreso' => 'decimal:4',
        'abierto' => 'boolean',
        'orden' => 'integer'
    ];

    // Relación con seguimiento
    public function seguimiento(): BelongsTo
    {
        return $this->belongsTo(Seguimiento::class, 'idSeguimiento', 'idSeguimiento');
    }

    // Relación con tarea padre
    public function padre(): BelongsTo
    {
        return $this->belongsTo(CronogramaTarea::class, 'parent_task_id', 'task_id');
    }

    // Relación con tareas hijas
    public function hijas(): HasMany
    {
        return $this->hasMany(CronogramaTarea::class, 'parent_task_id', 'task_id');
    }

    // Dependencias donde esta tarea es la fuente
    public function dependenciasOrigen(): HasMany
    {
        return $this->hasMany(CronogramaDependencia::class, 'source_task_id', 'task_id');
    }

    // Dependencias donde esta tarea es el destino
    public function dependenciasDestino(): HasMany
    {
        return $this->hasMany(CronogramaDependencia::class, 'target_task_id', 'task_id');
    }

    // Scope para obtener tareas de un seguimiento específico
    public function scopePorSeguimiento($query, $idSeguimiento)
    {
        return $query->where('idSeguimiento', $idSeguimiento);
    }

    // Scope para tareas raíz (sin padre)
    public function scopeRaiz($query)
    {
        return $query->whereNull('parent_task_id')->orWhere('parent_task_id', '');
    }

    // Accessor para progreso en porcentaje
    public function getProgresoPercentAttribute()
    {
        return round($this->progreso * 100);
    }

    // Mutator para progreso desde porcentaje
    public function setProgresoPercentAttribute($value)
    {
        $this->attributes['progreso'] = max(0, min(100, $value)) / 100;
    }

    // Método para formatear para el frontend
    public function toGanttFormat()
    {
        return [
            'id' => $this->task_id,
            'text' => $this->nombre,
            'start_date' => $this->fecha_inicio->format('Y-m-d'),
            'end_date' => $this->fecha_fin->format('Y-m-d'),
            'progress' => $this->progreso,
            'parent' => $this->parent_task_id ?: 0,
            'type' => $this->tipo,
            'open' => $this->abierto,
            'order' => $this->orden,
            'color' => $this->color,
            'description' => $this->descripcion
        ];
    }
}