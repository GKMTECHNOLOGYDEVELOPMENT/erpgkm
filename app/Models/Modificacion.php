<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modificacion extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'modificaciones';

    // Los campos que pueden ser asignados en masa
    protected $fillable = [
        'idTickets',
        'campo',
        'valor_antiguo',
        'valor_nuevo',
        'usuario',
    ];

    // Definir la relación con la tabla de órdenes (si es necesario)
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'idTickets');
    }
}
