<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Contacto
 * 
 * @property int $idContacto
 * @property string|null $nombre
 * @property int $idCast
 * 
 * @property Cast $cast
 * @property Collection|Movimientoarticulo[] $movimientoarticulos
 *
 * @package App\Models
 */
class Contacto extends Model
{
	protected $table = 'contacto';
	protected $primaryKey = 'idContacto';
	public $timestamps = false;

	protected $casts = [
		'idCast' => 'int'
	];

	protected $fillable = [
		'nombre',
		'idCast'
	];

	public function cast()
	{
		return $this->belongsTo(Cast::class, 'idCast');
	}

	public function movimientoarticulos()
	{
		return $this->hasMany(Movimientoarticulo::class, 'idContacto');
	}
}
