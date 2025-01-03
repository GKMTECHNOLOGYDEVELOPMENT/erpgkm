<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tienda
 * 
 * @property int $idTienda
 * @property string|null $nombre
 * 
 * @property Collection|Subsidiario[] $subsidiarios
 *
 * @package App\Models
 */
class Tienda extends Model
{
	protected $table = 'tienda';
	protected $primaryKey = 'idTienda';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function subsidiarios()
	{
		return $this->hasMany(Subsidiario::class, 'idTienda');
	}
}
