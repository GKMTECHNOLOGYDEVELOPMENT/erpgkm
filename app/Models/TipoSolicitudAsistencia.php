<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoSolicitudAsistencia extends Model
{
    protected $table = 'tipo_solicitud_asistencia';
    protected $primaryKey = 'id_tipo_solicitud';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombre_tip',
    ];

    public function solicitudes(): HasMany
    {
        return $this->hasMany(SolicitudAsistencia::class, 'id_tipo_solicitud', 'id_tipo_solicitud');
    }
}
