<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskCotizacion extends Model
{
    protected $table = 'task_cotizaciones';

    protected $fillable = [
        'task_id',
        'codigo_cotizacion',
        'fecha_cotizacion',
        'detalle_producto',
        'condiciones_comerciales',
        'total_cotizacion',
        'validez_cotizacion',
        'responsable_cotizacion',
        'observaciones',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
