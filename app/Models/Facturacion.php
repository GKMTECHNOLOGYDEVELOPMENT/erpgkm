<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Facturacion
 * 
 * @property int $idFacturacion
 * @property Carbon|null $fecha_facturacion
 * @property int|null $nro_orden
 * @property bool|null $pre_anticipo
 * @property float|null $venta
 * @property string|null $codigo_venta
 * @property float|null $gravada
 * @property float|null $total_igv
 * @property float|null $total_final
 * @property int $idCredito
 * @property int $idUsuario
 * @property int $idTipoOperacion
 * @property int $idMonedas
 * @property int $idTipoIgv
 * @property int $idSujeto
 * 
 * @property Credito $credito
 * @property Usuario $usuario
 * @property TipoOperacion $tipo_operacion
 * @property Moneda $moneda
 * @property Tipoigv $tipoigv
 * @property Sujeto $sujeto
 * @property Collection|FacturacionArticulo[] $facturacion_articulos
 *
 * @package App\Models
 */
class Facturacion extends Model
{
	protected $table = 'facturacion';
	protected $primaryKey = 'idFacturacion';
	public $timestamps = false;

	protected $casts = [
		'fecha_facturacion' => 'datetime',
		'nro_orden' => 'int',
		'pre_anticipo' => 'bool',
		'venta' => 'float',
		'gravada' => 'float',
		'total_igv' => 'float',
		'total_final' => 'float',
		'idCredito' => 'int',
		'idUsuario' => 'int',
		'idTipoOperacion' => 'int',
		'idMonedas' => 'int',
		'idTipoIgv' => 'int',
		'idSujeto' => 'int'
	];

	protected $fillable = [
		'fecha_facturacion',
		'nro_orden',
		'pre_anticipo',
		'venta',
		'codigo_venta',
		'gravada',
		'total_igv',
		'total_final',
		'idCredito',
		'idUsuario',
		'idTipoOperacion',
		'idMonedas',
		'idTipoIgv',
		'idSujeto'
	];

	public function credito()
	{
		return $this->belongsTo(Credito::class, 'idCredito');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'idUsuario');
	}

	public function tipo_operacion()
	{
		return $this->belongsTo(TipoOperacion::class, 'idTipoOperacion');
	}

	public function moneda()
	{
		return $this->belongsTo(Moneda::class, 'idMonedas');
	}

	public function tipoigv()
	{
		return $this->belongsTo(Tipoigv::class, 'idTipoIgv');
	}

	public function sujeto()
	{
		return $this->belongsTo(Sujeto::class, 'idSujeto');
	}

	public function facturacion_articulos()
	{
		return $this->hasMany(FacturacionArticulo::class, 'idFacturacion');
	}
}
