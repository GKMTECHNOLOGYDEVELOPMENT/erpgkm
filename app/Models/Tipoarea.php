<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tipoarea
 * 
 * @property int $idTipoArea
 * @property string|null $nombre
 * 
 * @property Collection|Ticketsoporte[] $ticketsoportes
 *
 * @package App\Models
 */
class Tipoarea extends Model
{
	protected $table = 'tipoarea';
	protected $primaryKey = 'idTipoArea';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function ticketsoportes()
	{
		return $this->hasMany(Ticketsoporte::class, 'idTipoArea');
	}
}
