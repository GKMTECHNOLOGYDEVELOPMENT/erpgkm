<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrioridadSolicitud extends Model
{
    use HasFactory;

    protected $table = 'prioridad_solicitud';
    protected $primaryKey = 'idPrioridad';
    
    protected $fillable = [
        'nombre',
        'nivel',
        'color',
        'descripcion',
        'estado'
    ];

    public $timestamps = true;
}