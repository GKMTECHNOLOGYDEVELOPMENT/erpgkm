<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskGanado extends Model
{
    protected $table = 'task_ganados';

    protected $fillable = [
        'task_id',
        'fecha_ganado',
        'codigo_cotizacion',
        'tipo_relacion',
        'tipo_servicio',
        'valor_ganado',
        'forma_cierre',
        'duracion_acuerdo',
        'observaciones',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
