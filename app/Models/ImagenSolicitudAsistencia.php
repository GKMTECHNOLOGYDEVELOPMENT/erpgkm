<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagenSolicitudAsistencia extends Model
{
    protected $table = 'imagen_solicitud_asistencia';
    protected $primaryKey = 'id_solicitud_imagen';
    public $incrementing = true;
    protected $keyType = 'int';

    public $timestamps = false; // solo tiene created_at

    protected $fillable = [
        'id_solicitud_asistencia',
        'imagen',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(SolicitudAsistencia::class, 'id_solicitud_asistencia', 'id_solicitud_asistencia');
    }
}
