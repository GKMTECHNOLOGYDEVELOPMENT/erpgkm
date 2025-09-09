<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustodiaUbicacion extends Model
{
    use HasFactory;

    protected $table = 'custodia_ubicacion';

    protected $fillable = [
        'idUbicacion',
        'idCustodia',
        'cantidad',
        'observacion',
    ];

     // Relación con Custodia
    public function custodia()
    {
        return $this->belongsTo(Custodia::class, 'idCustodia', 'id');
    }
    
    // Relación con Ubicacion (especificando las claves foráneas)
    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'idUbicacion', 'idUbicacion');
    }
}
