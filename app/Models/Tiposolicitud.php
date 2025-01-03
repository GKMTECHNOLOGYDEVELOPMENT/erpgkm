<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tiposolicitud
 * 
 * @property int $idTipoSolicitud
 * @property string|null $nombre
 * 
 * @property Collection|Solicitud[] $solicituds
 *
 * @package App\Models
 */
class Tiposolicitud extends Model
{
	protected $table = 'tiposolicitud';
	protected $primaryKey = 'idTipoSolicitud';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function solicituds()
	{
		return $this->hasMany(Solicitud::class, 'idTipoSolicitud');
	}
}
