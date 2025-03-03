<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagenApoyoSmart extends Model
{
    use HasFactory;

    // Si el nombre de la tabla en la base de datos no es el plural del nombre del modelo, puedes definirlo manualmente:
    protected $table = 'imagenapoyosmart';

    // Si el nombre de la clave primaria no es el estándar (id), puedes definirlo:
    protected $primaryKey = 'idImagenApoyoSmart';

    // Definir los campos que pueden ser asignados masivamente
    protected $fillable = [
        'imagen',
        'idVisitas',
        'descripcion',
    ];

    // Definir la relación con el modelo Visita (la relación es 'belongsTo' ya que idVisitas es una clave foránea en esta tabla)
    public function visita()
    {
        return $this->belongsTo(Visita::class, 'idVisitas');
    }
}
