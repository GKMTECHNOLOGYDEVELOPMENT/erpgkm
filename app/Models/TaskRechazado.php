<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskRechazado extends Model
{
    protected $table = 'task_rechazados';

    protected $fillable = [
        'task_id',
        'fecha_rechazo',
        'motivo_rechazo',
        'comentarios_cliente',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
