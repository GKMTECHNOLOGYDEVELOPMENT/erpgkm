<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Kit
 * 
 * @property int $idKit
 * @property string|null $codigo
 * @property string|null $nombre
 * @property string|null $descripcion
 * @property float|null $precio_compra
 * @property float|null $precio
 * @property Carbon|null $fecha
 * 
 * @property Collection|DetalleCotizacion[] $detalle_cotizacions
 * @property Collection|Articulo[] $articulos
 *
 * @package App\Models
 */
class Kit extends Model
{
	protected $table = 'kit';
	protected $primaryKey = 'idKit';
	public $timestamps = false;

	protected $casts = [
		'precio_compra' => 'float',
		'precio' => 'float',
		'fecha' => 'datetime'
	];

	protected $fillable = [
		'codigo',
		'nombre',
		'descripcion',
		'precio_compra',
		'precio',
		'fecha'
	];

	public function detalle_cotizacions()
	{
		return $this->hasMany(DetalleCotizacion::class, 'idKit');
	}

	public function articulos()
	{
		return $this->belongsToMany(Articulo::class, 'kit_articulo', 'idKit', 'idArticulos')
					->withPivot('idkit_articulo', 'cantidad');
	}
}
