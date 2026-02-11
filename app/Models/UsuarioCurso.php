<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioCurso extends Model
{
    use HasFactory;

    protected $table = 'usuarios_cursos';
    protected $primaryKey = 'idCurso';

    protected $fillable = [
        'idUsuario',
        'centroEstudios',
        'nombreCurso',
        'duracion',
        'fechaInicio',
        'fechaFin',
    ];

    protected $casts = [
        'fechaInicio' => 'date',
        'fechaFin' => 'date',
        'createdAt' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}