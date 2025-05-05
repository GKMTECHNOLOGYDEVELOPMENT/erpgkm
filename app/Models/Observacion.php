<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Observacion extends Model
{
    use HasFactory;

    protected $table = 'observaciones';
    protected $primaryKey = 'idObservaciones';
    public $timestamps = false;

    protected $fillable = [
        'idTipoAsunto',
        'mensaje',
        'fechaHora',
        'idUsuario',
        'lat',
        'lng',
        'ubicacion',
        'estado',
    ];

    public function anexos()
    {
        return $this->hasMany(AnexoObservacion::class, 'idObservaciones', 'idObservaciones');
    }
}
