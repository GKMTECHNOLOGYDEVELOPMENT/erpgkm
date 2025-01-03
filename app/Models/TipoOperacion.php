<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TipoOperacion
 * 
 * @property int $idTipoOperacion
 * @property string|null $nombre
 * 
 * @property Collection|Facturacion[] $facturacions
 *
 * @package App\Models
 */
class TipoOperacion extends Model
{
	protected $table = 'tipo_operacion';
	protected $primaryKey = 'idTipoOperacion';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function facturacions()
	{
		return $this->hasMany(Facturacion::class, 'idTipoOperacion');
	}
}
