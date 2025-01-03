<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tipomensaje
 * 
 * @property int $idTipoMensaje
 * @property string|null $nombre
 * 
 * @property Collection|Mensaje[] $mensajes
 *
 * @package App\Models
 */
class Tipomensaje extends Model
{
	protected $table = 'tipomensaje';
	protected $primaryKey = 'idTipoMensaje';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function mensajes()
	{
		return $this->hasMany(Mensaje::class, 'idTipoMensaje');
	}
}
