<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SolicitudAsistencia extends Model
{
    protected $table = 'solicitud_asistencia';
    protected $primaryKey = 'id_solicitud_asistencia';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_tipo_solicitud',
        'observacion',
        'fecha_solicitud',
        'rango_inicio_tiempo',
        'rango_final_tiempo',
        'id_tipo_educacion',
        'estado',
        'id_usuario',
    ];

    protected $casts = [
        'fecha_solicitud'      => 'datetime',
        'rango_inicio_tiempo'  => 'datetime',
        'rango_final_tiempo'   => 'datetime',
    ];

    public function tipoSolicitud(): BelongsTo
    {
        return $this->belongsTo(TipoSolicitudAsistencia::class, 'id_tipo_solicitud', 'id_tipo_solicitud');
    }

    public function tipoEducacion(): BelongsTo
    {
        return $this->belongsTo(TipoEducacion::class, 'id_tipo_educacion', 'id_tipo_educacion');
    }

    public function archivos(): HasMany
    {
        return $this->hasMany(ArchivoSolicitudAsistencia::class, 'id_solicitud_asistencia', 'id_solicitud_asistencia');
    }
    /**
     * Relación con notificaciones_solicitud_asistencia
     */
    public function notificaciones()
    {
        return $this->hasMany(
            NotificacionSolicitudAsistencia::class,
            'id_solicitud_asistencia',
            'id_solicitud_asistencia'
        );
    }

    public function imagenes(): HasMany
    {
        return $this->hasMany(ImagenSolicitudAsistencia::class, 'id_solicitud_asistencia', 'id_solicitud_asistencia');
    }

    public function evaluaciones(): HasMany
    {
        return $this->hasMany(EvaluarSolicitudAsistencia::class, 'id_solicitud_asistencia', 'id_solicitud_asistencia');
    }

    public function dias(): HasMany
    {
        return $this->hasMany(SolicitudAsistenciaDia::class, 'id_solicitud_asistencia', 'id_solicitud_asistencia');
    }


    
}
