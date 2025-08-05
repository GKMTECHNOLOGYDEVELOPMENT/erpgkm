<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    protected $table = 'seguimientos';
    protected $primaryKey = 'idSeguimiento';
    public $timestamps = true;

    protected $fillable = [
        'idEmpresa',
        'idContacto',
        'idUsuario',
        'tipoRegistro',
        'fechaIngreso',
    ];

    // Relaciones
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'idEmpresa');
    }

    public function contacto()
    {
        return $this->belongsTo(Contactos::class, 'idContacto');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario');
    }
}
