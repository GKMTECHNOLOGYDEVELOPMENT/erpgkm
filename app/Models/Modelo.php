<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;


/**
 * Class Modelo
 * 
 * @property int $idModelo
 * @property string|null $nombre
 * @property int $idMarca
 * @property int $idCategoria
 * 
 * @property Marca $marca
 * @property Categoria $categoria
 * @property Collection|Articulo[] $articulos
 *
 * @package App\Models
 */
class Modelo extends Model
{
	protected $table = 'modelo';
	protected $primaryKey = 'idModelo';
	public $timestamps = false;

	protected $casts = [
		'idMarca' => 'int',
		'idCategoria' => 'int'
	];

	protected $fillable = [
		'nombre',
		'idMarca',
		'idCategoria',
		'estado'
	];

	public function marca()
	{
		return $this->belongsTo(Marca::class, 'idMarca');
	}

	public function categoria()
	{
		return $this->belongsTo(Categoria::class, 'idCategoria', 'idCategoria');
	}
	

	public function articulos()
	{
		return $this->hasMany(Articulo::class, 'idModelo');
	}
}
