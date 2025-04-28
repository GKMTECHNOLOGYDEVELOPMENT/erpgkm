<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Marca
 * 
 * @property int $idMarca
 * @property string|null $nombre
 * 
 * @property Collection|Modelo[] $modelos
 *
 * @package App\Models
 */
class Marca extends Model
{
	protected $table = 'marca';
	protected $primaryKey = 'idMarca';
	public $timestamps = false;

	protected $fillable = [
		'nombre',
		'foto',
		'estado'
	];

	public function modelos()
	{
		return $this->hasMany(Modelo::class, 'idMarca');
	}
	public function clientesGenerales()
	{
		return $this->belongsToMany(ClienteGeneral::class, 'marca_clientegeneral', 'idMarca', 'idClienteGeneral');
	}

	public function articulos()
	{
		return $this->hasMany(Articulo::class, 'idMarca', 'idMarca');
	}
}
