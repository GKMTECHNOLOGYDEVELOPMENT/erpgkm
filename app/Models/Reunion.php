<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Reunion
 * 
 * @property int $idReunion
 * @property string|null $nombre
 * @property Carbon|null $fecha
 * @property int|null $encargado
 * @property int|null $idProyectosoftware
 * @property int|null $idProyectoparticipantes
 * 
 * @property ProyectoSoftware|null $proyecto_software
 * @property ProyectoParticipante|null $proyecto_participante
 * @property Collection|ReunionParticipante[] $reunion_participantes
 *
 * @package App\Models
 */
class Reunion extends Model
{
	protected $table = 'reunion';
	protected $primaryKey = 'idReunion';
	public $timestamps = false;

	protected $casts = [
		'fecha' => 'datetime',
		'encargado' => 'int',
		'idProyectosoftware' => 'int',
		'idProyectoparticipantes' => 'int'
	];

	protected $fillable = [
		'nombre',
		'fecha',
		'encargado',
		'idProyectosoftware',
		'idProyectoparticipantes'
	];

	public function proyecto_software()
	{
		return $this->belongsTo(ProyectoSoftware::class, 'idProyectosoftware');
	}

	public function proyecto_participante()
	{
		return $this->belongsTo(ProyectoParticipante::class, 'idProyectoparticipantes');
	}

	public function reunion_participantes()
	{
		return $this->hasMany(ReunionParticipante::class, 'idReunion');
	}
}
