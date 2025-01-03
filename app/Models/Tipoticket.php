<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tipoticket
 * 
 * @property int $idTtipotickets
 * @property string|null $nombre
 * 
 * @property Collection|Ticket[] $tickets
 *
 * @package App\Models
 */
class Tipoticket extends Model
{
	protected $table = 'tipotickets';
	protected $primaryKey = 'idTtipotickets';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function tickets()
	{
		return $this->hasMany(Ticket::class, 'idTtipotickets');
	}
}
