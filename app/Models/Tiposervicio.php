<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Tiposervicio
 * 
 * @property int $idTipoServicio
 * @property string|null $nombre
 * 
 * @property Collection|Solicitudesordene[] $solicitudesordenes
 *
 * @package App\Models
 */
class Tiposervicio extends Model
{
	protected $table = 'tiposervicio';
	protected $primaryKey = 'idTipoServicio';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];

	public function solicitudesordenes()
	{
		return $this->hasMany(Solicitudesordene::class, 'idTipoServicio');
	}
}
