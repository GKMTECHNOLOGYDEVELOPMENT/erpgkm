<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Solicitudesordene
 * 
 * @property int $idSolicitudesOrdenes
 * @property Carbon|null $fechaCreacion
 * @property string|null $estado
 * @property Carbon|null $fechaEntrega
 * @property string|null $numeroTicket
 * @property string|null $codigo
 * @property int $idTipoServicio
 * @property int $idTecnico
 * @property int $idUsuario
 * 
 * @property Tiposervicio $tiposervicio
 * @property Usuario $usuario
 * @property Collection|Firma[] $firmas
 * @property Collection|Ordenesarticulo[] $ordenesarticulos
 *
 * @package App\Models
 */
class Solicitudesordene extends Model
{
	protected $table = 'solicitudesordenes';
	protected $primaryKey = 'idSolicitudesOrdenes';
	public $timestamps = false;

	protected $casts = [
		'fechaCreacion' => 'datetime',
		'fechaEntrega' => 'datetime',
		'idTipoServicio' => 'int',
		'idTecnico' => 'int',
		'idUsuario' => 'int'
	];

	protected $fillable = [
		'fechaCreacion',
		'estado',
		'fechaEntrega',
		'numeroTicket',
		'codigo',
		'idTipoServicio',
		'idTecnico',
		'idUsuario'
	];

	public function tiposervicio()
	{
		return $this->belongsTo(Tiposervicio::class, 'idTipoServicio');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'idUsuario');
	}

	public function firmas()
	{
		return $this->hasMany(Firma::class, 'idSolicitudesOrdenes');
	}

	public function ordenesarticulos()
	{
		return $this->hasMany(Ordenesarticulo::class, 'idSolicitudesOrdenes');
	}
}
