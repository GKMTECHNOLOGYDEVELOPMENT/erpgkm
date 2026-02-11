<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioEstudio extends Model
{
    use HasFactory;

         public $timestamps = false;
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    
    protected $table = 'usuarios_estudios';
    protected $primaryKey = 'idEstudio';



    protected $fillable = [
        'idUsuario',
        'nivel',
        'termino',
        'centroEstudios',
        'especialidad',
        'gradoAcademico',
        'fechaInicio',
        'fechaFin',
    ];

    protected $casts = [
        'termino' => 'boolean',
        'fechaInicio' => 'date',
        'fechaFin' => 'date',
        'createdAt' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}