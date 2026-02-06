<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluarSolicitudAsistencia extends Model
{
    protected $table = 'evaluar_solicitud_asistencia';
    protected $primaryKey = 'id_evaluacion';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false; // solo tiene created_at

    protected $fillable = [
        'id_solicitud_asistencia',
        'id_tipo_solicitud',
        'estado',
        'id_usuario',
        'comentario',
        'fecha',
        'created_at',
    ];

    protected $casts = [
        'fecha'     => 'datetime',
        'created_at' => 'datetime',
    ];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(SolicitudAsistencia::class, 'id_solicitud_asistencia', 'id_solicitud_asistencia');
    }

    public function tipoSolicitud(): BelongsTo
    {
        return $this->belongsTo(TipoSolicitudAsistencia::class, 'id_tipo_solicitud', 'id_tipo_solicitud');
    }
    // Relación con usuario
    public function usuario()
    {
        // Ajusta según tu modelo de usuarios
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
}