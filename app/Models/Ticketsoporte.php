<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Ticketsoporte
 * 
 * @property int $idTicketSoporte
 * @property string|null $razon
 * @property Carbon|null $fecha
 * @property bool|null $estado
 * @property int $idTipoUsuarioSoporte
 * @property int $idTipoPrioridad
 * @property int $idTipoArea
 * @property int $idUsuario
 * 
 * @property Tipousuariosoporte $tipousuariosoporte
 * @property Tipoprioridad $tipoprioridad
 * @property Tipoarea $tipoarea
 * @property Usuario $usuario
 * @property Collection|Solucion[] $solucions
 *
 * @package App\Models
 */
class Ticketsoporte extends Model
{
	protected $table = 'ticketsoporte';
	protected $primaryKey = 'idTicketSoporte';
	public $timestamps = false;

	protected $casts = [
		'fecha' => 'datetime',
		'estado' => 'bool',
		'idTipoUsuarioSoporte' => 'int',
		'idTipoPrioridad' => 'int',
		'idTipoArea' => 'int',
		'idUsuario' => 'int'
	];

	protected $fillable = [
		'razon',
		'fecha',
		'estado',
		'idTipoUsuarioSoporte',
		'idTipoPrioridad',
		'idTipoArea',
		'idUsuario'
	];

	public function tipousuariosoporte()
	{
		return $this->belongsTo(Tipousuariosoporte::class, 'idTipoUsuarioSoporte');
	}

	public function tipoprioridad()
	{
		return $this->belongsTo(Tipoprioridad::class, 'idTipoPrioridad');
	}

	public function tipoarea()
	{
		return $this->belongsTo(Tipoarea::class, 'idTipoArea');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'idUsuario');
	}

	public function solucions()
	{
		return $this->hasMany(Solucion::class, 'idTicketSoporte');
	}
}
