<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticuloUbicacion extends Model
{
    use HasFactory;

    protected $table = 'articulo_ubicaciones';
    protected $primaryKey = 'idArticuloUbicacion';

    protected $fillable = [
        'articulo_id',
        'ubicacion_id',
        'origen_id',
        'origen',
        'cantidad'
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