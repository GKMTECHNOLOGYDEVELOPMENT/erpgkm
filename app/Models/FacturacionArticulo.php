<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class FacturacionArticulo
 * 
 * @property int $idFacturacionArticulo
 * @property int $idFacturacion
 * 
 * @property Facturacion $facturacion
 *
 * @package App\Models
 */
class FacturacionArticulo extends Model
{
	protected $table = 'facturacion_articulo';
	protected $primaryKey = 'idFacturacionArticulo';
	public $timestamps = false;

	protected $casts = [
		'idFacturacion' => 'int'
	];

	protected $fillable = [
		'idFacturacion'
	];

	public function facturacion()
	{
		return $this->belongsTo(Facturacion::class, 'idFacturacion');
	}
}
