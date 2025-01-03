<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Mensaje
 * 
 * @property int $idMensaje
 * @property string|null $textoMensaje
 * @property string|null $archivoUrl
 * @property Carbon|null $fechaEnvio
 * @property int $idConversacion
 * @property int $idUsuario
 * @property int $idTipoMensaje
 * 
 * @property Conversacione $conversacione
 * @property Usuario $usuario
 * @property Tipomensaje $tipomensaje
 * @property Collection|Archivo[] $archivos
 * @property Collection|Lectura[] $lecturas
 *
 * @package App\Models
 */
class Mensaje extends Model
{
	protected $table = 'mensajes';
	protected $primaryKey = 'idMensaje';
	public $timestamps = false;

	protected $casts = [
		'fechaEnvio' => 'datetime',
		'idConversacion' => 'int',
		'idUsuario' => 'int',
		'idTipoMensaje' => 'int'
	];

	protected $fillable = [
		'textoMensaje',
		'archivoUrl',
		'fechaEnvio',
		'idConversacion',
		'idUsuario',
		'idTipoMensaje'
	];

	public function conversacione()
	{
		return $this->belongsTo(Conversacione::class, 'idConversacion');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'idUsuario');
	}

	public function tipomensaje()
	{
		return $this->belongsTo(Tipomensaje::class, 'idTipoMensaje');
	}

	public function archivos()
	{
		return $this->hasMany(Archivo::class, 'idMensaje');
	}

	public function lecturas()
	{
		return $this->hasMany(Lectura::class, 'idMensaje');
	}
}
