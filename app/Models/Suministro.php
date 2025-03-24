<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Suministro
 * 
 * @property int $idSuministros
 * @property int|null $cantidad
 * @property int|null $idTickets
 * 
 * @property Ticket|null $ticket
 *
 * @package App\Models
 */
class Suministro extends Model
{
	protected $table = 'suministros';
	protected $primaryKey = 'idSuministros';
	public $timestamps = false;

	protected $casts = [
		'cantidad' => 'int',
		'idTickets' => 'int',
		'idArticulos' => 'int',
		'idVisitas' => 'int'
	];

	protected $fillable = [
		'cantidad',
		'idTickets',
		'idArticulos',
		'idVisitas'
	];

	public function ticket()
	{
		return $this->belongsTo(Ticket::class, 'idTickets');
	}
}
