<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sucursal
 * 
 * @property int $idSucursal
 * @property string|null $ruc
 * @property string|null $nombre
 * @property string|null $direccion
 * @property bool|null $estado
 * @property string|null $telefono
 * 
 * @property Collection|Proveedore[] $proveedores
 * @property Collection|Ubicacion[] $ubicacions
 * @property Collection|Usuario[] $usuarios
 *
 * @package App\Models
 */
class Sucursal extends Model
{
	protected $table = 'sucursal';
	protected $primaryKey = 'idSucursal';
	public $timestamps = false;

	protected $casts = [
		'estado' => 'bool'
	];

	protected $fillable = [
		'ruc',
		'nombre',
		'direccion',
		'estado',
		'telefono'
	];

	public function proveedores()
	{
		return $this->hasMany(Proveedore::class, 'idSucursal');
	}

	public function ubicacions()
	{
		return $this->hasMany(Ubicacion::class, 'idSucursal');
	}

	public function usuarios()
	{
		return $this->hasMany(Usuario::class, 'idSucursal');
	}
}
