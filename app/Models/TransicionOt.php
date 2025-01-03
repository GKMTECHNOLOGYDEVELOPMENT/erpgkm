<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TransicionOt
 * 
 * @property int $idTransicionots
 * @property string|null $justificacion
 * @property Carbon|null $fecha_registro
 * @property bool|null $activo
 * @property int|null $idTickets
 * 
 * @property Ticket|null $ticket
 *
 * @package App\Models
 */
class TransicionOt extends Model
{
	protected $table = 'transicion_ots';
	protected $primaryKey = 'idTransicionots';
	public $timestamps = false;

	protected $casts = [
		'fecha_registro' => 'datetime',
		'activo' => 'bool',
		'idTickets' => 'int'
	];

	protected $fillable = [
		'justificacion',
		'fecha_registro',
		'activo',
		'idTickets'
	];

	public function ticket()
	{
		return $this->belongsTo(Ticket::class, 'idTickets');
	}
}
