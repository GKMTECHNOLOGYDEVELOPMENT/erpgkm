<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArchivoSolicitudAsistencia extends Model
{
    protected $table = 'archivo_x_solicitud_asistencia';
    protected $primaryKey = 'id_archivo_x_solicitud';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false; // solo tiene created_at

    protected $fillable = [
        'id_solicitud_asistencia',
        'archivo_solicitud',
        'tipo_archivo',
        'espacio_archivo',
        'created_at',
    ];

    protected $casts = [
        'espacio_archivo' => 'int',
        'created_at'      => 'datetime',
    ];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(SolicitudAsistencia::class, 'id_solicitud_asistencia', 'id_solicitud_asistencia');
    }
}
