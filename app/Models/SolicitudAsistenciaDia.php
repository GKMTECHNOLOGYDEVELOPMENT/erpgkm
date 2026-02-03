<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudAsistenciaDia extends Model
{
    protected $table = 'solicitud_asistencia_dias';
    protected $primaryKey = 'id_solicitud_dia';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false; // solo tiene created_at

    protected $fillable = [
        'id_solicitud_asistencia',
        'fecha',
        'es_todo_el_dia',
        'hora_entrada',
        'hora_salida',
        'hora_llegada_trabajo',
        'observacion',
        'created_at',
    ];

    protected $casts = [
        'fecha'            => 'date',
        'es_todo_el_dia'   => 'boolean',
        'created_at'       => 'datetime',
    ];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(SolicitudAsistencia::class, 'id_solicitud_asistencia', 'id_solicitud_asistencia');
    }
}
