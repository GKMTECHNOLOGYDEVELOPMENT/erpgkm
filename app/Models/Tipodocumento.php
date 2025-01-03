<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tipodocumento
 * 
 * @property int $idTipoDocumento
 * @property string|null $nombre
 * 
 * @property Collection|Proveedore[] $proveedores
 * @property Collection|Usuario[] $usuarios
 *
 * @package App\Models
 */
class Tipodocumento extends Model
{
	protected $table = 'tipodocumento';
	protected $primaryKey = 'idTipoDocumento';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function proveedores()
	{
		return $this->hasMany(Proveedore::class, 'idTipoDocumento');
	}

	public function usuarios()
	{
		return $this->hasMany(Usuario::class, 'idTipoDocumento');
	}
}
