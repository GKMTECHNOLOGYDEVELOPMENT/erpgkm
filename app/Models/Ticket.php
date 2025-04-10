<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Ticket
 *
 * @property int $idTickets
 * @property int $idClienteGeneral
 * @property int $idCliente
 * @property string|null $numero_ticket
 * @property int|null $tipoServicio
 * @property Carbon|null $fecha_creacion
 * @property int|null $idTipotickets
 * @property int|null $idEstadoots
 * @property int $idTecnico
 * @property int $idUsuario
 * @property int $idTienda
 * @property string|null $fallaReportada
 * @property string|null $esRecojo
 * @property string|null $direccion
 * @property int|null $idMarca
 * @property int|null $idModelo
 * @property string|null $serie
 * @property Carbon|null $fechaCompra
 * @property string|null $lat
 * @property string|null $lng
 *
 * @property Tipoticket|null $tipoticket
 * @property EstadoOt|null $estado_ot
 * @property Cliente $cliente
 * @property Clientegeneral $clientegeneral
 * @property Usuario $usuario
 * @property Tienda $tienda
 * @property Marca|null $marca
 * @property Modelo|null $modelo
 * @property Collection|Cotizacione[] $cotizaciones
 * @property Collection|Detalleticket[] $detalletickets
 * @property Collection|Equipo[] $equipos
 * @property Collection|Firma[] $firmas
 * @property Collection|Proyecto[] $proyectos
 * @property Collection|Suministro[] $suministros
 * @property Collection|Ticketapoyo[] $ticketapoyos
 * @property Collection|TransicionStatusTicket[] $transicion_status_tickets
 * @property Collection|Visita[] $visitas
 *
 * @package App\Models
 */
class Ticket extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'idTickets';
    public $timestamps = false;

    protected $casts = [
        'idClienteGeneral' => 'int',
        'idCliente' => 'int',
        'tipoServicio' => 'int',
        'fecha_creacion' => 'datetime',
        'idTipotickets' => 'int',
        'idEstadoots' => 'int',
        'idTecnico' => 'int',
        'idUsuario' => 'int',
        'idTienda' => 'int',
        'idMarca' => 'int',
        'idModelo' => 'int',
        'idTicketFlujo' => 'int',
        'idEncargadoEnvio_Lima' => 'int',
        'fechaCompra' => 'datetime'
    ];

    protected $fillable = [
        'idClienteGeneral',
        'idCliente',
        'numero_ticket',
        'tipoServicio',
        'fecha_creacion',
        'idTipotickets',
        'idEstadoots',
        'idTicketFlujo',
        'idTecnico',
        'idUsuario',
        'idTienda',
        'fallaReportada',
        'linkubicacion',
        'esRecojo',
        'envio',
        'idEncargadoEnvio_Provincia',
        'direccion',
        'idMarca',
        'idModelo',
        'serie',
        'fechaCompra',
        'lat',
        'lng'
    ];

    public function tipoticket()
    {
        return $this->belongsTo(Tipoticket::class, 'idTipotickets');
    }

    public function estado_ot()
    {
        return $this->belongsTo(EstadoOt::class, 'idEstadoots');
    }

    // public function estadoflujo()
    // {
    //     return $this->belongsTo(EstadoFlujo::class, 'idEstadflujo');
    // }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'idCliente');
    }

    public function clientegeneral()
    {
        return $this->belongsTo(Clientegeneral::class, 'idClienteGeneral');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'idUsuario');
    }

    public function tienda()
    {
        return $this->belongsTo(Tienda::class, 'idTienda');
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'idMarca');
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class, 'idModelo');
    }

    public function cotizaciones()
    {
        return $this->hasMany(Cotizacione::class, 'idTickets');
    }

    public function detalletickets()
    {
        return $this->hasMany(Detalleticket::class, 'idTickets');
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'idTickets');
    }

    public function firmas()
    {
        return $this->hasMany(Firma::class, 'idTickets');
    }

    public function proyectos()
    {
        return $this->hasMany(Proyecto::class, 'idTickets');
    }

    public function suministros()
    {
        return $this->hasMany(Suministro::class, 'idTickets');
    }

    public function ticketapoyos()
    {
        return $this->hasMany(Ticketapoyo::class, 'idTicket');
    }

    public function transicion_status_tickets()
    {
        return $this->hasMany(TransicionStatusTicket::class, 'idTickets');
    }

    public function visitas()
    {
        return $this->hasMany(Visita::class, 'idTickets');
    }
    // Relación con el modelo Usuario (Técnico)
    public function tecnico()
    {
        return $this->belongsTo(Usuario::class, 'idTecnico', 'idUsuario');
    }
    // Relación con el modelo TipoServicio
    public function tiposervicio()
    {
        // La relación es belongsTo porque un Ticket "pertenece" a un TipoServicio
        return $this->belongsTo(Tiposervicio::class, 'tipoServicio', 'idTipoServicio');
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'idRol', 'id'); // Relación con la tabla "rol"
    }

    // Define la relación con la tabla ticketflujo
    public function ticketflujo()
    {
        return $this->belongsTo(TicketFlujo::class, 'idTicketFlujo', 'idTicketFlujo');
    }


    // Relación con el modelo CondicionesTicket
    public function condicionesTickets()
    {
        return $this->hasMany(CondicionesTicket::class, 'idTickets', 'idTickets');
    }

    public function seleccionarVisita()
    {
        return $this->hasOne(SeleccionarVisita::class, 'idTickets', 'idTickets');
    }

    public function manejoEnvio()
    {
        return $this->hasOne(ManejoEnvio::class, 'idTickets', 'idTickets');
    }
}
