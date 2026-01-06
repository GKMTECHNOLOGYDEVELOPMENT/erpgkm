<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Ordenesarticulo
 * 
 * @property int $idOrdenesArticulos
 * @property int|null $cantidad
 * @property bool|null $estado
 * @property string|null $observacion
 * @property varbinary|null $fotoRepuesto
 * @property Carbon|null $fechaUsado
 * @property Carbon|null $fechaSinUsar
 * @property int $idSolicitudesOrdenes
 * @property int $idArticulos
 * @property int $idUbicacion
 * 
 * @property Solicitudesordene $solicitudesordene
 * @property Articulo $articulo
 * @property Ubicacion $ubicacion
 *
 * @package App\Models
 */
class Ordenesarticulo extends Model
{
	protected $table = 'ordenesarticulos';
	protected $primaryKey = 'idOrdenesArticulos';
	public $timestamps = false;

	protected $casts = [
		'cantidad' => 'int',
		'estado' => 'bool',
		'fotoRepuesto' => 'varbinary',
		'fechaUsado' => 'datetime',
		'fechaSinUsar' => 'datetime',
		'idSolicitudesOrdenes' => 'int',
		'idArticulos' => 'int',
		'idUbicacion' => 'int'
	];

	protected $fillable = [
		'cantidad',
		'estado',
		'observacion',
		'fotoRepuesto',
		'fechaUsado',
		'fechaSinUsar',
		'idSolicitudesOrdenes',
		'foto_articulo_usado',
		'foto_articulo_no_usado',
		'idArticulos',
		'idUbicacion',
		'id_area_destino',
		'id_usuario_destino'
	];

	public function solicitudesordene()
	{
		return $this->belongsTo(Solicitudesordene::class, 'idSolicitudesOrdenes');
	}

	public function articulo()
	{
		return $this->belongsTo(Articulo::class, 'idArticulos');
	}

	public function ubicacion()
	{
		return $this->belongsTo(Ubicacion::class, 'idUbicacion');
	}
}
