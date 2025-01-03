<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SoporteOnsite
 * 
 * @property int $idSoporteonsite
 * @property string|null $diagnostico
 * @property string|null $servicioRealizado
 * @property string|null $observaciones
 * @property int|null $suministros
 * @property int|null $idTickets
 * 
 * @property Ticket|null $ticket
 *
 * @package App\Models
 */
class SoporteOnsite extends Model
{
	protected $table = 'soporte_onsite';
	protected $primaryKey = 'idSoporteonsite';
	public $timestamps = false;

	protected $casts = [
		'suministros' => 'int',
		'idTickets' => 'int'
	];

	protected $fillable = [
		'diagnostico',
		'servicioRealizado',
		'observaciones',
		'suministros',
		'idTickets'
	];

	public function ticket()
	{
		return $this->belongsTo(Ticket::class, 'idTickets');
	}
}
