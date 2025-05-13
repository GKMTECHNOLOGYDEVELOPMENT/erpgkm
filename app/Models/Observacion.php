<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AnexoObservacion;
use App\Models\Usuario;
use App\Models\TipoAsunto;
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
    public function encargadoUsuario()
    {
        return $this->belongsTo(Usuario::class, 'encargado', 'idUsuario');
    }
    public function tipoAsunto()
    {
        return $this->belongsTo(TipoAsunto::class, 'idTipoAsunto', 'idTipoAsunto');
    }
}
