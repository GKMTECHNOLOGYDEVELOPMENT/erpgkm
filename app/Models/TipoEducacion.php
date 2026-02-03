<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoEducacion extends Model
{
    protected $table = 'tipo_educacion';
    protected $primaryKey = 'id_tipo_educacion';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
    ];

    public function solicitudes(): HasMany
    {
        return $this->hasMany(SolicitudAsistencia::class, 'id_tipo_educacion', 'id_tipo_educacion');
    }
}
