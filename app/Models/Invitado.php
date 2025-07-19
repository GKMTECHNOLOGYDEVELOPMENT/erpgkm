<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invitado extends Model
{
    protected $table = 'invitados';
    protected $primaryKey = 'idinvitados';
    public $timestamps = true;

    protected $fillable = [
        'actividad_id',
        'id_usuarios',
    ];

    // Relación con Actividad
    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'actividad_id', 'actividad_id');
    }

    // Relación con Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuarios', 'idUsuario');
    }
}
