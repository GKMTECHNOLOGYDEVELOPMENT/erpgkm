<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Categorium
 * 
 * @property int $idCategoria
 * @property string|null $nombre
 * 
 * @property Collection|Modelo[] $modelos
 *
 * @package App\Models
 */
class Categorium extends Model
{
	protected $table = 'categoria';
	protected $primaryKey = 'idCategoria';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function modelos()
	{
		return $this->hasMany(Modelo::class, 'idCategoria');
	}
}
