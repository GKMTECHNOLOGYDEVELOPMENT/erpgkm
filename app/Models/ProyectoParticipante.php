<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProyectoParticipante
 * 
 * @property int $idProyectoparticipantes
 * @property int|null $idProyectosoftware
 * @property int|null $idRolsoftware
 * @property int $idUsuario
 * 
 * @property ProyectoSoftware|null $proyecto_software
 * @property RolSoftware|null $rol_software
 * @property Usuario $usuario
 * @property Collection|Reunion[] $reunions
 *
 * @package App\Models
 */
class ProyectoParticipante extends Model
{
	protected $table = 'proyecto_participantes';
	protected $primaryKey = 'idProyectoparticipantes';
	public $timestamps = false;

	protected $casts = [
		'idProyectosoftware' => 'int',
		'idRolsoftware' => 'int',
		'idUsuario' => 'int'
	];

	protected $fillable = [
		'idProyectosoftware',
		'idRolsoftware',
		'idUsuario'
	];

	public function proyecto_software()
	{
		return $this->belongsTo(ProyectoSoftware::class, 'idProyectosoftware');
	}

	public function rol_software()
	{
		return $this->belongsTo(RolSoftware::class, 'idRolsoftware');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'idUsuario');
	}

	public function reunions()
	{
		return $this->hasMany(Reunion::class, 'idProyectoparticipantes');
	}
}
