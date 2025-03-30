<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoRecojo extends Model
{
    use HasFactory;

    // Especificar el nombre de la tabla si no sigue el estándar de Laravel
    protected $table = 'tiporecojo';  // Cambia esto si el nombre de la tabla es diferente

    // Los campos que son asignables
    protected $fillable = ['nombre'];
}
