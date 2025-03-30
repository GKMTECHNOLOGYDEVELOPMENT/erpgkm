<?php

// En el archivo app/Models/TipoEnvio.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEnvio extends Model
{
    use HasFactory;

    protected $table = 'tipoenvio';  // Cambia esto si el nombre de la tabla es diferente


    // Si el nombre de la tabla no sigue el estándar plural, puedes indicarlo aquí
    // protected $table = 'tipoenvio';
    
    // Definir los campos que pueden ser asignados masivamente (opcional)
    protected $fillable = ['nombre'];
}
