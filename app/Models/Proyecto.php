<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Proyecto
 * 
 * @property int $idProyecto
 * @property string|null $nombre
 * @property Carbon|null $fechaEjecucion
 * @property Carbon|null $fechaFinal
 * @property bool|null $estado
 * @property int $idTickets
 * @property int $idCliente
 * @property int $idEncargado
 * @property int $idTipoTrabajo
 * 
 * @property Ticket $ticket
 * @property Cliente $cliente
 * @property Usuario $usuario
 * @property Tipotrabajo $tipotrabajo
 * @property Collection|Articulosproyecto[] $articulosproyectos
 * @property Collection|Tecnicoproyecto[] $tecnicoproyectos
 *
 * @package App\Models
 */
class Proyecto extends Model
{
	protected $table = 'proyecto';
	protected $primaryKey = 'idProyecto';
	public $timestamps = false;

	protected $casts = [
		'fechaEjecucion' => 'datetime',
		'fechaFinal' => 'datetime',
		'estado' => 'bool',
		'idTickets' => 'int',
		'idCliente' => 'int',
		'idEncargado' => 'int',
		'idTipoTrabajo' => 'int'
	];

	protected $fillable = [
		'nombre',
		'fechaEjecucion',
		'fechaFinal',
		'estado',
		'idTickets',
		'idCliente',
		'idEncargado',
		'idTipoTrabajo'
	];

	public function ticket()
	{
		return $this->belongsTo(Ticket::class, 'idTickets');
	}

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'idCliente');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'idEncargado');
	}

	public function tipotrabajo()
	{
		return $this->belongsTo(Tipotrabajo::class, 'idTipoTrabajo');
	}

	public function articulosproyectos()
	{
		return $this->hasMany(Articulosproyecto::class, 'idProyecto');
	}

	public function tecnicoproyectos()
	{
		return $this->hasMany(Tecnicoproyecto::class, 'idProyecto');
	}
}
