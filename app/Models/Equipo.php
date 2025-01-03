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
		'marca' => 'int',
		'modelo' => 'int',
		'idTickets' => 'int'
	];

	protected $fillable = [
		'marca',
		'modelo',
		'nserie',
		'modalidad',
		'idTickets'
	];

	public function ticket()
	{
		return $this->belongsTo(Ticket::class, 'idTickets');
	}
}
