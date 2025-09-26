<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticuloSerie extends Model
{
    use HasFactory;

    protected $table = 'articulo_series';
    protected $primaryKey = 'idArticuloSerie';

    protected $fillable = [
        'origen',
        'origen_id',
        'articulo_id',
        'ubicacion_id',
        'numero_serie',
        'estado'
    ];

    // Relaciones
    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'articulo_id', 'idArticulos');
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id', 'idUbicacion');
    }
}