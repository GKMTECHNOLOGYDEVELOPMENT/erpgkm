<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeleccionarSeguimiento extends Model
{
    use HasFactory;

    protected $table = 'seleccionarseguimiento';

    protected $primaryKey = 'id';

    public $timestamps = false; // Ya tienes un campo personalizado para timestamps

    protected $fillable = [
        'idseguimiento',
        'idprospecto',
        'idusuario',
        'idpersona',
        'fecha_seleccionada',
    ];

    // Relaciones (si las tienes, las puedes descomentar o ajustar)
    /*
    public function seguimiento()
    {
        return $this->belongsTo(Seguimiento::class, 'idseguimiento');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idusuario');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'idpersona');
    }

    public function prospecto()
    {
        return $this->belongsTo(Prospecto::class, 'idprospecto');
    }
    */
}
