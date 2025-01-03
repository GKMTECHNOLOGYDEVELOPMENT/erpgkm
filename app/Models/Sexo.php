<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sexo
 * 
 * @property int $idSexo
 * @property string|null $nombre
 * 
 * @property Collection|Usuario[] $usuarios
 *
 * @package App\Models
 */
class Sexo extends Model
{
	protected $table = 'sexo';
	protected $primaryKey = 'idSexo';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function usuarios()
	{
		return $this->hasMany(Usuario::class, 'idSexo');
	}
}
