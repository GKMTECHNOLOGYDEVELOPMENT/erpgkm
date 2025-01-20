<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Categorium
 * 
 * @property int $idCategoria
 * @property string|null $nombre
 * @property bool|null $estado
 * 
 * @property Collection|Equipo[] $equipos
 * @property Collection|Modelo[] $modelos
 *
 * @package App\Models
 */
class Categoria extends Model
{
	protected $table = 'categoria';
	protected $primaryKey = 'idCategoria';
	public $timestamps = false;

	protected $casts = [
		'estado' => 'bool'
	];

	protected $fillable = [
		'nombre',
		'estado'
	];

	public function equipos()
	{
		return $this->hasMany(Equipo::class, 'idCategoria');
	}

	public function modelos()
	{
		return $this->hasMany(Modelo::class, 'idCategoria');
	}
}
