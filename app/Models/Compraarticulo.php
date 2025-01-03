<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Compraarticulo
 * 
 * @property int $idCompraArticulo
 * @property string|null $serie
 * @property int|null $nro
 * @property int $idCompra
 * @property int $idArticulos
 * 
 * @property Compra $compra
 * @property Articulo $articulo
 *
 * @package App\Models
 */
class Compraarticulo extends Model
{
	protected $table = 'compraarticulo';
	protected $primaryKey = 'idCompraArticulo';
	public $timestamps = false;

	protected $casts = [
		'nro' => 'int',
		'idCompra' => 'int',
		'idArticulos' => 'int'
	];

	protected $fillable = [
		'serie',
		'nro',
		'idCompra',
		'idArticulos'
	];

	public function compra()
	{
		return $this->belongsTo(Compra::class, 'idCompra');
	}

	public function articulo()
	{
		return $this->belongsTo(Articulo::class, 'idArticulos');
	}
}
