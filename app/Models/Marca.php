<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Marca
 * 
 * @property int $idMarca
 * @property string|null $nombre
 * 
 * @property Collection|Modelo[] $modelos
 *
 * @package App\Models
 */
class Marca extends Model
{
	protected $table = 'marca';
	protected $primaryKey = 'idMarca';
	public $timestamps = false;

	protected $fillable = [
		'nombre',
		'estado'
	];

	public function modelos()
	{
		return $this->hasMany(Modelo::class, 'idMarca');
	}
}
