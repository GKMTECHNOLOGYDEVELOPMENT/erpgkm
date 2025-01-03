<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Movimientoarticulo
 * 
 * @property int $idMovimientoArticulo
 * @property Carbon|null $fecha_movimiento
 * @property varbinary|null $imagen
 * @property int|null $cantidad
 * @property int $idTipoMovimiento
 * @property int $idArticulos
 * @property int $idUbicacion
 * @property int $idContacto
 * 
 * @property Tipomovimiento $tipomovimiento
 * @property Articulo $articulo
 * @property Ubicacion $ubicacion
 * @property Contacto $contacto
 *
 * @package App\Models
 */
class Movimientoarticulo extends Model
{
	protected $table = 'movimientoarticulo';
	protected $primaryKey = 'idMovimientoArticulo';
	public $timestamps = false;

	protected $casts = [
		'fecha_movimiento' => 'datetime',
		'imagen' => 'varbinary',
		'cantidad' => 'int',
		'idTipoMovimiento' => 'int',
		'idArticulos' => 'int',
		'idUbicacion' => 'int',
		'idContacto' => 'int'
	];

	protected $fillable = [
		'fecha_movimiento',
		'imagen',
		'cantidad',
		'idTipoMovimiento',
		'idArticulos',
		'idUbicacion',
		'idContacto'
	];

	public function tipomovimiento()
	{
		return $this->belongsTo(Tipomovimiento::class, 'idTipoMovimiento');
	}

	public function articulo()
	{
		return $this->belongsTo(Articulo::class, 'idArticulos');
	}

	public function ubicacion()
	{
		return $this->belongsTo(Ubicacion::class, 'idUbicacion');
	}

	public function contacto()
	{
		return $this->belongsTo(Contacto::class, 'idContacto');
	}
}
