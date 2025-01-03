<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tipoigv
 * 
 * @property int $idTipoIgv
 * @property string|null $nombre
 * 
 * @property Collection|Facturacion[] $facturacions
 *
 * @package App\Models
 */
class Tipoigv extends Model
{
	protected $table = 'tipoigv';
	protected $primaryKey = 'idTipoIgv';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function facturacions()
	{
		return $this->hasMany(Facturacion::class, 'idTipoIgv');
	}
}
