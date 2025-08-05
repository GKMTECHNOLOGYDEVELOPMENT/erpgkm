<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_razon_social',
        'ruc',
        'giro_comercial',
        'ubicacion_geografica',
        'fuente_captacion_id'
    ];

    public function contactos()
    {
        return $this->hasMany(Contacto::class);
    }
}
