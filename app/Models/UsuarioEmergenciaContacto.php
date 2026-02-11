<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioEmergenciaContacto extends Model
{
    use HasFactory;

         public $timestamps = false;
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $table = 'usuarios_emergencia_contactos';
    protected $primaryKey = 'idContacto';

    protected $fillable = [
        'idUsuario',
        'apellidosNombres',
        'parentesco',
        'direccionTelefono',
    ];

    protected $casts = [
        'createdAt' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}