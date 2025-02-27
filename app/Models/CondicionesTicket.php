<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CondicionesTicket extends Model
{
    use HasFactory;

    // Definir el nombre de la tabla si no sigue la convención de plural
    protected $table = 'condicionesticket';

    public $timestamps = false;

    // Definir la clave primaria
    protected $primaryKey = 'idCondicionesticket';

    // Si la clave primaria no es un incremento automático
    public $incrementing = true;

    // Si el campo 'fecha_condicion' usa formato datetime
    protected $dates = ['fecha_condicion'];

    // Asegurarse de que estos campos son asignables
    protected $fillable = [
        'idTickets', 
        'idVisitas', 
        'titular', 
        'nombre', 
        'dni', 
        'telefono', 
        'servicio', 
        'motivo', 
        'fecha_condicion',
        'imagen'
    ];

    // Relación con el modelo Ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'idTickets', 'idTickets');
    }

    // Relación con el modelo Visita
    public function visita()
    {
        return $this->belongsTo(Visita::class, 'idVisitas', 'idVisitas');
    }
}
