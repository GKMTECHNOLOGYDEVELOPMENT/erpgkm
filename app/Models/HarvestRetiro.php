<?php
// app/Models/HarvestRetiro.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HarvestRetiro extends Model
{
    use HasFactory;

    protected $table = 'harvest_retiros';
    
    protected $fillable = [
        'id_custodia',
        'id_articulo',
        'codigo_repuesto',
        'nombre_repuesto',
        'cantidad_retirada',
        'observaciones',
        'id_responsable',
        'estado'
    ];

    // Relaciones
    public function custodia()
    {
        return $this->belongsTo(Custodia::class, 'id_custodia');
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'id_articulo');
    }

     public function responsable()
    {
        return $this->belongsTo(Usuario::class, 'id_responsable', 'idUsuario');
    }
}