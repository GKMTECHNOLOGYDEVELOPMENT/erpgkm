<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Permiso
 * 
 * @property int $idPermisos
 * @property string|null $nombre
 * @property int $idRol
 * 
 * @property Rol $rol
 *
 * @package App\Models
 */
class Permiso extends Model
{
	protected $table = 'permisos';
	protected $primaryKey = 'idPermisos';
	public $timestamps = false;

	protected $casts = [
		'idRol' => 'int'
	];

	protected $fillable = [
		'nombre',
		'idRol'
	];

	public function rol()
	{
		return $this->belongsTo(Rol::class, 'idRol');
	}
}
