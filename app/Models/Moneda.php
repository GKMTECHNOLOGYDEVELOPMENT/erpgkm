<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Moneda
 * 
 * @property int $idMonedas
 * @property string|null $nombre
 * 
 * @property Collection|Compra[] $compras
 * @property Collection|Cotizacione[] $cotizaciones
 * @property Collection|Facturacion[] $facturacions
 *
 * @package App\Models
 */
class Moneda extends Model
{
	protected $table = 'monedas';
	protected $primaryKey = 'idMonedas';
	public $timestamps = false;

	protected $fillable = [
		'nombre',
		'simbolo'
	];

	public function compras()
	{
		return $this->hasMany(Compra::class, 'idMonedas');
	}

	public function cotizaciones()
	{
		return $this->hasMany(Cotizacione::class, 'idMonedas');
	}

	public function facturacions()
	{
		return $this->hasMany(Facturacion::class, 'idMonedas');
	}

	 public function detallesCompra()
    {
        return $this->hasMany(SolicitudCompraDetalle::class, 'idMonedas');
    }
}
