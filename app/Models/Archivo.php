<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Archivo
 * 
 * @property int $idArchivos
 * @property string|null $nombreArchivo
 * @property string|null $tamanioArchivo
 * @property string|null $tipoArchivo
 * @property string|null $urlArchivo
 * @property Carbon|null $fechaSubida
 * @property int $idMensaje
 * 
 * @property Mensaje $mensaje
 *
 * @package App\Models
 */
class Archivo extends Model
{
	protected $table = 'archivos';
	protected $primaryKey = 'idArchivos';
	public $timestamps = false;

	protected $casts = [
		'fechaSubida' => 'datetime',
		'idMensaje' => 'int'
	];

	protected $fillable = [
		'nombreArchivo',
		'tamanioArchivo',
		'tipoArchivo',
		'urlArchivo',
		'fechaSubida',
		'idMensaje'
	];

	public function mensaje()
	{
		return $this->belongsTo(Mensaje::class, 'idMensaje');
	}
}
