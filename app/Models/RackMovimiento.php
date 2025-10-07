<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RackMovimiento extends Model
{
    use HasFactory;

    protected $table = 'rack_movimientos';
    protected $primaryKey = 'idMovimiento';

    protected $fillable = [
        'articulo_id', 'ubicacion_origen_id', 'ubicacion_destino_id',
        'rack_origen_id', 'rack_destino_id', 'cantidad', 'tipo_movimiento',
        'usuario_id', 'observaciones', 'codigo_ubicacion_origen',
        'codigo_ubicacion_destino', 'nombre_rack_origen', 'nombre_rack_destino'
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'articulo_id');
    }

    public function ubicacionOrigen()
    {
        return $this->belongsTo(RackUbicacion::class, 'ubicacion_origen_id');
    }

    public function ubicacionDestino()
    {
        return $this->belongsTo(RackUbicacion::class, 'ubicacion_destino_id');
    }

    public function rackOrigen()
    {
        return $this->belongsTo(Rack::class, 'rack_origen_id');
    }

    public function rackDestino()
    {
        return $this->belongsTo(Rack::class, 'rack_destino_id');
    }
}