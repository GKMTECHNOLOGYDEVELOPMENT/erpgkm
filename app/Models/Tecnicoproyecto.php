<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tecnicoproyecto
 * 
 * @property int $idTecnicoProyecto
 * @property int $idProyecto
 * 
 * @property Proyecto $proyecto
 *
 * @package App\Models
 */
class Tecnicoproyecto extends Model
{
	protected $table = 'tecnicoproyecto';
	protected $primaryKey = 'idTecnicoProyecto';
	public $timestamps = false;

	protected $casts = [
		'idProyecto' => 'int'
	];

	protected $fillable = [
		'idProyecto'
	];

	public function proyecto()
	{
		return $this->belongsTo(Proyecto::class, 'idProyecto');
	}
}
