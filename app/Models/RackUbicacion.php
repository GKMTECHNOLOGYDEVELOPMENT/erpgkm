<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RackUbicacion extends Model
{
    use HasFactory;

    protected $table = 'rack_ubicaciones';
    protected $primaryKey = 'idRackUbicacion';

    protected $fillable = [
        'rack_id', 'codigo', 'codigo_unico', 'nivel', 'posicion', 
        'estado_ocupacion', 'capacidad_maxima', 'articulo_id', 'cantidad_actual'
    ];

    public function rack()
    {
        return $this->belongsTo(Rack::class, 'rack_id');
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'articulo_id');
    }

    public function movimientosOrigen()
    {
        return $this->hasMany(RackMovimiento::class, 'ubicacion_origen_id');
    }

    public function movimientosDestino()
    {
        return $this->hasMany(RackMovimiento::class, 'ubicacion_destino_id');
    }

    public function historial()
    {
        return $this->hasMany(RackMovimiento::class, 'ubicacion_origen_id')
                    ->orWhere('ubicacion_destino_id', $this->idRackUbicacion)
                    ->orderBy('created_at', 'desc');
    }
}