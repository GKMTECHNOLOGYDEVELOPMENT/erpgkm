<?php

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
 * @property int|null $monedaCompra
 * @property int|null $monedaVenta
 * 
 * @property Collection|DetalleCotizacion[] $detalle_cotizacions
 * @property Collection|Articulo[] $articulos
 * @property Moneda|null $moneda_compra
 * @property Moneda|null $moneda_venta
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
		'fecha' => 'datetime',
		'monedaCompra' => 'int',
		'monedaVenta' => 'int'
	];

	protected $fillable = [
		'codigo',
		'nombre',
		'descripcion',
		'precio_compra',
		'precio',
		'fecha',
		'monedaCompra',
		'monedaVenta',
		'sku',
		'stock_total',
		'stock_minimo'
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

	public function moneda_compra()
	{
		return $this->belongsTo(Moneda::class, 'monedaCompra', 'idMonedas');
	}

	public function moneda_venta()
	{
		return $this->belongsTo(Moneda::class, 'monedaVenta', 'idMonedas');
	}
}
