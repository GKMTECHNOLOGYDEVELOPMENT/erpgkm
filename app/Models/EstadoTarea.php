<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EstadoTarea
 * 
 * @property int $idEstadotarea
 * @property string|null $nombre
 * 
 * @property Collection|TareasParticipante[] $tareas_participantes
 *
 * @package App\Models
 */
class EstadoTarea extends Model
{
	protected $table = 'estado_tarea';
	protected $primaryKey = 'idEstadotarea';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function tareas_participantes()
	{
		return $this->hasMany(TareasParticipante::class, 'idEstadotarea');
	}
}
