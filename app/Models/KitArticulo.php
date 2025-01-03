<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class KitArticulo
 * 
 * @property int $idkit_articulo
 * @property int|null $cantidad
 * @property int $idKit
 * @property int $idArticulos
 * 
 * @property Kit $kit
 * @property Articulo $articulo
 *
 * @package App\Models
 */
class KitArticulo extends Model
{
	protected $table = 'kit_articulo';
	protected $primaryKey = 'idkit_articulo';
	public $timestamps = false;

	protected $casts = [
		'cantidad' => 'int',
		'idKit' => 'int',
		'idArticulos' => 'int'
	];

	protected $fillable = [
		'cantidad',
		'idKit',
		'idArticulos'
	];

	public function kit()
	{
		return $this->belongsTo(Kit::class, 'idKit');
	}

	public function articulo()
	{
		return $this->belongsTo(Articulo::class, 'idArticulos');
	}
}
