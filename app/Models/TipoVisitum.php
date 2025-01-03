<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoVisitum
 * 
 * @property int $idTipovisita
 * @property string|null $nombre
 * @property int|null $idTickets
 * 
 * @property Ticket|null $ticket
 * @property AnexosVisita $anexos_visita
 *
 * @package App\Models
 */
class TipoVisitum extends Model
{
	protected $table = 'tipo_visita';
	protected $primaryKey = 'idTipovisita';
	public $timestamps = false;

	protected $casts = [
		'idTickets' => 'int'
	];

	protected $fillable = [
		'nombre',
		'idTickets'
	];

	public function ticket()
	{
		return $this->belongsTo(Ticket::class, 'idTickets');
	}

	public function anexos_visita()
	{
		return $this->hasOne(AnexosVisita::class, 'idTipovisita');
	}
}
