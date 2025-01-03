<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EstadoOt
 * 
 * @property int $idEstadoots
 * @property string|null $descripcion
 * @property string|null $color
 * 
 * @property Collection|Ticket[] $tickets
 *
 * @package App\Models
 */
class EstadoOt extends Model
{
	protected $table = 'estado_ots';
	protected $primaryKey = 'idEstadoots';
	public $timestamps = false;

	protected $fillable = [
		'descripcion',
		'color'
	];

	public function tickets()
	{
		return $this->hasMany(Ticket::class, 'idEstadoots');
	}
}
