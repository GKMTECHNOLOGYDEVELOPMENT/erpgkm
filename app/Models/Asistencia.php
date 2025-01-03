<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Asistencia
 * 
 * @property int $idAsistencias
 * @property Carbon|null $fecha
 * @property Carbon|null $fechaEntrada
 * @property Carbon|null $fechaInicioBreak
 * @property Carbon|null $fechaFinBreak
 * @property Carbon|null $fechaSalida
 * @property string|null $ubicacion
 * @property Carbon|null $ubicacionSalida
 * @property int $idUsuario
 * 
 * @property Usuario $usuario
 *
 * @package App\Models
 */
class Asistencia extends Model
{
	protected $table = 'asistencias';
	protected $primaryKey = 'idAsistencias';
	public $timestamps = false;

	protected $casts = [
		'fecha' => 'datetime',
		'fechaEntrada' => 'datetime',
		'fechaInicioBreak' => 'datetime',
		'fechaFinBreak' => 'datetime',
		'fechaSalida' => 'datetime',
		'ubicacionSalida' => 'datetime',
		'idUsuario' => 'int'
	];

	protected $fillable = [
		'fecha',
		'fechaEntrada',
		'fechaInicioBreak',
		'fechaFinBreak',
		'fechaSalida',
		'ubicacion',
		'ubicacionSalida',
		'idUsuario'
	];

	public function usuario()
	{
		return $this->belongsTo(Usuario::class, 'idUsuario');
	}
}
