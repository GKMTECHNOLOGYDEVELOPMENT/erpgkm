<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLevantamiento extends Model
{
    protected $table = 'task_levantamientos';

    protected $fillable = [
        'task_id',
        'fecha_requerimiento',
        'participantes',
        'ubicacion',
        'descripcion_requerimiento',
        'observaciones',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
