<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuentasBancarias extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'cuentasbancarias';
    public $timestamps = false;


    // Definir las columnas que se pueden asignar de forma masiva
    protected $fillable = [
        'tipodecuenta', // ID del tipo de cuenta (puedes definir una relación con una tabla de tipos de cuentas si es necesario)
        'numerocuenta', // Número de la cuenta bancaria
        'idUsuario',     // ID del usuario al que pertenece la cuenta bancaria
    ];

    // Definir una relación con el modelo Usuario (si es necesario)
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario');
    }


}
