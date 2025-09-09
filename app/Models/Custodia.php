<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Custodia extends Model
{
    use HasFactory;

    protected $table = 'custodias';

    protected $fillable = [
        'id_ticket',
        'codigocustodias',
        'estado',
        'fecha_ingreso_custodia',
        'fecha_devolucion',
        'observaciones',
        'ubicacion_actual',
        'responsable_entrega',
        'id_responsable_recepcion',
    ];

    // Relación con Ticket
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'id_ticket', 'idTickets');
    }

    public function responsableRecepcion()
    {
        return $this->belongsTo(Usuario::class, 'id_responsable_recepcion', 'idUsuario');
    }

}
