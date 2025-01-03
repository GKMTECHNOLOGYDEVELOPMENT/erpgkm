<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TrasladoEquipo
 * 
 * @property int $idTrasladoEquipo
 * @property string|null $diagnostico
 * @property string|null $servicioRealizado
 * @property int|null $suministros
 * @property string|null $observaciones
 * @property int|null $idTickets
 * 
 * @property Ticket|null $ticket
 *
 * @package App\Models
 */
class TrasladoEquipo extends Model
{
	protected $table = 'traslado_equipo';
	protected $primaryKey = 'idTrasladoEquipo';
	public $timestamps = false;

	protected $casts = [
		'suministros' => 'int',
		'idTickets' => 'int'
	];

	protected $fillable = [
		'diagnostico',
		'servicioRealizado',
		'suministros',
		'observaciones',
		'idTickets'
	];

	public function ticket()
	{
		return $this->belongsTo(Ticket::class, 'idTickets');
	}
}
