<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoSolicitud extends Model
{
    use HasFactory;

    protected $table = 'tipo_solicitud';
    protected $primaryKey = 'idTipoSolicitud';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado'
    ];

    public $timestamps = true;
}