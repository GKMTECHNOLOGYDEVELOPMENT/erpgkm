<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProyectoSoftware
 * 
 * @property int $idProyectosoftware
 * @property string|null $nombre
 * @property Carbon|null $fechainicio
 * @property Carbon|null $fechafinal
 * @property int|null $idImportancia
 * @property int $idEstadoSoftware
 * 
 * @property Importancium|null $importancium
 * @property Estadosoftware $estadosoftware
 * @property Collection|ProyectoParticipante[] $proyecto_participantes
 * @property Collection|Reunion[] $reunions
 * @property Collection|ReunionParticipante[] $reunion_participantes
 * @property Collection|Tarea[] $tareas
 *
 * @package App\Models
 */
class ProyectoSoftware extends Model
{
	protected $table = 'proyecto_software';
	protected $primaryKey = 'idProyectosoftware';
	public $timestamps = false;

	protected $casts = [
		'fechainicio' => 'datetime',
		'fechafinal' => 'datetime',
		'idImportancia' => 'int',
		'idEstadoSoftware' => 'int'
	];

	protected $fillable = [
		'nombre',
		'fechainicio',
		'fechafinal',
		'idImportancia',
		'idEstadoSoftware'
	];

	public function importancium()
	{
		return $this->belongsTo(Importancium::class, 'idImportancia');
	}

	public function estadosoftware()
	{
		return $this->belongsTo(Estadosoftware::class, 'idEstadoSoftware');
	}

	public function proyecto_participantes()
	{
		return $this->hasMany(ProyectoParticipante::class, 'idProyectosoftware');
	}

	public function reunions()
	{
		return $this->hasMany(Reunion::class, 'idProyectosoftware');
	}

	public function reunion_participantes()
	{
		return $this->hasMany(ReunionParticipante::class, 'idProyectosoftware');
	}

	public function tareas()
	{
		return $this->hasMany(Tarea::class, 'idProyectosoftware');
	}
}
