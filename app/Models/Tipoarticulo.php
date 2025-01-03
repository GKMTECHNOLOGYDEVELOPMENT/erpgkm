<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tipoarticulo
 * 
 * @property int $idTipoArticulo
 * @property string|null $nombre
 * 
 * @property Collection|Articulo[] $articulos
 *
 * @package App\Models
 */
class Tipoarticulo extends Model
{
	protected $table = 'tipoarticulos';
	protected $primaryKey = 'idTipoArticulo';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function articulos()
	{
		return $this->hasMany(Articulo::class, 'idTipoArticulo');
	}
}
