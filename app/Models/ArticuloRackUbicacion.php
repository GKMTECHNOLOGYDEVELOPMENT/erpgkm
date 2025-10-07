<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticuloRackUbicacion extends Model
{
    protected $table = 'articulo_rack_ubicaciones';
    protected $primaryKey = 'idArticuloRackUbicacion';
    
    protected $fillable = [
        'articulo_id', 'rack_ubicacion_id', 'cantidad', 'fecha_ingreso', 'fecha_ultimo_movimiento', 'estado'
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'articulo_id', 'idArticulos');
    }

    public function ubicacion()
    {
        return $this->belongsTo(RackUbicacion::class, 'rack_ubicacion_id', 'idRackUbicacion');
    }

    public function movimientos()
    {
        return $this->hasMany(RackMovimiento::class, 'articulo_id', 'articulo_id');
    }
}