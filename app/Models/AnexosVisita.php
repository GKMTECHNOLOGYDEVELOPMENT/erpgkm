<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AnexosVisita
 * 
 * @property int $idAnexoVisitas
 * @property varbinary|null $foto
 * @property string|null $descripcion
 * @property int|null $idTipovisita
 * @property int|null $idVisitas
 * 
 * @property TipoVisitum|null $tipo_visitum
 * @property Visita|null $visita
 *
 * @package App\Models
 */
class AnexosVisita extends Model
{
	protected $table = 'anexos_visitas';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'idAnexoVisitas' => 'int',

		'idTipovisita' => 'int',
		'idVisitas' => 'int'
	];

	protected $fillable = [
		'idAnexoVisitas',
		'foto',
		'descripcion',
		'idTipovisita',
		'idVisitas'
	];

	public function tipo_visitum()
	{
		return $this->belongsTo(TipoVisitum::class, 'idTipovisita');
	}

	public function visita()
	{
		return $this->belongsTo(Visita::class, 'idVisitas');
	}
}
