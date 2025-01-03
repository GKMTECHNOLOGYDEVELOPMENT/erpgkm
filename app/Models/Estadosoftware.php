<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Estadosoftware
 * 
 * @property int $idEstadoSoftware
 * @property string|null $nombre
 * 
 * @property Collection|ProyectoSoftware[] $proyecto_software
 * @property Collection|TareasParticipante[] $tareas_participantes
 *
 * @package App\Models
 */
class Estadosoftware extends Model
{
	protected $table = 'estadosoftware';
	protected $primaryKey = 'idEstadoSoftware';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function proyecto_software()
	{
		return $this->hasMany(ProyectoSoftware::class, 'idEstadoSoftware');
	}

	public function tareas_participantes()
	{
		return $this->hasMany(TareasParticipante::class, 'idEstadoSoftware');
	}
}
