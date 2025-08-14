<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Empresasform extends Model
{
    use HasFactory;

    protected $table = 'empresasform';

    protected $fillable = [
        'idSeguimiento',
        'nombre_razon_social',
        'ruc',
        'giro_comercial',
        'ubicacion_geografica',
        'fuente_captacion_id',
    ];

    // Relaciones

    public function seguimiento()
    {
        return $this->belongsTo(Seguimiento::class, 'idSeguimiento', 'idSeguimiento');
    }

    public function fuenteCaptacion()
    {
        return $this->belongsTo(FuenteCaptacion::class, 'fuente_captacion_id');
    }
}
