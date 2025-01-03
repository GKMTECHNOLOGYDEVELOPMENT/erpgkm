<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Subsidiario
 * 
 * @property int $idSubsidiarios
 * @property string|null $ruc
 * @property string|null $nombre
 * @property string|null $nombre_contacto
 * @property string|null $celular
 * @property string|null $email
 * @property string|null $direccion
 * @property string|null $referencia
 * @property int $idTienda
 * 
 * @property Tienda $tienda
 * @property Collection|Cotizacione[] $cotizaciones
 *
 * @package App\Models
 */
class Subsidiario extends Model
{
	protected $table = 'subsidiarios';
	protected $primaryKey = 'idSubsidiarios';
	public $timestamps = false;

	protected $casts = [
		'idTienda' => 'int'
	];

	protected $fillable = [
		'ruc',
		'nombre',
		'nombre_contacto',
		'celular',
		'email',
		'direccion',
		'referencia',
		'idTienda'
	];

	public function tienda()
	{
		return $this->belongsTo(Tienda::class, 'idTienda');
	}

	public function cotizaciones()
	{
		return $this->hasMany(Cotizacione::class, 'idSubsidiarios');
	}
}
