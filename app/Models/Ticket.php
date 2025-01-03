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
 * @property varbinary|null $IdTienda
 * @property string|null $numero_ticket
 * @property int|null $tipoServicio
 * @property Carbon|null $fecha_creacion
 * @property int|null $idTtipotickets
 * @property int|null $idEstadoots
 * @property int $idTecnico
 * @property int $idUsuario
 * 
 * @property Tipoticket|null $tipoticket
 * @property EstadoOt|null $estado_ot
 * @property Cliente $cliente
 * @property Clientegeneral $clientegeneral
 * @property Usuario $usuario
 * @property Collection|Cotizacione[] $cotizaciones
 * @property Collection|Equipo[] $equipos
 * @property Collection|Firma[] $firmas
 * @property Collection|LevantamientoInformacion[] $levantamiento_informacions
 * @property Collection|Proyecto[] $proyectos
 * @property Collection|SoporteOnsite[] $soporte_onsites
 * @property Collection|Suministro[] $suministros
 * @property Collection|TipoVisitum[] $tipo_visita
 * @property Collection|TransicionOt[] $transicion_ots
 * @property Collection|TrasladoEquipo[] $traslado_equipos
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
		'IdTienda' => 'varbinary',
		'tipoServicio' => 'int',
		'fecha_creacion' => 'datetime',
		'idTtipotickets' => 'int',
		'idEstadoots' => 'int',
		'idTecnico' => 'int',
		'idUsuario' => 'int'
	];

	protected $fillable = [
		'idClienteGeneral',
		'idCliente',
		'IdTienda',
		'numero_ticket',
		'tipoServicio',
		'fecha_creacion',
		'idTtipotickets',
		'idEstadoots',
		'idTecnico',
		'idUsuario'
	];

	public function tipoticket()
	{
		return $this->belongsTo(Tipoticket::class, 'idTtipotickets');
	}

	public function estado_ot()
	{
		return $this->belongsTo(EstadoOt::class, 'idEstadoots');
	}

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

	public function cotizaciones()
	{
		return $this->hasMany(Cotizacione::class, 'idTickets');
	}

	public function equipos()
	{
		return $this->hasMany(Equipo::class, 'idTickets');
	}

	public function firmas()
	{
		return $this->hasMany(Firma::class, 'idTickets');
	}

	public function levantamiento_informacions()
	{
		return $this->hasMany(LevantamientoInformacion::class, 'idTickets');
	}

	public function proyectos()
	{
		return $this->hasMany(Proyecto::class, 'idTickets');
	}

	public function soporte_onsites()
	{
		return $this->hasMany(SoporteOnsite::class, 'idTickets');
	}

	public function suministros()
	{
		return $this->hasMany(Suministro::class, 'idTickets');
	}

	public function tipo_visita()
	{
		return $this->hasMany(TipoVisitum::class, 'idTickets');
	}

	public function transicion_ots()
	{
		return $this->hasMany(TransicionOt::class, 'idTickets');
	}

	public function traslado_equipos()
	{
		return $this->hasMany(TrasladoEquipo::class, 'idTickets');
	}

	public function visitas()
	{
		return $this->hasMany(Visita::class, 'idTickets');
	}
}
