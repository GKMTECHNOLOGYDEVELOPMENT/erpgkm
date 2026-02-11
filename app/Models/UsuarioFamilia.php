<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioFamilia extends Model
{
    use HasFactory;

    protected $table = 'usuarios_familia';
    protected $primaryKey = 'idFamiliar';

    protected $fillable = [
        'idUsuario',
        'parentesco',
        'apellidosNombres',
        'nroDocumento',
        'ocupacion',
        'sexo',
        'fechaNacimiento',
        'domicilioActual',
    ];

    protected $casts = [
        'fechaNacimiento' => 'date',
        'createdAt' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}