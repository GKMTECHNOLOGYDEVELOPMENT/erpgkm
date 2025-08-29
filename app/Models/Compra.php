<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Compra
 * 
 * @property int $idCompra
 * @property string|null $serie
 * @property int|null $nro
 * @property Carbon|null $fechaEmision
 * @property Carbon|null $fechaVencimiento
 * @property varbinary|null $imagen
 * @property float|null $sujetoporcentaje
 * @property int|null $cantidad
 * @property float|null $gravada
 * @property float|null $igv
 * @property float|null $total
 * @property int $idMonedas
 * @property int $idDocumento
 * @property int $idImpuesto
 * @property int $idSujeto
 * @property int $idCondicionCompra
 * @property int $idTipoPago
 * 
 * @property Moneda $moneda
 * @property Documento $documento
 * @property Impuesto $impuesto
 * @property Sujeto $sujeto
 * @property Condicioncompra $condicioncompra
 * @property Tipopago $tipopago
 * @property Collection|Articulo[] $articulos
 * @property Collection|Proveedore[] $proveedores
 *
 * @package App\Models
 */
class Compra extends Model
{
	protected $table = 'compra';
	protected $primaryKey = 'idCompra';
	public $timestamps = false;

	protected $casts = [
		'nro' => 'int',
		'fechaEmision' => 'datetime',
		'fechaVencimiento' => 'datetime',
		'imagen' => 'varbinary',
		'sujetoporcentaje' => 'float',
		'cantidad' => 'int',
		'gravada' => 'float',
		'igv' => 'float',
		'total' => 'float',
		'idMonedas' => 'int',
		'idDocumento' => 'int',
		'idImpuesto' => 'int',
		'idSujeto' => 'int',
		'idCondicionCompra' => 'int',
		'idTipoPago' => 'int',
		'created_at' => 'datetime',   // <-- agregado
		'updated_at' => 'datetime'    // <-- agregado
	];

	protected $fillable = [
		'serie',
		'nro',
		'fechaEmision',
		'fechaVencimiento',
		'imagen',
		'sujetoporcentaje',
		'cantidad',
		'gravada',
		'igv',
		'total',
		'idMonedas',
		'idDocumento',
		'idImpuesto',
		'idSujeto',
		'idCondicionCompra',
		'idTipoPago',
		'proveedor_id'
	];

	public function moneda()
	{
		return $this->belongsTo(Moneda::class, 'idMonedas');
	}

	public function documento()
	{
		return $this->belongsTo(Documento::class, 'idDocumento');
	}

	public function impuesto()
	{
		return $this->belongsTo(Impuesto::class, 'idImpuesto');
	}

	public function sujeto()
	{
		return $this->belongsTo(Sujeto::class, 'idSujeto');
	}

	public function condicioncompra()
	{
		return $this->belongsTo(Condicioncompra::class, 'idCondicionCompra');
	}

	public function tipopago()
	{
		return $this->belongsTo(Tipopago::class, 'idTipoPago');
	}

	public function articulos()
	{
		return $this->belongsToMany(Articulo::class, 'compraarticulo', 'idCompra', 'idArticulos')
			->withPivot('idCompraArticulo', 'serie', 'nro');
	}

	public function proveedor()
	{
		return $this->belongsTo(\App\Models\Proveedore::class, 'proveedor_id', 'idProveedor');
	}
}
