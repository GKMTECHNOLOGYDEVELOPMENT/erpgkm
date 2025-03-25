<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $table = 'vehiculos'; // opcional si la tabla se llama igual

    protected $primaryKey = 'idVehiculo'; // clave primaria personalizada

    public $timestamps = false; // si no tienes created_at y updated_at

    protected $fillable = [
        'numero_placa'
    ];


}
