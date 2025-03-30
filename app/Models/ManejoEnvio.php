<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManejoEnvio extends Model
{
    protected $table = 'manejo_envio';
    protected $primaryKey = 'idmanejo_envio';
    public $timestamps = true;

    protected $fillable = [
        'numero_guia',
        'agenciaEnvio',
        'agenciaRecepcion',
        'clave',
        'fecha_envio',
        'fecha_llegada_estimada',
        'idUsuario',
        'idTickets',
        'tipo'
    ];

    // Relaciones
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'idTickets', 'idTickets');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario', 'idUsuario');
    }
}
