<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransicionStatusTicket extends Model
{
    protected $table = 'transicion_status_ticket';
    protected $primaryKey = 'idTransicionStatus';
    public $timestamps = false;

    protected $fillable = [
        'idTickets',
        'idVisitas',
        'idEstadoots',
        'justificacion',
        'fechaRegistro',
        'estado'
    ];

    public function estado_ot()
    {
        return $this->belongsTo(EstadoOt::class, 'idEstadoots', 'idEstadoots');
    }
    

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'idTickets', 'idTickets');
    }

    public function visita()
    {
        return $this->belongsTo(Visita::class, 'idVisitas');
    }
}
