<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ReunionParticipante
 * 
 * @property int $idReunionparticipantes
 * @property int|null $idReunion
 * @property int|null $idProyectosoftware
 * 
 * @property Reunion|null $reunion
 * @property ProyectoSoftware|null $proyecto_software
 *
 * @package App\Models
 */
class ReunionParticipante extends Model
{
	protected $table = 'reunion_participantes';
	protected $primaryKey = 'idReunionparticipantes';
	public $timestamps = false;

	protected $casts = [
		'idReunion' => 'int',
		'idProyectosoftware' => 'int'
	];

	protected $fillable = [
		'idReunion',
		'idProyectosoftware'
	];

	public function reunion()
	{
		return $this->belongsTo(Reunion::class, 'idReunion');
	}

	public function proyecto_software()
	{
		return $this->belongsTo(ProyectoSoftware::class, 'idProyectosoftware');
	}
}
