<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Equipo
 * 
 * @property int $idEquipos
 * @property int|null $marca
 * @property int|null $modelo
 * @property string|null $nserie
 * @property string|null $modalidad
 * @property int|null $idTickets
 * 
 * @property Ticket|null $ticket
 *
 * @package App\Models
 */
class Equipo extends Model
{
	protected $table = 'equipos';
	protected $primaryKey = 'idEquipos';
	public $timestamps = false;

	protected $casts = [
		'idMarca' => 'int',
		'idModelo' => 'int',
		'idCategoria' => 'int',
		'idTickets' => 'int',
		'idVisitas' => 'int'
	];
	
	protected $fillable = [
		'idMarca',
		'idModelo',
		'idCategoria',
		'idVisitas',
		'nserie',
		'modalidad',
		'idTickets'
	];
	

	public function ticket()
	{
		return $this->belongsTo(Ticket::class, 'idTickets');
	}
	public function modelo()
	{
		return $this->belongsTo(Modelo::class, 'idModelo', 'idModelo');
	}

	public function marca()
	{
		return $this->belongsTo(Marca::class, 'idMarca', 'idMarca');
	}

	public function categoria()
	{
		return $this->belongsTo(Categoria::class, 'idCategoria', 'idCategoria');
	}
}
