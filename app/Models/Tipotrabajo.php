<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tipotrabajo
 * 
 * @property int $idTipoTrabajo
 * @property string|null $nombre
 * 
 * @property Collection|Proyecto[] $proyectos
 *
 * @package App\Models
 */
class Tipotrabajo extends Model
{
	protected $table = 'tipotrabajo';
	protected $primaryKey = 'idTipoTrabajo';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function proyectos()
	{
		return $this->hasMany(Proyecto::class, 'idTipoTrabajo');
	}
}
