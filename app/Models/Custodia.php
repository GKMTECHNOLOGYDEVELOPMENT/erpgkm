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
        'idcliente',
        'numero_ticket',
        'idMarca',
        'idModelo',
        'serie',
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

    public function custodiaUbicacion()
    {
        return $this->hasOne(CustodiaUbicacion::class, 'idCustodia', 'id');
    }

    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idcliente', 'idCliente');
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'idMarca', 'idMarca');
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class, 'idModelo', 'idModelo');
    }

}
