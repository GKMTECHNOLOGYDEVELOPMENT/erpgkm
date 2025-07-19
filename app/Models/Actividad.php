<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Actividad extends Model
{
    protected $table = 'actividades';
    protected $primaryKey = 'actividad_id';

    protected $fillable = [
        'titulo',
        'etiqueta',
        'fechainicio',
        'fechafin',
        'enlaceevento',
        'ubicacion',
        'descripcion',
        'user_id',
    ];

    /**
     * Relación: una actividad pertenece a un usuario.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'idUsuario');
    }

    public function etiqueta(): BelongsTo
{
    return $this->belongsTo(Etiqueta::class, 'etiqueta_id'); // Ajusta si el nombre de columna es diferente
}

    public function invitados()
    {
        return $this->hasMany(Invitado::class, 'actividad_id', 'actividad_id');
    }
}
