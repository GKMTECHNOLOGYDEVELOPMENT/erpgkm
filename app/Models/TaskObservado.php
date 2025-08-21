<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskObservado extends Model
{
    protected $table = 'task_observados';

    protected $fillable = [
        'task_id',
        'fecha_observado',
        'estado_actual',
        'detalles',
        'comentarios',
        'acciones_pendientes',
        'detalle_observado',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
