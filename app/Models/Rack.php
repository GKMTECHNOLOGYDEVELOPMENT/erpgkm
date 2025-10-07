<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    use HasFactory;

    protected $table = 'racks';
    protected $primaryKey = 'idRack';

    protected $fillable = [
        'nombre', 'sede', 'filas', 'columnas', 'estado'
    ];

    public function ubicaciones()
    {
        return $this->hasMany(RackUbicacion::class, 'rack_id');
    }

    public function movimientosOrigen()
    {
        return $this->hasMany(RackMovimiento::class, 'rack_origen_id');
    }

    public function movimientosDestino()
    {
        return $this->hasMany(RackMovimiento::class, 'rack_destino_id');
    }
}