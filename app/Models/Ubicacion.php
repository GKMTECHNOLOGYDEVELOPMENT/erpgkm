<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Ubicacion
 * 
 * @property int $idUbicacion
 * @property string|null $nombre
 * @property int $idSucursal
 * 
 * @property Sucursal $sucursal
 * @property Collection|Movimientoarticulo[] $movimientoarticulos
 * @property Collection|Ordenesarticulo[] $ordenesarticulos
 * @property Collection|Ubicacione[] $ubicaciones
 *
 * @package App\Models
 */
class Ubicacion extends Model
{
	protected $table = 'ubicacion';
	protected $primaryKey = 'idUbicacion';
	public $timestamps = false;

	protected $casts = [
		'idSucursal' => 'int'
	];

	protected $fillable = [
		'nombre',
		'idSucursal'
	];

	public function sucursal()
	{
		return $this->belongsTo(Sucursal::class, 'idSucursal');
	}

	public function movimientoarticulos()
	{
		return $this->hasMany(Movimientoarticulo::class, 'idUbicacion');
	}

	public function ordenesarticulos()
	{
		return $this->hasMany(Ordenesarticulo::class, 'idUbicacion');
	}

	public function ubicaciones()
	{
		return $this->hasMany(Ubicacione::class, 'idUbicacion');
	}
}
