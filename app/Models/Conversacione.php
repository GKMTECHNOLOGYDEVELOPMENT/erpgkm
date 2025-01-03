<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Conversacione
 * 
 * @property int $idConversacion
 * @property string|null $nombre
 * @property bool|null $esGrupo
 * @property Carbon|null $fechaCreacion
 * @property int $creadoPor
 * 
 * @property Usuario $usuario
 * @property Collection|Mensaje[] $mensajes
 * @property Collection|Usuario[] $usuarios
 *
 * @package App\Models
 */
class Conversacione extends Model
{
	protected $table = 'conversaciones';
	protected $primaryKey = 'idConversacion';
	public $timestamps = false;

	protected $casts = [
		'esGrupo' => 'bool',
		'fechaCreacion' => 'datetime',
		'creadoPor' => 'int'
	];

	protected $fillable = [
		'nombre',
		'esGrupo',
		'fechaCreacion',
		'creadoPor'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'creadoPor');
	}

	public function mensajes()
	{
		return $this->hasMany(Mensaje::class, 'idConversacion');
	}

	public function usuarios()
	{
		return $this->belongsToMany(Usuario::class, 'usuariosconversaciones', 'idConversacion', 'idUsuario')
					->withPivot('idUsuariosConversacion', 'fechaAgregado', 'rol');
	}
}
