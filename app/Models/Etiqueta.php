<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Etiqueta extends Model
{
    protected $table = 'etiquetas';

    protected $fillable = [
        'nombre',
        'color',
        'icono',
        'user_id',
    ];

    /**
     * Relación: una etiqueta pertenece a un usuario.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'user_id', 'idUsuario');
    }
}
