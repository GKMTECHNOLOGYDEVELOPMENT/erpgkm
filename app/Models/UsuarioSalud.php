<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioSalud extends Model
{
    use HasFactory;

    protected $table = 'usuarios_salud';
    protected $primaryKey = 'idUsuario';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'idUsuario',
        'vacunaCovid',
        'covidDosis1',
        'covidDosis2',
        'dolenciaCronica',
        'dolenciaDetalle',
        'discapacidad',
        'discapacidadDetalle',
        'tipoSangre',
    ];

    protected $casts = [
        'vacunaCovid' => 'boolean',
        'covidDosis1' => 'date',
        'covidDosis2' => 'date',
        'dolenciaCronica' => 'boolean',
        'discapacidad' => 'boolean',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}