<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Lectura
 * 
 * @property int $idLecturas
 * @property Carbon|null $fechaLectura
 * @property int $idMensaje
 * @property int $idUsuario
 * 
 * @property Mensaje $mensaje
 * @property Usuario $usuario
 *
 * @package App\Models
 */
class Lectura extends Model
{
	protected $table = 'lecturas';
	protected $primaryKey = 'idLecturas';
	public $timestamps = false;

	protected $casts = [
		'fechaLectura' => 'datetime',
		'idMensaje' => 'int',
		'idUsuario' => 'int'
	];

	protected $fillable = [
		'fechaLectura',
		'idMensaje',
		'idUsuario'
	];

	public function mensaje()
	{
		return $this->belongsTo(Mensaje::class, 'idMensaje');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'idUsuario');
	}
}
