<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Usuariosconversacione
 * 
 * @property int $idUsuariosConversacion
 * @property Carbon|null $fechaAgregado
 * @property string|null $rol
 * @property int $idConversacion
 * @property int $idUsuario
 * 
 * @property Conversacione $conversacione
 * @property Usuario $usuario
 *
 * @package App\Models
 */
class Usuariosconversacione extends Model
{
	protected $table = 'usuariosconversaciones';
	protected $primaryKey = 'idUsuariosConversacion';
	public $timestamps = false;

	protected $casts = [
		'fechaAgregado' => 'datetime',
		'idConversacion' => 'int',
		'idUsuario' => 'int'
	];

	protected $fillable = [
		'fechaAgregado',
		'rol',
		'idConversacion',
		'idUsuario'
	];

	public function conversacione()
	{
		return $this->belongsTo(Conversacione::class, 'idConversacion');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'idUsuario');
	}
}
