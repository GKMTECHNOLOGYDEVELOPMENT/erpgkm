<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Solicitud
 * 
 * @property int $idSolicitud
 * @property int|null $dias
 * @property string|null $codigo
 * @property string|null $estado
 * @property int $idTipoSolicitud
 * @property int $idTenico
 * @property int $idEncargado
 * 
 * @property Tiposolicitud $tiposolicitud
 * @property Usuario $usuario
 * @property Collection|Articulosprestado[] $articulosprestados
 * @property Collection|Firma[] $firmas
 * @property Collection|Prestamosherramienta[] $prestamosherramientas
 *
 * @package App\Models
 */
class Solicitud extends Model
{
	protected $table = 'solicitud';
	protected $primaryKey = 'idSolicitud';
	public $timestamps = false;

	protected $casts = [
		'dias' => 'int',
		'idTipoSolicitud' => 'int',
		'idTenico' => 'int',
		'idEncargado' => 'int'
	];

	protected $fillable = [
		'dias',
		'codigo',
		'estado',
		'idTipoSolicitud',
		'idTenico',
		'idEncargado'
	];

	public function tiposolicitud()
	{
		return $this->belongsTo(Tiposolicitud::class, 'idTipoSolicitud');
	}

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'idEncargado');
	}

	public function articulosprestados()
	{
		return $this->hasMany(Articulosprestado::class, 'idSolicitud');
	}

	public function firmas()
	{
		return $this->hasMany(Firma::class, 'idSolicitud');
	}

	public function prestamosherramientas()
	{
		return $this->hasMany(Prestamosherramienta::class, 'idSolicitud');
	}
}
