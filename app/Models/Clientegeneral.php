<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Clientegeneral
 * 
 * @property int $idClienteGeneral
 * @property string|null $descripcion
 * @property bool|null $estado
 * 
 * @property Collection|Cotizacione[] $cotizaciones
 * @property Collection|Ticket[] $tickets
 *
 * @package App\Models
 */
class Clientegeneral extends Model
{
	protected $table = 'clientegeneral';
	protected $primaryKey = 'idClienteGeneral';
	public $timestamps = false;

	protected $casts = [
		'estado' => 'bool',
	];

	protected $fillable = [
		'descripcion',
		'estado',
		'foto'
	];

	public function cotizaciones()
	{
		return $this->hasMany(Cotizacione::class, 'idClienteGeneral');
	}

	public function tickets()
	{
		return $this->hasMany(Ticket::class, 'idClienteGeneral');
	}
	// En el modelo ClienteGeneral (App\Models\ClienteGeneral)
	public function clientes()
	{
		return $this->hasMany(Cliente::class, 'idClienteGeneral', 'idClienteGeneral');
	}
	public function marcas()
	{
		return $this->belongsToMany(Marca::class, 'marca_clientegeneral', 'idClienteGeneral', 'idMarca');
	}
}
