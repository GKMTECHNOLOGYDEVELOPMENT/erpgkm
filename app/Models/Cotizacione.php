<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cotizacione
 * 
 * @property int $idCotizaciones
 * @property string|null $codigo
 * @property Carbon|null $fecha_cotizacion
 * @property Carbon|null $hora_cotizacion
 * @property float|null $cotizacion_total
 * @property float|null $cotizacion_final
 * @property bool|null $estado
 * @property string|null $observacion
 * @property Carbon|null $fecha_levantamiento
 * @property string|null $serie
 * @property float|null $cotizacion_descuento
 * @property bool|null $alquiler
 * @property int $idCliente
 * @property int $idClienteGeneral
 * @property int $idSubsidiarios
 * @property int $idMonedas
 * @property int $idTickets
 * 
 * @property Cliente $cliente
 * @property Clientegeneral $clientegeneral
 * @property Subsidiario $subsidiario
 * @property Moneda $moneda
 * @property Ticket $ticket
 * @property Collection|DetalleCotizacion[] $detalle_cotizacions
 * @property Collection|Venta[] $ventas
 *
 * @package App\Models
 */
class Cotizacione extends Model
{
	protected $table = 'cotizaciones';
	protected $primaryKey = 'idCotizaciones';
	public $timestamps = false;

	protected $casts = [
		'fecha_cotizacion' => 'datetime',
		'hora_cotizacion' => 'datetime',
		'cotizacion_total' => 'float',
		'cotizacion_final' => 'float',
		'estado' => 'bool',
		'fecha_levantamiento' => 'datetime',
		'cotizacion_descuento' => 'float',
		'alquiler' => 'bool',
		'idCliente' => 'int',
		'idClienteGeneral' => 'int',
		'idSubsidiarios' => 'int',
		'idMonedas' => 'int',
		'idTickets' => 'int'
	];

	protected $fillable = [
		'codigo',
		'fecha_cotizacion',
		'hora_cotizacion',
		'cotizacion_total',
		'cotizacion_final',
		'estado',
		'observacion',
		'fecha_levantamiento',
		'serie',
		'cotizacion_descuento',
		'alquiler',
		'idCliente',
		'idClienteGeneral',
		'idSubsidiarios',
		'idMonedas',
		'idTickets'
	];

	public function cliente()
	{
		return $this->belongsTo(Cliente::class, 'idCliente');
	}

	public function clientegeneral()
	{
		return $this->belongsTo(Clientegeneral::class, 'idClienteGeneral');
	}

	public function subsidiario()
	{
		return $this->belongsTo(Subsidiario::class, 'idSubsidiarios');
	}

	public function moneda()
	{
		return $this->belongsTo(Moneda::class, 'idMonedas');
	}

	public function ticket()
	{
		return $this->belongsTo(Ticket::class, 'idTickets');
	}

	public function detalle_cotizacions()
	{
		return $this->hasMany(DetalleCotizacion::class, 'idCotizaciones');
	}

	public function ventas()
	{
		return $this->hasMany(Venta::class, 'idCotizaciones');
	}
}
