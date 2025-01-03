<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class LevantamientoInformacion
 * 
 * @property int $idLevatamientoinformacion
 * @property string|null $diagnostico
 * @property int|null $suministros
 * @property string|null $trabajo_realizar
 * @property int|null $idTickets
 * @property int $idNivelIncidencia
 * 
 * @property Ticket|null $ticket
 * @property Nivelincidencium $nivelincidencium
 *
 * @package App\Models
 */
class LevantamientoInformacion extends Model
{
	protected $table = 'levantamiento_informacion';
	protected $primaryKey = 'idLevatamientoinformacion';
	public $timestamps = false;

	protected $casts = [
		'suministros' => 'int',
		'idTickets' => 'int',
		'idNivelIncidencia' => 'int'
	];

	protected $fillable = [
		'diagnostico',
		'suministros',
		'trabajo_realizar',
		'idTickets',
		'idNivelIncidencia'
	];

	public function ticket()
	{
		return $this->belongsTo(Ticket::class, 'idTickets');
	}

	public function nivelincidencium()
	{
		return $this->belongsTo(Nivelincidencium::class, 'idNivelIncidencia');
	}
}
