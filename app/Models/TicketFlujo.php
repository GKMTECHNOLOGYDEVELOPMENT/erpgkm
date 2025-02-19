<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketFlujo extends Model
{
    use HasFactory;

    // Nombre de la tabla, ya que Laravel por convención espera el plural de la clase (ticketflujos)
    protected $table = 'ticketflujo';

    // Definir los campos que son asignables en masa
    protected $fillable = [
        'idTicket',
        'idEstadflujo',
        'fecha_creacion'
    ];

    // Relación con la tabla tickets
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'idTicket', 'idTickets');
    }

    // Relación con la tabla estado_flujo
    public function estadoFlujo()
    {
        return $this->belongsTo(EstadoFlujo::class, 'idEstadflujo', 'idEstadflujo');
    }
}
