<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cast
 * 
 * @property int $idCast
 * @property string|null $nombre
 * @property string|null $telefono
 * @property string|null $email
 * @property string|null $direccion
 * @property string|null $ruc
 * @property string|null $provincia
 * 
 * @property Collection|Contacto[] $contactos
 *
 * @package App\Models
 */
class Cast extends Model
{
	protected $table = 'cast';
	protected $primaryKey = 'idCast';
	public $timestamps = false;

	protected $fillable = [
		'nombre',
		'telefono',
		'email',
		'direccion',
		'ruc',
		'provincia'
	];

	public function contactos()
	{
		return $this->hasMany(Contacto::class, 'idCast');
	}
}
