<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Credito
 * 
 * @property int $idCredito
 * @property float|null $credito_dias
 * @property float|null $credito_porcentaje
 * @property string|null $credito_descripcion
 * @property int $idTipoVenta
 * 
 * @property TipoVentum $tipo_ventum
 * @property Collection|DetalleCotizacion[] $detalle_cotizacions
 * @property Collection|Facturacion[] $facturacions
 *
 * @package App\Models
 */
class Credito extends Model
{
	protected $table = 'credito';
	protected $primaryKey = 'idCredito';
	public $timestamps = false;

	protected $casts = [
		'credito_dias' => 'float',
		'credito_porcentaje' => 'float',
		'idTipoVenta' => 'int'
	];

	protected $fillable = [
		'credito_dias',
		'credito_porcentaje',
		'credito_descripcion',
		'idTipoVenta'
	];

	public function tipo_ventum()
	{
		return $this->belongsTo(TipoVentum::class, 'idTipoVenta');
	}

	public function detalle_cotizacions()
	{
		return $this->hasMany(DetalleCotizacion::class, 'idCredito');
	}

	public function facturacions()
	{
		return $this->hasMany(Facturacion::class, 'idCredito');
	}
}
