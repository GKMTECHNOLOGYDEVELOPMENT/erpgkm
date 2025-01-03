<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RolSoftware
 * 
 * @property int $idRolsoftware
 * @property string|null $nombre
 * 
 * @property Collection|ProyectoParticipante[] $proyecto_participantes
 *
 * @package App\Models
 */
class RolSoftware extends Model
{
	protected $table = 'rol_software';
	protected $primaryKey = 'idRolsoftware';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function proyecto_participantes()
	{
		return $this->hasMany(ProyectoParticipante::class, 'idRolsoftware');
	}
}
