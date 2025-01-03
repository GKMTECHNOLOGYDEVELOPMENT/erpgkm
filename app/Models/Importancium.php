<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Importancium
 * 
 * @property int $idImportancia
 * @property string|null $nombre
 * 
 * @property Collection|ProyectoSoftware[] $proyecto_software
 * @property Collection|Tarea[] $tareas
 *
 * @package App\Models
 */
class Importancium extends Model
{
	protected $table = 'importancia';
	protected $primaryKey = 'idImportancia';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function proyecto_software()
	{
		return $this->hasMany(ProyectoSoftware::class, 'idImportancia');
	}

	public function tareas()
	{
		return $this->hasMany(Tarea::class, 'idImportancia');
	}
}
