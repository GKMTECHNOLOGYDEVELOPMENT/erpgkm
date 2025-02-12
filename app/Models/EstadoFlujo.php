<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoFlujo extends Model
{
    use HasFactory;

    // Especificar el nombre de la tabla si no sigue la convención plural
    protected $table = 'estado_flujo';

    // Especificar la clave primaria si no es 'id' (en este caso es 'idEstadflujo')
    protected $primaryKey = 'idEstadflujo';

    // Indicar que 'idEstadflujo' es un campo autoincrementable
    public $incrementing = true;

    // Establecer el tipo de la clave primaria (si no es 'int')
    protected $keyType = 'int';

    // Habilitar la asignación masiva (añadir los campos que se pueden modificar)
    protected $fillable = ['descripcion', 'color'];

    // Indicar que no se está usando la marca de tiempo 'created_at' y 'updated_at'
    public $timestamps = false;

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'idEstadflujo', 'idEstadflujo');
    }



}
